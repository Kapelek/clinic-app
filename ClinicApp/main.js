function toggleWindow(){
    $("#window-bg").toggleClass("show");
}

function logout(){
    $.ajax({
        url: "logout.php",
        method: "POST",
        success: function(response) {
          console.log("SESSION CLOSED");
          window.location.href = "login/login.php";
        },
        error: function(xhr, status, error) {
            console.log("SESSION CLOSING ERROR: " + error);
        }
    });
}

