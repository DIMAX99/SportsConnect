<?php
include"../support_php/db.php";
session_start();
if(!isset($_SESSION['logged']) || $_SESSION['logged']!=true){
    header('location:org_login.php');
}
$sql="select * from sports";
$stmt= $con->prepare($sql);
$stmt->execute();
$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
// print_r($result);

$sql="select id,name from countries ";
$stmt=$con->prepare($sql);
$stmt->execute();
$arrCountry=$stmt->fetchAll(PDO::FETCH_ASSOC);


// $message="";
if(isset($_POST['submit'])){
    $regis_start_datetime=new DateTime($_POST['regis_start_date']);
    $regis_end_datetime=new DateTime($_POST['regis_end_date']);
    $tourna_start_datetime=new DateTime($_POST['start_date']);
    $tourna_end_datetime=new DateTime($_POST['end_date']);
    $currentDatetime=new DateTime();
    if(($regis_start_datetime<$tourna_start_datetime && $regis_start_datetime>$currentDatetime)&&($regis_end_datetime<$tourna_start_datetime)){
        if($regis_start_datetime<$regis_end_datetime){
            $query_id='select id from organization where username=:username';
            $stmt= $con->prepare($query_id);
            $stmt->bindParam(":username",$_SESSION['username']);
            $stmt->execute();
            $org_id=$stmt->fetch();
            $sql= "insert into tournament(org_id,name,start_date,end_date,registration_from,registration_till,country_id,state_id,city_id,sport_id,team_size,total_teams,additional_details) values(:org_id,:name,:start_date,:end_date,:registration_from,:registration_till,:country_id,:state_id,:city_id,:sport_id,:team_size,:total_teams,:additional_details);";
            $smt = $con->prepare($sql); 
            $smt->bindParam(":org_id",$org_id['id']);
            $smt->bindParam(":name",$_POST['tournament_name']);
            $smt->bindParam(":start_date",$_POST['start_date']);
            $smt->bindParam(":end_date",$_POST['end_date']);
            $smt->bindParam(":registration_from",$_POST['regis_start_date']);
            $smt->bindParam(":registration_till",$_POST['regis_end_date']);
            $smt->bindParam(":country_id",$_POST['country']);
            $smt->bindParam(":state_id",$_POST['state']);
            $smt->bindParam(":city_id",$_POST['city']);
            $smt->bindParam(":sport_id",$_POST['sport']);
            $smt->bindParam(":team_size",$_POST['team_size']);
            $smt->bindParam(":total_teams",$_POST['total_teams']);
            $smt->bindParam(":additional_details",$_POST['description']);
            if($smt->execute()){
                // $message="Tournament Created Successfully";
                setcookie('message','Tournament Created Successfully',time()+1800,'/');
                header('location:org_home.php');
                exit;
            }
            else{
                // $message= "Error creating tournament";
            }
        }
    } else{
        setcookie('message','Registeration date should be earlier than Tournament start_date',time()+1800,'/');
        header('location:org_home.php');
        exit;
    }
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@100..900&display=swap" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <script src="../Javascript/country.js"></script>
    <script src="../Javascript/del_tourna.js"></script>
    <title>Create Tournament</title>
</head>
<body>
    <section class="tournament">
        <h2>Create Tournaments</h2>
        <form action="" method="post">
            <div class="name">
                <label for="tournament_name">Name : </label>
                <input type="text" name="tournament_name" id="tournament_name">
            </div>
            <div class="time">
                <label for="start_date">Start Date : </label>
                <input type="datetime-local" name="start_date" id="start_date">
                <label for="end_date">End Date : </label>
                <input type="datetime-local" name="end_date" id="end_date">
            </div>
            <div class="csc">
                <label for="country">Country : </label>
                <select name="country" id="country">
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
                <select name="state" id="state"></select>
                <label for="city">City : </label>          
                <select name="city" id="city"></select>
            </div>
            <div class="location">
                <label for="address">Location : </label>
                <input type="text" name="address" id="address">
            </div>
            
            <div class="sport_detail">
                <label for="sport">Sport :</label>
                <select name="sport" id="sport">
                    <option value="-1">select sport</option>
                    <?php
                        foreach ($result as $row) {
                            echo '<option value="'.$row['id'].'">'.$row['sport'].'</option>';
                        }
                    ?>
                </select>
                <label for="team_size">Team Size : </label>
                <input type="number" name="team_size" id="team_size" min="1" max="50">
            </div>
            <div class="team_size">
                <label for="total_teams">No of Teams : </label>
                <input type="number" name="total_teams" id="total_teams" min="1">
            </div>
            <div class="regis_date_div">
                <label for="regis_start_date">Registration Start Date : </label>
                <input type="datetime-local" name="regis_start_date" id="regis_start_date"><br> 
                <label for="regis_end_date">Registration End Date : </label>
                <input type="datetime-local" name="regis_end_date" id="regis_end_date">
            </div>
            <div class="other">
                <label for="description">Description : </label>
                <input type="text" name="description" id="description" style="height:100px width=100%; ">
            </div>
            <div class="btn">
                <button type="submit" name="submit" onclick="showmessage()">Create Tournament</button>
            </div>
        </form>
    </section>
</body>
</html>