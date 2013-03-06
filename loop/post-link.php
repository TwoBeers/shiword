<?php
/**
 * post-link.php
 *
 * Template part file that contains the Link Format entry
 * 
 * @package Shiword
 * @since 2.07
 */
?>

<?php
	$shiword_first_link = shiword_get_first_link();
?>

<div <?php post_class( 'sw-entry' ) ?> id="post-<?php the_ID(); ?>">

	<?php shiword_thumb(); ?>

	<div class="post-body">

		<?php shiword_hook_entry_top(); ?>

		<?php shiword_post_title( $shiword_first_link ? array( 'alternative' => $shiword_first_link['text'] , 'title' => $shiword_first_link['text'], 'href' => $shiword_first_link['href'], 'target' => '_blank' ) : '' ) ; ?>

		<?php shiword_hook_like_it(); ?>

		<?php shiword_extrainfo( array( 'auth' => 0 ) ); ?>

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
