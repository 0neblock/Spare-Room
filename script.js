var roomString = "";
var room = 0;
var level = 0;
var building = 0;
var levels = 11;
var rooms = 25;
var buildings = [];
var advanceHours = 2;

$.mobile.loading("show");

$(function(){
    getRooms();
    populateBuildings();
});

function deleteRoom(room, day, hour){
    var deleteArray = {'room': room, 'day': day, 'hour': hour};
    if(confirm("Remove this Room from 'Free' list permanently ?")){
        $.post("delete_room.php", deleteArray, function(data){
            getRooms();
        });
    }
}

function submit(){
    var postArray = {room: roomString};
    $.mobile.loading("show");
    $.post("post_room.php", postArray, function(data){
        $.mobile.loading("hide");
        location.href = "#main";
        getRooms();
    });
}

function getRooms(){
    $.mobile.loading("show");
    $.getJSON("get_rooms.php?advanceHours=" + advanceHours, function(data){
        $("#current").html("");
        console.log(data);
        if(data.roomsAvailable > 0){
            $.each(data.formatted.current, function(index, room){
                $("#current").append("<li data-theme='a' onclick=\"deleteRoom('" + room.room + "', " + room.day + ", " + room.hour + ")\"><b>" + room.room + "</b> for " + room.hours + " hour(s)</li>");
                $("#current").listview("refresh");
            });
            $("#upcoming").html("");
            $("#upcomingText").hide();
        } else {
            $("#current").html("<li>" + data.message + "</li>");
            $("#current").listview("refresh");
            $("#currentText").hide();
            $("#upcomingText").hide();
        }
        if(data.formatted){
        if(data.formatted.freeFrom.length){
                $("#upcomingText").show();
                $.each(data.formatted.freeFrom, function(index, room){
                    $("#upcoming").append("<li data-theme='a'><b>" + room.room + "</b> is free from " + room.startTime + " until " + room.endTime + "</li>");
                    $("#upcoming").listview("refresh");
                });
            } else {
                $("#upcomingText").hide();
            }
        }
        $.mobile.loading("hide");
    });
}

function populateBuildings(){

    $.getJSON("get_buildings.php", function(data){
        $("#buildingPicker").html("");
        console.log(data);
        if(data.status == "OK"){
            $("#buildingPicker").trigger("create");
            $.each(data.buildings, function(index, building){
                $("#buildingPicker").append("<li><a data-icon='forward' onClick='clickBuilding($(this).html(), " + building.levels + ", " + building.rooms + ")'>" + building.building + "</a></li>");
                $("#buildingPicker").listview("refresh");
            });

            $("#levelUL").trigger("create");
            $("#roomUL").trigger("create");
            $("#roomConfirm").trigger("create");
        }
    });
}

function clickBuilding(buildNum, buildingLevels, buildingRooms){
    levels = buildingLevels;
    rooms = buildingRooms;
    $("#levelUL").html("");
    location.href = "#levels";
    building = buildNum;
    for(var i = 1; i <= levels; i++){
        $("#levelUL").append("<li><a href='#rooms' onclick='clickLevel($(this).html())'>" + i + "</a></li>");

    }
    $("#levelUL").trigger("create");
    $("#levelUL").listview("refresh");
}

function clickLevel(levelNum){
    $("#roomUL").html("");
    location.href = "#rooms";
    level = levelNum;
    roomString += "." + level.toString();
    for(var i = 1; i <= rooms; i++){
        $("#roomUL").append("<li><a onclick='clickRoom($(this).html())'>" + i + "</a></li>");
    }
    $("#roomUL").trigger("create");
    $("#roomUL").listview("refresh");
}

function clickRoom(roomNum){
    room = roomNum;
    roomString = building.toString() + "." + level.toString() + "." + room.toString();
    console.log(roomString);
    $("#roomConfirm").html("");
    location.href = "#confirm";
    $("#roomConfirm").append("<li>Room " + roomString + " is Free right now</li><br /><li><a href='#' onclick='submit();'>Confirm?</a></li>");
    $("#roomConfirm").trigger("create");
    $("#roomConfirm").listview("refresh");;
}


function addBuilding(){
    $("#addBuilding").val("Posting...");
    var addnumber = $("#addnumber").val();
    $("#addnumber").val("");
    var addlevels = $("#addlevels").val();
    $("#addlevels").val("");
    var addrooms = $("#addrooms").val();
    $("#addrooms").val("");
    $.post("post_building.php", {"number": addnumber, "levels": addlevels, "rooms": addrooms}, function(data){
        console.log(data);
        populateBuildings();
        $("#buildingPopup").popup("close");

    });
}
