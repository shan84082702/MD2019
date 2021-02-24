<?php
include("config.php");
class PaperMod{
	private $P_id;
	private $P_Title;
	private $P_Path;
	private $P_Topic;
	private $P_Session;
	private $P_Presentation;
	private $P_Author_String;
	public function getPaperList(){
		$paper_table=null;
		try{
			$mysqli_db = dbconect();
			$mysqli_db->query("SET NAMES utf8");
			$sql="SELECT `Pid`,`P_Title`,`P_Filepath`,paper.`CreatTime`,`S_Name`,`T_name`,concat(`A_Fname`,' ',`A_Lname`) as 'Name',`A_Email`,
            (CASE WHEN `isPass` IS null THEN '尚未審核' WHEN `isPass`='0' THEN 'N' ELSE 'Y' END) as isPass, 
            (CASE WHEN `P_Type`='0' THEN 'Oral' WHEN `P_Type`='1' THEN 'Poster' END) as Presentation,`Oid`,
            `O_isPay` FROM `paper` left join `order` on `order`.`O_Pid`=`paper`.`Pid` and O_isUsed='1' left join `session` 
            on `paper`.`P_Session`=`session`.`Sid` left join `topic` on `paper`.`P_Topic`=`topic`.`Tid`
			left join `account` on `account`.Aid=`paper`.P_Aid
            where P_Isused='1' order by `Pid`";
			$result=$mysqli_db->query($sql);
			$nums=mysqli_num_rows($result);
            $dataarray=array();
			if($nums>0)
			{
                $order_info="";
				while ($row = $result->fetch_object()) 
				{
                    if($row->Oid!=null){
                        if($row->O_isPay==0){
                            $order_info=$row->Oid."<strong>(尚未付款)</strong>";
                        }
                        else if($row->O_isPay==1){
                            $order_info=$row->Oid."(已付款)";
                        }
						else if($row->O_isPay==2){
                            $order_info=$row->Oid."(已付款(私下匯款))";
                        }
                    }
                    else if($row->Oid==null){
                        if($row->isPass=='Y'){
                            $order_info="<strong>尚未進行訂單註冊</strong>";
                        }
                        else if($row->isPass=='N'){
                            $order_info="投稿未通過";
                        }
                        else if($row->isPass=='尚未審核'){
                            $order_info="投稿尚未審核";
                        }
                    }
                    
                    $secsql = "SELECT `R_name` FROM `reviewer` LEFT JOIN `paper` ON `reviewer`.`Rid` = `paper`.`P_Main` WHERE `Pid` = '".$row->Pid."'";
					$secresult=$mysqli_db->query($secsql);
					$main_reviewer = "";
					$count=0;
					while ($secrow = $secresult->fetch_object()) 
					{
						$main_reviewer .= $secrow->R_name."(主審)";
					}

					$secsql = "SELECT `R_name` FROM `paper_reviewer` LEFT JOIN `reviewer` ON `paper_reviewer`.`Rid` = `reviewer`.`Rid` WHERE `Pid` = '".$row->Pid."'";
					$secresult=$mysqli_db->query($secsql);
					$reviewer = "";
					$secnums=mysqli_num_rows($secresult);
					$count=0;
					while ($secrow = $secresult->fetch_object()) 
					{
						if($count<$secnums-1){
							$reviewer .= $secrow->R_name."、";
						}
						else{
							$reviewer .= $secrow->R_name;
						}
						$count = $count + 1;
					}

					if($main_reviewer!="")
					{
						if($reviewer == "")
							$reviewer=$main_reviewer;
						else
							$reviewer=$main_reviewer."、".$reviewer;
					}
					
					$secsql = "SELECT concat(`Au_FName`,' ',`Au_LName`) as 'Name', `Au_Organization`, `Au_Mail` FROM `author` WHERE `Au_Pid` =  '".$row->Pid."' and Au_Isused='1'";
					$secresult=$mysqli_db->query($secsql);
					$author = "";
					$organization = "";
					$secnums=mysqli_num_rows($secresult);
					$count=0;
					while ($secrow = $secresult->fetch_object()) 
					{
						if($count<$secnums-1){
							$author .= $secrow->Name."(".$secrow->Au_Mail.")、<br/>";
							$organization .= $secrow->Au_Organization."、<br/>";
						}
						else{
							$author .= $secrow->Name."(".$secrow->Au_Mail.")";
							$organization .= $secrow->Au_Organization;
						}
						$count = $count + 1;
					}

					$secsql = "SELECT (CASE WHEN `Commend` IS null THEN 'N' ELSE 'Y' END) as 'isreview' FROM `paper` WHERE `Pid`=  '".$row->Pid."'";
					$secresult=$mysqli_db->query($secsql);
					$Main_Isreview = "";
					while ($secrow = $secresult->fetch_object()) 
					{	
						$Main_Isreview = $secrow->isreview."(主審)";
					}
					
					$secsql = "SELECT (CASE WHEN `Commend` IS null THEN 'N' ELSE 'Y' END) as 'isreview' FROM `paper_reviewer` WHERE `Pid`=  '".$row->Pid."'";
					$secresult=$mysqli_db->query($secsql);
					$secnums=mysqli_num_rows($secresult);
					$Isreview = "";
					$count=0;
					while ($secrow = $secresult->fetch_object()) 
					{
						if($count<$secnums-1){
							$Isreview .= $secrow->isreview."、";
						}
						else{
							$Isreview .= $secrow->isreview;
						}
						$count = $count + 1;
					}
					if($Main_Isreview!="")
					{
						if($Isreview == "")
							$Isreview=$Main_Isreview;
						else
							$Isreview=$Main_Isreview."、".$Isreview;
					}
					$temp = array("Id"=>$row->Pid,"Paper"=>"<a href='../CMS/".$row->P_Filepath."'>".$row->P_Title."</a>","Author"=>$author,"Main_Au"=>$row->Name,"Main_Email"=>$row->A_Email,"Orangization"=>$organization,"Session"=>$row->S_Name,"Topic"=>$row->T_name,"Presentation"=>$row->Presentation,"Reviewer"=>$reviewer,"Isreview"=>$Isreview,"Ispass"=>$row->isPass,"UploadTime"=>$row->CreatTime,"Action"=>"<button class='btn btn-primary' onclick=setpaper(".$row->Pid.") >指派/查看</button>","orderInfo"=>$order_info);
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
	public function getmaincommand($id){
		$recommand = "<h4><b>主審之評論</b></h4>";
		try{
			$mysqli_db = dbconect();
			$mysqli_db->query("SET NAMES utf8");
			$sql="SELECT  `paper`.`P_Main`,`paper`.`isPass`,`paper`.`Commend`,`reviewer`.R_name as 'name' 
					FROM `paper` LEFT JOIN `reviewer` 
					ON `paper`.`P_Main` = `reviewer`.`Rid` WHERE `Pid`=  '".$id."'";
			$result=$mysqli_db->query($sql);
			$nums=mysqli_num_rows($result);
			$name="";
			$isPass="";
			$commend="";
			if($nums>0)
			{
				while ($row = $result->fetch_object()) 
				{
					if($row->P_Main==null)
						$name="(尚未指定主審)";
					else
						$name=$row->name;
					if($row->isPass==null){
						$isPass="(主審尚未進行審核)";
					}
					else if($row->isPass==0){
						$isPass="未通過";
					}
					else if($row->isPass==1){
						$isPass="通過";
					}
					if($row->Commend==null){
						$commend="(無評論)";
					}
					else{
						$commend=$row->Commend;
					}
					$recommand .= "<h5>主審：".$name."</h5>";
					$recommand .= "<h5>論文是否通過：".$isPass."</h5>" ;
					$recommand .= "<div class='maincommand'><p style='margin-top:5px;margin-bottom:5px;margin-left:5px;margin-right:5px;'>".$commend."</p></div>" ;
				}
			}
		}
		catch(Exception $e){
		}
		return $recommand;
	}
	public function getcommand($id){
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
						$commend="(無評論)";
					}
					else{
						$commend=$row->command;
					}
					$recommand .= "<h5><button class='btn btn-primary' onclick='deletepr(".$row->PR_id.")'>Delete</button> ".$row->name."</h5>" ;
					$recommand .= "<div class='command'><p style='margin-top:5px;margin-bottom:5px;margin-left:5px;margin-right:5px;'>".$commend."</p></div>" ;
				}
			}
			else{
				$recommand .="<h5>尚未指定審查人員</h5>";
			}
		}
		catch(Exception $e){
		}
		return $recommand;
	}
	public function Checkreviewer($pid,$rid){
		$Rebol = false;
		$mysqli_db = dbconect();
		$sql = "SELECT * FROM `paper_reviewer` WHERE `Pid` ='".$pid."' AND `Rid` ='".$rid."' ";
		$result=$mysqli_db->query($sql);
		$nums=mysqli_num_rows($result);
		if($nums > 0)
		{
			$Rebol = true;
		}
		mysqli_close($mysqli_db);
		return $Rebol;
	}
	public function Addmain($pid,$rid){
		$mysqli_db = dbconect();
		$sql = "UPDATE `paper` set `P_Main`='".$rid."' WHERE `Pid` ='".$pid."';";
		$result=$mysqli_db->query($sql);
		
	}
	public function Checkmain($pid,$rid){
		$Rebol = false;
		$mysqli_db = dbconect();
		$sql = "SELECT * FROM `paper` WHERE `Pid` ='".$pid."' AND `P_Main` ='".$rid."' ";
		$result=$mysqli_db->query($sql);
		$nums=mysqli_num_rows($result);
		if($nums > 0)
		{
			$Rebol = true;
		}
		mysqli_close($mysqli_db);
		return $Rebol;
	}
	public function Addrtop($pid,$rid){
		$mysqli_db = dbconect();
		$sql = "SELECT * FROM `paper_reviewer` WHERE `Pid` ='".$pid."' AND `Rid` ='".$rid."' ";
		$result=$mysqli_db->query($sql);
		$nums=mysqli_num_rows($result);
		if($nums==0)
		{
			$secsql = "INSERT INTO `paper_reviewer`( `Pid`, `Rid`)VALUES ('".$pid."','".$rid."')";
			$mysqli_db->query($secsql);
		}
	}
	public function Delrtop($prid){
		$mysqli_db = dbconect();
		$sql = "DELETE FROM `paper_reviewer` WHERE `PR_id` = '".$prid."' ";
		$mysqli_db->query($sql);
	}
}
?>
