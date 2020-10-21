$(document).ready(function(){

    var rDate = trim($("#form_attendance_date").val());
    if (rDate == '') {
        $("#from_attendance_date").val(displayDateFormat);
    }
	var rDate = trim($("#to_attendance_date").val());
    if (rDate == '') {
        $("#to_attendance_date").val(displayDateFormat);
    }

    if(trigger){
        $("#reportForm").submit();     
    }

    $('#btView').click(function() {
        if(isValidForm()){
            $("#reportForm").submit();                 
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
