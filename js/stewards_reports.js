// On document ready...
$(function(){

	// Make sure that specified input is numeric
	$('.numeric').numeric();
	
	
	// Datepickers
	$(".datepicker").datepicker({
			showOn: "both",
			buttonImage: "/img/calendar.gif",
			dateFormat: 'yy-mm-dd'
	});
	
	
	
	
	/**************************************
	 *
	 * The following on doc ready functions only apply to the Stewards Report form (not the edit form)
	 *
	 **************************************/
	
	if( $('body.edit').length <= 0){
	
		// Validate Form
		$('#stewards_report').validate({
			rules: {
				name: "required",
				type: "required",
				project_location: "required",
				project_city: "required",
				project_county: "required",
				project_name: "required",
				project_start_date: "required",
				pay_period_start: "required",
				pay_period_ending: "required",
				company: "required",
				general_contractor: "required",
				job_duration: "required",
				percent_completed: "required",
				num_ironworkers: "required",
				job_funding: "required",
				foreman_name: "required",
				steward_name: "required",
				steward_address: "required",
				steward_phone: "required"
			}
		});
		
		
		// Setup form requirements
		for(var i=1; i<=num_workers; i++){
			setupFormRequirements(i);
		}
		
		
		// Expandable textarea (IE<=8)
		$("textarea").autoGrow();
		
		
		// Set project info from selected project name
		// on load
		var project_id = $('#project_name').val();
		if(project_id != '' && project_id != 'Other'){
			$('#project_location').hide();
			$('#project_address').attr('readonly', true);
			$('#project_city').attr('readonly', true);
			$('#project_county').attr('disabled', true); 
		} else { 
			$('#project_address').hide();
		}
		
		// on change
		$('#project_name').change(function(){
			var project_id = $(this).val();
			
			if(project_id != '' && project_id != 'Other'){
				$.ajax({
					type: 'POST',
					dataType: 'json',
					url: '../inc/requests/getProjectInfo.php',
					data: 'pid='+project_id,
					success: function(data){
						
						// Show project info
						if(data.address){	
							$('#project_location').val('').hide(); 
							$('#project_address').val( data.address ).attr('readonly', true).show();
						}
						if(data.city){
							$('#project_city').val( data.city ).attr('readonly', true);
						}
						if(data.county){	
							$('#project_county').val( data.county ).attr('disabled', true); 
							$('#project_county_hidden').val( data.county ); 
						}
						if(data.funding){
							switch(data.funding){
							case '2' :
								$('#job_funding_state').attr('checked', true);
								break;
							case '3' :
								$('#job_funding_federal').attr('checked', true);
								break;
							case '4' :
								$('#job_funding_private').attr('checked', true);
								break;
							default :
								$('#job_funding_state').attr('checked', false);
								$('#job_funding_federal').attr('checked', false);
								$('#job_funding_private').attr('checked', false);
							}
						}
					}
				});
			} else {
				$('#project_location').show(); 
				$('#project_address').val('').hide();
				$('#project_city').val('').attr('readonly', false);
				$('#project_county').val('').attr('disabled', false);
				$('#project_county_hidden').val('');
			}
		})
		
		
		// Set project city and county from selected address/location
		$('#project_location').change(function(){
			var location_id = $(this).val();
			
			if(location_id != '' && location_id != 'Other'){
				$.ajax({
					type: 'POST',
					dataType: 'json',
					url: '../inc/requests/getProjectLocation.php',
					data: 'lid='+location_id,
					success: function(data){
						if(data.city){
							$('#project_city').val( data.city );
						}
						if(data.county){
							$('#project_county').val( data.county );
							$('#project_county_hidden').val( data.county );
						}
					}
				});
			}
		});
		
		
		// Fill "Contractors Welfare/Pension Paid Up?" month after 
		// "Company - In which you are employed" is selected
		$('#company').change(function(){
			var employer_id = $(this).val();
			
			if( employer_id != '' && employer_id != 'Other'){
				$.ajax({
					type: 'POST',
					dataType: 'json',
					url: '../inc/requests/getEmployerInfo.php',
					data: 'eid='+employer_id,
					success: function(data){
						if(data.paid_through_month)	$('#contractor_welfare_paid_to_month').val( data.paid_through_month );
						
						var grace_period = 60; // Days after paid-through date that they are still considered paid up
						
						var paid_through_date = new Date(data.paid_through_year, data.paid_through_month-1, data.paid_through_day);
						paid_through_date.setDate( paid_through_date.getDate() + grace_period );
						
						var today = new Date();
						
						//console.log(paid_through_date);
						//console.log(today);
						
						if(today <= paid_through_date){
							$('#contractor_welfare_paid_yes').attr('checked', true);
							$('#contractor_welfare_paid_to_month').hide();
						} else {
							$('#contractor_welfare_paid_no').attr('checked', true);
							$('#contractor_welfare_paid_to_month').show();
						}
					}
				});
			}
		});
		
		
		// Changing the "Total No. of Iron Workers on this Report' input adds worker rows to that number
		$('#num_ironworkers').keyup(function(){
			delay(function(){
				var num_workers 					= parseInt( $('#nw').val() );
				var num_workers_inputted 	= parseInt( $('#num_ironworkers').val() );
				
				//console.log('# workers: '+num_workers);
				//console.log('# workers inputted: '+num_workers_inputted);
	
				// Add rows
				if(num_workers_inputted > num_workers){
					var difference = num_workers_inputted - num_workers;
					
					//console.log('# to add: '+difference);
					
					addWorkers(null, difference);
				}
			}, 1000);
		});
        
        
        // Pre-populate form
        $('#f-prepopulate-report').on('change', function(){
            var data = { 'id': $(this).val() };
            
            $.post('/inc/requests/getReport.php', data, function(response) {
                if(response == 0){
                    alert('An error occurred and the selected report could not be used.');
                } else {
                    
                    // Loop through returned data to populate simple form fields
                    $.each(response.data, function(key, value) {
                        var field = $('#stewards_report [name="' + key + '"]');
                        
                        if(field.length){
                            switch(field.prop("type")) {
                                case "radio":
                                case "checkbox":
                                    field.each(function() {
                                        if($(this).attr('value') == value) $(this).attr("checked",value);
                                    });
                                    break;
                                default:
                                    field.val(value);
                            }
                        }
                    });
                    
                    // Selects with 'Other' inputs
                    var select_inputs = ['project_name', 'project_location', 'project_county', 'company', 'general_contractor', 'journeymen_wages_paid'];
                    $.each(select_inputs, function(index, item) {
                        var option = $('#' + item + ' option').filter(function() { return $(this).html() == response.data[item]; });
                        if(option.length){
                            option.prop('selected', true);
                            $('#' + item + '_other').val('').hide();
                        } else {
                            $('#' + item).val('Other');
                            $('#' + item + '_other').val(response.data[item]).show();
                        }
                    });
                                                            
                    // Percent Completed
                    $('#percent_completed').val(parseInt(response.data.percent_completed).toString());
                                        
                    // Contractors Welfare/Pension Paid Up?
                    if(response.data.contractor_welfare_paid == 0){
                    	$('#contractor_welfare_paid_to_month').show();
                    } else {
                    	$('#contractor_welfare_paid_to_month').hide();
                    }
                    
                    // Foremen
                    if(response.data.foremen.length){
                        $('#foremen .foreman').remove();
                        $('#nf').val(0);
                        
                        addForemen(null, response.data.foremen.length, function(){
                            $.each(response.data.foremen, function(index, foreman){
                                var row = $('#foremen .foreman').eq(index);
                                var num = index + 1;
                                
                                row.find('input[name="foreman_first_name_' + num + '"]').val(foreman.first_name);
                                row.find('input[name="foreman_last_name_' + num + '"]').val(foreman.last_name);
                            });
                        });
                    }
                    
                    // Workers
                    if(response.data.workers.length){
                        $('#workers .worker').remove();
                        $('#nw').val(0);
                        
                        addWorkers(null, response.data.workers.length, function(){
                            $.each(response.data.workers, function(index, worker){
                                var row = $('#workers .worker').eq(index);
                                var num = index + 1;

                                row.find('input[name="worker_book_number_' + num + '"]').val(worker.book_number);
                                row.find('input[name="worker_first_name_' + num + '"]').val(worker.first_name);
                                row.find('input[name="worker_last_name_' + num + '"]').val(worker.last_name);
                                row.find('input[name="worker_local_number_' + num + '"]').val(worker.local_number);
                                row.find('select[name="worker_type_' + num + '"]').val(worker.type);
                                
                                if(worker.month_dues_paid){
                                    var date = worker.month_dues_paid.split('-');
                                    var date_dues_paid = new Date(date[0], parseInt(date[1]) - 1, date[2]);;
                                    
                                    row.find('select[name="worker_month_dues_paid_month_' + num + '"]').val(date_dues_paid.getMonth() + 1);
                                    row.find('select[name="worker_month_dues_paid_year_' + num + '"]').val(date_dues_paid.getFullYear());
                                }
                            });
                        });
                    }
                }
            }, 'json');
        });

		
	} // END Stewards Report form only
	
});




// Delay
var delay = (function(){
  var timer = 0;
  return function(callback, ms){
    clearTimeout (timer);
    timer = setTimeout(callback, ms);
  };
})();


// Additional form validation
function validateFormCustom(){
	var num_workers = parseInt( $('#nw').val() );
	
	// Alert user if the number of workers entered does not
	// match the value of #num_ironworkers
	var total = 0;
	for(var i=1; i<=num_workers; i++){
		if( $('#worker_first_name_'+i).val() != '' ){
			total++;
		}
	}	
	if(total != $('#num_ironworkers').val()){
		$('#num_ironworkers').focus();
		alert('The field, "Total No. of Iron Workers on this Report", does not match the number of workers entered.');
		
		return false;
	}
}


// Add a worker row to form
// Optional parameter to add a number of workers recursively
function addWorker(container, num_workers_to_add, callback){
	var obj = (container && container!='undefined' && container!='null') ? $('#'+container) : $('#workers');
	var num_workers = parseInt( $('#nw').val() );
	num_workers++;
		
	$('#workers_total_hours div.add_worker').html('<img src="../img/ajax-loader.gif" alt="" /> <span style="color:#666;">Loading</span>');
	
	$.ajax({
		type: 'POST',
		url: '../inc/stewards_report_form_worker_v2.php',
		data: 'i='+num_workers,
		success: function(data) {
			obj.append(data);
			$('#nw').val(num_workers);
			$('#workers_total_hours div.add_worker').html('<a href="javascript:addWorker(\''+container+'\');"><img src="../img/icon_plus_blue.jpg" alt="" /> Add Worker</a>');
			
			// Make sure that specified input is numeric
			$('.numeric').numeric();
			
			// Setup form requirements
			setupFormRequirements(num_workers);
            
            // Call function again if necessary
            if(num_workers_to_add!='undefined' && num_workers_to_add > 1){
                addWorker(container, num_workers_to_add - 1);
            }
            
            if(callback){
                callback(obj.last('.worker-row'));
            }
		}
	});
}


// Add multiple worker rows to form
function addWorkers(container, num_workers_to_add, callback){
	var obj = (container && container!='undefined') ? $('#'+container) : $('#workers');
	var num_workers = parseInt( $('#nw').val() );
	num_workers_to_add = parseInt(num_workers_to_add);
	
	$('#workers_total_hours div.add_worker').html('<img src="../img/ajax-loader.gif" alt="" /> <span style="color:#666;">Loading</span>');
	
	$.ajax({
		type: 'POST',
		url: '../inc/requests/reportAddWorkers.php',
		data: 'start='+(num_workers + 1)+'&add='+num_workers_to_add,
		success: function(data) {
			obj.append(data);
			
			$('#nw').val(num_workers + num_workers_to_add);
			$('#workers_total_hours div.add_worker').html('<a href="javascript:addWorker(\''+container+'\');"><img src="../img/icon_plus_blue.jpg" alt="" /> Add Worker</a>');
			
			// Make sure that specified input is numeric
			$('.numeric').numeric();
			
			// Setup form requirements
			setupFormRequirements(num_workers);
            
            if(callback){
                callback();
            }
		}
	});
}


// Update total worker hours worked and paid
function updateTotalWorkerHoursWorked(id){
	var num_workers = parseInt( $('#nw').val() );

	// Update worker row
	var st = Number($('#worker_time_straight_'+id).val());
	var th = Number($('#worker_time_half_'+id).val());
	var dt = Number($('#worker_time_double_'+id).val());
	var worked = st + th + dt;
	var paid 	 = st + (th * 1.5) + (dt * 2);
	
	$('#worker_hours_worked_'+id).val(worked);
	$('#worker_hours_paid_'+id).val(paid);
	
	
	// Calculate total values
	var total_worked = 0;
	var value_worked = 0;
	var total_paid = 0;
	var value_paid = 0;
	
	for(var i=1; i<=num_workers; i++){
		value_worked = $('#worker_hours_worked_'+i).val();
		total_worked += Number(value_worked);
		
		value_paid = $('#worker_hours_paid_'+i).val();
		total_paid += Number(value_paid);
	}
	$('#total_hours_worked').val(total_worked);
	$('#total_hours_paid').val(total_paid);
}


// Add a foreman row to form
function addForeman(container, callback){
	var obj = (container && container!='undefined') ? $('#'+container) : $('#foremen');
	var num_foremen = $('#nf').val();
    num_foremen++;
	
	$('#foremen div.add_foreman').html('<img src="../img/ajax-loader.gif" alt="" /> <span style="color:#666;">Loading</span>');
	
	$.ajax({
		type: 'POST',
		url: '../inc/stewards_report_form_foreman.php',
		data: 'i='+num_foremen,
		success: function(data) {
			obj.append(data);
			$('#nf').val(num_foremen);
			$('#foremen div.add_foreman').html('<a href="javascript:addForeman(\''+container+'\');"><img src="../img/icon_plus_blue.jpg" alt="" /> Add Foreman</a>');

            if(callback){
                callback(obj.last('.foreman-row'));
            }
		}
	});
}

// Add multiple foremen rows to form
function addForemen(container, num_foremen_to_add, callback){
    var obj = (container && container != 'undefined') ? $('#'+container) : $('#foremen');
    var num_foremen = parseInt( $('#nf').val() );
    num_foremen_to_add = parseInt(num_foremen_to_add);
    
    $('#foremen div.add-foreman').html('<img src="/img/ajax-loader.gif" alt=""> <span class="loading">Loading</span>');
    
    $.ajax({
        type: 'POST',
        url: '/inc/requests/reportAddForemen.php',
        data: 'start='+(num_foremen + 1)+'&add='+num_foremen_to_add,
        success: function(data) {
            obj.append(data);
            
            $('#nf').val(num_foremen + num_foremen_to_add);
            $('#foremen div.add-foreman').html('<a href="javascript:addForeman(\''+container+'\');" class="link-btn add"><span></span>Add Foreman</a>');
            
            if(callback){
                callback();
            }
        }
    });
}


// Add an accident row to form
function addAccident(container){
	var obj = (container && container!='undefined') ? $('#'+container) : $('#accidents');
	
	$('#add_accident').html('<img src="../img/ajax-loader.gif" alt="" /> <span style="color:#666;">Loading</span>');
		
	$.ajax({
		type: 'POST',
		url: '../inc/stewards_report_form_accident.php',
		data: 'i='+(num_accidents+1)+'&bkgd='+accident_bkgd,
		success: function(data) {
			obj.append(data);
			num_accidents++;
			$('#na').val(num_accidents);
			accident_bkgd = (accident_bkgd=='#fff') ? '#efefef' : '#fff'
			$('#add_accident').html('<a href="javascript:addAccident(\''+container+'\');"><img src="../img/icon_plus_blue.jpg" alt="" /> Add Accident</a>');
		}
	});
}


// Populate worker fields when a book number, or SSN, is entered
function findUser(book_number, which){
	//if( $('#worker_first_name_'+which).val() == '' && $('#worker_last_name_'+which).val() == '' ){
		$.ajax({
			type: 'POST',
			dataType: 'json',
			url: '../inc/requests/findUser.php',
			data: 'b='+book_number,
			success: function(data) {
				if(data != 0){
				
					// Only on main steward's report form (not on edit form)
					if( $('body.edit').length <= 0){
					
						// Add form requirements
						$('#worker_book_number_'+which).rules("add", { required: true });
						$('#worker_first_name_'+which).rules("add", { required: true });
						$('#worker_last_name_'+which).rules("add", { required: true });
						$('#worker_local_number_'+which).rules("add", { required: true });
						$('#worker_hours_worked_'+which).rules("add", { required: true });
						$('#worker_hours_paid_'+which).rules("add", { required: true });
						$('#worker_month_dues_paid_month_'+which).rules("add", { required: true });
						$('#worker_month_dues_paid_year_'+which).rules("add", { required: true });
					}
					
					// Populate form fields
					$('#worker_first_name_'+which).val(data.first_name);
					$('#worker_last_name_'+which).val(data.last_name);
					$('#worker_local_number_'+which).val(data.local_number);
					$('#worker_month_dues_paid_month_'+which).val(data.month_dues_paid_month);
					$('#worker_month_dues_paid_year_'+which).val(data.month_dues_paid_year);
					if(data.type != null && data.type != '') $('#worker_type_'+which).val(data.type);
					if(data.book_number != null && data.book_number != '') $('#worker_book_number_'+which).val(data.book_number);
					
				} else {
				
					// Only on main steward's report form (not on edit form)
					if( $('body.edit').length <= 0){
					
						// Remove form requirements
						$('#worker_book_number_'+which).rules("add", { required: true });
						$('#worker_first_name_'+which).rules("add", { required: true });
						$('#worker_last_name_'+which).rules("add", { required: true });
						$('#worker_local_number_'+which).rules("remove");
						$('#worker_hours_worked_'+which).rules("remove");
						$('#worker_hours_paid_'+which).rules("remove");
						$('#worker_month_dues_paid_month_'+which).rules("remove");
						$('#worker_month_dues_paid_year_'+which).rules("remove");
					}
				}
			}
		});
	//}
}


// Setup Worker Form Requirements
function setupFormRequirements(which){
	var fields = new Array('#worker_book_number_'+which, '#worker_first_name_'+which, '#worker_last_name_'+which, '#worker_local_number_'+which, '#worker_hours_worked_'+which, '#worker_hours_paid_'+which, '#worker_month_dues_paid_month_'+which, '#worker_month_dues_paid_year_'+which);
	$.each(fields, function(index, field){
									
		$(field).change(function(){
			checkFormRequirements(which, field);
		});
	});	
}


function checkFormRequirements(which){
	var pass = false;
	var fields = new Array('#worker_book_number_'+which, '#worker_first_name_'+which, '#worker_last_name_'+which, '#worker_local_number_'+which, '#worker_hours_worked_'+which, '#worker_hours_paid_'+which, '#worker_month_dues_paid_month_'+which, '#worker_month_dues_paid_year_'+which);
	$.each(fields, function(index, field){		
		if( $(field).val() != '' ){
			//console.log('REQUIRED -> ' + which + ' -> ' + field);
			pass = true;
			return false;
		}
	});
	
	if(pass){
		$('#worker_book_number_'+which).rules("add", { required: true });
		$('#worker_first_name_'+which).rules("add", { required: true });
		$('#worker_last_name_'+which).rules("add", { required: true });
		$('#worker_local_number_'+which).rules("add", { required: true });
		$('#worker_month_dues_paid_month_'+which).rules("add", { required: true });
		$('#worker_month_dues_paid_year_'+which).rules("add", { required: true });
		$('#worker_hours_worked_'+which).rules("add", { required: true });
		$('#worker_hours_paid_'+which).rules("add", { required: true });
	} else {
	
		$('#worker_book_number_'+which).rules("remove");
		$('#worker_first_name_'+which).rules("remove");
		$('#worker_last_name_'+which).rules("remove");
		$('#worker_local_number_'+which).rules("remove");
		$('#worker_month_dues_paid_month_'+which).rules("remove");
		$('#worker_month_dues_paid_year_'+which).rules("remove");
		$('#worker_hours_worked_'+which).rules("remove");
		$('#worker_hours_paid_'+which).rules("remove");
	}
}


// Get Work Description Items
function getWorkDescriptionItems(category_id, subcategory_id, container, counter, selected_item){
	$('#'+container).append('<img src="/img/ajax-loader.gif" alt="Loading" id="ajax-loader" />');
	
	if(subcategory_id == 'other'){
		$('#'+container).html('<textarea type="text" name="wd'+category_id+subcategory_id+'_'+counter+'" id="wd'+category_id+subcategory_id+'_'+counter+'" onchange="$(\'#wdv_other_'+counter+'\').val(this.value);" placeholder="Type info here"></textarea>');
		
		$('#wd'+category_id+subcategory_id+'_'+counter).autoGrow();
		
	} else {
		$.ajax({
			type: 'POST',
			url: '../inc/requests/getWorkDescriptionItems.php',
			data: 'cid=' + category_id + '&sid=' + subcategory_id + '&counter=' + counter + '&selected_item=' + (selected_item ? selected_item : ''),
			success: function(data){
				$('#ajax-loader').remove();
				if(data != 0){
					$('#'+container).html(data);
				}
			}
		});
	}
}


// Add Work Description Row
function addWorkDescription(){
	$('#work_descriptions div.add_work_description').html('<img src="../img/ajax-loader.gif" alt="" /> <span style="color:#666;">Loading</span>');
	
	$.ajax({
		type: 'POST',
		url: '../inc/stewards_report_form_work_description.php',
		data: 'i='+(num_work_descriptions+1),
		success: function(data) {
			$('#work_descriptions').append(data);
			num_work_descriptions++;
			$('#nwd').val(num_work_descriptions);
			$('#work_descriptions div.add_work_description').html('<a href="javascript:addWorkDescription();"><img src="../img/icon_plus_blue.jpg" alt="" /> Add Work Description</a>');
		}
	});
}