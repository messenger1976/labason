/**
 * Wave C polish: fix broken Add toolbar links + BS3/Ace leftovers
 * for Settings / HR / master-data / accounting CRUD views.
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

const WAVE_C_PREFIXES = [
  "employee",
  "addemployee",
  "responsibilities",
  "job_title",
  "web_settings",
  "global_settings",
  "database_backup",
  "classification",
  "amountrate",
  "addexpenses",
  "addledger",
  "addjournalvoucher",
  "addaccountgroup",
  "addsubaccountgroup",
  "addassets",
  "addshareholder",
  "payrol",
  "practise",
  "main-category",
  "adminconfiguration",
  "qrcode",
  "change-password",
  "change-username",
  "transaction",
  "share_profit",
  "profit_",
  "feesplaning",
];

const SKIP_RE = /(ajax|print|Copy|05-aug|04-aug|mobile_|mobilenotifications|backup\.php$)/i;

/** Correct Add / Create URLs for list toolbars (from waterbilling1 originals). */
const ADD_HREF_BY_LIST = {
  "employee-logins.php": "<?php echo ADMIN_URL?>employee_logins/add/",
  "addemployee.php": "<?php echo ADMIN_URL?>addemployee/add/",
  "responsibilities.php": "<?php echo ADMIN_URL?>responsibilities/add/",
  "job_title.php": "<?php echo ADMIN_URL?>job_title/add/",
  "classification.php": "<?php echo ADMIN_URL?>classification/add/",
  "classification_category.php": "<?php echo ADMIN_URL?>classification_category/add/",
  "amountrate.php": "<?php echo ADMIN_URL?>amountrate/add/",
  "addexpenses.php": "<?php echo ADMIN_URL?>addexpenses/add/",
  "addexpensestype.php": "<?php echo ADMIN_URL?>addexpensestype/add/",
  "addledger.php": "<?php echo ADMIN_URL?>addledger/add/",
  "addjournalvoucher.php": "<?php echo ADMIN_URL?>addjournalvoucher/add/",
  "addaccountgroup.php": "<?php echo ADMIN_URL?>addaccountgroup/add/",
  "addsubaccountgroup.php": "<?php echo ADMIN_URL?>addsubaccountgroup/add/",
  "addassets.php": "<?php echo ADMIN_URL?>addassets/add/",
  "addshareholder.php": "<?php echo ADMIN_URL?>addshareholder/add/",
  "payrols.php": "<?php echo ADMIN_URL?>payrols/add/",
  "feesplaning.php": "<?php echo ADMIN_URL?>feesplaning/add/",
  "transaction.php": "<?php echo ADMIN_URL?>addexpenses/add/",
  "database_backup.php": "<?php echo base_url();?>index.php/master/database_backup/create",
  "share_profitloss_view.php": "<?php echo ADMIN_URL?>addshareholder/add/",
};

/** List pages that should NOT show an Add toolbar button. */
const REMOVE_ADD_BTN = new Set([
  "web_settings.php",
  "global_settings.php",
  "adminconfiguration.php",
  "practise.php",
  "main-category.php",
  "share_profitloss_view.php",
]);

/** Cancel / back targets for add|edit|view forms. */
const LIST_URL_BY_MODULE = {
  employee_logins: "<?php echo ADMIN_URL;?>employee_logins/",
  "employee-logins": "<?php echo ADMIN_URL;?>employee_logins/",
  addemployee: "<?php echo ADMIN_URL;?>addemployee/",
  responsibilities: "<?php echo ADMIN_URL;?>responsibilities/",
  job_title: "<?php echo ADMIN_URL;?>job_title/",
  web_settings: "<?php echo ADMIN_URL;?>web_settings/",
  global_settings: "<?php echo ADMIN_URL;?>global_settings/",
  classification: "<?php echo ADMIN_URL;?>classification/",
  classification_category: "<?php echo ADMIN_URL;?>classification_category/",
  amountrate: "<?php echo ADMIN_URL;?>amountrate/",
  addexpenses: "<?php echo ADMIN_URL;?>addexpenses/",
  addexpensestype: "<?php echo ADMIN_URL;?>addexpensestype/",
  addledger: "<?php echo ADMIN_URL;?>addledger/",
  addjournalvoucher: "<?php echo ADMIN_URL;?>addjournalvoucher/",
  addaccountgroup: "<?php echo ADMIN_URL;?>addaccountgroup/",
  addsubaccountgroup: "<?php echo ADMIN_URL;?>addsubaccountgroup/",
  addassets: "<?php echo ADMIN_URL;?>addassets/",
  addshareholder: "<?php echo ADMIN_URL;?>addshareholder/",
  payrols: "<?php echo ADMIN_URL;?>payrols/",
  feesplaning: "<?php echo ADMIN_URL;?>feesplaning/",
  adminconfiguration: "<?php echo ADMIN_URL;?>adminconfiguration/",
  practise: "<?php echo ADMIN_URL;?>practise/",
  "main-category": "<?php echo ADMIN_URL;?>main-category/",
  qrcode: "<?php echo ADMIN_URL;?>addcustomer/",
  database_backup: "<?php echo base_url();?>index.php/master/database_backup/",
  profit_addshareholder: "<?php echo ADMIN_URL;?>addshareholder/",
  share_profitloss: "<?php echo ADMIN_URL;?>addshareholder/",
};

function isWaveC(name) {
  if (SKIP_RE.test(name) && name !== "database_backup.php") return false;
  const low = name.toLowerCase();
  return WAVE_C_PREFIXES.some((p) => low.startsWith(p.toLowerCase()));
}

function moduleKey(fileName) {
  return fileName
    .replace(/\.php$/i, "")
    .replace(/_(add|edit|view|search|payrolssearch)$/i, "")
    .replace(/-(add|edit|view|permissions)$/i, "");
}

function modernize(html) {
  let s = html;
  s = s.replace(/label label-success/g, "badge badge-success");
  s = s.replace(/label label-danger/g, "badge badge-danger");
  s = s.replace(/label label-warning/g, "badge badge-warning");
  s = s.replace(/label label-info/g, "badge badge-info");
  s = s.replace(/btn-default/g, "btn-secondary");
  s = s.replace(/\bcol-xs-(\d+)\b/g, "col-$1");
  s = s.replace(/\bfa fa-/g, "fal fa-");
  s = s.replace(/\bpull-right\b/g, "float-right");
  s = s.replace(/\bpull-left\b/g, "float-left");
  s = s.replace(/\bhidden-xs\b/g, "d-none d-sm-inline");
  s = s.replace(/\bhidden-sm\b/g, "d-sm-none d-md-inline");
  s = s.replace(/\bvisible-md visible-lg\b/g, "d-none d-md-inline");
  s = s.replace(/\bvisible-xs visible-sm\b/g, "d-md-none");
  s = s.replace(/\bicon-remove\b/g, "fal fa-times");
  s = s.replace(/\bicon-ok\b/g, "fal fa-check");
  s = s.replace(/\bicon-user\b/g, "fal fa-user");
  s = s.replace(/\bicon-chevron-down\b/g, "fal fa-chevron-down");
  s = s.replace(/\bicon-caret-down\b/g, "fal fa-caret-down");
  s = s.replace(/alert alert-block alert-success/g, "alert alert-success alert-dismissible fade show");
  s = s.replace(/class="panel panel-default"/g, 'class="sa4-legacy-inner"');
  s = s.replace(/input-group-addon/g, "input-group-text");
  return s;
}

function fixAddToolbar(fileName, html) {
  if (REMOVE_ADD_BTN.has(fileName)) {
    return html.replace(
      /\s*<div class="col-sm-6 col-md-6 text-right">\s*<a href="[^"]*" class="btn btn-success[^"]*"[\s\S]*?<\/a>\s*<\/div>/i,
      ""
    );
  }
  const href = ADD_HREF_BY_LIST[fileName];
  if (!href) return html;

  // Replace broken Add toolbar button href (home / dashboard / empty module root)
  return html.replace(
    /(<div class="col-sm-6 col-md-6 text-right">\s*<a href=")([^"]*)(" class="btn btn-success[^"]*"[\s\S]*?<i class="fal fa-plus[^"]*"><\/i>\s*Add)/i,
    `$1${href}$3`
  );
}

function fixCancelLinks(fileName, html) {
  const key = moduleKey(fileName);
  const listUrl = LIST_URL_BY_MODULE[key];
  if (!listUrl) return html;

  // Only rewrite Cancel buttons that currently go to bare ADMIN_URL or dashboard
  return html.replace(
    /(<a href=")(<\?php echo ADMIN_URL;?\s*\?>)(" class="btn btn-(?:secondary|default)"[^>]*>\s*Cancel)/gi,
    `$1${listUrl}$3`
  ).replace(
    /(<a href=")(<\?php echo ADMIN_URL;?\s*\?>dashboard\/?)(" class="btn btn-(?:secondary|default)"[^>]*>\s*Cancel)/gi,
    `$1${listUrl}$3`
  );
}

function unwrapNestedChrome(html) {
  let s = html;
  // Drop empty nested panels left from jarvis conversion
  s = s.replace(
    /<div class="panel">\s*<div>\s*<!-- widget edit box -->\s*<div class="panel">[\s\S]*?<\/div>\s*<\/div>\s*/gi,
    ""
  );
  // Remove leftover section widget-grid wrappers that add nothing once inside panel-content
  // Keep id for any JS that might target it, but avoid double nesting issues by leaving as-is
  return s;
}

function ensureSa4Chrome(fileName, html) {
  if (/page-breadcrumb/.test(html) && /panel-hdr/.test(html)) return html;
  // Already handled by prior migrator for almost all Wave C pages.
  return html;
}

function processFile(fileName) {
  const full = path.join(VIEWS, fileName);
  let html = fs.readFileSync(full, "utf8");
  const before = html;

  html = modernize(html);
  html = fixAddToolbar(fileName, html);
  html = fixCancelLinks(fileName, html);
  html = unwrapNestedChrome(html);
  html = ensureSa4Chrome(fileName, html);

  // database_backup: rename Add label to Create Backup when pointing at create
  if (fileName === "database_backup.php") {
    html = html.replace(
      /(database_backup\/create["'][^>]*>[\s\S]*?<i class="fal fa-plus[^"]*"><\/i>\s*)Add/i,
      "$1Create Backup"
    );
  }

  if (html !== before) {
    fs.writeFileSync(full, html, "utf8");
    return true;
  }
  return false;
}

const files = fs.readdirSync(VIEWS).filter((f) => f.endsWith(".php") && isWaveC(f));
let changed = 0;
const touched = [];
for (const f of files) {
  if (processFile(f)) {
    changed++;
    touched.push(f);
  }
}

console.log(`Wave C candidates: ${files.length}`);
console.log(`Updated: ${changed}`);
touched.forEach((t) => console.log(" - " + t));
