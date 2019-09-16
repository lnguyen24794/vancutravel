<?php
/**
 * The template for displaying Archive pages.
 *
 * Learn more: http://codex.wordpress.org/Template_Hierarchy
 *
 * @package Elixar
 */

get_header(); ?>
<!-- Archive Breadcrumbs -->
<div class="e-breadcrumb-page-title">
	<div class="container">
		<div class="row">
			<div class="col-sm-6">
				<?php the_archive_title( '<h1 class="e-page-title text-center-xs">', '</h1>' );
				the_archive_description( '<div class="archive-description">', '</div>' );?>
			</div>
			<div class="col-sm-6">
				<?php if (function_exists('elixar_breadcrumbs')) elixar_breadcrumbs(); ?>
			</div>
		</div>
	</div>
</div>
<!-- GRIDS -->
<div class="elixar-blog">
	<div class="container">
		<div id="main-content" class="row">
			<?php $elixar_blog_layout = sanitize_text_field( get_theme_mod( 'elixar_blog_temp_layout', 'rightsidebar' ) );
			if( get_post_meta(get_the_ID(), 'elixar_blog_temp_layout', true) != "" ) {
				$elixar_blog_layout = get_post_meta($page_id, 'elixar_blog_temp_layout', true);
			} else if ( $elixar_blog_layout ) {
				$elixar_blog_layout = $elixar_blog_layout;
			}
			$elixar_imageSize = $elixar_blog_layout == "fullwidth" ? 'elixar_blog_full_thumb' : 'elixar_blog_sidebar_thumb';
			if ( $elixar_blog_layout == "leftsidebar" ) {
				get_sidebar();
				$elixar_float = 8;
			} else if ( $elixar_blog_layout == "fullwidth" ) {
				$elixar_float = 12;
			} else if ( $elixar_blog_layout == "rightsidebar" ) {
				$elixar_float = 8;
			} else {
				$elixar_blog_layout = "rightsidebar";
				$elixar_float = 8;
			} ?>
			<!-- GRID POSTS -->
			<div class="col-sm-<?php echo absint($elixar_float); ?> e-content-block">
				<!-- POST ITEM 1 -->
				<?php if( have_posts() ){
						while ( have_posts() ) : the_post();
							get_template_part('blog', 'content');
						endwhile;
						wp_link_pages(); 
						the_posts_pagination( array(
							'prev_text'          => esc_html__( 'Prev', 'elixar' ),
							'next_text'          => esc_html__( 'Next', 'elixar' ),
							'screen_reader_text' => esc_html__( 'Post navigation', 'elixar' ),
						) );
				 	} ?>
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