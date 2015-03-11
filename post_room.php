<?php
    require_once("require.php");
    if(isset($_POST['room'])){
        $room = $_POST['room'];
        
        $time = time();
        $dayNum = date("N", $time);
        $hourNum = date("G", $time);
        $minuteNum = date("i",$time);
        if($minuteNum >= 30){
            $hourNum++;
        }
        $query = mysqli_query($con, "INSERT INTO rooms (`day`, `hour`, `room`) VALUES ('$dayNum', '$hourNum', '$room')") or die(mysqli_error($con));
        header("Location: /");
    
}
?>