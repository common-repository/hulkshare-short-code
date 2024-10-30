<?php
/*
Plugin Name: Hulkshare Shortcode
Plugin URI: http://wordpress.org/extend/plugins/hulkshare-shortcode/
Description: Embed Hulkshare player on Wordpress and customize it.
Version: 1.0
Author: Hulkshare Limited
Author URI: http://www.hulkshare.com
License: GPLv2 or later
*/

/*
This program is free software; you can redistribute it and/or
modify it under the terms of the GNU General Public License
as published by the Free Software Foundation; either version 2
of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
*/

if(! function_exists('hulkshare_hulkplayer')) {

	define('HULKSHARE_DEFAULT_COLOR', '83D519');

	/**
	 * The lonely short code handler.
	 *
	 * @param $params
	 * @return string
	 */
	function hulkshare_hulkplayer( $params ) {

		$code = '';
		if (isset($params['code']) || isset($params['pid']))
		{

			$type = 'html5'; // isset($params['type']) ? $params['type'] : get_option('hulkshare_player_type', 'html5');
			$width = isset($params['width']) ? $params['width'] : get_option('hulkshare_player_width', '100%');
			$color = isset($params['color']) ? $params['color'] : get_option('hulkshare_player_color', HULKSHARE_DEFAULT_COLOR);
			$enableDownload = isset($params['enabledownload']) ? $params['enabledownload'] : get_option('hulkshare_player_download', 'true');
			$enableCover = isset($params['enablecover']) ? $params['enablecover'] : get_option('hulkshare_player_cover', 'true');
			$autoPlay = isset($params['autoplay']) ? $params['autoplay'] : 'false';

			// sanitize
			$width = trim($width);
			$width = (intval($width)<=0 ? 100 : intval($width)) . (strpos($width, 'px')===false ? '%' : 'px');
			$color=trim($color);
			if(!preg_match('/[0-9-a-z]{3,6}/i', $color))
				$color = HULKSHARE_DEFAULT_COLOR;

			if($type=='flash') {
				$code = '<iframe src="http://www.hulkshare.com/embed_mp3.php'
					. '?fn='.$params['code']
					. '&type=4&skin=sheep" '
					. 'width="'.$width.'%" height="24" scrolling="no" frameborder=0 marginheight=0 marginwidth=0></iframe>';
			}elseif(isset($params['code'])){

				if(isset($params['media']) && $params['media']=='video') {
					// <iframe src="http://www.hulkshare.com/embed_video.php?video_id=11289887" width="600" height="337" scrolling="no" frameborder="0"></iframe>
					$code = '<iframe src="http://www.hulkshare.com/embed_video.php'
						. '?video_id=' . $params['code']
						. '" scrolling="no" frameborder="0" allowTransparency="true" '
						. 'width="'.(isset($params['width']) ? $params['width'] : '600').'px" '
						. 'height="'.(isset($params['width']) ? $params['width'] : '337').'px"></iframe>';

				}else{
					$code = '<iframe src="http://www.hulkshare.com/embed.php'
						. '?fn='.$params['code']
						. '&enableDownload=' . ($enableDownload=='false' ? 0 : 1)
						. '&enableCover=' . ($enableCover=='false' ? 0 : 1)
						. '&autoPlay=' . ($autoPlay=='false' ? 0 : 1)
						. '&width=' . $width
						. '&color=' . $color
						. '" scrolling="no" frameborder="0" allowTransparency="true" width="'.$width.'" height="160px"></iframe>';
				}
			}else{
				$code = '<iframe src="http://www.hulkshare.com/embed.php'
					. '?pid='.$params['pid']
					. '&enableDownload=' . ($enableDownload=='false' ? 0 : 1)
					. '&enableCover=' . ($enableCover=='false' ? 0 : 1)
					. '&autoPlay=' . ($autoPlay=='false' ? 0 : 1)
					. '&width=' . $width
					. '&color=' . $color
					. '" scrolling="no" frameborder="0" allowTransparency="true" width="'.$width.'" height="384px"></iframe>';

			}

		}
		return $code;
	}

	/**
	 * Links added to plugin row on plugins page.
	 *
	 * @param $links
	 * @param $file
	 * @return array
	 */
	function hulkshare_hulkplayer_meta( $links, $file ) {
		if ( strpos( $file, 'hulkshare.php' ) !== false ) {
			$links = array_merge( $links, array( '<a href="http://www.hulkshare.com" title="Plugin page">' . __('Hulkshare Music') . '</a>' ) );
		}
		return $links;
	}

	/**
	 * Initialize settings on db
	 *
	 */
	function register_hulkshare_settings() {
		register_setting('hulkshare-settings', 'hulkshare_player_type ');
		register_setting('hulkshare-settings', 'hulkshare_player_width ');
		register_setting('hulkshare-settings', 'hulkshare_player_cover');
		register_setting('hulkshare-settings', 'hulkshare_player_download');
		register_setting('hulkshare-settings', 'hulkshare_player_autoplay');
		register_setting('hulkshare-settings', 'hulkshare_player_color');
	}

	/**
	 * @param $links
	 * @return mixed
	 */
	function hulkshare_hulkplayer_settings_link($links) {
		$settings_link = '<a href="options-general.php?page=hulkshare-shortcode">Settings</a>';
		array_unshift($links, $settings_link);
		return $links;
	}

	/**
	 * Setup initialization
	 *
	 */
	function hulkshare_shortcode_options_menu() {
		add_options_page('Hulkshare Player Setup', 'Hulkshare', 'manage_options', 'hulkshare-shortcode', 'hulkshare_shortcode_setup_page');
		add_action('admin_init', 'register_hulkshare_settings');
	}

	/**
	 * Plugin config page
	 *
	 */
	function hulkshare_shortcode_setup_page() {
		if (!current_user_can('manage_options')) {
			wp_die( __('You do not have sufficient permissions to access this page.') );
		}

		?>
		<div class="wrap">
			<h2>Hulkshare Shortcode Player Settings</h2>
			<p>These settings will be the default settings used by the Hulkshare Shortcode throughout your blog.</p>
			<p>You can always override these settings on the shortcode. Setting the parameters in a shortcode overrides these defaults for that player. For example:</p>
			<pre>[hulkplayer code="3qrli9gvxvuo" type="html5" enableDownload="true" enableCover="true" color="83D519" width="50%"]</pre>

			<form method="post" action="options.php">
				<?php settings_fields( 'hulkshare-settings' ); ?>
				<table class="form-table">
					<tr valign="top">
						<th scope="row">Widget Type</th>
						<td>
							<input type="radio" id="player_iframe_true"  name="hulkshare_player_type" value="html5"  <?php if (strtolower(get_option('hulkshare_player_type', 'html5')) === 'html5')  echo 'checked'; ?> />
							<label for="player_iframe_true"  style="margin-right: 1em;">HTML5</label>
							<input type="radio" id="player_iframe_false" name="hulkshare_player_type" value="flash" <?php if (strtolower(get_option('hulkshare_player_type')) === 'flash') echo 'checked'; ?> />
							<label for="player_iframe_false" style="margin-right: 1em;">Flash</label>
						</td>
					</tr>
					<tr valign="top">
						<th scope="row">Width</th>
						<td>
							<input type="text" name="hulkshare_player_width" value="<?php echo get_option('hulkshare_player_width', '100%'); ?>" /> (px or %)<br />
						</td>
					</tr>
					<tr valign="top">
						<th scope="row">Show Track Cover</th>
						<td>
							<input type="radio" id="auto_cover_none" name="hulkshare_player_cover" value=""<?php if (get_option('hulkshare_player_cover')=='') echo 'checked'; ?> />
							<label for="auto_cover_none"  style="margin-right: 1em;">Default</label>
							<input type="radio" id="auto_play_true"  name="hulkshare_player_cover" value="true"<?php if (get_option('hulkshare_player_cover') == 'true') echo 'checked'; ?> />
							<label for="auto_cover_true"  style="margin-right: 1em;">True</label>
							<input type="radio" id="auto_cover_false" name="hulkshare_player_cover" value="false" <?php if (get_option('hulkshare_player_cover') == 'false') echo 'checked'; ?> />
							<label for="auto_cover_false" style="margin-right: 1em;">False</label>
						</td>
					</tr>

					<!-- tr valign="top">
						<th scope="row">Auto Play</th>
						<td>
							<input type="radio" id="auto_play_none" name="hulkshare_player_autoplay" value=""<?php if (get_option('hulkshare_player_autoplay') == '') echo 'checked'; ?> />
							<label for="auto_play_none"  style="margin-right: 1em;">Default</label>
							<input type="radio" id="auto_play_true"  name="hulkshare_player_autoplay" value="true"<?php if (get_option('hulkshare_player_autoplay') == 'true') echo 'checked'; ?> />
							<label for="auto_play_true"  style="margin-right: 1em;">True</label>
							<input type="radio" id="auto_play_false" name="hulkshare_player_autoplay" value="false" <?php if (get_option('hulkshare_player_autoplay') == 'false') echo 'checked'; ?> />
							<label for="auto_play_false" style="margin-right: 1em;">False</label>
						</td>
					</tr -->
					<tr valign="top">
						<th scope="row">Color</th>
						<td>
							<input type="text" name="hulkshare_player_color" value="<?php echo get_option('hulkshare_player_color', HULKSHARE_DEFAULT_COLOR); ?>" /> (color hex code e.g. ff6699)<br />
							Defines the color to paint the play button.
						</td>
					</tr>
				</table>

				<p class="submit">
					<input type="submit" class="button-primary" value="<?php _e('Save Changes') ?>" />
				</p>

			</form>
		</div>
	<?php

	}

	// ====== initialization and registrations ===== //
	// embed handler
	wp_oembed_add_provider('#https?://(.*)\.hulkshare\.com/.*#i', 'http://gustavo.dev.hulkshare.com/oembed.php', true);

	// enables the short code
	add_shortcode( 'hulkplayer', 'hulkshare_hulkplayer' );

	// this is for admin row
	add_filter( 'plugin_row_meta', 'hulkshare_hulkplayer_meta', 10, 2 );

	// 'Settings' link on plugins page
	add_filter("plugin_action_links_" . plugin_basename(__FILE__), 'hulkshare_hulkplayer_settings_link');

	// 'Hulkshare' link on Setup tab
	add_action('admin_menu', 'hulkshare_shortcode_options_menu');

}
