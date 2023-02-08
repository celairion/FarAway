<?php


function classified_theme_my_account_fav_fnc()
{


        ob_start();

        $uid = get_current_user_id();
        ProjectTheme_main_account_menu();


        ?>

              <div class="container mt-4">


                              <h4 class="account-ttl"><?php echo __('Favourites','ProjectTheme') ?></h4>


                                <div class="<?php if(!ProjectTheme_show_list_items()) echo 'row items-grid'; ?>">
                                  <?php

                                    global $wpdb;
                                    $s = "select * from ".$wpdb->prefix."ProjectTheme_watchlist where uid='$uid'";
                                    $r = $wpdb->get_results($s);

                                    if(count($r) > 0)
                                    {
                                          foreach($r as $row)
                                          {
                                            if(ProjectTheme_show_list_items())
                                            {

                                                                      ProjectTheme_single_post_list($row->pid);
                                            }
                                            else
                                            {
                                                 ProjectTheme_single_post_thumb($row->pid);
                                            }

                                          }
                                    }
                                    else
                                    {


                                  ?>
                                        <div class="card p-3">
                                              <?php _e('There are no favourite items.','ProjectTheme') ?>
                                        </div>


                                <?php  } ?>


                              </div>



              </div>



        <?php

        $page = ob_get_contents();
        ob_end_clean(); return $page;

}




?>
