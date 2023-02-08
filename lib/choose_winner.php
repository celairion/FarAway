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


if(!is_user_logged_in()) { wp_redirect(home_url()."/wp-login.php"); exit; }
//-----------

	add_filter('sitemile_before_footer', 'projectTheme_my_account_before_footer');
	function projectTheme_my_account_before_footer()
	{
		echo '<div class="clear10"></div>';
	}

	//----------

	global $wpdb,$wp_rewrite,$wp_query;

	$bid = projectTheme_get_bid_by_id($wp_query->query_vars['bid']);
	$pid = $wp_query->query_vars['pid'];
	$winner = get_post_meta($pid, 'winner', true);

	if(!empty($winner)) {

		if($ProjectTheme_payment_model == "marketplace_gateways")
		{
				// redirect to payment gateway for stripe connect, or other

					wp_redirect(ProjectTheme_get_payments_page_url2('paysplit', $oid)); //get_permalink(get_option('ProjectTheme_my_account_page_id')));

				die();
		}

		//-----

		$projectTheme_enable_paypal_ad = get_option('projectTheme_enable_paypal_ad');
		if($projectTheme_enable_paypal_ad == "yes")
		{
		  wp_redirect(get_permalink(get_option('ProjectTheme_my_account_awaiting_completion_id')));	exit;
		}

		//wp_redirect(get_permalink(get_option('ProjectTheme_my_account_page_id')));
		wp_redirect(ProjectTheme_get_payments_page_url2('escrow', $pid)); //get_permalink(get_option('ProjectTheme_my_account_page_id')));


	 exit;}

//---------------------------------

	$current_user = wp_get_current_user();
	$uid = $current_user->ID;

	$post_p = get_post($pid);

	if($post_p->post_author != $uid) { echo 'ERR. Not your project.'; exit;}

//----------------------------------

	if(isset($_POST['yes']))
	{

		$ProjectTheme_payment_model = get_option('ProjectTheme_payment_model');
		if($ProjectTheme_payment_model == "marketplace_gateways")
		{
					if(!function_exists('stripe_connect_project_theme_temp_redir'))
					{
						echo 'You have enabled the direct payments for projects, but you havent enabled stripe connect or other compatible gateway. Please download the extension from your sitemile.com account and install.';
						die();
					}
		}


		$tm = current_time('timestamp',0);
		update_post_meta($pid, 'closed','1');
		update_post_meta($pid, 'closed_date',$tm);
		update_post_meta($pid,	"is_new_project", "old");
		$expected_delivery = ($bid->days_done * 3600 * 24) + $tm;


		//-----------------create order for bid --------------------------------------------------

		$args = array('completion_date' => $expected_delivery,
								'buyer' 					=> get_current_user_id(),
								'freelancer' 			=> $bid->uid,
								'pid' 						=> $pid,
								'order_net_amount' => $bid->bid,
								'order_total_amount' => $bid->bid);

		$order = new project_orders();
		$oid = $order->insert_order($args);

		//---------

		$uid = $bid->uid;

		projectTheme_prepare_rating($pid, $bid->uid, $post_p->post_author);
		projectTheme_prepare_rating($pid, $post_p->post_author, $bid->uid);

		do_action('ProjectTheme_do_action_on_choose_winner', $bid->id );
		$query = "update ".$wpdb->prefix."project_bids set date_choosen='$tm', winner='1' where id='{$bid->id}'";
		$wpdb->query($query);


		ProjectTheme_send_email_on_win_to_bidder($pid, $uid);
		ProjectTheme_send_email_on_win_to_owner($pid, $uid);


		global $wpdb;
		$s = "select distinct uid from ".$wpdb->prefix."project_bids where uid!='$uid' and pid='$pid'";
		$r = $wpdb->get_results($s);

		foreach($r as $row)
		{
			$looser = $row->uid;
			ProjectTheme_send_email_on_win_to_loser($pid, $looser);
		}


	//-------------------- creating workspace ----------------------------------------------------------------

		$my_post = array( 'post_title'    => sprintf(__('Workspace for project #%s', 'ProjectTheme'),  $pid),
			  'post_status'   => 'publish',
			  'post_author'   => $post_p->post_author,
			  'post_parent' 	=> $pid,
			  'post_type'   	=> 'workspace' );

			// Insert the post into the database
			$nvp = wp_insert_post( $my_post );



			update_post_meta($nvp, 'freelancer' , $bid->uid); //->uid);
			update_post_meta($nvp, 'customer' , $current_user->ID); //->uid);
			update_post_meta($nvp, 'project' , $pid);

		//------------------

		update_post_meta($pid, 'winner', $uid);
		do_action('ProjectTheme_choose_winner',$pid);



		if($ProjectTheme_payment_model == "marketplace_gateways")
		{
				// redirect to payment gateway for stripe connect, or other

					wp_redirect(ProjectTheme_get_payments_page_url2('paysplit', $oid)); //get_permalink(get_option('ProjectTheme_my_account_page_id')));

				die();
		}

		if($ProjectTheme_payment_model == "invoice_model_pay_outside")
		{

				wp_redirect(get_permalink(get_option('ProjectTheme_my_account_buyer_area')));  
				die();
		}

		//wp_redirect(get_permalink(get_option('ProjectTheme_my_account_page_id')));
		wp_redirect(ProjectTheme_get_payments_page_url2('escrow', $oid)); //get_permalink(get_option('ProjectTheme_my_account_page_id')));


		exit;
	}

	if(isset($_POST['no']))
	{
		wp_redirect(get_permalink($pid));
		exit;
	}

//==========================

get_header('account');


	get_template_part ( 'lib/my_account/aside-menu'  );

?>



<div class="page-wrapper" style="display:block">
	<div class="container-fluid"  >


	<?php



	do_action('pt_for_demo_work_3_0');



?>

<div class="container">

<div class="row">
<div class="col-sm-12 col-lg-8">




<div class="page-header">
			<h1 class="page-title">
				<?php  printf(__("Choose Winner for your project: %s",'ProjectTheme'), $post_p->post_title); ?>
			</h1>
		</div></div></div>




<div class="card p-3">
		<?php


					$winner = get_userdata($bid->uid);



		 ?>
		<div class="box_content">
		<div class="mb-0"> <?php printf(__("You are about to choose a winner for your project: %s",'ProjectTheme'), '<b>' . $post_p->post_title . '</b>'); ?> </div>
		<div class="mb-0"> <?php printf(__("Winning Bid: %s",'ProjectTheme'), '<b>' . projectTheme_get_show_price($bid->bid) . '</b>'); ?> </div>
		<div class="mb-0"> <?php printf(__("Winner User: %s",'ProjectTheme'), '<b>' . $winner->user_login . '</b>'); ?> </div>
		<div class="mb-0"> <?php printf(__("Timeframe: <b>%s day(s)</b>",'ProjectTheme'),   $bid->days_done  ); ?> </div>

		<div class="p-3"></div>

		<form method="post" enctype="application/x-www-form-urlencoded">

		<input type="submit" name="yes" class="btn btn-primary" value="<?php _e("Yes, Choose winner Now!",'ProjectTheme'); ?>" />
		<input type="submit" name="no"  class="btn btn-primary" value="<?php _e("No",'ProjectTheme'); ?>" />

		</form>
		</div>

</div>

</div></div></div>




<?php

get_footer();

?>
