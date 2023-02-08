<?php

function ProjectTheme_my_account_feedbacks_area_function()
{

	global $current_user, $wp_query;
	$current_user=wp_get_current_user();

	$uid = $current_user->ID;



	get_template_part ( 'lib/my_account/aside-menu'  );

	?>


	<div class="page-wrapper" style="display:block">
		<div class="container"  >


		<?php



		do_action('pt_for_demo_work_3_0');


?>

<div class="container">

<div class="row">
<div class="col-sm-12 col-lg-12">
<div class="page-header">
				<h1 class="page-title">
					<?php echo sprintf(__('Reviews','ProjectTheme')  ) ?>
				</h1>
			</div></div></div>


<div class="row">



 <div class="account-main-area col-xs-12 col-sm-8 col-md-12 col-lg-12">

<?php

			$reviews_pg = get_option('ProjectTheme_my_account_feedback_id');
			if(empty($_GET['pg'])) $pg = 'home';
			else $pg = $_GET['pg'];


			$revnr = projecttheme_get_number_of_due_ratings($uid);
			if($revnr > 0) $revnr = '<span class="noti-noti">'.$revnr.'</span>';
			else $revnr = '';

?>

	 <ul class="nav nav-tabs" id="myTab-main" role="tablist">
	   <li class="nav-item">
	     <a class="nav-link <?php echo $pg == 'home' ? 'active' : '' ?>" id="home-tab"  href="<?php echo ProjectTheme_get_project_link_with_page($reviews_pg, 'home'); ?>" ><?php echo (sprintf(__('Reviews To Do %s','ProjectTheme'), $revnr)); ?></a>
	   </li>
	   <li class="nav-item">
	     <a class="nav-link <?php echo $pg == 'pending' ? 'active' : '' ?>" id="profile-tab" href="<?php echo ProjectTheme_get_project_link_with_page($reviews_pg, 'pending'); ?>"><?php _e('Pending Reviews','ProjectTheme') ?></a>
	   </li>
	   <li class="nav-item">
	     <a class="nav-link <?php echo $pg == 'myreviews' ? 'active' : '' ?>" id="contact-tab" href="<?php echo ProjectTheme_get_project_link_with_page($reviews_pg, 'myreviews'); ?>"><?php _e('My Reviews','ProjectTheme') ?></a>
	   </li>
	 </ul>



<?php

	if($pg == "home")
	{

 ?>

	  <div class="card" style="border-top:0">
			<div class="table-responsive">

			<?php

global $wpdb;
$query = "select * from ".$wpdb->prefix."project_ratings where fromuser='$uid' AND awarded='0'";
$r = $wpdb->get_results($query);

if(count($r) > 0)
{
	echo '<table class="table table-hover table-outline table-vcenter   card-table">';
		echo '<thead><tr>';

			echo '<th>'.__('Project Title','ProjectTheme').'</th>';
			echo '<th>'.__('To User','ProjectTheme').'</th>';
			echo '<th>'.__('Aquired on','ProjectTheme').'</th>';
			echo '<th>'.__('Price','ProjectTheme').'</th>';
			echo '<th>'.__('Options','ProjectTheme').'</th>';

		echo '</tr></thead><tbody>';


	foreach($r as $row)
	{
		$post 	= $row->pid;
		$post 	= get_post($post);
		$bid 	= projectTheme_get_winner_bid($row->pid);
		$user 	= get_userdata($row->touser);

		$dmt2 = get_post_meta($row->pid,'closed_date',true);

		if(!empty($dmt2))
		$dmt = date_i18n('d-M-Y H:i', $dmt2);

		echo '<tr>';


			echo '<td><a href="'.get_permalink($row->pid).'">'.$post->post_title.'</a></td>';
			echo '<td><a href="'.ProjectTheme_get_user_profile_link($user->ID).'">'.$user->user_login.'</a></td>';
			echo '<td>'.$dmt.'</td>';
			echo '<td>'.projectTheme_get_show_price($bid->bid).'</td>';
			echo '<td><a class="btn btn-outline-primary btn-sm" href="'.home_url().'/?p_action=rate_user&rid='.$row->id.'">'.__('Rate User','ProjectTheme').'</a></td>';

		echo '</tr>';

	}

	echo '</tbody></table>';
}
else
{
	echo '<div class="p-3">'; _e("There are no reviews to be awarded.","ProjectTheme"); echo '</div>';
}
?>


 </div>
		</div>

	<?php } elseif($pg == "pending") { ?>

		<div class="card" style="border-top:0">
			<div class="table-responsive">

				<?php

				global $wpdb;
				$query = "select * from ".$wpdb->prefix."project_ratings where touser='$uid' AND awarded='0'";
				$r = $wpdb->get_results($query);

				if(count($r) > 0)
				{
				echo '<table class="table table-hover table-outline table-vcenter   card-table">';
				echo '<thead><tr>';

				echo '<th>'.__('Project Title','ProjectTheme').'</th>';
				echo '<th>'.__('From User','ProjectTheme').'</th>';
				echo '<th>'.__('Aquired on','ProjectTheme').'</th>';
				echo '<th>'.__('Price','ProjectTheme').'</th>';


				echo '</tr></thead><tbody>';


				foreach($r as $row)
				{
				$post 	= $row->pid;
				$post 	= get_post($post);
				$bid 	= projectTheme_get_winner_bid($row->pid);
				$user 	= get_userdata($row->fromuser);

				$dmt2 = get_post_meta($row->pid,'closed_date',true);

				if(!empty($dmt2))
				$dmt = date_i18n('d-M-Y H:i', $dmt2);

				echo '<tr>';


				echo '<td><a href="'.get_permalink($row->pid).'">'.$post->post_title.'</a></td>';
				echo '<td><a href="'.ProjectTheme_get_user_profile_link($user->ID).'">'.$user->user_login.'</a></td>';
				echo '<td>'.$dmt.'</td>';
				echo '<td>'.projectTheme_get_show_price($bid->bid).'</td>';


				echo '</tr>';

				}

				echo '</tbody></table>';
				}
				else
				{
				echo '<div class="p-3">'; _e("There are no reviews to be awarded.","ProjectTheme"); echo '</div>';
				}
				?>

			</div></div>


	<?php } elseif($pg == "myreviews") { ?>


		<div class="card" style="border-top:0">
			<div class="table-responsive">

				<?php

		global $wpdb;
		$query = "select * from ".$wpdb->prefix."project_ratings where touser='$uid' AND awarded='1'";
		$r = $wpdb->get_results($query);

		if(count($r) > 0)
		{
		echo '<table class="table table-hover table-outline table-vcenter  card-table">';
			echo '<thead><tr>';

				echo '<th>'.__('Project Title','ProjectTheme').'</th>';
				echo '<th>'.__('From User','ProjectTheme').'</th>';
				echo '<th>'.__('Aquired on','ProjectTheme').'</th>';
				echo '<th>'.__('Price','ProjectTheme').'</th>';
				echo '<th>'.__('Rating','ProjectTheme').'</th>';


			echo '</tr></thead><tbody>';


		foreach($r as $row)
		{
			$post 	= $row->pid;
			$post 	= get_post($post);
			$bid 	= projectTheme_get_winner_bid($row->pid);
			$user 	= get_userdata($row->fromuser);

			$dmt2 =  get_post_meta($row->pid,'closed_date',true);

			if(!empty($dmt2))
			$dmt = date_i18n('d-M-Y H:i', $dmt2);

			echo '<tr>';

				echo '<td><a href="'.get_permalink($row->pid).'">'.$post->post_title.'</a></td>';
				echo '<td><a href="'.ProjectTheme_get_user_profile_link($user->ID).'">'.$user->user_login.'</a></td>';
				echo '<td>'.$dmt.'</td>';
				echo '<td>'.projectTheme_get_show_price($bid->bid).'</td>';
				echo '<td>'.floor($row->grade/2).'/5</td>';


			echo '</tr>';
			echo '<tr>';

			echo '<td colspan="5"><b>'.__('Comment','ProjectTheme').':</b> '.$row->comment.'</td>'	;
			echo '</tr>';



		}

		echo '</tbody></table>';
		}
		else
		{
		echo '<div class="p-3">'; _e("There are no reviews to be awarded.","ProjectTheme"); echo '</div>';
		}
		?>

			</div></div>

	<?php } ?>




           <!-- ##### -->







		</div>		</div>


				</div>		</div>

<?php get_template_part('lib/my_account/footer-area-account') ?>
			</div>

<?php



}

?>
