<?php
/**
 * Add theme dashboard page
 *
 * Get theme actions required
 *
 * @return array|mixed|void
 *
 * @package elixar
 */
function elixar_get_recommended_actions( ) {
    $actions = array();
    $front_page = get_option( 'page_on_front' );
    $actions['page_on_front'] = 'dismiss';
    $actions['page_template'] = 'dismiss';
    $actions['recommend_plugins'] = 'dismiss';
    if ( 'page' != get_option( 'show_on_front' ) ) {
        $front_page = 0;
    }
    if ( $front_page <= 0  ) {
        $actions['page_on_front'] = 'active';
        $actions['page_template'] = 'active';
    } else {
        if ( get_post_meta( $front_page, '_wp_page_template', true ) == 'home-page.php' ) {
            $actions['page_template'] = 'dismiss';
        } else {
            $actions['page_template'] = 'active';
        }
    }
    $recommend_plugins = get_theme_support( 'recommend-plugins' );
    if ( is_array( $recommend_plugins ) && isset( $recommend_plugins[0] ) ){
        $recommend_plugins = $recommend_plugins[0];
    } else {
        $recommend_plugins[] = array();
    }
    if ( ! empty( $recommend_plugins ) ) {
        foreach ( $recommend_plugins as $plugin_slug => $plugin_info ) {
            $plugin_info = wp_parse_args( $plugin_info, array(
                'name' => '',
                'active_filename' => '',
            ) );
            if ( $plugin_info['active_filename'] ) {
                $active_file_name = $plugin_info['active_filename'] ;
            } else {
                $active_file_name = $plugin_slug . '/' . $plugin_slug . '.php';
            }
            if ( ! is_plugin_active( $active_file_name ) ) {
                $actions['recommend_plugins'] = 'active';
            }
        }
    }
    $actions = apply_filters( 'elixar_get_recommended_actions', $actions );
    $hide_by_click = get_option( 'elixar_actions_dismiss' );
    if ( ! is_array( $hide_by_click ) ) {
        $hide_by_click = array();
    }
    $n_active  = $n_dismiss = 0;
    $number_notice = 0;
    foreach ( $actions as $k => $v ) {
        if ( ! isset( $hide_by_click[ $k ] ) ) {
            $hide_by_click[ $k ] = false;
        }
        if ( $v == 'active' ) {
            $n_active ++ ;
            $number_notice ++ ;
            if ( $hide_by_click[ $k ] ) {
                if ( $hide_by_click[ $k ] == 'hide' ) {
                    $number_notice -- ;
                }
            }
        } else if ( $v == 'dismiss' ) {
            $n_dismiss ++ ;
        }
    }
    $elixar_return = array(
        'actions' => $actions,
        'number_actions' => count( $actions ),
        'number_active' => $n_active,
        'number_dismiss' => $n_dismiss,
        'hide_by_click'  => $hide_by_click,
        'number_notice'  => $number_notice,
    );
    if ( $elixar_return['number_notice'] < 0 ) {
        $elixar_return['number_notice'] = 0;
    }
    return $elixar_return;
}
add_action('switch_theme', 'elixar_reset_recommended_actions');
function elixar_reset_recommended_actions () {
    delete_option('elixar_actions_dismiss');
}
if ( ! function_exists( 'elixar_admin_scripts' ) ) :
    /**
     * Enqueue scripts for admin page only: Theme info page
     */
    function elixar_admin_scripts( $hook ) {
        if ( $hook === 'widgets.php' || $hook === 'appearance_page_ft_elixar'  ) {
            wp_enqueue_style( 'elixar-admin-css', get_template_directory_uri() . '/css/customizer_css/css/elixar-admin.css' );
			wp_enqueue_style( 'elixar-plugins', get_template_directory_uri() . '/css/customizer_css/css/recommend.css' );
            // Add recommend plugin css
            wp_enqueue_style( 'plugin-install' );
            wp_enqueue_script( 'plugin-install' );
            wp_enqueue_script( 'updates' );
            add_thickbox();
        }
		 wp_enqueue_style( 'elixar-admincss', get_template_directory_uri() . '/css/elixar-admin.css' );
    }
endif;
add_action( 'admin_enqueue_scripts', 'elixar_admin_scripts' );
add_action('admin_menu', 'elixar_theme_info');
function elixar_theme_info() {
    $actions = elixar_get_recommended_actions();
    $number_count = $actions['number_notice'];
    if ( $number_count > 0 ){
        /* translators: %s: Update Label Text */
		$update_label = sprintf( _n( '%1$s action required', '%1$s actions required', $number_count, 'elixar' ), $number_count );
        $count = "<span class='update-plugins count-".esc_attr( $number_count )."' title='".esc_attr( $update_label )."'><span class='update-count'>" . number_format_i18n($number_count) . "</span></span>";
        /* translators: %s: Menu Title */
		$menu_title = sprintf( esc_html__('Elixar Theme %s', 'elixar'), $count );
    } else {
        $menu_title = esc_html__('Elixar Theme', 'elixar');
    }
    add_theme_page( esc_html__( 'Elixar Dashboard', 'elixar' ), $menu_title, 'edit_theme_options', 'ft_elixar', 'elixar_theme_info_page');
}
/**
 * Add admin notice when active theme, just show one timetruongsa@200811
 *
 * @return bool|null
 */
function elixar_admin_notice() {
    if ( ! function_exists( 'elixar_get_recommended_actions' ) ) {
        return false;
    }
    $actions = elixar_get_recommended_actions();
    $number_action = $actions['number_notice'];
    if ( $number_action > 0 ) {
        $theme_data = wp_get_theme();
        ?>
        <div class="updated notice notice-success notice-alt is-dismissible">
            <p><?php /* translators: %s: Welcome Page Notice */
			printf( esc_html__( 'Welcome! Thank you for choosing %1$s! To fully take advantage of the best our theme can offer please make sure you visit our %2$s Welcome page %3$s', 'elixar' ),  esc_html( $theme_data->Name ), '<a href="'. esc_url(admin_url( 'themes.php?page=ft_elixar' )).'">', '</a>' ); ?></p>
        </div>
        <?php
    }
}
function elixar_admin_import_notice(){
    ?>
    <div class="updated notice notice-success notice-alt is-dismissible">
        <p><?php /* translators: %s: Import Data Info */
		printf( esc_html__( 'Save time by import our demo data, your website will be set up and ready to customize in minutes. %s', 'elixar' ), '<a class="button button-secondary" href="'.esc_url( add_query_arg( array( 'page' => 'ft_elixar&tab=demo-data-importer' ), admin_url( 'themes.php' ) ) ).'">'.esc_html__( 'Import Demo Data', 'elixar' ).'</a>' ); ?></p>
    </div>
    <?php
}
function elixar_one_activation_admin_notice(){
    global $pagenow;
    if ( is_admin() && ('themes.php' == $pagenow) && isset( $_GET['activated'] ) ) {
        add_action( 'admin_notices', 'elixar_admin_notice' );
        add_action( 'admin_notices', 'elixar_admin_import_notice' );
    }
}
function elixar_render_recommend_plugins( $recommend_plugins = array() ){
    foreach ( $recommend_plugins as $plugin_slug => $plugin_info ) {
        $plugin_info = wp_parse_args( $plugin_info, array(
            'name' => '',
            'active_filename' => '',
        ) );
        $plugin_name = $plugin_info['name'];
        $status = is_dir( WP_PLUGIN_DIR . '/' . $plugin_slug );
        $button_class = 'install-now button';
        if ( $plugin_info['active_filename'] ) {
            $active_file_name = $plugin_info['active_filename'] ;
        } else {
            $active_file_name = $plugin_slug . '/' . $plugin_slug . '.php';
        }
        if ( ! is_plugin_active( $active_file_name ) ) {
            $button_txt = esc_html__( 'Install Now', 'elixar' );
            if ( ! $status ) {
                $install_url = wp_nonce_url(
                    add_query_arg(
                        array(
                            'action' => 'install-plugin',
                            'plugin' => $plugin_slug
                        ),
                        network_admin_url( 'update.php' )
                    ),
                    'install-plugin_'.$plugin_slug
                );
            } else {
                $install_url = add_query_arg(array(
                    'action' => 'activate',
                    'plugin' => rawurlencode( $active_file_name ),
                    'plugin_status' => 'all',
                    'paged' => '1',
                    '_wpnonce' => wp_create_nonce('activate-plugin_' . $active_file_name ),
                ), network_admin_url('plugins.php'));
                $button_class = 'activate-now button-primary';
                $button_txt = esc_html__( 'Active Now', 'elixar' );
            }
            $detail_link = add_query_arg(
                array(
                    'tab' => 'plugin-information',
                    'plugin' => $plugin_slug,
                    'TB_iframe' => 'true',
                    'width' => '772',
                    'height' => '349',
                ),
                network_admin_url( 'plugin-install.php' )
            );
            echo '<div class="rcp">';
            echo '<h4 class="rcp-name">';
            echo esc_html( $plugin_name );
            echo '</h4>';
            echo '<p class="action-btn plugin-card-'.esc_attr( $plugin_slug ).'"><a href="'. esc_url( $install_url ) .'" data-slug="'. esc_attr( $plugin_slug ) .'" class="'. esc_attr( $button_class ) .'">'. esc_html( $button_txt ) .'</a></p>';
            echo '<a class="plugin-detail thickbox open-plugin-details-modal" href="'. esc_url( $detail_link ) .'">'. esc_html__( 'Details', 'elixar' ) .'</a>';
            echo '</div>';
        }
    }
}
function elixar_admin_dismiss_actions(){
    // Action for dismiss
    if ( isset( $_GET['elixar_action_notice'] ) ) {
        $actions_dismiss =  get_option( 'elixar_actions_dismiss' );
        if ( ! is_array( $actions_dismiss ) ) {
            $actions_dismiss = array();
        }
        $action_key = sanitize_text_field( wp_unslash($_GET['elixar_action_notice']) );
        if ( isset( $actions_dismiss[ $action_key ] ) &&  $actions_dismiss[ $action_key ] == 'hide' ){
            $actions_dismiss[ $action_key ] = 'show';
        } else {
            $actions_dismiss[ $action_key ] = 'hide';
        }
        update_option( 'elixar_actions_dismiss', $actions_dismiss );
		if ( isset( $_SERVER['REQUEST_URI'] ) ) {
			$url = esc_url_raw( wp_unslash( $_SERVER['REQUEST_URI'] ) );
		}
        $url = remove_query_arg( 'elixar_action_notice', $url );
        wp_redirect( $url );
        die();
    }
    // Action for copy options
    if ( isset( $_POST['copy_from'] ) && isset( $_POST['copy_to'] ) ) {
        $from = sanitize_text_field( wp_unslash($_POST['copy_from']) );
        $to = sanitize_text_field( wp_unslash($_POST['copy_to']) );
        if ( $from && $to ) {
            $mods = get_option("theme_mods_" . $from);
            update_option("theme_mods_" . $to, $mods);
			if ( isset( $_SERVER['REQUEST_URI'] ) ) {
				$url = esc_url_raw( wp_unslash( $_SERVER['REQUEST_URI'] ) );
			}
            $url = add_query_arg(array('copied' => 1), $url);
            wp_redirect($url);
            die();
        }
    }
}
add_action( 'admin_init', 'elixar_admin_dismiss_actions' );
/* activation notice */
add_action( 'load-themes.php',  'elixar_one_activation_admin_notice'  );
function elixar_theme_info_page() {
    $theme_data = wp_get_theme('elixar');
    if ( isset( $_GET['elixar_action_dismiss'] ) ) {
        $actions_dismiss =  get_option( 'elixar_actions_dismiss' );
        if ( ! is_array( $actions_dismiss ) ) {
            $actions_dismiss = array();
        }
        $actions_dismiss[ sanitize_text_field( wp_unslash($_GET['elixar_action_dismiss']) ) ] = 'dismiss';
        update_option( 'elixar_actions_dismiss', $actions_dismiss );
    }
    // Check for current viewing tab
    $elixar_tab = null;
    if ( isset( $_GET['tab'] ) ) {
        $elixar_tab = sanitize_text_field( wp_unslash($_GET['tab']) );
    } else {
        $elixar_tab = null;
    }
    $actions_r = elixar_get_recommended_actions();
    $number_action = intval( $actions_r['number_notice'] );
    $actions = $actions_r['actions'];
    $current_action_link =  admin_url( 'themes.php?page=ft_elixar&tab=recommended_actions' );
    $recommend_plugins = get_theme_support( 'recommend-plugins' );
    if ( is_array( $recommend_plugins ) && isset( $recommend_plugins[0] ) ){
        $recommend_plugins = $recommend_plugins[0];
    } else {
        $recommend_plugins[] = array();
    }
    ?>
    <div class="wrap about-wrap theme_info_wrapper">
        <h1><?php /* translators: %s: Theme Version Info */
		printf(esc_html__('Welcome to Elixar - Version %1s', 'elixar'), floatval( $theme_data->Version ) ); ?></h1>
        <div class="about-text"><?php esc_html_e( 'Elixar is a creative and flexible WordPress theme well suited for business, portfolio, digital agency, product showcase, freelancers websites.', 'elixar' ); ?></div>
        <a target="_blank" href="<?php echo esc_url('https://www.webhuntinfotech.com/'); ?>" class="webhuntthemes-badge wp-badge"><span><?php echo esc_html('WebhuntThemes'); ?></span></a>
        <h2 class="nav-tab-wrapper">
            <a href="?page=ft_elixar" class="nav-tab<?php echo is_null($elixar_tab) ? ' nav-tab-active' : null; ?>"><?php esc_html_e( 'Elixar', 'elixar' ) ?></a>
            <a href="?page=ft_elixar&tab=recommended_actions" class="nav-tab<?php echo $elixar_tab == 'recommended_actions' ? ' nav-tab-active' : null; ?>"><?php esc_html_e( 'Recommended Actions', 'elixar' ); echo ( $number_action > 0 ) ? "<span class='theme-action-count'>".  intval($number_action) ."</span>" : ''; ?></a>
			<a href="?page=ft_elixar&tab=our-plugins" class="nav-tab<?php echo $elixar_tab == 'our-plugins' ? ' nav-tab-active' : null; ?>"><?php esc_html_e( 'Our Plugins', 'elixar' ); ?></span></a>
			<a href="?page=ft_elixar&tab=our-themes" class="nav-tab<?php echo $elixar_tab == 'our-themes' ? ' nav-tab-active' : null; ?>"><?php esc_html_e( 'Our Themes', 'elixar' ); ?></span></a>
            <?php if ( ! class_exists('Elixar_Premium') ) { ?>
            <a href="?page=ft_elixar&tab=free_pro" class="nav-tab<?php echo $elixar_tab == 'free_pro' ? ' nav-tab-active' : null; ?>"><?php esc_html_e( 'Free vs Pro', 'elixar' ); ?></span></a>
            <?php } ?>
            <a href="?page=ft_elixar&tab=changelog-viewer" class="nav-tab<?php echo $elixar_tab == 'changelog-viewer' ? ' nav-tab-active' : null; ?>"><?php esc_html_e( 'Changelog', 'elixar' ); ?></span></a>
            <?php do_action( 'elixar_admin_more_tabs' ); ?>
        </h2>
        <?php if ( is_null( $elixar_tab ) ) { ?>
            <div class="theme_info info-tab-content">
                <div class="theme_info_column clearfix">
                    <div class="theme_info_left">
                        <div class="theme_link">
                            <h3><?php esc_html_e( 'Theme Customizer', 'elixar' ); ?></h3>
                            <p class="about"><?php /* translators: %s: Get Started */
							printf( esc_html__('%s supports the Theme Customizer for all theme settings. Click "Customize" to start customize your site.', 'elixar'), esc_html( $theme_data->Name ) ); ?></p>
                            <p>
                                <a href="<?php echo esc_url(admin_url('customize.php')); ?>" class="button button-primary"><?php esc_html_e('Start Customize', 'elixar'); ?></a>
                            </p>
                        </div>
                        <div class="theme_link">
                            <h3><?php esc_html_e( 'Theme Documentation', 'elixar' ); ?></h3>
                            <p class="about"><?php /* translators: %s: Documentation Info */
							printf(esc_html__('Need any help to setup and configure %s? Please have a look at our documentations instructions.', 'elixar'), esc_html( $theme_data->Name) ); ?></p>
                            <p>
                                <a href="<?php echo esc_url( 'https://www.webhuntinfotech.com/elixar-lite-documentation/' ); ?>" target="_blank" class="button button-primary"><?php esc_html_e('Elixar Documentation', 'elixar'); ?></a>
                            </p>
                            <?php do_action( 'elixar_dashboard_theme_links' ); ?>
                        </div>
						<div class="theme_link">
                            <h3><?php esc_html_e( 'Elixar Demo Importer Files', 'elixar' ); ?></h3>
                            <p class="about"><?php echo esc_html__('To save time by import our demo data, your website will be set up and ready to customize in minutes. Download demo importer package here and import it manually.', 'elixar'); ?></p>
                            <p>
                                <a href="<?php echo esc_url( 'http://demo.webhuntinfotech.com/documentation/wp-content/uploads/2019/07/elixar-demo-importer-files.zip' ); ?>" target="_blank" class="button button-primary"><?php esc_html_e('Download Demo Importer Files', 'elixar'); ?></a>
                            </p>
                            <?php do_action( 'elixar_dashboard_theme_links' ); ?>
                        </div>
						<div class="theme_link">
                            <h3><?php esc_html_e( 'Create a Child Theme', 'elixar' ); ?></h3>
                            <p class="about"><?php printf(esc_html__('If you want to make changes to the themes files, those changes are likely to be overwritten when you next update the theme. In order to prevent that from happening, you need to create a child theme. For this, please follow the documentation below.', 'elixar'), esc_html( $theme_data->Name )); ?></p>
                            <p>
                            <a href="<?php echo esc_url( 'https://www.webhuntinfotech.com/create-child-theme/' ); ?>" target="_blank" class="button button-primary"><?php esc_html_e('View how to do this', 'elixar'); ?></a>
                            </p>
                        </div>
						<div class="theme_link">
                            <h3><?php esc_html_e( 'Gallery in Blog Posts', 'elixar' ); ?></h3>
                            <p class="about"><?php printf(esc_html__('If you want to use more than one images in your post or want to make gallery images in your post. This can be accomplished by following the documentation below.', 'elixar'), esc_html( $theme_data->Name )); ?></p>
                            <p>
                            <a href="<?php echo esc_url( 'https://demo.webhuntinfotech.com/blog/2016/01/11/add-gallery-posts-in-matrix-or-kyma-theme/' ); ?>" target="_blank" class="button button-primary"><?php esc_html_e('View how to do this', 'elixar'); ?></a>
                            </p>
                        </div>
                        <div class="theme_link">
                            <h3><?php esc_html_e( 'Having Trouble, Need Support?', 'elixar' ); ?></h3>
                            <p class="about"><?php /* translators: %s: Support Info */
							printf(esc_html__('Support for %s WordPress theme is conducted through WebhuntThemes support ticket system.', 'elixar'), esc_html( $theme_data->Name )); ?></p>
                            <p>
                                <a href="<?php echo esc_url('https://wordpress.org/support/theme/elixar/' ); ?>" target="_blank" class="button button-secondary"><?php echo sprintf( esc_html('Create a support ticket', 'elixar'), esc_html( $theme_data->Name )); ?></a>
                            </p>
                        </div>
                    </div>
                    <div class="theme_info_right">
                        <img src="<?php echo esc_url( get_template_directory_uri() . '/screenshot.png' ); ?>" alt="Theme Screenshot" />
                    </div>
                </div>
            </div>
        <?php } ?>
        <?php if ( $elixar_tab == 'recommended_actions' ) { ?>
            <div class="action-required-tab info-tab-content">
                <?php if ( is_child_theme() ){
                    $child_theme = wp_get_theme();
                    ?>
                    <form method="post" action="<?php echo esc_attr( $current_action_link ); ?>" class="demo-import-boxed copy-settings-form">
                        <p>
                           <strong> <?php /* translators: %s: Child Theme Info */printf( esc_html__(  'You\'re using %1$s theme, It\'s a child theme of Elixar', 'elixar' ) ,  esc_html( $child_theme->Name ) ); ?></strong>
                        </p>
                        <p><?php printf( esc_html__(  "Child theme uses it's own theme setting name, would you like to copy setting data from parent theme to this child theme?", 'elixar' ) ); ?></p>
                        <p>
                        <?php
                        $select = '<select name="copy_from">';
                        $select .= '<option value="">'.esc_html__( 'From Theme', 'elixar' ).'</option>';
                        $select .= '<option value="elixar">Elixar</option>';
                        $select .= '<option value="'.esc_attr( $child_theme->get_stylesheet() ).'">'. esc_html( $child_theme->Name ).'</option>';
                        $select .='</select>';
                        $select_2 = '<select name="copy_to">';
                        $select_2 .= '<option value="">'.esc_html__( 'To Theme', 'elixar' ).'</option>';
                        $select_2 .= '<option value="elixar">Elixar</option>';
                        $select_2 .= '<option value="'.esc_attr( $child_theme->get_stylesheet() ).'">'.esc_html( $child_theme->Name ).'</option>';
                        $select_2 .='</select>';
                        echo $select . ' to '. $select_2;
                        ?>
                        <input type="submit" class="button button-secondary" value="<?php esc_attr_e( 'Copy now', 'elixar' ); ?>">
                        </p>
                        <?php if ( isset( $_GET['copied'] ) && $_GET['copied'] == 1 ) { ?>
                            <p><?php esc_html_e( 'Your settings copied.', 'elixar' ); ?></p>
                        <?php } ?>
                    </form>
                <?php } ?>
                <?php if ( $actions_r['number_active']  > 0 ) { ?>
                    <?php $actions = wp_parse_args( $actions, array( 'page_on_front' => '', 'page_template' ) ) ?>
                    <?php if ( $actions['recommend_plugins'] == 'active' ) {  ?>
                        <div id="plugin-filter" class="recommend-plugins action-required">
                            <a  title="" class="dismiss" href="<?php echo add_query_arg( array( 'elixar_action_notice' => 'recommend_plugins' ), $current_action_link ); ?>">
                                <?php if ( $actions_r['hide_by_click']['recommend_plugins'] == 'hide' ) { ?>
                                    <span class="dashicons dashicons-hidden"></span>
                                <?php } else { ?>
                                    <span class="dashicons  dashicons-visibility"></span>
                                <?php } ?>
                            </a>
                            <h3><?php esc_html_e( 'Recommend Plugins', 'elixar' ); ?></h3>
                            <?php
                            elixar_render_recommend_plugins( $recommend_plugins );
                            ?>
                        </div>
                    <?php } ?>
                    <?php if ( $actions['page_on_front'] == 'active' ) {  ?>
                        <div class="theme_link  action-required">
                            <a title="<?php  esc_attr_e( 'Dismiss', 'elixar' ); ?>" class="dismiss" href="<?php echo add_query_arg( array( 'elixar_action_notice' => 'page_on_front' ), $current_action_link ); ?>">
                                <?php if ( $actions_r['hide_by_click']['page_on_front'] == 'hide' ) { ?>
                                    <span class="dashicons dashicons-hidden"></span>
                                <?php } else { ?>
                                    <span class="dashicons  dashicons-visibility"></span>
                                <?php } ?>
                            </a>
                            <h3><?php esc_html_e( 'Switch "Front page displays" to "A static page"', 'elixar' ); ?></h3>
                            <div class="about">
                                <p><?php esc_html_e( 'In order to have the home page for your website, please go to Customize -&gt; Static Front Page and switch "Front page displays" to "A static page".', 'elixar' ); ?></p>
                            </div>
                            <p>
                                <a  href="<?php echo esc_url(admin_url('options-reading.php')); ?>" class="button"><?php esc_html_e('Setup front page displays', 'elixar'); ?></a>
                            </p>
                        </div>
                    <?php } ?>
                    <?php if ( $actions['page_template'] == 'active' ) {  ?>
                        <div class="theme_link  action-required">
                            <a  title="<?php  esc_attr_e( 'Dismiss', 'elixar' ); ?>" class="dismiss" href="<?php echo add_query_arg( array( 'elixar_action_notice' => 'page_template' ), $current_action_link ); ?>">
                                <?php if ( $actions_r['hide_by_click']['page_template'] == 'hide' ) { ?>
                                    <span class="dashicons dashicons-hidden"></span>
                                <?php } else { ?>
                                    <span class="dashicons  dashicons-visibility"></span>
                                <?php } ?>
                            </a>
                            <h3><?php esc_html_e( 'Set your homepage page template to "Home".', 'elixar' ); ?></h3>
                            <div class="about">
                                <p><?php esc_html_e( 'In order to change homepage section contents, you will need to set template "Home" for your homepage.', 'elixar' ); ?></p>
                            </div>
                            <p>
                                <?php
                                $front_page = get_option( 'page_on_front' );
                                if ( $front_page <= 0  ) {
                                    ?>
                                    <a  href="<?php echo esc_url(admin_url('options-reading.php')); ?>" class="button"><?php esc_html_e('Setup front page displays', 'elixar'); ?></a>
                                    <?php
                                }
                                if ( $front_page > 0 && get_post_meta( $front_page, '_wp_page_template', true ) != 'home-page.php' ) {
                                    ?>
                                    <a href="<?php echo esc_url(get_edit_post_link( $front_page )); ?>" class="button"><?php esc_html_e('Change homepage page template', 'elixar'); ?></a>
                                    <?php
                                }
                                ?>
                            </p>
                        </div>
                    <?php } ?>
                    <?php do_action( 'elixar_more_required_details', $actions ); ?>
                <?php  } else { ?>
                    <h3><?php /* translators: %s: Update Info */
					printf( esc_html__( 'Keep update with %s', 'elixar' ) , esc_html( $theme_data->Name )); ?></h3>
                    <p><?php esc_html_e( 'Hooray! There are no required actions for you right now.', 'elixar' ); ?></p>
                <?php } ?>
            </div>
        <?php } ?>
	    <?php if ( ! class_exists('Elixar_Premium') ) { ?>
	    <?php if ( $elixar_tab == 'free_pro' ) { ?>
            <div id="free_pro" class="freepro-tab-content info-tab-content">
                <table class="free-pro-table">
                    <thead><tr><th></th><th><?php esc_html_e('Elixar Free', 'elixar') ?></th><th><?php esc_html_e('Elixar Premium', 'elixar') ?></th></tr></thead>
                    <tbody>
                    <tr>
                        <td>
                            <h4><?php esc_html_e('WooCommerce Support', 'elixar') ?></h4>
                        </td>
                        <td class="only-lite"><span class="dashicons-before dashicons-yes"></span></td>
                        <td class="only-lite"><span class="dashicons-before dashicons-yes"></span></td>
                    </tr>
                    <tr>
                        <td>
                            <h4><?php esc_html_e('Hero Section', 'elixar') ?></h4>
                        </td>
                        <td class="only-lite"><span class="dashicons-before dashicons-yes"></span></td>
                        <td class="only-lite"><span class="dashicons-before dashicons-yes"></span></td>
                    </tr>
                    <tr>
                        <td>
                            <h5><?php esc_html_e('&#45; Background Video', 'elixar') ?></h5>
                        </td>
                        <td class="only-pro"><span class="dashicons-before dashicons-no-alt"></span></td>
                        <td class="only-lite"><span class="dashicons-before dashicons-yes"></span></td>
                    </tr>
                    <tr>
                        <td>
                            <h5><?php esc_html_e('&#45; Background Slides', 'elixar') ?></h5>
                        </td>
                        <td class="only-pro"><span class="dashicons-before dashicons-no-alt"></span></td>
                        <td class="only-lite"><span class="dashicons-before dashicons-yes"></span></td>
                    </tr>
                    <tr>
                        <td>
                            <h4><?php esc_html_e('About Section', 'elixar') ?></h4>
                        </td>
                        <td class="only-lite"><span class="dashicons-before dashicons-yes"></span></td>
                        <td class="only-lite"><span class="dashicons-before dashicons-yes"></span></td>
                    </tr>
                    <tr>
                        <td>
                            <h5><?php esc_html_e('&#45; Number of items', 'elixar') ?></h5>
                        </td>
                        <td class="only-pro"><span class="dashicons-before "><?php echo esc_html('3'); ?></span></td>
                        <td class="only-lite"><span class="dashicons-before"><?php esc_html_e('Unlimited', 'elixar') ?></span></td>
                    </tr>
                    <tr>
                        <td>
                            <h4><?php esc_html_e('Service Section', 'elixar') ?></h4>
                        </td>
                        <td class="only-lite"><span class="dashicons-before dashicons-yes"></span></td>
                        <td class="only-lite"><span class="dashicons-before dashicons-yes"></span></td>
                    </tr>
                    <tr>
                        <td>
                            <h5><?php esc_html_e('&#45; Number of items', 'elixar') ?></h5>
                        </td>
                        <td class="only-pro"><span class="dashicons-before "><?php echo esc_html('4'); ?></span></td>
                        <td class="only-lite"><span class="dashicons-before"><?php esc_html_e('Unlimited', 'elixar') ?></span></td>
                    </tr>
                    <tr>
                        <td>
                            <h4><?php esc_html_e('Counter Section', 'elixar') ?></h4>
                        </td>
                        <td class="only-pro"><span class="dashicons-before dashicons-no-alt"></span></td>
                        <td class="only-lite"><span class="dashicons-before dashicons-yes"></span></td>
                    </tr>
                    <tr>
                        <td>
                            <h4><?php esc_html_e('Team Section', 'elixar') ?></h4>
                        </td>
                        <td class="only-pro"><span class="dashicons-before dashicons-no-alt"></span></td>
                        <td class="only-lite"><span class="dashicons-before dashicons-yes"></span></td>
                    </tr>
                    <tr>
                        <td>
                            <h4><?php esc_html_e('Latest Blog Section', 'elixar') ?></h4>
                        </td>
                        <td class="only-lite"><span class="dashicons-before dashicons-yes"></span></td>
                        <td class="only-lite"><span class="dashicons-before dashicons-yes"></span></td>
                    </tr>
                    <tr>
                        <td>
                            <h4><?php esc_html_e('Contact Section', 'elixar') ?></h4>
                        </td>
                        <td class="only-pro"><span class="dashicons-before dashicons-no-alt"></span></td>
                        <td class="only-lite"><span class="dashicons-before dashicons-yes"></span></td>
                    </tr>
                    <tr>
                        <td>
                            <h4><?php esc_html_e('Drag and drop section orders', 'elixar') ?></h4>
                        </td>
                        <td class="only-pro"><span class="dashicons-before dashicons-no-alt"></span></td>
                        <td class="only-lite"><span class="dashicons-before dashicons-yes"></span></td>
                    </tr>
                    <tr>
                        <td>
                            <h4><?php esc_html_e('Add New Section', 'elixar') ?></h4>
                        </td>
                        <td class="only-pro"><span class="dashicons-before dashicons-no-alt"></span></td>
                        <td class="only-lite"><span class="dashicons-before dashicons-yes"></span></td>
                    </tr>
                    <tr>
                        <td>
                            <h4><?php esc_html_e('Styling for all sections', 'elixar') ?></h4>
                        </td>
                        <td class="only-pro"><span class="dashicons-before dashicons-no-alt"></span></td>
                        <td class="only-lite"><span class="dashicons-before dashicons-yes"></span></td>
                    </tr>
                    <tr>
                        <td>
                            <h4><?php esc_html_e('Google Map Section', 'elixar') ?></h4>
                        </td>
                        <td class="only-pro"><span class="dashicons-before dashicons-no-alt"></span></td>
                        <td class="only-lite"><span class="dashicons-before dashicons-yes"></span></td>
                    </tr>
                    <tr>
                        <td>
                            <h4><?php esc_html_e('Pricing Section', 'elixar') ?></h4>
                        </td>
                        <td class="only-pro"><span class="dashicons-before dashicons-no-alt"></span></td>
                        <td class="only-lite"><span class="dashicons-before dashicons-yes"></span></td>
                    </tr>
                    <tr>
                        <td>
                            <h4><?php esc_html_e('Testimonial Section', 'elixar') ?></h4>
                        </td>
                        <td class="only-pro"><span class="dashicons-before dashicons-no-alt"></span></td>
                        <td class="only-lite"><span class="dashicons-before dashicons-yes"></span></td>
                    </tr>
                    <tr>
                        <td>
                            <h4><?php esc_html_e('Call To Action Section', 'elixar') ?></h4>
                        </td>
						<td class="only-lite"><span class="dashicons-before dashicons-yes"></span></td>
                        <td class="only-lite"><span class="dashicons-before dashicons-yes"></span></td>
                    </tr>
                    <tr>
                        <td>
                            <h4><?php esc_html_e('Projects Section', 'elixar') ?></h4>
                        </td>
                        <td class="only-pro"><span class="dashicons-before dashicons-no-alt"></span></td>
                        <td class="only-lite"><span class="dashicons-before dashicons-yes"></span></td>
                    </tr>
                    <tr>
                        <td>
                            <h4><?php esc_html_e('Typography Options', 'elixar') ?></h4>
                        </td>
                        <td class="only-pro"><span class="dashicons-before dashicons-no-alt"></span></td>
                        <td class="only-lite"><span class="dashicons-before dashicons-yes"></span></td>
                    </tr>
                    <tr>
                        <td>
                            <h4><?php esc_html_e('24/7 Priority Support', 'elixar') ?></h4>
                        </td>
                        <td class="only-pro"><span class="dashicons-before dashicons-no-alt"></span></td>
                        <td class="only-lite"><span class="dashicons-before dashicons-yes"></span></td>
                    </tr>
                    <tr class="ti-about-page-text-center"><td></td><td colspan="2"><a href="<?php echo esc_url('https://www.webhuntinfotech.com/theme/elixar-premium/'); ?>" target="_blank" class="button button-primary button-hero"><?php esc_html_e('Get Elixar Premium now!', 'elixar') ?></a></td></tr>
                    </tbody>
                </table>
            </div>
	    <?php } ?>
	    <?php } ?>
		
		<?php if ( $elixar_tab == 'our-plugins' ) { ?>
            <div id="our-plugins" class="proplugin-tab-content info-tab-content">
                <!-- Dashboard Settings panel content --->
				<div class="row">
				  <div class="panel panel-primary panel-default content-panel">
					<div class="panel-body">
						<table class="form-table2">
				  
						  <tr class="radio-span" style="border-bottom:none;">
							<td><?php
								include( ABSPATH . "wp-admin/includes/plugin-install.php" );
								global $elixar_tabs, $elixar_tab, $paged, $type, $term;
								$elixar_tabs     = array();
								$elixar_tab      = "search";
								$per_page = 50;
								$args     = array
								(
									"author"   => "webhuntinfotech",
									"page"     => $paged,
									"per_page" => $per_page,
									"fields"   => array( "last_updated" => true, "downloaded" => true, "icons" => true ),
									"locale"   => get_locale(),
								);
								$arges    = apply_filters( "install_plugins_table_api_args_$elixar_tab", $args );
								$api      = plugins_api( "query_plugins", $arges );
								$item     = $api->plugins;
								if ( ! function_exists( "wp_star_rating" ) ) {
									function wp_star_rating( $args = array() ) {
										$defaults = array(
											'rating' => 0,
											'type'   => 'rating',
											'number' => 0,
										);
										$r        = wp_parse_args( $args, $defaults );
						  
										// Non-english decimal places when the $rating is coming from a string
										$rating = str_replace( ',', '.', $r['rating'] );
						  
										// Convert Percentage to star rating, 0..5 in .5 increments
										if ( 'percent' == $r['type'] ) {
											$rating = round( $rating / 10, 0 ) / 2;
										}
						  
										// Calculate the number of each type of star needed
										$full_stars  = floor( $rating );
										$half_stars  = ceil( $rating - $full_stars );
										$empty_stars = 5 - $full_stars - $half_stars;
						  
										if ( $r['number'] ) {
											/* translators: 1: The rating, 2: The number of ratings */
											$format = sprintf( esc_html__( '%1$s rating based on %2$s rating', 'elixar'), esc_html__( '%1$s rating based on %2$s ratings', 'elixar') );
											$title  = sprintf( $format, number_format_i18n( $rating, 1 ), number_format_i18n( $r['number'] ) );
										} else {
											/* translators: 1: The rating */
											$title = sprintf( esc_html__( '%s rating', 'elixar' ), number_format_i18n( $rating, 1 ) );
										}
						  
										echo '<div class="star-rating" title="' . esc_attr( $title ) . '">';
										echo '<span class="screen-reader-text">' . esc_html( $title ) . '</span>';
										echo str_repeat( '<div class="star star-full"></div>', $full_stars );
										echo str_repeat( '<div class="star star-half"></div>', $half_stars );
										echo str_repeat( '<div class="star star-empty"></div>', $empty_stars );
										echo '</div>';
									}
								}
								?>
								<form id="frmrecommendation" class="layout-form">
								  <div id="poststuff" style="width: 99% !important;">
									<div id="post-body" class="metabox-holder">
									  <div id="postbox-container-2" class="postbox-container">
										<div id="advanced" class="meta-box-sortables">
											<div id="gallery_bank_get_started" class="postbox">
												<div class="handlediv" data-target="ux_recommendation"
													title="Click to toggle" data-toggle="collapse"><br></div>
												<h2 class="hndle">
													<span><?php esc_html_e('Get More Free Wordpress Plguins From WebHunt Infotech', 'elixar'); ?></span>
												</h2>
												<div class="inside">
													<div id="ux_recommendation" class="gallery_bank_layout">
									  
														<div class="separator-doubled"></div>
														<div class="fluid-layout">
															<div class="layout-span12">
																<div class="wp-list-table plugin-install">
																	<div id="the-list">
																		<?php
																		foreach ( (array) $item as $plugin ) {
																			if ( is_object( $plugin ) ) {
																				$plugin = (array) $plugin;
									  
																			}
																			if ( ! empty( $plugin["icons"]["svg"] ) ) {
																				$plugin_icon_url = $plugin["icons"]["svg"];
																			} elseif ( ! empty( $plugin["icons"]["2x"] ) ) {
																				$plugin_icon_url = $plugin["icons"]["2x"];
																			} elseif ( ! empty( $plugin["icons"]["1x"] ) ) {
																				$plugin_icon_url = $plugin["icons"]["1x"];
																			} else {
																				$plugin_icon_url = $plugin["icons"]["default"];
																			}
																			$plugins_allowedtags = array
																			(
																				"a"       => array(
																					"href"   => array(),
																					"title"  => array(),
																					"target" => array()
																				),
																				"abbr"    => array( "title" => array() ),
																				"acronym" => array( "title" => array() ),
																				"code"    => array(),
																				"pre"     => array(),
																				"em"      => array(),
																				"strong"  => array(),
																				"ul"      => array(),
																				"ol"      => array(),
																				"li"      => array(),
																				"p"       => array(),
																				"br"      => array()
																			);
																			$title               = wp_kses( $plugin["name"], $plugins_allowedtags );
																			$description         = strip_tags( $plugin["short_description"] );
																			$author              = wp_kses( $plugin["author"], $plugins_allowedtags );
																			$version             = wp_kses( $plugin["version"], $plugins_allowedtags );
																			$name                = strip_tags( $title . " " . $version );
																			$details_link        = self_admin_url( "plugin-install.php?tab=plugin-information&amp;plugin=" . $plugin["slug"] .
																												"&amp;TB_iframe=true&amp;width=600&amp;height=550" );
									  
																			/* translators: 1: Plugin name and version. */
																			$action_links[] = '<a href="' . esc_url( $details_link ) . '" class="thickbox" aria-label="' . esc_attr( sprintf( "More information about %s", $name ) ) . '" data-title="' . esc_attr( $name ) . '">' . esc_html__( 'More Details', 'elixar' ) . '</a>';
																			$action_links   = array();
																			if ( current_user_can( "install_plugins" ) || current_user_can( "update_plugins" ) ) {
																				$status = install_plugin_install_status( $plugin );
																				switch ( $status["status"] ) {
																					case "install":
																						if ( $status["url"] ) {
																							/* translators: 1: Plugin name and version. */
																							$action_links[] = '<a class="install-now button" href="' . $status['url'] . '" aria-label="' . esc_attr( sprintf( "Install %s now", $name ) ) . '">' . esc_html__( 'Install Now', 'elixar' ) . '</a>';
																						}
																						break;
																					case "update_available":
																						if ( $status["url"] ) {
																							/* translators: 1: Plugin name and version */
																							$action_links[] = '<a class="button" href="' . $status['url'] . '" aria-label="' . esc_attr( sprintf( "Update %s now", $name ) ) . '">' . esc_html__( 'Update Now', 'elixar' ) . '</a>';
																						}
																						break;
																					case "latest_installed":
																					case "newer_installed":
																						$action_links[] = '<span class="button button-disabled" title="' . esc_attr__( 'This plugin is already installed and is up to date', 'elixar' ) . ' ">' . esc_html__( 'Installed', 'elixar' ) . '</span>';
																						break;
																				}
																			}
																			?>
																			<div class="plugin-div plugin-div-settings">
																				<div class="plugin-div-top plugin-div-settings-top">
																					<div class="plugin-div-inner-content">
																						<a href="<?php echo esc_url( $details_link ); ?>"
																						class="thickbox plugin-icon plugin-icon-custom">
																							<img class="custom_icon"
																								src="<?php echo esc_attr( $plugin_icon_url ) ?>"/>
																						</a>
																						<div class="name column-name">
																							<h4>
																								<a href="<?php echo esc_url( $details_link ); ?>"
																								class="thickbox"><?php echo esc_html( $title ); ?></a>
																							</h4>
																						</div>
																						<div class="desc column-description">
																							<p>
																								<?php echo esc_html( $description ); ?>
																							</p>
																							<p class="authors">
																								<cite>
																									<?php esc_html_e('By', 'elixar'); ?> <?php echo $author; ?>
																								</cite>
																							</p>
																						</div>
																					</div>
																					<div class="action-links">
																						<ul class="plugin-action-buttons-custom">
																							<li>
																								<?php
																								if ( $action_links ) {
																									echo implode( "</li><li>", $action_links );
																								}
																								switch ( $plugin["slug"] ) {
																									case "photo-video-gallery-master" :
																										?>
																										<a class="plugin-div-button install-now button"
																										href="https://www.webhuntinfotech.com/plugin/photo-video-gallery-master-pro/"
																										target="_blank">
																											<?php esc_html_e( "Premium Detail Page", 'elixar' ); ?>
																										</a>
																										<a class="plugin-div-button install-now button"
																										href="http://demo.webhuntinfotech.com/demo?theme=pvgm-pro"
																										target="_blank">
																											<?php esc_html_e( "Demo Website", 'elixar' ); ?>
																										</a>
																										<?php
																										break;
																									case "coming-soon-master" :
																										?>
																										<a class="plugin-div-button install-now button"
																										href="https://www.webhuntinfotech.com/plugin/coming-soon-master-pro/"
																										target="_blank">
																											<?php esc_html_e( "Premium Detail Page", 'elixar' ); ?>
																										</a>
																										<a class="plugin-div-button install-now button"
																										href="http://demo.webhuntinfotech.com/csmp/"
																										target="_blank">
																											<?php esc_html_e( "Demo Website", 'elixar' ); ?>
																										</a>
																										<?php
																										break;
																									case "social-media-gallery" :
																										?>
																										<a class="plugin-div-button install-now button"
																										href="https://www.webhuntinfotech.com/plugin/social-media-gallery-pro/"
																										target="_blank">
																											<?php esc_html_e( "Premium Detail Page", 'elixar' ); ?>
																										</a>
																										<a class="plugin-div-button install-now button"
																										href="http://demo.webhuntinfotech.com/demo?theme=smg-pro"
																										target="_blank">
																											<?php esc_html_e( "Demo Website", 'elixar' ); ?>
																										</a>
																										<?php
																										break;
																									case "ultimate-gallery-master" :
																										?>
																										<a class="plugin-div-button install-now button"
																										href="https://www.webhuntinfotech.com/plugin/ultimate-gallery-master-pro/"
																										target="_blank">
																											<?php esc_html_e( "Premium Detail Page", 'elixar' ); ?>
																										</a>
																										<a class="plugin-div-button install-now button"
																										href="http://demo.webhuntinfotech.com/demo?theme=ugm-pro"
																										target="_blank">
																											<?php esc_html_e( "Demo Website", 'elixar' ); ?>
																										</a>
																										<?php
																										break;
																									case "fusion-slider":
																										?>
																										<a class="plugin-div-button install-now button"
																										href="https://www.webhuntinfotech.com/plugin/universal-slider-pro/"
																										target="_blank">
																											<?php esc_html_e( "Premium Detail Page", 'elixar' ); ?>
																										</a>
																										<a class="plugin-div-button install-now button"
																										href="http://demo.webhuntinfotech.com/demo?theme=fsp-pro#"
																										target="_blank">
																											<?php esc_html_e( "Demo Website", 'elixar' ); ?>
																										</a>
																										<?php
																										break;
																								
																								}
																								?>
																							</li>
																						</ul>
																					</div>
																				</div>
																				<div class="plugin-card-bottom plugin-card-bottom_settings">
																					<div class="vers column-rating">
																						<?php wp_star_rating( array(
																							"rating" => $plugin["rating"],
																							"type"   => "percent",
																							"number" => $plugin["num_ratings"]
																						) ); ?>
																						<span class="num-ratings">
																	(<?php echo number_format_i18n( $plugin["num_ratings"] ); ?>
																							)
																</span>
																					</div>
																					<div class="column-updated">
																						<strong><?php esc_html_e( 'Last Updated:',  'elixar'); ?></strong>
																						<span title="<?php echo esc_attr( $plugin["last_updated"] ); ?>">
																	<?php printf( "%s ago", human_time_diff( strtotime( $plugin["last_updated"] ) ) ); ?>
																</span>
																					</div>
																					<div class="column-downloaded">
																						<?php 
																						/* translators: %s: Downloads */
																						echo sprintf( _n( "%s download", "%s downloads", $plugin["downloaded"] ), number_format_i18n( $plugin["downloaded"] ) ); ?>
																					</div>
																					<div class="column-compatibility">
																						<?php
																						if ( ! empty( $plugin["tested"] ) && version_compare( substr( $GLOBALS["wp_version"], 0, strlen( $plugin["tested"] ) ), $plugin["tested"], ">" ) ) {
																							echo '<span class="compatibility-untested">' . esc_html__( '<strong>Untested</strong> with your version of WordPress', 'elixar' ) . '</span>';
																						} elseif ( ! empty( $plugin["requires"] ) && version_compare( substr( $GLOBALS["wp_version"], 0, strlen( $plugin["requires"] ) ), $plugin["requires"], "<" ) ) {
																							echo '<span class="compatibility-incompatible">' . esc_html__( 'Incompatible with your version of WordPress', 'elixar' ) . '</span>';
																						} else {
																							echo '<span class="compatibility-compatible">' . esc_html__( 'Compatible with your version of WordPress', 'elixar' ) . '</span>';
																						}
																						?>
																					</div>
																				</div>
																			</div>
																			<?php
																		}
																		?>
																	</div>
																</div>
															</div>
														</div>
													</div>
												</div>
											</div>
										</div>
									  </div>
									</div>
								  </div>
								</form>
							</td>
						  </tr>
						</table>
					</div>
				  </div>
				</div>
				<!-- /row -->
            </div>
        <?php } ?>
		
		<?php if ( $elixar_tab == 'our-themes' ) { ?>
            <div id="our-themes" class="protheme-tab-content info-tab-content">
			<!-- Dashboard Settings panel content --->
				<div class="row">
				  <div class="panel panel-primary panel-default content-panel">
					<div class="panel-body">
						<table class="form-table2">
				  
						  <tr class="radio-span" style="border-bottom:none;">
							<td><?php
								include( ABSPATH . "wp-admin/includes/theme-install.php" );
								global $elixar_tabs, $elixar_tab, $paged, $type, $term;
								$elixar_tabs     = array();
								$elixar_tab      = "search";
								$per_page = 50;
								$args     = array
								(
									"author"   => "webhuntinfotech",
									"page"     => $paged,
									"per_page" => $per_page,
									"fields"   => array( "last_updated" => true, "downloaded" => true, "icons" => true ),
									"locale"   => get_locale(),
								);
								$arges    = apply_filters( "install_plugins_table_api_args_$elixar_tab", $args );
								$api      = themes_api( "query_themes", $arges );
								$item     = $api->themes;
								if ( ! function_exists( "wp_star_rating" ) ) {
									function wp_star_rating( $args = array() ) {
										$defaults = array(
											'rating' => 0,
											'type'   => 'rating',
											'number' => 0,
										);
										$r        = wp_parse_args( $args, $defaults );
						  
										// Non-english decimal places when the $rating is coming from a string
										$rating = str_replace( ',', '.', $r['rating'] );
						  
										// Convert Percentage to star rating, 0..5 in .5 increments
										if ( 'percent' == $r['type'] ) {
											$rating = round( $rating / 10, 0 ) / 2;
										}
						  
										// Calculate the number of each type of star needed
										$full_stars  = floor( $rating );
										$half_stars  = ceil( $rating - $full_stars );
										$empty_stars = 5 - $full_stars - $half_stars;
						  
										if ( $r['number'] ) {
											/* translators: 1: The rating, 2: The number of ratings */
											$format = sprintf( esc_html__( '%1$s rating based on %2$s rating', 'elixar'), esc_html__( '%1$s rating based on %2$s ratings', 'elixar' ) );
											$title  = sprintf( $format, number_format_i18n( $rating, 1 ), number_format_i18n( $r['number'] ) );
										} else {
											/* translators: 1: The rating */
											$title = sprintf( esc_html__( '%s rating', 'elixar' ), number_format_i18n( $rating, 1 ) );
										}
						  
										echo '<div class="star-rating" title="' . esc_attr( $title ) . '">';
										echo '<span class="screen-reader-text">' . esc_html( $title ) . '</span>';
										echo str_repeat( '<div class="star star-full"></div>', $full_stars );
										echo str_repeat( '<div class="star star-half"></div>', $half_stars );
										echo str_repeat( '<div class="star star-empty"></div>', $empty_stars );
										echo '</div>';
									}
								}
								?>
								<form id="frmrecommendation" class="layout-form">
								  <div id="poststuff" style="width: 99% !important;">
									<div id="post-body" class="metabox-holder">
									  <div id="postbox-container-2" class="postbox-container">
										<div id="advanced" class="meta-box-sortables">
											<div id="gallery_bank_get_started" class="postbox">
												<div class="handlediv" data-target="ux_recommendation"
													title="Click to toggle" data-toggle="collapse"><br></div>
												<h2 class="hndle">
													<span><?php esc_html_e('Get More Free Wordpress Themes From WebHunt Infotech', 'elixar'); ?></span>
												</h2>
												<div class="inside">
													<div id="ux_recommendation" class="gallery_bank_layout">
									  
														<div class="separator-doubled"></div>
														<div class="fluid-layout">
															<div class="layout-span12">
																<div class="wp-list-table plugin-install">
																	<div id="the-list">
																		<?php
																		foreach ( (array) $item as $theme ) {
																			if ( is_object( $theme ) ) {
																				$theme = (array) $theme;
									  
																			}
																			if ( ! empty( $theme["screenshot_url"] ) ) {
																				$theme_screenshot_url = $theme["screenshot_url"];
																			} else {
																				$theme_screenshot_url = "";
																			}
																			$themes_allowedtags = array
																			(
																				"a"       => array(
																					"href"   => array(),
																					"title"  => array(),
																					"target" => array()
																				),
																				"abbr"    => array( "title" => array() ),
																				"acronym" => array( "title" => array() ),
																				"code"    => array(),
																				"pre"     => array(),
																				"em"      => array(),
																				"strong"  => array(),
																				"ul"      => array(),
																				"ol"      => array(),
																				"li"      => array(),
																				"p"       => array(),
																				"br"      => array()
																			);
																			$title               = wp_kses( $theme["name"], $themes_allowedtags );
																			$description         = strip_tags( $theme["description"] );
																			if( strlen($description)>250){
																				$description 	= substr($description, 0,250).'...';
																			}
																			$author              = wp_kses( $theme["author"], $themes_allowedtags );
																			$version             = wp_kses( $theme["version"], $themes_allowedtags );
																			$name                = strip_tags( $title . " " . $version );
																			$action_links   = array();
																			if( current_user_can('install_themes')){
																				$activate_link        = self_admin_url("theme-install.php?theme=".$theme["slug"]);
																				$action_links[] = '<a class="install-now button" href="' . wp_specialchars_decode((esc_url( $activate_link ))) . '" aria-label="' . esc_attr( sprintf( "Install %s now", $name ) ) . '">' . esc_html__( 'Install Now (Free)', 'elixar' ) . '</a>';
																			}
																			?>
																			<div class="plugin-div plugin-div-settings">
																				<div class="plugin-div-top plugin-div-settings-top">
																					<div class="plugin-div-inner-content">
																						<a href="<?php echo esc_attr( $theme_screenshot_url ) ?>"
																						class="thickbox plugin-icon plugin-icon-custom">
																							<img class="custom_icon"
																								src="<?php echo esc_attr( $theme_screenshot_url ) ?>"/>
																						</a>
																						<div class="name column-name">
																							<h4>
																								<?php echo esc_html( $title ); ?>
																							</h4>
																						</div>
																						<div class="desc column-description">
																							<p>
																								<?php echo esc_html( $description ); ?>
																							</p>
																							<p class="authors">
																								<cite>
																									<?php esc_html_e('By ', 'elixar'); echo $author["display_name"]; ?>
																								</cite>
																							</p>
																						</div>
																					</div>
																					<div class="action-links">
																						<ul class="plugin-action-buttons-custom">
																							<li>
																								<?php
																								if ( $action_links ) {
																									echo implode( "</li><li>", $action_links );
																								}
																								switch ( $theme["slug"] ) {
																									case "elixar" :
																										?>
																										<a class="plugin-div-button install-now button"
																										href="https://www.webhuntinfotech.com/theme/elixar-premium/"
																										target="_blank">
																											<?php esc_html_e( " Detail Page (Pro)", 'elixar' ); ?>
																										</a>
																										<a class="plugin-div-button install-now button"
																										href="http://demo.webhuntinfotech.com/elixar-premium/"
																										target="_blank">
																											<?php esc_html_e( "Demo Website (Pro)", 'elixar' ); ?>
																										</a>
																										<?php
																										break;
																									case "matrix" :
																										?>
																										<a class="plugin-div-button install-now button"
																										href="https://www.webhuntinfotech.com/theme/matrix-premium-29/"
																										target="_blank">
																											<?php esc_html_e( "Detail Page (Pro)", 'elixar' ); ?>
																										</a>
																										<a class="plugin-div-button install-now button"
																										href="http://demo.webhuntinfotech.com/demo?theme=matrix"
																										target="_blank">
																											<?php esc_html_e( "Demo Website (Pro)", 'elixar' ); ?>
																										</a>
																										<?php
																										break;
																									case "kyma" :
																										?>
																										<a class="plugin-div-button install-now button"
																										href="https://www.webhuntinfotech.com/theme/kyma-advanced-premium-wordpress-theme/"
																										target="_blank">
																											<?php esc_html_e( "Detail Page (Pro)", 'elixar' ); ?>
																										</a>
																										<a class="plugin-div-button install-now button"
																										href="http://demo.webhuntinfotech.com/demo?theme=kyma-advanced"
																										target="_blank">
																											<?php esc_html_e( "Demo Website (Pro)", 'elixar' ); ?>
																										</a>
																										<?php
																										break;
																									case "icare" :
																										?>
																										<a class="plugin-div-button install-now button"
																										href="https://www.webhuntinfotech.com/theme/icare-premium/"
																										target="_blank">
																											<?php esc_html_e( "Detail Page (Pro)", 'elixar' ); ?>
																										</a>
																										<a class="plugin-div-button install-now button"
																										href="https://demo.webhuntinfotech.com/demo?theme=icare-premium"
																										target="_blank">
																											<?php esc_html_e( "Demo Website (Pro)", 'elixar' ); ?>
																										</a>
																										<?php
																										break;
																									case "fortune":
																										?>
																										<a class="plugin-div-button install-now button"
																										href="https://www.webhuntinfotech.com/theme/fortune-premium-wordpress-theme/"
																										target="_blank">
																											<?php esc_html_e( "Detail Page (Pro)", 'elixar' ); ?>
																										</a>
																										<a class="plugin-div-button install-now button"
																										href="http://demo.webhuntinfotech.com/demo?theme=fortune"
																										target="_blank">
																											<?php esc_html_e( "Demo Website (Pro)", 'elixar' ); ?>
																										</a>
																										<?php
																										break;
																									case "awada":
																										?>
																										<a class="plugin-div-button install-now button"
																										href="https://www.webhuntinfotech.com/theme/awada-premium/"
																										target="_blank">
																											<?php esc_html_e( "Detail Page (Pro)", 'elixar' ); ?>
																										</a>
																										<a class="plugin-div-button install-now button"
																										href="http://demo.webhuntinfotech.com/demo?theme=awada"
																										target="_blank">
																											<?php esc_html_e( "Demo Website (Pro)", 'elixar' ); ?>
																										</a>
																										<?php
																										break;
																									case "ibiz":
																										?>
																										<a class="plugin-div-button install-now button"
																										href="https://www.webhuntinfotech.com/theme/awada-premium/"
																										target="_blank">
																											<?php esc_html_e( "Detail Page (Pro)", 'elixar' ); ?>
																										</a>
																										<a class="plugin-div-button install-now button"
																										href="http://demo.webhuntinfotech.com/demo?theme=awada"
																										target="_blank">
																											<?php esc_html_e( "Demo Website (Pro)", 'elixar' ); ?>
																										</a>
																										<?php
																										break;
																									case "unify":
																										?>
																										<a class="plugin-div-button install-now button"
																										href="https://www.webhuntinfotech.com/theme/matrix-premium-29/"
																										target="_blank">
																											<?php esc_html_e( "Detail Page (Pro)", 'elixar' ); ?>
																										</a>
																										<a class="plugin-div-button install-now button"
																										href="http://demo.webhuntinfotech.com/demo?theme=matrix"
																										target="_blank">
																											<?php esc_html_e( "Demo Website (Pro)", 'elixar' ); ?>
																										</a>
																										<?php
																										break;
																									case "spina":
																										?>
																										<a class="plugin-div-button install-now button"
																										href="https://www.webhuntinfotech.com/theme/kyma-advanced-premium-wordpress-theme/"
																										target="_blank">
																											<?php esc_html_e( "Detail Page (Pro)", 'elixar' ); ?>
																										</a>
																										<a class="plugin-div-button install-now button"
																										href="http://demo.webhuntinfotech.com/demo?theme=kyma-advanced"
																										target="_blank">
																											<?php esc_html_e( "Demo Website (Pro)", 'elixar' ); ?>
																										</a>
																										<?php
																										break;	
																								}
																								?>
																							</li>
																						</ul>
																					</div>
																				</div>
																				<div class="plugin-card-bottom plugin-card-bottom_settings">
																					<div class="vers column-rating">
																						<?php wp_star_rating( array(
																							"rating" => $theme["rating"],
																							"type"   => "percent",
																							"number" => $theme["num_ratings"]
																						) ); ?>
																						<span class="num-ratings">
																	(<?php echo number_format_i18n( $theme["num_ratings"] ); ?>
																							)
																</span>
																					</div>
																					<div class="column-updated">
																						<strong><?php esc_html_e( 'Last Updated:', 'elixar' ); ?></strong>
																						<span title="<?php echo esc_attr( $theme["last_updated"] ); ?>">
																	<?php printf( "%s ago", human_time_diff( strtotime( $theme["last_updated"] ) ) ); ?>
																</span>
																					</div>
																					<div class="column-downloaded">
																						<?php 
																						/* translators: %s: Downloads */
																						echo sprintf( _n( "%s download", "%s downloads", $theme["downloaded"]), number_format_i18n( $theme["downloaded"] ) ); ?>
																					</div>
																					<div class="column-compatibility">
																						<?php
																						if ( ! empty( $theme["tested"] ) && version_compare( substr( $GLOBALS["wp_version"], 0, strlen( $theme["tested"] ) ), $theme["tested"], ">" ) ) {
																							echo '<span class="compatibility-untested">' . esc_html__( '<strong>Untested</strong> with your version of WordPress', 'elixar' ) . '</span>';
																						} elseif ( ! empty( $theme["requires"] ) && version_compare( substr( $GLOBALS["wp_version"], 0, strlen( $theme["requires"] ) ), $theme["requires"], "<" ) ) {
																							echo '<span class="compatibility-incompatible">' . esc_html__( 'Incompatible with your version of WordPress', 'elixar' ) . '</span>';
																						} else {
																							echo '<span class="compatibility-compatible">' . esc_html__( 'Compatible with your version of WordPress', 'elixar' ) . '</span>';
																						}
																						?>
																					</div>
																				</div>
																			</div>
																			<?php
																		}
																		?>
																	</div>
																</div>
															</div>
														</div>
													</div>
												</div>
											</div>
										</div>
									  </div>
									</div>
								  </div>
								</form>
							</td>
						  </tr>
						</table>
					</div>
				  </div>
				</div>
				<!-- /row -->
			</div>
        <?php } ?>
		
        <?php if ( $elixar_tab == 'changelog-viewer' ) { ?>
            <div class="changelog-tab-content info-tab-content">
                <?php
                WP_Filesystem();
                global $wp_filesystem;
                $Elixar_lite_changelog = $wp_filesystem->get_contents( get_template_directory().'/CHANGELOG.md' );
                $Elixar_lite_changelog_lines = explode(PHP_EOL, $Elixar_lite_changelog);
                foreach($Elixar_lite_changelog_lines as $Elixar_lite_changelog_line){
                    if(substr( $Elixar_lite_changelog_line, 0, 3 ) === "###"){
                        echo '<h3>'.esc_html( substr($Elixar_lite_changelog_line,3) ).'</h3>';
                    }else if(substr( $Elixar_lite_changelog_line, 0, 2 ) === "##"){
                        echo '<h4>'.esc_html( substr($Elixar_lite_changelog_line,3) ).'</h4>';
                    } else {
                        echo esc_html( $Elixar_lite_changelog_line),'<br/>';
                    }
                }
                ?>
            </div>
        <?php } ?>
        <?php do_action( 'elixar_more_tabs_details', $actions ); ?>
    </div> <!-- END .theme_info -->
    <script type="text/javascript">
        jQuery(  document).ready( function( $ ){
            $( 'body').addClass( 'about-php' );
            $( '.copy-settings-form').on( 'submit', function(){
                var c = confirm( '<?php echo esc_html_e( 'Are you sure want to copy ?', 'elixar' ); ?>' );
                if ( ! c ) {
                    return false;
                }
            } );
        } );
    </script>
    <?php
}