<?php
use PHPMailer\PHPMailer\PHPMailer;
//Load composer's autoloader
include("config.php");
require 'vendor/autoload.php';
$Name = $_POST["Name"];
$Pwd = $_POST["Pwd"];
$Email = $_POST["Email"];
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
	$mail->Subject = "MD2019：Notification of Password"; //郵件標題
	$mail->Body = "<p> Dear ".$Name."</p> 
        <p>Your password of MD2019 <a href='http://www.mdassn.org/md2019/CMS/index.html'>Conference Management System (CMS)</a> is ".$Pwd.".</p>
        
        <p>With best regards,</p>
        <p>Program Committee,<br> 
        2019 International Conference on Medical Design (MD2019)<br>
        http://www.mdassn.org/md2019/
        </p>";
	$mail->IsHTML(true); //郵件內容為html
	$mail->AddAddress($Email); //收件者郵件及名稱
	if($mail->Send()){
        echo json_encode(array('isSuccess' => true,'msg'=>"已完成審核並寄送通知信")); 
	}
?>