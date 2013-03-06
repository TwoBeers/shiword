<?php
/**
 * comments.php
 *
 * This template file includes both the comments list and
 * the comment form
 *
 * @package Shiword
 * @since 1.00
 */
?>

<?php shiword_hook_comments_before(); ?>

<!-- begin comments -->

<?php
	if ( post_password_required() ) { 
		echo '<div class="meta" id="comments">' . __( 'Enter your password to view comments.', 'shiword' ) . '</div>';
		return;
	}
?>

<?php if ( have_comments() ) { ?>

	<div class="meta" id="comments"><?php comments_number( __( 'No Comments', 'shiword' ), __( '1 Comment', 'shiword' ), __( '% Comments', 'shiword' ) ); ?></div>

	<?php shiword_hook_comments_list_before(); ?>

	<ol class="commentlist">
		<?php wp_list_comments( 'type=comment' ); ?>
	</ol>

	<?php shiword_hook_comments_list_after(); ?>

<?php } ?>

<?php if ( comments_open() && ! shiword_is_printpreview() ) { ?>
	<?php comment_form(); ?>
<?php } ?>

<!-- end comments -->

<?php shiword_hook_comments_after(); ?>
