<?php
include"db.php";

$id=$_POST['id'];
$sql="select id,name from states where country_id=:id ";
    $stmt=$con->prepare($sql);
    $stmt->bindparam(":id",$id);
    $stmt->execute();
    $arrstate=$stmt->fetchAll(PDO::FETCH_ASSOC);
    $json_state=json_encode($arrstate);
    echo $json_state;
?>