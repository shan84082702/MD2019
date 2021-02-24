<?php
	include("config.php");
	
class MemberMod{
    public $Aid="";
    public $A_checkIdentity="";
	public function getMemberList(){
		$paper_table=null;
		try{
			$mysqli_db = dbconect();
			$mysqli_db->query("SET NAMES utf8");
			$sql="SELECT `Aid`, `A_Fname`,`A_Lname`,`A_Title`,`A_Email`,`A_Psd`,`A_Phone`,`A_Organization`,`A_Department`,`A_apply`,`A_checkIdentity`,`C_Name` FROM `account` LEFT JOIN `country` ON `country`.`Cid` = `account`.`A_Country`;";
			$result=$mysqli_db->query($sql);
			$nums=mysqli_num_rows($result);
			$dataarray=array();
			if($nums>0)
			{
				while ($row = $result->fetch_object()) 
				{
                    $iv_text="";
                    if ($row->A_apply == 1) {
                        if ($row->A_checkIdentity == 1) {
                            $iv_text="國外投稿者<br>(非學生)";
                        }
                        else if ($row->A_checkIdentity == 2) {
                            $iv_text="<button class='btn btn-primary' onclick=validaionPage(".$row->Aid.") >已驗證</button><br>台灣投稿者<br>(非學生)";
                        }
                        else if ($row->A_checkIdentity == 3) {
                            $iv_text="<button class='btn btn-primary' onclick=validaionPage(".$row->Aid.") >已驗證</button><br>台灣醫學設計<br>學會會員(非學生)";
                        }
                        else if ($row->A_checkIdentity == 4) {
                            $iv_text="<button class='btn btn-primary' onclick=validaionPage(".$row->Aid.") >已驗證</button><br>台塑集團員工<br>(非學生)";
                        }
                        else if ($row->A_checkIdentity == 5) {
                            $iv_text="<button class='btn btn-primary' onclick=validaionPage(".$row->Aid.") >已驗證</button><br>台灣投稿者<br>(學生)";
                        }
                        else if ($row->A_checkIdentity == 6) {
                            $iv_text="<button class='btn btn-primary' onclick=validaionPage(".$row->Aid.") >已驗證</button><br>台灣醫學設計<br>學會會員(學生)";
                        }
                        else if ($row->A_checkIdentity == 7) {
                            $iv_text="<button class='btn btn-primary' onclick=validaionPage(".$row->Aid.") >已驗證</button><br>台塑集團員工<br>(學生)";
                        }
                        else if ($row->A_checkIdentity == 8) {
                            $iv_text="<button class='btn btn-primary' onclick=validaionPage(".$row->Aid.") >已驗證</button><br>國外學生";
                        }
                    }
                    else if ($row->A_apply == 2) {
                        if ($row->A_checkIdentity == 1) {
                            $iv_text="<button class='btn btn-danger' onclick=validaionPage(".$row->Aid.") >進行驗證</button>";
                        }
                    }
                    else if ($row->A_apply == 3) {
                        if ($row->A_checkIdentity == 1) {
                            $iv_text="<button class='btn btn-danger' onclick=validaionPage(".$row->Aid.") >進行驗證</button>";
                        }
                        else if ($row->A_checkIdentity == 2) {
                            $iv_text="<button class='btn btn-danger' onclick=validaionPage(".$row->Aid.") >進行驗證</button>";
                        }
                    }
                    else if ($row->A_apply == 4) {
                        if ($row->A_checkIdentity == 1) {
                            $iv_text="<button class='btn btn-danger' onclick=validaionPage(".$row->Aid.") >進行驗證</button>";
                        }
                        else if ($row->A_checkIdentity == 2) {
                            $iv_text="<button class='btn btn-danger' onclick=validaionPage(".$row->Aid.") >進行驗證</button>";
                        }
                    }
                    else if ($row->A_apply == 5) {
                        if ($row->A_checkIdentity == 1) {
                            $iv_text="<button class='btn btn-danger' onclick=validaionPage(".$row->Aid.") >進行驗證</button>";
                        }
                        else if ($row->A_checkIdentity == 2) {
                            $iv_text="<button class='btn btn-danger' onclick=validaionPage(".$row->Aid.") >進行驗證</button>";
                        }
                    }
                    else if ($row->A_apply == 6) {
                        if ($row->A_checkIdentity == 1) {
                            $iv_text="<button class='btn btn-danger' onclick=validaionPage(".$row->Aid.") >進行驗證</button>";
                        }
                        else if ($row->A_checkIdentity == 2) {
                            $iv_text="<button class='btn btn-danger' onclick=validaionPage(".$row->Aid.") >進行驗證</button>";
                        }
                        else if ($row->A_checkIdentity == 3) {
                            $iv_text="<button class='btn btn-danger' onclick=validaionPage(".$row->Aid.") >進行驗證</button>";
                        }
                        else if ($row->A_checkIdentity == 5) {
                            $iv_text="<button class='btn btn-danger' onclick=validaionPage(".$row->Aid.") >進行驗證</button>";
                        }
                    }
                    else if ($row->A_apply == 7) {
                        if ($row->A_checkIdentity == 1) {
                            $iv_text="<button class='btn btn-danger' onclick=validaionPage(".$row->Aid.") >進行驗證</button>";
                        }
                        else if ($row->A_checkIdentity == 2) {
                            $iv_text="<button class='btn btn-danger' onclick=validaionPage(".$row->Aid.") >進行驗證</button>";
                        }
                        else if ($row->A_checkIdentity == 4) {
                            $iv_text="<button class='btn btn-danger' onclick=validaionPage(".$row->Aid.") >進行驗證</button>";
                        }
                        else if ($row->A_checkIdentity == 5) {
                            $iv_text="<button class='btn btn-danger' onclick=validaionPage(".$row->Aid.") >進行驗證</button>";
                        }
                    }
                    else if ($row->A_apply == 8) {
                        if ($row->A_checkIdentity == 1) {
                            $iv_text="<button class='btn btn-danger' onclick=validaionPage(".$row->Aid.") >進行驗證</button>";
                        }
                    }
                    $temp = array("Id"=>$row->Aid,"Fname"=>$row->A_Fname,"Lname"=>$row->A_Lname,"Title"=>$row->A_Title,"Email"=>$row->A_Email ,"Psd"=>$row->A_Psd,"Phone"=>$row->A_Phone,"Organization"=>$row->A_Organization,"Department"=>$row->A_Department,"Country"=>$row->C_Name,"Text"=>$iv_text);
					array_push($dataarray,$temp);
				}
			}
			else{
			}
		}
		catch(Exception $e){
		}
		return $dataarray;
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
    public function UpdateValidationInfo_notPass(){
        try{
			$mysqli_db = dbconect();
			$mysqli_db->query("SET NAMES utf8");
			$sql = "Update `account` set A_apply='1' where `Aid` = '".$this->Aid."'";
			$result = $mysqli_db->query($sql);
			$Reobj =$result;
		}
		catch(Exception $e){
		}
    }
    public function UpdateValidationInfo(){
        try{
			$mysqli_db = dbconect();
			$mysqli_db->query("SET NAMES utf8");
			$sql = "Update `account` set A_apply='1', A_checkIdentity = '".$this->A_checkIdentity."' where `Aid` = '".$this->Aid."'";
			$result = $mysqli_db->query($sql);
			$Reobj =$result;
		}
		catch(Exception $e){
		}
    }
}
?>