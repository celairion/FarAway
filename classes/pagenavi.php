<?php


function wp_pagenavi_project( $args = array() )
{
	if ( !is_array( $args ) ) {
		$argv = func_get_args();

		$args = array();
		foreach ( array( 'before', 'after', 'options' ) as $i => $key ) {
			$args[ $key ] = isset( $argv[ $i ]) ? $argv[ $i ] : '';
		}
	}

	$args = wp_parse_args( $args, array(
		'before' => '',
		'after' => '',
		'wrapper_tag' => 'div',
		'wrapper_class' => 'wp-pagenavi',
		'options' => array(),
		'query' => $GLOBALS['wp_query'],
		'type' => 'posts',
		'echo' => true
	) );

	extract( $args, EXTR_SKIP );

	$options = wp_parse_args( $options, PageNavi_Core::$options->get() );

	$instance = new PageNavi_Call( $args );

	list( $posts_per_page, $paged, $total_pages ) = $instance->get_pagination_args();

	if ( 1 == $total_pages && !$options['always_show'] )
		return;

	$pages_to_show = absint( $options['num_pages'] );
	$larger_page_to_show = absint( $options['num_larger_page_numbers'] );
	$larger_page_multiple = absint( $options['larger_page_numbers_multiple'] );
	$pages_to_show_minus_1 = $pages_to_show - 1;
	$half_page_start = floor( $pages_to_show_minus_1/2 );
	$half_page_end = ceil( $pages_to_show_minus_1/2 );
	$start_page = $paged - $half_page_start;

	if ( $start_page <= 0 )
		$start_page = 1;

	$end_page = $paged + $half_page_end;

	if ( ( $end_page - $start_page ) != $pages_to_show_minus_1 )
		$end_page = $start_page + $pages_to_show_minus_1;

	if ( $end_page > $total_pages ) {
		$start_page = $total_pages - $pages_to_show_minus_1;
		$end_page = $total_pages;
	}

	if ( $start_page < 1 )
		$start_page = 1;

	// Support for filters to change class names
	$class_names = array(
		'pages' => 'page-item',
		'first' => apply_filters( 'wp_pagenavi_class_first', 'first' ),
		'previouspostslink' => 'page-link',
		'extend' => 'page-item',
		'smaller' => apply_filters( 'wp_pagenavi_class_smaller', 'smaller' ),
		'page' => 'page-link',
		'current' => apply_filters( 'wp_pagenavi_class_current', 'current'),
		'larger' => apply_filters( 'wp_pagenavi_class_larger', 'larger' ),
		'nextpostslink' => apply_filters( 'wp_pagenavi_class_nextpostslink', 'nextpostslink'),
		'last' => apply_filters( 'wp_pagenavi_class_last', 'last'),
	);

	$out = '';
	switch ( intval( $options['style'] ) ) {
		// Normal
		case 1:
			// Text
			if ( !empty( $options['pages_text'] ) ) {
				$pages_text = str_replace(
					array( "%CURRENT_PAGE%", "%TOTAL_PAGES%" ),
					array( number_format_i18n( $paged ), number_format_i18n( $total_pages ) ),
					__( $options['pages_text'], 'wp-pagenavi' ) );
				$out .= "<li class='page-item disabled'><a href='#' class='page-link'>$pages_text</a></li>";
			}

			if ( $start_page >= 2 && $pages_to_show < $total_pages ) {
				// First
				$first_text = str_replace( '%TOTAL_PAGES%', number_format_i18n( $total_pages ), __( $options['first_text'], 'wp-pagenavi' ) );
				$out .= $instance->get_single( 1, $first_text, array(
					'class' => $class_names['first']
				), '%TOTAL_PAGES%' );
			}

			// Previous
			if ( $paged > 1 && !empty( $options['prev_text'] ) ) {
				$out .= "<li aria-current='page' class='page-item'>" . $instance->get_single( $paged - 1, $options['prev_text'], array(
					'class' => $class_names['previouspostslink'],
					'rel'   => 'prev'
				) ) . '</li>';
			}

			if ( $start_page >= 2 && $pages_to_show < $total_pages ) {
				if ( !empty( $options['dotleft_text'] ) )
					$out .= "<li class='{$class_names['extend']}'>{$options['dotleft_text']}</li>";
			}

			// Smaller pages
			$larger_pages_array = array();
			if ( $larger_page_multiple )
				for ( $i = $larger_page_multiple; $i <= $total_pages; $i+= $larger_page_multiple )
					$larger_pages_array[] = $i;

			$larger_page_start = 0;
			foreach ( $larger_pages_array as $larger_page ) {
				if ( $larger_page < ($start_page - $half_page_start) && $larger_page_start < $larger_page_to_show ) {
					$out .= $instance->get_single( $larger_page, $options['page_text'], array(
						'class' => "{$class_names['smaller']} {$class_names['page']}",
						'title' => sprintf( __( 'Page %s', 'wp-pagenavi' ), number_format_i18n( $larger_page ) ),
					) );
					$larger_page_start++;
				}
			}

			if ( $larger_page_start )
				$out .= "<li class='{$class_names['extend']}'>{$options['dotleft_text']}</li>";

			// Page numbers
			$timeline = 'smaller';
			foreach ( range( $start_page, $end_page ) as $i ) {
				if ( $i == $paged && !empty( $options['current_text'] ) ) {
					$current_page_text = str_replace( '%PAGE_NUMBER%', number_format_i18n( $i ), $options['current_text'] );
					$out .= "<li aria-current='page' class='page-item active'><a href='#'  class='page-link ' >$current_page_text</a></li>";
					$timeline = 'larger';
				} else {
					$out .= '<li class="page-item">'.$instance->get_single( $i, $options['page_text'], array(
						'class' => "{$class_names['page']} {$class_names[$timeline]}",
						'title' => sprintf( __( 'Page %s', 'wp-pagenavi' ), number_format_i18n( $i ) ),
					) ) . "</li>";
				}
			}

			// Large pages
			$larger_page_end = 0;
			$larger_page_out = '';
			foreach ( $larger_pages_array as $larger_page ) {
				if ( $larger_page > ($end_page + $half_page_end) && $larger_page_end < $larger_page_to_show ) {
					$larger_page_out .= $instance->get_single( $larger_page, $options['page_text'], array(
						'class' => "{$class_names['larger']} {$class_names['page']}",
						'title' => sprintf( __( 'Page %s', 'wp-pagenavi' ), number_format_i18n( $larger_page ) ),
					) );
					$larger_page_end++;
				}
			}

			if ( $larger_page_out ) {
				$out .= "<li class='{$class_names['extend']}'>{$options['dotright_text']}</li>";
			}
			$out .= $larger_page_out;

			if ( $end_page < $total_pages ) {
				if ( !empty( $options['dotright_text'] ) )
					$out .= "<li class='{$class_names['extend']}'>{$options['dotright_text']}</li>";
			}

			// Next
			if ( $paged < $total_pages && !empty( $options['next_text'] ) ) {
				$out .= '<li class="page-item">' . $instance->get_single( $paged + 1, $options['next_text'], array(
					'class' => 'page-link',
					'rel'   => 'next'
				) ). "</li>";
			}

			if ( $end_page < $total_pages ) {
				// Last
				$out .= $instance->get_single( $total_pages, __( $options['last_text'], 'wp-pagenavi' ), array(
					'class' => $class_names['last'],
				), '%TOTAL_PAGES%' );
			}
			break;


	}

  $wrapper_tag = 'ul';
  $wrapper_class = 'pagination';

	$out = $before . "<" . $wrapper_tag . " class='" . $wrapper_class . "' role='navigation'>\n$out\n</" . $wrapper_tag . ">" . $after;

	$out = apply_filters( 'wp_pagenavi', $out );

	if ( !$echo )
		return $out;

	echo '<div class="w-100 float-left">'. $out . '</div>';
}

 ?>
