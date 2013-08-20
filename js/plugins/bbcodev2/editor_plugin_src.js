
/**
 * $Id: editor_plugin_src.js 201 2007-02-12 15:56:56Z spocke $
 *
 * @id Moxiecode
 * @copyright Copyright © 2004-2008, Moxiecode Systems AB, All rights reserved.
 */

// URL : http://support.utiliweb.fr/handlers/tiny_mce/plugins/bbcodev2/editor_plugin_src.js

(function() {
	tinymce.create('tinymce.plugins.BBCodev2Plugin', {
		init : function(ed, url) {
			var t = this, dialect = ed.getParam('bbcode_dialect', 'punbb').toLowerCase();

			ed.onBeforeSetContent.add(function(ed, o) {
				o.content = t['_' + dialect + '_bbcode2html'](o.content);
			});

			ed.onPostProcess.add(function(ed, o) {
				if (o.set)
					o.content = t['_' + dialect + '_bbcode2html'](o.content);

				if (o.get)
					o.content = t['_' + dialect + '_html2bbcode'](o.content);
			});

			function replaceContent(component, tag) {
				var cm = ed.controlManager.get(component);
				if (ed.selection.getContent() !== '') {
					ed.selection.setContent('[' + tag + ']' + ed.selection.getContent() + '[/' + tag + ']');
				} else if (cm.isActive()) {
					ed.selection.setContent('[/' + tag + ']');
					cm.setActive(false);
				} else {
					ed.selection.setContent('[' + tag + ']');
					cm.setActive(true);
				}
			}

			ed.addCommand('preCmd', function () {
				 replaceContent('pre', 'code');
			});
			ed.addCommand('ytCmd', function () {
				replaceContent('yt', 'yt');
			});
			ed.addCommand('mapCmd', function () {
				replaceContent('map', 'map');
			});
			ed.addCommand('spoilCmd', function () {
				replaceContent('spoil', 'spoiler');
			});

			ed.addCommand('linkCmd', function () {
				var entry = prompt();
				if (entry)
					ed.selection.setContent('<a href="' + entry + '">' + ed.selection.getContent() + '</a>');
			});

			ed.addCommand('imageCmd', function () {
				var entry = prompt();
				if (entry)
					ed.selection.setContent('<img src="' + entry + '" style="max-width:100%" />');
			});

			ed.onInit.add(function (ed) {
				var cm = ed.controlManager;
				if (cm.get('pre'))
					cm.get('pre').settings.cmd = 'preCmd';
				if (cm.get('yt'))
					cm.get('yt').settings.cmd = 'ytCmd';
				if (cm.get('map'))
					cm.get('map').settings.cmd = 'mapCmd';
				if (cm.get('link'))
					cm.get('link').settings.cmd = 'linkCmd';
				if (cm.get('image'))
					cm.get('image').settings.cmd = 'imageCmd';
				if (cm.get('spoil'))
					cm.get('spoil').settings.cmd = 'spoilCmd';
			});
		},

		getInfo : function() {
			return {
				longname : 'BBCodev2 Plugin',
				id : 'Moxiecode Systems AB',
				idurl : 'http://tinymce.moxiecode.com',
				infourl : 'http://wiki.moxiecode.com/index.php/TinyMCE:Plugins/bbcodev2',
				version : tinymce.majorVersion + "." + tinymce.minorVersion
			};
		},

		// Private methods

		// HTML -> BBCode in PunBB dialect
		_punbb_html2bbcode : function(s) {
			s = tinymce.trim(s);

			function rep(re, str) {
				s = s.replace(re, str);
			};


			// example: <strong> to [b]
			rep(/<p class=\"codeStyle\">(.*?)<\/p>/gi,"[code]$1[/code]");
			rep(/<strong class=\"codeStyle\">(.*?)<\/strong>/gi,"[code]$1[/code]");
			rep(/<em class=\"codeStyle\">(.*?)<\/em>/gi,"[code]$1[/code]");

			rep(/<a target=\"_blank\" href=\"(.*?)\".*?>(.*?)<\/a>/gi,"[link=$1]$2[/link]");
			rep(/<a target=\"_self\" href=\"(.*?)\".*?>(.*?)<\/a>/gi,"[url=$1]$2[/url]");
			rep(/<a href=\"([^"]*)\">(.*?)<\/a>/gi,"[url=$1]$2[/url]");
			rep(/<a href=\"([^"]*)\" target=\"_blank\">(.*?)<\/a>/gi,"[link=$1]$2[/link]");
			rep(/<a href=\"([^"]*)\" target=\"_self\">(.*?)<\/a>/gi,"[url=$1]$2[/url]");



			rep(/<font.*?color=\"(.*?)\".*?class=\"codeStyle\".*?>(.*?)<\/font>/gi,"[code][color=$1]$2[/color][/code]");
			rep(/<font.*?color=\"(.*?)\".*?class=\"quoteStyle\".*?>(.*?)<\/font>/gi,"[blockquote][color=$1]$2[/color][/blockquote]");
			rep(/<font.*?class=\"codeStyle\".*?color=\"(.*?)\".*?>(.*?)<\/font>/gi,"[code][color=$1]$2[/color][/code]");
			rep(/<font.*?class=\"quoteStyle\".*?color=\"(.*?)\".*?>(.*?)<\/font>/gi,"[blockquote][color=$1]$2[/color][/blockquote]");
			rep(/<span style=\"color: ?(.*?);\">(.*?)<\/span>/gi,"[color=$1]$2[/color]");
			rep(/<font.*?color=\"(.*?)\".*?>(.*?)<\/font>/gi,"[color=$1]$2[/color]");
			rep(/<span style=\"font-size: (.*?)px;\">(.*?)<\/span>/gi,"[size=$1]$2[/size]");
			rep(/<font>(.*?)<\/font>/gi,"$1");
			rep(/<img class=\"normal\" src=\"(.*?)\".*?\/>/gi,"[img]$1[/img]");
			rep(/<img class=\"miniature\" src=\"(.*?)\".*?\/>/gi,"[img=miniature]$1[/img]");
			rep(/<img.*?src=\"(.*?)\".*?\/>/gi,"[img]$1[/img]");
			rep(/<span class=\"codeStyle\">(.*?)<\/span>/gi,"[code]$1[/code]");
			rep(/<span class=\"quoteStyle\">(.*?)<\/span>/gi,"[blockquote]$1[/blockquote]");
			rep(/<span class=\"quoteStyle\" id=\"(.*?)\">(.*?)<\/span>/gi,"[quote=$1]$2[/quote]");
			rep(/<span id=\"(.*?)\" class=\"quoteStyle\">(.*?)<\/span>/gi,"[quote=$1]$2[/quote]");
			rep(/<span class=\"hideStyle\">(.*?)<\/span>/gi,"[hide]$1[/hide]");
			rep(/<p class=\"quoteStyle\">(.*?)<\/p>/gi,"[blockquote]$1[/blockquote]");
			rep(/<p class=\"hideStyle\">(.*?)<\/p>/gi,"[hide]$1[/hide]");
			rep(/<p id=\"(.*?)\" class=\"quoteStyle\">(.*?)<\/p>/gi,"[quote=$1]$2[/quote]");
			rep(/<span class=\"quoteStyle\">(.*?)<\/span>/gi,"[blockquote]$1[/blockquote]");
			rep(/<strong class=\"quoteStyle\">(.*?)<\/strong>/gi,"[quote][b]$1[/b][/quote]");
			rep(/<em class=\"quoteStyle\">(.*?)<\/em>/gi,"[quote][i]$1[/i][/quote]");
			rep(/<u class=\"codeStyle\">(.*?)<\/u>/gi,"[code][u]$1[/u][/code]");
			rep(/<code>(.*?)<\/code>/gi,"[code]$1[/code]");
			rep(/<u class=\"quoteStyle\">(.*?)<\/u>/gi,"[quote][u]$1[/u][/quote]");
			rep(/<\/(strong|b)>/gi,"[/b]");
			rep(/<(strong|b)>/gi,"[b]");
			rep(/<\/(em|i)>/gi,"[/i]");
			rep(/<(em|i)>/gi,"[i]");
			rep(/<\/u>/gi,"[/u]");
			rep(/<div class=\"youtube\" title=\"(.*?)\">.*?<\/div>/gi,"[video=youtube]$1[/video]");
			rep(/<div class=\"dailymotion\" title=\"(.*?)\">.*?<\/div>/gi,"[video=dailymotion]$1[/video]");
			rep(/<div class=\"veoh\" title=\"(.*?)\">.*?<\/div>/gi,"[video=veoh]$1[/video]");
			rep(/<div class=\"vimeo\" title=\"(.*?)\">.*?<\/div>/gi,"[video=vimeo]$1[/video]");
			rep(/<div class=\"jiwa\" title=\"(.*?)\">.*?<\/div>/gi,"[video=jiwa]$1[/video]");
			rep(/<div class=\"deezer\" title=\"(.*?)\">.*?<\/div>/gi,"[deezer]$1[/deezer]");
			rep(/<div class=\"picasa\" title=\"(.*?)\">.*?<\/div>/gi,"[picasa]$1[/picasa]");
			rep(/<span style=\"text-decoration: line-through;\">(.*?)<\/span>/gi,"[no]$1[/no]");
			rep(/<span style=\"text-decoration: ?underline;\">(.*?)<\/span>/gi,"[u]$1[/u]");
			rep(/<p style=\"text-align: center;\"><\/p>/gi,"\n");
			rep(/<p style=\"text-align: left;\"><\/p>/gi,"\n");
			rep(/<p style=\"text-align: right;\"><\/p>/gi,"\n");
			rep(/<p style=\"text-align: justify;\"><\/p>/gi,"\n");
			rep(/<p style=\"text-align: (.*?);\">(.*?)<\/p>/gi,"[$1]$2[/$1]");
			rep(/<u>/gi,"[u]");
			rep(/<blockquote[^>]*>/gi,"[quote]");
			rep(/<\/blockquote>/gi,"[/quote]");

//listes
		rep(/<ul[^>]*>(.*?)<\/ul>/gi,"[list]$1[/list]");
         rep(/<ol style="list-style-type: lower-alpha;">(.*?)<\/ol>/gi,"[list=lower-alpha]$1[/list]");
         rep(/<ol[^>]*>(.*?)<\/ol>/gi,"[list=decimal]$1[/list]");
         rep(/<li>(.*?)<\/li>/gi,"[*]$1\n");


//tableaux
			rep(/<table.*?><tbody.*?>(.*?)<\/tbody><\/table>/gi,"[tableau]$1[/tableau]");
			rep(/<table.*?>(.*?)<\/table>/gi,"[tableau]$1[/tableau]");
			rep(/<tr.*?><td.*?>/gi,"[ligne]");
			rep(/<\/td><\/tr>/gi,"[/ligne]");
			rep(/<\/td><td.*?>/gi,"[|]");

			rep(/<p>/gi,"");
			rep(/<p.*?>/gi,"");
			rep(/<\/p>/gi,"<br \/>");
			rep(/<br \/>/gi,"\n");
			rep(/<br\/>/gi,"\n");
			rep(/<span style=\"color: (.*?);\">(.*?)<\/span>/gi,"[color=$1]$2[/color]");
			rep(/<span style=\"color: (.*?);\">(.*?)<\/span>/gi,"[color=$1]$2[/color]");
			rep(/<span style=\"color: (.*?);\">(.*?)<\/span>/gi,"[color=$1]$2[/color]");
			rep(/<font.*?color=\"(.*?)\".*?>(.*?)<\/font>/gi,"[color=$1]$2[/color]");
			rep(/<font.*?color=\"(.*?)\".*?>(.*?)<\/font>/gi,"[color=$1]$2[/color]");
			rep(/<font.*?color=\"(.*?)\".*?>(.*?)<\/font>/gi,"[color=$1]$2[/color]");
			rep(/<span style=\"font-size: (.*?)px;\">(.*?)<\/span>/gi,"[size=$1]$2[\/size]");
			rep(/<span style=\"font-size: (.*?)px;\">(.*?)<\/span>/gi,"[size=$1]$2[\/size]");
			rep(/<span style=\"font-size: (.*?)px;\">(.*?)<\/span>/gi,"[size=$1]$2[\/size]");
			rep(/<\!--.*?-->/gi,"");
			rep(/<\/span>/gi,"");
			rep(/<span.*?>/gi,"");
			rep(/<div.*?>/gi,"");
			rep(/<\/div>/gi,"");
			rep(/<tr.*?>/gi,"");
			rep(/<\/tr>/gi,"");
			rep(/<\/table>/gi,"");
			rep(/<table.*?>/gi,"");
			rep(/<\/tbody>/gi,"");
			rep(/<tbody.*?>/gi,"");
			rep(/<td.*?>/gi,"");
			rep(/<\/td>/gi,"");
			rep(/&nbsp;/gi," ");
			rep(/&quot;/gi,"\"");
			rep(/&lt;/gi,"<");
			rep(/&gt;/gi,">");
			rep(/&amp;/gi,"&");

		return s;
		},

		// BBCode -> HTML from PunBB dialect
		_punbb_bbcode2html : function(s) {
			s = tinymce.trim(s);

			function rep(re, str) {
				s = s.replace(re, str);
			};


			// example: [b] to <strong>
			rep(/\[code\](.*?)\[\/code\]/gi,"<p class=\"codeStyle\">$1</p>");
    		     rep(/\n\n/g, "<p><\/p>");
         		rep(/\n/g, "<br \/>");
			rep(/\[b\]/gi,"<strong>");
			rep(/\[\/b\]/gi,"</strong>");
			rep(/\[i\]/gi,"<em>");
			rep(/\[\/i\]/gi,"</em>");
			rep(/\[u\]/gi,"<u>");
			rep(/\[\/u\]/gi,"</u>");
			rep(/\[url=([^\]]+)\](.*?)\[\/url\]/gi,"<a href=\"$1\">$2</a>");
			rep(/\[url\](.*?)\[\/url\]/gi,"<a href=\"$1\">$1</a>");
			rep(/\[link=([^\]]+)\](.*?)\[\/link\]/gi,"<a target=\"_blank\" href=\"$1\">$2</a>");
			rep(/\[link\](.*?)\[\/link\]/gi,"<a target=\"blank\" href=\"$1\">$1</a>");
			rep(/\[img\](.*?)\[\/img\]/gi,"<img src=\"$1\" style=\"max-width:100%\" />");
			rep(/\[img=miniature\](.*?)\[\/img\]/gi,"<img class=\"miniature\" src=\"$1\" />");
			rep(/\[color=(.*?)\]/gi,"<font color=\"$1\">");
			rep(/\[\/color\]/gi,"</font>");
			rep(/\[hide\](.*?)\[\/hide\]/gi,"<p class=\"hideStyle\">$1</p>");
			rep(/\[blockquote.*?\](.*?)\[\/blockquote\]/gi,"<p class=\"quoteStyle\">$1</p>");
			rep(/\[quote=(.*?)\](.*?)\[\/quote\]/gi,"<p class=\"quoteStyle\" id=\"$1\">$2</p>");
			rep(/\[quote.*?=(.*?)\](.*?)\[\/quote.*?\]/gi,"<p class=\"quoteStyle\" id=\"$1\">$2</p>");
			rep(/\[size=(.*?)\]/gi,"<span style=\"font-size: $1px;\">");
			rep(/\[\/size\]/gi,"</span>");
			rep(/\[no](.*?)\[\/no\]/gi,"<span style=\"text-decoration: line-through;\">$1</span>");
			rep(/\[center](.*?)\[\/center\]/gi,"<p style=\"text-align: center;\">$1</p>");
			rep(/\[left](.*?)\[\/left\]/gi,"<p style=\"text-align: left;\">$1</p>");
			rep(/\[right](.*?)\[\/right\]/gi,"<p style=\"text-align: right;\">$1</p>");
			rep(/\[justify](.*?)\[\/justify\]/gi,"<p style=\"text-align: justify;\">$1</p>");
			rep(/\[video=youtube\](.*?)\[\/video\]/gi,"<div class=\"youtube\" title=\"$1\"><br><br>$1<\/div>");
			rep(/\[video=dailymotion\](.*?)\[\/video\]/gi,"<div class=\"dailymotion\" title=\"$1\"><br><br>$1<\/div>");
			rep(/\[video=veoh\](.*?)\[\/video\]/gi,"<div class=\"veoh\" title=\"$1\"><br><br>$1<\/div>");
			rep(/\[video=vimeo\](.*?)\[\/video\]/gi,"<div class=\"vimeo\" title=\"$1\"><br><br>$1<\/div>");
			rep(/\[video=jiwa\](.*?)\[\/video\]/gi,"<div class=\"jiwa\" title=\"$1\"><br><br>$1<\/div>");
			rep(/\[deezer\](.*?)\[\/deezer\]/gi,"<div class=\"deezer\" title=\"$1\"><br><br>$1<\/div>");
			rep(/\[picasa\](.*?)\[\/picasa\]/gi,"<div class=\"picasa\" title=\"$1\"><br><br><\/div>");


			//tableaux
			rep(/\[\|\]/gi,"</td><td>");
         rep(/\[ligne](.*?)\[\/ligne\]/gi,"<tr><td>$1</td></tr>");
         rep(/\[tableau](.*?)\[\/tableau\]/gi,"<table>$1</table>");
     	    // lists
         rep(/\[list\]\[\*\](.*?)\[\/list\]/gi, "<ul><li>$1</li></ul>");
         rep(/\[list=decimal\]\[\*\](.*?)\[\/list\]/gi, "<ol><li>$1</li></ol>");
         rep(/\[list=lower-alpha\]\[\*\](.*?)\[\/list\]/gi, "<ol style=\"list-style-type: lower-alpha;\"><li>$1</li></ol>");
         rep(/\[list\](.*?)\[\/list\]/gi, "<ul><li>$1</li></ul>");
         rep(/\[list=decimal\](.*?)\[\/list\]/gi, "<ol><li>$1</li></ol>");
         rep(/\[list=lower-alpha\](.*?)\[\/list\]/gi, "<ol style=\"list-style-type: lower-alpha;\"><li>$1</li></ol>");
         rep(/\[\*\]/gi, "</li><li>");


         // fix empty li's
         rep(/<li><br \/><\/li>/g, "");
         rep(/<li\/>/g, "");
			return s;
		}
	});

	// Register plugin
	tinymce.PluginManager.add('bbcodev2', tinymce.plugins.BBCodev2Plugin);
})();
