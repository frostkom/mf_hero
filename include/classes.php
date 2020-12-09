<?php

class mf_hero
{
	function __construct()
	{
		$this->meta_prefix = 'mf_hero_';
	}

	function wp_head()
	{
		// Have to check if rwmb_meta_boxes is used aswell
		/*if(!is_plugin_active("mf_widget_logic_select/index.php") || apply_filters('get_widget_search', 'hero-widget') > 0)
		{*/
			$plugin_include_url = plugin_dir_url(__FILE__);
			$plugin_version = get_plugin_version(__FILE__);

			mf_enqueue_style('style_hero', $plugin_include_url."style.php", $plugin_version);
		//}
	}

	function filter_is_file_used($arr_used)
	{
		global $wpdb;

		$result = $wpdb->get_results($wpdb->prepare("SELECT post_id FROM ".$wpdb->postmeta." WHERE meta_key = %s AND meta_value = %s", $this->meta_prefix.'image', $arr_used['id']));
		$rows = $wpdb->num_rows;

		if($rows > 0)
		{
			$arr_used['amount'] += $rows;

			foreach($result as $r)
			{
				if($arr_used['example'] != '')
				{
					break;
				}

				$arr_used['example'] = admin_url("post.php?action=edit&post=".$r->post_id);
			}
		}

		return $arr_used;
	}

	function widgets_init()
	{
		register_widget('widget_hero');
	}

	function get_gcd($a, $b)
	{
		return ($a % $b) ? $this->get_gcd($b, $a % $b) : $b;
	}

	function get_ratio($x, $y)
	{
		$gcd = $this->get_gcd($x, $y);

		return ($x / $gcd).":".($y / $gcd);
	}

	function meta_check_image()
	{
		global $post;

		$out = '';

		//$post_id = filter_input(INPUT_GET, 'post', FILTER_SANITIZE_NUMBER_INT);
		//$post_id = check_var('post', 'int');
		$post_id = $post->ID;

		if($post_id > 0)
		{
			$hero_title = get_post_meta($post_id, $this->meta_prefix.'title', true);
			$hero_image_id = get_post_meta($post_id, $this->meta_prefix.'image', true);

			if($hero_image_id > 0)
			{
				if(class_exists('mf_theme_core'))
				{
					global $obj_theme_core;

					if(!isset($obj_theme_core))
					{
						$obj_theme_core = new mf_theme_core();
					}

					$obj_theme_core->get_params();

					$website_max_width = isset($obj_theme_core->options['website_max_width']) ? $obj_theme_core->options['website_max_width'] : 2000;
				}

				else
				{
					$website_max_width = 2000;
				}

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
					$image_recommended_aspect_ratio = $this->get_ratio($image_recommended_width, $image_recommended_height);
					$image_aspect_ratio = $this->get_ratio($image_width, $image_height);

					$out .= get_toggler_container(array('type' => 'start', 'icon' => "fa fa-exclamation-triangle yellow", 'text' => sprintf(__("The image should have a ratio close to %s to better fill the container. It now has %s but you can change it by going through the list below.", 'lang_hero'), $image_recommended_aspect_ratio, $image_aspect_ratio)))
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

	function settings_hero()
	{
		$options_area = __FUNCTION__;

		add_settings_section($options_area, "", array($this, $options_area."_callback"), BASE_OPTIONS_PAGE);

		$arr_settings = array();
		$arr_settings['setting_hero_bg_color'] = __("Fade Color", 'lang_hero');

		show_settings_fields(array('area' => $options_area, 'object' => $this, 'settings' => $arr_settings));
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

	function admin_init()
	{
		if(!is_plugin_active("mf_base/index.php"))
		{
			deactivate_plugins(str_replace("include/classes.php", "index.php", plugin_basename(__FILE__)));
		}
	}

	function rwmb_meta_boxes($meta_boxes)
	{
		global $wpdb;

		$result = $wpdb->get_results($wpdb->prepare("SELECT ID FROM ".$wpdb->posts." INNER JOIN ".$wpdb->postmeta." ON ".$wpdb->posts.".ID = ".$wpdb->postmeta.".post_id WHERE post_type = %s AND post_status = %s AND (meta_key = %s OR meta_key = %s) AND meta_value != '' LIMIT 0, 3", 'page', 'publish', $this->meta_prefix.'title', $this->meta_prefix.'image'));

		if($wpdb->num_rows > 0)
		{
			foreach($result as $r)
			{
				do_log("Hero exists in ".get_post_title($r->ID)." (".get_permalink($r->ID)."). Please convert to widgets");
			}

			$meta_boxes[] = array(
				'id' => $this->meta_prefix.'hero',
				'title' => __("Hero", 'lang_hero'),
				'post_types' => array('page'),
				'context' => 'normal',
				'priority' => 'high',
				'fields' => array(
					array(
						'name' => __("Title", 'lang_hero'),
						'id' => $this->meta_prefix.'title',
						'type' => 'text',
						'attributes' => array(
							'condition_type' => 'hide_if_empty',
							'condition_field' => $this->meta_prefix.'content',
						),
					),
					array(
						'name' => __("Content", 'lang_hero'),
						'id' => $this->meta_prefix.'content',
						'type' => 'textarea',
					),
					array(
						'name' => __("Page", 'lang_hero')." <a href='".admin_url("post-new.php?post_type=page")."'><i class='fa fa-plus-circle fa-lg'></i></a>",
						'id' => $this->meta_prefix.'link',
						'type' => 'page',
						'attributes' => array(
							'condition_type' => 'show_if',
							'condition_field' => $this->meta_prefix.'external_link',
						),
					),
					array(
						'name' => __("External Link", 'lang_hero'),
						'id' => $this->meta_prefix.'external_link',
						'type' => 'url',
						'attributes' => array(
							'condition_type' => 'show_if',
							'condition_field' => $this->meta_prefix.'link',
						),
					),
					array(
						'id' => $this->meta_prefix.'image',
						'type' => 'file_advanced',
						'max_file_uploads' => 1,
						'mime_type' => 'image',
					),
					array(
						'id' => $this->meta_prefix.'check_image',
						'type' => 'custom_html',
						'callback' => array($this, 'meta_check_image'),
					),
					array(
						'name' => __("Fade to surrounding color", 'lang_hero'),
						'id' => $this->meta_prefix.'fade',
						'type' => 'select',
						'options' => get_yes_no_for_select(),
					),
				)
			);
		}

		return $meta_boxes;
	}

	function is_active_sidebar($is_active, $widget)
	{
		global $wp_query;

		$post = $wp_query->post;

		if(isset($post->ID))
		{
			if($widget == 'widget_front')
			{
				$post_id = $post->ID;
				$post_hero_title = get_post_meta($post_id, $this->meta_prefix.'title', true);
				$post_hero_image = get_post_meta_file_src(array('post_id' => $post_id, 'meta_key' => $this->meta_prefix.'image', 'is_image' => true));

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

	function dynamic_sidebar_after($widget)
	{
		global $wp_query;

		$post = $wp_query->post;

		if(isset($post->ID))
		{
			$post_id = $post->ID;

			switch($widget)
			{
				case 'widget_front':
					$post_hero_title = get_post_meta($post_id, $this->meta_prefix.'title', true);
					$post_hero_content = get_post_meta($post_id, $this->meta_prefix.'content', true);
					$post_hero_image_id = get_post_meta($post_id, $this->meta_prefix.'image', true);
					$post_hero_fade = get_post_meta($post_id, $this->meta_prefix.'fade', true);
					$post_hero_full_width_image = get_post_meta($post_id, $this->meta_prefix.'full_width_image', true);

					$post_hero_link = get_post_meta($post_id, $this->meta_prefix.'link', true);
					$post_hero_external_link = get_post_meta($post_id, $this->meta_prefix.'external_link', true);

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

					echo $this->get_widget($data);
				break;
			}
		}
	}

	function get_widget($data)
	{
		$out = $class = $a_start = $a_end = "";

		if(!isset($data['hero_image_id'])){														$data['hero_image_id'] = 0;}
		if(!isset($data['hero_image'])){														$data['hero_image'] = '';}
		if(!isset($data['hero_external_link'])){												$data['hero_external_link'] = '';}
		if(!isset($data['hero_fade']) || $data['hero_fade'] == ''){								$data['hero_fade'] = 'yes';}
		if(!isset($data['hero_full_width_image']) || $data['hero_full_width_image'] == ''){		$data['hero_full_width_image'] = 'no';}

		if($data['hero_title'] != '' || $data['hero_image_id'] > 0 || $data['hero_image'] != '')
		{
			if($data['hero_link'] > 0)
			{
				$a_start = "<a href='".get_permalink($data['hero_link'])."'>";
				$a_end = "</a>";
			}

			else if($data['hero_external_link'] != '')
			{
				$a_start = "<a href='".$data['hero_external_link']."'".(preg_match("/(youtube\.com|youtu.be)/i", $data['hero_external_link']) ? " rel='wp-video-lightbox'" : "").">";
				$a_end = "</a>";
			}

			if($data['hero_title'] != '')
			{
				$class .= ($class != '' ? " " : "")."has_text";
			}

			if($data['hero_image_id'] > 0 || $data['hero_image'] != '')
			{
				if($data['hero_title'] == '')
				{
					$class .= ($class != '' ? " " : "")."align_center";
				}

				else
				{
					$class .= ($class != '' ? " " : "")."align_right";
				}
			}

			$out = $data['before_widget']
				."<div".($class != '' ? " class='".$class."'" : "").">";

					if($data['hero_image_id'] > 0 || $data['hero_image'] != '')
					{
						$out .= "<div class='image".($data['hero_fade'] == 'yes' ? " image_fade" : "")."'>
							<div>"
								.$a_start
								.render_image_tag(array('id' => $data['hero_image_id'], 'src' => $data['hero_image']))
								.$a_end
							."</div>
						</div>";
					}

					if($data['hero_title'] != '')
					{
						$out .= $data['before_title']
							.$a_start
								.$data['hero_title']
							.$a_end
						.$data['after_title'];

						if($data['hero_content'] != '')
						{
							$out .= "<div class='content'>"
								.$a_start
									.apply_filters('the_content', $data['hero_content'])
								.$a_end
							."</div>";
						}
					}

				$out .= "</div>"
			.$data['after_widget'];
		}

		return $out;
	}
}

class widget_hero extends WP_Widget
{
	function __construct()
	{
		$this->widget_ops = array(
			'classname' => 'hero',
			'description' => __("Display Hero", 'lang_hero')
		);

		$this->arr_default = array(
			'hero_title' => "",
			'hero_content' => "",
			'hero_link' => 0,
			'hero_external_link' => "",
			'hero_image' => "",
			'hero_fade' => 'yes',
		);

		parent::__construct(str_replace("_", "-", $this->widget_ops['classname']).'-widget', __("Hero", 'lang_hero'), $this->widget_ops);
	}

	function widget($args, $instance)
	{
		global $obj_hero;

		extract($args);
		$instance = wp_parse_args((array)$instance, $this->arr_default);

		$data = $instance;
		$data['before_widget'] = $before_widget;
		$data['before_title'] = $before_title;
		$data['after_title'] = $after_title;
		$data['after_widget'] = $after_widget;

		if(!isset($obj_hero))
		{
			$obj_hero = new mf_hero();
		}

		echo $obj_hero->get_widget($data);
	}

	function update($new_instance, $old_instance)
	{
		$instance = $old_instance;
		$new_instance = wp_parse_args((array)$new_instance, $this->arr_default);

		$instance['hero_title'] = sanitize_text_field($new_instance['hero_title']);
		$instance['hero_content'] = strip_tags($new_instance['hero_content']);
		$instance['hero_link'] = sanitize_text_field($new_instance['hero_link']);
		$instance['hero_external_link'] = esc_url_raw($new_instance['hero_external_link']);
		$instance['hero_image'] = sanitize_text_field($new_instance['hero_image']);
		$instance['hero_fade'] = sanitize_text_field($new_instance['hero_fade']);

		return $instance;
	}

	function form($instance)
	{
		$instance = wp_parse_args((array)$instance, $this->arr_default);

		$arr_data = array();
		get_post_children(array('add_choose_here' => true), $arr_data);

		echo "<div class='mf_form'>"
			.show_textfield(array('name' => $this->get_field_name('hero_title'), 'value' => $instance['hero_title'], 'text' => __("Title", 'lang_hero'), 'xtra' => " id='".$this->widget_ops['classname']."-title'"))
			.show_textarea(array('name' => $this->get_field_name('hero_content'), 'text' => __("Content", 'lang_hero'), 'value' => $instance['hero_content']));

			if($instance['hero_external_link'] == '')
			{
				echo show_select(array('data' => $arr_data, 'name' => $this->get_field_name('hero_link'), 'text' => __("Link", 'lang_hero'), 'value' => $instance['hero_link']));
			}

			if(!($instance['hero_link'] > 0))
			{
				echo show_textfield(array('type' => 'url', 'name' => $this->get_field_name('hero_external_link'), 'value' => $instance['hero_external_link'], 'text' => __("External Link", 'lang_hero')));
			}

			echo get_media_library(array('type' => 'image', 'name' => $this->get_field_name('hero_image'), 'value' => $instance['hero_image']))
			.show_select(array('data' => get_yes_no_for_select(), 'name' => $this->get_field_name('hero_fade'), 'text' => __("Fade to surrounding color", 'lang_hero'), 'value' => $instance['hero_fade']))
		."</div>";
	}
}