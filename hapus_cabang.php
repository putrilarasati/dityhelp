<?php include("koneksi.php");
$id_cabang  = $_GET['id_cabang'];
$query = "DELETE FROM `cabangtbl` WHERE `id_cabang`='".$id_cabang."'";
//$result=mysql_query($query);
if (mysql_query($query)) {
 echo "Data berhasil dihapus";
 } else die ("Error menjalankan query". mysql_error());
 
print ($query);

header('location:halaman_data_cabang.php');
?>