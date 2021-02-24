<?php
	include("../Mod/CMSreviewer0010MOD.php");
	if ($_SERVER['REQUEST_METHOD'] == "POST") {
		if($_POST["action"] == "login"){
			LogInReviewer();
		}
		if($_POST["action"] == "check"){
			checkPsw();
		}
		if($_POST["action"] == "change"){
			changePsw();
		}
		if($_POST["action"] == "isAgree"){
			isAgree();
		}
		if($_POST["action"] == "checkAgree"){
			checkAgree();
		}
	}
	function LogInReviewer(){
        $retoken = "";
        $Rank=1;
		$strAcc = $_POST['Acc'];
		$strPsw = $_POST['Psw'];
		$findacc = new ReviewerMod;
		$findacc -> Acc = $strAcc;
		$findacc -> Psd = $strPsw;
		$dt = "";
		$dt = $findacc ->Get_Acc();
		$dtrows = mysqli_num_rows($dt);
		if($dtrows > 0)
		{
			//取得會員資料 和key包裝成token
            $row = $dt->fetch_object();
            $token['Rid'] = $row->Rid;
			$token['Acc'] = $strAcc;
            $token['Psw'] = $strPsw;
            $token['Rank'] = $Rank;
			$key = "md2019admin";
			$retoken = tokenencode(json_encode($token),$key);
			echo json_encode(array('isSuccess' => true,'msg'=>"Success",'token'=>$retoken,'IsAgree'=>$row->R_IsAgree,'Rid'=>$row->Rid)); 
		}
		else{
			echo json_encode(array('isSuccess' => false,'msg'=>"Wrong E-mail or password!")); 
		}
		return;
	}
	function checkPsw(){
		$token = $_POST['token'];
		$key = "md2019admin";
		$detoken = tokendecode(json_encode($token),$key);
        $token_obj = json_decode($detoken);
		$strPsw = $_POST['Psw'];
		if($token_obj->Psw == $strPsw)
		{
			echo json_encode(array('isSuccess' => true)); 
		}
		else{
			echo json_encode(array('isSuccess' => false,'msg'=>"密碼錯誤!")); 
		}
		return;
	}
	function changePsw(){
		$token = $_POST['token'];
		$key = "md2019admin";
		$detoken = tokendecode(json_encode($token),$key);
        $token_obj = json_decode($detoken);
		$strPsw = $_POST['NewPsw'];
		$changePsw = new ReviewerMod;
		$changePsw -> Acc = $token_obj->Acc;
		$changePsw -> Psd = $strPsw;
		$dt = $changePsw ->Change_Psw();
		$dtrows = mysqli_num_rows($dt);
		if($dtrows > 0)
		{
			$retoken = "";
        	$Rank=1;
			//取得會員資料 和key包裝成token
            $row = $dt->fetch_object();
            $newtoken['Rid'] = $row->Rid;
			$newtoken['Acc'] = $row->R_account;
            $newtoken['Psw'] = $strPsw;
            $newtoken['Rank'] = $Rank;
			$key = "md2019admin";
			$retoken = tokenencode(json_encode($newtoken),$key);
			echo json_encode(array('isSuccess' => true,'msg'=>"Success",'token'=>$retoken,'Rid'=>$row->Rid)); 
		}
		else{
			echo json_encode(array('isSuccess' => false,'msg'=>"發生錯誤!")); 
		}
	}
	function isAgree(){
		$token = $_POST['token'];
		$result = $_POST['result'];
		$key = "md2019admin";
		$detoken = tokendecode(json_encode($token),$key);
        $token_obj = json_decode($detoken);
		$isAgree = new ReviewerMod;
		$dt = $isAgree ->Reviewer_Change($token_obj->Rid,$result);
		echo json_encode(array('isSuccess' => true)); 
	}
	function checkAgree(){
		$token = $_POST['token'];
		$key = "md2019admin";
		$detoken = tokendecode(json_encode($token),$key);
        $token_obj = json_decode($detoken);
		$getAgree = new ReviewerMod;
		$dt = $getAgree ->Get_Agree($token_obj->Rid);
		$dtrows = mysqli_num_rows($dt);
		if($dtrows > 0)
		{
			$row = $dt->fetch_object();
			if($row->R_IsAgree==NULL)
				echo json_encode(array('notCheck' => true));
			else if($row->R_IsAgree==1)
				echo json_encode(array('notCheck' => false,'agree' => true));
			else if($row->R_IsAgree==0)
				echo json_encode(array('notCheck' => false,'agree' => false));
		}
	}
?>