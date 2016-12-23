 <title>Halaman Data Penilaian</title>
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
<li class="dropdown">
      <a class="dropdown-toggle" data-toggle="dropdown" href="#">Tagihan <span class="caret"></span></a>
      <ul class="dropdown-menu">
        <li><a href="halaman_datatagihan.php">Lihat Data Tagihan</a></li>
        <li><a href="halaman_inputdatatagihan.php">Input Data Tagihan</a></li>                      
      </ul>
    </li>
<li  class="dropdown">
      <a class="dropdown-toggle" data-toggle="dropdown" href="#">Penilaian <span class="caret"></span></a>
      <ul class="dropdown-menu">
        <li><a href="halaman_datapenilaian.php">Lihat Data Penilaian</a></li>
        <li><a href="halaman_indikatorpenilaian.php">Lihat Indikator Penilaian</a></li>                      
      </ul>
    </li>
 <li class="active" ><a href="halaman_kelola_akun.php">Kelola Akun</a></li>
 </div>


  <h2>Daftar Akun</h2>
<a  href="input_akun.php" type="button" class="btn btn-default">+ Tambah Data</a> 
 <br></br>
 <table class="table table-bordered">
   <?php

$query="SELECT * FROM usertbl";      
  
//menjalankan query      
if (mysql_query($query)) {      
$result=mysql_query($query);     
} else die ("Error menjalankan query". mysql_error()); 
                   if (mysql_num_rows($result) > 0)     
{     
    echo "<thead>";
    echo "<tr>";
    echo "<th>ID Pegawai</th>";
	echo "<th>Nama</th>";
    echo "<th>Password</th>";
    echo "<th>Level</th>";
    echo "<th>Aksi</th>";
    echo "</tr>";
    echo "</thead>";
    echo "<tbody>";
while($row = mysql_fetch_array($result)) {    
echo "<tr>";
echo "<td>".$row["id_pegawai"]."</td>";
echo "<td>".$row["nama"]."</td>";
echo "<td>".$row["password"]."</td>";
echo "<td>".$row["user_level"]."</td>";
echo "<td></td>";
echo "</tr>";

}
echo "</table>";
}
else echo "Tidak ada data dalam tabel";
?>
  </table>
</ul>
</body>
</html>