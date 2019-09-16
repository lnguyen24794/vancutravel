<?php
/**
 * The header for our theme.
 *
 * This is the template that displays all of the <head> section and everything up until <div id="content">
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package Elixar
 */

?><!DOCTYPE html>
<!--[if IE]><![endif]-->
<!--[if lt IE 7 ]> <html <?php language_attributes(); ?> class="ie6">    <![endif]-->
<!--[if IE 7 ]> <html <?php language_attributes(); ?> class="ie7">    <![endif]-->
<!--[if IE 8 ]> <html <?php language_attributes(); ?> class="ie8">    <![endif]-->
<!--[if IE 9 ]> <html <?php language_attributes(); ?>  <![endif]-->
<!--[if (gt IE 9)|!(IE)]><!--><html <?php language_attributes(); ?>><!--<![endif]-->
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="profile" href="http://gmpg.org/xfn/11">
	<?php if ( is_singular() && pings_open( get_queried_object() ) ) : ?>
	<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">
	<?php endif; ?>
	<?php wp_head(); ?>
</head>
<?php
$elixar_hide_header = false;
if (is_page()) {
	$elixar_hide_header = get_post_meta(get_the_ID(), 'elixar_hide_header', true);
}
if (!$elixar_hide_header) {
	get_template_part('header-2');
} ?>