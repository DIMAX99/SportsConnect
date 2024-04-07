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
    <title>Find Players</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script src="../Javascript/find.js"></script>
    <link rel="stylesheet" href="../CSS/find_player_pl.css">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
    <link href="https://fonts.googleapis.com/css2?family=Urbanist:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
    <style>
        main{
            padding-top: 5.125em;
        }
    </style>
</head>
<body>
    <?php
    include"../HTML/trial.php";
    ?>
    <main>
        <section class="search_sec">
            <div class="srchcon">
                <span class="material-symbols-outlined">search</span>
                <input type="search" name="player" id="player" placeholder="Search Players....">
            </div>
            <p>OR</p>
            <div class="near-btn-div">
                <button class="near-pl-btn" id="near-srch-btn">Show Players near me</button>
            </div>
        </section>
        <section class="player_details">
            <div id="player_info"></div>
        </section>
    </main>
    <script>
        $(document.body).on('click','.cancel-request-btn',function(){
            var player_list_item=$(this).closest('li');
            var player_username =player_list_item.find('.player_card_con .lower .pusername').text().trim();
            var button = $(this)
            jQuery.ajax({
               type:'post',
               url:'../support_php/ajax-del-friend-req.php',
               data:{player:player_username},
               success:function(rt){
                button.text('Add Friend');
                button.removeClass('cancel-request-btn');
                button.addClass('add-friend-btn');
                console.log(rt);
               } 
            });
        });
        $(document.body).on('click','.add-friend-btn',function(){
            var player_list_item=$(this).closest('li');
            var player_username =player_list_item.find('.player_card_con .lower .pusername').text().trim();
            var button = $(this)
            jQuery.ajax({
               type:'post',
               url:'../support_php/ajax-friend-request.php',
               data:{player:player_username},
               success:function(rt){
                button.text('Cancel Request');
                button.addClass('cancel-request-btn');
                button.removeClass('add-friend-btn');
                console.log(rt);
               } 
            });
        });
        $(document.body).on('click','.go-to-friend',function(){
            window.location.href="Friends.php";
        });
        var near_srch =document.querySelector('#near-srch-btn');
        near_srch.addEventListener('click',function(){
            jQuery.ajax({
               type:'post',
               url:'../support_php/ajax-near-player-filter-search.php',
               success:function(dt){
                jQuery('#player_info').html(dt);
               }
            });
        });
        
</script>
</body>
</html>
