<?php

add_action( 'template_redirect', 'shiword_init_audio_player' );

// setup for audio player
if ( !function_exists( 'shiword_init_audio_player' ) ) {
	function shiword_init_audio_player(){

		if ( is_admin() || shiword_is_mobile() || shiword_is_printpreview() ) return;

		add_action( 'shiword_hook_entry_bottom', 'shiword_add_audio_player' );

	}
}

// add scripts
if ( !function_exists( 'shiword_audioplayer_scripts' ) ) {
	function shiword_audioplayer_scripts(){
		global $shiword_version, $shiword_colors;

		wp_enqueue_script( 'shiword-audioplayer-script', get_template_directory_uri() . '/js/audio-player.dev.js', array( 'jquery', 'swfobject' ), $shiword_version, true  );
		$data = array(
			'unknown_media' => __( 'unknown media format', 'shiword' ),
			'player_path' => get_template_directory_uri().'/resources/audio-player/player.swf',
			'icon_color' => str_replace("#", "", $shiword_colors['main3']),
			'icon_hover_color' => str_replace("#", "", $shiword_colors['main4']),
		);
		wp_localize_script( 'shiword-audioplayer-script', 'shiwordAudioPlayer_l10n', $data );

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