<?php
use PHPMailer\PHPMailer\PHPMailer;
//Load composer's autoloader
include("config.php");
require 'vendor/autoload.php';
if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $token = $_POST["token"];
    $key = "CGUAdmin2019";
    $detoken = tokendecode(json_encode($token),$key);
    $token_obj = json_decode($detoken);
    if($_POST["action"] == "mail"){
      send_mail($token_obj);
    }
}

function send_mail($token_obj){
	$mysqli_db = dbconect();
	$mysqli_db->query("SET NAMES utf8");
	$Pid = $_POST["Pid"];
	$sql = "SELECT * FROM `paper`,`author` WHERE `paper`.`Pid`='$Pid' 
		and `paper`.`Pid`=`author`.`Au_Pid` and `author`.`Au_Isused`='1';";
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
			$mail->Subject = "MD 2019 have received the Paper proposal"; //郵件標題
			$mail->Body = "<p> Dear ".$row->Au_FName." ".$row->Au_LName."</p> 
							<p>
							We have received the submission entitled:  \"".$row->P_Title."\"  as Paper proposal for the 
							2019 Medical Design International Conference, where you are listed as one of the co-authors.
							The manuscript has been submitted by ".$token_obj->FName." ".$token_obj->LName.".														
                            </p>

							<p>
							You are invited to create an account in our <a href='http://www.mdassn.org/md2019/CMS/index.html'>Conference Management System (CMS)</a> to view the 
							details of the submission and track its status.
                            </p>
                            
                            <p>Chien-Lung Hsu<br>
	                        Program Committee Chair for MD 2019</p>

	                        <p>Kevin C. Tseng<br>
	                        General Chair for MD 2019</p>"; 
			$mail->IsHTML(true); //郵件內容為html
			$mail->AddAddress($row->Au_Mail); //收件者郵件及名稱
		/*	if($row->Au_Mail==$token_obj->Email){
				$mail->AddCC("M0729001@cgu.edu.tw");
				$mail->AddCC("B0329052@stmail.cgu.edu.tw");
				$mail->AddCC("shan84082702@yahoo.com.tw");
			}*/
			if($row->Au_Mail==$token_obj->Email){
				$mail->AddCC("md2019@pddlab.org");
				$mail->AddCC("ktseng@pddlab.org");
				$mail->AddCC("clhsu@mail.cgu.edu.tw");
			}
			if($mail->Send())
				$mail_succ++;
		}
    }
	if($mail_succ==$nums)
		echo json_encode(array('isSuccess' => true));
	else
		echo json_encode(array('isSuccess' => false));
} 
?>