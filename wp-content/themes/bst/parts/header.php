<!DOCTYPE html>
<html>
<head>
	<title><?php is_front_page() ? bloginfo('name') : wp_title('•', true, ''); ?></title>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
	<?php wp_head(); ?>
    <link rel="shortcut icon" href="<?php echo get_stylesheet_directory_uri(); ?>/favicon.ico" />
</head>

<body <?php body_class(); ?>>

<!--[if lt IE 8]>
<div class="alert alert-warning">
	You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> to improve your experience.
</div>
<![endif]-->    

<?php
/*
Upper menubar (at screen top)
=============================
Delete this whole <nav>...</nav> block if you don't want it, and delete the line in func/navbar.php that looks like this:
register_nav_menu('upper-bar', __('Screen-top menu'));
*/
?>
<div class="container">
    <nav role="navigation" id="primary-navigation">
        <div class="navbar-header" id="logo">
            <a href="<?php echo home_url(); ?>" title="<?php echo get_bloginfo('description'); ?>">
                <img src="<?php bloginfo('template_url') ?>/img/logo-smaller.png" alt="<?php bloginfo('description'); ?>" title="<?php bloginfo('name'); ?>">
            </a>
        </div><!-- /.navbar-header -->
        <div class="navbar-right">
            <form class="navbar-form" role="search" id="searchform" action="<?php echo home_url( '/' ); ?>">
                <div class="input-group">
                     <input name="s" id="s" type="text" class="search-query form-control" autocomplete="off" placeholder="<?php _e('Search','wpbootstrap'); ?>" data-provide="typeahead" data-items="4" data-source='<?php echo $typeahead_data; ?>'>
                    <div class="input-group-btn">
                        <button type="submit" class="btn btn-default">
                            <span class="glyphicon glyphicon-search"></span>
                        </button>
                    </div>
                </div>
            </form>  
            <div class="upper-navbar">    
              <?php				
                  $args = array(
                    'theme_location' => 'upper-bar',
                    'depth' => 0,
                    'container'	=> false,
                    'fallback_cb' => false,
                    'menu_class' => 'nav navbar-nav',
                    'walker' => new BootstrapNavMenuWalker()
                  );
                  wp_nav_menu($args);
              ?>
             </div>
         </div>
    </nav>
</div>

<?php
/*
Lower menubar (main menubar, below site title)
==============================================
Delete this whole <nav>...</nav> block if you don't want it, and delete the line in func/navbar.php that looks like this:
register_nav_menu('lower-bar', __('Main menu (below site title)'));
*/
?>
  <div class="main-nav navbar navbar-inverse navbar-static" role="navigation">
    <nav class="container">
      <div class="navbar-header">
        <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".lower-navbar">
          <span class="sr-only">Toggle navigation</span>
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
        </button>
        <?php /* <a class="navbar-brand" href="<?php echo home_url('/'); ?>"><?php bloginfo('name'); ?></a> */ ?>
      </div><!-- /.navbar-header -->
      <div class="collapse navbar-collapse lower-navbar" id="menu-primary-wrapper">    
        <?php				
            $args = array(
              'theme_location' => 'lower-bar',
              'depth' => 1,
              'container'	=> false,
              'fallback_cb' => false,
              'menu_class' => 'nav nav-justified',
              'walker' => new BootstrapNavMenuWalker()
            );
            wp_nav_menu($args);
        ?>
      </div><!-- /.navbar-collapse -->
  	</nav>
  </div>
<div class="container">
<?php 
$args = array(
    'container'       => 'div',   // container element
    'separator'       => '&#47;', // separator between items
    'before'          => '',      // HTML to output before
    'after'           => '',      // HTML to output after
    'show_on_front'   => false,    // whether to show on front
    'show_title'      => true,    // whether to show the current page title
    'show_browse'     => false,    // whether to show the "browse" text
    'echo'            => false,    // whether to echo or return the breadcrumbs
);
 if ( function_exists( 'breadcrumb_trail' ) ) print_r(breadcrumb_trail($args)); 
?>
</div>