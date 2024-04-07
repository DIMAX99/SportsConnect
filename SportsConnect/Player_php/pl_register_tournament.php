<?php
include"../support_php/db.php";
session_start();
$tournament_name=$_GET['name'];
$q="select * from tournament where name=:t_name";
        $stmt=$con->prepare($q);
        $stmt->bindParam(":t_name",$tournament_name);
        $stmt->execute();
        $d=$stmt->fetch(PDO::FETCH_ASSOC);
$sql="select id,name from countries ";
$stmt=$con->prepare($sql);
$stmt->execute();
$arrCountry=$stmt->fetchAll(PDO::FETCH_ASSOC);

if(isset($_POST['submit'])){
    $cr_team="insert into teams (name,leader_id,country_id,state_id,city_id) values(:t_name,:leader_id,:country_id,:state_id,:city_id);";
    $crstmt=$con->prepare($cr_team);
    $crstmt->bindParam(":t_name",$_POST['team_name']);
    $crstmt->bindParam(":leader_id",$_POST['leader_id']);
    $crstmt->bindParam(":country_id",$_POST['country']);
    $crstmt->bindParam(":state_id",$_POST['state']);
    $crstmt->bindParam(":city_id",$_POST['city']);
    $crstmt->execute();
    try{
        $con->beginTransaction();
        foreach($_POST['player_id'] as $id){
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
         $con->commit();
    }catch(PDOException $e){
        echo $e;
    }

    $registration="insert into registration (tournament_id,team_id) values((select id from tournament where name=:t_name),(select id from teams where name=:name));";
    $smt=$con->prepare($registration);
    $smt->bindParam(":t_name",$tournament_name);
    $smt->bindParam(":name",$_POST['team_name']);
    try{
        $smt->execute();
        echo 'registration done';
    }catch(PDOException $e){
        $msg='Registration failed';
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
    </style>
</head>
<body>
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