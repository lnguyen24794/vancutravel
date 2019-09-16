<?php
/**
 * Custom template tags for this theme.
 *
 * Eventually, some of the functionality here could be replaced by core features.
 *
 * @package Elixar
 */
if ( ! function_exists( 'elixar_posted_on' ) ) :
/**
 * Prints HTML with meta information for the current post-date/time and author.
 */
function elixar_posted_on() {
	if (get_the_title() == "") {
		$time_string = '<li class="entry-date" datetime="%1$s"><a href="' . esc_url( get_the_permalink() ) . '"><i class="fas fa-calendar"></i>%2$s</a></li>';
	} else {
		$time_string = '<li class="entry-date" datetime="%1$s"><a><i class="fas fa-calendar"></i>%2$s</a></li>';
	}
	if ( get_the_time( 'U' ) !== get_the_modified_time( 'U' ) ) {
		$time_string = '<li class="entry-date" datetime="%1$s"><a href="' . esc_url( get_the_permalink() ) . '"><i class="fas fa-calendar"></i>%2$s</a></li>';
	}
	$time_string = sprintf( $time_string,
		esc_attr( get_the_date( 'c' ) ),
		esc_html( get_the_date() )
	);
	$posted_on = sprintf(
		'<a href="' . esc_url( get_the_permalink() ) . '" rel="bookmark">' . $time_string . '</a>'
	);
	$byline = sprintf(
		'<li><a href="' . esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ) . '"><i class="fas fa-user"></i>' . esc_html( get_the_author() ) . '</a></li>'
	);
	$cat = sprintf(		
		'<li><a class="link-colored" href="#"><i class="fas fa-folder-open"></i>'. get_the_category_list( esc_html__(', ', 'elixar') ) .'</a></li>'
	);
	echo $posted_on . ' ' . $byline .' '. $cat . ' '; // WPCS: XSS OK.
}
endif;
if ( ! function_exists( 'elixar_edit_link' ) ) :
/**
 * Returns an accessibility-friendly link to edit a post or page.
 *
 * This also gives us a little context about what exactly we're editing
 * (post or page?) so that users understand a bit more where they are in terms
 * of the template hierarchy and their content. Helpful when/if the single-page
 * layout with multiple posts/pages shown gets confusing.
 */
function elixar_edit_link() {
	$link = edit_post_link(
		sprintf(
			/* translators: %s: Name of current post */
			__( ' Edit<span class="screen-reader-text"> "%s"</span>', 'elixar' ),
			get_the_title()
		),
		'<li><i class="fas fa-edit"></i>',
		'</li>'
	);
	return $link;
}
endif;
if ( ! function_exists( 'elixar_entry_footer' ) ) :
/**
 * Prints HTML with meta information for the categories, tags and comments.
 */
function elixar_entry_footer() {
	// Hide category and tag text for pages.
	if ( 'post' === get_post_type() ) {
		/* translators: used between list items, there is a space after the comma */
		$categories_list = get_the_category_list( esc_html__( ', ', 'elixar' ) );
		if ( $categories_list && elixar_categorized_blog() ) {
			/* translators: %s: Categories List */
			printf( '<span class="cat-links">' . esc_html__( 'Posted in %1$s', 'elixar' ) . '</span>', $categories_list ); // WPCS: XSS OK.
		}
		/* translators: used between list items, there is a space after the comma */
		$tags_list = get_the_tag_list( '', esc_html__( ', ', 'elixar' ) );
		if ( $tags_list ) {
			/* translators: %s: Tag List */
			printf( '<span class="tags-links">' . esc_html__( 'Tagged %1$s', 'elixar' ) . '</span>', $tags_list ); // WPCS: XSS OK.
		}
	}
	if ( ! is_single() && ! post_password_required() && ( comments_open() || get_comments_number() ) ) {
		echo '<span class="comments-link">';
		comments_popup_link( esc_html__( 'Leave a comment', 'elixar' ), esc_html__( '1 Comment', 'elixar' ), esc_html__( '% Comments', 'elixar' ) );
		echo '</span>';
	}
}
endif;
/**
 * Returns true if a blog has more than 1 category.
 *
 * @return bool
 */
function elixar_categorized_blog() {
	if ( false === ( $all_the_cool_cats = get_transient( 'elixar_categories' ) ) ) {
		// Create an array of all the categories that are attached to posts.
		$all_the_cool_cats = get_categories( array(
			'fields'     => 'ids',
			'hide_empty' => 1,
			// We only need to know if there is more than one category.
			'number'     => 2,
		) );
		// Count the number of categories that are attached to the posts.
		$all_the_cool_cats = count( $all_the_cool_cats );
		set_transient( 'elixar_categories', $all_the_cool_cats );
	}
	if ( $all_the_cool_cats > 1 ) {
		// This blog has more than 1 category so elixar_categorized_blog should return true.
		return true;
	} else {
		// This blog has only 1 category so elixar_categorized_blog should return false.
		return false;
	}
}
/**
 * Flush out the transients used in elixar_categorized_blog.
 */
function elixar_category_transient_flusher() {
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}
	// Like, beat it. Dig?
	delete_transient( 'elixar_categories' );
}
add_action( 'edit_category', 'elixar_category_transient_flusher' );
add_action( 'save_post',     'elixar_category_transient_flusher' );
/* Tags and links */
function elixar_tags_links()
{ if(has_tag()) { ?>
<div class="elixar-posts-tag-section shadow-around">
	<div class="elixar-tagline">
		<div class="row">
			<div class="col-md-12"><?php
				if (the_tags()) { ?>
					<div class="elixar-tagcloud">
						<?php esc_html(the_tags(' ', ', ', '')); ?>
					</div><?php
				}?>
		  </div>
		</div>
	</div>
</div>
<?php }
} ?>
<?php
if ( ! function_exists( 'elixar_load_section' ) ) {
    /**
     * Load section
     * @since 2.0.0
     * @param $section_id
     */
    function elixar_load_section( $section_id )
    {
        /**
         * Hook before section
         */
        do_action('elixar_before_section_' . $section_id);
        do_action('elixar_before_section_part', $section_id);
        get_template_part('section-parts/section', $section_id );
        /**
         * Hook after section
         */
        do_action('elixar_after_section_part', $section_id);
        do_action('elixar_after_section_' . $section_id);
    }
}
if ( ! function_exists('elixar_load_hero') ) {
    function elixar_load_hero_section(){
        if ( is_page_template('home-page.php') ) {
            elixar_load_section( 'slider' );
        }
    }
} 
add_action( 'elixar_header_end', 'elixar_load_hero_section' );
if ( ! function_exists( 'elixar_get_section_services_data' ) ) {
    /**
     * Get services data
     * @return array
     */
    function elixar_get_section_services_data()
    {
        $services = get_theme_mod('elixar_services');
        if (is_string($services)) {
            $services = json_decode($services, true);
        }
        $elixar_page_ids = array();
        if (!empty($services) && is_array($services)) {
            foreach ($services as $k => $v) {
                if (isset ($v['content_page'])) {
                    $v['content_page'] = absint($v['content_page']);
                    if ($v['content_page'] > 0) {
                        $elixar_page_ids[] = wp_parse_args($v, array(
                            'icon_type' => 'icon',
                            'image' => '',
                            'icon' => 'gg',
                            'enable_link' => 0
                        ));
                    }
                }
            }
        }
        // if still empty data then get some page for demo
        return $elixar_page_ids;
    }
}
if ( ! function_exists( 'elixar_get_section_extra_data' ) ) {
    /**
     * Get About data
     *
     * @return array
     */
    function elixar_get_section_extra_data()
    {
        $boxes = get_theme_mod('elixar_section_extra_boxes');
        if (is_string($boxes)) {
            $boxes = json_decode($boxes, true);
        }
        $elixar_page_ids = array();
        if (!empty($boxes) && is_array($boxes)) {
            foreach ($boxes as $k => $v) {
                if (isset ($v['content_page'])) {
                    $v['content_page'] = absint($v['content_page']);
                    if ($v['content_page'] > 0) {
                        $elixar_page_ids[] = wp_parse_args($v, array('enable_link' => 0, 'hide_title' => 0));
                    }
                }
            }
        }
        $elixar_page_ids = array_filter( $elixar_page_ids );
        return $elixar_page_ids;
    }
}
/*
** Footer Widget Column
*/
if ( ! function_exists( 'elixar_footer_widgets' ) ) {
    function elixar_footer_widgets(){
        $elixar_footer_columns = absint( get_theme_mod( 'elixar_footer_layout' , 4 ) );
        $max_cols = 12;
        $layouts = 12;
        if ( $elixar_footer_columns > 1 ){
            $default = "12";
            switch ( $elixar_footer_columns ) {
                case 4:
                    $default = '3+3+3+3';
                    break;
                case 3:
                    $default = '4+4+4';
                    break;
                case 2:
                    $default = '6+6';
                    break;
            }
            $layouts = sanitize_text_field( get_theme_mod( 'elixar_footer_custom_'.$elixar_footer_columns.'_columns', $default ) );
        }
        $layouts = explode( '+', $layouts );
        foreach ( $layouts as $k => $v ) {
            $v = absint( trim( $v ) );
            $v =  $v >= $max_cols ? $max_cols : $v;
            $layouts[ $k ] = $v;
        }
        $have_widgets = false;
        for ( $count = 0; $count < $elixar_footer_columns; $count++ ) {
            $id = 'footer-widget-' . ( $count + 1 );
            if ( is_active_sidebar( $id ) ) {
                $have_widgets = true;
            }
        }
        if ( $elixar_footer_columns > 0 && $have_widgets ) { ?>
			<?php
			for ( $count = 0; $count < $elixar_footer_columns; $count++ ) {
				$col = isset( $layouts[ $count ] ) ? $layouts[ $count ] : '';
				$id = 'footer-widget-' . ( $count + 1 );
				if ( $col ) {
					?>
					<div class="col-sm-<?php echo esc_attr( $col ); ?> col-xs-12" id="footer_widget-<?php echo esc_attr( $count + 1 ) ?>">
						<?php dynamic_sidebar( $id ); ?>
					</div>
					<?php
				}
			} ?>
        <?php }  ?>
        <?php
    }
}
add_action( 'elixar_footer_widgets_section', 'elixar_footer_widgets', 100 );
if ( ! function_exists( 'elixar_custom_inline_style' ) ) {
    /**
     * Add custom css to header
     *
     * @change 1.1.5
     */
	function elixar_custom_inline_style( ) {
            ob_start();
			// Topbar
			$elixar_topbar_bg_color = sanitize_hex_color_no_hash( get_theme_mod( 'elixar_topbar_bg_color' ) );
			$elixar_topbar_text_color = sanitize_hex_color( get_theme_mod( 'elixar_topbar_text_color' ) );
			?>
			.sitetopbar {
				background-color: #<?php echo esc_attr( $elixar_topbar_bg_color ); ?>;
			}
			.top-detail-inverse .social-top-detail i:before, .top-detail-inverse .social-top-detail i:after, .top-detail-inverse .social-top-detail i:hover:after, #header-nav ul li a:hover, #header-nav li.current-menu-item a, #header-nav li.current_page_item a, #header-nav li:hover > a, #header-nav ul li a {
				color: <?php echo esc_attr( $elixar_topbar_text_color ); ?> ;
			}
			<?php // Header
			$elixar_header_bg_color = sanitize_hex_color_no_hash( get_theme_mod( 'elixar_header_bg_color' ) );
			$elixar_logo_text_color = sanitize_hex_color_no_hash( get_theme_mod( 'elixar_logo_text_color' ) );
			$elixar_tagline_text_color = sanitize_hex_color_no_hash( get_theme_mod( 'elixar_tagline_text_color' ) );
			$elixar_header_link_color = sanitize_hex_color_no_hash( get_theme_mod( 'elixar_header_link_color' ) );
			$elixar_header_link_hover_color = sanitize_hex_color_no_hash( get_theme_mod( 'elixar_header_link_hover_color' ) );
			$header_text_color = get_header_textcolor();
			if( $elixar_header_bg_color ) {
			?>
			div#suprhead {
				background-color: #<?php echo esc_attr( $elixar_header_bg_color ); ?>;
			}
			<?php } if( $elixar_logo_text_color ) { ?>
			.site-branding-text .site-title a {
				color: #<?php echo esc_attr( $elixar_logo_text_color ); ?>;
			}
			<?php } if( $elixar_tagline_text_color ) { ?>
			.site-branding-text .site-description {
				color: #<?php echo esc_attr( $elixar_tagline_text_color ); ?>;
			}
			<?php } if( $elixar_header_bg_color ) { ?>
			div#quick-contact {
				color: #<?php echo esc_attr( $header_text_color ); ?>;
			}
			<?php } ?>
			#quick-contact a {
				color: #<?php echo esc_attr( $elixar_header_link_color ); ?>;
			}
			#quick-contact a:hover {
				color: #<?php echo esc_attr( $elixar_header_link_hover_color ); ?>;
			}
			<?php // Menubar
			$elixar_menu_bar_padding = absint( get_theme_mod( 'elixar_menu_bar_padding', 15 ) );
			$elixar_menubar_bg_color = sanitize_hex_color_no_hash( get_theme_mod( 'elixar_menubar_bg_color', 'd14f30' ) );
			$elixar_menu_item_color = sanitize_hex_color_no_hash( get_theme_mod( 'elixar_menu_item_color' ) );
			$elixar_menu_item_hover_color = sanitize_hex_color_no_hash( get_theme_mod( 'elixar_menu_item_hover_color' ) );?>
			#e_main_nav {
                padding: <?php echo intval( $elixar_menu_bar_padding ); ?>px 0;
            }
			#e_main_nav{
				background-color: #<?php echo esc_attr( $elixar_menubar_bg_color ); ?>;
			}
			.main-navigation ul li a{
				color: #<?php echo esc_attr( $elixar_menu_item_color ); ?>;
			}
			ul#primary-menu li a:hover, ul#primary-menu ul ul li a:hover, ul#primary-menu ul ul li.current-menu-item a, ul#primary-menu ul ul li.current_page_item a, ul#primary-menu ul ul li:hover>a, ul#primary-menu li.current_page_item a {
				color: #<?php echo esc_attr( $elixar_menu_item_hover_color ); ?>;
			}
			<?php
			$elixar_page_overlay_color = get_theme_mod( 'elixar_page_overlay_color' );
			$elixar_crumb_title_color = get_theme_mod( 'elixar_page_title_color' );
			$elixar_page_title_bg_image = esc_url(get_theme_mod('elixar_page_title_bg_image'));
			$elixar_page_padding_top = absint( get_theme_mod( 'elixar_page_padding_top', 20 ) ); 
			$elixar_page_padding_bottom = absint( get_theme_mod( 'elixar_page_padding_bottom', 20 ) );
			if ( function_exists( 'rwmb_meta' ) ) {
			if(rwmb_meta( 'elixar_page_padding_top' )!=""){
				$elixar_page_padding_top = rwmb_meta( 'elixar_page_padding_top' );
			}elseif(isset($elixar_page_padding_top)){
				$elixar_page_padding_top = $elixar_page_padding_top;
			}
			}
			if ( function_exists( 'rwmb_meta' ) ) {
			if(rwmb_meta( 'elixar_page_padding_bottom' )!=""){
				$elixar_page_padding_bottom = rwmb_meta( 'elixar_page_padding_bottom' );
			}elseif(isset($elixar_page_padding_bottom)){
				$elixar_page_padding_bottom = $elixar_page_padding_bottom;
			}
			}
			?>
			.e-breadcrumb-page-title {
                padding-top: <?php echo intval( $elixar_page_padding_top ); ?>px;
				padding-bottom: <?php echo intval( $elixar_page_padding_bottom ); ?>px;
            }
			<?php if ( empty( $elixar_page_title_bg_image ) ){
				$elixar_page_title_bg_image = '';
			}
			if ( is_page(  ) ) {
				$elixar_page_id = get_the_ID();
			} else {
				$elixar_page_id = get_option( 'page_for_posts' );
			}
			$page_breadcrumb_bg_img = '';
			if(get_post_meta(get_the_ID(), 'elixar_page_breadcrumb_bg_img', true)!=""){
				$page_breadcrumb_bg_img = get_post_meta(get_the_ID(), 'elixar_page_breadcrumb_bg_img', true);
			} else if(isset($elixar_page_title_bg_image)){
				$page_breadcrumb_bg_img = $elixar_page_title_bg_image;
			}
			if(get_post_meta(get_the_ID(), 'elixar_crumb_title_color', true)!=""){
				$title_font_color = get_post_meta(get_the_ID(), 'elixar_crumb_title_color', true);
			}elseif(isset($elixar_crumb_title_color)){
				$title_font_color = $elixar_crumb_title_color;
			}
			$page_breadcrumb_bg_color = '';
			if ( function_exists( 'rwmb_meta' ) ) {
			if(rwmb_meta( 'elixar_page_breadcrumb_bg_color' )!=""){
				$page_breadcrumb_bg_color = rwmb_meta( 'elixar_page_breadcrumb_bg_color' );
			} else if ( $elixar_page_overlay_color != '' ){
				$page_breadcrumb_bg_color = $elixar_page_overlay_color;
			}
			}
			if( $page_breadcrumb_bg_img != "" || $page_breadcrumb_bg_color != '' ) { ?>
			.e-breadcrumb-page-title {
				background: linear-gradient(<?php echo esc_attr( $page_breadcrumb_bg_color );?>, <?php echo esc_attr( $page_breadcrumb_bg_color );?>) repeat scroll 0% 0%, transparent url('<?php echo esc_url( $page_breadcrumb_bg_img );?>') repeat fixed center center;
			}
			<?php } if( $title_font_color != "" ){ ?>
			.e-breadcrumb-page-title, .e-breadcrumb-page-title .e-page-title {
				color : <?php echo esc_attr( $title_font_color ); ?>;
			}
			<?php } ?>
			<?php // Hero Section
            $large_text_color = sanitize_hex_color( get_theme_mod( 'elixar_hero_large_text_color' ) );//
            $large_text_bg_color = elixar_sanitize_color_alpha( get_theme_mod( 'elixar_hero_large_text_bg_color' ) );
			$elixar_hero_page = get_theme_mod('elixar_hero_page');
			if ( !empty( $elixar_hero_page ) && $elixar_hero_page !=0 ) {
				$elixar_hero_content = get_post($elixar_hero_page);
				$elixar_hero_bgurl = wp_get_attachment_url( get_post_thumbnail_id($elixar_hero_page) );
			}
			if( ! empty ( $elixar_hero_bgurl ) ) { ?>
			.hero-section-wrapper {
				background: linear-gradient(rgba(0,0,0,.3), rgba(0,0,0,.3)) repeat scroll 0% 0%, transparent url('<?php echo esc_url( $elixar_hero_bgurl );?>') repeat fixed center center;
			}
			<?php }
            if ( $large_text_color ) {
                ?>
                .e-hero-large-text {
                    color: <?php echo esc_attr( $large_text_color ); ?>;
                }
                <?php
            }
            if ( $large_text_bg_color ) {
                ?>
                .e-hero-large-text {
                    background: <?php echo esc_attr( $large_text_bg_color ); ?>;
                    padding: 10px;
                    text-shadow: none;
                    border-radius: 3px;
                }
                <?php
            }
			$hero_top_pad = absint( get_theme_mod( 'elixar_hero_padding_top', 10 ) );
			$hero_bottom_pad = absint( get_theme_mod( 'elixar_hero_padding_bottom', 10 ) );
			?>
			.hero-section-wrapper{
				padding: <?php echo intval( $hero_top_pad ); ?>% 0 <?php echo intval( $hero_bottom_pad ); ?>%;
			}
			
			<?php $elixar_cta_page = get_theme_mod('elixar_cta_page');
			if ( !empty($elixar_cta_page) && $elixar_cta_page!=0) {
			$elixar_cta_content = get_post($elixar_cta_page);
			$elixar_cta_bgurl = wp_get_attachment_url( get_post_thumbnail_id($elixar_cta_page) );
			}
			if( ! empty ( $elixar_cta_bgurl ) ) { ?>
			.section-cta {
				background: linear-gradient(rgba(0,0,0,.3), rgba(0,0,0,.3)) repeat scroll 0% 0%, transparent url('<?php echo esc_url( $elixar_cta_bgurl );?>') repeat fixed center center;
			}
			<?php } // Footer Section
			$elixar_footer_bg_color = sanitize_hex_color_no_hash( get_theme_mod( 'elixar_footer_bg_color' ) );
			$elixar_footer_text_color = sanitize_hex_color( get_theme_mod( 'elixar_footer_text_color' ) );
			$elixar_footer_widgets_title_color = sanitize_hex_color( get_theme_mod( 'elixar_footer_widgets_title_color' ) );
			$elixar_footer_widgets_link_color = sanitize_hex_color( get_theme_mod( 'elixar_footer_widgets_link_color' ) );
			$elixar_footer_widgets_link_hover_color = sanitize_hex_color( get_theme_mod( 'elixar_footer_widgets_link_hover_color' ) );
			?>
			#section_footer {
				background-color: #<?php echo esc_attr( $elixar_footer_bg_color ); ?>;
				color: <?php echo esc_attr( $elixar_footer_text_color ); ?>;
			}
			.content-section.footer-main p {
				color: <?php echo esc_attr( $elixar_footer_text_color ); ?>;
			}
			#section_footer h3.foo-widget-title {
				color: <?php echo esc_attr( $elixar_footer_widgets_title_color ); ?>;
			}
			.content-section.footer-main .footer_widget a {
				color: <?php echo esc_attr( $elixar_footer_widgets_link_color ); ?>;
			}
			.content-section.footer-main .footer_widget a:hover {
				color: <?php echo esc_attr( $elixar_footer_widgets_link_hover_color ); ?>;
			}
			<?php $elixar_copyright_bg_color = sanitize_hex_color_no_hash( get_theme_mod( 'elixar_copyright_bg_color' ) );
            $elixar_copyright_text_color = sanitize_hex_color( get_theme_mod( 'elixar_copyright_text_color' ) );
            $elixar_copyright_link_color = sanitize_hex_color( get_theme_mod( 'elixar_copyright_link_color' ) );
            $elixar_copyright_link_hover_color = sanitize_hex_color( get_theme_mod( 'elixar_copyright_link_hover_color' ) ); ?>
			.content-section.footer_copyright {
				background-color: #<?php echo esc_attr( $elixar_copyright_bg_color ); ?>;
			}
			.content-section.footer_copyright p.copyright-text{
				color: <?php echo esc_attr( $elixar_copyright_text_color ); ?>;
			}
			p.copyright-text a {
				color: <?php echo esc_attr( $elixar_copyright_link_color ); ?>;
			}
			p.copyright-text a:hover{
				color: <?php echo esc_attr( $elixar_copyright_link_hover_color ); ?>;
			}
        <?php $elixar_css = ob_get_clean();
        if ( trim( $elixar_css ) == "" ) {
            return ;
        }
        $elixar_css = preg_replace(
            array(
                // Remove comment(s)
                '#("(?:[^"\\\]++|\\\.)*+"|\'(?:[^\'\\\\]++|\\\.)*+\')|\/\*(?!\!)(?>.*?\*\/)|^\s*|\s*$#s',
                // Remove unused white-space(s)
                '#("(?:[^"\\\]++|\\\.)*+"|\'(?:[^\'\\\\]++|\\\.)*+\'|\/\*(?>.*?\*\/))|\s*+;\s*+(})\s*+|\s*+([*$~^|]?+=|[{};,>~+]|\s*+-(?![0-9\.])|!important\b)\s*+|([[(:])\s++|\s++([])])|\s++(:)\s*+(?!(?>[^{}"\']++|"(?:[^"\\\]++|\\\.)*+"|\'(?:[^\'\\\\]++|\\\.)*+\')*+{)|^\s++|\s++\z|(\s)\s+#si',
            ),
            array(
                '$1',
                '$1$2$3$4$5$6$7',
            ),
            $elixar_css
        );
        if ( ! function_exists( 'elixar_wp_get_custom_css' ) ) {  // Back-compat for WordPress < 4.7.
            $custom = get_option('elixar_custom_css');
            if ($custom) {
                $elixar_css .= "\n/* --- Begin custom CSS --- */\n" . $custom . "\n/* --- End custom CSS --- */\n";
            }
        }
       return apply_filters( 'elixar_custom_css', $elixar_css ) ;
	}
}
if ( function_exists( 'elixar_wp_update_custom_css_post' ) ) {
    // Migrate any existing theme CSS to the core option added in WordPress 4.7.
    $elixar_css = get_option( 'elixar_custom_css' );
    if ( $elixar_css ) {
        $elixar_core_css = elixar_wp_get_custom_css(); // Preserve any CSS already added to the core option.
        $elixar_return = elixar_wp_update_custom_css_post( $elixar_core_css ."\n". $elixar_css );
        if ( ! is_wp_error( $elixar_return ) ) {
            // Remove the old theme_mod, so that the CSS is stored in only one place moving forward.
            delete_option( 'elixar_custom_css' );
        }
    }
}
?>