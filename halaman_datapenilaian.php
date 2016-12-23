 <title>Halaman Data Penilaian</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="bootstrap-3.3.6-dist/css/bootstrap.min.css">
  <script src="bootstrap-3.3.6-dist/js/jquery.min.js"></script>
  <script src="bootstrap-3.3.6-dist/js/bootstrap.min.js"></script>
  <script type="text/javascript" src="JS/jquery-1.4.js"></script>
  <script type="text/javascript" src="JS/jquery.fusioncharts.js"></script>
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
<li class="active" class="dropdown">
      <a class="dropdown-toggle" data-toggle="dropdown" href="#">Penilaian <span class="caret"></span></a>
      <ul class="dropdown-menu">
        <li><a href="halaman_datapenilaian.php">Lihat Data Penilaian</a></li>
        <li><a href="halaman_indikatorpenilaian.php">Lihat Indikator Penilaian</a></li>                      
      </ul>
    </li>
 </div>
<div>
 Grafik Tingkat Pelayanan setiap Provider<hr align="left" size="1" color="#cccccc">
</div>
<table id="TableProv" border="0" align="center" cellpadding="10">
<tr bgcolor="#FF9900" style='display:none;'> <th>Provider</th> <th>Tingkat Pelayanan </th></tr>
 
		<?php
		include("koneksi.php");
                    
                $query_prov = "SELECT * FROM providertbl"; 
				$result_prov = mysql_query ($query_prov);
				$count_prov = mysql_num_rows ($result_prov);


                while( $data = mysql_fetch_array( $result_prov ) ){
				//$jumah_tagihan = $data['jumlah_tagihan'];
				//$nama_provider = $data['nama_provider'];
				$id_provider = $data['id_provider'];

				$query_toko= "SELECT AVG(nilai) AS total_nilai FROM penilaiantbl WHERE id_provider=".$id_provider."";
				//print ($query_toko);
				
				
				$result_toko = mysql_query ($query_toko);
				$data_toko = mysql_fetch_array ($result_toko);

				echo "<tr bgcolor='#D5F35B' style='display:none;'>
				
				<td> $data[nama_provider]</td>
				<td aligne='center'>$data_toko[total_nilai]</td>
				</tr>";
				
                  }             
                  ?>
				  </table>
				  <script type="text/javascript">
 $('#TableProv').convertToFusionCharts({
 swfPath: "Charts/",
 type: "MSColumn3D",
 data: "#TableProv",
 dataFormat: "HTMLTable"
 });


	</script>


</body>
</html>