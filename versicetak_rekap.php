<?php include("koneksi.php");

?>
<h2>Rekapitulasi Tagihan Koneksi Internet</h2>
<h4> Rekapitulasi Total Tagihan Per Cabang </h4>
<table class="table table-bordered">
<?php

$query1="SELECT * FROM cabangtbl";  
$result1= mysql_query ($query1);
$count1= mysql_num_rows ($result1);

while ($data1 = mysql_fetch_array ($result1) ) {
$id_cabang = $data1['id_cabang'];
$nama_cabang = $data1['nama_cabang'];

$query_sum="SELECT SUM(jumlah_tagihan) AS total_tagihan FROM tagihantbl a INNER JOIN tokotbl b ON a.id_toko=b.id_toko INNER JOIN providertbl c ON a.id_provider=c.id_provider WHERE b.id_cabang='".$id_cabang."'";


$a=1;
//$query_sum= "SELECT SUM(jumlah_tagihan) AS total_tagihan FROM tagihantbl a INNER JOIN tokotbl b ON a.id_toko=b.id_toko INNER JOIN providertbl c ON bid_cabang='".$id_cabang."'";    
//print($query_sum);
$result2 = mysql_query ($query_sum);
$data_sum = mysql_fetch_array ($result2);

    echo "<thead>";
	echo "<tr>";
    echo "<th class=\"col-md-1\" >No.</th>";
    echo "<th>ID Cabang</th>";
	echo "<th>Nama Cabang</th>";
	echo "<th>Total Tagihan</th>";
    echo "</tr>";
    echo "</thead>";

//while($row = mysql_fetch_array($result2)) { 

echo "<tbody>"; 
echo "<tr>";
echo "<td>$a</td>";
echo "<td>$id_cabang</td>";
echo "<td>$nama_cabang</td>";
echo "<td>$data_sum[total_tagihan]</td>";
echo "</tr>";

$a++;
}
echo "</table>";

?>


<h4> Rekapitulasi Total Tagihan Per Provider </h4>
<table class="table table-bordered">
<?php

$querya="SELECT * FROM providertbl";  
$resulta= mysql_query ($querya);
$counta= mysql_num_rows ($resulta);

while ($dataa = mysql_fetch_array ($resulta) ) {
$id_provider = $dataa['id_provider'];
$nama_provider = $dataa['nama_provider'];

$query_sum1="SELECT SUM(jumlah_tagihan) AS total_tagihan FROM tagihantbl a INNER JOIN tokotbl b ON a.id_toko=b.id_toko INNER JOIN providertbl c ON a.id_provider=c.id_provider WHERE a.id_provider='".$id_provider."'";

$a=1;
//$query_sum1= "SELECT SUM(jumlah_tagihan) AS total_tagihan FROM tagihantbl a INNER JOIN tokotbl b ON a.id_toko=b.id_toko INNER JOIN providertbl c ON c.id_provider='".$id_provider."'";    
//print($query_sum1);
$resultb = mysql_query ($query_sum1);
$data_sumb = mysql_fetch_array ($resultb);

    echo "<thead>";
    echo "<tr>";
	echo "<th class=\"col-md-1\" >No.</th>";
	echo "<th>Nama Provider</th>";
	echo "<th>Total Tagihan</th>";
    echo "</tr>";
    echo "</thead>";
    echo "<tbody>";
 
echo "<tr >";
echo "<td>$a</td>";
echo "<td>$nama_provider</td>";
echo "<td>$data_sumb[total_tagihan]</td>";
echo "</tr>";
}
echo "</table>";

?>

</body>
</html>