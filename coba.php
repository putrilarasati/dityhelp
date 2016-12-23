<?php
include_once "class.MultiGrid.inc.php";
$act = $_REQUEST['act'];

function getIndikator ($batas) {
$db_phone = new EyeMySQLAdap(':/cloudsql/database-sm:database-sm', 'root', '', 'PROFILE');

$dbserver="localhost";     
$dbusername="root";     
$dbpassword="";     
$dbname="alfamartdb";

$database=mysql_connect($dbserver,$dbusername,$dbpassword) or die(mysql_error());
$database2=mysql_select_db($dbname) or die (mysql_error()); 
$grid_all = new MultiGridcls($database2,'_all');
$grid_all->setQuery("id_indikator, indikator", "indikatortbl","","");
$grid_all->setResultsPerPage($batas);
$grid_all->setColumnHeader('id_indikator', 'ID Indikator');
$grid_all->setColumnHeader('indikator', 'INDIKATOR');
return $grid_all->printTable('table_all', "");

}

 if ($act == 'ALL'){
			
		echo getAll(20);
		
	}


?>