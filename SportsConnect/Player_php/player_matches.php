<?php
include"../support_php/db.php";
session_start();

if(isset($_COOKIE['player_error']))
{
    $message=$_COOKIE['player_error'];
    echo "<script>
                document.addEventListener('DOMContentLoaded', function(){
                var messagediv = document.getElementById('error');
                messagediv.innerText='$message';
                messagediv.style.display='block';
                setTimeout(function(){
                    messagediv.style.display='none';
                    },3000)
                });
        </script>";
    setcookie('player_error','',time()-60,'player_matches.php');
    
}
$all_sport="select * from sports";
$all_sport_stmt=$con->prepare($all_sport);
$all_sport_stmt->execute();
$sport_li='<ul id="sport_list">';
if($all_sport_stmt->rowCount()>0){
    while($sport=$all_sport_stmt->fetch(PDO::FETCH_ASSOC)){
        $sport_li.='<li class="sport_menu" data-sport="'.$sport['id'].'">'.$sport['sport'].'</li>';
    }
}
else{
    $sport_li.='<li>No Sport Available</li>';
}
$sport_li.='</ul>';

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
        .sport_menu:hover{
            color: red;
            cursor: pointer;
            border: 2px solid red;
        }
        .sport_menu.selected{
            color: red;
            border: 2px solid red;
        }
        #sport_list{
            display: flex;
            list-style: none;
            justify-content: space-evenly;
            background-color: black;
        }
        .sport_menu{
            margin: 10px;
            width: 12%;
            padding: 10px;
            box-sizing: border-box;
            display: flex;
            justify-content: center;
            align-items: center;
            border-radius: 5px;
            color: white;
            text-transform: uppercase;
            transition: 0.5s;
            border: 2px solid white;
            background-color: var(--primary-color);
        }
        
    </style>
</head>
<body>
    <?php
    include"../HTML/trial.php";
    ?>
    <main>
        <div class="error" id="error">
            
        </div>
        <div class="sport_nav">
            <?php
            echo $sport_li;
            ?>
        </div>
        <section class="upcoming_tournaments">
            <h2 id="title">All Matches</h2>
            <div id="up-tournaments">
                
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
<script>
    document.addEventListener('DOMContentLoaded', function() {
    // Get the list of sports
    var sportList = document.getElementById('sport_list');

    // Set default selected sport (e.g., football)
    var defaultSport = 1;

    // Highlight the default selected sport
    var defaultSportItem = sportList.querySelector('[data-sport="'+defaultSport+'"]');
    defaultSportItem.classList.add('selected');

    //select the div for showing the tournaments
    var tourna_div=document.getElementById('up-tournaments');

    // Fetch tournaments for the default selected sport
    fetchTournaments(defaultSport);

    // Add click event listener to each sport list item
    sportList.querySelectorAll('li').forEach(function(item) {
        item.addEventListener('click', function() {
            // Remove 'selected' class from all sport list items
            sportList.querySelectorAll('li').forEach(function(item) {
                item.classList.remove('selected');
            });

            // Add 'selected' class to the clicked sport list item
            this.classList.add('selected');

            // Get the selected sport from the data-sport attribute
            var selectedSport = this.dataset.sport;

            // Fetch tournaments for the selected sport
            fetchTournaments(selectedSport);
        });
    });

    // Function to fetch tournaments for a given sport
    function fetchTournaments(sport) {
        // AJAX call to fetch tournaments for the selected sport
        $.ajax({
           type:'post',
           url:'../support_php/ajax-fetch-tournament.php',
           data:{sport_id:sport},
           success:function(output){
            tourna_div.innerHTML='';
            tourna_div.innerHTML+=output;
           }

        });
    }
});
</script>
</html>