$(document).ready(function(){
	
    $("#btnExport").click(function() {
        $("#btnExport").val(lang_processing);
         if(isValidForm()){
            exportToExcel();            
        }
    });
    if(employeeId != '') {
        $('#employeeRecordsForm').append($('.actionbar > .formbuttons').html());
        $('.actionbar > .formbuttons').html('');
        $('.actionbar > .formbuttons').html($('#formbuttons').html());
        $('#formbuttons').html('');
    }
    
    var rDate = trim($("#form_attendance_date").val());
    if (rDate == '') {
        $("#from_attendance_date").val(displayDateFormat);
    }
	var rDate = trim($("#to_attendance_date").val());
    if (rDate == '') {
        $("#to_attendance_date").val(displayDateFormat);
    }

    if(trigger){
        autoFillEmpName(employeeId);
        $("#reportForm").submit();     
    }

    $('#btView').click(function() {
        if(isValidForm()){
            $("#reportForm").submit();                 
        }
    });
    
    $("#fingerspot_employeeName_empName").change(function(){
        autoFill('fingerspot_employeeName_empName', 'fingerspot_employeeName_empId', employees_fingerspot_employeeName );
    });

    function autoFill(selector, filler, data) {
        $("#" + filler).val("");
        $.each(data, function(index, item){
            if(item.name.toLowerCase() == $("#" + selector).val().toLowerCase()) {
                $("#" + filler).val(item.id);
                return true;
            }
        });
    }
        
    function autoFillEmpName(employeeId) {
        $("#fingerspot_employeeName_empId").val("");
        $.each(employees_fingerspot_employeeName , function(index, item){
            if(item.id == employeeId) {
                $("#fingerspot_employeeName_empId").val(item.id);
                $("#fingerspot_employeeName_empName").val(item.name);
                return true;
            }
        });
    }
	
	
	function getRelatedAttendanceRecords(employeeId,fromDate, toDate,actionRecorder){
    $.get(
        linkForGetRecords,
        {
            employeeId: employeeId,
            fromDate: toDate,
			toDate: toDate,
            actionRecorder:actionRecorder
        },
        function(data, textStatus) {
            if( data != ''){
                $("#recordsTable").show();
                $('#recordsTable1').html(data);    
            }
        });
    return false;
}
});
function isValidForm(){
	
        var validator = $("#reportForm").validate({

            rules: {
                'fingerspot[employeeName][empName]' : {
                    required:true
                },
                'fingerspot[fromDate]' : {
					required: true, 
                    valid_date: function() {
                        return {
                            format:datepickerDateFormat,
                            required:false,
                            displayFormat:displayDateFormat
                        }
                    }},
                'fingerspot[toDate]' : {
					required: true, 
                    valid_date: function() {
                        return {
                            format:datepickerDateFormat,
                            required:false,
                            displayFormat:displayDateFormat
                        }
                    },
                    date_range: function() {
                        return {
                            format:datepickerDateFormat,
                            displayFormat:displayDateFormat,
                            fromDate:$('#from_attendance_date').val()
                        }
                    }
                }
            },
            messages: {
				'fingerspot[employeeName][empName]': {
                    required: lang_NameRequired
                },
                'fingerspot[fromDate]' : {
					required: lang_NameRequired,
                    valid_date: errorForInvalidFormat
                },
                'fingerspot[toDate]' : {
					required: lang_NameRequired,
                    valid_date: errorForInvalidFormat,
                    date_range:lang_dateError
                }

            },
            errorPlacement: function(error, element) {
                error.insertAfter(element);
            }            
        });
    return true;
}

function exportToExcel(){
    var employeeId = $('#fingerspot_employeeName_empId').val();
    var fromDate = $('#from_attendance_date').val();
    var toDate = $('#to_attendance_date').val();
    $.ajax({
        type:     "post",
        data:    {
            employeeId: employeeId,
            fromDate: fromDate,
            toDate: toDate,
        },
        timeout: 0,
        cache:    false,
        url:      linkToExport,
        dataType: "text",
        error: function(xhr, status, error) {
            alert("Error hubungi admin atau team IT \n"+xhr.responseText)
            $("#btnExport").val("Export to Excel");
        },
        success: function (data) {
          location.reload();
        }
    });  
    return false;
        
}