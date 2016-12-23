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
echo pack("sssss", 0x203, 14, $Row, $Col, 0x0);
echo pack("d", $Value);
return;
}

function xlsWriteLabel($Row, $Col, $Value ) {
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
$data['alamat'][] = $res['alamat'];
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
xlsWriteLabel(1,3,"Data Toko" );
/*
untuk nama2 field dimulai dari baris array 2(baris 3 di excel) 
untuk kolomnya dimulai dari array 0(baris 1 di excel)
*/
xlsWriteLabel(3,1,"ID Toko" );
xlsWriteLabel(4,2,"Nama Toko");
xlsWriteLabel(5,3,"Alamat Toko");
xlsWriteLabel(6,4,"Status Toko" );
xlsWriteLabel(7,5,"Provider" );
xlsWriteLabel(8,6,"Tanggal Berlangganan" );
xlsWriteLabel(9,7,"Tanggal Berakhir" );
/*
untuk mulai baris data (row) dimulai pada array 3(baris 4 di excel) 
*/
$xlsRow = 3;
//untuk menampilkan data dari database di file excel
for ($y=0; $y<$jm; $y++) {
++$i;
xlsWriteNumber($xlsRow,1,"$i" );
xlsWriteLabel($xlsRow,4,$data['nama_toko'][$y]);
xlsWriteLabel($xlsRow,5,$data['alamat'][$y]);
xlsWriteLabel($xlsRow,6,$data['status_toko'][$y]);
xlsWriteLabel($xlsRow,7,$data['nama_provider'][$y]);
xlsWriteLabel($xlsRow,8,$data['berlangganan'][$y]);
xlsWriteLabel($xlsRow,9,$data['berakhir'][$y]);
$xlsRow++;
}
xlsEOF();
exit();
?>