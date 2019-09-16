<?php
/**
 * The template for displaying service section in custom home page.
 *
 * @package Coality
 */

$coality_id       = get_theme_mod( 'elixar_services_id', esc_html__('section-services', 'coality') );
$coality_enabled  = intval( get_theme_mod( 'elixar_services_enable', 0 ) );
$coality_title    = get_theme_mod( 'elixar_services_title', esc_html__('Our Services', 'coality' ));
// Get data
$coality_page_ids =  elixar_get_section_services_data();
if ( ! empty( $coality_page_ids ) ) :
$coality_service_layout = intval( get_theme_mod( 'elixar_service_layout', 6 ) );
$coality_desc = get_theme_mod( 'elixar_services_desc' );
if ( $coality_enabled == 1 ) : ?>
<!-- SECTION -->
<div id="<?php if ($coality_id != '') { echo esc_attr( $coality_id ); } ?>" class="content-section section-wrapper sp-sm section-dark section-services">
	<div class="container">
		<?php if ( $coality_title || $coality_desc ) { ?>
		<div class="row text-center">
			<div class="col-sm-8 col-sm-offset-2">
				<?php if ( $coality_title != '' ) echo '<h3 id="service_title" class="section-title title-xl hr text-margin">' . esc_html( $coality_title ) . '</h3>';
				if ( $coality_desc ) { echo '<p id="service_desc" class="lead section-desc text-margin">' . apply_filters( 'elixar_the_content', wp_kses_post( $coality_desc ) ) . '</p>'; } ?>
			</div>
		</div>
		<?php } ?>
		<div id="service-box-con" class="row">
		<?php if ( ! empty( $coality_page_ids ) ) {
			$coality_ser_icon_size = sanitize_text_field( get_theme_mod( 'elixar_service_icon_size', '4x' ) );
			foreach ($coality_page_ids as $coality_settings) {
			$coality_page_id = $coality_settings['content_page'];
			$coality_page_id = apply_filters( 'wpml_object_id', $coality_page_id, 'page', true );
			$coality_post = get_post($coality_page_id);
			setup_postdata( $coality_post );
			$coality_ser_media = '';
			if ( $coality_settings['icon_type'] == 'image' && $coality_settings['image'] ){
				$coality_ser_url = elixar_get_media_url( $coality_settings['image'] );
				if ( $coality_ser_url ) {
					$coality_ser_media = '<div class="service-icon-img"><span><img src="'.esc_url( $coality_ser_url ).'" alt="' . esc_attr( $coality_post->post_title ) .'" ></span></div>';
				}
			} else if ( $coality_settings['icon'] ) {
				if ( has_post_thumbnail( $coality_post ) ) {
					$coality_img_con_s = '<div class="service-f-image">';
					$coality_img_con_e = '</div>';
				} else {
					$coality_img_con_s = '<div class="service-icon">';
					$coality_img_con_e = '</div>';
				}
				$coality_ser_media = $coality_img_con_s.'<span><i class="' . esc_attr( $coality_settings['icon'] ) . ' "></i></span>'. $coality_img_con_e;
			}
			if ( $coality_service_layout == 12 ) {
				$coality_ser_classes = 'col-sm-12 col-lg-' . $coality_service_layout;
			} else {
				$coality_ser_classes = 'col-sm-6 col-lg-' . $coality_service_layout;
			}?>
			<!-- SERVICES -->
			<div class="<?php echo esc_attr( $coality_ser_classes ); ?>">
				<?php if ( !has_post_thumbnail( $coality_post ) ) {
					$coality_ser_text_center = 'text-center';
				} else {
					$coality_ser_text_center = '';
				} ?>
				<div class="c-serviceBox <?php echo esc_attr( $coality_ser_text_center ); ?>">
					<?php
					/* if ( ! empty( $coality_settings['enable_link']) && !empty($coality_settings['link']) )  {
						?>
						<a class="e-service-url" href="<?php echo esc_url($coality_settings['link']); ?>"><span class="screen-reader-text"><?php echo esc_html( $coality_post->post_title ); ?></span></a>
						<?php
					} */
					?>
					<?php if ( has_post_thumbnail( $coality_post ) ) { ?>
						<div class="service-thumbnail ">
							<?php
							echo get_the_post_thumbnail( $coality_post, 'elixar-medium' );
							?>
						</div>
                    <?php } ?>
					<?php if ( $coality_ser_media != '' ) {
						echo wp_kses_post( $coality_ser_media );
					} ?>
					
					<h3 class="title"><?php echo esc_html( $coality_post->post_title ); ?></h3>
					<p class="description"><?php echo wp_kses_post( $coality_post->post_content ); ?></p>
					<?php
					if ( ! empty( $coality_settings['enable_link']) && !empty($coality_settings['link']) ) {?>
						<a href="<?php echo esc_url($coality_settings['link']); ?>" target="_blank" class="read-more"><?php esc_html_e('Read More', 'coality'); ?></a>
					<?php } ?>
					
				</div>
			</div>
			<?php } wp_reset_postdata();
			} ?>
		</div>	
	</div>	
</div>
<?php endif;
endif; ?>