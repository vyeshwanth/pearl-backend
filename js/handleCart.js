$(
    $(".button_1").click(function() {
        var action = $(this).attr("data-action");
        var id = $(this).attr("data-event-id");
        $.ajax({
            type : "GET",
            dataType : "xml",
            url : "handleCart.php?id=" + id + "&action=" + action,
            success : function (data) {
                var status = $(data).find("status").text();
                $("#feedback").html("<strong>"+status+"</strong>");
                if(action='remove') {
                    $("tr[data-event-id ='" + id+ "'").remove();
                }
            }
        })
    })
);
