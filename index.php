<?php
/*
Plugin Name: MF Hero
Plugin URI: https://github.com/frostkom/mf_hero
Description: 
Version: 2.1.1
Author: Martin Fors
Author URI: http://frostkom.se
Text Domain: lang_hero
Domain Path: /lang

GitHub Plugin URI: frostkom/mf_hero
*/

include_once("include/classes.php");
include_once("include/functions.php");

add_action('init', 'init_hero');
add_action('widgets_init', 'widgets_hero');

if(is_admin())
{
	add_action('admin_init', 'settings_hero');

	add_action('rwmb_meta_boxes', 'meta_boxes_hero');
}

else
{
	add_filter('is_active_sidebar', 'is_active_sidebar_hero', 10, 2);
	add_action('dynamic_sidebar_after', 'dynamic_sidebar_after_hero');
}

load_plugin_textdomain('lang_hero', false, dirname(plugin_basename(__FILE__)).'/lang/');

function activate_hero()
{
	require_plugin("meta-box/meta-box.php", "Meta Box");
}