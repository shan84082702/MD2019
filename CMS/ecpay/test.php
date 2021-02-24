<?php
$str = "Hello World";
$file = fopen("hello.txt","a+"); //¶}±ÒÀÉ®×
fwrite($file,$str);
fclose($file);
?>