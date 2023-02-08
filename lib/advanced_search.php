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

	function projectTheme_posts_where2( $where ) {

			global $wpdb, $term;
			$term = trim($term);
			$term1 = explode(" ",$term);
			$xl = '';

			foreach($term1 as $tt)
			{
				$xl .= " AND ({$wpdb->posts}.post_title LIKE '%$tt%' OR {$wpdb->posts}.post_content LIKE '%$tt%')";

			}

			$where .= " AND (1=1 $xl )";

		return $where;
	}


	function projectTheme_posts_join2($join) {
		global $wp_query, $wpdb;

		$join .= " LEFT JOIN (
				SELECT post_id, meta_value as featured_due
				FROM $wpdb->postmeta
				WHERE meta_key =  'featured' ) AS DD
				ON $wpdb->posts.ID = DD.post_id ";

		$meta_key = $_GET['meta_key'];

		if(!empty($meta_key))
		{
				$join .= " LEFT JOIN (
				SELECT post_id, meta_value as meta_key_due
				FROM $wpdb->postmeta
				WHERE meta_key =  '$meta_key' ) AS BB
				ON $wpdb->posts.ID = BB.post_id ";
		}

		return $join;
	}

//------------------------------------------------------

	function projectTheme_posts_orderby( $orderby )
	{
		global $wpdb; $meta_key = $_GET['meta_key'];
		$order = $_GET['order'];


		if(!empty($meta_key))
		{
			$bbs = "meta_key_due+0 $order ,";
		}

		$orderby = " featured_due+0 desc , ".$bbs." $wpdb->posts.post_date desc ";

		//--------------------------------------

		if($_GET['orderby'] == "title")
		{
			$orderby = " featured_due+0 desc , $wpdb->posts.post_title ".$_GET['order']." ";
		}


		return $orderby;
	}


function ProjectTheme_advanced_search_area_main_function()
{



		if(isset($_GET['pj'])) $pj = $_GET['pj'];
	else $pj = 1;

	if(isset($_GET['order'])) $order = $_GET['order'];
	else $order = "DESC";

	if(isset($_GET['orderby'])) $orderby = $_GET['orderby'];
	else $orderby = "date";

	if(isset($_GET['meta_key'])) $meta_key = $_GET['meta_key'];
	else $meta_key = "";


	if(!empty($_GET['budgets'])) {


		$price_q = array(
			'key' => 'budgets',
			'value' => $_GET['budgets'],
			'compare' => '='
		);
	}


	if(isset($_GET['featured']))
	{
		$featured = array(
			'key' => 'featured',
			'value' => "1",
			//'type' => 'numeric',
			'compare' => '='
		);

	}


	$closed = array(
			'key' => 'closed',
			'value' => "0",
			//'type' => 'numeric',
			'compare' => '='
		);

		if(is_array($_GET['project_location_cat_multi']))
		if(count($_GET['project_location_cat_multi']) > 0)
		{
			$loc = array(
				'taxonomy' => 'project_location',
				'field' => 'term_id',
				'terms' => $_GET['project_location_cat_multi']

			);
		}
		else $loc = '';



		if(!empty($_GET['project_skill_cat'])) $skill = array(
				'taxonomy' => 'project_skill',
				'field' => 'term_id',
				'terms' => $_GET['project_skill_cat']

		);
		else $skill = '';



	if(is_array($_GET['project_cat_cat_multi']))
	if(count($_GET['project_cat_cat_multi']) > 0)
	{
		$category_taxonomy = array(
				'taxonomy' => 'project_cat',
				'field' => 'term_id',
				'terms' => $_GET['project_cat_cat_multi']

		);
	}


	if(!empty($_GET['project_cat_cat']))
	{
		$category_taxonomy = array(
				'taxonomy' => 'project_cat',
				'field' => 'term_id',
				'terms' => $_GET['project_cat_cat']

		);


	}

	//------------


	global $term;
	$term = trim($_GET['term']);

	if(!empty($_GET['term']))
	{
		add_filter( 'posts_where' , 'projectTheme_posts_where2' );

	}

	do_action('ProjectTheme_adv_search_before_search');

		add_filter('posts_join', 'projectTheme_posts_join2');
	add_filter('posts_orderby', 'projectTheme_posts_orderby' );

	//------------

//orderby price - meta_value_num

	$nrpostsPage = 10;
	$nrpostsPage = apply_filters('ProjectTheme_advanced_search_posts_per_page',$nrpostsPage);

	$args = array( 'posts_per_page' => $nrpostsPage, 'paged' => $pj, 'post_type' => 'project', 'order' => $order ,
	'meta_query' => array($price_q, $closed, $featured) ,'meta_key' => $meta_key, 'orderby'=>$orderby,'tax_query' => array( 'relation' => 'AND' , $loc, $category_taxonomy, $skill));



	$the_query = new WP_Query( $args );




	$nrposts = $the_query->found_posts;
	$totalPages = ceil($nrposts / $nrpostsPage);
	$pagess = $totalPages;

//===============*********=======================

	$obj2 = clone $the_query;
	do_action('projecttheme_above_advanced_search_page', $obj2);

?>

<div class="row">
<div id="right-sidebar2" class="float_left col-xs-12 col-sm-4 col-md-3 col-lg-3">
<script>


jQuery(document).ready(function()
{
	jQuery("#toggle-search-btn").click(function()
{

	jQuery("#search-filters-block").toggle();
		return false;
});
});


</script>
<div id="toggle-sidebar-search" class="w-100 p-2">	<a href="#" id='toggle-search-btn' class="btn btn-primary btn-block mb-4" ><?php _e('Toggle Search Filters','ProjectTheme'); ?></a></div>

	<ul class="sidebar-ul" id='search-filters-block'>
	<li class="">


        <div class="post">  <h3 class="widget-title"><?php _e('Filter Options','ProjectTheme'); ?></h3>
        <form method="get">
                   <ul id="medas">

                   <li>
                   <h2><?php _e('Keyword',"ProjectTheme"); ?>:</h2>
                   <p><input size="20" class="form-control" value="<?php echo htmlentities($_GET['term']); ?>" name="term" /></p>
                   </li>

                   <li>
                   <h2><?php _e('Price',"ProjectTheme"); ?>:</h2>
                   <p><?php echo ProjecTheme_get_budgets_dropdown($_GET['budgets'], 'form-control', 1); ?></p>
                   </li>


								<?php

										//*********************************************************************************************
										//
										//			LOCATIONS
										//
										//*********************************************************************************************



 									 			$ProjectTheme_enable_project_location = get_option('ProjectTheme_enable_project_location');
 												if($ProjectTheme_enable_project_location != "no")
 												{

													?>

									<li>
									<h2><?php _e('Location',"ProjectTheme"); ?>:</h2>

									 <?php if(get_option('ProjectTheme_enable_multi_cats') == "yes"){ ?>
									<div class="multi_cat_placeholder_thing">

												 <?php

														 $selected_arr = $_GET['project_location_cat_multi'];
														 echo projectTheme_get_categories_multiple('project_location', $selected_arr);

													 ?>

											 </div>


										 </li> <?php } else { ?>

											 <script>

											 function display_subcat2(vals)
						 					{
						 						jQuery.post("<?php echo esc_url( home_url() )  ?>/?get_locscats_for_me=1", {queryString: ""+vals+""}, function(data){
						 							if(data.length >0) {

						 								jQuery('#sub_locs').html(data);
						 								jQuery('#sub_locs2').html("");

						 							}
						 							else
						 							{
						 								jQuery('#sub_locs').html("");
						 								jQuery('#sub_locs2').html("");
						 							}
						 						});

						 					}

						 					function display_subcat3(vals)
						 					{
						 						jQuery.post("<?php echo esc_url( home_url() )  ?>/?get_locscats_for_me2=1", {queryString: ""+vals+""}, function(data){
						 							if(data.length >0) {

						 								jQuery('#sub_locs2').html(data);

						 							} else jQuery('#sub_locs2').html("");
						 						});

						 					}

						 					</script>


											 <?php

							 			 	echo projectTheme_get_categories_clck("project_location", $_GET['project_location_cat'] , __('Select Location','ProjectTheme'), "do_input_new", 'onchange="display_subcat2(this.value)"' );


							 								echo ' <span id="sub_locs">';


							 											if(!empty($_GET['project_location_cat']))
							 											{
							 												$args2 = "orderby=name&order=ASC&hide_empty=0&parent=". $_GET['project_location_cat'];
							 												$sub_terms2 = get_terms( 'project_location', $args2 );

																			if(count($sub_terms2) > 0)
																			{

									 												$ret = '<select class="form-control subselect-item" name="subloc">';
									 												$ret .= '<option value="">'.__('Select SubLocation','ProjectTheme'). '</option>';
									 												$selected1 = $_GET['subloc'];

									 												foreach ( $sub_terms2 as $sub_term2 )
									 												{
									 													$sub_id2 = $sub_term2->term_id;
									 													$ret .= '<option '.($selected1 == $sub_id2 ? "selected='selected'" : " " ).' value="'.$sub_id2.'">'.$sub_term2->name.'</option>';

									 												}
									 												$ret .= "</select>";
									 												echo $ret;

																			}

							 											}

							 										echo '</span>';


							 										echo ' <span id="sub_locs2">';


							 											if(!empty($_GET['subloc']))
							 											{
							 												$args2 = "orderby=name&order=ASC&hide_empty=0&parent=". $_GET['subloc'];
							 												$sub_terms2 = get_terms( 'project_location', $args2 );

																			if(count($sub_terms2) > 0)
																			{

									 												$ret = '<select class="form-control subselect-item" name="subloc2">';
									 												$ret .= '<option value="">'.__('Select SubLocation','ProjectTheme'). '</option>';
									 												$selected1 = $_GET['subloc2'];

									 												foreach ( $sub_terms2 as $sub_term2 )
									 												{
									 													$sub_id2 = $sub_term2->term_id;
									 													$ret .= '<option '.($selected1 == $sub_id2 ? "selected='selected'" : " " ).' value="'.$sub_id2.'">'.$sub_term2->name.'</option>';

									 												}
									 												$ret .= "</select>";
									 												echo $ret;

																			}


							 											}

							 										echo '</span>';

							 			 ?>

									 <?php }} ?>



								<?php

										//*********************************************************************************************
										//
										//			SKILLS
										//
										//*********************************************************************************************

								?>


								<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
								<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

								<style>

										.select2-selection { width: 100% }

								</style>

								<script>

								jQuery(document).ready(function() {
										jQuery('.js-example-basic-multiple').select2();
										jQuery('#skill-select-id').select2();
										});

								</script>



								<li>
							 <h2><?php _e('Skill',"ProjectTheme"); ?>:</h2>
							  <?php

										 echo projectTheme_get_categories_clck_multi("project_skill",$_GET['project_skill_cat']	 , __('Select Skill','ProjectTheme'), "skill-select-id", ' ' );

							  ?>
							 </li>



							 <?php

						 			//*********************************************************************************************
						 			//
						 			//			Categories
						 			//
						 			//*********************************************************************************************

						 	?>

                    <li>
                   <h2><?php _e('Category',"ProjectTheme"); ?>:</h2>

									 <?php if(1){ ?>






													 <?php

													  echo projectTheme_get_categories_clck_multi("project_cat",$_GET['project_cat_cat']	 , __('Select Category','ProjectTheme'), " ", ' ' );

											 //$selected_arr = $_GET['project_cat_cat_multi'];
											 //echo projectTheme_get_categories_multiple('project_cat', $selected_arr);

										 ?>



											 <?php } else { ?>
												 <p>
												 <?php

													 echo projectTheme_get_categories_clck("project_cat",$_GET['project_cat_cat']	 , __('Select Category','ProjectTheme'), "do_input_new", 'onchange="display_subcat(this.value)"' );

													 echo '<br/><span id="sub_cats">';


																 if(!empty($_GET['project_cat_cat']))
																 {
																	 $args2 = "orderby=name&order=ASC&hide_empty=0&parent=".$_GET['project_cat_cat'];
																	 $sub_terms2 = get_terms( 'project_cat', $args2 );

																	 if(count($sub_terms2) > 0)
																	 {

																			 $ret = '<select class="do_input_new form-control" name="subcat">';
																			 $ret .= '<option value="">'.__('Select Subcategory','ProjectTheme'). '</option>';
																			 $selected1 = $_GET['subcat'];

																			 foreach ( $sub_terms2 as $sub_term2 )
																			 {
																				 $sub_id2 = $sub_term2->term_id;
																				 $ret .= '<option '.($selected1 == $sub_id2 ? "selected='selected'" : " " ).' value="'.$sub_id2.'">'.$sub_term2->name.'</option>';

																			 }
																			 $ret .= "</select>";
																			 echo $ret;

																 		}
																 }

															 echo '</span>';

																									 ?></p>


																									 <script>

																												function display_subcat(vals)
																												{
																													jQuery.post("<?php echo esc_url( home_url() ) ; ?>/?get_subcats_for_me=1", {queryString: ""+vals+""}, function(data){
																														if(data.length >0) {

																															jQuery('#sub_cats').html(data);

																														} else  jQuery('#sub_cats').html(" ");
																													});

																												}

																												</script>

											 <?php } ?>


                   </li>



                   <?php do_action('ProjectTheme_adv_search_add_to_form'); ?>

									 <script>


										jQuery(document).ready(function( ) {
												jQuery('#featured').lc_switch('YES','NO');

											});

									 </script>

                   <li>
                   <h2 style="float:left; width: auto"><?php _e('Featured?',"ProjectTheme"); ?>:</h2>
                   <p style="float: right; width: auto"><input type="checkbox" name="featured" id="featured" value="1" <?php if(isset($_GET['featured'])) echo 'checked="checked"'; ?> /></p>
                   </li>



                    <li>

                   <input  type="submit" class="btn btn-block btn-primary" value="<?php _e("Refine Search","ProjectTheme"); ?>" name="ref-search" class="big-search-submit2" />
                   </li>
                   </ul>

                   </form>

                    <div class="clear10"></div>
                    <div style="float:left;width:100%">
                    <?php

						$ge = 'order='.($_GET['order'] == 'ASC' ? "DESC" : "ASC").'&meta_key=budgets&orderby=meta_value_num';
						foreach($_GET as $key => $value)
						{
							if($key != 'meta_key' && $key != 'orderby' && $key != 'order')
							{
								$ge .= '&'.$key."=". ($value);
							}
						}

					//------------------------

						$ge2 = 'order='.($_GET['order'] == 'ASC' ? "DESC" : "ASC").'&orderby=title';
						foreach($_GET as $key => $value)
						{
							if( $key != 'orderby' && $key != 'order')
							{
								$ge2 .= '&'.$key."=". ($value);
							}
						}
					//------------------------

						$ge3 = 'order='.($_GET['order'] == 'ASC' ? "DESC" : "ASC").'&meta_key=views&orderby=meta_value_num';
						foreach($_GET as $key => $value)
						{
							if($key != 'meta_key' && $key != 'orderby' && $key != 'order')
							{
								$ge3 .= '&'.$key."=". ($value);
							}
						}


					?>

                    <?php _e("Order by:","ProjectTheme");

					$ProjectTheme_advanced_search_page_id = get_option('ProjectTheme_advanced_search_page_id');
					if(ProjectTheme_using_permalinks())
					{
						$adv = get_permalink($ProjectTheme_advanced_search_page_id)."?";
					}
					else
					{
						$adv = get_permalink($ProjectTheme_advanced_search_page_id)."&";
					}

					?>
                    <a href="<?php echo $adv; echo htmlentities($ge); ?>"><?php _e("Price","ProjectTheme"); ?></a> |
                    <a href="<?php echo $adv; echo htmlentities($ge2); ?>"><?php _e("Name","ProjectTheme"); ?></a> |
                    <a href="<?php echo $adv; echo htmlentities($ge3); ?>"><?php _e("Visits","ProjectTheme"); ?></a>
                    </div>
    </div>
    </li>

	<?php dynamic_sidebar( 'other-page-area' ); ?>

</ul>

</div>



	<div id="content2" class="float_right col-xs-12 col-sm-8 col-md-9 col-lg-9" >


		<h4 class="mb-3 p-2" id="search-results-words"><?php _e('Search Results','ProjectTheme'); ?></h4>

<?php


		// The Loop

		if($the_query->have_posts()):
		while ( $the_query->have_posts() ) : $the_query->the_post();

			projectTheme_get_project_front_end( );


		endwhile;

	if(isset($_GET['pj'])) $pj = $_GET['pj'];
	else $pj = 1;

	$pjsk = $pj;

?>




                     <div class="div_class_div">
                     <?php


					$my_page 	= $pj;
					$page 		= $pj;

					$batch = 10;
					$nrpostsPage = $nrRes;
					$end = $batch * $nrpostsPage;

					if ($end > $pagess) {
						$end = $pagess;
					}
					$start = $end - $nrpostsPage + 1;

					if($start < 1) $start = 1;

					$links = '';

					$raport = ceil($my_page/$batch) - 1; if ($raport < 0) $raport = 0;

					$start 		= $raport * $batch + 1;
					$end		= $start + $batch - 1;
					$end_me 	= $end + 1;
					$start_me 	= $start - 1;

					if($end > $totalPages) $end = $totalPages;
					if($end_me > $totalPages) $end_me = $totalPages;

					if($start_me <= 0) $start_me = 1;

					$previous_pg = $page - 1;
					if($previous_pg <= 0) $previous_pg = 1;

					$next_pg = $pages_curent + 1;
					if($next_pg > $totalPages) $next_pg = 1;

					echo '<ul class="pagination">';
		if($my_page > 1)
		{
			echo '<li class="page-item"><a class="page-link" href="'.projectTheme_advanced_search_link_pgs($previous_pg).'">'.
			__("<< Previous","ProjectTheme").'</a></li>';
			echo '<li class="page-item"><a class="page-link" href="'.projectTheme_advanced_search_link_pgs($start_me).'"><<</a></li>';
		}


		for($i = $start; $i <= $end; $i ++) {
			if ($i == $pj) {
				echo '<li class="page-item active"><a class="page-link" id="activees" href="#">'.$i.'</a></li>';
			} else {


				echo '<li class="page-item"><a class="page-link" href="'.projectTheme_advanced_search_link_pgs($i).'">'.$i.'</a></li>';
			}
		}





		$next_pg = $pjsk+1;


		if($totalPages > $my_page)
		echo '<li class="page-item"><a class="page-link" href="'.projectTheme_advanced_search_link_pgs($end_me).'">>></a></li>';

		if($page < $totalPages)
		echo '<li class="page-item"><a class="page-link" href="'.projectTheme_advanced_search_link_pgs($next_pg).'">'.
		__("Next >>","ProjectTheme").'</a></li>';


		echo '</ul>';
					 ?>
                     </div>
                  <?php

     	else:


		echo '<div class="card"> <div class="p-3"> ';
		echo __('There are no projects posted on your search query.',"ProjectTheme");
		echo '</div></div>';


		endif;
		// Reset Post Data
		wp_reset_postdata();



		?>




</div></div>




<?php

}

?>
