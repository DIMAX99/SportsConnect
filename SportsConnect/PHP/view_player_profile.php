<?php
include("../PHP/db.php");

session_start();
// $_SESSION['logged']='';
if(!isset($_SESSION['logged']) || $_SESSION['logged']!=True){
    header('location:/SportsConnect/PHP/login.php');
    exit();
}
    $sql="select * from player where username=:username";
    $stmt=$con->prepare($sql);
    $stmt->bindParam(':username',$_SESSION['username']);
    $stmt->execute();
    $result=$stmt->fetch(PDO::FETCH_ASSOC);
    // print_r($result);
    $fname=$result['fname'];
    $mname=$result['mname'];
    $lname=$result['lname'];
    $dob=$result['dob'];
    $age=$result['age'];
    $gender=$result['gender'];
    $email=$result['email'];
    $country_id=$result['country_id'];
    $state_id=$result['state_id'];
    $city_id=$result['city_id'];
    $country_code=$result['country_code'];
    $number=$result['phonenumber'];
    $profile_pic=$result['image'];

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../CSS/player_view_profile.css">
    <title>Profile</title>
</head>
<body>
    <nav class="navbar">     
            <div class="logo" id="logo">
                <img src="../Images/logo_only.png" alt="logo" class="logo" name="logo">
                <div class="label">
                    <label for="logo">SPORTSCONNECT</label>
                </div>
            </div>
            <div class="nav2">
                <ul class="navlist" id="navlist">
                    <li><a href="../PHP/home.php">Home</a></li>
                    <li><a href="#">players</a></li>
                    <li><a href="#">matches</a></li> 
                </ul>
            </div>
    </nav>
</body>
</html>