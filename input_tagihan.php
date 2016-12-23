<?php include("koneksi.php");
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <title>Halaman Input Data Provider</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="bootstrap-3.3.6-dist/css/bootstrap.min.css">
  <script src="bootstrap-3.3.6-dist/js/jquery.min.js"></script>
  <script src="bootstrap-3.3.6-dist/js/bootstrap.min.js"></script>
  <script type="text/javascript" src="bootstrap-3.3.6-dist/js/bootstrap-datepicker/bootstrap-datepicker.js"></script>
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
        <li class="active" ><a href="halaman_datatagihan.php">Lihat Data Tagihan</a></li>
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
 
   <h2>Form Input Data Tagihan</h2>
 <form  id="FLogin" name="FLogin" method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>">

	<div class="form-group">
	<input id="namatoko" list="toko" name="toko">
	<datalist id="toko" >
	<?php
	
	$query="SELECT * FROM tokotbl";      
	if (mysql_query($query)) {      
	$result=mysql_query($query);     
} 	else die ("Error menjalankan query". mysql_error()); 
    if (mysql_num_rows($result) > 0)
{
while($row = mysql_fetch_array($result)) {
echo "<option value=\"".$row["nama_toko"]."\">";

} 
}
?>

  </datalist>
      <label for="focusedInput">Provider Active</label>
      <input id="focusedInput" name="nama_provider" type="text" disabled>
	  
	  
<?php
$sql=mysql_query("SELECT * FROM tokotbl a INNER JOIN providertbl b ON a.id_provider=b.id_provider");



?>
  
<div id="formtagihan">
<div class="form-group">
      <label for="focusedInput">Invoice Number	:</label>
      <input class="form-control" id="focusedInput" name="invoice_number" type="text">
    </div>
<div class="form-group">
      <label for="focusedInput">Bandwith	:</label>
      <input class="form-control" id="focusedInput" name="nama_provider" type="text">
    </div>
<div class="form-group">
      <label for="focusedInput">Jumlah Tagihan	:</label>
   <div class="input-group">
  <span class="input-group-addon">Rp.</span>
  <input type="number" class="form-control" aria-label="Amount (to the nearest dollar)">
  <span class="input-group-addon">.00</span>
</div>
</div>
<div>
<form action="action_page.php">
  <label> Periode Tagihan: </label>
</div>
<div>
<input type="text" data-date-format="dd-mm-yyyy" placeholder="Masukan tanggal rencana pengiriman" name="tanggalKirim" id="tanggalKirim" class="form-control datetime" 
											   value="">
</form>
</div>
<br>
<button type="submit" class="btn btn-default">Submit</button>
</div>

  <?php
if(isset($_POST['nama_provider']) && isset($_POST['periode_kontrak'])) {
if(!empty($_POST['nama_provider']) && !empty($_POST['periode_kontrak'])) {

$nama_provider  = $_POST['nama_provider'];
$periode_kontrak  = $_POST['periode_kontrak'];

mysql_query("INSERT INTO providertbl(nama_provider,periode_kontrak)
VALUE('$nama_provider','$periode_kontrak')")or die(mysql_error());
header('location:notifikasi_sukses.php');
}
}
?>
</body>
</html>
<script>
$(function() {
	$('body').on('focus',".datetime", function(){
		$(this).datepicker();				
	});	

	$("#formtagihan").hide();

	$('#namatoko').change(function(){
		$("#formtagihan").show();
	} );
}

);
</script>