<?php
include"db.php";
$id=$_POST['id'];
$sql="select phonecode from countries where id='$id'";
$stmt=$con->prepare($sql);
$stmt->execute();
$code=$stmt->fetch(PDO::FETCH_ASSOC);
$c=htmlspecialchars($code['phonecode']);
$json_code=json_encode($c);
echo $json_code;