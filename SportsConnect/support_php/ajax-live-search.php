<?php
include"../support_php/db.php";
session_start();
$search_value = $_POST["search"];
$srch='%'.$search_value.'%';

$sql="select * from player where username like :s and username!=:current_username";
$stmt= $con->prepare($sql);
$stmt->bindparam(":s",$srch);
$stmt->bindParam(":current_username",$_SESSION['username']);
$stmt->execute();
$id=$_SESSION['id'];
$output="";
if($stmt->rowCount()>0){
    $output='<ul class="player_list" id="player_list">';
            while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
                $query="select countries.name as country_name,states.name as state_name,cities.name as city_name from cities 
                                        join states on cities.state_id=states.id 
                                        join countries on states.country_id=countries.id 
                                        where cities.id=:city_id";
                $smt=$con->prepare($query);
                $smt->bindParam(":city_id",$row['city_id']);
                $smt->execute();
                $result=$smt->fetch(PDO::FETCH_ASSOC);
                
                // break;
                // print_r($_SESSION['id']);
                $output.='<li class=player_list_item>
                            <div class="player_card_con">
                                <div class="upper">
                                    <img id="player_profile_pic"  src="/SportsConnect/Images/uploaded_img/player/'.$row['image'].'"alt="dp">
                                </div>
                                <div class="lower">
                                    <p id="pn" class="pusername">'.$row['username'].'</p>
                                    <p id="pn_add">'.$result['country_name'].' , '.$result['state_name'].' , '.$result['city_name'].'</p>
                                </div>
                                <div class="view-addfriend-button-div">
                                    <div>
                                        <span class="material-symbols-outlined">person</span>
                                        <button class="view-player-profile-btn">View Profile</button>
                                    </div>';

                $check_pending_request="select status from friend_request where sender_id=:sender and reciever_id=:receiver";
                $check_p_r=$con->prepare($check_pending_request);
                $check_p_r->bindParam(":sender",$id);
                $check_p_r->bindParam(":receiver",$row['id']);
                $check_p_r->execute();

                if($check_p_r->rowCount()>0){
                    $c=$check_p_r->fetch();
                    if($c['status']==0){
                        $output.=           '<div class="btn-div">
                                            <span class="material-symbols-outlined">person_cancel</span>
                                            <button class="cancel-request-btn btn">Cancel Request</button>
                                            </div>
                                        </div>
                                    </div>
                                </li>';
                    }
                    else{
                        $output.=           '<div class="btn-div">
                                            <span class="material-symbols-outlined">person_add</span>
                                            <button class="message-friend-btn btn">Message</button>
                                            </div>
                                        </div>
                                    </div>
                                </li>';
                    }
                }
                else{
                    $output.='<div class="btn-div">
                                        <span class="material-symbols-outlined">person_add</span>
                                        <button class="add-friend-btn btn">Add Friend</button>
                                        </div>
                                    </div>
                                </div>
                            </li>';
                }
                                        
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
