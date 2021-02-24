<?php
use PHPMailer\PHPMailer\PHPMailer;
//Load composer's autoloader
require 'vendor/autoload.php';
include("config.php");
	$mysqli_db = dbconect();
	$mysqli_db->query("SET NAMES utf8");
	$sql = "SELECT * FROM `reviewer` WHERE `R_IsAgree` is null and `R_isused`='1' and `R_type`!='0'";
	$result=$mysqli_db->query($sql);
    $nums=mysqli_num_rows($result);
    $mail_succ=0;
	if($nums>0)
	{
		while ($row = $result->fetch_object()) 
		{
            $mail = new PHPMailer(); //建立新物件
			$mail->SMTPDebug = 2;
			$mail->IsSMTP(); //設定使用SMTP方式寄信
			$mail->SMTPAuth = true; //設定SMTP需要驗證
			$mail->SMTPSecure = false; 
			$mail->SMTPAutoTLS = false;
			$mail->SMTPSecure = "ssl"; // Gmail的SMTP主機需要使用SSL連線
			$mail->Host = "smtp.gmail.com"; //Gamil的SMTP主機
			$mail->Port = 465; //Gamil的SMTP主機的埠號(Gmail為465)。
			$mail->CharSet = "utf-8"; //郵件編碼
			$mail->Username = "md2019@pddlab.org"; //寄件人信箱md2019@pddlab.org
			$mail->Password = "md2019@pddlab.org"; //寄件人密碼
			$mail->From = "md2019@pddlab.org";
			$mail->FromName = "MD2019"; //寄件者姓名
			$mail->Subject = "Review Request for MD 2019 Paper"; //郵件標題
			$mail->Body = "<p> Dear ".$row->R_name."</p> 
			<p>
			Having the high level of knowledge in your research field the Programme Committee of MD2019 
			is inviting you to become a reviewer for our conference proceeding, participate in the 
			reviewing process and make recommendations to whether a manuscript is suitable for publishing 
			or not. MD2019 provides a platform for exchanging ideas and innovations about the latest 
			developments in the field of Medical Design among professionals around the world. With the 
			fast-growing of the ageing population, the smart health and state-of-art medical technologies 
			have fetched now world greatest concern. Recently, artificial intelligence and IoT become focal 
			subjects. It stimulates essential issues in those applications, where IoT works together with AI, 
			are only growing, creating new medical solutions and opportunities in the nearest future. 
			Therefore, we have “Designing for Health: Bridging the Gap Between Healthcare and Technology” 
			as our theme for this year’s conference. The programme committee of the MD2019 would like to 
			express its gratitude for your contribution to our conference. Please confirm below whether you 
			are willing to become part of our team of reviewers.
			</p>
			
			<p>
			Please log into the conference website to indicate whether you will undertake the review or not, 
			as well as to access the submission and to record your review and recommendation. The website is 
			http://www.mdassn.org/md2019/CMS_Admin/reviewerlogin.html
			</p>
			<p>Your account is ".$row->R_account."<br>
			Your password is ".$row->R_password."<br>
			You can change your password after you log into the website if you want.</p>

			<p>Thank you for your consideration.</p>
			
			<p>Chien-Lung Hsu<br>
			Program Committee Chair for MD 2019</p>

			<p>Kevin C. Tseng<br>
			General Chair for MD 2019</p>"; //郵件內容(忘記密碼的網頁)
			$mail->IsHTML(true); //郵件內容為html
			$mail->AddAddress($row->R_account); //收件者郵件及名稱
			if($mail->Send())
                $mail_succ++;    
		}
    }
    if($mail_succ==$nums)
        echo json_encode(array('isSuccess' => true));
    else
        echo json_encode(array('isSuccess' => false));
?>