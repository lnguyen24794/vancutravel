<?php
/**
 * add additional features to page and post using metabox. 
 *
 * @package elixar
 */
add_filter( 'rwmb_meta_boxes', 'elixar_register_meta_boxes' );
function elixar_register_meta_boxes( $elixar_meta_boxes )
{
	// IMPORTANT: This is depracated and not supported any more
	// Global variable which stores all meta boxes.
	global $elixar_meta_boxes;
	$elixar_meta_boxes   = array();
	$prefix = 'elixar_';
	// Post Layout
	$elixar_meta_boxes[] = array(
		'id'         => 'post_alignment',
		'title'      => esc_html__( 'Post Layout', 'elixar'),
		'post_types' => 'post',
		'context'    => 'normal',
		'priority'   => 'high',
		'fields' => array(
			array(
				'name'  => esc_html__( 'Post Alignment', 'elixar'),
				'id'    => $prefix . 'post_layout',
				'type'  => 'image_select',
				'options' => array( 'leftsidebar' => get_template_directory_uri().'/images/layout/elixar-left-sidebar.jpg', 'fullwidth' => get_template_directory_uri().'/images/layout/elixar-full-width.jpg', 'rightsidebar' => get_template_directory_uri().'/images/layout/elixar-right-sidebar.jpg' ),
				'std'   => '',
			),
		)
	);
	// Page Layout
	$elixar_meta_boxes[] = array(
		'id'         => 'page_alignment',
		'title'      => esc_html__( 'Page Layout', 'elixar'),
		'post_types' => 'page',
		'context'    => 'normal',
		'priority'   => 'high',
		'fields' => array(
			array(
				'name'  => esc_html__( 'Page Alignment', 'elixar'),
				'id'    => $prefix . 'page_layout',
				'type'  => 'image_select',
				'options' => array( 'leftsidebar' => get_template_directory_uri().'/images/layout/elixar-left-sidebar.jpg', 'fullwidth' => get_template_directory_uri().'/images/layout/elixar-full-width.jpg', 'rightsidebar' => get_template_directory_uri().'/images/layout/elixar-right-sidebar.jpg' ),
				'std'   => '',
			),
			array(
				'name'        => esc_html__( 'Select Page Section', 'elixar' ),
				'id'          => $prefix . 'page_section',
				'type'        => 'select_advanced',
				// Array of 'value' => 'Label' pairs for select box
				'options'     => array(
					'slider' => esc_html__( 'Hero', 'elixar' ),
					'service' => esc_html__( 'Service', 'elixar' ),
					'Blog' => esc_html__( 'Blog', 'elixar' ),
					'callout' => esc_html__( 'Call Out', 'elixar' ),
					'extra' => esc_html__( 'Extra', 'elixar' ),
				),
				// Select multiple values, optional. Default is false.
				'multiple'    => true,
				'placeholder' => esc_html__( 'Select an Item', 'elixar' ),
				'select_all_none' => false,
			),
			array(
				'name'        => esc_html__( 'Home Page Section Position', 'elixar' ),
				'id'          => $prefix . 'page_section_position',
				'type'        => 'select',
				// Array of 'value' => 'Label' pairs for select box
				'options'     => array(
					'' => esc_html__( 'Select Position', 'elixar' ),
					'top' => esc_html__( 'Top', 'elixar' ),
					'bottom' => esc_html__( 'Bottom', 'elixar' ),
				),
				// Select multiple values, optional. Default is false.
				'multiple'    => false,
				'std'         => '',
				'visible' => array('elixar_page_section', '!=', ''),
			),
			array(
				'name' => esc_html__( 'Custom Breadcrumbs', 'elixar'),
				'id'   => $prefix . 'page_breadcrumb_enabled',
				'type' => 'checkbox',
				'std'  => 0,
				'before'=>'<div class="elixar_meta_info wc-connect"><h2><span>Show Breadcrumbs</span></h2></div>'
			),
			array(
				'name'        => esc_html__( 'Page Title Type', 'elixar' ),
				'id'          => $prefix . 'page_crumb_and_title',
				'type'        => 'select',
				// Array of 'value' => 'Label' pairs for select box
				'options'     => array(
					'allow_both' => esc_html__( 'Title Bar With Breadcrumbs', 'elixar' ),
					'allow_title' => esc_html__( 'Title Bar Only', 'elixar' ),
					'not_of_them' => esc_html__('Hide All', 'elixar'),
				),
				// Select multiple values, optional. Default is false.
				'multiple'    => false,
				'std'         => 'allow_both',
				'visible' => array('elixar_page_breadcrumb_enabled', '=', 1)
			),
			array(
				'name' => esc_html__( 'Title Bar Font Color', 'elixar' ),
				'id'   => $prefix . 'crumb_title_color',
				'type' => 'color',
				'visible' => array('elixar_page_breadcrumb_enabled', '=', 1)
			),
			array(
				'name'        => esc_html__( 'Page Title Size', 'elixar' ),
				'id'          => $prefix . 'crumb_title_height',
				'type'        => 'select',
				// Array of 'value' => 'Label' pairs for select box
				'options'     => array(
					'default' => esc_html__( 'Small', 'elixar' ),
					'has_bg_image' => esc_html__( 'Medium', 'elixar' ),
					'large_header has_bg_image' => esc_html__( 'Large', 'elixar' ),
				),
				// Select multiple values, optional. Default is false.
				'multiple'    => false,
				'std'         => 'has_bg_image',
				'visible' => array('elixar_page_breadcrumb_enabled', '=', 1)
			),
			array(
				'name' => esc_html__( 'Background Color ', 'elixar' ),
				'id'   => $prefix . 'page_breadcrumb_bg_color',
				'type' => 'color',
				// Add alpha channel?
				'alpha_channel' => true,
				'visible' => array('elixar_page_breadcrumb_enabled', '=', 1)
			),
			array(
				'name' => esc_html__( 'Background image', 'elixar'),
				'id'   => $prefix . 'page_breadcrumb_bg_img',
				'type' => 'file_input',
				'visible' => array('elixar_page_breadcrumb_enabled', '=', 1)
			),
			array(
				'name' => esc_html__( 'Padding Top', 'elixar'),
				'id'   => $prefix . 'page_padding_top',
				'type' => 'number',
				'min'  => 0,
				'step' => 5,
				'placeholder'  => 0,
				'visible' => array('elixar_page_breadcrumb_enabled', '=', 1)
			),
			array(
				'name' => esc_html__( 'Padding Bottom', 'elixar'),
				'id'   => $prefix . 'page_padding_bottom',
				'type' => 'number',
				'min'  => 0,
				'step' => 5,
				'placeholder'  => 0,
				'visible' => array('elixar_page_breadcrumb_enabled', '=', 1)
			),
		)
	);
	// Page Settings
	$elixar_meta_boxes[] = array(
		'id'         => 'page_settings',
		'title'      => esc_html__( 'Page Settings', 'elixar'),
		'post_types' => 'page',
		'context'    => 'side',
		'priority'   => 'low',
		'fields' => array(
			array(
				'name' => esc_html__( 'Hide Header', 'elixar'),
				'id'   => $prefix . 'hide_header',
				'type' => 'checkbox',
				'std'  => 0,
			),
			array(
				'name' => esc_html__( 'Hide Footer', 'elixar'),
				'id'   => $prefix . 'hide_footer',
				'type' => 'checkbox',
				'std'  => 0,
			),
			/* array(
				'name' => esc_html__( 'Display featured image as header cover.', 'elixar'),
				'id'   => $prefix . 'page_cover',
				'type' => 'checkbox',
				'std'  => 0,
			), */
			array(
				'name' => esc_html__( 'Display page excerpt as header cover description.', 'elixar'),
				'id'   => $prefix . 'show_excerpt',
				'type' => 'checkbox',
				'std'  => 0,
			),
		)
	);
	return $elixar_meta_boxes;
}