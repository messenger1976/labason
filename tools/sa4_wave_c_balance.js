/**
 * Final Wave C tag-balance fix for list/search shells.
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

function balance(html) {
  return {
    div: (html.match(/<div[\s>]/gi) || []).length - (html.match(/<\/div>/gi) || []).length,
    sec: (html.match(/<section[\s>]/gi) || []).length - (html.match(/<\/section>/gi) || []).length,
  };
}

function fix(html) {
  let s = html;
  let b = balance(s);

  // List shell: missing row close before </section>
  while (b.div > 0) {
    if (/<\/div>\s*<\/section>\s*<\/main>/i.test(s)) {
      s = s.replace(/<\/div>\s*<\/section>\s*<\/main>/i, "</div>\n\t\t</div>\n\t</section>\n</main>");
    } else if (/<\/div>\s*<\/main>/i.test(s)) {
      s = s.replace(/<\/div>\s*<\/main>/i, "</div>\n\t</div>\n</main>");
    } else {
      s = s.replace(/<\/main>/i, "\t</div>\n</main>");
    }
    const nb = balance(s);
    if (nb.div >= b.div) break;
    b = nb;
  }

  // Extra closes: remove orphan </div> immediately before </section> or </main>
  while (b.div < 0) {
    let next = s;
    if (/<\/div>\s*<\/section>/i.test(s)) {
      next = s.replace(/<\/div>(\s*<\/section>)/i, "$1");
    } else if (/<\/div>\s*<\/main>/i.test(s)) {
      next = s.replace(/<\/div>(\s*<\/main>)/i, "$1");
    } else {
      next = s.replace(/<\/div>(\s*<\/div>\s*<\/main>)/i, "$1");
    }
    if (next === s) break;
    s = next;
    b = balance(s);
  }

  while (b.sec < 0) {
    const next = s.replace(/<\/section>/i, "");
    if (next === s) break;
    s = next;
    b = balance(s);
  }
  while (b.sec > 0) {
    const next = s.replace(/<\/main>/i, "\t</section>\n</main>");
    if (next === s) break;
    s = next;
    b = balance(s);
  }

  return s;
}

const files = fs.readdirSync(VIEWS).filter((f) => f.endsWith(".php") && isWaveC(f));
const still = [];
let changed = 0;
for (const f of files) {
  const full = path.join(VIEWS, f);
  const before = fs.readFileSync(full, "utf8");
  const after = fix(before);
  if (after !== before) {
    fs.writeFileSync(full, after, "utf8");
    changed++;
  }
  const b = balance(after);
  if (b.div !== 0 || b.sec !== 0) still.push(`${f}: div=${b.div} sec=${b.sec}`);
}
console.log(`Fixed: ${changed}`);
if (still.length) {
  console.log("Still bad:");
  still.forEach((x) => console.log(" - " + x));
} else {
  console.log("All balanced.");
}
