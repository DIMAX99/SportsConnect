<?php
include"../support_php/db.php";

if(isset($_POST['submit'])){
    $username=$_POST['username'];
    $password=$_POST['pass'];

    $sql="select password from organization where username=:username";
    $stmt=$con->prepare($sql);
    $stmt->bindParam(':username',$username);
    $stmt->execute();
    $detail=$stmt->fetch(PDO::FETCH_ASSOC);

    // print_r($password);
    // print_r($result['password']);
    // print_r(password_verify($password,$result['password']));
    if(empty($detail)){
        $error='Incorrect Username';
    }else{
        if(password_verify($password, $detail['password'])){
            session_start();
            $_SESSION['username']=$username;
            $_SESSION['logged']=true;
            header('location:org_home.php');
            exit();
        }else{
            $error='Incorrect Password';
        }
    }
    // include'../PHP/check.php';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/SportsConnect/CSS/org_log.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Urbanist:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
    <title>Login</title>
</head>
<body>
    <div class="container">
            <form id="log_form" action="" method="post">
                <div class="title">
                    <h2>Login</h2>
                    <?php 
                    if(!empty($error) && isset($error) ){
                        echo '<div class="show"><h4>'.$error.'</h4></div>';
                    }
                ?>
                </div>
                <div class="user log_det">
                   <label for="username">Username : </label>
                <input type="text" name="username" id="username" placeholder="Username" required> 
                </div>
                <div class="user_pass log_det">
                  <label for="pass">Password : </label>
                <input type="password" name="pass" id="pass" placeholder="Password" required>  
                </div> 
                <div class="logbtn">
                    <button type="submit" name="submit" id="submit" >LOGIN</button>
                </div> 
                <div class="reg">
                    <p>Don't have an Account? <a href="/SportsConnect/Org_php/organizer_reg.php">Register Now</a></p>
                </div>
            </form>
    </div>
</body>
</html>