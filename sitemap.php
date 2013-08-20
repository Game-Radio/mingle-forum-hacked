<?php

global $mingleforum;
$root = dirname(dirname(dirname(dirname(__FILE__))));
require_once($root . '/wp-load.php');
$mingleforum->setup_links();
$mingleforum->get_forum_admin_ops();
header('Content-type: application/xml; charset="utf-8"', true);
$mingleforum->do_sitemap();
