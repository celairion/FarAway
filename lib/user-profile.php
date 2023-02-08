<?php

	global $wpdb,$wp_rewrite,$wp_query;
	$username = urldecode($wp_query->query_vars['project_author']);
	$uid = $username;
	$paged = $wp_query->query_vars['paged'];



	$user = get_userdata($uid);
	if($user == false)
	{
		$user = get_user_by('login', $username);
		$uid = $user->ID;
	}



	$username = $user->user_login;

	$usr_nm = $user->first_name." ".$user->last_name;
	$usr_nm = trim($usr_nm);

	function sitemile_filter_ttl($title){return __("User Profile",'ProjectTheme')." - ";}
	add_filter( 'wp_title', 'sitemile_filter_ttl', 10, 3 );

get_header();


//$cover = ProjectTheme_get_profile_cover($uid,50,50);

?>



<div class="user-profile-cover-div"><div class="container">
	<div class="row">
				<div class="col-12 col-sm-12 col-md-3 text-center ">
						<div class="w-100">	<img width="190" height="190" class="card-profile-img-nw" border="0" src="<?php echo ProjectTheme_get_avatar_new_2021($uid ); ?>" id='single-project-avatar' /></div>
						<div class=""><?php echo ProjectTheme_project_get_star_rating_with_words($uid) ?></div>
				</div>

				<div class="col-12 col-sm-12 col-md-7 user-profile-main-info  ">
									<h1 class="mt-3 user-name-h1"><?php echo project_theme_get_name_of_user($uid) ; ?></h1>

									<div class="w-100 badges-div d-inline-block">

										<?php

										global $wpdb;
										$ss 		= "select * from ".$wpdb->prefix."project_freelancer_skills where uid='$uid'";
										$rr 		= $wpdb->get_results($ss);
										$arr1 	= '';
										$conts = 0;


										foreach($rr as $row)
										{
													$term = get_term_by( 'id', $row->catid, 'project_skill');
													 echo '<h5 class="my-badge2">'.$term->name.'</h5>'  ;
													 $conts++;
										}

										if($conts == 0) _e('No skills defined for this user.','ProjectTheme');


										 ?>

									</div><!-- end badges div -->

									<div class="w-100 job-title-profile mt-3 d-inline-block">
												<i class="fas fa-user-tie"></i> <?php

															$user_position = get_user_meta($uid,'user_position',true);
															if(empty($user_position)) echo __('n/a','ProjectTheme');
															else {
																echo strip_tags($user_position);
															}

												 ?>
									</div>


									<?php

									$opt = get_option('ProjectTheme_enable_project_location');
									if($opt != 'no'){

									 ?>

									<div class="w-100 job-location-profile mt-1 d-inline-block">
												<i class="fas fa-map-marked-alt"></i> <?php

															$user_location = get_user_meta($uid,'user_location',true);
															if(empty($user_location)) echo __('n/a','ProjectTheme');
															else {
																echo strip_tags($user_location);
															}

												 ?>
									</div>

								<?php } ?>

				</div>

				<div class="col-12 col-sm-12 col-md-2 pt-4">

							<div class="w-100 text-center mb-4"><h2 class="hrly-price text-success">
								<?php

								$pr = get_user_meta($uid, 'per_hour', true);
								if(empty($pr)) $pr = __('not defined','ProjectTheme');
								else { $pr = ProjectTheme_get_show_price($pr); $pr = sprintf(__('%s/hr','ProjectTheme'), $pr); }

								echo $pr;

								?>
							</h2></div>

							<div class="w-100 text-center">


								<?php				$current_user = wp_get_current_user();

												if($uid != $current_user->ID)
												{

										?>

					<?php

                        if(ProjectTheme_check_seller_in_user($pst_auction_new->post_author, get_current_user_id()))
                        {
                            ?>

                               <a href="<?php echo get_home_url() ?>/?unsave_this_seller=<?php echo $pst_auction_new->post_author ?>&return=pst&idres=<?php echo $pst_auction_new->ID ?>" class="btn btn-primary  btn-sm"><i class="fas fa-heart"></i> <?php _e("Seller Saved","auctiontheme") ?></a>

                            <?php
                        }
                        else {
                          // code...
                          ?>

                           <a href="<?php echo get_home_url() ?>/?save_this_seller=<?php echo $pst_auction_new->post_author ?>&return=pst&idres=<?php echo $pst_auction_new->ID ?>" class="btn btn-outline-primary  btn-sm"><i class="fas fa-heart"></i> <?php _e("Save this seller","ProjectTheme") ?></a>

                          <?php
                        }			

				<?php

				if(function_exists('lv_pp_myplugin_activate'))
				{

				if(is_user_logged_in()) $link = projecttheme_get_pm_link_from_user(get_current_user_id(), $uid);
				else $link = projecttheme_get_pm_link_from_user(0, 0);

				?>

				<a class='btn btn-sm btn-outline-primary' href="<?php echo $link ?>"><?php echo __('Chat with User','ProjectTheme'); ?></a>


				<?php
				}
				else {



					$ProjectTheme_allow_before_placing_a_bid = get_option('ProjectTheme_allow_before_placing_a_bid');
					if($ProjectTheme_allow_before_placing_a_bid != "no")
					{
				?>
										<a class='btn btn-sm btn-outline-primary' href="<?php echo ProjectTheme_get_priv_mess_page_url('send', '', '&uid='.$uid); ?>"><?php echo __('Contact User','ProjectTheme'); ?></a>

									<?php }}} ?>


							</div>
				</div>


	</div>

	<?php

			$profile_pg = $_GET['profile_pg'];
			if(empty($profile_pg)) $profile_pg = 'home';


	 ?>

	<div class="row" id="user-tabs">

		<ul class="nav nav-tabs m-auto" >
				<li class="nav-item">
				<a class="nav-link <?php echo $profile_pg == "home" ? "active" : "" ?> " id="home-tab"  href="<?php echo ProjectTheme_get_user_profile_link($uid,'home') ?>"  ><?php _e('Description','ProjectTheme') ?></a>
				</li>
				<?php
							if(ProjectTheme_is_user_business($uid))
							{
				 ?>
				<li class="nav-item">
				<a class="nav-link <?php echo $profile_pg == "projects" ? "active" : "" ?>" id="projects-tab"   href="<?php echo ProjectTheme_get_user_profile_link($uid,'projects') ?>"  ><?php _e('Projects','ProjectTheme') ?></a>
				</li>
			<?php } ?>



				<li class="nav-item">
				<a class="nav-link <?php echo $profile_pg == "reviews" ? "active" : "" ?>" id="reviews-tab"  href="<?php echo ProjectTheme_get_user_profile_link($uid,'reviews') ?>" ><?php _e('Reviews','ProjectTheme') ?></a>
				</li>
				</ul>

	</div>
</div>
	</div>

<!-- ########## -->

<div class="container mt-4 mb-4">
		<div id="main" class="wrapper"><div  class="row">

			<?php


					if($profile_pg == "home")
					{


			 ?>

			<div  class="account-sidebar col-xs-12 col-sm-4 col-md-4 col-lg-4">

				<div class="card card-profile">

							<div class="card-body text-center">




							<table class="table card-table" id='details-table'><tbody>


								<tr>
										<td class="font-weight-bold-new"><?php _e('<i class="fa fa-calendar"></i> Registered on','ProjectTheme'); ?></td>
										<td class="text-right"><?php

											//lets take the current user
											$current_user = get_userdata($uid);

										 $registered = strtotime($current_user->user_registered);
										 echo date_i18n("j F, Y", $registered);

										?></td>
								</tr>


								<tr>
										<td class="font-weight-bold-new"><?php printf(__('<i class="far fa-list-alt"></i> All open projects','ProjectTheme')); ?></td>
										<td class="text-right"><?php echo ProjectTheme_get_total_nr_of_open_projects_of_uid($uid) ?></td>
								</tr>


								<tr>
										<td class="font-weight-bold-new"><?php printf(__('<i class="fas fa-cog"></i> Projects in progress','ProjectTheme')); ?></td>
										<td class="text-right"><?php echo ProjectTheme_get_total_nr_of_progress_projects_of_uid($uid) ?></td>
								</tr>







				                    <?php
								$arrms = ProjectTheme_get_user_fields_values($uid);

								if(count($arrms) > 0)
									for($i=0;$i<count($arrms);$i++)
									{

								?>
				               	<tr>
									<td class="font-weight-bold-new"><i class="far fa-list-alt"></i> <?php echo $arrms[$i]['field_name'];?>  </td>
				               	 	<td class="text-right"> <?php echo $arrms[$i]['field_value'];?></td>
				                </tr>
								<?php }




		if(ProjectTheme_is_user_provider($uid)):

			$pr = get_user_meta($uid, 'per_hour', true);
			if(empty($pr)) $pr = __('not defined','ProjectTheme');
			else $pr = ProjectTheme_get_show_price($pr);

		?>
		<tr>
		<td class="font-weight-bold-new"><?php echo __('<i class="fas fa-money-bill-alt"></i> Hourly Rate:','ProjectTheme') ?>  </td>
			<td class="text-right"> <?php echo $pr;?></td>
		</tr>



	<?php endif; ?>

</tbody></table>







					</div>

		 </div>	 </div>






<div class="col-xs-12 col-sm-8 col-md-8 col-lg-8">

    		<div class="card">
            <div class="box_content">



                        <div class="user-profile-description">
                        <?php

                        $info = get_user_meta($uid, 'user_description', true);
                        if(empty($info)) _e("No personal info defined.",'ProjectTheme');
                        else echo $info;



                        ?>




                   	 	</div>

                </div>

            </div>




<?php

	if(ProjectTheme_is_user_provider($uid)){



?>




	<h3 class="my-account-headline-1"><?php echo __('Portfolio Pictures','ProjectTheme'); ?></h3>

<?php

		echo '<link media="screen" rel="stylesheet" href="'.get_template_directory_uri().'/css/colorbox.css" />';
		/*echo '<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.6.1/jquery.min.js"></script>'; */
		echo '<script src="'.get_template_directory_uri().'/js/jquery.colorbox.js"></script>';

				?>


                		<div class="card">

            	<div class="box_content">

		<script>



		var $ = jQuery;



			jQuery(document).ready(function(){



				jQuery("a[rel='image_gal1']").colorbox();
			});
</script>

     <?php

		$args = array(
		'order'          => 'ASC',
		'orderby'        => 'post_date',
		'post_type'      => 'attachment',
		'author'    => $uid,
		'meta_key' 		=> 'is_portfolio',
		'meta_value' 	=> '1',
		'post_mime_type' => 'image',
		'numberposts'    => -1,
		); $i = 0;

		$attachments = get_posts($args);



	if ($attachments) {
	    foreach ($attachments as $attachment) {
		$url = ($attachment->ID);

			echo '<div class="div_div"  id="image_ss'.$attachment->ID.'"> <a href="'.ProjectTheme_generate_thumb($url, 900,600).'" rel="image_gal1"><img width="70" class="image_class" height="70" src="' .
			ProjectTheme_generate_thumb($url, 70, 70). '" /></a>

			</div>';

	}
} else { echo __('There are no pictures yet.','ProjectTheme'); }


	?>

    </div>
    </div>



<?php }




?>




 </div>
<?php } elseif($profile_pg == "projects") { ?>

<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">

	<?php

	$closed = array(
								'key' => 'closed',
								'value' => '0',
								'compare' => '='
							);


		$nrpostsPage = 8;
		$args = array( 'author' => $uid , 'meta_query' => array($closed)  ,'posts_per_page' => $nrpostsPage, 'paged' => $paged, 'post_type' => 'project', 'order' => "DESC" , 'orderby'=>"date");
		$the_query = new WP_Query( $args );

			// The Loop

			if($the_query->have_posts()):
			while ( $the_query->have_posts() ) : $the_query->the_post();

				projectTheme_get_post();


			endwhile;

		if(function_exists('wp_pagenavi'))
		wp_pagenavi( array( 'query' => $the_query ) );

	          ?>

	          <?php
	     	else:

			echo '  <div class="card p-3"><div class=" ">'.__('No projects posted.','ProjectTheme') . '</div></div>';

			endif;
			// Reset Post Data
			wp_reset_postdata();



			?>


</div>


<?php } elseif($profile_pg == "reviews"){ ?>

<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
	<div class="card">
 						 <div class="table-responsive">

  <!-- ####### -->


 	<?php

 global $wpdb;
 $query = "select * from ".$wpdb->prefix."project_ratings where touser='$uid' AND awarded='1' order by id desc limit 5";
 $r = $wpdb->get_results($query);

 if(count($r) > 0)
 {


	echo '<table class="table table-hover ">';
	echo '<thead><tr>';
 	echo '<td>&nbsp;</td>';
 	echo '<td>'.__('Project Title','ProjectTheme').' </td>';

 	echo '<td>'.__('Aquired on','ProjectTheme').' </td>';
 	echo '<td>'.__('Price','ProjectTheme').'</td>';
 	echo '<td>'.__('Rating','ProjectTheme').'</td>';
 	echo '</tr></thead><tbody>';


 foreach($r as $row)
 {
 $post = $row->pid;
 $post = get_post($post);
 $bid = projectTheme_get_winner_bid($row->pid);
 $user = get_userdata($row->fromuser);

 	$date_z = get_post_meta($row->pid,'closed_date',true);

 echo '<tr>';

 	echo '<td><img class="img_class g_image_g" src="'.ProjectTheme_get_first_post_image($row->pid, 42, 42).'"
 									alt="'.$post->post_title.'" width="42" /></td>';
 	echo '<td><a href="'.get_permalink($row->pid).'">'.$post->post_title.'</a></td>';

 	echo '<td>'.(empty($date_z) ? "" : date('d-M-Y H:i:s',$date_z)).'</td>';
 	echo '<td>'.projectTheme_get_show_price($bid->bid).'</td>';
 	echo '<td>'.ProjectTheme_get_project_stars(floor($row->grade/2)).' ('.floor($row->grade/2).'/5)</td></tr>';



 echo '<tr><td colspan="5"><b>'.__('Comment','ProjectTheme').':</b> '.$row->comment.'</td></tr>'	;



 }

 echo '</tbody></table>';
 }
 else
 {
		 echo '<div class="p-3">';
		 _e("There are no reviews yet.","ProjectTheme");
		 echo '</div>';
 }
 ?>


 <!-- ####### -->


 </div>
 </div> </div>



<?php } ?>


</div></div></div>


<?php

	get_footer();

?>
