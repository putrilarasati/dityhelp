<!DOCTYPE html>
<html lang="en">
<head>
  <title>Halaman Data Provider</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.2/jquery.min.js"></script>
  <script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
</head>
<body>

<div class="container">
  <div class="jumbotron">
    <h3>Hi ! Anda berada di halaman utama Sistem Pelaporan dan Pengecekan Tagihan Koneksi Internet</h3>
<ul class="nav nav-tabs">
    <li class=><a href="#">Home</a></li>
    <li class= "dropdown">
      <a class="dropdown-toggle" data-toggle="dropdown" href="#">Data Provider <span class="caret"></span></a>
      <ul class="dropdown-menu">
        <li><a href="#">Input Data Provider</a></li>
        <li><a href="#">Lihat Data Provider</a></li>                      
      </ul>
    </li>
<li class="active" "dropdown">
      <a class="dropdown-toggle" data-toggle="dropdown" href="#">Data Tagihan <span class="caret"></span></a>
      <ul class="dropdown-menu">
        <li><a href="#">Input Data Tagihan</a></li>
        <li class="drowdown" ><a class="dropdown-toggle" data-toggle="dropdown" href="#" >Lihat Data Tagihan</a></li>
		
      </ul>
    </li>
<li class="dropdown">
      <a class="dropdown-toggle" data-toggle="dropdown" href="#">Data Penilaian Performa <span class="caret"></span></a>
      <ul class="dropdown-menu">
        <li><a href="#">Input Data Penilaian Performa</a></li>
        <li><a href="#">Lihat Data Penilaian Performa</a></li>                      
      </ul>
    </li>
  </ul>
</div>
<div class="container">
<div class="container">
  <div class="btn-group">
    <button type="button" class="btn btn-primary">Filter berdasarkan Provider</button>
    <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown">
      <span class="caret"></span>
    </button>
    <ul class="dropdown-menu" role="menu">
      <li><a href="#">Icon Plus</a></li>
      <li><a href="#">Telkomsel</a></li>
    </ul>
  </div>
<div class="btn-group">
    <button type="button" class="btn btn-primary">Filter berdasarkan Cabang</button>
    <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown">
      <span class="caret"></span>
    </button>
    <ul class="dropdown-menu" role="menu">
      <li><a href="#">DC. SAT BALARAJA</a></li>
      <li><a href="#">DC. SAT CILEUNGSI</a></li>
    </ul>
  </div>
<button type="button" class="btn btn-link">Lihat Rekapitulasi Data Dalam Bentuk Grafik</button>
</div>

  <h2>Daftar Tagihan Toko</h2>           
  <table class="table table-bordered">
    <thead>
      <tr>
        <th>ID Toko</th>
        <th>Nama Toko</th>
        <th>ID Cabang</th>
        <th>Nama Cabang</th>
        <th>Provider Active</th>
        <th>Invoice Number</th>
        <th>Bandwith</th>
        <th>Jumlah Tagihan</th>
        <th>Periode Tagihan</th>

      </tr>
    </thead>
    <tbody>
      <tr>
        <td>John</td>
        <td>Doe</td>
        <td>john@example.com</td>
      </tr>
      <tr>
        <td>Mary</td>
        <td>Moe</td>
        <td>mary@example.com</td>
      </tr>
      <tr>
        <td>July</td>
        <td>Dooley</td>
        <td>july@example.com</td>
      </tr>
    </tbody>
  </table>
</div>
</body>
</html>