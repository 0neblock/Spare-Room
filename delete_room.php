<?php
    require_once("require.php");

    if(isset($_GET['room']) && isset($_GET['day']) && isset($_GET['hour'])){
        $room = $_GET['room'];
        $hour = $_GET['hour'];
        $day = $_GET['day'];
        mysqli_query($con, "DELETE FROM `rooms` WHERE `room` = '$room' AND `hour` = '$hour' AND `day` = '$day'") or die(mysqli_query($con));
        header("Location: /");
    
    }

?>