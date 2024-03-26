<?php
include"db.php";
$sql="select name from states where id=:state_id";
    $stmt=$con->prepare($sql);
    $stmt->bindParam(":state_id",$state_id);
    $stmt->execute();
    $state_name=$stmt->fetch(PDO::FETCH_ASSOC);