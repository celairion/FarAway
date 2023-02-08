<?php

$pid = $_GET['pid'];
$pst = get_post($pid);
$post_au = $pst;

get_header();

$ttl = $pst->post_title;
$uid = get_current_user_id();


 ?>


 <div class="page_heading_me pt_template_page_1" id="pt_template_page_1">
 	<div class="page_heading_me_inner">
     <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12" id="x_templ_1_pt">
     	<div class="mm_inn"><?php

 						echo sprintf(	__('Message Board: %s','ProjectTheme'), $ttl);

 						?>
                      </div></div>




     </div>
 </div>


 <div class="container mt-3">
 <div class="row">



 <div id="content" class="col-xs-12 col-sm-12 col-md-12 col-lg-12">


        <?php


        if(!is_user_logged_in())
        {
          echo '<div class="card p-3"><p>';
          echo sprintf(__('You are not logged in. In order to bid please <a href="%s">login</a> or <a href="%s">register</a> an account','ProjectTheme'),
          home_url().'/wp-login.php',home_url().'/wp-login.php?action=register');
          echo '</p></div>';
        }
        else {
          // code...

          global $wpdb;

          if(isset($_POST['sumbit_message']) and ($uid == $pst->post_author or ProjectTheme_is_user_provider($uid)))
          {
            $my_message = projecttheme_sanitize_string($_POST['my_message']);

            if(!empty($my_message)):
            $now = $_POST['now'];

            $s = "select * from ".$wpdb->prefix."project_message_board where pid='$pid' and datemade='$now' and uid='$uid'";
            $r = $wpdb->get_results($s);

            if(count($r) == 0)
            {

                  $sh = "insert into ".$wpdb->prefix."project_message_board (pid, content, datemade, uid) values('$pid','$my_message','$now', '$uid')";
                  $wpdb->query($sh);


                  if($uid != $post_au->post_author)
                  {
                    ProjectTheme_send_email_on_message_board_owner($pid, $post_au->post_author, $uid);
                  }
                  else
                  {
                    $sh1 = "select distinct uid from ".$wpdb->prefix."project_message_board where pid='$pid' and uid!='$uid' AND uid!='".$post_au->post_author."'";
                    $rh1 = $wpdb->get_results($sh1);

                    foreach($rh1 as $kl)
                    {
                      ProjectTheme_send_email_on_message_board_bidder($pid, $post_au->post_author, $kl->uid);
                    }
                  }

            }

            $user = get_userdata($uid);


              echo '<div class="alert alert-success">';
                echo sprintf(__('Message posted successfuly.','ProjectTheme')  );
              echo '</div>';


            endif;

          }



          ?>


          <?php

            if($uid == $pst->post_author or ProjectTheme_is_user_provider($uid)):

          ?>  <div class="card p-3 mb-3">

                      <div class="padd10">
                      <form method="post" > <input type="hidden" value="<?php echo current_time('timestamp') ?>" name="now" />
                        <table class="table">
                          <tr><td><textarea name="my_message" required class="form-control" rows="5" placeholder="<?php _e('Your Message','ProjectTheme'); ?>" ></textarea></td></tr>

                      <tr><td>
                                      <input class="btn btn-primary" type="submit" id="sumbit_message" name="sumbit_message" value="<?php _e("Send Message",'ProjectTheme'); ?>" /> <a class="btn btn-secondary" href="<?php echo get_permalink($pid) ?>"><?php _e("Return to project",'ProjectTheme'); ?></a>

                                    </td></tr>
                    </table>


                          </form>
                   </div>   </div>


                     <div class="card p-3">


                       <?php


          			 $sh = "select * from ".$wpdb->prefix."project_message_board where pid='$pid' order by id desc";
          			 $rh = $wpdb->get_results($sh);

          			 if(count($rh) > 0):

                   echo '<ul class="message-board-list">';
          			 foreach($rh as $row):

          			 $user 			= get_userdata($row->uid);
          			 $now 			= $row->datemade;
          			 $my_message 	= $row->content;

          		echo '<li class="list-item-board">';
          			echo '<div class="message_board_title">';
          				echo sprintf(__('Posted by <b>%s</b> on <b>%s</b>','ProjectTheme'), $user->user_login, date_i18n(get_option('date_format'), $now) );
          			echo '</div>';


          			echo '<div class="message_board_message">';
          				echo htmlentities($my_message);
          			echo '</div>';

          		echo '</li>';
          			 endforeach;

                 echo '</ul>';

          			 else:

          			 _e('No messages posted yet.','ProjectTheme');

          			 endif;

          			 ?>



                      </div>

            <?php

             endif;

             ?>


          <?php
        }

         ?>



        </div>
        </div>
        </div>


        <?php get_footer() ?>
