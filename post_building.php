<?php
    require_once("require.php");

    if(isset($_POST['number']) && isset($_POST['levels']) && isset($_POST['rooms'])){
        mysqli_query($con, "INSERT INTO `buildings` (`number`,`levels`,`rooms`) VALUES ('$_POST[number]','$_POST[levels]','$_POST[rooms]')") or die(mysqli_error($con));
        echo json_encode(array("status"=>"OK"));
    }

?>
