<?php
/**
 * Template Name: Home
 *
 * The template used for displaying custom home page section like hero image, service, call to action, blog section etc. 
 *
 * @package Elixar
 */
get_header();
do_action( 'elixar_frontpage_before_section_parts' );
if ( ! has_action( 'elixar_frontpage_section_parts' ) ) {
	$elixar_sections = apply_filters( 'elixar_frontpage_sections_order', array('service', 'extra', 'feature', 'callout', 'blog') );
	foreach ( $elixar_sections as $elixar_section ) {
		elixar_load_section( $elixar_section );
	}
} else {
	do_action( 'elixar_frontpage_section_parts' );
}
do_action( 'elixar_frontpage_after_section_parts' );
get_footer(); ?>