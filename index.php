<?php
/*
Plugin Name: MF Hero
Plugin URI: https://github.com/frostkom/mf_hero
Description: 
Version: 2.2.13
Licence: GPLv2 or later
Author: Martin Fors
Author URI: https://frostkom.se
Text Domain: lang_hero
Domain Path: /lang

Depends: Meta Box, MF Base
GitHub Plugin URI: frostkom/mf_hero
*/

include_once("include/classes.php");
include_once("include/functions.php");

$obj_hero = new mf_hero();

if(is_admin())
{
	add_action('admin_init', 'settings_hero');

	add_action('rwmb_meta_boxes', array($obj_hero, 'rwmb_meta_boxes'));
}

else
{
	add_action('wp_head', array($obj_hero, 'wp_head'), 0);

	add_filter('is_active_sidebar', array($obj_hero, 'is_active_sidebar'), 10, 2);
	add_action('dynamic_sidebar_after', array($obj_hero, 'dynamic_sidebar_after'));
}

add_action('widgets_init', array($obj_hero, 'widgets_init'));

load_plugin_textdomain('lang_hero', false, dirname(plugin_basename(__FILE__)).'/lang/');

function activate_hero()
{
	require_plugin("meta-box/meta-box.php", "Meta Box");
}