<?php
include("config.php");

class ReviewerMod{
    public $Acc = "";
    public $Psd= "";
    
    public function Get_Acc(){
		$Reobj =null;
		try{
			$mysqli_db = dbconect();
			$mysqli_db->query("SET NAMES utf8");
			$sql = "SELECT * FROM `reviewer` WHERE `R_account` = '".$this->Acc."' and `R_password` ='".$this->Psd."' and `R_isused`='1'";
			$result = $mysqli_db->query($sql);
			$Reobj =$result;
		}
		catch(Exception $e){
		}
		return $Reobj ;
	}
	public function Change_Psw(){
		$Reobj = "";
		try{
			$mysqli_db = dbconect();
			$mysqli_db->query("SET NAMES utf8");
			$sql = "UPDATE `reviewer` set `R_password` ='".$this->Psd."' WHERE `R_account` = '".$this->Acc."';";
			if($mysqli_db->query($sql)){
				$sql = "SELECT * FROM `reviewer` WHERE `R_account` = '".$this->Acc."' and `R_password` ='".$this->Psd."' and `R_isused`='1'";
				$result = $mysqli_db->query($sql);
				$Reobj =$result;
			}
		}
		catch(Exception $e){
		}
		return $Reobj ;
	}
	public function Reviewer_Change($Rid,$result){
		try{
			$mysqli_db = dbconect();
			$mysqli_db->query("SET NAMES utf8");
			$sql = "UPDATE `reviewer` set `R_IsAgree` ='".$result."' WHERE `Rid` = '".$Rid."';";
			$mysqli_db->query($sql);
		}
		catch(Exception $e){
		}
	}
	public function Get_Agree($Rid){
		$Reobj =null;
		try{
			$mysqli_db = dbconect();
			$mysqli_db->query("SET NAMES utf8");
			$sql = "SELECT * FROM `reviewer` WHERE `Rid` = '".$Rid."'";
			$result = $mysqli_db->query($sql);
			$Reobj =$result;
		}
		catch(Exception $e){
		}
		return $Reobj ;
	}
}
?>