<?php
use PHPMailer\PHPMailer\PHPMailer;
/// Page Created by CGU Amor_Kai 2016 12 15
function dbconect()
{
	$host="localhost"; //replace with database hostname 
	$username="md2019"; //replace with database username 
	$password="md35795709"; //replace with database password 
	$db_name="md2019"; //replace with database name	 
	return new mysqli($host,$username,$password,$db_name);
}


function tokendecode($data,$key){
	$redata = openssl_decrypt(base64_decode($data), 'AES-128-ECB', $key, OPENSSL_RAW_DATA);
	return $redata;
}

function tokenencode($inputtoken,$key){
    $redata = base64_encode(openssl_encrypt($inputtoken, 'AES-128-ECB', $key, OPENSSL_RAW_DATA));
    return $redata;
}

class email_data{
	private $uacc='widelab@widelab.org';
	private $upasswd='widelab35795709';
	function __construct(){
		//Load composer's autoloader
		require 'vendor/autoload.php';
    }
	function send_email($to_email,$email_header,$content){//寄信
		$mail = new PHPMailer(); //建立新物件
		$mail->SMTPDebug = 2;
		$mail->IsSMTP(); //設定使用SMTP方式寄信
		$mail->SMTPAuth = true; //設定SMTP需要驗證
		$mail->SMTPSecure = "ssl"; // Gmail的SMTP主機需要使用SSL連線
		$mail->Host = "smtp.gmail.com"; //Gamil的SMTP主機
		$mail->Port = 465; //Gamil的SMTP主機的埠號(Gmail為465)。
		$mail->CharSet = "utf-8"; //郵件編碼
		$mail->Username = $this->uacc; //Gamil帳號
		$mail->Password = $this->upasswd; //Gmail密碼
		$mail->From = $this->uacc; //寄件者信箱
		$mail->FromName = "MD2019"; //寄件者姓名
		$mail->Subject = $email_header; //郵件標題
		$mail->Body = $content; //郵件內容(忘記密碼的網頁)
		//$mail->addAttachment('../uploadfile/file/dirname.png', 'new.jpg'); //附件，改以新的檔名寄出
		$mail->IsHTML(true); //郵件內容為html
		$mail->AddAddress($to_email); //收件者郵件及名稱

		if (!$mail->Send()) {
			echo json_encode(array(
				'msg' => '403',
				'out' => "Error: " . $mail->ErrorInfo,
			));
		} 
		/*else {
			echo json_encode(array(
				'msg' => '200',
				'out' => "",
			));
		}*/
	}
	
}

?>