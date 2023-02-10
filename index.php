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

 
<?php 



$hero_image = get_template_directory_uri(  ) . "/images/home_image21.jpg";
$main_tagline = "Welcome to Far Away";
$sub_tagline = "The best Travel Company in the World";

$top_padding = 70; // Starts Image
$bottom_padding = 70;
$gradient1 ="999999";
$gradient2 ="111111";

$show_searchbar = "yes";
$show_tint = "no";

projecttheme_front_page_slider_function_new_2023($hero_image, $main_tagline, $sub_tagline, $button1_caption, $button1_link, $button2_caption, $button2_link, $top_padding, $bottom_padding, $show_searchbar, $search_bar_background, $show_tint, $gradient1, $gradient2);


?>


<div class="container mt-4">

<h2 class="mb-3"><?php _e('Latest posted Trips','ProjectTheme') ?></h2>


<?php


$closed = array(
	'key' => 'closed',
	'value' => "0",
	//'type' => 'numeric',
	'compare' => '='
);


$args = array( 'posts_per_page' => 5, 'paged' => 1, 'post_type' => 'project',  
	'meta_query' => array($closed)  );



	$the_query = new WP_Query( $args );


	if($the_query->have_posts())
	{
		while ( $the_query->have_posts() )  { $the_query->the_post();

			projectTheme_get_project_front_end( );


		}


	}

	else
	{
		echo '<div class="card"> <div class="p-3"> ';
		echo __('There are no Trips posted on your search query.',"ProjectTheme");
		echo '</div></div>';
	}


?>
	<h2> Our Categories</h2>
	<! -- Eingefügte Übersicht Categories

	<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
	<div class="card p-4">
                <script>
				jQuery(document).ready(function() {
 		jQuery('.parent_taxe').click(function () {
			var rels = jQuery(this).attr('rel');
			jQuery("#" + rels).toggle();
			jQuery("#img_" + rels).attr("src","<?php echo get_template_directory_uri(); ?>/images/posted1.png");

			return false;
		});
});

				</script>
                   <?php
		$opt = get_option('ProjectTheme_show_subcats_enbl');
		if($opt == 'no')
		$smk_closed = "smk_closed_disp_none";
		else $smk_closed = '';
		//------------------------
		$arr = array();
        global $wpdb;
		$nr 		= 4;
		$terms 	= get_terms("project_cat","parent=0&hide_empty=0");

		 $count = count($terms);
		 $t 		= 1;
		 $gather = '';
		 if ( $count > 0 ){
		     foreach ( $terms as $term ) {

 				 if($t == 1)
				 { 	echo '<div class="row mb-4">';	 }
				 $total_ads = ProjectTheme_get_custom_taxonomy_count2('project', $term->slug, 'project_cat');
				 echo '<div class="col-xs-12 col-md-5 col-lg-3 mb-4">';
				 		echo '<div class="category-box-thing">';

								echo '<h3 class="category-heading"><a class="category-main-link-list" href="'.get_term_link($term,"project_cat").'">'.$term->name.' ('.$total_ads.')</a></h3>';

								$terms2 = get_terms("project_cat","parent=".$term->term_id."&hide_empty=0");
								if($terms2)
							 	{
										echo '<ul class="subcats-here">';
										foreach ( $terms2 as $term2 )
										{
												$tt = ProjectTheme_get_custom_taxonomy_count2('project', $term2->slug, 'project_cat');
												echo '<li class="item-element"><a href="'.get_term_link($term2,"project_cat").'">'.$term2->name.' ('.$tt.')</a></li>';

										}
										echo '</ul>';
								}
						echo '</div>';
				 echo '</div>';
				 if($t == 4)
				 {
					 	echo '</div>
						';
						$t = 0; }

				$t++;

		     }
		 }
         //=======================================

				 if($t != 5 ) echo '</div>';
         ?>
                </div>
                </div>
    <?php


?>

 		<! -- Eingefügte Übersicht Categories Ende


</div>
 

    
<?php

		get_footer();

?>