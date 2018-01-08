<?php

function settings_hero()
{
	$options_area = __FUNCTION__;

	add_settings_section($options_area, "", $options_area."_callback", BASE_OPTIONS_PAGE);

	$arr_settings = array();
	$arr_settings['setting_hero_bg_color'] = __("Fade Color", 'lang_hero');

	show_settings_fields(array('area' => $options_area, 'settings' => $arr_settings));
}

function settings_hero_callback()
{
	$setting_key = get_setting_key(__FUNCTION__);

	echo settings_header($setting_key, __("Hero", 'lang_hero'));
}

function setting_hero_bg_color_callback()
{
	$setting_key = get_setting_key(__FUNCTION__);
	$option = get_option($setting_key, "#019cdb");

	echo show_textfield(array('type' => 'color', 'name' => $setting_key, 'value' => $option));
}