<!doctype html>
<html lang="en" dir="ltr">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="author" content="GeniusOcean">
	<meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
	<!-- Title -->
	<title><?php echo e($gs->title); ?></title>
	<!-- favicon -->
	<link rel="icon" type="image/x-icon" href="<?php echo e(asset('assets/images/'.$gs->favicon)); ?>" />
	<!-- Bootstrap -->
	<link href="<?php echo e(asset('assets/admin/css/bootstrap.min.css')); ?>" rel="stylesheet" />
	<!-- Fontawesome -->
	<link rel="stylesheet" href="<?php echo e(asset('assets/admin/css/fontawesome.css')); ?>">
	<!-- icofont -->
	<link rel="stylesheet" href="<?php echo e(asset('assets/admin/css/icofont.min.css')); ?>">
	<!-- Sidemenu Css -->
	<link href="<?php echo e(asset('assets/admin/plugins/fullside-menu/css/dark-side-style.css')); ?>" rel="stylesheet" />
	<link href="<?php echo e(asset('assets/admin/plugins/fullside-menu/waves.min.css')); ?>" rel="stylesheet" />

	<link href="<?php echo e(asset('assets/admin/css/plugin.css')); ?>" rel="stylesheet" />

	<link href="<?php echo e(asset('assets/admin/css/jquery.tagit.css')); ?>" rel="stylesheet" />
	<link rel="stylesheet" href="<?php echo e(asset('assets/admin/css/bootstrap-coloroicker.css')); ?>">
	<!-- Main Css -->
	
	 <script src="https://js.pusher.com/7.0/pusher.min.js"></script>

	<!-- stylesheet -->
	<?php if(DB::table('admin_languages')->where('is_default','=',1)->first()->rtl == 1): ?>

	<link href="<?php echo e(asset('assets/admin/css/rtl/style.css')); ?>" rel="stylesheet" />
	<link href="<?php echo e(asset('assets/admin/css/rtl/custom.css')); ?>" rel="stylesheet" />
	<link href="<?php echo e(asset('assets/admin/css/rtl/responsive.css')); ?>" rel="stylesheet" />
	<link href="<?php echo e(asset('assets/admin/css/common.css')); ?>" rel="stylesheet" />

	<?php else: ?>

	<link href="<?php echo e(asset('assets/admin/css/style.css')); ?>" rel="stylesheet" />
	<link href="<?php echo e(asset('assets/admin/css/custom.css')); ?>" rel="stylesheet" />
	<link href="<?php echo e(asset('assets/admin/css/responsive.css')); ?>" rel="stylesheet" />
	<link href="<?php echo e(asset('assets/admin/css/common.css')); ?>" rel="stylesheet" />
	<?php endif; ?>
	<?php echo $__env->yieldContent('styles'); ?>
</head>

<body>
	<div class="page">
		<div class="page-main">
			<!-- Header Menu Area Start -->
			<div class="header">
				<div class="container-fluid">
					<div class="d-flex mobile-menu-check justify-content-between">
						<a class="admin-logo" href="<?php echo e(route('front.index')); ?>" target="_blank">
							<img src="<?php echo e(asset('assets/images/'.$gs->logo)); ?>" alt="">
						</a>
						<div class="menu-toggle-button">
							<a class="nav-link" href="javascript:;" id="sidebarCollapse">
								<div class="my-toggl-icon">
									<span class="bar1"></span>
									<span class="bar2"></span>
									<span class="bar3"></span>
								</div>
							</a>
						</div>
						<div class="right-eliment">
							<ul class="list">
								<input type="hidden" id="all_notf_count"
									value="<?php echo e(route('vendor-order-notf-count',Auth::user()->id)); ?>">
								<li class="bell-area">
									<a id="notf_order" class="dropdown-toggle-1" href="javascript:;">
										<i class="icofont-cart"></i>
										<span id="order-notf-count"><?php echo e(App\Models\UserNotification::countOrder(Auth::user()->id)); ?></span>
									</a>
									<div class="dropdown-menu">
										<div class="dropdownmenu-wrapper"
											data-href="<?php echo e(route('vendor-order-notf-show',Auth::user()->id)); ?>"
											id="order-notf-show">
										</div>
									</div>
								</li>
								<li class="login-profile-area">
									<a class="dropdown-toggle-1" href="javascript:;">
										<div class="user-img">
											<?php if(Auth::user()->is_provider == 1): ?>
											<img src="<?php echo e(Auth::user()->photo ? asset(Auth::user()->photo):asset('assets/images/noimage.png')); ?>"
												alt="">
											<?php else: ?>
											<img src="<?php echo e(Auth::user()->photo ? asset('assets/images/users/'.Auth::user()->photo ):asset('assets/images/noimage.png')); ?>"
												alt="">
											<?php endif; ?>
										</div>
									</a>
									<div class="dropdown-menu">
										<div class="dropdownmenu-wrapper">
											<ul>
												<h5><?php echo e(__('Welcome!')); ?></h5>
												<li>
													<a target="_blank"
														href="<?php echo e(route('front.vendor',str_replace(' ', '-', Auth::user()->shop_name))); ?>"><i
															class="fas fa-eye"></i> <?php echo e(__('Visit Store')); ?></a>
												</li>
												<li>
													<a href="<?php echo e(route('user-dashboard')); ?>"><i
															class="fas fa-sign-in-alt"></i> <?php echo e(__('User Panel')); ?></a>
												</li>
												<li>
													<a href="<?php echo e(route('vendor-profile')); ?>"><i class="fas fa-user"></i>
														<?php echo e(__('Edit Profile')); ?></a>
												</li>
												<li>
													<a href="<?php echo e(route('user-logout')); ?>"><i
															class="fas fa-power-off"></i> <?php echo e(__('Logout')); ?></a>
												</li>
											</ul>
										</div>
									</div>
								</li>
							</ul>
						</div>
					</div>
				</div>
			</div>
			<!-- Header Menu Area End -->
			<div class="wrapper">
				<!-- Side Menu Area Start -->
				<nav id="sidebar" class="nav-sidebar">
					<ul class="list-unstyled components" id="accordion">
						<li>
							<a target="_blank"
								href="<?php echo e(route('front.vendor',str_replace(' ', '-', Auth::user()->shop_name))); ?>"
								class="wave-effect"><i class="fas fa-eye mr-2"></i> <?php echo e(__('Visit Store')); ?></a>
						</li>
						<li>
							<a href="<?php echo e(route('vendor.dashboard')); ?>" class="wave-effect"><i
									class="fa fa-home mr-2"></i><?php echo e(__('Dashboard')); ?></a>
						</li>
						<li>
							<a href="<?php echo e(route('vendor.delivery.index')); ?>" class="wave-effect"><i
									class="fas fa-shipping-fast"></i><?php echo e(__('Delivery')); ?></a>
						</li>
						<li>
							<a href="<?php echo e(route('vendor.live-messages')); ?>" class="wave-effect"><i
									class="fa fa-newspaper mr-2"></i><?php echo e(__('Messages')); ?></a>
						</li>
						<li>
							<a href="#order" class="accordion-toggle wave-effect" data-toggle="collapse"
								aria-expanded="false"><i class="fas fa-hand-holding-usd"></i><?php echo e(__('Orders')); ?></a>
							<ul class="collapse list-unstyled" id="order" data-parent="#accordion">
								<li>
									<a href="<?php echo e(route('vendor-order-index')); ?>"> <?php echo e(__('All Orders')); ?></a>
								</li>
							</ul>
						</li>
						<li>
							<a href="#menu2" class="accordion-toggle wave-effect" data-toggle="collapse"
								aria-expanded="false">
								<i class="icofont-cart"></i><?php echo e(__('Products')); ?>

							</a>
							<ul class="collapse list-unstyled" id="menu2" data-parent="#accordion">
								<li>
									<a href="<?php echo e(route('vendor-prod-types')); ?>"><span><?php echo e(__('Add New Product')); ?></span></a>
								</li>
								<li>
									<a href="<?php echo e(route('vendor-prod-index')); ?>"><span><?php echo e(__('All Products')); ?></span></a>
								</li>
								<li>
									<a href="<?php echo e(route('admin-vendor-catalog-index')); ?>"><span><?php echo e(__('Product Catalogs')); ?></span></a>
								</li>
							</ul>
						</li>
						<?php if($gs->affilite == 1): ?>
						<li>
							<a href="#affiliateprod" class="accordion-toggle wave-effect" data-toggle="collapse"
								aria-expanded="false">
								<i class="icofont-cart"></i><?php echo e(__('Affiliate Products')); ?>

							</a>
							<ul class="collapse list-unstyled" id="affiliateprod" data-parent="#accordion">
								<li>
									<a href="<?php echo e(route('vendor-import-create')); ?>"><span><?php echo e(__('Add Affiliate Product')); ?></span></a>
								</li>
								<li>
									<a href="<?php echo e(route('vendor-import-index')); ?>"><span><?php echo e(__('All Affiliate Products')); ?></span></a>
								</li>
							</ul>
						</li>
						<?php endif; ?>
						<li>
							<a href="<?php echo e(route('vendor-prod-import')); ?>"><i class="fas fa-upload"></i><?php echo e(__('Bulk Product
								Upload')); ?></a>
						</li>
						<li>
							<a href="<?php echo e(route('vendor-wt-index')); ?>" class=" wave-effect"><i class="fas fa-list"></i><?php echo e(__('Withdraws')); ?></a>
						</li>
						<li>
							<a href="#general" class="accordion-toggle wave-effect" data-toggle="collapse"
								aria-expanded="false">
								<i class="fas fa-cogs"></i><?php echo e(__('Settings')); ?>

							</a>
							<ul class="collapse list-unstyled" id="general" data-parent="#accordion">
								
								<?php if($gs->vendor_ship_info == 1): ?>
								<li>
									<a href="<?php echo e(route('vendor-shipping-index')); ?>"><span><?php echo e(__('Shipping Methods')); ?></span></a>
								</li>


								<li>
									<a href="<?php echo e(route('vendor-package-index')); ?>"><span><?php echo e(__('Packagings')); ?></span></a>
								</li>

								<?php endif; ?>
								<li>
									<a href="<?php echo e(route('vendor-sociallink-index')); ?>"><span><?php echo e(__('Social Links')); ?></span></a>
								</li>
								
								<li>
									<a href="<?php echo e(route('vendor-pickup-point-index')); ?>"><span><?php echo e(__('Pickup Point')); ?></span></a>
								</li>
							</ul>
						</li>
						<li>
							<a href="<?php echo e(route('vendor.income')); ?>" class="wave-effect"><i class="fas fa-eye mr-2"></i>
								<?php echo e(__('Total Earning')); ?></a>
						</li>
					</ul>
				</nav>
				<!-- Main Content Area Start -->
				<?php echo $__env->yieldContent('content'); ?>
				<!-- Main Content Area End -->
			</div>
		</div>
	</div>
	<?php
	$curr = \App\Models\Currency::where('is_default','=',1)->first();
	?>
	<script type="text/javascript">
		var mainurl = "<?php echo e(url('/')); ?>";
		   var admin_loader = <?php echo e($gs->is_admin_loader); ?>;
		   var whole_sell = <?php echo e($gs->wholesell); ?>;
		   var getattrUrl = '<?php echo e(route('vendor-prod-getattributes')); ?>';
		   var curr = <?php echo json_encode($curr); ?>;
		   var lang  = {
			   'additional_price': '<?php echo e(__('0.00 (Additional Price)')); ?>'
				   };
	</script>
	<!-- Dashboard Core -->
	<script src="<?php echo e(asset('assets/admin/js/vendors/jquery-1.12.4.min.js')); ?>"></script>
	<script src="<?php echo e(asset('assets/admin/js/vendors/bootstrap.min.js')); ?>"></script>
	<script src="<?php echo e(asset('assets/admin/js/jqueryui.min.js')); ?>"></script>
	<!-- Fullside-menu Js-->
	<script src="<?php echo e(asset('assets/admin/plugins/fullside-menu/jquery.slimscroll.min.js')); ?>"></script>
	<script src="<?php echo e(asset('assets/admin/plugins/fullside-menu/waves.min.js')); ?>"></script>
	<script src="<?php echo e(asset('assets/admin/js/plugin.js')); ?>"></script>
	<script src="<?php echo e(asset('assets/admin/js/Chart.min.js')); ?>"></script>
	<script src="<?php echo e(asset('assets/admin/js/tag-it.js')); ?>"></script>
	<script src="<?php echo e(asset('assets/admin/js/nicEdit.js')); ?>"></script>
	<script src="<?php echo e(asset('assets/admin/js/bootstrap-colorpicker.min.js')); ?>"></script>
	<script src="<?php echo e(asset('assets/admin/js/notify.js')); ?>"></script>
	<script src="<?php echo e(asset('assets/admin/js/load.js')); ?>"></script>
	<!-- Custom Js-->
	<script src="<?php echo e(asset('assets/admin/js/custom.js')); ?>"></script>
	<!-- AJAX Js-->
	<script src="<?php echo e(asset('assets/admin/js/myscript.js')); ?>"></script>
	<?php echo $__env->yieldContent('scripts'); ?>
	<?php if($gs->is_admin_loader == 0): ?>
	<style>
		div#geniustable_processing {
			display: none !important;
		}
	</style>
	<?php endif; ?>
</body>

</html><?php /**PATH /home/u112990875/domains/fabilive.com/public_html/project/resources/views/layouts/vendor.blade.php ENDPATH**/ ?>