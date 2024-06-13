$(document).ready(()=>{
    updateRecords();
});

function updateRecords(){
    var formData = $("#filters").serialize();
    $.ajax({
        url: 'doctors_browse_get_records.php',
        type: 'GET',
        data: formData,
        dataType: 'html',
        success: function(response) {
            // console.log('DATA SUCCESFULLY LOADED');
            $('#rows-place').html(response);
            var numRows = document.getElementById('rows-place').getElementsByTagName('li').length;
            $("#num-rows").html(numRows);
        },
        error: function() {
            // console.log('PROBLEM WITH GETTING THE DATA');
        }
    });
}
