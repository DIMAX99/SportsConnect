<?php
include'../support_php/db.php';

session_start();

if(!isset($_SESSION['logged']) || $_SESSION['logged']!=true){
    header('location:/SportsConnect/Player_php/login.php');
    exit();
}else{
    $sql="select * from player where username=:username";
    $stmt=$con->prepare($sql);
    $stmt->bindParam(':username',$_SESSION['username']);
    $stmt->execute();
    $result=$stmt->fetch(PDO::FETCH_ASSOC);
    // print_r($result);
    $profile_pic=$result['image'];
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/SportsConnect/CSS/pl_home.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Urbanist:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
    <title>Welcome Player</title>
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
                    <li><a href="../Player_php/home.php">Home</a></li>
                    <li><a href="../Player_php/player_find.php">players</a></li>
                    <li><a href="#">matches</a></li> 
                </ul>
                <div class="account_icon" onclick="toggledropdown()">
                    <?php
                    echo '<img src="../Images/uploaded_img/player/'.$profile_pic.'" alt="pic" class="pic" name="profile_pic">';
                    ?>
                    <div id="dropdown" class="dropdown">
                        <!-- <?php
                        // echo '<img src="../Images/uploaded_img/'.$profile_pic.'" alt="pic" class="pic" name="profile_pic">';
                        ?> -->
                        <ul>
                            <li><a href="player_dashboard.php">View Profile</a></li>
                            <li><a href="logout.php">logout</a></li>
                        </ul>
                    </div>
                </div> 
            </div>
            <script>
                function toggledropdown(){
                    var dropdown =document.getElementById('dropdown');
                    if (dropdown.style.display === 'none' || dropdown.style.display === '') {
                        dropdown.style.display = 'block';
                        } 
                        else {
                            dropdown.style.display = 'none';
                        }
                     }
            </script>
            
    </nav>
</body>
</html>