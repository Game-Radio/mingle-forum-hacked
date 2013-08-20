<?php
if ( !defined('WP_CONTENT_DIR') )
	define( 'WP_CONTENT_DIR', ABSPATH . 'wp-content' );

define('WPFPLUGIN', "mingle-forum");

define('WPFDIR', dirname(plugin_basename(__FILE__)));
define('WPFPATH', WP_CONTENT_DIR . '/plugins/' . WPFDIR . '/');
define('WPFURL', WP_CONTENT_URL . '/plugins/' . WPFDIR . '/');
define('SKINDIR', WPFPATH . '/skins/');
define('SKINURL', WPFURL . '/skins/');
define('NO_SKIN_SCREENSHOT_URL', WP_CONTENT_URL . '/plugins/' . WPFDIR . '/skins/default.png');

define("ADMIN_PROFILE_URL", get_bloginfo("url")."/wp-admin/user-edit.php?user_id=");
define("PROFILE_URL", get_bloginfo("url")."/wp-admin/profile.php");

define("ADMIN_ROW_COL", "rows='8' cols='35'");
define("ROW_COL", "rows='20' cols='80'");

define('MAIN', "main");
define('THREAD', "thread");
define('SEARCH', "search");
define('PROFILE', "profile");
define('POSTREPLY', "postreply");
define('EDITPOST', "editpost");
define("NEWTOPICS", "newtopics");
define("NEWTOPIC", "newtopic");

define("CAT", 	__("Category", "mingleforum"));
define("FORUM", __("Forum", "mingleforum"));
define("GROUP", __("Group", "mingleforum"));
define("TOPIC", __("Topic", "mingleforum"));
define("POST", 	__("Post", "mingleforum"));

// Maybe change
define("SORT_ORDER", "DESC");
