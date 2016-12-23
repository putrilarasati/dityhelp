<?php include("koneksi.php");

if(isset($_POST['id_cabang']) && isset($_POST['nama_cabang']) && isset($_POST['alamat_cabang'])) {
if(!empty($_POST['id_cabang']) && !empty($_POST['nama_cabang']) && !empty($_POST['alamat_cabang'])) {
//print($_POST['id_cabang2']);
$id_cabang2  = $_POST['id_cabang'];
$nama_cabang  = $_POST['nama_cabang'];
$alamat_cabang  = $_POST['alamat_cabang'];
print($nama_cabang);
print($id_cabang2);
$query1= " UPDATE `cabangtbl` SET `alamat_cabang`='".$alamat_cabang."',`nama_cabang`='".$nama_cabang."' WHERE `id_cabang` LIKE '".$id_cabang2."%'";
print ($query1);
mysql_query($query1)or die(mysql_error());
//while($row = mysql_fetch_array($result)){



//}
header ('location:halaman_data_cabang.php?berhasil=1');
}
else {
header('location:halaman_data_cabang.php?berhasil=2');

}
}
?>