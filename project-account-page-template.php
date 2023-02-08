<?php
/*
Template Name: PT Account Pages
*/
?>

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

	global $is_account_page;
	$is_account_page = true;

	//--------------------------

	get_header('account');
	global   $wp_query;

	$gg = $wp_query->posts[0]->ID;
	$post = get_post($gg);

?>



<!-- ########## -->





<?php if ( have_posts() ) while ( have_posts() ) : the_post(); ?>
<?php the_content(); ?>
<?php endwhile; // end of the loop. ?>



<?php get_footer('account'); ?>
