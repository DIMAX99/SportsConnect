<?php
include'db.php';
$id=$_POST['id'];
$sql="select id,name from cities where state_id='$id'";
$stmt=$con->prepare($sql);
$stmt->execute();
$arr=$stmt->fetchAll(PDO::FETCH_ASSOC);
$html='';
foreach($arr as $list){
    $html.='<option value='.$list['id'].'>'.$list['name'].'</option>';
}
echo $html;