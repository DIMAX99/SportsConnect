<?php
include"db.php";

session_start();

$friend_id = $_POST['receiver_id'];
$message=$_POST['message'];
$add_chat="insert into messages(sender_id,receiver_id,msg) values(:sender_id,:receiver_id,:msg)";
$add_chat_stmt=$con->prepare($add_chat);
$add_chat_stmt->bindParam(":sender_id",$_SESSION['id']);
$add_chat_stmt->bindParam(":receiver_id",$friend_id);
$add_chat_stmt->bindParam(":msg",$message);
if($add_chat_stmt->execute()){
    echo 'msg sent';
}
else{
    echo 'error';
}