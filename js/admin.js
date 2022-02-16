var connecturl = OC.generateUrl('/apps/rocket_integration/setup-url');
$(document).ready(function () {
    console.log(connecturl);
    $("#rcconnect").on("click",function(){
        if ($("#rcuser").val() && $("#rcpassword").val() && $("#rcurl").val()) {
            $(".rocketinput").prop( "disabled", true );
            $(this).prop( "disabled", true );
            $(this).html('<img src="/apps/rocket_integration/img/1476.gif" width=16> Connecting...');
            console.log("admin rocket chat on");
            rcconnect($("#rcuser").val(), $("#rcpassword").val(), $("#rcurl").val());
        } else {
            alert("Check your user "+$("#rcuser").val()+" id, password and Rocket Chat Server URL ");
        }
        
    });
});
function rcconnect(rcuser, rcpassword, rcurl) {
    var data = {
        rcuser: rcuser,
        rcpassword: rcpassword,
        rcurl: rcurl,
    };

    $.ajax({
        url: connecturl,
        type: "post",
        data: data,
        success: function (data) {
            console.log(data);
            $(".rocketinput").prop( "disabled", false );
            $("#rcconnect").prop( "disabled", false );
            $("#rcconnect").html("Connect and register");
        },
        error: function (jqXHR, textStatus, errorThrown) {
            alert('Something went wrong');
            
        },
    }).done(function(res){
        console.log('in done');
        console.log(res);
        if (res.status == 'success') {
            var userId = res.userId;
            var authToken = res.authToken;
            console.log(userId);
            console.log(authToken);
            $('#personalAccessToken').val(authToken);
            $('#userId').val(userId);
        }
    });
}