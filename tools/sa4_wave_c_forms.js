/**
 * Wave C form deep-clean: flatten nested Ace shells and convert
 * input-group-text label spans into SA4 form-label + form-control rows.
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

const SKIP_RE = /(ajax|print|Copy|05-aug|04-aug|mobile_|mobilenotifications)/i;

function isWaveC(name) {
  if (SKIP_RE.test(name) && name !== "database_backup.php") return false;
  const low = name.toLowerCase();
  return WAVE_C_PREFIXES.some((p) => low.startsWith(p.toLowerCase()));
}

function cleanForm(html) {
  let s = html;

  // Flatten nested Ace panel shells inside SA4 panel-content
  s = s.replace(/<div class="sa4-legacy-inner">\s*/gi, "");
  // Remove leftover closing orphaned wrappers carefully: only when followed by form-actions/footer close patterns is hard;
  // instead strip empty wrapper openers we introduced and leave structure mostly intact.

  // Convert <span class="input-group-text">...<strong>Label</strong></span> into form-label
  s = s.replace(
    /<span class="input-group-text">\s*(?:<i class="[^"]*"><\/i>\s*)?<strong>([\s\S]*?)<\/strong>\s*<\/span>/gi,
    '<label class="form-label">$1</label>'
  );

  // Ace checkbox class
  s = s.replace(/\bclass="ace"/g, 'class="checkbox"');

  // Remaining Ace visibility leftovers missed by first pass
  s = s.replace(/\bhidden-md\b/g, "d-md-none");
  s = s.replace(/\bhidden-lg\b/g, "d-lg-none");
  s = s.replace(/\bvisible-lg\b/g, "d-lg-inline");

  // form-actions → button row
  s = s.replace(/<div class="form-actions">/gi, '<div class="form-group mt-3">');

  // Soften fieldset legends
  s = s.replace(/<legend>/gi, '<h5 class="mb-3">');
  s = s.replace(/<\/legend>/gi, "</h5>");

  // Remove empty nested panel remnants from jarvis conversion
  s = s.replace(
    /<div class="panel">\s*<div>\s*<!--\s*widget edit box\s*-->\s*<div class="panel">[\s\S]*?<\/div>\s*<\/div>\s*/gi,
    ""
  );

  // Drop mobile Ace dropdown action blocks (desktop icons remain)
  s = s.replace(
    /<div class="d-md-none">\s*<div class="inline position-relative">[\s\S]*?<\/div>\s*<\/div>/gi,
    ""
  );

  return s;
}

const files = fs.readdirSync(VIEWS).filter((f) => f.endsWith(".php") && isWaveC(f));
let changed = 0;
for (const f of files) {
  const full = path.join(VIEWS, f);
  const before = fs.readFileSync(full, "utf8");
  const after = cleanForm(before);
  if (after !== before) {
    fs.writeFileSync(full, after, "utf8");
    changed++;
    console.log(" - " + f);
  }
}
console.log(`Deep-cleaned: ${changed}/${files.length}`);
