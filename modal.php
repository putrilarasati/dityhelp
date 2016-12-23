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
 
   <h2>Daftar Cabang</h2>
<a href="input_cabang.php" type="button" class="btn btn-default">+ Tambah Data</a> 
 <br></br>
 <table class="table table-bordered">
 <?php

$query="SELECT * FROM cabangtbl";      
  
//menjalankan query      
if (mysql_query($query)) {      
$result=mysql_query($query);     
} else die ("Error menjalankan query". mysql_error()); 
                   if (mysql_num_rows($result) > 0)     
{     
    echo "<thead>";
    echo "<tr>";
    echo "<th>ID Cabang</th>";
    echo "<th>Nama Cabang</th>";
    echo "<th>Aksi</th>";
    echo "</tr>";
    echo "</thead>";
    echo "<tbody>";
while($row = mysql_fetch_array($result)) {    
echo "<tr>";
echo "<td>".$row["id_cabang"]."</td>";
echo "<td>".$row["nama_cabang"]."</td>";
echo "<td> <a href=\"#\" class=\"glyphicon glyphicon-remove text text-danger\" id=\"myBtn\" title=\"Klik untuk menghapus data\"> </a></td>";






echo "</tr>";
}
echo "</table>";
}
else echo "Tidak ada data dalam tabel";
?>
 <!-- Modal -->
  <div class="modal fade" id="myModal" role="dialog">
    <div class="modal-dialog">
    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Modal Header</h4>
        </div>
        <div class="modal-body">
          <p>Some text in the modal.</p>
        </div>
        <div class="modal-footer">
		<button type="button" class="btn btn-default" >Ya</button>
		<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
      </div>
      
    </div>
  </div>

 
  <ul class="pagination">
  <li class="active"><a href="#">1</a></li>
  <li ><a href="#">2</a></li>
  <li><a href="#">3</a></li>
  <li><a href="#">4</a></li>
  <li><a href="#">5</a></li>
</ul>
<script>
$(document).ready(function(){
    $("#myBtn").click(function(){
        $("#myModal").modal();
    });
});
</script>


</body>
</html>