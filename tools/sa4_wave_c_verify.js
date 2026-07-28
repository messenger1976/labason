const fs = require("fs");
const path = require("path");
const VIEWS = "c:/xampp/htdocs/labasonsandbox/application/modules/master/views";
const prefs = [
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
const skip = /(ajax|print|Copy|05-aug|04-aug|mobile_|mobilenotifications)/i;
const files = fs
  .readdirSync(VIEWS)
  .filter(
    (f) =>
      f.endsWith(".php") &&
      !skip.test(f) &&
      prefs.some((p) => f.toLowerCase().startsWith(p.toLowerCase()))
  );

function bal(h) {
  return {
    div:
      (h.match(/<div[\s>]/gi) || []).length -
      (h.match(/<\/div>/gi) || []).length,
    sec:
      (h.match(/<section[\s>]/gi) || []).length -
      (h.match(/<\/section>/gi) || []).length,
    crumb: /page-breadcrumb/.test(h),
    panel: /panel-hdr/.test(h),
    main: /js-page-content/.test(h),
    badAdd:
      /btn btn-success[^>]*>[\s\S]{0,80}fal fa-plus[\s\S]{0,40}Add/i.test(h) &&
      /href="<?php echo ADMIN_URL\?>" class="btn btn-success/i.test(h),
  };
}

const bad = [];
for (const f of files) {
  const h = fs.readFileSync(path.join(VIEWS, f), "utf8");
  const b = bal(h);
  const probs = [];
  if (b.div) probs.push("div=" + b.div);
  if (b.sec) probs.push("sec=" + b.sec);
  if (!b.crumb) probs.push("no-crumb");
  if (!b.panel) probs.push("no-panel");
  if (!b.main) probs.push("no-main");
  if (b.badAdd) probs.push("bad-add");
  if (probs.length) bad.push(f + ": " + probs.join(","));
}
console.log("Checked", files.length);
if (!bad.length) console.log("ALL OK");
else bad.forEach((x) => console.log(x));

// spot-check add hrefs
const lists = [
  "employee-logins.php",
  "addemployee.php",
  "responsibilities.php",
  "job_title.php",
  "classification.php",
  "amountrate.php",
  "addexpenses.php",
  "addledger.php",
  "database_backup.php",
  "web_settings.php",
];
for (const f of lists) {
  const h = fs.readFileSync(path.join(VIEWS, f), "utf8");
  const m = h.match(/<a href="([^"]+)" class="btn btn-success[^"]*"[^>]*>[\s\S]*?fa-plus/);
  console.log(f, "=>", m ? m[1] : "NO-ADD");
}
