<?php
$str = "Hello World";
$file = fopen("hello.txt","a+"); //�}���ɮ�
fwrite($file,$str);
fclose($file);
?>