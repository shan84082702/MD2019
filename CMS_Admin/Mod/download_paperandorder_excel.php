<?php
	require_once 'PHPExcel-1.8/PHPExcel-1.8/Classes/PHPExcel.php';
	header("Content-Type:text/html;charset=utf-8");
	header("Content-Type:application/vnd.ms-excel;charset=UTF-8 ");
	$objPHPExcel = new PHPExcel();
	$mysqli_db = mysqli_connect("localhost","md2019","md35795709","md2019") or die("CONNECTION FAIL");
	$mysqli_db->query("SET NAMES utf8");
	$sql="SELECT `Pid`,`P_Title`,`P_Filepath`,paper.`CreatTime`,`S_Name`,`T_name`,
		concat(`A_Fname`,' ',`A_Lname`) as 'Name',`A_Email`,`O_Aid`,
        (CASE WHEN `isPass` IS null THEN '尚未審核' WHEN `isPass`='0' THEN 'N' ELSE 'Y' END) as isPass, 
        (CASE WHEN `P_Type`='0' THEN 'Oral' WHEN `P_Type`='1' THEN 'Poster' END) as Presentation,`Oid`,
        `O_isPay`,`O_Money`,`O_type`,(CASE WHEN `O_Dietary`='0' THEN '無' WHEN `O_Dietary`='1' THEN '素食' END) as `O_Dietary`,`O_Name`
		FROM `paper` left join `order` on `order`.`O_Pid`=`paper`.`Pid` and O_isUsed='1' left join `session` 
        on `paper`.`P_Session`=`session`.`Sid` left join `topic` on `paper`.`P_Topic`=`topic`.`Tid`
		left join `account` on `account`.Aid=`paper`.P_Aid
        where P_Isused='1' order by `Pid`";
	$result=$mysqli_db->query($sql);
	$arr=array('論文編號','論文名稱','作者','通訊作者','通訊作者Email','單位','Session','Topic','報告形式','審查人員','審查狀況','訂單編號','繳費者姓名','繳費者EAMIL','繳費身分','繳納費用','付費狀況','特殊飲食要求');
	$objPHPExcel->getActiveSheet()->fromArray($arr);
	$index=0;
	while ($row = $result->fetch_object()) 
	{	
		$order_info="";
		$orderID="";
		if($row->Oid!=null){
			if($row->O_isPay==0){
				$order_info="尚未付款";
				$orderID=$row->Oid;
			}
			else if($row->O_isPay==1){
				$order_info="已付款";
				$orderID=$row->Oid;
			}
			else if($row->O_isPay==2){
				$order_info="已付款(私下匯款)";
				$orderID=$row->Oid;
			}
		}
		else if($row->Oid==null){
			if($row->isPass=='Y'){
				$orderID="無";
			}
			else if($row->isPass=='N'){
				$orderID="無(論文投稿未通過)";
			}
			else if($row->isPass=='尚未審核'){
				$orderID="無(論文投稿尚未審核)";
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
				$author .= $secrow->Name."(".$secrow->Au_Mail.")、";
				$organization .= $secrow->Au_Organization."、";
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
		
		$secsql = "SELECT `A_Email` FROM `account` where `Aid` = '".$row->O_Aid."'";
		$secresult=$mysqli_db->query($secsql);
		$order_email = "";
		if ($secrow = $secresult->fetch_object()) 
		{
			$order_email= $secrow->A_Email;
		}
		
		$money_type="";
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
		
		$real_index=$index+2;
		$objPHPExcel->setActiveSheetIndex(0)
				->setCellValue('A'.$real_index,$row->Pid)
				->setCellValue('B'.$real_index,$row->P_Title)
				->setCellValue('C'.$real_index,$author)
				->setCellValue('D'.$real_index,$row->Name)
				->setCellValue('E'.$real_index,$row->A_Email)
				->setCellValue('F'.$real_index,$organization)
				->setCellValue('G'.$real_index,$row->S_Name)
				->setCellValue('H'.$real_index,$row->T_name)
				->setCellValue('I'.$real_index,$row->Presentation)
				->setCellValue('J'.$real_index,$reviewer)
				->setCellValue('K'.$real_index,$Isreview)
				->setCellValueExplicit('L'.$real_index,$orderID,PHPExcel_Cell_DataType::TYPE_STRING)
				->setCellValue('M'.$real_index,$row->O_Name)
				->setCellValue('N'.$real_index,$order_email)
				->setCellValue('O'.$real_index,$money_type)
				->setCellValue('P'.$real_index,$row->O_Money)
				->setCellValue('Q'.$real_index,$order_info)
				->setCellValue('R'.$real_index,$row->O_Dietary);
		$index++;
		
	}
	$objPHPExcel->getActiveSheet()->setTitle('投稿論文繳費狀況');
	
	$objPHPExcel->createSheet();
	$objPHPExcel->setActiveSheetIndex(1);
	$sql2="SELECT `Oid`,`O_Pid`,`O_Aid`,`O_Name`,(CASE WHEN `O_Dietary`='0' THEN 
				'無' ELSE '素食' END) as `O_Dietary` ,(CASE WHEN `O_Detailed` 
				is NULL THEN '無' WHEN `O_Detailed`='' Then '無' ELSE `O_Detailed` END) as 
				`O_Detailed`,`O_Money`,`O_Organization`,`O_type`, (CASE WHEN `O_isPay`='0' THEN 'N' 
				WHEN `O_isPay`='1' THEN 'Y' ELSE 'Y(私下匯款)' END) as `O_isPay`, 
				`C_Name`, `A_Email` FROM `order` left join `paper` on 
				`paper`.Pid=`order`.O_Pid left join `country` on `country`.`Cid` = `order`.`O_Country` 
				left join `account` on `O_Aid`=`Aid` 
				where `order`.O_isUsed='1' and (`order`.O_Pid is null or `order`.O_Pid=0);";
	$result2=$mysqli_db->query($sql2);
	$arr2=array('訂單編號','姓名','EMAIL','單位', '特殊飲食要求', '繳費身分', '繳納費用', '繳費狀況');
	$objPHPExcel->getActiveSheet()->fromArray($arr2);
	$index2=0;
	while($row = $result2->fetch_object()){
		$money_type="";
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
		
		$real_index2=$index2+2;
		$objPHPExcel->setActiveSheetIndex(1)
					->setCellValueExplicit('A'.$real_index2,$row->Oid,PHPExcel_Cell_DataType::TYPE_STRING)
					->setCellValue('B'.$real_index2,$row->O_Name)
					->setCellValue('C'.$real_index2,$row->A_Email)
					->setCellValue('D'.$real_index2,$row->O_Organization)
					->setCellValue('E'.$real_index2,$row->O_Dietary)
					->setCellValue('F'.$real_index2,$money_type)
					->setCellValue('G'.$real_index2,$row->O_Money)
					->setCellValue('H'.$real_index2,$row->O_isPay);
		$index2++;
	}
	$objPHPExcel->getActiveSheet()->setTitle('未投稿之報名訂單');
	
	//匯出成EXCEL
	header('Content-Type: application/vnd.ms-excel');
	header('Content-Disposition: attachment;filename="MD2019投稿論文訂單暨未投稿訂單整理_'.date('Ymd').'.xls"');
	header('Cache-Control: max-age=0');
	$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
	$objWriter->save('php://output');
?>