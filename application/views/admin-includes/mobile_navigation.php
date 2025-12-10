<!-- Left panel : Navigation area -->
		<!-- Note: This width of the aside area can be adjusted through LESS variables -->
		<aside id="left-panel">

			<!-- User info -->
			<div class="login-info">
				<span> <!-- User image size is adjusted inside CSS, it should stay as it --> 
					
					<a href="javascript:void(0);" id="show-shortcut" data-action="toggleShortcut">
						<img src="<?php echo base_url(); ?>/assets/avatars/avatar.png" alt="me" class="online" /> 
						<span>
							<?php echo $this->session->userdata('name');?>
						</span>
						<i class="fa fa-angle-right"></i>
					</a> 
					
				</span>
			</div>
			<!-- end user info -->

			<!-- NAVIGATION : This navigation is also responsive-->
			<nav>
				<!-- 
				NOTE: Notice the gaps after each icon usage <i></i>..
				Please note that these links work a bit different than
				traditional href="" links. See documentation for details.
				-->

				<ul>
				<?php if( ( array_key_exists('dashboard',$roleResponsible) && ($roleResponsible['dashboard'] == 1) ) || ( $this->session->userdata('usertype') == 'admin' )){ ?>
					<li class="<?php if($this->uri->segment(2)=='dashboard') echo 'active';?>">
						<a href="<?php echo ADMIN_URL;?>mobile_dashboard" title="Dashboard"><i class="fa fa-lg fa-fw fa-home"></i> <span class="menu-item-parent">Dashboard</span></a>
					</li>
				<?php } ?>	

					



					




					<!--<li class="<?php if($this->uri->segment(2)=='reports'){echo 'active open';}?>">
						<a href="#"><i class="fa fa-lg fa-fw fa-pencil-square-o"></i> <span class="menu-item-parent">Reports</span></a>
						<ul>
							<?php if((array_key_exists('adddailyreport',$roleResponsible) && ($roleResponsible['adddailyreport'] == 1)) || ($this->session->userdata('usertype') == 'admin') || ($this->session->userdata('usertype') == 'subadmin') ){ ?>
							<li class="<?php if($this->uri->segment(2)=='adddailyreport') echo 'active';?>">
								<a href="<?php echo ADMIN_URL;?>adddailyreport"> <span class="menu-item-parent">Daily Report</span></a>
							</li>	
							<?php } ?>

							<?php if((array_key_exists('adddailyreport',$roleResponsible) && ($roleResponsible['adddailyreport'] == 1)) || ($this->session->userdata('usertype') == 'admin') || ($this->session->userdata('usertype') == 'subadmin') ){ ?>
							<li class="<?php if($this->uri->segment(2)=='reports' && $this->uri->segment(3)=='monthly_billing_report') echo 'active';?>">
								<a href="<?php echo ADMIN_URL;?>reports/monthly_billing_report"> <span class="menu-item-parent">Monthly Billing Report</span></a>
							</li>	
							<?php } ?>

							<?php if((array_key_exists('adddailyreport',$roleResponsible) && ($roleResponsible['adddailyreport'] == 1)) || ($this->session->userdata('usertype') == 'admin') || ($this->session->userdata('usertype') == 'subadmin') ){ ?>
							<li class="<?php if($this->uri->segment(2)=='reports' && $this->uri->segment(3)=='aging_ar_report') echo 'active';?>">
								<a href="<?php echo ADMIN_URL;?>reports/aging_ar_report"> <span class="menu-item-parent">Aging A/R Report</span></a>
							</li>	
							<?php } ?>

							
						   
						</ul>
					</li>-->







                    
					
					<?php if((array_key_exists('technicalproblems',$roleResponsible) && ($roleResponsible['technicalproblems'] == 1) ) || ($this->session->userdata('usertype') == 'admin')){ ?>
						<li class="<?php if($this->uri->segment(2)=='technicalproblems'){echo 'active';}?>">
							<a href="#"><i class="fa fa-lg fa-fw fa-gavel"></i> <span class="menu-item-parent">Tickets </span></a>
							<ul>
								<li><a href="<?php echo ADMIN_URL;?>mobile_dashboard">Add</a></li>
								<li><a href="<?php echo ADMIN_URL;?>mobile_tickets">Listing</a></li>
							</ul>
						</li>
					<?php } ?>
					<!--<?php if((array_key_exists('technicalsearch',$roleResponsible) && ($roleResponsible['technicalsearch'] == 1) ) || ($this->session->userdata('usertype') == 'admin')){ ?>
						<li class="<?php if($this->uri->segment(3)=='technicalsearch' && $this->uri->segment(2)=='addcustomer') echo 'active';?>">
							<a href="<?php echo ADMIN_URL;?>addcustomer/technicalsearch/"><i class="glyphicon glyphicon-zoom-in"></i><span class="menu-item-parent">Technical Problems View</span></a>
						</li>	
					<?php } ?>-->
					
					<li class="<?php if($this->uri->segment(2)=='change_username'){echo 'active open';}?>
<?php if($this->uri->segment(2)=='change_password'){echo 'active open';}?>
<?php if($this->uri->segment(2)=='classification_category'){echo 'active open';}?>
<?php if($this->uri->segment(2)=='classification'){echo 'active open';}?>">
						<a href="#"><i class="fa fa-lg fa-fw fa fa-key"></i> <span class="menu-item-parent">Setting</span></a>
						<ul>
							<?php
							if($this->session->userdata('usertype') == 'admin'){
							?>
							<li class="<?php if($this->uri->segment(3)=='adminconfiguration') echo 'active';?>">
								<a href="<?php echo ADMIN_URL;?>addcustomer/adminconfiguration"> Admin Configuration</a>
							</li>
							<?php
							}
							?>
						   <li class="<?php if($this->uri->segment(2)=='change_username' && $this->uri->segment(2)=='change_username'){echo 'active';}?>">
								<a href="<?php echo ADMIN_URL;?>change_username/"> Change Username</a>
							</li>
							<li class="<?php if($this->uri->segment(2)=='change_password' && $this->uri->segment(2)=='change_password') echo 'active';?>">
								<a href="<?php echo ADMIN_URL;?>change_password/"> Change Password</a>
							</li>
							<li class="<?php if($this->uri->segment(2)=='classification_category') echo 'active';?>">
								<a href="<?php echo ADMIN_URL;?>classification_category/"> Classification Category</a>
							</li>
							<li class="<?php if($this->uri->segment(2)=='classification') echo 'active';?>">
								<a href="<?php echo ADMIN_URL;?>classification/"> Classification</a>
							</li>
						</ul>
					</li>
					</ul>
			</nav>
			

			<span class="minifyme" data-action="minifyMenu"> 
				<i class="fa fa-arrow-circle-left hit"></i> 
			</span>

		</aside>
		<!-- END NAVIGATION -->