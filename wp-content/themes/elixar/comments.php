<?php
/**
 * The template for displaying comments.
 *
 * This is the template that displays the area of the page that contains both the current comments
 * and the comment form.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package Elixar
 */
/*
 * If the current post is protected by a password and
 * the visitor has not yet entered the password we will
 * return early without loading the comments.
 */
if ( post_password_required() ) {
	return;
} ?>
<!-- COMMENTS -->
<?php if ( have_comments() ) { ?>
<div id="comments" class="elixar-blog-comments shadow-around">
	<div class="row">
		<div class="col-sm-12">
		<h3 class="title-md hr-left mb-fourty">
			<?php $elixar_comments_number = get_comments_number();
			if ( '1' === $elixar_comments_number ) {
				/* translators: %s: post title */
				printf( esc_html_x( 'One Comment on &ldquo;%s&rdquo;', 'comments title', 'elixar' ), get_the_title() );
			} else {
				printf(
					/* translators: 1: number of comments, 2: post title */
					_nx(
						'%1$s Comment on &ldquo;%2$s&rdquo;',
						'%1$s Comments on &ldquo;%2$s&rdquo;',
						$elixar_comments_number,
						'comments title',
						'elixar'
					),
					number_format_i18n( $elixar_comments_number ),
					'<span>' . esc_html( get_the_title() ) . '</span>'
				);
			} ?>
		</h3>
		<?php if ( get_comment_pages_count() > 1 && get_option( 'page_comments' ) ) { // Are there comments to navigate through? ?>
		<nav id="comment-nav-above" class="navigation comment-navigation" role="navigation">
			<h2 class="screen-reader-text"><?php esc_html_e( 'Comment navigation', 'elixar' ); ?></h2>
			<div class="nav-links">
				<div class="nav-previous"><?php previous_comments_link( esc_html__( 'Older Comments', 'elixar' ) ); ?></div>
				<div class="nav-next"><?php next_comments_link( esc_html__( 'Newer Comments', 'elixar' ) ); ?></div>
			</div><!-- .nav-links -->
		</nav><!-- #comment-nav-above -->
		<?php } // Check for comment navigation. ?>
		<div class="col-sm-12">
		<?php wp_list_comments( 'callback=elixar_comments&style=div' ); ?>
		</div>
		<?php if ( get_comment_pages_count() > 1 && get_option( 'page_comments' ) ) { // Are there comments to navigate through? ?>
		<nav id="comment-nav-below" class="navigation comment-navigation" role="navigation">
			<h2 class="screen-reader-text"><?php esc_html_e( 'Comment navigation', 'elixar' ); ?></h2>
			<div class="nav-links">
				<div class="nav-previous"><?php previous_comments_link( esc_html__( 'Older Comments', 'elixar' ) ); ?></div>
				<div class="nav-next"><?php next_comments_link( esc_html__( 'Newer Comments', 'elixar' ) ); ?></div>
			</div><!-- .nav-links -->
		</nav><!-- #comment-nav-below -->
		<?php } // Check for comment navigation.
		// Check for have_comments().
		// If comments are closed and there are comments, let's leave a little note, shall we?
		if ( ! comments_open() && get_comments_number() && post_type_supports( get_post_type(), 'comments' ) ) { ?>
			<p class="no-comments"><?php esc_html_e( 'Comments are closed.', 'elixar' ); ?></p>
		<?php } ?>
		</div>
	</div>
</div><!-- #comments -->		
<?php } if ( comments_open() ) { ?>
<div class="elixar-blog-comments shadow-around">
	<div class="row">
		<div class="col-sm-12">
<?php $elixar_fields = array(
		'author' => '<label>Name<span class="e-default-colored">*</span></label><div class="row mb-twenty"><div class="col-md-7 col-md-offset-0"><input type="text" class="form-control" value="" placeholder="' . esc_attr('Name (required)', 'elixar') . '" name="author" id="author"></div></div>',
		'email' => '<label>Email<span class="e-default-colored">*</span></label><div class="row mb-twenty"><div class="col-md-7 col-md-offset-0"><input type="text" class="form-control" value="" placeholder="' . esc_attr('Email (required)', 'elixar') . '" name="email" id="email"></div></div>',
	);
	function elixar_comment_defaullt_fields($elixar_fields)
	{
		return $elixar_fields;
	}
	add_filter('elixar_comment_form_default_fields', 'elixar_comment_defaullt_fields');
	$elixar_comments_args = array(
		'fields' => apply_filters('elixar_comment_form_default_fields', $elixar_fields),
		'label_submit' => esc_html__( 'Submit Message', 'elixar' ),
		/* translators: %s: Leave Reply Title */
		'title_reply_to' =>  '<h3 class="title-md hr-left mb-fourty">' . esc_html__( 'Leave a Reply to %s', 'elixar' ) . '</h3>',
		'title_reply' => '<h3 class="title-md hr-left mb-fourty">' . esc_html__( "Leave a reply", 'elixar' ) . '</h3>',
		 'comment_notes_after' => '', 
		'comment_field' => '<label>Message<span class="e-default-colored">*</span></label><div class="row mb-twenty"><div class="col-md-11 col-md-offset-0"><textarea class="form-control" rows="8" name="comment" placeholder="' . esc_attr( 'Comment...', 'elixar' ) . '" id="comment"></textarea></div></div>',
		'class_submit' => 'btn btn-primary',
	);
	comment_form($elixar_comments_args);
	?>
			</div>
		</div>
	</div>
<?php } ?>