<?php
/**
 * post-quote.php
 *
 * Template part file that contains the Quote Format entry
 * 
 * @package Shiword
 * @since 2.07
 */
?>

<?php 
	$shiword_first_quote = shiword_get_blockquote();
	$shiword_auth = ( !$shiword_first_quote || $shiword_first_quote['cite'] == '' ) ? 0 : $shiword_first_quote['cite'];
?>

<div <?php post_class( 'sw-entry' ) ?> id="post-<?php the_ID(); ?>">

	<?php shiword_thumb(); ?>

	<div class="post-body">

		<?php shiword_hook_entry_top(); ?>

		<?php shiword_post_title( array( 'alternative' => $shiword_first_quote['quote'] ? '&#8220;' . $shiword_first_quote['quote'] . '&#8221;' : '' ) ); ?>

		<?php shiword_hook_like_it(); ?>

		<?php shiword_extrainfo( array( 'auth' => $shiword_auth ) ); ?>

		<div class="storycontent">

			<?php if ( ( shiword_get_opt( 'shiword_xcont' ) == 1 ) || is_archive() || is_search() ) // compact view
				the_excerpt();
			else // normal view
				the_content();
			?>

		</div>

		<?php shiword_hook_entry_bottom(); ?>

	</div>

	<div class="fixfloat"> </div>

</div>
