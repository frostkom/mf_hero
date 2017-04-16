<?php

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

function meta_boxes_hero($meta_boxes)
{
	$meta_prefix = "mf_hero_";

	/*$arr_data_widget_area = array();
	$arr_data_widget_area[''] = "-- ".__("Choose here", 'lang_hero')." --";

	foreach($GLOBALS['wp_registered_sidebars'] as $sidebar)
	{
		$arr_data_widget_area[$sidebar['id']] = $sidebar['name'];
	}*/

	$arr_data_link = array();
	get_post_children(array('add_choose_here' => true, 'output_array' => true), $arr_data_link);

	$meta_boxes[] = array(
		'id' => $meta_prefix.'hero',
		'title' => __("Hero", 'lang_hero'),
		'pages' => array('page'),
		'context' => 'after_title',
		'priority' => 'high',
		'fields' => array(
			/*array(
				'name' => __("Widget Area", 'lang_hero'),
				'id' => $meta_prefix.'widget_area',
				'type' => 'select',
				'options' => $arr_data_widget_area,
				'attributes' => array(
					'condition_type' => 'hide_if',
					'condition_field' => '#'.$meta_prefix.'title, #'.$meta_prefix.'content, #'.$meta_prefix.'link, .rwmb-field input[name='.$meta_prefix.'image]',
				),
			),*/
			array(
				'name' => __("Title", 'lang_hero'),
				'id' => $meta_prefix.'title',
				'type' => 'text',
			),
			array(
				'name' => __("Content", 'lang_hero'),
				'id' => $meta_prefix.'content',
				'type' => 'textarea',
			),
			array(
				'name' => __("Link", 'lang_hero'),
				'id' => $meta_prefix.'link',
				'type' => 'select',
				'options' => $arr_data_link,
			),
			array(
				'id' => $meta_prefix.'image',
				'type' => 'file_advanced',
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

			if($post_hero_title != '')
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

		if($widget == 'widget_front') //$post_hero_widget_area = get_post_meta($post_id, $meta_prefix.'widget_area', true);
		{
			$post_hero_title = get_post_meta($post_id, $meta_prefix.'title', true);

			if($post_hero_title != '')
			{
				$post_hero_content = get_post_meta($post_id, $meta_prefix.'content', true);
				$post_hero_link = get_post_meta($post_id, $meta_prefix.'link', true);
				//$post_hero_image = get_post_meta($post_id, $meta_prefix.'image', true);
				$post_hero_image = get_post_meta_file_src(array('post_id' => $post_id, 'meta_key' => $meta_prefix.'image', 'is_image' => true));

				$obj_hero = new mf_hero();

				$data = array(
					'before_widget' => "<div class='widget hero'>",
					'before_title' => "<h3>",
					'after_title' => "</h3>",
					'after_widget' => "</div>",
					'hero_title' => $post_hero_title,
					'hero_content' => $post_hero_content,
					'hero_link' => $post_hero_link,
					'hero_image' => $post_hero_image,
				);

				echo $obj_hero->get_widget($data);
			}
		}
	}
}