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

function get_gcd($a, $b)
{
    return ($a % $b) ? get_gcd($b, $a % $b) : $b;
}

function get_ratio($x, $y)
{
    $gcd = get_gcd($x, $y);

    return ($x / $gcd).":".($y / $gcd);
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

		if($hero_image_id > 0)
		{
			list($options_params, $options) = get_params();
			$website_max_width = isset($options['website_max_width']) ? $options['website_max_width'] : 2000;

			if($hero_title != '')
			{
				$image_recommended_width = 800;
				$image_recommended_height = 450;
				$image_recommended_min_width = mf_format_number(($website_max_width / 2));
			}

			else
			{
				$image_recommended_width = 930;
				$image_recommended_height = 350;
				$image_recommended_min_width = mf_format_number($website_max_width);
			}

			$arr_image = wp_get_attachment_image_src($hero_image_id, 'full');

			$image_width = $arr_image[1];
			$image_height = $arr_image[2];
			$image_ratio = $image_height > 0 ? ($image_width / $image_height) : 0;
			$image_recommended_ratio = mf_format_number(($image_recommended_width / $image_recommended_height));

			if($image_width == 0)
			{
				$out .= "<p>".__("The image does not seam to exist anymore", 'lang_hero')."</p>";
			}

			else if($image_width < $image_recommended_min_width)
			{
				$out .= "<p>".sprintf(__("The image should be at least %d px in width to fill the width of the container. It is now only %d px wide so I would urge you to upload a larger image", 'lang_hero'), $image_recommended_min_width, $image_width)."</p>";
			}

			else if($image_ratio > ($image_recommended_ratio * 1.1) || $image_ratio < ($image_recommended_ratio * .9))
			{
				$image_recommended_aspect_ratio = get_ratio($image_recommended_width, $image_recommended_height);
				$image_aspect_ratio = get_ratio($image_width, $image_height);

				$out .= get_toggler_container(array('type' => 'start', 'icon' => "fa-warning yellow", 'text' => sprintf(__("The image should have a ratio close to %s to better fill the container. It now has %s but you can change it by going through the list below.", 'lang_hero'), $image_recommended_aspect_ratio, $image_aspect_ratio)))
					."<ol>
						<li>".__("Press Edit next to the thumbnail above", 'lang_hero')."</li>
						<li>".__("Press Edit image right below the image", 'lang_hero')."</li>
						<li>".__("Make a selection on top of the image", 'lang_hero')."</li>
						<li>".sprintf(__("Add %s in Aspect Ratio in the right column", 'lang_hero'), $image_recommended_aspect_ratio)."</li>
						<li>".__("You might have to do #3 and #4 a few times until you are happy with the selection", 'lang_hero')."</li>
						<li>".__("Press the crop tool to the left above the image", 'lang_hero')."</li>
						<li>".__("If you are happy with the result, press Save and you are done!", 'lang_hero')."</li>
					</ol>"
				.get_toggler_container(array('type' => 'end'));
			}
		}
	}

	return $out != '' ? $out : '&nbsp;';
}

function meta_boxes_hero($meta_boxes)
{
	$meta_prefix = "mf_hero_";

	//$arr_data_link = array();
	//get_post_children(array('add_choose_here' => true), $arr_data_link);

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
			array(
				'name' => __("Page", 'lang_hero'),
				'id' => $meta_prefix.'link',
				'type' => 'page',
				//'type' => 'select',
				//'options' => $arr_data_link,
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
			/*array(
				'name' => __("Full Width Image", 'lang_hero'),
				'id' => $meta_prefix.'full_width_image',
				'type' => 'select',
				'options' => get_yes_no_for_select(array('add_choose_here' => true)),
				'std' => 'no',
			),*/
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

	else //If this is called from style.php it has to return true
	{
		$is_active = true;
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
			$post_hero_fade = get_post_meta($post_id, $meta_prefix.'fade', true);
			$post_hero_full_width_image = get_post_meta($post_id, $meta_prefix.'full_width_image', true);

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
				'hero_fade' => $post_hero_fade,
				'hero_full_width_image' => $post_hero_full_width_image,
			);

			$obj_hero = new mf_hero();
			echo $obj_hero->get_widget($data);
		}
	}
}