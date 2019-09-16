<?php
/**
 * Custom functions that act independently of the theme templates.
 *
 * Eventually, some of the functionality here could be replaced by core features.
 *
 * @package Elixar
 */
/**
 * Adds custom classes to the array of body classes.
 *
 * @param array $classes Classes for the body element.
 * @return array
 */
function elixar_body_classes( $classes ) {
	$header_sticky = intval(get_theme_mod('elixar_sticky_menu_bar_enable', 0));
	$layout_option = sanitize_text_field( get_theme_mod('elixar_site_layout', '') );
	// Adds a class of group-blog to blogs with more than 1 published author.
	if ( is_multi_author() ) {
		$classes[] = 'group-blog';
	}
	if( $layout_option == 'boxed' ){
		$classes[]    = 'body-boxed';	
	}else{
		$classes[]    = '';
	}
	if( $header_sticky == 1 ){
		$classes[]    = 'enabled-sticky-primary-menu';	
	}else{
		$classes[]    = '';
	}
	return $classes;
}
add_filter( 'body_class', 'elixar_body_classes' );
/**
 * Get media from a variable for home page section
 *
 * @param array $media
 * @return false|string
 */
if ( ! function_exists( 'elixar_get_media_url' ) ) {
    function elixar_get_media_url($media = array())
    {
        $media = wp_parse_args($media, array('url' => '', 'id' => ''));
        $url = '';
        if ($media['id'] != '') {
            $url = wp_get_attachment_url($media['id']);
        }
        if ($url == '' && $media['url'] != '') {
            $url = $media['url'];
        }
        return $url;
    }
} ?>
<?php
if ( ! function_exists( 'elixar_after_import_setup' ) ) {
function elixar_after_import_setup() {
    // Assign menus to their locations.
    $main_menu = get_term_by( 'name', 'Primary Menu', 'nav_menu' );
    $top_menu = get_term_by( 'name', 'Top Menu', 'nav_menu' );
    set_theme_mod( 'nav_menu_locations', array(
			'primary' => $main_menu->term_id,
			'top' => $top_menu->term_id,
		)
    );
	// Assign front page and posts page (blog page).
    $front_page_id = get_page_by_title( esc_html__( 'Home', 'elixar' ) );
    $blog_page_id  = get_page_by_title( esc_html__( 'Blog', 'elixar' ) );
    update_option( 'show_on_front', 'page' );
    update_option( 'page_on_front', $front_page_id->ID );
    update_option( 'page_for_posts', $blog_page_id->ID );
}
add_action( 'pt-ocdi/after_import', 'elixar_after_import_setup' );
} ?>