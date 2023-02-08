<?php

add_action('widgets_init', 'projecttheme_frontpage_section_images');
function projecttheme_frontpage_section_images() {
	register_widget('projecttheme_front_page_sections');
}

class projecttheme_front_page_sections extends WP_Widget {

	function __construct() {
		$widget_ops = array( 'classname' => 'front-page-sections', 'description' => 'Show "how it works" section on front page/other page.' );
		$control_ops = array( 'width' => 200, 'height' => 250, 'id_base' => 'front-page-sections' );
		parent::__construct( 'front-page-sections', 'ProjectTheme - Front page sections', $widget_ops );
	}

	function widget($args, $instance) {
		extract($args);

		$tt_margin_bottom =  $instance['tt_margin_bottom'];
		$section_margin_top_bottom =  $instance['section_margin_top_bottom'];

		?>


					<style>
								.elementor-widget-wp-widget-front-page-sections h5
								{
									text-align: <?php echo $instance['orientation'] ?>;
                  font-size:  <?php echo $instance['title_font_size'] ?>;
									margin-bottom: <?php echo $instance['tt_margin_bottom'] ?>px;
								}

								.elementor-widget-wp-widget-front-page-sections
								{
										padding-top: <?php echo $instance['section_margin_top_bottom'] ?>px;
												padding-bottom: <?php echo $instance['section_margin_top_bottom'] ?>px;
								}



					</style>


          <div class="container">

		<?php

		echo $before_widget;

		if ($instance['title']) echo $before_title . apply_filters('widget_title', $instance['title']) . $after_title;
		global $width_widget_categories, $height_widget_categories;


 		echo '<div class="row">';
		for($i=1; $i<=4; $i++)
		{

					echo '<div class="col col-sm-12 col-md-3">';

						?>

							<p class="text-center mb-4">		<img src="<?php echo $instance['column_icon' . $i] ?>" width="55" /> </p>
							<p class="small-widget-title text-center"><?php echo $instance['column_title' . $i] ?></p>

								<p class="mt-4 text-center"><?php echo $instance['column_text' . $i] ?></p>

						<?php

					echo '</div>';


		}
		echo '</div>';


		echo $after_widget;
    ?>

  </div>


    <?php
	}

	//=========================================================================

	function update($new_instance, $old_instance) {

		return $new_instance;
	}


	//=========================================================================

	function form($instance) { ?>
		<p>
			<label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Widget Title','ProjectTheme'); ?>:</label>
			<input type="text" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>"
			value="<?php echo esc_attr( $instance['title'] ); ?>" style="width:95%;" />
		</p>


		<p>
			<label for="<?php echo $this->get_field_id('tt_margin_bottom'); ?>"><?php _e('Title margin bottom','ProjectTheme'); ?>:</label>
			<input type="text" id="<?php echo $this->get_field_id('tt_margin_bottom'); ?>" name="<?php echo $this->get_field_name('tt_margin_bottom'); ?>"
			value="<?php echo esc_attr( $instance['tt_margin_bottom'] ); ?>" style="width:95%;" placeholder="px" />
		</p>


		<p>
			<label for="<?php echo $this->get_field_id('section_margin_top_bottom'); ?>"><?php _e('Section margin top/bottom','ProjectTheme'); ?>:</label>
			<input type="text" id="<?php echo $this->get_field_id('section_margin_top_bottom'); ?>" name="<?php echo $this->get_field_name('section_margin_top_bottom'); ?>"
			value="<?php echo esc_attr( $instance['section_margin_top_bottom'] ); ?>" style="width:95%;" placeholder="px" />
		</p>


    <p>
			<label for="<?php echo $this->get_field_id('title_font_size'); ?>"><?php _e('Font size title','ProjectTheme'); ?>:</label>
			<input type="text" id="<?php echo $this->get_field_id('title_font_size'); ?>" name="<?php echo $this->get_field_name('title_font_size'); ?>"
			value="<?php echo esc_attr( $instance['title_font_size'] ); ?>" style="width:95%;" placeholder="px" />
		</p>



    <?php

          for($i=1; $i<=4; $i++)
          {

     ?>
     <p>
 			<label for="<?php echo $this->get_field_id('column_icon' . $i); ?>"><?php printf(__('Column %s icon','ProjectTheme'), $i); ?>:</label>
 			<input type="text" id="<?php echo $this->get_field_id('column_icon'. $i); ?>" name="<?php echo $this->get_field_name('column_icon'. $i); ?>"
 			value="<?php echo esc_attr( $instance['column_icon' . $i] ); ?>" style="width:95%;" />
 		</p>



    <p>
     <label for="<?php echo $this->get_field_id('column_title' . $i); ?>"><?php printf(__('Column %s title','ProjectTheme'), $i); ?>:</label>
     <input type="text" id="<?php echo $this->get_field_id('column_title'. $i); ?>" name="<?php echo $this->get_field_name('column_title'. $i); ?>"
     value="<?php echo esc_attr( $instance['column_title' . $i] ); ?>" style="width:95%;" />
   </p>


    <p>
			<label for="<?php echo $this->get_field_id('column_content' . $i); ?>"><?php printf(__('Column %s content','ProjectTheme'), $i); ?>:</label>
      <textarea style="height: 150px; width: 100%" name="<?php echo $this->get_field_name('column_text'. $i); ?>"><?php echo esc_attr( $instance['column_text' . $i] ); ?></textarea>
		</p>




  <?php } ?>

		<p>
			<label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title orientation','ProjectTheme'); ?>:</label>


			<select name="<?php echo $this->get_field_name('orientation'); ?>">
				<?php  $vv = esc_attr( $instance['orientation'] ); ?>
					<option value="left" <?php echo $vv == "left" ? "selected='selected'" : "" ?>>Left</option>
					<option value="center" <?php echo $vv == "center" ? "selected='selected'" : "" ?>>Center</option>
					<option value="right" <?php echo $vv == "right" ? "selected='selected'" : "" ?>>Right</option>

			</select>

		</p>






	<?php
	}
}




?>
