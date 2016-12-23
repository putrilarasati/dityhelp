<?php include("koneksi.php");
if(isset($_POST['id_indikator']) && isset($_POST['indikator'])) {
print ("apa");
if(!empty($_POST['id_indikator']) && !empty($_POST['indikator'])) {

$id_indikator  = $_POST['id_indikator'];
$indikator = $_POST['indikator'];

$query= " UPDATE `indikatortbl` SET `indikator`='".$indikator."' WHERE `id_indikator`='".$id_indikator."'";
print ($query);
mysql_query($query)or die(mysql_error());
header('location:halaman_indikatorpenilaian.php?berhasil=1');
}
else {
header('location:halaman_indikatorpenilaian.php?berhasil=2');
}
}

$id_indikator = $_GET['id_indikator'];
$query = "SELECT * FROM indikatortbl where id_indikator=".$id_indikator."";
print ($query);
$result=mysql_query($query);

while($row = mysql_fetch_array($result)){
$id_indikator= $row["id_indikator"];
$indikator = $row["indikator"];
print($id_indikator);
print($indikator);

}

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <title>Halaman Input Indikator Baru</title>
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
<li class="active"  class="dropdown">
      <a class="dropdown-toggle" data-toggle="dropdown" href="#">Penilaian <span class="caret"></span></a>
      <ul class="dropdown-menu">
        <li><a href="halaman_datapenilaian.php">Lihat Data Penilaian</a></li>
        <li><a href="halaman_indikatorpenilaian.php">Lihat Indikator Penilaian</a></li>                      
      </ul>
    </li>
 <li><a href="halaman_kelola_akun.php">Kelola Akun</a></li>
 </div>
 
   <h2>Form Input Indikator Baru</h2>
 <form  id="Findikator" name="Findikator" method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>">

<div class="form-group">
      <label for="focusedInput">Indikator	:</label>
      <input class="form-control" id="focusedInput" name="indikator" value=  " <?php echo $indikator;
	  ?>" type="text">  
	  <input type="hidden" name="id_indikator" value= " <?php echo $id_indikator; ?>" >
    </div>
<form>
<div>
<button type="submit" class="btn btn-default">Submit</button>
  </form>

</body>
</html>