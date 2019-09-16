<?php
/**
 * The template for displaying 404 pages (not found).
 *
 * @link https://codex.wordpress.org/Creating_an_Error_404_Page
 *
 * @package Elixar
 */

get_header(); ?>
<!-- SECTION HEADING -->
<div class="e-breadcrumb-page-title">
  <div class="container">
	<div class="row">
	  <div class="col-sm-6">
		<h1 class="e-page-title text-center-xs"><?php esc_html_e( '404 Error', 'elixar' ); ?></h1>
	  </div>
	</div>
  </div>
</div>
<div class="elixar-blog">
	<!-- SECTION -->
	<div class="content-section">
		<div class="container">
			<div class="row">
				<div class="col-sm-6 text-center elixar-latest-item shadow-around">
					<h3 class="error-title title-404 text-margin-lg"><?php esc_html_e( '404&#33;', 'elixar' ); ?></h3>
					<p class="error-desc text-margin-lg subtitle-404"><?php esc_html_e( 'Oops&#33; That page can&rsquo;t be found.', 'elixar' ); ?></p>
					<a href="<?php echo esc_url( home_url() ); ?>" class="btn btn-sm btn-primary btn-rounded text-margin-lg"><i class="fas fa-chevron-left"></i><?php esc_html_e( 'Back to Homepage', 'elixar' ); ?></a>
					<div class="mt-thirty mb-twenty">
						<?php get_search_form(); ?>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>	
<?php get_footer(); ?>