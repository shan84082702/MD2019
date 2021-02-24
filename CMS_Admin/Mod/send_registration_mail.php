<?php
use PHPMailer\PHPMailer\PHPMailer;
//Load composer's autoloader
require 'vendor/autoload.php';
include("config.php");
	$mysqli_db = dbconect();
	$mysqli_db->query("SET NAMES utf8");
	$sql = "SELECT `Pid`,`P_Title`,`paper`.`CreatTime`,`S_Name`,`T_name`,`P_Type`,`A_Email`,A_Fname,A_Lname,
    (CASE WHEN `P_Type`='0' THEN 'Oral' WHEN `P_Type`='1' THEN 'Poster' END) as Presentation,
     `Oid` FROM `paper` left join `order` on `order`.`O_Pid`=`paper`.`Pid` left join `session` 
    on `paper`.`P_Session`=`session`.`Sid` left join `topic` on `paper`.`P_Topic`=`topic`.`Tid` 
    left join `account` on `account`.Aid=`paper`.P_Aid
    where P_Isused='1' and isPass=1 order by `Pid`";
	$result=$mysqli_db->query($sql);
    $nums=mysqli_num_rows($result);
    $mail_succ=0;
	if($nums>0)
	{
		while ($row = $result->fetch_object()) 
		{
            if($row->Oid==null){
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
                $mail->Subject = "MD2019：Please have a regular registration as soon as possible"; //郵件標題
                $mail->Body = "<p> Dear ".$row->A_Fname." ".$row->A_Lname."</p>
                                <p>
                                Your paper or poster was be accepted and no author of the accepted paper/poster
                                have a regular registration yet. <font style='color:rgb(255, 0, 0);'>At least one author 
                                of every accepted paper or poster should have a Regular Registration no later than 11 November 2019 
                                to be presented at the conference and included in the proceedings.</font>
                                Therefore, please have a regular registration as soon as possible.Thank you.
                                </p>

                                <p>The detail of accpted paper/poster is as follows:<br>
                                Paper Title:".$row->P_Title."<br>
                                Session:".$row->S_Name."<br>
                                Topic:".$row->T_name."<br>
                                Presentation:".$row->Presentation."<br>
                                Created Time:".$row->CreatTime."<br>
                                PS. One regular registration will cover the publication of only one accepted paper or poster.</p>
								
								<p>Please ignore this message if you have already done it.</p>

                                <p>
                                You can log into the
                                <a href='http://www.mdassn.org/md2019/CMS/index.html'>Conference Management System (CMS)</a>,
                                to see more details and have a regular registration and pay with credit card.
                                </p>

                                <p>We look forward to seeing you in November at MD2019!</p>
            
                                <p>Thanks,<br>
                                MD2019 Secretariat office<br> 
                                md2019@pddlab.org
                                </p>"; 
                $mail->IsHTML(true); //郵件內容為html
                $mail->AddAddress($row->A_Email); //收件者郵件及名稱
                if($mail->Send())
                    $mail_succ++;
                }
            else
                $mail_succ++;     
		}
    }
    if($mail_succ==$nums)
        echo json_encode(array('isSuccess' => true,'msg'=>"已經寄出EMAIL進行催繳!"));
    else
        echo json_encode(array('isSuccess' => false));
?>