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

<?php if ( have_posts() ) while ( have_posts() ) : the_post(); ?>





<div class="page_heading_me pt_template_page_1" id="pt_template_page_1">
	<div class="page_heading_me_inner">

		<div class="container">
		<div class="row">
    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12" id="x_templ_1_pt">
    	<div class="mm_inn"><h1><?php

							  the_title();

						?></h1>


						<?php

								if(function_exists('bcn_display'))
								{
								    echo '<p>';
								    bcn_display();
									echo '</p>';
								}

						?>
                     </div></div>



    </div>
</div></div></div>



<!-- ########## -->
<div class="container mt-3">
<div class="row">


<?php


	$ProjectTheme_adv_code_single_page_above_content = stripslashes(get_option('ProjectTheme_adv_code_single_page_above_content'));
		if(!empty($ProjectTheme_adv_code_single_page_above_content)):

			echo '<div class="full_width_a_div">';
			echo $ProjectTheme_adv_code_single_page_above_content;
			echo '</div>';

		endif;
?>




<div id="content" class="col-xs-12 col-sm-12 col-md-8 col-lg-8">
			<div class="card p-3 blog-content">



<?php the_content(); ?>



			</div>
            </div>



<div id="right-sidebar" class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
    <ul class="sidebar-ul">
        <?php dynamic_sidebar( 'single-widget-area' ); ?>
    </ul>
</div>



</div></div>

<?php endwhile; // end of the loop. ?>

<?php get_footer(); ?>
