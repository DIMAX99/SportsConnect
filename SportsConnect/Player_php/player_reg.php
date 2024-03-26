<?php
include'../support_php/db.php';

session_start();
// $_SESSION['logged']='';
if(isset($_SESSION['logged'])){
    header('location:/SportsConnect/Player_php/home.php');
    exit;
}

$sql="select id,name from countries ";
$stmt=$con->prepare($sql);
$stmt->execute();
$arrCountry=$stmt->fetchAll(PDO::FETCH_ASSOC);
// print_r($arrCountry);

if(isset($_POST['submit'])){
$fname=$_POST['fname'];
$mname=$_POST['mname'];
$lname=$_POST['lname'];
$rawdob=$_POST['dob'];
$dob = date('Y-m-d', strtotime($rawdob));
$dobDate = new DateTime($dob);
$currentDate = new DateTime();
$age = $currentDate->diff($dobDate)->y;
$gender=$_POST['gender'];
$email=$_POST['email'];
$country_id=$_POST['country'];
$state_id=$_POST['state'];
$city_id=$_POST['city'];
$country_code=$_POST['no'];
$phonenumber=$_POST['phone'];

$image=$_FILES['image']['name'];
$image_size=$_FILES['image']['size'];
$image_tmp=$_FILES['image']['tmp_name'];
$image_type=$_FILES['image']['type'];

$username=$_POST['username'];
$password=$_POST['pass'];
$hashedpass=password_hash($password, PASSWORD_DEFAULT);

$selected_sport=$_POST['sports'];

$check = "select email,username from player where email=:email || username=:username";
$stmt=$con->prepare($check);
$stmt->bindParam(':email',$email);
$stmt->bindParam(':username',$username);
$stmt->execute();
$r=$stmt->fetchAll(PDO::FETCH_ASSOC);
if($stmt->rowCount()>0){
    if($r['0']['email']==$email && $r['0']['username']==$username){
        $error = 'User already exists';
    }
    else{
        if($r['0']['email']==$email){
        $error = 'Email already exists';
        }
        else{
            $error = 'Username already exists';
        }
    }
    
    
    // print_r($r);
}
else{
        // Check if the table is empty
    $result = $con->query("select count(*) as count from player");
    $row = $result->fetch(PDO::FETCH_ASSOC);
    if ($row['count'] == 0) {
        // If the table is empty, reset the auto-increment value to 1
        $con->query("ALTER TABLE player AUTO_INCREMENT = 1");
    }
    $insert="insert into player(fname, mname, lname, dob, age, gender, email, country_id, state_id, city_id, country_code, phonenumber, image, username, password) values(:fname, :mname, :lname, :dob, :age, :gender, :email, :country_id, :state_id, :city_id, :country_code, :phonenumber, :image, :username, :hashedpass)";
    $final=$con->prepare($insert);
    $final->bindParam(':fname',$fname);
    $final->bindParam(':mname',$mname);
    $final->bindParam(':lname',$lname);
    $final->bindParam(':dob',$dob);
    $final->bindParam(':age',$age);
    $final->bindParam(':gender',$gender);
    $final->bindParam(':email',$email);
    $final->bindParam(':country_id',$country_id);
    $final->bindParam(':state_id',$state_id);
    $final->bindParam(':city_id',$city_id);
    $final->bindParam(':country_code',$country_code);
    $final->bindParam(':phonenumber',$phonenumber);
    $final->bindParam(':image',$image);
    $final->bindParam(':username',$username);
    $final->bindParam(':hashedpass',$hashedpass);

    $result=$final->execute();

    $find="select id from player where username=:username";
    $stm=$con->prepare($find);
    $stm->bindParam(':username',$username);
    $stm->execute();
    $user_id=$stm->fetch(PDO::FETCH_ASSOC);

    foreach($selected_sport as $sport){
        $sql="select id from sports where sport=:sport";
        $smt=$con->prepare($sql);
        $smt->bindParam(':sport',$sport);
        $smt->execute();
        $sport_id=$smt->fetch(PDO::FETCH_ASSOC);
        $add="insert into play(user_id, sport_id) values(:user_id, :sport_id)";
        $prep=$con->prepare($add);
        $prep->bindParam(':user_id',$user_id['id']);
        $prep->bindParam(':sport_id',$sport_id['id']);
        $prep->execute();
    }
    if($result && $prep){
        move_uploaded_file($image_tmp,"../Images/uploaded_img/player/".$image);
        $error='Registered Successfully';
        header('location:../Player_php/login.php');
    }else{
        $error='Registeration failed';
    }
}
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../CSS/p_reg.css">
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Urbanist:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
    <script src="../Javascript/country.js"></script>
    <script src="../Javascript/show.js"></script>
    <title>Register</title>
</head>
<body>
    <!-- <img src="../Images/background.jpg" alt="img" class="bkg" id="bkg"> -->
    <div class="container" id="container">
        <form id="regform" action="" method="post" enctype="multipart/form-data">
            <div class="title" id="title">
                <h3>REGISTRATION</h3>
            </div>
            <?php
            if(!empty($error) && isset($error)){
                echo '<div id="error"><h4 id="error_msg" >'.$error.'</h4></div>';
            }   
            ?>
            <div class="Name" id="name">
            <label for="fname">First Name : </label>
            <input type="text" name="fname" id="fname" placeholder="First name" class="box" required>
            <label for="mname">Middle Name : </label>
            <input type="text" name="mname" id="mname" placeholder="Middle name" class="box">
            <label for="lname">Last Name : </label>
            <input type="text" name="lname" id="lname" placeholder="Last name" class="box" required>  
            </div>
            
            <div class="date_gen">
                <div class="date">
                <label for="dob">Date of Birth : </label>
            <input type="date" name="dob" id="dob" placeholder="Enter dob" class="box">
            </div>
            <div class="gen">
                <label for="male">Gender : </label>
                <label for="male">male</label>
                <input type="radio" name="gender" id="male" value="male">
                <label for="female">female</label>
                <input type="radio" name="gender" id="female" value="female">
                <label for="other">others</label>
                <input type="radio" name="gender" id="other" value="other">
            </div>
            </div>
            
            <div class="mail">
                 <label for="email">Email Id : </label>
            <input type="email" name="email" id="email" placeholder="Enter your mail" class="box" required>
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
            
            <div class="sportselection">
            <label for="sports1">Select Your Sports : </label>
                <div class="sport">
                    <div class="lab">
                        <label for="sport1">Football</label>
                        <label for="sport2">Cricket</label>
                        <label for="sport3">Badminton</label>
                        <label for="sport4">Basketball</label>
                        <label for="sport5">Tennis</label>
                    </div>
                    <div class="check">
                        <input type="checkbox" name="sports[]" id="sport1" value="football">
                        <input type="checkbox" name="sports[]" id="sport2" value="cricket">
                        <input type="checkbox" name="sports[]" id="sport3" value="badminton">
                        <input type="checkbox" name="sports[]" id="sport4" value="basketball">
                        <input type="checkbox" name="sports[]" id="sport5" value="tennis">   
                    </div>
                </div>
                
            </div>
            <div class="contact" id="contact">
            <label for="no">Country Code : </label>
            <input type="text" name="no" id="no">
            <label for="phone">Phone Number : </label>
            <input type="number" id="number" name="phone" required>
            </div>
            <div class="dp">
                <label for="image">Upload Profile Photo : </label>
                <input type="file" name="image" id="image" accept="image/jpg,image/jpeg,image/png"/>
            </div>
            <div class="ldetail" id="ldetail">
                <label for="username">Username : </label>
                <input type="text" name="username" id="username" placeholder="Username">
                <label for="pass">Password : </label>
                <input type="password" name="pass" id="pass" placeholder="Password">
            </div>
            <div class="reg" id="reg">
                <button id="submit" name="submit" type="submit">Register</button>
            </div>
        </form>
        <div class="login">
            <p class="else" >already have an account? <a href="../Player_php/login.php" class="login">login now</a></p>
        </div>
    </div>
</body>
</html>