<?php
try{
    $con=new PDO("mysql:host=localhost;dbname=sc","root","");
}catch(PDOException $e){
    echo $e->getMessage();
}