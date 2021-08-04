<?php

if(!defined('ABSPATH'))
{
	header("Content-Type: text/css; charset=utf-8");

	$folder = str_replace("/wp-content/plugins/mf_hero/include", "/", dirname(__FILE__));

	require_once($folder."wp-load.php");
}

if(!isset($obj_theme_core))
{
	$obj_theme_core = new mf_theme_core();
}

$obj_theme_core->get_params();

if(isset($obj_theme_core->options['hero_bg_color']) && $obj_theme_core->options['hero_bg_color'] != '')
{
	$setting_hero_bg_color = $obj_theme_core->options['hero_bg_color'];
}

else
{
	$setting_hero_bg_color = get_option('setting_hero_bg_color');
}

echo "@media all
{";

	if($setting_hero_bg_color != '')
	{
		echo ".widget.hero.allow_bg_color
		{
			background: ".$setting_hero_bg_color.";
		}";
	}

		echo "#wrapper .widget.hero > div
		{
			overflow: hidden;
			padding-top: 0;
			padding-bottom: 0;
		}

			.widget.hero .has_text
			{
				padding-right: 2em;
				padding-left: 2em;
			}

			.widget.hero .align_center
			{
				text-align: center;
			}

			.widget.hero .image
			{
				display: block;
				position: relative;
			}

				.widget.hero .align_right .image, .widget.hero .align_left .image
				{
					width: 59%;
				}

				.widget.hero .align_right.has_text
				{
					padding-right: 0;
				}

					.widget.hero .align_right .image
					{
						float: right;
					}

				.widget.hero .align_left.has_text
				{
					padding-left: 0;
				}

					.widget.hero .align_left .image
					{
						float: left;
					}";

				if($setting_hero_bg_color != '')
				{
					echo ".widget.hero .image.image_fade:before, .widget.hero .image.image_fade:after
					{
						bottom: 0;
						content: '';
						position: absolute;
						top: 0;
						width: 20%;
					}

						.widget.hero .image.image_fade:before
						{
							background: linear-gradient(to right, ".$setting_hero_bg_color." 0, transparent 100%);
							left: 0;
						}

						.widget.hero .image.image_fade:after
						{
							background: linear-gradient(to left, ".$setting_hero_bg_color." 0, transparent 100%);
							right: 0;
						}

							.is_mobile .widget.hero .image.image_fade
							{
								float: none;
								margin-bottom: -10%;
								text-align: center;
								width: 100%;
							}

					.widget.hero .image.image_fade div:after
					{
						background: linear-gradient(to top, ".$setting_hero_bg_color." 0, transparent 100%);
						bottom: 0;
						content: '';
						left: 0;
						position: absolute;
						right: 0;
						height: 20%;
					}";
				}

					echo ".widget.hero img
					{
						display: inline-block;
					}

					.widget.hero h3, .widget.hero .content
					{
						position: relative;
					}

						.widget.hero .align_right h3, .widget.hero .align_right .content, .widget.hero .align_left h3, .widget.hero .align_left .content
						{
							clear: left;
							float: left;
							width: 50%;
						}

						.widget.hero .align_right h3, .widget.hero .align_right .content
						{
							margin-right: -10% !important;
						}

						.widget.hero .align_left h3, .widget.hero .align_left .content
						{
							margin-left: -10% !important;
							text-align: right;
						}

							.is_mobile .widget.hero h3, .is_mobile .widget.hero .content
							{
								float: none;
								margin: 0;
								text-align: center;
								width: 100%;
							}

						.widget.hero h3
						{
							font-size: 5em !important;
							margin-top: 1.3em !important;
						}

							.is_mobile .widget.hero h3
							{
								font-size: 3em !important;
							}

						.widget.hero .content p
						{
							padding-bottom: 1em;
						}
}";