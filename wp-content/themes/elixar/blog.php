<?php
/**
 * Template Name: Blog
 *
 * @package Elixar
 */
get_header(); 
get_template_part('breadcrumbs'); ?>
<!-- GRIDS -->
<div class="elixar-blog">
	<div class="container">
		<div class="row">
			<?php $elixar_blog_layout = sanitize_text_field( get_theme_mod( 'elixar_blog_temp_layout', 'rightsidebar' ) );
			if( get_post_meta(get_the_ID(), 'elixar_blog_temp_layout', true) !="" ) {
				$elixar_blog_layout = get_post_meta($page_id, 'elixar_blog_temp_layout', true);
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
			<div class="col-sm-<?php echo intval( $elixar_float ) ; ?> e-content-block " id="sidebar_primary_blog">
				<!-- POST ITEM 1 -->
					<?php $elixar_paged = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;
					$elixar_args = array('post_type' => 'post', 'paged' => $elixar_paged);
					$elixar_wp_query = new WP_Query( $elixar_args );
					while ( $elixar_wp_query->have_posts() ):
					$elixar_wp_query->the_post();
						get_template_part('blog', 'content');
					endwhile;
					wp_link_pages(); ?>
				<!-- PAGINATAION -->
				<?php the_posts_pagination( array(
					'prev_text'          => esc_html__( 'Prev', 'elixar' ),
					'next_text'          => esc_html__( 'Next', 'elixar' ),
					'screen_reader_text' => esc_html__( 'Post navigation', 'elixar' ),
				) ); ?>
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