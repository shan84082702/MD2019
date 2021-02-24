<?php
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

?>