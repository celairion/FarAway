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


function ProjectTheme_my_account_area_main_function()
{


 ob_start();
 global $wpdb;
 

			$date_format =  get_option( 'date_format' );

				global $current_user, $wp_query;
				$current_user=wp_get_current_user();

				$uid = $current_user->ID;



				include ( 'aside-menu.php'  );

				?>


				<div class="page-wrapper" style="display:block">
					<div class="container"  >


					<?php



					do_action('pt_for_demo_work_3_0');



?>



<div class="row">
<div class="col-sm-12 col-lg-12">

<?php

if(isset($_GET['payment_ok']))
{
			?>

						<div class="alert alert-success"><?php _e('Your payment was successful. You will receive an email shortly.','ProjectTheme') ?></div>

			<?php

}


if(isset($_GET['payment_ok_not']))
{
			?>

						<div class="alert alert-danger"><?php _e('There was an unknown error.','ProjectTheme') ?></div>

			<?php

}

 ?>


<div class="page-header">
              <h1 class="page-title">
                <?php echo sprintf(__('Welcome, %s','ProjectTheme'), project_theme_get_name_of_user($uid) ) ?>
              </h1>
            </div></div></div>


						<?php

							do_action('pt_at_account_dash_top');

						if(ProjectTheme_is_user_provider($uid)){

									do_action('stripe_connect_thing_notification');

						}

						 ?>

						<div class="card-group">


 						<?php

								$ProjectTheme_payment_model = get_option('ProjectTheme_payment_model');
								if($ProjectTheme_payment_model != "marketplace_gateways") // this is if you are not using the stripe connect or other split payment gateways
								{

						 		?>
						                <div class="card border-right">
						                  <div class="card-body p-3 text-center">
																<div class="text-right text-green">
						                       &nbsp;
						                    </div>
						                    <div class="h1 m-0 text-success"><?php echo projectTheme_get_show_price(ProjectTheme_get_credits(get_current_user_id()), 0) ?></div>
						                    <div class="text-muted mb-4"><?php echo __('Your Balance','ProjectTheme') ?></div>
																<div class=""><a href="<?php echo get_permalink( get_option('ProjectTheme_my_account_payments_id') ); ?>" class="btn btn-secondary btn-sm"><?php echo __('Finances','ProjectTheme') ?></a></div>
						                  </div>
						                </div>

													<?php } ?>

						                <div class="card border-right">
						                  <div class="card-body p-3 text-center">
																<div class="text-right text-green">
						                       &nbsp;
						                    </div>
						                    <div class="h1 m-0"><?php echo ProjectTheme_get_total_nr_of_open_projects_of_uid(get_current_user_id()) ?></div>
						                    <div class="text-muted mb-4"><?php echo __('All Open Projects','ProjectTheme') ?></div>
																<div class=""><a href="<?php
																$pgid = get_option('ProjectTheme_my_account_buyer_area');
																echo ProjectTheme_get_project_link_with_page($pgid, 'home') ?>"
																class="btn btn-secondary btn-sm"><?php echo __('My Projects','ProjectTheme') ?></a></div>
						                  </div>
						                </div>

														<?php

														if(function_exists('lv_pp_myplugin_activate'))
														{
																$mid = get_option('ProjectTheme_my_account_livechat_id');
														}
														else {
															// code...
															$mid = get_option('ProjectTheme_my_account_private_messages_id');
														}

														 ?>
						                <div class="card border-right">
						                  <div class="card-body p-3 text-center">
																<div class="text-right text-green">
						                       &nbsp;
						                    </div>
						                    <div class="h1 m-0"><?php echo projectTheme_get_unread_number_messages(get_current_user_id()) ?></div>
						                    <div class="text-muted mb-4"><?php echo __('Unread Messages','ProjectTheme') ?></div>
																<div class=""><a href="<?php echo get_permalink( $mid ); ?>" class="btn btn-secondary btn-sm"><?php echo __('Messages','ProjectTheme') ?></a></div>
						                  </div>
						                </div>


						                <div class="card">
						                  <div class="card-body p-3 text-center">
						                    <div class="text-right text-green">
						                       &nbsp;
						                    </div>
						                    <div class="h1 m-0"><?php echo pt_show_unawarded_reviews(get_current_user_id()) ?></div>
						                    <div class="text-muted mb-4"><?php echo __('Reviews to award','ProjectTheme') ?></div>
																<div class=""><a href="<?php echo get_permalink( get_option('ProjectTheme_my_account_feedback_id') ); ?>" class="btn btn-secondary btn-sm"><?php echo __('Reviews','ProjectTheme') ?></a></div>
						                  </div>
						                </div>






						            </div>






<div class="row">



    	<div class="account-main-area col-xs-12 col-sm-8 col-md-12 col-lg-12">
<?php





if(ProjectTheme_is_user_business($uid)){



	do_action("pt_account_page_is_customer");

?>


<h3 class="my-account-headline-1"><?php echo __('Latest Posted Projects','ProjectTheme'); ?></h3>






														<?php


																	 global $wp_query, $custom_post_project_type_name;
																	 $query_vars = $wp_query->query_vars;
																	 $post_per_page = 3;


																	 $closed = array(
																			 'key' => 'closed',
																			 'value' => "0",
																			 'compare' => '='
																		 );


																		 $winner = array(
																				 'key' => 'winner',
																				 'value' => "0",
																				 'compare' => '='
																			 );

																	/* $paid = array(
																			 'key' => 'paid',
																			 'value' => "1",
																			 'compare' => '='
																		 ); */

																	 $args = array('post_type' => $custom_post_project_type_name, 'author' => $uid, 'order' => 'DESC', 'orderby' => 'date', 'posts_per_page' => $post_per_page,
																	 'paged' => 1, 'meta_query' => array($winner, $closed), 'post_status' =>array('publish','draft') );

																	 query_posts($args);



																	 if(have_posts()) :
																		 ?>



																		 <?php

																	 while ( have_posts() ) : the_post();

																		 projectTheme_get_post_acc();
																	 endwhile;

																	 $has_posted_yes = 1;

																	 ?>




																	 <?php

																		else:

																			echo '<div class="card section-vbox"><div class="p-3">';
																	 _e("There are no projects yet.",'ProjectTheme');
																	 echo '</div></div>';

																	 endif;

																	 wp_reset_query();


																	 ?>






												<?php
															if(!empty($has_posted_yes)) {  ?>

																<div class="row"><div class="col-sm-12 mb-5">
																		<a href="<?php echo ProjectTheme_get_project_link_with_page(get_option('ProjectTheme_my_account_buyer_area'), 'home') ?>" class="btn btn-outline-secondary btn-sm"><?php _e("See all posted projects",'ProjectTheme'); ?></a>
															 </div></div>

																 <?php }
												 ?>






												 <h3 class="my-account-headline-1"><?php echo __('Active Quotes','ProjectTheme'); ?></h3>




												 				<?php

																global $wpdb;

																$prf = $wpdb->prefix;
																$s = "select SQL_CALC_FOUND_ROWS * from ".$prf."project_bids bids, ".$prf."postmeta pmeta, ".$prf."posts posts where posts.ID=pmeta.post_id and posts.post_type='project' and
																posts.post_status='publish' and posts.post_author='$uid' and pmeta.meta_key='winner' and pmeta.meta_value='0' and bids.pid=posts.ID order by posts.ID desc limit 3";
																$r = $wpdb->get_results($s);


																	?>

																<div class="card" style="border-top:0">

														      <?php

														            if(count($r) > 0)
														            {
														                  ?>
														                  <div class="p-3 table-responsive">
														                  <table class="table table-hover table-outline table-vcenter   card-table">
														                    <thead><tr>

														                      <th><?php echo __('Project Title','ProjectTheme'); ?></th>
														                      <th><?php echo __('Provider','ProjectTheme'); ?></th>
														                      <th><?php echo __('Quote','ProjectTheme') ?></th>
														                      <th><?php echo __('Date Made','ProjectTheme') ?></th>
														                      <th><?php echo __('Timeframe','ProjectTheme') ?></th>
														                      <th><?php echo __('Options','ProjectTheme') ?></th>

														                     </tr></thead><tbody>

														                       <?php

														                              foreach($r as $row)
														                              {

														                                         $provider = get_userdata($row->uid);

														                                  ?>

														                                      <tr>
														                                            <td><a href="<?php echo get_permalink($row->ID) ?>"><?php echo $row->post_title ?></a></td>
														                                            <td><a href="<?php echo get_permalink($row->ID) ?>"><?php echo $provider->user_login ?></a></td>
														                                            <td class='text-success'><?php echo projectTheme_get_show_price($row->bid, 0) ?></td>
														                                            <td><?php echo get_the_date($date_format, $row->datemade) ?></td>
														                                            <td><?php echo  sprintf(__('%s day(s)','ProjectTheme'), $row->days_done) ?></td>
														                                            <td><a href="<?php echo get_the_permalink( $row->ID ); ?>" class='btn btn-outline-primary btn-sm'><?php echo __('View Project','ProjectTheme') ?></a>
														                                            <a href="<?php echo get_the_permalink( $row->ID ); ?>" class='btn btn-outline-success btn-sm'><?php echo __('Choose Winner','ProjectTheme') ?></a></td>
														                                      </tr>
														                                  <?php
														                              }

														                       ?>


														                   </tbody>
														                  </table>  </div>

														                  <?php $has_actv_quotes = 1;
														            }
														            else {

														       ?>


														        <div class="p-3">
														          <?php _e('You do not have any active quotes.','ProjectTheme') ?>
														        </div>

														      <?php } ?>

														 </div>



													 <?php
	 															if(!empty($has_actv_quotes)) {

																			$pgid = get_option('ProjectTheme_my_account_buyer_area');

																	?>

	 																<div class="row"><div class="col-sm-12 mb-5">
	 																		<a href="<?php echo ProjectTheme_get_project_link_with_page($pgid, 'quotes') ?>" class="btn btn-outline-secondary btn-sm"><?php _e("See all active quotes",'ProjectTheme'); ?></a>
	 															 </div></div>

	 																 <?php }	 ?>



											<?php } // end buyer side ?>



											<?php if(ProjectTheme_is_user_provider($uid)){ ?>


<?php

/*********************************************************************************************************************************
*
*			SECTion
*
*********************************************************************************************************************************/

?>

										 <h3 class="my-account-headline-1"><?php echo __('Outstanding Projects','ProjectTheme'); ?></h3>


										<?php


										$uid = get_current_user_id();

										$prf = $wpdb->prefix;
										  $s = "select * from ".$prf."project_orders orders where orders.freelancer='$uid' and order_status='0' order by id desc limit 3";
										$r = $wpdb->get_results($s);


										if(count($r) > 0)
										{
												?>

												<div class="card"><div class="p-3 table-responsive">
                        <table class="table table-hover table-outline table-vcenter   card-table">
                          <thead><tr>

                            <th><?php echo __('Project Title','ProjectTheme'); ?></th>
                            <th><?php echo __('Price','ProjectTheme') ?></th>
                            <th><?php echo __('Date Made','ProjectTheme') ?></th>
                            <th><?php echo __('Completion','ProjectTheme') ?></th>
                            <th><?php echo __('Options','ProjectTheme') ?></th>

                           </tr></thead><tbody>

                             <?php

                             $now = current_time('timestamp');

                                    foreach($r as $row)
                                    {

                                               $provider  = get_userdata($row->freelancer);
                                               $pst       = get_post($row->pid);

                                        ?>

                                            <tr>
                                                  <td><p class="mb-1"><a href="<?php echo get_permalink($pst->ID) ?>"><?php echo $pst->post_title ?></a></p>
                                                    <?php

																										pt_freelancer_area_payment_status($row);

                                                     ?>

                                                  </td>

                                                  <td class='text-success'><?php echo projectTheme_get_show_price($row->order_total_amount, 0) ?></td>
                                                  <td><?php echo date_i18n($date_format, $row->datemade) ?></td>
                                                  <td <?php if($row->completion_date < $now) echo 'class="text-danger"'; ?>><?php echo  date_i18n($date_format, $row->completion_date) ?></td>
                                                  <td><a href="<?php echo projecttheme_get_workspace_link_from_project_id( $row->pid ); ?>" class='btn btn-outline-primary btn-sm'><?php echo __('Workspace','ProjectTheme') ?></a>
                                                  <a href="<?php echo home_url(); ?>/?p_action=mark_delivered&oid=<?php echo $row->id ?>" class='btn btn-outline-primary btn-sm'><?php echo __('Mark Delivered','ProjectTheme') ?></a></td>
                                            </tr>
                                        <?php
                                    }

                             ?>


                         </tbody>
                        </table>
																	</div>	</div>


												<?php $has_outst_yes = 1;
										}
										else {

											echo '<div class="card section-vbox"><div class="p-3">';
										_e("There are no projects yet.",'ProjectTheme');
										echo '</div></div>';

										}

										 ?>






												  <?php
															if(!empty($has_outst_yes)) {

																$freelancer_pg = get_option('ProjectTheme_my_account_freelancer_area');

																?>

																<div class="row"><div class="col-sm-12 mb-5">
																		<a href="<?php echo ProjectTheme_get_project_link_with_page($freelancer_pg, 'pending') ?>" class="btn btn-outline-secondary btn-sm"><?php _e("See all pending projects",'ProjectTheme'); ?></a>
															 </div></div>

														 <?php }  	 ?>

<?php

/*********************************************************************************************************************************
*
*			SECTion
*
*********************************************************************************************************************************/

?>

			<h3 class="my-account-headline-1"><?php echo __('My Active Quotes','ProjectTheme'); ?></h3>



			<?php

			global $wpdb;
			$uid = get_current_user_id();


			$prf = $wpdb->prefix;
			$s = "select * from ".$prf."project_bids bids, ".$prf."posts posts,   ".$prf."postmeta pmeta where posts.ID=pmeta.post_id and
			pmeta.meta_key='winner' and pmeta.meta_value='0' and posts.ID=bids.pid and bids.uid='$uid' limit 3";
			$r = $wpdb->get_results($s);


			?>

			<div class="card" style="border-top:0">

				<?php

							if(count($r) > 0)
							{
										?>
										<div class="p-3">
										<table class="table table-hover table-outline table-vcenter   card-table">
											<thead><tr>

												<th><?php echo __('Project Title','ProjectTheme'); ?></th>
												<th><?php echo __('My Bid','ProjectTheme') ?></th>
												<th><?php echo __('Date Made','ProjectTheme') ?></th>
												<th><?php echo __('Timeframe','ProjectTheme') ?></th>
												<th><?php echo __('Options','ProjectTheme') ?></th>

											 </tr></thead><tbody>

												 <?php

																foreach($r as $row)
																{



																		?>

																				<tr>
																							<td><a href="<?php echo get_permalink($row->ID) ?>"><?php echo $row->post_title ?></a></td>
																							<td class='text-success'><?php echo projectTheme_get_show_price($row->bid) ?></td>
																							<td><?php echo date_i18n($date_format, $row->datemade) ?></td>
																							<td><?php echo  sprintf(__('%s day(s)','ProjectTheme'), $row->days_done)  ?></td>
																							<td><a href="<?php echo get_the_permalink( $row->ID ); ?>" class='btn btn-outline-primary btn-sm'><?php echo __('View Project','ProjectTheme') ?></a></td>
																				</tr>
																		<?php
																}

												 ?>


										 </tbody>
										</table> </div>

										<?php $has_won_yes = 1;
							}
							else {

				 ?>


					<div class="p-3">
						<?php echo sprintf(__('You do not have any active quotes. <a href="%s">Search</a> for projects.','ProjectTheme'), get_permalink(get_option('ProjectTheme_advanced_search_page_id'))) ?>
					</div>

				<?php } ?>

			</div>


			<?php
					if(!empty($has_won_yes)) {  ?>

						<div class="row"><div class="col-sm-12 mb-5">
								<a href="<?php echo   ProjectTheme_get_project_link_with_page($freelancer_pg, 'home') ?>" class="btn btn-outline-secondary btn-sm"><?php _e("See all active quotes",'ProjectTheme'); ?></a>
					 </div></div>

				 <?php } else { echo '<div class="row clear10"></div>'; }	 ?>

<?php



/*********************************************************************************************************************************
*
*			SECTion
*
*********************************************************************************************************************************/

?>


											<?php } // end freelancer side ?>
        <?php




	if(isset($_GET['payment_done']))
	{


						?>
						<div class="saved_thing">
						<?php echo sprintf(__('Your payment was received.','ProjectTheme')  ); ?>
						</div>

						<?php

				}

			if(isset($_GET['prj_not_approved']))
			{

				$psts = get_post($_GET['prj_not_approved']);
		?>

        <div class="saved_thing">
        <?php echo sprintf(__('Your payment was received for the item: <b>%s</b> but your project needs to be approved.
		You will be notified when your project will be approved and live on our website','ProjectTheme'), $psts->post_title ); ?>
        </div>

        	<?php
			}



			?>










        </div></div> <!-- end dif content -->



</div>



<?php get_template_part('lib/my_account/footer-area-account') ?>
</div>

<?php


			$page = ob_get_contents();
			   ob_end_clean();
				 return $page;
}


?>
