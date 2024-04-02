<?php

include"db.php";

session_start();
$pusername=$_POST['player'];
$update_req_status="update friend_request set status=1 where sender_id=(select id from player where username=:pusername)";
$update=$con->prepare($update_req_status);
$update->bindParam(":pusername",$pusername);
$update->execute();

$add="insert into friend(friend_1,friend_2) values(:friend1,(select id from player where username=:friend2))";
$add_friend=$con->prepare($add);
$add_friend->bindParam(":friend1",$_SESSION['id']);
$add_friend->bindParam(":friend2",$pusername);
$add_friend->execute();
echo 'done';

