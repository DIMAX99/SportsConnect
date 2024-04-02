<?php

include'db.php';

session_start();

$to_friend=$_POST['friend'];
$find_chat="select * from messages where (sender_id=:user_id and receiver_id=:to_friend_username) or (sender_id=:to_friend_username and receiver_id=:user_id)";
$find_chat_stmt=$con->prepare($find_chat);
$find_chat_stmt->bindParam(":user_id",$_SESSION['id']);
$find_chat_stmt->bindParam(":to_friend_username",$to_friend);
$find_chat_stmt->execute();

$receiver_det="select username,image from player where id=:friend_id";
$receiver_det_stmt=$con->prepare($receiver_det);
$receiver_det_stmt->bindParam(":friend_id",$to_friend);
$receiver_det_stmt->execute();
$receiver_details=$receiver_det_stmt->fetch();

// echo $receiver_details;

$chat_output='<ul id="chats-list">';
if($find_chat_stmt->rowCount()>0){
    $chat_output.='<li><h4>'.$receiver_details['username'].'<h4></li>';
    while($chat = $find_chat_stmt->fetch(PDO::FETCH_ASSOC)){
        $chat_output.='<li><p>'.$chat['msg'].'</p></li>';
    }
}
else{
    $chat_output.='<li><p>no message</p></li>';
}
$chat_output.='</ul>';
echo $chat_output;
