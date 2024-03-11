<?php
include"../PHP/db.php";

if(empty($result)){
        $error='Incorrect Username';
    }else{
        if(password_verify($password, $result['password'])){
            header('location:org_home.html');
        }else{
            print_r('error');
            $error='Incorrect Password';
        }
    }