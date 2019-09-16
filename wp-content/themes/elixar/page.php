<?php
/**
 * The template for displaying all pages.
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages
 * and that other 'pages' on your WordPress site will use a
 * different template.
 *
 * @package Elixar
 */

get_header();
$elixar_page_section = get_theme_mod( 'elixar_page_section', array() );
$elixar_page_section_position = get_theme_mod( 'elixar_page_section_position', 'top' );
$elixar_page_section_posi = $elixar_breadcrumb = '';
$elixar_page_title_bar_enable = intval( get_theme_mod( 'elixar_page_title_bar_enable', 1 ) );
$elixar_breadcrumb = ( get_post_meta( get_the_ID(), 'elixar_page_breadcrumb_enabled', true ) == true ) ? get_post_meta( get_the_ID(), 'elixar_page_breadcrumb_enabled', true ) : $elixar_page_title_bar_enable;
if ( $elixar_breadcrumb == true || $elixar_breadcrumb == 0 ) { get_template_part( 'breadcrumbs' ); }
$elixar_page_section_posi = ( get_post_meta( get_the_ID(), 'elixar_page_section_position', true ) == true ) ? get_post_meta( get_the_ID(), 'elixar_page_section_position', true ) : $elixar_page_section_position;
if ( function_exists( 'rwmb_meta' ) ) {
if( $elixar_page_section_posi == 'top' ) {
	if( ( rwmb_meta( 'elixar_page_section' ) == true ) || ( isset( $elixar_page_section ) && !empty( $elixar_page_section ) ) ) {
		//$elixar_page_section = '';
		$elixar_page_section = ( rwmb_meta( 'elixar_page_section' ) == true ) ? rwmb_meta( 'elixar_page_section' ) : $elixar_page_section;
		foreach( $elixar_page_section as $elixar_value ) {
			get_template_part( 'section-parts/section', $elixar_value );
		}
	}
}
}
the_post(); ?>
<!-- GRIDS -->
<div class="elixar-blog">
	<div class="container">
		<div id="main-content" class="row">
			<?php $elixar_page_layout = sanitize_text_field( get_theme_mod( 'elixar_page_layout', 'rightsidebar' ) );
			if( get_post_meta( get_the_ID(), 'elixar_page_layout', true) != "" ) {
				$elixar_page_layout = get_post_meta( get_the_ID(), 'elixar_page_layout', true );
			} else if ( $elixar_page_layout ) {
				$elixar_page_layout = $elixar_page_layout;
			}
			$elixar_imageSize = $elixar_page_layout == "fullwidth" ? 'elixar_blog_full_thumb' : 'elixar_blog_sidebar_thumb';
			if ( $elixar_page_layout == "leftsidebar" ) {
				get_sidebar();
				$elixar_float = 8;
			} else if ( $elixar_page_layout == "fullwidth" ) {
				$elixar_float = 12;
			} else if ( $elixar_page_layout == "rightsidebar" ) {
				$elixar_float = 8;
			} else {
				$elixar_page_layout = "rightsidebar";
				$elixar_float = 8;
			} ?>
			<!-- GRID POSTS -->
			<div class="col-sm-<?php echo absint($elixar_float); ?> e-content-block" id="sidebar_primary">
				<!-- POST ITEM 1 -->
				<div class="row">
					<div class="col-sm-12">
					<?php get_template_part( 'blog', 'content' ); ?>
					</div>
				</div>
			</div>
			<!-- GRID POSTS END -->
			<!-- GRID SIDEBAR -->
			<?php if ( $elixar_page_layout == "rightsidebar" ) {
			get_sidebar(); } ?>
			<!-- GRID SIDEBAR END -->
		</div>
	</div>
</div>
<?php
if ( function_exists( 'rwmb_meta' ) ) {
if( $elixar_page_section_posi == 'bottom' ) {
	if( ( rwmb_meta( 'elixar_page_section' ) == true ) || ( isset( $elixar_page_section ) && !empty( $elixar_page_section ) ) ) {
		//$elixar_page_section = '';
		$elixar_page_section = ( rwmb_meta( 'elixar_page_section' ) == true ) ? rwmb_meta( 'elixar_page_section' ) : $elixar_page_section;
		foreach( $elixar_page_section as $elixar_value ) {
			get_template_part( 'section-parts/section', $elixar_value );
		}
	}
}
} get_footer(); ?>