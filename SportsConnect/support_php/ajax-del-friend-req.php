<?php
session_start();
include'db.php';
$player_name=$_POST['player'];
$get_player_id="delete from friend_request where sender_id=:sender_id and reciever_id=(select id from player where username=:pl_username)";
$delete_req_query=$con->prepare($get_player_id);
$delete_req_query->bindParam(":sender_id",$_SESSION['id']);
$delete_req_query->bindParam(":pl_username",$player_name);
if($delete_req_query->execute()){
    echo 'done';
}

