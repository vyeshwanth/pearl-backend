var tot_amnt = 900;
$('.contact-form').find('.form-control').each(function() {
  var targetItem = $(this).parent();
  if ($(this).val()) {
    $(targetItem).find('label').css({
      'top': '10px',
      'fontSize': '14px'
    });
  }
});
$('.contact-form').find('.form-control').focus(function() {
  $(this).parent('.input-block').addClass('focus');
  $(this).parent().find('label').animate({
    'top': '10px',
    'fontSize': '14px'
  }, 300);
});
$('.contact-form').find('.form-control').blur(function() {
  if ($(this).val().length == 0) {
    $(this).parent('.input-block').removeClass('focus');
    $(this).parent().find('label').animate({
      'top': '25px',
      'fontSize': '18px'
    }, 300);
  }
});
$('.amount h1').html("Total Amount: <strong>" + tot_amnt + "</strong>/-");

//Ajax calls


$(
    $form = $("#reg_form"),

    $(".remove-item").click(function() {
        var action = $(this).attr("data-action");
        var id = $(this).attr("data-event-id");
        $.ajax({
            type : "GET",
            dataType : "xml",
            url : "handleCart.php?id=" + id + "&action=" + action,
            success : function (data) {
                var status = $(data).find("status").text();
                if(action =='remove') {
                    $("tr[data-event-id ='" + id+ "'").remove();
                }
            }
        })
    }),

    //pre-populating form details
    $form.find("input[name='email']").keyup(function validator() {
        var email = $form.find("input[name='email']").val();
        if(validateEmail(email)){
            prePopulateForm(email);
        }
    }),

    $form.submit(function(e) {
        e.preventDefault();
        console.log("submitted");
        $.ajax({
            type: "POST",
            dataType : "xml",
            url : "register.php",
            data : $form.serialize(),
            success : function (data)
            {
                var $user_status = $(data).find("userRegistration").text();
                var $errors = $(data).find("errorMessage").length;
                console.log($user_status);
                console.log($errors);
                var userRegStatus = $(data).find("userRegistration").attr("status");
                console.log(userRegStatus);
                if(!$errors && userRegStatus!=0)
                {
                    var $pearl_id = $(data).find("pearlId").text();
                    var messageType = "success";
                    var messageTitle = "successfully registered";
                    var messageText = "Your pearl-id is " + $pearl_id +". A confirmation email has been sent to your email-id";
                }
                else if(userRegStatus == 0)
                {
                    var messageType = "error";
                    var messageTitle = "Registration failed";
                    var messageText = $user_status;
                }
                swal({
                    title : messageTitle,
                    text : messageText,
                    type : messageType
                });
            }
        })
    })
);

// function to validate Email-id
function validateEmail(email) {
    var regex = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
    return regex.test(email);
}
//
function prePopulateForm(email) {
    console.log("request made");
    $.ajax({
        type: "POST",
        dataType: "xml",
        url: "userdata.php",
        data: {'email': email},
        success : handlePrePopulateFormResponse
    });
}
handlePrePopulateFormResponse = function(data){
    var text = $(data).find("status").text();
    if(text == 'registered'){
        var $details = $(data).find("details"),
            email = $details.find("email").text(),
            name = $details.find("name").text(),
            gender = $details.find("gender").text(),
            DOB = $details.find("DOB").text(),
            college = $details.find("college").text(),
            city = $details.find("city").text(),
            phone = $details.find("phone").text(),
            $form_input = $('.contact-form').find('.form-control');

        $form_input.parent('.input-block').addClass('focus');
        $form_input.parent().find('label').animate({
            'top': '10px',
            'fontSize': '14px'
        }, 300);

        $form.find("input[name='email']").val(email);
        $form.find("input[name='name']").val(name).attr("readonly","readonly");
        $form.find("select[name='gender']").val(gender).attr("readonly","readonly");
        $form.find("input[name='DOB']").val(DOB).attr("readonly","readonly");
        $form.find("input[name='college']").val(college).attr("readonly","readonly");
        $form.find("input[name='city']").val(city).attr("readonly","readonly");
        $form.find("input[name='phone']").val(phone).attr("readonly","readonly");
        //displaying registered events
        // var $eventsRegd = $(data).find("eventsRegd").find("event");
        // $eventsRegd.each(function() {
        //     var name = $(this).find("name").text();
        //     console.log(name);
        //     $("#events_registered").append("<li>"+name+"</li>").css("display","block");
        // });
    }
};