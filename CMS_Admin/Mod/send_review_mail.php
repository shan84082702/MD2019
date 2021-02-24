<?php
use PHPMailer\PHPMailer\PHPMailer;
//Load composer's autoloader
include("config.php");
require 'vendor/autoload.php';
	$mysqli_db = dbconect();
	$mysqli_db->query("SET NAMES utf8");
    $Pid = $_POST["Pid"];
    $upload_pid="";
    $upload_ptitle="";
    $upload_commend="";
    $upload_FName="";
    $upload_LName="";
    $upload_sql = "SELECT paper.Pid,paper.P_Title,paper.Commend,account.A_Fname, account.A_Lname, account.A_Email FROM `account`,`paper` 
    WHERE `paper`.`Pid`='$Pid' and `paper`.`P_Aid`=`account`.`Aid`;";
    $upload_result=$mysqli_db->query($upload_sql);
    while ($row = $upload_result->fetch_object()){
        $upload_pid=$row->Pid;
        $upload_ptitle=$row->P_Title;
        $upload_commend=$row->Commend;
        $upload_FName=$row->A_Fname;
        $upload_LName=$row->A_Lname;
        $upload_Email=$row->A_Email;
    }
	$sql = "SELECT * FROM `paper`,`author` WHERE `paper`.`Pid`='$Pid' 
		and `paper`.`Pid`=`author`.`Au_Pid` and `author`.`Au_Isused`='1';";
	$result=$mysqli_db->query($sql);
	$nums=mysqli_num_rows($result);
	$mail_succ=0;
	if($nums>0)
	{
		while ($row = $result->fetch_object()) 
		{
            $mailContent="";
            if($row->P_Type==0){
                $mailContent="<h3 style='color:rgb(192, 0, 0);text-align:center;'>Notification of Acceptance</h3>
                <h4 style='color:rgb(192, 0, 0);text-align:center;'>2019 International Conference on Medical Design (MD2019)</h4>
                <h4 style='color:rgb(192, 0, 0);text-align:center;'>November 19-22, 2019 | Taipei, Taiwan, R.O.C.<br>
                http://www.mdassn.org/md2019/</h4>

                <p style='color:rgb(192, 0, 0);text-align:center;'>Paper ID：".$upload_pid."<br>
                Paper Title：".$upload_ptitle."<br>
                Submitted by：".$upload_FName." ".$upload_LName." (".$upload_Email.")</p>
                
                <p> Dear ".$row->Au_FName." ".$row->Au_LName."</p> 
                <p>
                <strong>Congratulations!</strong> The review process of 2019 International Conference on Medical Design (MD2019) has been completed. 
                Based on the following recommendations of the reviewers and the Technical Program Committee, we are pleased to inform 
                you that your abstract identified above has been accepted for oral presentation. <font style='color:rgb(255, 0, 0);'>Please submit the camera-ready version 
                of your paper via the submission system before November 4 2019. At least one author should have a Regular Registration 
                no later than 11 November 2019.</font> You are cordially invited to present the abstract orally at MD 2019 to be held in Taipei, 
                Taiwan during November 19-22, 2019.														
                </p>

                <p style='color:rgb(192, 0, 0);'>
                The abstract/Full paper presented on MD 2019 will be included into Conference Abstract Collection. The presenter will be 
                offered with presentation certificate.
                </p>

                <p>Reviews’ Comments:<br>".$upload_commend."<br><br></p>

                <p>With best regards,<br></p>
                
                <p>Program Committee,<br> 
                2019 International Conference on Medical Design (MD2019)<br>
                http://www.mdassn.org/md2019/
                </p>";
            }
            else if($row->P_Type==1){
                $mailContent="<h3 style='color:rgb(192, 0, 0);text-align:center;'>Notification of Acceptance</h3>
                <h4 style='color:rgb(192, 0, 0);text-align:center;'>2019 International Conference on Medical Design (MD2019)</h4>
                <h4 style='color:rgb(192, 0, 0);text-align:center;'>November 19-22, 2019 | Taipei, Taiwan, R.O.C.<br>
                http://www.mdassn.org/md2019/</h4>

                <p style='color:rgb(192, 0, 0);text-align:center;'>Paper ID：".$upload_pid."<br>
                Paper Title：".$upload_ptitle."<br>
                Submitted by：".$upload_FName." ".$upload_LName." (".$upload_Email.")</p>
                
                <p> Dear ".$row->Au_FName." ".$row->Au_LName."</p> 
                <p>
                <strong>Congratulations!</strong> The review process of 2019 International Conference on Medical Design (MD2019) has been completed. 
                Based on the following recommendations of the reviewers and the Technical Program Committee, we are pleased to inform 
                you that your abstract identified above has been accepted for poster presentation. <font style='color:rgb(255, 0, 0);'>Please submit the camera-ready version 
                of your paper via the submission system before November 4 2019. At least one author should have a Regular Registration 
                no later than 11 November 2019.</font> You are cordially invited to present the poster at MD 2019 to be held in Taipei, 
                Taiwan during November 19-22, 2019.														
                </p>

                <p style='color:rgb(192, 0, 0);'>
                The abstract/Full paper presented on MD 2019 will be included into Conference Abstract Collection. The presenter will be 
                offered with presentation certificate.
                </p>

                <p>Reviews’ Comments:<br>".$upload_commend."<br><br></p>

                <p>With best regards,<br></p>
                
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
			$mail->Subject = "Notification of Acceptance"; //郵件標題
            $mail->Body = $mailContent; 
			$mail->IsHTML(true); //郵件內容為html
			$mail->AddAddress($row->Au_Mail); //收件者郵件及名稱
		/*	if($row->Au_Mail==$token_obj->Email){
				$mail->AddCC("M0729001@cgu.edu.tw");
				$mail->AddCC("B0329052@stmail.cgu.edu.tw");
				$mail->AddCC("shan84082702@yahoo.com.tw");
			}*/
		/*	if($row->Au_Mail==$token_obj->Email){
				$mail->AddCC("md2019@pddlab.org");
				$mail->AddCC("ktseng@pddlab.org");
				$mail->AddCC("clhsu@mail.cgu.edu.tw");
			}*/
			if($mail->Send())
				$mail_succ++;
		}
    }
	if($mail_succ==$nums)
		echo json_encode(array('isSuccess' => true));
	else
		echo json_encode(array('isSuccess' => false));

?>