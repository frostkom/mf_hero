<?php

class mf_hero
{
	function __construct(){}

	function get_widget($data)
	{
		$out = $data['before_widget']
			."<div class='align_right'>";

				if($data['hero_image'] != '')
				{
					$out .= "<div class='hero_image'><div><img src='".$data['hero_image']."'></div></div>";
				}

				$out .= $data['before_title'].$data['hero_title'].$data['after_title']
				."<div class='hero_content'>";

					if($data['hero_link'] > 0)
					{
						$out .= "<a href='".get_permalink($data['hero_link'])."'>";
					}

						$out .= apply_filters('the_content', $data['hero_content']);

					if($data['hero_link'] > 0)
					{
						$out .= "</a>";
					}

				$out .= "</div>
			</div>"
		.$data['after_widget'];

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

		wp_enqueue_style('style_hero', plugin_dir_url(__FILE__)."style.php");
	}

	function widget($args, $instance)
	{
		global $wpdb;

		extract($args);

		if($instance['hero_title'] != '' && $instance['hero_content'] != '')
		{
			$obj_hero = new mf_hero();

			$data = $instance;
			$data['before_widget'] = $before_widget;
			$data['before_title'] = $before_title;
			$data['after_title'] = $after_title;
			$data['after_widget'] = $after_widget;

			echo $obj_hero->get_widget($data);
		}
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

		echo "<p>"
			.show_textfield(array('name' => $this->get_field_name('hero_title'), 'value' => $instance['hero_title'], 'text' => __("Title", 'lang_hero'), 'xtra' => " class='widefat'"))
		."</p>
		<p>"
			.show_textarea(array('name' => $this->get_field_name('hero_content'), 'value' => $instance['hero_content'], 'text' => __("Content", 'lang_hero'), 'xtra' => " class='widefat'"))
		."</p>
		<p>"
			.show_select(array('data' => $arr_data, 'name' => $this->get_field_name('hero_link'), 'text' => __("Link", 'lang_hero'), 'value' => $instance['hero_link'], 'xtra' => " class='widefat'"))
		."</p>
		<p>"
			.get_file_button(array('name' => $this->get_field_name('hero_image'), 'value' => $instance['hero_image']))
		."</p>";
	}
}