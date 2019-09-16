<?php 
/**
 * The main template file
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * E.g., it puts together the home page when no home.php file exists.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Elixar
 */

get_header(); 
get_template_part( 'breadcrumbs' ); ?>
<!-- GRIDS -->
<div class="elixar-blog">
	<div class="container">
		<div id="main-content" class="row">
			<?php $elixar_blog_layout = sanitize_text_field( get_theme_mod( 'elixar_blog_temp_layout', 'rightsidebar' ) );
			if( get_post_meta( get_the_ID(), 'elixar_blog_temp_layout', true ) !="" ) {
				$elixar_blog_layout = get_post_meta( $page_id, 'elixar_blog_temp_layout', true );
			} else if ( $elixar_blog_layout ) {
				$elixar_blog_layout = $elixar_blog_layout;
			}
			$elixar_imageSize = $elixar_blog_layout == "fullwidth" ? 'elixar_blog_full_thumb' : 'elixar_blog_sidebar_thumb';
			if ( $elixar_blog_layout == "leftsidebar" ) {
				get_sidebar();
				$elixar_float = 8;
			} elseif ( $elixar_blog_layout == "fullwidth" ) {
				$elixar_float = 12;
			} elseif ( $elixar_blog_layout == "rightsidebar" ) {
				$elixar_float = 8;
			} else {
				$elixar_blog_layout = "rightsidebar";
				$elixar_float = 8;
			} ?>
			<!-- GRID POSTS -->
			<div class="col-sm-<?php echo intval( $elixar_float ); ?>">
				<!-- POST ITEM 1 -->
				<?php if ( have_posts() ) :
				while ( have_posts() ) : the_post();
					get_template_part( 'blog', 'content' );
				endwhile;
				wp_link_pages();
				the_posts_pagination( array(
					'prev_text'          => esc_html__( 'Prev', 'elixar' ),
					'next_text'          => esc_html__( 'Next', 'elixar' ),
					'screen_reader_text' => __( 'Post navigation', 'elixar' ),
				) );
				wp_reset_postdata();
				else : 
					get_template_part( 'template-parts/content', 'none' );
				endif; ?>
			</div>
			<!-- GRID POSTS END -->
			<!-- GRID SIDEBAR -->
			<?php if ( $elixar_blog_layout == "rightsidebar" ) {
			get_sidebar(); } ?>
			<!-- GRID SIDEBAR END -->
		</div>
	</div>
</div>
<?php get_footer(); ?>