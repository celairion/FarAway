<?php

// Register and load the widget
function pt_how_it_works_proj_widget() {
    register_widget( 'pt_how_it_works_widget_class' );
}
add_action( 'widgets_init', 'pt_how_it_works_proj_widget' );

// Creating the widget
class pt_how_it_works_widget_class extends WP_Widget {

function __construct() {
parent::__construct(

// Base ID of your widget
'pt_how_it_works_widget_class',

// Widget name will appear in UI
__('ProjectTheme - How It Works', 'ProjectTheme'),

// Widget description
array( 'description' => __( 'Shows the how it works widget.', 'ProjectTheme' ), )
);
}

// Creating widget front-end

public function widget( $args, $instance ) {
$title = apply_filters( 'widget_title', $instance['title'] );

// before and after widget arguments are defined by themes
echo $args['before_widget'];

?>
<style>
      .elementor-widget-wp-widget-pt_how_it_works_widget_class h5
      {
        text-align: <?php echo $instance['orientation'] ?> !important
      }

</style>


<?php


		if ($instance['title']) echo '<h5>' . apply_filters('widget_title', $instance['title']) . '</h5>';
		$limit = $instance['show_projects_limit'];

		if(empty($limit) || !is_numeric($limit)) $limit = 5;

				 global $wpdb;
				 $querystr = "
					SELECT distinct wposts.*
					FROM $wpdb->posts wposts, $wpdb->postmeta wpostmeta
					WHERE wposts.ID = wpostmeta.post_id
					AND wpostmeta.meta_key = 'closed'
					AND wpostmeta.meta_value = '0' AND
					wposts.post_status = 'publish'
					AND wposts.post_type = 'project'
					ORDER BY wposts.post_date DESC LIMIT ".$limit;

				 $pageposts = $wpdb->get_results($querystr, OBJECT);

				 ?>

					 <?php $i = 0; if ($pageposts): ?>
					 <?php global $post; ?>
                     <?php foreach ($pageposts as $post): ?>
                     <?php setup_postdata($post); ?>


                     <?php projectTheme_get_project_front_end(); ?>


                     <?php endforeach;

                      $caption = $instance['search_more_caption'];

                      if(empty($caption))
                      $caption = __('Search For More Projects','ProjectTheme');

						echo '<div class="see-all-freelancers"><a class="btn btn-outline-primary" href="'.get_permalink(get_option('ProjectTheme_advanced_search_page_id')).'">'.$caption.'</a></div>';



					 ?>
                     <?php else : ?> <?php $no_p = 1; ?>
                       <div class="padd100"><p class="center"><?php _e("Sorry, there are no posted projects yet.",'ProjectTheme'); ?></p></div>

                     <?php endif; ?>

                     <?php


echo $args['after_widget'];
}

// Widget Backend
public function form( $instance ) {
if ( isset( $instance[ 'title' ] ) ) {
$title = $instance[ 'title' ];
$search_more_caption = $instance[ 'search_more_caption' ];


}
else {
$title = __( 'New title', 'ProjectTheme' );
}
// Widget admin form
?>
<p>
  <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title','ProjectTheme'); ?>:</label>
  <input type="text" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>"
  value="<?php echo esc_attr( $instance['title'] ); ?>" style="width:95%;" />
</p>

<p>
  <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title orientation','ProjectTheme'); ?>:</label>


  <select name="<?php echo $this->get_field_name('orientation'); ?>">
    <?php  $vv = esc_attr( $instance['orientation'] ); ?>
      <option value="left" <?php echo $vv == "left" ? "selected='selected'" : "" ?>>Left</option>
      <option value="center" <?php echo $vv == "center" ? "selected='selected'" : "" ?>>Center</option>
      <option value="right" <?php echo $vv == "right" ? "selected='selected'" : "" ?>>Right</option>

  </select>

</p>



<p>
  <label for="<?php echo $this->get_field_id('title_1'); ?>"><?php _e('Title #1','ProjectTheme'); ?>:</label>
  <input type="text" id="<?php echo $this->get_field_id('title_1'); ?>" name="<?php echo $this->get_field_name('title_1'); ?>"
  value="<?php echo esc_attr( $instance['title_1'] ); ?>" style="width:95%;" />
</p>


<p>
  <label for="<?php echo $this->get_field_id('icon_1'); ?>"><?php _e('Icon Link #1','ProjectTheme'); ?>:</label>
  <input type="text" id="<?php echo $this->get_field_id('icon_1'); ?>" name="<?php echo $this->get_field_name('icon_1'); ?>"
  value="<?php echo esc_attr( $instance['icon_1'] ); ?>" style="width:95%;" />
</p>


<p>
  <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title #1','ProjectTheme'); ?>:</label>
  <textarea name="<?php echo $this->get_field_name('content_1'); ?>" id="<?php echo $this->get_field_id('content_1'); ?>" style="width:100%; height: 120px"><?php echo esc_attr( $instance['content_1'] ); ?></textarea>
</p>


<!-- ###### -->

<p>
  <label for="<?php echo $this->get_field_id('title_2'); ?>"><?php _e('Title #2','ProjectTheme'); ?>:</label>
  <input type="text" id="<?php echo $this->get_field_id('title_2'); ?>" name="<?php echo $this->get_field_name('title_2'); ?>"
  value="<?php echo esc_attr( $instance['title_2'] ); ?>" style="width:95%;" />
</p>


<p>
  <label for="<?php echo $this->get_field_id('icon_2'); ?>"><?php _e('Icon Link #2','ProjectTheme'); ?>:</label>
  <input type="text" id="<?php echo $this->get_field_id('icon_2'); ?>" name="<?php echo $this->get_field_name('icon_2'); ?>"
  value="<?php echo esc_attr( $instance['icon_2'] ); ?>" style="width:95%;" />
</p>


<p>
  <label for="<?php echo $this->get_field_id('content_2'); ?>"><?php _e('Content #2','ProjectTheme'); ?>:</label>
  <textarea name="<?php echo $this->get_field_name('content_2'); ?>" id="<?php echo $this->get_field_id('content_2'); ?>" style="width:100%; height: 120px"><?php echo esc_attr( $instance['content_2'] ); ?></textarea>
</p>


<!-- ###### -->

<p>
  <label for="<?php echo $this->get_field_id('title_3'); ?>"><?php _e('Title #3','ProjectTheme'); ?>:</label>
  <input type="text" id="<?php echo $this->get_field_id('title_3'); ?>" name="<?php echo $this->get_field_name('title_3'); ?>"
  value="<?php echo esc_attr( $instance['title_3'] ); ?>" style="width:95%;" />
</p>


<p>
  <label for="<?php echo $this->get_field_id('icon_3'); ?>"><?php _e('Icon Link #3','ProjectTheme'); ?>:</label>
  <input type="text" id="<?php echo $this->get_field_id('icon_3'); ?>" name="<?php echo $this->get_field_name('icon_3'); ?>"
  value="<?php echo esc_attr( $instance['icon_3'] ); ?>" style="width:95%;" />
</p>


<p>
  <label for="<?php echo $this->get_field_id('content_3'); ?>"><?php _e('Content #3','ProjectTheme'); ?>:</label>
  <textarea name="<?php echo $this->get_field_name('content_3'); ?>" id="<?php echo $this->get_field_id('content_3'); ?>" style="width:100%; height: 120px"><?php echo esc_attr( $instance['content_3'] ); ?></textarea>
</p>



<!-- ###### -->

<p>
  <label for="<?php echo $this->get_field_id('title_1'); ?>"><?php _e('Title #4','ProjectTheme'); ?>:</label>
  <input type="text" id="<?php echo $this->get_field_id('title_4'); ?>" name="<?php echo $this->get_field_name('title_4'); ?>"
  value="<?php echo esc_attr( $instance['title_4'] ); ?>" style="width:95%;" />
</p>


<p>
  <label for="<?php echo $this->get_field_id('icon_4'); ?>"><?php _e('Icon Link #4','ProjectTheme'); ?>:</label>
  <input type="text" id="<?php echo $this->get_field_id('icon_1'); ?>" name="<?php echo $this->get_field_name('icon_4'); ?>"
  value="<?php echo esc_attr( $instance['icon_4'] ); ?>" style="width:95%;" />
</p>


<p>
  <label for="<?php echo $this->get_field_id('content_4'); ?>"><?php _e('Content #4','ProjectTheme'); ?>:</label>
  <textarea name="<?php echo $this->get_field_name('content_4'); ?>" id="<?php echo $this->get_field_id('content_4'); ?>" style="width:100%; height: 120px"><?php echo esc_attr( $instance['content_4'] ); ?></textarea>
</p>






<?php
}

// Updating widget replacing old instances with new
public function update( $new_instance, $old_instance ) {
$instance = array();
$instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
$instance['search_more_caption'] =  $new_instance['search_more_caption']  ;
$instance['show_projects_limit'] = ( ! empty( $new_instance['show_projects_limit'] ) ) ? strip_tags( $new_instance['show_projects_limit'] ) : '';

return $new_instance;
}
} // Class pt_how_it_works_widget_class ends here




?>
