<?php
require 'connect.inc.php';
header('Content-Type: text/xml');
echo '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>';
if( isset($_GET['id']) && isset($_COOKIE['cart_id']) && $_GET['action'] )
{
    $cart_id = mysqli_real_escape_string($connection, $_COOKIE['cart_id']);
    $event_id = mysqli_real_escape_string($connection, $_GET['id']);
    $action = $_GET['action'];
    echo '<response>';
    handleCartItem($connection, $cart_id, $event_id,$action);
    echo '</response>';

}
function handleCartItem($sqlConnection, $cart_id, $event_id,$action){

    $status = '';
    $query = "SELECT `event_ids` FROM `carts_info` WHERE cart_id='$cart_id'";
    $query_run = mysqli_query($sqlConnection, $query);

    if( $query_run && mysqli_num_rows($query_run) == 1 )
    {

        $result = mysqli_fetch_assoc($query_run);
        $events_array = json_decode($result['event_ids']);
        if( $action == 'add' )
        {
            //add the event to cart
            if( in_array($event_id, $events_array) )
            {
                //check whether event is already in cart
                $status = "Event Already in Cart";
            }
            else
            {
                //adding event to the array
                array_push($events_array, $event_id);
                $status = "Event added to cart";
            }
        }
        elseif ($action == "remove")
        {
            //remove event from the cart
            $key = array_search($event_id, $events_array);
            if($key !== false)
            {
                //removing event form the array
                unset($events_array[$key]);
                $events_array = array_values($events_array);
                $status = "Event removed from cart";

            }
        }
        $event_ids_updated = json_encode($events_array);
        //updating the array in database
        $event_ids_update_query = "UPDATE `carts_info` SET `event_ids` = '$event_ids_updated' WHERE `cart_id`='$cart_id'";

        if(mysqli_query($sqlConnection, $event_ids_update_query))
        {
            echo '<status>'.$status.'</status>';
        }

    }

}