function backUrl(){
    var previousUrl = document.referrer;
    if(previousUrl.includes("doctors_browse")==true){
        return previousUrl;
    }else{
        return "doctors_browse.php"
    }
}