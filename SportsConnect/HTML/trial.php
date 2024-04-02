<?php
    include"../support_php/db.php";
    $sql="select * from player where username=:username";
    $stmt=$con->prepare($sql);
    $stmt->bindParam(':username',$_SESSION['username']);
    $stmt->execute();
    $result=$stmt->fetch(PDO::FETCH_ASSOC);
    // print_r($result);
    $profile_pic=$result['image'];

?>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Oswald:wght@200..700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary-color: #1b2330;
            --font-size: 16px;
        }
        nav{
            display: flex;
            position: fixed;
            z-index: 1000;
            width: 100%;
            overflow: visible;
            justify-content: space-around;
            background-color: rgb(245, 245, 245);
            height: 4.5em;
            align-items: center;
            padding-top: 10px;
            padding-left: 20px;
            padding-right: 20px;
        }
        nav>.logo{
            display: flex;
            /* flex: 2; */
            align-items: center;
        }
        nav>.logo>img{
            object-fit: cover;
            aspect-ratio: 3/2;
        }
        nav>.nav-menu{
            width: 30%;
            /* flex: 3; */
        }
        nav>.nav-menu>.nav-menu-list{
            list-style: none;
            display: flex;
            justify-content: space-around;
        }
        .nav-menu-list>li>a{
            text-decoration: none;
            font-size: 20px;
            color: #1b2330;
            font-family: "Oswald", sans-serif;
            transition: 0.5s;
        }
        .nav-menu-list>li>a:hover{
            color: red;
        }
        .pic{
            overflow: hidden;
            height: 3.7em;
            object-fit: cover;
            border-radius: 50%;
        }
        .account_icon{
            position: relative;
            /* flex: 1; */
        }
        .dropdown{
            position: absolute;
            z-index: 9999;
            height: auto;
            width: 90px;
            margin-top: 10px;
            visibility: hidden;
            opacity: 0;
            transition: 0.5s;
        } 
        .dropdown ul{
            list-style: none;
        }
        .dropdown ul li{
            border: 2px solid red;
            margin-bottom: 5px;
            padding: 5px;
        }
        .dropdown ul li a{
            text-decoration: none;
            color: var(--primary-color);
        }
        .dropdown ul li a:hover{
            color: rgb(255, 98, 0);
        }
    </style>
    <nav role="navigation">
        <div class="logo">
            <img src="../Images/Sports_Connect_logo.png" alt="logo">
            <h2>SportsConnect</h2>
        </div>
        <div class="nav-menu">
            <ul class="nav-menu-list">
                <li><a href="../Player_php/home.php">Home</a></li>
                <li><a href="../Player_php/Friends.php">Friends</a></li>
                <li><a href="../Player_php/player_find.php">Find Players</a></li>
                <li><a href="#">Matches</a></li>
            </ul>
        </div>
        <div class="account_icon" onclick="toggledropdown()">
            <?php
            echo '<img src="../Images/uploaded_img/player/'.$profile_pic.'" alt="pic" class="pic" name="profile_pic">';
            ?>
            <div id="dropdown" class="dropdown">
                <!-- <?php
                // echo '<img src="../Images/uploaded_img/'.$profile_pic.'" alt="pic" class="pic" name="profile_pic">';
                ?> -->
                <ul>
                    <li><a href="player_dashboard.php">View Profile</a></li>
                    <li><a href="logout.php">logout</a></li>
                </ul>
            </div>
        </div> 
    </nav>
    <script>
        function toggledropdown(){
            var dropdown =document.getElementById('dropdown');
            if (dropdown.style.visibility === 'hidden' || dropdown.style.visibility==='') {
                dropdown.style.visibility = 'visible';
                dropdown.style.opacity='1';
                } 
                else {
                    dropdown.style.visibility = 'hidden';
                    dropdown.style.opacity='0';
                }
             }
    </script> 