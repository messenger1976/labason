<?php
$logged_in_user = trim((string) $this->session->userdata('name'));
if ($logged_in_user === '') {
	$logged_in_user = trim((string) $this->session->userdata('username'));
}
$admin_name = trim((string) $this->session->userdata('admininfo_name'));
if ($admin_name === '') { $admin_name = 'Billing System'; }
if (!isset($roleResponsible) || !is_array($roleResponsible)) { $roleResponsible = array(); }
$avatar_relative = 'assets/avatars/avatar.png';
$avatar_dir = FCPATH . 'uploads/profile/';
$avatar_key = preg_replace('/[^a-z0-9_-]/i', '', strtolower((string) $this->session->userdata('usertype'))) . '_' . (int) $this->session->userdata('userid');
$avatar_mtime = 0;
foreach (array('jpg', 'jpeg', 'png', 'gif', 'webp') as $avatar_ext) {
	$avatar_abs = $avatar_dir . $avatar_key . '.' . $avatar_ext;
	if (is_file($avatar_abs)) {
		$avatar_relative = 'uploads/profile/' . $avatar_key . '.' . $avatar_ext;
		$avatar_mtime = @filemtime($avatar_abs);
		break;
	}
}
if (!$avatar_mtime) {
	$avatar_default_abs = FCPATH . $avatar_relative;
	$avatar_mtime = @filemtime($avatar_default_abs) ?: time();
}
$avatar_url = base_url($avatar_relative) . '?v=' . $avatar_mtime;
?>
<aside class="page-sidebar">
	<div class="page-logo">
		<a href="<?php echo ADMIN_URL; ?>dashboard/" class="page-logo-link press-scale-down d-flex align-items-center position-relative">
			<img src="<?php echo base_url(); ?>img/pmroxas-logo.png" alt="Logo" aria-roledescription="logo" style="width:32px;height:32px;">
			<span class="page-logo-text mr-1"><?php echo htmlspecialchars($admin_name, ENT_QUOTES, 'UTF-8'); ?></span>
		</a>
	</div>
	<nav id="js-primary-nav" class="primary-nav" role="navigation">
		<div class="nav-filter">
			<div class="position-relative">
				<input type="text" id="nav_filter_input" placeholder="Filter menu" class="form-control" tabindex="0">
				<a href="#" onclick="return false;" class="btn-primary btn-search-close js-waves-off" data-action="toggle" data-class="list-filter-active" data-target=".page-sidebar">
					<i class="fal fa-chevron-up"></i>
				</a>
			</div>
		</div>
		<div class="info-card">
			<img src="<?php echo htmlspecialchars($avatar_url, ENT_QUOTES, 'UTF-8'); ?>" class="profile-image rounded-circle" alt="User" style="object-fit:cover;">
			<div class="info-card-text">
				<a href="javascript:void(0);" class="d-flex align-items-center text-white">
					<span class="text-truncate text-truncate-sm d-inline-block"><?php echo htmlspecialchars($logged_in_user, ENT_QUOTES, 'UTF-8'); ?></span>
				</a>
				<span class="d-inline-block text-truncate text-truncate-sm"><?php echo htmlspecialchars((string)$this->session->userdata('usertype'), ENT_QUOTES, 'UTF-8'); ?></span>
			</div>
			<img src="<?php echo base_url(); ?>sa4/img/card-backgrounds/cover-2-lg.png" class="cover" alt="cover">
			<a href="#" onclick="return false;" class="pull-trigger-btn" data-action="toggle" data-class="list-filter-active" data-target=".page-sidebar" data-focus="nav_filter_input">
				<i class="fal fa-angle-down"></i>
			</a>
		</div>
		<ul id="js-nav-menu" class="nav-menu">
			<?php if( ( array_key_exists('dashboard',$roleResponsible) && ($roleResponsible['dashboard'] == 1) ) || ( $this->session->userdata('usertype') == 'admin' )){ ?>
			<li class="<?php if($this->uri->segment(2)=='dashboard') echo 'active';?>">
				<a href="<?php echo ADMIN_URL;?>dashboard/" title="Dashboard">
					<i class="fal fa-home"></i>
					<span class="nav-link-text">Dashboard</span>
				</a>
			</li>
			<?php } ?>

			<li class="<?php if($this->uri->segment(2)=='addcustomer' && ($this->uri->segment(3)=='' || $this->uri->segment(3)=='index' || $this->uri->segment(3)=='add' || $this->uri->segment(3)=='edit')){echo 'active open';}?>
						<?php if($this->uri->segment(2)=='add_zone'){echo 'active open';}?>
						<?php if($this->uri->segment(2)=='statementofaccountlist'){echo 'active open';}?>">
				<a href="#" title="Customers">
					<i class="fal fa-user"></i>
					<span class="nav-link-text">Customers</span>
				</a>
				<ul>
					<?php if((array_key_exists('addcustomer',$roleResponsible) && ($roleResponsible['addcustomer'] == 1) ) || ($this->session->userdata('usertype') == 'admin')){ ?>
					<li class="<?php if($this->uri->segment(2)=='addcustomer' && ($this->uri->segment(3)=='' || $this->uri->segment(3)=='index' || $this->uri->segment(3)=='add' || $this->uri->segment(3)=='edit')){echo 'active';}?>">
						<a href="<?php echo ADMIN_URL;?>addcustomer" title="Customers Listing">
							<span class="nav-link-text">Customers Listing</span>
						</a>
					</li>
					<?php } ?>
					<?php if((array_key_exists('add_zone',$roleResponsible) && ($roleResponsible['add_zone'] == 1) ) || ($this->session->userdata('usertype') == 'admin')){ ?>
					<li class="<?php if($this->uri->segment(2)=='add_zone'){echo 'active';}?>">
						<a href="<?php echo ADMIN_URL;?>add_zone" title="Zone Names">
							<span class="nav-link-text">Zone Names</span>
						</a>
					</li>
					<?php } ?>
					<?php if((array_key_exists('statementofaccountlist',$roleResponsible) && ($roleResponsible['statementofaccountlist'] == 1) ) || ($this->session->userdata('usertype') == 'admin')){ ?>
					<li class="<?php if($this->uri->segment(2)=='statementofaccountlist'){echo 'active';}?>">
						<a href="<?php echo ADMIN_URL;?>statementofaccountlist" title="Statement of Account">
							<span class="nav-link-text">Statement of Account</span>
						</a>
					</li>
					<?php } ?>
				</ul>
			</li>

			<li class="<?php if($this->uri->segment(3)=='addmetercustomerreading' && $this->uri->segment(2)=='addcustomer'){echo 'active open';}?>
			            <?php if($this->uri->segment(3)=='metersearch' && $this->uri->segment(2)=='addcustomer'){echo 'active open';}?>
						<?php if($this->uri->segment(3)=='monthlysearch' && $this->uri->segment(2)=='addcustomer'){echo 'active open';}?>
						<?php if($this->uri->segment(3)=='generatemetercustomer_search' && $this->uri->segment(2)=='addcustomer'){echo 'active open';}?>
						<?php if($this->uri->segment(3)=='income_reportsearch' && $this->uri->segment(2)=='addcustomer'){echo 'active open';}?>
						<?php if($this->uri->segment(3)=='paidsearch' && $this->uri->segment(2)=='addcustomer'){echo 'active open';}?>
						<?php if($this->uri->segment(3)=='unpaidsearch' && $this->uri->segment(2)=='addcustomer'){echo 'active open';}?>
						<?php if($this->uri->segment(2)=='feesplaning'){echo 'active open';}?>
						<?php if($this->uri->segment(2)=='paymentmonthlycustomer'){echo 'active open';}?>
						<?php if($this->uri->segment(2)=='addpaymentcustomer'){echo 'active open';}?>
						<?php if($this->uri->segment(2)=='leakingentry'){echo 'active open';}?>">
				<a href="#" title="Finance">
					<i class="fal fa-money-bill"></i>
					<span class="nav-link-text">Finance</span>
				</a>
				<ul>
					<?php if((array_key_exists('addmetercustomerreading',$roleResponsible) && ($roleResponsible['addmetercustomerreading'] == 1) ) || ($this->session->userdata('usertype') == 'admin')){ ?>
					<li class="<?php if($this->uri->segment(2)=='addmetercustomerreading' && $this->uri->segment(3)!='edit'){echo 'active';}?>">
						<a href="<?php echo ADMIN_URL;?>addmetercustomerreading" title="Add Meter Customers Reading">
							<span class="nav-link-text">Add Meter Customers Reading</span>
						</a>
					</li>
					<?php } ?>

					<?php if((array_key_exists('addpaymentcustomer',$roleResponsible) && ($roleResponsible['addpaymentcustomer'] == 1) ) || ($this->session->userdata('usertype') == 'admin')){ ?>
					<li class="<?php if($this->uri->segment(2)=='addpaymentcustomer'){echo 'active';}?>">
						<a href="<?php echo ADMIN_URL;?>addpaymentcustomer" title="Meter Customers Bills">
							<span class="nav-link-text">Meter Customers Bills</span>
						</a>
					</li>
					<?php } ?>

					<?php if((array_key_exists('leakingentry',$roleResponsible) && ($roleResponsible['leakingentry'] == 1) ) || ($this->session->userdata('usertype') == 'admin')){ ?>
					<li class="<?php if($this->uri->segment(2)=='Leakingentry'){echo 'active';}?>">
						<a href="<?php echo ADMIN_URL;?>Leakingentry" title="Leaking Entry">
							<span class="nav-link-text">Leaking Entry</span>
						</a>
					</li>
					<?php } ?>

					<?php if((array_key_exists('feesplaning',$roleResponsible) && ($roleResponsible['feesplaning'] == 1) ) || ($this->session->userdata('usertype') == 'admin')){ ?>
					<li class="<?php if($this->uri->segment(2)=='feesplaning'){echo 'active';}?>">
						<a href="<?php echo ADMIN_URL;?>feesplaning" title="Monthly Fees Plans">
							<span class="nav-link-text">Monthly Fees Plans</span>
						</a>
					</li>
					<?php } ?>

					<?php if((array_key_exists('paymentmonthlycustomer',$roleResponsible) && ($roleResponsible['paymentmonthlycustomer'] == 1) ) || ($this->session->userdata('usertype') == 'admin')){ ?>
					<li class="<?php if($this->uri->segment(2)=='paymentmonthlycustomer'){echo 'active';}?>">
						<a href="<?php echo ADMIN_URL;?>paymentmonthlycustomer" title="Monthly Customers Bills">
							<span class="nav-link-text">Monthly Customers Bills</span>
						</a>
					</li>
					<?php } ?>

					<?php if((array_key_exists('metersearch',$roleResponsible) && ($roleResponsible['metersearch'] == 1) ) || ($this->session->userdata('usertype') == 'admin')){ ?>
					<li class="<?php if($this->uri->segment(3)=='metersearch' && $this->uri->segment(2)=='addcustomer'){echo 'active';}?>">
						<a href="<?php echo ADMIN_URL;?>addcustomer/metersearch" title="Meter Customer Search">
							<span class="nav-link-text">Meter Customer Search</span>
						</a>
					</li>
					<?php } ?>

					<?php if((array_key_exists('monthlysearch',$roleResponsible) && ($roleResponsible['monthlysearch'] == 1) ) || ($this->session->userdata('usertype') == 'admin')){ ?>
					<li class="<?php if($this->uri->segment(3)=='monthlysearch' && $this->uri->segment(2)=='addcustomer'){echo 'active';}?>">
						<a href="<?php echo ADMIN_URL;?>addcustomer/monthlysearch" title="Monthly Customer Search">
							<span class="nav-link-text">Monthly Customer Search</span>
						</a>
					</li>
					<?php } ?>

					<?php if((array_key_exists('generatemetercustomer_search',$roleResponsible) && ($roleResponsible['generatemetercustomer_search'] == 1) ) || ($this->session->userdata('usertype') == 'admin')){ ?>
					<li class="<?php if($this->uri->segment(3)=='generatemetercustomer_search' && $this->uri->segment(2)=='addcustomer') echo 'active';?>">
						<a href="<?php echo ADMIN_URL;?>addcustomer/generatemetercustomer_search/" title="Both Type Of Customers">
							<span class="nav-link-text">Both Type Of Customers</span>
						</a>
					</li>
					<?php } ?>

					<?php if((array_key_exists('income_reportsearch',$roleResponsible) && ($roleResponsible['income_reportsearch'] == 1) ) || ($this->session->userdata('usertype') == 'admin')){ ?>
					<li class="<?php if($this->uri->segment(3)=='income_reportsearch' && $this->uri->segment(2)=='addcustomer') echo 'active';?>">
						<a href="<?php echo ADMIN_URL;?>addcustomer/income_reportsearch/" title="Income Report search">
							<span class="nav-link-text">Income Report search</span>
						</a>
					</li>
					<?php } ?>

					<?php if((array_key_exists('paidsearch',$roleResponsible) && ($roleResponsible['paidsearch'] == 1) ) || ($this->session->userdata('usertype') == 'admin')){ ?>
					<li class="<?php if($this->uri->segment(3)=='paidsearch' && $this->uri->segment(2)=='addcustomer') echo 'active';?>">
						<a href="<?php echo ADMIN_URL;?>addcustomer/paidsearch/" title="Paid Search">
							<span class="nav-link-text">Paid Search</span>
						</a>
					</li>
					<?php } ?>

					<?php if((array_key_exists('unpaidsearch',$roleResponsible) && ($roleResponsible['unpaidsearch'] == 1) ) || ($this->session->userdata('usertype') == 'admin')){ ?>
					<li class="<?php if($this->uri->segment(3)=='unpaidsearch' && $this->uri->segment(2)=='addcustomer') echo 'active';?>">
						<a href="<?php echo ADMIN_URL;?>addcustomer/unpaidsearch/" title="Unpaid Search">
							<span class="nav-link-text">Unpaid Search</span>
						</a>
					</li>
					<?php } ?>
				</ul>
			</li>

			<?php // Transaction, Journal Voucher, Trial Balance (standalone) — commented out in original ?>

			<?php if((array_key_exists('addemployee',$roleResponsible) && ($roleResponsible['addemployee'] == 1) ) || ($this->session->userdata('usertype') == 'admin')){ ?>
			<li class="<?php if($this->uri->segment(2)=='addemployee'){echo 'active open';}?>
						   <?php if($this->uri->segment(2)=='payrols'){echo 'active open';}?>
						   <?php if($this->uri->segment(3)=='payrolssearch'){echo 'active open';}?>
						    <?php if($this->uri->segment(2)=='job_title'){echo 'active open';}?>">
				<a href="#" title="Employee">
					<i class="fal fa-user"></i>
					<span class="nav-link-text">Employee</span>
				</a>
				<ul>
					<li class="<?php if($this->uri->segment(2)=='addemployee'){echo 'active';}?>">
						<a href="<?php echo ADMIN_URL;?>addemployee" title="Add Employee">
							<span class="nav-link-text">Add Employee</span>
						</a>
					</li>
					<li class="<?php if($this->uri->segment(2)=='job_title'){echo 'active';}?>">
						<a href="<?php echo ADMIN_URL;?>job_title" title="Job Title Names">
							<span class="nav-link-text">Job Title Names</span>
						</a>
					</li>
					<?php // Payrols / Payrols Search — commented out in original ?>
				</ul>
			</li>
			<?php } ?>

			<?php // Share Holder — commented out in original ?>

			<?php if((array_key_exists('addexpenses',$roleResponsible) && ($roleResponsible['addexpenses'] == 1) ) || ($this->session->userdata('usertype') == 'admin')){ ?>
			<li class="<?php if($this->uri->segment(2)=='addexpenses'){echo 'active open';}?>
						   <?php if($this->uri->segment(2)=='bsearch'){echo 'active open';}?>">
				<a href="#" title="Expenses">
					<i class="fal fa-edit"></i>
					<span class="nav-link-text">Expenses</span>
				</a>
				<ul>
					<li class="<?php if($this->uri->segment(2)=='addexpensestype' && $this->uri->segment(2)=='addexpensestype'){echo 'active';}?>">
						<a href="<?php echo ADMIN_URL;?>addexpensestype" title="Add Expenses Type">
							<span class="nav-link-text">Add Expenses Type</span>
						</a>
					</li>
					<li class="<?php if($this->uri->segment(2)=='addexpenses' && $this->uri->segment(2)=='addexpenses'){echo 'active';}?>">
						<a href="<?php echo ADMIN_URL;?>addexpenses" title="Add Expenses">
							<span class="nav-link-text">Add Expenses</span>
						</a>
					</li>
					<li class="<?php if($this->uri->segment(3)=='bsearch' && $this->uri->segment(2)=='addexpenses') echo 'active';?>">
						<a href="<?php echo ADMIN_URL;?>addexpenses/bsearch/" title="Balance Sheet Search">
							<span class="nav-link-text">Balance Sheet Search</span>
						</a>
					</li>
				</ul>
			</li>
			<?php } ?>

			<?php
			$show_accounting_nav = ($this->session->userdata('usertype') == 'admin')
				|| (array_key_exists('addexpenses',$roleResponsible) && ($roleResponsible['addexpenses'] == 1))
				|| (array_key_exists('addledger',$roleResponsible) && ($roleResponsible['addledger'] == 1))
				|| (array_key_exists('ar_adjustment',$roleResponsible) && ($roleResponsible['ar_adjustment'] == 1));
			if ($show_accounting_nav) {
			?>
			<li class="<?php if(in_array($this->uri->segment(2), array('addaccountgroup','addsubaccountgroup','addjournalvoucher','trialbalance','aradjustment'), true)){echo 'active open';}?>">
				<a href="#" title="Accounting">
					<i class="fal fa-book"></i>
					<span class="nav-link-text">Accounting</span>
				</a>
				<ul>
					<?php if((array_key_exists('addexpenses',$roleResponsible) && ($roleResponsible['addexpenses'] == 1)) || ($this->session->userdata('usertype') == 'admin')){ ?>
					<li class="<?php if($this->uri->segment(2)=='addaccountgroup'){echo 'active';}?>">
						<a href="<?php echo ADMIN_URL;?>addaccountgroup" title="Add Account Expenses Type">
							<span class="nav-link-text">Add Account Expenses Type</span>
						</a>
					</li>
					<li class="<?php if($this->uri->segment(2)=='addsubaccountgroup'){echo 'active';}?>">
						<a href="<?php echo ADMIN_URL;?>addsubaccountgroup" title="Add Sub Account Group">
							<span class="nav-link-text">Add Sub Account Group</span>
						</a>
					</li>
					<li class="<?php if($this->uri->segment(2)=='addjournalvoucher') echo 'active';?>">
						<a href="<?php echo ADMIN_URL;?>addjournalvoucher" title="Journal Voucher">
							<span class="nav-link-text">Journal Voucher</span>
						</a>
					</li>
					<li class="<?php if($this->uri->segment(2)=='trialbalance') echo 'active';?>">
						<a href="<?php echo ADMIN_URL;?>trialbalance" title="Trial Balance">
							<span class="nav-link-text">Trial Balance</span>
						</a>
					</li>
					<li>
						<a href="<?php echo ADMIN_URL;?>addledger/reportdisplay" title="Journal Report">
							<span class="nav-link-text">Journal Report</span>
						</a>
					</li>
					<?php } ?>
					<?php if((array_key_exists('ar_adjustment',$roleResponsible) && ($roleResponsible['ar_adjustment'] == 1)) || ($this->session->userdata('usertype') == 'admin')){ ?>
					<li class="<?php if($this->uri->segment(2)=='aradjustment') echo 'active';?>">
						<a href="<?php echo ADMIN_URL;?>aradjustment" title="AR Adjustment">
							<span class="nav-link-text">AR Adjustment</span>
						</a>
					</li>
					<?php } ?>
				</ul>
			</li>
			<?php } ?>

			<li class="<?php if($this->uri->segment(2)=='reports' || $this->uri->segment(2)=='customerbalancemonitor'){echo 'active open';}?>">
				<a href="#" title="Reports">
					<i class="fal fa-edit"></i>
					<span class="nav-link-text">Reports</span>
				</a>
				<ul>
					<?php if((array_key_exists('adddailyreport',$roleResponsible) && ($roleResponsible['adddailyreport'] == 1)) || ($this->session->userdata('usertype') == 'admin') || ($this->session->userdata('usertype') == 'subadmin') ){ ?>
					<li class="<?php if($this->uri->segment(2)=='adddailyreport') echo 'active';?>">
						<a href="<?php echo ADMIN_URL;?>adddailyreport" title="Daily Report">
							<span class="nav-link-text">Daily Report</span>
						</a>
					</li>
					<?php } ?>

					<?php if((array_key_exists('adddailyreport',$roleResponsible) && ($roleResponsible['adddailyreport'] == 1)) || ($this->session->userdata('usertype') == 'admin') || ($this->session->userdata('usertype') == 'subadmin') ){ ?>
					<li class="<?php if($this->uri->segment(2)=='adddailyreportnogrouping') echo 'active';?>">
						<a href="<?php echo ADMIN_URL;?>adddailyreportnogrouping" title="Daily Report - No grouping">
							<span class="nav-link-text">Daily Report - No grouping</span>
						</a>
					</li>
					<?php } ?>

					<?php if((array_key_exists('adddailyreport',$roleResponsible) && ($roleResponsible['adddailyreport'] == 1)) || ($this->session->userdata('usertype') == 'admin') || ($this->session->userdata('usertype') == 'subadmin') ){ ?>
					<li class="<?php if($this->uri->segment(2)=='reports' && $this->uri->segment(3)=='monthly_billing_report') echo 'active';?>">
						<a href="<?php echo ADMIN_URL;?>reports/monthly_billing_report" title="Monthly Billing Report">
							<span class="nav-link-text">Monthly Billing Report</span>
						</a>
					</li>
					<?php } ?>

					<?php if((array_key_exists('lowtonoconsumption',$roleResponsible) && ($roleResponsible['lowtonoconsumption'] == 1)) || ($this->session->userdata('usertype') == 'admin') ){ ?>
					<li class="<?php if($this->uri->segment(2)=='reports' && $this->uri->segment(3)=='low_to_no_consumption') echo 'active';?>">
						<a href="<?php echo ADMIN_URL;?>reports/low_to_no_consumption" title="Low to No Consumption">
							<span class="nav-link-text">Low to No Consumption</span>
						</a>
					</li>
					<?php } ?>

					<?php if((array_key_exists('adddailyreport',$roleResponsible) && ($roleResponsible['adddailyreport'] == 1)) || ($this->session->userdata('usertype') == 'admin') || ($this->session->userdata('usertype') == 'subadmin') ){ ?>
					<li class="<?php if($this->uri->segment(2)=='reports' && $this->uri->segment(3)=='customer_report') echo 'active';?>">
						<a href="<?php echo ADMIN_URL;?>reports/customer_report" title="Customer Report">
							<span class="nav-link-text">Customer Report</span>
						</a>
					</li>
					<?php } ?>

					<?php if((array_key_exists('adddailyreport',$roleResponsible) && ($roleResponsible['adddailyreport'] == 1)) || ($this->session->userdata('usertype') == 'admin') || ($this->session->userdata('usertype') == 'subadmin') ){ ?>
					<li class="<?php if($this->uri->segment(2)=='reports' && $this->uri->segment(3)=='aging_ar_report') echo 'active';?>">
						<a href="<?php echo ADMIN_URL;?>reports/aging_ar_report" title="Aging A/R Report">
							<span class="nav-link-text">Aging A/R Report</span>
						</a>
					</li>
					<?php } ?>

					<?php if((array_key_exists('adddailyreport',$roleResponsible) && ($roleResponsible['adddailyreport'] == 1)) || ($this->session->userdata('usertype') == 'admin') || ($this->session->userdata('usertype') == 'subadmin') ){ ?>
					<li class="<?php if($this->uri->segment(2)=='reports' && $this->uri->segment(3)=='arrears_monitoring_report') echo 'active';?>">
						<a href="<?php echo ADMIN_URL;?>reports/arrears_monitoring_report" title="Arrears Monitoring">
							<span class="nav-link-text">Arrears Monitoring</span>
						</a>
					</li>
					<?php } ?>

					<?php if((array_key_exists('adddailyreport',$roleResponsible) && ($roleResponsible['adddailyreport'] == 1)) || ($this->session->userdata('usertype') == 'admin') || ($this->session->userdata('usertype') == 'subadmin') ){ ?>
					<li class="<?php if($this->uri->segment(2)=='reports' && $this->uri->segment(3)=='customer_payment_monitoring_report') echo 'active';?>">
						<a href="<?php echo ADMIN_URL;?>reports/customer_payment_monitoring_report" title="Customer Payment Monitoring">
							<span class="nav-link-text">Customer Payment Monitoring</span>
						</a>
					</li>
					<?php } ?>

					<?php if((array_key_exists('customerbalancemonitor',$roleResponsible) && ($roleResponsible['customerbalancemonitor'] == 1)) || ($this->session->userdata('usertype') == 'admin') ){ ?>
					<li class="<?php if($this->uri->segment(2)=='customerbalancemonitor') echo 'active';?>">
						<a href="<?php echo ADMIN_URL;?>customerbalancemonitor" title="Customer Balance Monitor">
							<span class="nav-link-text">Customer Balance Monitor</span>
						</a>
					</li>
					<?php } ?>

					<?php if((array_key_exists('adddailyreport',$roleResponsible) && ($roleResponsible['adddailyreport'] == 1)) || ($this->session->userdata('usertype') == 'admin') || ($this->session->userdata('usertype') == 'subadmin') ){ ?>
					<li class="<?php if($this->uri->segment(2)=='reports' && $this->uri->segment(3)=='monthly_income_report_analytic') echo 'active';?>">
						<a href="<?php echo ADMIN_URL;?>reports/monthly_income_report_analytic" title="Monthly Income Report Analytic">
							<span class="nav-link-text">Monthly Income Report Analytic</span>
						</a>
					</li>
					<?php } ?>
					<?php // Leaking Report — commented out in original ?>
				</ul>
			</li>

			<?php if((array_key_exists('addassets',$roleResponsible) && ($roleResponsible['addassets'] == 1) ) || ($this->session->userdata('usertype') == 'admin')){ ?>
			<li class="<?php if($this->uri->segment(2)=='addassets') echo 'active';?>">
				<a href="<?php echo ADMIN_URL;?>addassets" title="Assets">
					<i class="fal fa-edit"></i>
					<span class="nav-link-text">Assets</span>
				</a>
			</li>
			<?php } ?>

			<?php if((array_key_exists('addledger',$roleResponsible) && ($roleResponsible['addledger'] == 1) ) || ($this->session->userdata('usertype') == 'admin')){ ?>
			<li class="<?php if($this->uri->segment(2)=='addledger') echo 'active';?>">
				<a href="<?php echo ADMIN_URL;?>addledger" title="Ledger">
					<i class="fal fa-edit"></i>
					<span class="nav-link-text">Ledger</span>
				</a>
			</li>
			<?php } ?>

			<?php if((array_key_exists('technicalproblems',$roleResponsible) && ($roleResponsible['technicalproblems'] == 1) ) || ($this->session->userdata('usertype') == 'admin')){ ?>
			<li class="<?php if($this->uri->segment(2)=='technicalproblems'){echo 'active';}?>">
				<a href="<?php echo ADMIN_URL;?>technicalproblems" title="Technical Problems">
					<i class="fal fa-gavel"></i>
					<span class="nav-link-text">Technical Problems</span>
				</a>
			</li>
			<?php } ?>
			<?php // Technical Problems View — commented out in original ?>

			<?php if((array_key_exists('web_settings',$roleResponsible) && ($roleResponsible['web_settings'] == 1) ) || ($this->session->userdata('usertype') == 'admin')){ ?>
			<li class="<?php if($this->uri->segment(2)=='web_settings') echo 'active';?>">
				<a href="<?php echo ADMIN_URL;?>web_settings" title="Admin Address">
					<i class="fal fa-map-marker-alt"></i>
					<span class="nav-link-text">Admin Address</span>
				</a>
			</li>
			<?php } ?>

			<?php if((array_key_exists('mobile_notifications',$roleResponsible) && ($roleResponsible['mobile_notifications'] == 1) ) || ($this->session->userdata('usertype') == 'admin')){ ?>
			<li class="<?php if($this->uri->segment(2)=='mobilenotifications'){echo 'active open';}?>">
				<a href="#" title="Mobile Notifications">
					<i class="fal fa-mobile"></i>
					<span class="nav-link-text">Mobile Notifications</span>
				</a>
				<ul>
					<li class="<?php if($this->uri->segment(2)=='mobilenotifications' && ($this->uri->segment(3)=='' || $this->uri->segment(3)=='index')) echo 'active';?>">
						<a href="<?php echo ADMIN_URL;?>mobilenotifications/" title="Notifications">
							<span class="nav-link-text">Notifications</span>
						</a>
					</li>
					<li class="<?php if($this->uri->segment(2)=='mobilenotifications' && $this->uri->segment(3)=='settings') echo 'active';?>">
						<a href="<?php echo ADMIN_URL;?>mobilenotifications/settings" title="Settings">
							<span class="nav-link-text">Settings</span>
						</a>
					</li>
					<li class="<?php if($this->uri->segment(2)=='mobilenotifications' && $this->uri->segment(3)=='dashboard') echo 'active';?>">
						<a href="<?php echo ADMIN_URL;?>mobilenotifications/dashboard" title="Analytics Dashboard">
							<span class="nav-link-text">Analytics Dashboard</span>
						</a>
					</li>
				</ul>
			</li>
			<?php } ?>

			<?php if((array_key_exists('admin',$roleResponsible) && ($roleResponsible['admin'] == 1) ) || ($this->session->userdata('usertype') == 'admin')){ ?>
			<li class="<?php if($this->uri->segment(2)=='responsibilities'){echo 'active open';}?>
							<?php if($this->uri->segment(2)=='addbillingperiod'){echo 'active open';}?>
							<?php if($this->uri->segment(2)=='createbalanceforward'){echo 'active open';}?>
						   <?php if($this->uri->segment(2)=='employee_logins'){echo 'active open';}?>
						   <?php if($this->uri->segment(2)=='system_activity'){echo 'active open';}?>
						   <?php if($this->uri->segment(2)=='manual_or_series'){echo 'active open';}?>
						   <?php if($this->uri->segment(2)=='mobilenotifications'){echo 'active open';}?>
						   <?php if($this->uri->segment(2)=='classification_category'){echo 'active open';}?>
						   <?php if($this->uri->segment(2)=='classification'){echo 'active open';}?>
						   <?php if($this->uri->segment(2)=='amountrate'){echo 'active open';}?>
						   <?php if($this->uri->segment(2)=='Leakingentrycorrection'){echo 'active open';}?>">
				<a href="#" title="Admin">
					<i class="fal fa-user"></i>
					<span class="nav-link-text">Admin</span>
				</a>
				<ul>
					<?php if((array_key_exists('responsibilities',$roleResponsible) && ($roleResponsible['responsibilities'] == 1) ) || ($this->session->userdata('usertype') == 'admin')){ ?>
					<li class="<?php if($this->uri->segment(2)=='responsibilities' && $this->uri->segment(2)=='responsibilities'){echo 'active';}?>">
						<a href="<?php echo ADMIN_URL;?>responsibilities" title="Roles & Responsibilities">
							<span class="nav-link-text">Roles & Responsibilities</span>
						</a>
					</li>
					<?php } ?>

					<?php if((array_key_exists('employee_logins',$roleResponsible) && ($roleResponsible['employee_logins'] == 1) ) || ($this->session->userdata('usertype') == 'admin')){ ?>
					<li class="<?php if($this->uri->segment(2)=='employee_logins' && $this->uri->segment(2)=='employee_logins') echo 'active';?>">
						<a href="<?php echo ADMIN_URL;?>employee_logins/" title="Employee Login">
							<span class="nav-link-text">Employee Login</span>
						</a>
					</li>
					<?php } ?>

					<?php if((array_key_exists('system_activity',$roleResponsible) && ($roleResponsible['system_activity'] == 1 || (is_array($roleResponsible['system_activity']) && count($roleResponsible['system_activity']) > 0) || (is_string($roleResponsible['system_activity']) && $roleResponsible['system_activity'] !== '' && $roleResponsible['system_activity'] !== '0')) ) || ($this->session->userdata('usertype') == 'admin')){ ?>
					<li class="<?php if($this->uri->segment(2)=='system_activity') echo 'active';?>">
						<a href="<?php echo ADMIN_URL;?>system_activity/" title="System Activity">
							<span class="nav-link-text">System Activity</span>
						</a>
					</li>
					<?php } ?>

					<?php if((array_key_exists('manual_or_series',$roleResponsible) && ($roleResponsible['manual_or_series'] == 1 || (is_array($roleResponsible['manual_or_series']) && count($roleResponsible['manual_or_series']) > 0)) ) || ($this->session->userdata('usertype') == 'admin')){ ?>
					<li class="<?php if($this->uri->segment(2)=='manual_or_series') echo 'active';?>">
						<a href="<?php echo ADMIN_URL;?>manual_or_series/" title="Manual OR Series">
							<span class="nav-link-text">Manual OR Series</span>
						</a>
					</li>
					<?php } ?>

					<?php if((array_key_exists('addbillingperiod',$roleResponsible) && ($roleResponsible['addbillingperiod'] == 1) ) || ($this->session->userdata('usertype') == 'admin')){ ?>
					<li class="<?php if($this->uri->segment(2)=='addbillingperiod' && $this->uri->segment(2)=='addbillingperiod') echo 'active';?>">
						<a href="<?php echo ADMIN_URL;?>addbillingperiod/" title="Setup Schedule Billing Period">
							<span class="nav-link-text">Setup Schedule Billing Period</span>
						</a>
					</li>
					<?php } ?>

					<?php if((array_key_exists('createbalanceforward',$roleResponsible) && ($roleResponsible['createbalanceforward'] == 1) ) || ($this->session->userdata('usertype') == 'admin')){ ?>
					<li class="<?php if($this->uri->segment(2)=='createbalanceforward' && $this->uri->segment(2)=='createbalanceforward') echo 'active';?>">
						<a href="<?php echo ADMIN_URL;?>createbalanceforward/" title="Create Balance Forward">
							<span class="nav-link-text">Create Balance Forward</span>
						</a>
					</li>
					<?php } ?>

					<?php if((array_key_exists('or_correction',$roleResponsible) && ($roleResponsible['or_correction'] == 1) ) || ($this->session->userdata('usertype') == 'admin')){ ?>
					<li class="<?php if($this->uri->segment(2)=='or_correction' && $this->uri->segment(2)=='or_correction'){echo 'active';}?>">
						<a href="<?php echo ADMIN_URL;?>or_correction/" title="OR Correction">
							<span class="nav-link-text">OR Correction</span>
						</a>
					</li>
					<?php } ?>

					<?php if((array_key_exists('meter_reading_correction',$roleResponsible) && ($roleResponsible['meter_reading_correction'] == 1) ) || ($this->session->userdata('usertype') == 'admin')){ ?>
					<li class="<?php if($this->uri->segment(2)=='addmetercustomerreading' && $this->uri->segment(3)=='edit'){echo 'active';}?>">
						<a href="<?php echo ADMIN_URL;?>addmetercustomerreading/edit" title="Meter Reading Correction">
							<span class="nav-link-text">Meter Reading Correction</span>
						</a>
					</li>
					<?php } ?>

					<?php if((array_key_exists('leaking_entry_correction',$roleResponsible) && ($roleResponsible['leaking_entry_correction'] == 1) ) || ($this->session->userdata('usertype') == 'admin')){ ?>
					<li class="<?php if($this->uri->segment(2)=='Leakingentrycorrection'){echo 'active';}?>">
						<a href="<?php echo ADMIN_URL;?>Leakingentrycorrection" title="Leaking Entry Correction">
							<span class="nav-link-text">Leaking Entry Correction</span>
						</a>
					</li>
					<?php } ?>

					<?php if((array_key_exists('database_backup',$roleResponsible) && ($roleResponsible['database_backup'] == 1 || (is_array($roleResponsible['database_backup']) && count($roleResponsible['database_backup']) > 0)) ) || ($this->session->userdata('usertype') == 'admin')){ ?>
					<li class="<?php if($this->uri->segment(2)=='database_backup') echo 'active';?>">
						<a href="<?php echo ADMIN_URL;?>database_backup/" title="Database Backup">
							<span class="nav-link-text">Database Backup</span>
						</a>
					</li>
					<?php } ?>

					<?php if((array_key_exists('classification_category',$roleResponsible) && ($roleResponsible['classification_category'] == 1) ) || ($this->session->userdata('usertype') == 'admin')){ ?>
					<li class="<?php if($this->uri->segment(2)=='classification_category') echo 'active';?>">
						<a href="<?php echo ADMIN_URL;?>classification_category/" title="Classification Category">
							<span class="nav-link-text">Classification Category</span>
						</a>
					</li>
					<?php } ?>

					<?php if((array_key_exists('classification',$roleResponsible) && ($roleResponsible['classification'] == 1) ) || ($this->session->userdata('usertype') == 'admin')){ ?>
					<li class="<?php if($this->uri->segment(2)=='classification') echo 'active';?>">
						<a href="<?php echo ADMIN_URL;?>classification/" title="Classification">
							<span class="nav-link-text">Classification</span>
						</a>
					</li>
					<?php } ?>

					<?php if((array_key_exists('amountrate',$roleResponsible) && ($roleResponsible['amountrate'] == 1) ) || ($this->session->userdata('usertype') == 'admin')){ ?>
					<li class="<?php if($this->uri->segment(2)=='amountrate'){echo 'active';}?>">
						<a href="<?php echo ADMIN_URL;?>amountrate" title="Per Unit Value">
							<span class="nav-link-text">Per Unit Value</span>
						</a>
					</li>
					<?php } ?>
				</ul>
			</li>
			<?php } ?>

			<li class="<?php if($this->uri->segment(2)=='change_username'){echo 'active open';}?>
						   <?php if($this->uri->segment(2)=='change_password'){echo 'active open';}?>
						   <?php if($this->uri->segment(2)=='global_settings'){echo 'active open';}?>">
				<a href="#" title="Setting">
					<i class="fal fa-key"></i>
					<span class="nav-link-text">Setting</span>
				</a>
				<ul>
					<?php
					if($this->session->userdata('usertype') == 'admin'){
					?>
					<li class="<?php if($this->uri->segment(2)=='global_settings') echo 'active';?>">
						<a href="<?php echo ADMIN_URL;?>global_settings" title="Global Settings">
							<span class="nav-link-text">Global Settings</span>
						</a>
					</li>
					<li class="<?php if($this->uri->segment(3)=='adminconfiguration') echo 'active';?>">
						<a href="<?php echo ADMIN_URL;?>addcustomer/adminconfiguration" title="Admin Configuration">
							<span class="nav-link-text">Admin Configuration</span>
						</a>
					</li>
					<?php
					}
					?>
					<li class="<?php if($this->uri->segment(2)=='change_username' && $this->uri->segment(2)=='change_username'){echo 'active';}?>">
						<a href="<?php echo ADMIN_URL;?>change_username/" title="Change Username">
							<span class="nav-link-text">Change Username</span>
						</a>
					</li>
					<li class="<?php if($this->uri->segment(2)=='change_password' && $this->uri->segment(2)=='change_password') echo 'active';?>">
						<a href="<?php echo ADMIN_URL;?>change_password/" title="Change Password">
							<span class="nav-link-text">Change Password</span>
						</a>
					</li>
				</ul>
			</li>
		</ul>
		<div class="filter-message js-filter-message bg-success-600"></div>
	</nav>
	<div class="nav-footer shadow-top">
		<a href="#" onclick="return false;" data-action="toggle" data-class="nav-function-minify" class="hidden-md-down">
			<i class="ni ni-chevron-right"></i>
			<i class="ni ni-chevron-right"></i>
		</a>
	</div>
</aside>
