<?php
/**
 * Uninstall the Hulkshare plugin
 * @author: jantsch
 *
 */

if( !defined( 'ABSPATH') && !defined('WP_UNINSTALL_PLUGIN') )
	exit();

delete_option('hulkshare_player_width');
delete_option('hulkshare_player_type');
delete_option('hulkshare_player_cover');
delete_option('hulkshare_player_autoplay');
delete_option('hulkshare_player_color');