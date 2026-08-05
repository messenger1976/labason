/**
 * Repair Wave C form closing tags after sa4-legacy-inner unwrap,
 * and clean mangled Ace visibility classes on icons.
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

function countTags(html, openRe, closeRe) {
  return {
    open: (html.match(openRe) || []).length,
    close: (html.match(closeRe) || []).length,
  };
}

function repair(html) {
  let s = html;

  // Remove duplicate end-widget-grid section closes
  s = s.replace(
    /<\/section>\s*<!--\s*end widget grid\s*-->\s*<\/section>\s*<!--\s*end widget grid\s*-->/gi,
    "</section>\n\t\t\t<!-- end widget grid -->"
  );

  // Also handle blank-line separated duplicates
  s = s.replace(
    /<\/section>\s*<!--\s*end widget grid\s*-->\s*\n+\s*<\/section>\s*<!--\s*end widget grid\s*-->/gi,
    "</section>\n\t\t\t<!-- end widget grid -->"
  );

  // Close form-group/row that was left open before </form>
  s = s.replace(
    /(<div class="form-group mt-3">\s*<div class="row">\s*<div class="col-md-12">[\s\S]*?<\/div>)\s*<\/form>/gi,
    "$1\n\t\t\t\t\t\t\t\t\t</div>\n\t\t\t\t\t\t\t\t</div>\n\t\t\t\t\t\t\t</form>"
  );

  // After </form>, drop one orphan </div> that belonged to removed sa4-legacy-inner
  // Pattern: </form> ... </div> (orphan) ... </div> (col)
  s = s.replace(
    /(<\/form>)\s*<\/div>(\s*<\/div>\s*<\/div>)/i,
    "$1$2"
  );

  // Clean mangled Ace visibility class stacks on icons
  s = s.replace(
    /\s*(?:d-md-none|d-sm-none|d-md-inline|d-none|d-sm-inline|d-lg-none|d-lg-inline|hidden-md|hidden-sm|hidden-xs|hidden-lg|visible-md|visible-lg)+/g,
    ""
  );

  // Fix double spaces in class attrs
  s = s.replace(/class="([^"]*?)\s{2,}([^"]*)"/g, 'class="$1 $2"');

  return s;
}

function balanceHint(html) {
  const div = countTags(html, /<div[\s>]/gi, /<\/div>/gi);
  const sec = countTags(html, /<section[\s>]/gi, /<\/section>/gi);
  return {
    divDelta: div.open - div.close,
    secDelta: sec.open - sec.close,
  };
}

const files = fs.readdirSync(VIEWS).filter((f) => f.endsWith(".php") && isWaveC(f));
let changed = 0;
const stillBad = [];
for (const f of files) {
  const full = path.join(VIEWS, f);
  const before = fs.readFileSync(full, "utf8");
  let after = repair(before);

  // If still one extra </div>, remove one more orphan after form
  let bal = balanceHint(after);
  if (bal.divDelta === -1) {
    after = after.replace(/(<\/form>[\s\S]{0,120}?)<\/div>/i, "$1");
  }
  if (bal.secDelta === -1 || balanceHint(after).secDelta === -1) {
    after = after.replace(/<\/section>\s*<!--\s*end widget grid\s*-->/i, "<!-- end widget grid -->");
  }

  // web_settings-edit / addshareholder_add may have -2
  bal = balanceHint(after);
  while (bal.divDelta < 0) {
    const next = after.replace(/(<\/form>[\s\S]{0,200}?)<\/div>/i, "$1");
    if (next === after) break;
    after = next;
    bal = balanceHint(after);
  }
  while (bal.secDelta < 0) {
    const next = after.replace(/<\/section>(\s*<!--\s*end widget grid\s*-->)?/i, "$1");
    if (next === after) break;
    after = next;
    bal = balanceHint(after);
  }

  if (after !== before) {
    fs.writeFileSync(full, after, "utf8");
    changed++;
  }
  bal = balanceHint(after);
  if (bal.divDelta !== 0 || bal.secDelta !== 0) {
    stillBad.push(`${f}: div=${bal.divDelta} sec=${bal.secDelta}`);
  }
}

console.log(`Repaired files touched/checked: ${changed}/${files.length}`);
if (stillBad.length) {
  console.log("Still unbalanced:");
  stillBad.forEach((x) => console.log(" - " + x));
} else {
  console.log("All Wave C files balanced for div/section.");
}
