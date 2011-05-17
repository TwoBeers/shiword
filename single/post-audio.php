<?php global $shiword_is_printpreview; ?>
<div <?php post_class() ?> id="post-<?php the_ID(); ?>">
	<?php if ( $shiword_is_printpreview ) { // print buttons. visible only in print preview mode ?>
		<div id="close_preview">
			<a href="<?php the_permalink() ?>" rel="bookmark"><?php _e( 'Close', 'shiword' ); ?></a>
			<a href="javascript:window.print()" id="print_button"><?php _e( 'Print', 'shiword' ); ?></a>
			<script type="text/javascript" defer="defer">
				document.getElementById("print_button").style.display = "block"; // print button (available only with js active)
			</script>
		</div>
	<?php } ?>
	<h2 class="storytitle"><a href="<?php the_permalink() ?>" rel="bookmark"><?php the_title(); ?></a></h2>
	<?php shiword_extrainfo( true, true, true, true, true ); ?>
	<div class="storycontent">
		<?php shiword_add_audio_player(); ?>
		<?php the_content(); ?>
	</div>
	<div class="fixfloat">
		<?php wp_link_pages( 'before=<div class="meta comment_tools" style="text-align: right;">' . __( 'Pages:', 'shiword' ) . '&after=</div><div class="fixfloat"></div>' ); ?>
	</div>
	<div class="fixfloat"> </div>
</div>