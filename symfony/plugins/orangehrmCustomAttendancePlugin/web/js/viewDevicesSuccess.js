$(document).ready(function() {

    $("#reportForm").validate({
        rules: {
            'fingerspotDevices[serverIp]': {required: true },
            'fingerspotDevices[serverPort]': { required: true },
            'fingerspotDevices[devicesSn]': { required: true }
        },
        messages: {
            'fingerspotDevices[serverIp]': { required: lang_Required },
            'fingerspotDevices[serverPort]': { required: lang_Required },
            'fingerspotDevices[devicesSn]': { required: lang_Required }
        }
    });

    $(".editable").each(function(){
        $(this).attr("disabled", "disabled");
    });

    $("#btnSave").click(function() {
        //if user clicks on Edit make all fields editable
        if($("#btnSave").attr('value') == edit) {
            
            $("#reportForm .editable").each(function(){
                $(this).removeAttr("disabled");
            });                        

            $("#btnSave").attr('value', save);
            return;
        }

        if($("#btnSave").attr('value') == save) {
            if ($("#reportForm").valid()) {
                $("#btnSave").val(lang_processing);
            }
            $("#reportForm").submit();
        }
    });
    
    });
