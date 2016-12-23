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
<table class="table table-bordered">
<?php

$id_provider = $_POST['id_provider'];
$id_cabang = $_POST['id_cabang'];
$bulan = $_POST['bulan'];
$tahun = $_POST['tahun'];

$query1= "SELECT * FROM tagihantbl a INNER JOIN tokotbl b ON a.id_toko=b.id_toko INNER JOIN providertbl c ON a.id_provider=c.id_provider where a.id_provider='".$id_provider."' AND id_cabang='".$id_cabang."' AND month(periode_tagihan)='".$bulan."' AND year(periode_tagihan)='".$tahun."'";      
print ($query1);
if (mysql_query($query1)) {

$result=mysql_query($query1);
}
else die ("Error". mysql_error());      
                    if (mysql_num_rows($result) > 0)     
{  
	echo "<thead>";
    echo "<tr>";
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
echo "<td>".$row["id_toko"]."</td>";
echo "<td>".$row["nama_toko"]."</td>";
echo "<td>".$row["nama_provider"]."</td>";
echo "<td>".$row["invoice_number"]."</td>";
echo "<td>".$row["bandwith"]."</td>";
echo "<td>".$row["jumlah_tagihan"]."</td>";
echo "<td>".$row["periode_tagihan"]."</td>";
echo "</tr>";
}
echo "</table>";

}
else echo "Tidak ada data";

?>

</tbody>
</table>
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