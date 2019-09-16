<?php
/**
 * The template for displaying service section in custom home page.
 *
 * @package Elixar
 */

$elixar_id       = get_theme_mod( 'elixar_services_id', esc_html__('section-services', 'elixar') );
$elixar_enabled  = intval( get_theme_mod( 'elixar_services_enable', 0 ) );
$elixar_title    = get_theme_mod( 'elixar_services_title', esc_html__('Our Services', 'elixar' ));
// Get data
$elixar_page_ids =  elixar_get_section_services_data();
if ( ! empty( $elixar_page_ids ) ) :
$elixar_service_layout = intval( get_theme_mod( 'elixar_service_layout', 6 ) );
$elixar_desc = get_theme_mod( 'elixar_services_desc' );
if ( $elixar_enabled == 1 ) : ?>
<!-- SECTION -->
<div id="<?php if ($elixar_id != '') { echo esc_attr( $elixar_id ); } ?>" class="content-section section-wrapper sp-sm section-dark section-services">
	<div class="container">
		<?php if ( $elixar_title || $elixar_desc ) { ?>
		<div class="row text-center">
			<div class="col-sm-8 col-sm-offset-2">
				<?php if ( $elixar_title != '' ) echo '<h3 id="service_title" class="section-title title-xl hr text-margin">' . esc_html( $elixar_title ) . '</h3>';
				if ( $elixar_desc ) { echo '<p id="service_desc" class="lead section-desc text-margin">' . wp_kses_post( $elixar_desc ) . '</p>'; } ?>
			</div>
		</div>
		<?php } ?>
		<div id="service-box-con" class="row">
		<?php if ( ! empty( $elixar_page_ids ) ) {
			$elixar_ser_icon_size = sanitize_text_field( get_theme_mod( 'elixar_service_icon_size', '4x' ) );
			foreach ($elixar_page_ids as $elixar_settings) {
			$elixar_page_id = $elixar_settings['content_page'];
			$elixar_page_id = apply_filters( 'wpml_object_id', $elixar_page_id, 'page', true );
			$elixar_post = get_post($elixar_page_id);
			setup_postdata( $elixar_post );
			$elixar_ser_media = '';
			if ( $elixar_settings['icon_type'] == 'image' && $elixar_settings['image'] ){
				$elixar_ser_url = elixar_get_media_url( $elixar_settings['image'] );
				if ( $elixar_ser_url ) {
					$elixar_ser_media = '<div class="service-image-circle text-margin"><img src="'.esc_url( $elixar_ser_url ).'" alt="" class="service-img-' . esc_attr( $elixar_ser_icon_size ) . '"></div>';
				}
			} else if ( $elixar_settings['icon'] ) {
				if ( has_post_thumbnail( $elixar_post ) ) {
					$elixar_img_con_s = '<div class="service-f-image">';
					$elixar_img_con_e = '</div>';
				} else {
					$elixar_img_con_s = '<div class="service-f-img">';
					$elixar_img_con_e = '</div>';
				}
				$elixar_ser_media = $elixar_img_con_s.'<i class="' . esc_attr( $elixar_settings['icon'] ) . ' fa-' . esc_attr( $elixar_ser_icon_size ) . ' service-icon-circle text-margin"></i>'. $elixar_img_con_e;
			}
			if ( $elixar_service_layout == 12 ) {
				$elixar_ser_classes = 'col-sm-12 col-lg-' . $elixar_service_layout;
			} else {
				$elixar_ser_classes = 'col-sm-6 col-lg-' . $elixar_service_layout;
			}?>
			<!-- SERVICES -->
			<div class="<?php echo esc_attr( $elixar_ser_classes ); ?>" data-sr="enter top over .7s and move 200px">
				<?php if ( !has_post_thumbnail( $elixar_post ) ) {
					$elixar_ser_text_center = 'text-center';
				} else {
					$elixar_ser_text_center = '';
				} ?>
				<div class="icon-boxes-con e-servicebox shadow-around <?php echo esc_attr( $elixar_ser_text_center ); ?>">
					<?php
					if ( ! empty( $elixar_settings['enable_link']) && !empty($elixar_settings['link']) )  {
						?>
						<a class="e-service-url" href="<?php echo esc_url($elixar_settings['link']); ?>" target="_blank"><span class="screen-reader-text"><?php echo esc_html( $elixar_post->post_title ); ?></span></a>
						<?php
					}
					?>
					<?php if ( has_post_thumbnail( $elixar_post ) ) { ?>
						<div class="service-thumbnail ">
							<?php
							echo get_the_post_thumbnail( $elixar_post, 'elixar-medium' );
							?>
						</div>
                    <?php } ?>
					<?php if ( $elixar_ser_media != '' ) {
						echo wp_kses_post( $elixar_ser_media );
					} ?>
					<div class="service-content">
						<h3 class="title-sm text-margin-sm service-title-margin"><?php echo esc_html( $elixar_post->post_title ); ?></h3>
						<p class="text-margin-sm"><?php echo wp_kses_post( $elixar_post->post_content ); ?></p>
					</div>
				</div>
			</div>
			<?php } wp_reset_postdata();
			} ?>
		</div>	
	</div>	
</div>
<?php endif;
endif; ?>