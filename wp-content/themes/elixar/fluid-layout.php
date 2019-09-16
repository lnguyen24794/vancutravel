<?php 
/**
 *Template Name: Fluid Layout
 *
 *fluid width layout has a flexible width.
 *
 *@package Elixar
 */
get_header(); 
get_template_part('breadcrumbs');?>
<section class="content-section section-wrapper sp-sm">
	<div class="fliud-container">
	    <div class="section-content">
	    <?php while ( have_posts() ) : the_post();
	    		the_content();
				wp_link_pages( array(
					'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'elixar' ),
					'after'  => '</div>',
				) );
	    		endwhile; ?>
	    </div>
	</div>
</section>
<?php get_footer(); ?>