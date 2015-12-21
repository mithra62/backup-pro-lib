/**
 * mithra62 - Backup Pro
 *
 * @copyright	Copyright (c) 2015, mithra62, Eric Lamb.
 * @link		http://mithra62.com/projects/view/backup-pro
 * @version		3.0
 * @filesource 	./wp/backup_pro.js
 */

jQuery(document).ready(function() {
	
//	alert(ajaxurl);
	//lil' method to send the backup note to the server
	function bp_save_note(text_div, element, backup_type)
	{
		var note_text = jQuery(element).val();
		var note_backup = jQuery(element).attr("rel");
		var dataString = "backup="+note_backup+"&note_text="+note_text+"&backup_type="+backup_type+"&action=procBackupProNoteAction";
		
		jQuery.ajax({
			type: "POST",
			url: ajaxurl,
			data: dataString,
			success: function(){

				jQuery(text_div).html(note_text).show();
				jQuery(element).hide();
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