function clickMe(obj,getaction){
var value = obj.id;
	jQuery.ajax({
		url : '/admin/attributeset/processajaxactive',
		type: 'POST',
		dataType: 'JSON',
		data: {'UID': obj.id, 'action': getaction},
		beforeSend : function() {
			/* Logic before ajax request sent */
		},
		success: function(data, status){
			
			if(data.status == 'error'){
				// Perform any operation on error
				alert(data.message);
			}else{
			//alert(getaction);
				if(getaction == 'active') {
				var str='<span onclick="clickMe(this,\'deactive\')" class="label label-sm label-success change_status" id="'+obj.id+'">Active</span>';

				}
				else
				{
				var str='<span onclick="clickMe(this,\'active\')" class="label label-sm label-danger change_status" id="'+obj.id+'">Expired</span>';
				
				}
			
				// Perform any operation on success
				//alert(str);
				jQuery("#changeStatus"+obj.id).html(str);
			}
		},
		error : function(xhr, textStatus, errorThrown) {
			if (xhr.status === 0) {
				alert('Not connect.\n Verify Network.');
			} else if (xhr.status == 404) {
				alert('Requested page not found. [404]');
            } else if (xhr.status == 500) {
            	alert('Server Error [500].');
            } else if (errorThrown === 'parsererror') {
            	alert('Requested JSON parse failed.');
            } else if (errorThrown === 'timeout') {
            	alert('Time out error.');
            } else if (errorThrown === 'abort') {
            	alert('Ajax request aborted.');
            } else {
            	alert('There was some error. Try again.');
            }
		},
		complete: function(){
			// Perform any operation need on success/error
		}
	});
}


function getHtmlResponse(obj){
	jQuery.ajax({
		url : '/application/javascript/get-html-response',
		type: 'POST',
		data: {'tempData': jQuery('#tempData').val()},
		beforeSend : function() {
			/* Logic before ajax request sent */
		},
		success: function(data, status){
			
			if(status){
				jQuery("#response").html(data);				
			}else{
				jQuery("#response").html('There was some error. Try again.');
			}
		},
		error : function(xhr, textStatus, errorThrown) {
			if (xhr.status === 0) {
				alert('Not connect.\n Verify Network.');
			} else if (xhr.status == 404) {
				alert('Requested page not found. [404]');
            } else if (xhr.status == 500) {
            	alert('Server Error [500].');
            } else if (errorThrown === 'parsererror') {
            	alert('Requested JSON parse failed.');
            } else if (errorThrown === 'timeout') {
            	alert('Time out error.');
            } else if (errorThrown === 'abort') {
            	alert('Ajax request aborted.');
            } else {
            	alert('There was some error. Try again.');
            }
		},
		complete: function(){
			// Perform any operation need on success/error
		}
	});
}


	jQuery( "#form-field-selectsetList" ).change(function() {
		 var optionSelected = $(this).find("option:selected");
		 var valueSelected  = optionSelected.val();
		 var url=jQuery('#myFrm').attr('action');
		 url=url+"?id="+valueSelected;
		 
		 window.location.href=url;
		
	});
	
	jQuery("#form-field-select-1-sortby").change(function() {
	
			var optionSelected = $(this).find("option:selected");
			var valueSelected  = optionSelected.val();
			
			if(valueSelected > 0) { 
					var url= "/admin/weapon/index" ; 
					url=url+"/"+valueSelected;
					window.location.href=url;
			}
	
	
	});
/*
	jQuery("table#sample-table-1").on('click' ,'tr td', function() { 
			//alert('hiu');
			var myval = $(this).attr('class');
			alert(myval);
			});
*/