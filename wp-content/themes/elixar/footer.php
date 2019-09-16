<?php
/**
 * The template for displaying the footer.
 *
 * Contains the closing of the #content div and all content after.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package Elixar
 */
?>
<!-- SECTION FOOTER -->
<?php
/* Footer Ribbon */
$elixar_footer_ribbon_enable = intval( get_theme_mod( 'elixar_footer_ribbon_enable', 0 ) );
$elixar_footer_ribbon_text = get_theme_mod( 'elixar_footer_ribbon_text', esc_html__('Get in Touch', 'elixar' ));
/* Footer Widget */
$elixar_footer_columns = absint( get_theme_mod( 'elixar_footer_layout' ) );
/* Social */
$elixar_social_footer_enable = intval( get_theme_mod('elixar_social_footer_enable', 0 ) );
$elixar_social_footer_title = get_theme_mod( 'elixar_social_footer_title', esc_html__('Connect With Us On Social', 'elixar' ));
$elixar_fb_url = get_theme_mod( 'elixar_fb_url' );
$elixar_twitter_url = get_theme_mod( 'elixar_twitter_url' );
$elixar_gplus_url = get_theme_mod( 'elixar_gplus_url' );
$elixar_instagram_url = get_theme_mod( 'elixar_instagram_url' );
$elixar_flickr_url = get_theme_mod( 'elixar_flickr_url' );
$elixar_skype_url = get_theme_mod( 'elixar_skype_url');
/* Copyright */
$elixar_copyright_enable = intval( get_theme_mod( 'elixar_copyright_enable', 1 ) );
/* translators: %s: Copyright Text */
$elixar_copyright_text = get_theme_mod( 'elixar_copyright_text', sprintf( esc_html__('Theme powered by %1$s WordPress %3$s & developed by %2$s WebHunt Infotech %3$s', "elixar"), '<a href="https://wordpress.org/" target="_blank">', '<a href="https://webhuntinfotech.com/" target="_blank">','</a>') );
/* Back To Top */
$elixar_scroll_back_top_enable = intval( get_theme_mod( 'elixar_back_top_enable', 1 ) );

$elixar_hide_footer = false;
if ( is_page() ){
    $elixar_hide_footer = get_post_meta( get_the_ID(), 'elixar_hide_footer', true );
}
if ( ! $elixar_hide_footer ) {
if ( $elixar_footer_columns != 0 || $elixar_social_footer_enable == 1 || $elixar_footer_ribbon_enable == 1 ) { ?>
<div class="content-section footer-main" id="section_footer">
	<div class="container">
		<?php if ( $elixar_footer_columns != 0 || $elixar_footer_ribbon_enable == 1 ) { ?>
		<div class="row">
			<?php if ( $elixar_footer_ribbon_enable == 1 ) { if( $elixar_footer_ribbon_text != '' ){ ?>
				<div class="footer-ribbon footer-ribbon-left">
					<span><?php echo esc_html( $elixar_footer_ribbon_text ); ?></span>
				</div>
			<?php } }
			do_action('elixar_footer_widgets_section'); ?>
		</div>
		<!-- FOOTER SOCIAL -->
		<?php } if( $elixar_social_footer_enable == 1 ) { ?>
		<div class="elixar-footer-social <?php if( $elixar_footer_columns != 0 ) { echo 'e_footer_social'; } ?>">
			<?php if(!empty ( $elixar_social_footer_title )) { ?>
			<h1 class="footer-social-title wow animated fadeInUp animated"><?php echo esc_html( $elixar_social_footer_title ); ?></h1>			
			<?php } ?>
			<ul class="footer-social-icons">
				<?php if( !empty ( $elixar_fb_url ) ){ ?>
				<li><a href="<?php echo esc_url( $elixar_fb_url ); ?>" data-toggle="tooltip"><i class="fab fa-facebook-f a-facebook"></i></a></li>
				<?php } if( !empty ( $elixar_twitter_url ) ){ ?>
				<li><a href="<?php echo esc_url( $elixar_twitter_url ); ?>" data-toggle="tooltip"><i class="fab fa-twitter a-twitter"></i></a></li>
				<?php } if( !empty ( $elixar_gplus_url ) ){ ?>
				<li><a href="<?php echo esc_url( $elixar_gplus_url ); ?>" data-toggle="tooltip"><i class="fab fa-google-plus-g a-google-plus"></i></a></li>
				<?php } if( !empty ( $elixar_instagram_url ) ){ ?>
				<li><a href="<?php echo esc_url( $elixar_instagram_url ); ?>" data-toggle="tooltip"><i class="fab fa-instagram a-instagram"></i></a></li>
				<?php } if( !empty ( $elixar_flickr_url ) ){ ?>
				<li><a href="<?php echo esc_url( $elixar_flickr_url ); ?>" data-toggle="tooltip"><i class="fab fa-flickr a-flickr"></i></a></li>
				<?php } if( !empty ( $elixar_skype_url ) ){ ?>
				<li><a href="<?php echo esc_attr( $elixar_skype_url ); ?>" data-toggle="tooltip"><i class="fab fa-skype a-skype"></i></a></li>
				<?php } ?>
			</ul>
		</div>
		<?php } ?>
	</div>
</div>
<?php }
} ?>
<!--SECTION FOOTER BOTTOM -->
<div class="content-section footer_copyright">
	<div class="container">
		<div class="row">
			<div class="col-sm-12 text-center <?php if ( $elixar_copyright_enable == 1 ) { echo 'col-footer-copyright '; } ?> copyright_text_footer">
				<?php if ( $elixar_scroll_back_top_enable == 1 ) { ?>
					<a id="scroll-top-fixed" href="#"><i class="fas fa-angle-up fa-2x"></i></a>
				<?php } if ( $elixar_copyright_enable == 1 ) { ?>
				<p class="copyright-text"><?php echo  wp_kses_post($elixar_copyright_text) ; ?></p>
				<?php } ?>
			</div>
		</div>
	</div>
</div>
<!--End SECTION FOOTER BOTTOM -->
</div>
<?php wp_footer(); ?>
</body>
</html>