<?php
include"db.php";

$sql="select id,name from countries";
$stmt=$con->prepare($sql);
$stmt->execute();
$arrCountry=$stmt->fetchAll(PDO::FETCH_ASSOC);


if(isset($_POST['submit'])){

    $name=$_POST['name'];
    $website=$_POST['org_link'];
    $country_id=$_POST['country'];
    $state_id=$_POST['state'];
    $city_id=$_POST['city'];
    $email=$_POST['email'];
    $country_code=$_POST['no'];
    $phonenumber=$_POST['phone'];
    $username=$_POST['username'];
    $password=$_POST['pass'];

    $image=$_FILES['image']['name'];
    $image_size=$_FILES['image']['size'];
    $image_tmp=$_FILES['image']['tmp_name'];
    $image_type=$_FILES['image']['type'];

    $sql="select * from organization where username=:username || email=:email";
    $stmt=$con->prepare($sql);
    $stmt->bindParam(':username',$username);
    $stmt->bindParam(':email',$email);
    $stmt->execute();


    if($stmt->rowCount()>0){
        $error='User Already Exists';
    }
    else{
        $hashedpass=password_hash($password,PASSWORD_DEFAULT);
            // Check if the table is empty
        $result = $con->query("select count(*) as count from organization");
        $row = $result->fetch(PDO::FETCH_ASSOC);
        if ($row['count'] == 0) {
            // If the table is empty, reset the auto-increment value to 1
            $con->query("ALTER TABLE organization AUTO_INCREMENT = 1");
        }
        $insert="insert into organization(name, website, country_id, state_id, city_id, email, country_code, phonenumber, image, username, password) values(:name, :website, :country_id, :state_id, :city_id, :email, :country_code, :phonenumber, :image, :username, :password)";
        $stm=$con->prepare($insert);
        $stm->bindParam(':name',$name);
        $stm->bindParam(':website',$website);
        $stm->bindParam(':country_id',$country_id);
        $stm->bindParam(':state_id',$state_id);
        $stm->bindParam(':city_id',$city_id);
        $stm->bindParam(':email',$email);
        $stm->bindParam(':country_code',$country_code);
        $stm->bindParam(':phonenumber',$phonenumber);
        $stm->bindParam(':image',$image);
        $stm->bindParam(':username',$username);
        $stm->bindParam(':password',$hashedpass);

        $result=$stm->execute();
        if($result){
            move_uploaded_file($image_tmp,"../Images/uploaded_img/org/".$image);
            $message='Registration Successfull';
            header('location:org_login.php');
        }else{
            $message='Registration failed';
        }

    }


}

?>  
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../CSS/org_reg.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Urbanist:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script src="../Javascript/country.js"></script>
    <script src="../Javascript/show.js"></script>
    <title>Registration</title>
</head>
<body>
    <div class="container" id="container">
        <form id="registration"  action="" method="post" enctype="multipart/form-data">
            <div class="title">
                <h2>Register</h2>
                <?php 
                    if(!empty($error) && isset($error) ){
                        echo '<div class="show"><h4>'.$error.'</h4></div>';
                    }
                ?>
                
            </div>
            <div class="name">
                <label for="org_name">Organization Name : </label>
            <input type="text" name="name" id="org_name" required>
            </div>
            <div class="link">
                <label for="org_link">Website : </label>
                <div class="s">
                    <input type="text" name="org_link" id="org_link">
                    <small>if any</small>
                </div>
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
            <div class="mail">
                <label for="email">Email Id : </label>
           <input type="email" name="email" id="email" class="box" required>
           </div>
           <div class="contact" id="contact">
            <label for="no">Country Code : </label>
            <input type="text" name="no" id="no" required>
            <label for="number">Phone Number : </label>
            <input type="text" id="number" name="phone" required>
            </div>
            <div class="dp">
                <label for="image">Upload Organization Logo : </label>
                <input type="file" name="image" id="image" accept="image/jpg,image/jpeg,image/png"/>
            </div>
            <div class="ldetail" id="ldetail">
                <label for="username">Username : </label>
                <input type="text" name="username" id="username" placeholder="Username" required>
                <label for="pass">Password : </label>
                <input type="password" name="pass" id="pass" placeholder="Password" required>
            </div>
            <div class="reg">
                <button id="submit" type="submit" name="submit" >Register</button>
            </div>
            <div class="other">
                <p>Already have an account? <a href="/SportsConnect/PHP/org_login.php">Login now</a></p>
            </div>

        </form>

    </div>
</body>
</html>