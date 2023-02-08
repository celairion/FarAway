<?php

get_header();


$key = $_GET['key'];
global $wpdb;

$s = "select * from ".$wpdb->prefix."users where user_activation_key='$key'";
$r = $wpdb->get_results($s);

if(count($r) == 0) $no_user_found = 1;
{

      $row = $r[0];
      $uid = $row->ID;

      update_user_meta( $uid, 'active_user', 			'yes' );

}

 ?>




 <div class="page_heading_me_project pt_template_page_1 " id="pt_template_page_1">
 													<div class="page_heading_me_inner page_heading_me_inner_project container" > <div class="row">
 													<div class="main-pg-title col-xs-12 col-sm-12 col-md-12 col-lg-12" id="x_templ_1_pt">
 															<div class="mm_inn mm_inn21"><?php  printf(__("Activate your account",'ProjectTheme')); ?> </div>
 								</div></div></div></div>




 								<div class="container pt-4">



 								<div class="row">




 									<div class="account-main-area-1 col-xs-12 col-sm-12 col-md-12 col-lg-12">
 								            <div class="card">
 								            	<div class="p-4">

                                  <?php

                                        if($no_user_found == 1)
                                        {
                                              ?>

                                                  <h5><?php echo sprintf(__('The key you are trying to use is not valid.', 'ProjectTheme') ) ?></h5>

                                              <?php
                                        }
                                        else {
                                          // code...

                                   ?>
                                  <h5><?php echo sprintf(__('Your account was activated. You can <a href="%s">login here</a>.', 'ProjectTheme'), get_site_url() . "/wp-login.php") ?></h5>

                                <?php } ?>

 			</div>
 			</div>


         <div class="clear100"></div>

     </div>
         </div>
         </div>

 <?php

 get_footer();

 ?>
