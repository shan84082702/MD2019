<?php
	include("../Mod/CMSadmin0040MOD.php");
	if ($_SERVER['REQUEST_METHOD'] == "POST") {
		if($_POST["action"] == "getorderlist"){
			$token = $_POST["token"];
			$key = "md2019admin";
			$detoken = tokendecode(json_encode($token),$key);
			$token_obj = json_decode($detoken);
			if($token_obj->Rank == 2){
				GetOrderList();
			}
        }
        else if($_POST["action"] == "getorderdetail"){
			$token = $_POST["token"];
			$key = "md2019admin";
			$detoken = tokendecode(json_encode($token),$key);
			$token_obj = json_decode($detoken);
			if($token_obj->Rank == 2){
				GetOrderDetail();
			}
		}
	}
	function GetOrderList(){
		$Order = new OrderMod;
		$reordertable = $Order->getOrderList(0);
		$reordertable2 = $Order->getOrderList(1);
		//echo $repapertable;
		echo json_encode(array('isSuccess' => true,'ordertable'=>$reordertable,'ordertable2'=>$reordertable2)); 
    }
    function GetOrderDetail(){
        $Order = new OrderMod;
        $Oid = $_POST["orderid"];
		$reordertable = $Order->getOrderDetail($Oid);
		echo json_encode(array('isSuccess' => true,'ordertable'=>$reordertable)); 
    }
?>