<?php
use PHPMailer\PHPMailer\PHPMailer;
//Load composer's autoloader
require 'vendor/autoload.php';
include("config.php");
	$mysqli_db = dbconect();
	$mysqli_db->query("SET NAMES utf8");
	$sql = "SELECT distinct `paper_reviewer`.`Rid` as ID, `reviewer`.`R_name`,`reviewer`.`R_account`,
	`reviewer`.`R_password` FROM `paper_reviewer` left join `reviewer` on `reviewer`.`Rid`=`paper_reviewer`.`Rid` 
	WHERE `Commend` is null 
	UNION 
	SELECT distinct `paper`.`P_Main` as ID, `reviewer`.`R_name`,`reviewer`.`R_account`,`reviewer`.`R_password` 
	FROM `paper` left join `reviewer` on `reviewer`.`Rid`=`paper`.`P_Main` WHERE `paper`.`Commend` is null 
	and `paper`.`isPass` is null and `paper`.`P_isUsed`=1 and `paper`.`P_Main` is not null";
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
							There are some paper submissions needing your review and recommendation.<br>
							Please log into the conference website to access your review and recommendation of the submissions.
							</p>
							
							<p>The website is http://www.mdassn.org/md2019/CMS_Admin/reviewerlogin.html <br>
							Your account is ".$row->R_account."<br>
							Your password is ".$row->R_password."</p>

							<p>Thank you for considering this request.</p>

							<p>Program Committee,<br> 
							2019 International Conference on Medical Design (MD2019)<br>
							http://www.mdassn.org/md2019/</p>"; //郵件內容(忘記密碼的網頁)
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