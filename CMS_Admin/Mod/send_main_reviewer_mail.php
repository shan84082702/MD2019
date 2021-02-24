<?php
use PHPMailer\PHPMailer\PHPMailer;
//Load composer's autoloader
require 'vendor/autoload.php';
include("config.php");
	$Pid  = $_POST["pid"];
	$Rid  = $_POST["rid"];
	$mysqli_db = dbconect();
	$mysqli_db->query("SET NAMES utf8");
	$sql = "SELECT `Pid`,`P_Title`,`Rid`,`R_name`,`R_account`,`R_password` FROM `paper`, `reviewer` WHERE `Pid`='".$Pid."' and `Rid`='".$Rid."';";
	$result=$mysqli_db->query($sql);
	$row = $result->fetch_object();
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
					The submission <span style='color:rgb(192, 0, 0)'>".$row->P_Title."</span> needs your review and recommendation.<br>
					Please log into the conference website to access your review and recommendation of the submission.
					</p>
					
					<p>The website is http://www.mdassn.org/md2019/CMS_Admin/reviewerlogin.html <br>
					Your account is ".$row->R_account."<br>
					Your password is ".$row->R_password."</p>

					<p>Thank you for considering this request.</p>

					<p>Program Committee,<br> 
					2019 International Conference on Medical Design (MD2019)<br>
					http://www.mdassn.org/md2019/</p>"; //郵件內容(忘記密碼的網頁)
	//$mail->addAttachment('../uploadfile/file/dirname.png', 'new.jpg'); //附件，改以新的檔名寄出
	$mail->IsHTML(true); //郵件內容為html
	$mail->AddAddress($row->R_account); //收件者郵件及名稱
	if($mail->Send()){
		echo json_encode(array('isSuccess' => true,'msg'=>"寄信成功")); 
	}
?>