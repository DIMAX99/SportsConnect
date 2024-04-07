<?php
include"../support_php/db.php";
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="stylesheet" href="../CSS/pl_matches.css">
    <link href="https://fonts.googleapis.com/css2?family=Urbanist:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Oswald:wght@200..700&display=swap" rel="stylesheet">
    <title>Matches</title>
    <style>
        :root {
            --primary-color: #1b2330;
            --font-size: 16px;
            --primary-back-color:rgb(245, 245, 245);
        }
        *{
            margin: 0%;
            padding: 0%;
            font-family: "Oswald", sans-serif;
            /* background-color: var(--primary-back-color); */
        }
        main{
            padding: 5.125em 0 0 0;
        }
        .upcoming_tournaments>#title{
            margin-top: 15px;
            text-align: center;
        }
        .alrdy_regis_btn{
            background-color: green;
        }
        .full-regis-btn{
            background: gray;
        }
        .can-regis{
            padding: 5px;
            margin: 5px;
            background-color: darkred;
        }
    </style>
</head>
<body>
    <?php
    include"../HTML/trial.php";
    ?>
    <main>
        <section class="upcoming_tournaments">
            <h2 id="title">Upcoming Matches</h2>
            <div class="up-tournaments">
                <?php
                $ouput_up_tourna='<ul id="upcoming_tournament_list">';
                $find_tourna="select * from tournament";
                $find_tourna_stmt=$con->prepare($find_tourna);
                $find_tourna_stmt->execute();
                if($find_tourna_stmt->rowCount()>0){
                    while($tournaments=$find_tourna_stmt->fetch(PDO::FETCH_ASSOC)){
                        $if_registered=0;
                        $find_org="select name from organization where id=:id";
                        $find_org_stmt=$con->prepare($find_org);
                        $find_org_stmt->bindParam(":id",$tournaments['org_id']);
                        $find_org_stmt->execute();
                        $org_name=$find_org_stmt->fetch();
                        $find_sport="select sport from sports where id=:sport_id";
                        $find_sport_stmt=$con->prepare($find_sport);
                        $find_sport_stmt->bindParam(":sport_id",$tournaments['sport_id']);
                        $find_sport_stmt->execute();
                        $sport=$find_sport_stmt->fetch();
                        $t_name=$tournaments['name'];
                        $c_l="select count(*) 
                                from teams 
                                where id in (select team_id from registration where tournament_id=:tourna_id) 
                                and
                                leader_id=:current_user_id;";
                        $c_l_stmt=$con->prepare($c_l);
                        $c_l_stmt->bindParam(":tourna_id",$tournaments['id']);
                        $c_l_stmt->bindParam(":current_user_id",$_SESSION['unique_id']);
                        $c_l_stmt->execute();
                        $is_leader=$c_l_stmt->fetchColumn();
                        $c_pl="select player_id 
                                from team_member 
                                where team_id in (select team_id from registration where tournament_id=:tourna_id) 
                                and
                                player_id=:current_user_id;";
                        $c_pl_stmt=$con->prepare($c_pl);
                        $c_pl_stmt->bindParam(":tourna_id",$tournaments['id']);
                        $c_pl_stmt->bindParam(":current_user_id",$_SESSION['unique_id']);
                        $c_pl_stmt->execute();
                        $no_of_reg="select count(*) from registration where tournament_id=:tourna_id";
                        $no_of_reg_stmt=$con->prepare($no_of_reg);
                        $no_of_reg_stmt->bindParam(":tourna_id",$tournaments['id']);
                        $no_of_reg_stmt->execute();
                        $teams_registered=$no_of_reg_stmt->fetchColumn();
                        if($c_pl_stmt->rowCount()>0){
                            $if_registered=1;
                        }

                        $formatted_reg_start_date=date("d/m/y h:i A", strtotime($tournaments['registration_from']));
                        $formatted_reg_end_date=date("d/m/y h:i A", strtotime($tournaments['registration_till']));
                        $formatted_start_date=date("d/m/y h:i A", strtotime($tournaments['start_date']));
                        $formatted_end_date=date("d/m/y h:i A", strtotime($tournaments['end_date']));
                        $ouput_up_tourna.='<li class="tournament_list_item">
                                            <div class="t_name">
                                                <h3>'.$tournaments['name'].'</h3>
                                            </div>
                                            <div class="organization_name">
                                                <h4>Organized By : </h4><p>'.$org_name['name'].'</p>
                                            </div>
                                            <div class="t_regis_start_date">
                                                <p>Registration starts : '.$formatted_reg_start_date.'</p>
                                                <p>Registration Ends : '.$formatted_reg_end_date.'</p>
                                            </div>
                                            <div class="t_start_end_date">
                                                <p>Starts : '.$formatted_start_date.'</p>
                                                <p>Ends : '.$formatted_end_date.'</p>
                                            </div>
                                            <div class="t_sports">
                                                <p>Sports : '.$sport['sport'].'</p>
                                            </div>
                                            <div class="t_teams_regis">
                                                <p>Team size : '.$tournaments['team_size'].'</p>
                                                <p>Total Teams : '.$teams_registered.'/'.$tournaments['total_teams'].'</p>
                                            </div>';
                            if($if_registered==0){
                                if($teams_registered==$tournaments['total_teams']){
                                    $ouput_up_tourna.='<div class="t_register_btns">
                                                        <button class="t-regis-btn full-regis-btn" disabled>Registerations Full</button>
                                                        </div>
                                                    </li>';

                                }else{
                                    $ouput_up_tourna.='<div class="t_register_btns">
                                                        <button class="t-regis-btn" onclick="register(\''. htmlspecialchars($tournaments['name']) .'\')">Register</button>
                                                        </div>
                                                    </li>';
                                }
                            }
                            else{
                                $ouput_up_tourna.='<div class="t_register_btns">
                                                    <button class="t-regis-btn alrdy_regis_btn" disabled>Registered</button>';
                                if($is_leader==1){
                                    $ouput_up_tourna.='<button class ="t-regis-btn can-regis">Cancel Registration</button>
                                                        </div>
                                                    </li>';
                                }
                                else{
                                    $ouput_up_tourna.='</div>
                                    </li>';
                                }
                            }
                                            
                    }
                    $ouput_up_tourna.='</ul>';
                    echo $ouput_up_tourna;
                }
                ?>
            </div>
            
        </section>
    </main>
    <script>
        function register(tour_name){
            var url = "pl_register_tournament.php?name="+encodeURIComponent(tour_name);
            window.location.href=url; 
        }
    </script>
</body>
</html>