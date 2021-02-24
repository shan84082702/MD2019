<?php
include("config.php");
class ReviewerMod{
	public function getReviewerList($type){
		$paper_table=null;
		try{
			$mysqli_db = dbconect();
			$mysqli_db->query("SET NAMES utf8");
			$sql="SELECT `Rid`, `R_name`, `R_account`, `R_password`, (CASE WHEN `R_IsAgree`='1' THEN 'Y' WHEN `R_IsAgree`='0' THEN 'N' ELSE '尚未確認' END) as `R_IsAgree`, `R_creattime` FROM `reviewer` WHERE `R_isused` = 1 and `R_type`=$type;";
			$result=$mysqli_db->query($sql);
			$nums=mysqli_num_rows($result);
			$dataarray=array();
			if($nums>0)
			{
				while ($row = $result->fetch_object()) 
				{
					$temp = array("Name"=>$row->R_name,"Mail"=>$row->R_account,"Pwd"=>$row->R_password,"IsAgree"=>$row->R_IsAgree,"CreatTime"=>$row->R_creattime,"Action"=>"<button  class='btn btn-primary' id='R_".$row->Rid."' onclick='DelReviewer(this.id)'>Delete</button>");
					array_push($dataarray,$temp);
				}
			}
			mysqli_close($mysqli_db);
		}
		catch(Exception $e){
		}
		return $dataarray;
	}
	public function getReviewerSelectoption($type){
		$relist="";
		try{
			$mysqli_db = dbconect();
			$mysqli_db->query("SET NAMES utf8");
			$sql="SELECT `Rid`, `R_name`, `R_account`, `R_password`, `R_creattime` FROM `reviewer` WHERE `R_isused` = 1 and `R_IsAgree` = 1 and `R_type` = $type;";
			$result=$mysqli_db->query($sql);
			$nums=mysqli_num_rows($result);
			if($nums>0)
			{
				$relist="<option value=0>---請選擇---</option>";
				while ($row = $result->fetch_object()) 
				{
					$relist .="<option value=".$row->Rid.">".$row->R_name."</option>";
				}
			}
			else{
				$relist="<option value=0>(尚無審核人員)</option>";
			}
		}
		catch(Exception $e){
		}
		return $relist;
	}
	public function findSameEmail($mail){
		try{
			$mysqli_db = dbconect();
			$mysqli_db->query("SET NAMES utf8");
			$sql="SELECT `A_Psd` FROM `account` WHERE `A_Email` = '".$mail."';";
			$result=$mysqli_db->query($sql);
			mysqli_close($mysqli_db);
		}
		catch(Exception $e){
		}
		return $result;
	}
	public function addReviwer($name,$mail,$psd,$type){
		$paper_table=null;
		$dataarray=array();
		try{
			$mysqli_db = dbconect();
			$mysqli_db->query("SET NAMES utf8");
			$sql="INSERT INTO `reviewer`(`R_name`, `R_account`, `R_password`, `R_type`) VALUES ('".$name."','".$mail."','".$psd."','".$type."');";
			$result=$mysqli_db->query($sql);
			$sql="SELECT @@IDENTITY as ID;";
			$result=$mysqli_db->query($sql);
			$row = $result->fetch_object();
			$temp = array("Name"=>$name,"Mail"=>$mail,"Pwd"=>$psd,"IsAgree"=>'尚未確認',"CreatTime"=>date("Y/m/d h:i:s"),"Action"=>"<button  class='btn btn-primary' id='R_".$row->ID."' onclick='DelReviewer(this.id)'>Delete</button>");
			array_push($dataarray,$temp);
			mysqli_close($mysqli_db);
		}
		catch(Exception $e){
		}
		return $dataarray;
	}
	public function delReviwer($id){
		try{
			$mysqli_db = dbconect();
			$mysqli_db->query("SET NAMES utf8");
			$sql="UPDATE `reviewer` SET `R_isused`=0 WHERE `Rid` = '".$id."';";
			$result=$mysqli_db->query($sql);
			$sql2="DELETE FROM `paper_reviewer` WHERE `Rid` = '".$id."';";
			$result2=$mysqli_db->query($sql2);
		}
		catch(Exception $e){
		}
		mysqli_close($mysqli_db);
		return True;
    }
    public function Check_Same_Mail($mail,$type){
		$Rebol = false;
		try{
			$mysqli_db = dbconect();
			$mysqli_db->query("SET NAMES utf8");
			$sql = "SELECT * FROM `reviewer` WHERE `R_account` = '".$mail."' and `R_type` = '".$type."' and `R_isused`='1'";
			$result = $mysqli_db->query($sql);
			$dtrows = mysqli_num_rows($result);
			if($dtrows > 0)
			{
				$Rebol = true;
			}
			mysqli_close($mysqli_db);
		}
		catch(Exception $e){
		}
		return $Rebol;
    }
}
?>
