<?php
require 'connect.inc.php';
header('Content-Type: text/xml');
echo '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>';
echo '<response>';
// Check for cookie if not set, create on
if( !isset($_COOKIE) ){

    setCartIdCookie($connection);

}

// validating form and registering
if(formValidate()){

    $cart_id = mysqli_real_escape_string($connection, $_COOKIE['cart_id']);
    $email = mysqli_real_escape_string($connection, trim($_POST['email']));

    // check if the email-id exists
    if(!existingEmail($connection, $email)){
        $name = mysqli_real_escape_string($connection, trim($_POST['name']));
        $gender = mysqli_real_escape_string($connection, trim($_POST['gender']));
        $DOB = mysqli_real_escape_string($connection, trim($_POST['DOB']));
        $college = mysqli_real_escape_string($connection, trim($_POST['college']));
        $city = mysqli_real_escape_string($connection, trim($_POST['city']));
        $phone = mysqli_real_escape_string($connection, trim($_POST['phone']));
        // if email-id doesn't exist, add the user
        $query1 = "INSERT INTO `users`(`user_id`, `pearl_id` ,`email_id`, `name`, `gender`, `DOB`, `college`, `city`, `phone`)
                  VALUES(NULL,0, '$email', '$name', '$gender', '$DOB', '$college', '$city', '$phone')";
        $insertUser = mysqli_query($connection, $query1);

        //If successfully added the user
        if($insertUser)
        {
            $user_id = mysqli_insert_id($connection);
            //pearl id generation
            $pearl_id = pearlIdGenerator($connection, $user_id);
            if($pearl_id !== false)
            {
                $log_info = "user_id: $user_id , pearl_id: $pearl_id , email_id: $email , name: $name , gender: $gender , DOB: $DOB , college: $college , city: $city , phone: $phone , status: successful \n" ;
                $filename = "user_requests.log";
                $file = fopen( $filename, "w" );
                if($file != false)
                {
                    fwrite( $file, $log_info );
                    fclose( $file );
                }
                echo '<userRegistration status="1">User registration successful</userRegistration>';
                echo '<pearlId>'.$pearl_id.'</pearlId>';
                registerEvents($connection, $user_id, $cart_id);

            }
        }
        else
        {
            $log_info = "email_id: $email , name: $name , gender: $gender , DOB: $DOB , college: $college , city: $city , phone: $phone , status: failed \n" ;
            $filename = "user_requests.log";
            $file = fopen( $filename, "w" );
            if($file != false)
            {
                fwrite( $file, $log_info );
                fclose( $file );
            }
            echo '<userRegistration status="0">User registration failed</userRegistration>';
        }
    }
    elseif( $query2_run = mysqli_query($connection, "SELECT `user_id`, `pearl_id` FROM `users` WHERE  `email_id` = '$email'") )
    {
            echo '<userRegistration status="2">User already registered</userRegistration>';
            $query2_result = mysqli_fetch_assoc($query2_run);
            $user_id = $query2_result['user_id'];
            echo '<pearlId>'.$query2_result['pearl_id'].'</pearlId>';
            registerEvents($connection, $user_id, $cart_id);
    }
    
}
else
{
    echo '<userRegistration status="0">All fields should be filled</userRegistration>';
}
echo '</response>';

function setCartIdCookie($sqlConnection)
{
    $expiry_time = time()+86400;
    $cart_id = uniqid();
    $event_ids = array();
    $event_ids_json = json_encode($event_ids);
    setcookie('cart_id', $cart_id, $expiry_time);
    $cartIdCookie_query = "INSERT INTO `carts_info`(cart_id, event_ids, timestamp) VALUES ('$cart_id','$event_ids_json', '$expiry_time')";
    $cartIdCookie_query_run = mysqli_query($sqlConnection, $cartIdCookie_query);
    if(!$cartIdCookie_query_run){
        echo 'Something went wrong!!!';
    }
}

function formValidate(){
    $input_fields = ["email","name","gender","DOB", "college", "city", "phone"];
    foreach ($input_fields as $input_field)
    {
        if(!isset($_POST[$input_field]) || empty($_POST[$input_field]))
        {
            return false;
        }
    }
    return true;
}

function existingEmail($sqlConnection, $email)
{
    $result = mysqli_query($sqlConnection, "SELECT `user_id` FROM `users` WHERE `email_id` = '$email'");
    if($result && mysqli_num_rows($result) == 0){
        return false;
    }
    else{
        return true;
    }
}

function registerEvents($sqlConnection, $user_id, $cart_id)
{
    //getting the events in the cart
    $errorFlag = 0;
    $cart_events_query = mysqli_query($sqlConnection, "SELECT `event_ids` FROM `carts_info` WHERE `cart_id` = '$cart_id'");
    if( $cart_events_query && mysqli_num_rows($cart_events_query)==1 )
    {
        $query_result = mysqli_fetch_assoc($cart_events_query);
        $event_ids = json_decode($query_result['event_ids']);
        if( empty($event_ids) )
        {
            echo '<Message>No events are registered</Message>';
            return;
        }

        foreach( $event_ids as $event_id )
        {
            $result = mysqli_query($sqlConnection, "SELECT `event_id` FROM `event_users`
                                                    WHERE `event_id`='$event_id' AND `user_id`='$user_id'");
            if(mysqli_num_rows($result)==0)
            {
                $regEvent_query = mysqli_query($sqlConnection, "INSERT INTO `event_users`(`user_id`,`event_id`,`registered_on`)
                                                                VALUES ('$user_id', '$event_id', CURRENT_TIMESTAMP)" );
                if(!$regEvent_query)
                {
                    $errorFlag = 1 ;
                    echo '<errorMessage>Trouble registering for Event Event-id:'.$event_id.'</errorMessage>';
                }

            }
        }
        if(!$errorFlag){
            echo '<Message>Registered for events successfully</Message>';
        }
    }
}

function pearlIdGenerator($sqlConnection, $id)
{

    $pearl_id =  'PEARL'.$id.'H';
    $pearl_id_insertQuery = "UPDATE `users` SET `pearl_id` = '$pearl_id' WHERE `user_id`='$id'";
    if( !mysqli_query($sqlConnection, $pearl_id_insertQuery) )
    {
        echo  'Something went wrong!!!';
        return false;
    }
    return $pearl_id;
}