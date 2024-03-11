<?php
include"db.php";
$id=$_POST['id'];
$sql="select phonecode from countries where id='$id'";
$stmt=$con->prepare($sql);
$stmt->execute();
$code=$stmt->fetch(PDO::FETCH_ASSOC);
echo htmlspecialchars($code['phonecode']);