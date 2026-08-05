<?php
/**
 * Shared SA4 KPI subheader block.
 * Expects optional vars: $sa4_page_icon, $sa4_page_title, $sa4_page_subtitle
 * Optional: $sa4_show_transdate = true to show TRANSACTION DATE (session $_SESSION['trans_date'])
 * Optional precomputed: $sa4_kpi_income, $sa4_kpi_expense, $sa4_kpi_customers
 * Otherwise loads from my_model / comm_model helpers.
 */
if (!isset($sa4_page_icon)) { $sa4_page_icon = 'fal fa-th-list'; }
if (!isset($sa4_page_title)) { $sa4_page_title = 'Manage'; }
if (!isset($sa4_page_subtitle)) { $sa4_page_subtitle = 'Records'; }
if (!isset($sa4_show_transdate)) { $sa4_show_transdate = false; }

$__intotal = isset($sa4_kpi_income) ? (float) $sa4_kpi_income : null;
$__extotal = isset($sa4_kpi_expense) ? (float) $sa4_kpi_expense : null;
$__count_id = isset($sa4_kpi_customers) ? (int) $sa4_kpi_customers : null;
$__trans_date = isset($_SESSION['trans_date']) ? $_SESSION['trans_date'] : date('d-m-Y');

if ($__intotal === null || $__extotal === null || $__count_id === null) {
	$__intotal = 0;
	$__extotal = 0;
	$__count_id = 0;
	try {
		// CI/HMVC: isset($this->my_model) is often false even when loaded — probe by access.
		$__kpi = null;
		foreach (array('my_model', 'comm_model') as $__prop) {
			$__obj = isset($this->{$__prop}) ? $this->{$__prop} : null;
			if (!is_object($__obj)) {
				try { $__obj = $this->{$__prop}; } catch (Exception $e) { $__obj = null; }
			}
			if (is_object($__obj) && method_exists($__obj, 'get_income_metercustomer')) {
				$__kpi = $__obj;
				break;
			}
		}
		if ($__kpi !== null) {
			$income1 = $__kpi->get_income_metercustomer();
			if (is_array($income1)) { extract($income1); }
			$income2 = method_exists($__kpi, 'get_income_monthlycustomer') ? $__kpi->get_income_monthlycustomer() : array();
			if (is_array($income2)) { extract($income2); }
			$__intotal = (isset($total1) ? (float) $total1 : 0) + (isset($total2) ? (float) $total2 : 0);

			$expense1 = method_exists($__kpi, 'get_outcome_expenses') ? $__kpi->get_outcome_expenses() : array();
			if (is_array($expense1)) { extract($expense1); }
			$expense2 = method_exists($__kpi, 'get_outcome_payroll') ? $__kpi->get_outcome_payroll() : array();
			if (is_array($expense2)) { extract($expense2); }
			$__extotal = (isset($extotal1) ? (float) $extotal1 : 0) + (isset($extotal2) ? (float) $extotal2 : 0);

			$total_customer = method_exists($__kpi, 'total_customer') ? $__kpi->total_customer() : array();
			if (is_array($total_customer)) { extract($total_customer); }
			$__count_id = isset($count_id) ? (int) $count_id : 0;
		}
	} catch (Exception $e) {
		// keep zeros
	}
}
?>
<div class="subheader">
	<h1 class="subheader-title">
		<i class="subheader-icon <?php echo htmlspecialchars($sa4_page_icon); ?>"></i>
		<?php echo htmlspecialchars($sa4_page_title); ?> <span class="fw-300"><?php echo htmlspecialchars($sa4_page_subtitle); ?></span>
	</h1>
	<?php if (!empty($sa4_show_transdate)) { ?>
	<div class="subheader-block d-flex align-items-center">
		<div class="d-inline-flex flex-column justify-content-center mr-3">
			<span class="fw-300 fs-xs d-block opacity-50"><small>TRANSACTION DATE</small></span>
			<div class="input-group input-group-sm" style="min-width:140px;max-width:160px;">
				<input type="text" class="form-control" name="header_transdate" id="header_transdate" value="<?php echo htmlspecialchars($__trans_date); ?>" readonly>
				<div class="input-group-append">
					<span class="input-group-text fs-xl"><i class="fal fa-calendar"></i></span>
				</div>
			</div>
		</div>
	</div>
	<?php } ?>
	<div class="subheader-block d-flex align-items-center<?php echo !empty($sa4_show_transdate) ? ' border-faded border-right-0 border-top-0 border-bottom-0 ml-3 pl-3' : ''; ?>">
		<div class="d-inline-flex flex-column justify-content-center mr-3">
			<span class="fw-300 fs-xs d-block opacity-50"><small>INCOME</small></span>
			<span class="fw-500 fs-xl d-block color-primary-500">₱ <?php echo number_format($__intotal, 2); ?></span>
		</div>
		<span class="sparklines d-none d-xl-inline-block" sparkType="bar" sparkBarColor="#886ab5" sparkHeight="32px" sparkBarWidth="5px" values="3,4,3,6,7,3,3,6,2,6,4"></span>
	</div>
	<div class="subheader-block d-flex align-items-center border-faded border-right-0 border-top-0 border-bottom-0 ml-3 pl-3">
		<div class="d-inline-flex flex-column justify-content-center mr-3">
			<span class="fw-300 fs-xs d-block opacity-50"><small>EXPENSE</small></span>
			<span class="fw-500 fs-xl d-block color-danger-500">₱ <?php echo number_format($__extotal, 2); ?></span>
		</div>
		<span class="sparklines d-none d-xl-inline-block" sparkType="bar" sparkBarColor="#fe6bb0" sparkHeight="32px" sparkBarWidth="5px" values="1,4,3,6,5,3,9,6,5,9,7"></span>
	</div>
	<div class="subheader-block d-flex align-items-center border-faded border-right-0 border-top-0 border-bottom-0 ml-3 pl-3">
		<div class="d-inline-flex flex-column justify-content-center mr-3">
			<span class="fw-300 fs-xs d-block opacity-50"><small>TOTAL CUSTOMER</small></span>
			<span class="fw-500 fs-xl d-block color-success-500"><?php echo (int) $__count_id; ?></span>
		</div>
		<span class="sparklines d-none d-xl-inline-block" sparkType="bar" sparkBarColor="#1dc9b7" sparkHeight="32px" sparkBarWidth="5px" values="2,5,3,7,4,6,3,8,5,4,6"></span>
	</div>
</div>
