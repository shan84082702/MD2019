<?php
	include("config.php");
	
class OrderMod{
	public function getOrderList($type){
		$paper_table=null;
		try{
			$mysqli_db = dbconect();
			$mysqli_db->query("SET NAMES utf8");
			if($type==0)
				$sql="SELECT `Oid`,`O_Pid`,`O_Aid`,`O_Name`,(CASE WHEN `O_Dietary`='0' THEN 
				'No special Dietary Needs' ELSE 'Vegan' END) as `O_Dietary` ,(CASE WHEN `O_Detailed` 
				is NULL THEN '無' WHEN `O_Detailed`='' Then '無' ELSE `O_Detailed` END) as 
				`O_Detailed`,`O_Money`,`O_Organization`,`O_type`, (CASE WHEN `O_isPay`='0' THEN 'N' 
				WHEN `O_isPay`='1' THEN 'Y' ELSE 'Y(私下匯款)' END) as `O_isPay`, `C_Name`, `P_Title` FROM `order` left join `paper` on 
				`paper`.Pid=`order`.O_Pid left join `country` on `country`.`Cid` = `order`.`O_Country` 
				where `order`.O_isUsed='1' and (`order`.O_Pid is null or `order`.O_Pid=0)";
			else if($type==1)
				$sql="SELECT `Oid`,`O_Pid`,`O_Aid`,`O_Name`,(CASE WHEN `O_Dietary`='0' THEN 
				'No special Dietary Needs' ELSE 'Vegan' END) as `O_Dietary` ,(CASE WHEN `O_Detailed` 
				is NULL THEN '無' WHEN `O_Detailed`='' Then '無' ELSE `O_Detailed` END) as 
				`O_Detailed`,`O_Money`,`O_Organization`,`O_type`, (CASE WHEN `O_isPay`='0' THEN 'N' 
				WHEN `O_isPay`='1' THEN 'Y' ELSE 'Y(私下匯款)' END) as `O_isPay`, `C_Name`, 
				`P_Title` FROM `order` left join `paper` on 
				`paper`.Pid=`order`.O_Pid left join `country` on `country`.`Cid` = `order`.`O_Country` 
				where `order`.O_isUsed='1' and `order`.O_Pid is not null and `order`.O_Pid !=0";
			$result=$mysqli_db->query($sql);
			$nums=mysqli_num_rows($result);
            $orderarray=array();
			if($nums>0)
			{
                $money_type="";
                $paper_info="";
				while ($row = $result->fetch_object()) 
				{
                    if ($row->O_type == 1)
                        $money_type = "Early bird-International-Delegate";
                    else if ($row->O_type == 2)
                        $money_type = "Early bird-International-Student";
                    else if ($row->O_type == 3)
                        $money_type = "Early bird-International-Non Presenter";
                    else if ($row->O_type == 4)
                        $money_type = "Early bird-Taiwan-Delegate";
                    else if ($row->O_type == 5)
                        $money_type = "Early bird-Taiwan-Student";
                    else if ($row->O_type == 6)
                        $money_type = "Early bird-Taiwan-Non Presenter";
                    else if ($row->O_type == 7)
                        $money_type = "Regular-International-Delegate";
                    else if ($row->O_type == 8)
                        $money_type = "Regular-International-Student";
                    else if ($row->O_type == 9)
                        $money_type = "Regular-International-Non Presenter";
                    else if ($row->O_type == 10)
                        $money_type = "Regular-Taiwan-Delegate";
                    else if ($row->O_type == 11)
                        $money_type = "Regular-Taiwan-Student";
                    else if ($row->O_type == 12)
                        $money_type = "Regular-Taiwan-Non Presenter";
                    else if ($row->O_type == 13)
                        $money_type = "Regular-Taiwan-Medical Design Members or Employees in Formosa Plastics Group";
                    else if ($row->O_type == 14)
                        $money_type = "Regular-International Partical Workshop-Delegate";
                    else if ($row->O_type == 15)
                        $money_type = "Regular-International Partical Workshop-Medical Design Members or Employees in Formosa Plastics Group";
                    else if ($row->O_type == 16)
                        $money_type = "Regular-Each Local Partical Workshop-Delegate";
                    else if ($row->O_type == 17)
                        $money_type = "Regular-Each Local Partical Workshop-Medical Design Members or Employees in Formosa Plastics Group";
                    
                    if($row->O_Pid==0){
                        $paper_info="Just attend Conference or Workshop";
                    }
                    else{
                        $paper_info="<a href='javascript: void(0)' onclick=setpaper(this.id) id='".$row->O_Pid."'>".$row->P_Title."</a>";
                    }

                    $temp = array("Id"=>"<a href='javascript: void(0)' onclick=orderdetail(this.id) id='".$row->Oid."'>".$row->Oid."</a>","Name"=>$row->O_Name,"Dietary"=>$row->O_Dietary,"Detailed"=>$row->O_Detailed,"Account"=>$row->O_Money ,"Organization"=>$row->O_Organization,"Country"=>$row->C_Name,"Type"=>$money_type,"isPay"=>$row->O_isPay,"paper"=>$paper_info);
					array_push($orderarray,$temp);
				}
			}
			else{
			}
		}
		catch(Exception $e){
		}
		return $orderarray;
    }
    public function getOrderDetail($oid){
		try{
			$mysqli_db = dbconect();
			$mysqli_db->query("SET NAMES utf8");
			$sql="SELECT `Oid`,`O_Aid`,`O_Pid`,`O_Name`,(CASE WHEN `O_Dietary`='0' THEN 
            'No special Dietary Needs' ELSE 'Vegan' END) as `O_Dietary`,(CASE WHEN `O_Detailed` 
            is NULL THEN '無' WHEN `O_Detailed`='' Then '無' ELSE `O_Detailed` END) as 
            `O_Detailed`,`O_Money`,`O_Organization`,`O_Country`,`O_type`,(CASE WHEN `O_isPay`='0' THEN 'N' 
				WHEN `O_isPay`='1' THEN 'Y' ELSE 'Y(私下匯款)' END) as `O_isPay`,
				`O_PaymentTime`,`O_Phone`,`A_Email`,`C_Name` FROM 
            `order`,`country`,`account` where `order`.O_isUsed='1' and 
            `country`.`Cid` = `order`.`O_Country` and `order`.`O_Aid`=`account`.`Aid` and `Oid`='$oid'";
			$result=$mysqli_db->query($sql);
			$nums=mysqli_num_rows($result);
            $orderarray=array();
            $money_type="";
			if($nums>0)
			{
				while ($row = $result->fetch_object()) 
				{
                    if ($row->O_type == 1)
                        $money_type = "Early bird-International-Delegate";
                    else if ($row->O_type == 2)
                        $money_type = "Early bird-International-Student";
                    else if ($row->O_type == 3)
                        $money_type = "Early bird-International-Non Presenter";
                    else if ($row->O_type == 4)
                        $money_type = "Early bird-Taiwan-Delegate";
                    else if ($row->O_type == 5)
                        $money_type = "Early bird-Taiwan-Student";
                    else if ($row->O_type == 6)
                        $money_type = "Early bird-Taiwan-Non Presenter";
                    else if ($row->O_type == 7)
                        $money_type = "Regular-International-Delegate";
                    else if ($row->O_type == 8)
                        $money_type = "Regular-International-Student";
                    else if ($row->O_type == 9)
                        $money_type = "Regular-International-Non Presenter";
                    else if ($row->O_type == 10)
                        $money_type = "Regular-Taiwan-Delegate";
                    else if ($row->O_type == 11)
                        $money_type = "Regular-Taiwan-Student";
                    else if ($row->O_type == 12)
                        $money_type = "Regular-Taiwan-Non Presenter";
                    else if ($row->O_type == 13)
                        $money_type = "Regular-Taiwan-Medical Design Members or Employees in Formosa Plastics Group";
                    else if ($row->O_type == 14)
                        $money_type = "Regular-International Partical Workshop-Delegate";
                    else if ($row->O_type == 15)
                        $money_type = "Regular-International Partical Workshop-Medical Design Members or Employees in Formosa Plastics Group";
                    else if ($row->O_type == 16)
                        $money_type = "Regular-Each Local Partical Workshop-Delegate";
                    else if ($row->O_type == 17)
                        $money_type = "Regular-Each Local Partical Workshop-Medical Design Members or Employees in Formosa Plastics Group";

                    $temp = array("Id"=>$row->Oid,"Name"=>$row->O_Name,"Dietary"=>$row->O_Dietary,"Detailed"=>$row->O_Detailed,"Account"=>$row->O_Money ,"Organization"=>$row->O_Organization,"Country"=>$row->C_Name,"Type"=>$money_type,"isPay"=>$row->O_isPay,"PaymentTime"=>$row->O_PaymentTime,"Phone"=>$row->O_Phone,"Email"=>$row->A_Email,"Pid"=>$row->O_Pid);
					array_push($orderarray,$temp);
				}
			}
			else{
			}
		}
		catch(Exception $e){
		}
		return $orderarray;
	}
}
?>