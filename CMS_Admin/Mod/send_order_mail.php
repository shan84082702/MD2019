<?php
use PHPMailer\PHPMailer\PHPMailer;
//Load composer's autoloader
require 'vendor/autoload.php';
include("config.php");
	$mysqli_db = dbconect();
	$mysqli_db->query("SET NAMES utf8");
	$sql = "SELECT `Oid`,`O_Aid`,`A_Fname`,`A_Lname`,`O_type`,`O_Money`,`A_Email`, SUBSTR(`order`.`CreatTime`,1,10) as CreateTime FROM `order`,`account` WHERE `order`.`O_Aid`=`account`.`Aid` and `order`.`O_isPay`='0' and `O_isUsed`='1';";
	$result=$mysqli_db->query($sql);
    $nums=mysqli_num_rows($result);
    $mail_succ=0;
	if($nums>0)
	{
		while ($row = $result->fetch_object()) 
		{
            
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
            $mail->Subject = "We’re waiting for your Full Payment for MD2019"; //郵件標題
            $mail->Body = "<p> Dear ".$row->A_Fname." ".$row->A_Lname."</p>
                            <p>
                            We are excited to see you in November for MD2019!<br>
                            You currently have an outstanding balance of NT$".$row->O_Money." 
                            which you created at ".$row->CreateTime.".<br>
                            Full payment is due by November 11.
                            </p>

                            <p>
                            You can log into the
                            <a href='http://www.mdassn.org/md2019/CMS/index.html'>Conference Management System (CMS)</a>,
                            and go to the REGISTRATION STATUS page to see more detail and pay your balance easily. 
                            You can pay with credit card. Please login and make your payment before the payment 
                            due date! 
                            </p>

                            <p>
                            If you create the order repeatedly, and you have already pay the other order, 
                            this order is useless, you can log into the 
                            <a href='http://www.mdassn.org/md2019/CMS/index.html'>Conference Management System (CMS)</a>,
                            and go to the REGISTRATION STATUS page to delete the order.
                            </p>

                            <p>The order detail is as follows:<br>
                            Order Number:".$row->Oid."<br>
							Purchase Item:".$money_type."<br>
                            Amount:NT$".$row->O_Money."<br>
                            Creasted Time:".$row->CreateTime."<br>
                            PS. The early bird payment deadline is 19 September 2019.</p>

                            <p>Please ignore this message if you have already done it.</p>
							
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
    }
    if($mail_succ==$nums)
        echo json_encode(array('isSuccess' => true,'msg'=>"已經寄出EMAIL進行催繳!"));
    else
        echo json_encode(array('isSuccess' => false));
?>