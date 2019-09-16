<?php
/**
 * The template for displaying product search forms in elixar
 *
 * @package Elixar
 */
?>
<form action="<?php echo esc_url( home_url('/') ); ?>" autocomplete="off" method="get" role="search">
	<div class="input-group text-margin">
		<input type="search" value="<?php echo get_search_query(); ?>" name="s" id="s" class="form-control input-lg" placeholder="<?php esc_html_e( 'Search Product...', 'elixar' ); ?>">
		<input type="hidden" name="post_type" value="product" />
		<span class="input-group-btn">
			<button type="submit" id="searchsubmit" class="btn btn-primary input-lg btn-z-index"><i class="fas fa-search"></i><span class="e_srch_text"><?php esc_html_e( 'Search','elixar' ); ?></span></button>
		</span>
	</div>
</form>