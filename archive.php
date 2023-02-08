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


	get_header();


?>
<div class="page_heading_me pt_template_page_1" id="pt_template_page_1">
	<div class="page_heading_me_inner">
    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12" id="x_templ_1_pt">
    	<div class="mm_inn"><?php if ( is_day() ) : ?>
							<?php printf( __( 'Daily Blog Archives: %s', 'ProjectTheme' ), '<span>' . get_the_date() . '</span>' ); ?>
						<?php elseif ( is_month() ) : ?>
							<?php printf( __( 'Monthly Blog Archives: %s', 'ProjectTheme' ), '<span>' . get_the_date( _x( 'F Y', 'monthly archives date format', 'ProjectTheme' ) ) . '</span>' ); ?>
						<?php elseif ( is_year() ) : ?>
							<?php printf( __( 'Yearly Blog Archives: %s', 'ProjectTheme' ), '<span>' . get_the_date( _x( 'Y', 'yearly archives date format', 'ProjectTheme' ) ) . '</span>' ); ?>
						<?php else : ?>
							<?php _e( 'Blog Archives', 'ProjectTheme' ); ?>
						<?php endif; ?></div>


<?php

		if(function_exists('bcn_display'))
		{
		    echo '<div class="my_box3_breadcrumb breadcrumb-wrap">';
		    bcn_display();
			echo '</div>';
		}

?>

    </div>



    </div>
</div>







<div class="container mt-4">
		<div id="main" class="row">


              <div class="col-xs-12 col-sm-12 col-md-8 col-lg-8">
<?php if ( have_posts() ) { while ( have_posts() ) : the_post(); ?>

<?php ProjectTheme_get_post_blog(); ?>

<?php endwhile; // end of the loop.


}
else
{
	_e('No blog posts for this tag.','ProjectTheme');

}?>

    </div>





		<div id="right-sidebar" class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
		    <ul class="sidebar-ul">
		        <?php dynamic_sidebar( 'other-page-area' ); ?>
		    </ul>
		</div>



</div></div>

<?php get_footer(); ?>
