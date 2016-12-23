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