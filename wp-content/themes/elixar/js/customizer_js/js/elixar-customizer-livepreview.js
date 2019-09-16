/*!
 * customizer.js
 *
 * Theme Customizer enhancements for a better user experience.
 *
 * Contains handlers to make Theme Customizer preview reload changes asynchronously.
 *
 * @package Elixar
 */
( function( $ , api ) {
	"use strict";
	// Site title and description.
	wp.customize( 'blogname', function( value ) {
		value.bind( function( to ) {
			$( 'h1.site-title a, p.site-title a' ).text( to );
		} );
	} );
	wp.customize( 'blogdescription', function( value ) {
		value.bind( function( to ) {
			$( 'p.site-description' ).text( to );
		} );
	} );
	// Header text color.
	wp.customize( 'header_textcolor', function( value ) {
		value.bind( function( to ) {
			if ( 'blank' === to ) {
				$( '.site-title a, .site-description' ).css( {
					'clip': 'rect(1px, 1px, 1px, 1px)',
					'position': 'absolute'
				} );
			} else {
				$( '.site-title a, .site-description' ).css( {
					'clip': 'auto',
					'position': 'relative'
				} );
				$( '.site-title a, .site-description' ).css( {
					'color': to
				} );
			}
		} );
	} );
	wp.customize('elixar_logo_layout', function (value) {
        value.bind(function (to) {
            if (to == 'right') {
                $('.navbar-header').addClass('logo_right');
            } else {
                $('.navbar-header').removeClass('logo_right');
            }
        });
    });
	//Global Settings
	wp.customize('elixar_back_top_enable', function (value) {
        value.bind(function (to) {
            if (to)
                $('#scroll-top-fixed').hide();
            else
                $('#scroll-top-fixed').show();
        });
    });
	//Top Bar
	wp.customize('elixar_topbar_enable', function (value) {
        value.bind(function (to) {
            if (to)
                $('.sitetopbar').hide();
            else
                $('.sitetopbar').show();
        });
    });
	wp.customize('elixar_topbar_menu_disable', function (value) {
        value.bind(function (to) {
            if (to)
                $('.contact_info, #mobile-trigger-quick').hide();
            else
                $('.contact_info, #mobile-trigger-quick').show();
        });
    });
	wp.customize('elixar_topbar_bg_color',function( value ) {
		value.bind(function(to) {
			$('.sitetopbar').css('background-color', to ? to : '' );
		});
	});
	wp.customize('elixar_topbar_text_color',function( value ) {
		value.bind(function(to) {
			$('.top-detail-inverse .social-top-detail i:before, .top-detail-inverse .social-top-detail i:after, .top-detail-inverse .social-top-detail i:hover:after, #header-nav ul li a:hover, #header-nav li.current-menu-item a, #header-nav li.current_page_item a, #header-nav li:hover>a, #header-nav ul li a').css( 'color', to );
			$('.sitetopbar').css('color', to ? to : '' );
		});
	});
	//Header
	wp.customize('elixar_header_contact_disable', function (value) {
        value.bind(function (to) {
            if (to){
				$('#quick-contact').css('display', 'none');
            } else {
				$('#quick-contact').removeAttr('style');
			}
        });
    });
	wp.customize('elixar_show_cartcount', function (value) {
        value.bind(function (to) {
            if (to){
				$('.cart-section').css('display', 'none');
			} else {
				$('.cart-section').removeAttr('style');
			}
        });
    });
	//Navigation
	wp.customize('elixar_sticky_menu_bar_enable', function (value) {
        value.bind(function (to) {
            if (to){
				$('#e_main_nav').css('position', 'static');
            } else {
				$('#e_main_nav').css('position', 'fixed');
			}
        });
    });
	wp.customize('elixar_show_search_in_header', function (value) {
        value.bind(function (to) {
            if (to){
				$('.header-search-box').css('display', 'none');
			} else {
				$('.header-search-box').removeAttr('style');
			}
        });
    });
	wp.customize('elixar_header_contact_phone', function (value) {
        value.bind(function (to) {
            $('.quick-call strong').html(to);
        });
    });
	wp.customize('elixar_header_contact_phone_icon', function (value) {
		value.bind(function (to) {
			$('.quick-call i').attr('class', to);
		});
    });
	wp.customize('elixar_header_contact_phone_info', function (value) {
        value.bind(function (to) {
            $('.quick-call a').html(to);
        });
    });
	wp.customize('elixar_header_contact_email', function (value) {
        value.bind(function (to) {
            $('.quick-email strong').html(to);
        });
    });
	wp.customize('elixar_header_contact_email_icon', function (value) {
		value.bind(function (to) {
			$('.quick-email i').attr('class', to);
		});
    });
	wp.customize('elixar_header_contact_email_info', function (value) {
        value.bind(function (to) {
            $('.quick-email a').html(to);
        });
    });
	wp.customize('elixar_header_contact_address', function (value) {
        value.bind(function (to) {
            $('.quick-address strong').html(to);
        });
    });
	wp.customize('elixar_header_contact_address_icon', function (value) {
		value.bind(function (to) {
			$('.quick-address i').attr('class', to);
		});
    });
	wp.customize('elixar_header_contact_address_info', function (value) {
        value.bind(function (to) {
            $('.quick-address a').html(to);
        });
    });
	wp.customize('elixar_header_bg_color',function( value ) {
		value.bind(function(to) {
			$('div#suprhead').css('background-color', to ? to : '' );
		});
	});
	wp.customize('elixar_logo_text_color',function( value ) {
		value.bind(function(to) {
			$('.site-branding-text .site-title a').css('color', to ? to : '' );
		});
	});
	wp.customize('elixar_tagline_text_color',function( value ) {
		value.bind(function(to) {
			$('.site-branding-text .site-description').css('color', to ? to : '' );
		});
	});
	wp.customize('elixar_header_link_color',function( value ) {
		value.bind(function(to) {
			$('#quick-contact a').css('color', to ? to : '' );
		});
	});
	wp.customize('elixar_header_link_hover_color',function( value ) {
		value.bind(function(to) {
			$('#quick-contact a:hover').css('color', to ? to : '' );
		});
	});
	wp.customize('header_textcolor',function( value ) {
		value.bind(function(to) {
			$('div#quick-contact').css('color', to ? to : '' );
		});
	});
	wp.customize('elixar_menu_bar_padding',function( value ) {
		value.bind(function(to) {
			$('#e_main_nav').css('padding', to ? to : '' );
		});
	});
	wp.customize('elixar_menubar_bg_color',function( value ) {
		value.bind(function(to) {
			$('#e_main_nav').css('background-color', to ? to : '' );
		});
	});
	wp.customize('elixar_menu_item_color',function( value ) {
		value.bind(function(to) {
			$('.main-navigation ul li a').css('color', to ? to : '' );
		});
	});
	wp.customize('elixar_menu_item_hover_color',function( value ) {
		value.bind(function(to) {
			$('ul#primary-menu li a:hover, ul#primary-menu ul ul li a:hover, ul#primary-menu ul ul li.current-menu-item a, ul#primary-menu ul ul li.current_page_item a, ul#primary-menu ul ul li:hover>a, ul#primary-menu li.current_page_item a').css('color', to ? to : '' );
		});
	});
	//Site Layout
	wp.customize('elixar_site_layout', function (value) {
        value.bind(function (to) {
            if (to == 'boxed') {
                $('body').addClass('body-boxed');
            } else {
                $('body').removeClass('body-boxed');
            }
        });
    });
	wp.customize( 'elixar_page_title_type', function( value ) {
		value.bind( function( to ) {
			if (to == 'allow_title') {
				$( 'ol.breadcrumb' ).removeAttr('style'); 
				$( 'ol.breadcrumb' ).css( 'display', 'none' );
			} else if (to == 'allow_both') {
				$( 'ol.breadcrumb' ).removeAttr('style');
			}
		} );
	} );
	wp.customize('elixar_page_padding_top',function( value ) {
		value.bind(function(to) {
			$('.e-breadcrumb-page-title').css('padding-top', to + 'px');
		});
	});
	wp.customize('elixar_page_padding_bottom',function( value ) {
		value.bind(function(to) {
			$('.e-breadcrumb-page-title').css('padding-bottom', to + 'px');
		});
	});
	wp.customize('elixar_page_title_bg_image',function( value ) {
		value.bind(function(to) {
			$('.e-breadcrumb-page-title').css('background-image', 'url(' + to + ')');
		});
	});
	wp.customize('elixar_page_overlay_color',function( value ) {
		value.bind(function(to) {
			$('.e-breadcrumb-page-title').css({background: 'linear-gradient(' + to + ', ' + to + ') repeat scroll 0% 0%'});
		});
	});
	wp.customize('elixar_page_title_color',function( value ) {
		value.bind(function(to) {
			$('.e-breadcrumb-page-title, .e-breadcrumb-page-title .e-page-title').css('color', to);
		});
	});
	wp.customize('elixar_single_post_thumb', function (value) {
        value.bind(function (to) {
            if (to){
				$('#sidebar_primary_single img.wp-post-image').css('display', 'none');
			} else {
				$('#sidebar_primary_single img.wp-post-image').removeAttr('style');
			}
        });
    });
	wp.customize('elixar_single_post_meta', function (value) {
        value.bind(function (to) {
            if (to){
				$('#sidebar_primary_single ul.e-post-meta-part').css('display', 'none');
			} else {
				$('#sidebar_primary_single ul.e-post-meta-part').removeAttr('style');
			}
        });
    });
	wp.customize('elixar_single_post_title', function (value) {
        value.bind(function (to) {
            if (to){
				$('#sidebar_primary_single h3.title-lg').css('display', 'none');
			} else {
				$('#sidebar_primary_single h3.title-lg').removeAttr('style');
			}
        });
    });
	//Social
	wp.customize('elixar_social_top_disable', function (value) {
        value.bind(function (to) {
            if (to){
				$('.sitetopbar .topsocial').css('display', 'none');
			} else {
				$('.sitetopbar .topsocial').removeAttr('style');
			}
        });
    });
	wp.customize('elixar_social_footer_enable', function (value) {
        value.bind(function (to) {
            if (to){
				$('.footer-main .e_footer_social').css('display', 'none');
			} else {
				$('.footer-main .e_footer_social').removeAttr('style');
			}
        });
    });
	wp.customize('elixar_social_footer_title', function (value) {
        value.bind(function (to) {
            $('.e_footer_social h1.footer-social-title').html(to);
        });
    });
	wp.customize('elixar_footer_ribbon_enable', function (value) {
        value.bind(function (to) {
            if (to){
				$('.footer-main .footer-ribbon').css('display', 'none');
			} else {
				$('.footer-main .footer-ribbon').removeAttr('style');
			}
        });
    });
	wp.customize('elixar_footer_ribbon_text', function (value) {
        value.bind(function (to) {
            $('.footer-main .footer-ribbon span').html(to);
        });
    });
	wp.customize('elixar_footer_bg_color',function( value ) {
		value.bind(function(to) {
			$('#section_footer').css('background-color', to);
		});
	});
	wp.customize('elixar_footer_text_color',function( value ) {
		value.bind(function(to) {
			$('.content-section.footer-main p').css('color', to);
		});
	});
	wp.customize('elixar_footer_widgets_title_color',function( value ) {
		value.bind(function(to) {
			$('#section_footer h3.foo-widget-title').css('color', to);
		});
	});
	wp.customize('elixar_footer_widgets_link_color',function( value ) {
		value.bind(function(to) {
			$('.content-section.footer-main .footer_widget a').css('color', to);
		});
	});
	wp.customize('elixar_footer_widgets_link_hover_color',function( value ) {
		value.bind(function(to) {
			$('.content-section.footer-main .footer_widget a:hover').css('color', to);
		});
	});
	//Copyright
	wp.customize('elixar_copyright_enable', function (value) {
        value.bind(function (to) {
            if (to){
				$('.footer_copyright').css('display', 'none');
			} else {
				$('.footer_copyright').removeAttr('style');
			}
        });
    });
	wp.customize('elixar_copyright_text', function (value) {
        value.bind(function (to) {
            $('p.copyright-text').html(to);
        });
    });
	
	wp.customize('elixar_copyright_bg_color',function( value ) {
		value.bind(function(to) {
			$('.content-section.footer_copyright').css('background-color', to);
		});
	});
	wp.customize('elixar_copyright_text_color',function( value ) {
		value.bind(function(to) {
			$('.content-section.footer_copyright p.copyright-text').css('color', to);
		});
	});
	wp.customize('elixar_copyright_link_color',function( value ) {
		value.bind(function(to) {
			$('p.copyright-text a#copyright').css('color', to);
		});
	});
	wp.customize('elixar_copyright_link_hover_color',function( value ) {
		value.bind(function(to) {
			$('p.copyright-text a#copyright:hover').css('color', to);
		});
	});
	//Hero Section
	wp.customize('elixar_hero_enable', function (value) {
        value.bind(function (to) {
            if (to){
				$('.hero-section-wrapper').css('display', 'none');
			} else {
				$('.hero-section-wrapper').css('display', '');
			}
        });
    });
	wp.customize('elixar_hero_padding_top',function( value ) {
		value.bind(function(to) {
			$('.hero-section-wrapper').css('padding-top', to + '%');
		});
	});
	wp.customize('elixar_hero_padding_bottom',function( value ) {
		value.bind(function(to) {
			$('.hero-section-wrapper').css('padding-bottom', to + '%');
		});
	});
	
	wp.customize('elixar_hero_large_text_color',function( value ) {
		value.bind(function(to) {
			$('.e-hero-large-text').css('color', to);
		});
	});
	wp.customize('elixar_hero_large_text_bg_color',function( value ) {
		value.bind(function(to) {
			$('.e-hero-large-text').css('background-color', to);
		});
	});
	wp.customize('elixar_hero_btn1_text', function (value) {
        value.bind(function (to) {
            $('#e-hero-btn1').html(to);
        });
    });
	wp.customize('elixar_hero_btn2_text', function (value) {
        value.bind(function (to) {
            $('#e-hero-btn2').html(to);
        });
    });
	//Service Section
	wp.customize('elixar_services_enable', function (value) {
        value.bind(function (to) {
            if (to){
				$('.section-services').css('display', 'none');
			} else {
				$('.section-services').css('display', '');
			}
        });
    });
	wp.customize('elixar_services_title', function (value) {
        value.bind(function (to) {
            $('#service_title').html(to);
        });
    });
	wp.customize('elixar_services_desc', function (value) {
        value.bind(function (to) {
            $('#service_desc').html(to);
        });
    });
	//Extra Section
	wp.customize('elixar_section_extra_enable', function (value) {
        value.bind(function (to) {
            if (to){
				$('.section-extra').css('display', 'none');
			} else {
				$('.section-extra').css('display', '');
			}
        });
    });
	wp.customize('elixar_section_extra_title', function (value) {
        value.bind(function (to) {
            $('#about_title').html(to);
        });
    });
	wp.customize('elixar_section_extra_desc', function (value) {
        value.bind(function (to) {
            $('#about_desc').html(to);
        });
    });
	//CTA Section
	wp.customize('elixar_cta_enable', function (value) {
        value.bind(function (to) {
            if (to){
				$('.section-cta').css('display', 'none');
			} else {
				$('.section-cta').css('display', '');
			}
        });
    });
	wp.customize('elixar_cta_btn1_text', function (value) {
        value.bind(function (to) {
            $('#cta_btn_txt').html(to);
        });
    });
	wp.customize('elixar_cta_btn1_icon', function (value) {
        value.bind(function (to) {
			$('#cta_btn i').removeAttr('class');
            $('#cta_btn i').addClass( to);
        });
    });
	//blog Section
	wp.customize('elixar_blog_enable', function (value) {
        value.bind(function (to) {
            if (to){
				$('.section-blog').css('display', 'none');
			} else {
				$('.section-blog').css('display', '');
			}
        });
    });
	wp.customize('elixar_blog_title', function (value) {
        value.bind(function (to) {
            $('#blog_title').html(to);
        });
    });
	wp.customize('elixar_blog_desc', function (value) {
        value.bind(function (to) {
            $('#blog_desc').html(to);
        });
    });
	wp.customize('elixar_blog_more_text', function (value) {
        value.bind(function (to) {
            $('#elixar-read-more-btn').html(to);
        });
    });
	wp.customize('elixar_load_post_button_enable', function (value) {
        value.bind(function (to) {
            if (to){
				$('.load-button').css('display', 'none');
			} else {
				$('.load-button').css('display', '');
			}
        });
    });
    // Site footer info bg
    wp.customize( 'elixar_footer_info_bg', function( value ) {
        value.bind( function( to ) {
            $( '.site-footer .site-info, .site-footer .btt a' ).css( {
                'background': to
            } );
            $( '.site-footer .site-info').css( {
                color: 'rgba(255, 255, 255, 0.7)',
            } );
            $( '.site-footer .btt a, .site-footer .site-info a').css( {
                color: 'rgba(255, 255, 255, 0.9)',
            } );
        } );
    } );
    /**
     * Handle rendering of partials.
     *
     * @param {api.selectiveRefresh.Placement} placement
     */
    api.selectiveRefresh.bind( 'partial-content-rendered', function( placement ) {
        $( window ).resize();
    } );
    // Site footer widgets
    wp.customize( 'elixar_btt_disable', function( value ) {
        value.bind( function( to ) {
            if ( to === true || to == 'true' ) {
                $( '.site-footer .btt ' ).hide();
            } else {
                $( '.site-footer .btt ' ).show();
            }
        } );
    } );
    $( window ).resize( function(){
        var css_code = $( '#elixar-style-inline-css' ).html();
        // Fix Chrome Lost CSS When resize ??
        $( '#elixar-style-inline-css' ).html( css_code );
    });
    wp.customize.selectiveRefresh.bind( 'partial-content-rendered', function( settings ) {
        if (  settings.partial.id  == 'elixar-header-section' ) {
            $( document ) .trigger( 'header_view_changed',[ settings.partial.id ] );
        }
        $( document ) .trigger( 'selectiveRefresh-rendered',[ settings.partial.id ] );
    } );
} )( jQuery , wp.customize );