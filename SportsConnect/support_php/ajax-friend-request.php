<?php
include"db.php";
session_start();
$player_to_name=$_POST['player'];
$addrequest="insert into friend_request(sender_id,reciever_id) values(:sender,(select id from player where username=:receiver))";
$request_statement=$con->prepare($addrequest);
$request_statement->bindParam(":sender",$_SESSION['id']);
$request_statement->bindParam(":receiver",$player_to_name);
 if($request_statement->execute()){
    echo "Friend Request Sent";
    
 }
 else{
    echo "Failed to send Request";
 }
?>