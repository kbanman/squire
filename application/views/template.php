<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<title><?php echo $title; ?></title>
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<meta name="description" content="">
		<meta name="author" content="">
		<meta name="apple-mobile-web-app-capable" content="yes" />
		<meta name="apple-mobile-web-app-status-bar-style" content="black" />

		<link href="<?php echo URL::to_asset('bootstrap/css/bootstrap-mod.css'); ?>" rel="stylesheet">

		<style type="text/css">
			body {
				padding-top: 60px;
				padding-bottom: 40px;
			}
			.sidebar-nav {
				padding: 9px 0;
			}
			.nav .sub {
				display: none;
			}
			#search-results {
				display: none;
			}
		</style>
		<?php if ($responsive): ?>
		<link href="<?php echo URL::to_asset('bootstrap/css/responsive.css'); ?>" rel="stylesheet">
		<?php endif; ?>
		<link href="<?php echo URL::to_asset('jquery-ui-bootstrap/css/custom-theme/jquery-ui-1.8.16.custom.css'); ?>" rel="stylesheet">
		<link href="<?php echo URL::to_asset('css/squire.css'); ?>" rel="stylesheet">
		<?php echo Asset::container('header')->styles(); ?>
		
		<!-- HTML5 shim, for IE6-8 support of HTML5 elements -->
		<!--[if lt IE 9]>
			<script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
		<![endif]-->

		<!-- fav and touch icons 
		<link rel="shortcut icon" href="../assets/ico/favicon.ico">
		<link rel="apple-touch-icon-precomposed" sizes="114x114" href="../assets/ico/apple-touch-icon-114-precomposed.png">
		<link rel="apple-touch-icon-precomposed" sizes="72x72" href="../assets/ico/apple-touch-icon-72-precomposed.png">
		<link rel="apple-touch-icon-precomposed" href="../assets/ico/apple-touch-icon-57-precomposed.png">-->
		<?php echo View::make('partials.sq_js'); ?>
	</head>

	<body>
		<div class="navbar navbar-fixed-top">
			<div class="navbar-inner">
				<div class="container<?php if ($fluid) echo '-fluid'; ?>">
					<a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
					</a>
					<a class="brand" href="<?php echo URL::to('/'); ?>"><?php echo $site_name; ?></a>
					<div class="nav-collapse">
						<ul class="nav">
							<?php echo Section::yield('nav'); ?>
						</ul>
						<?php echo $search; ?>
					</div><!--/.nav-collapse -->
				</div>
			</div>
		</div>

		<div class="container<?php if ($fluid) echo '-fluid'; ?>">
			<?php if (isset($layout)): include "layout/{$layout}.php"; else: ?>
			<?php if ( ! empty($page_heading)): ?><h1><?php echo $page_heading; ?></h1><?php endif; ?>
			<?php echo $content.Section::yield('content'); ?>
			<?php endif; ?>

			<footer>
				<?php echo $footer; ?>
				<?php if (Auth::check()): ?>
				Logged in as <?php echo Auth::user()->email; ?>. <?php echo HTML::link('login/logout', 'Logout'); ?>
				<?php endif; ?>
			</footer>

		</div><!--/.fluid-container-->

		<script type="text/javascript">
			var Quill = {
				navtimers: []
			};
			
		</script>
		<script src="<?php echo URL::to_asset('js/jquery.min.js'); ?>"></script>
		<script src="<?php echo URL::to_asset('js/jquery-ui.min.js'); ?>"></script>
		<script src="<?php echo URL::to_asset('js/jquery-ui-timepicker.js'); // breaks leads_submit.js ?>"></script>
		<script src="<?php echo URL::to_asset('js/jquery-validation.js'); ?>"></script>
		<script src="<?php echo URL::to_asset('js/jquery-validation-methods.min.js'); ?>"></script>
		<script src="<?php echo URL::to_asset('bootstrap/js/bootstrap.min.js'); ?>"></script>
		<script src="<?php echo URL::to_asset('js/squire.js'); ?>"></script>
		<?php echo Asset::container('footer')->scripts(); ?>
		<?php Anbu::render(); ?>
	</body>
</html>
