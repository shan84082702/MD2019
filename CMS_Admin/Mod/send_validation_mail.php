<?php
use PHPMailer\PHPMailer\PHPMailer;
//Load composer's autoloader
include("config.php");
require 'vendor/autoload.php';
$memberid = $_POST["memberid"];
$apply = $_POST["apply"];
$checkIdentity = $_POST["checkIdentity"];
    $mysqli_db = dbconect();
	$mysqli_db->query("SET NAMES utf8");
	$sql = "SELECT `A_Fname`,`A_Lname`,`A_Email` FROM `account` WHERE `Aid`='$memberid';";
	$result=$mysqli_db->query($sql);
    $row = $result->fetch_object();
    $identity_type="";
    $validation_action="";
    if($apply==2){
        $identity_type="Taiwanese participant(non student)";
        $validation_action="upload the correct national identification card (or resident certificate) image";
    }
    else if($apply==3){
        $identity_type="Medical Design Member(non student)";
        $validation_action="upload the correct national identification card (or resident certificate) image and enter the correct membership card number";
    }
    else if($apply==4){
        $identity_type="Employee in Formosa Plastics Group(non student)";
        $validation_action="upload the correct national identification card (or resident certificate) image and enter the correct employee card number";
    }
    else if($apply==5){
        $identity_type="Taiwanese student";
        $validation_action="upload the correct national identification card (or resident certificate) image and the correct student ID card image";
    }
    else if($apply==6){
        $identity_type="Medical Design Member and a Taiwanese student)";
        $validation_action="upload the correct national identification card (or resident certificate) image and the correct student ID card image and enter the correct membership card number";
    }
    else if($apply==7){
        $identity_type="Employee in Formosa Plastics Group and a Taiwanese student";
        $validation_action="upload the correct national identification card (or resident certificate) image and the correct student ID card image and enter the correct employee card number";
    }
    else if($apply==8){
        $identity_type="International student";
        $validation_action="upload the correct student ID card image";
    }

    $mailContent="";
    if($checkIdentity==0){
        $mailContent="<p> Dear ".$row->A_Fname." ".$row->A_Lname."</p> 
        <p>The identity validation process has been completed.</p>
        <p>
        With the information you uploaded, <strong>we can’t confirm that you are a 
        ".$identity_type."</strong>, so <strong>you can’t receive the discounted registration 
        fee for ".$identity_type."</strong>.
        </p>
        <p>
        If you actually are a ".$identity_type.", please <strong>".$validation_action."</strong> 
        in the IDENTITY VALIDATION page and check every information is actually uploaded after applying
        (the uploaded information will update in the IDENTITY VALIDATION page immediately).
        </p>
        <p>If you have any problem, please contact md2019@pddlab.org, thank you.</p>
        <p>
        You can log into the <a href='http://www.mdassn.org/md2019/CMS/index.html'>Conference Management System (CMS)</a> 
        to view more details.
        </p>
        <p>With best regards,</p>
        <p>Program Committee,<br> 
        2019 International Conference on Medical Design (MD2019)<br>
        http://www.mdassn.org/md2019/
        </p>";
    }
    else if($apply==$checkIdentity){
        $mailContent="<p> Dear ".$row->A_Fname." ".$row->A_Lname."</p> 
        <p>The identity validation process has been completed.</p>
        <p>
        We confirm that you are a ".$identity_type.", so <strong>you can receive the discounted 
        fee for ".$identity_type."</strong>.
        </p>
        <p>
        You can log into the <a href='http://www.mdassn.org/md2019/CMS/index.html'>Conference Management System (CMS)</a> 
        to view more details and create (or edit) a registration order for discounted fee and pay.
        </p>
        <p>If you have any problem, please contact md2019@pddlab.org, thank you.</p>
        
        <p>With best regards,</p>
        <p>Program Committee,<br> 
        2019 International Conference on Medical Design (MD2019)<br>
        http://www.mdassn.org/md2019/
        </p>";
    }
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
	$mail->Subject = "MD2019：Notification of Identity Validation"; //郵件標題
	$mail->Body = $mailContent;
	$mail->IsHTML(true); //郵件內容為html
	$mail->AddAddress($row->A_Email); //收件者郵件及名稱
	if($mail->Send()){
        echo json_encode(array('isSuccess' => true,'msg'=>"已完成審核並寄送通知信")); 
	}
?>