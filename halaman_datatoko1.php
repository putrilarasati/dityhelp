<?php include("koneksi.php");
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <title>Halaman Data Toko</title>
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
 
   <form role="form">
    <div class="form-group">
      <label for="sel1">Filter berdasarkan Provider:</label>
      <select name="id_provider" class="form-control" id="sel1">
 <?php
 

$query = "SELECT * FROM `providertbl`";
$result=mysql_query($query);
while($row = mysql_fetch_array($result)){
echo "<option value=\"".$row["id_provider"]."\"> ".$row["nama_provider"]."  </option>"; 

}
?>
      </select>
	  
</div>

  <h2>Daftar Provider Toko</h2>
<a href="halaman_inputtoko.php" type="button" class="btn btn-default">Tambah Data</a> <a href="grafik1.php" type="button" class="btn btn-link">Lihat Rekapitulasi Data Dalam Bentuk Grafik</a>  
 <br></br>
 <table class="table table-bordered col-md-12">
 <?php

$query="SELECT * FROM tokotbl a INNER JOIN cabangtbl c ON a.id_cabang=c.id_cabang INNER JOIN mappingprovidertbl d ON a.id_toko=d.id_toko INNER JOIN providertbl b ON d.id_provider=b.id_provider";      
  
//menjalankan query      
if (mysql_query($query)) {      
$result=mysql_query($query);     
} else die ("Error menjalankan query". mysql_error()); 
                   if (mysql_num_rows($result) > 0)     
{     
    echo "<thead>";
    echo "<tr>";
    echo "<th class=\"col-md-1\" >ID Toko</th>";
    echo "<th class=\"col-md-1\" >Nama Toko</th>";
    echo "<th class=\"col-md-1\" >Provider</th>";
	echo "<th class=\"col-md-1\" >Aksi</th>";
   // echo "<th>Tanggal Tutup Toko</th>";
    echo "</tr>";
    echo "</thead>";
    echo "<tbody>";
while($row = mysql_fetch_array($result)) {    
echo "<tr>";
echo "<td>".$row["id_toko"]."</td>";
echo "<td><a href=\"#\"title=\"Klik untuk melihat detail data\"  data-toggle=\"popover\" data-content= \"<strong> haha </strong> ".$row["status_toko"]." \" html: \"true\" >  ".$row["nama_toko"]."</a></td>";
echo "<td><a href=\"#\" data-toggle=\"popover\"  data-content=\"Some content inside the popover\" title=\"Klik untuk melihat detail data\"> ".$row["nama_provider"]."</a></td>";


echo "<td><a href=\"edit_toko.php?id_toko=".$row["id_toko"]."\"  class=\"glyphicon glyphicon-pencil\" title=\"Klik untuk mengedit data\"> </a> &nbsp; &nbsp; <a href=\"hapus_toko.php?id_toko=".$row["id_toko"]."\"  class=\"glyphicon glyphicon-remove text text-danger\" title=\"Klik untuk menghapus data\"> </a></td>";
//echo "<td>".$row["tutup_toko"]."</td>";

echo "</tr>";
}
echo "</table>";
}
else echo "Tidak ada data dalam tabel";
?>
  <ul class="pagination">
  <li class="active"><a href="#">1</a></li>
  <li ><a href="#">2</a></li>
  <li><a href="#">3</a></li>
  <li><a href="#">4</a></li>
  <li><a href="#">5</a></li>
</ul>
</body>
</html>
<script>
$(document).ready(function(){
    $('[data-toggle="popover"]').popover();   
});
</script>