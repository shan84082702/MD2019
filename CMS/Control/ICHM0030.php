<?php
    include("../Mod/config.php");
    if ($_SERVER['REQUEST_METHOD'] == "POST") {
		$token = $_POST["token"];
		$key = "CGUAdmin2019";
		$detoken = tokendecode(json_encode($token),$key);
        $token_obj = json_decode($detoken);
        if($_POST["action"] == "insert_prores"){
            Insert_Prores($token_obj->Aid);
        }
        else if($_POST["action"] == "getProres"){
            getProres();
        }
        else if($_POST["action"] == "getProres_1"){
            getProres_1();
        }
        else if($_POST["action"] == "getProres_2"){
            getProres_2();
        }
        else if($_POST["action"] == "prores"){
            Prores();
        }
        else if($_POST["action"] == "prores_1"){
            Prores_1();
        }
        else if($_POST["action"] == "prores_2"){
            Prores_2();
        }
        else if($_POST["action"] == "getOrderSummaryInfo"){
            getOrderSummaryInfo($token_obj->Aid);
        }
        else if($_POST["action"] == "getPEInfo"){
            getPEInfo();
        }
        else if($_POST["action"] == "prores_edit"){
            Prores_edit($token_obj->Aid);
        }
        else if($_POST["action"] == "cancel"){
            Cancel();
        }
        else if($_POST["action"] == "getSubmissionOption"){
            getSubmissionOption($token_obj->Aid);
        }
        else if($_POST["action"] == "getOrderCanPay"){
            getOrderCanPay($token_obj->Aid);
        }
        else if($_POST["action"] == "delorder"){
            Cancel();
        }
    }

    function Insert_Prores($Aid){
        $mysqli_db = dbconect();
        $mysqli_db->query("SET NAMES utf8");
        $Oid=$Aid.date('Ymdhis',time());
	    $sql = "INSERT INTO `order` (`Oid`,`O_Aid`) VALUES ('".$Oid."','".$Aid."');";	
	    $result=$mysqli_db->query($sql);
	    echo json_encode(array('isSuccess' => true,"OrderId" => $Oid)); 
    }

    function getProres(){
        $Oid=$_POST['Oid'];
        $mysqli_db = dbconect();
        $mysqli_db->query("SET NAMES utf8");
	    $sql = "SELECT `O_Dietary`, `O_Detailed` from `order` WHERE `Oid` = '".$Oid."';";	
        $result=$mysqli_db->query($sql);
        $row=$result->fetch_array(MYSQLI_ASSOC);
        $out['O_Dietary']=$row['O_Dietary'];
        $out['O_Detailed']=$row['O_Detailed'];
	    echo json_encode(array('isSuccess' => true,"out" => $out)); 
    }

    function getProres_1(){
        $Oid=$_POST['Oid'];
        $mysqli_db = dbconect();
        $mysqli_db->query("SET NAMES utf8");
	    $sql = "SELECT `O_Pid` from `order` WHERE `Oid` = '".$Oid."';";	
        $result=$mysqli_db->query($sql);
        $row=$result->fetch_array(MYSQLI_ASSOC);
        $out['O_Pid']=$row['O_Pid'];
	    echo json_encode(array('isSuccess' => true,"out" => $out)); 
    }

    function getProres_2(){
        $Oid=$_POST['Oid'];
        $mysqli_db = dbconect();
        $mysqli_db->query("SET NAMES utf8");
	    $sql = "SELECT `O_Pid`,`O_Name`,`O_Phone`,`O_Organization`,`O_Stree`,`O_Country`,`O_Area`,`O_City`,`O_Zipcode` FROM `order` WHERE `Oid` = '".$Oid."';";	
        $result=$mysqli_db->query($sql);
        $row=$result->fetch_array(MYSQLI_ASSOC);
        $out['O_Name']=$row['O_Name'];
        $out['O_Phone']=$row['O_Phone'];
        $out['O_Organization']=$row['O_Organization'];
        $out['O_Stree']=$row['O_Stree'];
        $out['O_Country']=$row['O_Country'];
        $out['O_Area']=$row['O_Area'];
        $out['O_City']=$row['O_City'];
        $out['O_Zipcode']=$row['O_Zipcode'];
        $out['O_Pid']=$row['O_Pid'];
	    echo json_encode(array('isSuccess' => true,"out" => $out)); 
    }

    function Prores(){
        $Oid=$_POST['Oid'];
        $needs = $_POST['needs'];
	    $detail = $_POST['detail'];
        $mysqli_db = dbconect();
        $mysqli_db->query("SET NAMES utf8");
		$detail=str_replace("'","\'",$detail);
		$detail=str_replace('"','\"',$detail);
	    $sql = "UPDATE `order` SET `O_Dietary`='".$needs."',`O_Detailed`='".$detail."' WHERE `Oid` = '".$Oid."';";	
	    $result=$mysqli_db->query($sql);
	    echo json_encode(array('isSuccess' => true)); 
    }

    function Prores_1(){
        $type = $_POST['type'];
        $submission_option = $_POST['submission_option'];
        $Oid = $_POST['Oid'];
        if($type=="1")
            $money=15600;
        if($type=="2")
            $money=6240;
        if($type=="3")
            $money=9360;
        if($type=="4")
            $money=3500;
		if($type=="5")
            $money=2000;
        if($type=="6")
            $money=2500;
        if($type=="7")
            $money=18720;
        if($type=="8")
            $money=9360;
		if($type=="9")
            $money=12480;
        if($type=="10")
            $money=5000;
        if($type=="11")
            $money=2500;
        if($type=="12")
            $money=3000;
		if($type=="13")
            $money=2000;
        if($type=="14")
            $money=31000;
        if($type=="15")
            $money=29000;
        if($type=="16")
            $money=2000;
		if($type=="17")
            $money=1500;
        $mysqli_db = dbconect();
        $mysqli_db->query("SET NAMES utf8");
        $updata = "UPDATE `order` SET `O_Money` = '".$money."', `O_type`='".$type."', `O_Pid` = '".$submission_option."' WHERE `Oid` = '".$Oid."';";
        $result=$mysqli_db->query($updata);
	    echo json_encode(array('isSuccess' => true, "money"=>$money)); 
    }

    function Prores_2(){
        $name = $_POST['name'];
        $phone = $_POST['phone'];
	    $org = $_POST['org'];
	    $street = $_POST['street'];
	    $country = $_POST['country'];
	    $area = $_POST['area'];
	    $city = $_POST['city'];
        $zipcode = $_POST['zipcode'];
        $Oid = $_POST['Oid'];
        $mysqli_db = dbconect();
        $mysqli_db->query("SET NAMES utf8");
	    $updata = "UPDATE `order` SET `O_Name`='".$name."',`O_Phone`='".$phone."', `O_Organization`= '".$org."', `O_Country`='".$country."', `O_City`= '".$city."', `O_Stree`='".$street."', `O_Zipcode`='".$zipcode."', `O_Area`='".$area."',`CreatTime`= CURRENT_TIMESTAMP, `O_isUsed`='1'  WHERE `Oid` = '".$Oid."';";
	    $result=$mysqli_db->query($updata);
	    echo json_encode(array('isSuccess' => true,"msg" => '0')); 
    }

    function getOrderSummaryInfo($Aid){
        $mysqli_db = dbconect();
        $mysqli_db->query("SET NAMES utf8");
		$sql = "SELECT * FROM `order` WHERE `O_Aid`='".$Aid."' and `O_isUsed`='1';";
        $result = $mysqli_db->query($sql);
        $paper_table="";
        if(mysqli_num_rows($result)>0){
            $paper_table="<table><tr><th>Number</th> <th>Order Date</th> <th>Amount</th> <th>Status</th> <th>Payment Date</th> <th>Action</th> <th>Delete</th></tr>";
		    while($row =$result->fetch_object()){
			    $number = $row->Oid;
                $order = $row->CreatTime;
			    if($row->O_isPay==0){
                    $status = "Undone";
                    $edit = "<a href='javascript: void(0)' onclick=proregisteredit(this.id) id='".$number."'>view/edit/pay</a>";
                    $delete = "<button  class='btn-sm btn-primary' id='".$number."' onclick='DelOrder(this.id)'>Delete</button>";
                    $PaymentTime="Undone";
                }
			    else{
                    $status = "Complete";
                    $edit = "<a href='javascript: void(0)' onclick=proregisterview(this.id) id='".$number."'>view</a>";
                    $delete = "";
                    $PaymentTime=$row->O_PaymentTime;
                }
                $money = "NT$".$row->O_Money;
                $paper_table.="<tr><td>".$number."</td> <td>".$order."</td> <td>".$money."</td> <td>".$status."</td>
                    <td>$PaymentTime</td> <td>".$edit."</td> <td>".$delete."</td>";
            }
            $paper_table.="</table>";
        }
        else
            $paper_table="No Order!";
        
        echo json_encode(array('isSuccess' => true,'paper_table'=>$paper_table));
    }

    function getPEInfo(){
        $Oid=$_POST['Oid'];
        $mysqli_db = dbconect();
        $mysqli_db->query("SET NAMES utf8");
        $sql = "SELECT * FROM `order` WHERE `Oid` = '".$Oid."';";
        //$sql = "SELECT `order`.*, `paper`.`P_Title` FROM `order`,`paper` WHERE `Oid` = '".$Oid."' and `order`.`O_Pid` =`paper`.`Pid`;";
        $result = $mysqli_db->query($sql);
        while($row = $result->fetch_object()){
            $O_Aid = $row->O_Aid;
            $O_Name = $row->O_Name;
            $O_Dietary = $row->O_Dietary;
            $O_Detailed = $row->O_Detailed;
            $O_Money = $row->O_Money;
            $O_type = $row->O_type;
            $O_Organization = $row->O_Organization;
            $O_Country = $row->O_Country;
            $O_City = $row->O_City;
            $O_Stree = $row->O_Stree;
            $O_Zipcode = $row->O_Zipcode;
            $O_Area = $row->O_Area;
            $O_Phone = $row->O_Phone;
            $O_Pid = $row->O_Pid;
            $O_isPay = $row->O_isPay;
        }
        $P_Title = "";
        if($O_Pid!=0 && $O_Pid!=NULL){
            $sql = "SELECT P_Title FROM `paper` WHERE `Pid` = '".$O_Pid."';";
            $result = $mysqli_db->query($sql);
            $row = $result->fetch_object();
            $P_Title = $row->P_Title;
        }
        echo json_encode(array('isSuccess' => true,'O_Dietary'=>$O_Dietary,'O_Detailed'=>$O_Detailed,'P_Title'=>$P_Title,'O_Pid'=>$O_Pid,'O_type'=>$O_type,'O_Name'=>$O_Name,'O_Phone'=>$O_Phone,'O_Organization'=>$O_Organization,'O_Stree'=>$O_Stree,'O_Country'=>$O_Country,'O_Area'=>$O_Area,'O_City'=>$O_City,'O_Zipcode'=>$O_Zipcode));
    }

    function Prores_edit($Aid){
        $needs = $_POST['needs'];
        $detail = $_POST['detail'];
		$detail=str_replace("'","\'",$detail);
		$detail=str_replace('"','\"',$detail);
        $type = $_POST['type'];
        $Oid = $_POST['Oid'];
        $new_Oid=$Aid.date('Ymdhis',time());
        
        if($type=="1")
            $money=15600;
        if($type=="2")
            $money=6240;
        if($type=="3")
            $money=9360;
        if($type=="4")
            $money=3500;
		if($type=="5")
            $money=2000;
        if($type=="6")
            $money=2500;
        if($type=="7")
            $money=18720;
        if($type=="8")
            $money=9360;
		if($type=="9")
            $money=12480;
        if($type=="10")
            $money=5000;
        if($type=="11")
            $money=2500;
        if($type=="12")
            $money=3000;
		if($type=="13")
            $money=2000;
        if($type=="14")
            $money=31000;
        if($type=="15")
            $money=29000;
        if($type=="16")
            $money=2000;
		if($type=="17")
            $money=1500;
    
        $name = $_POST['name'];
        $phone = $_POST['phone'];
        $submission_option = $_POST['submission_option'];
	    $org = $_POST['org'];
	    $street = $_POST['street'];
	    $country = $_POST['country'];
	    $area = $_POST['area'];
	    $city = $_POST['city'];
        $zipcode = $_POST['zipcode'];
        $mysqli_db = dbconect();
        $mysqli_db->query("SET NAMES utf8");
    
        $sql = "Update `order` set `Oid`='$new_Oid', `O_Name`='$name', `O_Phone`='$phone', `O_Pid`='$submission_option', `O_Dietary`='$needs', `O_Detailed`='$detail', `O_Money`='$money', `O_Organization`='$org', 
            `O_Country`='$country', `O_City`='$city', `O_Stree`='$street', `O_Zipcode`='$zipcode', `O_Area`='$area', `O_type`='$type'
            where Oid='$Oid'";	
	    $result=$mysqli_db->query($sql);
	    echo json_encode(array('isSuccess' => true, "OrderId"=>$new_Oid)); 
    }

    function Cancel(){
        $Oid = $_POST['Oid'];
        $mysqli_db = dbconect();
        $mysqli_db->query("SET NAMES utf8");
	    $delete = "DELETE from `order` WHERE `Oid` = '".$Oid."';";
	    $result=$mysqli_db->query($delete);
	    echo json_encode(array('isSuccess' => true)); 
    }

    function getSubmissionOption($Aid){
        $Oid = $_POST['Oid'];
        $mysqli_db = dbconect();
        $mysqli_db->query("SET NAMES utf8");
	    $sql = "SELECT Pid,P_Title from paper where P_Aid='$Aid' and Pid not in (SELECT Pid FROM `paper` INNER JOIN `order` on `order`.`O_Pid`=`paper`.`Pid` where `paper`.`P_Aid`=`order`.`O_Aid` and `order`.`O_isUsed`='1')";
	    $result=$mysqli_db->query($sql);
	    $out=array();
		if($result->num_rows>0){
			while($row = $result->fetch_array(MYSQLI_ASSOC)){
                $out1['Pid']=$row['Pid'];
                $out1['P_Title']=$row['P_Title'];
				array_push($out,$out1);
				unset($out1);
			}
        }
        echo json_encode(array('isSuccess' => true,'out'=>$out)); 
    }

    function getOrderCanPay($Aid){
        $Oid=$_POST['OrderId'];
        $mysqli_db = dbconect();
        $mysqli_db->query("SET NAMES utf8");
	    $sql = "SELECT O_type, A_checkIdentity FROM `order`,`account` WHERE `Oid` = '".$Oid."' and `Aid` = '".$Aid."';";
        $result = $mysqli_db->query($sql);
        $row = $result->fetch_object();
        $O_canPay=0;
        $O_feeType=0;
        $O_type = $row->O_type;
        $A_checkIdentity = $row->A_checkIdentity;
        if($O_type==1 || $O_type==3 || $O_type==7 || $O_type==9 || $O_type==14){
            $O_canPay=1;
            $O_feeType=1;
        }
        else if($O_type==2 || $O_type==8){
            if($A_checkIdentity==5 || $A_checkIdentity==6 || $A_checkIdentity==7 || $A_checkIdentity==8){
                $O_canPay=1;
            }
            $O_feeType=2;
        }
        else if($O_type==4 || $O_type==6 || $O_type==10 || $O_type==12 || $O_type==16){
            if($A_checkIdentity==2 || $A_checkIdentity==3 || $A_checkIdentity==4 || $A_checkIdentity==5 || $A_checkIdentity==6 || $A_checkIdentity==7){
                $O_canPay=1;
            }
            $O_feeType=3;
        }
        else if($O_type==5 || $O_type==11){
            if($A_checkIdentity==5 || $A_checkIdentity==6 || $A_checkIdentity==7){
                $O_canPay=1;
            }
            $O_feeType=4;
        }
        else if($O_type==13 || $O_type==15 || $O_type==17){
            if($A_checkIdentity==3 || $A_checkIdentity==4 || $A_checkIdentity==6 || $A_checkIdentity==7){
                $O_canPay=1;
            }
            $O_feeType=5;
        }
	    echo json_encode(array('isSuccess' => true,'O_canPay'=>$O_canPay,'O_feeType'=>$O_feeType)); 
    }
?>