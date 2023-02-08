<?php
/***************************************************************************
*
*	ProjectTheme - copyright (c) - sitemile.com
*	The only project theme for wordpress on the world wide web.
*
*	Coder: Andrei Dragos Saioc
*	Email: sitemile[at]sitemile.com | andreisaioc[at]gmail.com
*	More info about the theme here: http://sitemile.com/products/wordpress-project-freelancer-theme/
*	since v1.2.5.3
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



    <link rel="stylesheet" href="<?php echo get_template_directory_uri()?>/assets/css/nice-select.css">

    <!--====== Font Awesome CSS ======-->
    <link rel="stylesheet" href="<?php echo get_template_directory_uri()?>/assets/css/all.min.css">

    <!--====== Bootstrap CSS ======-->
    <link rel="stylesheet" href="<?php echo get_template_directory_uri()?>/assets/css/bootstrap.min.css">

    <!--====== Default CSS ======-->
    <link rel="stylesheet" href="<?php echo get_template_directory_uri()?>/assets/css/default.css">

    <!--====== Style CSS ======-->
    <link rel="stylesheet" href="<?php echo get_template_directory_uri()?>/assets/css/style.css">


<style>

  <?php echo get_template_part('custom-style-programming') ?>

</style>

	</head>
	<body <?php body_class(); ?> > <?php do_action('ProjectTheme_after_body_tag_open'); ?>



    <!--====== PRELOADER PART START ======-->

    <div class="preloader">
        <div class="loader">
            <div class="ytp-spinner">
                <div class="ytp-spinner-container">
                    <div class="ytp-spinner-rotator">
                        <div class="ytp-spinner-left">
                            <div class="ytp-spinner-circle"></div>
                        </div>
                        <div class="ytp-spinner-right">
                            <div class="ytp-spinner-circle"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <!-- ################ header area #######################-->

    <header class="header_area">

        <div class="header_navbar">
            <div class="container">
                <nav class="navbar navbar-expand-lg">
                    <a class="navbar-brand" href="<?php echo get_home_url() ?>">

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

                        <img src="<?php echo $logo ?>" width="<?php echo $width ?>" alt="logo">
                    </a>

                    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="fasse" aria-label="Toggle navigation">
                        <span class="toggler-icon"></span>
                        <span class="toggler-icon"></span>
                        <span class="toggler-icon"></span>
                    </button>

                    <div class="collapse navbar-collapse sub-menu-bar" id="navbarSupportedContent">


                                                            <?php

                                                            $menu_name = 'primary-projecttheme-main-menu';

                                                            wp_nav_menu( array(
                                                              'theme_location'    => $menu_name,
                                                              'depth'             => 2,
                                                              'container'         => false,
                                                              'container_class'   => 'container-class',
                                                              'container_id'      => 'navbarSupportedContent',
                                                              'menu_class'        => 'navbar-nav m-auto',
                                                              'fallback_cb'       => 'WP_Bootstrap_Navwalker2::fallback',
                                                              'walker'            => new WP_Bootstrap_Navwalker2(),
                                                            ) );


                                                            ?>





                    </div>

                    <?php

                           $menu_name = 'primary-projecttheme-non-loggedin';
                           if(is_user_logged_in())
                           {
                                $menu_name = 'primary-projecttheme-loggedin-freelancer';
                                if(ProjectTheme_is_user_business(get_current_user_id()))
                                {
                                      $menu_name = 'primary-projecttheme-loggedin-customer';
                                }
                           }

                     ?>
                    <div class="navbar_btn">
                        <ul>
                            <li>
                                <div class="dropdown">
                                   <!-- deleted line with Account Menu dropdown -->

                                    <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">

                                      <?php
                                      wp_nav_menu( array(
                                        'theme_location'    => $menu_name,
                                        'depth'             => 2,
                                        'container'         => false,
                                        'container_class'   => 'container-class',
                                        'container_id'      => 'navbarSupportedContent',
                                        'menu_class'        => 'noneclass',
                                        'fallback_cb'       => 'WP_Bootstrap_Navwalker::fallback',
                                        'walker'            => new WP_Bootstrap_Navwalker(),
                                      ) );


                                      ?>

                                    </div>
                                </div>
                            </li>
                            <?php

                                      if(is_user_logged_in())
                                      {
                                        if(ProjectTheme_is_user_provider(get_current_user_id()))
                                        {
                                          ?>
                                          <li><a class="sign-up" href="<?php echo get_permalink(get_option('ProjectTheme_post_new_page_id')) ?>"><?php _e('Post Trip','ProjectTheme'); ?></a></li>
                                          <?php
                                        }
                                        else
                                        {
                                          ?>
                                          <li><a class="sign-up" href="<?php echo get_permalink(get_option('ProjectTheme_post_new_page_id')) ?>"><?php _e('Post Trip','ProjectTheme'); ?></a></li>
                                          <?php
                                        }

                                      }
                                      else {
                                        ?>
                                            <li><a class="sign-up" href="<?php echo get_permalink(get_option('ProjectTheme_post_new_page_id')) ?>"><?php _e('Post Trip','ProjectTheme'); ?></a></li>
                                        <?php
                                      }

                            ?>

                        </ul>
                    </div>
                </nav>
            </div>
        </div>
      </header>
