<?php
/**
 * The template for displaying extra content section in custom home page.
 *
 * @package Elixar
 */
$elixar_id       = get_theme_mod( 'elixar_section_extra_id', esc_html__('section-extra', 'elixar') );
$elixar_enabled  = intval( get_theme_mod( 'elixar_section_extra_enable', 1 ) );
$elixar_title    = get_theme_mod( 'elixar_section_extra_title', esc_html__('WHY CHOOSE US', 'elixar' ));
$elixar_desc     = wp_kses_post( get_theme_mod( 'elixar_section_extra_desc') );
// Get data
$elixar_page_ids =  elixar_get_section_extra_data();
$elixar_content_source = get_theme_mod( 'elixar_section_extra_content_source' );
if ( ! empty( $elixar_page_ids ) ) : ?>
<?php if ( $elixar_enabled == 1 ) : ?>
	<div id="<?php if ($elixar_id != '') { echo esc_attr( $elixar_id ); }; ?>" class="content-section sp-sm section-wrapper section-extra">
		<div class="container">
			<?php if ( $elixar_title || $elixar_desc ) { ?>
			<div class="row text-center">
				<div class="col-sm-8 col-sm-offset-2">
					<?php if ( $elixar_title != '' ) echo '<h3 id="about_title" class="section-title title-xl hr text-margin">' . esc_html( $elixar_title ) . '</h3>';
					if ( $elixar_desc ) { echo '<p id="about_desc" class="lead section-desc text-margin">' .wp_kses_post( $elixar_desc ) . '</p>'; } ?>
				</div>
			</div>
			<?php } ?>
			<div id="about-box-con" class="row">
				<?php
				if ( ! empty ( $elixar_page_ids ) ) {
					$elixar_extra_col = 3;
					$elixar_num_col = 4;
					$n = count( $elixar_page_ids );
					if ($n < 4) {
						switch ($n) {
							case 3:
								$elixar_extra_col = 4;
								$elixar_num_col = 3;
								break;
							case 2:
								$elixar_extra_col = 6;
								$elixar_num_col = 2;
								break;
							default:
								$elixar_extra_col = 12;
								$elixar_num_col = 1;
						}
					}
					// Layout columns
					$elixar_extra_layout = absint( get_theme_mod( 'elixar_section_extra_layout', 3 ) );
					if ( $n > $elixar_extra_layout ) {
						$elixar_num_col = $elixar_extra_layout;
						$elixar_extra_col = round( 12/ $elixar_extra_layout );
					}
					foreach ( $elixar_page_ids as $elixar_page_id => $elixar_settings ) {
						$elixar_page_id = $elixar_settings['content_page'];
						$elixar_page_id = apply_filters( 'wpml_object_id', $elixar_page_id, 'page', true );
						$elixar_page = get_post( $elixar_page_id );
						setup_postdata( $elixar_page );
						$elixar_extra_class = 'col-lg-' . $elixar_extra_col;
						if ($n == 1) {
							$elixar_extra_class .= ' col-sm-12';
						} else {
							$elixar_extra_class .= ' col-sm-6';
						}
					?>
					<!-- About Us -->
					<div class="<?php echo esc_attr( $elixar_extra_class ); ?> about-box-con" data-sr="enter top over .7s and move 200px">
						<?php if ( has_post_thumbnail( $elixar_page ) ) { ?>
								<div class="about-image"><?php
									if ( $elixar_settings['enable_link'] ) {
										echo '<a href="' . esc_url( get_permalink( $elixar_page )  ). '">';
									}
									echo get_the_post_thumbnail( $elixar_page, 'elixar_section_extra_thumb' );
									if ( $elixar_settings['enable_link'] ) {
										echo '</a>';
									}
									?></div>
							<?php } ?>
							<?php if ( !$elixar_settings['hide_title'] ) { ?>
								<h3 class="title-sm text-margin-sm about-title-margin"><?php
									if ( $elixar_settings['enable_link'] ) {
										echo '<a href="' . esc_url( get_permalink($elixar_page) ) . '">';
									}
									echo esc_html( get_the_title( $elixar_page ) );
									if ( $elixar_settings['enable_link'] ) {
										echo '</a>';
									}
									?></h3>
							<?php } ?>
							<?php
							if ( $elixar_content_source == 'excerpt' ) {
								the_excerpt( $elixar_page );
							} else {
								$elixar_extra_content = apply_filters( 'the_content', $elixar_page->post_content );
								$elixar_extra_content = str_replace( ']]>', ']]&gt;', $elixar_extra_content );
								echo wp_kses_post( $elixar_extra_content );
							} ?>
					</div>
					<?php } // end foreach
					wp_reset_postdata();
				}// ! empty pages ids
				?>
		</div>
	</div>
</div>
<?php endif;
endif; ?>