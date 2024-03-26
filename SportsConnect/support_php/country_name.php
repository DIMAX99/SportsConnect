<?php
include"../support_php/db.php";
$sql="select name from countries where id=:country_id";
    $stmt=$con->prepare($sql);
    $stmt->bindParam(":country_id",$country_id);
    $stmt->execute();
    $country_name=$stmt->fetch(PDO::FETCH_ASSOC);