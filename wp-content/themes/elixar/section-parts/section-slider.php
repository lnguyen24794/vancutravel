<?php
/**
 * The template for displaying hero image section in custom home page.
 *
 * @package Elixar
 */

$elixar_id         = get_theme_mod( 'elixar_hero_id', esc_html__('section-hero', 'elixar') );
$elixar_enabled    =  intval( get_theme_mod( 'elixar_hero_enable', 1 ) );
$elixar_hero_page = get_theme_mod('elixar_hero_page');
$elixar_slider_bg_img = "";
if ( !empty( $elixar_hero_page ) && $elixar_hero_page !=0 ) {
	$elixar_hero_content = get_post($elixar_hero_page);
	$elixar_slider_url = wp_get_attachment_url( get_post_thumbnail_id($elixar_hero_page) );
	$elixar_slider_bg_img = $elixar_slider_url!=false ? sprintf(", transparent url('%s') repeat fixed center center",esc_url($elixar_slider_url)) : '';
}
if ( $elixar_enabled == 1 && ! empty ( $elixar_slider_bg_img ) ) : ?>
<div id="<?php if ( $elixar_id != '' ){ echo esc_attr( $elixar_id ); } ?>" class="evideobg hero-section-wrapper">
	<div class="content-section section-wrapper">
		<div class="container">
			<div class="row">
				<?php 
				$elixar_hero_btn_text  = get_theme_mod( 'elixar_hero_btn1_text', esc_html__('Get Started', 'elixar') );
				$elixar_hero_btn_url  = get_theme_mod( 'elixar_hero_btn1_link', esc_url( home_url( '/' )).esc_html__('#', 'elixar') );
				$elixar_hero_btn_target =  intval( get_theme_mod( 'elixar_hero_btn_target', 1 ) );
				$elixar_hero_btn_target = ( $elixar_hero_btn_target == 1 ) ? '_blank' : '';
				$elixar_hero_btn_2_text  = get_theme_mod( 'elixar_hero_btn2_text', esc_html__('Documentation', 'elixar') );
				$elixar_hero_btn_2_url  = get_theme_mod( 'elixar_hero_btn2_link', esc_url( home_url( '/' )).esc_html__('#', 'elixar') );
				$elixar_hero_btn_2_target =  intval( get_theme_mod( 'elixar_hero_btn_2_target', 1 ) );
				$elixar_hero_btn_2_target = ( $elixar_hero_btn_2_target == 1 ) ? '_blank' : '';
                $elixar_btn_1_style = get_theme_mod( 'elixar_hero_btn1_style', 'btn-primary' );
                $elixar_btn_2_style = get_theme_mod( 'elixar_hero_btn2_style', 'btn-primary btn-ghost' ); ?>
				<div class="col-md-12 col-sm-12 text-center text-video-bg">
					<?php if ( $elixar_hero_content->post_title != '' ) echo '<h1 class="text-margin text-uppercase text-white e-hero-large-text">' . esc_html( $elixar_hero_content->post_title ) . '</h1>'; ?>
					<?php if( $elixar_hero_content->post_content != '' ) echo '<p class="lead text-white e-hero-small-text">' . do_shortcode( $elixar_hero_content->post_content ) . '</p>'; ?>
					<?php if( $elixar_hero_btn_text != '' ) echo '<a id="e-hero-btn1" href="'. esc_url( $elixar_hero_btn_url ) .'" target="' . esc_attr( $elixar_hero_btn_target ) . '" class="btn ' . esc_attr( $elixar_btn_1_style ) . ' btn-lg">' . wp_kses_post( $elixar_hero_btn_text ) . '</a>'; ?>
					<?php if( $elixar_hero_btn_2_text != '' ) echo '<a id="e-hero-btn2" href="' . esc_url($elixar_hero_btn_2_url) . '" target="' . esc_attr( $elixar_hero_btn_2_target ) . '" class="btn ' . esc_attr( $elixar_btn_2_style ) . ' btn-lg">' . wp_kses_post($elixar_hero_btn_2_text) . '</a>'; ?>
				</div>
			</div>
		</div>
	</div>
</div>
<?php endif; ?>