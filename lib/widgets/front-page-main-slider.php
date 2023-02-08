<?php



// Register and load the widget
function pt_main_page_slider_bo_widget() {
    register_widget( 'pt_main_page_slider_bo_widget_class' );
}
add_action( 'widgets_init', 'pt_main_page_slider_bo_widget' );

// Creating the widget
class pt_main_page_slider_bo_widget_class extends WP_Widget {

function __construct() {
parent::__construct(

// Base ID of your widget
'pt_main_page_slider_bo_widget_class',

// Widget name will appear in UI
__('ProjectTheme - Main Slider Home', 'ProjectTheme'),

// Widget description
array( 'description' => __( 'Shows the big slider for project theme homepage.', 'ProjectTheme' ), )
);
}

// Creating widget front-end

public function widget( $args, $instance ) {
$title = apply_filters( 'widget_title', $instance['title'] );

// before and after widget arguments are defined by themes
echo $args['before_widget'];
 
 projecttheme_front_page_slider_function();

echo $args['after_widget'];
}

// Widget Backend
public function form( $instance ) {
if ( isset( $instance[ 'title' ] ) ) {
$title = $instance[ 'title' ];
}
else {
$title = __( 'New title', 'ProjectTheme' );
}
// Widget admin form
?>
<p>
<!--<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:', 'ProjectTheme' ); ?></label>
<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" /> -->


Control the options for this widget, and the captions of the buttons, links, images and more from <a href="<?php echo admin_url() ?>admin.php?page=layout-settings&activate_tab=tabs_home" target="_blank">this link</a>.
</p>
<?php
}

// Updating widget replacing old instances with new
public function update( $new_instance, $old_instance ) {
$instance = array();
$instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
return $instance;
}
} // Class pt_main_page_slider_bo_widget_class ends here




?>
