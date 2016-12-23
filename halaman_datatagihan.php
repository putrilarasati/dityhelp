<?php include("koneksi.php");

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <title>Halaman Data Tagihan</title>
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
<li  class="active"  class="dropdown">
      <a class="dropdown-toggle" data-toggle="dropdown" href="#">Tagihan <span class="caret"></span></a>
      <ul class="dropdown-menu">
        <li><a href="halaman_datatagihan.php">Lihat Data Tagihan</a></li>
        <li><a href="input_tagihan1.php">Input Data Tagihan</a></li>                      
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
 <h2>Daftar Tagihan Koneksi Internet</h2>
 <a href="input_tagihan1.php" type="button" class="btn btn-default">+ Tambah Data</a>  <a href="rekapitulasi_tagihan.php" type="button" class="btn btn-link"  >Lihat Rekapitulasi Data</a>  

 <form  id="filter" name="filter" method="POST" action="hasil_filter.php">
 <div class="form-group">
 
 <h5>Tabel dibawah merupakan daftar tagihan koneksi internet secara keseluruhan. Untuk melakukan filter data, pilih attribut flter pada form berikut:</h5>
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
<div class="form-group">
<label for="sel2">Filter berdasarkan Cabang:</label>
<select name="id_cabang" class="form-control" id="sel2">
 <?php
 

$query = "SELECT * FROM `cabangtbl`";
$result=mysql_query($query);
while($row = mysql_fetch_array($result)){
echo "<option value=\"".$row["id_cabang"]."\"> ".$row["nama_cabang"]."  </option>"; 

}

?>
</select>  
</div>
<label for="sel1">Bulan:</label>
<select name="bulan" class="form-control" id="sel1">
<option value="01">Januari</option>
<option value="02">Februari</option>
<option value="03">Maret</option>
<option value="04">April</option>
<option value="05">Mei</option>
<option value="06">Juni</option>
<option value="07">Juli</option>
<option value="08">Agustus</option>
<option value="09">September</option>
<option value="10">Oktober</option>
<option value="11">November</option>
<option value="12">Desember</option>
</select>

<label for="sel1">Tahun:</label>
<select name="tahun" class="form-control" id="sel1">
<?php
$mulai= date('Y') - 50;
for($i = $mulai;$i<$mulai + 100;$i++){
    $sel = $i == date('Y') ? ' selected="selected"' : '';
    echo '<option value="'.$i.'"'.$sel.'>'.$i.'</option>';
}
?>
</select>
<br>
<input type="submit" name="Filter" value="filter" class="btn btn-default"></input>  
</form>
<br>
<div>
<table class="table table-bordered">
<?php

$query="SELECT * FROM tagihantbl a INNER JOIN tokotbl b ON a.id_toko=b.id_toko INNER JOIN providertbl c ON a.id_provider=c.id_provider";      

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
    echo "<th>ID Toko</th>";
    echo "<th>Nama Toko</th>";
    echo "<th>Provider</th>";
    echo "<th>Invoice Number</th>";
	echo "<th>Bandwith</th>";
	echo "<th>Jumlah Tagihan</th>";
	echo "<th>Periode Tagihan</th>";
    echo "</tr>";
    echo "</thead>";
    echo "<tbody>";
while($row = mysql_fetch_array($result)) {    
echo "<tr>";
echo "<td>".$a."</td>";
echo "<td>".$row["id_toko"]."</td>";
echo "<td>".$row["nama_toko"]."</td>";
echo "<td>".$row["nama_provider"]."</td>";
echo "<td>".$row["invoice_number"]."</td>";
echo "<td>".$row["bandwith"]."</td>";
echo "<td>".$row["jumlah_tagihan"]."</td>";
echo "<td>".$row["periode_tagihan"]."</td>";
echo "</tr>";
$a++;
}


echo "</table>";
}
else echo "Tidak ada data dalam tabel";
?>
Download Data versi Excel      <a href="javascript:;" ><img src="bootstrap-3.3.6-dist/images/excel.png"width="18" height="18" border="0" onClick="window.open('./export_tagihansemua.php','scrollwindow','top=200,left=300,width=800,height=500');"></a>

<div>
  <ul class="pagination">
  <li class="active"><a href="#">1</a></li>
  <li ><a href="#">2</a></li>
  <li><a href="#">3</a></li>
  <li><a href="#">4</a></li>
  <li><a href="#">5</a></li>
</ul>
</div>
<script>
$(document).ready(function(){
    $("sel1").click(function(){
        $(this).hide();
    });
});
</script>
</body>
</html>