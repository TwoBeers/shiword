<?php
/**
 * audio-player.php
 *
 * Code for the audio player
 *
 * @package Shiword
 * @since 3.04
 */


class Shiword_Audio_Player {

	function __construct() {

		add_action( 'template_redirect', array( $this, 'init' ) );

	}


// setup for audio player
	function init(){

		if ( is_admin() || shiword_is_mobile() || shiword_is_printpreview() ) return;

		add_action( 'shiword_hook_entry_bottom', array( $this, 'the_audio_player' ) );

	}


// add scripts
	function scripts(){
		global $shiword_colors;

		wp_enqueue_script( 'shiword-audioplayer-script', get_template_directory_uri() . '/js/audio-player.min.js', array( 'jquery', 'swfobject' ), shiword_get_info( 'version' ), true  );
		$data = array(
			'unknown_media' => __( 'unknown media format', 'shiword' ),
			'player_path' => get_template_directory_uri().'/resources/audio-player/player.swf',
			'icon_color' => str_replace("#", "", $shiword_colors['main3']),
			'icon_hover_color' => str_replace("#", "", $shiword_colors['main4']),
		);
		wp_localize_script( 'shiword-audioplayer-script', 'shiwordAudioPlayer_l10n', $data );

	}


// search for linked mp3's and add an audio player
	function the_audio_player( $text = '' ) {
		global $post;

		$pattern = "/<a ([^=]+=['\"][^\"']+['\"] )*href=['\"](([^\"']+\.(mp3|ogg|m4a)))['\"]( [^=]+=['\"][^\"']+['\"])*>([^<]+)<\/a>/i";

		if ( $text != '')
			preg_match_all( $pattern, $text, $result );
		elseif ( is_attachment() )
			preg_match_all( $pattern, wp_get_attachment_link( $post->ID ), $result );
		else
			preg_match_all( $pattern, $post->post_content, $result );

		if ( $result[0] )
			$this->scripts(); // Add js

		$instance = 0;

		foreach ($result[0] as $key => $value) {
			$instance++;
?>

<div class="sw-player-container">
	<small><?php echo $result[0][$key];?></small>
	<div class="sw-player-content">
		<audio controls="" id="sw-player-<?php echo $instance . '-' . $post->ID; ?>" class="no-player">
			<source src="<?php echo esc_url( $result[3][$key] );?>" />
			<span class="sw-player-notice"><?php _e( 'this audio type is not supported by your browser','shiword' ); ?></span>
		</audio>
	</div>
</div>

<?php
		}

	}

}

new Shiword_Audio_Player;