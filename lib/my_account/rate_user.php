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

		global $wpdb, $current_user, $wpdb;
		$current_user = wp_get_current_user();


 		$id = $wp_query->query_vars['rid'];
		$s = "select * from ".$wpdb->prefix."project_ratings where id='$id'";
		$result = $wpdb->get_results($s);

		$row = $result[0];
		$pid = $row->pid;
		$user = get_userdata($row->touser);
		$post_pr = get_post($row->pid);

 		$my_uid = $row->touser;

 		//-------------------------

 		$fromuser = $row->fromuser;
 		if($current_user->ID != $fromuser) { wp_redirect(home_url()); exit; }

 		//-------------------------

 		get_header('account');
		include ( 'aside-menu.php'  );
 ?>

 <script>


 jQuery(document).ready(function() {


	 var $star_rating = jQuery('.star-rating .fa-star');

	 var SetRatingStar = function() {
		 return $star_rating.each(function() {
			 if (parseInt($star_rating.siblings('input.rating-value').val()) >= parseInt(jQuery(this).data('rating'))) {
				 return jQuery(this).removeClass('far').addClass('fas');
			 } else {
				 return jQuery(this).removeClass('fas').addClass('far');
			 }
		 });
	 };

	 $star_rating.on('click', function() {
		 $star_rating.siblings('input.rating-value').val(jQuery(this).data('rating'));
		 return SetRatingStar();
	 });

	 SetRatingStar();

 });

 </script>

 <style>
 .star-rating {
   line-height:32px;
   font-size:1.35em;
 }

 .star-rating .fa-star{color: #efd300;}

 </style>

 <div class="page-wrapper" style="display:block">
 	<div class="container-fluid"  >


 	<?php



 	do_action('pt_for_demo_work_3_0');

 	do_action('pt_at_account_dash_top');

 ?>
 <div class="container">
 <div class="row">
 <div class="col-sm-12 col-lg-12">
 <div class="page-header">
 			<h1 class="page-title">
 			<?php printf(__("Review User %s for project %s",'ProjectTheme'), $user->user_login, $post_pr->post_title ) ; ?>
 			</h1>
 		</div></div></div>









 <div class="row">



 <div class="account-main-area col-xs-12 col-sm-8 col-md-12 col-lg-12">


 	<!-- ### -->

             <div class="card p-3">







                <?php
			$nok = 1;

			if(isset($_POST['rateme']))
			{

				$rating = $_POST['rating']*2;
				$id 		= $_POST['rid'];
				$comment = nl2br(strip_tags($_POST['commenta']));

				if(empty($comment)):

					$nok = 1;

					echo '<div class="alert alert-danger">';
					echo __('Please leave a comment with your review.','ProjectTheme');
					echo '</div>';

				else:

					$tm = current_time('timestamp',0);


					$s = "update ".$wpdb->prefix."project_ratings set grade='$rating', datemade='$tm', comment='$comment', awarded='1' where id='$id'";
					$wpdb->query($s);


					echo '<p>';

					$link = get_permalink(get_option('ProjectTheme_my_account_page_id'));
					printf(__("Your rating has been posted.<br/><br/><a href='%s' class='btn btn-sm btn-primary'>Return to your account area</a>","ProjectTheme"),$link);

					echo '</p>';
					$nok = 0;

					//---------------------------

					$cool_user_rating = get_user_meta($my_uid, 'cool_user_rating', true);
					if(empty($cool_user_rating)) update_user_meta($my_uid, 'cool_user_rating', 0);

					//---------------------------

					$cool_user_rating = get_user_meta($my_uid, 'cool_user_rating', true);

					global $wpdb;
					$s = "select grade from ".$wpdb->prefix."project_ratings where touser='$my_uid' AND awarded='1'";
					$r = $wpdb->get_results($s);
					$i = 0; $s = 0;

					if(count($r) > 0)
					{
						foreach($r as $row) // = mysql_fetch_object($r))
						{
							$i++;
							$s = $s + $row->grade;

						}

						$rating2 = round(($s/$i)/2, 2);
						update_user_meta($my_uid, 'cool_user_rating', $rating2);

					}

					ProjectTheme_send_email_on_rated_user($pid, $my_uid, $rating/2);


					//---------------------------

				endif;
			}

			if($nok == 1)
			{

		?>
        <form method="post">
					<input type='hidden' value='<?php echo $_GET['rid'] ?>' name='rid' />

          <div class="form-group">
        	<label class="form-label"><?php echo __('Your Rating:','ProjectTheme'); ?></label>
					<div class="star-rating">
			 <span class="far fa-star" data-rating="1"></span>
			 <span class="far fa-star" data-rating="2"></span>
			 <span class="far fa-star" data-rating="3"></span>
			 <span class="far fa-star" data-rating="4"></span>
			 <span class="far fa-star" data-rating="5"></span>
			 <input type="hidden" name="rating" class="rating-value" value="1">
		 </div>
        </div>

        <div class="form-group">
        		<label class="form-label"><?php echo __('Your Comment:','ProjectTheme'); ?></label>
        	 <textarea name="commenta" class="form-control" rows="5" cols="40" ></textarea>
        </div>



           <div class="form-group">

        	 <input type="submit" name="rateme" class="btn btn-primary" value="<?php _e("Submit Rating","ProjectTheme"); ?>"  /></p>
        </div>




         </form> <?php } ?>


                </div>
                </div>



</div></div></div></div>

<?php get_footer(); ?>
