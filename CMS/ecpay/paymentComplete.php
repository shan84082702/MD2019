<?php
use PHPMailer\PHPMailer\PHPMailer;
include("../Mod/config.php");
require '../Mod/vendor/autoload.php';
$sesoid = $_GET['oid'];
//$type="";
foreach ($_POST as $key => $value) {
    $received[$key] = $value;
}

if($received['RtnCode']){
    $mysqli_db = dbconect();
	$mysqli_db->query("SET NAMES utf8");
/*	$selsql = "SELECT * FROM `order` WHERE `Oid` =  '".$sesoid."'";
	$selresult=$mysqli_db->query($selsql);
	while($row = $selresult->fetch_object()){
		$type = $row->O_type;
		$Aid = $row->O_Aid;
		break;
    }*/
    $PaymentTime=$_POST['TradeDate'];
	//$PaymentTime="2019/07/24"; //TEST
	$sql="UPDATE `order` SET `O_isPay`=1,`O_PaymentTime`='".$PaymentTime."' WHERE `Oid`='".$sesoid."';"; 
	if($mysqli_db->query($sql)){
        send_payment_complete_mail($sesoid,$PaymentTime);
    }
	
/*	$update_account = "UPDATE `account` SET `A_Type`='".$type."' WHERE `Aid`='".$Aid."';";
	$result=$mysqli_db->query($update_account);*/
}

function send_payment_complete_mail($Oid,$PaymentTime){
	$mysqli_db = dbconect();
	$mysqli_db->query("SET NAMES utf8");
	$sql = "SELECT `order`.`Oid`,`order`.`O_Pid`, `order`.`O_Money`, `order`.`O_type`, `order`.`O_isMail`, `account`.`A_Fname`, 
    `account`.`A_Lname`, `account`.`A_Email` FROM `order`,`account` 
    WHERE `order`.`O_Aid`=`account`.`Aid` and `order`.`Oid`='".$Oid."';";
	$result=$mysqli_db->query($sql);
	$nums=mysqli_num_rows($result);
	if($nums>0)
	{
        $row = $result->fetch_object();
		if($row->O_isMail==0){
			$submission="";
			if($row->O_Pid!=0){
				$sql2="SELECT P_Title from paper where Pid='".$row->O_Pid."';";
				$result2=$mysqli_db->query($sql2);
				$row2 = $result2->fetch_object();
				$submission = $row2->P_Title;
			}
			else if($row->O_Pid==0){
				$submission = "(No Submissiton (Just attend Conference or Workshop))";
			}
			else if($row->O_Pid==null){
				$submission = "(You haven't selected yet.)";
			}
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
			$mail->Subject = "MD2019:Your Registration Order Payment is Complete"; //郵件標題
			$mail->Body = "<p> Dear ".$row->A_Fname." ".$row->A_Lname."</p> 
							<p>
							We have received your payment of registration order for NT$".$row->O_Money." by credit card.														
							</p>

							<p>
							The order detail is as follows:<br>
							Order Number:".$row->Oid."<br>
							Purchase Item:".$money_type."<br>
							Total Amount:NT$".$row->O_Money."<br>
							Payment:Credit Card<br>
							Payment Time:".$PaymentTime."<br>
							Selected Submission Title:".$submission." 
							</p>
							
							<p>You can log into the  
							<a href='http://www.mdassn.org/md2019/CMS/index.html'>Conference Management System (CMS)</a> to view more 
							details of the order.</p>
							
							<p>Chien-Lung Hsu<br>
							Program Committee Chair for MD 2019</p>

							<p>Kevin C. Tseng<br>
							General Chair for MD 2019</p>"; 
			$mail->IsHTML(true); //郵件內容為html
			$mail->AddAddress($row->A_Email); //收件者郵件及名稱
			if($mail->Send()){
				$mysqli_db->query("SET NAMES utf8");
				$secsql = " UPDATE `order` SET `O_isMail` = 1 WHERE `Oid` = ".$row->Oid."";
				$mysqli_db->query($secsql);
			}
		}
    }
}
/*
function ecPayComplete()
    {
        foreach ($_POST as $key => $value) {
            $received[$key] = $value;
        }
        $checkMacValue = $received['CheckMacValue'];
		file_put_contents('./result', $checkMacValue);
        // confirm checkMacValue
        $ckechsum = $checkMacValue;

        if ($ckechsum == true) {

            $paidFlag = ($this->config['debug'] == true) ? 0 : 1; // 若目前為偵錯模式，則模擬付款設定為 0

            if ($received['RtnCode'] != 1 || $received['SimulatePaid'] == $paidFlag) {
                echo '0|TradeError';
            } else {
                $tradeAmt = $received['TradeAmt'];
                $serialNum = $received['MerchantTradeNo'];
                $tradeNo = $received['TradeNo']; // ecpay的交易編號
                $paymentDate = preg_replace('/\//', '-', $received['PaymentDate']);
                $paymentType = $this->ecPay->paymentTypeTransform($received['PaymentType']);

                // Step 1. update order base info in green heart system
                $statement = "UPDATE ps_orders SET valid=1, total_paid_real=$tradeAmt, current_state=2, payment='$paymentType' WHERE reference='$serialNum'";
                $feedback = $this->dbCon->sqli_exec($statement);

                if ($feedback) {
                    // Step 2. create a new record into table ps_order_payment
                    $statement = "INSERT INTO ps_order_payment (id_order_payment, order_reference, id_currency, amount, payment_method, conversion_rate, transaction_id, date_add) VALUES (0, '$serialNum', 1, $tradeAmt, '$paymentType', 1, '$tradeNo', '$paymentDate')";
                    $feedback = $this->dbCon->sqli_exec($statement);

                    if ($feedback) {
                        // Step 3. send complete mail when payment complete

                        $statement = "SELECT p.id_customer, c.email, c.firstname FROM ps_orders p
                        LEFT JOIN ps_customer c ON (c.id_customer=p.id_customer)
                        WHERE p.reference='$serialNum'";
                        $feedback = $this->dbCon->sqli_query($statement);
                        $user = json_decode($feedback);
                        $email = $user[0]->email;
                        $name = $user[0]->firstname;

                        $mail = $this->mail->mailSendPhpmailer_paymentComplete($email,
                            [
                                'name' => $name,
                                'serialNum' => $serialNum,
                            ]
                        );

                        if ($mail == true) {
                            echo '1|OK';
                        } else {
                            echo '0|CompleteMailSendError';
                        }
                    } else {
                        echo '0|PaymentInfoCreateError';
                    }
                } else {
                    echo '0|OrderStateUpdateError';
                }
            }
        } 
        else {
            echo '0|ChecksumError';
        }
    }*/
?>