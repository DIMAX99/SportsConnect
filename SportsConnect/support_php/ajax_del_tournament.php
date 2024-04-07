<?php

include"db.php";

$name=$_POST['tournament_name'];
$del_query="delete from tournament where name=:name";
$smtm=$con->prepare($del_query);
$smtm->bindParam(":name",$name);
if($smtm->execute()){
    echo "Deleted successfully";
}
else{
    echo"error";
}