
<!DOCTYPE html>
<html lang="en">
<head>
  <title>Halaman Utama Cabang</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="bootstrap-3.3.6-dist/css/bootstrap.min.css">
  <script src="bootstrap-3.3.6-dist/js/jquery.min.js"></script>
  <script src="bootstrap-3.3.6-dist/js/bootstrap.min.js"></script>
  <script src="Highcharts-5.0.4/code/highcharts.js"></script>
  <script src="Highcharts-5.0.4/code/modules/exporting.js"></script>
  
</head>
<body>

<div class="container">
  <div class="jumbotron">
  <div class="pull-right"><a href="#"><img src="bootstrap-3.3.6-dist/images/images_burned.png" alt="" /></a></div>
<h2>Sistem Pelaporan dan Pengecekan Tagihan Koneksi Internet</h2>

 
<ul class="nav nav-tabs">
    <li class="active" ><a href="halaman_utama_cabang.php">Home</a></li>
    <li class="dropdown">
      <a class="dropdown-toggle" data-toggle="dropdown" href="#">Data Toko <span class="caret"></span></a>
      <ul class="dropdown-menu">
        <li><a href="halaman_datatoko.php">Lihat Data Provider Toko</a></li>
        <li><a href="halaman_inputtoko.php">Input Data Provider Toko</a></li>
		<li><a href="halaman_gantiprovidertoko.php">Ganti Provider Toko</a></li>		
      </ul>
    </li>
<li  ><a href="halaman_dataprovider.php">Provider</a></li>

 </div>
<script type="text/javascript" src="JS/jquery-1.4.js"></script>
<script type="text/javascript" src="JS/jquery.fusioncharts.js"></script>

<div>
 Grafik Jumlah Toko setiap Provider<hr align="left" size="1" color="#cccccc">
</div>
<table id="TableSiswa" border="0" align="center" cellpadding="10">
<tr bgcolor="#FF9900" style='display:none;'> <th>Provider</th> <th>Jumlah Toko</th></tr>
 
		<?php
		include("koneksi.php");
                    
                $query_prov = "SELECT * FROM providertbl"; 
				$result_prov = mysql_query ($query_prov);
				$count_prov = mysql_num_rows ($result_prov);
                while( $data = mysql_fetch_array( $result_prov ) ){
				$id_provider = $data['id_provider'];

				$query_toko= "SELECT COUNT(*) AS jumlah_toko FROM mappingprovidertbl WHERE id_provider=".$id_provider."";
				//print ($query_toko);
				$result_toko = mysql_query ($query_toko);
				$data_toko = mysql_fetch_array ($result_toko);

				echo "<tr bgcolor='#D5F35B' style='display:none;'>
				
				<td> $data[nama_provider]</td>
				<td aligne='center'>$data_toko[jumlah_toko]</td>
				</tr>";
				
                  }             
                  ?>
				  </table>
				  <script type="text/javascript">
 $('#TableSiswa').convertToFusionCharts({
 swfPath: "Charts/",
 type: "MSColumn3D",
 data: "#TableSiswa",
 dataFormat: "HTMLTable"
 });


	</script>
	</head>
	<body>

<div id="container" style="min-width: 310px; height: 400px; margin: 0 auto"></div>





</body>
</html>
