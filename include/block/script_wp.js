(function()
{
	var el = wp.element.createElement,
		registerBlockType = wp.blocks.registerBlockType,
		SelectControl = wp.components.SelectControl,
		TextControl = wp.components.TextControl,
		MediaUpload = wp.blockEditor.MediaUpload,
		Button = wp.components.Button,
		MediaUploadCheck = wp.blockEditor.MediaUploadCheck,
		InspectorControls = wp.blockEditor.InspectorControls;

	registerBlockType('mf/hero',
	{
		title: script_hero_block_wp.block_title,
		description: script_hero_block_wp.block_description,
		icon: 'megaphone',
		category: 'widgets',
		'attributes':
		{
			'align':
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
			return el(
				'div',
				{className: 'wp_mf_block_container'},
				[
					el(
						InspectorControls,
						'div',
						el(
							TextControl,
							{
								label: script_hero_block_wp.hero_title_label,
								type: 'text',
								value: props.attributes.hero_title,
								onChange: function(value)
								{
									props.setAttributes({hero_title: value});
								}
							}
						),
						el(
							TextControl,
							{
								label: script_hero_block_wp.hero_content_label,
								type: 'text',
								value: props.attributes.hero_content,
								onChange: function(value)
								{
									props.setAttributes({hero_content: value});
								}
							}
						),
						el(
							SelectControl,
							{
								label: script_hero_block_wp.hero_link_label,
								value: props.attributes.hero_link,
								options: convert_php_array_to_block_js(script_hero_block_wp.hero_link),
								onChange: function(value)
								{
									props.setAttributes({hero_link: value});
								}
							}
						),
						el(
							TextControl,
							{
								label: script_hero_block_wp.hero_external_link_label,
								type: 'text',
								value: props.attributes.hero_external_link,
								onChange: function(value)
								{
									props.setAttributes({hero_external_link: value});
								}
							}
						),
						el(
							SelectControl,
							{
								label: script_hero_block_wp.hero_content_align_label,
								value: props.attributes.hero_content_align,
								options: convert_php_array_to_block_js(script_hero_block_wp.hero_content_align),
								onChange: function(value)
								{
									props.setAttributes({hero_content_align: value});
								}
							}
						),
						el(
							SelectControl,
							{
								label: script_hero_block_wp.hero_fade_label,
								value: props.attributes.hero_fade,
								options: convert_php_array_to_block_js(script_hero_block_wp.hero_fade),
								onChange: function(value)
								{
									props.setAttributes({hero_fade: value});
								}
							}
						)
					),
					el(
						'strong',
						{className: props.className},
						script_hero_block_wp.block_title
					),
					el(
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
											script_hero_block_wp.hero_image_label
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
					)
				]
			);

			return arr_out;
		},
		save: function()
		{
			return null;
		}
	});
})();