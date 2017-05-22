<?php

class mf_hero
{
	function __construct(){}

	function get_widget($data)
	{
		$class = $a_start = $a_end = "";

		if($data['hero_title'] != '' || $data['hero_image'] != '')
		{
			if($data['hero_link'] > 0)
			{
				$a_start = "<a href='".get_permalink($data['hero_link'])."'>";
				$a_end = "</a>";
			}

			if($data['hero_title'] != '' && $data['hero_image'] != '')
			{
				$class = "align_right";
			}

			else if($data['hero_image'] != '')
			{
				$class = "align_center";
			}

			$out = $data['before_widget']
				."<div".($class != '' ? " class='".$class."'" : "").">";

					if($data['hero_image'] != '')
					{
						$out .= "<div class='image'>
							<div>"
								.$a_start
									."<img src='".$data['hero_image']."'>"
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
		$widget_ops = array(
			'classname' => 'hero',
			'description' => __("Display Hero", 'lang_hero')
		);

		$control_ops = array('id_base' => 'hero-widget');

		parent::__construct('hero-widget', __("Hero", 'lang_hero'), $widget_ops, $control_ops);
	}

	function widget($args, $instance)
	{
		global $wpdb;

		extract($args);

		$data = $instance;
		$data['before_widget'] = $before_widget;
		$data['before_title'] = $before_title;
		$data['after_title'] = $after_title;
		$data['after_widget'] = $after_widget;

		$obj_hero = new mf_hero();
		echo $obj_hero->get_widget($data);
	}

	function update($new_instance, $old_instance)
	{
		$instance = $old_instance;

		$instance['hero_title'] = strip_tags($new_instance['hero_title']);
		$instance['hero_content'] = strip_tags($new_instance['hero_content']);
		$instance['hero_link'] = strip_tags($new_instance['hero_link']);
		$instance['hero_image'] = strip_tags($new_instance['hero_image']);

		return $instance;
	}

	function form($instance)
	{
		global $wpdb;

		$defaults = array(
			'hero_title' => "",
			'hero_content' => "",
			'hero_link' => 0,
			'hero_image' => "",
		);
		$instance = wp_parse_args((array)$instance, $defaults);

		$arr_data = array();
		get_post_children(array('add_choose_here' => true, 'output_array' => true), $arr_data);

		echo "<div class='mf_form'>"
			.show_textfield(array('name' => $this->get_field_name('hero_title'), 'value' => $instance['hero_title'], 'text' => __("Title", 'lang_hero')))
			.show_textarea(array('name' => $this->get_field_name('hero_content'), 'text' => __("Content", 'lang_hero'), 'value' => $instance['hero_content']))
			.show_select(array('data' => $arr_data, 'name' => $this->get_field_name('hero_link'), 'text' => __("Link", 'lang_hero'), 'value' => $instance['hero_link']))
			.get_file_button(array('name' => $this->get_field_name('hero_image'), 'value' => $instance['hero_image']))
		."</div>";
	}
}