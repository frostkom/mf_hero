<?php

header("Content-Type: text/css; charset=utf-8");

if(!defined('ABSPATH'))
{
	$folder = str_replace("/wp-content/plugins/mf_hero/include", "/", dirname(__FILE__));

	require_once($folder."wp-load.php");
}

$setting_hero_bg_color = get_option_or_default('setting_hero_bg_color', "#019cdb");

echo "@media all
{
	.widget.hero > div
	{
		overflow: hidden;
	}

		.widget.hero .align_center
		{
			text-align: center;
		}

		.widget.hero .image
		{
			display: inline-block;
			position: relative;
		}

			.widget.hero .align_right .image, .widget.hero .align_left .image
			{
				width: 60%;
			}
		
			.widget.hero .align_right .image
			{
				float: right;
			}

			.widget.hero .align_left .image
			{
				float: left;
			}

			.widget.hero .image:before, .widget.hero .image:after
			{
				bottom: 0;
				content: '';
				position: absolute;
				top: 0;
				width: 20%;
			}

				.widget.hero .image:before
				{
					background: linear-gradient(to right, ".$setting_hero_bg_color." 0, transparent 100%);
					left: 0;
				}

				.widget.hero .image:after
				{
					background: linear-gradient(to left, ".$setting_hero_bg_color." 0, transparent 100%);
					right: 0;
				}

					.is_mobile .widget.hero .image
					{
						float: none;
						margin-bottom: -10%;
						width: 100%;
					}

			.widget.hero .image div:after
			{
				background: linear-gradient(to top, ".$setting_hero_bg_color." 0, transparent 100%);
				bottom: 0;
				content: '';
				left: 0;
				position: absolute;
				right: 0;
				height: 20%;
			}

				.widget.hero img
				{
					display: block;
				}

				.widget.hero h3, .widget.hero .content
				{
					position: relative;
				}

					.widget.hero .align_right h3, .widget.hero .align_right .content
					{
						clear: left;
						float: left;
						margin-right: -10%;
						width: 50%;
					}

					.widget.hero .align_left h3, .widget.hero .align_left .content
					{
						clear: right;
						float: right;
						margin-left: -10%;
						text-align: right;
						width: 50%;
					}

						.is_mobile .widget.hero h3, .is_mobile .widget.hero .content
						{
							float: none;
							margin: 0;
							text-align: left;
							width: 100%;
						}

					.widget.hero h3
					{
						font-size: 5em;
						font-weight: normal;
					}

						.is_mobile .widget.hero h3
						{
							font-size: 3em;
						}
}";