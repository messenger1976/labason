<?php
/**
 * Shared SA4 KPI subheader block.
 * Expects optional vars: $sa4_page_icon, $sa4_page_title, $sa4_page_subtitle
 * Uses $this->my_model income/expense/customer helpers when available.
 */
if (!isset($sa4_page_icon)) { $sa4_page_icon = 'fal fa-th-list'; }
if (!isset($sa4_page_title)) { $sa4_page_title = 'Manage'; }
if (!isset($sa4_page_subtitle)) { $sa4_page_subtitle = 'Records'; }

$__intotal = 0;
$__extotal = 0;
$__count_id = 0;
try {
	if (isset($this->my_model) && method_exists($this->my_model, 'get_income_metercustomer')) {
		$income1 = $this->my_model->get_income_metercustomer();
		if (is_array($income1)) { extract($income1); }
		$income2 = $this->my_model->get_income_monthlycustomer();
		if (is_array($income2)) { extract($income2); }
		$__intotal = (isset($total1) ? (float)$total1 : 0) + (isset($total2) ? (float)$total2 : 0);

		$expense1 = $this->my_model->get_outcome_expenses();
		if (is_array($expense1)) { extract($expense1); }
		$expense2 = $this->my_model->get_outcome_payroll();
		if (is_array($expense2)) { extract($expense2); }
		$__extotal = (isset($extotal1) ? (float)$extotal1 : 0) + (isset($extotal2) ? (float)$extotal2 : 0);

		$total_customer = $this->my_model->total_customer();
		if (is_array($total_customer)) { extract($total_customer); }
		$__count_id = isset($count_id) ? (int)$count_id : 0;
	}
} catch (Exception $e) {
	// keep zeros
}
?>
<div class="subheader">
	<h1 class="subheader-title">
		<i class="subheader-icon <?php echo htmlspecialchars($sa4_page_icon); ?>"></i>
		<?php echo htmlspecialchars($sa4_page_title); ?> <span class="fw-300"><?php echo htmlspecialchars($sa4_page_subtitle); ?></span>
	</h1>
	<div class="subheader-block d-lg-flex align-items-center">
		<div class="d-inline-flex flex-column justify-content-center mr-3">
			<span class="fw-300 fs-xs d-block opacity-50"><small>INCOME</small></span>
			<span class="fw-500 fs-xl d-block color-primary-500">₱ <?php echo number_format($__intotal, 2); ?></span>
		</div>
		<span class="sparklines hidden-lg-down" sparkType="bar" sparkBarColor="#886ab5" sparkHeight="32px" sparkBarWidth="5px" values="3,4,3,6,7,3,3,6,2,6,4"></span>
	</div>
	<div class="subheader-block d-lg-flex align-items-center border-faded border-right-0 border-top-0 border-bottom-0 ml-3 pl-3">
		<div class="d-inline-flex flex-column justify-content-center mr-3">
			<span class="fw-300 fs-xs d-block opacity-50"><small>EXPENSE</small></span>
			<span class="fw-500 fs-xl d-block color-danger-500">₱ <?php echo number_format($__extotal, 2); ?></span>
		</div>
		<span class="sparklines hidden-lg-down" sparkType="bar" sparkBarColor="#fe6bb0" sparkHeight="32px" sparkBarWidth="5px" values="1,4,3,6,5,3,9,6,5,9,7"></span>
	</div>
	<div class="subheader-block d-lg-flex align-items-center border-faded border-right-0 border-top-0 border-bottom-0 ml-3 pl-3">
		<div class="d-inline-flex flex-column justify-content-center mr-3">
			<span class="fw-300 fs-xs d-block opacity-50"><small>TOTAL CUSTOMER</small></span>
			<span class="fw-500 fs-xl d-block color-success-500"><?php echo (int)$__count_id; ?></span>
		</div>
		<span class="sparklines hidden-lg-down" sparkType="bar" sparkBarColor="#1dc9b7" sparkHeight="32px" sparkBarWidth="5px" values="2,5,3,7,4,6,3,8,5,4,6"></span>
	</div>
</div>
