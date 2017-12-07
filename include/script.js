jQuery(function($)
{
	$(".widget.hero").each(function()
	{
		var dom_obj = $(this),
			dom_bg = dom_obj.find(".image.image_fade").css('background-color'),
			dom_parent = dom_obj.parent("div").parent("div");

		if(typeof dom_bg != 'undefined')
		{
			dom_parent.css({'background-color': dom_bg});
		}
	});
});