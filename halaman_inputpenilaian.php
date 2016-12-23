<?php include("koneksi.php");
for ($a=0;$a<$_POST['nilai'];$a++) {
//print ($_POST['id_provider']);
//print ($_POST['id_indikator'.$a]);
//print ($_POST['optradio'.$a]);

$id_provider  = $_POST['id_provider'];
$id_indikator  = $_POST['id_indikator'.$a];
$optradio = $_POST['optradio'.$a];
$query = "INSERT INTO `penilaiantbl` (`id_provider`, `id_indikator`, `nilai`)
VALUES('$id_provider','$id_indikator','$optradio')";
$result=mysql_query($query);

}
if(isset($_POST['id_provider']) && isset($_POST['id_indikator']) && isset($_POST['nilai'])) {

if(!empty($_POST['id_provider']) && !empty($_POST['id_indikator']) && !empty($_POST['nilai'])) {

header('location:halaman_inputpenilaian.php?berhasil=1');
}
else {
header('location:halaman_inputpenilaian.php?berhasil=2');
}
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <title>Halaman Input Data Penilaian</title>
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
    <li  class="dropdown">
      <a class="dropdown-toggle" data-toggle="dropdown" href="#">Data Toko <span class="caret"></span></a>
      <ul class="dropdown-menu">
        <li><a href="halaman_datatoko.php">Lihat Data Provider Toko</a></li>
        <li><a href="halaman_inputtoko.php">Input Data Provider Toko</a></li>                      
      </ul>
    </li>
<li class="active"class="dropdown">
      <a class="dropdown-toggle" data-toggle="dropdown" href="#">Provider <span class="caret"></span></a>
      <ul class="dropdown-menu">
        <li><a href="halaman_dataprovider.php">Lihat Data Provider Aktif</a></li>
        <li><a href="halaman_inputpenilaian.php">Input Penilaian Provider</a></li>                      
      </ul>
    </li>
 </div>
 <?php
if (isset($_GET['berhasil'])) {
$berhasil=$_GET['berhasil'];

if ($berhasil==1) {
?>

<div class="alert alert-success">
  <strong>Success!</strong> Penilaian berhasil ditambahkan.
</div>
 <?php
}
else {
?>
<div class="alert alert-danger">
  <strong>Gagal!</strong> Penilaian gagal ditambahkan, mohon periksa kembali format pengisian.
</div>
<?php
}
}
?> 
<h4>Anda dapat melakukan penilaian performa tiap provider, dengan mengisi formulir dibawah:</h4>
 <form  id="Finputnilai" name="Finputnilai" method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>">
    <div class="form-group">
      <label for="sel1"> Pilih provider yang akan dinilai:</label> 
      <select name="id_provider" class="form-control" id="sel1">
 <?php
 

$query = "SELECT * FROM `providertbl`";
$result=mysql_query($query);
while($row = mysql_fetch_array($result)){
echo "<option value=\"".$row["id_provider"]."\"> ".$row["nama_provider"]."  </option>"; 

}
?>
      </select>
<br>
 <table class="table table-bordered">
 <?php
 

$query="SELECT * FROM indikatortbl";      
  
//menjalankan query      
if (mysql_query($query)) {      
$result=mysql_query($query);     
} else die ("Error menjalankan query". mysql_error()); 
                   if (mysql_num_rows($result) > 0)     
{   

    echo "<thead>";
    echo "<tr>";
    echo "<th>Indikator Penilaian</th>";
	echo "<th>Nilai</th>";
    echo "</tr>";
    echo "</thead>";
    echo "<tbody>";
$i=0;   
while($row = mysql_fetch_array($result)) { 
echo "<tr>";
echo "<td> <input type=\"hidden\" name=\"id_indikator".$i."\" value=\"".$row["id_indikator"]."\" > ".$row["indikator"]."</td>";
echo "<td><label class=\"radio-inline\"><input type=\"radio\" value=\"1\" name=\"optradio".$i."\">1</label>
<label class=\"radio-inline\"><input type=\"radio\" value=\"2\" name=\"optradio".$i."\">2</label>
<label class=\"radio-inline\"><input type=\"radio\" value=\"3\" name=\"optradio".$i."\">3</label>
<label class=\"radio-inline\"><input type=\"radio\" value=\"4\" name=\"optradio".$i."\">4</label>
<label class=\"radio-inline\"><input type=\"radio\" value=\"5\" name=\"optradio".$i."\">5</label></td>";
echo "</tr>";
$i++;
}
echo "<input type=\"hidden\" name=\"nilai\" value=\"".$i."\" >";


echo "</tbody>";
echo "</table>";
}
else echo "Tidak ada data dalam tabel";
?>

  </form>
 <div>
 <button type="submit" class="btn btn-default">Submit</button>
</div>
</body>
</html>