<?php
include"../support_php/db.php";
session_start();
if(!isset($_SESSION['logged']) || $_SESSION['logged']!=true){
    header('location:org_login.php');
}
if(isset($_COOKIE['cr_message'])){
    // print_r('donr');
    $message=$_COOKIE['cr_message'];
    echo "<script>
            document.addEventListener('DOMContentLoaded', function(){
            var messagediv = document.getElementById('msg');
            messagediv.innerText='$message';
            messagediv.style.display='block';
            setTimeout(function(){
                messagediv.style.display='none';
                messagediv.innerText='';
            },3000)
        });
        </script>";
    setcookie('cr_message','',time()-1800,'org-create-tournament');
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
$msg_error=[];
date_default_timezone_set('UTC');
// $message="";
if(isset($_POST['submit'])){
    if($_POST['country'] ==-1 || $_POST['state']==-1 || $_POST['city']==-1 || $_POST['sport']==-1){
        setcookie('cr_message','Not a Valid Country,State,City or Sport',time()+1800,'org-create-tournament.php');
        header('location:org-create-tournament.php');
    }
    else{
        try{
            $con->beginTransaction();
            $start_time_obj=[];
            foreach($_POST['start_time'] as $start_time){
                $start_time_obj[]=new DateTime($start_time);
            }
            $end_time_obj=[];
            foreach($_POST['end_time'] as $end_time){
                $end_time_obj[]=new DateTime($end_time);
            }
            $regis_start_datetime=new DateTime($_POST['regis_start_date']);
            $regis_end_datetime=new DateTime($_POST['regis_end_date']);
            $tourna_start_datetime=$start_time_obj[0];
            $tourna_end_datetime=end($end_time_obj);
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
                    $smt->bindParam(":start_date",$_POST['start_time'][0]);
                    $smt->bindParam(":end_date",$_POST['end_time'][count($_POST['end_time'])-1]);
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
                        $i=0;
                        $previous;
                        while($i<count($start_time_obj)){
                            $temp=$i+1;
                            $start_date_only=$start_time_obj[$i]->format('Y-m-d');
                            $end_date_only=$end_time_obj[$i]->format('Y-m-d');
                            if(($start_time_obj[$i]<$end_time_obj[$i]) && ($start_date_only===$end_date_only)){
                                if(!isset($previous)){
                                    $day_schedule="insert into tournament_day_schedule(tourna_id,day,day_start_time,day_end_time) values((select id from tournament where name=:name and org_id=:org_id),:day,:start_time,:end_time);";
                                    $day_schedule_stmt=$con->prepare($day_schedule);
                                    $day_schedule_stmt->bindParam(":name",$_POST['tournament_name']);
                                    $day_schedule_stmt->bindParam(":org_id",$org_id['id']);
                                    $day_schedule_stmt->bindParam(":day",$temp);
                                    $day_schedule_stmt->bindParam(":start_time",$_POST['start_time'][$i]);
                                    $day_schedule_stmt->bindParam(":end_time",$_POST['end_time'][$i]);
                                    if($day_schedule_stmt->execute()){
                                        $previous=$end_time_obj[$i];
                                    }
                                    else{
                                        $con->rollBack();
                                        setcookie('cr_message','error 1',time()+1800,'org-create-tournament.php');
                                        header('location:org-create-tournament.php');
                                        break;
                                    }
                                }
                                else{
                                    $previous->setTime(0,0,0)->modify('+1 day');
                                    if($start_time_obj[$i]>$previous){
                                        $day_schedule="insert into tournament_day_schedule(tourna_id,day,day_start_time,day_end_time) values((select id from tournament where name=:name and org_id=:org_id),:day,:start_time,:end_time);";
                                        $day_schedule_stmt=$con->prepare($day_schedule);
                                        $day_schedule_stmt->bindParam(":name",$_POST['tournament_name']);
                                        $day_schedule_stmt->bindParam(":org_id",$org_id['id']);
                                        $day_schedule_stmt->bindParam(":day",$temp);
                                        $day_schedule_stmt->bindParam(":start_time",$_POST['start_time'][$i]);
                                        $day_schedule_stmt->bindParam(":end_time",$_POST['end_time'][$i]);
                                        if($day_schedule_stmt->execute()){
                                            $previous=$start_time_obj[$i];
                                        }
                                        else{
                                            $con->rollBack();
                                            setcookie('cr_message','error 2',time()+1800,'org-create-tournament.php');
                                            header('location:org-create-tournament.php');
                                            break;
                                        }
                                    }
                                    else{
                                        $con->rollBack();
                                        setcookie('cr_message','start date of all day should be after end date of previous day end date',time()+1800,'org-create-tournament.php');
                                        header('location:org-create-tournament.php');
                                        break;
                                    }
                                }
                            }
                            else{
                                // print_r('error');
                                $con->rollBack();
                                setcookie('cr_message','Start date and end date Should be on same day',time()+1800,'org-create-tournament.php');
                                header('location:org-create-tournament.php');
                                break;
                                // setcookie('message','only one tournament per day',time()+1800,'/');
                                // header('location:org_home.php');
                            }
                            $i=$i+1;
                        }   
                    }
                    else{
                        $con->rollBack();
                        setcookie('message','Tournament Creation Failed',time()+1800,'/');
                        header('location:org_home.php');
                        exit; 
                        // $message= "Error creating tournament";
                    }
                }
                else{
                    $con->rollBack();
                    setcookie('cr_message','Registration end datetime cannot be before Registration start datetime or same as Registration start datetime',time()+1800,'org-create-tournament.php');
                    header('location:org-create-tournament.php');
                    
                }
            } else{
                $con->rollBack();
                setcookie('cr_message','Registration date should should be earlier than start date or cannot be current datetime',time()+1800,'org-create-tournament.php');
                header('location:org-create-tournament.php');
                
            }
            $con->commit();
            setcookie('message','Tournament Created Successfully',time()+1800,'/');
            header('location:org_home.php');
            exit;
    }catch(PDOException $e){
        $msg_pdo_error[]=$e;
    }
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
    <style>
        #day_schedule_input{
            display: flex;
            flex-direction: column;
        }
        #msg_error_div>p{
            color: red;
        }
    </style>
</head>
<body>
    <div class="msg" id="msg">
    </div>
    <section class="tournament">
        <h2>Create Tournaments</h2>
        <form action="" method="post">
            <div class="name">
                <label for="tournament_name">Name : </label>
                <input type="text" name="tournament_name" id="tournament_name" required>
            </div>
            <div class="csc">
                <label for="country">Country : </label>
                <select name="country" id="country" required>
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
                <select name="state" id="state" required>
                <option value="-1">Select State</option>
                </select>
                <label for="city">City : </label>          
                <select name="city" id="city" required>
                <option value="-1">Select City</option>
                </select>
            </div>
            <div class="location">
                <label for="address">Location : </label>
                <input type="text" name="address" id="address" required>
            </div>
            
            <div class="sport_detail">
                <label for="sport">Sport :</label>
                <select name="sport" id="sport" required>
                    <option value="-1">select sport</option>
                    <?php
                        foreach ($result as $row) {
                            echo '<option value="'.$row['id'].'">'.$row['sport'].'</option>';
                        }
                    ?>
                </select>
                <label for="team_size">Team Size : </label>
                <input type="number" name="team_size" id="team_size" min="1" max="50" required>
            </div>
            <div class="team_size">
                <label for="total_teams">No of Teams : </label>
                <input type="number" name="total_teams" id="total_teams" min="1" required>
            </div>
            <div class="regis_date_div">
                <label for="regis_start_date">Registration Start Date : </label>
                <input type="datetime-local" name="regis_start_date" id="regis_start_date" required><br> 
                <label for="regis_end_date">Registration End Date : </label>
                <input type="datetime-local" name="regis_end_date" id="regis_end_date" required>
            </div>
            <div class="day_schedule">
                <label for="days">Total days :</label>
                <input type="number" name="days" id="days" max=30 min=1 value=1 required>
                <div id="day_schedule_input"></div>
            </div>
            
            <div class="other">
                <label for="description">Description : </label>
                <input type="text" name="description" id="description" style="height:100px width=100%;" required>
            </div>
            <div class="btn">
                <button type="submit" name="submit" onclick="showmessage()">Create Tournament</button>
            </div>
        </form>
    </section>
    <script>

        var dayinput=document.getElementById('days');
        dayinput.addEventListener('input',function(){
            if(dayinput.value>30){
                dayinput.value=30;
            }
            var noofday=parseInt(dayinput.value);
            var days_container=document.getElementById('day_schedule_input');
            days_container.innerHTML="";
            var i=1;
            while(i<=noofday){
                var input_container=document.createElement('div');
                input_container.setAttribute('class','days_input_container');

                var start_time_container=document.createElement('div');
                input_container.setAttribute('class','start_input_container');

                var end_time_container=document.createElement('div');
                input_container.setAttribute('class','end_input_container');

                var day_heading=document.createElement('p');
                day_heading.setAttribute('class','days_schedule');
                day_heading.textContent='Day'+' '+i;

                var day_start_label=document.createElement('label');
                day_start_label.setAttribute('for','start_days_schedule');
                day_start_label.textContent='Start Time :';

                var schedule_start_input=document.createElement('input');
                schedule_start_input.setAttribute('type','datetime-local');
                schedule_start_input.setAttribute('class','start_days_schedule');
                schedule_start_input.setAttribute('name','start_time[]');
                schedule_start_input.required=true;

                var day_end_label=document.createElement('label');
                day_end_label.setAttribute('for','end_days_schedule');
                day_end_label.textContent='End Time : ';

                var schedule_end_input=document.createElement('input');
                schedule_end_input.setAttribute('type','datetime-local');
                schedule_end_input.setAttribute('class','end_days_schedule');
                schedule_end_input.setAttribute('name','end_time[]');
                schedule_end_input.required=true;

                days_container.appendChild(input_container);
                input_container.appendChild(day_heading);
                input_container.appendChild(start_time_container);
                start_time_container.appendChild(day_start_label);
                start_time_container.appendChild(schedule_start_input);
                input_container.appendChild(end_time_container);
                end_time_container.appendChild(day_end_label);
                end_time_container.appendChild(schedule_end_input);
                i++;
            }
        });
    </script>
</body>

</html>