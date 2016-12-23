<?php include("koneksi.php");
$id_toko  = $_GET['id_toko'];
$query = "DELETE FROM `tokotbl` WHERE `id_toko`='".$id_toko."'";
//$result=mysql_query($query);
if (mysql_query($query)) {
 echo "Data berhasil dihapus";
 } else die ("Error menjalankan query". mysql_error());
 
print ($query);

header('location:notif.php');
?>