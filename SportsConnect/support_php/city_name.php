<?php
include"../support_php/db.php";
$sql="select name from cities where id=:city_id";
    $stmt=$con->prepare($sql);
    $stmt->bindParam(":city_id",$city_id);
    $stmt->execute();
    $city_name=$stmt->fetch(PDO::FETCH_ASSOC);