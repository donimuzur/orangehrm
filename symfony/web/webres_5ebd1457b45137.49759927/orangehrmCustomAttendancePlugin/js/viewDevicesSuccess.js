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

    $("#btnInfo").click(function() {
        $('#DeviceInfo1').html(''); 
        $("#btnInfo").val(lang_processing);
        getDeviceInfo();
    });
    
    $("#btnSyncTime").click(function() {
        $("#btnSyncTime").val(lang_processing);
        getSyncTime();
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
    function getDeviceInfo(){
        var ServerIp = $('#fingerspotDevices_serverIp').val();
        var ServerPort = $('#fingerspotDevices_serverPort').val();
        var SerialNumber = $('#fingerspotDevices_devicesSn').val();
        $.get(
            linkForGetDeviceInfo,
            {
                ServerIp: ServerIp,
                ServerPort: ServerPort,
                SerialNumber : SerialNumber,
                actionRecorder:actionRecorder
            },
            function(data, textStatus) {
                if( data != ''){
                    $("#DeviceInfo").show();
                    $('#DeviceInfo1').html(data); 
                    $("#btnInfo").val("Get Device Info"); 
                }  
            });
                        
        return false;
            
    }

    function getSyncTime(){
        var ServerIp = $('#fingerspotDevices_serverIp').val();
        var ServerPort = $('#fingerspotDevices_serverPort').val();
        var SerialNumber = $('#fingerspotDevices_devicesSn').val();
        $.get(
            linkForSyncTime,
            {
                ServerIp: ServerIp,
                ServerPort: ServerPort,
                SerialNumber : SerialNumber,
                actionRecorder:actionRecorder
            },
            function(data, textStatus) {
                location.reload(); 
            });
                        
        return false;
            
    }