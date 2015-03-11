<?php
    require_once("require.php");

    if(isset($_POST['room']) && isset($_POST['day']) && isset($_POST['hour'])){
        $room = $_POST['room'];
        $hour = $_POST['hour'];
        $day = $_POST['day'];
        mysqli_query($con, "DELETE FROM `rooms` WHERE `room` = '$room' AND `hour` = '$hour' AND `day` = '$day'") or die(mysqli_query($con));
        header("Location: /");
    
    }

?>