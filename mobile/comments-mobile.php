<?php
/**
 * The mobile theme - Comments template
 *
 * @package Shiword
 * @subpackage mobile
 * @since 3.03
 */
?>

<!-- begin comments -->
<?php
	if ( post_password_required() ) {
		echo '<p>' . __( 'Enter your password to view comments.', 'shiword' ) . '</p>';
		return;
	} 
?>

<?php if ( have_comments() ) { ?>

	<?php echo apply_filters( 'shiword_mobile_filter_seztitle', __('Comments','shiword') . ' (' . get_comments_number() . ')' ); ?>

	<?php do_action( 'shiword_mobile_hook_comments_before' ); ?>

	<ol class="commentlist">
		<?php wp_list_comments(); ?>
	</ol>

	<?php do_action( 'shiword_mobile_hook_comments_after' ); ?>

<?php } ?>

<?php
	if ( comments_open() )
		comment_form();
?>
<!-- end comments -->
