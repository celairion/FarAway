<?php


if(!function_exists('pricerr_theme_my_account_watchlist_fn'))
{
function pricerr_theme_my_account_watchlist_fn()
{

  ?>

  <div class="container">


  <?php

	global $current_user;
	$current_user = wp_get_current_user();
	$uid = $current_user->ID;

	//-------------------------------------

    $pg = $_GET['pg'] ?? "home";
	if(!isset($pg)) $pg = 'home';


			global $current_user;
			$current_user = wp_get_current_user();
			$uid = $current_user->ID;

			global $wpdb; $prefix = $wpdb->prefix;
		?>


	<?php PricerrTheme_get_users_links() ?>
	<div class="row row-no-margin">








     <div class="col-xs-12 col-sm-12 col-md-12">
		<!-- page content here -->


            <div class="box_title3 mt-4 mb-4"><div class="inner-me"><?php _e('Watchlist / Liked Jobs','pricerrtheme') ?></div></div>

			<?php echo pricerr_demo() ?>




<?php



				global $wpdb, $wp_query;
				$s = "select * from ".$wpdb->prefix."job_likes where uid='$uid' order by id asc";
				$r = $wpdb->get_results($s);



				$my_arr = array();

				if(count($r) > 0)
				foreach($r as $item)
				{
					$my_arr[] = $item->pid;
				}

				if(count($my_arr) == 0) $my_arr[0] = 0;

				$args = array('post__in' => $my_arr,
				'post_type' 	=> 'job',
				'paged'			=> $wp_query->query_vars['paged']);

				$the_query = new WP_Query( $args );

				if($the_query->have_posts()):
				// The Loop
				while ( $the_query->have_posts() ) : $the_query->the_post();

					PricerrTheme_get_post_thumbs('post-thumbs-wrapper2');

				endwhile;

				if(function_exists('wp_pagenavi')):

					echo '<div class="navi-wrap">';
					wp_pagenavi( array( 'query' => $the_query ) );
					echo '</div>';

				endif;

				else:
          echo '<div class="my_box3 padd10 mb-4">';
					_e('There are no liked jobs yet.','pricerrtheme') ;
          echo '  </div>';

				endif;

				// Reset Post Data
				wp_reset_postdata();


?>


                                         </div>
                                                      </div>
<?php


}}


 ?>
