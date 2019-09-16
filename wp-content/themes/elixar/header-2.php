<?php
/**
 * The header for our theme.
 *
 * The template used for displaying header content in header.php. it's include in header.php file.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package Elixar
 */
 
/* Topbar */
$elixar_topbar_enable = intval( get_theme_mod( 'elixar_topbar_enable', 0 ) );
$elixar_social_top_enable = intval( get_theme_mod( 'elixar_social_top_enable', 1 ) );
$elixar_topbar_menu_enable = intval( get_theme_mod( 'elixar_topbar_menu_enable', 1 ) );
$elixar_fb_url = get_theme_mod( 'elixar_fb_url' );
$elixar_twitter_url = get_theme_mod( 'elixar_twitter_url' );
$elixar_gplus_url = get_theme_mod( 'elixar_gplus_url' );
$elixar_instagram_url = get_theme_mod( 'elixar_instagram_url' );
$elixar_flickr_url = get_theme_mod( 'elixar_flickr_url' );
$elixar_skype_url = get_theme_mod( 'elixar_skype_url' );
/* Header */
$elixar_logo_layout = sanitize_text_field( get_theme_mod( 'elixar_logo_layout', 'left' ) );
$elixar_header_contact_enable = intval( get_theme_mod( 'elixar_header_contact_enable', 0 ) );
$elixar_show_cartcount = intval( get_theme_mod( 'elixar_show_cartcount', 0 ) );
$elixar_show_search_in_header = intval( get_theme_mod( 'elixar_show_search_in_header', 1 ) );
$elixar_header_contact_phone  = get_theme_mod( 'elixar_header_contact_phone', esc_html__( 'Phone', 'elixar' ) );
$elixar_header_contact_phone_icon = get_theme_mod( 'elixar_header_contact_phone_icon', 'fas fa-phone' );
$elixar_header_contact_phone_info  = get_theme_mod( 'elixar_header_contact_phone_info', '+099 99999' );
$elixar_header_contact_email  = get_theme_mod( 'elixar_header_contact_email', esc_html__( 'Email', 'elixar' ) );
$elixar_header_contact_email_icon = get_theme_mod( 'elixar_header_contact_email_icon', 'fas fa-envelope' );
$elixar_header_contact_email_info  = get_theme_mod( 'elixar_header_contact_email_info', sanitize_email('example@example.com') );
$elixar_header_contact_address  = get_theme_mod( 'elixar_header_contact_address', esc_html__( 'Address', 'elixar' ) );
$elixar_header_contact_address_icon = get_theme_mod( 'elixar_header_contact_address_icon', 'fas fa-map' );
$elixar_header_contact_address_info  = get_theme_mod( 'elixar_header_contact_address_info', esc_html__('123 Main Street India', 'elixar') );
/* Menu Bar Position */
$elixar_menu_bar_position = sanitize_text_field( get_theme_mod( 'elixar_menu_bar_position', 'top' ) ); ?>
<body <?php body_class( 'body-nav-fixed-menu-top ' ); ?>>
<div class="main_wrapper">
<a class="skip-link screen-reader-text" href="#content"><?php esc_html_e( 'Skip to content', 'elixar' ); ?></a>
<!-- NAVBAR -->
<div id="site-header">
		<?php if( $elixar_topbar_enable == 1 ) {
			if( $elixar_social_top_enable == 1 || $elixar_topbar_menu_enable == 1 ) { ?>
			<div class="container sitetopbar top_details top-detail-inverse">
				<div class="row">
					<div class="col-md-12 col-sm-12 col-xs-12">
						<div class="topbar-inner">
							<div class="row">
							<?php if( $elixar_social_top_enable == 1 ) { ?>
							<div class="col-sm-6 col-xs-12 topsocial">
								<div class="list-inline social-top-detail pull-left">
									<?php if( !empty ( $elixar_fb_url ) ) { ?>
									<a href="<?php echo esc_url( $elixar_fb_url ); ?>"><i class="elixar-facebook"></i></a>
									<?php } if( !empty ( $elixar_twitter_url ) ) { ?>
									<a href="<?php echo esc_url( $elixar_twitter_url ); ?>"><i class=" elixar-twitter"></i></a>
									<?php } if( !empty ( $elixar_gplus_url ) ) { ?>
									<a href="<?php echo esc_url( $elixar_gplus_url ); ?>"><i class="elixar-google-plus"></i></a>
									<?php } if( !empty ( $elixar_instagram_url ) ) { ?>
									<a href="<?php echo esc_url( $elixar_instagram_url ); ?>"><i class="elixar-instagram"></i></a>
									<?php } if( !empty ( $elixar_flickr_url ) ) { ?>
									<a href="<?php echo esc_url( $elixar_flickr_url ); ?>"><i class="elixar-flickr"></i></a>
									<?php } if( !empty ( $elixar_skype_url ) ) { ?>
									<a href="<?php echo esc_attr( $elixar_skype_url ); ?>"><i class="elixar-skype"></i></a>
									<?php } ?>
								</div>
							</div>
							<?php } if( $elixar_topbar_menu_enable == 1 ) { ?>
							<div class="col-sm-6 hidden-xs contact_info">
								<nav id="header-nav" class="menu-top-menu-container pull-right">
									<?php wp_nav_menu(
										array(
										'theme_location' => 'top',
										'menu_class'     => 'menu',
										'depth'          => 1,
										'fallback_cb'    => false,
										)
									); ?>
								</nav>
							</div>
							<?php } ?>
						</div>	
					</div>
				</div>
			</div>
		</div>
		<?php } } ?>
		<div class="mobile-nav-wrap">
			<a id="mobile-trigger" href="#mob-menu"><i class="fas fa-list-ul" aria-hidden="true"></i><span><?php esc_html_e( 'Main Menu', 'elixar'); ?><span></span></span></a>
			<div id="mob-menu">
				<?php
				wp_nav_menu( array(
					'theme_location' => 'primary',
					'container'      => '',
					'fallback_cb' => 'elixar_primary_navigation_fallback',
				) );
				?>
			</div><!-- #mob-menu -->
			<?php if ( has_nav_menu( 'top' ) ) : ?>
			<a id="mobile-trigger-quick" href="#mob-menu-quick"><span><?php esc_html_e( 'Top Menu', 'elixar' ); ?></span><i class="fas fa-list-ul" aria-hidden="true"></i></a>
			<div id="mob-menu-quick">
				<?php
				wp_nav_menu( array(
					'theme_location' => 'top',
					'container'      => '',
					'depth'          => 1,
					'fallback_cb'    => false,
				) );
				?>
			</div><!-- #mob-menu-quick -->
		<?php endif; ?>
		</div>
		<nav id="nav" class="navbar navbar-default">	
			<div id="suprhead" class="e-site-header" role="banner">
				<div class="container">
					<div class="navbar-header <?php if ( $elixar_logo_layout=='right' ) { echo 'logo_right'; } ?>">
						<div class="site-branding">
							<?php if ( function_exists( 'the_custom_logo' ) && has_custom_logo() ) {the_custom_logo(); } ?>
							<div <?php if ( !has_custom_logo() ) { echo "class='site-branding-text'"; } ?>>
								<?php if ( is_front_page() ) : ?>
									<h1 class="site-title"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a></h1>
								<?php else : ?>
									<p class="site-title"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a></p>
								<?php endif; ?>
								<?php
								$elixar_description = get_bloginfo( 'description', 'display' );
								if ( $elixar_description || is_customize_preview() ) : ?>
									<p class="site-description"><?php echo esc_html( $elixar_description ); ?></p>
								<?php endif; ?>
							</div><!-- .site-branding-text -->
						</div><!-- .site-branding -->
					</div>
					<?php if ( $elixar_header_contact_enable == 1 ){ ?>
					<div class="e-contact-right-head <?php if ( $elixar_logo_layout == 'right' ) { echo 'pull-left'; } ?>">
						<div id="quick-contact">
							<ul class="quick-contact-list">
								<?php if( !empty( $elixar_header_contact_phone) || !empty($elixar_header_contact_phone_info ) ) { ?>
									<li class="quick-call">
										<?php if( !empty( $elixar_header_contact_phone_icon ) ) { ?><i class="<?php echo esc_attr( $elixar_header_contact_phone_icon ); ?>"></i><?php } ?>
										<strong id="quick-call"><?php echo esc_html( $elixar_header_contact_phone ); ?></strong>
										<a href="tel:<?php echo esc_html( $elixar_header_contact_phone_info );?>"><?php echo esc_html( $elixar_header_contact_phone_info );?></a>
									</li>
								<?php }
								if( !empty( $elixar_header_contact_email ) || !empty($elixar_header_contact_email_info ) ) { ?>
									<li class="quick-email">
										<?php if( $elixar_header_contact_email_icon ){ ?><i class="<?php echo esc_attr( $elixar_header_contact_email_icon ); ?>"></i><?php } ?>
										<strong id="quick-email"><?php echo esc_html( $elixar_header_contact_email ); ?></strong>
										<a href="mailto:<?php echo esc_attr( $elixar_header_contact_email_info );?>"><?php echo esc_attr( $elixar_header_contact_email_info );?></a>
									</li>
								<?php } if( !empty( $elixar_header_contact_address) || !empty($elixar_header_contact_address_info ) ) { ?>
									<li class="quick-address">
										<?php if( $elixar_header_contact_address_icon ) { ?><i class="<?php echo esc_attr( $elixar_header_contact_address_icon ); ?>"></i><?php } ?>
										<strong id="quick-address"><?php echo esc_html( $elixar_header_contact_address ); ?></strong>
										<?php echo wp_kses_post( $elixar_header_contact_address_info ); ?>
									</li>
								<?php } ?>
							</ul><!-- .quick-contact-list -->
						</div><!--  .quick-contact -->
						<?php if ( $elixar_show_cartcount == 1 && elixar_woocommerce_status() ) : ?>
							<div class="cart-section">
								<div class="shopping-cart-views">
									<a href="<?php echo esc_url( wc_get_cart_url() ); ?>" class="cart-contents">
										<i class="fas fa-shopping-bag" aria-hidden="true"></i>
										<span class="cart-value"><?php echo wp_kses_data( WC()->cart->get_cart_contents_count() );?></span>
									</a>
								</div><!-- .shopping-cart-views -->
							</div><!-- .cart-section -->
						<?php endif; ?>	
					</div><!-- .e-contact-right-head -->
					<span class="e_top_expande not_expanded">
						<i class="no_exp fas fa-angle-double-down"></i>
						<i class="exp fas fa-angle-double-up"></i>
					</span>
					<?php } ?>
				</div>
			</div>
		</nav>
		<?php if( $elixar_menu_bar_position == 'below_hero' ) {
			if ( is_page_template( 'home-page.php' ) ) {
				do_action( 'elixar_header_end' );
			}
		} ?>
		<div id="e_main_nav" class="clear-fix">
			<div class="container">
				<nav id="site-navigation" class="main-navigation" role="navigation">
					<div class="wrap-menu-content">
					<?php wp_nav_menu( array(
					'theme_location' => 'primary',
					'container' => false,
					'menu_id' => 'primary-menu',
					'fallback_cb' => 'elixar_primary_navigation_fallback',
					) ); ?>
				</div><!--/.nav-collapse -->
			</nav>
			<?php if ( $elixar_show_search_in_header == 1 ) : ?>
				<div class="header-search-box">
					<a href="#" class="search-icon"><i class="fas fa-search"></i></a>
					<div class="search-box-wrap">
						<?php get_search_form(); ?>
					</div>
				</div><!-- .header-search-box -->
			<?php endif; ?>
			</div>
		</div>
</div>
<!-- NAVBAR END -->
<?php
if( $elixar_menu_bar_position != 'below_hero' ) {
	if ( is_page_template( 'home-page.php' ) ) {
		do_action( 'elixar_header_end' );
	}
} ?>