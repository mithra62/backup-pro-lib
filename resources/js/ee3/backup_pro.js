$(document).ready(function() {
	
	//lil' method to send the backup note to the server
	function bp_save_note(text_div, element, backup_type)
	{
		var note_text = $(element).val();
		var note_backup = $(element).attr("rel");
		var dataString = "backup="+note_backup+"&note_text="+note_text+"&backup_type="+backup_type + "&"+$.param({ "XID": EE.XID});
		var url = $("#__note_url").val();

		$.ajax({
			type: "POST",
			url: url,
			data: dataString,
			success: function(){

				$(text_div).html(note_text).show();
				$(element).hide();
			},
				error: function(jqXHR, textStatus){
			}
		});
		
	}
	
	window.bp_save_note=bp_save_note;

	/**
	$.ajax({
		type: "POST",
		url: EE.BASE+"&C=addons_modules&M=show_module_cp&module=backup_pro&method=l&",
		data: $.param({ "XID": EE.XID}),
		success: function(){

		},
			error: function(jqXHR, textStatus){
		}
	});
	**/
	
});