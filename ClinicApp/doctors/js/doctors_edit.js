function showNewSpecWindow(){
    $("#doctors-new-specialization").toggleClass("display-block");
}

function showSalary(){
    if($("#checkbox-doctor-status").hasClass("checked")==false){
        $("#salary-line").css("display","block");
    }else{
        $("#salary-line").css("display","none");
    }
    $("#checkbox-doctor-status").toggleClass("checked");
}