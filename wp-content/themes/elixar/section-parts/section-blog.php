<?php
/**
 * The template for displaying blog posts section in custom home page.
 *
 * @package Elixar
 */

$elixar_id        = get_theme_mod( 'elixar_blog_id', esc_html__('section-blog', 'elixar') );
$elixar_enabled   = intval( get_theme_mod( 'elixar_blog_enable', 0 ) );
$elixar_title     = get_theme_mod( 'elixar_blog_title', esc_html__('Latest Blog', 'elixar' ));
$elixar_blog_post_count    = absint( get_theme_mod( 'elixar_blog_number', '3' ) );
$elixar_more_text = get_theme_mod( 'elixar_blog_more_text', esc_html__('Read More', 'elixar' ));
$elixar_load_post_button = intval( get_theme_mod( 'elixar_load_post_button_enable', 0 ) );
$elixar_blog_load_more_text = get_theme_mod( 'elixar_blog_load_more_text', esc_html__('Load More Posts', 'elixar' ));
$elixar_blog_more_loading_text = get_theme_mod( 'elixar_blog_more_loading_text', esc_html__('Loading...', 'elixar' ));
$elixar_blog_no_more_post_text = get_theme_mod( 'elixar_blog_no_more_post_text', esc_html__('No more older post found', 'elixar' ));
$elixar_desc = get_theme_mod( 'elixar_blog_desc' );
if ( $elixar_enabled == 1 ) : ?>
<div id="<?php if ( $elixar_id != '' ) { echo esc_attr( $elixar_id ); } ?>" class="content-section sp-sm section-wrapper section-dark section-blog">
	<div class="container">
		<?php if ( $elixar_title || $elixar_desc ) { ?>
		<div class="row text-center">
			<div class="col-sm-8 col-sm-offset-2">
				<?php if ( $elixar_title != '' ) echo '<h3 id="blog_title" class="section-title title-xl hr text-margin">' . esc_html( $elixar_title ) . '</h3>';
				if ( $elixar_desc ) { echo '<p id="blog_desc" class="lead section-desc text-margin">' . 
				wp_kses_post( $elixar_desc ) . '</p>'; } ?>
			</div>
		</div>
		<?php } ?>
		<div class="row">
			<div class="col-sm-12 e-content-block">
			<!-- POST ITEM 1 -->
				<div class="row masonry animatedParent ajax_posts" id="content" role="main">
					<?php $elixar_imageSize = 'elixar_blog_home_thumb';
					$elixar_cat = absint( get_theme_mod( 'elixar_blog_cat' ) );
					$elixar_orderby = sanitize_text_field( get_theme_mod('elixar_blog_orderby') );
					$elixar_order = sanitize_text_field( get_theme_mod('elixar_blog_order') );
					$elixar_args = array('post_type' => 'post', 'posts_per_page' => $elixar_blog_post_count, 'suppress_filters' => 0,);
					if ( $elixar_cat > 0 ) {
						$elixar_args['category__in'] = array( $elixar_cat );
					}
					if ( $elixar_orderby && $elixar_orderby != 'default' ) {
						$elixar_args['orderby'] = $elixar_orderby;
					}
					if ( $elixar_order ) {
						$elixar_args['order'] = $elixar_order;
					}
					$elixar_query = new WP_Query( $elixar_args );
					if ( $elixar_query->have_posts() ) {
					while ( $elixar_query->have_posts() ) : $elixar_query->the_post();
					$elixar_img_class = array('class'=>'img-responsive img-slide full-width-img'); ?>
					<div class="col-sm-4 animated fadeInDownShort grid-item e-pdt-30">
						<div class="home-blog-latest-item shadow-around" data-sr="enter left scale up 20% delay once">
							<div id="post-<?php the_ID(); ?>" <?php post_class('e-blog-grid-block-details'); ?>>
								<?php if ( has_post_thumbnail() ) { ?>
									<a href="<?php the_permalink(); ?>" class="text-margin display-block">
										<?php the_post_thumbnail($elixar_imageSize, $elixar_img_class); ?>
									</a>
									<?php } ?>
									<div class="e-blog-grid-block-text">
										<?php the_title( sprintf( '<h3 class="title-lg text-uppercase text-margin"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h3>' ); ?>
										<ul class="list-unstructured list-inline text-margin e-post-meta-part">
											<?php elixar_posted_on(); ?>
											<li><i class="fas fa-comment"></i> <?php esc_url( comments_popup_link( esc_html__('No Comments', 'elixar'), esc_html__('1 Comment', 'elixar'), esc_html__('% Comments', 'elixar') ) ); ?></li>
										</ul>
										<?php the_excerpt(); ?>
									</div>
								</div>
								<?php $elixar_count1 = strlen(get_the_content());
								$elixar_count2 = strlen(get_the_excerpt());
								if( $elixar_count1>$elixar_count2 ) { ?>
										<div class="elixar-read-more">
											<a id="elixar-read-more-btn" href="<?php the_permalink(); ?>"><?php if ( $elixar_more_text != '' ) echo esc_html( $elixar_more_text ); ?></a>
											<p><?php esc_url(comments_popup_link(esc_html__('No Comments', 'elixar'), esc_html__('1 Comment', 'elixar'), esc_html__('% Comments', 'elixar'))); ?></p>
										</div>
									<?php } ?>
							</div>
					</div>
					<?php endwhile;
					} ?>
				</div>
				<?php if ( $elixar_load_post_button == 1 ) { ?>
				<div id="load-button" class="load-button">
					<a class="text-margin btn btn-lg btn-primary append-button" data-loading-text="<i class='fas fa-spinner fa-spin'></i> <?php echo wp_kses_post( $elixar_blog_more_loading_text ); ?>"><span class="append-button"><i class="fas fa-spinner"></i><?php echo wp_kses_post( $elixar_blog_load_more_text ); ?></span></a>
				</div>
				<?php } ?>
			</div>
		</div>
	</div>
</div>
<?php endif; ?>