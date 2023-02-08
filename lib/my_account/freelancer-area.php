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
function pt_freelancer_area_timing_status($row)
{
  $now = current_time( 'timestamp' );
  if($row->completion_date < $now)
  {
      echo '<div class="alert alert-danger alert-smaller-padding"><small class="">'.sprintf(__('You have gone past the deadline with this project.','ProjectTheme')) . '</small></div>';
  }

}

//*******************************************************
//
//      freelancer area
//
//*******************************************************

function pt_freelancer_area_payment_status($row)
{
  $date_format = get_option('date_format');

  $ProjectTheme_payment_model = get_option('ProjectTheme_payment_model');
  if($ProjectTheme_payment_model == "ewallet_only")
  {


        $order = new project_orders($row->id);

        if($order->has_escrow_deposited() == false)
        {
            echo '<div class="alert alert-warning alert-smaller-padding"><small class="">'.sprintf(__('Waiting the customer to deposit escrow.','ProjectTheme')) . '</small></div>';
        }
        else {
          $obj = $order->get_escrow_object();
          echo '<div class="alert alert-success alert-smaller-padding"><small class="">'.sprintf(__('Escrow was deposited on %s.','ProjectTheme'), date_i18n($date_format, $obj->datemade)) . '</small></div>';
        }
  }
  elseif($ProjectTheme_payment_model == "invoice_model_pay_outside")
  {

      // right now nothing here, maybe in the future we can put a message
      // but bills will appear in their finances area

      echo '<div class="alert alert-warning alert-smaller-padding"><small class="">
      '.sprintf(__('The payment for this project is done outside of the website.','ProjectTheme')) . '</small></div>';
  }
  else {
    // code...


    $order = new project_orders($row->id);

    if($order->has_marketplace_payment_been_deposited() == false)
    {
        echo '<div class="alert alert-warning alert-smaller-padding"><small class="">'.sprintf(__('This project has not been paid by the customer.','ProjectTheme')) . '</small></div>';
    }
    else {

      $obj = $order->get_marketplace_payment_object();
      echo '<div class="alert alert-success alert-smaller-padding"><small class="">'.sprintf(__('Payment was sent on %s.','ProjectTheme'), date_i18n($date_format, $obj->datemade)) . '</small></div>';

    }
  }

  do_action('pt_on_freelancer_payment_status', $row);

}


//*******************************************************
//
//      freelancer area
//
//*******************************************************

function project_theme_my_account_freelancer_area_fnc()
{
       ob_start();

				global $current_user, $wp_query;
				$current_user=wp_get_current_user();

				$uid = $current_user->ID;
        $date_format =  get_option( 'date_format' );



        				get_template_part ( 'lib/my_account/aside-menu'  );

				?>


				<div class="page-wrapper" style="display:block">
					<div class="container"  >


					<?php



					do_action('pt_for_demo_work_3_0');


?>
<div class="row">
<div class="col-sm-12 col-lg-12">
<div class="page-header">
              <h1 class="page-title">
                <?php echo sprintf(__('Freelancer Area','ProjectTheme')  ) ?>
              </h1>
            </div></div></div>









<div class="row">



    	<div class="account-main-area col-xs-12 col-sm-8 col-md-12 col-lg-12">



        <?php

        do_action('pt_freelancer_area_at_top');

        			$freelancer_pg = get_option('ProjectTheme_my_account_freelancer_area');
              $pgid = $freelancer_pg;

        			if(empty($_GET['pg'])) $pg = 'home';
        			else $pg = $_GET['pg'];

              //-------- active quotes number-------

              $actq = pt_all_sent_bids_number($uid);
              if($actq > 0)
              {
                  $actq = '<span class="noti-noti2">' . $actq . '</span>';
              }
              else $actq = '';

              //------ pending projects --------

              $orders = new project_orders();
              $pending_projects = $orders->get_number_of_pending_projects_as_freelancer($uid);
              if($pending_projects > 0) $pending_projects = '<span class="noti-noti">' . $pending_projects . '</span>';
              else $pending_projects = '';


        ?>


        <ul class="nav nav-tabs" id="myTab-main" role="tablist">
          <li class="nav-item">
            <a class="nav-link <?php echo $pg == 'home' ? 'active' : '' ?>" id="home-tab"  href="<?php echo ProjectTheme_get_project_link_with_page($freelancer_pg, 'home'); ?>" ><?php printf(__('Active Quotes %s','ProjectTheme'), $actq) ?></a>
          </li>
          <li class="nav-item">
            <a class="nav-link <?php echo $pg == 'pending' ? 'active' : '' ?>" id="profile-tab" href="<?php echo ProjectTheme_get_project_link_with_page($freelancer_pg, 'pending'); ?>"><?php printf(__('Pending Projects %s','ProjectTheme'), $pending_projects) ?></a>
          </li>
          <li class="nav-item">
            <a class="nav-link <?php echo $pg == 'delivered' ? 'active' : '' ?>" id="contact-tab" href="<?php echo ProjectTheme_get_project_link_with_page($freelancer_pg, 'delivered'); ?>"><?php _e('Delivered','ProjectTheme') ?></a>
          </li>

          <li class="nav-item">
            <a class="nav-link <?php echo $pg == 'completed' ? 'active' : '' ?>" id="contact-tab" href="<?php echo ProjectTheme_get_project_link_with_page($freelancer_pg, 'completed'); ?>"><?php _e('Completed','ProjectTheme') ?></a>
          </li>


          <li class="nav-item">
            <a class="nav-link <?php echo $pg == 'cancelled' ? 'active' : '' ?>" id="contact-tab" href="<?php echo ProjectTheme_get_project_link_with_page($freelancer_pg, 'cancelled'); ?>"><?php _e('Cancelled','ProjectTheme') ?></a>
          </li>

        </ul>

        <?php

        global $wpdb;
        $uid = get_current_user_id();

        $current_page = empty($_GET['pj']) ? 1 : $_GET['pj'];

        $amount_per_page = 10;
        $offset = ($current_page -1)*$amount_per_page;



        	if($pg == "home")
        	{
                $prf = $wpdb->prefix;
                $s = "select * from ".$prf."project_bids bids, ".$prf."posts posts,   ".$prf."postmeta pmeta where posts.ID=pmeta.post_id and
                pmeta.meta_key='winner' and pmeta.meta_value='0' and posts.ID=bids.pid and bids.uid='$uid'";
                $r = $wpdb->get_results($s);



         ?>

        	  <div class="card" style="border-top:0">

              <?php

                    if(count($r) > 0)
                    {
                          ?>
                          <div class="p-3">
     <div class="table-responsive">
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
                                                    <td class='text-success'><?php

                                                    $hourly_paid = get_post_meta($row->ID,'hourly_paid',true);
                                                    if($hourly_paid == 1)
                                                    {
                                                         echo projectTheme_get_show_price($row->bid, 2)."/hr";
                                                    }
                                                    else

                                                    echo projectTheme_get_show_price($row->bid, 2);

                                                     ?></td>
                                                    <td><?php echo date_i18n($date_format, $row->datemade) ?></td>
                                                    <td><?php echo  sprintf(__('%s day(s)','ProjectTheme'), $row->days_done)  ?></td>
                                                    <td><a href="<?php echo get_the_permalink( $row->ID ); ?>" class='btn btn-outline-primary btn-sm'><?php echo __('View Project','ProjectTheme') ?></a>
                                                    <?php do_action('projecttheme_freelancer_active_quotes_buttons', $row); ?></td>
                                              </tr>
                                          <?php
                                      }

                               ?>


                           </tbody>
                         </table> </div></div>

                          <?php
                    }
                    else {

               ?>


                <div class="p-3">
                  <?php echo sprintf(__('You do not have any active quotes. <a href="%s">Search</a> for projects.','ProjectTheme'), get_permalink(get_option('ProjectTheme_advanced_search_page_id'))) ?>
                </div>

              <?php } ?>

            </div>


            <?php
          }
            elseif($pg == "pending")
          	{


              $uid = get_current_user_id();

              $prf = $wpdb->prefix;
              $s = "select SQL_CALC_FOUND_ROWS * from ".$prf."project_orders orders where orders.freelancer='$uid' and order_status='0' order by id desc limit $offset, $amount_per_page";
              $r = $wpdb->get_results($s);

              $total_rows   = projecttheme_get_last_found_rows();
              $own_pagination = new own_pagination($amount_per_page, $total_rows, ProjectTheme_get_project_link_with_page($pgid, 'pending'). "&");


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
                                                    pt_freelancer_area_timing_status($row);

                                                     ?>

                                                  </td>

                                                  <td class='text-success'><?php

                                                  $hourly_paid = get_post_meta($row->pid,'hourly_paid',true);
                                                  if($hourly_paid == 1)
                                                  {
                                                       echo projectTheme_get_show_price($row->order_total_amount, 2)."/hr";
                                                  }
                                                  else

                                                  echo projectTheme_get_show_price($row->order_total_amount, 2);


                                                   ?></td>
                                                  <td><?php echo date_i18n($date_format, $row->datemade) ?></td>
                                                  <td <?php if($row->completion_date < $now) echo 'class="text-danger"'; ?>><?php echo  date_i18n($date_format, $row->completion_date) ?></td>
                                                  <td><a href="<?php echo projecttheme_get_workspace_link_from_project_id($row->pid); ?>" class='btn btn-outline-primary btn-sm'><?php echo __('Workspace','ProjectTheme') ?></a>
                                                  <a href="<?php echo home_url(); ?>/?p_action=mark_delivered&oid=<?php echo $row->id ?>" class='btn btn-outline-primary btn-sm'><?php echo __('Mark Delivered','ProjectTheme') ?></a>

                                                  <?php do_action('projecttheme_freelancer_pending_projects', $row); ?>

                                                </td>
                                            </tr>
                                        <?php
                                    }

                             ?>


                         </tbody>
                        </table> <?php echo $own_pagination->display_pagination(); ?>  </div>

                        <?php
                  }
                  else {

             ?>


              <div class="p-3">
                <?php _e('You do not have any pending projects.','ProjectTheme') ?>
              </div>

            <?php } ?>

          </div>

            <?php }
            elseif($pg == "delivered")
            {

              $uid = get_current_user_id();

              $prf = $wpdb->prefix;
              $s = "select SQL_CALC_FOUND_ROWS * from ".$prf."project_orders orders where orders.freelancer='$uid' and order_status='1' order by id='desc' limit $offset, $amount_per_page";
              $r = $wpdb->get_results($s);

              $total_rows   = projecttheme_get_last_found_rows();
              $own_pagination = new own_pagination($amount_per_page, $total_rows, ProjectTheme_get_project_link_with_page($pgid, 'delivered'). "&");


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




                                                            echo '<div class="alert alert-success alert-smaller-padding"><small class="">'.sprintf(__('Waiting for customer to accept the project.','ProjectTheme')) . '</small></div>';

                                                     ?>

                                                  </td>

                                                  <td class='text-success'><?php echo projectTheme_get_show_price($row->order_total_amount, 0) ?></td>
                                                  <td><?php echo date_i18n($date_format, $row->datemade) ?></td>
                                                  <td <?php if($row->completion_date < $now) echo 'class="text-danger"'; ?>><?php echo  date_i18n($date_format, $row->completion_date) ?></td>
                                                  <td><a href="<?php echo projecttheme_get_workspace_link_from_project_id( $pst->ID ); ?>" class='btn btn-outline-primary btn-sm'><?php echo __('Workspace','ProjectTheme') ?></a>

                                                    <?php do_action('projecttheme_freelancer_delivered_projects', $row); ?>
                                                 </td>
                                            </tr>
                                        <?php
                                    }

                             ?>


                         </tbody>
                        </table> <?php echo $own_pagination->display_pagination(); ?>  </div>

                        <?php
                  }
                  else {

             ?>


              <div class="p-3">
                <?php _e('You do not have any delivered projects.','ProjectTheme') ?>
              </div>

            <?php } ?>

                  </div>

            <?php
          }
          elseif($pg == "completed")
          {
            $uid = get_current_user_id();

            $prf = $wpdb->prefix;
            $s = "select SQL_CALC_FOUND_ROWS * from ".$prf."project_orders orders where orders.freelancer='$uid' and order_status='2' order by id='desc' limit $offset, $amount_per_page";
            $r = $wpdb->get_results($s);

            $total_rows   = projecttheme_get_last_found_rows();
            $own_pagination = new own_pagination($amount_per_page, $total_rows, ProjectTheme_get_project_link_with_page($pgid, 'completed'). "&");


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
                                                <td><a href="<?php echo projecttheme_get_workspace_link_from_project_id( $pst->ID ); ?>" class='btn btn-outline-primary btn-sm'><?php echo __('Workspace','ProjectTheme') ?></a>
                                               </td>
                                          </tr>
                                      <?php
                                  }

                           ?>


                       </tbody>
                      </table> <?php echo $own_pagination->display_pagination(); ?>  </div>

                      <?php
                }
                else {

           ?>


            <div class="p-3">
              <?php _e('You do not have any completed projects.','ProjectTheme') ?>
            </div>

          <?php } ?>
  </div>

          <?php }   elseif($pg == "cancelled")
            {
              $uid = get_current_user_id();

              $prf = $wpdb->prefix;
              $s = "select SQL_CALC_FOUND_ROWS * from ".$prf."project_orders orders where orders.freelancer='$uid' and order_status='3' order by id='desc' limit $offset, $amount_per_page";
              $r = $wpdb->get_results($s);

              $total_rows   = projecttheme_get_last_found_rows();
              $own_pagination = new own_pagination($amount_per_page, $total_rows, ProjectTheme_get_project_link_with_page($pgid, 'pending'). "&");


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
                            <th><?php echo __('Price','ProjectTheme') ?></th>
                            <th><?php echo __('Date Made','ProjectTheme') ?></th>
                            <th><?php echo __('Cancelled','ProjectTheme') ?></th>
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
                                                  <td><a href="<?php echo get_permalink($pst->ID) ?>"><?php echo $pst->post_title ?></a>
                                                    <?php

                                                          $order = new project_orders($row->id);

                                                          if($order->has_escrow_deposited() == false)
                                                          {
                                                              echo '<br/><small class="">'.sprintf(__('Waiting the customer to deposit escrow.','ProjectTheme')) . '</small>';
                                                          }
                                                          else {
                                                            $obj = $order->get_escrow_object();

                                                          }




                                                     ?>

                                                  </td>

                                                  <td class='text-success'><?php echo projectTheme_get_show_price($row->order_total_amount, 0) ?></td>
                                                  <td><?php echo date_i18n($date_format, $row->datemade) ?></td>
                                                  <td><?php echo  date_i18n($date_format, $row->cancelled_date) ?></td>
                                                  <td><a href="<?php echo get_the_permalink( $row->ID ); ?>" class='btn btn-outline-primary btn-sm'><?php echo __('Workspace','ProjectTheme') ?></a>
                                                 </td>
                                            </tr>
                                        <?php
                                    }

                             ?>


                         </tbody>
                        </table> <?php echo $own_pagination->display_pagination(); ?>  </div>

                        <?php
                  }
                  else {

             ?>


              <div class="p-3">
                <?php _e('You do not have any cancelled orders.','ProjectTheme') ?>
              </div>

            <?php } ?>
  </div>

            <?php } ?>



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
