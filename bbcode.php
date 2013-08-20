<?php
/*
* Class for parsing BBCode
* @author - Paul Carter, http://cartpauj.com
*Ui = 1 line
*Uis = Multiple Lines
*/
if (!class_exists('cartpaujBBCodeParser'))
{
	class cartpaujBBCodeParser {

		var $patterns = array
		(
			'/\[list\](.+)\[\/list\]/Uis',
			'/\[\*\](.+)\\n/Ui',
			'/\[spoil\](.+)\[\/spoil\]/Uis',
			'/\[b\](.+)\[\/b\]/Uis',
			'/\[i\](.+)\[\/i\]/Uis',
			'/\[u\](.+)\[\/u\]/Uis',
			'/\[s\](.+)\[\/s\]/Uis',
			'/\[url=(.+)\](.+)\[\/url\]/Ui',
			'/\[url](.+)\[\/url\]/Ui',
			'/\[map](.+)\[\/map\]/Ui',
			'/\[yt](.+)\[\/yt\]/Ui',
			'/\[embed](.+)\[\/embed\]/Ui',
			'/\[email](.+)\[\/email\]/Ui',
			'/\[email=(.+)\](.+)\[\/email\]/Ui',
			'/\[img\](.+)\[\/img\]/Ui',
			'/\[img=(.+)\](.+)\[\/img\]/Ui',
			'/\[color=(\#[0-9a-f]{6}|[a-z]+)\](.+)\[\/color\]/Ui',
			'/\[color=(\#[0-9a-f]{6}|[a-z]+)\](.+)\[\/color\]/Uis',
			'/\[left\](.+)\[\/left\]/Ui',
			'/\[left\](.+)\[\/left\]/Uis',
			'/\[center\](.+)\[\/center\]/Ui',
			'/\[center\](.+)\[\/center\]/Uis',
			'/\[right\](.+)\[\/right\]/Ui',
			'/\[right\](.+)\[\/right\]/Uis',
			'/\[justify\](.+)\[\/justify\]/Ui',
			'/\[justify\](.+)\[\/justify\]/Uis',
			'/\[spoiler\](.+)\[\/spoiler\]/Ui',
			'/\[spoiler\](.+)\[\/spoiler\]/Uis',
			'/\[spoiler=(.+)\](.+)\[\/spoiler\]/Ui',
			'/\[spoiler=(.+)\](.+)\[\/spoiler\]/Uis',
			'/\[quote](.+)\[\/quote]/Ui',
			'/\[quote](.+)\[\/quote]/Uis'
		);

		var $replacements = array
		(
			'<ul>\1</ul>',
			'<li>\1</li>',
			'<span style = "color:transparent">\1</span>',
			'<b>\1</b>',
			'<i>\1</i>',
			'<u>\1</u>',
			'<s>\1</s>',
			'<a href = "\1" target = "_blank">\2</a>',
			'<a href = "\1" target = "_blank">\1</a>',
			'<iframe width = "400" height = "325" frameborder = "0" scrolling = "no" marginheight = "0" marginwidth = "0" src = "\1&output=embed">Your browser does not support iFrames</iframe>',
			'\1',
			'\1',
			'<a href = "mailto:\1">\1</a>',
			'<a href = "mailto:\1">\2</a>',
			'<a href = "\1"><img src = "\1" alt = "Image" /></a>',
			'<a href = "\1"><img src = "\1" alt = "\2" /></a>',
			'<span style = "color: \1;">\2</span>',
			'<div style = "color: \1;">\2</div>',
			'<p style = "text-align:left">\1</p>',
			'<p style = "text-align:left">\1</p>',
			'<p style = "text-align:center">\1</p>',
			'<p style = "text-align:center">\1</p>',
			'<p style = "text-align:right">\1</p>',
			'<p style = "text-align:right">\1</p>',
			'<p style = "text-align:justify">\1</p>',
			'<p style = "text-align:justify">\1</p>',
			'<ul class="mingle-spoiler-view" style = "list-style-type:none"><li style = "cursor:pointer"><h3><span>+</span>Spoiler</h3></li><li class = "mingle-spoiler-view-hide" style = "display:none">\1</li></ul>',
			'<ul class="mingle-spoiler-view" style = "list-style-type:none"><li style = "cursor:pointer"><h3><span>+</span>Spoiler</h3></li><li class = "mingle-spoiler-view-hide" style = "display:none">\1</li></ul>',
			'<ul class="mingle-spoiler-view" style = "list-style-type:none"><li style = "cursor:pointer"><h3><span>+</span>\1</h3></li><li style = "display:none">\2</li></ul>',
			'<ul class="mingle-spoiler-view" style = "list-style-type:none"><li style = "cursor:pointer"><h3><span>+</span>\1</h3></li><li style = "display:none">\2</li></ul>',
			'<blockquote>\1</blockquote>',
			'<blockquote>\1</blockquote>'
		);

		function bbc2html($subject)
		{
			$codes = array(array(), array());
			preg_match_all('/\[code\](.+)\[\/code\]/Uis', $subject, $codes);

			foreach ($codes[0] as $num => $code) {
				$subject = str_replace($code, "[code$num]", $subject);
			}

			$subject = preg_replace($this->patterns, $this->replacements, $subject);

			foreach ($codes[1] as $num => $code) {
				$subject = str_replace("[code$num]", '<pre class="code">'.$code.'</pre>', $subject);
			}

			return $subject;
		}

		function get_editor($text='') {
			global $mingleforum;

			function mingle_get_editor_mce_external_plugins($plugins) {
				$plugins['bbcodev2'] = plugins_url('/js/plugins/bbcodev2/editor_plugin_src.js', __FILE__);
				$plugins['emotions'] = plugins_url('/js/plugins/emotions/editor_plugin_src.js', __FILE__);
				return $plugins;
			}
			add_filter('mce_external_plugins', 'mingle_get_editor_mce_external_plugins');

			ob_start();
			wp_editor($text, 'message', array(
				'wpautop' => false,
				'tinymce' => array(
					'plugins' => 'bbcodev2,emotions',
					'theme_advanced_buttons1' => 'bold,italic,underline,strikethrough,pre,blockquote,spoil,justifyleft,justifycenter,justifyright,justifyfull,bullist,link,unlink,image,forecolor,yt,map,emotions',
					'theme_advanced_buttons2' => '',
					'apply_source_formatting' => false,
					'setup' => 'function (ed) {
						ed.addButton("pre", {
							title: "'.__("Code").'",
							image: "'.$mingleforum->skin_url.'/images/bbc/code.png"
						})
						ed.addButton("yt", {
							title: "'.__("Embed YouTube Video").'",
							image: "'.$mingleforum->skin_url.'/images/bbc/yt.png"
						})
						ed.addButton("map", {
							title: "'.__("Embed Google Map").'",
							image: "'.$mingleforum->skin_url.'/images/bbc/gm.png"
						})
						ed.addButton("spoil", {
							title: "'.__("Spoiler").'",
							image: "'.$mingleforum->skin_url.'/images/bbc/spoil.png"
						})
					}'
				)
			));
			return ob_get_clean();
		}

	}
}
