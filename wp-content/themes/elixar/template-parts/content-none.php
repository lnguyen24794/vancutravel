<?php
/**
 * Template part for displaying a message that posts cannot be found.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package Elixar
 */
?>
<div class="elixar-latest-item shadow-around">
	<div <?php post_class('e-blog-grid-block'); ?>>
		<div class="e-blog-grid-block-text">
			<h3 class="title-lg text-uppercase text-margin"><?php esc_html_e( 'Nothing Found', 'elixar' ); ?></h3>
			<?php if ( is_home() && current_user_can( 'publish_posts' ) ) : ?>
			<?php /* translators: %s: Get Started */ ?>
			<p><?php printf( wp_kses( __( 'Ready to publish your first post? <a href="%1$s">Get started here</a>.', 'elixar' ), array( 'a' => array( 'href' => array() ) ) ), esc_url( admin_url( 'post-new.php' ) ) ); ?></p>
		<?php elseif ( is_search() ) : ?>
			<p><?php esc_html_e( 'Sorry, but nothing matched your search terms. Please try again with some different keywords.', 'elixar' ); ?></p>
			<?php get_search_form(); ?>
		<?php else : ?>
			<p><?php esc_html_e( 'It seems we can&rsquo;t find what you&rsquo;re looking for. Perhaps searching can help.', 'elixar' ); ?></p>
			<?php get_search_form(); ?>
		<?php endif; ?>
		</div>
	</div>
</div>