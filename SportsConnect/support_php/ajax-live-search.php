<?php
include"../support_php/db.php";

$search_value = $_POST["search"];
$srch='%'.$search_value.'%';

$sql="select * from player where username like :s";
$stmt= $con->prepare($sql);
$stmt->bindparam(":s",$srch);
$stmt->execute();

$output="";
if($stmt->rowCount()>0){
    $output='<ul class="player_list" id="player_list">';
            while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
                // print_r($row);
                $query="select countries.name as country_name,states.name as state_name,cities.name as city_name from cities 
                                        join states on cities.state_id=states.id 
                                        join countries on states.country_id=countries.id 
                                        where cities.id=:city_id";
                $smt=$con->prepare($query);
                $smt->bindParam(":city_id",$row['city_id']);
                $smt->execute();
                $result=$smt->fetch(PDO::FETCH_ASSOC);
                // print_r($result);
                // break;
                $output.='<li class=player_list_item>
                            <div class="player_card_con">
                                <div class="upper">
                                    <img id="player_profile_pic"  src="/SportsConnect/Images/uploaded_img/player/'.$row['image'].'"alt="dp">
                                </div>
                                <div class="lower">
                                    <p id="pn">'.$row['username'].'</p>
                                    <p id="pn_add">'.$result['country_name'].' , '.$result['state_name'].' , '.$result['city_name'].'</p>
                                </div>
                            </div>
                        </li>';
            }
    $output.= '</ul>';
    echo $output;
}
else{
    $output='<ul class="player_list" id="player_list">
            <li>No Player Found</li>
            </ul>';
    echo $output;
}
