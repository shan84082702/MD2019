<?php
	include("../Mod/CMSadmin0030MOD.php");
	if ($_SERVER['REQUEST_METHOD'] == "POST") {
		$token = $_POST["token"];
		$key = "md2019admin";
		$detoken = tokendecode(json_encode($token),$key);
        $token_obj = json_decode($detoken);
        if($_POST["action"] == "getreviewerlist" && ($token_obj->Rank == 2)){
			GetReviewerList();
		}
		else if($_POST["action"] == "selectlist" && ($token_obj->Rank == 2)){
			GetReviewerList_Selectoption();
		}
		else if($_POST["action"] == "addreviwer" && ($token_obj->Rank == 2)){
			AddReviewer();
		}
		else if($_POST["action"] == "delreviwer" && ($token_obj->Rank == 2)){
			DelReviwer();
		}
    }
    function GetReviewerList(){
		$Reviewer = new ReviewerMod;
        $maintable = $Reviewer->getReviewerList(1);
        $rereviewertable = $Reviewer->getReviewerList(2);
		//echo $repapertable;
		echo json_encode(array('isSuccess' => true,'datatable'=>$maintable,'re_datatable'=>$rereviewertable)); 
	}
	function GetReviewerList_Selectoption(){
		$Reviewer = new ReviewerMod;
        $mainlist = $Reviewer->getReviewerSelectoption(1);
        $rereviewerlist = $Reviewer->getReviewerSelectoption(2);
		//echo $repapertable;
		echo json_encode(array('isSuccess' => true,'list'=>$mainlist,'re_list'=>$rereviewerlist)); 
	}
	function AddReviewer(){
		$psd = "";
		$mail = $_POST["email"];
        $name = $_POST["name"];
        $type = $_POST["type"];
        $Reviewer = new ReviewerMod;
        if($Reviewer ->Check_Same_Mail($mail,$type)==false){
            //查看投稿系統有沒有相同的帳號 若有採取一樣的密碼
		    $dt = $Reviewer ->findSameEmail($mail);
		    $dtrows = mysqli_num_rows($dt);
		    if($dtrows==0){
			    $str = 'abcdefghijklmnopqrstuvwxyz1234567890'; 
			    $l = strlen($str); //取得字串長度  
			    //隨機取出 8 個字  
			    for($i=0; $i<8; $i++){  
		  		    $num=rand(0,$l-1);  
		  		    $psd.= $str[$num];  
			    } 
		    }
		    else{
			    $row = $dt->fetch_object();
			    $psd = $row->A_Psd;
		    }
		    $rereviewertable = $Reviewer->addReviwer($name,$mail,$psd,$type);
            echo json_encode(array('isSuccess' => true,'datatable'=>$rereviewertable));
        }
        else{
			echo json_encode(array('isSuccess' => false,'msg'=>"Email is repeat!!")); 
		}
	}
	function DelReviwer(){
		$psd = "";
		$id = $_POST["rid"];
		$Reviewer = new ReviewerMod;
		$Reviewer->delReviwer($id);
		unset($Reviewer);
		echo json_encode(array('isSuccess' => true)); 
	}
?>