/**
 * Restore leakingentry Add/Edit modal into SA4 list page.
 */
const fs = require("fs");
const path = require("path");

const origPath = path.join("c:/xampp/htdocs/labasonsandbox/tools/orig/leakingentry.php");
const currentPath = path.join("c:/xampp/htdocs/labasonsandbox/application/modules/master/views/leakingentry.php");
const outPath = currentPath;

const orig = fs.readFileSync(origPath, "utf8");
const current = fs.readFileSync(currentPath, "utf8");

const modalMatch = orig.match(/<!-- Modal -->[\s\S]*?<!-- \/\.modal -->/);
if (!modalMatch) {
  console.error("Modal not found");
  process.exit(1);
}
let modal = modalMatch[0];
modal = modal.replace(
  /<div class="modal-header">[\s\S]*?<h4 class="modal-title" id="myModalLabel">[\s\S]*?<\/h4>/,
  `<div class="modal-header">
								<h4 class="modal-title" id="myModalLabel">Add New Leaking Record</h4>
								<button type="button" class="close" data-dismiss="modal" aria-label="Close">
									<span aria-hidden="true">&times;</span>
								</button>`
);
modal = modal.replace('class="modal-dialog"', 'class="modal-dialog modal-lg"');

// Keep current table body (already SA4-ish); extract from current
const tableMatch = current.match(/<table id="dt_basic"[\s\S]*?<\/table>/);
if (!tableMatch) {
  console.error("Table not found in current view");
  process.exit(1);
}
let table = tableMatch[0]
  .replace(/label label-primary/g, "badge badge-primary")
  .replace(/label label-default/g, "badge badge-secondary")
  .replace('<th data-hide="phone"><input type="checkbox"/></th>', '<th data-hide="phone"><input type="checkbox" id="dt_select_all"/></th>');

// Functional JS from select2 init through helpers
const jsStart = orig.indexOf("// Function to initialize Select2 properly for modals");
const jsEnd = orig.lastIndexOf("</script>");
if (jsStart < 0 || jsEnd < 0) {
  console.error("JS block not found");
  process.exit(1);
}
let functionalJs = orig.slice(jsStart, jsEnd);
functionalJs = functionalJs
  .replace(/url:\s*'Leakingentry\/get_customer_meter_reading'/g, "url: '<?php echo ADMIN_URL; ?>Leakingentry/get_customer_meter_reading'")
  .replace(/url:\s*'Leakingentry\/get_customer_meter_reading_detail'/g, "url: '<?php echo ADMIN_URL; ?>Leakingentry/get_customer_meter_reading_detail'");

const helpersIdx = functionalJs.indexOf("function parseDmyString");
if (helpersIdx < 0) {
  console.error("helpers not found");
  process.exit(1);
}
let readyBody = functionalJs.slice(0, helpersIdx).trimEnd();
const helpers = functionalJs.slice(helpersIdx);
// Strip original document.ready closer
readyBody = readyBody.replace(/\}\)\s*$/, "").trimEnd();

readyBody = readyBody.replace(
  "$('#add_record').on('click', function(evt){\n                evt.preventDefault();",
  `$('#add_record').on('click', function(evt){
                evt.preventDefault();
				$('#myModal').modal('show');`
);

const out = `<?php
	$sa4_page_icon = 'fal fa-tint';
	$sa4_page_title = 'Manage';
	$sa4_page_subtitle = 'Leaking Ledger';
	$sa4_loading_label = 'Leaking Ledger';
	$sa4_dt_entity = 'leaking records';
	$sa4_panel_id = 'panel-leakingentry';
?>
<style>
	.select2-container { width: 100% !important; }
	.setStatus { cursor: pointer; }
	.select2-dropdown { z-index: 9999 !important; }
	.select2-container--open { z-index: 9999 !important; }
	#myModal .modal-body { max-height: 70vh; overflow-y: auto; }
</style>
<link rel="stylesheet" media="screen, print" href="<?php echo base_url(); ?>sa4/css/formplugins/select2/select2.bundle.css">
<main id="js-page-content" role="main" class="page-content">
	<ol class="breadcrumb page-breadcrumb">
		<li class="breadcrumb-item"><a href="<?php echo ADMIN_URL; ?>dashboard">Home</a></li>
		<li class="breadcrumb-item active">Leaking Ledger Listing</li>
		<li class="position-absolute pos-top pos-right d-none d-sm-block"><span class="js-get-date"></span></li>
	</ol>
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
				<div id="panel-leakingentry" class="panel">
					<div class="panel-hdr">
						<h2>Leaking <span class="fw-300"><i>Ledger</i></span></h2>
						<div class="panel-toolbar">
							<button type="button" class="btn btn-primary btn-sm waves-effect waves-themed mr-2" id="add_record">
								<i class="fal fa-plus mr-1"></i> Add Record
							</button>
							<button class="btn btn-panel" data-action="panel-collapse" data-toggle="tooltip" data-offset="0,10" data-original-title="Collapse"></button>
							<button class="btn btn-panel" data-action="panel-fullscreen" data-toggle="tooltip" data-offset="0,10" data-original-title="Fullscreen"></button>
						</div>
					</div>
					<div class="panel-container show">
						<div class="panel-content">
							<form method="post" action="<?php echo ADMIN_URL; ?>leakingentry/multi_delete" id="sa4-list-form">
								<div class="row mb-3 align-items-end">
									<div class="col-sm-12">
										<button type="submit" class="btn btn-danger btn-sm waves-effect waves-themed" onclick="return deleteAllData();">
											<i class="fal fa-trash-alt mr-1"></i> Delete Selected
										</button>
									</div>
								</div>
								${table}
							</form>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>
</main>

${modal}

<?php include(__DIR__ . '/partials/sa4_dt_loading.php'); ?>
<?php include('footer.php'); ?>
<script src="<?php echo base_url(); ?>sa4/js/formplugins/select2/select2.bundle.js"></script>
<?php include(__DIR__ . '/partials/sa4_dt_init.js.php'); ?>
<script type="text/javascript">
$(document).ready(function() {
	if ($.fn.datepicker) {
		$("#payment_date").datepicker({
			dateFormat: 'dd-mm-yy',
			firstDay: 1
		});
	}

${readyBody}

});

${helpers}
</script>
</body>
</html>
`;

fs.writeFileSync(outPath, out, "utf8");
console.log("Wrote", outPath);
console.log("readyBody chars", readyBody.length, "helpers chars", helpers.length, "modal chars", modal.length);
