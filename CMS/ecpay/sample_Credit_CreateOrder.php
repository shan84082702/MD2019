<?php
/**
*    Credit信用卡付款產生訂單範例
*/
    
    //載入SDK(路徑可依系統規劃自行調整)
    include('ECPay.Payment.Integration.php');
    include("../Mod/config.php");
    try {
        $sesoid = $_GET['oid'];        
        $omoney = 0;
        $discribe="";
        $mysqli_db = dbconect();
		$mysqli_db->query("SET NAMES utf8");
    	$sql="SELECT * FROM `order` WHERE `Oid` = ".$sesoid .""; 
    	$result=$mysqli_db->query($sql);
    	if($row = $result->fetch_object())
		{
				$omoney = $row->O_Money;
				$otype = $row->O_type;
		}
        if ($otype == 1)
            $discribe = "Early bird-International-Delegate";
        else if ($otype == 2)
            $discribe = "Early bird-International-Student";
        else if ($otype == 3)
            $discribe = "Early bird-International-Non Presenter";
        else if ($otype == 4)
            $discribe = "Early bird-Taiwan-Delegate";
        else if ($otype == 5)
            $discribe = "Early bird-Taiwan-Student";
        else if ($otype == 6)
            $discribe = "Early bird-Taiwan-Non Presenter";
        else if ($otype == 7)
            $discribe = "Regular-International-Delegate";
        else if ($otype == 8)
            $discribe = "Regular-International-Student";
        else if ($otype == 9)
            $discribe = "Regular-International-Non Presenter";
        else if ($otype == 10)
            $discribe = "Regular-Taiwan-Delegate";
        else if ($otype == 11)
            $discribe = "Regular-Taiwan-Student";
        else if ($otype == 12)
            $discribe = "Regular-Taiwan-Non Presenter";
        else if ($otype == 13)
            $discribe = "Regular-Taiwan-Medical Design Members or Employees in Formosa Plastics Group";
        else if ($otype == 14)
            $discribe = "Regular-International Partical Workshop-Delegate";
        else if ($otype == 15)
            $discribe = "Regular-International Partical Workshop-Medical Design Members or Employees in Formosa Plastics Group";
        else if ($otype == 16)
            $discribe = "Regular-Each Local Partical Workshop-Delegate";
        else if ($otype == 17)
            $discribe = "Regular-Each Local Partical Workshop-Medical Design Members or Employees in Formosa Plastics Group";

    	$obj = new ECPay_AllInOne();
        
        //Testing Environment
        $obj->ServiceURL  = "https://payment-stage.ecpay.com.tw/Cashier/AioCheckOut/V5";   //服務位置
        $obj->HashKey     = '5294y06JbISpM5x9';                                           //測試用Hashkey，請自行帶入ECPay提供的HashKey
        $obj->HashIV      = 'v77hoKGq4kWxNNIS';                                           //測試用HashIV，請自行帶入ECPay提供的HashIV
        $obj->MerchantID  = '2000132';                                                     //測試用MerchantID，請自行帶入ECPay提供的MerchantID
        
        
        //Formal Environment
        /*$obj->ServiceURL  = "https://payment.ecpay.com.tw/Cashier/AioCheckOut/V5";   //正式服務位置
		//***you only need to modify HashKey,HashIV, and MerchantID*** 
        $obj->HashKey     = 'D2uLc5n8kYd78NUj';                                           //正式Hashkey
        $obj->HashIV      = 'pWiuaXJ7hpFgGiOA';                                           //正式HashIV
        $obj->MerchantID  = '3133930';                                                     //正式MerchantID
        */
        $obj->EncryptType = '1';                                                           //CheckMacValue加密類型，請固定填入1，使用SHA256加密
        //基本參數(請依系統規劃自行調整)
        $MerchantTradeNo = $sesoid ;
        //$obj->Send['ReturnURL']         = "http://spark5.widelab.org/~csie062452/aammar/CMS/ecpay/paymentComplete.php?oid=".$sesoid."" ;    //付款完成通知回傳的網址
        $obj->Send['ReturnURL']         = "http://www.mdassn.org/md2019/CMS/ecpay/paymentComplete.php?oid=".$sesoid."" ; 
        $obj->Send['MerchantTradeNo']   = $MerchantTradeNo;                          //訂單編號
        $obj->Send['MerchantTradeDate'] = date('Y/m/d H:i:s');                       //交易時間
        $obj->Send['TotalAmount']       = $omoney;                                      //交易金額
        $obj->Send['TradeDesc']         = $discribe;                          //交易描述
        $obj->Send['ChoosePayment']     = ECPay_PaymentMethod::Credit;              //付款方式:Credit
        //$obj->Send['ClientBackURL']     = "http://spark5.widelab.org/~csie062452/aammar/CMS/ordersummary.html" ;              //Client端返回特店的按鈕連結
        $obj->Send['ClientBackURL']     = "http://www.mdassn.org/md2019/CMS/ordersummary.html"; 

        

        //訂單的商品資料
        array_push($obj->Send['Items'], array('Name' =>  $discribe, 'Price' => (int) $omoney,
                   'Currency' => "元", 'Quantity' => (int) "1", 'URL' => "dedwed"));
        //Credit信用卡分期付款延伸參數(可依系統需求選擇是否代入)
        //以下參數不可以跟信用卡定期定額參數一起設定
        $obj->SendExtend['CreditInstallment'] = '' ;    //分期期數，預設0(不分期)，信用卡分期可用參數為:3,6,12,18,24
        $obj->SendExtend['Redeem'] = false ;           //是否使用紅利折抵，預設false
        $obj->SendExtend['UnionPay'] = false;          //是否為聯營卡，預設false;
        //Credit信用卡定期定額付款延伸參數(可依系統需求選擇是否代入)
        //以下參數不可以跟信用卡分期付款參數一起設定
        // $obj->SendExtend['PeriodAmount'] = '' ;    //每次授權金額，預設空字串
        // $obj->SendExtend['PeriodType']   = '' ;    //週期種類，預設空字串
        // $obj->SendExtend['Frequency']    = '' ;    //執行頻率，預設空字串
        // $obj->SendExtend['ExecTimes']    = '' ;    //執行次數，預設空字串
        
        # 電子發票參數
        /*
        $obj->Send['InvoiceMark'] = ECPay_InvoiceState::Yes;
        $obj->SendExtend['RelateNumber'] = "Test".time();
        $obj->SendExtend['CustomerEmail'] = 'murk1993@gmail.com';
        $obj->SendExtend['CustomerPhone'] = '0988816158';
        $obj->SendExtend['TaxType'] = ECPay_TaxType::Dutiable;
        $obj->SendExtend['CustomerAddr'] = '新北新庄瓊林南路118-26號4樓';
        $obj->SendExtend['InvoiceItems'] = array();
        // 將商品加入電子發票商品列表陣列
        foreach ($obj->Send['Items'] as $info)
        {
            array_push($obj->SendExtend['InvoiceItems'],array('Name' => $info['Name'],'Count' =>
                $info['Quantity'],'Word' => '個','Price' => $info['Price'],'TaxType' => ECPay_TaxType::Dutiable));
        }
        $obj->SendExtend['InvoiceRemark'] = '測試發票備註';
        $obj->SendExtend['DelayDay'] = '0';
        $obj->SendExtend['InvType'] = ECPay_InvType::General;
        */
        //產生訂單(auto submit至ECPay)
        $obj->CheckOut();
    
    } catch (Exception $e) {
    	echo $e->getMessage();
    } 
 
?>