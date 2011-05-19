<?php get_header(); ?>
<?php global $shiword_is_printpreview; ?>
<?php
	$sw_use_side = ( ( $shiword_opt['shiword_rsideb'] == 0 ) || ( ( $shiword_opt['shiword_rsideb'] == 1 ) && ( $shiword_opt['shiword_rsidebpages'] == 0 ) ) ) ? false : true; 
	$sw_postswidth = ( $sw_use_side ) ? 'posts_narrow' : 'posts_wide';
?>
<div class="<?php echo $sw_postswidth; ?> letsstick">

<?php if ( have_posts() ) {
	while ( have_posts() ) {
		the_post(); ?>
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
			<?php shiword_extrainfo( false, false, true, false, false, true ); ?>
			<div class="storycontent">
				<?php the_content();	?>
			</div>
			<div class="fixfloat">
				<?php wp_link_pages( 'before=<div class="meta comment_tools" style="text-align: right;">' . __( 'Pages:', 'shiword' ) . '&after=</div><div class="fixfloat"></div>' ); ?>
			</div>
			<div class="fixfloat"> </div>
			<?php $sw_tmptrackback = get_trackback_url(); ?>
		</div>	
		<?php comments_template(); // Get wp-comments.php template ?>
		
		<?php if ( $shiword_opt['shiword_navlinks'] == 1 ) { ?>
			<div class="w_title" style="border-bottom: none; border-top: 1px solid #404040;">
				<?php next_post_link('&laquo; %link'); ?>
				<span> - </span>
				<?php previous_post_link('%link &raquo;'); ?>
			</div>
		<?php } ?>

	<?php } 
} else { ?>
	<p><?php _e( 'Sorry, no posts matched your criteria.', 'shiword' );?></p>
<?php } ?>
</div>

<?php if ( $sw_use_side ) get_sidebar(); // show sidebar ?>

<?php get_footer(); ?>
