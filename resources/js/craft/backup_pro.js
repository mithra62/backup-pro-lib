/**
 * mithra62 - Backup Pro
 *
 * @copyright	Copyright (c) 2015, mithra62, Eric Lamb.
 * @link		http://mithra62.com/projects/view/backup-pro
 * @version		3.0
 * @filesource 	./craft/backup_pro.js
 */

$(document).ready(function() {
	
	//lil' method to send the backup note to the server
	function bp_save_note(text_div, element, backup_type)
	{
		var note_text = $(element).val();
		var note_backup = $(element).attr("rel");
		var dataString = "backup="+note_backup+"&note_text="+note_text+"&backup_type="+backup_type;
		
		Craft.postActionRequest('backupPro/manage/note', dataString, function(response) {
			
			$(text_div).html(note_text).show();
			$(element).hide();
			
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