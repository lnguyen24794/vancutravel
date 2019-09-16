<?php
/**
 * Template part for displaying single posts.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package Elixar
 */
$elixar_related_post_title = get_theme_mod( 'elixar_related_post_title', esc_html__( 'You might also like:-', 'elixar' ) );
$elixar_single_post_thumb = intval( get_theme_mod( 'elixar_single_post_thumb', 1 ) );
$elixar_single_post_meta = intval( get_theme_mod( 'elixar_single_post_meta', 1 ) );
$elixar_single_post_title = intval( get_theme_mod( 'elixar_single_post_title', 1 ) ); ?>
<!-- POST ITEM 1 -->
<div class="row">
	<div class="col-sm-12">
		<div class="elixar-blog-details shadow-around">
			<div id="post-<?php the_ID(); ?>" <?php post_class('e-blog-grid-block-details'); ?>>
				<?php if( $elixar_single_post_thumb == 1 ) { if( has_post_thumbnail() ) {
				$elixar_post_image_id = get_post_thumbnail_id();
				$elixar_post_image = wp_get_attachment_image_src( $elixar_post_image_id , 'full');
				global $elixar_imageSize;
				global $elixar_img_class; ?>
				<a href="<?php echo esc_url( $elixar_post_image[0] );?>" class="text-margin display-block">
					<?php the_post_thumbnail($elixar_imageSize, $elixar_img_class);?>
				</a>
				<?php } } ?>
				<div class="e-blog-grid-block-text">
					<?php if( $elixar_single_post_title == 1 ) { ?><h3 class="title-lg text-uppercase text-margin"><a><?php the_title(); ?></a></h3><?php }if( $elixar_single_post_meta == 1 ) { ?>
					<ul class="list-unstructured list-inline text-margin e-post-meta-part">
						<?php elixar_posted_on(); ?>
						<li><i class="fas fa-comment"></i> <?php esc_url( comments_popup_link( esc_html__( 'No Comments', 'elixar' ), esc_html__( '1 Comment', 'elixar' ), esc_html__( '% Comments', 'elixar' ) ) ); ?></li>
						<?php elixar_edit_link(); ?>
					</ul>
					<?php } the_content();
					wp_link_pages( array(
						'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'elixar' ),
						'after'  => '</div>',
					) ); wp_reset_postdata(); ?>

					<?php
					the_post_navigation( array(
			            'prev_text'                  =>  '&laquo; %title' ,
			            'next_text'                  =>  '%title &raquo;'
		             ) ); ?>
				</div>
			</div>
		</div>
		<?php elixar_tags_links(); ?>
		<!--- Related Products --->
		<?php $elixar_post_tags = wp_get_post_tags(get_the_ID());
		$elixar_post_num = sizeOf( $elixar_post_tags );
		$elixar_post_tagarr = array();
		for ($i = 0; $i < $elixar_post_num; $i++) {
			$elixar_post_tagarr[$i] = $elixar_post_tags[$i]->term_id;
		}
		if ( isset($elixar_post_tags) && !empty ($elixar_post_tags) ) { ?>
		<?php
		$elixar_postNot_in[] = get_the_ID();
		$elixar_args = array(
			'tag__in' => $elixar_post_tagarr,
			'post__not_in' => $elixar_postNot_in,
			'ignore_sticky_posts' => 1
		);
		$elixar_query = new WP_Query($elixar_args);
		if ( $elixar_query->have_posts() ) {
		$i=1; ?>
		<div class="elixar-related-posts-section shadow-around">
			<div class="clearfix"></div>
			<div class="elixar-related-posts">
				<?php if( $elixar_related_post_title != '' ) { echo '<h4 class="entry-post-title"> ' . esc_html( $elixar_related_post_title ) . '</h4>'; } ?>
				<ul><?php while( $elixar_query->have_posts() ):$elixar_query->the_post(); ?>
					<li class="col-md-4">
						<a href="<?php the_permalink(); ?>" class="related-thumb">
						<?php $elixar_img_class = array('class' => 'img-responsive');
						if( has_post_thumbnail() ) {
							the_post_thumbnail( 'elixar_related_post_thumb', $elixar_img_class );
						} ?>
						</a>
						<h2 class="entry-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
					</li>
					<?php if( $i%3==0 ){ echo '<div class="clearfix"></div><hr class="elixar-related-post-underline"/>'; }
					$i++; endwhile; wp_reset_postdata(); ?>
				</ul>
			</div>
		</div>
		<?php }
		} ?>
	</div>
</div>
<!-- GRID POSTS END -->