<?php

// Register and load the widget
function pt_main_page_latest_proj_widget() {
    register_widget( 'pt_main_page_latest_proj_big_class' );
}
add_action( 'widgets_init', 'pt_main_page_latest_proj_widget' );

// Creating the widget
class pt_main_page_latest_proj_big_class extends WP_Widget {

function __construct() {
parent::__construct(

// Base ID of your widget
'pt_main_page_latest_proj_big_class',

// Widget name will appear in UI
__('ProjectTheme - Latest posts big', 'ProjectTheme'),

// Widget description
array( 'description' => __( 'Shows the latest posted projects big.', 'ProjectTheme' ), )
);
}

// Creating widget front-end

public function widget( $args, $instance ) {
$title = apply_filters( 'widget_title', $instance['title'] );

// before and after widget arguments are defined by themes
?>

<div class="container">
<?php
echo $args['before_widget'];


$margin_top =  $instance['margin_top'];
$margin_bottom =  $instance['margin_bottom'];

?>
<style>
      .elementor-widget-wp-widget-pt_main_page_latest_proj_big_class h5
      {
        text-align: <?php echo $instance['orientation'] ?> !important
      }

      .elementor-widget-wp-widget-pt_main_page_latest_proj_big_class
      {
          padding-top: <?php echo $margin_top ?>px;
          padding-bottom: <?php echo $margin_bottom ?>px;
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
?>
</div>
<?php
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
  <label for="<?php echo $this->get_field_id('margin_bottom'); ?>"><?php _e('Margin Bottom','ProjectTheme'); ?>:</label>
  <input type="text" id="<?php echo $this->get_field_id('margin_bottom'); ?>" name="<?php echo $this->get_field_name('margin_bottom'); ?>"
  value="<?php echo esc_attr( $instance['margin_bottom'] ); ?>" style="width:95%;" placeholder='px' />
</p>


<p>
  <label for="<?php echo $this->get_field_id('margin_top'); ?>"><?php _e('Margin Top','ProjectTheme'); ?>:</label>
  <input type="text" id="<?php echo $this->get_field_id('margin_top'); ?>" name="<?php echo $this->get_field_name('margin_top'); ?>"
  value="<?php echo esc_attr( $instance['margin_top'] ); ?>" style="width:95%;" placeholder='px' />
</p>



<p>
  <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Search More Button Caption','ProjectTheme'); ?>:</label>
  <input type="text" id="<?php echo $this->get_field_id('search_more_caption'); ?>" name="<?php echo $this->get_field_name('search_more_caption'); ?>"
  value="<?php echo esc_attr( $instance['search_more_caption'] ); ?>" style="width:95%;" />
</p>


		<p>
			<label for="<?php echo $this->get_field_id('show_projects_limit'); ?>"><?php _e('Show projects limit','ProjectTheme'); ?>:</label>
			<input type="text" id="<?php echo $this->get_field_id('show_projects_limit'); ?>" name="<?php echo $this->get_field_name('show_projects_limit'); ?>"
			value="<?php echo esc_attr( $instance['show_projects_limit'] ); ?>" style="width:20%;" />
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
} // Class pt_main_page_latest_proj_big_class ends here




?>
