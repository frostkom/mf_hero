<?php
/*
Plugin Name: MF Hero
Plugin URI: https://github.com/frostkom/mf_hero
Description:
Version: 2.6.2
Licence: GPLv2 or later
Author: Martin Fors
Author URI: https://martinfors.se
Text Domain: lang_hero
Domain Path: /lang

Depends: Meta Box, MF Base
GitHub Plugin URI: frostkom/mf_hero
*/

if(!function_exists('is_plugin_active') || function_exists('is_plugin_active') && is_plugin_active("mf_base/index.php") && is_plugin_active("mf_theme_core/index.php"))
{
	include_once("include/classes.php");

	$obj_hero = new mf_hero();

	add_action('cron_base', 'activate_hero', mt_rand(1, 10));

	add_action('enqueue_block_editor_assets', array($obj_hero, 'enqueue_block_editor_assets'));
	add_action('init', array($obj_hero, 'init'));

	if(is_admin())
	{
		register_activation_hook(__FILE__, 'activate_hero');

		add_filter('display_post_states', array($obj_hero, 'display_post_states'), 10, 2);

		add_action('rwmb_meta_boxes', array($obj_hero, 'rwmb_meta_boxes'));
	}

	else
	{
		add_filter('is_active_sidebar', array($obj_hero, 'is_active_sidebar'), 10, 2);
		add_action('dynamic_sidebar_after', array($obj_hero, 'dynamic_sidebar_after'));
	}

	add_filter('filter_options_params', array($obj_hero, 'filter_options_params'));
	add_filter('filter_is_file_used', array($obj_hero, 'filter_is_file_used'));

	add_action('widgets_init', array($obj_hero, 'widgets_init'));

	function activate_hero()
	{
		require_plugin("meta-box/meta-box.php", "Meta Box");
	}
}