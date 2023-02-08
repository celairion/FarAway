<?php
/***************************************************************************
*
*
*       This is the header file for the account pages for the project theme
*       was added since v4.1.0 of the project theme
*
***************************************************************************/


?>
<html <?php language_attributes(); ?>  dir="ltr">
 <head>

 <meta charset="UTF-8">
 <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
 <meta http-equiv="X-UA-Compatible" content="ie=edge">
 <meta http-equiv="Content-Language" content="en" />
 <meta name="msapplication-TileColor" content="#2d89ef">
 <meta name="theme-color" content="#4188c9">
 <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent"/>
 <meta name="apple-mobile-web-app-capable" content="yes">
 <meta name="mobile-web-app-capable" content="yes">
 <meta name="HandheldFriendly" content="True">
 <meta name="MobileOptimized" content="320">



    <link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />
	<?php wp_enqueue_script("jquery"); ?>

	<?php
  add_filter('wp_enqueue_scripts','ProjectTheme_add_theme_scripts_for_account_pages');
		wp_head();

	?>


<script src="<?php echo get_template_directory_uri() ?>/js/jquery.countdown.js" defer></script>
<script src="<?php echo get_template_directory_uri() ?>/js/vegas.min.js" defer></script>
<link rel="stylesheet" href="<?php echo get_template_directory_uri() ?>/css/vegas.css" async>


<script>


jQuery(document).ready(function(){


  jQuery('.expiration_project_p').each(function(index)
  {
  var until_now = jQuery(this).html();
  jQuery(this).countdown({until: until_now, format: 'd H M S', compact: false});


  });

jQuery(".home_blur").vegas({
slides: [

<?php

for($i=1;$i<=10; $i++)
{
  $fri = get_option('ProjectTheme_slider_img_' . $i);
  if(!empty($fri))
  {
?>

    { src: "<?php echo $fri ?>" },

<?php }} ?>
]
});});

</script>

    <?php do_action('ProjectTheme_before_head_tag_closes'); ?>






	</head>
	<body <?php body_class(); ?> > <?php do_action('ProjectTheme_after_body_tag_open'); ?>


    <!-- ###### -->
    <div class="preloader" style="display: none;">
        <div class="lds-ripple">
            <div class="lds-pos"></div>
            <div class="lds-pos"></div>
        </div>
    </div>


    <div id="main-wrapper" data-theme="light" data-layout="vertical" data-navbarbg="skin6" data-sidebartype="full"  data-sidebar-position="fixed" data-header-position="fixed" data-boxed-layout="full">
      <?php

      //starting of generating and working with the logo

      $logo = get_option('ProjectTheme_logo_URL');
      if(empty($logo)){

        $logo = get_template_directory_uri().'/images/project_theme_logo.png';
        $logo = apply_filters('ProjectTheme_logo_URL', $logo);
      }

      $logo_options = '';
      $logo_options = apply_filters('ProjectTheme_logo_options', $logo_options);


      $width = 200;
      $ProjectTheme_logo_width = get_option('ProjectTheme_logo_width');
      if(!empty($ProjectTheme_logo_width)) $width = $ProjectTheme_logo_width;


      ?>



      <header class="topbar" data-navbarbg="skin6">
            <nav class="navbar top-navbar navbar-expand-md navbar-light">
                <div class="navbar-header" data-logobg="skin6">
                    <!-- This is for the sidebar toggle which is visible on mobile only -->
                    <a class="nav-toggler waves-effect waves-light d-block d-md-none" href="javascript:void(0)"><i class="ti-menu ti-close"></i></a>
                    <!-- ============================================================== -->
                    <!-- Logo -->
                    <!-- ============================================================== -->
                    <div class="navbar-brand">
                        <!-- Logo icon -->
                        <a href="<?php echo get_home_url(); ?>">
                            <b class="logo-icon">
                                <!-- Dark Logo icon -->
                                    <img  class="header-brand-img" alt="<?php bloginfo('name'); ?> logo" <?php echo $logo_options; ?> src="<?php echo $logo; ?>" width="<?php echo $width ?>"  />
                            </b>
                            <!--End Logo icon -->

                        </a>
                    </div>
                    <!-- ============================================================== -->
                    <!-- End Logo -->
                    <!-- ============================================================== -->
                    <!-- ============================================================== -->
                    <!-- Toggle which is visible on mobile only -->
                    <!-- ============================================================== -->
                    <a class="topbartoggler d-block d-md-none waves-effect waves-light" href="javascript:void(0)" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation"><i class="ti-more"></i></a>
                </div>
                <!-- ============================================================== -->
                <!-- End Logo -->
                <!-- ============================================================== -->
                <div class="navbar-collapse collapse" id="navbarSupportedContent">
                    <!-- ============================================================== -->
                    <!-- toggle and nav items -->
                    <!-- ============================================================== -->
                    <ul class="navbar-nav float-left mr-auto ml-3 pl-1">
                        <!-- Notification -->
                        <?php do_action('pt_notification_bell_account_side') ?>
                        <!-- End Notification -->
                        <!-- ============================================================== -->
                        <!-- create new -->
                        <!-- ============================================================== -->
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-settings svg-icon"><circle cx="12" cy="12" r="3"></circle><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1-2-2 2 2 0 0 1 2-2h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83 2 2 0 0 1 2.83 0l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 2-2 2 2 0 0 1 2 2v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2 2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1z"></path></svg>
                            </a>
                            <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                                <a class="dropdown-item" href="<?php echo get_permalink(get_option('ProjectTheme_my_account_personal_info_id')) ?>"><?php _e('Account Settings','ProjectTheme') ?></a>
                                <a class="dropdown-item" href="<?php echo get_permalink(get_option('ProjectTheme_my_account_payments_id')) ?>"><?php _e('Finances','ProjectTheme') ?></a>
                                <?php do_action('pt_settings_menu_account_head') ?>

                            </div>
                        </li>

                    </ul>
                    <!-- ============================================================== -->
                    <!-- Right side toggle and nav items -->
                    <!-- ============================================================== -->
                    <ul class="navbar-nav float-right">
                        <!-- ============================================================== -->
                        <!-- Search -->
                        <!-- ============================================================== -->
                        <li class="nav-item d-none d-md-block">
                            <a class="nav-link" href="javascript:void(0)">
                                <form>
                                    <div class="customize-input">
                                        <input class="form-control custom-shadow custom-radius border-0 bg-white" type="search" placeholder="Search" aria-label="Search">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-search form-control-icon"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
                                    </div>
                                </form>
                            </a>
                        </li>
                        <!-- ============================================================== -->
                        <!-- User profile and search -->
                        <!-- ============================================================== -->
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="javascript:void(0)" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <img src="<?php echo  ProjectTheme_get_avatar(get_current_user_id(),48, 48) ?>" alt="user" class="rounded-circle" width="40">
                                <span class="ml-2 d-none d-lg-inline-block">
                                  <?php echo sprintf(__('<span>Hello,</span> <span class="text-dark">%s</span>','ProjectTheme'), project_theme_get_name_of_user(get_current_user_id())) ?>
                                   <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevron-down svg-icon"><polyline points="6 9 12 15 18 9"></polyline></svg></span>
                            </a>
                            <div class="dropdown-menu dropdown-menu-right user-dd animated flipInY">
                              <?php

                                  $is_admin = current_user_can( 'manage_options' );
                                  if($is_admin)
                                  {

                               ?>

                               <a class="dropdown-item" href="<?php echo get_admin_url() ?>"><?php _e('WP-Admin','ProjectTheme') ?></a>

                             <?php } ?>

                                <a class="dropdown-item" href="<?php echo get_permalink(get_option('ProjectTheme_my_account_page_id')) ?>"><?php _e('My Account','ProjectTheme') ?></a>

                                <a class="dropdown-item" href="<?php echo get_permalink(get_option('ProjectTheme_my_account_private_messages_id')) ?>"><?php _e('Messaging','ProjectTheme') ?></a>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="<?php echo get_permalink(get_option('ProjectTheme_my_account_personal_info_id')) ?>"><?php _e('Account Setting','ProjectTheme'); ?></a>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="<?php echo wp_logout_url() ?>"><i class="fas fa-sign-out-alt"></i> <?php _e('Logout','ProjectTheme'); ?></a>
                                <div class="dropdown-divider"></div>
                                <div class="pl-4 p-3"><a href="<?php echo ProjectTheme_get_user_profile_link(get_current_user_id()) ?>" class="btn btn-sm btn-info"><?php _e('View Profile','ProjectTheme'); ?></a></div>
                                <?php do_action('pt_do_account_menu_top') ?>
                            </div>
                        </li>
                        <!-- ============================================================== -->
                        <!-- User profile and search -->
                        <!-- ============================================================== -->
                    </ul>
                </div>
            </nav>
        </header>
