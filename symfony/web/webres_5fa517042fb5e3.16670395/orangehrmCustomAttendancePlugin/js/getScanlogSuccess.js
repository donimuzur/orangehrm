$(document).ready(function() {
   
    $("#btnSimpanData").click(function() {
        $("#btnSimpanData").val("Proses Simpan....");
        getSimpanData();
    });
});

function getSimpanData(){
    $.ajax({
        type:     "get",
        timeout: 0,
        cache:    false,
        url:      linkForSaveScanlog,
        dataType: "text",
        error: function(xhr, status, error) {
            alert("Error hubungi admin atau team IT \n"+xhr.responseText)
            $("#btnSimpanData").val("Simpan Data Scanlog"); 
        },
        success: function (data) {
            $("#btnSimpanData").val("Simpan Data Scanlog"); 
            location.reload(); 
        }
    });  
    return false;
}