<?php
include"db.php";

$id=$_POST['id'];
$sql="select id,name from cities where state_id=:id ";
    $stmt=$con->prepare($sql);
    $stmt->bindparam(":id",$id);
    $stmt->execute();
    $arrcity=$stmt->fetchAll(PDO::FETCH_ASSOC);
    $json_city=json_encode($arrcity);
    echo $json_city;