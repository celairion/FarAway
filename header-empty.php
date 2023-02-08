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






	</head>
	<body <?php body_class(); ?> > <?php do_action('ProjectTheme_after_body_tag_open'); ?>





 
