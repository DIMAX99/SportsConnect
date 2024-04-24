<?php
include"../support_php/db.php";
session_start();
$tournament_name=$_GET['name'];
$q="select * from tournament where name=:t_name";
        $stmt=$con->prepare($q);
        $stmt->bindParam(":t_name",$tournament_name);
        $stmt->execute();
        $d=$stmt->fetch(PDO::FETCH_ASSOC);

$get_new_day="select * 
        from tournament_day_schedule
        where 
        tourna_id=:tourna_id";
$get_new_day_stmt=$con->prepare($get_new_day);
$get_new_day_stmt->bindParam(":tourna_id",$d['id']);
$get_new_day_stmt->execute();

$sql="select id,name from countries ";
$stmt=$con->prepare($sql);
$stmt->execute();
$arrCountry=$stmt->fetchAll(PDO::FETCH_ASSOC);


if(isset($_POST['submit'])){
    try{
        $con->beginTransaction();
        $cr_team="insert into teams (name,leader_id,country_id,state_id,city_id) values(:t_name,:leader_id,:country_id,:state_id,:city_id);";
        $crstmt=$con->prepare($cr_team);
        $crstmt->bindParam(":t_name",$_POST['team_name']);
        $crstmt->bindParam(":leader_id",$_POST['leader_id']);
        $crstmt->bindParam(":country_id",$_POST['country']);
        $crstmt->bindParam(":state_id",$_POST['state']);
        $crstmt->bindParam(":city_id",$_POST['city']);
        $crstmt->execute();
        foreach($_POST['player_id'] as $id)
        {   
            $total_conflict=0;
            $query="select t.tournament_id 
                    from registration t,team_member tm 
                    where t.team_id=tm.team_id and tm.player_id=:player_id";
            $querycheck=$con->prepare($query);
            $querycheck->bindParam(":player_id",$id);
            $querycheck->execute();
            while($reg=$querycheck->fetch(PDO::FETCH_ASSOC)){
                $get_day="select * 
                        from tournament_day_schedule
                        where 
                        tourna_id=:tourna_id";
                $get_day_stmt=$con->prepare($get_day);
                $get_day_stmt->bindParam(":tourna_id",$reg['tournament_id']);
                $get_day_stmt->execute();
                while($day=$get_day_stmt->fetch(PDO::FETCH_ASSOC)){
                    $day_obj=new DateTime($day['day_start_time']);
                    $day_date=$day_obj->format('Y-m-d');
                    $old_start_time=date('H:i:s',strtotime($day['day_start_time']));
                    $old_end_time=date('H:i:s',strtotime($day['day_end_time']));
                    while($new_day=$get_new_day_stmt->fetch(PDO::FETCH_ASSOC)){
                        $new_day_obj=new DateTime($day['day_start_time']);
                        $new_day_date=$new_day_obj->format('Y-m-d');
                        $new_start_time=date('H:i:s',strtotime($new_day['day_start_time']));
                        $new_end_time=date('H:i:s',strtotime($new_day['day_end_time']));
                        if($day_date==$new_day_date){
                            if(!(($new_end_time<$old_start_time)&&($new_start_time>$old_end_time))){
                                $total_conflict=$total_conflict+1;
                            }
                        }
                    }
                }
            }
            if($total_conflict==0){
            $t_pl="INSERT INTO team_member (player_id, team_id)
            values (:player_id, (SELECT id FROM teams WHERE name = :t_name AND EXISTS (SELECT 1 FROM player WHERE unique_id = :player_id)));";
            $t_pl_stmt=$con->prepare($t_pl);
            $t_pl_stmt->bindParam(":player_id",$id);
            $t_pl_stmt->bindParam(":t_name",$_POST['team_name']);
            if(!$t_pl_stmt->execute()){
                $con->rollBack();
                break;
            }  
            } 
            else{
                $con->rollBack();
                setcookie("player_error","Timing Conflicts of some player",time()+60,"player_matches.php");
                break;
            }
         }
         $registration="insert into registration (tournament_id,team_id) values((select id from tournament where name=:t_name),(select id from teams where name=:name));";
        $smt=$con->prepare($registration);
        $smt->bindParam(":t_name",$tournament_name);
        $smt->bindParam(":name",$_POST['team_name']);
        if($smt->execute()){
            $con->commit();
            setcookie("player_error","Registration Successfull",time()+60,"player_matches.php");
            header("location:player_matches.php");
        }
        else{
            $con->rollBack();
            setcookie("player_error","Registration Failed",time()+60,"player_matches.php");
            header("location:player_matches.php");
        }
    }catch(PDOException $e){
        setcookie("player_error","Incorrect Player id OR Failed to register",time()+60,"player_matches.php");
    }
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script src="../Javascript/country.js"></script>
    <title>Register</title>
    <style>
        *{
            padding: 0;
            margin: 0;
        }
        .team_player_id{
            display: flex;
            flex-direction: column;
        }
        .team_player_id>input{
            width: 150px;
            margin: 10px;
        }
        #msg_error_div>p{
            color: red;
        }
    </style>
</head>
<body>
    <div id="msg_error_div">
        <?php
        if(isset($msg_error)){
            echo '<p>'.$message.'</p>';
        }
        ?>
    </div>
    <h1>Registeration</h1>
    <section class="tourna-det">
        <div class="tournament">
            <div class="t-name-div">
                <h2><?php echo $d['name'];?></h2>
            </div>
            <div class="org-name-div">
                <?php
                $o="select name from organization where id=:org_id";
                $st=$con->prepare($o);
                $st->bindParam(":org_id",$d['org_id']);
                $st->execute();
                $org_name=$st->fetch();
                echo '<h4>'.$org_name['name'].'</h4>';
                ?>
            </div>
        </div>
    </section>
    <section class="reg-sec">
        <form id="tourna-reg-form" method="POST" enctype="multipart/form-data">
            <div>
                <label for="team_name">Team Name : </label>
                <input type="text" id="team_name" name="team_name">
            </div>
            <div class="address" id="address">
                <label for="country">Country : </label>
            <select id="country" name="country">
                <option value="-1">Select Country</option>
                <?php
                foreach($arrCountry as $country){
                    ?>
                    <option value="<?php echo $country['id']?>"><?php echo $country['name']?></option>
                    <?php
                }
                ?>
            </select>
            <label for="state">State : </label>
            <select id="state" name="state">
                <option value="-1">Select State</option>
            </select>
            <label for="city">City : </label>
            <select id="city" name="city">
                <option value="-1">Select City</option>
            </select>
            </div>
            <div class="dp">
                <label for="image">Upload Team Logo : </label>
                <input type="file" name="image" id="image" accept="image/jpg,image/jpeg,image/png"/>
            </div>
            <?php
            $i=1;
            $input='<div class="team_player_id">
                    <small>Note : player id is available in profile section of each player</small>
                    <ul>
                    <li><label for="leader_id">Captain id : </label>
                    <input id="leader_id" name="leader_id" required></li>';
            while($i<=$d['team_size']){
                $input.='<li><label for="player '.$i.'">Player '.$i.' id : </label>
                        <input id="player '.$i.'" name="player_id[]" required></li>';
                $i=$i+1;
            }
            $input.='</ul></div>';
            echo $input;
            ?>
            <div class="submit-team-btn">
                <button type="submit" name="submit">Register</button>
            </div>
        </form>
    </section>
    
</body>
</html>