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


?>


<style>

	<?php

				$ProjectTheme_bottom_bar_text_col = get_option('ProjectTheme_bottom_bar_text_col');
				$ProjectTheme_bottom_bar_bg_col = get_option('ProjectTheme_bottom_bar_bg_col');

				if(!empty($ProjectTheme_bottom_bar_text_col))
				{
						?>
									.footer_copyright
									{
										background-color: <?php echo $ProjectTheme_bottom_bar_bg_col ?>
									}
						<?php
				}


				if(!empty($ProjectTheme_bottom_bar_text_col))
				{
						?>
									.footer_copyright, .copyright p, .copyright a
									{
										color: <?php echo $ProjectTheme_bottom_bar_text_col ?>
									}
						<?php
				}

	 ?>

</style>

</div>


	<div id="footer">
	<div id="colophon" class="container">

		<?php
                get_sidebar( 'footer' );
        ?>





        </div>
    </div>


		<div class="footer_copyright pt-15 pb-30">
		<div class="container">
		<div class="footer_copyright_wrapper text-center d-sm-flex justify-content-between align-items-center">
		<div class="copyright mt-15">
		<p><?php echo stripslashes(get_option('ProjectTheme_left_side_footer')); ?></p>
		</div>
		<div class="payment mt-15">
		<ul>
		<li><a href="#"><i class="fab fa-facebook"></i></a></li>
		<li><a href="#"><i class="fab fa-twitter-square"></i></a></li>
		<li><a href="#"><i class="fab fa-instagram-square"></i></a></li>
		<li><a href="#"><i class="fab fa-youtube-square"></i></a></li>
		</ul>
		</div>
		</div>
		</div>
		</div>

</div>


<?php

	$ProjectTheme_enable_google_analytics = get_option('ProjectTheme_enable_google_analytics');
	if($ProjectTheme_enable_google_analytics == "yes"):
		echo stripslashes(get_option('ProjectTheme_analytics_code'));
	endif;

	//----------------

	$ProjectTheme_enable_other_tracking = get_option('ProjectTheme_enable_other_tracking');
	if($ProjectTheme_enable_other_tracking == "yes"):
		echo stripslashes(get_option('ProjectTheme_other_tracking_code'));
	endif;


?>


	<?php
            wp_footer();
    ?>


		    <!--====== Jquery js ======-->
		    <script src="<?php echo get_template_directory_uri()?>/assets/js/vendor/modernizr-3.7.1.min.js"></script>

		    <!--====== Bootstrap js ======-->
		    <script src="<?php echo get_template_directory_uri()?>/assets/js/popper.min.js"></script>
		    <script src="<?php echo get_template_directory_uri()?>/assets/js/bootstrap.min.js"></script>

		    <!--====== Slick js ======-->
		    <script src="<?php echo get_template_directory_uri()?>/assets/js/slick.min.js"></script>

		    <!--====== Magnific Popup js ======-->
		    <script src="<?php echo get_template_directory_uri()?>/assets/js/jquery.magnific-popup.min.js"></script>

		    <!--====== Nice Select js ======-->
		    <script src="<?php echo get_template_directory_uri()?>/assets/js/jquery.nice-select.min.js"></script>

		    <!--====== Counter Up js ======-->
		    <script src="<?php echo get_template_directory_uri()?>/assets/js/waypoints.min.js"></script>
		    <script src="<?php echo get_template_directory_uri()?>/assets/js/jquery.counterup.min.js"></script>

		    <!--====== Price Range js ======-->
		    <script src="<?php echo get_template_directory_uri()?>/assets/js/ion.rangeSlider.min.js"></script>

		    <!--====== Ajax Contact js ======-->
		    <script src="<?php echo get_template_directory_uri()?>/assets/js/ajax-contact.js"></script>

		    <!--====== Main js ======-->
		    <script src="<?php echo get_template_directory_uri()?>/assets/js/main.js"></script>

</div>

<a href="#" class="back-to-top"><i class="fa fa-angle-up"></i></a>

</body>
</html>
