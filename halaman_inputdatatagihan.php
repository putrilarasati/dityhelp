<?php include("koneksi.php");
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <title>Halaman Utama HO</title>
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
     <li class="active" ><a href="halaman_data_provider.php">Data Provider </a></li>
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
<h4>Silahkan masukkan data tagihan koneksi internet pada formulir dibawah</h4>
<form class="form-inline" role="form">
    <div class="form-group">
      <label for="focusedInput">ID Toko</label>
      <input class="form-control" id="focusedInput" type="text">
    </div>
<br>
<br>
<div class="form-group">
      <label for="focusedInput">Nama Toko</label>
      <input class="form-control" id="focusedInput" type="text">
    </div>
<br>
<br>
<div class="form-group">
      <label for="focusedInput">Alamat Toko</label>
      <input class="form-control" id="focusedInput" type="text">
    </div>
<br>
<br>
<form role="form">
    <div class="form-group">
      <label for="sel1">Cabang:</label>
      <select class="form-control" id="sel1">
        <option>1</option>
        <option>2</option>
        <option>3</option>
        <option>4</option>
      </select>
<br>
<br>
</form>
<form role="form">
    <div class="form-group">
      <label for="sel1">Provider:</label>
      <select class="form-control" id="sel1">
        <option>1</option>
        <option>2</option>
        <option>3</option>
        <option>4</option>
      </select>
</div>
</form>

<form>
<div>
<br>
<button type="submit" class="btn btn-default">Submit</button>
  </form>
</div>
</body>
</html>