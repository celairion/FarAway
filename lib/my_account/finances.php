<?php
function projecttheme_finances_card_group()
{
      global $wpdb;
      $uid = get_current_user_id();
      $ProjectTheme_payment_model = get_option('ProjectTheme_payment_model');

      if($ProjectTheme_payment_model != "invoice_model_pay_outside")
      {

               $pending_incoming = pt_calculate_pending_incoming($uid);
               $pending_outgoing = pt_calculate_pending_outgoing($uid);
               $pgid = get_option('ProjectTheme_my_account_payments_id');



               $ProjectTheme_payment_model = get_option('ProjectTheme_payment_model');
               if($ProjectTheme_payment_model != "marketplace_gateways") // this is if you are not using the stripe connect or other split payment gateways
               {

      ?>

      <div class="card-group">



                      <div class="card border-right">
                        <div class="card-body p-3 text-center">
                          <div class="text-right text-green">
                             &nbsp;
                          </div>
                          <div class="h1 m-0 text-success"><?php echo projectTheme_get_show_price(ProjectTheme_get_credits(get_current_user_id()), 0) ?></div>
                          <div class="text-muted mb-4"><?php echo __('Your Balance','ProjectTheme') ?></div>
                          <div class="">
                            <?php

                                  if(ProjectTheme_is_user_business(get_current_user_id()))
                                  {

                             ?>


                            <a href="<?php echo ProjectTheme_get_project_link_with_page($pgid, 'deposit'); ?>" class="btn btn-success btn-sm"><?php echo __('Deposit More','ProjectTheme') ?></a> <?php } ?> </div>
                        </div>
                      </div>

                      <?php
                      if(ProjectTheme_is_user_business(get_current_user_id()))
                      {

                        ?>
                      <div class="card border-right">
                        <div class="card-body p-3 text-center">
                          <div class="text-right text-green">
                             &nbsp;
                          </div>
                          <div class="h1 m-0"><?php echo projectTheme_get_show_price($pending_outgoing) ?></div>
                          <div class="text-muted mb-4"><?php echo __('Pending Outgoing','ProjectTheme') ?></div>

                        </div>
                      </div> <?php } ?>


                      <div class="card border-right">
                        <div class="card-body p-3 text-center">
                          <div class="text-right text-green">
                             &nbsp;
                          </div>
                          <div class="h1 m-0"><?php echo projectTheme_get_show_price($pending_incoming) ?></div>
                          <div class="text-muted mb-4"><?php echo __('Pending Incoming','ProjectTheme') ?></div>

                        </div>
                      </div>


                      <div class="card">
                        <div class="card-body p-3 text-center">
                          <div class="text-right text-green">
                             &nbsp;
                          </div>
                          <div class="h1 m-0"><?php echo projectTheme_get_show_price(ProjectTheme_get_credits(get_current_user_id()), 0) ?></div>
                          <div class="text-muted mb-4"><?php echo __('Available for withdrawal','ProjectTheme') ?></div>
                          <div class=""><a href="<?php echo ProjectTheme_get_project_link_with_page($pgid, 'withdraw'); ?>" class="btn btn-warning btn-sm"><?php echo __('Request Withdrawal','ProjectTheme') ?></a></div>
                        </div>
                      </div>

                  </div>

                  <?php


                  $openWithdrawals = project_count_open_withdrawals($uid);
                  if($openWithdrawals > 0)
                  {
                    ?>
                            <div class="alert alert-warning"><?php echo sprintf(__('You have %s pending withdrawal requests. <a href="%s">Click here</a> to view status.','ProjectTheme'), $openWithdrawals, ProjectTheme_get_project_link_with_page($pgid, 'pending_withdrawals')) ; ?></div>
                    <?php
                  }

                }

                  }
}



function project_theme_my_account_payments_fnc()
{

  ob_start();

  $date_format =  get_option( 'date_format' );

   global $current_user, $wp_query, $wpdb;
   $current_user=wp_get_current_user();

   $uid = $current_user->ID;



   				get_template_part ( 'lib/my_account/aside-menu'  );



          $ProjectTheme_payment_model = get_option('ProjectTheme_payment_model');
          if($ProjectTheme_payment_model == "invoice_model_pay_outside")
          {

              ?>

              <div class="page-wrapper" style="display:block;">
                <div class="container"  >


                  <div class="row mt-4">
                  <div class="col-sm-12 col-lg-12">
                  <div class="page-header">
                         <h1 class="page-title">
                           <?php echo sprintf(__('Pending Payments','ProjectTheme')  ) ?>
                         </h1>
                       </div></div></div>

                  <?php



                  do_action('pt_for_demo_work_3_0');


                  if(isset($_GET['billid']))
                  {

                        $billid = $_GET['billid'];

                      ?>


                       <div class="card mb-4 p-3">

                              <p><?php echo __('Use the options below to pay for this bill:','ProjectTheme'); ?></p>

                                <div class="mt-4">

                                  <?php

                                  $ProjectTheme_paypal_enable = get_option('ProjectTheme_paypal_enable');
                                  if($ProjectTheme_paypal_enable == "yes")
                                  {
                                      ?>
                                              <a href="" class="btn btn-primary"><?php _e('Pay by PayPal','ProjectTheme')  ?></a>
                                      <?php
                                  }


                                   $ProjectTheme_moneybookers_enable = get_option('ProjectTheme_moneybookers_enable');
                                  if($ProjectTheme_moneybookers_enable == "yes")
                                  {
                                      ?>
                                              <a href="" class="btn btn-primary"><?php _e('Pay by Skrill','ProjectTheme')  ?></a>
                                      <?php
                                  }


                                  do_action('projecttheme_pay_for_bill', $billid);

                                   ?>


                                </div>

                       </div>



                      <?php

                  }
                  else {

               ?>




               <div class="card mb-4 p-3">


                         <?php




                                  global $wpdb;
                                  $s = "select * from ".$wpdb->prefix."project_bills_site where uid='$uid' and paid='0'";
                                  $r = $wpdb->get_results($s);

                                  if(count($r) == 0)
                                  {
                                        _e('You do not have any pending bills.','ProjectTheme');

                                  }
                                  else {
                                    // code...
                                    ?>

                                    <div class="row border-bottom">
                                          <div class="col-6 col-md-3 pb-2 pt-2"><?php _e('Project Title','ProjectTheme') ?></div>
                                          <div class="col-6 col-md-3 pb-2 pt-2"><?php _e('Amount','ProjectTheme') ?></div>
                                          <div class="col-6 col-md-3 pb-2 pt-2"><?php _e('Date','ProjectTheme') ?></div>
                                          <div class="col-6 col-md-3 pb-2 pt-2"><?php _e('Payment','ProjectTheme') ?></div>
                                    </div>

                                    <?php

                                    $finances_payment_page_id = get_option('ProjectTheme_my_account_payments_id');

                                    foreach($r as $row)
                                    {

                                      $project = get_post($row->pid);


                                        ?>

                                        <div class="row border-bottom">
                                              <div class="col-6 col-md-3 pb-2 pt-2"><a href="<?php echo get_permalink($row->pid) ?>"><?php echo $project->post_title ?></a></div>
                                              <div class="col-6 col-md-3 pb-2 pt-2"><?php echo projecttheme_get_show_price($row->amount) ?></div>
                                              <div class="col-6 col-md-3 pb-2 pt-2"><?php echo date('d-m-Y', $row->datemade) ?></div>
                                              <div class="col-6 col-md-3 pb-2 pt-2"><a href="<?php echo ProjectTheme_get_payments_page_url_billid($row->id) ?>" class="btn btn-sm btn-outline-primary"><?php _e('Pay Now','ProjectTheme') ?></a></div>
                                        </div>


                                        <?php
                                    }
                                  }

                          ?>



               </div>


               <div class="row mt-4">
               <div class="col-sm-12 col-lg-12">
               <div class="page-header">
                      <h1 class="page-title">
                        <?php echo sprintf(__('Paid Bills','ProjectTheme')  ) ?>
                      </h1>
                    </div></div></div>



                    <div class="card mb-4 p-3">


                              <?php

                                       global $wpdb;
                                       $s = "select * from ".$wpdb->prefix."project_bills_site where uid='$uid' and paid='1'";
                                       $r = $wpdb->get_results($s);

                                       if(count($r) == 0)
                                       {
                                             _e('You do not have any pending bills.','ProjectTheme');

                                       }
                                       else {
                                         // code...
                                         ?>

                                         <div class="row border-bottom">
                                               <div class="col-6 col-md-3 pb-2 pt-2"><?php _e('Project Title','ProjectTheme') ?></div>
                                               <div class="col-6 col-md-3 pb-2 pt-2"><?php _e('Amount','ProjectTheme') ?></div>
                                               <div class="col-6 col-md-3 pb-2 pt-2"><?php _e('Date','ProjectTheme') ?></div>
                                               <div class="col-6 col-md-3 pb-2 pt-2"><?php _e('Payment','ProjectTheme') ?></div>
                                         </div>

                                         <?php

                                         foreach($r as $row)
                                         {

                                           $project = get_post($row->pid);


                                             ?>

                                             <div class="row border-bottom">
                                                   <div class="col-6 col-md-3 pb-2 pt-2"><a href="<?php echo get_permalink($row->pid) ?>"><?php echo $project->post_title ?></a></div>
                                                   <div class="col-6 col-md-3 pb-2 pt-2"><?php echo projecttheme_get_show_price($row->amount) ?></div>
                                                   <div class="col-6 col-md-3 pb-2 pt-2"><?php echo date('d-m-Y', $row->datemade) ?></div>
                                                   <div class="col-6 col-md-3 pb-2 pt-2"><a href="" class="btn btn-sm btn-outline-primary"><?php _e('Pay Now','ProjectTheme') ?></a></div>
                                             </div>


                                             <?php
                                         }
                                       }

                               ?>



                    </div>

                  <?php } ?>
                </div>

                <?php get_template_part('lib/my_account/footer-area-account') ?>
              </div>


              <?php

          }
          else //not paid outside the website
          {


   ?>


   <div class="page-wrapper" style="display:block">
     <div class="container"  >


     <?php



     do_action('pt_for_demo_work_3_0');

  ?>





       <?php

             $pgid = get_option('ProjectTheme_my_account_payments_id');
             if(empty($_GET['pg'])) $pg = 'home';
             else $pg = $_GET['pg'];




       ?>



  <div class="row">



  <div class="account-main-area col-xs-12 col-sm-12 col-md-12 col-lg-12">


    <?php
//******************************************************************************
//
//      Pending Withdrawals Page
//
//******************************************************************************


if($pg =='releaseescrow')
{

  ?>

  <div class="row">
  <div class="col-sm-12 col-lg-12">
  <div class="page-header">
         <h1 class="page-title">
           <?php echo sprintf(__('Release Escrow Payment','ProjectTheme')  ) ?>
         </h1>
       </div></div></div>

       <?php

          if($_GET['escrowyesconfirm'] == 1)
          {



                  $id = $_GET['id'];
                  $order = new project_orders($id);
                  $order->has_escrow_deposited();

                  $escrow = $order->get_escrow_object();
                  $amount = $escrow->amount;

                  if(!$order->is_escrow_released())
                  {
                      do_action('projecttheme_on_escrow_am_aff', get_current_user_id(), $amount);

                      $order->release_escrow();
                      $toid   = $escrow->toid;
                      $obj    = $order->get_order();
                      $my_pst = get_post($obj->pid);

                      ProjectTheme_send_email_when_on_completed_project($obj->pid, $toid, $amount);

                      //******* complete escrow ********

                      $projectTheme_fee_after_paid = get_option('projectTheme_fee_after_paid');
                      if(!empty($projectTheme_fee_after_paid)):

                        $deducted = $amount*($projectTheme_fee_after_paid * 0.01);
                      else:

                        $deducted = 0;

                      endif;

                      $current_user = wp_get_current_user();

                      //-------------------------------------------------------------------------------

                      $cr = projectTheme_get_credits($toid);
                      projectTheme_update_credits($toid, $cr + $amount - $deducted);

                      $reason = sprintf(__('Escrow payment received from %s for the project <b>%s</b>','ProjectTheme'), $current_user->user_login, $my_pst->post_title);
                      projectTheme_add_history_log('1', $reason, $amount, $toid, $uid);


                      if($deducted > 0)
                      $reason = sprintf(__('Payment fee for project %s','ProjectTheme'), $my_pst->post_title);
                      projectTheme_add_history_log('0', $reason, $deducted, $toid );

                      //-----------------------------
                      $email 		= get_bloginfo('admin_email');
                      $site_name 	= get_bloginfo('name');

                      $usr = get_userdata($uid);

                      $subject = __("Money Escrow Completed",'ProjectTheme');
                      $message = sprintf(__("You have released the escrow of: %s","ProjectTheme"), ProjectTheme_get_show_price($amount));

                      //($usr->user_email, $subject , $message);

                      //-----------------------------

                      $usr = get_userdata($toid);

                      $reason = sprintf(__('Escrow Payment completed, sent to %s for project <b>%s</b>','ProjectTheme'), $usr->user_login, $my_pst->post_title);
                      projectTheme_add_history_log('0', $reason, $amount, $uid, $toid, $my_pst->ID);

                      $subject = __("Money Escrow Completed","ProjectTheme");
                      $message = sprintf(__("You have received the amount of: %s","ProjectTheme"), ProjectTheme_get_show_price($amount));

                  }
                  //********************************


              ?>

                    <div class="card mb-4">
                            <p class=" p-3">
                            <?php _e('Your escrow payment was released successfully.','ProjectTheme') ?>
                            </p>

                             <div class="card-footer card-footer-border">
                                    <a href="<?php echo ProjectTheme_get_project_link_with_page($pgid, 'outgoing') ?>" class="btn btn-success"><?php _e('Go back to finances','ProjectTheme') ?></a>
                             </div>
                    </div>

              <?php
          }
          else {


        ?>
        <div class="card mb-4">
            <?php

                    $id = $_GET['id'];
                    $order = new project_orders($id);
                    $order->has_escrow_deposited();

                    $escrow = $order->get_escrow_object();
                    $amount = $escrow->amount;

                    echo '<p class=" p-3">';
                       printf(__('Are you sure you want to release the escrow of: <span class="text-success">%s</span>','ProjectTheme'), projectTheme_get_show_price($amount));

                       echo '</p>';


             ?>

             <script>
              function ptgoBack() {
                window.history.back();
              }
              </script>

             <div class="card-footer card-footer-border">
                  <a href="<?php echo ProjectTheme_get_project_link_with_page($pgid, 'releaseescrow', '&escrowyesconfirm=1&id=' . $id) ?>" class="btn btn-success"><?php _e('Yes','ProjectTheme') ?></a>
                  <a href="#" onclick="ptgoBack()" class="btn  btn-secondary"><?php _e('No, go back','ProjectTheme') ?></a>
             </div>

        </div>
  <?php
        }

        ?>

          </div>


          <?php

}

elseif($pg =='paysplit')
{


  $poid = $_GET['poid'];
  $order = new project_orders($poid);
  $order_o = $order->get_order();
  $pid = $order_o->pid;
  $total = $order_o->order_total_amount;
  $cr = projectTheme_get_credits($uid);

  $pst = get_post($pid);
  $winnerid = $order_o->freelancer;


?>


<div class="row">
<div class="col-sm-12 col-lg-12">
<div class="page-header">
       <h1 class="page-title">
         <?php echo sprintf(__('Choose payment method','ProjectTheme')  ) ?>
       </h1>
     </div></div></div>



     <div class="card mb-4 p-3">
        <table class="table">
            <tbody>

              <tr>
                    <td><?php _e('Project Name:','ProjectTheme'); ?></td>
                    <td class=""><?php echo $pst->post_title ?></td>
              </tr>


              <tr>
                    <td><?php _e('Winning Amount:','ProjectTheme'); ?></td>
                    <td class="text-success"><?php echo projectTheme_get_show_price($order_o->order_total_amount) ?></td>
              </tr>


              <tr>
                    <td><?php _e('Service Provider:','ProjectTheme'); ?></td>
                    <td><a href="<?php echo ProjectTheme_get_user_profile_link($winnerid) ?>" target="_blank"><?php echo project_theme_get_name_of_user($winnerid) ?></a></td>
              </tr>

            </tbody>
        </table>

     </div>


     <div class="card mb-4 p-3">

          <?php do_action('projecttheme_payment_split', $poid) ?>

     </div>

     </div>
<?php




}
elseif($pg =='escrow')
{


      $poid = $_GET['poid'];
      $order = new project_orders($poid);
      $order_o = $order->get_order();
      $pid = $order_o->pid;
      $total = $order_o->order_total_amount;
      $cr = projectTheme_get_credits($uid);

      $pst = get_post($pid);
      $winnerid = $order_o->freelancer;

  ?>

  <div class="row">
  <div class="col-sm-12 col-lg-12">
  <div class="page-header">
         <h1 class="page-title">
           <?php echo sprintf(__('Deposit Escrow','ProjectTheme')  ) ?>
         </h1>
       </div></div></div>



       <div class="card mb-4 p-3">
          <table class="table">
              <tbody>

                <tr>
                      <td><?php _e('Project Name:','ProjectTheme'); ?></td>
                      <td class=""><?php echo $pst->post_title ?></td>
                </tr>


                <tr>
                      <td><?php _e('Winning Amount:','ProjectTheme'); ?></td>
                      <td class="text-success"><?php echo projectTheme_get_show_price($order_o->order_total_amount) ?></td>
                </tr>

                <?php

                      $projectTheme_fee_service_fee = get_option('projectTheme_fee_service_fee');
                      if($projectTheme_fee_service_fee > 0)
                      {
                            $service_fee = round(0.01 * $order_o->order_total_amount* $projectTheme_fee_service_fee,2);

                         ?>


                         <tr>
                               <td><?php _e('Service Fee:','ProjectTheme'); ?></td>
                               <td class="text-success"><?php echo projectTheme_get_show_price($service_fee) ?></td>
                         </tr>


                         <tr>
                               <td><?php _e('Total Amount:','ProjectTheme'); ?></td>
                               <td class="text-success"><?php echo projectTheme_get_show_price($service_fee + $order_o->order_total_amount) ?></td>
                         </tr>

                         <?php
                      }
                      else {

                            $service_fee = 0;

                      }

                 ?>


                <tr>
                      <td><?php _e('Service Provider:','ProjectTheme'); ?></td>
                      <td><a href="<?php echo ProjectTheme_get_user_profile_link($winnerid) ?>" target="_blank"><?php echo project_theme_get_name_of_user($winnerid) ?></a></td>
                </tr>

              </tbody>
          </table>

       </div>

       <?php

       if($_GET['another'] == 'ewalletyes')
       {

          if(!$order->has_escrow_deposited())
          {
            $args['method']          = __('eWallet','ProjectTheme');
            $args['sending_user']   = $order_o->buyer;
            $args['receiving_user'] = $order_o->freelancer;
            $args['amount']         = $order_o->order_total_amount; // + $service_fee;

            $order->insert_escrow($args);

            $cr = projectTheme_get_credits(get_current_user_id());
            projectTheme_update_credits(get_current_user_id(), $cr - $order_o->order_total_amount - $service_fee);


            if($service_fee > 0)
            {
              $pst = get_post($order_o->pid);
              $reason = sprintf(__('Service fee for escrow for project: <b>%s</b>','ProjectTheme'),  $pst->post_title);
              projectTheme_add_history_log('0', $reason, $service_fee, get_current_user_id());
            }

          }


          ?>
              <div class="alert alert-success">
                        <?php
                              $pgid = get_option('ProjectTheme_my_account_buyer_area');
                              $lnk = ProjectTheme_get_project_link_with_page($pgid, 'pending');
                              printf(__('You have successfuly initiated the escrow. <a href="%s">Go back</a> to pending projects.','ProjectTheme'), $lnk);

                        ?>
              </div>

          <?php
       }

       elseif($_GET['another'] == "ewallet")
       {
              ?>

                   <div class="card mb-4 p-3">

                         <p class="mb-4"> <?php _e('You are about to escrow money for this project: ','ProjectTheme'); ?></p>
                           <p> <?php printf(__('Your current balance is: %s ','ProjectTheme'), projecttheme_get_show_price(ProjectTheme_get_credits(get_current_user_id()))); ?></p>
                           <?php

                                  if($cr < $total)
                                  {
                                    ?>
                                            <div class="alert alert-danger"><?php _e('You do not have enough balance in your wallet.','ProjectTheme') ?></div>
                                    <?php
                                  }

                            ?>
                           <p>

                                <?php if($cr >= $total) { ?><a href="<?php echo ProjectTheme_get_payments_page_url2('escrow', $_GET['poid'], 'ewalletyes') ?>" class="btn btn-success"><?php _e('Yes Confirm','ProjectTheme') ?></a> <?php } ?>
                                <a href="<?php echo ProjectTheme_get_payments_page_url2('escrow', $_GET['poid'], '') ?>" class="btn btn-secondary"><?php _e('No, go back','ProjectTheme') ?></a>

                           </p>
                   </div>


              <?php
       }
       else {
         ?>
       <div class="card mb-4 p-3">
         <p class="mb-4"> <?php _e('You are about to deposit escrow payment for this project. Use the options below: ','ProjectTheme'); ?></p>

           <div class="col col-xs-12 col-md-6 col-lg-6">
           <?php


           $ProjectTheme_paypal_enable = get_option('ProjectTheme_paypal_enable');
           if($ProjectTheme_paypal_enable == "yes") //$credits < $bid->bid )
           {

             $poid = $_GET['poid'];


             $link = site_url() . "/?pay_escrow_by_pp=" . $poid;

         ?>

               <p><a href="<?php echo $link ?>" class="btn btn-primary btn-block"><?php printf(__('Make escrow of %s through PayPal','ProjectTheme'), projectTheme_get_show_price($order_o->order_total_amount + $service_fee)); ?></a></p>

         <?php
         }

         ?>

                 <p><a href="<?php echo ProjectTheme_get_payments_page_url2('escrow', $_GET['poid'], 'ewallet') ?>" class="btn btn-primary btn-block"><?php printf(__('Make escrow of %s through eWallet Balance','ProjectTheme'), projectTheme_get_show_price($order_o->order_total_amount + $service_fee)); ?></a></p>

         <?php


                    do_action('ProjectTheme_escrow_tag');

             ?>
       </div>
     </div>

   <?php } ?>
   </div>
  <?php


}
    elseif($pg =='pending_withdrawals')
    {
        ?>

        <div class="row">
        <div class="col-sm-12 col-lg-12">
        <div class="page-header">
               <h1 class="page-title">
                 <?php echo sprintf(__('Pending Withdrawals','ProjectTheme')  ) ?>
               </h1>
             </div></div></div>

             <div class="w-100 mb-3">
               <a href="<?php echo ProjectTheme_get_project_link_with_page($pgid, 'home') ?>" class="btn btn-success btn-sm"><?php _e('Go to Finances home','ProjectTheme') ?></a>
               <a href="<?php echo ProjectTheme_get_project_link_with_page($pgid, 'withdraw') ?>" class="btn btn-success btn-sm"><?php _e('Go to Withdraw','ProjectTheme') ?></a> </div>

               <!-- ### -->

               <?php
                     if(!empty($_GET['yes_ok']))
                     {
                          $id = $_GET['close_request'];
                          $withdrawal_object = new project_withdrawal($id);
                          $request = $withdrawal_object->get_withdrawal_request();

                          $err_nr = 0;

                          if($request == false)
                          {
                              $err_nr = 111;
                          }
                          else {


                              if($request->uid != get_current_user_id())
                              {
                                  $err_nr = 222;
                              }
                              else {
                                //ok branch, delete the request

                                $withdrawal_object->delete_withdrawal_request();


                              }

                          }

                          if($err_nr == 0)
                          {
                             ?>
                                    <div class="alert alert-success"><?php _e('Your request has been deleted','ProjectTheme') ?></div>
                             <?php
                          }
                          else {
                            ?>
                                   <div class="alert alert-danger"><?php echo sprintf(__('There was an unknown error: %s','ProjectTheme'), $err_nr); ?></div>
                            <?php
                          }


                     }
                    elseif(!empty($_GET['close_request']))
                    {
                        ?>
                                <div class="alert alert-warning">
                                  <div class="w-100 mb-3"><?php _e('Are you want to cancel this request?','ProjectTheme') ?></div>

                                          <div class="w-100"><a href="<?php echo  ProjectTheme_get_project_link_with_page($pgid, 'pending_withdrawals', '&yes_ok=yes&close_request=' . $_GET['close_request']) ?>" class="btn btn-primary btn-sm"><?php _e('Confirm','ProjectTheme') ?></a>
                                          <a href="<?php echo ProjectTheme_get_project_link_with_page($pgid, 'pending_withdrawals') ?>" class="btn btn-secondary btn-sm"><?php _e('No','ProjectTheme') ?></a></div>
                                </div>

                        <?php
                    }

                ?>


             <div class="card mb-4">
               <?php

                   $s = "select * from ".$wpdb->prefix."project_withdraw where done='0' and rejected!='1' AND uid='$uid' order by id desc";
                   $r = $wpdb->get_results($s);

                   if(count($r) == 0) echo '<div class="p-3">' .__('No withdrawals pending yet.','ProjectTheme') . '</div>';
                   else
                   {
                     ?>
<div class="table-responsive">
                     <table class="table table-hover table-outline table-vcenter   card-table">
                       <thead><tr>

                         <th><?php echo __('Date','ProjectTheme'); ?></th>
                         <th><?php echo __('Amount','ProjectTheme') ?></th>
                         <th><?php echo __('Method','ProjectTheme') ?></th>
                         <th><?php echo __('Details','ProjectTheme') ?></th>
                         <th><?php echo __('Options','ProjectTheme') ?></th>

                        </tr></thead><tbody>

                     <?php

                     foreach($r as $row) // = mysql_fetch_object($r))
                     {


                       echo '<tr>';
                       echo '<td>'.date_i18n('d-M-Y g:i A', $row->datemade).'</td>';
                       echo '<td class="text-success">'.ProjectTheme_get_show_price($row->amount).'</td>';
                       echo '<td >'.$row->methods .'</td>';
                       echo '<td >'.$row->payeremail .'</td>';
                       echo '<td><a href="'. ProjectTheme_get_project_link_with_page($pgid, 'pending_withdrawals', '&close_request=' . $row->id) .'"
                       class="btn btn-outline-primary btn-sm">'.__('Close Request','ProjectTheme'). '</a></td>';
                       echo '</tr>';


                     }
                     ?>

                   </tbody></table></div>

                     <?php

                   }


                ?>
             </div>   </div>

        <?php
    }
    //******************************************************************************
    //
    //      Main Withdrawals Page
    //
    //******************************************************************************
    elseif($pg == "withdraw")
    {

          if(isset($_POST['withdraw_paypal']))
          {
              $amount = $_POST['amount'];
              $paypal = $_POST['paypal'];

              global $wpdb;
              $uid  = get_current_user_id();

              $min = get_option('project_theme_min_withdraw');
  						if(empty($min)) $min = 0;
              $bal = projectTheme_get_credits($uid);

              if($bal < $amount)
              {
                    echo '<div class="alert alert-danger">'.__('Your balance is lower than the amount you requested.','ProjectTheme').'</div>';
              }
              elseif($amount < $min) {
                    // code...
                    echo '<div class="alert alert-danger">'.sprintf(__('The withdraw limit is %s, please request the same amount or higher.','ProjectTheme'), projectTheme_get_show_price($min)).'</div>';
              }
              else {

                    if(!empty($_POST['tm']))
                    {
                      $tm = $_POST['tm']; //current_time('timestamp',0);
                    } else $tm = current_time('timestamp');

                    //------

                    $s = "select * from ".$wpdb->prefix."project_withdraw where uid='$uid' and datemade='$tm' ";
                    $r = $wpdb->get_results($s);

                    if(count($r) == 0)
                    {
                        $meth = 'PayPal';
                        $s = "insert into ".$wpdb->prefix."project_withdraw (methods, payeremail, amount, datemade, uid, done)
                        values('$meth','$paypal','$amount','$tm','$uid','0')";
                        $wpdb->query($s);


                        // added 3.1.7
                        ProjectTheme_send_email_on_withdrawal_requested_user($uid, $amount, $meth);
                        ProjectTheme_send_email_on_withdrawal_requested_admin($uid, $amount, $meth);

                        projectTheme_update_credits($uid, $bal - $amount);

                      }

                      echo '<div class="alert alert-success">'.__('Your withdrawal request has been submitted.','ProjectTheme').'</div>';
              }
          }// endif withdraw by paypal

          //----------------------------



          if(isset($_POST['withdraw_by_skrill']))
          {
              $amount = $_POST['amount'];
              $paypal = $_POST['skrill_email'];

              global $wpdb;
              $uid  = get_current_user_id();

              $min = get_option('project_theme_min_withdraw');
  						if(empty($min)) $min = 0;
              $bal = projectTheme_get_credits($uid);

              if($bal < $amount)
              {
                    echo '<div class="alert alert-danger">'.__('Your balance is lower than the amount you requested.','ProjectTheme').'</div>';
              }
              elseif($amount < $min) {
                    // code...
                    echo '<div class="alert alert-danger">'.sprintf(__('The withdraw limit is %s, please request the same amount or higher.','ProjectTheme'), projectTheme_get_show_price($min)).'</div>';
              }
              else {

                    if(!empty($_POST['tm']))
                    {
                      $tm = $_POST['tm']; //current_time('timestamp',0);
                    } else $tm = current_time('timestamp');

                    //------

                    $s = "select * from ".$wpdb->prefix."project_withdraw where uid='$uid' and datemade='$tm' ";
                    $r = $wpdb->get_results($s);

                    if(count($r) == 0)
                    {
                        $meth = 'Skrill';
                        $s = "insert into ".$wpdb->prefix."project_withdraw (methods, payeremail, amount, datemade, uid, done)
                        values('$meth','$paypal','$amount','$tm','$uid','0')";
                        $wpdb->query($s);


                        // added 3.1.7
                        ProjectTheme_send_email_on_withdrawal_requested_user($uid, $amount, $meth);
                        ProjectTheme_send_email_on_withdrawal_requested_admin($uid, $amount, $meth);

                        projectTheme_update_credits($uid, $bal - $amount);

                      }

                      echo '<div class="alert alert-success">'.__('Your withdrawal request has been submitted.','ProjectTheme').'</div>';
              }
          }// endif withdraw by skrill



          if(isset($_POST['withdraw_bnk']))
          {
              $amount = $_POST['amount'];
              $paypal = $_POST['bnk_dets'];

              global $wpdb;
              $uid  = get_current_user_id();

              $min = get_option('project_theme_min_withdraw');
  						if(empty($min)) $min = 0;
              $bal = projectTheme_get_credits($uid);

              if($bal < $amount)
              {
                    echo '<div class="alert alert-danger">'.__('Your balance is lower than the amount you requested.','ProjectTheme').'</div>';
              }
              elseif($amount < $min) {
                    // code...
                    echo '<div class="alert alert-danger">'.sprintf(__('The withdraw limit is %s, please request the same amount or higher.','ProjectTheme'), projectTheme_get_show_price($min)).'</div>';
              }
              else {

                    if(!empty($_POST['tm']))
                    {
                      $tm = $_POST['tm']; //current_time('timestamp',0);
                    } else $tm = current_time('timestamp');

                    //------

                    $s = "select * from ".$wpdb->prefix."project_withdraw where uid='$uid' and datemade='$tm' ";
                    $r = $wpdb->get_results($s);

                    if(count($r) == 0)
                    {
                        $meth = 'Bank';
                        $s = "insert into ".$wpdb->prefix."project_withdraw (methods, payeremail, amount, datemade, uid, done)
                        values('$meth','$paypal','$amount','$tm','$uid','0')";
                        $wpdb->query($s);


                        // added 3.1.7
                        ProjectTheme_send_email_on_withdrawal_requested_user($uid, $amount, $meth);
                        ProjectTheme_send_email_on_withdrawal_requested_admin($uid, $amount, $meth);

                        projectTheme_update_credits($uid, $bal - $amount);

                      }

                      echo '<div class="alert alert-success">'.__('Your withdrawal request has been submitted.','ProjectTheme').'</div>';
              }
          }// endif withdraw by skrill


          do_action('projecttheme_at_top_of_withdraw_page');

      ?>


      <div class="row">
      <div class="col-sm-12 col-lg-12">
      <div class="page-header">
             <h1 class="page-title">
               <?php echo sprintf(__('Withdraw Money','ProjectTheme')  ) ?>
             </h1>
           </div></div></div>

           <div class="w-100 mb-3"><a href="<?php echo ProjectTheme_get_project_link_with_page($pgid, 'home') ?>" class="btn btn-success btn-sm"><?php _e('Go to Finances home','ProjectTheme') ?></a>

             <a href="<?php echo ProjectTheme_get_project_link_with_page($pgid, 'pending_withdrawals') ?>" class="btn btn-success btn-sm"><?php _e('Pending Withdrawals','ProjectTheme') ?></a>

           </div>


          <?php




            projecttheme_finances_card_group();



            ?>

            <div class="alert alert-secondary"><?php _e('Use the below payment options to request withdrawal of your earnings.','ProjectTheme'); ?></div>


            <?php

            $opt = get_option('ProjectTheme_paypal_enable');
            if($opt == "yes")
            {

          ?>

          <div class="card mb-4"><div class="card-body">
           <h5 class="cff123 mb-4"><?php _e('Widthdraw by PayPal','ProjectTheme') ?></h5>


                     <form method="post" enctype="application/x-www-form-urlencoded">
                     <input type="hidden" name="meth" value="PayPal" />
                     <input type="hidden" name="tm" value="<?php echo current_time('timestamp',0) ?>" />


                     <div class="form-group">
                     <div class="input-group">
                         <span class="input-group-prepend">
                           <span class="input-group-text"><?php echo projectTheme_currency() ?></span>
                         </span>
                         <input type="number" step="0.01" name="amount" required="" class="form-control no-border-radius" placeholder="<?php _e('Amount to withdraw','ProjectTheme') ?>">
                       </div></div>


                         <div class="form-group">
                       <div class="input-group">
                           <input type="text"  required="" class="form-control no-border-radius" name="paypal" placeholder="<?php _e('Your PayPal email address','ProjectTheme') ?>">
                         </div>	</div>

                         </div>

                   <div class="card-footer text-right"> <input type="submit" class="btn btn-success" name="withdraw_paypal" value="<?php echo __("Withdraw","ProjectTheme"); ?>" /> </div>


                 </form>
                       </div>

          <?php

        }// endif PayPal

        $opt = get_option('ProjectTheme_moneybookers_enable');
        if($opt == "yes"){
          ?>

          <div class="card mb-4"><div class="card-body">
           <h5 class="cff123 mb-4"><?php _e('Widthdraw by Skrill','ProjectTheme') ?></h5>


                     <form method="post" enctype="application/x-www-form-urlencoded">
                     <input type="hidden" name="meth" value="Skrill" />
                     <input type="hidden" name="tm" value="<?php echo current_time('timestamp',0) ?>" />


                     <div class="form-group">
                     <div class="input-group">
                         <span class="input-group-prepend">
                           <span class="input-group-text"><?php echo projectTheme_currency() ?></span>
                         </span>
                         <input type="number" step="0.01" name="amount" required="" class="form-control no-border-radius" placeholder="<?php _e('Amount to withdraw','ProjectTheme') ?>">
                       </div></div>


                         <div class="form-group">
                       <div class="input-group">
                           <input type="text"  required="" class="form-control no-border-radius" name="skrill_email" placeholder="<?php _e('Your Skrill email address','ProjectTheme') ?>">
                         </div>	</div>

                         </div>

                   <div class="card-footer text-right"> <input type="submit" class="btn btn-success" name="withdraw_by_skrill" value="<?php echo __("Withdraw","ProjectTheme"); ?>" /> </div>

                 </form>	 </div>

          <?php
        }//endif moneybookers

        $opt = get_option('ProjectTheme_bank_details_enable');
        if($opt == "yes")
        {
          ?>
          <div class="card mb-4"><div class="card-body">
           <h5 class="cff123 mb-4"><?php _e('Widthdraw by Bank','ProjectTheme') ?></h5>


                     <form method="post" enctype="application/x-www-form-urlencoded">
                     <input type="hidden" name="meth3" value="Bank" />
                     <input type="hidden" name="tm" value="<?php echo current_time('timestamp',0) ?>" />


                     <div class="form-group">
                     <div class="input-group">
                         <span class="input-group-prepend">
                           <span class="input-group-text"><?php echo projectTheme_currency() ?></span>
                         </span>
                         <input type="number" step="0.01" name="amount" required="" class="form-control no-border-radius" placeholder="<?php _e('Amount to withdraw','ProjectTheme') ?>">
                       </div></div>


                         <div class="form-group">
                       <div class="input-group">
                         <textarea class="form-control no-border-radius" placeholder="<?php _e('Your Bank Details','ProjectTheme') ?>" name="bnk_dets"></textarea>

                         </div>	</div>

                         </div>

                   <div class="card-footer text-right"> <input type="submit" class="btn btn-success" name="withdraw_bnk" value="<?php echo __("Withdraw","ProjectTheme"); ?>" /> </div>

                 </form>	 </div>


          <?php
        } // endif bank

        ?>


                       <?php do_action('ProjectTheme_add_new_withdraw_methods');

                       ?>

                     </div>

                       <?php

    }
    //******************************************************************************
    //
    //      DEPOSIT PAGE
    //
    //******************************************************************************
    elseif($pg == "deposit")
    {
                ?>

                <div class="row">
                <div class="col-sm-12 col-lg-12">
                <div class="page-header">
                       <h1 class="page-title">
                         <?php echo sprintf(__('Deposit Money','ProjectTheme')  ) ?>
                       </h1>
                     </div></div></div>



                    <div class="w-100 mb-3"><a href="<?php echo ProjectTheme_get_project_link_with_page($pgid, 'home') ?>" class="btn btn-success btn-sm"><?php _e('Go to Finances home','ProjectTheme') ?></a></div>

                    <?php projecttheme_finances_card_group() ?>

                    <div class="alert alert-secondary"><?php _e('Use the below payment options to deposit more money into your e-wallet','ProjectTheme'); ?></div>

                    <?php

                    $opt = get_option('ProjectTheme_bank_details_enable');
                    if($opt == "yes"){


                      ?>


                      <div class="card mb-4"> <div class="box_content">

                              <strong><?php _e('Deposit money by Bank','ProjectTheme'); ?></strong><br/><br/>


                                <div class="input-group">
                                      <p class="w-100 mb-4"><?php _e('Please use the bank details below to deposit money, and after please notify us to manually add balance to your account.','ProjectTheme'); ?>
                                      </p>



                                      <h4 class="w-100"><b><?php _e('Bank details:' ,'' ) ?></b></h4>
                                      <p><?php echo stripslashes(get_option('ProjectTheme_bank_details_txt')) ?></p>
                                    </div>



                          </div>	</div>



                      <?php


                    }



                    $opt = get_option('ProjectTheme_paypal_enable');
                    if($opt == "yes"){

                          ?>

                          <div class="card mb-4"> <div class="box_content">

                                  <strong><?php _e('Deposit money by PayPal','ProjectTheme'); ?></strong><br/><br/>

                                  <form method="post" action="<?php echo esc_url( home_url() )  ?>/?p_action=paypal_deposit_pay"> <input type="hidden" value="deposit" name="deposit" />
                                    <?php

                                          $fee = get_option('projectTheme_tax_fee_paypal_deposit');
                                          if($fee > 0)
                                          {


                                     ?>
                                      <div class="input-group"><?php echo sprintf(__('You will have to pay a fee of %s on top of this amount.','ProjectTheme'), $fee."%") ?></div>

                                    <?php } ?>
                                    <div class="input-group">
                                          <span class="input-group-prepend">
                                            <span class="input-group-text"><?php echo projectTheme_currency(); ?></span>
                                          </span>
                                          <input type="number" step="0.01" name="amount" required class="form-control no-border-radius" class="amount" placeholder="<?php _e("Amount to deposit","ProjectTheme"); ?>">

                                          <span class="input-group-append">
                                                <button class="btn btn-success" type="submit"><?php _e('Deposit','ProjectTheme'); ?></button>
                                              </span>

                                        </div>


                                </form>
                              </div>	</div>

                          <?php


                    } //endif paypal

                    $ProjectTheme_moneybookers_enable = get_option('ProjectTheme_moneybookers_enable');
            				if($ProjectTheme_moneybookers_enable == "yes"){

                     ?>

                     <div class="card mb-4"> <div class="box_content">

                             <strong><?php _e('Deposit money by Skrill','ProjectTheme'); ?></strong><br/><br/>

                             <form method="post" action="<?php echo esc_url( home_url() )  ?>/?p_action=mb_deposit_pay">



                             <div class="input-group">
                                   <span class="input-group-prepend">
                                     <span class="input-group-text"><?php echo projectTheme_currency(); ?></span>
                                   </span>
                                   <input type="number" step="0.01" required class="form-control no-border-radius" name="amount" placeholder="<?php _e("Amount to deposit","ProjectTheme"); ?>">

                                   <span class="input-group-append">
                                         <button class="btn btn-success" type="submit"><?php _e('Deposit','ProjectTheme'); ?></button>
                                       </span>

                                 </div>


                           </form>





                         </div>
                         </div>


                   <?php } //endif skrill

                        do_action('ProjectTheme_deposit_methods', $uid);

                    ?>

                  </div>

                <?php
          }
          //******************************************************************************
          //
          //      FINANCES MAIN SCREEN
          //
          //******************************************************************************
          else {
            //// else no deposit ------- home screen --------

     ?>


     <div class="row">
     <div class="col-sm-12 col-lg-12">
     <div class="page-header">
            <h1 class="page-title">
              <?php echo sprintf(__('Finances','ProjectTheme')  ) ?>
            </h1>
          </div></div></div>


    <?php


    if($_GET['cardonfile'] == 1)
    {
       ?>
            <script src="https://js.stripe.com/v3/"></script>
            <script>


            function stripeTokenHandler(token) {
    // Insert the token ID into the form so it gets submitted to the server
    const form = document.getElementById('payment-form');
    const hiddenInput = document.createElement('input');
    hiddenInput.setAttribute('type', 'hidden');
    hiddenInput.setAttribute('name', 'stripeToken');
    hiddenInput.setAttribute('value', token.id);
    form.appendChild(hiddenInput);    // Submit the form
    form.submit();
}


                jQuery(document).ready(function(){
                  var stripe = Stripe('<?php echo get_option('ProjectTheme_stripe_publishable_test') ?>');

                      var elements = stripe.elements();
                      var cardElement = elements.create('card');
                      cardElement.mount('#card-element');


                      var cardholderName = document.getElementById('cardholder-name');
                      var cardButton = document.getElementById('card-button');
                      var resultContainer = document.getElementById('card-result');

                      cardButton.addEventListener('click', function(ev) {

                        stripe.createPaymentMethod({
                            type: 'card',
                            card: cardElement,
                            billing_details: {
                              name: cardholderName.value,
                            },
                          }
                        ).then(function(result) {
                          if (result.error) {
                            // Display error.message in your UI
                            resultContainer.textContent = result.error.message;
                          } else {
                            // You have successfully created a new PaymentMethod
                            console.log(result);
                            resultContainer.textContent = "Redirecting...."; //"Created payment method: " + result.paymentMethod.id;

                            stripe.createToken(cardElement).then(function(result1) {
                                    // Handle result.error or result.token
                                    if (result1.error) {

                                    }
                                    else {
                                           stripeTokenHandler(result1.token);
                                    }

                                  });


                          }
                        });
                      });


                });


            </script>
            <div class="alert alert-danger">  <?php   echo '<p>'; _e('You need to add a credit card to your account to be able to hire an hourly rate freelancer.','ProjectTheme'); echo '</p>'; ?>     </div>

            <div class="card p-3">

                <div style="max-width: 450px">


              <style>
       /* Blue outline on focus */
       .StripeElement--focus {
           border-color: #80BDFF;
           outline:0;
           box-shadow: 0 0 0 .2rem rgba(0,123,255,.25);
           transition: border-color .15s ease-in-out, box-shadow .15s ease-in-out;
       }
       /* Can't see what I type without this */
       #card-number.form-control,
       #card-cvc.form-control,
       #card-exp.form-control {
           display:inline-block;
       }
   </style>
<form action="<?php echo get_site_url() ?>/?add_card_for_me=1" method="post" id="payment-form">

                      <input id="cardholder-name" class="form-control mb-2" placeholder="Name on the card" type="text">
                          <!-- placeholder for Elements -->
                          <div id="card-element" style="height: 2.4em; padding: .7em; border:1px solid #aaa"></div>
                          <div id="card-result"></div>


                        </form>

                      <?php
                      echo '<p class="mt-3"><a href="#" id="card-button" class="btn btn-primary">'.__('Add your card','ProjectTheme').'</a></p>';


                 ?>

               </div>     </div>
       <?php
    }
$uid = get_current_user_id();

        $added_card_on = get_user_meta($uid,'added_card_on',true);
        $last4 = get_user_meta($uid,'last4',true);

        if(!empty($added_card_on))
        {
            ?>
            <div class="alert alert-success">

                <p>You have added your card successfully.</p>
                <p>**** **** **** <?php echo   $last4; ?></p>

              </div>
            <?php
        }



        do_action('pt_on_top_finances_home');

        if(isset($_GET['success']))
        {
          echo '<div class="alert alert-success">'; echo __('You have activated the free membership!','ProjectTheme'); echo '</div>';
        }


    if(ProjectTheme_is_user_provider($uid)){

          do_action('stripe_connect_thing_notification');

    }



     ?>

      <?php projecttheme_finances_card_group() ?>


      <?php

            $out = pt_get_outgoing_payments_nr(get_current_user_id());
            if($out > 0)
            {
                $out = '<span class="noti-noti">' . $out . '</span>';
            } else $out = '';

             $ProjectTheme_payment_model = get_option('ProjectTheme_payment_model');

       ?>


   <ul class="nav nav-tabs" id="myTab-main" role="tablist">
     <?php

        if("marketplace_gateways" == $ProjectTheme_payment_model)
        {
            ?>


            <li class="nav-item">
              <a class="nav-link <?php echo $pg == 'home' ? 'active' : '' ?>" id="home-tab"  href="<?php echo ProjectTheme_get_project_link_with_page($pgid, 'home'); ?>" ><?php _e('Incoming Payments','ProjectTheme') ?></a>
            </li>

            <li class="nav-item">
              <a class="nav-link <?php echo $pg == 'outgoing' ? 'active' : '' ?>" id="home-tab"  href="<?php echo ProjectTheme_get_project_link_with_page($pgid, 'outgoing'); ?>" ><?php printf(__('Outgoing Payments %s','ProjectTheme'), $out) ?></a>
            </li>


            <?php
        }
        else {


      ?>

     <li class="nav-item">
       <a class="nav-link <?php echo $pg == 'home' ? 'active' : '' ?>" id="home-tab"  href="<?php echo ProjectTheme_get_project_link_with_page($pgid, 'home'); ?>" ><?php _e('Incoming Payments','ProjectTheme') ?></a>
     </li>

     <?php
     if(ProjectTheme_is_user_business(get_current_user_id()))
     {

       ?>

     <li class="nav-item">
       <a class="nav-link <?php echo $pg == 'outgoing' ? 'active' : '' ?>" id="home-tab"  href="<?php echo ProjectTheme_get_project_link_with_page($pgid, 'outgoing'); ?>" ><?php printf(__('Outgoing Payments %s','ProjectTheme'), $out) ?></a>
     </li>


   <?php } } ?>



   <li class="nav-item">
     <a class="nav-link <?php echo $pg == 'transactions' ? 'active' : '' ?>" id="profile-tab" href="<?php echo ProjectTheme_get_project_link_with_page($pgid, 'transactions'); ?>"><?php _e('Transactions','ProjectTheme') ?></a>
   </li>
   </ul>


   <?php

     $current_page = empty($_GET['pj']) ? 1 : $_GET['pj'];

     $amount_per_page = 10;
     $offset = ($current_page -1)*$amount_per_page;

     //------------------------------------------------

     if($pg == "home")
     {


       $date_format =  get_option( 'date_format' );

        $prf = $wpdb->prefix;
        $s = "select SQL_CALC_FOUND_ROWS * from ".$prf."project_escrows escrow, ".$prf."project_orders orders where orders.id=escrow.oid and escrow.toid='$uid' and escrow.released='0' order by escrow.id desc limit $offset, $amount_per_page";
        $r = $wpdb->get_results($s);

        $total_rows   = projecttheme_get_last_found_rows();
        $own_pagination = new own_pagination($amount_per_page, $total_rows, ProjectTheme_get_project_link_with_page($pgid, 'home'). "&");



  ?>

  <div class="card" style="border-top:0">

  <?php

        if(count($r) > 0)
        {
              ?>
              <div class="p-3"><div class="table-responsive">
              <table class="table table-hover table-outline table-vcenter   card-table">
                <thead><tr>

                  <th><?php echo __('Project Title','ProjectTheme'); ?></th>
                  <th><?php echo __('Amount','ProjectTheme') ?></th>
                  <th><?php echo __('From User','ProjectTheme') ?></th>
                  <th><?php echo __('Date Made','ProjectTheme') ?></th>
                  <th><?php echo __('Options','ProjectTheme') ?></th>

                 </tr></thead><tbody>

                   <?php

                          foreach($r as $row)
                          {
                                  $pst = get_post($row->pid);
                                  $from_user = get_userdata($row->fromid);
                              ?>

                                  <tr>
                                        <td><a href="<?php echo get_permalink($pst->ID) ?>"><?php echo $pst->post_title ?></a></td>
                                        <td class='text-success'><?php echo projectTheme_get_show_price($row->amount) ?></td>
                                        <td><a href="<?php echo ProjectTheme_get_user_profile_link($from_user->ID) ?>"><?php echo  ($from_user->user_login) ?></a></td>
                                        <td><?php echo date_i18n($date_format, $row->datemade) ?></td>
                                        <td><a href="<?php echo projecttheme_get_workspace_link_from_project_id( $pst->ID ) ?>" class="btn btn-outline-primary btn-sm"><?php _e('View Workspace','ProjectTheme') ?></a></td>
                                  </tr>
                              <?php
                          }

                   ?>


               </tbody>
             </table></div>

              <?php echo $own_pagination->display_pagination(); ?>
            </div>

              <?php
        }
        else {

   ?>


    <div class="p-3">
      <?php _e('You do not have any active projects.','ProjectTheme') ?>
    </div>

  <?php } ?>
</div>

<?php } elseif('outgoing' == $pg){

  global $wpdb;
  $uid = get_current_user_id();

  $prf = $wpdb->prefix;
  $s = "select SQL_CALC_FOUND_ROWS * from ".$prf."project_escrows escrow, ".$prf."project_orders orders where orders.id=escrow.oid and escrow.fromid='$uid' and escrow.released='0' order by escrow.id desc limit $offset, $amount_per_page";
  $r = $wpdb->get_results($s);


  $total_rows   = projecttheme_get_last_found_rows();
  $own_pagination = new own_pagination($amount_per_page, $total_rows, ProjectTheme_get_project_link_with_page($pgid, 'outgoing'). "&");

  ?>

  <div class="card" style="border-top:0">

  <?php

  $date_format = get_option( 'date_format' );

      if(count($r) > 0)
      {
            ?>
            <div class="p-3"><div class="table-responsive">
            <table class="table table-hover table-outline table-vcenter   card-table">
              <thead><tr>

                <th><?php echo __('Project Title','ProjectTheme'); ?></th>
                <th><?php echo __('Amount','ProjectTheme') ?></th>
                <th><?php echo __('To User','ProjectTheme') ?></th>
                <th><?php echo __('Date Made','ProjectTheme') ?></th>
                <th><?php echo __('Options','ProjectTheme') ?></th>

               </tr></thead><tbody>

                 <?php

                       foreach($r as $row)
                       {
                               $pst = get_post($row->pid);
                               $from_user = get_userdata($row->toid);


                           ?>

                               <tr>
                                     <td><a href="<?php echo get_permalink($pst->ID) ?>"><?php echo $pst->post_title ?></a></td>
                                     <td class='text-success'><?php echo projecttheme_get_show_price($row->amount) ?></td>
                                     <td><a href="<?php echo ProjectTheme_get_user_profile_link($from_user->ID) ?>"><?php echo  ($from_user->user_login) ?></a></td>
                                     <td><?php echo date_i18n($date_format, $row->datemade) ?></td>
                                     <td>
                                        <?php
                                                $show_this = 1;
                                                $show_this = apply_filters('PT_show_release_escrow_button_in_finances', $row);

                                                if($show_this == 1)
                                                {
                                        ?>
                                       <a href="<?php echo ProjectTheme_get_project_link_with_page($pgid, 'releaseescrow', '&id=' . $row->id) ?>" class="btn btn-success"><?php _e('Release','ProjectTheme'); ?></a>
                                     <?php  } else echo $show_this; ?>
                                     </td>
                               </tr>
                           <?php
                       }

                 ?>


             </tbody>
           </table></div> <?php echo $own_pagination->display_pagination(); ?> </div>

            <?php
      }
      else {

  ?>


  <div class="p-3">
    <?php _e('You do not have any active projects.','ProjectTheme') ?>
  </div>

  <?php } ?>

  </div>

  <?php }elseif('transactions' == $pg)
  {

  $prf = $wpdb->prefix;
  $s = "select SQL_CALC_FOUND_ROWS * from ".$wpdb->prefix."project_payment_transactions where uid='$uid' order by id desc limit $offset, $amount_per_page";
  $r = $wpdb->get_results($s);

  $total_rows   = projecttheme_get_last_found_rows();
  $own_pagination = new own_pagination($amount_per_page, $total_rows, ProjectTheme_get_project_link_with_page($pgid, 'transactions'). "&");


  ?>

  <div class="card" style="border-top:0">

  <?php

  if(count($r) > 0)
  {
       ?>
       <div class="p-3"><div class="table-responsive">
       <table class="table table-hover table-outline table-vcenter   card-table">
         <thead><tr>

           <th><?php echo __('Event','ProjectTheme'); ?></th>
           <th><?php echo __('Date','ProjectTheme'); ?></th>
           <th><?php echo __('Amount','ProjectTheme') ?></th>
           <?php do_action('projecttheme_transactions_add_table_th') ?>
          </tr></thead><tbody>

            <?php

            $now = current_time('timestamp');

                   foreach($r as $row)
                   {

                              $provider  = get_userdata($row->freelancer);
                              $pst       = get_post($row->pid);

                              if($row->tp == 0){ $class="text-danger"; $sign = "-"; }
                							else { $class="text-success"; $sign = "+"; }

                       ?>

                           <tr>
                                 <td> <?php echo $row->reason ?> </td>
                                 <td><?php echo date_i18n('d-M-Y / H:i', $row->datemade) ?></td>
                                 <td class="<?php echo $class ?>"><?php echo $sign.projectTheme_get_show_price($row->amount, 0) ?></td>
                                    <?php do_action('projecttheme_transactions_add_table_td', $row) ?>
                           </tr>
                       <?php
                   }

            ?>


        </tbody>
      </table> </div><?php echo $own_pagination->display_pagination(); ?>  </div>

       <?php
  }
  else {

  ?>


  <div class="p-3">
  <?php _e('You do not have any transactions.','ProjectTheme') ?>
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
       <div class="p-3"><div class="table-responsive">
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
                                 <td><a href="<?php echo get_permalink($row->ID) ?>"><?php echo $pst->post_title ?></a></td>
                                 <td><a href="<?php echo get_permalink($row->ID) ?>"><?php echo $provider->user_login ?></a></td>
                                 <td class='text-success'><?php echo projectTheme_get_show_price($row->order_total_amount, 0) ?></td>
                                 <td><?php echo date_i18n($date_format, $row->datemade) ?></td>
                                 <td><?php echo  date_i18n($date_format, $row->marked_done_freelancer) ?></td>
                                 <td>
                                   <div class="dropdown z1x1x2"> <span class="noti-noti x1x2z3">1</span>
                                     <button class="btn btn-secondary dropdown-toggle dropdown-functions-settings" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                           <i class="fas fa-cog"></i></button>
                                               <div class="dropdown-menu" id="options-thing-sale" aria-labelledby="dropdownMenuButton">
                                                   <a class="dropdown-item" href="#">Workspace <span class="noti-noti">1</span></a>

                         <a class="dropdown-item" href="<?php echo get_site_url(); ?>/?p_action=mark_completed&id=<?php echo $row->id; ?>"><?php echo __('Mark Completed','ProjectTheme') ?></a>
                               </div>
                             </div>

                           </tr>
                       <?php
                   }

            ?>


        </tbody>
      </table></div> <?php echo $own_pagination->display_pagination(); ?>  </div>

       <?php
  }
  else {

  ?>


  <div class="p-3">
  <?php _e('You do not have any active projects.','ProjectTheme') ?>
  </div>

  <?php } ?>

</div> <!-- end card div -->

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
       <div class="p-3"><div class="table-responsive">
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
                                 <td><?php echo  date_i18n($date_format, $row->marked_done_buyer) ?></td>
                                 <td>-</td>
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
  <?php _e('You do not have any cancelled projects.','ProjectTheme') ?>
  </div>

  <?php } ?>

</div><!-- end card div -->

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
       <div class="p-3"><div class="table-responsive">
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
                                 <td><?php echo  date_i18n($date_format, $row->marked_done_buyer) ?></td>
                                 <td><a href="#" class="btn btn-outline-primary btn-sm"><?php _e('Workspace','ProjectTheme'); ?></a></td>
                           </tr>
                       <?php
                   }

            ?>


        </tbody>
      </table></div><?php echo $own_pagination->display_pagination(); ?>  </div>

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
   <div class="p-3"><div class="table-responsive">
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
                             <td><a href="<?php echo get_the_permalink( $row->ID ); ?>" class='btn btn-outline-primary btn-sm'><?php echo __('Publish','ProjectTheme') ?></a></td>
                       </tr>
                   <?php
               }

        ?>


    </tbody>
  </table></div>

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

</div>

  <?php } ?>

   </div> <!-- end dif content -->





<?php }  ?>

</div></div>






<?php get_template_part('lib/my_account/footer-area-account') ?>

</div>

  <?php


}


  $page = ob_get_contents();
  ob_end_clean();
  return $page;


}


 ?>
