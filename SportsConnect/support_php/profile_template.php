<?php
include"../support_php/db.php";

    $sql="select * from player where username=:username";
    $stmt=$con->prepare($sql);
    $stmt->bindParam(':username',$_SESSION['username']);
    $stmt->execute();
    $result=$stmt->fetch(PDO::FETCH_ASSOC);
    // print_r($result);
    $profile_pic=$result['image'];

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../CSS/pl_dashboard.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0" />
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0" />
    <title>Profile_template</title>
</head>
<body>
    <nav class="nav_bar">
        <span class="menu_bar" id="menu_bar_icon" onclick="menuopen()"><img src="../Images/menubaricon.svg" alt="menu" class="menu_icon"></span>
        <div class="profile_photo">
            <a href="../Player_php/home.php">Home</a>
            <?php
            echo '<img id="profile_pic" src="../Images/uploaded_img/player/'.$result['image'].'">';
            ?>      
        </div>
    </nav>
    <div class="side_nav_bar" id="side_nav_bar">
        <ul class="side_nav_bar_menu_list">
            <span class="material-symbols-outlined" id="menuclose" onclick="menuclose()">close</span>
            <li>
                <span class="material-symbols-outlined icon" id="dash_icon" >home</span>
                <a href="../Player_php/player_dashboard.php">DashBoard</a>
            </li>
            <li>
                <span class="material-symbols-outlined icon">person_edit</span>    
                <a href="../Player_php/edit_profile_pl.php">Edit Profile</a>
            </li>
            <!-- <li>
                <span class="material-symbols-outlined icon">flip_camera_ios</span>
                <a href="../Player_php/player_pass_reset.php">Change Profile Pic</a>
            </li> -->
        </ul>
    </div>
    <script>
        function menuopen(){
            document.getElementById('side_nav_bar').style.width= "250px";
        }
        function menuclose(){
            document.getElementById('side_nav_bar').style.width="0px";
        }
    </script>
</body>
</html>
    
    