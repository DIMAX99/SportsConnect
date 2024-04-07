<?php
include"../support_php/db.php";

if(empty($result)){
        $error='Incorrect Username';
    }else{
        if(password_verify($password, $result['password'])){
            header('location:../Org_php/org_home.html');
        }else{
            print_r('error');
            $error='Incorrect Password';
        }
    }