<?php
header('Content-Type: application/json; charset=UTF-8'); 
include('../Mod/config.php');
$mysqli_db = dbconect();
$mysqli_db->query("SET NAMES utf8");
$sql ="SELECT `Sid`,`S_Name` FROM `session` WHERE 1";
$result = $mysqli_db->query($sql);
$str="";
while($row = $result->fetch_object()){
    $str .= "<option value =".$row->Sid.">".$row->S_Name."</option>";
}
echo json_encode(array('isSuccess' => true,'str'=>$str));
mysqli_close($mysqli_db);

?>