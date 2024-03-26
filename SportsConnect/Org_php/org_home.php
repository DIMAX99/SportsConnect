<?php
include"../support_php/db.php";
session_start();
if(!isset($_SESSION['logged']) || $_SESSION['logged']!=true){
    header('location:org_login.php');
}
else{
$sql="select * from sports";
$stmt= $con->prepare($sql);
$stmt->execute();
$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
// print_r($result);

$sql="select id,name from countries ";
$stmt=$con->prepare($sql);
$stmt->execute();
$arrCountry=$stmt->fetchAll(PDO::FETCH_ASSOC);

if(isset($_COOKIE['message'])){
    // print_r('donr');
    $message=$_COOKIE['message'];
    echo "<script>
            document.addEventListener('DOMContentLoaded', function(){
            var messagediv = document.getElementById('msg');
            messagediv.innerText='$message';
            messagediv.style.display='block';
            setTimeout(function(){
                messagediv.style.display='none';
            },3000)
        });
        </script>";
    setcookie('message','',time()-1800,'/');
    }


// $message="";
if(isset($_POST['submit'])){
    $query_id='select id from organization where username=:username';
    $stmt= $con->prepare($query_id);
    $stmt->bindParam(":username",$_SESSION['username']);
    $stmt->execute();
    $org_id=$stmt->fetch();
    $sql= "insert into tournament(org_id,name,start_date,end_date,registration_from,registration_till,country_id,state_id,city_id,sport_id,team_size,additional_details) values(:org_id,:name,:start_date,:end_date,:registration_from,:registration_till,:country_id,:state_id,:city_id,:sport_id,:team_size,:additional_details);";
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
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@100..900&display=swap" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script src="../Javascript/country.js"></script>
    <script src="../Javascript/del_tourna.js"></script>
    <title>Admin Panel</title>
    <style>
        *{
            padding: 0;
            margin: 0;
            font-family: "Outfit", sans-serif;
        }
        nav{
            background-color: blue;
            height: 50px;
        }
        nav>a{
            color: white;
            text-decoration: none;
            font-size: 20px;
        }
        nav>a:hover{
            color: grey;
        }
        .msg{
            height: 50px;
            display: none;
            border: 2px solid green;
            background-color: rgba(201,242,155,.5);
            position: absolute;
            left: 40%;
            width: 300px;
        }
        .msg>p{
            color: green;
        }
        .sh_tourna_div{
            padding: 10px;
            box-sizing: border-box;
        }
        #tournament_list{
            list-style: none;
        }
        .tourna_list_item{
            margin: 10px;
            border: 4px solid blue;
            border-radius: 12px;
            padding: 10px;
        }
        .edit_delete_btn{
            
        }
        .delete_btn{
            padding: 5px;
            border: 2px solid red;
            background-color: rgba(245,0,0,.5);
            border-radius: 8px;
            cursor: pointer;
        }
    </style>
</head>
<body>
    <nav class="navbar" >
        <a href="#">logout</a>
    </nav>
    <div class="msg" id="msg">
    </div>
    <section class="tournament">
        <h2>Tournaments</h2>
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

    <section class="showtournament">
        <h1>Tournaments</h1>
        <div class="sh_tourna_div">
        <?php
        // print_r($org_id);
        $query="select * from tournament where org_id=(select id from organization where username=:username)";
        $statement=$con->prepare($query);
        $statement->bindParam(":username",$_SESSION['username']);
        $statement->execute();
        // $r=$statement->fetchAll(PDO::FETCH_ASSOC);
        // print_r($r);
        // print_r($tournament);
        if($statement->rowCount()>0){
            $output='<ul id="tournament_list">';
            while($tournament=$statement->fetch(PDO::FETCH_ASSOC)){
                $sql="select sport from sports where id=:sport_id";
                $smt=$con->prepare($sql);
                $smt->bindParam(':sport_id',$tournament['sport_id']);
                $smt->execute();
                $sport_id=$smt->fetch();
                $output.='<li class="tourna_list_item">
                            <div class="tournament_con">
                                <div class="tournament_name">
                                    <h2>Tournament Name : '.$tournament['name'].'</h2>
                                </div>
                                <div class="tournament_sport">
                                    <h3>Sport : '.$sport_id['sport'].'</h3>
                                </div>
                                <div class="tournament_time">
                                    <h3>Start Date : '.$tournament['start_date'].'</h3>
                                    <h3>End Date : '.$tournament['end_date'].'</h3>
                                </div>
                                <div class="registration_date_time">
                                    <h3>Registrations : </h3>
                                    <h3>From : '.$tournament['registration_from'].' - To : '.$tournament['registration_till'].'</h3>
                                </div>
                                <div class="tourna_team_size">
                                    <h3>Team Size : '.$tournament['team_size'].'</h3>
                                </div>
                                <div class="tourna_description">
                                    <h3>Description : </h3>
                                    <p>'.$tournament['additional_details'].'</p>
                                </div>
                                <div class="edit_delete_btn">
                                    <button type="delete" class="delete_btn">Delete</button>
                                </div>
                            </div>
                          </li>';
            }
            $output.='</ul>';
            echo $output;
        }
        ?>
        
        </div>
    </section>
</body>
</html>
