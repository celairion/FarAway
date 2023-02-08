<div class="modal fade" id="contactDetailsModal" tabindex="-1" role="dialog" aria-labelledby="contactDetailsModal" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLongTitle"><?php _e('Pay for contact details','ProjectTheme') ?></h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
      <?php


      if(is_user_logged_in())
      {

            $fee = projecttheme_get_show_price(get_option('project_theme_contact_details_fee'));

          ?>
                  <p><?php printf(__('To reveal the customer details, you need to pay a fee of: %s',''), $fee) ?></p>

                  <p class="mt-4">

                      <?php

                            global $post;
                            $pid = $post->ID;

                            $opt = get_option('ProjectTheme_paypal_enable');
                            if($opt == "yes")
                            {

                      ?>

                          <a href="<?php echo get_site_url() ?>/?pay_contact_details_fee_paypal=1&pid=<?php echo $pid ?>"
                            class="btn btn-primary"><?php _e('Pay by PayPal','ProjectTheme'); ?></a>


                    <?php } ?>

                    </p>

          <?php
      }
      else {
        // code...
        printf(__('To reveal the customer details, you need to <a href="%s">login</a> first.','ProjectTheme'), ProjectTheme_login_url());
      }


      ?>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>

      </div>
    </div>
  </div>
</div>
