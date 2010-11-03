<!-- begin comments -->
<?php
	if ( isset( $_SERVER['SCRIPT_FILENAME'] ) && 'comments.php' == basename( $_SERVER['SCRIPT_FILENAME'] ) ) die ('Please do not load this page directly. Thanks!');

	if ( post_password_required() ) { ?>
		<div class="meta" id="comments" style="text-align: right;"><?php _e( 'Enter your password to view comments.' ); ?></div>
		<?php return;
	} ?>

<?php if ( comments_open() ) { ?>
	<?php if ( have_comments() ) { ?>
		<div class="meta" id="comments" style="text-align: right;"><?php comments_number( __( 'No Comments' ), __( '1 Comment' ), __( '% Comments' ) ); ?></div>
		<ol id="commentlist">
			<?php wp_list_comments( 'type=comment' ); ?>
			<?php wp_list_comments( 'type=pings' ); ?>
		</ol>
		<?php if ( get_comment_pages_count() > 1 && get_option( 'page_comments' ) ) { ?>
			<div class="navigate_comments">
				<div class="nav-previous-comm"><?php previous_comments_link(); ?></div>
				<div class="nav-next-comm"><?php next_comments_link(); ?></div>
				<div class="fixfloat"> </div>
			</div>
		<?php } ?>
	<?php } ?>

<?php
$fields =  array(
	'author' => '<p class="comment-form-author">' . '<input id="author" name="author" type="text" value="' . esc_attr( $commenter['comment_author'] ) . '" size="30" aria-required="true" />' .
	            '<label for="author">' . __( 'Name' ) . '</label> ' . ( $req ? '<span class="required">*</span>' : '' ) .'</p>',
	'email'  => '<p class="comment-form-email">' . '<input id="email" name="email" type="text" value="' . sanitize_email(  $commenter['comment_author_email'] ) . '" size="30" aria-required="true" />' .
	            '<label for="email">' . __( 'Email' ) . '</label> ' . ( $req ? '<span class="required">*</span>' : '' ) .'</p>',
	'url'    => '<p class="comment-form-url">' . '<input id="url" name="url" type="text" value="' . esc_url( $commenter['comment_author_url'] ) . '" size="30" />' .
	            '<label for="url">' . __( 'Website' ) . '</label>' .'</p>',
); ?>

	<?php $custom_args = array(
		'fields'               => apply_filters( 'comment_form_default_fields', $fields ),
		'comment_field'        => '<p class="comment-form-comment" style="text-align: center;"><textarea id="comment" name="comment" cols="45" rows="7" style="width: 95%;" aria-required="true"></textarea></p>',
		'comment_notes_after'  => '<p class="form-allowed-tags"><small style="float: right; color: #999999; text-align: justify; width: 85%;">' . sprintf( __( 'You may use these <abbr title="HyperText Markup Language">HTML</abbr> tags and attributes: %s' ), allowed_tags() ) . '</small></p>',
		'label_submit'         => __( 'Say It!' ),
		'logged_in_as'         => '<p class="logged-in-as">' . sprintf( __( 'Logged in as <a href="%1$s">%2$s</a>.' ), admin_url( 'profile.php' ), $user_identity ) . '</p>',
		'cancel_reply_link'    => ' - ' . __( 'Cancel reply' ),
		'title_reply'          => __( 'Leave a Reply' ) ,

	);
	comment_form( $custom_args ); ?>
<?php } ?>
<!-- end comments -->
