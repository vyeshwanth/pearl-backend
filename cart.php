<?php
$event_ids ='';
function cartGenerator($sqlConnection){
    if(isset($_COOKIE['cart_id'])){
        $cart_id = mysqli_real_escape_string($sqlConnection, $_COOKIE['cart_id']);
        $query = "SELECT `event_ids` FROM `carts_info` WHERE cart_id='$cart_id'";
        $query_run = mysqli_query($sqlConnection, $query);
        if($query_run ){
            $query_result = mysqli_fetch_assoc($query_run);
            $event_ids = join(",", json_decode($query_result['event_ids']));
            $event_details_query = "SELECT * FROM `events` WHERE event_id IN($event_ids)";
            if($event_details_query_run = mysqli_query($sqlConnection, $event_details_query)){
                while($event_details = mysqli_fetch_assoc($event_details_query_run)){
                    echo '<tr data-event-id="'.$event_details["event_id"].'">';
                    echo '<td>'.$event_details['name'].'</td>';
                    echo '<td>'.$event_details['price'].'</td>';
                    echo '<td><button class = "button_1" data-event-id="'.$event_details["event_id"].'" data-action = "remove">Remove</button></td>';
                    echo '</tr>';
                }
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
</head>
<style>
    table{
        width: 70%;
        border: solid black 1px;
        border-bottom: none;
        border-right: none;
        padding: 0;
        margin: 10px auto 10px auto;
    }
    th{
        border: solid black 1px;
        border-left: none;
        border-top: none;
    }
    td{
        border-right: solid black 1px;
        border-bottom: solid black 1px;
        text-align: center;
        padding: 0;
        margin: 0;
    }
    #checkout_butt{
        float: right;
        margin-right: 300px;
        text-decoration: none;
        padding: 2px;
        color: black;
    }
</style>
<body>
<table>
    <tbody>
    <tr>
        <th>Event name</th>
        <th>Price</th>
        <th>Select</th>
    </tr>
    <?php
    require 'connect.inc.php';
    cartGenerator($connection)
    ?>
    </tbody>
</table>
    <a href="register.html" id="checkout_butt">Checkout</a>
<div id="feedback"></div>
</body>
<script type="text/javascript" src="js/jquery-3.1.1.min.js"></script>
<script type="text/javascript" src="js/handleCart.js"></script>
</html>
