=== Hulkshare Shortcode ===
Contributors: jantsch
Tags: hulkshare, player, html5, flash, shortcode
Requires at least: 3.0.1
Tested up to: 3.6
Stable tag: trunk
License: GPLv2
License URI: http://www.gnu.org/licenses/gpl-2.0.html

The Hulkshare plugin enables you to integrate the Hulkshare player into your WordPress blog and customize it.

== Description ==

The Hulkshare plugin enables you to integrate the Hulkshare player into your WordPress blog and customize it.

It also supports the following optional parameters:
* width = widget width (in % or px)
* autoPlay = (true or false)
* enableCover = (true or false)
* enableDownload = (true or false)
* color = (color hex code) will paint the play button and the waveform

Examples:

`[hulkplayer code="fsn5z97kzojk"]`
Embed a track player

`[hulkplayer code="fsn5z97kzojk" enableDownload="false" enableCover="false" color="D51811"]`
Embed a track with no download button, cover picture, red buttons nor waveform

`[hulkplayer code="11289887" media="video"]`
Embeds a video.

== Installation ==

1. Upload `hulkshare.php` to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Place the short code for Wordpress, something like [hulkplayer code="moixg98k3wn4"], inside your posts or pages, publish, and you are done!


== Frequently Asked Questions ==

= Where do I get the shortcode? =

Go to [Hulkshare](http://www.hulkshare.com/ "Hulkshare Music") and locate the track you want to embed. Hover over the track, and the "Share” button will appear. Pressing it will show a pop up. Copy the shortcode in the “WordPress” area. Paste it in the HTML editor of your blog page and you’re done :)

If your track isn't there just signup and upload! It’s free!

== Changelog ==

= 1.0 =
* First version