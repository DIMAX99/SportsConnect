<?php
include'../support_php/db.php';

session_start();

if(!isset($_SESSION['logged']) || $_SESSION['logged']!=true){
    header('location:/SportsConnect/Player_php/login.php');
    exit();
}else{
    
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/SportsConnect/CSS/pl_home.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Urbanist:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Oswald:wght@200..700&display=swap" rel="stylesheet">
    <title>Welcome Player</title>
    <style>
        main{
            padding: 5.125em 0 0 0;
        }
    </style>
</head>
<body>
    <?php
    include"../HTML/trial.php";
    ?>
    <main>
        <h3>Registered Tournaments</h3>
    </main>
</body>
</html>