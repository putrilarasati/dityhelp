<?php include("koneksi.php");
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <title>Halaman Data Cabang</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="bootstrap-3.3.6-dist/css/bootstrap.min.css">
  <script src="bootstrap-3.3.6-dist/js/jquery.min.js"></script>
  <script src="bootstrap-3.3.6-dist/js/bootstrap.min.js"></script>
</head>
<body>

<div class="container">
<div class="jumbotron">
<div class="pull-right"><a href="#"><img src="bootstrap-3.3.6-dist/images/images_burned.png" alt="" /></a></div>
<h2>Sistem Pelaporan dan Pengecekan Tagihan Koneksi Internet</h2>

 
<ul class="nav nav-tabs">
     <li><a href="halaman_utama_ho.php">Home</a></li>
     <li class="active" ><a href="halaman_data_cabang.php">Data Cabang </a></li>
     <li><a href="halaman_data_provider.php">Data Provider </a></li>
<li class="dropdown">
      <a class="dropdown-toggle" data-toggle="dropdown" href="#">Tagihan <span class="caret"></span></a>
      <ul class="dropdown-menu">
        <li><a href="halaman_datatagihan.php">Lihat Data Tagihan</a></li>
        <li><a href="halaman_inputdatatagihan.php">Input Data Tagihan</a></li>                      
      </ul>
    </li>
<li class="dropdown">
      <a class="dropdown-toggle" data-toggle="dropdown" href="#">Penilaian <span class="caret"></span></a>
      <ul class="dropdown-menu">
        <li><a href="halaman_datapenilaian.php">Lihat Data Penilaian</a></li>
        <li><a href="halaman_indikatorpenilaian.php">Lihat Indikator Penilaian</a></li>                      
      </ul>
    </li>
 </div>
 
<h2>Daftar Cabang</h2>
<a href="input_cabang.php" title="Klik untuk menambah data cabang baru" type="button" class="btn btn-default">+ Tambah Data</a> 

<h5>Berikut ini merupakan daftar cabang PT. Sumber Alfaria Trijaya</h5>
<?php
if (isset($_GET['berhasil'])) {
$berhasil=$_GET['berhasil'];

if ($berhasil==1) {
?>

<div class="alert alert-success">
  <strong>Success!</strong> Perubahan berhasil disimpan.
</div>
 <?php
}
else {
?>
<div class="alert alert-danger">
  <strong>Gagal!</strong> Perubahan gagal disimpan, mohon periksa kembali format pengisian.
</div>
<?php
}
}
?> 
 <table class="table table-bordered">
 <?php
$batas=10;
$pg=isset($_GET['pg'])?$_GET['pg']:"";
if ( empty( $pg ) ) {
$posisi = 0;
$pg = 1;
} else {
$posisi = ( $pg - 1 ) * $batas;
}

$query="SELECT * FROM cabangtbl limit $posisi, $batas";      
$no=1+$posisi; 

$a=1;
//menjalankan query      
if (mysql_query($query)) {      
$result=mysql_query($query);     
} else die ("Error menjalankan query". mysql_error()); 
                   if (mysql_num_rows($result) > 0)     
{     
    echo "<thead>";
    echo "<tr>";
	echo "<th>No.</th>";
    echo "<th>ID Cabang</th>";
    echo "<th>Nama Cabang</th>";
	echo "<th>Alamat</th>";
    echo "<th>Aksi</th>";
    echo "</tr>";
    echo "</thead>";
    echo "<tbody>";
while($row = mysql_fetch_array($result)) {    
echo "<tr>";
echo "<td>".$a."</td>";
echo "<td>".$row["id_cabang"]."</td>";
echo "<td>".$row["nama_cabang"]."</td>";
echo "<td>".$row["alamat_cabang"]."</td>";
echo "<td><a href=\"edit_cabang2.php?id_cabang=".$row["id_cabang"]."\"  class=\"glyphicon glyphicon-pencil\" title=\"Klik untuk mengedit data\"> </a> &nbsp; &nbsp; <span class=\"test\"> <a href=\"hapus_cabang.php?id_cabang=".$row["id_cabang"]."\" class=\"glyphicon glyphicon-remove text text-danger\" id=\"btn\" title=\"Klik untuk menghapus data\"> </a> </span> </td>";
echo "</tr>";
$a++;
}
echo "</table>";
}
else echo "Tidak ada data dalam tabel";

$no++;


$jml_data= mysql_num_rows(mysql_query("SELECT * FROM cabangtbl"));

$jml_hal=ceil($jml_data/$batas);

if ($pg>1) {
$link = $pg-1;
$prev= "<a href='?pg=$link'>Sebelumnya </a>";
}
else	{
$prev="sebelumnya";
}
$nmr="";
for ($i=1; $i<= $jml_hal; $i++) {

if($i == $pg) {
$nmr.=$i."";
}
else	{
$nmr.="<a href='?pg=$i'>$i</a>";
}
}
if ($pg < $jml_hal) {
$link = $pg + 1;
$next = " <a href='?pg=$link'>Selanjutnya</a>";
} else {
$next = " Selanjutnya";
}
echo $prev . $nmr . $next;
?>

<script>
$(document).ready(function(){
$(".test").click(function() {
return confirm("Apakah anda yakin untuk menghapus?");

});
});
</script>


</body>
</html>