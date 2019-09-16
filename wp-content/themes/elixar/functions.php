<?php
/**
 * Elixar functions and definitions.
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package Elixar
 */

require(get_template_directory() . '/inc/custom/metabox.php');
/**
 * Customizer additions.
 */
require get_template_directory() . '/inc/customizer/customizer.php';
/**
 * Add theme info page
 */
require get_template_directory() . '/inc/customizer/dashboard.php';
/**
 * Sanitize Customizer Fields
 */
require get_template_directory() . '/inc/customizer/sanitize.php';
/**
 * Implement the Custom Header feature.
 */
require get_template_directory() . '/inc/custom-header.php';
/**
 * Custom template tags for this theme.
 */
require get_template_directory() . '/inc/template-tags.php';
/**
 * Custom functions that act independently of the theme templates.
 */
require get_template_directory() . '/inc/extras.php';
/**
 * Load Jetpack compatibility file.
 */
require get_template_directory() . '/inc/jetpack.php';
/**
 * FontAwesome Array
 */
require get_template_directory() . '/inc/font-awesome-list.php';
/**
* Load Tgmpa
*/
if ( ! function_exists( 'tgmpa' ) ) :
require_once dirname( __FILE__ ) . '/class-tgm-plugin-activation.php';
endif;
if ( ! function_exists( 'elixar_setup' ) ) :
/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which
 * runs before the init hook. The init hook is too late for some features, such
 * as indicating support for post thumbnails.
 */
function elixar_setup()
{
	/*
	 * Make theme available for translation.
	 * Translations can be filed in the /languages/ directory.
	 * If you're building a theme based on Elixar, use a find and replace
	 * to change 'elixar' to the name of your theme in all the template files.
	 */
	load_theme_textdomain( 'elixar', get_template_directory() . '/languages' );
	// Add default posts and comments RSS feed links to head.
	add_theme_support( 'automatic-feed-links' );
	/*
	 * Let WordPress manage the document title.
	 * By adding theme support, we declare that this theme does not use a
	 * hard-coded <title> tag in the document head, and expect WordPress to
	 * provide it for us.
	 */
	add_theme_support( 'title-tag' );
	add_post_type_support( 'page', 'excerpt' );
	/*
	 * Enable support for Post Thumbnails on posts and pages.
	 *
	 * @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
	 */
	add_theme_support( 'post-thumbnails' );
	add_image_size( 'elixar_section_extra_thumb', 640, 400, true );
	add_image_size( 'elixar_gallery_thumb', 615, 400, true );
	add_image_size( 'elixar_blog_home_thumb', 348, 196, array( 'top, left' ) );
	add_image_size( 'elixar_blog_full_thumb', 1110, 515, true );
    add_image_size( 'elixar_blog_sidebar_thumb', 805, 350, true );
	// This theme uses wp_nav_menu() in one location.
	register_nav_menus( array(
		'primary' => esc_html__( 'Primary Menu', 'elixar' ),
		'top'     => esc_html__( 'Top Menu', 'elixar' ),
	) );
	/*
	 * Switch default core markup for search form, comment form, and comments
	 * to output valid HTML5.
	 */
	add_theme_support( 'html5', array(
		'search-form',
		'comment-form',
		'comment-list',
		'gallery',
		'caption',
	) );
	// Set up the WordPress core custom background feature.
	add_theme_support( 'custom-background', apply_filters( 'elixar_custom_background_args', array(
		'default-color' => 'ffffff',
		'default-image' => '',
	) ) );
	// Enable support for custom logo.
	add_theme_support( 'custom-logo', array(
		'width'       => 150,
		'height'      => 75,
		'flex-height' => true,
		'header-text' => array( 'site-title', 'site-description' ),
	) );
	// Add support for editor styles.
	add_theme_support( 'editor-styles' );
	// Enqueue editor styles.
	add_editor_style(get_stylesheet_directory_uri() . '/css/elixar-editor-style.css');
	/* WooCommerce Support */
    add_theme_support('woocommerce');
    add_theme_support('wc-product-gallery-zoom');
    add_theme_support('wc-product-gallery-lightbox');
    add_theme_support('wc-product-gallery-slider');
	// Enable support for selective refresh of widgets in Customizer.
	add_theme_support( 'customize-selective-refresh-widgets' );
	// Add support for Block Styles.
	add_theme_support( 'wp-block-styles' );
	// Add support for full and wide align images.
	add_theme_support( 'align-wide' );

	// Recommend plugins
	add_theme_support( 'recommend-plugins', array(
		'one-click-demo-import'=> array(
			'name'     => esc_html__( 'One Click Demo Import', 'elixar' ), // The plugin name.
			'active_filename' => 'one-click-demo-import/one-click-demo-import.php',
		),
		'contact-form-7' => array(
			'name' => esc_html__( 'Contact Form 7', 'elixar' ),
			'active_filename' => 'contact-form-7/wp-contact-form-7.php',
		),
		'fusion-slider'=>array(
			'name'     => esc_html__( 'Fusion Slider', 'elixar' ), // The plugin name.
			'active_filename' => 'fusion-slider/fusion-slider.php',
		),
		'meta-box'=>array(
			'name'     => esc_html__( 'Meta Box', 'elixar' ), // The plugin name.
			'active_filename' => 'meta-box/meta-box.php',
		)
	) );
}
endif; // elixar_setup
add_action( 'after_setup_theme', 'elixar_setup' );
/**
 * Set the content width in pixels, based on the theme's design and stylesheet.
 *
 * Priority 0 to make it available to lower priority callbacks.
 *
 * @global int $content_width Content width.
 */
function elixar_content_width() {
	// This variable is intended to be overruled from themes.
	// Open WPCS issue: {@link https://github.com/WordPress-Coding-Standards/WordPress-Coding-Standards/issues/1043}.
	// phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedVariableFound
	$GLOBALS['content_width'] = apply_filters( 'elixar_content_width', 805 );
}
add_action( 'after_setup_theme', 'elixar_content_width', 0 );
// Read more tag to formatting in blog page
function elixar_content_more($more)
{
	global $post;
	if ( is_admin() ) {
		return $more;
	}	
	return '<div class="elixar-read-more shadow-around"><a href="' . esc_url( get_permalink($post->ID) ) . '">' . esc_html__('Read More', 'elixar') . '</a></div>';
}
add_filter('the_content_more_link', 'elixar_content_more');
/**
 * Register widget area.
 *
 * @link https://developer.wordpress.org/themes/functionality/sidebars/#registering-a-sidebar
 */
function elixar_widgets_init() {
	register_sidebar( array(
		'name'          => esc_html__( 'Sidebar Widget Area', 'elixar' ),
		'id'            => 'sidebar-widget',
		'description'   => esc_html__( 'Sidebar widget area', 'elixar' ),
		'before_widget' => '<div id="%1$s" class="widget %2$s elixar-product-sidebar shadow-around">',
		'after_widget'  => '</div>',
		'before_title'  => '<div class="row-heading row-heading-mt-fourty"><h3 class="title-sm text-uppercase hr-left text-margin">',
		'after_title'   => '</h3></div>',
	));
	if ( elixar_woocommerce_status() ) {
        register_sidebar( array(
			'name'          => esc_html__( 'Woo-Commerce Sidebar Widget Area', 'elixar' ),
			'id'            => 'elixar-shop-sidebar-widget',
			'description'   => esc_html__( 'Woo-Commerce sidebar widget area', 'elixar' ),
			'before_widget' => '<div id="%1$s" class="widget %2$s elixar-product-sidebar shadow-around">',
			'after_widget'  => '</div>',
			'before_title'  => '<div class="row-heading row-heading-mt-fourty"><h3 class="title-sm text-uppercase hr-left text-margin">',
			'after_title'   => '</h3></div>',
		));
    }
	for ( $i = 1; $i<= 4; $i++ ) {
		register_sidebar( array(
			/* translators: %s: Footer Widget Name */
			'name'          => sprintf( esc_html__( 'Footer Widget Area %d', 'elixar'), $i ),
			'id'            => 'footer-widget-'.$i,
			/* translators: %s: Footer Widget Description */
			'description'   => sprintf( esc_html__( 'Footer widget area %d', 'elixar' ), $i ),
			'before_widget' => '<div id="%1$s" class="footer_widget %2$s">',
            'after_widget' => '</div>',
			'before_title'  => '<h3 class="text-margin title-sm hr-left mb-twenty foo-widget-title">',
			'after_title'   => '</h3>',
		) );
	}
}
add_action( 'widgets_init', 'elixar_widgets_init' );
/**
 * Enqueue scripts and styles.
 */
function elixar_scripts() {
	$minified_assests = intval( get_theme_mod( 'elixar_minified_assests', 0 ) );
	$min = ''; 
	if ( $minified_assests == 1 ) {
		$min = '.min';
	}
	//Default CSS
	wp_enqueue_style( 'jquery-sidr-dark', get_template_directory_uri() . '/css/sidr/css/jquery.sidr.dark'.$min.'.css' );
	wp_enqueue_style( 'bootstrap', get_template_directory_uri() . '/css/bootstrap'.$min.'.css' );
	wp_enqueue_style( 'elixar-style', get_stylesheet_uri() );
	$custom_css = elixar_custom_inline_style();
    wp_add_inline_style( 'elixar-style', $custom_css );
	wp_enqueue_script( 'jquery-skip-link-focus-fix', get_template_directory_uri() . '/js/skip-link-focus-fix'.$min.'.js', array(), '20130115', true );
	/* Added by hunny */
	wp_enqueue_style( 'elixar-theme-skin-red', get_template_directory_uri(). '/css/skins/elixar-red'.$min.'.css' );
	wp_enqueue_style( 'elixar-font-awesome', get_template_directory_uri() . '/css/all'.$min.'.css' );
	$elixar_is_rtl_enable = intval( get_theme_mod( 'elixar_is_rtl_enable', 0 ) );
	if( $elixar_is_rtl_enable == 1 ) {
		wp_enqueue_style( 'elixar-style-main-rtl', get_template_directory_uri().'/css/elixar-rtl'.$min.'.css' );
	}
	//Footer JS
	wp_enqueue_script( 'jquery-sidr', get_template_directory_uri() . '/js/sidr/js/jquery.sidr'.$min.'.js', array(), '10122018', true );
	wp_enqueue_script( 'bootstrap', get_template_directory_uri() . '/js/bootstrap'.$min.'.js', array('jquery'), '10122018', true );
	//Default JS
	wp_enqueue_script( 'imagesloaded','', array('jquery'), '10122018', true );
	if ( class_exists( 'WooCommerce' ) ) {
		wp_enqueue_script( 'jquery-dcjqaccordion', get_template_directory_uri() . '/js/jquery.dcjqaccordion'.$min.'.js', array(), '10122018', true );
	}
	wp_enqueue_script( 'elixar-theme', get_template_directory_uri() . '/js/section/elixar-theme.js', array('jquery'), '10122018', true );
	wp_enqueue_script( 'elixar-custom', get_template_directory_uri() . '/js/section/elixar-custom.js', array('jquery'), '10122018', true );
	//Enable Scroll Reveal Effect
	$scroll_reveal = intval( get_theme_mod( 'elixar_animation_enable', 1 ) );
	if ( $scroll_reveal == 1 ) {
		wp_enqueue_script( 'jquery-scrollReveal', get_template_directory_uri() . '/js/scrollReveal'.$min.'.js', array('jquery'), '10122018', true );
		wp_enqueue_script( 'elixar-sreveal', get_template_directory_uri() . '/js/section/elixar-scroll-reveal.js', array('jquery'), '10122018', true );
		wp_localize_script( 'elixar-sreveal', 'enable_sreveal_obj', array(
			'scroll_reveal' => $scroll_reveal,
		) );
	}
	$elixar_load_post_button = intval( get_theme_mod( 'elixar_load_post_button_enable', 1 ) );
	if ( $elixar_load_post_button == 1 ) {
		wp_enqueue_script( 'masonry', '', array('jquery'), '10122018', true );
		$elixar_blog_post_count = absint( get_theme_mod( 'elixar_blog_number', '3' ) );
		$elixar_blog_no_more_post_text = get_theme_mod( 'elixar_blog_no_more_post_text', esc_html__('No more older post found', 'elixar' ));
		wp_enqueue_script( 'elixar-load-posts', get_template_directory_uri() . '/js/section/elixar-load-posts.js', array(), '10122018', true );
		wp_localize_script( 'elixar-load-posts', 'load_More_Post_Obj', array(
			'ajaxurl'  => admin_url( 'admin-ajax.php' ),
			'ppp'=> $elixar_blog_post_count,
			'noposts'  => $elixar_blog_no_more_post_text,
		) );
	}
	//[if IE 8]><html class="ie8"><![endif]
	wp_enqueue_script( 'jquery-html5shiv', get_template_directory_uri() . '/js/html5shiv'.$min.'.js', array('jquery'), '10122018', true );
	wp_enqueue_script( 'jquery-respond', get_template_directory_uri() . '/js/respond'.$min.'.js', array('jquery'), '10122018', true );
	wp_script_add_data( 'jquery-html5shiv', 'conditional', 'lt IE 9' );
  	wp_script_add_data( 'jquery-respond', 'conditional', 'lt IE 9' );
	
	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
	// Google Fonts
	wp_enqueue_style( 'Open Sans', '//fonts.googleapis.com/css?family=Open+Sans:400italic,400,600,700,800,300' );
}
add_action( 'wp_enqueue_scripts', 'elixar_scripts' );
if ( !function_exists( 'elixar_register_required_plugins' ) ):
    /**
     * Register the required plugins for this theme.
     *
     * In this example, we register five plugins:
     * - one included with the TGMPA library
     * - two from an external source, one from an arbitrary source, one from a GitHub repository
     * - two from the .org repo, where one demonstrates the use of the `is_callable` argument
     *
     * The variable passed to tgmpa_register_plugins() should be an array of plugin
     * arrays.
     *
     * This function is hooked into tgmpa_init, which is fired within the
     * TGM_Plugin_Activation class constructor.
     */
    function elixar_register_required_plugins()
{
        /*
         * Array of plugin arrays. Required keys are name and slug.
         * If the source is NOT from the .org repo, then source is also required.
         */
        $plugins = array(
            array(
                'name'     => esc_html__('One Click Demo Import', 'elixar'), // The plugin name.
                'slug'     => 'one-click-demo-import', // The plugin slug (typically the folder name).
                'required' => false, // If false, the plugin is only 'recommended' instead of required.
            ),
			array(
                'name'     => esc_html__('Contact Form 7', 'elixar'), // The plugin name.
                'slug'     => 'contact-form-7', // The plugin slug (typically the folder name).
                'required' => false, // If false, the plugin is only 'recommended' instead of required.
            ),
            array(
                'name'     => esc_html__('Fusion Slider', 'elixar'), // The plugin name.
                'slug'     => 'fusion-slider', // The plugin slug (typically the folder name).
                'required' => false, // If false, the plugin is only 'recommended' instead of required.
            ),
			array(
                'name'     => esc_html__('Meta Box', 'elixar'), // The plugin name.
                'slug'     => 'meta-box', // The plugin slug (typically the folder name).
                'required' => false, // If false, the plugin is only 'recommended' instead of required.
            ),
        );
        /*
         * Array of configuration settings. Amend each line as needed.
         *
         * TGMPA will start providing localized text strings soon. If you already have translations of our standard
         * strings available, please help us make TGMPA even better by giving us access to these translations or by
         * sending in a pull-request with .po file(s) with the translations.
         *
         * Only uncomment the strings in the config array if you want to customize the strings.
         */
        $config = array(
            'id'           => 'tgmpa', // Unique ID for hashing notices for multiple instances of TGMPA.
            'default_path' => '', // Default absolute path to bundled plugins.
            'menu'         => 'tgmpa-install-plugins', // Menu slug.
            'parent_slug'  => 'themes.php', // Parent menu slug.
            'capability'   => 'edit_theme_options', // Capability needed to view plugin install page, should be a capability associated with the parent menu used.
            'has_notices'  => true, // Show admin notices or not.
            'dismissable'  => true, // If false, a user cannot dismiss the nag message.
            'dismiss_msg'  => '', // If 'dismissable' is false, this message will be output at top of nag.
            'is_automatic' => false, // Automatically activate plugins after installation or not.
            'message'      => '', // Message to output right before the plugins table.
            'strings'      => array(
                'page_title'                      => esc_html__('Install Required Plugins', 'elixar'),
                'menu_title'                      => esc_html__('Install Plugins', 'elixar'),
                /* translators: %s: plugin name. */
				'installing'                      => esc_html__('Installing Plugin: %s', 'elixar'),
                'oops'                            => esc_html__('Something went wrong with the plugin API.', 'elixar'),
                /* translators: %1$s: plugin name(s). */
				'notice_can_install_required'     => _n_noop('This theme requires the following plugin: %1$s.', 'This theme requires the following plugins: %1$s.', 'elixar'),
                /* translators: %1$s: plugin name(s). */
				'notice_can_install_recommended'  => _n_noop('This theme recommends the following plugin: %1$s.', 'This theme recommends the following plugins: %1$s.', 'elixar'),
                /* translators: %1$s: plugin name(s). */
				'notice_cannot_install'           => _n_noop('Sorry, but you do not have the correct permissions to install the %1$s plugin.', 'Sorry, but you do not have the correct permissions to install the %1$s plugins.', 'elixar'),
                /* translators: %1$s: plugin name(s). */
				'notice_ask_to_update'            => _n_noop('The following plugin needs to be updated to its latest version to ensure maximum compatibility with this theme: %1$s.', 'The following plugins need to be updated to their latest version to ensure maximum compatibility with this theme: %1$s.', 'elixar'),
                /* translators: %1$s: plugin name(s). */
				'notice_ask_to_update_maybe'      => _n_noop('There is an update available for: %1$s.', 'There are updates available for the following plugins: %1$s.', 'elixar'),
                /* translators: %1$s: plugin name(s). */
				'notice_cannot_update'            => _n_noop('Sorry, but you do not have the correct permissions to update the %1$s plugin.', 'Sorry, but you do not have the correct permissions to update the %1$s plugins.', 'elixar'),
                /* translators: %1$s: plugin name(s). */
				'notice_can_activate_required'    => _n_noop('The following required plugin is currently inactive: %1$s.', 'The following required plugins are currently inactive: %1$s.', 'elixar'),
				/* translators: %1$s: plugin name(s). */
                'notice_can_activate_recommended' => _n_noop('The following recommended plugin is currently inactive: %1$s.', 'The following recommended plugins are currently inactive: %1$s.', 'elixar'),
				/* translators: %1$s: plugin name(s). */
                'notice_cannot_activate'          => _n_noop('Sorry, but you do not have the correct permissions to activate the %1$s plugin.', 'Sorry, but you do not have the correct permissions to activate the %1$s plugins.', 'elixar'),
                'install_link'                    => _n_noop('Begin installing plugin', 'Begin installing plugins', 'elixar'),
                'update_link'                     => _n_noop('Begin updating plugin', 'Begin updating plugins', 'elixar'),
                'activate_link'                   => _n_noop('Begin activating plugin', 'Begin activating plugins', 'elixar'),
                'return'                          => esc_html__('Return to Required Plugins Installer', 'elixar'),
                'plugin_activated'                => esc_html__('Plugin activated successfully.', 'elixar'),
                'activated_successfully'          => esc_html__('The following plugin was activated successfully:', 'elixar'),
                /* translators: %1$s: plugin name(s). */
				'plugin_already_active'           => esc_html__('No action taken. Plugin %1$s was already active.', 'elixar'),
				/* translators: %s: plugin name(s). */
                'plugin_needs_higher_version'     => esc_html__('Plugin not activated. A higher version of %s is needed for this theme. Please update the plugin.', 'elixar'),
				/* translators: %1$s: dashboard link. */
                'complete'                        => esc_html__('All plugins installed and activated successfully. %1$s', 'elixar'),
                'contact_admin'                   => esc_html__('Please contact the administrator of this site for help.', 'elixar'),
                'nag_type'                        => 'updated', // Determines admin notice type - can only be 'updated', 'update-nag' or 'error'.
            ),
        );
        tgmpa($plugins, $config);
    }
endif;
add_action( 'tgmpa_register', 'elixar_register_required_plugins' );
function elixar_comments($comments, $args, $depth)
{
    extract($args, EXTR_SKIP);
    if ( 'div' == $args['style'] ) {
        $add_below = 'comment';
    } else {
        $add_below = 'div-comment';
    }
    ?>
    <div <?php comment_class("media comment"); ?>>
		<a class="media-left" href="#">
		<?php 
		if ( $args['avatar_size'] != 0 ) {
			echo get_avatar($comments, 60);
		 } ?></a>
		<div class="media-body">	 
			 <h4 class="media-heading"><?php printf( '%s', esc_html( get_comment_author() ) );?></h4>
			 <ul class="list-unstructured list-inline e-post-meta-part">
				<li>
					<a href="#" class="user-by"><?php printf( '%s', esc_html( get_comment_author() ) );?></a>
				</li>
				<li>
					<a href="#"><?php
					/* translators: %1$s: Comment Date, %1$s: Comment Date */
					printf( esc_html__( '%1$s at %2$s', 'elixar' ), get_comment_date(), get_comment_time() ); ?></a>
				</li>
				<li>
				<a><?php edit_comment_link(); ?></a>
				</li>
			</ul>
			<?php if ( $comments->comment_approved != '0' ) {
				comment_text(); ?>
				<a class="display-inline-block"><small><i class="fas fa-reply mr-eight"></i><?php comment_reply_link( array_merge( $args, array( 'add_below' => $add_below, 'depth' => $depth, 'max_depth' => $args['max_depth']) ) ); ?></small></a>
			<?php } else {
				echo '<p>' . esc_html__( 'Your comment is awaitting for moderation.','elixar' ) . '</p>';
			} ?>
		</div>
<?php
}
/* Convert hexdec color string to rgb(a) string */
function elixarhex2rgba( $color, $opacity = false ) {
	$default = 'rgb(0,0,0)';
	//Return default if no color provided
	if( empty( $color ) )
	return $default; 
	//Sanitize $color if "#" is provided 
	if ( $color[0] == '#' ) {
		$color = substr( $color, 1 );
	}
	//Check if color has 6 or 3 characters and get values
	if ( strlen( $color ) == 6 ) {
			$hex = array( $color[0] . $color[1], $color[2] . $color[3], $color[4] . $color[5] );
	} elseif ( strlen( $color ) == 3 ) {
			$hex = array( $color[0] . $color[0], $color[1] . $color[1], $color[2] . $color[2] );
	} else {
			return $default;
	}
	//Convert hexadec to rgb
	$rgb =  array_map('hexdec', $hex);
	//Check if opacity is set(rgba or rgb)
	if( $opacity ) {
		if( abs( $opacity ) > 1 )
			$opacity = 1.0;
		$output = 'rgba('.implode(",",$rgb).','.$opacity.')';
	} else {
		$output = 'rgb('.implode(",",$rgb).')';
	}
	//Return rgb(a) color string
	return $output;
}
/* Breadcrumbs  */
function elixar_breadcrumbs()
{
    $delimiter = wp_kses_post(' <i class="fas fa-chevron-circle-right breadcrumbs_space"></i> ');
    $home = esc_html__( 'Home', 'elixar' ); // text for the 'Home' link
    $before = ''; // tag before the current crumb
    $after = ''; // tag after the current crumb
    echo '<ol class="breadcrumb text-right text-center-xs">';
    global $post;
    $homeLink = home_url();
    echo '<li><a href="' . esc_url( $homeLink ) . '">' . $home . '</a>' . $delimiter;
    if ( is_category() ) {
        global $wp_query;
        $cat_obj = $wp_query->get_queried_object();
        $thisCat = $cat_obj->term_id;
        $thisCat = get_category($thisCat);
        $parentCat = get_category($thisCat->parent);
        if ( $thisCat->parent != 0 )
            echo(get_category_parents($parentCat, TRUE, ' ' . $delimiter . '</li> '));
        echo $before . esc_html_e("Category ", 'elixar') . ' ' . single_cat_title($delimiter, false) . '' . $after;
    } elseif ( is_day() ) {
        echo '<li><a href="' . esc_url( get_year_link(get_the_time('Y')) ). '">' . get_the_time('Y') . '</a>' . $delimiter . '</li>';
        echo '<li><a href="' . esc_url( get_month_link(get_the_time('Y')), get_the_time('m') ) . '">' . get_the_time('F') . '</a> ' . $delimiter;
        echo $before . get_the_time('d') . '</li>';
    } elseif (  is_month() ) {
        echo '<li><a href="' . esc_url( get_year_link(get_the_time('Y')) ) . '">' . get_the_time('Y') . '</a>' . $delimiter;
        echo $before . get_the_time('F') . '</li>';
    } elseif ( is_year() ) {
        echo $before . get_the_time('Y') . '</li>';
    } elseif ( is_single() && !is_attachment() ) {
        if ( get_post_type() != 'post' ) {
            $post_type = get_post_type_object(get_post_type());
            if( get_post_type()=="product" ) {
                $shop_page_url = get_permalink( wc_get_page_id( 'shop' ) );
                echo '<li><a href="' . esc_url( $shop_page_url ) . '">' . $post_type->labels->singular_name . '</a> ' . $delimiter;
            } else {
				$slug = $post_type->rewrite;
				echo '<li><a href="' . esc_url( $homeLink ) . '/' . $slug['slug'] . '/">' . $slug['slug'] . '</a> ' . $delimiter;
			}
            echo $before . esc_html( get_the_title() ) . '</li>';
        } else {
            $cat = get_the_category();
            $cat = $cat[0];
            echo $before . esc_html( get_the_title() ) . '</li>';
        }
    } else if( class_exists('woocommerce') && is_shop() ) {
        echo $before . esc_html__('Shop','elixar') . $after;
    }else if ( !is_single() && !is_page() && get_post_type() != 'post' ) {
        $post_type = get_post_type_object(get_post_type());
		if( $post_type==null ) {
			echo $before . esc_html__('Nothing Found','elixar') . $after;
		} else {
			$slug = $post_type->rewrite;
			echo $before . $slug['slug'] . $after;
		}
    } else if ( is_attachment() ) {
        $parent = get_post($post->post_parent);
        $cat = get_the_category($parent->ID);
        $cat = $cat[0];
        echo get_category_parents($cat, TRUE, ' ' . $delimiter . ' ');
        echo '<li><a href="' . esc_url( get_permalink($parent) ) . '">' . $parent->post_title . '</a>' . $delimiter;
        echo $before . esc_html( get_the_title() ) . $after;
    } else if ( is_page() && !$post->post_parent ) {
        echo $before . esc_html( get_the_title() ) . $after;
    } else if ( is_page() && $post->post_parent ) {
        $parent_id = $post->post_parent;
        $breadcrumbs = array();
        while ( $parent_id ) {
            $page = get_page($parent_id);
            $breadcrumbs[] = '<li><a href="' . esc_url( get_permalink($page->ID) ) . '">' . esc_html( get_the_title($page->ID) ) . '</a></li>';
            $parent_id = $page->post_parent;
        }
        $breadcrumbs = array_reverse($breadcrumbs);
        foreach ( $breadcrumbs as $crumb )
            echo $crumb . ' ' . $delimiter . ' ';
        echo $before . esc_html( get_the_title() ) . $after;
    } elseif ( is_search() ) {
        echo $before . esc_html_e("Search results for ", 'elixar') . get_search_query() . '"' . $after;
    } elseif ( is_tag() ) {
        echo $before . esc_html_e('Tag ', 'elixar') . single_tag_title($delimiter, false) . $after;
    } elseif ( is_author() ) {
        global $author;
        $userdata = get_userdata($author);
        echo $before . esc_html_e("Articles posted by: ", 'elixar') . $userdata->display_name . $after;
    } elseif ( is_404() ) {
        echo $before . esc_html_e("Error 404 ", 'elixar') . $after;
    } elseif ( single_post_title() ) {
        echo $before . single_post_title() . $after;
    }
    echo '</ol>';
}
// get taxonomies terms links
function elixar_custom_taxonomies_terms_links()
{
    // get post by post id
    global $post;
    // get post type by post
    $post_type = $post->post_type;
    // get post type taxonomies
    $taxonomies = get_object_taxonomies($post_type, 'objects');
    $out = array();
    foreach ( $taxonomies as $taxonomy_slug => $taxonomy ) {
        // get the terms related to post
        $terms = get_the_terms($post->ID, $taxonomy_slug);
        if ( !empty( $terms ) ) {
            foreach ( $terms as $term ) {
                $out[] =
                    '  <a href="'
                    . get_term_link($term->slug, $taxonomy_slug) . '">'
                    . $term->name
                    . "</a>";
            }
        }
    }
    return implode(', ', $out);
}
/* Woocommerce supoport */
remove_action('woocommerce_before_main_content', 'woocommerce_output_content_wrapper', 10);
remove_action('woocommerce_after_main_content', 'woocommerce_output_content_wrapper_end', 10);
add_action('woocommerce_before_main_content', 'elixar_theme_wrapper_start', 10);
add_action('woocommerce_after_main_content', 'elixar_theme_wrapper_end', 10);
function elixar_theme_wrapper_start()
{ ?>
<!-- SECTION HEADING -->
<?php $elixar_page_title_bar_enable = intval( get_theme_mod( 'elixar_page_title_bar_enable', 1 ) );
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
				<div class="col-sm-6 woo-crumbs">
					<?php if( !is_product() ) : ?>
					<h1 class="e-page-title text-center-xs"><?php woocommerce_page_title(); ?></h1>
					<?php else: 
					woocommerce_template_single_title();
					endif; ?>
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
<?php }
if ( is_active_sidebar( 'elixar-shop-sidebar-widget' ) ) {
	$pro_layout = 'col-sm-8';
} else {
	$pro_layout = 'col-sm-12';
}
echo '<div class="elixar-woo-product">
	<div class="container">
		<div class="row">
			<div class="'.$pro_layout.' e-content-block">
				<div class="elixar-woo-product-detail shadow-around">';
}
function elixar_theme_wrapper_end()
{ ?>
				</div>
			</div>
			<?php get_sidebar( 'shop' ); ?>
		</div>
	</div>
</div>
<?php }
/**
 * elixar_hide_page_title
 *
 * Removes the "shop" title on the main shop page
 */
function elixar_hide_page_title() {
    return false;
}
add_filter( 'woocommerce_show_page_title' , 'elixar_hide_page_title' );
/**
 * Removes breadcrumbs
 */
remove_action( 'woocommerce_before_main_content', 'woocommerce_breadcrumb', 20, 0 );

if ( ! function_exists( 'elixar_primary_navigation_fallback' ) ) :
	/**
	 * Fallback for primary navigation.
	 *
	 * @since 1.0.0
	 */
	function elixar_primary_navigation_fallback() {
		echo '<ul>';
		echo '<li><a href="' . esc_url( home_url( '/' ) ) . '">' . esc_html__( 'Home', 'elixar' ) . '</a></li>';
		wp_list_pages( array(
			'title_li' => '',
			'depth'    => 10,
		) );
		echo '</ul>';
	}
endif;
/**
 * Adjust a hex color brightness
 * Allows us to create hover styles for custom link colors
 *
 * @param  strong  $hex   hex color e.g. #111111.
 * @param  integer $steps factor by which to brighten/darken ranging from -255 (darken) to 255 (brighten).
 * @return string        brightened/darkened hex color
 * @since  1.0.0
 */
function elixar_adjust_color_brightness( $hex, $steps )
{
    // Steps should be between -255 and 255. Negative = darker, positive = lighter.
    $steps = max(-255, min(255, $steps));
    // Format the hex color string.
    $hex = str_replace('#', '', $hex);
    if ( 3 == strlen( $hex ) ) {
        $hex = str_repeat(substr($hex, 0, 1), 2) . str_repeat(substr($hex, 1, 1), 2) . str_repeat(substr($hex, 2, 1), 2);
    }
    // Get decimal values.
    $r = hexdec(substr($hex, 0, 2));
    $g = hexdec(substr($hex, 2, 2));
    $b = hexdec(substr($hex, 4, 2));
    // Adjust number of steps and keep it inside 0 to 255.
    $r = max(0, min(255, $r + $steps));
    $g = max(0, min(255, $g + $steps));
    $b = max(0, min(255, $b + $steps));
    $r_hex = str_pad(dechex($r), 2, '0', STR_PAD_LEFT);
    $g_hex = str_pad(dechex($g), 2, '0', STR_PAD_LEFT);
    $b_hex = str_pad(dechex($b), 2, '0', STR_PAD_LEFT);
    return '#' . $r_hex . $g_hex . $b_hex;
}
?>
<?php 
/* Ajax Load Moe posts */
add_action('wp_ajax_nopriv_elixar_more_post_ajax', 'elixar_more_post_ajax');
add_action('wp_ajax_elixar_more_post_ajax', 'elixar_more_post_ajax');
if ( !function_exists( 'elixar_more_post_ajax' ) ) {
    function elixar_more_post_ajax(){
        $ppp     = (isset($_POST['ppp'])) ? $_POST['ppp'] : 3;
        $offset  = (isset($_POST['offset'])) ? $_POST['offset'] : 0;
		$elixar_more_text = get_theme_mod( 'elixar_blog_more_text', esc_html__('Read More', 'elixar' ));
        $cat = absint( get_theme_mod( 'elixar_blog_cat' ) );
        $args = array(
            'post_type'      => 'post',
            'posts_per_page' => $ppp,
            'category__in'   => $cat,
            'offset'         => $offset,
        );
        $loop = new WP_Query($args);
        $i=1;
        if ($loop -> have_posts()) :
            while ($loop -> have_posts()) :
                $loop -> the_post(); ?>
               <div class="col-sm-4 animated fadeInDownShort grid-item">
						<div class="home-blog-latest-item shadow-around" data-sr="enter left scale up 20% delay once">
							<div id="post-<?php the_ID(); ?>" <?php post_class('e-blog-grid-block-details'); ?>>
								<?php if ( has_post_thumbnail() ) { ?>
								<a href="<?php the_permalink(); ?>" class="text-margin display-block">
									<?php the_post_thumbnail( $elixar_imageSize, $elixar_img_class ); ?>
								</a>
								<?php } ?>
								<div class="e-blog-grid-block-text">
									<?php the_title( sprintf( '<h3 class="title-lg text-uppercase text-margin"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h3>' ); ?>
									<ul class="list-unstructured list-inline text-margin e-post-meta-part">
										<?php elixar_posted_on(); ?>
										<li><i class="fas fa-comment"></i> <?php esc_url(comments_popup_link( esc_html__( 'No Comments', 'elixar' ), esc_html__( '1 Comment', 'elixar' ), esc_html__( '% Comments', 'elixar' ) ) ); ?></li>
									</ul>
									<?php the_excerpt(); ?>
								</div>
							</div>
							<?php $elixar_count1 = strlen(get_the_content());
							$elixar_count2 = strlen(get_the_excerpt());
							if( $elixar_count1>$elixar_count2 ) { ?>
								<div class="elixar-read-more">
									<a href="<?php the_permalink(); ?>"><?php if ( $elixar_more_text != '' ) echo esc_html( $elixar_more_text ); ?></a>
									<p><?php esc_url( comments_popup_link( esc_html__( 'No Comments', 'elixar' ), esc_html__( '1 Comment', 'elixar' ), esc_html__( '% Comments', 'elixar' ) ) ); ?></p>
								</div>
							<?php } ?>
							</div>
					</div>
                <?php $i++;
            endwhile;
        endif;
        wp_reset_postdata();
    }
}
?>
<?php
if ( ! function_exists( 'elixar_woocommerce_status' ) ) :
	/**
	 * Return WooCommerce status.
	 *
	 * @since 1.0.0
	 *
	 * @return bool Active status.
	 */
	function elixar_woocommerce_status() {
		return class_exists( 'WooCommerce' );
	}
endif;
?>