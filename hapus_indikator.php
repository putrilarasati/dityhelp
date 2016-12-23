<?php include("koneksi.php");
$id_indikator = $_GET['id_indikator'];
$query = "DELETE FROM `indikatortbl` WHERE `indikatortbl`.`id_indikator`='".$id_indikator."'";
print ($query);
if (mysql_query($query)) {
 echo "Data berhasil dihapus";
 } else die ("Error menjalankan query". mysql_error());
 


header('location:halaman_indikatorpenilaian.php');
?>