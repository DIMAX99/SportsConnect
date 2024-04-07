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
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Urbanist:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
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
            height: max-content;
            display:flex;
            width: 97%;
            overflow-x: scroll;
            scrollbar-width: none;
        }
        .tourna_list_item{
            margin: 10px;
            border: 4px solid blue;
            border-radius: 12px;
            padding: 10px;
        }
        .edit_delete_btn{
            
        }
        /* .delete_btn{
            padding: 5px;
            border: 2px solid red;
            background-color: rgba(245,0,0,.5);
            border-radius: 8px;
            cursor: pointer;
        } */
        main{
            padding: 5.125em 0 0 0;
            overflow: hidden;
        }
        .showtournament{
            height: 400px;
            width: 100%;
            padding: 20px;
            background-color: lavender;
        }
        .create-tourna-btns{
            display: flex;
            justify-content: center;
            align-items: center;
            margin-top: 20px;
            margin-right: 20px;
        }
        .tourna-btns{
            padding: 10px;
            font-size: 20px;
            background-color: #1b2330;
            color: red;
            transition: 0.5s;
            border-radius: 8px;
            outline: none;
            border: 3px solid red;
        }
        .tourna-btns:hover{
            background-color: red;
            color: white;
            border-color: #1b2330;
            cursor: pointer;
        }
        #create-tourna-section{
            display: flex;
            align-items: center;
            justify-content: center;
            
        }
        .tourna-manage-btn{
            padding: 5px;
            color: aliceblue;
            background-color: #1b2330;
            border: 2px solid aliceblue;
            border-radius: 5px;
        }
        .tourna-manage-btn:hover{
            cursor: pointer;
        }
    </style>
</head>
<body>
    <?php
    include"org_template_navbar.html";
    ?>
    <main>
    <div class="msg" id="msg">
    </div>
    <section class="showtournament">
        <h1>Tournaments Created</h1>
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
        $output='<ul id="tournament_list">';
        if($statement->rowCount()>0){
            while($tournament=$statement->fetch(PDO::FETCH_ASSOC)){
                $formatted_reg_start_date=date("d/m/y h:i A", strtotime($tournament['registration_from']));
                $formatted_reg_end_date=date("d/m/y h:i A", strtotime($tournament['registration_till']));
                $formatted_start_date=date("d/m/y h:i A", strtotime($tournament['start_date']));
                $formatted_end_date=date("d/m/y h:i A", strtotime($tournament['end_date']));
                $sql="select sport from sports where id=:sport_id";
                $smt=$con->prepare($sql);
                $smt->bindParam(':sport_id',$tournament['sport_id']);
                $smt->execute();
                $sport_id=$smt->fetch();
                $output.='<li class="tourna_list_item">
                            <div class="tournament_con">
                                <div class="tournament_name">
                                    <h2>'.$tournament['name'].'</h2>
                                </div>
                                <div class="tournament_sport">
                                    <h3>Sport : '.$sport_id['sport'].'</h3>
                                </div>
                                <div class="registration_date_time">
                                    <h4>Registeration Starts : '.$formatted_reg_start_date.'</h4>
                                    <h4>Registeration Ends : '.$formatted_reg_end_date.'</h4>
                                </div>
                                <div class="tournament_time">
                                    <h4>Start Date : '.$formatted_start_date.'</h4>
                                    <h4>End Date : '.$formatted_end_date.'</h4>
                                </div>
                                <div class="tourna_team_size">
                                    <h4>Team Size : '.$tournament['team_size'].'</h4>
                                    <h4>Teams : 0/'.$tournament['total_teams'].'</h4>
                                </div>
                                <div class="edit_delete_btn">
                                <button class="tourna-manage-btn">Manage</button>
                                <button class="tourna-manage-btn">View Teams</button>
                                <button type="delete" class="delete_btn tourna-manage-btn">Delete</button>
                                </div>
                            </div>
                          </li>';
            }
        }
        else{
            $output.='<li id="item-no-tourna"><div id="no-tourna-div">No Tournaments Created</div></li>';
        }
        $output.='</ul>';
            echo $output;
        ?>
        </div>
    </section>
    <section id="create-tourna-section">
        <div class="create-tourna-btns">
            <button  id="create-tourna-btn" class="create-tourna-btn tourna-btns">Create Tournament</button>
        </div>
        <div class="manage-tourna-btns create-tourna-btns">
            <button class="manage-tourna-btn tourna-btns">Manage Tournaments</button>
        </div>
    </section>
    </main>
</body>
<script>
    var crte_tourna=document.querySelector('#create-tourna-btn');
    crte_tourna.addEventListener('click',function(){
        window.location.href="org-create-tournament.php";
    });
</script>
</html>
