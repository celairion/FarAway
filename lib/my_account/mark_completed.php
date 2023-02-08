<?php
if(!is_user_logged_in()) { wp_redirect(home_url()."/wp-login.php"); exit; }
//-----------

	add_filter('sitemile_before_footer', 'projectTheme_my_account_before_footer');
	function projectTheme_my_account_before_footer()
	{
		echo '<div class="clear10"></div>';
	}

	//----------

	global $wpdb,$wp_rewrite,$wp_query;

	$id 				= $wp_query->query_vars['id'];
	$order 			= new project_orders($id);
	$order_obj 	= $order->get_order();


	//-----------

	global $current_user;
	$current_user=wp_get_current_user();
	$uid = $current_user->ID;


	$post_pr = get_post($order_obj->pid);

	//---------------------------

	if($uid != $post_pr->post_author) { wp_redirect(home_url()); exit; }

	//---------------------------
	$pid = $order_obj->pid;

	if(isset($_POST['yes']))
	{
		$tm = current_time('timestamp',0);


		if($order->is_order_buyer_completed() == false)
		{

			$order->mark_buyer_completed();
			$projectTheme_get_winner_bid = projectTheme_get_winner_bid($pid);

			ProjectTheme_send_email_on_completed_project_to_bidder($pid, $projectTheme_get_winner_bid->uid);
			ProjectTheme_send_email_on_completed_project_to_owner($pid);

		}

		wp_redirect(ProjectTheme_get_project_link_with_page(get_option('ProjectTheme_my_account_buyer_area'), 'completed'));
		exit;
	}

	if(isset($_POST['no']))
	{
		wp_redirect(ProjectTheme_get_project_link_with_page(get_option('ProjectTheme_my_account_buyer_area'), 'delivered'));
		exit;
	}



	//---------------------------------

	get_header('account');



global $current_user, $wp_query;
$current_user=wp_get_current_user();

$uid = $current_user->ID;



get_template_part ( 'lib/my_account/aside-menu'  );

?>


<div class="page-wrapper" style="display:block">
	<div class="container-fluid"  >


	<?php



	do_action('pt_for_demo_work_3_0');


?>
<div class="container">
<div class="row">
<div class="col-sm-12 col-lg-12">
<div class="page-header">
			<h1 class="page-title">
				<?php  printf(__("Mark the project as completed: %s",'ProjectTheme'), $post_pr->post_title); ?>
			</h1>
		</div></div></div>









<div class="row">



<div class="account-main-area col-xs-12 col-sm-8 col-md-12 col-lg-12">


	<!-- ### -->

            <div class="card p-3">
               <?php

			   printf(__("You are about to mark this project as completed: %s",'ProjectTheme'), $post_pr->post_title); echo '<br/>';
			  _e("The service provider will be notified about this action. After this you can pay the project from your Outstanding Payments section.",'ProjectTheme') ;

			   ?>

                <div class="clear10"></div>

               <form method="post"  >

               <input type="submit" name="yes" class="btn btn-primary btn-sm" value="<?php _e("Yes, Mark Completed!",'ProjectTheme'); ?>" />
               <input type="submit" name="no"  class="btn btn-primary btn-sm" value="<?php _e("No",'ProjectTheme'); ?>" />

               </form>
    </div>
			</div>
			</div>





    </div></div> </div>

<?php

get_footer();

?>
