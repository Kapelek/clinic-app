function backUrl(){
    var previousUrl = document.referrer;
    if(previousUrl.includes("patients_browse")==true){
        return previousUrl;
    }else{
        return "patients_browse.php"
    }
}