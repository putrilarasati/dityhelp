<?php include("koneksi.php");

if(isset($_POST['invoice_number']) && isset($_POST['id_toko1']) && isset($_POST['id_provider']) && isset($_POST['bandwith']) && isset($_POST['jumlah_tagihan']) && isset($_POST['periode_tagihan'])) {
print ("hello");
if(!empty($_POST['invoice_number']) && !empty($_POST['id_toko1']) && !empty($_POST['id_provider']) && !empty($_POST['bandwith']) && !empty($_POST['jumlah_tagihan']) && !empty($_POST['periode_tagihan'])) {

$invoice_number  = $_POST['invoice_number'];
$id_toko1  = $_POST['id_toko1'];
$id_provider  = $_POST['id_provider'];
$bandwith  = $_POST['bandwith'];
$jumlah_tagihan  = $_POST['jumlah_tagihan'];
$periode_tagihan= $_POST['periode_tagihan'];

mysql_query("INSERT INTO `tagihantbl` (`id_toko`, `id_provider`, `invoice_number`, `bandwith`, `jumlah_tagihan`, `periode_tagihan`)
VALUES('$id_toko1','$id_provider','$invoice_number','$bandwith','$jumlah_tagihan','$periode_tagihan')")or die(mysql_error());
header('location:input_tagihan1.php?berhasil=1');
}
else {
header('location:input_provider.php?berhasil=2');
}
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <title>Halaman Input Data Provider</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="bootstrap-3.3.6-dist/css/bootstrap.min.css">
  <script src="bootstrap-3.3.6-dist/js/jquery.min.js"></script>
  <script src="bootstrap-3.3.6-dist/js/bootstrap.min.js"></script>
  <script type="text/javascript" src="bootstrap-3.3.6-dist/js/bootstrap-datepicker/bootstrap-datepicker.js"></script>
</head>
<body>

<div class="container">
  <div class="jumbotron">
  <div class="pull-right"><a href="#"><img src="bootstrap-3.3.6-dist/images/images_burned.png" alt="" /></a></div>
<h2>Sistem Pelaporan dan Pengecekan Tagihan Koneksi Internet</h2>
<ul class="nav nav-tabs">
     <li><a href="halaman_utama_ho.php">Home</a></li>
     <li><a href="halaman_data_cabang.php">Data Cabang </a></li>
     <li><a href="halaman_data_provider.php">Data Provider </a></li>
<li class= "active" "dropdown">
      <a class="dropdown-toggle" data-toggle="dropdown" href="#">Tagihan <span class="caret"></span></a>
      <ul class="dropdown-menu">
        <li class ><a href="halaman_datatagihan.php">Lihat Data Tagihan</a></li>
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
 <h2>Form Input Data Tagihan</h2>
 <?php
if (isset($_GET['berhasil'])) {
$berhasil=$_GET['berhasil'];

if ($berhasil==1) {
?>

<div class="alert alert-success">
  <strong>Success!</strong> Penambahan data tagihan berhasil dilakukan.
</div>
 <?php
}
else {
?>
<div class="alert alert-danger">
  <strong>Gagal!</strong> Penambahan data tagihan gagal dilakukan, mohon periksa kembali format pengisian.
</div>
<?php
}
}
?> 
 
<form  id="FLogin" name="FLogin" method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>">
 <div class="form-group">
<input id="id_toko" list="id_toko" name="id_toko">
	<datalist id="id_toko" >
	<?php
	
	$query="SELECT * FROM tokotbl";      
	if (mysql_query($query)) {      
	$result=mysql_query($query);     
} 	else die ("Error menjalankan query". mysql_error()); 
    if (mysql_num_rows($result) > 0)
{
while($row = mysql_fetch_array($result)) {
echo "<option value=\"".$row["id_toko"]."\">";

} 
}
?>
</datalist>
</form>

<button type="submit" class="btn btn-default">Cek Provider</button>
<?php
if(isset($_POST['id_toko'])) {
if(!empty($_POST['id_toko'])) {

$id_toko = $_POST['id_toko'];
$query="SELECT * FROM `tokotbl` WHERE `id_toko` = '$id_toko' ";
$result=mysql_query($query);

while($row = mysql_fetch_array($result)) {

$id_toko=$row['id_toko'];
$nama_toko=$row['nama_toko'];
}
$query2="SELECT * FROM `mappingprovidertbl` WHERE `id_toko`='$id_toko'";

$result2=mysql_query($query2);
while($row = mysql_fetch_array($result2)){
$id_provider=$row['id_provider'];
}
$query3="SELECT * FROM `providertbl` WHERE `id_provider`='$id_provider'";

$result3=mysql_query($query3);
while($row = mysql_fetch_array($result3)){
$nama_provider=$row['nama_provider'];


}
//print ($nama_provider);

?>
<form  id="FLogin2" name="FLogin2" method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>">
<div id="formtagihan">
<div class="form-group">
      <label for="focusedInput">ID Toko	:</label>
      <input class="form-control" id="focusedInput" name="id_toko" type="text" value="<?php echo $id_toko; ?>" disabled>
	   <input name="id_toko1" value="<?php echo $id_toko; ?>" type="hidden">
	 
    </div>
<div class="form-group">
      <label for="focusedInput">Nama Toko	:</label>
      <input class="form-control" id="focusedInput" name="nama_toko" type="text" value="<?php echo $nama_toko; ?>" disabled>
	   <input name="nama_toko" value="<?php echo $nama_toko; ?>" type="hidden">
	 
    </div>
<div class="form-group">
      <label for="focusedInput">Provider	:</label>
      <input class="form-control" id="focusedInput" name="nama_provider" type="text" value="<?php echo $nama_provider; ?>" disabled>
	  <input type="hidden" name="id_provider" value="<?php echo $id_provider; ?>">
    </div>
<div class="form-group">
      <label for="focusedInput">Invoice Number	:</label>
      <input class="form-control" id="focusedInput" name="invoice_number" type="text">
    </div>
<div class="form-group">
      <label for="focusedInput">Bandwith	:</label>
      <input class="form-control" id="focusedInput" name="bandwith" type="text">
    </div>
<div class="form-group">
      <label for="focusedInput">Jumlah Tagihan	:</label>
   <div class="input-group">
  <span class="input-group-addon">Rp.</span>
  <input type="number" class="form-control" aria-label="Amount (to the nearest dollar)" name="jumlah_tagihan">
  <span class="input-group-addon">.00</span>
</div>
</div>
<div>

  <label> Periode Tagihan: </label>
</div>
<div>
<input type="text" data-date-format="yyyy-mm-dd" placeholder="Masukan tanggal periode tagihan" name="periode_tagihan" id="periode_tagihan" class="form-control datetime" 
											   value="">

</div>
<br>
<button type="submit" class="btn btn-default">Submit</button>
</form>
<?php

}
}
?>

</body>


</html>
<script>
$(function() {
	$('body').on('focus',".datetime", function(){
		$(this).datepicker();				
	});	
	}

);
</script>