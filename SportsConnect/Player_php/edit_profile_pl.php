<?php
include'../support_php/db.php';

session_start();

if(!isset($_SESSION['logged']) || $_SESSION['logged']!=true){
    header('location:/SportsConnect/Player_php/login.php');
    exit();
}else{
    
    // print_r($arrCountry);



    $query="select * from player where username=:username";
    $statement=$con->prepare($query);
    $statement->bindParam(":username",$_SESSION['username']);
    $statement->execute();
    $detail=$statement->fetch(PDO::FETCH_ASSOC);
    // print_r($detail);
    $find_csc="select countries.name as country_name, countries.id as country_id, states.name as state_name, states.id as state_id, cities.name as city_name, cities.id as city_id 
                from cities
                join states on cities.state_id=states.id
                join countries on states.country_id=countries.id
                where cities.id=:city_id";
    $stmt=$con->prepare($find_csc);
    $stmt->bindParam(":city_id",$detail["city_id"]);
    $stmt->execute();
    $address=$stmt->fetch(PDO::FETCH_ASSOC);
    // $message="";
    
    if(isset($_POST['save'])){
        $fname=$_POST['fname'];
        $mname=$_POST['mname'];
        $lname=$_POST['lname'];
        $rawdob=$_POST['dob'];
        $dob = date('Y-m-d', strtotime($rawdob));
        $dobDate = new DateTime($dob);
        $currentDate = new DateTime();
        $age = $currentDate->diff($dobDate)->y;
        $gender=$_POST['gender'];
        $country_id=$_POST['country'];
        $state_id=$_POST['state'];
        $city_id=$_POST['city'];
        $country_code=$_POST['code'];
        $phonenumber=$_POST['number'];

        // $image=$_FILES['dp']['name'];
        // $image_size=$_FILES['dp']['size'];
        // $image_tmp=$_FILES['dp']['tmp_name'];
        // $image_type=$_FILES['dp']['type'];

        $sql="update player set fname=:fname, mname=:mname, lname=:lname, dob=:dob, age=:age, 
            gender=:gender, country_id=:country_id, state_id=:state_id, city_id=:city_id, country_code=:country_code, 
            phonenumber=:phonenumber where username=:username";
        $smt=$con->prepare($sql);
        $smt->bindParam(':fname',$fname);
        $smt->bindParam(':mname',$mname);
        $smt->bindParam(':lname',$lname);
        $smt->bindParam(':dob',$dob);
        $smt->bindParam(':age',$age);
        $smt->bindParam(':gender',$gender);
        $smt->bindParam(':country_id',$country_id);
        $smt->bindParam(':state_id',$state_id);
        $smt->bindParam(':city_id',$city_id);
        $smt->bindParam(':country_code',$country_code);
        $smt->bindParam(':phonenumber',$phonenumber);
        // $smt->bindParam(':image',$image);
        $smt->bindParam(':username',$_SESSION['username']);
        if($smt->execute()){
            $message="Profile Updated Successfully";
            header('location:edit_profile_pl.php');
            exit();

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
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Mukta+Vaani:wght@200;300;400;500;600;700;800&display=swap" rel="stylesheet">
    <title>Edit Profile</title>
    <style>
        *{
            margin: 0;
            padding: 0;
            font-family: "Mukta Vaani", sans-serif;
        }
        .player_profile_dp{
            display: flex;
            /* align-items: center; */
            justify-content: center;
            margin: 20px 10px 10px 10px;
        }
        .player_profile_dp>img{
            height: 200px;
            width: 200px;
            border: 5px solid lavender;
            margin: 10px 20px 10px 20px
        }
        .form_container{
            padding: 30px 50px 5px 50px;
            font-size: 25px;
        }
        .det_input{
            font-size: 23px;
            outline: none;
            border-radius: 6px;
            padding-left: 10px;
            padding-right: 10px;
            margin-right: 20px;
        }
        .not_change_input{
            font-size: 23px;
            outline: none;
            border-radius: 6px;
            padding-left: 10px;
            padding-right: 10px;
            margin-right: 20px;
        }
        .common{
            margin-bottom: 20px;
        }
        .btn{
            display: flex;
            justify-content: center;
            align-items: center;
            margin-bottom: 20px;
        }
        .btn>button{
            font-size: 20px;
            margin-right: 30px;
            padding: 8px;
            border-radius: 7px;
            cursor: pointer;
        }
        input[type=number]::-webkit-inner-spin-button, 
        input[type=number]::-webkit-outer-spin-button { 
            -webkit-appearance: none; 
            margin: 0; 
        }

        input[type=number] {
            -moz-appearance: textfield; /* Firefox */
        }
        select {
            -webkit-appearance: none;
            -moz-appearance: none;
            appearance: none;
            z-index: 0;
        }
    </style>
</head>
<body>
    <?php
    include"../support_php/profile_template.php";
    ?>
    <!-- <script>
        var msg = "<?php echo $message;?>";
        if (msg.trim() !== "") {
        alert(msg);
        }
    </script> -->
    <section class="player_profile_dp">
        <!-- <div> -->
            <?php
            echo '<img id="dp" name="dp" src="../Images/uploaded_img/player/'.$result['image'].'" alt="dp">';
            ?>
        <!-- </div>   -->
        <!-- <div>
            <p>Change Profile Pic</p>
            <input type="file" name="dp" id="n_dp">
        </div> -->
    </section>
    <section id="player_det">
        <form action="" method="post"  enctype="multipart/form-data">
            <div class="form_container">
                    <div class="name common">
                    <label for="fname">First Name :</label>
                    <input type="text" name="fname" id="fname" class="det_input" disabled 
                    <?php
                    echo 'value="'.$detail['fname'].'"';
                    ?>>
                    <label for="mname">Middle Name :</label>
                    <input type="text" name="mname" id="mname" class="det_input" disabled
                    <?php
                    echo 'value="'.$detail['mname'].'"';
                    ?>>
                    <label for="lname">Last Name :</label>
                    <input type="text" name="lname" id="lname" class="det_input" disabled
                    <?php
                    echo 'value="'.$detail['lname'].'"';
                    ?>> 
                </div>
                <div class="dob_age common">
                    <label for="dob">Date of Birth :</label>
                    <input type="date" name="dob" id="dob" class="det_input" disabled
                    <?php
                    echo 'value="'.$detail['dob'].'"';
                    ?>>
                    <label for="age">Age :</label>
                    <input type="number" name="age" id="age" class="det_input" disabled
                    <?php
                    echo 'value="'.$detail['age'].'"';
                    ?>>
                </div>
                <div class="gen common">
                    <label for="gender">Gender :</label>
                    <select name="gender" id="gender" class="det_input" disabled="disabled">
                        
                    </select>
                </div>
                <div class="mail common">
                    <label for="mail">Email :</label>
                    <input type="email" name="mail" id="mail" class="not_change_input" disabled
                    <?php
                    echo 'value="'.$detail['email'].'"';
                    ?>>
                </div>
                <div class="address common">
                    <label for="country">Country :</label>
                    <select name="country" id="country" class="det_input" disabled>

                    </select>
                    
                    <label for="state">State :</label>
                    <select name="state" id="state" class="det_input" disabled>

                    </select>
                    <label for="city">City :</label>
                    <select name="city" id="city" class="det_input" disabled>

                    </select>
                </div>
                <div class="code_number common">
                    <label for="code">Country Code :</label>
                    <input type="text" name="code" id="code" class="det_input" disabled
                    <?php
                    echo 'value="'.$detail['country_code'].'"';
                    ?>>
                    <label for="number">Phone Number :</label>
                    <input type="number" name="number" id="number" class="det_input" disabled
                    <?php
                    echo 'value="'.$detail['phonenumber'].'"';
                    ?>>     
                </div>
                <!-- <label for="dp">Change Profile Pic : </label>
                <input type="file" name="dp" id="n_dp" class="det_input" accept="image/jpg,image/jpeg,image/png" disabled> -->
            </div>
            <div class="btn">
            <button id="edit_profile">Edit Profile</button>
            <button id="save" name="save" type="submit" disabled>Save</button>
            <!-- <button type="reset">Cancel</button>  -->
            </div>
        </form>
        
    </section>
    <script>
            document.getElementById('edit_profile').addEventListener('click',function(){

                var inputs =document.querySelectorAll('.det_input');
                var genderselect = document.getElementById('gender');

                var genderoptions=['male','female','other'];

                
                    jQuery.ajax({
                         type:"get",
                         url:"../support_php/ajax_get_countries.php",
                         dataType:"json",
                         success:function(countries){
                                 var country_select = document.getElementById('country');
                                 countries.forEach(function(country){
                                 console.log(country);
                                 var option = document.createElement("option");
                                 option.text = country.name;
                                 option.value = country.id; 
                                 country_select.appendChild(option);
                                 });
                             }
                        });
                        jQuery('#country').change(function(){
                            var id = jQuery(this).val();
                        jQuery.ajax({
                        type:'post',
                        url:'../support_php/ajax_get_state.php',
                        dataType:"json",
                        data:{id:id},
                        success:function(states){
                                var state_select = document.getElementById('state');
                                state_select.innerHTML="";
                                states.forEach(function(state){
                                    var state_option = document.createElement("option");
                                    state_option.text=state.name;
                                    state_option.value=state.id;
                                    state_select.appendChild(state_option);
                                });
                        }
                        });
                        jQuery.ajax({
                            type:'post',
                            url:'../support_php/ajax_get_code.php',
                            dataType:"json",
                            data:{id:id},
                            success:function(phone_code){
                                var phone_code = phone_code.startsWith('+') ? phone_code : '+' + phone_code;
                                jQuery('#code').val(phone_code);
                            }
                        });
                        });
                        //query for getting city
                        jQuery("#state").change(function(){
                            var id = jQuery(this).val();
                            jQuery.ajax({
                                type:'post',
                                url:'../support_php/ajax_get_city.php',
                                dataType:"json",
                                data:{id:id},
                                success:function(cities){
                                    var city_select = document.getElementById('city');
                                    city_select.innerHTML="";
                                    cities.forEach(function(city){
                                        var city_option = document.createElement("option");
                                        city_option.text=city.name;
                                        city_option.value=city.id;
                                        city_select.appendChild(city_option);
                                    });
                                }
                            });
                        });
                        
                var save=document.querySelector('#save')
                inputs.forEach(function(input){
                    input.removeAttribute('disabled');
                });
                save.removeAttribute('disabled');
                genderselect.innerHTML='';
                genderoptions.forEach(function(option){
                    genderselect.innerHTML+=`
                    <option value="${option}">${option}</option>
                    `;
                });
                document.getElementById('edit_profile').disabled=true;
            });
    </script>
    <script>
        document.addEventListener("DOMContentLoaded", function(){
            var genderselect = document.getElementById('gender');
            var usergender="<?php echo $detail['gender'];?>";
            genderselect.innerHTML=`
                <option value="${usergender}" selected>${usergender}</option>"
            `;
            var country = document.getElementById('country');
            var usercountry_id="<?php echo $address['country_id'];?>";
            var usercountry="<?php echo $address['country_name'];?>";
            country.innerHTML=`
                <option value="${usercountry_id}">${usercountry}</option>
            `;
            var state = document.getElementById('state');
            var userstate_id="<?php echo $address['state_id'];?>";
            var userstate="<?php echo $address['state_name'];?>";
            state.innerHTML=`
                <option value="${userstate_id}">${userstate}</option>
            `;
            var city = document.getElementById('city');
            var usercity_id="<?php echo $address['city_id'];?>";
            var usercity="<?php echo $address['city_name'];?>";
            city.innerHTML=`
                <option value="${usercity_id}">${usercity}</option>
            `;
        });
    </script>
</body>
</html>