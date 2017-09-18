jQuery(document).ready(function($){
	//upload
	$("[name='nostalgia_upload_button']").live('click', function(){
		var self = $(this);
		window.send_to_editor = function(html) 
		{
			var url;
			if($('img',html).length)
			{
				url = $('img',html).attr('src');
				if(self.attr("id")=="main_box_upload_button")
					url = $(html).html();
			}
			else
				url = $(html).attr('href');
			self.prev().val(url);
			tb_remove();
		}
	 	 
		tb_show('', 'media-upload.php?amp;type=image&amp;TB_iframe=true');
		return false;
	});
	$("#nostalgia-options-tabs").tabs({
		selected: $("#nostalgia-options-tabs #nostalgia-selected-tab").val()
	});
	$("#nostalgia_add_new_button").click(function(){
		$(this).parent().parent().before($(this).parent().parent().prev().prev().clone().wrap('<div>').parent().html().replace($(".background_url_row").length, $(".background_url_row").length+1)+$(this).parent().parent().prev().clone().wrap('<div>').parent().html().replace($(".background_url_row").length, $(".background_url_row").length+1));
		$(".background_url_row:last [id^='nostalgia_background_url_'][type='text']").attr("id", "nostalgia_background_url_" + $(".background_url_row").length).val('');
		$(".background_url_row:last [id^='nostalgia_background_url_'][type='button']").attr("id", "nostalgia_background_url_button_" + $(".background_url_row").length);
		$(".background_title_row:last [id^='nostalgia_background_title_'][type='text']").attr("id", "nostalgia_background_title_" + $(".background_url_row").length).val('');
	});
	$("#nostalgia_add_new_button_track").click(function(){
		$(this).parent().parent().before($(this).parent().parent().prev().clone().wrap('<div>').parent().html().replace($(".track_url_row").length, $(".track_url_row").length+1));
		$(".track_url_row:last [id^='nostalgia_track_url_'][type='text']").attr("id", "nostalgia_track_url_" + $(".track_url_row").length).val('');
		$(".track_url_row:last [id^='nostalgia_track_url_'][type='button']").attr("id", "nostalgia_track_url_button_" + $(".track_url_row").length);
	});
	$("#nostalgia_add_new_button_image").click(function(){
		$(this).parent().parent().before($(this).parent().parent().prev().prev().clone().wrap('<div>').parent().html().replace($(".image_url_row").length, $(".image_url_row").length+1)+$(this).parent().parent().prev().clone().wrap('<div>').parent().html().replace($(".image_url_row").length, $(".image_url_row").length+1));
		$(".image_url_row:last [id^='nostalgia_image_url_'][type='text']").attr("id", "nostalgia_image_url_" + $(".image_url_row").length).val('');
		$(".image_url_row:last [id^='nostalgia_image_url_'][type='button']").attr("id", "nostalgia_image_url_button_" + $(".image_url_row").length);
		$(".image_title_row:last [id^='nostalgia_image_title_'][type='text']").attr("id", "nostalgia_image_title_" + $(".image_url_row").length).val('');
	});
	//colorpicker
	$("#color").ColorPicker({
		onChange: function(hsb, hex, rgb) {
			$("#color").val(hex);
		},
		onSubmit: function(hsb, hex, rgb, el){
			$(el).val(hex);
			$(el).ColorPickerHide();
		},
		onBeforeShow: function (){
			$(this).ColorPickerSetColor(this.value);
		}
	});
});