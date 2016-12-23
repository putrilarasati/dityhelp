<?php 
ob_start(); 

session_start();  
  
$dbserver="localhost";     
$dbusername="root";     
$dbpassword="";     
$dbname="alfamartdb";

mysql_connect($dbserver,$dbusername,$dbpassword) or die(mysql_error());     
mysql_select_db($dbname) or die (mysql_error()); 
?> 