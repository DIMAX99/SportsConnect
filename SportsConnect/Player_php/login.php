<?php
include("../support_php/db.php");

session_start();
// $_SESSION['logged']='';
if(isset($_SESSION['logged'])){
    header('location:/SportsConnect/Player_php/home.php');
    exit;
}else{


if(isset($_POST['submit'])){
    $username=$_POST['username'];
    $password=$_POST['pass'];
    $validpass=password_hash($password,PASSWORD_DEFAULT);

    $sql="select id,password from player where username=:username";
    $stmt=$con->prepare($sql);
    $stmt->bindParam(':username',$username);   
    $stmt->execute();
    $detail=$stmt->fetch(PDO::FETCH_ASSOC);
    
    if(empty($detail)){
        $error='Incorrect Username';
    }else{
       if(password_verify($password, $detail['password'])){
        session_start();
        $_SESSION['username']=$username;
        $_SESSION['id']=$detail['id'];
        $_SESSION['logged']=true;
        header('location:../Player_php/home.php');
        exit;
        }
    else{
        $error='Incorrect Password';
    } 
    }
}
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../CSS/log.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Sora:wght@100..800&display=swap" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Urbanist:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
    <title>Document</title>
</head>
<body>
    <!-- <img src="../Images/background.jpg" alt="img" class="bkg" id="bkg"> -->
    <div class="container" id="container">
        <form id="regform" action="" method="post" enctype="multipart/form-data">
            <h2 id="title" >LOGIN</h2>
            <?php
            if(!empty($error) && isset($error)){
                echo '<div id="error"><h4>'.$error.'</h4></div>';
            }   
            ?>
            <div class="logdet1">
            <label for="username">Username : </label>
            <input type="text" name="username" id="username" placeholder="Username"> 
            </div>
            <div class="logdet2">
            <label for="pass">Password : </label>
            <input type="password" name="pass" id="pass" placeholder="Password">
            </div> 
            <div class="logbtn">
                <button type="submit" name="submit" id="submit" >LOGIN</button>
            </div> 
            <div class="reg">
                <p>Don't have an Account? <a id="link" href="player_reg.php">Register Now</a></p>
            </div>
    </div>
</body>
</html>