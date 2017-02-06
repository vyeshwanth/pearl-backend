<?php
require 'connect.inc.php';
if( !isset($_COOKIE['cart_id']) )
{
   //set cookie if not set
    setCartIdCookie($connection);
}

$query = "SELECT * FROM `events`";
$query_run = mysqli_query($connection, $query);

if(!$query_run){

    echo 'problem loading events';

}

function setCartIdCookie($sqlConnection){

    $expiry_time = time()+86400;
    $cart_id = uniqid();
    $event_ids = array();
    $event_ids_json = json_encode($event_ids);
    setcookie('cart_id', $cart_id, $expiry_time);
    // Store the cart id in the database
    $cartIdCookie_query = "INSERT INTO `carts_info`(cart_id, event_ids, timestamp) VALUES ('$cart_id','$event_ids_json', '$expiry_time')";
    $cartIdCookie_query_run = mysqli_query($sqlConnection, $cartIdCookie_query);

    if( !$cartIdCookie_query_run )
    {

        echo 'Something went wrong';

    }

}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="css/eventslist.css" />
</head>
<body>
<div id="event-container">
    <ul>
        <?php
        while ( $event = mysqli_fetch_assoc($query_run) )
        {

            echo '<a href="event.php?id='.$event["event_id"].'"><li>'.$event["name"].'</li></a>';

        }
        ?>
    </ul>
</div>
</body>
</html>
