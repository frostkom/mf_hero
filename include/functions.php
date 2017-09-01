<?php

function init_hero()
{
	if(!is_admin())
	{
		mf_enqueue_style('style_hero', plugin_dir_url(__FILE__)."style.php", get_plugin_version(__FILE__));
	}
}

function widgets_hero()
{
	register_widget('widget_hero');
}

function settings_hero()
{
	$options_area = __FUNCTION__;

	add_settings_section($options_area, "", $options_area."_callback", BASE_OPTIONS_PAGE);

	$arr_settings = array();
	$arr_settings['setting_hero_bg_color'] = __("Background Color", 'lang_hero');

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

function meta_check_image()
{
	global $wpdb;

	$meta_prefix = "mf_hero_";

	$out = '';

	$post_id = filter_input(INPUT_GET, 'post', FILTER_SANITIZE_NUMBER_INT);

	if($post_id > 0)
	{
		$hero_title = get_post_meta($post_id, $meta_prefix.'title', true);
		$hero_image_id = get_post_meta($post_id, $meta_prefix.'image', true);

		if($hero_image_id > 0 && get_current_user_id() == 1)
		{
			list($options_params, $options) = get_params();
			$website_max_width = isset($options['website_max_width']) ? $options['website_max_width'] : 2000;

			$arr_image = wp_get_attachment_image_src($hero_image_id, 'full');

			$image_width = $arr_image[1];
			$image_height = $arr_image[2];
			$image_ratio = ($image_width / $image_height);

			if($hero_title != '')
			{
				$image_recommended_width = mf_format_number(($website_max_width / 2));
				$image_recommended_ratio = mf_format_number((800 / 450));
			}

			else
			{
				$image_recommended_width = mf_format_number($website_max_width);
				$image_recommended_ratio = mf_format_number((930 / 350));
			}

			if($image_width < $image_recommended_width)
			{
				$out .= sprintf(__("The image should be at least %d px in width to fill the width of the container. It is now only %d px wide", 'lang_hero'), $image_width, $image_recommended_width);
			}

			else if($image_ratio > ($image_recommended_ratio * 1.1) || $image_ratio < ($image_recommended_ratio * .9))
			{
				$out .= sprintf(__("The image should have a ratio close to %d. It now has %s (%d x %d)", 'lang_hero'), $image_recommended_ratio, $image_ratio, $image_width, $image_height);
			}
		}
	}

	return $out != '' ? $out : '&nbsp;';
}

function meta_boxes_hero($meta_boxes)
{
	$meta_prefix = "mf_hero_";

	$arr_data_link = array();
	get_post_children(array('add_choose_here' => true, 'output_array' => true), $arr_data_link);

	$meta_boxes[] = array(
		'id' => $meta_prefix.'hero',
		'title' => __("Hero", 'lang_hero'),
		'post_types' => array('page'),
		'context' => 'after_title',
		'priority' => 'high',
		'fields' => array(
			/*array(
				'name' => __("Widget Area", 'lang_hero'),
				'id' => $meta_prefix.'widget_area',
				'type' => 'select',
				'options' => get_sidebars_for_select(),
				'attributes' => array(
					'condition_type' => 'hide_if_empty',
					'condition_field' => '#'.$meta_prefix.'title, #'.$meta_prefix.'content, #'.$meta_prefix.'link, .rwmb-field input[name='.$meta_prefix.'image]',
				),
			),*/
			array(
				'name' => __("Title", 'lang_hero'),
				'id' => $meta_prefix.'title',
				'type' => 'text',
				'attributes' => array(
					'condition_type' => 'hide_if_empty',
					'condition_field' => $meta_prefix.'content',
				),
			),
			array(
				'name' => __("Content", 'lang_hero'),
				'id' => $meta_prefix.'content',
				'type' => 'textarea',
			),
			/*array(
				'name' => __("Link", 'lang_hero'),
				'id' => $meta_prefix.'link',
				'type' => 'select',
				'options' => $arr_data_link,
			),*/
			array(
				'name' => __("Page", 'lang_hero'),
				'id' => $meta_prefix.'link',
				'type' => 'select', //Replace with 'page'
				'options' => $arr_data_link,
				//'options' => get_posts_for_select(array('add_choose_here' => true, 'optgroup' => false)),
				'attributes' => array(
					'condition_type' => 'show_if',
					'condition_field' => $meta_prefix.'external_link',
				),
			),
			array(
				'name' => __("External Link", 'lang_hero'),
				'id' => $meta_prefix.'external_link',
				'type' => 'url',
				'attributes' => array(
					'condition_type' => 'show_if',
					'condition_field' => $meta_prefix.'link',
				),
			),
			array(
				'id' => $meta_prefix.'image',
				'type' => 'file_advanced',
			),
			array(
				'id' => $meta_prefix.'check_image',
				'type' => 'custom_html',
				'callback' => 'meta_check_image',
			),
			array(
				'name' => __("Fade to surrounding color", 'lang_hero'),
				'id' => $meta_prefix.'fade',
				'type' => 'select',
				'options' => get_yes_no_for_select(),
			),
		)
	);

	return $meta_boxes;
}

function is_active_sidebar_hero($is_active, $widget)
{
	global $wp_query;

	$meta_prefix = "mf_hero_";

	$post = $wp_query->post;

	if(isset($post->ID))
	{
		if($widget == 'widget_front') //$post_hero_widget_area = get_post_meta($post_id, $meta_prefix.'widget_area', true);
		{
			$post_id = $post->ID;
			$post_hero_title = get_post_meta($post_id, $meta_prefix.'title', true);
			$post_hero_image = get_post_meta_file_src(array('post_id' => $post_id, 'meta_key' => $meta_prefix.'image', 'is_image' => true));

			if($post_hero_title != '' || $post_hero_image != '')
			{
				$is_active = true;
			}
		}
	}

	return $is_active;
}

function dynamic_sidebar_after_hero($widget)
{
	global $wp_query;

	$meta_prefix = "mf_hero_";

	$post = $wp_query->post;

	if(isset($post->ID))
	{
		$post_id = $post->ID;

		if($widget == 'widget_front')
		{
			$post_hero_title = get_post_meta($post_id, $meta_prefix.'title', true);
			$post_hero_content = get_post_meta($post_id, $meta_prefix.'content', true);
			$post_hero_image_id = get_post_meta($post_id, $meta_prefix.'image', true);
			//$post_hero_image = get_post_meta_file_src(array('post_id' => $post_id, 'meta_key' => $meta_prefix.'image', 'is_image' => true));
			$post_hero_fade = get_post_meta($post_id, $meta_prefix.'fade', true);

			$post_hero_link = get_post_meta($post_id, $meta_prefix.'link', true);
			$post_hero_external_link = get_post_meta($post_id, $meta_prefix.'external_link', true);

			$data = array(
				'before_widget' => "<div class='widget hero'>",
				'before_title' => "<h3>",
				'after_title' => "</h3>",
				'after_widget' => "</div>",
				'hero_title' => $post_hero_title,
				'hero_content' => $post_hero_content,
				'hero_link' => $post_hero_link,
				'hero_external_link' => $post_hero_external_link,
				'hero_image_id' => $post_hero_image_id,
				//'hero_image' => $post_hero_image,
				'hero_fade' => $post_hero_fade,
			);

			$obj_hero = new mf_hero();
			echo $obj_hero->get_widget($data);
		}
	}
}