<?php
/**
 * The template for displaying call to action section in custom home page.
 *
 * @package Elixar
 */
$elixar_id = get_theme_mod( 'elixar_cta_id', esc_html__('section-cta', 'elixar') );
$elixar_enabled = intval( get_theme_mod( 'elixar_cta_enable', 0 ) );
$elixar_cta_page = get_theme_mod('elixar_cta_page');
if ( $elixar_enabled == 1 && !empty($elixar_cta_page) && $elixar_cta_page!=0) :
	$elixar_cta_content = get_post($elixar_cta_page);
	$elixar_cta_url = wp_get_attachment_url( get_post_thumbnail_id($elixar_cta_page) );
	$elixar_cta_bg_img = $elixar_cta_url!=false ? sprintf(", transparent url('%s') repeat fixed center center",esc_url($elixar_cta_url)) : '';
endif;
if ( $elixar_enabled == 1 && ! empty ( $elixar_cta_bg_img ) ) : ?>
<div id="<?php if ( $elixar_id != '' ){ echo esc_attr( $elixar_id ); } ?>" class="section-cta">
	<div class="content-section sp-sm section-wrapper">
		<div class="container">
			<div class="row">
				<?php 
				$elixar_cta_btn_text = get_theme_mod( 'elixar_cta_btn1_text', esc_html__('Purchase Now', 'elixar') );
				$elixar_cta_btn_icon = get_theme_mod('elixar_cta_btn1_icon', 'fas fa-shopping-cart');
				$elixar_cta_btn_url  = get_theme_mod( 'elixar_cta_btn1_link', esc_url( home_url( '/' )).esc_html__('#', 'elixar') );
				$elixar_cta_btn_target =  intval( get_theme_mod( 'elixar_cta_btn1_target', 1 ) );
				$elixar_cta_btn_target = ( $elixar_cta_btn_target == 1 ) ? '_blank' : '';
                $elixar_btn_1_style = get_theme_mod( 'elixar_cta_btn1_style', 'btn-primary' ); ?>
				<div class="col-sm-12 text-center animatedParent">
					<?php if ( $elixar_cta_content->post_title != '' ) echo '<h3 id="cta_title" class="text-margin title-xl  text-uppercase animated growIn delay-100 text-white" data-id="1">' . esc_html( $elixar_cta_content->post_title ) . '</h3>'; ?>
					<?php if( $elixar_cta_content->post_content != '' ) echo '<p id="cta_desc" class="lead text-margin animated bounceInRight delay-500 text-white" data-id="2">' . do_shortcode( $elixar_cta_content->post_content ) . '</p>'; ?>
					<?php if( $elixar_cta_btn_text != '' ) echo '<a id="cta_btn" href="'. esc_url( $elixar_cta_btn_url ) .'" target="' . esc_attr( $elixar_cta_btn_target ) . '" class="btn ' . esc_attr( $elixar_btn_1_style ) . ' btn-lg text-margin animated bounceInLeft delay-1000" data-id="3"><i class="'. esc_attr( $elixar_cta_btn_icon ) .'"></i> <span id="cta_btn_txt">' . wp_kses_post( $elixar_cta_btn_text ) . '</span></a>'; ?>
				</div>
			</div>
		</div>
	</div>	
</div>
<?php endif; ?>