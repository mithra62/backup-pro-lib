jQuery(document).ready(function() {
	
	//settings form
	var backup_type = '';
	if(jQuery("#auto_threshold").val() == "custom")
	{
		jQuery("#auto_threshold_custom_wrap").show();
	}
		
	if(jQuery("#db_backup_method").val() == "mysqldump")
	{
		jQuery("#mysqldump_command_wrap").show();
	}

	if(jQuery("#db_restore_method").val() == "mysql")
	{
		jQuery("#mysqlcli_command_wrap").show();
	}				
	
	var def_assign = "0";
	jQuery("#auto_threshold").change(function(){
		var new_assign = jQuery("#auto_threshold").val();
		if(new_assign == def_assign || new_assign != "custom")
		{
			jQuery("#auto_threshold_custom_wrap").hide();
			jQuery("#auto_threshold_custom_wrap").val(new_assign);
		}
		else
		{
			jQuery("#auto_threshold_custom_wrap").show();
		}
	});	

	var def_assign = "php";
	jQuery("#db_backup_method").change(function(){
		var new_assign = jQuery("#db_backup_method").val();
		if(new_assign == def_assign)
		{
			jQuery("#mysqldump_command_wrap").hide();
		}
		else
		{
			jQuery("#mysqldump_command_wrap").show();
		}
	});	

	jQuery("#db_restore_method").change(function(){
		var new_assign = jQuery("#db_restore_method").val();
		if(new_assign == def_assign)
		{
			jQuery("#mysqlcli_command_wrap").hide();
		}
		else
		{
			jQuery("#mysqlcli_command_wrap").show();
		}
	});
	//end settings form
	

	//now the testing cron 
	jQuery(".test_cron").click(function (e) {
		
		e.preventDefault();
		var backup_type = jQuery(this).attr("rel");
		var url = jQuery(this).attr("href");
		var link = this;
		
		var image_id = "#animated_" + backup_type;
		jQuery(image_id).show();
		jQuery(link).hide();

		jQuery.ajax({
			url: url,
			context: document.body,
			success: function(xhr){
				alert(" Cron: Complete");
				jQuery(image_id).hide();
				jQuery(link).show();
				//clean_bp_errors(backup_type);
			},
			error: function(data, status, errorThrown) {
				alert(" Cron: Failed with status "+ data.status +"\n" +errorThrown );
				jQuery(image_id).hide();
				jQuery(link).show();									
			}
		});			
		
		return false;
		
	});	
	
	
});