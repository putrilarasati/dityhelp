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
	 $query_prov = "SELECT * FROM providertbl"; 
				$result_prov = mysql_query ($query_prov);
				$count_prov = mysql_num_rows ($result_prov);
                while( $data = mysql_fetch_array( $result_prov ) ){
				$id_provider = $data['id_provider'];
                $query_toko   = "SELECT COUNT (*) AS jumlah_toko FROM mappingprovidertbl WHERE id_provider='$id_provider'";    
				//print ($query_toko);
				$result_toko = mysql_query ($query_toko);
                while ($data_toko = mysql_fetch_array( $result_toko )){
               // $jumlah = $data['jumlah_toko'];                 
				  
				  
				  

				  
                  
                  } ?>
		
            ['<?php echo $data[nama_provider]; ?>', <?php echo $data_toko[jumlah_toko]; ?>]
			<?php
			}
			?>
			
		

		]
		}]
    });
});

	</script>
	</head>
	<body>

<div id="container" style="min-width: 310px; height: 400px; margin: 0 auto"></div>





</body>
</html>
