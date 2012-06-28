<?php

add_action( 'template_redirect', 'shiword_init_audio_player' );

// setup for audio player
if ( !function_exists( 'shiword_init_audio_player' ) ) {
	function shiword_init_audio_player(){
		global $shiword_is_printpreview, $shiword_is_mobile_browser;

		if ( is_admin() || $shiword_is_mobile_browser || $shiword_is_printpreview || !is_singular() ) return;

		add_action( 'wp_head', 'shiword_localize_audio_player' );
		add_action( 'shiword_hook_after_post', 'shiword_add_audio_player' );

	}
}

// add scripts
if ( !function_exists( 'shiword_audioplayer_scripts' ) ) {
	function shiword_audioplayer_scripts(){
		global $shiword_version;

		wp_enqueue_script( 'sw-audioplayer', get_template_directory_uri() . '/js/audio-player.min.js', array( 'jquery', 'swfobject' ), $shiword_version, true  );

	}
}

// initialize scripts
if ( !function_exists( 'shiword_localize_audio_player' ) ) {
	function shiword_localize_audio_player(){
		global $shiword_colors;

?>

<script type="text/javascript">
	/* <![CDATA[ */
		sw_unknown_media_format = "<?php _e( 'unknown media format', 'shiword' ); ?>";
		sw_SWFPlayer = "<?php echo get_template_directory_uri().'/resources/audio-player/player.swf'; ?>";
		sw_righticon = "<?php echo str_replace("#", "", $shiword_colors['main3']); ?>";
		sw_righticonhover = "<?php echo str_replace("#", "", $shiword_colors['main4']); ?>";
	/* ]]> */
</script>

<?php

	}
}

// search for linked mp3's and add an audio player
if ( !function_exists( 'shiword_add_audio_player' ) ) {
	function shiword_add_audio_player( $text = '' ) {
		global $post;
		
		$pattern = "/<a ([^=]+=['\"][^\"']+['\"] )*href=['\"](([^\"']+\.(mp3|ogg|m4a)))['\"]( [^=]+=['\"][^\"']+['\"])*>([^<]+)<\/a>/i";
		
		if ( $text != '')
			preg_match_all( $pattern, $text, $result );
		elseif ( is_attachment() )
			preg_match_all( $pattern, wp_get_attachment_link( $post->ID ), $result );
		else
			preg_match_all( $pattern, $post->post_content, $result );


		if ( $result[0] )
			// Add js
			shiword_audioplayer_scripts();
			

		foreach ($result[0] as $key => $value) {
?>

<div class="sw-player-container">
	<small><?php echo $result[0][$key];?></small>
	<div class="sw-player-content">
		<audio controls="">
			<source src="<?php echo $result[3][$key];?>" />
			<span class="sw-player-notice"><?php _e( 'this audio type is not supported by your browser','shiword' ); ?></span>
		</audio>
	</div>
</div>

<?php
		}
	}
}

?>