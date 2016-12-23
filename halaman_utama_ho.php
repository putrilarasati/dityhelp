
<!DOCTYPE html>
<html lang="en">
<head>
  <title>Halaman Utama HO</title>
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
     <li class="active"><a href="halaman_utama_ho.php">Home</a></li>
     <li><a href="halaman_data_cabang.php">Data Cabang </a></li>
     <li><a href="halaman_data_provider.php">Data Provider </a></li>
<li class="dropdown">
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
</body>

<div>
 Grafik Jumlah tagihan setiap Provider<hr align="left" size="1" color="#cccccc">
</div>
<table id="TableProv" border="0" align="center" cellpadding="10">
<tr bgcolor="#FF9900" style='display:none;'> <th>Provider</th> <th>Jumlah Tagihan</th></tr>
 
		<?php
		include("koneksi.php");
                    
                $query_prov = "SELECT * FROM providertbl"; 
				$result_prov = mysql_query ($query_prov);
				$count_prov = mysql_num_rows ($result_prov);


                while( $data = mysql_fetch_array( $result_prov ) ){
				//$jumah_tagihan = $data['jumlah_tagihan'];
				//$nama_provider = $data['nama_provider'];
				$id_provider = $data['id_provider'];

				$query_toko= "SELECT SUM(jumlah_tagihan) AS total_tagihan FROM tagihantbl WHERE id_provider=".$id_provider."";
				//print ($query_toko);
				
				
				$result_toko = mysql_query ($query_toko);
				$data_toko = mysql_fetch_array ($result_toko);

				echo "<tr bgcolor='#D5F35B' style='display:none;'>
				
				<td> $data[nama_provider]</td>
				<td aligne='center'>$data_toko[total_tagihan]</td>
				</tr>";
				
                  }             
                  ?>
				  </table>
<script type="text/javascript" src="http://static.fusioncharts.com/code/latest/fusioncharts.js"></script>
<script type="text/javascript" src="http://static.fusioncharts.com/code/latest/themes/fusioncharts.theme.fint.js?cacheBust=56"></script>			  
<script type="text/javascript">
 $('#TableProv').convertToFusionCharts({
 swfPath: "Charts/",
 type: "MSColumn3D",
 width: '450',
 height: '300',
 data: "#TableProv",
 dataFormat: "json",
 dataSource: {
 "charts": {
"theme": "fint",
            "caption": "Quarterly Revenue",
            "subcaption": "Last year",
            "xaxisname": "Quarter",
            "yaxisname": "Amount (In USD)",
            "numberPrefix": "$",
            "rotateValues": "0",
            "placeValuesInside": "0",
            "valueFontColor": "#000000",
            "valueBgColor": "#FFFFFF",
            "valueBgAlpha": "50",
            //Disabling number scale compression
            "formatNumberScale": "0",
            //Defining custom decimal separator
            "decimalSeparator": ",",
            //Defining custom thousand separator
            "thousandSeparator": "."
        }
	
	}


 });
</script>


</html>