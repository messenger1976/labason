/**
 * Bulk SA4 hybrid migrator for labasonsandbox master views (Node.js).
 */
const fs = require("fs");
const path = require("path");

const VIEWS = path.join(
  "c:",
  "xampp",
  "htdocs",
  "labasonsandbox",
  "application",
  "modules",
  "master",
  "views"
);

const SKIP_NAMES = new Set([
  "addcustomer.php",
  "add_zone.php",
  "dashboard.php",
  "footer.php",
  "login.php",
  "forgot-password.php",
  "change-password.php",
  "change-username.php",
  "header.php",
  "include.php",
  "mobile_footer.php",
]);

const SKIP_PREFIXES = ["mobile_", "mobilenotifications"];
const SKIP_CONTAINS = ["05-aug", "04-aug", " Copy", "- Copy"];

function shouldSkip(name) {
  if (SKIP_NAMES.has(name)) return true;
  if (SKIP_PREFIXES.some((p) => name.startsWith(p))) return true;
  if (SKIP_CONTAINS.some((s) => name.includes(s))) return true;
  return false;
}

function isPrintish(name) {
  const n = name.toLowerCase();
  return (
    n.includes("print") ||
    n.endsWith("_printtopdf.php") ||
    n.endsWith("-print.php")
  );
}

function titleFromFile(name) {
  let base = name.replace(/\.php$/i, "").replace(/[_-]+/g, " ");
  const words = base.split(/\s+/).filter(Boolean).map((w) => w.charAt(0).toUpperCase() + w.slice(1));
  const nice = words.join(" ") || "Page";
  const low = name.toLowerCase();
  let icon = "fal fa-th-list";
  if (low.includes("employee") || low.includes("payrol")) icon = "fal fa-users";
  else if (low.includes("expense")) icon = "fal fa-money-bill";
  else if (low.includes("payment") || low.includes("ledger") || low.includes("journal")) icon = "fal fa-wallet";
  else if (low.includes("customer")) icon = "fal fa-user-friends";
  else if (low.includes("asset")) icon = "fal fa-boxes";
  else if (low.includes("share")) icon = "fal fa-chart-pie";
  else if (/(report|aging|trial|statement|arrears)/.test(low)) icon = "fal fa-chart-bar";
  else if (low.includes("setting") || low.includes("config")) icon = "fal fa-cog";
  else if (low.includes("meter") || low.includes("reading")) icon = "fal fa-tachometer-alt";
  else if (low.includes("classif")) icon = "fal fa-tags";
  return { icon, title: "Manage", subtitle: nice };
}

function extractBreadcrumbs(html) {
  const m = html.match(/<ol[^>]*class="[^"]*breadcrumb[^"]*"[^>]*>([\s\S]*?)<\/ol>/i);
  if (!m) return [["Home", "<?php echo ADMIN_URL; ?>"], ["List", null]];
  const items = [];
  const lis = m[1].match(/<li[^>]*>([\s\S]*?)<\/li>/gi) || [];
  for (const li of lis) {
    const a = li.match(/<a[^>]+href=["']([^"']+)["'][^>]*>([\s\S]*?)<\/a>/i);
    if (a) {
      const label = a[2].replace(/<[^>]+>/g, "").trim() || "Page";
      items.push([label, a[1]]);
    } else {
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
    if (href && !active) {
      parts.push(`\t\t<li class="breadcrumb-item"><a href="${href}">${label}</a></li>`);
    } else {
      parts.push(`\t\t<li class="breadcrumb-item active">${label}</li>`);
    }
  });
  parts.push('\t\t<li class="position-absolute pos-top pos-right d-none d-sm-block"><span class="js-get-date"></span></li>');
  parts.push("\t</ol>");
  return parts.join("\n");
}

function extractTableBlock(html) {
  const m = html.match(/(<table[^>]*id=["']dt_basic["'][\s\S]*?<\/table>)/i);
  return m ? m[1] : null;
}

function extractMultiDeleteAction(html) {
  const m = html.match(/<form[^>]+action=["']([^"']*multi_delete[^"']*)["'][^>]*>/i);
  return m ? m[1] : null;
}

function extractAddHref(html) {
  const m = html.match(/<a[^>]+href=["']([^"']+)["'][^>]*>[\s\S]*?(?:Add|Create|New)[\s\S]*?<\/a>/i);
  return m ? m[1] : null;
}

function extractInnerContent(html) {
  let m = html.match(
    /(?:<!--\s*widget content\s*-->|<div class="widget-body[^"]*"[^>]*>)([\s\S]*?)(?:<!--\s*end widget content\s*-->|<\/div>\s*<!--\s*end widget div\s*-->)/i
  );
  if (m) return m[1].trim();

  m = html.match(/<div id="content"[^>]*>([\s\S]*?)<\/div>\s*<!--\s*END MAIN CONTENT/i);
  if (m) {
    const inner = m[1];
    const m2 = inner.match(/(<section id="widget-grid"[\s\S]*)/i);
    return (m2 ? m2[1] : inner).trim();
  }

  m = html.match(/(<(?:form|table|div class="row")[\s\S]+)/i);
  if (m) {
    return m[1].split(/<\?php\s+include\(['"]footer\.php['"]\)/)[0].trim();
  }
  return html;
}

function stripBoilerplateScripts(html) {
  let out = html.replace(/<script[^>]+src=["'][^"']*js\/plugin\/datatables[^"']*["'][^>]*>\s*<\/script>\s*/gi, "");
  out = out.replace(/<script[^>]+src=["'][^"']*datatable-responsive[^"']*["'][^>]*>\s*<\/script>\s*/gi, "");
  out = out.replace(/<!--\s*Your GOOGLE ANALYTICS CODE Below\s*-->[\s\S]*$/i, "");
  return out;
}

function keepCustomScripts(html) {
  const scripts = [];
  const re = /<script(?![^>]+src=)[^>]*>[\s\S]*?<\/script>/gi;
  let m;
  while ((m = re.exec(html))) {
    const block = m[0];
    if (block.includes("ResponsiveDatatablesHelper") || block.includes("_gaq")) continue;
    if (block.includes("sDom") && block.includes("dt-toolbar")) continue;
    if (block.includes("dataTable(") && block.includes("pageSetUp") && block.length > 1500) continue;
    scripts.push(block);
  }
  return scripts.join("\n");
}

function modernizeClasses(s) {
  return s
    .replace(/label label-success/g, "badge badge-success")
    .replace(/label label-danger/g, "badge badge-danger")
    .replace(/label label-warning/g, "badge badge-warning")
    .replace(/label label-info/g, "badge badge-info")
    .replace(/btn-default/g, "btn-secondary")
    .replace(/\bcol-xs-(\d+)\b/g, "col-$1")
    .replace(/\bfa fa-/g, "fal fa-")
    .replace(/arrowed-in arrowed-in-right/g, "")
    .replace(/\barrowed\b/g, "");
}

function migrateList(fileName, html) {
  const { icon, title, subtitle } = titleFromFile(fileName);
  const crumbs = extractBreadcrumbs(html);
  let table = extractTableBlock(html);
  if (!table) return migrateChrome(fileName, html, true);

  table = modernizeClasses(table);
  if (!/class="[^"]*table/.test(table)) {
    table = table.replace(
      /(<table[^>]*id=["']dt_basic["'])/i,
      '$1 class="table table-bordered table-hover table-striped w-100"'
    );
  }

  const action = extractMultiDeleteAction(html);
  const addHref = extractAddHref(html);
  const panelId = "panel-" + fileName.replace(/\.php$/i, "").toLowerCase().replace(/[^a-z0-9]+/g, "-");
  const entity = subtitle.toLowerCase();

  let addBtn = "";
  if (addHref) {
    addBtn = `
									<div class="col-sm-6 col-md-6 text-right">
										<a href="${addHref}" class="btn btn-success btn-sm waves-effect waves-themed">
											<i class="fal fa-plus mr-1"></i> Add
										</a>
									</div>`;
  }

  let deleteBtn = "";
  let formOpen = "";
  let formClose = "";
  if (action) {
    formOpen = `<form method="post" action="${action}" id="sa4-list-form">`;
    formClose = "</form>";
    deleteBtn = `
									<div class="col-sm-6 col-md-6">
										<button type="submit" class="btn btn-danger btn-sm waves-effect waves-themed" onclick="return deleteAllData();">
											<i class="fal fa-trash-alt mr-1"></i> Delete Selected
										</button>
									</div>`;
  }

  let toolbar = "";
  if (deleteBtn || addBtn) {
    toolbar = `
								<div class="row mb-3 align-items-end">
${deleteBtn}
${addBtn}
								</div>`;
  }

  return `<?php
	$sa4_page_icon = '${icon}';
	$sa4_page_title = '${title}';
	$sa4_page_subtitle = '${subtitle}';
	$sa4_loading_label = '${subtitle}';
	$sa4_dt_entity = '${entity}';
	$sa4_panel_id = '${panelId}';
?>
<main id="js-page-content" role="main" class="page-content">
${breadcrumbHtml(crumbs)}
<?php include(__DIR__ . '/partials/sa4_kpi_subheader.php'); ?>

	<?php if ($this->session->flashdata('msg_succ')) { ?>
	<div class="alert alert-success alert-dismissible fade show" role="alert">
		<button type="button" class="close" data-dismiss="alert" aria-label="Close">
			<span aria-hidden="true"><i class="fal fa-times"></i></span>
		</button>
		<strong>Success!</strong> <?php echo $this->session->flashdata('msg_succ'); ?>
	</div>
	<?php } ?>

	<section id="widget-grid" class="">
		<link rel="stylesheet" media="screen, print" href="<?php echo base_url(); ?>sa4/css/datagrid/datatables/datatables.bundle.css">
		<div class="row">
			<div class="col-xl-12">
				<div id="${panelId}" class="panel">
					<div class="panel-hdr">
						<h2>${subtitle} <span class="fw-300"><i>Listing</i></span></h2>
						<div class="panel-toolbar">
							<button class="btn btn-panel" data-action="panel-collapse" data-toggle="tooltip" data-offset="0,10" data-original-title="Collapse"></button>
							<button class="btn btn-panel" data-action="panel-fullscreen" data-toggle="tooltip" data-offset="0,10" data-original-title="Fullscreen"></button>
						</div>
					</div>
					<div class="panel-container show">
						<div class="panel-content">
							${formOpen}
${toolbar}
								${table}
							${formClose}
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>
</main>

<?php include(__DIR__ . '/partials/sa4_dt_loading.php'); ?>
<?php include('footer.php'); ?>
</body>
</html>
<?php include(__DIR__ . '/partials/sa4_dt_init.js.php'); ?>
`;
}

function migrateChrome(fileName, html, fullKpi) {
  const { icon, title, subtitle } = titleFromFile(fileName);
  const crumbs = extractBreadcrumbs(html);
  let body = html;
  const mainIdx = body.search(/<!--\s*MAIN PANEL\s*-->|<div id="main"/i);
  if (/<!DOCTYPE|<html/i.test(body) && mainIdx >= 0) {
    body = body.slice(mainIdx);
  }
  body = stripBoilerplateScripts(body);
  let content = extractInnerContent(body);
  content = modernizeClasses(content);
  content = content
    .replace(/<article[^>]*>/gi, '<div class="col-xl-12">')
    .replace(/<\/article>/gi, "</div>")
    .replace(/<div class="jarviswidget[^"]*"[^>]*>/gi, '<div class="panel">')
    .replace(/<div class="jarviswidget-editbox">[\s\S]*?<\/div>/gi, "")
    .replace(/<div class="widget-body[^"]*"[^>]*>/gi, "");

  // Convert first jarvis header if still present
  content = content.replace(/<header([^>]*)>([\s\S]*?)<\/header>/i, (all, _a, inner) => {
    const text = inner.replace(/<[^>]+>/g, " ").replace(/\s+/g, " ").trim().slice(0, 80);
    return `<div class="panel-hdr"><h2>${text || subtitle}</h2>
					<div class="panel-toolbar">
						<button class="btn btn-panel" data-action="panel-collapse" data-toggle="tooltip" data-offset="0,10" data-original-title="Collapse"></button>
						<button class="btn btn-panel" data-action="panel-fullscreen" data-toggle="tooltip" data-offset="0,10" data-original-title="Fullscreen"></button>
					</div>
				</div>
				<div class="panel-container show">
					<div class="panel-content">`;
  });

  if (!/class="panel"/.test(content) && !/panel-hdr/.test(content)) {
    content = `
	<div class="row">
		<div class="col-xl-12">
			<div class="panel">
				<div class="panel-hdr">
					<h2>${subtitle} <span class="fw-300"><i>Details</i></span></h2>
					<div class="panel-toolbar">
						<button class="btn btn-panel" data-action="panel-collapse" data-toggle="tooltip" data-offset="0,10" data-original-title="Collapse"></button>
						<button class="btn btn-panel" data-action="panel-fullscreen" data-toggle="tooltip" data-offset="0,10" data-original-title="Fullscreen"></button>
					</div>
				</div>
				<div class="panel-container show">
					<div class="panel-content">
${content}
					</div>
				</div>
			</div>
		</div>
	</div>`;
  }

  let kpi;
  if (fullKpi) {
    kpi = `<?php
	$sa4_page_icon = '${icon}';
	$sa4_page_title = '${title}';
	$sa4_page_subtitle = '${subtitle}';
?>
<?php include(__DIR__ . '/partials/sa4_kpi_subheader.php'); ?>
`;
  } else {
    kpi = `\t<div class="subheader">
		<h1 class="subheader-title">
			<i class="subheader-icon ${icon}"></i>
			${title} <span class="fw-300">${subtitle}</span>
		</h1>
	</div>
`;
  }

  let dtBits = "";
  if (/id=["']dt_basic["']/.test(content)) {
    const panelId = "panel-" + fileName.replace(/\.php$/i, "").toLowerCase().replace(/[^a-z0-9]+/g, "-");
    content =
      `<link rel="stylesheet" media="screen, print" href="<?php echo base_url(); ?>sa4/css/datagrid/datatables/datatables.bundle.css">\n` +
      content.replace('class="panel"', `id="${panelId}" class="panel"`);
    dtBits = `
<?php
	$sa4_loading_label = '${subtitle}';
	$sa4_dt_entity = '${subtitle.toLowerCase()}';
	$sa4_panel_id = '${panelId}';
?>
<?php include(__DIR__ . '/partials/sa4_dt_loading.php'); ?>
<?php include(__DIR__ . '/partials/sa4_dt_init.js.php'); ?>
`;
  }

  const scripts = keepCustomScripts(html);

  return `<main id="js-page-content" role="main" class="page-content">
${breadcrumbHtml(crumbs)}
${kpi}
${content}
</main>
<?php include('footer.php'); ?>
</body>
</html>
${scripts}
${dtBits}
`;
}

function migratePrint(fileName, html) {
  let html2 = html
    .replace(/smartadmin-production\.min\.css/g, "sa4/css/app.bundle.css")
    .replace(/smartadmin-production-plugins\.min\.css/g, "sa4/css/vendors.bundle.css");
  if (!html2.includes("js-page-content") && html2.includes('id="main"')) {
    html2 = migrateChrome(fileName, html2, false);
  }
  return html2;
}

function classify(name, html) {
  const low = name.toLowerCase();
  if (isPrintish(low)) return "print";
  if (html.includes("js-page-content")) return "skip";
  if ((/_ajax| ajax/.test(low)) && !html.includes('id="main"') && !html.includes("jarviswidget")) {
    return "ajax";
  }
  if (html.includes('id="dt_basic"') && (html.includes("jarviswidget") || html.includes('id="main"'))) {
    return "list";
  }
  if (html.includes('id="main"') || html.includes("jarviswidget")) {
    if (/(_add|_edit|_view|-add|-edit|-view|import|generate)/.test(low)) return "form";
    if (/(report|aging|arrears|trial|statement|invoice|qrcode|balance|monitor)/.test(low)) return "report";
    if (html.includes('id="dt_basic"')) return "list";
    return "form";
  }
  return "skip";
}

function main() {
  const stats = { list: 0, form: 0, report: 0, print: 0, ajax: 0, skip: 0, error: 0 };
  const files = fs.readdirSync(VIEWS).filter((f) => f.endsWith(".php"));

  for (const name of files.sort()) {
    if (shouldSkip(name)) {
      stats.skip++;
      continue;
    }
    const full = path.join(VIEWS, name);
    let html;
    try {
      html = fs.readFileSync(full, "utf8");
    } catch (e) {
      console.log("ERROR read", name, e.message);
      stats.error++;
      continue;
    }

    const kind = classify(name, html);
    try {
      if (kind === "skip") {
        stats.skip++;
        continue;
      }
      if (kind === "ajax") {
        const neu = modernizeClasses(html);
        if (neu !== html) {
          fs.writeFileSync(full, neu, "utf8");
          stats.ajax++;
          console.log("ajax  ", name);
        } else {
          stats.skip++;
        }
        continue;
      }

      let neu;
      if (kind === "list") neu = migrateList(name, html);
      else if (kind === "print") neu = migratePrint(name, html);
      else neu = migrateChrome(name, html, false);

      fs.writeFileSync(full, neu, "utf8");
      stats[kind]++;
      console.log(kind.padEnd(6), name);
    } catch (e) {
      console.log("ERROR migrate", name, e.message);
      stats.error++;
    }
  }

  console.log("STATS", stats);
}

main();
