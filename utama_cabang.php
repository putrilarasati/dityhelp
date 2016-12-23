<?php include("koneksi.php");
?>
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


<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.8.2/jquery.min.js"></script>
		<style type="text/css">
	<table id="TableSiswa" border="0" align="center" cellpadding="10">
<tr bgcolor="#FF9900" style='display:none;'> <th>Provider</th> <th>Jumlah Toko</th></tr>
 	
${demo.css}
		</style>
		<script type="text/javascript">
$(function () {
    Highcharts.chart('container', {
        chart: {
            type: 'column',
            options3d: {
                enabled: true,
                alpha: 10,
                beta: 25,
                depth: 70
            }
        },
        title: {
            text: 'Grafik Provider yang digunakan oleh Toko'
        },
        subtitle: {
            text: 'Notice the difference between a 0 value and a null point'
        },
        plotOptions: {
            column: {
                depth: 25
            }
        },
        xAxis: {
            categories: Highcharts.getOptions().lang.shortMonths
        },
        yAxis: {
            title: {
                text: 'Jumlah'
            }
        },
        series: [
		<?php
		$sql   = "SELECT id_provider  FROM providertbl";
        $query = mysql_query( $sql )  or die(mysql_error());
            while( $ret = mysql_fetch_array( $query ) ){
            	$nama_provider=$ret['nama_provider'];                     
                 $sql_jumlah   = "SELECT COUNT (*) AS jumlah_toko FROM mappingprovidertbl WHERE id_provider='$id_provider'";        
                 $query_jumlah = mysql_query( $sql_jumlah ) or die(mysql_error());
                 while( $data = mysql_fetch_array( $query_jumlah ) ){
                    $jumlah = $data['jumlah_toko']; 
					
                  }             
                  ?>
                  {
		
            name: '<?php echo $nama_provider; ?>',
            data: [<?php echo $jumlah; ?>]
        },
		 <?php } ?>
		 
		]
    });
});
</table>
	</script>
	</head>
	<body>

<div id="container" style="min-width: 310px; height: 400px; margin: 0 auto"></div>





</body>
</html>
