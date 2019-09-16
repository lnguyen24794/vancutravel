<?php
/**
 * The template used for displaying hierarchical series of hyperlinks displayed at the top of a web page, indicating the page's position in the overall structure of the website.
 *
 * @package Elixar
 */
?>
<!-- SECTION HEADING -->
<?php
$elixar_crumb_and_title = '';
$elixar_page_title_bar_enable = intval( get_theme_mod( 'elixar_page_title_bar_enable', 1 ) );
$elixar_page_title_type = sanitize_text_field( get_theme_mod( 'elixar_page_title_type', 'allow_both' ) );
if( get_post_meta(get_the_ID(), 'elixar_page_breadcrumb_enabled', true) == true ) {
	$elixar_crumb_and_title = get_post_meta(get_the_ID(), 'elixar_page_crumb_and_title', true);
} else if( $elixar_page_title_bar_enable == 1 ) {
	$elixar_crumb_and_title = $elixar_page_title_type;
}
if ( $elixar_crumb_and_title == 'allow_both' || $elixar_crumb_and_title == 'allow_title' ) { ?>
<div class="e-breadcrumb-page-title-overlay">
	<div class="e-breadcrumb-page-title">
		<div class="container">
			<div class="row">
				<div class="col-sm-6">
					<h1 class="e-page-title text-center-xs"><?php the_title(); ?></h1>
					<?php
					if ( is_page(  ) ) {
						$elixar_page_id = get_the_ID();
					} else {
						$elixar_page_id = get_option( 'page_for_posts' );
					}
					if ( get_post_meta( $page_id, 'elixar_show_excerpt', true ) ) {
						$elixar_post = get_post($elixar_page_id);
						if ($elixar_post->post_excerpt) {
							$elixar_excerpt = get_the_excerpt($elixar_page_id);
							if ($elixar_excerpt) {
								echo '<div class="e-page-tagline">' . wp_kses_post( $elixar_excerpt ). '</div>';
							}
						}
					} ?>
				</div>
				<?php if ( $elixar_crumb_and_title == 'allow_both' ) { ?>
					<div class="col-sm-6">
						<?php elixar_breadcrumbs(); ?>
					</div>
				<?php } ?>
			</div>
		</div>
	</div>
</div>
<?php } ?>