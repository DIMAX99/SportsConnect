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
    <title>Dashboard</title>
</head>
<body>
    <?php
    include"../support_php/profile_template.php";
    ?>
</body>
</html>