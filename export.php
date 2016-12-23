<?php
function xlsBOF () {

echo pack ("ssssss", 0x809, 0x8, 0x0, 0x10, 0x0, 0x0);
return;
}

function xlsEOF () {
echo pack ("ss", 0x0A, 0x00);
return;
}

function xlsWriteNumber ($Row, $Col, $Value) {
$L = strlen($Value);
echo pack("ssssss", 0x204, 8 + $L, $Row, $Col, 0x0, $L);
echo $Value;
return;
}

include ("koneksi.php");
//query database untuk menampilkan data siswa
$queabsdetail = "SELECT * FROM tokotbl a INNER JOIN cabangtbl c ON a.id_cabang=c.id_cabang INNER JOIN mappingprovidertbl d ON a.id_toko=d.id_toko INNER JOIN providertbl b ON d.id_provider=b.id_provider";
$exequeabsdetail = mysql_query($queabsdetail);
while($res = mysql_fetch_array($exequeabsdetail)){
//mengambil data siswa dari database dimasukan ke array
$data['id_toko'][] = $res['id_toko'];
$data['nama_toko'][] = $res['nama_toko'];
$data['status_toko'][] = $res['status_toko'];
$data['nama_provider'][] = $res['nama_provider'];
$data['berlangganan'][] = $res['berlangganan'];
$data['berakhir'][] = $res['berakhir'];
}
//untuk primary key table data_siswa yaitu id_siswa

$jm = sizeof($data['id_toko']);
header("Pragma: public" );
header("Expires: 0" );
header("Cache-Control: must-revalidate, post-check=0, pre-check=0" );
header("Content-Type: application/force-download" );
header("Content-Type: application/octet-stream" );
header("Content-Type: application/download" );
header("Content-Disposition: attachment;filename=data_toko.xls" );
header("Content-Transfer-Encoding: binary" );
xlsBOF();
/* 
posisi excel berdasarkan baris dan kolom
diaplikasi posisinya berdasarkan nomor array dimulai dari 0
sedangkan di excel dimulai dari 1
ini untuk judul di excel. posisinya di baris array 0, kolom array 3
berarti posisi di excel 0 berarti baris 1, dan 3 berarti kolom 4
*/
xlsWriteLabel(0,3,"Data Toko" );
/*
untuk nama2 field dimulai dari baris array 2(baris 3 di excel) 
untuk kolomnya dimulai dari array 0(baris 1 di excel)
*/
echo "xlsWriteLabel(2,0,"ID Toko" )";
echo "xlsWriteLabel(2,1,"Nama Toko")";
echo "xlsWriteLabel(2,2,"Alamat Toko")";
echo "xlsWriteLabel(2,3,"Status Toko" )";
echo "xlsWriteLabel(2,4,"Provider" )";
echo "xlsWriteLabel(2,5,"Tanggal Berlangganan" )";
/*
untuk mulai baris data (row) dimulai pada array 3(baris 4 di excel) 
*/
$xlsRow = 3;
//untuk menampilkan data dari database di file excel
for ($y=0; $y<$jm; $y++) {
++$i;
echo "xlsWriteNumber($xlsRow,0,$i )";
echo "xlsWriteLabel($xlsRow,1,$data['nama_toko'][$y])";
echo "xlsWriteLabel($xlsRow,2,$data['alamat'][$y])";
echo "xlsWriteLabel($xlsRow,3,$data['status_toko'][$y])";
echo "xlsWriteLabel($xlsRow,4,$data['nama_provider'][$y])";
echo "xlsWriteLabel($xlsRow,5,$data['berlangganan'][$y])";
echo "xlsWriteLabel($xlsRow,5,$data['berakhir'][$y])";
$xlsRow++;
}
function xlsEOF();
exit();
?>