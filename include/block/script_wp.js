(function()
{
	var __ = wp.i18n.__,
		el = wp.element.createElement,
		registerBlockType = wp.blocks.registerBlockType,
		SelectControl = wp.components.SelectControl,
		TextControl = wp.components.TextControl,
		MediaUpload = wp.blockEditor.MediaUpload,
	    Button = wp.components.Button,
		MediaUploadCheck = wp.blockEditor.MediaUploadCheck;

	registerBlockType('mf/hero',
	{
		title: __("Hero", 'lang_hero'),
		description: __("Display a Hero", 'lang_hero'),
		icon: 'megaphone',
		category: 'widgets',
		'attributes':
		{
			'align':
			{
				'type': 'string',
				'default': ''
			},
			'full_width':
			{
                'type': 'string',
                'default': ''
            },
			'hero_title':
			{
                'type': 'string',
                'default': ''
            },
			'hero_content':
			{
                'type': 'string',
                'default': ''
            },
			'hero_link':
			{
                'type': 'string',
                'default': ''
            },
			'hero_external_link':
			{
                'type': 'string',
                'default': ''
            },
			'hero_content_align':
			{
                'type': 'string',
                'default': ''
            },
			'hero_image':
			{
                'type': 'string',
                'default': ''
            },
			'hero_image_id':
			{
                'type': 'string',
                'default': ''
            },
			'hero_fade':
			{
                'type': 'string',
                'default': ''
            }
		},
		'supports':
		{
			'html': false,
			'multiple': false,
			'align': true,
			'spacing':
			{
				'margin': true,
				'padding': true
			},
			'color':
			{
				'background': true,
				'gradients': false,
				'text': true
			},
			'defaultStylePicker': true,
			'typography':
			{
				'fontSize': true,
				'lineHeight': true
			},
			"__experimentalBorder":
			{
				"radius": true
			}
		},
		edit: function(props)
		{
			var arr_out = [];

			/* Select */
			/* ################### */
			var arr_options = [];

			jQuery.each(script_hero_block_wp.yes_no, function(index, value)
			{
				if(index == "")
				{
					index = 0;
				}

				arr_options.push({label: value, value: index});
			});

			arr_out.push(el(
				'div',
				{className: "wp_mf_block " + props.className},
				el(
					SelectControl,
					{
						label: __("Full Width", 'lang_hero'),
						value: props.attributes.full_width,
						options: arr_options,
						onChange: function(value)
						{
							props.setAttributes({full_width: value});
						}
					}
				)
			));
			/* ################### */

			/* Text */
			/* ################### */
			arr_out.push(el(
				'div',
				{className: "wp_mf_block " + props.className},
				el(
					TextControl,
					{
						label: __("Title", 'lang_hero'),
						type: 'text',
						value: props.attributes.hero_title,
						/*help: __("Description...", 'lang_hero'),*/
						onChange: function(value)
						{
							props.setAttributes({hero_title: value});
						}
					}
				)
			));
			/* ################### */

			/* Text */
			/* ################### */
			arr_out.push(el(
				'div',
				{className: "wp_mf_block " + props.className},
				el(
					TextControl,
					{
						label: __("Content", 'lang_hero'),
						type: 'text',
						value: props.attributes.hero_content,
						onChange: function(value)
						{
							props.setAttributes({hero_content: value});
						}
					}
				)
			));
			/* ################### */

			/* Select */
			/* ################### */
			var arr_options = [];

			jQuery.each(script_hero_block_wp.hero_link, function(index, value)
			{
				if(index == "")
				{
					index = 0;
				}

				arr_options.push({label: value, value: index});
			});

			arr_out.push(el(
				'div',
				{className: "wp_mf_block " + props.className},
				el(
					SelectControl,
					{
						label: __("Link", 'lang_hero'),
						value: props.attributes.hero_link,
						options: arr_options,
						onChange: function(value)
						{
							props.setAttributes({hero_link: value});
						}
					}
				)
			));
			/* ################### */

			/* Text */
			/* ################### */
			arr_out.push(el(
				'div',
				{className: "wp_mf_block " + props.className},
				el(
					TextControl,
					{
						label: __("External Link", 'lang_hero'),
						type: 'text',
						value: props.attributes.hero_external_link,
						onChange: function(value)
						{
							props.setAttributes({hero_external_link: value});
						}
					}
				)
			));
			/* ################### */

			/* Select */
			/* ################### */
			var arr_options = [];

			jQuery.each(script_hero_block_wp.hero_content_align, function(index, value)
			{
				if(index == "")
				{
					index = 0;
				}

				arr_options.push({label: value, value: index});
			});

			arr_out.push(el(
				'div',
				{className: "wp_mf_block " + props.className},
				el(
					SelectControl,
					{
						label: __("Align Content", 'lang_hero'),
						value: props.attributes.hero_content_align,
						options: arr_options,
						onChange: function(value)
						{
							props.setAttributes({hero_content_align: value});
						}
					}
				)
			));
			/* ################### */

			/* Media */
			/* ################### */
			arr_out.push(el(
				'div',
				{className: "wp_mf_block " + props.className},
				el(
                    MediaUploadCheck,
                    {},
                    el(
                        MediaUpload,
                        {
                            onSelect: function(value)
							{
								props.setAttributes({hero_image: value.url, hero_image_id: value.id});
							},
                            allowedTypes: ['image'],
                            value: props.attributes.hero_image_id,
                            render: function(obj)
							{
                                return el(
                                    Button,
                                    {
                                        onClick: obj.open
                                    },
                                    __("Image", 'lang_hero')
                                );
                            }
                        }
                    )
                ),
                props.attributes.hero_image && el(
                    'img',
                    {
                        src: props.attributes.hero_image,
                        alt: ''
                    }
                )
			));
			/* ################### */

			/* Select */
			/* ################### */
			var arr_options = [];

			jQuery.each(script_hero_block_wp.hero_fade, function(index, value)
			{
				if(index == "")
				{
					index = 0;
				}

				arr_options.push({label: value, value: index});
			});

			arr_out.push(el(
				'div',
				{className: "wp_mf_block " + props.className},
				el(
					SelectControl,
					{
						label: __("Overlay Color", 'lang_hero'),
						value: props.attributes.hero_fade,
						options: arr_options,
						onChange: function(value)
						{
							props.setAttributes({hero_fade: value});
						}
					}
				)
			));
			/* ################### */

			return arr_out;
		},
		save: function()
		{
			return null;
		}
	});
})();