<?php
require 'connect.inc.php';
header('Content-Type: text/xml');
echo '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>';
echo '<response>';
if( isset($_POST['email']) )
{
    $email = mysqli_real_escape_string($connection,$_POST['email']);
    $query = "SELECT * FROM `users` WHERE `email_id` = '$email'";
    $query_run = mysqli_query($connection, $query);
    if($query_run)
    {

        if(mysqli_num_rows($query_run) == 1 )
        {
            //echoing the personal details
            echo '<status>registered</status>';
            $result = mysqli_fetch_assoc($query_run);
            $user_id = $result["user_id"];
            echo '<details>';
            echo '<email>'.$email.'</email>';
            echo '<name>'.$result["name"].'</name>';
            echo '<gender>'.$result["gender"].'</gender>';
            echo '<DOB>'.$result["DOB"].'</DOB>';
            echo '<college>'.$result["college"].'</college>';
            echo '<city>'.$result["city"].'</city>';
            echo '<phone>'.$result["phone"].'</phone>';
            echo '</details>';
            $events_query = "SELECT `name` FROM `events` WHERE `event_id` IN (SELECT `event_id` FROM `event_users` WHERE `user_id`='$user_id')";
            $events = mysqli_query($connection, $events_query);

            if($events && mysqli_num_rows($events)!=0)
            {

                echo '<eventsRegd>';

                while ( $event = mysqli_fetch_assoc($events) )
                {

                    echo '<event>';
                    echo '<name>'.$event["name"].'</name>';
                    echo '</event>';

                }

                echo '</eventsRegd>';

            }

        }

    }
}

if(isset($_COOKIE['cart_id'])) {
    $cart_id = mysqli_real_escape_string($connection, $_COOKIE['cart_id']);
    $query = "SELECT `event_ids` FROM `carts_info` WHERE cart_id='$cart_id'";
    $query_run = mysqli_query($connection, $query);
    if( $query_run ){

        $query_result = mysqli_fetch_assoc($query_run);
        $event_ids = join(",", json_decode($query_result['event_ids']));
        $event_details_query = "SELECT * FROM `events` WHERE event_id IN($event_ids)";

        if( $event_details_query_run = mysqli_query($connection, $event_details_query) )
        {
            echo '<newEventsReg>';

            while($event_details = mysqli_fetch_assoc($event_details_query_run))
            {

                echo '<event>';
                echo '<name>'.$event_details['name'].'</name>';
                echo '</event>';

            }

            echo '</newEventsReg>';
        }
    }
}
echo '</response>';
