<?php
/**
 * The sidebar containing the shop page widget area.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package Elixar
 */
?>
<?php 
global $sidebar; 
if ( is_active_sidebar( 'elixar-shop-sidebar-widget' ) ) { ?>
	<div id="sidebar" class="col-sm-4 <?php echo esc_attr( $sidebar ); ?>">
		<?php dynamic_sidebar( 'elixar-shop-sidebar-widget' ); ?>
	</div>
<?php } ?>