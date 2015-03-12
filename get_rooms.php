<?php

require_once("require.php");

$array = array();

$time = time();
$dayNum = date("N", $time);
$hourNum = date("G", $time);
$minuteNum = date("i",$time);
if($minuteNum >= 30){
    $hourNum++;
}

if(isset($_GET['advanceHours'])){
    $advanceHours = $_GET['advanceHours'];
    $advanceHours = $hourNum + $advanceHours;
} else {
    $advanceHours = 24;
}

$roomsInARow = array();

$allRooms = array();

$query = mysqli_query($con, "SELECT * FROM rooms WHERE `day` = '$dayNum' AND `hour` >= '$hourNum' AND `hour` <= '$advanceHours' ORDER BY `room` ASC, `hour` ASC") or die(mysqli_error($con));
$i = 0;
$roomsArray = array();
$formattedArray = array("current" => array(), "freeFrom" => array());

for($i = 0; $i < mysqli_num_rows($query); $i++){
    $room = mysqli_fetch_assoc($query);
    $startTime = (intVal($room['hour']) - 1) . ":30";
    $endTime = $room['hour'] . ":30";
    if(!isset($roomsInARow[strval($room['room'])])){
        $roomsInARow[strval($room['room'])] = array("num" => 1, "lasti" => $i);
        array_push($allRooms, strval($room['room']));
    } else {
        if($roomsInARow[strval($room['room'])]["lasti"] == $i - 1){
            $roomsInARow[strval($room['room'])]["num"]++;
        }
    }
    if($room['hour'] == $hourNum){
        array_push($formattedArray["current"], array("room" => $room['room'], "startTime" => $startTime, "endTime" => $endTime, "hours" => 1, "day" => $room['day'], "hour" => $room['hour']));
    }
    
    array_push($roomsArray, array("room" => $room['room'], "startTime" => $startTime, "endTime" => $endTime, "day" => $room['day']));
    
    if($room['hour'] > $hourNum){
        // Is an upcoming room
        array_push($formattedArray["freeFrom"], array("room" => $room['room'], "startTime" => $startTime, "endTime" => $endTime));
    }
}

for($i = 0; $i < sizeof($allRooms); $i++){
    if($roomsInARow[$allRooms[$i]]["num"] > 1){
        // this room has more slots
        for($j = 0; $j < sizeof($formattedArray["current"]); $j++){
            if($formattedArray["current"][$j]["room"] == $allRooms[$i]){
                $formattedArray["current"][$j]["hours"] = $roomsInARow[$allRooms[$i]]["num"];
            }
        }
        
    }
}

$array["rooms"] = $roomsArray;
$array["formatted"] = $formattedArray;
$array["roomsAvailable"] = sizeof($formattedArray["current"]);
if(!$array["roomsAvailable"]){
    $array["message"] = "No Rooms Available.";
}

if(!$i){
    // No free rooms
    $array = array("roomsAvailable" => 0, "message" => "There are no Free Rooms.");
}


echo json_encode($array);

?>