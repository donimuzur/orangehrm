$(document).ready(function(){
    var rDate = trim($("#form_attendance_date").val());
    if (rDate == '') {
        $("#from_attendance_date").val(displayDateFormat);
    }
	var rDate = trim($("#to_attendance_date").val());
    if (rDate == '') {
        $("#to_attendance_date").val(displayDateFormat);
    }
    
    $('#btView').click(function() {
        if(isValidForm()){
            $("#reportForm").submit();                 
        }
    });

    $("#btnExport").click(function() {
        $("#btnExport").val(lang_processing);
         if(isValidForm()){
            exportToExcel();            
        }
    });
});

function isValidForm(){
        var validator = $("#reportForm").validate({

            rules: {
                'fingerspot[fromDate]' : {
					required: true, 
                    valid_date: function() {
                        return {
                            format:datepickerDateFormat,
                            required:true,
                            displayFormat:displayDateFormat
                        }
                    }},
                'fingerspot[toDate]' : {
					required: true, 
                    valid_date: function() {
                        return {
                            format:datepickerDateFormat,
                            required:true,
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
    var fromDate = $('#from_attendance_date').val();
    var toDate = $('#to_attendance_date').val();
    $.ajax({
        type:     "post",
        data:    {
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
            if(data.includes("sukses")){
                window.location = linkToDownloadFile;
                $("#btnExport").val("Export to Excel");
            }
            else{
                location.reload();
            }
        }
    });  
    return false;
        
}