<?php
require 'connect.inc.php';

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
                $i=1;
                while($event_details = mysqli_fetch_assoc($event_details_query_run)){
                    echo '<tr data-event-id="'.$event_details["event_id"].'">';
                    echo '<td class="index">'.$i.'</td>';
                    echo '<td class="organisationname">'.$event_details["name"].'</td>';
                    echo '<td class="actions">';
                    echo '<div  class="remove-item" title="Remove" data-event-id="'.$event_details["event_id"].'" data-action = "remove" >Remove</div>';
                    echo '</td></tr>';
                    $i++;
                }
            }
        }
    }
}