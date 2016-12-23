<?php include("koneksi.php");
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
</head>
<body>

<div class="container">
  <div class="jumbotron">
  <div class="pull-right"><a href="#"><img src="bootstrap-3.3.6-dist/images/images_burned.png" alt="" /></a></div>
<h2>Sistem Pelaporan dan Pengecekan Tagihan Koneksi Internet</h2>
 
<ul class="nav nav-tabs">
    <li><a href="halaman_utamacabang.php">Home</a></li>
    <li class="active" class="dropdown">
      <a class="dropdown-toggle" data-toggle="dropdown" href="#">Data Toko <span class="caret"></span></a>
      <ul class="dropdown-menu">
        <li><a href="halaman_datatoko.php">Lihat Data Provider Toko</a></li>
        <li><a href="halaman_inputtoko.php">Input Data Provider Toko</a></li>                      
      </ul>
    </li>
<li class="dropdown">
      <a class="dropdown-toggle" data-toggle="dropdown" href="#">Provider <span class="caret"></span></a>
      <ul class="dropdown-menu">
        <li><a href="halaman_dataprovider.php">Lihat Data Provider Aktif</a></li>
        <li><a href="halaman_inputpenilaian.php">Input Penilaian Provider</a></li>                      
      </ul>
    </li>
 </div>
<h4>Silahkan masukkan data provider pada formulir dibawah</h4>
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