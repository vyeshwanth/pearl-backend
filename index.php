<?php

require 'functions.php';

if( !isset($_COOKIE['cart_id']) )
{
    //set cookie if not set
    setCartIdCookie($connection);

}


?>

<!DOCTYPE html>
<html >
<head>
    <meta charset="UTF-8">
    <title>Registration Page</title>
    <link rel='stylesheet prefetch' href='http://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css'>
    <script src='http://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js'></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="css/sweetalert.css">
    <link rel="stylesheet" href="css/style.css">
</head>

<body>
  

    <div class="modal fade" id="myModal" role="dialog">
        <div class="modal-dialog">


            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">ALL EVENTS</h4>
                </div>
                <div class="modal-body">
                    <form action="#">
                        <p>
                            <input type="checkbox" id="event1" />
                            <label for="event1">Gliteratti</label>
                        </p>
                        <p>
                            <input type="checkbox" id="event2" checked="checked" />
                            <label for="event2">Terpischore</label>
                        </p>
                        <p>
                            <input type="checkbox" id="event3" checked="checked" />
                            <label for="event3">Till Deaf Do we Part</label>
                        </p>
                        <p>
                            <input type="checkbox" id="event4" checked="checked"/>
                            <label for="event4">Cipher</label>
                        </p>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Apply</button>
                </div>
            </div>

        </div>
    </div>
    <div class="page">

        <table class="layout display responsive-table">
            <thead>
                <tr>
                    <th colspan="3">Registration Cart</th>
                </tr>
            </thead>
            <tbody>
            <?php cartGenerator($connection); ?>
            </tbody>
        </table>
        <button type="button" class="btn btn-info btn-lg addMore" data-toggle="modal" data-target="#myModal">Add</button>
    </div>
    <div class="details">
        <section class="contact-wrap">
            <form id="reg_form" class="contact-form">
                <div class="col-sm-12">
                    <div class="input-block">
                        <label for="email">Email</label>
                        <input type="text" class="form-control" name="email" autocomplete="off">
                    </div>
                </div>
                <div class="col-sm-12">
                    <div class="input-block">
                        <label for="name">Full Name</label>
                        <input type="text" class="form-control" name="name" autocomplete="off">
                    </div>
                </div>
                <div class="col-sm-12">
                    <div class="input-block">
                        <select  name="gender" class="form-control" >
                            <option value="male">Male</option>
                            <option value="female">Female</option>
                        </select>
                    </div>
                </div>
                <div class="col-sm-12">
                    <div class="input-block">
                        <label for="DOB">Date Of Birth</label>
                        <input type="text" class="form-control" name="DOB" autocomplete="off">
                    </div>
                </div>
                <div class="col-sm-12">
                    <div class="input-block">
                        <label for="college">College</label>
                        <input type="text" class="form-control" name="college" autocomplete="off">
                    </div>
                </div>
                <div class="col-sm-12">
                    <div class="input-block">
                        <label for="city">City</label>
                        <input type="text" class="form-control" name="city" autocomplete="off">
                    </div>
                </div>
                <div class="col-sm-12">
                    <div class="input-block">
                        <label for="phone">Phone Number</label>
                        <input type="text" class="form-control" name="phone" autocomplete="off">
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="input-block">
                        <input type="submit" class="form-control" name="register" value="Register" >
                    </div>
                </div>
            </form>
        </section>
        <section>
            <div class="amount">
                <h1 class="col-sm-12"></h1>
                <button class="square-button">Proceed To Payment</button>
            </div>
        </section>
    </div>
    <script type="text/javascript" src="js/registration.js"></script>
    <script  type="text/javascript" src="js/sweetalert.min.js"></script>
</body>
</html>
