<?php
/*
Plugin Name: MF Hero
Plugin URI: https://github.com/frostkom/mf_hero
Description:
Version: 2.5.6
Licence: GPLv2 or later
Author: Martin Fors
Author URI: https://martinfors.se
Text Domain: lang_hero
Domain Path: /lang

Depends: Meta Box, MF Base
GitHub Plugin URI: frostkom/mf_hero
*/

if(!function_exists('is_plugin_active') || function_exists('is_plugin_active') && is_plugin_active("mf_base/index.php"))
{
	include_once("include/classes.php");

	$obj_hero = new mf_hero();

	add_action('init', array($obj_hero, 'init'));

	if(is_admin())
	{
		register_uninstall_hook(__FILE__, 'uninstall_hero');

		add_action('rwmb_meta_boxes', array($obj_hero, 'rwmb_meta_boxes'));
	}

	else
	{
		add_action('wp_head', array($obj_hero, 'wp_head'), 0);

		add_filter('is_active_sidebar', array($obj_hero, 'is_active_sidebar'), 10, 2);
		add_action('dynamic_sidebar_after', array($obj_hero, 'dynamic_sidebar_after'));
	}

	add_filter('filter_options_params', array($obj_hero, 'filter_options_params'));
	add_filter('filter_is_file_used', array($obj_hero, 'filter_is_file_used'));
	add_action('widgets_init', array($obj_hero, 'widgets_init'));

	load_plugin_textdomain('lang_hero', false, dirname(plugin_basename(__FILE__))."/lang/");

	function activate_hero()
	{
		require_plugin("meta-box/meta-box.php", "Meta Box");
	}

	function uninstall_hero()
	{
		mf_uninstall_plugin(array(
			'options' => array('setting_hero_bg_color', 'setting_hero_text_color'),
		));
	}
}