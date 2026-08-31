<?php
/**
 * One-off diagnostic: orphan meter payments (blank month and/or year).
 * Labason: php sql/scan_orphan_ors.php [YYYY]
 */
error_reporting(E_ALL);
ini_set('display_errors', '1');

define('BASEPATH', true);
$config_path = dirname(__DIR__) . '/application/config/database.php';
if (!is_readable($config_path)) {
	fwrite(STDERR, "Cannot read database config\n");
	exit(1);
}
include $config_path;
$c = isset($db['default']) ? $db['default'] : null;
if (!$c) {
	fwrite(STDERR, "No default DB config\n");
	exit(1);
}

$mysqli = @new mysqli($c['hostname'], $c['username'], $c['password'], $c['database'], isset($c['port']) ? (int)$c['port'] : 3306);
if ($mysqli->connect_error) {
	fwrite(STDERR, "DB connect failed\n");
	exit(1);
}
$mysqli->set_charset('utf8');

$year_filter = null;
if (isset($argv[1]) && preg_match('/^\d{4}$/', $argv[1])) {
	$year_filter = (int) $argv[1];
}

$where = "(TRIM(IFNULL(p.month,'')) = '' OR TRIM(IFNULL(p.year,'')) = '')";
if ($year_filter !== null) {
	$where .= " AND YEAR(p.date) = ".$year_filter;
}

echo "Database: ".$c['database']."\n";
echo "Scan: blank month and/or year".($year_filter !== null ? " (payment date year ".$year_filter.")" : " (all years)")."\n";
echo str_repeat('-', 72)."\n";

$sql_count = "
SELECT
	COUNT(*) AS orphan_rows,
	COUNT(DISTINCT p.customer_id) AS distinct_customers,
	COUNT(DISTINCT p.or_number) AS distinct_ors,
	COALESCE(SUM(CAST(p.grand_total AS DECIMAL(12,2))), 0) AS sum_grand_total,
	COALESCE(SUM(CAST(p.pay_amount AS DECIMAL(12,2))), 0) AS sum_pay_amount,
	MIN(p.date) AS earliest_date,
	MAX(p.date) AS latest_date
FROM tbl_addmetercustomer p
WHERE ".$where;
$r = $mysqli->query($sql_count);
if (!$r) {
	fwrite(STDERR, "Query failed: ".$mysqli->error."\n");
	exit(1);
}
$row = $r->fetch_assoc();
foreach ($row as $k => $v) {
	echo str_pad($k, 22).': '.$v."\n";
}

if ($year_filter === null) {
	echo "\nBy calendar year of payment date:\n";
	$sql_by_year = "
	SELECT YEAR(date) AS pay_year, COUNT(*) AS rows_n,
		COALESCE(SUM(CAST(grand_total AS DECIMAL(12,2))), 0) AS sum_grand
	FROM tbl_addmetercustomer
	WHERE (TRIM(IFNULL(month,'')) = '' OR TRIM(IFNULL(year,'')) = '')
	GROUP BY YEAR(date)
	ORDER BY pay_year DESC
	";
	$r = $mysqli->query($sql_by_year);
	while ($x = $r->fetch_assoc()) {
		echo "  ".$x['pay_year']."  rows=".$x['rows_n']."  sum_grand=".$x['sum_grand']."\n";
	}
}

echo "\nDetail (id, or_number, date, customer_id, grand_total, pay_amount, meters, invoice):\n";
$sql_detail = "
SELECT p.id, p.or_number, p.date, p.customer_id,
	p.grand_total, p.pay_amount, p.oldmeter, p.aftermeter, p.month, p.year,
	p.invoice_id
FROM tbl_addmetercustomer p
WHERE ".$where."
ORDER BY p.date DESC, p.id DESC
LIMIT 500
";
$r = $mysqli->query($sql_detail);
$n = 0;
$zero_meter = 0;
while ($x = $r->fetch_assoc()) {
	$n++;
	if ((string)$x['oldmeter'] === '0' && (string)$x['aftermeter'] === '0') {
		$zero_meter++;
	}
	echo sprintf(
		"%d\tOR=%s\tdate=%s\tcust=%s\tgrand=%s\tpay=%s\tmeter=%s/%s\tinv=%s\n",
		(int)$x['id'],
		$x['or_number'],
		$x['date'],
		$x['customer_id'],
		$x['grand_total'],
		$x['pay_amount'],
		$x['oldmeter'],
		$x['aftermeter'],
		$x['invoice_id']
	);
}
echo "Listed ".$n." row(s). Of those, meters 0/0: ".$zero_meter."\n";

$mysqli->close();
