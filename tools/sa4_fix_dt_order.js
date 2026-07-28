const fs = require("fs");
const path = require("path");
const V = path.join("c:/xampp/htdocs/labasonsandbox/application/modules/master/views");
const needle = "<?php include(__DIR__ . '/partials/sa4_dt_init.js.php'); ?>";
let n = 0;
for (const f of fs.readdirSync(V)) {
  if (!f.endsWith(".php")) continue;
  const full = path.join(V, f);
  let html = fs.readFileSync(full, "utf8");
  if (!html.includes("sa4_dt_init.js.php")) continue;
  // remove all occurrences then place once before </body>
  html = html.split(needle).join("");
  if (html.includes("</body>")) {
    html = html.replace("</body>", needle + "\n</body>");
  } else {
    html = html.trimEnd() + "\n" + needle + "\n";
  }
  fs.writeFileSync(full, html);
  n++;
}
console.log("fixed order", n);
