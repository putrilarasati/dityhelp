<?php include("koneksi.php");

if(isset($_POST['id_provider']) && isset($_POST['nama_provider']) && isset($_POST['periode_kontrak'])) {
if(!empty($_POST['id_provider']) && !empty($_POST['nama_provider']) && !empty($_POST['periode_kontrak'])) {
$id_provider  = $_POST['id_provider'];
$nama_provider  = $_POST['nama_provider'];
$periode_kontrak  = $_POST['periode_kontrak'];

$query= " UPDATE `providertbl` SET `nama_provider`='".$nama_provider."',`periode_kontrak`='".$periode_kontrak."' WHERE `id_provider`='".$id_provider."'";

mysql_query($query)or die(mysql_error());

header('location:halaman_data_provider.php?berhasil=1');
}
else {
header('location:halaman_data_provider.php?berhasil=2');

}
}
$id_provider = $_GET['id_provider'];
$query = "SELECT * FROM providertbl where id_provider=".$id_provider."";
//print ($query);
$result=mysql_query($query);

while($row = mysql_fetch_array($result)){
$nama_provider= $row["nama_provider"];
$periode_kontrak = $row["periode_kontrak"];


}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <title>Halaman Edit Provider Active</title>
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
     <li class="active" ><a href="halaman_data_provider.php">Data Provider </a></li>
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
 </div>
 <h2>Form Edit Data Provider</h2>
 <h5>Untuk mengubah Data Provider, silahkan isi formulir berikut :</h5>
 <form  id="FLogin" name="FLogin" method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>">
    <div class="form-group">
      <label for="focusedInput">Nama Provider	:</label>
	  <input class="form-control" id="focusedInput" name="nama_provider" value=  " <?php echo $nama_provider;  ?>" type="text">  
	  <input type="hidden" name="id_provider" value= " <?php echo $id_provider; ?>" >
	  
  </select>
    </div>
<div class="form-group">
  <label for="sel1">Periode Kontrak:</label>

  <select name="periode_kontrak" class="form-control" id="sel1">
    <option value="Bebas Kontrak" >Bebas Kontrak</option>
    <option value="1 Tahun" >1 Tahun</option>
    <option value="2 Tahun" >2 Tahun</option>
    <option value="3 Tahun" >3 Tahun</option>

</select>

</div>
<div>
<button type="submit" class="btn btn-default">Submit</button>     <a href="halaman_data_provider.php" type="button" class=" btn-link">Cancel</a>
</form>
</body>
</html>