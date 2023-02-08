<?php



add_action('widgets_init', 'pt_register_new_front_page_slider');
function pt_register_new_front_page_slider() {
	register_widget('ProjectTheme_new_front_page_slider');
}

class ProjectTheme_new_front_page_slider extends WP_Widget {

	function __construct() {
		$widget_ops = array( 'classname' => 'new-frontpage-slider', 'description' => 'Add a new frontpage slider - hero image.' );
		$control_ops = array( 'width' => 200, 'height' => 250, 'id_base' => 'new-frontpage-slider' );
		parent::__construct( 'new-frontpage-slider', 'ProjectTheme - New Frontpage Slider', $widget_ops );
	}

	function widget($args, $instance) {
		extract($args);

		echo $before_widget;


  	$hero_image = $instance['hero_image'];

    $main_tagline = $instance['main_tagline'];
    $sub_tagline = $instance['sub_tagline'];

    $button1_caption  = $instance['button1_caption'];
    $button1_link     = $instance['button1_link'];

    $button2_caption  = $instance['button2_caption'];
    $button2_link     = $instance['button2_link'];


    $top_padding = $instance['top_padding'];
    $bottom_padding = $instance['bottom_padding'];


    $show_searchbar = $instance['show_searchbar'];
    $search_bar_background = $instance['search_bar_background'];



		$show_tint = $instance['show_tint'];
    $gradient1 = $instance['gradient1'];
    $gradient2 = $instance['gradient2'];


    projecttheme_front_page_slider_function_new_2023($hero_image, $main_tagline, $sub_tagline, $button1_caption, $button1_link, $button2_caption, $button2_link, $top_padding, $bottom_padding, $show_searchbar, $search_bar_background, $show_tint, $gradient1, $gradient2);

 

		echo $after_widget;
	}

	function update($new_instance, $old_instance) {

		return $new_instance;
	}

	function form($instance) {



    ?>



		<p>
      <label for="<?php echo $this->get_field_id('hero_image'); ?>"><?php _e('Hero Image','ProjectTheme'); ?>:</label>
      <input type="text" id="<?php echo $this->get_field_id('hero_image'); ?>" name="<?php echo $this->get_field_name('hero_image'); ?>"
      value="<?php echo esc_attr( $instance['hero_image'] ); ?>" style="width:95%;" />
    </p>


		<p>
      <label for="<?php echo $this->get_field_id('show_tint'); ?>"><?php _e('Show tint','ProjectTheme'); ?>:</label>

						<select id="<?php echo $this->get_field_id('show_tint'); ?>" name="<?php echo $this->get_field_name('show_tint'); ?>">

									<option value="yes" <?php echo $instance['show_tint'] == "yes" ? "selected='selected'" : "" ?>>Yes</option>
									<option value="no" <?php echo $instance['show_tint'] == "no" ? "selected='selected'" : "" ?>>No</option>

						</select>

		</p>


		<p>
      <label for="<?php echo $this->get_field_id('background_tint'); ?>"><?php _e('Background tint','ProjectTheme'); ?>:</label>



			<input type="text" id="<?php echo $this->get_field_id('gradient1'); ?>" name="<?php echo $this->get_field_name('gradient1'); ?>"
			value="<?php echo esc_attr( $instance['gradient1'] ); ?>" style="width:95%;" placeholder="add a hex color code" />


			<input type="text" id="<?php echo $this->get_field_id('gradient2'); ?>" name="<?php echo $this->get_field_name('gradient2'); ?>"
			value="<?php echo esc_attr( $instance['gradient2'] ); ?>" style="width:95%;" placeholder="add a hex color code" />





    </p>


    <p>
			<label for="<?php echo $this->get_field_id('main_tagline'); ?>"><?php _e('Main Tagline','ProjectTheme'); ?>:</label>
			<input type="text" id="<?php echo $this->get_field_id('main_tagline'); ?>" name="<?php echo $this->get_field_name('main_tagline'); ?>"
			value="<?php echo esc_attr( $instance['main_tagline'] ); ?>" style="width:95%;" />
		</p>


    <p>
			<label for="<?php echo $this->get_field_id('sub_tagline'); ?>"><?php _e('Sub Tagline','ProjectTheme'); ?>:</label>
			<input type="text" id="<?php echo $this->get_field_id('sub_tagline'); ?>" name="<?php echo $this->get_field_name('sub_tagline'); ?>"
			value="<?php echo esc_attr( $instance['sub_tagline'] ); ?>" style="width:95%;" />
		</p>


    <p>
			<label for="<?php echo $this->get_field_id('button1_caption'); ?>"><?php _e('Button #1 Caption','ProjectTheme'); ?>:</label>
			<input type="text" id="<?php echo $this->get_field_id('button1_caption'); ?>" name="<?php echo $this->get_field_name('button1_caption'); ?>"
			value="<?php echo esc_attr( $instance['button1_caption'] ); ?>" style="width:95%;" />
		</p>


    <p>
			<label for="<?php echo $this->get_field_id('button1_link'); ?>"><?php _e('Button #1 Link','ProjectTheme'); ?>:</label>
			<input type="text" id="<?php echo $this->get_field_id('button1_link'); ?>" name="<?php echo $this->get_field_name('button1_link'); ?>"
			value="<?php echo esc_attr( $instance['button1_link'] ); ?>" style="width:95%;" />
		</p>



    <p>
			<label for="<?php echo $this->get_field_id('button2_caption'); ?>"><?php _e('Button #2 Caption','ProjectTheme'); ?>:</label>
			<input type="text" id="<?php echo $this->get_field_id('button2_caption'); ?>" name="<?php echo $this->get_field_name('button2_caption'); ?>"
			value="<?php echo esc_attr( $instance['button2_caption'] ); ?>" style="width:95%;" />
		</p>


    <p>
			<label for="<?php echo $this->get_field_id('button2_link'); ?>"><?php _e('Button #2 Link','ProjectTheme'); ?>:</label>
			<input type="text" id="<?php echo $this->get_field_id('button2_link'); ?>" name="<?php echo $this->get_field_name('button2_link'); ?>"
			value="<?php echo esc_attr( $instance['button2_link'] ); ?>" style="width:95%;" />
		</p>



    <p>
			<label for="<?php echo $this->get_field_id('show_searchbar'); ?>"><?php _e('Show search bar','ProjectTheme'); ?>:</label>

      <?php

            $val = $instance['button2_link'];

       ?>

      <select name="<?php echo $this->get_field_name('show_searchbar'); ?>" id="<?php echo $this->get_field_id('show_searchbar'); ?>">
          <option value="yes" <?php echo $val == "yes" ? "selected='selected'" : "" ?>>Yes</option>
          <option value="no" <?php echo $val == "no" ? "selected='selected'" : "" ?>>No</option>
      </select>
		</p>


    <p>
      <label for="<?php echo $this->get_field_id('search_bar_background'); ?>"><?php _e('Search bar background','ProjectTheme'); ?>:</label>
      <input type="text" id="<?php echo $this->get_field_id('search_bar_background'); ?>" name="<?php echo $this->get_field_name('search_bar_background'); ?>"
      value="<?php echo esc_attr( $instance['search_bar_background'] ); ?>" style="width:95%;" placeholder="Color code hex here" />
    </p>




    <p>
      <label for="<?php echo $this->get_field_id('top_padding'); ?>"><?php _e('Top Padding','ProjectTheme'); ?>:</label>
      <input type="text" id="<?php echo $this->get_field_id('top_padding'); ?>" name="<?php echo $this->get_field_name('top_padding'); ?>"
      value="<?php echo esc_attr( $instance['top_padding'] ); ?>" style="width:95%;" placeholder="px" />
    </p>


    <p>
      <label for="<?php echo $this->get_field_id('bottom_padding'); ?>"><?php _e('Bottom Padding','ProjectTheme'); ?>:</label>
      <input type="text" id="<?php echo $this->get_field_id('bottom_padding'); ?>" name="<?php echo $this->get_field_name('bottom_padding'); ?>"
      value="<?php echo esc_attr( $instance['bottom_padding'] ); ?>" style="width:95%;" placeholder="px" />
    </p>




	<?php
	}
}



?>
