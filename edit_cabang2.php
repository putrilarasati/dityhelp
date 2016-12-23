<?php include("koneksi.php");

?>

<?php
$id_cabang = $_GET['id_cabang'];
$query = "SELECT * FROM cabangtbl where id_cabang LIKE '".$id_cabang."%'";
//print ($query);
$result=mysql_query($query);

while($row = mysql_fetch_array($result)){
$id_cabang1= $row["id_cabang"];
$nama_cabang= $row["nama_cabang"];
$alamat_cabang = $row["alamat_cabang"];

//print($id_cabang1);
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
 </div>
 <h2>Form Edit Data Cabang</h2>
  <h5>Silahkan ubah data pada form dibawah : </h5>

 <form  id="FLogin" name="FLogin" method="POST" action="edit2.php">
    <div class="form-group">
      <label for="focusedInput">Nama Cabang	:</label>
	  <input class="form-control" id="focusedInput" name="nama_cabang" value=  " <?php echo $nama_cabang;  ?>" type="text">  
	  <input type="hidden" class="form-control" id="focusedInput" name="id_cabang" value= "<?php echo $id_cabang1; ?>" >
	  
  </select>
    </div>
<div class="form-group">
<label for="focusedInput">Alamat Cabang	:</label>
	  <input class="form-control" id="focusedInput" name="alamat_cabang" value=  " <?php echo $alamat_cabang;  ?>" type="text">  
</div>
<div>
<button type="submit" class="btn btn-default">Submit</button>     <a href="halaman_data_cabang.php" type="button" class=" btn-link">Cancel</a>


</form>
</body>
</html>