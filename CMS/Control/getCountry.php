<?php
header('Content-Type: application/json; charset=UTF-8'); 
include('../Mod/config.php');
$mysqli_db = dbconect();
$mysqli_db->query("SET NAMES utf8");
$sql ="SELECT `Cid`,`C_Name` FROM `country` WHERE 1";
$result = $mysqli_db->query($sql);
$str="";
while($row = $result->fetch_object()){
    $str .= "<option value =".$row->Cid.">".$row->C_Name."</option>";
}
echo json_encode(array('isSuccess' => true,'str'=>$str));
mysqli_close($mysqli_db);

?>