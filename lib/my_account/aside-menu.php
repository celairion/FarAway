<?php


global $current_user, $wpdb;
$current_user = wp_get_current_user();

  $uid = $current_user->ID;


    $rd = projectTheme_get_unread_number_messages($current_user->ID); $rd1 = $rd;
    if($rd > 0) $ssk_unread_messages = ' &nbsp; <span class="float-right"><span class="badge badge-primary">'.$rd.'</span></span> '; else $ssk_unread_messages = '';


 ?>

 <script>

    jQuery(document).ready(function(){

          var value1 = jQuery(".buyer-menu-itm").html();
          <?php

              if($my_tt_buyer > 0) {
                 ?>

                      jQuery(".buyer-menu-itm").html(value1 + " " + '&nbsp;<span class="float-right"><span class="badge badge-primary"><?php echo $my_tt_buyer ?></span></span>');

                 <?php } ?>

  });

 </script>

 <?php

        global $post;
        $page_id = $post->ID;

        $ProjectTheme_my_account_buyer_area     = get_option('ProjectTheme_my_account_buyer_area');
        $ProjectTheme_my_account_freelancer_area     = get_option('ProjectTheme_my_account_freelancer_area');
        $ProjectTheme_my_account_payments_page      = get_option('ProjectTheme_my_account_payments_id');
        $ProjectTheme_my_account_contests_customer_id      = get_option('ProjectTheme_my_account_contests_customer_id');
        $ProjectTheme_my_account_contests_freelancer_id      = get_option('ProjectTheme_my_account_contests_freelancer_id');
 
        

        // ---- - buyer area number count -------

        $orders = new project_orders();
        $pending_proj = $orders->get_number_of_open_orders_for_buyer($uid);
        $unpub = projecttheme_get_number_of_unpublished($uid);
        $delivered_nr = $orders->get_number_of_delivered_orders_for_buyer($uid);

        $active_quotes = pt_all_received_bids_number($uid);
        $buyer_area_number = $active_quotes + $pending_proj + $unpub + $delivered_nr;

        if($buyer_area_number > 0) $buyer_area_number = '&nbsp; <span class="float-right"><span class="badge badge-primary">'.$buyer_area_number.'</span></span>';
        else $buyer_area_number = '';

        //---------pending projects ----------

        $revnr = projecttheme_get_number_of_due_ratings($uid);

        if($revnr > 0) $revnr = '&nbsp; <span class="float-right"><span class="badge badge-primary">'.$revnr.'</span></span>';
        else $revnr = '';

        //-------

        if($_GET['p_action'] == 'workspaces')
        {
          $workspaces_active = 1;
        }

        $s = "select id from ".$wpdb->prefix."project_bills_site where uid='$uid' and paid='0'";
        $r = $wpdb->get_results($s);

        if(count($r) > 0) $ff = '&nbsp; <span class="float-right"><span class="badge badge-primary">'.count($r).'</span></span>';

  ?>

<aside class="left-sidebar" data-sidebarbg="skin6">
            <!-- Sidebar scroll-->
            <div class="scroll-sidebar ps-container ps-theme-default ps-active-y" data-sidebarbg="skin6" data-ps-id="437dd4f7-8e46-06ef-2ae4-4c003687c2b6">
                <!-- Sidebar navigation-->
                <nav class="sidebar-nav">
                    <ul id="sidebarnav" class="in">
                        <li class="sidebar-item">
                          <a class="sidebar-link sidebar-link" href="<?php echo get_permalink(get_option('ProjectTheme_my_account_page_id')); ?>" aria-expanded="false"><i class="fas fa-home"></i>
                            <?php _e('Dashboard','ProjectTheme') ?></span></a></li>

                            <?php

                                if(ProjectTheme_is_user_business($uid))
                                {

                             ?>
                            <li class="sidebar-item"> <a class="sidebar-link" href="<?php echo get_permalink(get_option('ProjectTheme_post_new_page_id')); ?>" aria-expanded="false">
                              <span class="hide-menu buyer-menu-itm"><i class="fas fa-newspaper"></i> <?php echo sprintf(__('Post New Project','ProjectTheme')) ?></span></a>
                            </li>
                          <?php }

                              if(ProjectTheme_is_user_provider($uid))
                              {
                          ?>


                            <li class="sidebar-item"> <a class="sidebar-link" href="<?php echo get_permalink(get_option('ProjectTheme_advanced_search_page_id')); ?>" aria-expanded="false">
                              <span class="hide-menu buyer-menu-itm"><i class="fas fa-search"></i> <?php echo sprintf(__('Search Projects','ProjectTheme')) ?></span></a>
                            </li>


                            <?php
                          }

                                if(ProjectTheme_is_user_business($uid))
                                {

                             ?>

                            <li class="sidebar-item"> <a class="sidebar-link" href="<?php echo get_permalink(get_option('ProjectTheme_provider_search_page_id')); ?>" aria-expanded="false">
                              <span class="hide-menu buyer-menu-itm"><i class="fas fa-users"></i> <?php echo sprintf(__('Service Providers','ProjectTheme')) ?></span></a>
                            </li>

                          <?php }


                              do_action('projecttheme_before_first_divider')

                          ?>


                        <li class="list-divider"></li>


                        <li class="sidebar-item <?php echo $workspaces_active == 1 ? "selected" : "" ?>">
                          <a class="sidebar-link <?php echo $workspaces_active == 1 ? "active" : "" ?>" href="<?php echo get_permalink(get_option('ProjectTheme_my_account_workspaces_id')); ?>" aria-expanded="false">
                          <span class="hide-menu"><i class="fas fa-clipboard-list"></i> <?php echo sprintf(__('Workspaces','ProjectTheme')) ?></span></a>
                        </li>



                        <li class="sidebar-item <?php echo $ProjectTheme_my_account_payments_page == $page_id ? "selected" : "" ?>">
                          <a class="sidebar-link <?php echo $ProjectTheme_my_account_payments_page == $page_id ? "active" : "" ?>" href="<?php echo get_permalink($ProjectTheme_my_account_payments_page) ?>" aria-expanded="false">
                          <span class="hide-menu buyer-menu-itm"><i class="fas fa-wallet"></i> <?php echo sprintf(__('Finances %s','ProjectTheme'), $ff); ?></span></a>
                        </li>


                        <?php

              					if(function_exists('lv_pp_myplugin_activate'))
              					{
                              $ProjectTheme_my_account_live_messaging = get_option('ProjectTheme_my_account_livechat_id');

              										?>

                           <li class="sidebar-item <?php echo $ProjectTheme_my_account_live_messaging == $page_id ? "selected" : "" ?>">
                             <a class="sidebar-link <?php echo $ProjectTheme_my_account_live_messaging == $page_id ? "active" : "" ?>" href="<?php echo get_permalink(get_option('ProjectTheme_my_account_livechat_id')); ?>" aria-expanded="false">
                           <span class="hide-menu"><i class="far fa-envelope"></i> <?php echo sprintf(__('Messaging %s','ProjectTheme'), $ssk_unread_messages) ?></span></a>
                           </li>

              										<?php

              						}
              						else {

                                $ProjectTheme_my_account_pmid = get_option('ProjectTheme_my_account_private_messages_id');

              					 ?>

                         <li class="sidebar-item <?php echo $ProjectTheme_my_account_pmid == $page_id ? "selected" : "" ?>">
                           <a class="sidebar-link <?php echo $ProjectTheme_my_account_pmid == $page_id ? "active" : "" ?>" href="<?php echo get_permalink(get_option('ProjectTheme_my_account_private_messages_id')); ?>" aria-expanded="false">
                           <span class="hide-menu"><i class="far fa-envelope"></i> <?php echo sprintf(__('Messaging %s','ProjectTheme'), $ssk_unread_messages) ?></span></a>
                         </li>



              					<?php } ?>




                        <li class="sidebar-item"> <a class="sidebar-link" href="<?php echo get_permalink(get_option('ProjectTheme_my_account_personal_info_id')); ?>" aria-expanded="false">
                          <span class="hide-menu"><i class="fas fa-user-cog"></i> <?php echo sprintf(__('Account Settings','ProjectTheme')) ?></span></a>
                        </li>

                          <?php

                          //------ if this user is customer ------
                          //
                          //
                          //----------------------------------------
                          if(ProjectTheme_is_user_business($uid))
                          {

                           ?>

<li class="sidebar-item <?php echo $ProjectTheme_my_account_buyer_area == $page_id ? "selected" : "" ?>">
                          <a class="sidebar-link <?php echo $ProjectTheme_my_account_buyer_area == $page_id ? "active" : "" ?>" href="<?php echo get_permalink(get_option('ProjectTheme_my_account_buyer_area')) ?>" aria-expanded="false">
                          <span class="hide-menu buyer-menu-itm"><i class="fas fa-user-tie"></i> <?php echo sprintf(__('Customer Area %s','ProjectTheme'), $buyer_area_number) ?></span></a>
                        </li>


<?php

if(function_exists('pt_contest_template_redirect'))
{

  ?>

                        <li class="sidebar-item <?php echo $ProjectTheme_my_account_contests_customer_id == $page_id ? "selected" : "" ?>">
                          <a class="sidebar-link <?php echo $ProjectTheme_my_account_contests_customer_id == $page_id ? "active" : "" ?>" href="<?php echo get_permalink(get_option('ProjectTheme_my_account_contests_customer_id')) ?>" aria-expanded="false">
                          <span class="hide-menu buyer-menu-itm"><i class="fas fa-user-tie"></i> <?php echo sprintf(__('Posted Contests','ProjectTheme')) ?></span></a>
                        </li> <?php } ?>


                        <?php

                          }

                        //------ if this user is freelancer ------
                        //
                        //
                        //----------------------------------------

                        if(ProjectTheme_is_user_provider($uid))
                        {

                        $orders           = new project_orders();
                        $pending_projects = $orders->get_number_of_pending_projects_as_freelancer(get_current_user_id());

                        $total_freelancer = $pending_projects;
                        if($total_freelancer > 0) $total_freelancer = '&nbsp; <span class="float-right"><span class="badge badge-primary">' .$total_freelancer. '</span></span>';
                        else $total_freelancer = '';

                         ?>


<li class="sidebar-item <?php echo $ProjectTheme_my_account_freelancer_area == $page_id ? "selected" : "" ?>">
                          <a class="sidebar-link <?php echo $ProjectTheme_my_account_freelancer_area == $page_id ? "active" : "" ?>" href="<?php echo get_permalink( get_option('ProjectTheme_my_account_freelancer_area') ) ?>" aria-expanded="false">
                          <span class="hide-menu"><i class="fas fa-toolbox"></i> <?php echo sprintf(__('Freelancer %s','ProjectTheme'), $total_freelancer) ?></span></a>
                        </li>


                        <?php

if(function_exists('pt_contest_template_redirect'))
{

  ?>


                        <li class="sidebar-item <?php echo $ProjectTheme_my_account_contests_freelancer_id == $page_id ? "selected" : "" ?>">
                          <a class="sidebar-link <?php echo $ProjectTheme_my_account_contests_freelancer_id == $page_id ? "active" : "" ?>" href="<?php echo get_permalink( get_option('ProjectTheme_my_account_contests_freelancer_id') ) ?>" aria-expanded="false">
                          <span class="hide-menu"><i class="fas fa-toolbox"></i> <?php echo sprintf(__('Contests %s','ProjectTheme'), $total_freelancer) ?></span></a>
                        </li> <?php } ?>

                        <?php

                        }

                              $revpage = get_option('ProjectTheme_my_account_feedback_id');

                         ?>
                        <li class="sidebar-item <?php echo $revpage == $page_id ? "selected" : "" ?>">
                          <a class="sidebar-link <?php echo $revpage == $page_id ? "active" : "" ?>" href="<?php echo get_permalink($revpage); ?>" aria-expanded="false">
                          <span class="hide-menu"><i class="fas fa-star"></i> <?php echo sprintf(__('Reviews %s','ProjectTheme'), $revnr) ?></span></a>
                        </li>

                        <?php do_action('ProjectTheme_my_account_main_menu'); ?>


                        <li class="list-divider"></li>

                        <li class="sidebar-item"> <a class="sidebar-link" href="<?php echo wp_logout_url() ?>" aria-expanded="false">
                          <span class="hide-menu"><i class="fas fa-sign-out-alt"></i> <?php echo sprintf(__('Logout','ProjectTheme')) ?></span></a>
                        </li>








                    </ul>
                </nav>
                <!-- End Sidebar navigation -->
            <div class="ps-scrollbar-x-rail" style="left: 0px; bottom: 0px;"><div class="ps-scrollbar-x" tabindex="0" style="left: 0px; width: 0px;"></div></div><div class="ps-scrollbar-y-rail" style="top: 0px; height: 620px; right: 3px;"><div class="ps-scrollbar-y" tabindex="0" style="top: 0px; height: 362px;"></div></div></div>
            <!-- End Sidebar scroll-->
        </aside>
