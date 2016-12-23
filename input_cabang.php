<?php include("koneksi.php");

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <title>Halaman Input Data Cabang</title>
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
 <li><a href="halaman_kelola_akun.php">Kelola Akun</a></li>
 </div>
 
   <h2>Form Input Data Cabang Baru</h2>
<?php
if (isset($_GET['berhasil'])) {
$berhasil=$_GET['berhasil'];

if ($berhasil==1) {
?>

<div class="alert alert-success">
  <strong>Success!</strong> Data Cabang berhasil ditambahkan.
</div>
 <?php
}
else {
?>
<div class="alert alert-danger">
  <strong>Gagal!</strong> Data cabang gagal ditambahkan, mohon periksa kembali format pengisian.
</div>
<?php
}
}
?> 
 <form  id="FLogin" name="FLogin" method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>">
    <div class="form-group">
      <label for="focusedInput">ID Cabang		:</label>
      <input class="form-control" id="focusedInput" name="id_cabang" type="text">
    </div>
<div class="form-group">
      <label for="focusedInput">Nama Cabang		:</label>
      <input class="form-control" id="focusedInput" name="nama_cabang" type="text">
    </div>
<div class="form-group">
      <label for="focusedInput">Alamat Cabang	:</label>
      <input class="form-control" id="focusedInput" name="alamat" type="text">
    </div>
<form>
<div>
<button type="submit" class="btn btn-default">Submit</button>
  </form>
  <?php
if(isset($_POST['id_cabang']) && isset($_POST['nama_cabang']) && isset($_POST['alamat']) ) {
if(!empty($_POST['id_cabang']) && !empty($_POST['nama_cabang']) && !empty($_POST['alamat'])) {



$id_cabang = $_POST['id_cabang'];
$nama_cabang  = $_POST['nama_cabang'];
$alamat  = $_POST['alamat'];

mysql_query("INSERT INTO cabangtbl(id_cabang,nama_cabang,alamat_cabang)
VALUE('$id_cabang','$nama_cabang','$alamat')")or die(mysql_error());
header('location:input_cabang.php?berhasil=1');

}
else {
header('location:input_cabang.php?berhasil=2');
}
}
?>
</body>
</html>