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
        $last_msg_id=$chat['message_id'];
        if($chat['sender_id']==$_SESSION['id']){
            $chat_output.='<li class="sender_list_item"><div class="sender_chat"><p>'.$chat['msg'].'</p></div></li>';
        }
        else{
            $chat_output.='<li class="receiver_item"><div class="receiver_chat"><p>'.$chat['msg'].'</p></div></li>';
        }

    }
}
else{
    $chat_output.='<li><p>no message</p></li>';
    $last_msg_id=0;
}
$chat_output.='</ul>';

// echo $last_msg_id;
$response = array(
    'chatOutput' => $chat_output,
    'lastMessageId' => $last_msg_id
);
echo json_encode($response);