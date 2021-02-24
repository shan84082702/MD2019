<?php
include("config.php");
class PaperMod{
	public $P_id;
	private $P_Title;
	private $P_Topic;
	private $P_Session;
	private $P_Presentation;
	private $P_Path;
	private $P_Author_String;
	public $R_id;
	public $isPass;
	public $command;
	public function getMainReviewrPaperList($Rid){
		$paper_table=null;
		try{
			$mysqli_db = dbconect();
            $mysqli_db->query("SET NAMES utf8");
            $reviewer="";
            $actionbtn="";
			$sql="SELECT paper.Pid,paper.P_Title,paper.P_Filepath,paper.Commend,session.S_Name,
				topic.T_name,(CASE WHEN `P_Type`='0' THEN 'Oral' WHEN `P_Type`='1' THEN 'Poster' END) as Presentation 
				FROM paper,session,topic where `paper`.`P_Main`='$Rid' and `paper`.`P_Isused`='1' and 
				paper.P_Session=session.Sid and paper.P_Topic=topic.Tid order by Pid";
			$result=$mysqli_db->query($sql);
			$nums=mysqli_num_rows($result);
			$dataarray=array();
			if($nums>0)
			{
				while ($row = $result->fetch_object()) 
				{
					if($row->Commend!=NULL){
                        $reviewer="Y";
                        $actionbtn="<button class='btn btn-primary' onclick=setmainpaper(".$row->Pid.") >Edit</button>";
                    }
                    else{
                        $reviewer="N";
                        $actionbtn="<button class='btn btn-primary' onclick=setmainpaper(".$row->Pid.") >Review</button>";
                    }
					
					$secsql = "SELECT concat(`Au_FName`,' ',`Au_LName`) as 'Name', `Au_Organization` FROM `author` WHERE `Au_Pid` =  '".$row->Pid."' and Au_Isused='1'";
					$secresult=$mysqli_db->query($secsql);
					$author = "";
					$organization = "";
					$secnums=mysqli_num_rows($secresult);
					$count=0;
					while ($secrow = $secresult->fetch_object()) 
					{
						if($count<$secnums-1){
							$author .= $secrow->Name."、<br/>";
							$organization .= $secrow->Au_Organization."、<br/>";
						}
						else{
							$author .= $secrow->Name;
							$organization .= $secrow->Au_Organization;
						}
						$count = $count + 1;
					}
					$temp = array("Id"=>$row->Pid,"Paper"=>"<a href='../CMS/".$row->P_Filepath."'>".$row->P_Title."</a>","Author"=>$author,"Orangization"=>$organization,"Session"=>$row->S_Name,"Topic"=>$row->T_name,"Presentation"=>$row->Presentation,"Reviewer"=>$reviewer,"Action"=>$actionbtn);
					array_push($dataarray,$temp);
				}
			}
		}
		catch(Exception $e){
		}
		return $dataarray;
	}
	public function getReviewrPaperList($Rid){
		$paper_table=null;
		try{
			$mysqli_db = dbconect();
            $mysqli_db->query("SET NAMES utf8");
            $reviewer="";
            $actionbtn="";
			$sql="SELECT paper.Pid,paper.P_Title,paper.P_Filepath,paper_reviewer.Commend,
				(CASE WHEN `P_Type`='0' THEN 'Oral' WHEN `P_Type`='1' THEN 'Poster' END) as Presentation,
				T_name,S_Name FROM reviewer 
                LEFT JOIN paper_reviewer ON `reviewer`.`Rid` = `paper_reviewer`.`Rid` 
                LEFT JOIN paper ON `paper_reviewer`.`Pid` = `paper`.`Pid`
                LEFT JOIN topic ON `paper`.`P_Topic` = `topic`.`Tid`
                LEFT JOIN session ON `paper`.`P_Session` = `session`.`Sid`
                where `reviewer`.`Rid`='$Rid' and `paper`.`P_Isused`='1' order by Pid";
			$result=$mysqli_db->query($sql);
			$nums=mysqli_num_rows($result);
			$dataarray=array();
			if($nums>0)
			{
				while ($row = $result->fetch_object()) 
				{
					if($row->Commend!=NULL){
                        $reviewer="Y";
                        $actionbtn="<button class='btn btn-primary' onclick=setpaper(".$row->Pid.") >Edit</button>";
                    }
                    else{
                        $reviewer="N";
                        $actionbtn="<button class='btn btn-primary' onclick=setpaper(".$row->Pid.") >Review</button>";
                    }
					
					$secsql = "SELECT concat(`Au_FName`,' ',`Au_LName`) as 'Name', `Au_Organization` FROM `author` WHERE `Au_Pid` =  '".$row->Pid."' and Au_Isused='1'";
					$secresult=$mysqli_db->query($secsql);
					$author = "";
					$organization = "";
					$secnums=mysqli_num_rows($secresult);
					$count=0;
					while ($secrow = $secresult->fetch_object()) 
					{
						if($count<$secnums-1){
							$author .= $secrow->Name."、<br/>";
							$organization .= $secrow->Au_Organization."、<br/>";
						}
						else{
							$author .= $secrow->Name;
							$organization .= $secrow->Au_Organization;
						}
						$count = $count + 1;
					}
					$temp = array("Id"=>$row->Pid,"Paper"=>"<a href='../CMS/".$row->P_Filepath."'>".$row->P_Title."</a>","Author"=>$author,"Orangization"=>$organization,"Session"=>$row->S_Name,"Topic"=>$row->T_name,"Presentation"=>$row->Presentation,"Reviewer"=>$reviewer,"Action"=>$actionbtn);
					array_push($dataarray,$temp);
				}
			}
		}
		catch(Exception $e){
		}
		return $dataarray;
	}
	public function Paper($id){
		try{
			$mysqli_db = dbconect();
			$mysqli_db->query("SET NAMES utf8");
			$sql="SELECT `Pid`,`P_Title`,`P_Filepath`,`CreatTime`,(CASE WHEN `P_Type`='0' THEN 'Oral' WHEN `P_Type`='1' THEN 'Poster' END) as Presentation,`S_Name`,`T_name` FROM `paper`, `session`, `topic` where P_Isused='1' AND `paper`.`P_Session`=`session`.`Sid` AND `paper`.`P_Topic`=`topic`.`Tid` AND `Pid` = '".$id."';";
			$result=$mysqli_db->query($sql);
			$nums=mysqli_num_rows($result);
			$dataarray=array();
			if($nums>0)
			{
				
				while ($row = $result->fetch_object()) 
				{
					$this->P_Title = $row->P_Title;
					$this->P_Path = $row->P_Filepath;
					$this->P_Session = $row->S_Name;
					$this->P_Topic = $row->T_name;
					$this->P_Presentation = $row->Presentation;
					
					$secsql = "SELECT concat(`Au_FName`,'-',`Au_LName`) as 'Name', `Au_Organization` FROM `author` WHERE `Au_Pid` =  '".$row->Pid."'";
					$secresult=$mysqli_db->query($secsql);
					$author = "";
					$organization = "";
					$secnums=mysqli_num_rows($secresult);
					$count=0;
					while ($secrow = $secresult->fetch_object()) 
					{
						if($count<$secnums-1){
							$author .= $secrow->Name." (".$secrow->Au_Organization.")、";
						}
						else{
							$author .= $secrow->Name." (".$secrow->Au_Organization.")";
						}
						$count = $count + 1;
					}
					$this->P_Author_String = $author;
				}
			}
		}
		catch(Exception $e){
		}
	}
	public function getauthour(){
		$reauthour = "Author：".$this->P_Author_String;
		return $reauthour;
	}
	public function getPaper(){
		$repaper = "Title："."<a href='../CMS/".$this->P_Path."'>".$this->P_Title."</a>";
		return $repaper;
	}
	public function getTopic(){
		$repaper = "Topic：".$this->P_Topic;
		return $repaper;
	}
	public function getSession(){
		$repaper = "Session：".$this->P_Session;
		return $repaper;
	}
	public function getPresentation(){
		$repaper = "Presentation：".$this->P_Presentation;
		return $repaper;
	}
	public function getmainpass($id){
		try{
			$mysqli_db = dbconect();
			$mysqli_db->query("SET NAMES utf8");
			$sql="SELECT  `paper`.`isPass` FROM `paper` WHERE `Pid`=  '".$id."'";
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
	public function getmaincommand($id){
		try{
			$mysqli_db = dbconect();
			$mysqli_db->query("SET NAMES utf8");
			$sql="SELECT `Commend` FROM `paper` WHERE `Pid`= '".$id."';";
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
				}
			}
		}
		catch(Exception $e){
		}
		return $commend;
	}
	public function getothercommand($id){
		$recommand = "<h4><b>其他審查人員之評論</b></h4>";
		try{
			$mysqli_db = dbconect();
			$mysqli_db->query("SET NAMES utf8");
			$sql="SELECT  `PR_id`,`reviewer`.R_name as 'name',`paper_reviewer`.Commend as 'command' 
					FROM `paper_reviewer` 
					LEFT JOIN `reviewer` 
					ON `paper_reviewer`.`Rid` = `reviewer`.`Rid` 
					WHERE `Pid`=  '".$id."' and  `reviewer`.`R_isused` = 1;";
			$result=$mysqli_db->query($sql);
			$nums=mysqli_num_rows($result);
			if($nums>0)
			{
				$commend="";
				while ($row = $result->fetch_object()) 
				{	
					if($row->command==null){
						$commend="無評論";
					}
					else{
						$commend=$row->command;
					}
					$recommand .= "<h5>".$row->name."</h5>" ;
					$recommand .= "<div class='command'><p style='margin-top:5px;margin-bottom:5px;margin-left:5px;margin-right:5px;'>".$commend."</p></div>" ;
				}
			}
			else{
				$recommand .="<h5>尚未有其他審查人員</h5>";
			}
		}
		catch(Exception $e){
		}
		return $recommand;
    }
	public function getcommand($id, $Rid){
		$recommand = "";
		try{
			$mysqli_db = dbconect();
			$mysqli_db->query("SET NAMES utf8");
			$sql="SELECT * from paper_reviewer where Pid='$id' and Rid='$Rid'";
			$result=$mysqli_db->query($sql);
			while ($row = $result->fetch_object()) 
			{
                if($row->Commend!=NULL)
                    $recommand = $row->Commend;
			}
		}
		catch(Exception $e){
		}
		return $recommand;
	}
	public function MainReviewSubmit(){
		try{
			$mysqli_db = dbconect();
			$mysqli_db->query("SET NAMES utf8");
			$command=str_replace("'","\'",$this->command);
			$command=str_replace('"','\"',$command);
			$sql="UPDATE `paper` SET `Commend`='".$command."', `isPass`='".$this->isPass."' WHERE `Pid`='".$this->P_id."';";
			$result=$mysqli_db->query($sql);
			return $command;
		}
		catch(Exception $e){
		}
	}
    public function ReviewSubmit(){
		try{
			$mysqli_db = dbconect();
			$mysqli_db->query("SET NAMES utf8");
			$command=str_replace("'","\'",$this->command);
			$command=str_replace('"','\"',$command);
			$sql="UPDATE `paper_reviewer` SET `Commend`='".$command."' WHERE `Rid`='".$this->R_id."' and `Pid`='".$this->P_id."';";
			$result=$mysqli_db->query($sql);
		}
		catch(Exception $e){
		}
	}
}
?>
