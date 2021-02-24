<?php
	include("../Mod/ICHM0010MOD.php");
	if ($_SERVER['REQUEST_METHOD'] == "POST") {
		$token = $_POST["token"];
		if($token == "none"){
			if($_POST["action"] == "login"){
				LogInMem();
			}
			else if($_POST["action"] == "regist"){
				ResMember();
			} 
			else if($_POST["action"] == "forgetpsd"){
				ForGetPsd();
			}
			
		}
		else{
			$key = "CGUAdmin2019";
			$detoken = tokendecode(json_encode($token),$key);
			$token_obj = json_decode($detoken);
			if($_POST["action"] == "getself"){
				GetSelfMem($token_obj);
            }
            else if($_POST["action"] == "gettype"){
				GetSelfType($token_obj);
			}
			else if($_POST["action"] == "changetype"){
				ChangeType($token_obj);
			}
			else if($_POST["action"] == "getOrderSelfInfo"){
				GetOrderSelfInfo($token_obj);
            }
            else if($_POST["action"] == "getValidationInfo"){
				GetValidationInfo($token_obj);
            }
            elseif($_POST["action"] == "uploadIdentityInfo"){
                UploadIdentityInfo($token_obj);
            }
        }
	}
	function LogInMem(){
		$retoken = "";
		$strEmail = $_POST['Email'];
		$strPsw = $_POST['Psw'];
		$findacc = new MemMod;
		$findacc -> Email = $strEmail;
		$findacc -> Psd = $strPsw;
		$dt = "";
		$dt = $findacc ->Get_Acc();
		$dtrows = mysqli_num_rows($dt);
		if($dtrows > 0)
		{
			//取得會員資料 和key包裝成token
			$row = $dt->fetch_object();
			$token['Aid'] = $row->Aid;
			$token['Email'] = $strEmail;
			$token['FName'] = $row->A_Fname;
			$token['LName'] = $row->A_Lname;
			$token['Title'] = $row->A_Title;
			$token['Country'] = $row->A_Country;
			$token['Organization'] = $row->A_Organization;
			$token['Depart'] = $row->A_Department;
			$token['Phone'] = $row->A_Phone;
			$key = "CGUAdmin2019";
			$retoken = tokenencode(json_encode($token),$key);
			echo json_encode(array('isSuccess' => true,'msg'=>"Success",'token'=>$retoken,'FName'=>$token['FName'],'LName'=>$token['LName'],'Aid'=>$token['Aid'])); 
		}
		else{
			echo json_encode(array('isSuccess' => false,'msg'=>"Wrong E-mail or password!")); 
		}
		return;
	}
	function ResMember() {
		$retoken = "";
		$strEmail = $_POST['Email'];
		$strPsd = $_POST['Psd'];
		$strFName = $_POST['FName'];
		$strLName = $_POST['LName'];
		$strTitle = $_POST['Title'];
		$strCountry = $_POST['Country'];
		$strOrga = $_POST['Orga'];
		$strDepart = $_POST['Depart'];
		$strStreet = $_POST['Street'];
		$strCity = $_POST['City'];
		$strArea = $_POST['Area'];
		$strCode = $_POST['Code'];
		$strPhone = $_POST['Phone'];
		$strFax = $_POST['Fax'];
		$isSub = $_POST['isSub'];
		
		$addmem = new MemMod;
		$addmem -> Email = $strEmail;
		if($addmem ->Check_Same_Mail()==false)
		{
			$addmem -> Psd= $strPsd;
			$addmem -> Title = $strTitle;
			$addmem -> FName = $strFName;
			$addmem -> LName = $strLName;
			$addmem -> Country = $strCountry;
			$addmem -> Orga = $strOrga;
			$addmem -> Depart = $strDepart;
			$addmem -> Street = $strStreet;
			$addmem -> City = $strCity;
			$addmem -> Area = $strArea;
			$addmem -> Code = $strCode;
			$addmem -> Phone = $strPhone;
			$addmem -> Fax = $strFax;
			$addmem -> isSub = $isSub;
			$dt = "";
			$dt = $addmem ->InsertData();
			$dtrows = mysqli_num_rows($dt);
			if($dtrows > 0){
				$row = $dt->fetch_object();
				$token['Aid'] = $row->Aid;
				$token['Email'] = $strEmail;
			 	$token['FName'] = $strFName;
				$token['LName'] = $strLName;
				$token['Title'] = $strTitle;
				$token['Country'] = $strCountry;
				$token['Organization'] = $strOrga;
				$token['Depart'] = $strDepart;
				$token['Phone'] = $strPhone;
				$key = "CGUAdmin2019";
				$retoken = tokenencode(json_encode($token),$key);
				echo json_encode(array('isSuccess' => true,'msg'=>"Success",'token'=>$retoken,'FName'=>$token['FName'],'LName'=>$token['LName'],'Aid'=>$token['Aid']));
			}
		}
		else{
			echo json_encode(array('isSuccess' => false,'msg'=>"Email is repeat!!")); 
		}
		return;
		
	}
	function GetSelfMem($token_obj){
		echo json_encode(array('isSuccess' => true,'Email'=>$token_obj->Email,'FName'=>$token_obj->FName,'LName'=>$token_obj->LName,'Dept'=>$token_obj->Depart,'Country'=>$token_obj->Country,'Orga'=>$token_obj->Organization,'Phone'=>$token_obj->Phone,'Title'=>$token_obj->Title)); 
    }
    function GetSelfType($token_obj){
        $findacc = new MemMod;
        $findacc -> Aid = $token_obj->Aid;
		$dt = "";
        $dt = $findacc->Get_Type();
        $row = $dt->fetch_object();
        $type = $row->A_Type;
		echo json_encode(array('isSuccess' => true,'msg'=>"Success",'type'=>$type)); 
	}
	function ChangeType($token_obj){
		$strType = $_POST['type'];
		$findacc = new MemMod;
		$findacc -> Aid = $token_obj->Aid;
		$findacc -> Type= $strType;
		$dt = "";
        $dt = $findacc->Change_Type();
		echo json_encode(array('isSuccess' => true,'msg'=>"Success")); 
	}
	function GetOrderSelfInfo($token_obj){
        $findacc = new MemMod;
        $findacc -> Aid = $token_obj->Aid;
		$dt = "";
        $dt = $findacc->Get_SelfInfo();
		$row=$dt->fetch_array(MYSQLI_ASSOC);
		$out['A_Fname']=$row['A_Fname'];
		$out['A_Lname']=$row['A_Lname'];
		$out['A_Phone']=$row['A_Phone'];
        $out['A_Organization']=$row['A_Organization'];
        $out['A_Street']=$row['A_Street'];
        $out['A_Country']=$row['A_Country'];
        $out['A_Area']=$row['A_Area'];
        $out['A_City']=$row['A_City'];
        $out['A_Zipcode']=$row['A_Zipcode'];
		echo json_encode(array('isSuccess' => true,'msg'=>"Success",'out'=>$out)); 
    }
    function GetValidationInfo($token_obj){
        $findacc = new MemMod;
        $findacc -> Aid = $token_obj->Aid;
		$dt = "";
        $dt = $findacc->Get_SelfInfo();
		$row=$dt->fetch_array(MYSQLI_ASSOC);
		$out['A_apply']=$row['A_apply'];
		$out['A_checkIdentity']=$row['A_checkIdentity'];
        $out['A_cardNo']=$row['A_cardNo'];
        $out['A_stuImg']=$row['A_stuImg'];
        $out['A_identityImg']=$row['A_identityImg'];
		echo json_encode(array('isSuccess' => true,'msg'=>"Success",'out'=>$out)); 
    }
    function UploadIdentityInfo($token_obj){
        $apply = $_POST["apply"];
        $cardNo = $_POST["cardNo"];
        $findacc = new MemMod;
        $findacc -> Aid = $token_obj->Aid;
        $findacc -> apply = $apply;
        $findacc -> cardNo = $cardNo;
        $filename=$token_obj->Aid.date('Ymdhis',time());
        $filename2=$token_obj->Aid.date('Ymdhis',time())."_stu";
        $dest="";
        $dest2="";
        if($_FILES['upimg']['name']!=""){
            if ($_FILES['upimg']['error'] === UPLOAD_ERR_OK) {
                $extension=pathinfo($_FILES['upimg']['name'],PATHINFO_EXTENSION);
                $dest = "identity_img\/".$filename.".".$extension;
                move_uploaded_file($_FILES['upimg']['tmp_name'],sprintf('../identity_img/%s',$filename.".".$extension));
                $findacc -> identityImg = $dest;
                $findacc->ImgUpload();
            }
        }
        if($_FILES['upimg2']['name']!=""){
            if ($_FILES['upimg2']['error'] === UPLOAD_ERR_OK) {
                $extension=pathinfo($_FILES['upimg2']['name'],PATHINFO_EXTENSION);
                $dest2 = "identity_img\/".$filename2.".".$extension;
                move_uploaded_file($_FILES['upimg2']['tmp_name'],sprintf('../identity_img/%s',$filename2.".".$extension));
                $findacc -> stuImg = $dest2;
                $findacc->StuImgUpload();
            }
        }
        $findacc->InfoUpload();
        echo json_encode(array('isSuccess' => true,'msg'=>"上傳成功"));
    }
	function ForGetPsd() {
		try{ 
			$St = new MemMod;
			$strEmail = $_POST['Email'];
			if (!preg_match("/([\w\-]+\@[\w\-]+\.[\w\-]+)/", $strEmail)) {
				echo json_encode(array('isSuccess' => false,'msg'=>"Email format error!")); 
				return ;
			}
			$St -> Email=$strEmail;
			$dt = "";
			$dt = $St ->Get_Psd();
			$dtrows = mysqli_num_rows($dt);
			if($dtrows > 0)
			{
				$row = $dt->fetch_object();
				echo json_encode(array('isSuccess' => true,'password'=>$row->A_Psd,'name'=>$row->A_Fname." ".$row->A_Lname));  
			}
			else{
				echo json_encode(array('isSuccess' => false,'msg'=>"The Email is not exit!"));
				return ;
			}
			return ;
		}
		catch(Exception $e){
			echo json_encode(array('isSuccess' => false));
			return;
		}
	}

?>