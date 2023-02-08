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

 	session_start();
	global $current_user, $wp_query;
	$pid 	=  $wp_query->query_vars['pid'];

	function ProjectTheme_filter_ttl($title){return __("Delete Project",'ProjectTheme')." - ";}
	add_filter( 'wp_title', 'ProjectTheme_filter_ttl', 10, 3 );

	if(!is_user_logged_in()) { wp_redirect(  esc_url( home_url() ) ."/wp-login.php"); exit; }


	$current_user=wp_get_current_user();

	$post = get_post($pid);

	$uid 	= $current_user->ID;
	$title 	= $post->post_title;
	$cid 	= $current_user->ID;

	$winner = get_post_meta($pid, 'winner', true);

	if(!empty($winner)) { echo 'Project has a winner, cant be deleted. Sorry!'; exit; }
	if($uid != $post->post_author) { echo 'Not your post. Sorry!'; exit; }

//-------------------------------------

get_header('account');

  get_template_part ( 'lib/my_account/aside-menu'  );


?>


<div class="page-wrapper" style="display:block">
	<div class="container-fluid"  >


	<?php



	do_action('pt_for_demo_work_3_0');

	do_action('pt_at_account_dash_top');

?>

<div class="container">

<div class="row">
<div class="col-sm-12 col-lg-8">




<div class="page-header">
			<h1 class="page-title">
			<?php printf(__("Delete Project - %s", "ProjectTheme"), $post->post_title); ?>
			</h1>
		</div></div></div>




<div class="card p-3">



  <?php

if(isset($_POST['are_you_sure']))
{
wp_delete_post($pid);

echo '<p>';
echo sprintf(__("The project has been deleted. <a href='%s' class='btn btn-primary'>Return to your account</a> ",'ProjectTheme'), get_permalink(get_option('ProjectTheme_my_account_page_id')));

echo '</p>';
}
else
{
?>

      <form method="post" enctype="application/x-www-form-urlencoded">
      <?php _e("Are you sure you want to delete this project?",'ProjectTheme'); ?><br/><br/>
      <input class="btn btn-primary" type="submit" name="are_you_sure" value="<?php _e("Confirm Deletion",'ProjectTheme'); ?>"  />
      </form>

   <?php } ?>

    </div>
			</div>
			</div>





    </div>






<?php get_footer(); ?>
