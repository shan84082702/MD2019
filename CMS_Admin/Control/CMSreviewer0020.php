<?php
	include("../Mod/CMSreviewer0020MOD.php");
	if ($_SERVER['REQUEST_METHOD'] == "POST") {
        $token = $_POST["token"];
		$key = "md2019admin";
		$detoken = tokendecode(json_encode($token),$key);
        $token_obj = json_decode($detoken);
        if($_POST["action"] == "getMainReviewrPaperList" && ($token_obj->Rank == 1)){
			getMainReviewrPaperList();
        }
		if($_POST["action"] == "getReviewrPaperList" && ($token_obj->Rank == 1)){
			getReviewrPaperList();
        }
        if($_POST["action"] == "getmainpaper" && ($token_obj->Rank == 1)){
			getmainreviewpaper();
        }
        if($_POST["action"] == "getpaper" && ($token_obj->Rank == 1)){
			getreviewpaper();
        }
        if($_POST["action"] == "mainreviewsubmit" && ($token_obj->Rank == 1)){
			mainreviewsubmit();
		}
        if($_POST["action"] == "reviewsubmit" && ($token_obj->Rank == 1)){
			reviewsubmit();
		}
    }
    function getMainReviewrPaperList(){
        $Rid=$_POST["Rid"];
        $Pap = new PaperMod;
		$repapertable = $Pap->getMainReviewrPaperList($Rid);
		//echo $repapertable;
		echo json_encode(array('isSuccess' => true,'datatable'=>$repapertable)); 
    }
	function getReviewrPaperList(){
        $Rid=$_POST["Rid"];
        $Pap = new PaperMod;
		$repapertable = $Pap->getReviewrPaperList($Rid);
		//echo $repapertable;
		echo json_encode(array('isSuccess' => true,'datatable'=>$repapertable)); 
    }
    function getmainreviewpaper(){
        $pid  = $_POST["pid"];
        $Rid  = $_POST["Rid"];
		$Pap = new PaperMod;
		$Pap->Paper($pid);
		$repaper = $Pap->getPaper();
		$reauthour = $Pap->getauthour();
		$resession = $Pap->getSession();
		$retopic = $Pap->getTopic();
		$representation = $Pap->getPresentation();
        $recommand = $Pap->getothercommand($pid);
        $isPass = $Pap->getmainpass($pid);
        $mainrecommand = $Pap->getmaincommand($pid);
		//echo $repapertable;
        echo json_encode(array('isSuccess' => true,'paper'=>$repaper,'author'=>$reauthour,'session'=>$resession,'topic'=>$retopic,'presentation'=>$representation,'command'=>$recommand,'isPass'=>$isPass,'maincommand'=>$mainrecommand)); 
    }
    function getreviewpaper(){
        $pid  = $_POST["pid"];
        $Rid  = $_POST["Rid"];
		$Pap = new PaperMod;
		$Pap->Paper($pid);
		$repaper = $Pap->getPaper();
		$reauthour = $Pap->getauthour();
		$resession = $Pap->getSession();
		$retopic = $Pap->getTopic();
		$representation = $Pap->getPresentation();
		$recommand = $Pap->getcommand($pid, $Rid);
		//echo $repapertable;
		echo json_encode(array('isSuccess' => true,'paper'=>$repaper,'author'=>$reauthour,'session'=>$resession,'topic'=>$retopic,'presentation'=>$representation,'command'=>$recommand)); 
    }
    function mainreviewsubmit(){
        $pid  = $_POST["pid"];
        $Rid  = $_POST["Rid"];
        $isPass  = $_POST["isPass"];
        $command  = $_POST["command"];
        $Pap = new PaperMod;
		$Pap->P_id = $pid;
		$Pap->R_id = $Rid;
		$Pap->isPass = $isPass;
		$Pap->command = $command;
        $result=$Pap->MainReviewSubmit();
        echo json_encode(array('isSuccess' => true,'result' => $result)); 
    }
    function reviewsubmit(){
        $pid  = $_POST["pid"];
        $Rid  = $_POST["Rid"];
        $command  = $_POST["command"];
        $Pap = new PaperMod;
		$Pap->P_id = $pid;
		$Pap->R_id = $Rid;
		$Pap->command = $command;
        $Pap->ReviewSubmit();
        echo json_encode(array('isSuccess' => true)); 
    }
?>