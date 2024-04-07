<?php

include"db.php";

session_start();

//  
$last_msg_id=$_POST['lastMessageId'];
$to_friend=$_POST['to_friend_id'];
try{
$update_chat="select * from messages where ((sender_id=:user_id and receiver_id=:to_friend_username) or (sender_id=:to_friend_username and receiver_id=:user_id)) and message_id>:lastMessageId";
$update_stmt=$con->prepare($update_chat);
$update_stmt->bindParam(":user_id",$_SESSION['id']);
$update_stmt->bindParam(":to_friend_username",$to_friend);
$update_stmt->bindParam(":lastMessageId",$last_msg_id);
$update_stmt->execute();
$chat_output='';
if($update_stmt->rowCount()>0){
    while($chat = $update_stmt->fetch(PDO::FETCH_ASSOC)){
        $last_msg_id=$chat['message_id'];
        if($chat['sender_id']==$_SESSION['id']){
            $chat_output.='<li class="sender_list_item"><div class="sender_chat"><p>'.$chat['msg'].'</p></div></li>';
        }
        else{
            $chat_output.='<li class="receiver_item"><div class="receiver_chat"><p>'.$chat['msg'].'</p></div></li>';
        }
    }
}
}
catch(PDOException $e){
    $chat_output=$e;
}
$response = array(
    'chatOutput' => $chat_output,
    'lastMessageId' => $last_msg_id
);
echo json_encode($response); 

