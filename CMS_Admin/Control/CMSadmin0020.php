<?php
	include("../Mod/CMSadmin0020MOD.php");
	if ($_SERVER['REQUEST_METHOD'] == "POST") {
		$token = $_POST["token"];
		$key = "md2019admin";
		$detoken = tokendecode(json_encode($token),$key);
        $token_obj = json_decode($detoken);
        if($_POST["action"] == "getpaperlist" && ($token_obj->Rank == 2)){
			GetPaperList();
		}
		else if($_POST["action"] == "getpaper" && ($token_obj->Rank == 2)){
			GetPaper();
		}
		else if($_POST["action"] == "addmain" && ($token_obj->Rank == 2)){
			Addmain();
		}
		else if($_POST["action"] == "addrtop" && ($token_obj->Rank == 2)){
			Addrtop();
		}
		else if($_POST["action"] == "delpr" && ($token_obj->Rank == 2)){
			delrtop();
		}
    }
    function GetPaperList(){
		$Pap = new PaperMod;
		$repapertable = $Pap->getPaperList();
		//echo $repapertable;
		echo json_encode(array('isSuccess' => true,'datatable'=>$repapertable)); 
	}
	function GetPaper(){
		$pid  = $_POST["pid"];
		$Pap = new PaperMod;
		$Pap->Paper($pid);
		$repaper = $Pap->getPaper();
		$reauthour = $Pap->getauthour();
		$resession = $Pap->getSession();
		$retopic = $Pap->getTopic();
		$representation = $Pap->getPresentation();
		$recommand = $Pap->getcommand($pid);
		$mainrecommand = $Pap->getmaincommand($pid);
		//echo $repapertable;
		echo json_encode(array('isSuccess' => true,'paper'=>$repaper,'author'=>$reauthour,'session'=>$resession,'topic'=>$retopic,'presentation'=>$representation,'command'=>$recommand,'maincommand'=>$mainrecommand)); 
	}
	function Addmain(){
		$pid  = $_POST["pid"];
		$rid  = $_POST["rid"];
		$Pap = new PaperMod;
		if($Pap->Checkreviewer($pid,$rid)==false){
			$Pap->Addmain($pid,$rid);
			$recommand = $Pap->getmaincommand($pid);
			echo json_encode(array('isSuccess' => true,'command'=>$recommand)); 
		}
		else{
			echo json_encode(array('isRepeat' => true)); 
		}
	}
	function Addrtop(){
		$pid  = $_POST["pid"];
		$rid  = $_POST["rid"];
		$Pap = new PaperMod;
		if($Pap->Checkmain($pid,$rid)==false){
			$Pap->Addrtop($pid,$rid);
			$recommand = $Pap->getcommand($pid);
			echo json_encode(array('isSuccess' => true,'command'=>$recommand)); 
		}
		else{
			echo json_encode(array('isRepeat' => true)); 
		}
	}
	function delrtop(){
		$prid  = $_POST["prid"];
		$pid  = $_POST["pid"];
		$Pap = new PaperMod;
		$Pap->Delrtop($prid);
		$recommand = $Pap->getcommand($pid);
		echo json_encode(array('isSuccess' => true,'command'=>$recommand)); 
	}
?>