/**
 * Second-pass SA4 migrator for leftover pages + ajax/print cleanup.
 */
const fs = require("fs");
const path = require("path");
const { pathToFileURL } = require("url");

// reuse helpers by requiring first script logic inline-ish
const VIEWS = path.join("c:", "xampp", "htdocs", "labasonsandbox", "application", "modules", "master", "views");

const PASS2_PAGES = [
  "addpaymentcustomer_edit.php",
  "leaking_soa_statement.php",
  "main-category.php",
  "main-category-add.php",
  "main-category-edit.php",
  "main-category-view.php",
  "paymentmonthlycustomer_edit.php",
  "paymentmonthlycustomer_invoice.php",
  "payrols_edit.php",
  "practise.php",
  "practise_add.php",
  "practise_edit.php",
  "statementofaccount_search.php",
  "web_settings-view.php",
  "addexpenses_invoice.php",
  "order-invoice.php",
  "responsibilities-permissions.php",
];

// Load migrate functions from first script by eval of extracted parts — simpler: spawn require of rewritten exports.
const m1 = fs.readFileSync(path.join(__dirname, "sa4_migrate_views.js"), "utf8");
// Instead duplicate minimal chrome wrapper:

function titleFromFile(name) {
  let base = name.replace(/\.php$/i, "").replace(/[_-]+/g, " ");
  const words = base.split(/\s+/).filter(Boolean).map((w) => w.charAt(0).toUpperCase() + w.slice(1));
  return { icon: "fal fa-th-list", title: "Manage", subtitle: words.join(" ") || "Page" };
}

function extractBreadcrumbs(html) {
  const m = html.match(/<ol[^>]*class="[^"]*breadcrumb[^"]*"[^>]*>([\s\S]*?)<\/ol>/i);
  if (!m) return [["Home", "<?php echo ADMIN_URL; ?>"], ["Page", null]];
  const items = [];
  const lis = m[1].match(/<li[^>]*>([\s\S]*?)<\/li>/gi) || [];
  for (const li of lis) {
    const a = li.match(/<a[^>]+href=["']([^"']+)["'][^>]*>([\s\S]*?)<\/a>/i);
    if (a) items.push([a[2].replace(/<[^>]+>/g, "").trim() || "Page", a[1]]);
    else {
      const label = li.replace(/<[^>]+>/g, "").trim();
      if (label) items.push([label, null]);
    }
  }
  return items.length ? items : [["Home", "<?php echo ADMIN_URL; ?>"], ["Page", null]];
}

function breadcrumbHtml(items) {
  const parts = ['\t<ol class="breadcrumb page-breadcrumb">'];
  items.forEach(([label, href], i) => {
    const active = i === items.length - 1;
    if (href && !active) parts.push(`\t\t<li class="breadcrumb-item"><a href="${href}">${label}</a></li>`);
    else parts.push(`\t\t<li class="breadcrumb-item active">${label}</li>`);
  });
  parts.push('\t\t<li class="position-absolute pos-top pos-right d-none d-sm-block"><span class="js-get-date"></span></li>');
  parts.push("\t</ol>");
  return parts.join("\n");
}

function modernize(s) {
  return s
    .replace(/label label-success/g, "badge badge-success")
    .replace(/label label-danger/g, "badge badge-danger")
    .replace(/label label-warning/g, "badge badge-warning")
    .replace(/label label-info/g, "badge badge-info")
    .replace(/btn-default/g, "btn-secondary")
    .replace(/\bcol-xs-(\d+)\b/g, "col-$1")
    .replace(/\bfa fa-/g, "fal fa-");
}

function migrateChrome(fileName, html) {
  const { icon, title, subtitle } = titleFromFile(fileName);
  const crumbs = extractBreadcrumbs(html);
  let body = html;
  const mainIdx = body.search(/<!--\s*MAIN PANEL\s*-->|<div id="main"/i);
  if (/<!DOCTYPE|<html/i.test(body) && mainIdx >= 0) body = body.slice(mainIdx);

  let content = body;
  const cm = body.match(/<div id="content"[^>]*>([\s\S]*?)<\/div>\s*<!--\s*END MAIN CONTENT/i);
  if (cm) {
    content = cm[1];
    const m2 = content.match(/(<section id="widget-grid"[\s\S]*)/i);
    if (m2) content = m2[1];
  } else {
    // Ace / non-standard: take from first form or row
    const m = body.match(/(<(?:form|div class="row"|section)[\s\S]+)/i);
    if (m) content = m[1].split(/<\?php\s+include\(['"]footer\.php['"]\)/)[0];
  }

  content = modernize(content);
  content = content
    .replace(/<div class="jarviswidget[^"]*"[^>]*>/gi, '<div class="panel">')
    .replace(/<div class="jarviswidget-editbox">[\s\S]*?<\/div>/gi, "")
    .replace(/<div class="widget-body[^"]*"[^>]*>/gi, "");

  if (!/class="panel"/.test(content) && !/panel-hdr/.test(content)) {
    content = `
	<div class="row">
		<div class="col-xl-12">
			<div class="panel">
				<div class="panel-hdr"><h2>${subtitle}</h2>
					<div class="panel-toolbar">
						<button class="btn btn-panel" data-action="panel-collapse" data-toggle="tooltip" data-offset="0,10" data-original-title="Collapse"></button>
						<button class="btn btn-panel" data-action="panel-fullscreen" data-toggle="tooltip" data-offset="0,10" data-original-title="Fullscreen"></button>
					</div>
				</div>
				<div class="panel-container show"><div class="panel-content">
${content}
				</div></div>
			</div>
		</div>
	</div>`;
  }

  // keep non-boilerplate scripts
  const scripts = [];
  const re = /<script(?![^>]+src=)[^>]*>[\s\S]*?<\/script>/gi;
  let sm;
  while ((sm = re.exec(html))) {
    const block = sm[0];
    if (block.includes("ResponsiveDatatablesHelper") || block.includes("_gaq")) continue;
    if (block.includes("sDom") && block.includes("dt-toolbar")) continue;
    scripts.push(block);
  }

  let dt = "";
  if (/id=["']dt_basic["']/.test(content)) {
    const panelId = "panel-" + fileName.replace(/\.php$/i, "").toLowerCase().replace(/[^a-z0-9]+/g, "-");
    content =
      `<link rel="stylesheet" media="screen, print" href="<?php echo base_url(); ?>sa4/css/datagrid/datatables/datatables.bundle.css">\n` +
      content.replace('class="panel"', `id="${panelId}" class="panel"`);
    dt = `
<?php
	$sa4_loading_label = '${subtitle}';
	$sa4_dt_entity = '${subtitle.toLowerCase()}';
	$sa4_panel_id = '${panelId}';
?>
<?php include(__DIR__ . '/partials/sa4_dt_loading.php'); ?>
<?php include(__DIR__ . '/partials/sa4_dt_init.js.php'); ?>
`;
  }

  return `<main id="js-page-content" role="main" class="page-content">
${breadcrumbHtml(crumbs)}
	<div class="subheader">
		<h1 class="subheader-title">
			<i class="subheader-icon ${icon}"></i>
			${title} <span class="fw-300">${subtitle}</span>
		</h1>
	</div>
${content}
</main>
<?php include('footer.php'); ?>
</body>
</html>
${scripts.join("\n")}
${dt}
`;
}

function cleanAjax(html) {
  let s = modernize(html);
  s = s.replace(/<div class="jarviswidget[^"]*"[^>]*>/gi, "");
  // don't blindly remove closing divs; leave structure mostly intact
  s = s.replace(/data-widget-editbutton="false"/gi, "");
  return s;
}

function cleanPrint(html) {
  let s = html
    .replace(/smartadmin-production\.min\.css/g, "sa4/css/app.bundle.css")
    .replace(/smartadmin-production-plugins\.min\.css/g, "sa4/css/vendors.bundle.css")
    .replace(/href=["']css\/bootstrap\.min\.css["']/g, 'href="<?php echo base_url(); ?>sa4/css/vendors.bundle.css"');
  // if relative css without php, leave
  s = modernize(s);
  return s;
}

let stats = { pages: 0, ajax: 0, print: 0, skip: 0 };

for (const name of PASS2_PAGES) {
  const full = path.join(VIEWS, name);
  if (!fs.existsSync(full)) {
    console.log("missing", name);
    continue;
  }
  const html = fs.readFileSync(full, "utf8");
  if (html.includes("js-page-content")) {
    stats.skip++;
    continue;
  }
  fs.writeFileSync(full, migrateChrome(name, html), "utf8");
  stats.pages++;
  console.log("page ", name);
}

for (const name of fs.readdirSync(VIEWS)) {
  if (!name.endsWith(".php")) continue;
  const low = name.toLowerCase();
  const full = path.join(VIEWS, name);
  const html = fs.readFileSync(full, "utf8");
  if (html.includes("js-page-content")) continue;

  if (/ajax|_ajax| ajax/.test(low) || /_modal\.php$/.test(low)) {
    const neu = cleanAjax(html);
    if (neu !== html) {
      fs.writeFileSync(full, neu, "utf8");
      stats.ajax++;
      console.log("ajax ", name);
    }
    continue;
  }

  if (/print|invoice|printtopdf/.test(low) && !PASS2_PAGES.includes(name)) {
    const neu = cleanPrint(html);
    if (neu !== html) {
      fs.writeFileSync(full, neu, "utf8");
      stats.print++;
      console.log("print", name);
    }
  }
}

console.log("STATS2", stats);
