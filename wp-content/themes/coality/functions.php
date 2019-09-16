<?php
/**
 * coality functions and definitions.
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package Coality
 */
require_once( trailingslashit( get_stylesheet_directory() ) . 'customizer-button/class-customize.php' );
function coality_enqueue_theme_skin() {
	$minified_assests = intval( get_theme_mod( 'elixar_minified_assests', 0 ) );
	$min = ''; 
	if ( $minified_assests == 1 ) {
		$min = '.min';
	}
	
	$parent_style = 'elixar-style'; // This is 'twentyfifteen-style' for the Twenty Fifteen theme.
    wp_enqueue_style( $parent_style, get_template_directory_uri() . '/style.css', array( 'bootstrap' ) );
	
    wp_enqueue_style( 'coality',
        get_stylesheet_directory_uri() . '/style.css',
        array( $parent_style ),
        wp_get_theme()->get('Version')
    );
	wp_enqueue_style( 'coality-theme-skin-blue', get_stylesheet_directory_uri(). '/css/skins/coality-blue'.$min.'.css',array('coality') );
		
	$coality_is_rtl_enable = intval( get_theme_mod( 'elixar_is_rtl_enable', 0 ) );
	if( $coality_is_rtl_enable == 1 ) {
		wp_enqueue_style( 'coality-style-main-rtl', get_stylesheet_directory_uri().'/css/coality-rtl'.$min.'.css' );
	}
	wp_enqueue_script( 'coality-theme', get_stylesheet_directory_uri() . '/js/coality-theme.js', array('jquery') );
}
add_action( 'wp_enqueue_scripts', 'coality_enqueue_theme_skin');
function coality_deenqueue_parent_theme_skin() {
	/* Deenqueu parent styles */
	wp_dequeue_style('elixar-theme-skin-red');
	wp_dequeue_style('elixar-style-main-rtl');
	wp_dequeue_script('elixar-theme');
}
add_action( 'wp_enqueue_scripts', 'coality_deenqueue_parent_theme_skin', 11);

add_action( 'customize_register', 'coality_customizer', 20 );
function coality_customizer( $wp_customize ) {
	$wp_customize->get_setting( 'elixar_menubar_bg_color' )->default = '#ffffff';
	$wp_customize->get_setting( 'elixar_menu_item_color' )->default = '#2196f3';
	$wp_customize->remove_control('elixar_service_icon_size');
}

function coality_theme_setup()
{
	load_child_theme_textdomain('coality', get_template_directory() . '/languages');
}
add_action('after_setup_theme', 'coality_theme_setup', 20);