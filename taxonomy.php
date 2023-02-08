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


	function projectTheme_posts_join3($join) {
		global $wp_query, $wpdb;

		$join .= " LEFT JOIN (
				SELECT post_id, meta_value as featured_due
				FROM $wpdb->postmeta
				WHERE meta_key =  'featured' ) AS DD
				ON $wpdb->posts.ID = DD.post_id ";


		return $join;
	}

//------------------------------------------------------

	function projectTheme_posts_orderby3( $orderby )
	{
		global $wpdb;
		$orderby = " featured_due+0 desc, $wpdb->posts.post_date desc ";
		return $orderby;
	}


	add_filter('posts_join', 	'projectTheme_posts_join3');
	add_filter('posts_orderby', 'projectTheme_posts_orderby3' );

global $query_string;

$closed = array(
		'key' => 'closed',
		'value' => "0",
		//'type' => 'numeric',
		'compare' => '='
);

if(!empty($_GET['ending']))
{
	$ends = array(
		'key' => 'ending',
		'value' => (current_time('timestamp', 0) + 3600*24*$_GET['ending']),
		//'type' => 'numeric',
		'compare' => '<');

}

if(!empty($_GET['budgets']))
{
	$budgets = array(
		'key' => 'budgets',
		'value' => $_GET['budgets'],
		//'type' => 'numeric',
		'compare' => '=');

}



if(!empty($_GET['min_price']) or !empty($_GET['max_price']))
{

	$start = $_GET['min_price']; if(empty($start)) $start = 0;
	$end = $_GET['max_price']; if(empty($end)) $end = 999999999;

	$budgets1 = array(
		'key' => 'budgets',
		'value' => array($start, $end),
		'type' => 'numeric',
		'compare' => 'BETWEEN');

}






$prs_string_qu = wp_parse_args($query_string);
$prs_string_qu['meta_query'] = array($closed, $ends, $budgets, $budgets1);
$prs_string_qu['meta_key'] = 'featured';
$prs_string_qu['orderby'] = 'meta_value';
$prs_string_qu['order'] = 'DESC';

if(!empty($_GET['keyword']))
$prs_string_qu['s'] = $_GET['keyword'];

query_posts($prs_string_qu);

$term = get_term_by( 'slug', get_query_var( 'term' ), get_query_var( 'taxonomy' ) );
$term_title = $term->name;

//======================================================

	get_header();

	$ProjectTheme_adv_code_cat_page_above_content = stripslashes(get_option('ProjectTheme_adv_code_cat_page_above_content'));
		if(!empty($ProjectTheme_adv_code_cat_page_above_content)):

			echo '<div class="full_width_a_div">';
			echo $ProjectTheme_adv_code_cat_page_above_content;
			echo '</div>';

		endif;


?>


<div class="page_heading_me pt_template_page_1" id="pt_template_page_1">
	<div class="page_heading_me_inner">
    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12" id="x_templ_1_pt">
			<div class="container">
    	<div class="mm_inn"><h1><?php
						if(empty($term_title)) echo __("All Posted Projects",'ProjectTheme');
						else { echo sprintf( __("Latest Posted Projects in %s",'ProjectTheme'), $term_title);



						}
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



    </div>
</div>



<?php projecttheme_search_box_thing() ?>

<!-- ########## -->

<div class="container mt-4">
		<div id="main" class="row">

<?php

	if(ProjectTheme_using_permalinks())
	{
		$lnk = get_term_link(get_query_var( 'term' ), get_query_var( 'taxonomy' ));
	}
	else
	{
		$lnk = get_home_url();
	}


?>

<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
<form method="get" action="<?php echo $lnk ?>">
<div class="card p-3">
	<div class="row">

        	<div class="col-12 col-xs-6 col-md-6  mb-4">
				<div class="search-keyword-bb-left"><?php echo __('Keyword:','ProjectTheme') ?> </div>
				<div class="search-keyword-bb-right"><input type="text" placeholder="<?php _e('Type here...','ProjectTheme') ?>" value="<?php echo stripslashes($_GET['keyword']) ?>" name="keyword" size="30" class="form-control" /> </div>
			</div>

			<?php

					$ProjectTheme_budget_option = get_option('ProjectTheme_budget_option');
					if($ProjectTheme_budget_option == "input_box")
					{
							?>


							<div class="col-12 col-xs-6 col-md-6  mb-4">
							<div class="search-keyword-bb-left"><?php echo __('Minimum Budget:','ProjectTheme') ?> </div>
							<div class="search-keyword-bb-right"><input type="text" placeholder="<?php echo projecttheme_currency() ?>" value="<?php echo stripslashes($_GET['min_price']) ?>" name="min_price" size="30" class="form-control" /> </div>
							</div>


							<div class="col-12 col-xs-6 col-md-6  mb-4">
							<div class="search-keyword-bb-left"><?php echo __('Maximum Budget:','ProjectTheme') ?> </div>
							<div class="search-keyword-bb-right"><input type="text" placeholder="<?php echo projecttheme_currency() ?>" value="<?php echo stripslashes($_GET['max_price']) ?>" name="max_price" size="30" class="form-control" /> </div>
							</div>

							<?php
					}
					else {

			 ?>
            <div class="col-12 col-xs-6 col-md-6  mb-4">
				<div class="search-keyword-bb-left"><?php echo __('Project Budget:','ProjectTheme') ?> </div>
				<div class="search-keyword-bb-right"><?php echo ProjecTheme_get_budgets_dropdown($_GET['budgets'], 'form-control' , 1); ?> </div>
			</div>

		<?php } ?>


            <div class="col-12 col-xs-6 col-md-6 mb-4">
				<div class="search-keyword-bb-left"><?php echo __('Ending In:','ProjectTheme') ?> </div>
				<div class="search-keyword-bb-right"><select name="ending" class="form-control">
                <option value=""><?php _e('Select Period','ProjectTheme') ?></option>
                <option value="1" <?php echo ($_GET['ending'] == 1 ? 'selected="selected"' : '') ?>><?php _e('1 day','ProjectTheme') ?></option>
                <option value="7" <?php echo ($_GET['ending'] == 7 ? 'selected="selected"' : '') ?>><?php _e('7 days','ProjectTheme') ?></option>
                <option value="20" <?php echo ($_GET['ending'] == 20 ? 'selected="selected"' : '') ?>><?php _e('20 days','ProjectTheme') ?></option>
                <option value="30" <?php echo ($_GET['ending'] == 30 ? 'selected="selected"' : '') ?>><?php _e('30 days','ProjectTheme') ?></option>
                <option value="180" <?php echo ($_GET['ending'] == 180 ? 'selected="selected"' : '') ?>><?php _e('6 Months','ProjectTheme') ?></option>

                </select> </div>
			</div>


             <div class="col-12 col-xs-12 col-md-12  mb-4">
             	<div class="search-keyword-bb-left"></div>
				<div class="search-keyword-bb-right"><input type="submit" name="apply_filters" class="btn btn-primary" value="<?php _e('Apply Filters','ProjectTheme') ?>" /></div>
			</div>


	</div>
</div><!-- end filter-field-area -->
</form>


</div>


<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">





<?php if ( have_posts() ): while ( have_posts() ) : the_post(); ?>

<?php projectTheme_get_project_front_end(); ?>

<?php
 		endwhile;

		if(function_exists('wp_pagenavi')):
		wp_pagenavi(); endif;

     	else:

				echo '<div class="card p-3">';
		echo __('No projects posted.',"ProjectTheme");
		echo '</div>';

		endif;
		// Reset Post Data
		wp_reset_postdata();

		?>



</div>





</div>
</div>


<?php

	get_footer();

?>
