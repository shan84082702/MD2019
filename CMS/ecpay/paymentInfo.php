<?php
include("../Mod/config.php");
$sesoid = $_GET['oid'];
$PaymentType = $_POST['PaymentType'];

$mysqli_db = dbconect();
$mysqli_db->query("SET NAMES utf8");
$sql="UPDATE `order` SET `O_PayMethod`='".$PaymentType."' WHERE `Oid`='".$sesoid."';"; 
$result=$mysqli_db->query($sql);
	

?>