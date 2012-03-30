<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title><?php echo $title; ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">

    <link href="<?php echo URL::to_asset('bootstrap/css/bootstrap-mod.css'); ?>" rel="stylesheet">

    <style type="text/css">
      body {
        padding-top: 60px;
        padding-bottom: 40px;
      }
      .sidebar-nav {
        padding: 9px 0;
      }
    </style>

    <link href="<?php echo URL::to_asset('bootstrap/css/responsive.css'); ?>" rel="stylesheet">

    <!-- HTML5 shim, for IE6-8 support of HTML5 elements -->
    <!--[if lt IE 9]>
      <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->

    <!-- fav and touch icons 
    <link rel="shortcut icon" href="../assets/ico/favicon.ico">
    <link rel="apple-touch-icon-precomposed" sizes="114x114" href="../assets/ico/apple-touch-icon-114-precomposed.png">
    <link rel="apple-touch-icon-precomposed" sizes="72x72" href="../assets/ico/apple-touch-icon-72-precomposed.png">
    <link rel="apple-touch-icon-precomposed" href="../assets/ico/apple-touch-icon-57-precomposed.png">-->
  </head>

  <body>

    <div class="navbar navbar-fixed-top">
      <div class="navbar-inner">
        <div class="container-fluid">
          <a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </a>
          <a class="brand" href="#"><?php echo $sitename; ?></a>
          <div class="nav-collapse">
            <ul class="nav">
              <?php echo Section::yield('nav'); ?>
            </ul>
            <form class="form-search pull-right">
              <input type="text" class="search-query" placeholder="Search for">
              <div id="search-results"></div>
            </form>
          </div><!--/.nav-collapse -->
        </div>
      </div>
    </div>

    <div class="container-fluid">
      <?php if (isset($layout)): include "layout/{$layout}.php"; else: ?>
      <?php echo $content.Section::yield('content'); ?>
      <?php endif; ?>

      <hr>

      <footer>
        <p>&copy; Company 2012</p>
      </footer>

    </div><!--/.fluid-container-->

    <script src="<?php echo URL::to_asset('js/jquery.min.js'); ?>"></script>
    <script src="<?php echo URL::to_asset('js/jquery-ui.min.js'); ?>"></script>
    <script src="<?php echo URL::to_asset('bootstrap/js/bootstrap-transition.js'); ?>"></script>
    <script src="<?php echo URL::to_asset('bootstrap/js/bootstrap-alert.js'); ?>"></script>
    <script src="<?php echo URL::to_asset('bootstrap/js/bootstrap-modal.js'); ?>"></script>
    <script src="<?php echo URL::to_asset('bootstrap/js/bootstrap-dropdown.js'); ?>"></script>
    <script src="<?php echo URL::to_asset('bootstrap/js/bootstrap-scrollspy.js'); ?>"></script>
    <script src="<?php echo URL::to_asset('bootstrap/js/bootstrap-tab.js'); ?>"></script>
    <script src="<?php echo URL::to_asset('bootstrap/js/bootstrap-tooltip.js'); ?>"></script>
    <script src="<?php echo URL::to_asset('bootstrap/js/bootstrap-popover.js'); ?>"></script>
    <script src="<?php echo URL::to_asset('bootstrap/js/bootstrap-button.js'); ?>"></script>
    <script src="<?php echo URL::to_asset('bootstrap/js/bootstrap-collapse.js'); ?>"></script>
    <script src="<?php echo URL::to_asset('bootstrap/js/bootstrap-carousel.js'); ?>"></script>
    <script src="<?php echo URL::to_asset('bootstrap/js/bootstrap-typeahead.js'); ?>"></script>

  </body>
</html>
