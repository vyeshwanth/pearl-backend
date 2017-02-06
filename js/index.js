$(
    $form = $("#reg_form"),
    $form.find("input[name='DOB']").datepicker(),
    //pre-populating form details
    $form.find("input[name='email']").keyup(function validator() {
        var email = $form.find("input[name='email']").val();
        if(validateEmail(email)){
            ajax_call(email);
        }
    })
);

// function to validate Email-id
function validateEmail(email) {
    var regex = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
    return regex.test(email);
}
//
function ajax_call(email) {
    console.log("request made");
    $.ajax({
        type: "POST",
        dataType: "xml",
        url: "userdata.php",
        data: {'email': email},
        success : function(data){
           var text = $(data).find("status").text();
           if(text == 'registered'){
               var $details = $(data).find("details"),
                   email = $details.find("email").text(),
                   name = $details.find("name").text(),
                   gender = $details.find("gender").text(),
                   DOB = $details.find("DOB").text(),
                   college = $details.find("college").text(),
                   city = $details.find("city").text(),
                   phone = $details.find("phone").text();
                   $form.find("input[name='email']").val(email);
                   $form.find("input[name='name']").val(name).attr("disabled","disabled");
                   $form.find("select[name='gender']").val(gender).attr("disabled","disabled");
                   $form.find("input[name='DOB']").val(DOB).attr("disabled","true");
                   $form.find("input[name='college']").val(college).attr("disabled","true");
                   $form.find("input[name='city']").val(city).attr("disabled","true");
                   $form.find("input[name='phone']").val(phone).attr("disabled","true");
               //displaying registered events
               var $eventsRegd = $(data).find("eventsRegd").find("event");
               $eventsRegd.each(function() {
                   var name = $(this).find("name").text();
                   console.log(name);
                   $("#events_registered").append("<li>"+name+"</li>").css("display","block");
               });
           }
            var $newEventsRegNames = $(data).find("newEventsReg").find("event");
            $newEventsRegNames.each(function () {
                var name = $(this).find("name").text();
                console.log(name);
                $("#new_events_reg").append("<li>"+name+"</li>").css("display","block");
            });
        }
    });
}
