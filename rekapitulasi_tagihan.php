<?php include("koneksi.php");

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <title>Halaman Rekapitulasi Data Tagihan</title>
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
 <h2>Rekapitulasi Tagihan Koneksi Internet</h2>

<h4> Rekapitulasi Total Tagihan Per Cabang </h4>
<table class="table table-bordered">
<?php

$query1="SELECT * FROM cabangtbl";  
$result1= mysql_query ($query1);
$count1= mysql_num_rows ($result1);

while ($data1 = mysql_fetch_array ($result1) ) {
$id_cabang = $data1['id_cabang'];
$nama_cabang = $data1['nama_cabang'];

$query_sum="SELECT SUM(jumlah_tagihan) AS total_tagihan FROM tagihantbl a INNER JOIN tokotbl b ON a.id_toko=b.id_toko INNER JOIN providertbl c ON a.id_provider=c.id_provider WHERE b.id_cabang='".$id_cabang."'";


$a=1;
//$query_sum= "SELECT SUM(jumlah_tagihan) AS total_tagihan FROM tagihantbl a INNER JOIN tokotbl b ON a.id_toko=b.id_toko INNER JOIN providertbl c ON bid_cabang='".$id_cabang."'";    
//print($query_sum);
$result2 = mysql_query ($query_sum);
$data_sum = mysql_fetch_array ($result2);

    echo "<thead>";
	echo "<tr>";
    echo "<th class=\"col-md-1\" >No.</th>";
    echo "<th>ID Cabang</th>";
	echo "<th>Nama Cabang</th>";
	echo "<th>Total Tagihan</th>";
    echo "</tr>";
    echo "</thead>";

//while($row = mysql_fetch_array($result2)) { 

echo "<tbody>"; 
echo "<tr>";
echo "<td>$a</td>";
echo "<td>$id_cabang</td>";
echo "<td>$nama_cabang</td>";
echo "<td>$data_sum[total_tagihan]</td>";
echo "</tr>";

$a++;
}
echo "</table>";

?>


<h4> Rekapitulasi Total Tagihan Per Provider </h4>
<table class="table table-bordered">
<?php

$querya="SELECT * FROM providertbl";  
$resulta= mysql_query ($querya);
$counta= mysql_num_rows ($resulta);

while ($dataa = mysql_fetch_array ($resulta) ) {
$id_provider = $dataa['id_provider'];
$nama_provider = $dataa['nama_provider'];

$query_sum1="SELECT SUM(jumlah_tagihan) AS total_tagihan FROM tagihantbl a INNER JOIN tokotbl b ON a.id_toko=b.id_toko INNER JOIN providertbl c ON a.id_provider=c.id_provider WHERE a.id_provider='".$id_provider."'";

$a=1;
//$query_sum1= "SELECT SUM(jumlah_tagihan) AS total_tagihan FROM tagihantbl a INNER JOIN tokotbl b ON a.id_toko=b.id_toko INNER JOIN providertbl c ON c.id_provider='".$id_provider."'";    
//print($query_sum1);
$resultb = mysql_query ($query_sum1);
$data_sumb = mysql_fetch_array ($resultb);

    echo "<thead>";
    echo "<tr>";
	echo "<th class=\"col-md-1\" >No.</th>";
	echo "<th>Nama Provider</th>";
	echo "<th>Total Tagihan</th>";
    echo "</tr>";
    echo "</thead>";
    echo "<tbody>";
 
echo "<tr >";
echo "<td>$a</td>";
echo "<td>$nama_provider</td>";
echo "<td>$data_sumb[total_tagihan]</td>";
echo "</tr>";
}
echo "</table>";

?>


Download Data versi Excel      <a href="javascript:;" ><img src="bootstrap-3.3.6-dist/images/excel.png"width="18" height="18" border="0" onClick="window.open('./eksport_rekapitulasi.php','scrollwindow','top=200,left=300,width=800,height=500');"></a>

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