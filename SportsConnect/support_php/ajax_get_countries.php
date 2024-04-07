<?php
include"db.php";

    $sql="select id,name from countries ";
    $stmt=$con->prepare($sql);
    $stmt->execute();
    $arrCountry=$stmt->fetchAll(PDO::FETCH_ASSOC);
    $json_country=json_encode($arrCountry);
    echo $json_country;