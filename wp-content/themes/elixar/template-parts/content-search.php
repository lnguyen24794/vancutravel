<?php
/**
 * Template part for displaying results in search pages.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package Elixar
 */
?>
<?php global $elixar_imageSize;
$elixar_img_class = array( 'class'=>'img-responsive img-slide' ); ?>
<div class="elixar-latest-item shadow-around">
	<div id="post-<?php the_ID(); ?>" <?php post_class('e-blog-grid-block'); ?>>
		<?php if ( has_post_thumbnail() ) { ?>
		<a href="<?php the_permalink(); ?>" class="text-margin display-block">
		  <?php the_post_thumbnail( $elixar_imageSize, $elixar_img_class );?>
		</a>
		<?php } ?>
		<div class="e-blog-grid-block-text">
			<?php the_title( sprintf( '<h3 class="title-lg text-uppercase text-margin"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h3>' );
				//if ( 'post' === get_post_type() ) : ?>
				<ul class="list-unstyled list-inline text-margin e-post-meta-part">
				<?php elixar_posted_on(); ?>
					<li><i class="fas fa-comment"></i> <?php esc_url( comments_popup_link(esc_html__( 'No Comments', 'elixar' ), esc_html__( '1 Comment', 'elixar' ), esc_html__( '% Comments', 'elixar' ) ) ); ?></li>
					<?php elixar_edit_link(); ?>
				</ul>
			<?php the_excerpt(); ?>
		</div>
	</div>
</div>
<?php $elixar_count1 = strlen(get_the_content());
$elixar_count2 = strlen(get_the_excerpt());
if( $elixar_count1>$elixar_count2 ) { ?>
<div class="elixar-latest-item shadow-around">
	<div class="elixar-read-more">
		<a href="<?php the_permalink(); ?>"><?php esc_html_e('Read More', 'elixar'); ?></a>
		<p><?php esc_url( comments_popup_link( esc_html__( 'No Comments', 'elixar' ), esc_html__( '1 Comment', 'elixar' ), esc_html__( '% Comments', 'elixar' ) ) ); ?></p>
	</div>
</div>	
<?php } ?>