<?php

include"db.php";

$tourna_id=$_POST['tourna_id'];

try{
$con->beginTransaction();
$t_f_registration="select team_id from registration where tournament_id=:tourna_id";
$t_f_stmt=$con->prepare($t_f_registration);
$t_f_stmt->bindParam(":tourna_id",$tourna_id);
$t_f_stmt->execute();
if($t_f_stmt->rowCount()>0){
        while($team_id=$t_f_stmt->fetch(PDO::FETCH_ASSOC)){

            //delete all tournament registrations
            $d_f_regis="delete from registration where tournament_id=:tourna_id and team_id=:teamid";
            $d_f_stmt=$con->prepare($d_f_regis);
            $d_f_stmt->bindParam(":tourna_id",$tourna_id);
            $d_f_stmt->bindParam(":teamid",$team_id);
            $d_f_stmt->execute();
            
            //delete all team_member of teams registered for the tournament
            $d_f_team_member="delete from team_member where team_id=:team_id";
            $d_f_team_member_stmt=$con->prepare($d_f_team_member);
            $d_f_team_member_stmt->bindParam(":team_id",$team_id);
            $d_f_team_member_stmt->execute();

            //delete all teams registered for the tournament
            $d_f_team="delete from teams where id=:team_id";
            $d_f_team_stmt=$con->prepare($d_f_team);
            $d_f_team_stmt->bindParam(":team_id",$team_id);
            $d_f_team_stmt->execute();
        }
    }
        //delete all the schedule of all days of the tournament
        $d_sch_del="delete from tournament_day_schedule where tourna_id=:tourna_id";
        $d_sch_del_stmt=$con->prepare($d_sch_del);
        $d_sch_del_stmt->bindParam(":tourna_id",$tourna_id);
        $d_sch_del_stmt->execute();

        //finally delete the tournament
        $del_tourna="delete from tournament where id=:tourna_id";
        $del_tourna_stmt=$con->prepare($del_tourna);
        $del_tourna_stmt->bindParam(":tourna_id",$tourna_id);
        $del_tourna_stmt->execute();
    
}catch(PDOException $e){
    $con->rollBack();
    setcookie('message',"Tournament deletion error",time()+1800,'/SportsConnect/Org_php');
    exit;
}
$con->commit();
setcookie('message','Tournament deleted successfully',time()+1800,'/SportsConnect/Org_php');
exit;
