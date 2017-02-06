<?php
require 'connect.inc.php';

$event_id = '';
$event_title = '';
if( isset($_GET['id']) )
{
    $event_id = mysqli_real_escape_string($connection, $_GET['id']);
    $query = "SELECT * FROM `events` WHERE event_id = '$event_id'";
    $query_run = mysqli_query($connection, $query);
    if(!$query_run)
    {
        echo 'problem loading event details';
    }
    else
    {
        $result = mysqli_fetch_assoc($query_run);
        $event_title = $result['name'];
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
</head>
<body>
<h1><?php echo $event_title; ?></h1>
<button class="button_1" data-event-id="<?php echo $event_id; ?>" data-action = "add">Add to cart</button>
<div id="feedback"></div>
</body>
<script type="text/javascript" src="js/jquery-3.1.1.min.js"></script>
<script type="text/javascript" src="js/handleCart.js"></script>
</html>
