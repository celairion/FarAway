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
function pt_freelancer_area_timing_status_customer($row)
{
  $now = current_time( 'timestamp' );
  if($row->completion_date < $now)
  {
      echo '<div class="alert alert-danger alert-smaller-padding"><small class="">'.sprintf(__('Deadline has been delayed.','ProjectTheme')) . '</small></div>';
  }

}
//*******************************************************
//
//      freelancer area
//
//*******************************************************


function pt_show_buyer_payment_status($row)
{
  $date_format = get_option('date_format');


  $ProjectTheme_payment_model = get_option('ProjectTheme_payment_model');
  if($ProjectTheme_payment_model == "ewallet_only")
  {

    $order = new project_orders($row->id);

    if($order->has_escrow_deposited() == false)
    {
        $lnk = ProjectTheme_get_payments_page_url2('escrow', $row->id);
        echo '<div class="alert alert-warning alert-smaller-padding"><small class="">'.sprintf(__('The escrow has not been deposited yet. <a href="%s">Click here</a> to deposit escrow.','ProjectTheme'), $lnk) . '</small></div>';
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
    // payments are done outside the website

      echo '<div class="alert alert-warning alert-smaller-padding"><small class="">
      '.sprintf(__('The payment for this project is done outside of the website.','ProjectTheme')) . '</small></div>';

  }
  else {
    // code...
    //

    $order = new project_orders($row->id);

    if($order->has_marketplace_payment_been_deposited() == false)
    {
        $lnk = ProjectTheme_get_payments_page_url2('paysplit', $row->id);
        echo '<div class="alert alert-warning alert-smaller-padding"><small class="">'.sprintf(__('This project has not been paid. <a href="%s">Click here</a> to make payment.','ProjectTheme'), $lnk) . '</small></div>';
    }
    else {
      $obj = $order->get_marketplace_payment_object();

      echo '<div class="alert alert-success alert-smaller-padding"><small class="">'.sprintf(__('Payment was sent on %s.','ProjectTheme'), date_i18n($date_format, $obj->datemade)) . '</small></div>';

    }
  }

  do_action('pt_on_buyer_payment_status', $row);
}


//*******************************************************
//
//      freelancer area
//
//*******************************************************

function project_theme_my_account_buyer_area_fnc()
{
       ob_start();

				global $current_user, $wp_query, $wpdb;
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
<div class="col-sm-12 col-lg-8">




<div class="page-header">
              <h1 class="page-title">
                <?php echo sprintf(__('Customer Area','ProjectTheme')  ) ?>
              </h1>
            </div></div></div>





            <?php


            do_action('pt_buyer_area_at_top');



                  $pgid = get_option('ProjectTheme_my_account_buyer_area');
                  if(empty($_GET['pg'])) $pg = 'home';
                  else $pg = $_GET['pg'];

                  //------- active quotes number -----


                  $active_quotes = pt_all_received_bids_number($uid);
                  if($active_quotes > 0)
                  {
                        $active_quotes = '<span class="noti-noti">'.$active_quotes.'</span>';
                  } else $active_quotes = '';

                  //---------pending projects ----------

                  $orders = new project_orders();
                  $pending_proj = $orders->get_number_of_open_orders_for_buyer($uid);
                  if($pending_proj > 0)
                  {
                        $pending_proj = '<span class="noti-noti2">'.$pending_proj.'</span>';
                  } else $pending_proj = '';

                  //--- delivered -------------------

                  $delivered_nr = $orders->get_number_of_delivered_orders_for_buyer($uid);
                  if($delivered_nr > 0)
                  {
                        $delivered_nr = '<span class="noti-noti">'.$delivered_nr.'</span>';
                  } else $delivered_nr = '';


                  //------- unpublished -----


                  $unpub = projecttheme_get_number_of_unpublished($uid);

                  if($unpub > 0)
                  {
                        $unpub = '<span class="noti-noti2">'.$unpub.'</span>';
                  } else $unpub = '';


            ?>



<div class="row">



    	<div class="account-main-area col-xs-12 col-sm-8 col-md-12 col-lg-12">


        <ul class="nav nav-tabs" id="myTab-main" role="tablist">

          <li class="nav-item">
            <a class="nav-link <?php echo $pg == 'home' ? 'active' : '' ?>" id="home-tab"  href="<?php echo ProjectTheme_get_project_link_with_page($pgid, 'home'); ?>" ><?php _e('Active Projects','ProjectTheme') ?></a>
          </li>

          <li class="nav-item">
            <a class="nav-link <?php echo $pg == 'quotes' ? 'active' : '' ?>" id="home-tab"  href="<?php echo ProjectTheme_get_project_link_with_page($pgid, 'quotes'); ?>" ><?php printf(__('Active Quotes %s','ProjectTheme'), $active_quotes) ?></a>
          </li>
          <li class="nav-item">
            <a class="nav-link <?php echo $pg == 'pending' ? 'active' : '' ?>" id="profile-tab" href="<?php echo ProjectTheme_get_project_link_with_page($pgid, 'pending'); ?>"><?php printf(__('Pending Projects %s','ProjectTheme'), $pending_proj) ?></a>
          </li>
          <li class="nav-item">
            <a class="nav-link <?php echo $pg == 'delivered' ? 'active' : '' ?>" id="contact-tab" href="<?php echo ProjectTheme_get_project_link_with_page($pgid, 'delivered'); ?>"><?php printf(__('Delivered %s','ProjectTheme'), $delivered_nr) ?></a>
          </li>

          <li class="nav-item">
            <a class="nav-link <?php echo $pg == 'completed' ? 'active' : '' ?>" id="contact-tab" href="<?php echo ProjectTheme_get_project_link_with_page($pgid, 'completed'); ?>"><?php _e('Completed','ProjectTheme') ?></a>
          </li>


          <li class="nav-item">
            <a class="nav-link <?php echo $pg == 'cancelled' ? 'active' : '' ?>" id="contact-tab" href="<?php echo ProjectTheme_get_project_link_with_page($pgid, 'cancelled'); ?>"><?php _e('Cancelled','ProjectTheme') ?></a>
          </li>


          <li class="nav-item">
            <a class="nav-link <?php echo $pg == 'unpublished' ? 'active' : '' ?>" id="contact-tab" href="<?php echo ProjectTheme_get_project_link_with_page($pgid, 'unpublished'); ?>"><?php printf(__('Unpublished %s','ProjectTheme'), $unpub) ?></a>
          </li>

        </ul>


        <?php

          $current_page = empty($_GET['pj']) ? 1 : $_GET['pj'];

          $amount_per_page = 10;
          $offset = ($current_page -1)*$amount_per_page;

          //------------------------------------------------

          if($pg == "home")
          {

             $prf = $wpdb->prefix;
             $s = "select SQL_CALC_FOUND_ROWS * from ".$prf."postmeta pmeta, ".$prf."posts posts where posts.ID=pmeta.post_id and posts.post_type='project' and
             posts.post_status='publish' and posts.post_author='$uid' and pmeta.meta_key='closed' and pmeta.meta_value='0' order by posts.ID desc limit $offset, $amount_per_page";
             $r = $wpdb->get_results($s);

     				 $total_rows   = projecttheme_get_last_found_rows();
     				 $own_pagination = new own_pagination($amount_per_page, $total_rows, ProjectTheme_get_project_link_with_page($pgid, 'home'). "&");



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
                       <th><?php echo __('Budget','ProjectTheme') ?></th>
                       <th><?php echo __('Date Made','ProjectTheme') ?></th>
                       <th><?php echo __('Quotes','ProjectTheme') ?></th>
                       <th><?php echo __('Options','ProjectTheme') ?></th>

                      </tr></thead><tbody>

                        <?php

                               foreach($r as $row)
                               {



                                   ?>

                                       <tr>
                                             <td><a href="<?php echo get_permalink($row->ID) ?>"><?php echo $row->post_title ?></a><br/>
                                                  <small class="nb-padd"><?php printf(__('You have %s active proposals.','ProjectTheme'), projectTheme_number_of_bid($row->ID)) ?></small>
                                             </td>
                                             <td class='text-success'><?php



                                             $hourly_paid = get_post_meta($row->ID,'hourly_paid',true);
                                             if($hourly_paid == 1)
                                             {
                                                 $hourly_rate = get_post_meta($row->ID,'hourly_rate',true);
                                                 echo projecttheme_get_show_price($hourly_rate) . "/hr";
                                             }
                                             else
                                             echo ProjectTheme_get_budget_name_string_fromID(get_post_meta($row->ID,'budgets',true));

                                              ?></td>
                                             <td><?php echo get_the_date($date_format, $row->ID) ?></td>
                                             <td><?php echo  projectTheme_number_of_bid($row->ID) ?></td>
                                             <td><a href="<?php echo get_the_permalink( $row->ID ); ?>" class='btn btn-outline-primary btn-sm'><?php echo __('View Project','ProjectTheme') ?></a></td>
                                       </tr>
                                   <?php
                               }

                        ?>


                    </tbody>
                   </table>

                   <?php echo $own_pagination->display_pagination(); ?>
                 </div>

                   <?php
             }
             else {

        ?>


         <div class="p-3">
           <?php

           $link =get_permalink(get_option('ProjectTheme_post_new_page_id'));

           printf(__('You do not have any active projects. <a href="%s">Click here</a> to post more.','ProjectTheme'), $link); ?>
         </div>

       <?php } ?>

     </div>

     <?php } elseif('quotes' == $pg){


       $uid = get_current_user_id();

       $prf = $wpdb->prefix;

       $s = "select SQL_CALC_FOUND_ROWS * from ".$prf."project_bids bids
       INNER JOIN $wpdb->posts posts ON posts.ID=bids.pid
       INNER JOIN ".$prf."postmeta pmeta ON pmeta.post_id=bids.pid
       WHERE posts.post_author='$uid' AND pmeta.meta_value='0' and pmeta.meta_key='winner' and exists(select * from $wpdb->users users where users.ID=bids.uid) order by bids.id desc limit $offset, $amount_per_page";


       $r = $wpdb->get_results($s);




       $total_rows   = projecttheme_get_last_found_rows();
       $own_pagination = new own_pagination($amount_per_page, $total_rows, ProjectTheme_get_project_link_with_page($pgid, 'quotes'). "&");

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
                                        $pid = $row->pid;
                                 ?>

                                     <tr>
                                           <td><a href="<?php echo get_permalink($pid) ?>"><?php echo $row->post_title ?></a></td>
                                           <td><a href="<?php echo get_permalink($pid) ?>"><?php echo $provider->user_login ?></a></td>
                                           <td class='text-success'><?php

                                           $hourly_paid = get_post_meta($row->ID,'hourly_paid',true);
                                           if($hourly_paid == 1)
                                           {
                                                echo projectTheme_get_show_price($row->bid, 0)."/hr";
                                           }
                                           else

                                           echo projectTheme_get_show_price($row->bid, 0); ?></td>
                                           <td><?php echo get_the_date($date_format, $row->datemade) ?></td>
                                           <td><?php echo  sprintf(__('%s day(s)','ProjectTheme'), $row->days_done) ?></td>
                                           <td><a href="<?php echo get_the_permalink( $row->ID ); ?>" class='btn btn-outline-primary btn-sm'><?php echo __('View Project','ProjectTheme') ?></a>
                                           <a href="<?php echo get_the_permalink( $row->ID ); ?>" class='btn btn-outline-success btn-sm'><?php echo __('Choose Winner','ProjectTheme') ?></a></td>
                                     </tr>
                                 <?php
                             }

                      ?>


                  </tbody>
                 </table> <?php echo $own_pagination->display_pagination(); ?> </div>

                 <?php
           }
           else {

      ?>


       <div class="p-3">
         <?php _e('You do not have any active quotes.','ProjectTheme') ?>
       </div>

     <?php } ?>

</div>

<?php }elseif('pending' == $pg)
{
  $uid = get_current_user_id();

  $prf = $wpdb->prefix;
  $s = "select SQL_CALC_FOUND_ROWS * from ".$prf."project_orders orders where orders.buyer='$uid' and order_status='0' order by id desc limit $offset, $amount_per_page";
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
                <th><?php echo __('Provider','ProjectTheme'); ?></th>
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
                                      <td><p class="mb-2"><a href="<?php echo get_permalink($pst->ID) ?>"><?php echo $pst->post_title ?></a></p>
                                        <?php



                                              echo '<div class="alert alert-secondary alert-smaller-padding"><small>' . __('Waiting for the freelancer to deliver work.','ProjectTheme') . '</small></div>';
                                              pt_show_buyer_payment_status($row);
                                              pt_freelancer_area_timing_status_customer($row);
                                         ?>

                                      </td>
                                      <td><a href="<?php echo get_permalink($row->ID) ?>"><?php echo $provider->user_login ?></a></td>
                                      <td class='text-success'><?php echo projectTheme_get_show_price($row->order_total_amount, 0) ?></td>
                                      <td><?php echo date_i18n($date_format, $row->datemade) ?></td>
                                      <td <?php if($row->completion_date < $now) echo 'class="text-danger"'; ?>><?php echo  date_i18n($date_format, $row->completion_date) ?></td>
                                      <td><a href="<?php echo projecttheme_get_workspace_link_from_project_id( $row->pid ); ?>" class='btn btn-outline-primary btn-sm'><?php echo __('Workspace','ProjectTheme') ?></a></td>
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
    <?php _e('You do not have any active projects.','ProjectTheme') ?>
  </div>

<?php } ?>
</div>

<?php }elseif($pg == 'delivered'){


  $prf = $wpdb->prefix;
  $s = "select SQL_CALC_FOUND_ROWS * from ".$prf."project_orders orders where orders.buyer='$uid' and order_status='1' order by id='desc' limit $offset, $amount_per_page";
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
                <th><?php echo __('Provider','ProjectTheme'); ?></th>
                <th><?php echo __('Price','ProjectTheme') ?></th>
                <th><?php echo __('Date Made','ProjectTheme') ?></th>
                <th><?php echo __('Completed On','ProjectTheme') ?></th>
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
                                      <td><p class="mb-1"><a href="<?php echo get_permalink($row->ID) ?>"><?php echo $pst->post_title ?></a></p>
                                        <?php


                                            pt_show_buyer_payment_status($row);


                                         ?>

                                      </td>
                                      <td><a href="<?php echo get_permalink($row->ID) ?>"><?php echo $provider->user_login ?></a></td>
                                      <td class='text-success'><?php echo projectTheme_get_show_price($row->order_total_amount, 0) ?></td>
                                      <td><?php echo date_i18n($date_format, $row->datemade) ?></td>
                                      <td><?php echo  date_i18n($date_format, $row->marked_done_freelancer) ?></td>
                                      <td>
                                        <div class="dropdown z1x1x2"> <span class="noti-noti x1x2z3">1</span>
                                          <button class="btn btn-secondary dropdown-toggle dropdown-functions-settings" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                <i class="fas fa-cog"></i></button>
                                                    <div class="dropdown-menu" id="options-thing-sale" aria-labelledby="dropdownMenuButton">
                                                        <a class="dropdown-item" href="<?php echo projecttheme_get_workspace_link_from_project_id( $pst->ID ); ?>"><?php echo __('Workspace','ProjectTheme') ?> <span class="noti-noti">1</span></a>

                              <a class="dropdown-item" href="<?php echo get_site_url(); ?>/?p_action=mark_completed&id=<?php echo $row->id; ?>"><?php echo __('Mark Completed','ProjectTheme') ?></a>
                                    </div>
                                  </div>

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
    <?php _e('You do not have any active projects.','ProjectTheme') ?>
  </div>

<?php } ?>
</div>

<?php }elseif($pg == 'cancelled'){

  $prf = $wpdb->prefix;
  $s = "select SQL_CALC_FOUND_ROWS * from ".$prf."project_orders orders where orders.buyer='$uid' and order_status='3' order by id='desc' limit $offset, $amount_per_page";
  $r = $wpdb->get_results($s);

  $total_rows   = projecttheme_get_last_found_rows();
  $own_pagination = new own_pagination($amount_per_page, $total_rows, ProjectTheme_get_project_link_with_page($pgid, 'cancelled'). "&");



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
                <th><?php echo __('Price','ProjectTheme') ?></th>
                <th><?php echo __('Date Started','ProjectTheme') ?></th>
                <th><?php echo __('Cancelled On','ProjectTheme') ?></th>
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
                                      <td><a href="<?php echo get_permalink($row->ID) ?>"><?php echo $pst->post_title ?></a></td>
                                      <td><a href="<?php echo get_permalink($row->ID) ?>"><?php echo $provider->user_login ?></a></td>
                                      <td class='text-success'><?php echo projectTheme_get_show_price($row->order_total_amount, 0) ?></td>
                                      <td><?php echo date_i18n($date_format, $row->datemade) ?></td>
                                      <td><?php echo  date_i18n($date_format, $row->cancelled_date) ?></td>
                                      <td>-</td>
                                </tr>
                            <?php
                        }

                 ?>


             </tbody>
            </table> </div>

            <?php
      }
      else {

 ?>


  <div class="p-3">
    <?php _e('You do not have any cancelled projects.','ProjectTheme') ?>
  </div>

<?php } ?>

</div>
  <?php }elseif($pg == 'completed'){


  $prf = $wpdb->prefix;
  $s = "select SQL_CALC_FOUND_ROWS * from ".$prf."project_orders orders where orders.buyer='$uid' and order_status='2' order by id='desc' limit $offset, $amount_per_page";
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
                <th><?php echo __('Provider','ProjectTheme'); ?></th>
                <th><?php echo __('Price','ProjectTheme') ?></th>
                <th><?php echo __('Date Started','ProjectTheme') ?></th>
                <th><?php echo __('Accepted On','ProjectTheme') ?></th>
                <th><?php echo __('Options','ProjectTheme') ?></th>

               </tr></thead><tbody>

                 <?php

                 $now = current_time('timestamp');
                 $pgid_payments = get_option('ProjectTheme_my_account_payments_id');

                        foreach($r as $row)
                        {

                                   $provider  = get_userdata($row->freelancer);
                                   $pst       = get_post($row->pid);

                            ?>

                                <tr>
                                      <td><a href="<?php echo get_permalink($row->pid) ?>"><?php echo $pst->post_title ?></a></td>
                                      <td><a href="<?php echo ProjectTheme_get_user_profile_link($provider->ID) ?>"><?php echo $provider->user_login ?></a></td>
                                      <td class='text-success'><?php echo projectTheme_get_show_price($row->order_total_amount, 0) ?></td>
                                      <td><?php echo date_i18n($date_format, $row->datemade) ?></td>
                                      <td><?php echo  date_i18n($date_format, $row->marked_done_buyer) ?></td>
                                      <td>
                                          <?php

                                          $order = new project_orders($row->id);

                                          if(!$order->is_escrow_released())
                                          {
                                                  ?>

                                                        <a href="<?php echo ProjectTheme_get_project_link_with_page($pgid_payments, 'releaseescrow', '&id=' . $row->id) ?>" class="btn btn-outline-success btn-sm"><?php _e('Release Escrow','ProjectTheme'); ?></a>

                                                  <?php
                                          }

                                           ?>

                                        <a href="<?php echo projecttheme_get_workspace_link_from_project_id( $pst->ID ); ?>" class="btn btn-outline-primary btn-sm"><?php _e('Workspace','ProjectTheme'); ?></a></td>
                                </tr>
                            <?php
                        }

                 ?>


             </tbody>
            </table><?php echo $own_pagination->display_pagination(); ?>  </div>

            <?php
      }
      else {

 ?>


  <div class="p-3">
    <?php _e('You do not have any active projects.','ProjectTheme') ?>
  </div>

<?php } ?>

</div>

<?php }  elseif($pg == 'unpublished'){

  $prf = $wpdb->prefix;
  $s = "select SQL_CALC_FOUND_ROWS * from ".$prf."postmeta pmeta, ".$prf."posts posts where posts.ID=pmeta.post_id and posts.post_type='project' and
  posts.post_status='draft' and posts.post_author='$uid' and pmeta.meta_key='closed' and pmeta.meta_value='0' order by posts.ID desc limit $offset, $amount_per_page";
  $r = $wpdb->get_results($s);

  $total_rows   = projecttheme_get_last_found_rows();
  $own_pagination = new own_pagination($amount_per_page, $total_rows, ProjectTheme_get_project_link_with_page($pgid, 'home'). "&");



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
            <th><?php echo __('Budget','ProjectTheme') ?></th>
            <th><?php echo __('Date Made','ProjectTheme') ?></th>
            <th><?php echo __('Quotes','ProjectTheme') ?></th>
            <th><?php echo __('Options','ProjectTheme') ?></th>

           </tr></thead><tbody>

             <?php

                    foreach($r as $row)
                    {



                        ?>

                            <tr>
                                  <td><a href="<?php echo get_permalink($row->ID) ?>"><?php echo $row->post_title ?></a></td>
                                  <td class='text-success'><?php echo ProjectTheme_get_budget_name_string_fromID(get_post_meta($row->ID, 'budgets', true)) ?></td>
                                  <td><?php echo get_the_date($date_format, $row->ID) ?></td>
                                  <td><?php echo  projectTheme_number_of_bid($row->ID) ?></td>
                                  <td><a href="<?php echo ProjectTheme_post_new_with_pid_stuff_thg($row->ID, '4'); ?>" class='btn btn-outline-primary btn-sm'><?php echo __('Publish','ProjectTheme') ?></a>
                                  <a href="<?php echo get_site_url() ?>/?p_action=delete_project&pid=<?php echo $row->ID; ?>" class='btn btn-outline-danger btn-sm'><?php echo __('Delete','ProjectTheme') ?></a></td>
                            </tr>
                        <?php
                    }

             ?>


         </tbody>
        </table>

        <?php echo $own_pagination->display_pagination(); ?>
      </div>

        <?php
  }
  else {

  ?>


  <div class="p-3">
  <?php _e('You do not have any unpublished projects.','ProjectTheme') ?>
  </div>

  <?php } ?>



<?php } ?> </div>

        </div></div> <!-- end dif content -->





		<?php get_template_part('lib/my_account/footer-area-account') ?>
</div></div>

<?php


      $page = ob_get_contents();
      ob_end_clean();
      return $page;

}



 ?>
