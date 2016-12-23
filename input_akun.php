<?php include("koneksi.php");

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <title>Halaman Input Indikator Baru</title>
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
     <li><a href="halaman_data_cabang.php">Data Cabang </a></li>
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
 <li class="active" ><a href="halaman_kelola_akun.php">Kelola Akun</a></li>
 </div>
 
   <h2>Form Input Akun Baru</h2>
 <form  id="Findikator" name="Findikator" method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>">

<div class="form-group">
      <label for="focusedInput">ID Pegawai	:</label>
      <input class="form-control" id="focusedInput" name="id_pegawai" type="text">
    </div>
	<div class="form-group">
      <label for="focusedInput">Nama	:</label>
      <input class="form-control" id="focusedInput" name="nama" type="text">
    </div>
<div class="form-group">
      <label for="focusedInput">Password	:</label>
      <input class="form-control" id="focusedInput" name="password" type="text">
    </div>
   <div class="form-group">
      <label for="sel1"> Pilih User Level:</label> 
      <select name="user_level" class="form-control" id="sel1">
	  <option value="ho">HO </option>
	  <option value="ho">Cabang </option>
</select>
</div>
<form>
<div>
<button type="submit" class="btn btn-default">Submit</button>
  </form>
    </form>
  <?php
if (isset($_POST['id_pegawai']) && isset($_POST['nama']) && isset($_POST['password']) && isset($_POST['user_level'])) {

if (!empty($_POST['id_pegawai']) && !empty($_POST['nama']) && !empty($_POST['password']) && !empty($_POST['user_level'])) {


$id_pegawai = $_POST['id_pegawai'];
$nama = $_POST['nama'];
$password = $_POST['password'];
$user_level = $_POST['user_level'];


mysql_query("INSERT INTO usertbl(id_pegawai,nama,password,user_level)
VALUE('$id_pegawai','$nama','$password','$user_level')")or die(mysql_error());
header('location:notifikasi_sukses.php');
}
}
?>
</body>
</html>