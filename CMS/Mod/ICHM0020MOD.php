<?php
include("config.php");
class PaperMod{
	private $P_id;
	private $P_Title;
    private $P_Session;
    private $P_Topic;
    private $P_Presentation;
	private $P_Path;
	private $P_Author_String;
	
	public function Set_PTitle($title){
		$this->P_Title = $title;
	}
	public function Set_PSession($session){
		$this->P_Session = $session;
    }
    public function Set_PTopic($topic){
		$this->P_Topic = $topic;
    }
    public function Set_PPresentation($presentation){
		$this->P_Presentation = $presentation;
	}
	public function Set_PAuthor($author_string){
		$this->P_Author_String = $author_string;
	}
	public function Set_Pid($Pid){
		$this->P_id = $Pid;
	}
	public function Get_Pid($Aid){
		$Reobj =null;
		try{
			$mysqli_db = dbconect();
			$mysqli_db->query("SET NAMES utf8");
			$sql="SELECT MAX(`Pid`) as Pid FROM `paper` WHERE P_Aid='".$Aid."';";
			$result=$mysqli_db->query($sql);
			$Reobj=$result;
		}
		catch(Exception $e){
		}
		return $Reobj ;
	}
    public function PaperUpload($Aid,$Path,$Path2){
		$mysqli_db = dbconect();
		$mysqli_db->query("SET NAMES utf8");
		$title=str_replace("'","\'",$this->P_Title);
		$title=str_replace('"','\"',$title);
        $sql = "INSERT INTO `paper` (`P_Title`, `P_Aid`, `P_Filepath`, `P_Filepath2`, `P_Session`, `P_Topic`, `P_Type`) VALUES ('".$title."', '".$Aid."', '".$Path."', '".$Path2."', '".$this->P_Session."','".$this->P_Topic."','".$this->P_Presentation."')";	
		$mysqli_db->query($sql);
		$sql= "SELECT MAX(`Pid`) as Pid FROM `paper` WHERE P_Aid='".$Aid."';";
		$result = $mysqli_db->query($sql);
		while($row = $result->fetch_object()){
			$this->P_id = $row->Pid;
		}
		$this->P_Author_String = json_decode($this->P_Author_String);
		foreach ($this->P_Author_String as $key => $value) 
		{
			$sql = "INSERT INTO `author` (`Au_Pid`, `Au_Order`, `Au_FName`, `Au_LName`, `Au_Mail`, `Au_Country`, `Au_Organization`, `Au_Department`, `Au_Isused`) 
			VALUES ('".$this->P_id."', '$value->Order', '$value->FName', '$value->LName', '$value->Email', '$value->Country', '$value->Organization', '$value->Department', '$value->isUsed')";
			$mysqli_db->query($sql);
		}
	}
	public function Get_PaperInfo($Pid){
		$Reobj =null;
		try{
			$mysqli_db = dbconect();
			$mysqli_db->query("SET NAMES utf8");
			$sql="SELECT * FROM `paper`, `session`, `topic` WHERE Pid='".$Pid."' and `paper`.P_Topic=`topic`.Tid and `paper`.P_Session=`session`.Sid;";
			$result=$mysqli_db->query($sql);
			$Reobj=$result;
		}
		catch(Exception $e){
		}
		return $Reobj;
	}
	public function Get_PaperAuthor($Pid){
		$Reobj =null;
		try{
			$mysqli_db = dbconect();
			$mysqli_db->query("SET NAMES utf8");
			$sql="SELECT * FROM `author` WHERE Au_Pid='".$Pid."';";
			$result=$mysqli_db->query($sql);
			$au_table ="";
			$au_table .= "<table><tr><th>Order</th> <th>Name</th> <th>Email</th> <th>Organization</th></tr>";
			while($row = $result->fetch_object()){
				$au_table .="<tr><td>".$row->Au_Order."</td> <td>".$row->Au_FName." ".$row->Au_LName."</td> <td>".$row->Au_Mail."</td> <td>".$row->Au_Organization."</td> <tr>";
			}
			$au_table .="</table>";
		}
		catch(Exception $e){
		}
		return $au_table ;
	}
	public function cancelSubmit($Pid){
		$mysqli_db = dbconect();
		$mysqli_db->query("SET NAMES utf8");
		$sql="Delete from `author` where Au_Pid='".$Pid."';";
		$result=$mysqli_db->query($sql);
		$sql="Delete from `paper` where Pid='".$Pid."';";
		$result=$mysqli_db->query($sql);
		mysqli_close($mysqli_db);
	}
	public function submitDone($Pid){
		$mysqli_db = dbconect();
		$mysqli_db->query("SET NAMES utf8");
		$sql="Update `author` set Au_Isused='1' where Au_Pid='".$Pid."';";
		$result=$mysqli_db->query($sql);
		$sql="Update `paper` set P_Isused='1' where Pid='".$Pid."';";
		$result=$mysqli_db->query($sql);
		mysqli_close($mysqli_db);
	}
	public function getSelfPaperList($Aid){
		$paper_table=null;
		try{
			$mysqli_db = dbconect();
			$mysqli_db->query("SET NAMES utf8");
			$sql="Select `Pid`, `P_Title`, (CASE WHEN `isPass` IS null THEN 'Unreviewed' WHEN `isPass`='0' THEN 'N' ELSE 'Y' END) as `isPass`, `CreatTime` from `paper` where P_Aid='".$Aid."' and P_Isused='1';";
			$result=$mysqli_db->query($sql);
			$nums=mysqli_num_rows($result);
			if($nums>0)
			{
				$paper_table="<table><tr><th>ID</th> <th>Title</th> <th>Pass or not</th> <th>Create Time</th></tr>";
				while ($row = $result->fetch_object()) 
				{
					$paper_table.="<tr><td>".$row->Pid."</td> <td><a href='javascript: void(0)' onclick=submittedpaper(this.id) id='".$row->Pid."'>".$row->P_Title."</a></td> <td><a href='javascript: void(0)' onclick=paperreview(this.id) id='".$row->Pid."'>".$row->isPass."</a></td> <td>".$row->CreatTime."</td><tr>";
				}
				$paper_table.="</table>";
			}
			else{
				$paper_table="You have not submitted any proposals (as corresponding author)";
			}
		}
		catch(Exception $e){
		}
		return $paper_table;
	}
	public function getSelfPaperList_Co($mail){
		$paper_table=null;
		try{
			$mysqli_db = dbconect();
			$mysqli_db->query("SET NAMES utf8");
			$sql="SELECT `paper`.`Pid`,`paper`.`P_Title`,
				(CASE WHEN `isPass` IS null THEN 'Unreviewed' WHEN `isPass`='0' THEN 'N' ELSE 'Y' END) as `isPass`,
				`paper`.`CreatTime` from  `paper`, `author`,  `account` 
				where `author`.`Au_Mail`='$mail' and `paper`.`Pid`= `author`.`Au_Pid` 
				and `paper`.`P_Aid`=`account`.`Aid` 
				and `account`.`A_Email`!=`author`.`Au_Mail`;";
			$result=$mysqli_db->query($sql);
			$nums=mysqli_num_rows($result);
			if($nums>0)
			{
				$paper_table="<table><tr><th>ID</th> <th>Title</th> <th>Pass or not</th> <th>Create Time</th></tr>";
				while ($row = $result->fetch_object()) 
				{
					$paper_table.="<tr><td>".$row->Pid."</td> <td><a href='javascript: void(0)' onclick=viewpaper(this.id) id='".$row->Pid."'>".$row->P_Title."</a></td> <td><a href='javascript: void(0)' onclick=paperreview(this.id) id='".$row->Pid."'>".$row->isPass."</a></td> <td>".$row->CreatTime."</td><tr>";
				}
				$paper_table.="</table>";
			}
			else{
				$paper_table="You have not submitted any proposals (as co-author)";
			}
		}
		catch(Exception $e){
		}
		return $paper_table;
	}
	public function Get_ESPAuthor($Pid){
		$Reobj =null;
		try{
			$mysqli_db = dbconect();
			$mysqli_db->query("SET NAMES utf8");
			$arrayOrder = array();
			$sql="SELECT * FROM `author` WHERE Au_Pid='".$Pid."';";
			$result=$mysqli_db->query($sql);
			$nums=mysqli_num_rows($result);
			$au_table ="";
			$au_table .= "<table><tr><th>Order</th> <th>Name</th> <th>Email</th> <th>Organization</th> <th>Action</th></tr>";
			$num=0;
			while($row = $result->fetch_object())
			{
				$select="";
				for($i=1; $i<=$nums; $i++){
					if($i==$row->Au_Order)
						$select.="<option value='".$i."' selected='true'>".$i."</option>";
					else
						$select.="<option value='".$i."'>".$i."</option>";
				}
				if($num==0){
					$au_table.="<tr><td><select id='".$row->Au_Mail."'>".$select."</select></td> <td>".$row->Au_FName." ".$row->Au_LName."</td> <td>".$row->Au_Mail."</td> 
				<td>".$row->Au_Organization."</td> <td></td> <td style='display:none'>".$row->Au_Country."</td> <td style='display:none'>".$row->Au_Department."</td> 
				<td style='display:none'>".$row->Au_FName."</td> <td style='display:none'>".$row->Au_LName."</td></tr>";
				}
				else{
					$au_table.="<tr><td><select id='".$row->Au_Mail."'>".$select."</select></td> <td>".$row->Au_FName." ".$row->Au_LName."</td> <td>".$row->Au_Mail."</td> 
				<td>".$row->Au_Organization."</td> <td><button id='".$row->Au_Order."1' onclick='rmauthor(this)'>Delete</button></td> <td style='display:none'>".$row->Au_Country."</td> <td style='display:none'>".$row->Au_Department."</td> 
				<td style='display:none'>".$row->Au_FName."</td> <td style='display:none'>".$row->Au_LName."</td></tr>";
				}
				array_push($arrayOrder, $row->Au_Order); 
				$num++;
			}
			$au_table .="</table>";
		}
		catch(Exception $e){
		}
		return $au_table ;
	}
	/*備份用*/
	/*
	public function EditPaperUpload($Path){
		$mysqli_db = dbconect();
		$mysqli_db->query("SET NAMES utf8");
		$sql = "Update `paper` set `P_Title`='".$this->P_Title."', `P_Filepath`='".$Path."', `P_Session`='".$this->P_Session."' where `Pid`='".$this->P_id."';";	
		$mysqli_db->query($sql);
		$sql = "Delete from author where Au_Pid='".$this->P_id."';";	
		$mysqli_db->query($sql);
		$this->P_Author_String = json_decode($this->P_Author_String);
		foreach ($this->P_Author_String as $key => $value) 
		{
			$sql = "INSERT INTO `author` (`Au_Pid`, `Au_Order`, `Au_FName`, `Au_LName`, `Au_Mail`, `Au_Country`, `Au_Organization`, `Au_Department`, `Au_Isused`) 
			VALUES ('".$this->P_id."', '$value->Order', '$value->FName', '$value->LName', '$value->Email', '$value->Country', '$value->Organization', '$value->Department', '$value->isUsed')";
			$mysqli_db->query($sql);
		}
	}
	*/
	public function EditPaperUpload($Path){
		$mysqli_db = dbconect();
		$mysqli_db->query("SET NAMES utf8");
		$sql = "Update `paper` set `P_Filepath`='".$Path."' where `Pid`='".$this->P_id."';";	
		$mysqli_db->query($sql);
	}
	public function EditPaperUpload2($Path){
		$mysqli_db = dbconect();
		$mysqli_db->query("SET NAMES utf8");
		$sql = "Update `paper` set `P_Filepath2`='".$Path."' where `Pid`='".$this->P_id."';";	
		$mysqli_db->query($sql);
	}
	public function EditPaperInfo(){
		$mysqli_db = dbconect();
		$mysqli_db->query("SET NAMES utf8");
		$title=str_replace("'","\'",$this->P_Title);
		$title=str_replace('"','\"',$title);
		$sql = "Update `paper` set `P_Title`='".$title."', `P_Session`='".$this->P_Session."', `P_Topic`='".$this->P_Topic."', `P_Type`='".$this->P_Presentation."' where `Pid`='".$this->P_id."';";	
		$mysqli_db->query($sql);
		$sql = "Delete from author where Au_Pid='".$this->P_id."';";	
		$mysqli_db->query($sql);
		$this->P_Author_String = json_decode($this->P_Author_String);
		foreach ($this->P_Author_String as $key => $value) 
		{
			$sql = "INSERT INTO `author` (`Au_Pid`, `Au_Order`, `Au_FName`, `Au_LName`, `Au_Mail`, `Au_Country`, `Au_Organization`, `Au_Department`, `Au_Isused`) 
			VALUES ('".$this->P_id."', '$value->Order', '$value->FName', '$value->LName', '$value->Email', '$value->Country', '$value->Organization', '$value->Department', '$value->isUsed')";
			$mysqli_db->query($sql);
		}
	}
	public function getmainpass(){
		try{
			$mysqli_db = dbconect();
			$mysqli_db->query("SET NAMES utf8");
			$sql="SELECT  `paper`.`isPass` FROM `paper` WHERE `Pid`=  '".$this->P_id."'";
			$result=$mysqli_db->query($sql);
			$nums=mysqli_num_rows($result);
			$isPass="";
			if($nums>0)
			{
				while ($row = $result->fetch_object()) 
				{
					$isPass=$row->isPass;
				}
			}
		}
		catch(Exception $e){
		}
		return $isPass;
	}
	public function getmaincommand(){
		try{
			$mysqli_db = dbconect();
			$mysqli_db->query("SET NAMES utf8");
			$sql="SELECT `Commend` FROM `paper` WHERE `Pid`= '".$this->P_id."';";
			$result=$mysqli_db->query($sql);
			$nums=mysqli_num_rows($result);
			$commend="";
			if($nums>0)
			{
				while ($row = $result->fetch_object()) 
				{
					if($row->Commend!=null){
						$commend=$row->Commend;
					}
					if($row->Commend==null){
						$commend='(No comment)';
					}
				}
			}
		}
		catch(Exception $e){
		}
		return $commend;
	}
	public function getothercommand(){
		$recommand = "";
		try{
			$mysqli_db = dbconect();
			$mysqli_db->query("SET NAMES utf8");
			$sql="SELECT `Commend` as 'command' FROM `paper_reviewer` WHERE `Pid`= '".$this->P_id."' and `Commend` is NOT NULL;";
			$result=$mysqli_db->query($sql);
			$nums=mysqli_num_rows($result);
			if($nums>0)
			{
				$commend="";
				while ($row = $result->fetch_object()) 
				{
					$commend=$row->command;
					$recommand .= "<textarea style='height:100px;margin-top:5px;' disabled>".$commend."</textarea>" ;
				}
			}
			else{
				$recommand="<div class='row' style='margin-top:5px;margin-left:5%;margin-top:5px;'><h6>(No comment)</h6></div>";
			}
		}
		catch(Exception $e){
		}
		return $recommand;
    }
}
?>
