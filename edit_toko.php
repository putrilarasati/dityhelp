<?php include("koneksi.php");

if(isset($_POST['id_toko']) && isset($_POST['nama_toko']) && isset($_POST['alamat']) && isset($_POST['id_cabang']) && isset($_POST['id_provider']) && isset($_POST['status_berlangganan']) && isset($_POST['berlangganan']) && isset($_POST['berakhir']) && isset($_POST['status_toko']) && isset($_POST['buka_toko'])) {

if(!empty($_POST['id_toko']) && !empty($_POST['nama_toko']) && !empty($_POST['alamat']) && !empty($_POST['id_cabang']) && !empty($_POST['id_provider']) && !empty($_POST['status_berlangganan']) && !empty($_POST['berlangganan']) && !empty($_POST['berakhir']) && !empty($_POST['status_toko']) && !empty($_POST['buka_toko'])) {

$id_toko  = $_POST['id_toko'];
$nama_toko  = $_POST['nama_toko'];
$alamat  = $_POST['alamat'];
$id_cabang  = $_POST['id_cabang'];
$id_provider  = $_POST['id_provider'];
$status_berlangganan  = $_POST['status_berlangganan'];
$berlangganan  = $_POST['berlangganan'];
$berakhir  = $_POST['berakhir'];
$status_toko = $_POST['status_toko'];
$buka_toko = $_POST['buka_toko'];

$query= " UPDATE `tokotbl` SET `nama_toko`='".$nama_toko."',`alamat`='".$nama_cabang."' WHERE `id_cabang`='".$id_cabang."'";
print ($query);
mysql_query($query)or die(mysql_error());

header('location:halaman_data_provider.php?berhasil=1');
}
else {
header('location:halaman_data_provider.php?berhasil=2');

}
}

$id_toko  = $_GET['id_toko'];
$query = "SELECT * FROM tokotbl a INNER JOIN cabangtbl c ON a.id_cabang=c.id_cabang INNER JOIN mappingprovidertbl d ON a.id_toko=d.id_toko INNER JOIN providertbl b ON d.id_provider=b.id_provider WHERE a.id_toko='$id_toko'";
print ($query);
$result=mysql_query($query);

while($row = mysql_fetch_array($result)){
$nama_toko = $row["nama_toko"];
$alamat = $row["alamat"];
$id_cabang = $row["id_cabang"];
$nama_cabang = $row["nama_cabang"];
$nama_provider = $row["nama_provider"];
$berlangganan = $row["berlangganan"];
$berakhir = $row["berakhir"];
$buka_toko = $row["buka_toko"];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <title>Halaman Input Data Toko</title>
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
<li  ><a href="halaman_dataprovider.php">Provider</a></li>
 </div>
 
<h4>Silahkan masukkan data provider pada formulir dibawah</h4>
   <h2>Form Input Data Provider Baru</h2>
 <form  id="Finputtoko" name="Finputtoko" method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>">
    <div class="form-group">
      <label for="focusedInput">ID Toko	:</label>
      <input class="form-control" id="focusedInput" name="id_toko" type="text" value=" <?php echo $id_toko; ?>" disabled >
      
    </div>
	<div class="form-group">
      <label for="focusedInput">Nama Toko	:</label>
      <input class="form-control" id="focusedInput" name="nama_toko" value= " <?php echo $nama_toko;
	  ?>" type="text">
    </div>
    <div class="form-group">
      <label for="focusedInput">Alamat	:</label>
      <input class="form-control" id="focusedInput" name="alamat" type="text" value= " <?php echo $alamat;
	  ?>" >
    </div>
<div class="form-group">
  <label for="sel1">Nama Cabang	:</label>
  <select name="id_cabang" class="form-control" id="sel1">
 <?php
$query = "SELECT * FROM `cabangtbl`";
$result=mysql_query($query);
while($row = mysql_fetch_array($result)){
if ($id_cabang==$row["id_cabang"]) {
echo "<option value=\"".$row["id_cabang"]."\" selected > ".$row["nama_cabang"]." </option>";  
}
else {
echo "<option value=\"".$row["id_cabang"]."\" > ".$row["nama_cabang"]." </option>";
}
}
?>
</select>
</div>
<div class="form-group">
  <label for="sel1">Nama Provider	:</label>
  <select name="id_provider" class="form-control" id="sel2">
 <?php
 

$query = "SELECT * FROM `providertbl`";
$result=mysql_query($query);
while($row = mysql_fetch_array($result)){
echo "<option value=\"".$row["id_provider"]."\"> ".$row["nama_provider"]."  </option>"; 
}
?>
  </select>
</div>
<div class="form-group">
      <label for="focusedInput">Status Berlangganan	:</label>
      <input class="form-control" id="focusedInput" name="status_berlangganan1" type="text" value="Aktif" disabled>
	  <input class="form-control" id="focusedInput" name="status_berlangganan" type="hidden" value="Aktif">
</div>
<div>


  <label> Tanggal Berlangganan: </label>
</div>
<div>
<input type="text" data-date-format="yyyy-mm-dd" placeholder="Masukan tanggal mulai berlangganan yang tertera pada kontrak" name="berlangganan" id="periode_tagihan" class="form-control datetime" 
											   value= " <?php echo $berlangganan; ?>">

</div>
<div>
  <label> Tanggal Berakhir: </label>
</div>
<div>
<input type="text" data-date-format="yyyy-mm-dd" placeholder="Masukan tanggal berakhir langganan yang tertera pada kontrak" name="berakhir" id="periode_tagihan" class="form-control datetime" 
											   value= " <?php echo $berakhir; ?>">

</div>
<div class="form-group">
      <label for="focusedInput">Status Toko	:</label>
      <input class="form-control" id="focusedInput" name="status_toko1" type="text" value="Buka" disabled>
	  <input class="form-control" id="focusedInput" name="status_toko" type="hidden" value="Buka" >
</div>
<div>

  <label> Tanggal Buka Toko: </label>
</div>
<div>
<input type="text" data-date-format="yyyy-mm-dd" placeholder="Masukan tanggal periode tagihan" name="buka_toko" id="buka_toko" class="form-control datetime" 
											   value= " <?php echo $buka_toko; ?>" disabled>

</div>
<br>
<div>
<button type="submit" class="btn btn-default">Submit</button>
</div>
</form>


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