<?php
/**
 * The template for displaying Comments.
 * The area of the page that contains both current comments
 * and the comment form. The actual display of comments is
 * handled by a callback to wpgrade_comment() which is
 * located in the functions.php file.
 *
 * @package Noah Lite
 * @since   Noah 1.0
 */

/*
 * If the current post is protected by a password and
 * the visitor has not yet entered the password we will
 * return early without loading the comments.
 */
if ( post_password_required() ) {
	return;
}
?>
<div class="u-content-width">
	<div id="comments" class="comments-area">
		<?php
		$req 			= get_option( 'require_name_email' );
		$required_text 	= sprintf( ' ' . esc_html__('Required fields are marked %s', 'noah-lite' ), '<span class="required">*</span>' );
		$aria_req 		= ( $req ? " aria-required='true'" : '' );
		$html_req 		= ( $req ? " required='required'" : '' );
		$html5    		= current_theme_supports( 'html5', 'comment-form' );
		$fields   		=  array(
			'author' => '<p class="comment-form-author">' . '<label for="author">' . esc_html__( 'Name', 'noah-lite' ) . ( $req ? ' <span class="required">*</span>' : '' ) . '</label> ' .
				'<input id="author" name="author" type="text" value="' . esc_attr( $commenter['comment_author'] ) . '" size="30" maxlength="245"' . $aria_req . $html_req . ' /></p>',
			'email'  => '<p class="comment-form-email"><label for="email">' . esc_html__( 'Email', 'noah-lite' ) . ( $req ? ' <span class="required">*</span>' : '' ) . '</label> ' .
				'<input id="email" name="email" ' . ( $html5 ? 'type="email"' : 'type="text"' ) . ' value="' . esc_attr(  $commenter['comment_author_email'] ) . '" size="30" maxlength="100" aria-describedby="email-notes"' . $aria_req . $html_req  . ' /></p>',
			'url'    => '<p class="comment-form-url"><label for="url">' . esc_html__( 'Website', 'noah-lite' ) . '</label> ' .
				'<input id="url" name="url" ' . ( $html5 ? 'type="url"' : 'type="text"' ) . ' value="' . esc_attr( $commenter['comment_author_url'] ) . '" size="30" maxlength="200" /></p>',
		);

		if ( have_comments() ) : ?>
			<h2 class="comments-title">
				<span class="c-btn fs-18 _display-block">
				<?php
				printf( _nx( 'One thought on &ldquo;%2$s&rdquo;', '%1$s thoughts on &ldquo;%2$s&rdquo;', get_comments_number(), 'comments title', 'noah-lite' ),
					number_format_i18n( get_comments_number() ), get_the_title() );
				?>
				</span>
			</h2>

			<ol class="comment-list">
				<?php
				wp_list_comments( array(
					'style'       => 'ol',
					'short_ping'  => true,
					'avatar_size' => 74,
				) );
				?>
			</ol><!-- .comment-list -->

			<?php noahlite_the_comments_navigation(); ?>

		<?php endif; // have_comments() ?>

		<?php
		// If comments are closed and there are comments, let's leave a little note, shall we?
		if ( ! comments_open() && get_comments_number() && post_type_supports( get_post_type(), 'comments' ) ) : ?>
			<div class="c-btn fs-18 _display-block"><?php _e( 'Comments are closed', 'noah-lite' ); ?></div>
		<?php endif; ?>

		<?php

		$fields = array(
			'author' => '<p class="comment-form-author">' . '<label for="author">' . esc_html__( 'Name', 'noah-lite' ) . ( $req ? ' <span class="required">*</span>' : '' ) . '</label> ' .
				'<input id="author" placeholder="' . esc_attr__( 'Your name', 'noah-lite' ) . '" name="author" type="text" value="' . esc_attr( $commenter['comment_author'] ) . '" size="30" maxlength="245"' . $aria_req . $html_req . ' /></p>',
			'email'  => '<p class="comment-form-email"><label for="email">' . esc_html__( 'Email', 'noah-lite' ) . ( $req ? ' <span class="required">*</span>' : '' ) . '</label> ' .
				'<input id="email" placeholder="' . esc_attr__( 'your@email.com', 'noah-lite' ) . '" name="email" ' . ( $html5 ? 'type="email"' : 'type="text"' ) . ' value="' . esc_attr(  $commenter['comment_author_email'] ) . '" size="30" maxlength="100" aria-describedby="email-notes"' . $aria_req . $html_req  . ' /></p>',
			'url'    => '<p class="comment-form-url"><label for="url">' . esc_html__( 'Website', 'noah-lite' ) . '</label> ' .
				'<input id="url" placeholder="' . esc_attr__( 'Your website', 'noah-lite' ) . '" name="url" ' . ( $html5 ? 'type="url"' : 'type="text"' ) . ' value="' . esc_attr( $commenter['comment_author_url'] ) . '" size="30" maxlength="200" /></p>',
		);

		$args = array(
			'fields' 				=> $fields,
			'title_reply_before'   	=> '<h3 class="h4 comment-reply-title" id="reply-title">',
			'title_reply_after'    	=> '</h3>',
			'title_reply'          	=> esc_html__( 'Leave a Comment', 'noah-lite' ),
			'comment_field'        	=> '<p class="comment-form-comment"><label for="comment">' . _x( 'Comment', 'noun', 'noah-lite' ) . '</label> <textarea placeholder="' . esc_attr__( 'Your message', 'noah-lite' ) . '" id="comment" name="comment" cols="45" rows="8" maxlength="65525" aria-required="true" required="required"></textarea></p>',
			'comment_notes_before'  => '',
			'comment_notes_after'  	=> '<p class="comment-notes"><span id="email-notes">' . esc_html__( 'Your email address will not be published.', 'noah-lite' ) . '</span>'. ( $req ? $required_text : '' ) . '</p>',
		);

		comment_form( $args ); ?>

	</div><!-- .comments-area -->
</div>
