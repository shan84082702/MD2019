<?php
	include("../Mod/ICHM0020MOD.php");
	if ($_SERVER['REQUEST_METHOD'] == "POST") {
		$token = $_POST["token"];
		$key = "CGUAdmin2019";
		$detoken = tokendecode(json_encode($token),$key);
        $token_obj = json_decode($detoken);
        if($_POST["action"] == "uploadpaper"){
          PaperUpload($token_obj);
		}
		else if($_POST["action"] == "getPid"){
			getPid($token_obj);
		}
		else if($_POST["action"] == "getPaperInfo"){
			getPaperInfo();
		}
		else if($_POST["action"] == "getPaperAuthor"){
			getPaperAuthor();
		}
		else if($_POST["action"] == "cancelSubmit"){
			cancelSubmit();
		}
		else if($_POST["action"] == "submitDone"){
			submitDone();
		}
		else if($_POST["action"] == "getSelfPaperList"){
			getSelfPaperList($token_obj);
		}
		else if($_POST["action"] == "getESPAuthor"){
			getESPAuthor();
		}
		else if($_POST["action"] == "uploadeditpaper"){
			EditPaperUpload($token_obj);
		}
		else if($_POST["action"] == "getpaperreview"){
			getpaperreview();
		}
    }
    function PaperUpload($token_obj){
			$title = $_POST["Title"];
            $session = $_POST["Session"];
            $topic = $_POST["Topic"];
            $presentation = $_POST["Presentation"];
			$author_string = $_POST["Author_string"];
			$dest = "";
			$dest2 = "";
			$file_succ = false;
			$file_succ2 = false;
			$Pap = new PaperMod;
			$Pap->Set_PTitle($title);
            $Pap->Set_PSession($session);
            $Pap->Set_PTopic($topic);
            $Pap->Set_PPresentation($presentation);
			$Pap->Set_PAuthor($author_string);
			$filename=$token_obj->Aid.date('Ymdhis',time());
			if ($_FILES['upfile']['error'] === UPLOAD_ERR_OK) {
				$dest = "uploads\/".$filename.".pdf";
				move_uploaded_file($_FILES['upfile']['tmp_name'],sprintf('../uploads/%s',$filename.".pdf"));	
				$file_succ = true;
			}
			if ($_FILES['upfile2']['error'] === UPLOAD_ERR_OK) {
				$extension=pathinfo($_FILES['upfile2']['name'],PATHINFO_EXTENSION);
				if(in_array($extension,array('docx'))){
					$dest2 = "uploads\/".$filename.".docx";
					move_uploaded_file($_FILES['upfile2']['tmp_name'],sprintf('../uploads/%s',$filename.".docx"));
				}
				else if(in_array($extension,array('doc'))){
					$dest2 = "uploads\/".$filename.".doc";
					move_uploaded_file($_FILES['upfile2']['tmp_name'],sprintf('../uploads/%s',$filename.".doc"));
				}
				$file_succ2 = true;
			}
			if($file_succ && $file_succ2){
				$Pap->PaperUpload($token_obj->Aid,$dest,$dest2);
				echo json_encode(array('isSuccess' => true,'msg'=>"上傳成功"));
			}
			else{
				echo "<script>alert('上傳失敗')</script>"; 
			}
		}
		function getPid($token_obj){
			$Pap = new PaperMod;
			$dt = "";
			$dt = $Pap->Get_Pid($token_obj->Aid);
			$dtrows = mysqli_num_rows($dt);
			if($dtrows > 0){
				$row = $dt->fetch_object();
				echo json_encode(array('isSuccess' => true,'msg'=>"Success",'Pid'=>$row->Pid));
			}
		}
		function getPaperInfo(){
			$Pid = $_POST["Pid"];
			$Pap = new PaperMod;
			$dt = "";
			$dt = $Pap->Get_PaperInfo($Pid);
			$dtrows = mysqli_num_rows($dt);
			if($dtrows > 0){
				$row = $dt->fetch_object();
				echo json_encode(array('isSuccess' => true,'msg'=>"Success",'Title'=>$row->P_Title,'Path'=>$row->P_Filepath,'Path2'=>$row->P_Filepath2,'Session'=>$row->S_Name, 'SessionID'=>$row->P_Session,'Topic'=>$row->T_name, 'TopicID'=>$row->P_Topic,'PresentationID'=>$row->P_Type));
			}
		}
		function getPaperAuthor(){
			$Pid = $_POST["Pid"];
			$Pap = new PaperMod;
			$au_table = "";
			$au_table = $Pap -> Get_PaperAuthor($Pid);
			echo json_encode(array('isSuccess' => true,'au_table'=>$au_table)); 
		}
		function cancelSubmit(){
			$Pid = $_POST["Pid"];
			$Pap = new PaperMod;
			$Pap->cancelSubmit($Pid);
			echo json_encode(array('isSuccess' => true,'msg'=>"Submition is canceled!")); 
		}
		function submitDone(){
			$Pid = $_POST["Pid"];
			$Pap = new PaperMod;
			$Pap->submitDone($Pid);
			echo json_encode(array('isSuccess' => true,'msg'=>"Submition is successful!"));
		}
		function getSelfPaperList($token_obj){
			$Pap = new PaperMod;
			$paper_table = "";
			$paper_table = $Pap->getSelfPaperList($token_obj->Aid);
			$paper_table_co = "";
			$paper_table_co = $Pap->getSelfPaperList_Co($token_obj->Email);
			echo json_encode(array('isSuccess' => true,'paper_table'=>$paper_table,'paper_table_co'=>$paper_table_co));
		}
		function getESPAuthor(){
			$Pid = $_POST["Pid"];
			$Pap = new PaperMod;
			$au_table = "";
			$au_table = $Pap -> Get_ESPAuthor($Pid);
			echo json_encode(array('isSuccess' => true,'au_table'=>$au_table)); 
		}
		function EditPaperUpload($token_obj){
			$title = $_POST["Title"];
            $session = $_POST["Session"];
            $topic = $_POST["Topic"];
            $presentation = $_POST["Presentation"];
			$author_string = $_POST["Author_string"];
			$Pid = $_POST["Pid"];
			$dest = "";
			$dest2 = "";
			$Pap = new PaperMod;
			$Pap->Set_PTitle($title);
			$Pap->Set_PSession($session);
			$Pap->Set_PAuthor($author_string);
            $Pap->Set_PTopic($topic);
            $Pap->Set_PPresentation($presentation);
			$Pap->Set_Pid($Pid);
			$filename=$token_obj->Aid.date('Ymdhis',time());
			if($_FILES['upfile']['name']!=""){
				if ($_FILES['upfile']['error'] === UPLOAD_ERR_OK) {
					$dest = "uploads\/".$filename.".pdf";
					move_uploaded_file($_FILES['upfile']['tmp_name'],sprintf('../uploads/%s',$filename.".pdf"));
					$Pap->EditPaperUpload($dest);
				}
			}
			if($_FILES['upfile2']['name']!=""){
				if ($_FILES['upfile2']['error'] === UPLOAD_ERR_OK) {
					$extension=pathinfo($_FILES['upfile2']['name'],PATHINFO_EXTENSION);
					$tempfile = $_FILES['upfile2']['tmp_name'];
					if(in_array($extension,array('docx'))){
						$dest2 = "uploads\/".$filename.".docx";
						move_uploaded_file($_FILES['upfile2']['tmp_name'],sprintf('../uploads/%s',$filename.".docx"));
					}
					else if(in_array($extension,array('doc'))){
						$dest2 = "uploads\/".$filename.".doc";
						move_uploaded_file($_FILES['upfile2']['tmp_name'],sprintf('../uploads/%s',$filename.".doc"));
					}
					$Pap->EditPaperUpload2($dest2);
				}
			}
			$Pap->EditPaperInfo();
			echo json_encode(array('isSuccess' => true,'msg'=>"上傳成功"));
		}

		function getpaperreview(){
			$pid  = $_POST["pid"];
			$Pap = new PaperMod;
			$Pap->Set_Pid($pid);
        	$recommand = $Pap->getothercommand();
        	$isPass = $Pap->getmainpass();
        	$mainrecommand = $Pap->getmaincommand();
        	echo json_encode(array('isSuccess' => true,'command'=>$recommand,'isPass'=>$isPass,'maincommand'=>$mainrecommand)); 
		}
?>