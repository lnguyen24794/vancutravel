<?php
/**
 * The template for displaying all single posts.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package Elixar
 */
get_header();
get_template_part( 'breadcrumbs' ); ?>
<!-- GRIDS -->
<div class="elixar-blog-single">
	<div class="container" id="post-page-padding">
		<div id="main-content" class="row">
			<!-- GRID POSTS -->
			<?php $elixar_post_layout = sanitize_text_field( get_theme_mod( 'elixar_post_layout', 'rightsidebar' ) );
			if( get_post_meta( get_the_ID(), 'elixar_post_layout', true) != "" ) {
				$elixar_post_layout = get_post_meta( get_the_ID(), 'elixar_post_layout', true );
			} else if ( $elixar_post_layout ) {
				$elixar_post_layout = $elixar_post_layout;
			}
			$elixar_imageSize = $elixar_post_layout == "fullwidth" ? 'elixar_blog_full_thumb' : 'elixar_blog_sidebar_thumb';
			$elixar_img_class = array( 'class'=>'img-responsive img-slide' );
			if ( $elixar_post_layout == "leftsidebar" ) {
				get_sidebar();
				$elixar_float = 8;
			} else if ( $elixar_post_layout == "fullwidth" ) {
				$elixar_float = 12;
			} else if ( $elixar_post_layout == "rightsidebar" ) {
				$elixar_float = 8;
			} else {
				$elixar_post_layout = "rightsidebar";
				$elixar_float = 8;
			}
			if ( have_posts() ) : ?>
			<div class="col-sm-<?php echo absint( $elixar_float ); ?> e-content-block" id="sidebar_primary_single">
				<?php while ( have_posts() ) : the_post(); ?>
					<?php get_template_part( 'template-parts/content', 'single' );
						// If comments are open or we have at least one comment, load up the comment template.
						if ( comments_open() || get_comments_number() ) :
							comments_template();
						endif;
					endwhile; // End of the loop. ?>
			</div>
			<?php else :
				get_template_part( 'template-parts/content', 'none' );
			endif; ?>
			<?php if ( $elixar_post_layout == "rightsidebar" ) {
			get_sidebar(); } ?>
			<!-- SIDEBAR END -->
		</div>
	</div>
</div>
<?php get_footer(); ?>