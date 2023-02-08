<div id="footer">
<div id="colophon" class="container">

  <?php
              get_sidebar( 'footer' );
      ?>


          <div id="site-info">
              <div class="padd10">


                      <div id="site-info-left">
                          <h3><?php echo stripslashes(get_option('ProjectTheme_left_side_footer')); ?></h3>
                      </div>

                      <div id="site-info-right">
                          <?php echo stripslashes(get_option('ProjectTheme_right_side_footer')); ?>
                      </div>


              </div>
          </div>


      </div>
  </div>
