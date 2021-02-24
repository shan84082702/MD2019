<?php
include("config.php");
class MemMod{
	public $Aid="";
	public $Email = "";
	public $Psd= "";
	public $Title = "";
	public $FName ="";
	public $LName ="";
	public $Country = "";
	public $Orga = "";
	public $Depart ="";
	public $Street = "";
	public $City = "";
	public $Area = "";
	public $Code = "";
	public $Phone = "";
	public $Fax = "";
	public $isSub = "";
    public $Type="";
    public $apply="";
    public $checkIdentity="";
    public $cardNo="";
    public $identityImg="";
    public $stuImg="";

	public function Get_Acc(){
		$Reobj =null;
		try{
			$mysqli_db = dbconect();
			$mysqli_db->query("SET NAMES utf8");
			$sql = "SELECT * FROM `account` WHERE `A_Email` = '".$this->Email."' and `A_Psd` ='".$this->Psd."'";
			$result = $mysqli_db->query($sql);
			$Reobj =$result;
		}
		catch(Exception $e){
		}
		return $Reobj ;
	}
	public function InsertData(){
		$Rebol = false;
		try{
			$mysqli_db = dbconect();
			$mysqli_db->query("SET NAMES utf8");
			$sql = "INSERT INTO `account`(`A_Email`, `A_Psd`, `A_Fname`, `A_Lname`, `A_Title`, `A_Organization`, 
					`A_Department`, `A_Country`, `A_City`, `A_Street`, `A_Zipcode`, `A_Area`, `A_Phone`, `A_Fax`, `A_Issubscribe`)  
					VALUES ('".$this->Email."','".$this->Psd."','".$this->FName."','".$this->LName."','".$this->Title."',
					'".$this->Orga."','".$this->Depart."','".$this->Country."','".$this->City."','".$this->Street."',
					'".$this->Code."','".$this->Area."','".$this->Phone."','".$this->Fax."','".$this->isSub."')";
			$result = $mysqli_db->query($sql);
			$sql2= "SELECT MAX(`Aid`) as Aid FROM `account`;";
			$result2 = $mysqli_db->query($sql2);
			mysqli_close($mysqli_db);
			$Reobj =$result2;
		}
		catch(Exception $e){
		}
		return $Reobj;
	}
	public function Check_Same_Mail(){
		$Rebol = false;
		try{
			$mysqli_db = dbconect();
			$mysqli_db->query("SET NAMES utf8");
			$sql = "SELECT * FROM `account` WHERE `A_Email` = '".$this->Email."'";
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
    public function Get_Type(){
        $Reobj="";
        try{
			$mysqli_db = dbconect();
			$mysqli_db->query("SET NAMES utf8");
			$sql = "SELECT A_Type FROM `account` WHERE `Aid` = '".$this->Aid."'";
			$result = $mysqli_db->query($sql);
			$Reobj =$result;
		}
		catch(Exception $e){
		}
		return $Reobj;
	}
	public function Change_Type(){
        try{
			$mysqli_db = dbconect();
			$mysqli_db->query("SET NAMES utf8");
			$sql = "UPDATE `account` SET `A_Type`='".$this->Type."' WHERE `Aid`='".$this->Aid."';";
			$result=$mysqli_db->query($sql);
		}
		catch(Exception $e){
		}
	}
	
	public function Get_SelfInfo(){
        $Reobj="";
        try{
			$mysqli_db = dbconect();
			$mysqli_db->query("SET NAMES utf8");
			$sql = "SELECT * FROM `account` WHERE `Aid` = '".$this->Aid."'";
			$result = $mysqli_db->query($sql);
			$Reobj =$result;
		}
		catch(Exception $e){
		}
		return $Reobj;
    }
    public function ImgUpload(){
		$mysqli_db = dbconect();
		$mysqli_db->query("SET NAMES utf8");
		$sql = "Update `account` set `A_identityImg`='".$this->identityImg."' where `Aid`='".$this->Aid."';";	
		$mysqli_db->query($sql);
    }
    public function StuImgUpload(){
		$mysqli_db = dbconect();
		$mysqli_db->query("SET NAMES utf8");
		$sql = "Update `account` set `A_stuImg`='".$this->stuImg."' where `Aid`='".$this->Aid."';";	
		$mysqli_db->query($sql);
    }
    public function InfoUpload(){
		$mysqli_db = dbconect();
		$mysqli_db->query("SET NAMES utf8");
		$sql = "Update `account` set `A_apply`='".$this->apply."', `A_cardNo`='".$this->cardNo."' where `Aid`='".$this->Aid."' and `A_checkIdentity`!='".$this->apply."';";	
		$mysqli_db->query($sql);
	}
	public function Get_Psd(){
		$Reobj =null;
		try{
			$mysqli_db = dbconect();
			$mysqli_db->query("SET NAMES utf8");
			$sql = "SELECT * FROM `account` WHERE `A_Email` = '".$this->Email."'";
			$result = $mysqli_db->query($sql);
			$Reobj =$result;
		}
		catch(Exception $e){
		}
		return $Reobj ;
	}	
}
?>
