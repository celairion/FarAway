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
	global $projecttheme_en_k;
global $dateformat;


	function projectTheme_colorbox_stuff()
	{

		echo '<link media="screen" rel="stylesheet" href="'.get_template_directory_uri().'/css/colorbox.css" />';
		/*echo '<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.6.1/jquery.min.js"></script>'; */
		echo '<script src="'.get_template_directory_uri().'/js/jquery.colorbox.js"></script>';

		$get_bidding_panel = 'get_bidding_panel';
		$get_bidding_panel = apply_filters('ProjectTheme_get_bidding_panel_string', $get_bidding_panel) ;

?>

		<script>

		var $ = jQuery;

			jQuery(document).ready(function(){






				jQuery("#report-this-link").click( function() {

					if(jQuery("#report-this").css('display') == 'none')
					jQuery("#report-this").show('slow');
					else
					jQuery("#report-this").hide('slow');

					return false;
				});


				jQuery("#contact_seller-link").click( function() {

					if(jQuery("#contact-seller").css('display') == 'none')
					jQuery("#contact-seller").show('slow');
					else
					jQuery("#contact-seller").hide('slow');

					return false;
				});

		});
		</script>

<?php
	}

	add_action('wp_head','projectTheme_colorbox_stuff');
	//=============================

	global $current_user;
	$current_user = wp_get_current_user();
	$uid = $current_user->ID;
	global $wpdb;


/*****************************************************
*
*
******************************************************/




	if(isset($_POST['bid_now_reverse']))
	{
		if(is_user_logged_in()):
		if(isset($_POST['control_id']))
		{
			$cryptor = new ProjectTheme_cryptor($projecttheme_en_k);
			$pid 	= $cryptor->decrypt($_POST['control_id']);

			$post 		= get_post($pid);
			$bid 		= trim($_POST['bid']);
			$des 		= trim(strip_tags(projecttheme_sanitize_string($_POST['description2'])));
			$post 		= get_post($pid);

			$tm 		= current_time('timestamp',0);
			$days_done	= projecttheme_sanitize_string(trim($_POST['days_done']));

			//---------------------



			$projectTheme_enable_custom_bidding = get_option('projectTheme_enable_custom_bidding');
			if($projectTheme_enable_custom_bidding == "yes")
			{

				$ProjectTheme_get_project_primary_cat = ProjectTheme_get_project_primary_cat($pid);
				$projectTheme_theme_bidding_cat_ = get_option('projectTheme_theme_bidding_cat_' . $ProjectTheme_get_project_primary_cat);

				if($projectTheme_theme_bidding_cat_ > 0)
				{
					$ProjectTheme_get_credits = ProjectTheme_get_credits($uid);
					$do_not_show = 0;
					$prc = $projectTheme_theme_bidding_cat_;

					if(	$ProjectTheme_get_credits < $projectTheme_theme_bidding_cat_) { $do_not_show = 1;
						$prc = $projectTheme_theme_bidding_cat_;

					}


				}

			}


			//---------------------

			$closed = get_post_meta($pid,'closed',true);
			if($closed == "1") { echo 'DEBUG.Project Closed'; exit; }

			//---------------------

			if(empty($days_done) || !is_numeric($days_done))
			{
				$days_done = 3;
			}

			$query = "select * from ".$wpdb->prefix."project_bids where uid='$uid' AND pid='$pid'";
			$r = $wpdb->get_results($query);

			$other_error_to_pace_bid = false;
			$other_error_to_pace_bid = apply_filters('ProjectTheme_other_error_to_pace_bid', $other_error_to_pace_bid, $pid);

			if($other_error_to_pace_bid == true):

				$bid_posted = "0";
				$errors = apply_filters('ProjectTheme_post_bid_errors_array', $errors, $pid);

			else:


				if(!is_numeric($bid)):

					$bid_posted = "0";
					$errors['numeric_bid_tp'] = __("Your bid must be numeric type. Eg: 9.99",'ProjectTheme');

				elseif($uid == $post->post_author):

					$bid_posted = "0";
					$errors['not_yours'] = __("Your cannot bid your own projects.",'ProjectTheme');

				elseif(count($r) > 0):

					$row 	= $r[0];
					$id 	= $row->id;


					$query 	= "update ".$wpdb->prefix."project_bids set bid='$bid', days_done='$days_done',
					description='$des',date_made='$tm',uid='$uid' where id='$id'";
					$wpdb->query($query);
					$bid_posted = 1;




				else:

					$query = "insert into ".$wpdb->prefix."project_bids (days_done,bid,description, uid, pid, date_made)
					values('$days_done','$bid','$des','$uid','$pid','$tm')";
					$wpdb->query($query);
					$bid_posted = 1;

					do_action('projettheme_on_placing_a_bid', $pid, $bid, $uid);

					//**********

					if($do_not_show == 0)
					{
						if($prc > 0)
						{
							$pst = get_post($pid);
							$cr = projectTheme_get_credits($uid);
							projectTheme_update_credits($uid, $cr - $prc);

							$reason = sprintf(__('Payment for bidding on project: <a href="%s">%s</a>','ProjectTheme'), get_permalink($pid), $pst->post_title);
							projectTheme_add_history_log('0', $reason, $prc, $uid);
						}
					}


					//**********

					do_action('ProjectTheme_post_bid_ok_action');

					add_post_meta($pid,'bid',$uid);

				endif; // endif has bid already

			endif;
		}




		if($bid_posted == 1):

			ProjectTheme_send_email_when_bid_project_owner($pid, $uid, $bid);
			ProjectTheme_send_email_when_bid_project_bidder($pid, $uid, $bid);

			//---------------------

			$prm = ProjectTheme_using_permalinks();
			if($prm == true)
			wp_redirect(get_permalink(get_the_ID()) . "/?bid_posted=1");
			else
			{
				wp_redirect(get_permalink(get_the_ID()) . "&bid_posted=1");
			}

			exit;


		endif; //endif bid posted

	else:

		wp_redirect(home_url()."/wp-login.php");
		$_SESSION['redirect_me_back'] = get_permalink($pid);

		exit;

	endif;
	}


	//=============================
	//function Project_change_main_class() { echo "<style> #main { background:url('".get_template_directory_uri."/images/bg1.png')  } </style>"; }
	//add_filter('wp_head', 'Project_change_main_class');


	get_header();
	global $post;


	if(function_exists('projecttheme_is_user_able_to_access'))
	{
			$res = projecttheme_is_user_able_to_access(get_current_user_id(), $post->ID);
			if($res == false)
			{
				 ?>

				 <div class="container pt-4" <?php post_class(); ?>>
				 	<div class="wrapper col-xs-12 col-sm-12 col-md-12 col-lg-12">

				 	<div class="card">
				 					<div class="p-3">


				 							<div class="w-100">
				 							<?php echo sprintf(__('You need to upgrade membership before you see the details of this project. <a href="%s">Click here</a> to upgrade.','ProjectTheme') , get_permalink(get_option('ProjectTheme_my_account_page_id'))); ?>
				 							</div>
				 	</div>
				 	</div>



				 	</div></div>


				 <?php

				 get_footer();
				 exit;
			}
	}



	$hide_project_p = get_post_meta($post->ID, 'hide_project', true);

	if(($hide_project_p == "1" or $hide_project_p == "yes") && !is_user_logged_in()):
	?>


	<div class="page_heading_me_project pt_template_page_1 " id="pt_template_page_1">
	                        <div class="page_heading_me_inner page_heading_me_inner_project container"> <div class="row">
	                        <div class="main-pg-title col-xs-12 col-sm-12 col-md-12 col-lg-12" id="x_templ_1_pt">
	                            <div class="mm_inn mm_inn21"><h1><?php echo $post->post_title; ?> </h1>        </div>



	                        </div>


	    </div>
	</div></div>



	<div class="container pt-4" <?php post_class(); ?>>
		<div class="wrapper col-xs-12 col-sm-12 col-md-12 col-lg-12">

    <div class="card">
            <div class="p-3">

            	<h4 class="box_title"><?php echo sprintf(__("Project \"%s\" is marked hidden.",'ProjectTheme'), $post->post_title); ?></h4>
                <div class="w-100">
                <?php echo sprintf(__('The project "%s" was marked as hidden. <a href="%s">Please login</a> to see project details.','ProjectTheme') , $post->post_title, home_url()."/wp-login.php"); ?>
                </div>
    </div>
    </div>



    </div></div></div>

    </div></div>

    <?php

	get_footer();
	exit;
	endif;

	?>










<?php if ( have_posts() ) while ( have_posts() ) : the_post(); ?>

  <?php



	$hourly_paid = get_post_meta(get_the_ID(), 'hourly_paid', true);


	$location   		= get_post_meta(get_the_ID(), "Location", true);
	$ending     		= get_post_meta(get_the_ID(), "ending", true);
	$featured     		= get_post_meta(get_the_ID(), "featured", true);
	$private_bids     	= get_post_meta(get_the_ID(), "private_bids", true);

	//---- increase views

	$views    	= get_post_meta(get_the_ID(), "views", true);
	$views 		= $views + 1;
	update_post_meta(get_the_ID(), "views", $views);



?>
                <div class="page_heading_me_project pt_template_page_1 " id="pt_template_page_1">
                        <div class="page_heading_me_inner page_heading_me_inner_project container" > <div class="row">
                        <div class="main-pg-title col-xs-12 col-sm-12 col-md-12 col-lg-12" id="x_templ_1_pt">
                            <div class="mm_inn mm_inn21"><h1><?php the_title() ?></h1>


															<?php

					if(function_exists('bcn_display'))
					{
							echo '<p>';
							bcn_display();
						echo '</p>';
					}

			?>



                </div>





                        </div>



    </div>
</div></div>

<?php

		$pg = $_GET['pg'];
		if(empty($pg)) $pg = 'home';

		$winner = get_post_meta(get_the_ID(),'winner',true);
		if(empty($winner)) update_post_meta(get_the_ID(),'winner',"0");

 ?>
<div class="w-100 container-with-menu">
		<div class="container">
			<ul class="nav nav-tabs m-auto" id='tabs-for-project' >
					<li class="nav-item">
					<a class="nav-link <?php echo $pg == "home" ? "active" : "" ?> " id="home-tab"  href="<?php echo ProjectTheme_get_project_link_with_page(get_the_ID(),'home') ?>"  ><?php _e('Main Details','ProjectTheme') ?></a>
					</li>
					<li class="nav-item">
					<a class="nav-link <?php echo $pg == "description" ? "active" : "" ?>" id="description-tab"   href="<?php echo ProjectTheme_get_project_link_with_page(get_the_ID(),'description') ?>"  ><?php _e('Description','ProjectTheme') ?></a>
					</li>

					<?php

							$ProjectTheme_enable_project_location = get_option('ProjectTheme_enable_project_location');
							if($ProjectTheme_enable_project_location == "yes")
							{

								$x = get_option('ProjectTheme_enable_map_proj_page');
								if($x == "yes")
								{



					 ?>
					<li class="nav-item">
					<a class="nav-link <?php echo $pg == "map" ? "active" : "" ?>" id="map-tab"  href="<?php echo ProjectTheme_get_project_link_with_page(get_the_ID(),'map') ?>" ><?php _e('Map Location','ProjectTheme') ?></a>
					</li>

				<?php }} ?>


					<li class="nav-item">
					<a class="nav-link <?php echo $pg == "pics" ? "active" : "" ?>" id="pics-tab"  href="<?php echo ProjectTheme_get_project_link_with_page(get_the_ID(),'pics') ?>" ><?php _e('Pictures and Files','ProjectTheme') ?></a>
					</li>


				<!--	<li class="nav-item">
					<a class="nav-link <?php echo $pg == "reviews" ? "active" : "" ?>" id="reviews-tab"  href="<?php echo ProjectTheme_get_project_link_with_page(get_the_ID(),'reviews') ?>" ><?php _e('User Reviews','ProjectTheme') ?></a>
				</li> -->

					</ul>
		</div>
</div>


<div class="container pt-4">

<?php 	do_action('pt_for_demo_work_3_0'); ?>

<?php

if($pg == "home") {



			 if($post->post_author == get_current_user_id())
			 {

						 $show_stuff = 1; $owner = 1; $show_this_around = 1;

					 ?>



							 <?php
							 $ProjectTheme_enable_project_files = get_option('ProjectTheme_enable_project_files');
							 $winner = get_post_meta(get_the_ID(), 'winner', true);


							 $post = get_post(get_the_ID());
							 global $wpdb;
							 $pid = get_the_ID();

							 $bids = "select * from ".$wpdb->prefix."project_bids where pid='$pid' order by id DESC";
							 $res  = $wpdb->get_results($bids);

							 if($post->post_author == $uid) $owner = 1; else $owner = 0;

							 if(count($res) > 0)
							 {
								 ?>
												 <table class="table table-hover table-outline table-vcenter   card-table">
													 <thead><tr>
																	 <th>&nbsp;</th>
																	 <th><?php _e('Username','ProjectTheme'); ?></th>
																	 <th><?php _e('Proposal','ProjectTheme'); ?></th>
																	 <th><?php _e('Date','ProjectTheme'); ?></th>
																	 <th><?php _e('Time','ProjectTheme'); ?></th>
																	 <th> &nbsp;</th>
																	 <th> &nbsp;</th>

													 </tr></thead> <tbody>

								 <?php

							 if($private_bids == 'yes' or $private_bids == '1' or $private_bids == 1)
							 {
							 if ($owner == 1) $show_stuff = 1;
							 else if(projectTheme_current_user_has_bid($uid, $res)) $show_stuff = 1;
							 else $show_stuff = 0;
							 }
							 else $show_stuff = 1;

							 //------------



							 //-------------

							 foreach($res as $row)
							 {

							 if ($owner == 1) $show_this_around = 1;
							 else
							 {
							 if($private_bids == 'yes' or $private_bids == '1' or $private_bids == 1)
							 {
								if($uid == $row->uid) 	$show_this_around = 1;
								else $show_this_around = 0;
							 }
							 else
							 $show_this_around = 1;

							 }



							 if($show_this_around == 1):

							 $user = get_userdata($row->uid);
							 echo '<tr class=" ">';
							 echo '<td> <img src="'.ProjectTheme_get_avatar($user->ID,	40, 40).'" width="40" class="avatar-from-list" /> </td>';
							 echo '<td>  <a href="'.ProjectTheme_get_user_profile_link($user->ID).'">'.$user->user_login.'</a></td>';
							 echo '<td><i class="bid-money"></i>  '.ProjectTheme_get_show_price($row->bid).' ' .($hourly_paid == "1" ? "/hr"  : ""). '</td>';
							 echo '<td><i class="bid-clock"></i> '.date_i18n("d-M-Y H:i:s", $row->date_made).'</td>';
							 echo '<td><i class="bid-days"></i> '. sprintf(__("%s days" ,"ProjectTheme"), $row->days_done) .'</td>';
							 if ($owner == 1 ) {

							 $nr = 7;
							 if(empty($winner)) // == 0)
								echo '<td>  <a href="'.home_url().'/?p_action=choose_winner&pid='.get_the_ID().'&bid='.$row->id.'" class="btn btn-secondary btn-sm   ">'.__('Select as Winner','ProjectTheme').'</a></td>';

							 if($ProjectTheme_enable_project_files != "no")
							 {
								if(projecttheme_see_if_project_files_bid(get_the_ID(), $row->uid) == true or get_current_user_id() == $row->uid)
								{
								echo '<td>  ';
								echo '<a href="#" class="btn btn-outline-secondary btn-sm" data-toggle="modal" data-target="#exampleModal'.$row->uid.'"  >'.sprintf(__('%s See Bid Files','ProjectTheme'), '<i class="fas fa-folder-open"></i>').'</a> ';
								echo '</td>';

								 ?>


								 <div class="modal fade" id="exampleModal<?php echo $row->uid ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
										 <div class="modal-dialog" role="document">
										 <div class="modal-content">
										 <div class="modal-header">
											 <h5 class="modal-title" id="exampleModalLabel"><?php _e('Bid Files','ProjectTheme') ?></h5>
											 <button type="button" class="close" data-dismiss="modal" aria-label="Close">
												 <span aria-hidden="true">&times;</span>
											 </button>
										 </div>
										 <div class="modal-body">
											 <?php




															$args = array(
															'order'          => 'ASC',
															'post_type'      => 'attachment',
															'post_parent'    => get_the_ID(),
															'post_author'    => $row->uid,
															'meta_key'		 => 'is_bidding_file',
															'meta_value'	 => '1',
															'numberposts'    => -1,
															'post_status'    => null,
															);
															$attachments = get_posts($args);



															$vv1 = 0;

															if ($attachments)
															{
																	foreach ($attachments as $attachment)
																	{

																				 $url 	= $attachment->post_title;
																				 $imggg = $attachment->post_mime_type;

																				 if($attachment->post_author == $row->uid)
																				 {

																				 echo '<div class="div_div"  id="image_ss'.$attachment->ID.'"><a target="_blank" href="'.wp_get_attachment_url($attachment->ID).'">'.$url.'</a>
																							 </div>';
																					$vv1++;
																				}

																	 }
															}

															if($vv1 == 0) { _e("There are no files attached.","ProjectTheme"); }


															?>
										 </div>
										 <div class="modal-footer">
											 <button type="button" class="btn btn-secondary" data-dismiss="modal"><?php _e('Close This','ProjectTheme') ?></button>

										 </div>
										 </div>
										 </div>
									</div>


								 <?php

								}

							 }

							 //---------- live chat -----------------------

							 if(function_exists('lv_pp_myplugin_activate') and $is_author != 1)
							 {
								global $post;


								if(is_user_logged_in()) $link = projecttheme_get_pm_link_from_user(get_current_user_id(), $row->uid);
								else $link = projecttheme_get_pm_link_from_user(0, 0);

								echo '<td> <a href="'.$link.'" class="btn btn-primary btn-sm   ">'.sprintf(__('Chat User','ProjectTheme')).'</a></td>';

							 }
							 else echo '<td> <a class="btn btn-primary btn-sm  "  href="'.ProjectTheme_get_priv_mess_page_url('send', '', '&uid='.$row->uid.'&pid='.get_the_ID()).'">'.__('Send Message','ProjectTheme').'</a></td>';
							 }
							 else $nr = 4;

							 if($closed == "1") { if($row->winner == 1) echo '<td>'.__('Project Winner','ProjectTheme').'</td>';   }


							 echo '</tr>';

							 //echo '<div class="my_td_with_border">'.$row->description.'</div>';
							 //echo '</div>';
							 endif;
							 }



							 echo '</tbody></table>';
							 }
							 else {   echo '<div class="padd20">'; _e("No proposals placed yet.",'ProjectTheme'); echo '</div>';  }
							 ?>


										</div>			 </div></div></div>


						 <?php
			 }



	 ?>

	<div  class="row">

	<?php

	$ProjectTheme_adv_code_project_page_above_content = get_option('ProjectTheme_adv_code_project_page_above_content');
	if(!empty($ProjectTheme_adv_code_project_page_above_content))
	{
	echo '<div class="padd10 full_width" style="padding-top:0">'.$ProjectTheme_adv_code_project_page_above_content.'</div> <div class="clear10"></div>';

	}




	?>

	<div   class="col-xs-12 col-sm-8 col-md-8 col-lg-8" id='mainsc'>


	<?php

	if(isset($_POST['report_this']) and is_user_logged_in())
	{

	if(isset($_SESSION['reported-soon']))
	{
	$rp = $_SESSION['reported-soon'];
	if($rp < current_time('timestamp',0)) { $_SESSION['reported-soon'] = current_time('timestamp',0) + 60; $rep_ok = 1; }
	else { $rep_ok = 0; }
	}
	else
	{
	$_SESSION['reported-soon'] = current_time('timestamp',0) + 60; $rep_ok = 1;
	}

	if($rep_ok == 1)
	{

	$pid_rep = $_POST['pid_rep'];
	$reason_report = nl2br($_POST['reason_report']);

	//---- send email to admin
	$subject = __("Report offensive project", 'ProjectTheme')." : ".get_the_title();

	$message = __("This project has been reported as offensive", 'ProjectTheme');
	$message .= " : <a href='".get_permalink(get_the_ID())."'>".get_the_title()."</a>";
	$message .= " <br/>Message: ".strip_tags($_POST['reason_report']);

	$recipients = get_option('admin_email');

	ProjectTheme_send_email($recipients, $subject, $message);

	//------------------------
	?>
	 <div class="card">
			 <div class="padd10">
			 <div class="box_content">

						 <?php _e('Thank you! Your report has been submitted.','ProjectTheme'); ?>

			 </div>
		 </div>
	 </div>

	 <div class="clear10"></div>

	<?php
	}
	else
	{
	?>


	 <div class="card">
			 <div class="padd10">
			 <div class="box_content" style="color:red;"><b>

						 <?php _e('Slow down buddy! You reported this before.','ProjectTheme'); ?>
					 </b>
			 </div>
		 </div>
	 </div>

	 <div class="clear10"></div>

	<?php
	}
	}

	?>

	<div id="report-this" style="display:none">
	<div class="card">
			 <div class="padd10">

				 <div class="box_title"><?php echo __("Report this project",'ProjectTheme'); ?></div>
					 <div class="box_content">
					 <?php

	 if(!is_user_logged_in()):

	 ?>

					 <?php echo sprintf(__('You need to be <a href="%s">logged</a> in to use this feature.','ProjectTheme'), home_url()."/wp-login.php" ); ?>
					 <?php else: ?>


		 <form method="post"><input type="hidden" value="<?php the_ID(); ?>" name="pid_rep" />
							 <ul class="post-new3">


	 <li>
		 <h2><?php echo __('Reason for reporting','ProjectTheme'); ?>:</h2>
	 <p><textarea rows="4" cols="40" class="do_input" required  name="reason_report"></textarea></p>
	 </li>




	 <li>
	 <h2>&nbsp;</h2>
	 <p><input type="submit" name="report_this" value="<?php _e('Submit Report','ProjectTheme'); ?>" /></p>
	 </li>


	</ul>
	</form> <?php endif; ?>


	 </div>
	</div>
	</div>

			 <div class="clear10"></div>

	</div>


	<!-- ######### -->

	<?php

	if(isset($_POST['contact_seller']))
	{

	if(isset($_SESSION['contact_soon']))
	{
	$rp = $_SESSION['contact_soon'];
	if($rp < current_time('timestamp',0)) { $_SESSION['contact_soon'] = current_time('timestamp',0) + 60; $rep_ok = 1; }
	else { $rep_ok = 0; }
	}
	else
	{
	$_SESSION['contact_soon'] = current_time('timestamp',0) + 60; $rep_ok = 1;
	}

	if($rep_ok == 1)
	{

	$subject = $_POST['subject'];
	$email = $_POST['email'];
	$message = nl2br($_POST['message']);

	//---- send email to admin


	$p = get_post(get_the_ID());
	$a = $p->post_author;
	$a = get_userdata($a);

	ProjectTheme_send_email($a->user_email, $subject, $message."<br/>From Email: ".$email);

	//------------------------
	?>
	 <div class="card">
			 <div class="padd10">
			 <div class="box_content">

						 <?php _e('Thank you! Your message has been sent.','ProjectTheme'); ?>

			 </div>
		 </div>
	 </div>

	 <div class="clear10"></div>

	<?php
	}
	else
	{
	?>

			 <div class="card">
			 <div class="padd10">
			 <div class="box_content">

						 <?php _e('Slow down buddy!.','ProjectTheme'); ?>

			 </div>
		 </div>
	 </div>

	 <div class="clear10"></div>


			<?php
	}
	}

	?>

	<?php


	if($bid_posted == "0"){ ?>

					 <div class="bid_panel_err">
					 <div class="padd10">
					 <?php _e("Your bid has not been posted. Please correct the errors and try again.",'ProjectTheme');
									 echo '<br/>';
									 foreach($errors as $err)
									 echo $err.'<br/>';
						?>
					 </div>
					 </div>

	 <?php } ?>


	 <?php if($_GET['bid_posted'] == 1) { ?>

					 <div class="alert alert-success">

					 <?php _e("Your bid has been posted.",'ProjectTheme');

						?>

					 </div>

	 <?php } ?>





	<div class="project-signle-content-main card">

			 <div class="kt-portlet__head">
				 <div class="kt-portlet__head-label">
						 <h3 class="kt-portlet__head-title">
							 <?php _e('Main Details','ProjectTheme') ?>
						 </h3>
				 </div>
			 </div>


					 <?php

	 $closed 	 = get_post_meta(get_the_ID(),'closed',true);

	 ?>

	 <div class="project-page-details-holder ">
					 <?php
	 if($closed == "0") : ?>




					 <div class="bid_panel_front  ">


					 <div class="small_buttons_div_left  p-3">
						 <div class="container"   id="project-details-id">
<?php

		$hourly_paid = get_post_meta(get_the_ID(),'hourly_paid', true);
		if($hourly_paid == "1")
		{

?>

<div class="row">
	<div class="col-md-4 column-details-1"><img src="<?php echo get_template_directory_uri() ?>/images/wallet_icon2.png" width="18" height="18" alt="budget" /> <?php echo __("Hourly Rate",'ProjectTheme'); ?> </div>
	<div class="col-md-8 column-details-2"><?php echo projecttheme_get_show_price(get_post_meta(get_the_ID(), 'hourly_rate', true)); ?></div>
</div>

<?php } else { ?>


	<div class="row">
			<div class="col-md-4 column-details-1"><img src="<?php echo get_template_directory_uri() ?>/images/coins_icon.png" width="18" height="18" alt="coins" />	<?php echo __("Costs",'ProjectTheme'); ?> </div>
			<div class="col-md-8 column-details-2"><?php echo ProjectTheme_average_bid(get_the_ID()); ?></div>
	</div>

<?php } ?>






						 <?php

			 $ProjectTheme_enable_project_location = get_option('ProjectTheme_enable_project_location');
			 if($ProjectTheme_enable_project_location == "yes"):

			 ?>
			 <div class="row">
	 <div class="col-md-4 column-details-1"><img src="<?php echo get_template_directory_uri(); ?>/images/loc_icon.png" width="18" height="18" alt="location" />
		 
	<?php echo __("Location",'ProjectTheme'); ?> </div>
	 <div class="col-md-8 column-details-2"><?php echo get_the_term_list( get_the_ID(), 'project_location', '', ', ', '' ); ?></div>
 </div>


 <div class="row">
	 
<div class="col-md-4 column-details-1"><img src="<?php echo get_template_directory_uri(); ?>/images/loc_icon.png" width="18" height="18" alt="location" />
	

<?php echo __("Address",'ProjectTheme'); ?> </div>
<div class="col-md-8 column-details-2"><?php echo $location; ?></div>
</div>





									 <?php endif; ?>


							 <div class="row">
			<div class="col-md-4 column-details-1"><img src="<?php echo get_template_directory_uri(); ?>/images/cate_icon.png" width="18" height="18" alt="category" />
			 <?php echo __("Category",'ProjectTheme'); ?></div>
			<div class="col-md-8 column-details-2"><?php echo get_the_term_list( get_the_ID(), 'project_cat', '', ', ', '' ); ?></div>
		</div>


	<div class="row">
	<div class="col-md-4 column-details-1"><img src="<?php echo get_template_directory_uri(); ?>/images/cate_icon.png" width="18" height="18" alt="category" />
	<?php echo __("Skills",'ProjectTheme'); ?></div>
	<div class="col-md-8 column-details-2"><?php



		 $my_arr 	= array();
		 $cat 		= wp_get_object_terms(get_the_ID(), 'project_skill');



		 if(is_array($cat))
		 {
			 	if(count($cat) == 0)
				{
						echo __('n/a','ProjectTheme');
				}
				else {

				 foreach($cat as $c)
				 {
						 echo '<h5 class="my-badge">'.$c->name.'</h5>';
				 }
			 }
	 	 }
		 else
		 echo __('n/a','ProjectTheme');

		 ?>


	</div>
	</div>


			 <div class="row">
					 <div class="col-md-4 column-details-1"><img src="<?php echo get_template_directory_uri(); ?>/images/cal_icon.png" width="18" height="18" alt="calendar" />
					 <?php echo __("Posted on",'ProjectTheme'); ?>  </div>
					 <div class="col-md-8 column-details-2"><?php the_time("jS F Y "); ?></div>
				 </div>



				 <div class="row">
	 <div class="col-md-4 column-details-1"><img src="<?php echo get_template_directory_uri(); ?>/images/clock_icon.png" width="18" height="18" alt="clock" />
		<?php echo __("Project Deadline",'ProjectTheme'); ?></div>
	 <div class="col-md-8 column-details-2"><?php echo date_i18n("d-M-Y",get_post_meta(get_the_ID(), 'deadline', true)); ?></div>
 </div>
						 
							 
							 <div class="row">
											 <div class="col-md-4 column-details-1">	<img src="<?php echo get_template_directory_uri() ?>/images/prop_icon.png" width="18" height="18" alt="Proposals" />		 
													 
					<?php echo __("Proposals",'ProjectTheme'); ?></div>
					 <div class="col-md-8 column-details-2"><?php echo projectTheme_number_of_bid(get_the_ID()); ?></div>
				 </div>


				 <?php

 $my_arrms = true;
 $my_arrms = apply_filters('ProjectTheme_show_fields_in_sidebar', $my_arrms);

 if($my_arrms == true):

 $arrms = ProjectTheme_get_project_fields_values(get_the_ID());



 if(count($arrms) > 0)
	 for($i=0;$i<count($arrms);$i++)
	 {

 ?>
			 <div class="row">
	 <div class="col-md-4 column-details-1">	<img src="<?php echo get_template_directory_uri() ?>/images/prop_icon.png" width="18" height="18" alt="Proposals" /> <?php echo $arrms[$i]['field_name'];?>:</div>
					 <div class="col-md-8 column-details-2"><?php echo $arrms[$i]['field_value'];?></div>
				 </div>
 <?php } endif; ?>



			 </div>



							 </div>
							 <!-- ########### -->



					 </div>


					 <?php  else:
	 // project closed
	 ?>

					 <div class="bid_panel">


						 <?php

		 $pid 	= get_the_ID();
		 $winner = get_post_meta(get_the_ID(), 'winner', true);

		 if(!empty($winner))
		 {

			 global $wpdb;
			 $q = "select bid from ".$wpdb->prefix."project_bids where pid='$pid' and winner='1'";
			 $r = $wpdb->get_results($q);
			 $r = $r[0];

			 echo '  <div class="p-3">';

			 printf(__("Project closed for price: %s",'ProjectTheme'), ProjectTheme_get_show_price($r->bid));


			 echo ' </div>';

		 }

		 ?>


					 </div>

					 <?php endif; ?>



		 </div>


	</div>






			 <!-- ####################### -->
	<?php

	$private_bids = get_post_meta(get_the_ID(), 'private_bids', true);

	?>



	<h3 class="my-account-headline-1"><?php echo __('Description','ProjectTheme'); ?> <?php

	?></h3>

					<div class="card p-4">
						<div class="description-content"><?php the_content() ?></div>
						
							 </div>





	<h3 class="my-account-headline-1 mt-3"><?php echo __('Related Projects','ProjectTheme'); ?></h3>



	<?php

	if(empty($limit) || !is_numeric($limit)) $limit = 3;



			global $wpdb, $custom_post_project_type_name;
			$querystr = "
			 SELECT distinct wposts.*
			 FROM $wpdb->posts wposts, $wpdb->postmeta wpostmeta
			 WHERE wposts.ID = wpostmeta.post_id
			 AND wpostmeta.meta_key = 'closed'
			 AND wpostmeta.meta_value = '0' AND
			 wposts.post_status = 'publish'
			 AND wposts.post_type = '$custom_post_project_type_name'
			 ORDER BY wposts.post_date DESC LIMIT ".$limit;

			$pageposts = $wpdb->get_results($querystr, OBJECT);

			?>

				<?php $i = 0; if ($pageposts): ?>
				<?php global $post; ?>
									<?php foreach ($pageposts as $post): ?>
									<?php setup_postdata($post); ?>


									<?php projectTheme_get_project_front_end(); ?>


								<?php endforeach; endif;

								wp_reset_postdata();

								?>







	</div>


	<script>

	jQuery(document).ready(function(){

				//apply-for-this-project

				if(jQuery( window ).width() < 600)
				{

							jQuery("#mainsc").prepend("<ul class='sidebar-ul' id='preps'></ul>");
							jQuery("#apply-for-this-project").prependTo( jQuery("#preps") );

				}

	});

	</script>

	<?php

	echo '<div class="col-xs-12 col-sm-4 col-md-4 col-lg-4"><div class="page-sidebar">';
	echo '<ul class="sidebar-ul">';




	if(get_current_user_id() == $post->post_author)
	{
	?>

			 <li class="widget-container widget_text" id="ad-other-details">

						 <div class="mb-3"><?php printf(__('You are the owner of this Trip.','ProjectTheme')); ?></div>

						 <div class="row">
						 <div class="col col-sm-6">	<a  href="<?php echo home_url(); ?>/?p_action=edit_project&pid=<?php the_ID(); ?>" class="btn btn-outline-primary btn-sm btn-block"><?php _e("Edit",'ProjectTheme'); ?></a> </div>
						 <div class="col col-sm-6">		<a   href="<?php echo home_url(); ?>/?p_action=repost_project&pid=<?php the_ID(); ?>" class="btn btn-outline-primary btn-sm btn-block"><?php _e("Repost",'ProjectTheme'); ?></a> </div>
					 </div>


			 </li>

	<?php
	}

	?>
	<li class="widget-container widget_text"  >


		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css">
		<script src="https://cdn.jsdelivr.net/sharer.js/latest/sharer.min.js"></script>
		<script>

		document.addEventListener("DOMContentLoaded", function(event) {

		// Uses sharer.js
		//  https://ellisonleao.github.io/sharer.js/#twitter
		   var url = window.location.href;
		   var title = document.title;
		   var subject = "Read this good article";
		   var via = "yourTwitterUsername";
		   //console.log( url );
		   //console.log( title );

		//facebook
		jQuery('#share-wa').attr('data-url', url).attr('data-title', title).attr('data-sharer', 'whatsapp');
		//facebook
		jQuery('#share-fb').attr('data-url', url).attr('data-sharer', 'facebook');
		//twitter
		jQuery('#share-tw').attr('data-url', url).attr('data-title', title).attr('data-via', via).attr('data-sharer', 'twitter');
		//linkedin
		jQuery('#share-li').attr('data-url', url).attr('data-sharer', 'linkedin');
		// google plus

		  // email
		  jQuery('#share-em').attr('data-url', url).attr('data-title', title).attr('data-subject', subject).attr('data-sharer', 'email');

		//Prevent basic click behavior
		jQuery( ".sharer button" ).click(function() {
		  event.preventDefault();
		});


		// only show whatsapp on mobile devices
		var isMobile = false; //initiate as false
		// device detection
		if(/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|ipad|iris|kindle|Android|Silk|lge |maemo|midp|mmp|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows (ce|phone)|xda|xiino/i.test(navigator.userAgent)
		    || /1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i.test(navigator.userAgent.substr(0,4))) {
		    isMobile = true;
		}

		if ( isMobile == true ) {
		jQuery("#share-wa").hide();
		}







		});


		</script>

	<div class="social align-center">
	    <a href="#" id="share-wa" class="sharer button"><i class="fa fa-2x fa-whatsapp"></i></a>



	  <a href="#" id="share-fb" class="sharer button"><i class="fa fa-2x fa-facebook-square"></i></a>
	  <a href="#" id="share-tw" class="sharer button"><i class="fa fa-2x fa-twitter-square"></i></a>
	  <a href="#" id="share-li" class="sharer button"><i class="fa fa-2x fa-linkedin-square"></i></a>
	  <a href="#" id="share-gp" class="sharer button"><i class="fa fa-2x fa-google-plus-square"></i></a>
	  <a href="#" id="share-em" class="sharer button"><i class="fa fa-2x fa-envelope-square"></i></a>
	</div>

</li>


	<?php if(get_current_user_id() == $post->post_autor) $is_author = 1; ?>
	<?php if(get_current_user_id() != $post->post_author) { ?>


	<li class="widget-container widget_text" id="apply-for-this-project">



	<h3 class="widget-title text-center"><?php _e("Apply for this Project",'ProjectTheme'); ?></h3>


	<?php


		$ProjectTheme_payment_model = get_option('ProjectTheme_payment_model');
		if($ProjectTheme_payment_model == "pay_for_contact")
		{
				// pay for contact details

				if(!is_user_logged_in())
				{
						?>

								<a href="#" data-toggle="modal" data-target="#contactDetailsModal" class="btn btn-primary btn-block"><?php _e('Pay for contact details','ProjectTheme') ?></a>

						<?php

							get_template_part('lib/pay-for-contact-details');
				}
				else {
					// code...

					$reveal_details_ = get_post_meta($pid,'reveal_details_' . $uid,true);
					if($reveal_details_ == "paid")
					{

								$post_author = get_userdata($post->post_author);

								?>

											<p><?php echo sprintf(__('The user contact details: %s','ProjectTheme'), $post_author->user_email) ?></p>
								<?php
					}
					else {


					?>

								<a href="#" data-toggle="modal" data-target="#contactDetailsModal" class="btn btn-primary btn-block"><?php _e('Pay for contact details','ProjectTheme') ?></a>

					<?php

								get_template_part('lib/pay-for-contact-details');

				}
			}

		}
		else {


	 ?>


	<?php if($closed != 1) { ?>	<p class="text-center">
	 <?php _e('You can use the button below to apply and submit a proposal for this project.','ProjectTheme') ?>
	 </p>

	<?php } ?>


	<p id='proposal_btn_p' style="text-align:center">
		<?php

					 global $current_user, $post;
					 $current_user = wp_get_current_user();
					 $uid = $current_user->ID;




					 if($closed == "0" and !is_user_logged_in())
					 {
					 ?>


					 <a href="#" class="btn btn-primary btn-lg btn-block mb-2" data-toggle="modal" data-target="#bidding-panel-modal" ><?php _e('Submit a Proposal','ProjectTheme'); ?></a>

	<?php

	global $myown_project_bid; $myown_project_bid = get_the_ID();
	get_template_part('lib/bidding-panel');


					 }
					 else
					 {

					 if($closed == "0" && ProjectTheme_is_user_provider(get_current_user_id()) == true):


						 $cc = projectTheme_get_bid_by_uid(get_the_ID(), get_current_user_id());


						 if($cc != false)
						 {

							 	if(pt_see_if_bid_id_has_files(get_the_ID(), get_current_user_id()) > 0)
								{

								 ?>

										<div class="alert alert-secondary mb-3 text-center"><?php _e('Your bid has files added. Edit them with the button below','ProjectTheme'); ?></div>

									<?php } ?>


										 <div class="w-100 mb-3">
											 <a href="#" class="btn btn-primary btn-lg btn-block" data-toggle="modal" data-target="#bidding-panel-modal"  ><?php printf(__('EDIT BID %s','ProjectTheme'), projecttheme_get_show_price($cc->bid) . ($hourly_paid == "1" ? "/hr" : "")); ?></a>
										 </div>

							 			  <div class="w-100">
										 <a class="btn btn-outline-warning btn-sm btn-block mb-3" href="<?php echo get_site_url() ?>/?p_action=retract_bid&id=<?php echo $cc->id ?>"><?php _e('Retract your bid','ProjectTheme'); ?></a>
										  </div>

								 <?php
										 global $myown_project_bid; $myown_project_bid = get_the_ID();
										 include 'lib/bidding-panel.php';

						 }
						 else
						 {

							 global $myown_project_bid; $myown_project_bid = get_the_ID();
							 include 'lib/bidding-panel.php';
				 ?>

					 <a href="#" class="btn btn-primary btn-lg btn-block mb-2 mt-2" data-toggle="modal" data-target="#bidding-panel-modal" ><?php _e('Submit a Proposal','ProjectTheme'); ?></a>


						 <?php } else: ?>

					 <?php _e('Project is closed, or you cannot bid due to restrictions.','ProjectTheme'); ?>

				 <?php endif; } ?>






	</p>

	<?php





	if(function_exists('lv_pp_myplugin_activate') and $is_author != 1)
	{
		global $post;


		if(is_user_logged_in()) $link = projecttheme_get_pm_link_from_user(get_current_user_id(), $post->post_author);
		else $link = projecttheme_get_pm_link_from_user(0, 0);

	 ?>
	<p>
						 <a href="<?php echo $link ?>" class="btn btn-outline-primary btn-lg btn-block" ><?php _e('Chat With User','ProjectTheme'); ?></a>

	</p>
	<?php } ?>

<?php
			$allow = false;

			$ProjectTheme_allow_before_placing_a_bid = get_option('ProjectTheme_allow_before_placing_a_bid');
			if($ProjectTheme_allow_before_placing_a_bid == "no")
			{
				 $bid = projectTheme_get_bid_by_uid(get_the_ID(), get_current_user_id());
				 if($bid == false) $allow = false;
				 else $allow = true;
			}
			else $allow = true;


			if($allow)
			{
 ?>
	<p>
	<a href="<?php

							 $post = get_post(get_the_ID());
							 if($current_user->ID == $post->post_author)
							 echo '#';
							 else
							 echo ProjectTheme_get_priv_mess_page_url('send', '', '&uid='.$post->post_author.'&pid='.get_the_ID());

						 ?>" class="btn btn-outline-primary btn-sm btn-block"><?php _e('Contact Project Owner','ProjectTheme') ?></a></p> <?php } ?>





	</li> <?php  } ?>




		<?php


			 $ProjectTheme_enable_project_message_board = get_option('ProjectTheme_enable_project_message_board');
			 if($ProjectTheme_enable_project_message_board != "no")
			 {

		 ?>	<li>
						<p>		<a href="<?php echo get_site_url() ?>/?p_action=project-message-board&pid=<?php the_ID() ?>" class=" btn btn-outline-primary btn-sm btn-block " ><?php _e('Project Message Board','ProjectTheme') ?></a> </p>


	</li>


<?php } }//end else pay contact details ?>






	<li class="widget-container widget_text" id="ad-other-details">
	<h3 class="widget-title text-center"><?php _e("Trips Posted By",'ProjectTheme'); ?></h3>

	 <div class="row">

			 <div class=" col-md-12 text-center mt-4 "><img width="150" height="150" border="0" class="project-single-avatar rounded-circle" src="<?php echo ProjectTheme_get_avatar($post->post_author, 160, 160); ?>" /> </div>


				 <div class=" col-md-12 text-center mt-4 mb-3"><a class="avatar-posted-by-username" href="<?php echo ProjectTheme_get_user_profile_link($post->post_author); ?>"><h5><?php echo project_theme_get_name_of_user($post->post_author) ?></h5></a></div>
				 <div class=" col-md-12 text-center"><?php echo ProjectTheme_project_get_star_rating($post->post_author); ?></div>
				 <div class=" col-md-12 text-center"><a href="<?php echo ProjectTheme_get_user_feedback_link($post->post_author); ?>"><?php _e('View User Feedback','ProjectTheme'); ?></a></div>



	 </div>

	 <p>


					 <?php

		 $has_created 	= projectTheme_get_total_number_of_created_Projects($post->post_author);
		 $has_rated 		= projectTheme_get_total_number_of_rated_Projects($post->post_author);

	 ?>


	 <div class="d-flex  mb-0">
				 <div class="mr-auto p-2"><i class="fas fa-folder"></i> <?php _e("Has created:",'ProjectTheme');?></div>
				 <div class="p-2 "><?php echo sprintf(__("%s Trips(s)",'ProjectTheme'), $has_created); ?></div>
	 </div>



	 <div class="d-flex  mb-0">
				 <div class="mr-auto p-2"><i class="fas fa-certificate"></i> <?php _e("Has rated:",'ProjectTheme');?></div>
				 <div class="p-2 "><?php echo sprintf(__("%s Trips(s)",'ProjectTheme'), $has_rated); ?></div>
	 </div>





				 <br/><br/>
					<a href="<?php echo ProjectTheme_get_user_profile_link($post->post_author, 'projects'); ?>" class="btn btn-outline-primary btn-sm btn-block"><?php _e('See More Trips by this user','ProjectTheme'); ?></a><br/>

		
		</p>

	</p>
	</li>





	<li class="widget-container widget_text" id="ad-other-details">
	<h3 class="widget-title"><?php _e("Other Details",'ProjectTheme'); ?></h3>
	<p>
	<ul class="other-dets other-dets2">





					 <?php

	 $rt = get_option('projectTheme_show_project_views');

	 if($rt != 'no'):
	 ?>

	 <li>
		 <img src="<?php echo get_template_directory_uri(); ?>/images/viewed.png" width="15" height="15" />
		 <h3><?php _e("Viewed",'ProjectTheme');?>:</h3>
		 <p><?php echo $views; ?> <?php _e("times",'ProjectTheme');?></p>
	 </li>
	 <?php endif; ?>




	</ul>
	<?php

	 if(ProjectTheme_is_owner_of_post())
	 {

	 ?>

	<div class="row pt-4">


	<div class="col col-sm-6">	<a  href="<?php echo home_url(); ?>/?p_action=edit_project&pid=<?php the_ID(); ?>" class="btn btn-primary btn-block"><?php _e("Edit",'ProjectTheme'); ?></a> </div>
	<div class="col col-sm-6">		<a   href="<?php echo home_url(); ?>/?p_action=repost_project&pid=<?php the_ID(); ?>" class="btn btn-primary btn-block"><?php _e("Repost",'ProjectTheme'); ?></a> </div>
	<!--	<a href="<?php echo home_url(); ?>/?p_action=delete_project&pid=<?php the_ID(); ?>" class="nice_link"><?php _e("Delete",'ProjectTheme'); ?></a> -->


	</div>
	<?php } else {?>

	<div class="row pt-4">


	<div class="col col-sm-6">	<a href="#" id="report-this-link" class="btn btn-primary btn-block"><?php _e("Report",'ProjectTheme'); ?></a></div>

	<?php
	if($allow)
	{

		 ?>
	 <div class="col col-sm-6">    <a href="<?php
			 $post = get_post(get_the_ID());


	echo ProjectTheme_get_priv_mess_page_url('send', '', '&uid='.$post->post_author.'&pid='.get_the_ID());

	?>" class="btn btn-primary btn-block"><?php _e("Contact Seller",'ProjectTheme'); ?></a></div> <?php } ?> </div>

					 <?php } ?>
	</p>
	</li>


	<?php

			 dynamic_sidebar( 'project-widget-area' );
	echo '</ul>';
	echo '</div></div>';


	//===============================================================================================
	?>

	<?php

	$ProjectTheme_adv_code_project_page_below_content = get_option('ProjectTheme_adv_code_project_page_below_content');
	if(!empty($ProjectTheme_adv_code_project_page_below_content))
	{
	echo '<div class="padd10 full_width" style="padding-top:0">'.$ProjectTheme_adv_code_project_page_below_content.'</div> <div class="clear10"></div>';

	}


	?>



	</div></div>


<?php

/// ending home part ----------------------------------

} elseif($pg == "description") { ?>

	<h5 class="my-account-headline-1"><?php echo __('Project Description','ProjectTheme'); ?></h5>

<div class="card project-description-box p-4">



	<?php the_content();

	do_action('ProjectTheme_after_description_in_single_proj_page');

	 ?>

</div>


<?php } elseif($pg == "pics") { ?>

<?php

$ProjectTheme_enable_images_in_projects = get_option('ProjectTheme_enable_images_in_projects');
$ProjectTheme_enable_images_in_projects = apply_filters('ProjectTheme_enable_images_in_projects_hk', $ProjectTheme_enable_images_in_projects);

if($ProjectTheme_enable_images_in_projects == "yes"):

?>

<!-- ####################### -->



<div class="card">


						<div class="box_content3">
														<div class="padd20">
<?php

$arr = ProjectTheme_get_post_images(get_the_ID());
$xx_w = 600;
$projectTheme_width_of_project_images = get_option('projectTheme_width_of_project_images');

if(!empty($projectTheme_width_of_project_images)) $xx_w = $projectTheme_width_of_project_images;
if(!is_numeric($xx_w)) $xx_w = 600;

if($arr)
{


echo '<ul class="image-gallery">';
foreach($arr as $image)
{
echo '<li><a href="'.ProjectTheme_generate_thumb($image, 900,$xx_w).'" data-toggle="lightbox" data-gallery="example-gallery" ><img src="'.ProjectTheme_generate_thumb($image, 100,80).'" width="100" class="img_class" /></a></li>';
}
echo '</ul>';

}
else { echo __('No images.','ProjectTheme') ;}

?>


		</div>
						</div>
</div>



<h5 class="my-account-headline-1"><?php _e("Project Files",'ProjectTheme'); ?></h5>
<div class="card">
	<?php
	//---------------------
	// build the exclude list
	//---------------------
	// build the exclude list
	$exclude = array();

	$args = array(
	'order'          => 'ASC',
	'post_type'      => 'attachment',
	'post_mime_type' => 'image',
	'post_parent'    => get_the_ID(),
	'numberposts'    => -1,
	'post_status'    => null,
	);

	$attachments = get_posts($args);

	foreach($attachments as $att) $exclude[] = $att->ID;

	//-0------------------



	$args = array(
	'order'          => 'ASC',
	'post_type'      => 'attachment',
	'meta_key' => 'is_bidding_file',
	'meta_value' => '1',
	'post_parent'    => get_the_ID(),
	'numberposts'    => -1,
	'post_status'    => null,
	);

	$attachments = get_posts($args);

	foreach($attachments as $att) $exclude[] = $att->ID;

	//------------------

	$args = array(
	'order'          => 'ASC',
	'post_type'      => 'attachment',
	'post_parent'    => get_the_ID(),
	'exclude'    => $exclude,
	'numberposts'    => -1,
	'post_status'    => null,
	);
	$attachments = get_posts($args);
	 ?>


	 <?php

						$ProjectTheme_enable_project_files = get_option('ProjectTheme_enable_project_files');
						if($ProjectTheme_enable_project_files != "no"):

					 ?>





		<ul class="list-group">
		<?php

		if(count($attachments) == 0) echo '<li class="list-group-item">'.__('No project files.','ProjectTheme').'</li>';

		foreach($attachments as $at)
		{



		?>

						<li class="list-group-item"> <a href="<?php echo wp_get_attachment_url($at->ID); ?>"><?php echo $at->post_title; ?></a>
		</li>
	 <?php }   ?>
	 </ul>

	 <?php endif; ?>


</div>

<?php endif; ?>



<?php } elseif($pg == "map") { ?>


	<?php

	$ProjectTheme_enable_project_location = get_option('ProjectTheme_enable_project_location');
	if($ProjectTheme_enable_project_location == "yes"):

?>


				<h3 class="my-account-headline-1"><?php echo __('Map Location','ProjectTheme'); ?></h3>


		<div id="map" style="float:left;width: 100%; height: 300px;border:1px solid #ccc;float:left; border-radius:5px" class="mb-4"></div>

						<script type="text/javascript" src="https://maps.google.com/maps/api/js?key=<?php echo get_option('ProjectTheme_radius_maps_api_key') ?>&sensor=false"></script>

				<script type="text/javascript"
				src="<?php echo get_template_directory_uri(); ?>/js/mk.js"></script>
																						<script type="text/javascript">




var geocoder;
var map;
function initialize() {
geocoder = new google.maps.Geocoder();
var latlng = new google.maps.LatLng(-34.397, 150.644);
var myOptions = {
	zoom: 13,
	center: latlng,
	mapTypeId: google.maps.MapTypeId.ROADMAP
}
map = new google.maps.Map(document.getElementById("map"), myOptions);
}

function codeAddress(address) {

geocoder.geocode( { 'address': address}, function(results, status) {
	if (status == google.maps.GeocoderStatus.OK) {
		map.setCenter(results[0].geometry.location);
		var marker = new MarkerWithLabel({

				position: results[0].geometry.location,
	map: map,
	 labelContent: address,
	 labelAnchor: new google.maps.Point(22, 0),
	 labelClass: "labels", // the CSS class for the label
	 labelStyle: {opacity: 1.0}

		});
	} else {
		//alert("Geocode was not successful for the following reason: " + status);
	}
});
}

initialize();

codeAddress("<?php

global $post;
$pid = $post->ID;

$terms = wp_get_post_terms($pid,'project_location');
foreach($terms as $term)
{
echo $term->name." ";
}

$location = get_post_meta($pid, "Location", true);
echo $location;

?>");

</script>


 <?php endif; ?>

<?php } ?>


<?php endwhile; // end of the loop.
wp_reset_postdata();


?>
<?php
	get_footer();
?>
