<?php
	//include("../Mod/config.php");
	include("../Mod/CMSadmin0010MOD.php");
	if ($_SERVER['REQUEST_METHOD'] == "POST") {
		if($_POST["action"] == "login"){
			LogInAdmin();
		}
		else{
			$token = $_POST["token"];
			$key = "md2019admin";
			$detoken = tokendecode(json_encode($token),$key);
			$token_obj = json_decode($detoken);
			if($token_obj->Rank == 2){
                if($_POST["action"] == "getValidationInfo"){
				    GetValidationInfo();
                }
                else if($_POST["action"] == "getmemberlist"){
				    GetMemberList();
                }
                else if($_POST["action"] == "updateValidationInfo"){
				    UpdateValidationInfo();
                }
			}
		}
	}
	function LogInAdmin(){
		$Acc = "CGU_MD2019";
		$Psw = "md2019admin";
		$Rank = 2;
		$retoken="";
		if(($_POST["Acc"]==$Acc)&&($_POST["Psw"] == $Psw)){
			$token['Acc'] = $Acc;
			$token['Rank'] = $Rank;
			$token['Psw'] = $Psw;
			$key = "md2019admin";
			$retoken = tokenencode(json_encode($token),$key);
			///echo $retoken;
			echo json_encode(array('isSuccess' => true,'msg'=>"登入成功!!",'token'=>$retoken)); 
		}
		else if(($_POST["Acc"]=="CGUTest")&&($_POST["Psw"] == "123456")){
			$token['Acc'] = "CGUTest";
			$token['Rank'] = $Rank;
			$token['Psw'] = "123456";
			$key = "md2019admin";
			$retoken = tokenencode(json_encode($token),$key);
			///echo $retoken;
			echo json_encode(array('isSuccess' => true,'msg'=>"登入成功!!",'token'=>$retoken)); 
		}
		else{
			echo json_encode(array('isSuccess' => false,'msg'=>"帳號或密碼錯誤")); 
		}
		//echo "1.".$Acc."1-1.".$PosAcc."2.".$Psw."2-1.".$PosPsw.$_POST["Acc"];
		return;
	}
	
	function GetMemberList(){
		$Mem = new MemberMod;
		$remembertable = $Mem->getMemberList();
		//echo $repapertable;
		echo json_encode(array('isSuccess' => true,'datatable'=>$remembertable)); 
    }
    
    function GetValidationInfo(){
        $memberid = $_POST["memberid"];
        $Mem = new MemberMod;
        $Mem -> Aid = $memberid;
		$dt = "";
        $dt = $Mem->Get_SelfInfo();
        $row=$dt->fetch_array(MYSQLI_ASSOC);
        $out['A_name']=$row['A_Fname']." ".$row['A_Lname'];
		$out['A_apply']=$row['A_apply'];
		$out['A_checkIdentity']=$row['A_checkIdentity'];
		$out['A_cardNo']=$row['A_cardNo'];
        $out['A_identityImg']=$row['A_identityImg'];
        $out['A_stuImg']=$row['A_stuImg'];
		echo json_encode(array('isSuccess' => true,'msg'=>"Success",'out'=>$out)); 
    }

    function UpdateValidationInfo(){
        $memberid = $_POST["memberid"];
        $checkIdentity = $_POST["checkIdentity"];
        $Mem = new MemberMod;
        $Mem -> Aid = $memberid;
        if($checkIdentity==0){
            $Mem->UpdateValidationInfo_notPass();
        }
        else if($checkIdentity!=0){
            $Mem -> A_checkIdentity = $checkIdentity;
            $Mem->UpdateValidationInfo();
        }
		echo json_encode(array('isSuccess' => true,'msg'=>"Success")); 
    }