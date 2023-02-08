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
	$oid = $wp_query->query_vars['oid'];

	$order = new project_orders($oid);
	$order_object = $order->get_order();
	$pid = $order_object->pid;

	//------------

	global $current_user;
	$current_user=wp_get_current_user();
	$uid = $current_user->ID;

	$post_pr = get_post($pid);

	//---------------------------

	if($uid != $order_object->freelancer) { wp_redirect(home_url()); exit; }

	//---------------------------

	if(isset($_POST['yes']))
	{

		$order->mark_freelancer_completed();
		wp_redirect( ProjectTheme_get_project_link_with_page(get_option('ProjectTheme_my_account_freelancer_area'), 'delivered') );

		exit;
	}

	if(isset($_POST['no']))
	{
		wp_redirect( ProjectTheme_get_project_link_with_page(get_option('ProjectTheme_my_account_freelancer_area'), 'pending') );
		exit;
	}



	//---------------------------------

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
			<?php  printf(__("Mark the project delivered: %s",'ProjectTheme'), $post_pr->post_title); ?>
			</h1>
		</div></div></div>




<div class="card p-3">



               <?php

			   printf(__("You are about to mark this project as delivered: %s",'ProjectTheme'), $post_pr->post_title); echo '<br/>';
			  _e("The project owner will be notified to accept the delivery and get you paid.",'ProjectTheme') ;

			   ?>

                <div class="clear10"></div>

               <form method="post"  >

               <input type="submit" name="yes" class="btn btn-primary" value="<?php _e("Yes, Mark Delivered!",'ProjectTheme'); ?>" />
               <input type="submit" name="no" class="btn btn-primary"  value="<?php _e("No",'ProjectTheme'); ?>" />

               </form>
    </div>
			</div>
			</div>





    </div>

<?php

get_footer();

?>
