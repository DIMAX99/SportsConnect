<?php
include"../support_php/db.php";

session_start();

if(!isset($_SESSION['logged']) || $_SESSION['logged']!=true){
    header('location:/SportsConnect/Player_php/login.php');
    exit();
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../CSS/player_friends.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>

    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
    <link href="https://fonts.googleapis.com/css2?family=Urbanist:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
    <title>Friends</title>
    <style>
        main{
            padding: 5.125em;
        }
    </style>
</head>
<body>
    <?php
    include"../HTML/trial.php";
    ?>
    <main>
        <div id="chat-friend-con">

        <div class="current_friend_list">
                <h3>Friends</h3> 
                <div class="friend_list_container">
                    <?php
                    $find="select f.* 
                    from friend f
                    join friend_request fr on (f.friend_1 = fr.sender_id AND f.friend_2 = fr.reciever_id) OR (f.friend_1 = fr.reciever_id AND f.friend_2 = fr.sender_id)
                    where (fr.sender_id = :current_user_id OR fr.reciever_id = :current_user_id) AND fr.status = 1";
                    $find_stmt=$con->prepare($find);
                    $find_stmt->bindParam(":current_user_id",$_SESSION['id']);
                    $find_stmt->execute();
                    // $friend=$find_stmt->fetchAll(PDO::FETCH_ASSOC);
                    // print_r($friend);
                        
                    $friend_output_list='<ul id="friend_list">';
                    if($find_stmt->rowCount()>0){
                        while($friend=$find_stmt->fetch(PDO::FETCH_ASSOC)){
                            // print_r($friend);
                            $friend_id=($friend['friend_1']==$_SESSION['id'])?$friend['friend_2']:$friend['friend_1'];
                            $friend_det="select id,username,image from player where id=:friend_id";
                            $friend_details=$con->prepare($friend_det);
                            $friend_details->bindParam(":friend_id",$friend_id);
                            $friend_details->execute();
                            $f_det=$friend_details->fetch();

                            $friend_output_list.='<li class="friend_list_item">
                                                <div class="friend-list-con">
                                                    <div class="friend-dp-con">
                                                        <img src="../Images/uploaded_img/player/'.$f_det['image'].'" alt="dp">
                                                    </div>
                                                    <div class="friend-name-con">
                                                        <h4 class="fusername" data-id="'.$f_det['id'].'">'.$f_det['username'].'</h4>
                                                    </div>
                                                    <div class="v-m-btn-div">
                                                        <button class="v-m-btn v">View Profile</button>
                                                        <button class="v-m-btn m">Message</button>
                                                    </div>
                                                </div>
                                                </li>';
                        }
                        $friend_output_list.='</ul>';
                    }
                    else{
                        $friend_output_list.='<li><div><h4>No Friends</h4></div></li>';
                    }
                    echo $friend_output_list;
                    
                    ?>
                </div>
            </div>
            <div class="chat-section">
                <h3>Chats</h3>
                    <div class="chat-area-div">

                    </div>
                    <div class="message-send-input">
                        <form action="" id="typing-area" autocomplete="off">
                            <input type="text" id="receiver_id" class="receiver_id" name="receiver_id" value="123" hidden>
                            <input type="text" placeholder="Type a message here...." class="message" name="message">
                            <button type="submit" id="send-msg-btn">Send</button>
                        </form>
                    </div>
            </div>
            <div id="friend-request-con">
                <h3>Friend Requests</h3>
                <?php
                $request="select * from friend_request where reciever_id=:user_id and status=0";
                $pending_request_inbox=$con->prepare($request);
                $pending_request_inbox->bindParam(":user_id",$_SESSION['id']);
                $pending_request_inbox->execute();
                $request_list="<ul id='request_list'>";
                if($pending_request_inbox->rowCount()>0){
                    while($player_request=$pending_request_inbox->fetch(PDO::FETCH_ASSOC)){

                        $request_details="select username,image from player where id=:player_sender_id";
                        $sender_request_details=$con->prepare($request_details);
                        $sender_request_details->bindParam(":player_sender_id",$player_request['sender_id']);
                        $sender_request_details->execute();
                        $sender=$sender_request_details->fetch(PDO::FETCH_ASSOC);

                        $request_list.='<li class="request-list-item">
                                        <div class="r-l-i-div">
                                            <div class="sender-dp">
                                                <img src="../Images/uploaded_img/player/'.$sender['image'].'" alt="dp">
                                            </div>
                                            <div class="sender-name-div">
                                                <h4 class="pusername">'.$sender['username'].'</h4>
                                            </div>
                                            <div class="a-r-btn-div">
                                                <button class="a-r-btn acc">Accept</button>
                                                <button class="a-r-btn rej">Reject</button>
                                            </div>
                                        </div>
                                        </li>';
                    }      
                }
                else{
                    $request_list.='<li class="pending_request_message">
                                    <h3>No Pending Request</h3>
                                    </li>';
                }
                $request_list.='</ul>';
                    echo $request_list; 
                ?>
            </div>
            
        </div>
    </main>
    <script>
        $(document.body).on('click','.acc',function(){
            var player_list_item=$(this).closest('li');
            var player_username =player_list_item.find('.r-l-i-div .sender-name-div .pusername').text().trim();
            var button = $(this)
            // console.log(player_username);
            $.ajax({
               type:'post',
               url:'../support_php/ajax-add-friend.php',
               data:{player:player_username},
               success:function(rt){
                console.log(rt)
                location.reload();
               } 
            });
        });
    </script>
    <script>
        const form = document.querySelector("#typing-area");
        const sendbtn = document.querySelector("#send-msg-btn");
        var to = form.querySelector("#receiver_id");
        $(document.body).on('click','.m',function(){
            var friend_list_item=$(this).closest('li');
            // console.log(friend_list_item);
            var friend_id=friend_list_item.find('.fusername')[0].dataset.id;
            var btn = $(this);
            to.value = friend_id
            var chat_container = document.querySelector('.chat-area-div');
            chat_container.innerHTML="";
            var chat_div = document.createElement('div');
            // console.log(friend_id);
            $.ajax({
                type:'post',
                url:'../support_php/ajax-view-friend-chat-area.php',
                data:{friend:friend_id},
                success:function(r){
                    // hiddeninput.value = (friend_username);
                    chat_container.appendChild(chat_div);
                    chat_div.innerHTML=r;
                    // console.log(r);
                }
            });
        });    
        sendbtn.addEventListener('click',function(event){
            event.preventDefault();
            var formData = new FormData(form);
            $.ajax({
                type:'post',
                url:'../support_php/ajax-send-chat.php',
                data: formData,
                processData: false, // Important: Prevent jQuery from processing the data
                contentType: false,
                success:function(y){
                    console.log(y);
                }
            });
        });
    </script>
</body>
</html>