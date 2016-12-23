<?php include("koneksi.php");
$id_provider  = $_GET['id_provider'];
$query = "DELETE FROM `providertbl` WHERE `providertbl`.`id_provider`='".$id_provider."'";
print ($query);
if (mysql_query($query)) {
 echo "Data berhasil dihapus";
 } else die ("Error menjalankan query". mysql_error());
 


header('location:halaman_data_provider.php');
?>