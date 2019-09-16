<?php
/**
 * Elixar Theme Customizer.
 *
 * @package Elixar
 */
/**
 * Add postMessage support for site title and description for the Theme Customizer.
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function elixar_customize_register($wp_customize)
{
    // Load custom controls.
    require get_template_directory() . '/inc/customizer/customizer-controls.php';
    /**
     * Hook to add other customize
     */
    do_action('elixar_customize_before_register', $wp_customize);
    $pages           = get_pages();
    $option_pages    = array();
    $option_pages[0] = esc_html__('Select page', 'elixar');
    foreach ($pages as $p) {
        $option_pages[$p->ID] = $p->post_title;
    }
    $users = get_users(array(
        'orderby' => 'display_name',
        'order'   => 'ASC',
        'number'  => '',
    ));
    $option_users[0] = esc_html__('Select member', 'elixar');
    foreach ($users as $user) {
        $option_users[$user->ID] = $user->display_name;
    }
    // Move background color setting alongside background image.
    $wp_customize->get_control('background_color')->section  = 'background_image';
    $wp_customize->get_control('background_color')->priority = 20;
    $wp_customize->get_control('header_textcolor')->section  = 'header_image';
    $wp_customize->get_control('header_textcolor')->priority = 11;
    // Change control title and description.
    $wp_customize->get_section('background_image')->title       = esc_html__('Site Layout & Background', 'elixar');
    $wp_customize->get_section('background_image')->description = esc_html__('Site Layout & Background Options', 'elixar');
    // Change header image section title & priority.
    $wp_customize->get_section('header_image')->title = esc_html__('Header Options', 'elixar');
    // move general control into general panel
    $wp_customize->get_section('static_front_page')->panel = 'elixar_general_settings_panel';
    $wp_customize->get_section('title_tagline')->panel     = 'elixar_general_settings_panel';
    $wp_customize->get_section('background_image')->panel  = 'elixar_general_settings_panel';
    $wp_customize->get_section('header_image')->panel      = 'elixar_general_settings_panel';
    // Selective refresh.
    $wp_customize->get_setting('blogname')->transport         = 'postMessage';
    $wp_customize->get_setting('blogdescription')->transport  = 'postMessage';
    $wp_customize->get_setting('header_textcolor')->transport = 'postMessage';
    /*------------------------------------------------------------------------*/
    /*  STATIC FRONT PAGE
    /*------------------------------------------------------------------------*/
    $wp_customize->add_section('static_front_page', array(
        'title'       => esc_html__('Static Front Page', 'elixar'),
        'panel'       => 'elixar_general_settings_panel',
        'description' => esc_html__('Your theme supports a static front page.', 'elixar'),
    ));
    /*------------------------------------------------------------------------*/
    /*  TITLE AND TAGLINE SETTINGS
    /*------------------------------------------------------------------------*/
    $wp_customize->add_section('title_tagline', array(
        'title'    => esc_html__('Site Logo/Title/Tagline', 'elixar'),
        'panel'    => 'elixar_general_settings_panel',
        'priority' => 5,
    ));
    /*------------------------------------------------------------------------*/
    /*  Site Options
    /*------------------------------------------------------------------------*/
    $wp_customize->add_panel('elixar_general_settings_panel',
        array(
            'priority'       => 5,
            'capability'     => 'edit_theme_options',
            'theme_supports' => '',
            'title'          => esc_html__('General Settings', 'elixar'),
            'description'    => '',
        )
    );
    /* Logo Settings
    ----------------------------------------------------------------------*/
    // Logo Layout (Left/Right)
    $wp_customize->add_setting('elixar_logo_layout',
        array(
            'sanitize_callback' => 'elixar_sanitize_select',
            'default'           => 'left',
            'transport'         => 'postMessage',
            'active_callback'   => 'elixar_showon_frontpage',
        )
    );
    $wp_customize->add_control('elixar_logo_layout',
        array(
            'type'    => 'select',
            'label'   => esc_html__('Logo Layout', 'elixar'),
            'section' => 'title_tagline',
            'choices' => array(
                'left'  => esc_html__('Left', 'elixar'),
                'right' => esc_html__('Right', 'elixar'),
            ),
        )
    );
    /* Global Settings
    ----------------------------------------------------------------------*/
    $wp_customize->add_section('elixar_global_settings',
        array(
            'priority'    => 7,
            'title'       => esc_html__('Global Settings', 'elixar'),
            'description' => '',
            'panel'       => 'elixar_general_settings_panel',
        )
    );
    // Disable Back to Top Button
    $wp_customize->add_setting('elixar_back_top_enable',
        array(
            'sanitize_callback' => 'elixar_sanitize_checkbox',
            'default'           => 1,
        )
    );
    $wp_customize->add_control('elixar_back_top_enable',
        array(
            'type'        => 'checkbox',
            'label'       => esc_html__('Show footer back to top?', 'elixar'),
            'section'     => 'elixar_global_settings',
            'description' => esc_html__('Check this box to show footer back to top button.', 'elixar'),
        )
    );
    // Disable Back to Top Button
    $wp_customize->add_setting('elixar_animation_enable',
        array(
            'sanitize_callback' => 'elixar_sanitize_checkbox',
            'default'           => 1,
        )
    );
    $wp_customize->add_control('elixar_animation_enable',
        array(
            'type'        => 'checkbox',
            'label'       => esc_html__('Enable All Animation?', 'elixar'),
            'section'     => 'elixar_global_settings',
            'description' => esc_html__('Check this box to enable animation effect on custom Home page', 'elixar'),
        )
    );
    $wp_customize->add_setting('elixar_is_rtl_enable',
        array(
            'sanitize_callback' => 'elixar_sanitize_checkbox',
            'default'           => 0,
        )
    );
    $wp_customize->add_control('elixar_is_rtl_enable',
        array(
            'type'        => 'checkbox',
            'label'       => esc_html__('Change Site Direction?', 'elixar'),
            'section'     => 'elixar_global_settings',
            'description' => esc_html__('Check this box to enable site direction ltr to rtl.', 'elixar'),
        )
    );
    // Enqueue Minified Assets(Js and CSS)
    $wp_customize->add_setting('elixar_minified_assests',
        array(
            'sanitize_callback' => 'elixar_sanitize_checkbox',
            'default'           => 0,
        )
    );
    $wp_customize->add_control('elixar_minified_assests',
        array(
            'type'        => 'checkbox',
            'label'       => esc_html__('Enqueue Minified Assets(Js and CSS)', 'elixar'),
            'section'     => 'elixar_global_settings',
            'description' => esc_html__('Check this box to enable minified assets like JS & CSS', 'elixar'),
        )
    );
    /* Topbar Settings
    ----------------------------------------------------------------------*/
    $wp_customize->add_section('elixar_topbar_settings',
        array(
            'priority'    => 9,
            'title'       => esc_html__('Top Bar Options', 'elixar'),
            'description' => '',
            'panel'       => 'elixar_general_settings_panel',
        )
    );
    // Disable Top Bar
    $wp_customize->add_setting('elixar_topbar_enable',
        array(
            'sanitize_callback' => 'elixar_sanitize_checkbox',
            'default'           => 0,
        )
    );
    $wp_customize->add_control('elixar_topbar_enable',
        array(
            'type'        => 'checkbox',
            'label'       => esc_html__('Enable Topbar?', 'elixar'),
            'section'     => 'elixar_topbar_settings',
            'description' => esc_html__('Check this box to enable topbar.', 'elixar'),
        )
    );
    // Enable Topbar
    $wp_customize->add_setting('elixar_topbar_menu_enable',
        array(
            'sanitize_callback' => 'elixar_sanitize_checkbox',
            'default'           => 1,
        )
    );
    $wp_customize->add_control('elixar_topbar_menu_enable',
        array(
            'type'        => 'checkbox',
            'label'       => esc_html__('Enable Topbar Menu?', 'elixar'),
            'section'     => 'elixar_topbar_settings',
            'description' => esc_html__('Check this box to enable the topbar menu.', 'elixar'),
        )
    );
    // Topbar BG Color
    $wp_customize->add_setting('elixar_topbar_bg_color',
        array(
            'sanitize_callback' => 'sanitize_hex_color',
            'default'           => null,
            'transport'         => 'postMessage',
        )
    );
    $wp_customize->add_control(new WP_Customize_Color_Control(
        $wp_customize,
        'elixar_topbar_bg_color',
        array(
            'label'   => esc_html__('Top Bar Background Color', 'elixar'),
            'section' => 'elixar_topbar_settings',
        )
    )
    );
    // Topbar Text Color
    $wp_customize->add_setting('elixar_topbar_text_color',
        array(
            'sanitize_callback' => 'sanitize_hex_color',
            'default'           => null,
            'transport'         => 'postMessage',
        )
    );
    $wp_customize->add_control(new WP_Customize_Color_Control(
        $wp_customize,
        'elixar_topbar_text_color',
        array(
            'label'   => esc_html__('Top Bar Text Color', 'elixar'),
            'section' => 'elixar_topbar_settings',
        )
    )
    );
    /* Header Settings
    ----------------------------------------------------------------------*/
    $wp_customize->add_section('elixar_header_settings',
        array(
            'priority'    => 15,
            'title'       => esc_html__('Header', 'elixar'),
            'description' => '',
            'panel'       => 'elixar_general_settings_panel',
        )
    );
    // Header Contact Disable
    $wp_customize->add_setting('elixar_header_contact_enable',
        array(
            'sanitize_callback' => 'elixar_sanitize_checkbox',
            'default'           => 0,
        )
    );
    $wp_customize->add_control('elixar_header_contact_enable',
        array(
            'type'        => 'checkbox',
            'label'       => esc_html__('Enable Header Contact?', 'elixar'),
            'section'     => 'header_image',
            'description' => esc_html__('Check this box to enable the header contact info.', 'elixar'),
        )
    );
    // Header Cart Enable
    $wp_customize->add_setting('elixar_show_cartcount',
        array(
            'sanitize_callback' => 'elixar_sanitize_checkbox',
            'default'           => 0,
        )
    );
    $wp_customize->add_control('elixar_show_cartcount',
        array(
            'type'        => 'checkbox',
            'label'       => esc_html__('Enable Shopping Cart?', 'elixar'),
            'section'     => 'header_image',
            'description' => esc_html__('Check this box to enable shopping cart box from header.', 'elixar'),
        )
    );
    // Contact Phone
    $wp_customize->add_setting('elixar_header_contact_phone',
        array(
            'sanitize_callback' => 'sanitize_text_field',
            'default'           => esc_html__('Phone', 'elixar'),
            'transport'         => 'postMessage',
        )
    );
    $wp_customize->add_control('elixar_header_contact_phone',
        array(
            'label'   => esc_html__('Contact Title', 'elixar'),
            'section' => 'header_image',
        )
    );
    // Contact Phone Icon
    $wp_customize->add_setting('elixar_header_contact_phone_icon',
        array(
            'sanitize_callback' => 'elixar_sanitize_text',
            'default'           => 'fas fa-phone',
            'transport'         => 'postMessage',
        )
    );
    $wp_customize->add_control(new Elixar_Fontawesome_Icon_Chooser(
        $wp_customize,
        'elixar_header_contact_phone_icon',
        array(
            'label'   => esc_html__('Contact Icon', 'elixar'),
            'section' => 'header_image',
        )
    ));
    // Contact Phone info
    $wp_customize->add_setting('elixar_header_contact_phone_info',
        array(
            'sanitize_callback' => 'sanitize_text_field',
            'default'           => '+099-99999',
            'transport'         => 'postMessage',
        )
    );
    $wp_customize->add_control('elixar_header_contact_phone_info',
        array(
            'label'       => esc_html__('Contact Number*', 'elixar'),
            'section'     => 'header_image',
            'description' => esc_html__('Write your company/business contact number here.', 'elixar'),
        )
    );
    // Contact Email
    $wp_customize->add_setting('elixar_header_contact_email',
        array(
            'sanitize_callback' => 'sanitize_text_field',
            'default'           => esc_html__('Email', 'elixar'),
            'transport'         => 'postMessage',
        )
    );
    $wp_customize->add_control('elixar_header_contact_email',
        array(
            'label'   => esc_html__('Email Title', 'elixar'),
            'section' => 'header_image',
        )
    );
    // Contact Email Icon
    $wp_customize->add_setting('elixar_header_contact_email_icon',
        array(
            'sanitize_callback' => 'elixar_sanitize_text',
            'default'           => 'fas fa-envelope',
            'transport'         => 'postMessage',
        )
    );
    $wp_customize->add_control(new Elixar_Fontawesome_Icon_Chooser(
        $wp_customize,
        'elixar_header_contact_email_icon',
        array(
            'label'   => esc_html__('Email Icon', 'elixar'),
            'section' => 'header_image',
        )
    ));
    // Contact Email info
    $wp_customize->add_setting('elixar_header_contact_email_info',
        array(
            'sanitize_callback' => 'sanitize_email',
            'default'           => 'example@example.com',
            'transport'         => 'postMessage',
        )
    );
    $wp_customize->add_control('elixar_header_contact_email_info',
        array(
            'label'       => esc_html__('Email*', 'elixar'),
            'section'     => 'header_image',
            'description' => esc_html__('Write your company/business email address here.', 'elixar'),
        )
    );
    // Contact Address
    $wp_customize->add_setting('elixar_header_contact_address',
        array(
            'sanitize_callback' => 'sanitize_text_field',
            'default'           => esc_html__('Address', 'elixar'),
            'transport'         => 'postMessage',
        )
    );
    $wp_customize->add_control('elixar_header_contact_address',
        array(
            'label'   => esc_html__('Address Title', 'elixar'),
            'section' => 'header_image',
        )
    );
    // Contact Address Icon
    $wp_customize->add_setting('elixar_header_contact_address_icon',
        array(
            'sanitize_callback' => 'elixar_sanitize_text',
            'default'           => 'fas fa-map',
            'transport'         => 'postMessage',
        )
    );
    $wp_customize->add_control(new Elixar_Fontawesome_Icon_Chooser(
        $wp_customize,
        'elixar_header_contact_address_icon',
        array(
            'label'   => esc_html__('Address Icon', 'elixar'),
            'section' => 'header_image',
        )
    ));
    // Contact Address info
    $wp_customize->add_setting('elixar_header_contact_address_info',
        array(
            'sanitize_callback' => 'sanitize_text_field',
            'default'           => esc_html__('123 Main Street India', 'elixar'),
            'transport'         => 'postMessage',
        )
    );
    $wp_customize->add_control('elixar_header_contact_address_info',
        array(
            'label'       => esc_html__('Address*', 'elixar'),
            'section'     => 'header_image',
            'description' => esc_html__('Write your company/business address here.', 'elixar'),
        )
    );
    // Header BG Color
    $wp_customize->add_setting('elixar_header_bg_color',
        array(
            'sanitize_callback'    => 'sanitize_hex_color_no_hash',
            'sanitize_js_callback' => 'maybe_hash_hex_color',
            'default'              => '',
            'transport'            => 'postMessage',
        ));
    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'elixar_header_bg_color',
        array(
            'label'       => esc_html__('Header Background Color', 'elixar'),
            'section'     => 'header_image',
            'description' => '',
        )
    ));
    // Site Title Color
    $wp_customize->add_setting('elixar_logo_text_color',
        array(
            'sanitize_callback'    => 'sanitize_hex_color_no_hash',
            'sanitize_js_callback' => 'maybe_hash_hex_color',
            'default'              => '',
            'transport'            => 'postMessage',
        ));
    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'elixar_logo_text_color',
        array(
            'label'       => esc_html__('Site Title Color', 'elixar'),
            'section'     => 'header_image',
            'description' => esc_html__('Only set if you don\'t use an image logo.', 'elixar'),
        )
    ));
    $wp_customize->add_setting('elixar_tagline_text_color',
        array(
            'sanitize_callback'    => 'sanitize_hex_color_no_hash',
            'sanitize_js_callback' => 'maybe_hash_hex_color',
            'default'              => '',
            'transport'            => 'postMessage',
        ));
    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'elixar_tagline_text_color',
        array(
            'label'       => esc_html__('Site Tagline Color', 'elixar'),
            'section'     => 'header_image',
            'description' => esc_html__('Only set if display site tagline.', 'elixar'),
        )
    ));
    // Header Link Color
    $wp_customize->add_setting('elixar_header_link_color',
        array(
            'sanitize_callback'    => 'sanitize_hex_color_no_hash',
            'sanitize_js_callback' => 'maybe_hash_hex_color',
            'default'              => '',
            'transport'            => 'postMessage',
        ));
    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'elixar_header_link_color',
        array(
            'label'       => esc_html__('Header Link Color', 'elixar'),
            'section'     => 'header_image',
            'description' => '',
        )
    ));
    // Header Menu Hover Color
    $wp_customize->add_setting('elixar_header_link_hover_color',
        array(
            'sanitize_callback'    => 'sanitize_hex_color_no_hash',
            'sanitize_js_callback' => 'maybe_hash_hex_color',
            'default'              => '',
            'transport'            => 'postMessage',
        ));
    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'elixar_header_link_hover_color',
        array(
            'label'       => esc_html__('Header Link Hover Color', 'elixar'),
            'section'     => 'header_image',
            'description' => '',
        )
    ));
    /* Navigation Settings
    ----------------------------------------------------------------------*/
    $wp_customize->add_section('elixar_navigation_settings',
        array(
            'priority'    => 25,
            'title'       => esc_html__('Navigation Options', 'elixar'),
            'description' => '',
            'panel'       => 'elixar_general_settings_panel',
        )
    );
    // Enable Sticky Menu Bar
    $wp_customize->add_setting('elixar_sticky_menu_bar_enable',
        array(
            'sanitize_callback' => 'elixar_sanitize_checkbox',
            'default'           => 0,
        )
    );
    $wp_customize->add_control('elixar_sticky_menu_bar_enable',
        array(
            'type'        => 'checkbox',
            'label'       => esc_html__('Enable Sticky Menu Bar?', 'elixar'),
            'section'     => 'elixar_navigation_settings',
            'description' => esc_html__('Check this box to enable sticky menu bar when scroll.', 'elixar'),
        )
    );
    // Menu Bar Search Enable
    $wp_customize->add_setting('elixar_show_search_in_header',
        array(
            'sanitize_callback' => 'elixar_sanitize_checkbox',
            'default'           => 1,
        )
    );
    $wp_customize->add_control('elixar_show_search_in_header',
        array(
            'type'        => 'checkbox',
            'label'       => esc_html__('Enable Search Box*?', 'elixar'),
            'section'     => 'elixar_navigation_settings',
            'description' => esc_html__('Check this box to enable search box from header.', 'elixar'),
        )
    );
    // Menu Bar Position (Top/Bottom)
    $wp_customize->add_setting('elixar_menu_bar_position',
        array(
            'sanitize_callback' => 'elixar_sanitize_select',
            'default'           => 'top',
            'active_callback'   => 'elixar_showon_frontpage',
        )
    );
    $wp_customize->add_control('elixar_menu_bar_position',
        array(
            'type'    => 'select',
            'label'   => esc_html__('Menu Bar Position', 'elixar'),
            'section' => 'elixar_navigation_settings',
            'choices' => array(
                'top'        => esc_html__('Top', 'elixar'),
                'below_hero' => esc_html__('Below Hero Section', 'elixar'),
            ),
        )
    );
    $wp_customize->add_setting('elixar_menu_bar_padding',
        array(
            'sanitize_callback' => 'absint',
            'default'           => '',
            'transport'         => 'postMessage',
        )
    );
    $wp_customize->add_control('elixar_menu_bar_padding',
        array(
            'label'       => esc_html__('Menu Bar Padding', 'elixar'),
            'description' => esc_html__('Padding top and bottom for Navigation bar (pixels).', 'elixar'),
            'section'     => 'elixar_navigation_settings',
        )
    );
    // Footer BG Color
    $wp_customize->add_setting('elixar_menubar_bg_color', array(
        'sanitize_callback'    => 'sanitize_hex_color_no_hash',
        'sanitize_js_callback' => 'maybe_hash_hex_color',
        'default'              => '#d14f30',
        'transport'            => 'postMessage',
    ));
    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'elixar_menubar_bg_color',
        array(
            'label'       => esc_html__('Menu Bar Background Color', 'elixar'),
            'section'     => 'elixar_navigation_settings',
            'description' => '',
        )
    ));
    $wp_customize->add_setting('elixar_menu_item_color', array(
        'sanitize_callback'    => 'sanitize_hex_color',
        'sanitize_js_callback' => 'maybe_hash_hex_color',
        'default'              => '',
        'transport'            => 'postMessage',
    ));
    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'elixar_menu_item_color',
        array(
            'label'       => esc_html__('Menu Link Color', 'elixar'),
            'section'     => 'elixar_navigation_settings',
            'description' => '',
        )
    ));
    // Header Menu Hover Color
    $wp_customize->add_setting('elixar_menu_item_hover_color',
        array(
            'sanitize_callback'    => 'sanitize_hex_color_no_hash',
            'sanitize_js_callback' => 'maybe_hash_hex_color',
            'default'              => '',
            'transport'            => 'postMessage',
        ));
    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'elixar_menu_item_hover_color',
        array(
            'label'       => esc_html__('Menu Link Hover/Active Color', 'elixar'),
            'section'     => 'elixar_navigation_settings',
            'description' => '',
        )
    ));
    /* Site Layout & Background Settings
    ----------------------------------------------------------------------*/
    // Site Layout (Full/Boxed)
    $wp_customize->add_setting('elixar_site_layout',
        array(
            'sanitize_callback' => 'elixar_sanitize_select',
            'default'           => '',
            'transport'         => 'postMessage',
            'active_callback'   => 'elixar_showon_frontpage',
        )
    );
    $wp_customize->add_control('elixar_site_layout',
        array(
            'type'    => 'select',
            'label'   => esc_html__('Site Layout', 'elixar'),
            'section' => 'background_image',
            'choices' => array(
                ''      => esc_html__('Full', 'elixar'),
                'boxed' => esc_html__('Boxed', 'elixar'),
            ),
        )
    );
    /* Page & Breadcrumb Settings
    ----------------------------------------------------------------------*/
    $wp_customize->add_section('elixar_breadcrumb_settings',
        array(
            'priority'    => null,
            'title'       => esc_html__('Page Options', 'elixar'),
            'description' => '',
            'panel'       => 'elixar_general_settings_panel',
        )
    );
    // Page Sidebar settings
    $wp_customize->add_setting('elixar_page_layout',
        array(
            'sanitize_callback' => 'elixar_sanitize_select',
            'default'           => 'rightsidebar',
        )
    );
    $wp_customize->add_control('elixar_page_layout',
        array(
            'type'        => 'select',
            'label'       => esc_html__('Page Layout', 'elixar'),
            'description' => esc_html__('Page Layout, this will be apply for all pages, exclude home page and custom page templates.', 'elixar'),
            'section'     => 'elixar_breadcrumb_settings',
            'choices'     => array(
                'rightsidebar' => esc_html__('Right sidebar', 'elixar'),
                'leftsidebar'  => esc_html__('Left sidebar', 'elixar'),
                'fullwidth'    => esc_html__('No sidebar', 'elixar'),
            ),
        )
    );
    // Disable the page title bar
    $wp_customize->add_setting('elixar_page_title_bar_enable',
        array(
            'sanitize_callback' => 'elixar_sanitize_checkbox',
            'default'           => 1,
        )
    );
    $wp_customize->add_control('elixar_page_title_bar_enable',
        array(
            'type'        => 'checkbox',
            'label'       => esc_html__('Enable Page Title bar?', 'elixar'),
            'section'     => 'elixar_breadcrumb_settings',
            'description' => esc_html__('Check this box to enable the page title bar on all pages.', 'elixar'),
        )
    );
    $wp_customize->add_setting('elixar_page_title_type',
        array(
            'sanitize_callback' => 'elixar_sanitize_select',
            'default'           => 'allow_both',
            'transport'         => 'postMessage',
        )
    );
    $wp_customize->add_control('elixar_page_title_type',
        array(
            'label'   => esc_html__('Page Title Type', 'elixar'),
            'section' => 'elixar_breadcrumb_settings',
            'type'    => 'select',
            'choices' => array(
                'allow_both'  => esc_html__('Title Bar With Breadcrumbs', 'elixar'),
                'allow_title' => esc_html__('Title Bar Only', 'elixar'),
            ),
        )
    );
    $wp_customize->add_setting('elixar_page_padding_top',
        array(
            'sanitize_callback' => 'absint',
            'default'           => 20,
            'transport'         => 'postMessage',
        )
    );
    $wp_customize->add_control('elixar_page_padding_top',
        array(
            'label'       => esc_html__('Padding Top', 'elixar'),
            'description' => esc_html__('The page cover padding top in percent (px).', 'elixar'),
            'section'     => 'elixar_breadcrumb_settings',
        )
    );
    $wp_customize->add_setting('elixar_page_padding_bottom',
        array(
            'sanitize_callback' => 'absint',
            'default'           => 20,
            'transport'         => 'postMessage',
        )
    );
    $wp_customize->add_control('elixar_page_padding_bottom',
        array(
            'label'       => esc_html__('Padding Bottom', 'elixar'),
            'description' => esc_html__('The page cover padding bottom in percent (px).', 'elixar'),
            'section'     => 'elixar_breadcrumb_settings',
        )
    );
    // Breadcrumb BG image
    $wp_customize->add_setting('elixar_page_title_bg_image',
        array(
            'sanitize_callback' => 'esc_url_raw',
            'default'           => '',
            'transport'         => 'postMessage',
        )
    );
    $wp_customize->add_control(new WP_Customize_Image_Control(
        $wp_customize,
        'elixar_page_title_bg_image',
        array(
            'label'   => esc_html__('Background image', 'elixar'),
            'section' => 'elixar_breadcrumb_settings',
        )
    ));
    // Overlay color
    $wp_customize->add_setting('elixar_page_overlay_color',
        array(
            'sanitize_callback' => 'elixar_sanitize_color_alpha',
            'default'           => 'rgba(247, 247, 247, .4)',
            'transport'         => 'postMessage',
        )
    );
    $wp_customize->add_control(new Elixar_Alpha_Color_Control(
        $wp_customize,
        'elixar_page_overlay_color',
        array(
            'label'   => esc_html__('Background Overlay Color', 'elixar'),
            'section' => 'elixar_breadcrumb_settings',
        )
    )
    );
    // Overlay Text Color
    $wp_customize->add_setting('elixar_page_title_color',
        array(
            'sanitize_callback' => 'sanitize_hex_color',
            'default'           => null,
            'transport'         => 'postMessage',
        )
    );
    $wp_customize->add_control(new WP_Customize_Color_Control(
        $wp_customize,
        'elixar_page_title_color',
        array(
            'label'   => esc_html__('Text Color', 'elixar'),
            'section' => 'elixar_breadcrumb_settings',
        )
    )
    );
    $wp_customize->add_setting('elixar_page_section',
        array(
            'sanitize_callback' => 'elixar_sanitize_array_string',
            'default'           => array(),
            'transport'         => 'refresh',
        )
    );
    $wp_customize->add_control(new Elixar_Dropdown_Multiple_Chooser(
        $wp_customize,
        'elixar_page_section',
        array(
            'label'       => esc_html__('Select Page Section', 'elixar'),
            'placeholder' => esc_html__('Select home sections to display on page', 'elixar'),
            'section'     => 'elixar_breadcrumb_settings',
            'choices'     => array(
                'slider'  => esc_html__('Hero', 'elixar'),
                'service' => esc_html__('Service', 'elixar'),
                'blog'    => esc_html__('Blog', 'elixar'),
                'callout' => esc_html__('Call Out', 'elixar'),
                'extra'   => esc_html__('Extra', 'elixar'),
            ),
        )
    )
    );
    $wp_customize->add_setting('elixar_page_section_position',
        array(
            'sanitize_callback' => 'elixar_sanitize_select',
            'default'           => 'top',
            'transport'         => 'refresh',
        )
    );
    $wp_customize->add_control('elixar_page_section_position',
        array(
            'label'   => esc_html__('Page Section Position', 'elixar'),
            'section' => 'elixar_breadcrumb_settings',
            'type'    => 'select',
            'choices' => array(
                'top'    => esc_html__('Top', 'elixar'),
                'bottom' => esc_html__('Bottom', 'elixar'),
            ),
        )
    );
    /* Single Post Settings
    ----------------------------------------------------------------------*/
    $wp_customize->add_section('elixar_post_settings',
        array(
            'priority'    => null,
            'title'       => esc_html__('Post Options', 'elixar'),
            'description' => '',
            'panel'       => 'elixar_general_settings_panel',
        )
    );
    // Post Sidebar settings
    $wp_customize->add_setting('elixar_blog_temp_layout',
        array(
            'sanitize_callback' => 'elixar_sanitize_select',
            'default'           => 'rightsidebar',
        )
    );
    $wp_customize->add_control('elixar_blog_temp_layout',
        array(
            'type'        => 'select',
            'label'       => esc_html__('Blog Template Layout', 'elixar'),
            'description' => esc_html__('This will be apply for blog template layout', 'elixar'),
            'section'     => 'elixar_post_settings',
            'choices'     => array(
                'rightsidebar' => esc_html__('Right sidebar', 'elixar'),
                'leftsidebar'  => esc_html__('Left sidebar', 'elixar'),
                'fullwidth'    => esc_html__('No sidebar', 'elixar'),
            ),
        )
    );
    $wp_customize->add_setting('elixar_post_layout',
        array(
            'sanitize_callback' => 'elixar_sanitize_select',
            'default'           => 'rightsidebar',
        )
    );
    $wp_customize->add_control('elixar_post_layout',
        array(
            'type'        => 'select',
            'label'       => esc_html__('Single Post Layout', 'elixar'),
            'description' => esc_html__('Post Layout, this will be apply for all post.', 'elixar'),
            'section'     => 'elixar_post_settings',
            'choices'     => array(
                'rightsidebar' => esc_html__('Right sidebar', 'elixar'),
                'leftsidebar'  => esc_html__('Left sidebar', 'elixar'),
                'fullwidth'    => esc_html__('No sidebar', 'elixar'),
            ),
        )
    );
    // Related Post Title
    $wp_customize->add_setting('elixar_related_post_title',
        array(
            'sanitize_callback' => 'sanitize_text_field',
            'default'           => esc_html__('You might also like:-', 'elixar'),
            'transport'         => 'postMessage',
        )
    );
    $wp_customize->add_control('elixar_related_post_title',
        array(
            'label'       => esc_html__('Related Post Title', 'elixar'),
            'description' => esc_html__('This title will be shown on related blog posts.', 'elixar'),
            'section'     => 'elixar_post_settings',
            'description' => '',
        )
    );
    $wp_customize->add_setting('elixar_single_post_thumb',
        array(
            'sanitize_callback' => 'elixar_sanitize_checkbox',
            'default'           => 1,
        )
    );
    $wp_customize->add_control('elixar_single_post_thumb',
        array(
            'type'        => 'checkbox',
            'label'       => esc_html__('Enable Single Post Thumbnail', 'elixar'),
            'section'     => 'elixar_post_settings',
            'description' => esc_html__('Check this box to enable post thumbnail on single post.', 'elixar'),
        )
    );
    $wp_customize->add_setting('elixar_single_post_meta',
        array(
            'sanitize_callback' => 'elixar_sanitize_checkbox',
            'default'           => 1,
        )
    );
    $wp_customize->add_control('elixar_single_post_meta',
        array(
            'type'        => 'checkbox',
            'label'       => esc_html__('Enable Single Post Meta', 'elixar'),
            'section'     => 'elixar_post_settings',
            'description' => esc_html__('Check this box to enable single post meta such as post date, author, category, comment etc.', 'elixar'),
        )
    );
    $wp_customize->add_setting('elixar_single_post_title',
        array(
            'sanitize_callback' => 'elixar_sanitize_checkbox',
            'default'           => 0,
        )
    );
    $wp_customize->add_control('elixar_single_post_title',
        array(
            'type'        => 'checkbox',
            'label'       => esc_html__('Enable Single Post Title', 'elixar'),
            'section'     => 'elixar_post_settings',
            'description' => esc_html__('Check this box to enable title on single post.', 'elixar'),
        )
    );
    /* Social Settings
    ----------------------------------------------------------------------*/
    $wp_customize->add_section('elixar_social_settings',
        array(
            'title'       => esc_html__('Social Options', 'elixar'),
            'description' => '',
            'panel'       => 'elixar_general_settings_panel',
        )
    );
    // Top Social Enable
    $wp_customize->add_setting('elixar_social_top_enable',
        array(
            'sanitize_callback' => 'elixar_sanitize_checkbox',
            'default'           => 1,
        )
    );
    $wp_customize->add_control('elixar_social_top_enable',
        array(
            'type'        => 'checkbox',
            'label'       => esc_html__('Enable Top Bar Social?', 'elixar'),
            'section'     => 'elixar_social_settings',
            'description' => esc_html__('Check this box to enable topbar social section.', 'elixar'),
        )
    );
    // Footer Social Disable
    $wp_customize->add_setting('elixar_social_footer_enable',
        array(
            'sanitize_callback' => 'elixar_sanitize_checkbox',
            'default'           => 0,
        )
    );
    $wp_customize->add_control('elixar_social_footer_enable',
        array(
            'type'        => 'checkbox',
            'label'       => esc_html__('Enable Footer Social?', 'elixar'),
            'section'     => 'elixar_social_settings',
            'description' => esc_html__('Check this box to enable footer social section.', 'elixar'),
        )
    );
    $wp_customize->add_setting('elixar_social_footer_guide',
        array(
            'sanitize_callback' => 'elixar_sanitize_text',
        )
    );
    $wp_customize->add_control(new Elixar_Misc_Control($wp_customize, 'elixar_social_footer_guide',
        array(
            'section'     => 'elixar_social_settings',
            'type'        => 'custom_message',
            'description' => esc_html__('These social profiles setting below will display at the topbar & footer of your site.', 'elixar'),
        )
    ));
    // Footer Social Title
    $wp_customize->add_setting('elixar_social_footer_title',
        array(
            'sanitize_callback' => 'sanitize_text_field',
            'default'           => esc_html__('Connect With Us On Social', 'elixar'),
            'transport'         => 'postMessage',
        )
    );
    $wp_customize->add_control('elixar_social_footer_title',
        array(
            'label'       => esc_html__('Social Footer Title', 'elixar'),
            'section'     => 'elixar_social_settings',
            'description' => '',
        )
    );
    // Social Facebook URL
    $wp_customize->add_setting('elixar_fb_url',
        array(
            'sanitize_callback' => 'esc_url_raw',
            'default'           => '',
            'transport'         => 'postMessage',
        )
    );
    $wp_customize->add_control('elixar_fb_url',
        array(
            'label'       => esc_html__('Facebook URL', 'elixar'),
            'section'     => 'elixar_social_settings',
            'description' => '',
        )
    );
    // Social Twitter URL
    $wp_customize->add_setting('elixar_twitter_url',
        array(
            'sanitize_callback' => 'esc_url_raw',
            'default'           => '',
            'transport'         => 'postMessage',
        )
    );
    $wp_customize->add_control('elixar_twitter_url',
        array(
            'label'       => esc_html__('Twitter URL', 'elixar'),
            'section'     => 'elixar_social_settings',
            'description' => '',
        )
    );
    // Social Google+ URL
    $wp_customize->add_setting('elixar_gplus_url',
        array(
            'sanitize_callback' => 'esc_url_raw',
            'default'           => '',
            'transport'         => 'postMessage',
        )
    );
    $wp_customize->add_control('elixar_gplus_url',
        array(
            'label'       => esc_html__('Google+ URL', 'elixar'),
            'section'     => 'elixar_social_settings',
            'description' => '',
        )
    );
    // Social Instagram URL
    $wp_customize->add_setting('elixar_instagram_url',
        array(
            'sanitize_callback' => 'esc_url_raw',
            'default'           => '',
            'transport'         => 'postMessage',
        )
    );
    $wp_customize->add_control('elixar_instagram_url',
        array(
            'label'       => esc_html__('Instagram URL', 'elixar'),
            'section'     => 'elixar_social_settings',
            'description' => '',
        )
    );
    // Social Flickr URL
    $wp_customize->add_setting('elixar_flickr_url',
        array(
            'sanitize_callback' => 'esc_url_raw',
            'default'           => '',
            'transport'         => 'postMessage',
        )
    );
    $wp_customize->add_control('elixar_flickr_url',
        array(
            'label'       => esc_html__('Flickr URL', 'elixar'),
            'section'     => 'elixar_social_settings',
            'description' => '',
        )
    );
    // Social Skype URL
    $wp_customize->add_setting('elixar_skype_url',
        array(
            'sanitize_callback' => 'sanitize_text_field',
            'default'           => '',
            'transport'         => 'postMessage',
        )
    );
    $wp_customize->add_control('elixar_skype_url',
        array(
            'label'       => esc_html__('Skype', 'elixar'),
            'section'     => 'elixar_social_settings',
            'description' => esc_html__('e.g. skype:SKYPE_USERNAME?call', 'elixar'),
        )
    );
    /* Footer Settings
    ----------------------------------------------------------------------*/
    $wp_customize->add_section('elixar_footer_settings',
        array(
            'priority'    => null,
            'title'       => esc_html__('Footer Options', 'elixar'),
            'description' => '',
            'panel'       => 'elixar_general_settings_panel',
        )
    );
    // Copyright Section Enable
    $wp_customize->add_setting('elixar_footer_ribbon_enable',
        array(
            'sanitize_callback' => 'elixar_sanitize_checkbox',
            'default'           => 0,
        )
    );
    $wp_customize->add_control('elixar_footer_ribbon_enable',
        array(
            'type'        => 'checkbox',
            'label'       => esc_html__('Enable Footer Ribbon?', 'elixar'),
            'section'     => 'elixar_footer_settings',
            'description' => esc_html__('Check this box to enable footer ribbon.', 'elixar'),
        )
    );
    // Footer Ribbon Text
    $wp_customize->add_setting('elixar_footer_ribbon_text',
        array(
            'sanitize_callback' => 'sanitize_text_field',
            'default'           => esc_html__('Get in Touch', 'elixar'),
            'transport'         => 'postMessage',
        )
    );
    $wp_customize->add_control('elixar_footer_ribbon_text',
        array(
            'label'       => esc_html__('Footer Ribbon Text', 'elixar'),
            'section'     => 'elixar_footer_settings',
            'description' => '',
        )
    );
    $wp_customize->add_setting('elixar_footer_layout',
        array(
            'sanitize_callback' => 'absint',
            'default'           => '',
            'transport'         => 'refresh',
        )
    );
    $wp_customize->add_control('elixar_footer_layout',
        array(
            'type'        => 'select',
            'label'       => esc_html__('Layout', 'elixar'),
            'section'     => 'elixar_footer_settings',
            'default'     => 0,
            'description' => esc_html__('Number footer columns to display.', 'elixar'),
            'choices'     => array(
                4 => 4,
                3 => 3,
                2 => 2,
                1 => 1,
                0 => esc_html__('Disable footer widgets', 'elixar'),
            ),
        )
    );
    for ($i = 1; $i <= 4; $i++) {
        $df = 12;
        if ($i > 1) {
            $_n = 12 / $i;
            $df = array();
            for ($j = 0; $j < $i; $j++) {
                $df[$j] = $_n;
            }
            $df = join('+', $df);
        }
        $wp_customize->add_setting('elixar_footer_custom_' . $i . '_columns',
            array(
                'sanitize_callback' => 'sanitize_text_field',
                'default'           => $df,
            )
        );
        $wp_customize->add_control('elixar_footer_custom_' . $i . '_columns',
            array(
				/* translators: %s: Column Width */
                'label'       => $i == 1 ? __('Custom footer 1 column width', 'elixar') : sprintf(__('Custom footer %s columns width', 'elixar'), $i),
                'section'     => 'elixar_footer_settings',
                'description' => esc_html__('Enter int numbers and sum of them must smaller or equal 12, separated by "+"', 'elixar'),
            )
        );
    }
    // Footer BG Color
    $wp_customize->add_setting('elixar_footer_bg_color', array(
        'sanitize_callback'    => 'sanitize_hex_color_no_hash',
        'sanitize_js_callback' => 'maybe_hash_hex_color',
        'default'              => '',
        'transport'            => 'postMessage',
    ));
    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'elixar_footer_bg_color',
        array(
            'label'       => esc_html__('Background Color', 'elixar'),
            'section'     => 'elixar_footer_settings',
            'description' => '',
        )
    ));
    $wp_customize->add_setting('elixar_footer_text_color', array(
        'sanitize_callback' => 'sanitize_hex_color',
        'default'           => '',
        'transport'         => 'postMessage',
    ));
    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'elixar_footer_text_color',
        array(
            'label'       => esc_html__('Text Color', 'elixar'),
            'section'     => 'elixar_footer_settings',
            'description' => '',
        )
    ));
    // Footer Heading color
    $wp_customize->add_setting('elixar_footer_widgets_title_color',
        array(
            'sanitize_callback' => 'sanitize_hex_color',
            'default'           => '',
            'transport'         => 'postMessage',
        )
    );
    $wp_customize->add_control(new WP_Customize_Color_Control(
        $wp_customize,
        'elixar_footer_widgets_title_color',
        array(
            'label'   => esc_html__('Widget Title Color', 'elixar'),
            'section' => 'elixar_footer_settings',
        )
    )
    );
    $wp_customize->add_setting('elixar_footer_widgets_link_color',
        array(
            'sanitize_callback' => 'sanitize_hex_color',
            'default'           => '',
            'transport'         => 'postMessage',
        )
    );
    $wp_customize->add_control(new WP_Customize_Color_Control(
        $wp_customize,
        'elixar_footer_widgets_link_color',
        array(
            'label'   => esc_html__('Link Color', 'elixar'),
            'section' => 'elixar_footer_settings',
        )
    )
    );
    $wp_customize->add_setting('elixar_footer_widgets_link_hover_color',
        array(
            'sanitize_callback' => 'sanitize_hex_color',
            'default'           => '',
            'transport'         => 'postMessage',
        )
    );
    $wp_customize->add_control(new WP_Customize_Color_Control(
        $wp_customize,
        'elixar_footer_widgets_link_hover_color',
        array(
            'label'   => esc_html__('Link Hover Color', 'elixar'),
            'section' => 'elixar_footer_settings',
        )
    )
    );
    /* Footer Copyright Settings
    ----------------------------------------------------------------------*/
    $wp_customize->add_section('elixar_footer_copyright',
        array(
            'priority'    => null,
            'title'       => esc_html__('Footer Copyright Options', 'elixar'),
            'description' => '',
            'panel'       => 'elixar_general_settings_panel',
        )
    );
    // Copyright Section Enable
    $wp_customize->add_setting('elixar_copyright_enable',
        array(
            'sanitize_callback' => 'elixar_sanitize_checkbox',
            'default'           => 1,
        )
    );
    $wp_customize->add_control('elixar_copyright_enable',
        array(
            'type'        => 'checkbox',
            'label'       => esc_html__('Enable Copyright Section?', 'elixar'),
            'section'     => 'elixar_footer_copyright',
            'description' => esc_html__('Check this box to enable copyright section.', 'elixar'),
        )
    );
    // Footer Developed By Text
    $wp_customize->add_setting('elixar_copyright_text',
        array(
            'sanitize_callback' => 'wp_kses_post',
            /* translators: %s: Copyright Text */
			'default'           => sprintf(__('Theme powered by %1$s WordPress %3$s & developed by %2$s WebHunt Infotech %3$s', "elixar"),
                '<a href="https://wordpress.org/" target="_blank">',
                '<a href="https://webhuntinfotech.com/" target="_blank">',
                '</a>'
            ),
            'transport'         => 'postMessage',
        )
    );
    $wp_customize->add_control(new Elixar_Editor_Custom_Control(
        $wp_customize,
        'elixar_copyright_text',
        array(
            'label'       => esc_html__('Copyright Content Here', 'elixar'),
            'section'     => 'elixar_footer_copyright',
            'description' => '',
        )
    ));
    // Footer Widgets Color
    $wp_customize->add_setting('elixar_copyright_bg_color', array(
        'sanitize_callback'    => 'sanitize_hex_color',
        'sanitize_js_callback' => 'maybe_hash_hex_color',
        'default'              => '',
        'transport'            => 'postMessage',
    ));
    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'elixar_copyright_bg_color',
        array(
            'label'       => esc_html__('Background Color', 'elixar'),
            'section'     => 'elixar_footer_copyright',
            'description' => '',
        )
    ));
    // Footer Widgets Color
    $wp_customize->add_setting('elixar_copyright_text_color', array(
        'sanitize_callback'    => 'sanitize_hex_color',
        'sanitize_js_callback' => 'maybe_hash_hex_color',
        'default'              => '',
        'transport'            => 'postMessage',
    ));
    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'elixar_copyright_text_color',
        array(
            'label'       => esc_html__('Text Color', 'elixar'),
            'section'     => 'elixar_footer_copyright',
            'description' => '',
        )
    ));
    $wp_customize->add_setting('elixar_copyright_link_color', array(
        'sanitize_callback'    => 'sanitize_hex_color',
        'sanitize_js_callback' => 'maybe_hash_hex_color',
        'default'              => '',
        'transport'            => 'postMessage',
    ));
    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'elixar_copyright_link_color',
        array(
            'label'       => esc_html__('Link Color', 'elixar'),
            'section'     => 'elixar_footer_copyright',
            'description' => '',
        )
    ));
    $wp_customize->add_setting('elixar_copyright_link_hover_color', array(
        'sanitize_callback'    => 'sanitize_hex_color',
        'sanitize_js_callback' => 'maybe_hash_hex_color',
        'default'              => '',
        'transport'            => 'postMessage',
    ));
    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'elixar_copyright_link_hover_color',
        array(
            'label'       => esc_html__('Link Hover Color', 'elixar'),
            'section'     => 'elixar_footer_copyright',
            'description' => '',
        )
    ));
    if (!function_exists('elixar_wp_get_custom_css')) {
        // Back-compat for WordPress < 4.7.
        /* Custom CSS Settings
        ----------------------------------------------------------------------*/
        $wp_customize->add_section(
            'elixar_custom_code',
            array(
                'title' => esc_html__('Custom CSS', 'elixar'),
                'panel' => 'elixar_general_settings_panel',
            )
        );
        $wp_customize->add_setting(
            'elixar_custom_css',
            array(
                'default'           => '',
                'sanitize_callback' => 'elixar_sanitize_css',
                'type'              => 'option',
            )
        );
        $wp_customize->add_control(
            'elixar_custom_css',
            array(
                'label'   => esc_html__('Custom CSS', 'elixar'),
                'section' => 'elixar_custom_code',
                'type'    => 'textarea',
            )
        );
    } else {
        $wp_customize->get_section('custom_css')->priority = 994;
    }
    /*------------------------------------------------------------------------*/
    /*  Section: Hero
    /*------------------------------------------------------------------------*/
    $wp_customize->add_panel('elixar_hero_panel',
        array(
            'priority'        => 130,
            'title'           => esc_html__('Section: Hero', 'elixar'),
            'description'     => '',
            'active_callback' => 'elixar_showon_frontpage',
        )
    );
    // Hero settings
    $wp_customize->add_section('elixar_hero_settings',
        array(
            'priority'    => 3,
            'title'       => esc_html__('Hero Settings', 'elixar'),
            'description' => '',
            'panel'       => 'elixar_hero_panel',
        )
    );
    // Show section
    $wp_customize->add_setting('elixar_hero_enable',
        array(
            'sanitize_callback' => 'elixar_sanitize_checkbox',
            'default'           => 1,
        )
    );
    $wp_customize->add_control('elixar_hero_enable',
        array(
            'type'        => 'checkbox',
            'label'       => esc_html__('Enable this section?', 'elixar'),
            'section'     => 'elixar_hero_settings',
            'description' => esc_html__('Check this box to enable this section.', 'elixar'),
        )
    );
    // Section ID
    $wp_customize->add_setting('elixar_hero_id',
        array(
            'sanitize_callback' => 'sanitize_text_field',
            'default'           => esc_html__('section-hero', 'elixar'),
            'transport'         => 'postMessage',
        )
    );
    $wp_customize->add_control('elixar_hero_id',
        array(
            'label'       => esc_html__('Section ID:', 'elixar'),
            'section'     => 'elixar_hero_settings',
            'description' => esc_html__('The section id, we will use this for link anchor.', 'elixar'),
        )
    );
    // Hero content padding top
    $wp_customize->add_setting('elixar_hero_padding_top',
        array(
            'sanitize_callback' => 'absint',
            'default'           => 10,
            'transport'         => 'postMessage',
        )
    );
    $wp_customize->add_control('elixar_hero_padding_top',
        array(
            'label'       => esc_html__('Padding Top:', 'elixar'),
            'section'     => 'elixar_hero_settings',
            'description' => esc_html__('The hero content padding top in percent (%).', 'elixar'),
        )
    );
    // Hero content padding bottom
    $wp_customize->add_setting('elixar_hero_padding_bottom',
        array(
            'sanitize_callback' => 'absint',
            'default'           => 10,
            'transport'         => 'postMessage',
        )
    );
    $wp_customize->add_control('elixar_hero_padding_bottom',
        array(
            'label'       => esc_html__('Padding Bottom:', 'elixar'),
            'section'     => 'elixar_hero_settings',
            'description' => esc_html__('The hero content padding bottom in percent (%).', 'elixar'),
        )
    );
       
    // hero content
    $wp_customize->add_section('elixar_hero_content',
        array(
            'priority'    => 3,
            'title'       => esc_html__('Hero Content', 'elixar'),
            'description' => '',
            'panel'       => 'elixar_hero_panel',
        )
    );
    $wp_customize->add_setting('elixar_hero_page',
        array(
            'sanitize_callback' => 'absint',
            'default'           => 0,
        )
    );
    $wp_customize->add_control('elixar_hero_page',
        array(
            'label'       => esc_html__('Select a page', 'elixar'),
            'section'     => 'elixar_hero_content',
            'type'=>'dropdown-pages'
        )
    );
    $wp_customize->add_setting('elixar_hero_large_text_color',
        array(
            'sanitize_callback' => 'sanitize_hex_color',
            'default'           => null,
            'transport'         => 'postMessage',
        )
    );
    $wp_customize->add_control(new WP_Customize_Color_Control(
        $wp_customize,
        'elixar_hero_large_text_color',
        array(
            'label'   => esc_html__('Hero Title Color', 'elixar'),
            'section' => 'elixar_hero_content',
        )
    )
    );
    $wp_customize->add_setting('elixar_hero_large_text_bg_color',
        array(
            'sanitize_callback' => 'elixar_sanitize_color_alpha',
            'default'           => '',
            'transport'         => 'postMessage',
        )
    );
    $wp_customize->add_control(new Elixar_Alpha_Color_Control(
        $wp_customize,
        'elixar_hero_large_text_bg_color',
        array(
            'label'   => esc_html__('Title Background Color', 'elixar'),
            'section' => 'elixar_hero_content',
        )
    )
    );
    // Button #1 Text
    $wp_customize->add_setting('elixar_hero_btn1_text',
        array(
            'sanitize_callback' => 'sanitize_text_field',
            'default'           => esc_html__('About Us', 'elixar'),
            'transport'         => 'postMessage',
        )
    );
    $wp_customize->add_control('elixar_hero_btn1_text',
        array(
            'label'   => esc_html__('Button #1 Text', 'elixar'),
            'section' => 'elixar_hero_content',
        )
    );
    // Button #1 Link
    $wp_customize->add_setting('elixar_hero_btn1_link',
        array(
            'sanitize_callback' => 'esc_url_raw',
            'default'           => esc_url(home_url('/')) . esc_html__('#about', 'elixar'),
            'transport'         => 'refresh',
        )
    );
    $wp_customize->add_control('elixar_hero_btn1_link',
        array(
            'label'   => esc_html__('Button #1 Link', 'elixar'),
            'section' => 'elixar_hero_content',
        )
    );
    // Button #1 Style
	$wp_customize->add_setting( 'elixar_hero_btn1_style',
		array(
			'sanitize_callback' => 'elixar_sanitize_text',
			'default'           => 'btn-primary',
			'transport'	=> 'refresh'
		)
	);
	$wp_customize->add_control( 'elixar_hero_btn1_style',
		array(
			'label' 		=> esc_html__('Button #1 style', 'elixar'),
			'section' 		=> 'elixar_hero_content',
			'type'          => 'select',
			'choices' => array(
				'btn-primary' => esc_html__('Button Primary', 'elixar'),
				'btn-primary btn-ghost' => esc_html__('Button Secondary', 'elixar'),
				'btn-default' => esc_html__('Button Default', 'elixar'),
				'' => esc_html__('Button', 'elixar'),
				'btn-green' => esc_html__('Success', 'elixar'),
				'btn-blue' => esc_html__('Info', 'elixar'),
				'btn-yellow' => esc_html__('Warning', 'elixar'),
				'btn-red' => esc_html__('Danger', 'elixar'),
			)
		)
	);
    // Button #1 Target
    $wp_customize->add_setting('elixar_hero_btn_target',
        array(
            'sanitize_callback' => 'elixar_sanitize_checkbox',
            'default'           => 1,
        )
    );
    $wp_customize->add_control('elixar_hero_btn_target',
        array(
            'type'        => 'checkbox',
            'label'       => esc_html__('Open Link into New Tab/Window', 'elixar'),
            'section'     => 'elixar_hero_content',
            'description' => esc_html__('Check this box to open link into new tab/window', 'elixar'),
        )
    );
    // Button #2 Text
    $wp_customize->add_setting('elixar_hero_btn2_text',
        array(
            'sanitize_callback' => 'sanitize_text_field',
            'default'           => esc_html__('Get Started', 'elixar'),
            'transport'         => 'postMessage',
        )
    );
    $wp_customize->add_control('elixar_hero_btn2_text',
        array(
            'label'   => esc_html__('Button #2 Text', 'elixar'),
            'section' => 'elixar_hero_content',
        )
    );
    // Button #2 Link
    $wp_customize->add_setting('elixar_hero_btn2_link',
        array(
            'sanitize_callback' => 'esc_url_raw',
            'default'           => esc_url(home_url('/')) . esc_html__('#contact', 'elixar'),
            'transport'         => 'refresh',
        )
    );
    $wp_customize->add_control('elixar_hero_btn2_link',
        array(
            'label'   => esc_html__('Button #2 Link', 'elixar'),
            'section' => 'elixar_hero_content',
        )
    );
    // Button #2 Style
	$wp_customize->add_setting( 'elixar_hero_btn2_style',
		array(
			'sanitize_callback' => 'elixar_sanitize_text',
			'default'           => ' btn-primary btn-ghost',
			'transport'	=> 'refresh'
		)
	);
	$wp_customize->add_control( 'elixar_hero_btn2_style',
		array(
			'label' 		=> esc_html__('Button #2 style', 'elixar'),
			'section' 		=> 'elixar_hero_content',
			'type'          => 'select',
			'choices' => array(
				'btn-primary' => esc_html__('Button Primary', 'elixar'),
				'btn-primary btn-ghost' => esc_html__('Button Secondary', 'elixar'),
				'btn-default' => esc_html__('Button Default', 'elixar'),
				'' => esc_html__('Button', 'elixar'),
				'btn-green' => esc_html__('Success', 'elixar'),
				'btn-blue' => esc_html__('Info', 'elixar'),
				'btn-yellow' => esc_html__('Warning', 'elixar'),
				'btn-red' => esc_html__('Danger', 'elixar'),
			)
		)
	);
    // Button #2 Target
    $wp_customize->add_setting('elixar_hero_btn_2_target',
        array(
            'sanitize_callback' => 'elixar_sanitize_checkbox',
            'default'           => 1,
        )
    );
    $wp_customize->add_control('elixar_hero_btn_2_target',
        array(
            'type'        => 'checkbox',
            'label'       => esc_html__('Open Link into New Tab/Window', 'elixar'),
            'section'     => 'elixar_hero_content',
            'description' => esc_html__('Check this box to open link into new tab/window', 'elixar'),
        )
    );
    // END For Hero layout ------------------------
    /*------------------------------------------------------------------------*/
    /*  Section: Calltoaction
    /*------------------------------------------------------------------------*/
    $wp_customize->add_panel('elixar_cta_panel',
        array(
            'priority'        => 130,
            'title'           => esc_html__('Section: CallToAction', 'elixar'),
            'description'     => '',
            'active_callback' => 'elixar_showon_frontpage',
        )
    );
    // CallToAction settings
    $wp_customize->add_section('elixar_cta_settings',
        array(
            'priority'    => 3,
            'title'       => esc_html__('CallToAction Settings', 'elixar'),
            'description' => '',
            'panel'       => 'elixar_cta_panel',
        )
    );
    // Show section
    $wp_customize->add_setting('elixar_cta_enable',
        array(
            'sanitize_callback' => 'elixar_sanitize_checkbox',
            'default'           => 0,
        )
    );
    $wp_customize->add_control('elixar_cta_enable',
        array(
            'type'        => 'checkbox',
            'label'       => esc_html__('Enable this section?', 'elixar'),
            'section'     => 'elixar_cta_settings',
            'description' => esc_html__('Check this box to enable this section.', 'elixar'),
        )
    );
    // Section ID
    $wp_customize->add_setting('elixar_cta_id',
        array(
            'sanitize_callback' => 'sanitize_text_field',
            'default'           => esc_html__('section-cta', 'elixar'),
            'transport'         => 'refresh',
        )
    );
    $wp_customize->add_control('elixar_cta_id',
        array(
            'label'       => esc_html__('Section ID:', 'elixar'),
            'section'     => 'elixar_cta_settings',
            'description' => esc_html__('The section id, we will use this for link anchor.', 'elixar'),
        )
    );

    $wp_customize->add_section('elixar_cta_content',
        array(
            'priority'    => 9,
            'title'       => esc_html__('CallToAction Content', 'elixar'),
            'description' => '',
            'panel'       => 'elixar_cta_panel',
        )
    );
    // For CallToAction layout ------------------------
    // Large Text
    $wp_customize->add_setting('elixar_cta_page',
        array(
            'sanitize_callback' => 'absint',
            'default'           => 0,
        )
    );
    $wp_customize->add_control('elixar_cta_page',
        array(
            'label'       => esc_html__('Select a page', 'elixar'),
            'section'     => 'elixar_cta_content',
            'type'=>'dropdown-pages',
        )
    );
    
    // Button Text
    $wp_customize->add_setting('elixar_cta_btn1_text',
        array(
            'sanitize_callback' => 'sanitize_text_field',
            'default'           => esc_html__('Purchase Now', 'elixar'),
            'transport'         => 'postMessage',
        )
    );
    $wp_customize->add_control('elixar_cta_btn1_text',
        array(
            'label'   => esc_html__('Button Text', 'elixar'),
            'section' => 'elixar_cta_content',
        )
    );
    // CTA Button Icon
    $wp_customize->add_setting('elixar_cta_btn1_icon',
        array(
            'sanitize_callback' => 'sanitize_text_field',
            'default'           => 'fas fa-shopping-cart',
            'transport'         => 'postMessage',
        )
    );
    $wp_customize->add_control(new Elixar_Fontawesome_Icon_Chooser(
        $wp_customize,
        'elixar_cta_btn1_icon',
        array(
            'label'   => esc_html__('FontAwesome Icon', 'elixar'),
            'section' => 'elixar_cta_content',
        )
    ));
    // Button Link
    $wp_customize->add_setting('elixar_cta_btn1_link',
        array(
            'sanitize_callback' => 'esc_url_raw',
            'default'           => '',
            'transport'         => 'refresh',
        )
    );
    $wp_customize->add_control('elixar_cta_btn1_link',
        array(
            'label'   => esc_html__('Button Link', 'elixar'),
            'section' => 'elixar_cta_content',
        )
    );
    // Button Style
    $wp_customize->add_setting('elixar_cta_btn1_style',
        array(
            'sanitize_callback' => 'elixar_sanitize_text',
            'default'           => 'btn-primary',
            'transport'         => 'refresh',
        )
    );
    $wp_customize->add_control('elixar_cta_btn1_style',
        array(
            'label'   => esc_html__('Button style', 'elixar'),
            'section' => 'elixar_cta_content',
            'type'    => 'select',
            'choices' => array(
                'btn-primary'           => esc_html__('Button Primary', 'elixar'),
                'btn-primary btn-ghost' => esc_html__('Button Secondary', 'elixar'),
                'btn-default'           => esc_html__('Button Default', 'elixar'),
                ''                      => esc_html__('Button', 'elixar'),
                'btn-green'             => esc_html__('Success', 'elixar'),
                'btn-blue'              => esc_html__('Info', 'elixar'),
                'btn-yellow'            => esc_html__('Warning', 'elixar'),
                'btn-red'               => esc_html__('Danger', 'elixar'),
            ),
        )
    );
    // Button Target
    $wp_customize->add_setting('elixar_cta_btn1_target',
        array(
            'sanitize_callback' => 'elixar_sanitize_checkbox',
            'default'           => 1,
        )
    );
    $wp_customize->add_control('elixar_cta_btn1_target',
        array(
            'type'        => 'checkbox',
            'label'       => esc_html__('Open Link into New Tab/Window', 'elixar'),
            'section'     => 'elixar_cta_content',
            'description' => esc_html__('Check this box to open link into new tab/window', 'elixar'),
        )
    );
    // END For Calltoaction layout ------------------------
    /*------------------------------------------------------------------------*/
    /*  Section: Extra
    /*------------------------------------------------------------------------*/
    $wp_customize->add_panel('elixar_section_extra',
        array(
            'priority'        => 160,
            'title'           => esc_html__('Section: Extra', 'elixar'),
            'description'     => '',
            'active_callback' => 'elixar_showon_frontpage',
        )
    );
    $wp_customize->add_section('elixar_section_extra_settings',
        array(
            'priority'    => 3,
            'title'       => esc_html__('Section Settings', 'elixar'),
            'description' => '',
            'panel'       => 'elixar_section_extra',
        )
    );
    // Show Content
    $wp_customize->add_setting('elixar_section_extra_enable',
        array(
            'sanitize_callback' => 'elixar_sanitize_checkbox',
            'default'           => 0,
        )
    );
    $wp_customize->add_control('elixar_section_extra_enable',
        array(
            'type'        => 'checkbox',
            'label'       => esc_html__('Enable this section?', 'elixar'),
            'section'     => 'elixar_section_extra_settings',
            'description' => esc_html__('Check this box to enable this section.', 'elixar'),
        )
    );
    // Section ID
    $wp_customize->add_setting('elixar_section_extra_id',
        array(
            'sanitize_callback' => 'sanitize_text_field',
            'default'           => esc_html__('section-extra', 'elixar'),
            'transport'         => 'refresh',
        )
    );
    $wp_customize->add_control('elixar_section_extra_id',
        array(
            'label'       => esc_html__('Section ID:', 'elixar'),
            'section'     => 'elixar_section_extra_settings',
            'description' => esc_html__('The section id, we will use this for link anchor.', 'elixar'),
        )
    );
    // Title
    $wp_customize->add_setting('elixar_section_extra_title',
        array(
            'sanitize_callback' => 'sanitize_text_field',
            'default'           => esc_html__('WHY CHOOSE US', 'elixar'),
            'transport'         => 'postMessage',
        )
    );
    $wp_customize->add_control('elixar_section_extra_title',
        array(
            'label'       => esc_html__('Section Title', 'elixar'),
            'section'     => 'elixar_section_extra_settings',
            'description' => '',
        )
    );
    // Description
    $wp_customize->add_setting('elixar_section_extra_desc',
        array(
            'sanitize_callback' => 'sanitize_text_field',
            'default'           => '',
            'transport'         => 'postMessage',
        )
    );
    $wp_customize->add_control('elixar_section_extra_desc',
        array(
            'label'       => esc_html__('Section Description', 'elixar'),
            'section'     => 'elixar_section_extra_settings',
            'description' => '',
        )
    );
    $wp_customize->add_section('elixar_section_extra_content',
        array(
            'priority'    => 6,
            'title'       => esc_html__('Section Content', 'elixar'),
            'description' => '',
            'panel'       => 'elixar_section_extra',
        )
    );
    // Order & Stlying
    $wp_customize->add_setting(
        'elixar_section_extra_boxes',
        array(
            //'default' => '',
            'sanitize_callback' => 'elixar_sanitize_repeatable_data_field',
            'transport'         => 'refresh', // refresh or postMessage
        ));
    $wp_customize->add_control(
        new Elixar_Customize_Repeatable_Control(
            $wp_customize,
            'elixar_section_extra_boxes',
            array(
                'label'         => esc_html__('Extra content page', 'elixar'),
                'description'   => '',
                'section'       => 'elixar_section_extra_content',
                'live_title_id' => 'content_page', // apply for unput text and textarea only
                'title_format'  => esc_html__('[live_title]', 'elixar'), // [live_title]
                'max_item'      => 3, // Maximum item can add
                'limited_msg'   => wp_kses_post(__('Upgrade to <a target="_blank" href="https://www.webhuntinfotech.com/theme/elixar-premium/">Elixar Premium</a> to be able to add more items and unlock other premium features!', 'elixar')),
                'fields'        => array(
                    'content_page' => array(
                        'title'   => esc_html__('Select a page', 'elixar'),
                        'type'    => 'select',
                        'options' => $option_pages,
                    ),
                    'hide_title'   => array(
                        'title' => esc_html__('Hide item title', 'elixar'),
                        'type'  => 'checkbox',
                    ),
                    'enable_link'  => array(
                        'title' => esc_html__('Link to single page', 'elixar'),
                        'type'  => 'checkbox',
                    ),
                ),
            )
        )
    );
    // Extra content source
    $wp_customize->add_setting('elixar_section_extra_content_source',
        array(
            'sanitize_callback' => 'elixar_sanitize_select',
            'default'           => 'content',
            'transport'         => 'refresh',
        )
    );
    $wp_customize->add_control('elixar_section_extra_content_source',
        array(
            'label'       => esc_html__('Item content source', 'elixar'),
            'section'     => 'elixar_section_extra_content',
            'description' => '',
            'type'        => 'select',
            'choices'     => array(
                'content' => esc_html__('Full Page Content', 'elixar'),
                'excerpt' => esc_html__('Page Excerpt', 'elixar'),
            ),
        )
    );
    /*------------------------------------------------------------------------*/
    /*  Section: Services
    /*------------------------------------------------------------------------*/
    $wp_customize->add_panel('elixar_services',
        array(
            'priority'        => 170,
            'title'           => esc_html__('Section: Services', 'elixar'),
            'description'     => '',
            'active_callback' => 'elixar_showon_frontpage',
        )
    );
    $wp_customize->add_section('elixar_service_settings',
        array(
            'priority'    => 3,
            'title'       => esc_html__('Section Settings', 'elixar'),
            'description' => '',
            'panel'       => 'elixar_services',
        )
    );
    // Show Content
    $wp_customize->add_setting('elixar_services_enable',
        array(
            'sanitize_callback' => 'elixar_sanitize_checkbox',
            'default'           => 0,
        )
    );
    $wp_customize->add_control('elixar_services_enable',
        array(
            'type'        => 'checkbox',
            'label'       => esc_html__('Enable this section?', 'elixar'),
            'section'     => 'elixar_service_settings',
            'description' => esc_html__('Check this box to enable this section.', 'elixar'),
        )
    );
    // Section ID
    $wp_customize->add_setting('elixar_services_id',
        array(
            'sanitize_callback' => 'sanitize_text_field',
            'default'           => esc_html__('section-services', 'elixar'),
            'transport'         => 'refresh',
        )
    );
    $wp_customize->add_control('elixar_services_id',
        array(
            'label'       => esc_html__('Section ID:', 'elixar'),
            'section'     => 'elixar_service_settings',
            'description' => 'The section id, we will use this for link anchor.',
        )
    );
    // Title
    $wp_customize->add_setting('elixar_services_title',
        array(
            'sanitize_callback' => 'sanitize_text_field',
            'default'           => esc_html__('Our Services', 'elixar'),
            'transport'         => 'postMessage',
        )
    );
    $wp_customize->add_control('elixar_services_title',
        array(
            'label'       => esc_html__('Section Title', 'elixar'),
            'section'     => 'elixar_service_settings',
            'description' => '',
        )
    );
    // Description
    $wp_customize->add_setting('elixar_services_desc',
        array(
            'sanitize_callback' => 'sanitize_text_field',
            'default'           => '',
            'transport'         => 'postMessage',
        )
    );
    $wp_customize->add_control('elixar_services_desc',
        array(
            'label'       => esc_html__('Section Description', 'elixar'),
            'section'     => 'elixar_service_settings',
            'description' => '',
        )
    );
    // Services layout
    $wp_customize->add_setting('elixar_service_layout',
        array(
            'sanitize_callback' => 'absint',
            'default'           => 6,
            'transport'         => 'refresh',
        )
    );
    $wp_customize->add_control('elixar_service_layout',
        array(
            'label'       => esc_html__('Services Layout Setting', 'elixar'),
            'section'     => 'elixar_service_settings',
            'description' => '',
            'type'        => 'select',
            'choices'     => array(
                3  => esc_html__('4 Columns', 'elixar'),
                4  => esc_html__('3 Columns', 'elixar'),
                6  => esc_html__('2 Columns', 'elixar'),
                12 => esc_html__('1 Column', 'elixar'),
            ),
        )
    );
    $wp_customize->add_section('elixar_service_content',
        array(
            'priority'    => 6,
            'title'       => esc_html__('Section Content', 'elixar'),
            'description' => '',
            'panel'       => 'elixar_services',
        )
    );
    // Section service content.
    $wp_customize->add_setting(
        'elixar_services',
        array(
            'sanitize_callback' => 'elixar_sanitize_repeatable_data_field',
            'transport'         => 'refresh', // refresh or postMessage
        ));
    $wp_customize->add_control(
        new Elixar_Customize_Repeatable_Control(
            $wp_customize,
            'elixar_services',
            array(
                'label'         => esc_html__('Service content', 'elixar'),
                'description'   => '',
                'section'       => 'elixar_service_content',
                'live_title_id' => 'content_page', // apply for unput text and textarea only
                'title_format'  => esc_html__('[live_title]', 'elixar'), // [live_title]
                'max_item'      => 4, // Maximum item can add,
                'limited_msg'   => wp_kses_post(__('Upgrade to <a target="_blank" href="https://www.webhuntinfotech.com/theme/elixar-premium/">Elixar Premium</a> to be able to add more items and unlock other premium features!', 'elixar')),
                'fields'        => array(
                    'icon_type'    => array(
                        'title'   => esc_html__('Custom icon', 'elixar'),
                        'type'    => 'select',
                        'options' => array(
                            'icon'  => esc_html__('Icon', 'elixar'),
                            'image' => esc_html__('image', 'elixar'),
                        ),
                    ),
                    'icon'         => array(
                        'title'    => esc_html__('Icon', 'elixar'),
                        'type'     => 'icon',
                        'required' => array('icon_type', '=', 'icon'),
                    ),
                    'image'        => array(
                        'title'    => esc_html__('Image', 'elixar'),
                        'type'     => 'media',
                        'required' => array('icon_type', '=', 'image'),
                    ),
                    'content_page' => array(
                        'title'   => esc_html__('Select a page', 'elixar'),
                        'type'    => 'select',
                        'options' => $option_pages,
                    ),
                    'enable_link'  => array(
                        'title' => esc_html__('Add Link?', 'elixar'),
                        'type'  => 'checkbox',
                    ),
                    'link'         => array(
                        'title'    => esc_html__('Link', 'elixar'),
                        'type'     => 'text',
                        'required' => array('enable_link', '=', true),
                    ),
                ),
            )
        )
    );
    // Services icon size
    $wp_customize->add_setting('elixar_service_icon_size',
        array(
            'sanitize_callback' => 'elixar_sanitize_select',
            'default'           => '4x',
            'transport'         => 'refresh',
        )
    );
    $wp_customize->add_control('elixar_service_icon_size',
        array(
            'label'       => esc_html__('Icon Size', 'elixar'),
            'section'     => 'elixar_service_content',
            'description' => '',
            'type'        => 'select',
            'choices'     => array(
                '4x' => esc_html__('4x', 'elixar'),
                '3x' => esc_html__('3x', 'elixar'),
                '2x' => esc_html__('2x', 'elixar'),
                '1x' => esc_html__('1x', 'elixar'),
            ),
        )
    );
    /*------------------------------------------------------------------------*/
    /*  Section: Blog
    /*------------------------------------------------------------------------*/
    $wp_customize->add_panel('elixar_blog',
        array(
            'priority'        => 180,
            'title'           => esc_html__('Section: Blog', 'elixar'),
            'description'     => '',
            'active_callback' => 'elixar_showon_frontpage',
        )
    );
    $wp_customize->add_section('elixar_blog_settings',
        array(
            'priority'    => 3,
            'title'       => esc_html__('Section Settings', 'elixar'),
            'description' => '',
            'panel'       => 'elixar_blog',
        )
    );
    // Show Content
    $wp_customize->add_setting('elixar_blog_enable',
        array(
            'sanitize_callback' => 'elixar_sanitize_checkbox',
            'default'           => 0,
        )
    );
    $wp_customize->add_control('elixar_blog_enable',
        array(
            'type'        => 'checkbox',
            'label'       => esc_html__('Enable this section?', 'elixar'),
            'section'     => 'elixar_blog_settings',
            'description' => esc_html__('Check this box to enable this section.', 'elixar'),
        )
    );
    // Section ID
    $wp_customize->add_setting('elixar_blog_id',
        array(
            'sanitize_callback' => 'sanitize_text_field',
            'default'           => esc_html__('section-blog', 'elixar'),
            'transport'         => 'refresh',
        )
    );
    $wp_customize->add_control('elixar_blog_id',
        array(
            'label'       => esc_html__('Section ID:', 'elixar'),
            'section'     => 'elixar_blog_settings',
            'description' => esc_html__('The section id, we will use this for link anchor.', 'elixar'),
        )
    );
    // Title
    $wp_customize->add_setting('elixar_blog_title',
        array(
            'sanitize_callback' => 'sanitize_text_field',
            'default'           => esc_html__('Latest Blog', 'elixar'),
            'transport'         => 'postMessage',
        )
    );
    $wp_customize->add_control('elixar_blog_title',
        array(
            'label'       => esc_html__('Section Title', 'elixar'),
            'section'     => 'elixar_blog_settings',
            'description' => '',
        )
    );
    // Description
    $wp_customize->add_setting('elixar_blog_desc',
        array(
            'sanitize_callback' => 'sanitize_text_field',
            'default'           => '',
            'transport'         => 'postMessage',
        )
    );
    $wp_customize->add_control('elixar_blog_desc',
        array(
            'label'       => esc_html__('Section Description', 'elixar'),
            'section'     => 'elixar_blog_settings',
            'description' => '',
        )
    );
    // hr
    $wp_customize->add_setting('elixar_blog_settings_hr',
        array(
            'sanitize_callback' => 'sanitize_text_field',
        )
    );
    $wp_customize->add_control(new Elixar_Misc_Control($wp_customize, 'elixar_blog_settings_hr',
        array(
            'section' => 'elixar_blog_settings',
            'type'    => 'hr',
        )
    ));
    // Number of post to show.
    $wp_customize->add_setting('elixar_blog_number',
        array(
            'sanitize_callback' => 'absint',
            'default'           => 3,
        )
    );
    $wp_customize->add_control('elixar_blog_number',
        array(
            'label'       => esc_html__('Number of post to show', 'elixar'),
            'section'     => 'elixar_blog_settings',
            'description' => '',
        )
    );
    $wp_customize->add_setting('elixar_blog_cat',
        array(
            'sanitize_callback' => 'sanitize_text_field',
            'default'           => 0,
            'transport'         => 'refresh',
        )
    );
    $wp_customize->add_control(new Elixar_Category_Control(
        $wp_customize,
        'elixar_blog_cat',
        array(
            'label'       => esc_html__('Category to show', 'elixar'),
            'section'     => 'elixar_blog_settings',
            'description' => '',
        )
    ));
    $wp_customize->add_setting('elixar_blog_orderby',
        array(
            'sanitize_callback' => 'elixar_sanitize_select',
            'default'           => 0,
            'transport'         => 'refresh',
        )
    );
    $wp_customize->add_control(
        'elixar_blog_orderby',
        array(
            'label'   => esc_html__('Order By', 'elixar'),
            'section' => 'elixar_blog_settings',
            'type'    => 'select',
            'choices' => array(
                'default'       => esc_html__('Default', 'elixar'),
                'id'            => esc_html__('ID', 'elixar'),
                'author'        => esc_html__('Author', 'elixar'),
                'title'         => esc_html__('Title', 'elixar'),
                'date'          => esc_html__('Date', 'elixar'),
                'comment_count' => esc_html__('Comment Count', 'elixar'),
                'menu_order'    => esc_html__('Order by Page Order', 'elixar'),
                'rand'          => esc_html__('Random order', 'elixar'),
            ),
        )
    );
    $wp_customize->add_setting('elixar_blog_order',
        array(
            'sanitize_callback' => 'elixar_sanitize_select',
            'default'           => 'desc',
            'transport'         => 'refresh',
        )
    );
    $wp_customize->add_control(
        'elixar_blog_order',
        array(
            'label'   => esc_html__('Order', 'elixar'),
            'section' => 'elixar_blog_settings',
            'type'    => 'select',
            'choices' => array(
                'desc' => esc_html__('Descending', 'elixar'),
                'asc'  => esc_html__('Ascending', 'elixar'),
            ),
        )
    );
    $wp_customize->add_setting('elixar_blog_more_text',
        array(
            'sanitize_callback' => 'sanitize_text_field',
            'default'           => esc_html__('Read More', 'elixar'),
            'transport'         => 'postMessage',
        )
    );
    $wp_customize->add_control('elixar_blog_more_text',
        array(
            'label'       => esc_html__('Read More Button Text', 'elixar'),
            'section'     => 'elixar_blog_settings',
            'description' => '',
        )
    );
    // Show Load More Button
    $wp_customize->add_setting('elixar_load_post_button_enable',
        array(
            'sanitize_callback' => 'elixar_sanitize_checkbox',
            'default'           => 1,
        )
    );
    $wp_customize->add_control('elixar_load_post_button_enable',
        array(
            'type'        => 'checkbox',
            'label'       => esc_html__('Show Load More Button', 'elixar'),
            'section'     => 'elixar_blog_settings',
            'description' => esc_html__('Check this box to show load more post button', 'elixar'),
        )
    );
    /* Load More Button Text*/
    $wp_customize->add_setting('elixar_blog_load_more_text',
        array(
            'sanitize_callback' => 'sanitize_text_field',
            'default'           => esc_html__('Load More', 'elixar'),
            'transport'         => 'postMessage',
        )
    );
    $wp_customize->add_control('elixar_blog_load_more_text',
        array(
            'label'       => esc_html__('Load More Button Text', 'elixar'),
            'section'     => 'elixar_blog_settings',
            'description' => '',
        )
    );
    /* Loading Text */
    $wp_customize->add_setting('elixar_blog_more_loading_text',
        array(
            'sanitize_callback' => 'sanitize_text_field',
            'default'           => esc_html__('Loading...', 'elixar'),
            'transport'         => 'refresh',
        )
    );
    $wp_customize->add_control('elixar_blog_more_loading_text',
        array(
            'label'       => esc_html__('Loading Text', 'elixar'),
            'section'     => 'elixar_blog_settings',
            'description' => '',
        )
    );
    /* Blog No More Post Text */
    $wp_customize->add_setting('elixar_blog_no_more_post_text',
        array(
            'sanitize_callback' => 'sanitize_text_field',
            'default'           => esc_html__('No more older post found', 'elixar'),
            'transport'         => 'refresh',
        )
    );
    $wp_customize->add_control('elixar_blog_no_more_post_text',
        array(
            'label'       => esc_html__('No More Post Text*', 'elixar'),
            'section'     => 'elixar_blog_settings',
            'description' => '',
        )
    );
    $wp_customize->get_control('elixar_site_layout')->priority = 9;
    /**
     * Hook to add other customize
     */
    do_action('elixar_customize_after_register', $wp_customize);
    /* Add Partial Refresh */
    $partial_refresh = array(
        //Header Contact
        'elixar_header_contact_phone'   => array(
            'selector' => '#quick-call',
        ),
        'elixar_header_contact_email'   => array(
            'selector' => '#quick-email',
        ),
        'elixar_header_contact_address' => array(
            'selector' => '#quick-address',
        ),
        //Hero
        'elixar_hero_btn1_text'         => array(
            'selector' => '#e-hero-btn1',
        ),
        'elixar_hero_btn2_text'         => array(
            'selector' => '#e-hero-btn2',
        ),
        //blog
        'elixar_blog_title'             => array(
            'selector' => '#blog_title',
        ),
        'elixar_blog_desc'              => array(
            'selector' => '#blog_desc',
        ),
        //CTA
        'elixar_cta_btn1_text'          => array(
            'selector' => '#cta_btn_txt',
        ),
        //Extra
        'elixar_section_extra_title'           => array(
            'selector' => '#about_title',
        ),
        'elixar_section_extra_desc'            => array(
            'selector' => '#about_desc',
        ),
        'elixar_section_extra_boxes'           => array(
            'selector' => '#about-box-con',
        ),
        //Services
        'elixar_services_title'         => array(
            'selector' => '#service_title',
        ),
        'elixar_services_desc'          => array(
            'selector' => '#service_desc',
        ),
        'elixar_services'               => array(
            'selector' => '#service-box-con',
        ),
        //Footer Social
        'elixar_social_footer_disable'  => array(
            'selector' => '.elixar-footer-social',
        ),
        //Footer Copyright
        'elixar_copyright_text'             => array(
            'selector' => '.copyright-text',
        ),
    );
    // Abort if selective refresh is not available.
    if (!isset($wp_customize->selective_refresh)) {
        return;
    }
    foreach ($partial_refresh as $id => $opt) {
        $wp_customize->selective_refresh->add_partial($id, array(
            'selector'        => $opt['selector'],
            'render_callback' => 'elixar_get_theme_mod',
        ));
    }

    $wp_customize->selective_refresh->add_partial('blogname', array(
        'selector'        => '.site-title',
        'render_callback' => 'elixar_get_site_name',
    ));
    $wp_customize->selective_refresh->add_partial('blogdescription', array(
        'selector'        => '.site-description',
        'render_callback' => 'elixar_get_site_description',
    ));
    /**
     * Get site name.
     *
     * @since 2.1.5
     * @return string
     */
    function elixar_get_site_name()
    {
        return get_bloginfo('name', 'display');
    }
    /**
     * Get site description.
     *
     * @since 2.1.5
     * @return string
     */
    function elixar_get_site_description()
    {
        return get_bloginfo('description', 'display');
    }
    function elixar_get_theme_mod($mod){
    	return get_theme_mod($mod->settings[0]);
    }
}
add_action('customize_register', 'elixar_customize_register');
/**
 * Binds JS handlers to make Theme Customizer preview reload changes asynchronously.
 */
function elixar_customize_preview_js()
{
    wp_enqueue_script('elixar_customizer_livepreview', get_template_directory_uri() . '/js/customizer_js/js/elixar-customizer-livepreview.js', array('customize-preview', 'customize-selective-refresh'), false, true);
}
add_action('customize_preview_init', 'elixar_customize_preview_js', 65);
add_action('customize_controls_enqueue_scripts', 'elixar_customize_js_settings');
function elixar_customize_js_settings()
{
    if (!function_exists('elixar_get_recommended_actions')) {
        return;
    }
    $actions       = elixar_get_recommended_actions();
    $number_action = $actions['number_notice'];
    wp_localize_script('customize-controls', 'elixar_customizer_settings', array(
        'number_action'     => $number_action,
        'is_plus_activated' => class_exists('Elixar_Premium') ? 'y' : 'n',
        'action_url'        => admin_url('themes.php?page=ft_elixar&tab=recommended_actions'),
    ));
}
/**
 * Customizer Icon picker
 */
function elixar_customize_controls_enqueue_scripts()
{
    wp_localize_script('customize-controls', 'Elixar_Icon_Picker',
        apply_filters('elixar_icon_picker_js_setup',
            array(
                'search' => esc_html__('Search', 'elixar'),
                'fonts'  => array(
                    'font-awesome' => array(
                        // Name of icon
                        'name'   => esc_html__('Font Awesome', 'elixar'),
                        // prefix class example for font-awesome fa-fa-{name}
                        'prefix' => '',
                        // font url
                        'url'    => esc_url(add_query_arg(array('ver' => '4.7.0'), get_template_directory_uri() . '/css/all.css')),
                        // Icon class name, separated by |
                        'icons'  => 'fab fa-500px|fab fa-accessible-icon|fab fa-accusoft|fab fa-adn|fab fa-adversal|fab fa-affiliatetheme|fab fa-algolia|fab fa-amazon|fab fa-amazon-pay|fab fa-amilia|fab fa-android|fab fa-angellist|fab fa-angrycreative|fab fa-angular|fab fa-app-store|fab fa-app-store-ios|fab fa-apper|fab fa-apple|fab fa-apple-pay|fab fa-asymmetrik|fab fa-audible|fab fa-autoprefixer|fab fa-avianex|fab fa-aviato|fab fa-aws|fab fa-bandcamp|fab fa-behance|fab fa-behance-square|fab fa-bimobject|fab fa-bitbucket|fab fa-bitcoin|fab fa-bity|fab fa-black-tie|fab fa-blackberry|fab fa-blogger|fab fa-blogger-b|fab fa-bluetooth|fab fa-bluetooth-b|fab fa-btc|fab fa-buromobelexperte|fab fa-buysellads|fab fa-cc-amazon-pay|fab fa-cc-amex|fab fa-cc-apple-pay|fab fa-cc-diners-club|fab fa-cc-discover|fab fa-cc-jcb|fab fa-cc-mastercard|fab fa-cc-paypal|fab fa-cc-stripe|fab fa-cc-visa|fab fa-centercode|fab fa-chrome|fab fa-cloudscale|fab fa-cloudsmith|fab fa-cloudversify|fab fa-codepen|fab fa-codiepie|fab fa-connectdevelop|fab fa-contao|fab fa-cpanel|fab fa-creative-commons|fab fa-creative-commons-by|fab fa-creative-commons-nc|fab fa-creative-commons-nc-eu|fab fa-creative-commons-nc-jp|fab fa-creative-commons-nd|fab fa-creative-commons-pd|fab fa-creative-commons-pd-alt|fab fa-creative-commons-remix|fab fa-creative-commons-sa|fab fa-creative-commons-sampling|fab fa-creative-commons-sampling-plus|fab fa-creative-commons-share|fab fa-css3|fab fa-css3-alt|fab fa-cuttlefish|fab fa-d-and-d|fab fa-dashcube|fab fa-delicious|fab fa-deploydog|fab fa-deskpro|fab fa-deviantart|fab fa-digg|fab fa-digital-ocean|fab fa-discord|fab fa-discourse|fab fa-dochub|fab fa-docker|fab fa-draft2digital|fab fa-dribbble|fab fa-dribbble-square|fab fa-dropbox|fab fa-drupal|fab fa-dyalog|fab fa-earlybirds|fab fa-ebay|fab fa-edge|fab fa-elementor|fab fa-ember|fab fa-empire|fab fa-envira|fab fa-erlang|fab fa-ethereum|fab fa-etsy|fab fa-expeditedssl|fab fa-facebook|fab fa-facebook-f|fab fa-facebook-messenger|fab fa-facebook-square|fab fa-firefox|fab fa-first-order|fab fa-first-order-alt|fab fa-firstdraft|fab fa-flickr|fab fa-flipboard|fab fa-fly|fab fa-font-awesome|fab fa-font-awesome-alt|fab fa-font-awesome-flag|fab fa-font-awesome-logo-full|fab fa-fonticons|fab fa-fonticons-fi|fab fa-fort-awesome|fab fa-fort-awesome-alt|fab fa-forumbee|fab fa-foursquare|fab fa-free-code-camp|fab fa-freebsd|fab fa-fulcrum|fab fa-galactic-republic|fab fa-galactic-senate|fab fa-get-pocket|fab fa-gg|fab fa-gg-circle|fab fa-git|fab fa-git-square|fab fa-github|fab fa-github-alt|fab fa-github-square|fab fa-gitkraken|fab fa-gitlab|fab fa-gitter|fab fa-glide|fab fa-glide-g|fab fa-gofore|fab fa-goodreads|fab fa-goodreads-g|fab fa-google|fab fa-google-drive|fab fa-google-play|fab fa-google-plus|fab fa-google-plus-g|fab fa-google-plus-square|fab fa-google-wallet|fab fa-gratipay|fab fa-grav|fab fa-gripfire|fab fa-grunt|fab fa-gulp|fab fa-hacker-news|fab fa-hacker-news-square|fab fa-hips|fab fa-hire-a-helper|fab fa-hooli|fab fa-hornbill|fab fa-hotjar|fab fa-houzz|fab fa-html5|fab fa-hubspot|fab fa-imdb|fab fa-instagram|fab fa-internet-explorer|fab fa-ioxhost|fab fa-itunes|fab fa-itunes-note|fab fa-java|fab fa-jedi-order|fab fa-jenkins|fab fa-joget|fab fa-joomla|fab fa-js|fab fa-js-square|fab fa-jsfiddle|fab fa-keybase|fab fa-keycdn|fab fa-kickstarter|fab fa-kickstarter-k|fab fa-korvue|fab fa-laravel|fab fa-lastfm|fab fa-lastfm-square|fab fa-leanpub|fab fa-less|fab fa-line|fab fa-linkedin|fab fa-linkedin-in|fab fa-linode|fab fa-linux|fab fa-lyft|fab fa-magento|fab fa-mailchimp|fab fa-mandalorian|fab fa-mastodon|fab fa-maxcdn|fab fa-medapps|fab fa-medium|fab fa-medium-m|fab fa-medrt|fab fa-meetup|fab fa-megaport|fab fa-microsoft|fab fa-mix|fab fa-mixcloud|fab fa-mizuni|fab fa-modx|fab fa-monero|fab fa-napster|fab fa-nimblr|fab fa-nintendo-switch|fab fa-node|fab fa-node-js|fab fa-npm|fab fa-ns8|fab fa-nutritionix|fab fa-odnoklassniki|fab fa-odnoklassniki-square|fab fa-old-republic|fab fa-opencart|fab fa-openid|fab fa-opera|fab fa-optin-monster|fab fa-osi|fab fa-page4|fab fa-pagelines|fab fa-palfed|fab fa-patreon|fab fa-paypal|fab fa-periscope|fab fa-phabricator|fab fa-phoenix-framework|fab fa-phoenix-squadron|fab fa-php|fab fa-pied-piper|fab fa-pied-piper-alt|fab fa-pied-piper-hat|fab fa-pied-piper-pp|fab fa-pinterest|fab fa-pinterest-p|fab fa-pinterest-square|fab fa-playstation|fab fa-product-hunt|fab fa-pushed|fab fa-python|fab fa-qq|fab fa-quinscape|fab fa-quora|fab fa-r-project|fab fa-ravelry|fab fa-react|fab fa-readme|fab fa-rebel|fab fa-red-river|fab fa-reddit|fab fa-reddit-alien|fab fa-reddit-square|fab fa-rendact|fab fa-renren|fab fa-replyd|fab fa-researchgate|fab fa-resolving|fab fa-rocketchat|fab fa-rockrms|fab fa-safari|fab fa-sass|fab fa-schlix|fab fa-scribd|fab fa-searchengin|fab fa-sellcast|fab fa-sellsy|fab fa-servicestack|fab fa-shirtsinbulk|fab fa-shopware|fab fa-simplybuilt|fab fa-sistrix|fab fa-sith|fab fa-skyatlas|fab fa-skype|fab fa-slack|fab fa-slack-hash|fab fa-slideshare|fab fa-snapchat|fab fa-snapchat-ghost|fab fa-snapchat-square|fab fa-soundcloud|fab fa-speakap|fab fa-spotify|fab fa-squarespace|fab fa-stack-exchange|fab fa-stack-overflow|fab fa-staylinked|fab fa-steam|fab fa-steam-square|fab fa-steam-symbol|fab fa-sticker-mule|fab fa-strava|fab fa-stripe|fab fa-stripe-s|fab fa-studiovinari|fab fa-stumbleupon|fab fa-stumbleupon-circle|fab fa-superpowers|fab fa-supple|fab fa-teamspeak|fab fa-telegram|fab fa-telegram-plane|fab fa-tencent-weibo|fab fa-themeco|fab fa-themeisle|fab fa-trade-federation|fab fa-trello|fab fa-tripadvisor|fab fa-tumblr|fab fa-tumblr-square|fab fa-twitch|fab fa-twitter|fab fa-twitter-square|fab fa-typo3|fab fa-uber|fab fa-uikit|fab fa-uniregistry|fab fa-untappd|fab fa-usb|fab fa-ussunnah|fab fa-vaadin|fab fa-viacoin|fab fa-viadeo|fab fa-viadeo-square|fab fa-viber|fab fa-vimeo|fab fa-vimeo-square|fab fa-vimeo-v|fab fa-vine|fab fa-vk|fab fa-vnv|fab fa-vuejs|fab fa-weebly|fab fa-weibo|fab fa-weixin|fab fa-whatsapp|fab fa-whatsapp-square|fab fa-whmcs|fab fa-wikipedia-w|fab fa-windows|fab fa-wix|fab fa-wolf-pack-battalion|fab fa-wordpress|fab fa-wordpress-simple|fab fa-wpbeginner|fab fa-wpexplorer|fab fa-wpforms|fab fa-xbox|fab fa-xing|fab fa-xing-square|fab fa-y-combinator|fab fa-yahoo|fab fa-yandex|fab fa-yandex-international|fab fa-yelp|fab fa-yoast|fab fa-youtube|fab fa-youtube-square|far fa-address-book|far fa-address-card|far fa-angry|far fa-arrow-alt-circle-down|far fa-arrow-alt-circle-left|far fa-arrow-alt-circle-right|far fa-arrow-alt-circle-up|far fa-bell|far fa-bell-slash|far fa-bookmark|far fa-building|far fa-calendar|far fa-calendar-alt|far fa-calendar-check|far fa-calendar-minus|far fa-calendar-plus|far fa-calendar-times|far fa-caret-square-down|far fa-caret-square-left|far fa-caret-square-right|far fa-caret-square-up|far fa-chart-bar|far fa-check-circle|far fa-check-square|far fa-circle|far fa-clipboard|far fa-clock|far fa-clone|far fa-closed-captioning|far fa-comment|far fa-comment-alt|far fa-comment-dots|far fa-comments|far fa-compass|far fa-copy|far fa-copyright|far fa-credit-card|far fa-dizzy|far fa-dot-circle|far fa-edit|far fa-envelope|far fa-envelope-open|far fa-eye|far fa-eye-slash|far fa-file|far fa-file-alt|far fa-file-archive|far fa-file-audio|far fa-file-code|far fa-file-excel|far fa-file-image|far fa-file-pdf|far fa-file-powerpoint|far fa-file-video|far fa-file-word|far fa-flag|far fa-flushed|far fa-folder|far fa-folder-open|far fa-font-awesome-logo-full|far fa-frown|far fa-frown-open|far fa-futbol|far fa-gem|far fa-grimace|far fa-grin|far fa-grin-alt|far fa-grin-beam|far fa-grin-beam-sweat|far fa-grin-hearts|far fa-grin-squint|far fa-grin-squint-tears|far fa-grin-stars|far fa-grin-tears|far fa-grin-tongue|far fa-grin-tongue-squint|far fa-grin-tongue-wink|far fa-grin-wink|far fa-hand-lizard|far fa-hand-paper|far fa-hand-peace|far fa-hand-point-down|far fa-hand-point-left|far fa-hand-point-right|far fa-hand-point-up|far fa-hand-pointer|far fa-hand-rock|far fa-hand-scissors|far fa-hand-spock|far fa-handshake|far fa-hdd|far fa-heart|far fa-hospital|far fa-hourglass|far fa-id-badge|far fa-id-card|far fa-image|far fa-images|far fa-keyboard|far fa-kiss|far fa-kiss-beam|far fa-kiss-wink-heart|far fa-laugh|far fa-laugh-beam|far fa-laugh-squint|far fa-laugh-wink|far fa-lemon|far fa-life-ring|far fa-lightbulb|far fa-list-alt|far fa-map|far fa-meh|far fa-meh-blank|far fa-meh-rolling-eyes|far fa-minus-square|far fa-money-bill-alt|far fa-moon|far fa-newspaper|far fa-object-group|far fa-object-ungroup|far fa-paper-plane|far fa-pause-circle|far fa-play-circle|far fa-plus-square|far fa-question-circle|far fa-registered|far fa-sad-cry|far fa-sad-tear|far fa-save|far fa-share-square|far fa-smile|far fa-smile-beam|far fa-smile-wink|far fa-snowflake|far fa-square|far fa-star|far fa-star-half|far fa-sticky-note|far fa-stop-circle|far fa-sun|far fa-surprise|far fa-thumbs-down|far fa-thumbs-up|far fa-times-circle|far fa-tired|far fa-trash-alt|far fa-user|far fa-user-circle|far fa-window-close|far fa-window-maximize|far fa-window-minimize|far fa-window-restore|fas fa-address-book|fas fa-address-card|fas fa-adjust|fas fa-align-center|fas fa-align-justify|fas fa-align-left|fas fa-align-right|fas fa-allergies|fas fa-ambulance|fas fa-american-sign-language-interpreting|fas fa-anchor|fas fa-angle-double-down|fas fa-angle-double-left|fas fa-angle-double-right|fas fa-angle-double-up|fas fa-angle-down|fas fa-angle-left|fas fa-angle-right|fas fa-angle-up|fas fa-angry|fas fa-archive|fas fa-archway|fas fa-arrow-alt-circle-down|fas fa-arrow-alt-circle-left|fas fa-arrow-alt-circle-right|fas fa-arrow-alt-circle-up|fas fa-arrow-circle-down|fas fa-arrow-circle-left|fas fa-arrow-circle-right|fas fa-arrow-circle-up|fas fa-arrow-down|fas fa-arrow-left|fas fa-arrow-right|fas fa-arrow-up|fas fa-arrows-alt|fas fa-arrows-alt-h|fas fa-arrows-alt-v|fas fa-assistive-listening-systems|fas fa-asterisk|fas fa-at|fas fa-atlas|fas fa-audio-description|fas fa-award|fas fa-backspace|fas fa-backward|fas fa-balance-scale|fas fa-ban|fas fa-band-aid|fas fa-barcode|fas fa-bars|fas fa-baseball-ball|fas fa-basketball-ball|fas fa-bath|fas fa-battery-empty|fas fa-battery-full|fas fa-battery-half|fas fa-battery-quarter|fas fa-battery-three-quarters|fas fa-bed|fas fa-beer|fas fa-bell|fas fa-bell-slash|fas fa-bezier-curve|fas fa-bicycle|fas fa-binoculars|fas fa-birthday-cake|fas fa-blender|fas fa-blind|fas fa-bold|fas fa-bolt|fas fa-bomb|fas fa-bong|fas fa-book|fas fa-book-open|fas fa-bookmark|fas fa-bowling-ball|fas fa-box|fas fa-box-open|fas fa-boxes|fas fa-braille|fas fa-briefcase|fas fa-briefcase-medical|fas fa-broadcast-tower|fas fa-broom|fas fa-brush|fas fa-bug|fas fa-building|fas fa-bullhorn|fas fa-bullseye|fas fa-burn|fas fa-bus|fas fa-bus-alt|fas fa-calculator|fas fa-calendar|fas fa-calendar-alt|fas fa-calendar-check|fas fa-calendar-minus|fas fa-calendar-plus|fas fa-calendar-times|fas fa-camera|fas fa-camera-retro|fas fa-cannabis|fas fa-capsules|fas fa-car|fas fa-caret-down|fas fa-caret-left|fas fa-caret-right|fas fa-caret-square-down|fas fa-caret-square-left|fas fa-caret-square-right|fas fa-caret-square-up|fas fa-caret-up|fas fa-cart-arrow-down|fas fa-cart-plus|fas fa-certificate|fas fa-chalkboard|fas fa-chalkboard-teacher|fas fa-chart-area|fas fa-chart-bar|fas fa-chart-line|fas fa-chart-pie|fas fa-check|fas fa-check-circle|fas fa-check-double|fas fa-check-square|fas fa-chess|fas fa-chess-bishop|fas fa-chess-board|fas fa-chess-king|fas fa-chess-knight|fas fa-chess-pawn|fas fa-chess-queen|fas fa-chess-rook|fas fa-chevron-circle-down|fas fa-chevron-circle-left|fas fa-chevron-circle-right|fas fa-chevron-circle-up|fas fa-chevron-down|fas fa-chevron-left|fas fa-chevron-right|fas fa-chevron-up|fas fa-child|fas fa-church|fas fa-circle|fas fa-circle-notch|fas fa-clipboard|fas fa-clipboard-check|fas fa-clipboard-list|fas fa-clock|fas fa-clone|fas fa-closed-captioning|fas fa-cloud|fas fa-cloud-download-alt|fas fa-cloud-upload-alt|fas fa-cocktail|fas fa-code|fas fa-code-branch|fas fa-coffee|fas fa-cog|fas fa-cogs|fas fa-coins|fas fa-columns|fas fa-comment|fas fa-comment-alt|fas fa-comment-dots|fas fa-comment-slash|fas fa-comments|fas fa-compact-disc|fas fa-compass|fas fa-compress|fas fa-concierge-bell|fas fa-cookie|fas fa-cookie-bite|fas fa-copy|fas fa-copyright|fas fa-couch|fas fa-credit-card|fas fa-crop|fas fa-crop-alt|fas fa-crosshairs|fas fa-crow|fas fa-crown|fas fa-cube|fas fa-cubes|fas fa-cut|fas fa-database|fas fa-deaf|fas fa-desktop|fas fa-diagnoses|fas fa-dice|fas fa-dice-five|fas fa-dice-four|fas fa-dice-one|fas fa-dice-six|fas fa-dice-three|fas fa-dice-two|fas fa-digital-tachograph|fas fa-divide|fas fa-dizzy|fas fa-dna|fas fa-dollar-sign|fas fa-dolly|fas fa-dolly-flatbed|fas fa-donate|fas fa-door-closed|fas fa-door-open|fas fa-dot-circle|fas fa-dove|fas fa-download|fas fa-drafting-compass|fas fa-drum|fas fa-drum-steelpan|fas fa-dumbbell|fas fa-edit|fas fa-eject|fas fa-ellipsis-h|fas fa-ellipsis-v|fas fa-envelope|fas fa-envelope-open|fas fa-envelope-square|fas fa-equals|fas fa-eraser|fas fa-euro-sign|fas fa-exchange-alt|fas fa-exclamation|fas fa-exclamation-circle|fas fa-exclamation-triangle|fas fa-expand|fas fa-expand-arrows-alt|fas fa-external-link-alt|fas fa-external-link-square-alt|fas fa-eye|fas fa-eye-dropper|fas fa-eye-slash|fas fa-fast-backward|fas fa-fast-forward|fas fa-fax|fas fa-feather|fas fa-feather-alt|fas fa-female|fas fa-fighter-jet|fas fa-file|fas fa-file-alt|fas fa-file-archive|fas fa-file-audio|fas fa-file-code|fas fa-file-contract|fas fa-file-download|fas fa-file-excel|fas fa-file-export|fas fa-file-image|fas fa-file-import|fas fa-file-invoice|fas fa-file-invoice-dollar|fas fa-file-medical|fas fa-file-medical-alt|fas fa-file-pdf|fas fa-file-powerpoint|fas fa-file-prescription|fas fa-file-signature|fas fa-file-upload|fas fa-file-video|fas fa-file-word|fas fa-fill|fas fa-fill-drip|fas fa-film|fas fa-filter|fas fa-fingerprint|fas fa-fire|fas fa-fire-extinguisher|fas fa-first-aid|fas fa-fish|fas fa-flag|fas fa-flag-checkered|fas fa-flask|fas fa-flushed|fas fa-folder|fas fa-folder-open|fas fa-font|fas fa-font-awesome-logo-full|fas fa-football-ball|fas fa-forward|fas fa-frog|fas fa-frown|fas fa-frown-open|fas fa-futbol|fas fa-gamepad|fas fa-gas-pump|fas fa-gavel|fas fa-gem|fas fa-genderless|fas fa-gift|fas fa-glass-martini|fas fa-glass-martini-alt|fas fa-glasses|fas fa-globe|fas fa-globe-africa|fas fa-globe-americas|fas fa-globe-asia|fas fa-golf-ball|fas fa-graduation-cap|fas fa-greater-than|fas fa-greater-than-equal|fas fa-grimace|fas fa-grin|fas fa-grin-alt|fas fa-grin-beam|fas fa-grin-beam-sweat|fas fa-grin-hearts|fas fa-grin-squint|fas fa-grin-squint-tears|fas fa-grin-stars|fas fa-grin-tears|fas fa-grin-tongue|fas fa-grin-tongue-squint|fas fa-grin-tongue-wink|fas fa-grin-wink|fas fa-grip-horizontal|fas fa-grip-vertical|fas fa-h-square|fas fa-hand-holding|fas fa-hand-holding-heart|fas fa-hand-holding-usd|fas fa-hand-lizard|fas fa-hand-paper|fas fa-hand-peace|fas fa-hand-point-down|fas fa-hand-point-left|fas fa-hand-point-right|fas fa-hand-point-up|fas fa-hand-pointer|fas fa-hand-rock|fas fa-hand-scissors|fas fa-hand-spock|fas fa-hands|fas fa-hands-helping|fas fa-handshake|fas fa-hashtag|fas fa-hdd|fas fa-heading|fas fa-headphones|fas fa-headphones-alt|fas fa-headset|fas fa-heart|fas fa-heartbeat|fas fa-helicopter|fas fa-highlighter|fas fa-history|fas fa-hockey-puck|fas fa-home|fas fa-hospital|fas fa-hospital-alt|fas fa-hospital-symbol|fas fa-hot-tub|fas fa-hotel|fas fa-hourglass|fas fa-hourglass-end|fas fa-hourglass-half|fas fa-hourglass-start|fas fa-i-cursor|fas fa-id-badge|fas fa-id-card|fas fa-id-card-alt|fas fa-image|fas fa-images|fas fa-inbox|fas fa-indent|fas fa-industry|fas fa-infinity|fas fa-info|fas fa-info-circle|fas fa-italic|fas fa-joint|fas fa-key|fas fa-keyboard|fas fa-kiss|fas fa-kiss-beam|fas fa-kiss-wink-heart|fas fa-kiwi-bird|fas fa-language|fas fa-laptop|fas fa-laugh|fas fa-laugh-beam|fas fa-laugh-squint|fas fa-laugh-wink|fas fa-leaf|fas fa-lemon|fas fa-less-than|fas fa-less-than-equal|fas fa-level-down-alt|fas fa-level-up-alt|fas fa-life-ring|fas fa-lightbulb|fas fa-link|fas fa-lira-sign|fas fa-list|fas fa-list-alt|fas fa-list-ol|fas fa-list-ul|fas fa-location-arrow|fas fa-lock|fas fa-lock-open|fas fa-long-arrow-alt-down|fas fa-long-arrow-alt-left|fas fa-long-arrow-alt-right|fas fa-long-arrow-alt-up|fas fa-low-vision|fas fa-luggage-cart|fas fa-magic|fas fa-magnet|fas fa-male|fas fa-map|fas fa-map-marked|fas fa-map-marked-alt|fas fa-map-marker|fas fa-map-marker-alt|fas fa-map-pin|fas fa-map-signs|fas fa-marker|fas fa-mars|fas fa-mars-double|fas fa-mars-stroke|fas fa-mars-stroke-h|fas fa-mars-stroke-v|fas fa-medal|fas fa-medkit|fas fa-meh|fas fa-meh-blank|fas fa-meh-rolling-eyes|fas fa-memory|fas fa-mercury|fas fa-microchip|fas fa-microphone|fas fa-microphone-alt|fas fa-microphone-alt-slash|fas fa-microphone-slash|fas fa-minus|fas fa-minus-circle|fas fa-minus-square|fas fa-mobile|fas fa-mobile-alt|fas fa-money-bill|fas fa-money-bill-alt|fas fa-money-bill-wave|fas fa-money-bill-wave-alt|fas fa-money-check|fas fa-money-check-alt|fas fa-monument|fas fa-moon|fas fa-mortar-pestle|fas fa-motorcycle|fas fa-mouse-pointer|fas fa-music|fas fa-neuter|fas fa-newspaper|fas fa-not-equal|fas fa-notes-medical|fas fa-object-group|fas fa-object-ungroup|fas fa-outdent|fas fa-paint-brush|fas fa-paint-roller|fas fa-palette|fas fa-pallet|fas fa-paper-plane|fas fa-paperclip|fas fa-parachute-box|fas fa-paragraph|fas fa-parking|fas fa-passport|fas fa-paste|fas fa-pause|fas fa-pause-circle|fas fa-paw|fas fa-pen|fas fa-pen-alt|fas fa-pen-fancy|fas fa-pen-nib|fas fa-pen-square|fas fa-pencil-alt|fas fa-pencil-ruler|fas fa-people-carry|fas fa-percent|fas fa-percentage|fas fa-phone|fas fa-phone-slash|fas fa-phone-square|fas fa-phone-volume|fas fa-piggy-bank|fas fa-pills|fas fa-plane|fas fa-plane-arrival|fas fa-plane-departure|fas fa-play|fas fa-play-circle|fas fa-plug|fas fa-plus|fas fa-plus-circle|fas fa-plus-square|fas fa-podcast|fas fa-poo|fas fa-portrait|fas fa-pound-sign|fas fa-power-off|fas fa-prescription|fas fa-prescription-bottle|fas fa-prescription-bottle-alt|fas fa-print|fas fa-procedures|fas fa-project-diagram|fas fa-puzzle-piece|fas fa-qrcode|fas fa-question|fas fa-question-circle|fas fa-quidditch|fas fa-quote-left|fas fa-quote-right|fas fa-random|fas fa-receipt|fas fa-recycle|fas fa-redo|fas fa-redo-alt|fas fa-registered|fas fa-reply|fas fa-reply-all|fas fa-retweet|fas fa-ribbon|fas fa-road|fas fa-robot|fas fa-rocket|fas fa-rss|fas fa-rss-square|fas fa-ruble-sign|fas fa-ruler|fas fa-ruler-combined|fas fa-ruler-horizontal|fas fa-ruler-vertical|fas fa-rupee-sign|fas fa-sad-cry|fas fa-sad-tear|fas fa-save|fas fa-school|fas fa-screwdriver|fas fa-search|fas fa-search-minus|fas fa-search-plus|fas fa-seedling|fas fa-server|fas fa-share|fas fa-share-alt|fas fa-share-alt-square|fas fa-share-square|fas fa-shekel-sign|fas fa-shield-alt|fas fa-ship|fas fa-shipping-fast|fas fa-shoe-prints|fas fa-shopping-bag|fas fa-shopping-basket|fas fa-shopping-cart|fas fa-shower|fas fa-shuttle-van|fas fa-sign|fas fa-sign-in-alt|fas fa-sign-language|fas fa-sign-out-alt|fas fa-signal|fas fa-signature|fas fa-sitemap|fas fa-skull|fas fa-sliders-h|fas fa-smile|fas fa-smile-beam|fas fa-smile-wink|fas fa-smoking|fas fa-smoking-ban|fas fa-snowflake|fas fa-solar-panel|fas fa-sort|fas fa-sort-alpha-down|fas fa-sort-alpha-up|fas fa-sort-amount-down|fas fa-sort-amount-up|fas fa-sort-down|fas fa-sort-numeric-down|fas fa-sort-numeric-up|fas fa-sort-up|fas fa-spa|fas fa-space-shuttle|fas fa-spinner|fas fa-splotch|fas fa-spray-can|fas fa-square|fas fa-square-full|fas fa-stamp|fas fa-star|fas fa-star-half|fas fa-star-half-alt|fas fa-step-backward|fas fa-step-forward|fas fa-stethoscope|fas fa-sticky-note|fas fa-stop|fas fa-stop-circle|fas fa-stopwatch|fas fa-store|fas fa-store-alt|fas fa-stream|fas fa-street-view|fas fa-strikethrough|fas fa-stroopwafel|fas fa-subscript|fas fa-subway|fas fa-suitcase|fas fa-suitcase-rolling|fas fa-sun|fas fa-superscript|fas fa-surprise|fas fa-swatchbook|fas fa-swimmer|fas fa-swimming-pool|fas fa-sync|fas fa-sync-alt|fas fa-syringe|fas fa-table|fas fa-table-tennis|fas fa-tablet|fas fa-tablet-alt|fas fa-tablets|fas fa-tachometer-alt|fas fa-tag|fas fa-tags|fas fa-tape|fas fa-tasks|fas fa-taxi|fas fa-terminal|fas fa-text-height|fas fa-text-width|fas fa-th|fas fa-th-large|fas fa-th-list|fas fa-thermometer|fas fa-thermometer-empty|fas fa-thermometer-full|fas fa-thermometer-half|fas fa-thermometer-quarter|fas fa-thermometer-three-quarters|fas fa-thumbs-down|fas fa-thumbs-up|fas fa-thumbtack|fas fa-ticket-alt|fas fa-times|fas fa-times-circle|fas fa-tint|fas fa-tint-slash|fas fa-tired|fas fa-toggle-off|fas fa-toggle-on|fas fa-toolbox|fas fa-tooth|fas fa-trademark|fas fa-train|fas fa-transgender|fas fa-transgender-alt|fas fa-trash|fas fa-trash-alt|fas fa-tree|fas fa-trophy|fas fa-truck|fas fa-truck-loading|fas fa-truck-moving|fas fa-tshirt|fas fa-tty|fas fa-tv|fas fa-umbrella|fas fa-umbrella-beach|fas fa-underline|fas fa-undo|fas fa-undo-alt|fas fa-universal-access|fas fa-university|fas fa-unlink|fas fa-unlock|fas fa-unlock-alt|fas fa-upload|fas fa-user|fas fa-user-alt|fas fa-user-alt-slash|fas fa-user-astronaut|fas fa-user-check|fas fa-user-circle|fas fa-user-clock|fas fa-user-cog|fas fa-user-edit|fas fa-user-friends|fas fa-user-graduate|fas fa-user-lock|fas fa-user-md|fas fa-user-minus|fas fa-user-ninja|fas fa-user-plus|fas fa-user-secret|fas fa-user-shield|fas fa-user-slash|fas fa-user-tag|fas fa-user-tie|fas fa-user-times|fas fa-users|fas fa-users-cog|fas fa-utensil-spoon|fas fa-utensils|fas fa-vector-square|fas fa-venus|fas fa-venus-double|fas fa-venus-mars|fas fa-vial|fas fa-vials|fas fa-video|fas fa-video-slash|fas fa-volleyball-ball|fas fa-volume-down|fas fa-volume-off|fas fa-volume-up|fas fa-walking|fas fa-wallet|fas fa-warehouse|fas fa-weight|fas fa-weight-hanging|fas fa-wheelchair|fas fa-wifi|fas fa-window-close|fas fa-window-maximize|fas fa-window-minimize|fas fa-window-restore|fas fa-wine-glass|fas fa-wine-glass-alt|fas fa-won-sign|fas fa-wrench|fas fa-x-ray|fas fa-yen-sign',
                    ),
                ),
            )
        )
    );
}
add_action('customize_controls_enqueue_scripts', 'elixar_customize_controls_enqueue_scripts');
