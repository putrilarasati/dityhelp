<?php include("koneksi.php");
?>
<h2>Daftar Tagihan Koneksi Internet</h2>
<table class="table table-bordered">
<?php

$query="SELECT * FROM tagihantbl a INNER JOIN tokotbl b ON a.id_toko=b.id_toko INNER JOIN providertbl c ON a.id_provider=c.id_provider";      
  
//menjalankan query      
if (mysql_query($query)) {      
$result=mysql_query($query);     
} else die ("Error menjalankan query". mysql_error()); 
                   if (mysql_num_rows($result) > 0)     
{     
    echo "<thead>";
    echo "<tr>";
    echo "<th>ID Toko</th>";
    echo "<th>Nama Toko</th>";
    echo "<th>Provider</th>";
    echo "<th>Invoice Number</th>";
	echo "<th>Bandwith</th>";
	echo "<th>Jumlah Tagihan</th>";
	echo "<th>Periode Tagihan</th>";
    echo "</tr>";
    echo "</thead>";
    echo "<tbody>";
while($row = mysql_fetch_array($result)) {    
echo "<tr>";
echo "<td>".$row["id_toko"]."</td>";
echo "<td>".$row["nama_toko"]."</td>";
echo "<td>".$row["nama_provider"]."</td>";
echo "<td>".$row["invoice_number"]."</td>";
echo "<td>".$row["bandwith"]."</td>";
echo "<td>".$row["jumlah_tagihan"]."</td>";
echo "<td>".$row["periode_tagihan"]."</td>";
echo "</tr>";
}
echo "</table>";
}
else echo "Tidak ada data dalam tabel";
?>

</tbody>
</table>