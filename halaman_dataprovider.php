<?php include("koneksi.php");

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <title>Halaman Data Provider Active</title>
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
    <li class="dropdown">
      <a class="dropdown-toggle" data-toggle="dropdown" href="#">Data Toko <span class="caret"></span></a>
      <ul class="dropdown-menu">
        <li><a href="halaman_datatoko.php">Lihat Data Provider Toko</a></li>
        <li><a href="halaman_inputtoko.php">Input Data Provider Toko</a></li>
		<li><a href="halaman_gantiprovidertoko.php">Ganti Provider Toko</a></li>		
      </ul>
    </li>
<li class="active" ><a href="halaman_dataprovider.php">Provider</a></li>
</div>


  <h2>Data Provider Active</h2>
  <a href="halaman_inputpenilaian.php" type="button" class="btn btn-default">Masukkan penilaian untuk provider</a> 

 <br></br>
 <table class="table table-bordered">
 <?php

$query="SELECT * FROM providertbl";      
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
    echo "<th>Nama Provider</th>";
    echo "<th>Periode Kontrak</th>";
    echo "</tr>";
    echo "</thead>";
    echo "<tbody>";
while($row = mysql_fetch_array($result)) {    
echo "<tr>";
echo "<td>".$a."</td>";
echo "<td>".$row["nama_provider"]."</td>";
echo "<td>".$row["periode_kontrak"]."</td>";
echo "</tr>";

$a++;
}
echo "</table>";
}
else echo "Tidak ada data dalam tabel";
?>
  <ul class="pagination">
  <li class="active"><a href="#">1</a></li>
  <li><a href="#">2</a></li>
  <li><a href="#">3</a></li>
  <li><a href="#">4</a></li>
  <li><a href="#">5</a></li>
</ul>
</div> 
</body>
</html>