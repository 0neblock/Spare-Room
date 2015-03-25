<?php

require_once("require.php");

$array = array("status" => "OK");

$query = mysqli_query($con, "SELECT * FROM `buildings`");

$buildings = array();

for($i = 0; $i < mysqli_num_rows($query); $i++){

    $building = mysqli_fetch_assoc($query);
    array_push($buildings, array("building" => $building['number'], "levels" => $building['levels'], "rooms" => $building['rooms']));

}
$array['buildings'] = $buildings;
header("Content-Type: application/json");
echo json_encode($array);

?>
