<?php
/**
 * The sidebar containing the main widget area.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package Elixar
 */
?>
<?php 
global $sidebar; 
?>
<div id="sidebar" class="col-sm-4 <?php echo esc_attr( $sidebar ); ?>">
    <div class="elixar_sidebar__inner">
        <?php if ( is_active_sidebar( 'sidebar-widget' ) ) {
            dynamic_sidebar( 'sidebar-widget' );
        } else {
            $elixar_args = array(
                'before_widget' => '<div class="elixar-product-sidebar shadow-around">',
    			'after_widget'  => '</div>',
                'before_title'  => '<div class="row-heading row-heading-mt-fourty"><h3 class="title-sm text-uppercase hr-left text-margin">',
    			'after_title'   => '</h3></div>',
            );
            the_widget( 'WP_Widget_Search', null, $elixar_args );
            the_widget( 'WP_Widget_Archives', null, $elixar_args );
            the_widget( 'WP_Widget_Tag_Cloud', null, $elixar_args );
        } ?>
    </div>
</div>