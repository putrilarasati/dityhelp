<?php include("koneksi.php");

if(isset($_POST['id_toko1']) && isset($_POST['keterangan']) && isset($_POST['id_provider1']) && isset($_POST['status']) && isset($_POST['berlangganan']) && isset($_POST['berakhir'])) {
print ("hello");
if(!empty($_POST['id_toko1']) && !empty($_POST['keterangan']) && !empty($_POST['id_provider1']) && !empty($_POST['status']) && !empty($_POST['berlangganan']) && !empty($_POST['berakhir'])) {

$id_toko1  = $_POST['id_toko1'];
$keterangan  = $_POST['keterangan'];
$id_provider1  = $_POST['id_provider1'];
$status  = $_POST['status'];
$berlangganan= $_POST['berlangganan'];
$berakhir= $_POST['berakhir'];

mysql_query("INSERT INTO `mappingprovidertbl` (`id_toko`, `id_provider`, `status`, `berlangganan`, `berakhir`, `keterangan`)
VALUES('$id_toko1','$id_provider1','$status','$berlangganan','$berakhir', '$keterangan')")or die(mysql_error());
header('location:halaman_gantiprovidertoko.php?berhasil=1');
}
else {
header('location:halaman_gantiprovidertoko.php?berhasil=2');
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
     <li><a href="halaman_utama_cabang.php">Home</a></li>
     <li class="active" class="dropdown">
      <a class="dropdown-toggle" data-toggle="dropdown" href="#">Data Toko <span class="caret"></span></a>
      <ul class="dropdown-menu">
        <li><a href="halaman_datatoko.php">Lihat Data Provider Toko</a></li>
        <li><a href="halaman_inputtoko.php">Input Data Provider Toko</a></li>
		<li><a href="halaman_gantiprovidertoko.php">Ganti Provider Toko</a></li>		
      </ul>
    </li>
     <li><a href="halaman_dataprovider.php">Data Provider </a></li>
 </div>
 <h2>Form Ganti Provider</h2>
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

<button type="submit" class="btn btn-default">Ganti Provider</button>
<?php
if(isset($_POST['id_toko'])) {
if(!empty($_POST['id_toko'])) {

$id_toko = $_POST['id_toko'];
$query="SELECT * FROM `tokotbl` WHERE `id_toko` = '$id_toko' ";
$result=mysql_query($query);

while($row = mysql_fetch_array($result)) {

$id_toko=$row['id_toko'];
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
print ($id_toko);
print ($id_provider);


?>
<form  id="FLogin2" name="FLogin2" method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>">
<div id="formtagihan">
<div class="form-group">
      <label for="focusedInput">ID Toko	:</label>
      <input class="form-control" id="focusedInput" name="id_toko" type="text" value="<?php echo $id_toko; ?>" disabled>
	  <input name="id_toko1" value="<?php echo $id_toko; ?>" type="hidden">
	 
    </div>
<div class="form-group">
      <label for="focusedInput">Provider Lama	:</label>
      <input class="form-control" id="focusedInput" name="nama_provider" type="text" value="<?php echo $nama_provider; ?>" disabled>
	  <input type="hidden" name="id_provider" value="<?php echo $id_provider; ?>">
    </div>
<div class="form-group">
      <label for="focusedInput">Keterangan Ganti Provider	:</label>
      <select name="keterangan" class="form-control" id="sel1">
	<option value="Cancel By Request" >Cancel By Request</option>
    <option value="Kontrak Habis" >Kontrak Habis</option>
    <option value="Toko Tutup" >Toko Tutup</option>

  </select>
    </div>
<div class="form-group">
      <label for="focusedInput">Provider Baru	:</label>
	  <select name="id_provider1" class="form-control" id="sel2">
	   <?php
 

$query = "SELECT * FROM `providertbl`";
$result=mysql_query($query);
while($row = mysql_fetch_array($result)){
echo "<option value=\"".$row["id_provider"]."\"> ".$row["nama_provider"]."  </option>"; 
}
?>
</select>
	<div class="form-group">
    <label for="focusedInput">Status	:</label>
	<input class="form-control" id="focusedInput" name="status" type="text" value="Aktif" disabled>

</div>
<div>

  <label> Mulai berlangganan: </label>
</div>
<div>
<input type="text" data-date-format="yyyy-mm-dd" placeholder="Masukan tanggal mulai berlangganan" name="berlangganan" id="periode_tagihan" class="form-control datetime" 
											   value="">

</div>
<div>

<label> Berakhir Berlangganan : </label>
</div>
<div>
<input type="text" data-date-format="yyyy-mm-dd" placeholder="Masukan tanggal mulai berakhir" name="berakhir" id="periode_tagihan" class="form-control datetime" 
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