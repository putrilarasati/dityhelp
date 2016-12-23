<?php
session_start();
$act = $_REQUEST['act'];
$id = $_REQUEST['id'];
$fill = $_REQUEST['fill'];
$filter = $_REQUEST['filter'];
$kode = $_REQUEST['kode'];

include_once '_conn/query.php';
include_once "../class.eyemysqladap.inc.php";
include_once "../class.MultiGrid.inc.php";

function yymmdd_to_ddmmyy ($tanggal){
	list($thn,$bln,$tgl) = explode("-",$tanggal);
	$hasil = "";
	if ($thn !== "" && $bln !== "" && $tgl != ""){
		$hasil = "$tgl-$bln-$thn";
	}
	return $hasil;
}

function getPhone($GET_ID, $batas){
	function returnopsi_phone($lastname){								
			$PROFILEPHONE_ID = trim(substr($lastname,strpos($lastname, "1=>")+3,strpos($lastname, "2=>")-3));
			$PROFILEALAMAT_ID = trim(substr($lastname,strpos($lastname, "2=>")+3,strpos($lastname, "3=>")-strpos($lastname, "2=>")-3));
			$TIPE = trim(substr($lastname,strpos($lastname, "3=>")+3,strpos($lastname, "4=>")-strpos($lastname, "3=>")-3));
			$NAMA = trim(substr($lastname,strpos($lastname, "4=>")+3,strpos($lastname, "5=>")-strpos($lastname, "4=>")-3));
			$KODEAREA = trim(substr($lastname,strpos($lastname, "5=>")+3,strpos($lastname, "6=>")-strpos($lastname, "5=>")-3));
			$NOMER = trim(substr($lastname,strpos($lastname, "6=>")+3,strpos($lastname, "7=>")-strpos($lastname, "6=>")-3));
			$EXTENSION = trim(substr($lastname,strpos($lastname, "7=>")+3,strpos($lastname, "8=>")-strpos($lastname, "7=>")-3));
			$AKTIF = trim(substr($lastname,strpos($lastname, "8=>")+3,strpos($lastname, "9=>")-strpos($lastname, "8=>")-3));
			$KETERANGAN = trim(substr($lastname,strpos($lastname, "9=>")+3));
																	
			if($_REQUEST['status'] == 'NEW' || $_REQUEST['status'] == 'EDIT' || $_REQUEST['status'] == 'VALID2'){
				return '<a class="edit_telepon" data-toggle="modal" href="#modalDialog" 	profilephone_id="' .$PROFILEPHONE_ID 
						.'" profilealamat_id="' .$PROFILEALAMAT_ID .'" tipe="' .$TIPE .'" nama="' .$NAMA .'" kodearea="' .$KODEAREA  .'" extension="' .$EXTENSION
						.'" keterangan="' .$KETERANGAN .'" nomer="' .$NOMER .'" aktif="' .$AKTIF .'">'
						.'<i class="glyphicon glyphicon-edit"></i></a>';
			}			
	}
	
	$db_phone = new EyeMySQLAdap(':/cloudsql/database-sm:database-sm', 'root', '', 'PROFILE');
	$select = "I.PROFILEPHONE_ID, I.PROFILEALAMAT_ID, I.TIPE, I.NAMA, I.KODEAREA, I.NOMER, I.EXTENTION, I.AKTIF, 'Opsi' AS Opsi";
	$from = "PROFILE.PROFILE_PHONE I INNER JOIN PROFILE.PROFILE_ALAMAT PA ON I.PROFILEALAMAT_ID = PA.PROFILEALAMAT_ID";
	$filter = 'I.PROFILEALAMAT_ID = "' .$GET_ID. '"';
	
	$grid_phone = new MultiGridcls($db_phone,'_phone_' .$GET_ID);				
	$grid_phone->setQuery($select, $from, "I.NAMA",$filter);
	$grid_phone->setResultsPerPage($batas);														
	$grid_phone->hideColumn('PROFILEPHONE_ID');
	$grid_phone->hideColumn('PROFILEALAMAT_ID');
	$grid_phone->setColumnHeader('TIPE', 'Tipe');													
	$grid_phone->setColumnHeader('NAMA', 'Nama');
	$grid_phone->setColumnHeader('NOMER', 'Nomer');
	$grid_phone->setColumnHeader('EXTENTION', 'Extention');
	$grid_phone->setColumnHeader('AKTIF', 'Aktif');
	$grid_phone->setColumnType('AKTIF', MultiGridcls::TYPE_ARRAY, Array("Y" => "Ya", "T" => "Tidak"));
	$grid_phone->setColumnType('Opsi', MultiGridcls::TYPE_FUNCTION, 'returnopsi_phone', '1=>%PROFILEPHONE_ID% 2=>%PROFILEALAMAT_ID% 3=>%TIPE% 4=>%NAMA% 5=>%KODEAREA% 6=>%NOMER% 7=>%EXTENTION% 8=>%AKTIF% 9=>%KETERANGAN%');													
	$exec = new crud_master('PROFILE', "PROFILE.PROFILE_PHONE");
	if ($exec->CheckValue('PROFILEALAMAT_ID','PROFILEALAMAT_ID = "' .$GET_ID .'"')  != 0){
	return $grid_phone->printTable('table_phone_' .$GET_ID,$linkserch);	
	}
	
}

function getProfil($GET_ID_PELANGGAN){		

	$table_pelanggan= new query('PROFILE','PROFILE.PROFILE PP, MARKETING.PELANGGAN MP'); //('NAMA DATABASE','NAMA TABEL')
	$pelanggan = $table_pelanggan->selectBy(
		'PP.PROFILE_ID, MP.PELANGGAN_ID, MP.CONTACTPERSON, MP.STATUSAGEN, MP.ALAMATEMAIL, MP.NPWP, MP.GPS, 
		PP.NAMA, PP.KODE, MP.KETERANGAN, MP.NOCP, MP.DATE_EDITED',
		'PP.PROFILE_ID = "'.$GET_ID_PELANGGAN.'" AND PP.PROFILE_ID=MP.PELANGGAN_ID');
	if ($pelanggan->num_rows() == 0){
		echo "0";
		exit;
	}
	$pelanggan = $pelanggan->current();	
	$nocp = $pelanggan->NOCP;
	if ($nocp == 0 || $nocp == null){
		$nocp2 = "Nomor telepon cp untuk pelanggan ini belum diisi.";
	}
	else {
		$nocp2 = "";
		$nocp = str_replace("-", "", $nocp);
		$sum = strlen($nocp);
		for ($i = 0; $i<$sum;){
			
			$nocp2 .= substr($nocp, $i, 3);
			if ($sum - $i > 3){
			$nocp2 .= "-";
			}
			$i +=3;
			
		}
	}
	
	$email = $pelanggan->ALAMATEMAIL;
	if ($email != 0 || $email != null){
		$mail = explode(",", $email);
		$mail2 ="";
		foreach ($mail as $item)
		{
			$mail2.= "$item<br>";
		}
		//$mail2 .= "</ul>";
	}
	$edited = $pelanggan->DATE_EDITED;
	if($edited != null && $edited != '0000-00-00'){
	$now = date('Y-m-d');
	$edited2 = (abs(strtotime($now) - strtotime($edited))/(60*60*24));
		if($edited2 > 1){
			$date_edited = "last edited : ".$edited2." days ago";
		}
		else if($edited2 == 0){
			$date_edited = "recently edited";
		}
		else{
			$date_edited = "edited one day ago";
		}
	}
	else{
		$date_edited= "";
	}
	
	$table_tipe= new query('MARKETING','MARKETING.AGEN_TIPE AT, MARKETING.AGEN_TIPE2 AT2');
	$querytipe = $table_tipe->selectBy('AT.AGENTIPE_ID,AT.TIPE', 'AT2.PROFILE_ID = "'.$GET_ID_PELANGGAN.'"
		AND AT.AGENTIPE_ID  = AT2.AGENTIPE_ID 
		AND (AT2.TGL_SELESAI IS NULL OR AT2.TGL_SELESAI LIKE "%0000%")');
	$tipecurrent = $querytipe->current();
	$tipeid = $tipecurrent->AGENTIPE_ID;
	
	$table_area= new query('MARKETING','MARKETING.MKT_AREA A, MARKETING.AGEN_AREA AA');
	$queryarea = $table_area->selectBy('A.AREA, A.MKTAREA_ID', 'AA.KONTAK_ID = "'.$GET_ID_PELANGGAN.'" 
			AND (AA.TGL_SELESAI IS NULL OR AA.TGL_SELESAI LIKE "%0000%")
			AND A.MKTAREA_ID = AA.MKTAREA_ID');
	$areacurrent = $queryarea->current();
	$areaid = $areacurrent->MKTAREA_ID;

	$table_propinsi= new query('MARKETING','MARKETING.MKT_PROPINSI P, MARKETING.MKT_PROAREA MP');
	$querypropinsi = $table_propinsi->selectBy('P.PROPINSI, P.MKTPROPINSI_ID'
		, 'MP.MKTAREA_ID = "'.$areaid.'" AND (MP.TGL_SELESAI IS NULL OR MP.TGL_SELESAI LIKE "%0000%")
		AND P.MKTPROPINSI_ID = MP.MKTPROPINSI_ID');
	//echo $querypropinsi->printquery();
	$propinsicurrent = $querypropinsi->current();
	$propinsiid = $propinsicurrent->MKTPROPINSI_ID;

	$table_wilayah = new query('MARKETING','MARKETING.MKT_WILAYAH W, MARKETING.MKT_WILPRO MW');
	$querywilayah = $table_wilayah->selectBy('W.WILAYAH, W.MKTWILAYAH_ID', 'MW.MKTPROPINSI_ID = "'.$propinsiid.'"
		AND (MW.TGL_SELESAI IS NULL OR MW.TGL_SELESAI LIKE "%0000%")
		AND W.MKTWILAYAH_ID = MW.MKTWILAYAH_ID');
	$wilayahcurrent = $querywilayah->current();
	$wilayahid = $wilayahcurrent->MKTWILAYAH_ID;

	$table_regional = new query('MARKETING','MARKETING.MKT_REGIONAL R, MARKETING.MKT_REGWIL MR');
	$queryregional = $table_regional->selectBy('R.MKTREGIONAL_ID, R.REGIONAL', 'MR.MKTWILAYAH_ID = "'.$wilayahid.'"
		AND (MR.TGL_SELESAI IS NULL OR MR.TGL_SELESAI LIKE "%0000%")
		AND R.MKTREGIONAL_ID = MR.MKTREGIONAL_ID');
	$regionalcurrent = $queryregional->current();
	$regionalid = $regionalcurrent->MKTREGIONAL_ID;

	$arr = array("nama" => $pelanggan->NAMA, "contactperson" => $pelanggan->CONTACTPERSON, "nocontactperson" => $nocp2, "contact_id" => $nocp,
						"status" => $pelanggan->STATUSAGEN, "email" => $mail2, "email_id" => $email,"npwp" => $pelanggan->NPWP, "edited" => $date_edited,
						"kode" => $pelanggan->KODE, "gps" => $pelanggan->GPS, "tipe" => $tipecurrent->TIPE,
						"regional" => $regionalcurrent->REGIONAL, "wilayah" => $wilayahcurrent->WILAYAH, "propinsi" => $propinsicurrent->PROPINSI, "area" => $areacurrent->AREA,
						"regional_id" =>$regionalid, "wilayah_id" => $wilayahid, "propinsi_id" => $propinsiid, "area_id" => $areaid, "tipe_id" => $tipeid
					);
	
	return json_encode($arr);
}

function getKode($GET_ID, $GET_KODE){
	$arr = 0;
	//$table_pelanggan= new query('PROFILE','PROFILE.PROFILE');
	//$pelanggan = $table_pelanggan->selectBy('PROFILE_ID','PROFILE_ID = "'.$GET_ID.'" AND KODE = "'.$GET_KODE.'"');
	$exec = new crud_master('PROFILE', 'PROFILE.PROFILE');
	
	if ($exec->CheckValue('PROFILE_ID','PROFILE_ID = "' .$GET_ID .'" AND KODE = "'.$GET_KODE.'"')  != 1){
	//if ($pelanggan->num_rows() != 1){
		//$kode = $table_pelanggan->selectBy('KODE', 'KODE = "'.$GET_KODE);
		if($exec->CheckValue('PROFILE_ID', 'KODE = "'.$GET_KODE.'"') != 0){
		//if ($kode->num_rows() != 0){
			$arr = "Kode Pelanggan sudah dipakai, silahkan ganti yang baru!";
		}
	}
	else {
		$arr = 0;
	}
	return $arr;
}

function getPIC($GET_ID_PELANGGAN, $batas){		
	$dbtask = new EyeMySQLAdap(':/cloudsql/database-sm:database-sm', 'root', '', 'PROFILE');
	$select = "I.PROFILEPIC_ID, I.NAMA AS PEGAWAI_ID, K.NIK AS NIK, K.NAMA AS NAMA, I.JABATAN, I.KETERANGAN, I.AKTIF, 'Opsi' AS Opsi";
	$from = "PROFILE.PROFILE_PIC I, HRD.KARYAWAN K";
	$filter = "I.NAMA = K.KARYAWAN_ID AND I.PROFILE_ID = '" .$GET_ID_PELANGGAN. "'";
	$grid_pic = new MultiGridcls($dbtask,'_pic');				
	$grid_pic->setQuery($select, $from, "K.NAMA",$filter);
													
	$grid_pic->setResultsPerPage($batas);														
	$grid_pic->hideColumn('PROFILEPIC_ID');
	$grid_pic->hideColumn('PEGAWAI_ID');
	$grid_pic->setColumnHeader('JABATAN', 'Jabatan');													
	$grid_pic->setColumnHeader('KETERANGAN', 'Keterangan');
	$grid_pic->setColumnHeader('NAMA', 'Nama PIC');
	$grid_pic->setColumnHeader('AKTIF', 'Aktif');
	$grid_pic->setColumnType('AKTIF', MultiGridcls::TYPE_ARRAY, Array("Y" => "Ya", "T" => "Tidak"));
	$grid_pic->setColumnType('Opsi', MultiGridcls::TYPE_FUNCTION, 'returnopsi_pic', '1=>%PROFILEPIC_ID% 2=>%NIK% 3=>%JABATAN% 4=>%KETERANGAN% 5=>%NAMA% 6=>%PEGAWAI_ID% 7=>%AKTIF%');
													
	function returnopsi_pic($lastname){								
		$PROFILEPIC_ID = trim(substr($lastname,strpos($lastname, "1=>")+3,strpos($lastname, "2=>")-3));
		$NIK = trim(substr($lastname,strpos($lastname, "2=>")+3,strpos($lastname, "3=>")-strpos($lastname, "2=>")-3));
		$JABATAN = trim(substr($lastname,strpos($lastname, "3=>")+3,strpos($lastname, "4=>")-strpos($lastname, "3=>")-3));
		$KETERANGAN = trim(substr($lastname,strpos($lastname, "4=>")+3,strpos($lastname, "5=>")-strpos($lastname, "4=>")-3));
		$NAMA = trim(substr($lastname,strpos($lastname, "5=>")+3,strpos($lastname, "6=>")-strpos($lastname, "5=>")-3));
		$PEGAWAI_ID = trim(substr($lastname,strpos($lastname, "6=>")+3,strpos($lastname, "7=>")-strpos($lastname, "6=>")-3));
		$AKTIF = trim(substr($lastname,strpos($lastname, "7=>")+3));
														
		if($_REQUEST['status'] == 'NEW' || $_REQUEST['status'] == 'EDIT'){
			return '<a class="edit_pic" data-toggle="modal" href="#modalDialog" nama="' .$NAMA .'" nik="' .$NIK .'" profilepic_id="' 
				.$PROFILEPIC_ID .'" jabatan="' .$JABATAN .'" pegawai_id="' .$PEGAWAI_ID .'" keterangan="' .$KETERANGAN .'" aktif="' .$AKTIF .'">'
				.'<i class="glyphicon glyphicon-edit"></i></a>';
		}
	}				
	return $grid_pic->printTable('table_pic',$linkserch);
} 

function getDokumen($GET_ID_PELANGGAN, $batas){		
	$dbtask = new EyeMySQLAdap(':/cloudsql/database-sm:database-sm', 'root', '', 'PROFILE');
	$select = "D.DOKUMEN_ID, D.DOKUMEN, D.LINKDRIVE, D.AKTIF, 'Opsi' AS Opsi";
	$from = "MARKETING.DOKUMEN D INNER JOIN MARKETING.PELANGGAN P ON D.PELANGGAN_ID = P.PELANGGAN_ID";
	$filter = 'P.PELANGGAN_ID = "'  .$GET_ID_PELANGGAN. '"';
	$grid_dokumen = new MultiGridcls($dbtask,'_dokumen');				
	$grid_dokumen->setQuery($select, $from, "D.DOKUMEN_ID",$filter);
													
	$grid_dokumen->setResultsPerPage($batas);														
	$grid_dokumen->hideColumn('DOKUMEN_ID');
	$grid_dokumen->setColumnHeader('LINKDRIVE', 'Link');													
	$grid_dokumen->setColumnHeader('DOKUMEN', 'Nama Dokumen');													
	$grid_dokumen->setColumnHeader('AKTIF', 'Aktif');
	$grid_dokumen->setColumnType('AKTIF', MultiGridcls::TYPE_ARRAY, Array("Y" => "Ya", "T" => "Tidak"));
	$grid_dokumen->setColumnType('Opsi', MultiGridcls::TYPE_FUNCTION, 'returnopsi_dokumen', '1=>%DOKUMEN_ID% 2=>%DOKUMEN% 3=>%LINKDRIVE% 4=>%AKTIF%');
												
	function returnopsi_dokumen($lastname){								
		$DOKUMEN_ID = trim(substr($lastname,strpos($lastname, "1=>")+3,strpos($lastname, "2=>")-3));
		$DOKUMEN = trim(substr($lastname,strpos($lastname, "2=>")+3,strpos($lastname, "3=>")-strpos($lastname, "2=>")-3));
		$LINK = trim(substr($lastname,strpos($lastname, "3=>")+3,strpos($lastname, "4=>")-strpos($lastname, "3=>")-3));
		$AKTIF= trim(substr($lastname,strpos($lastname, "4=>")+3));
														
		if($_REQUEST['status'] == 'NEW' || $_REQUEST['status'] == 'EDIT' || $_REQUEST['status'] == 'VALID2'){
			return '<a class="edit_dokumen" data-toggle="modal" href="#modalDialog" dokumen="' .$DOKUMEN .'" link="' .$LINK .'" dokumen_id="' 
				.$DOKUMEN_ID .'" aktif="' .$AKTIF .'">'
				.'<i class="glyphicon glyphicon-edit"></i></a>';
		}
	}
	//$exec = new crud_master('MARKETING', "MARKETING.DOKUMEN");
	//if ($exec->CheckValue('PELANGGAN_ID','PELANGGAN_ID = "' .$GET_ID_PELANGGAN.'"')  != 0){
	return $grid_dokumen->printTable('table_dokumen',$linkserch);
	//}
} 
																		
function getInfoLainLain($GET_ID_PELANGGAN, $batas){									
		$dbtask = new EyeMySQLAdap(':/cloudsql/database-sm:database-sm', 'root', '', 'PROFILE');
		
		function returnopsi_infolain ($lastname){								
			$PROFILEINFO_ID = trim(substr($lastname,strpos($lastname, "1=>")+3,strpos($lastname, "2=>")-3));
			$FIELD_ID = trim(substr($lastname,strpos($lastname, "2=>")+3,strpos($lastname, "3=>")-strpos($lastname, "2=>")-3));
			$FIELD = trim(substr($lastname,strpos($lastname, "3=>")+3,strpos($lastname, "4=>")-strpos($lastname, "3=>")-3));
			$NILAI = trim(substr($lastname,strpos($lastname, "4=>")+3,strpos($lastname, "5=>")-strpos($lastname, "4=>")-3));
			$AKTIF = trim(substr($lastname,strpos($lastname, "5=>")+3));
																												
			if($_REQUEST['status'] == 'NEW' || $_REQUEST['status'] == 'EDIT' || $_REQUEST['status'] == 'VALID2'){
				return '<a class="edit_infolain" data-toggle="modal" href="#modalDialog" profileinfo_id="' .$PROFILEINFO_ID .'" field="' .$FIELD_ID .'" nama_field="' 
							.$FIELD .'" nilai="' .$NILAI .'" aktif="' .$AKTIF .'"><i class="glyphicon glyphicon-edit"></i></a>';
			}
		}				
		$select = "I. PROFILEINFO_ID, I.FIELD_ID, F.FIELD, I.NILAI, I.AKTIF, 'Opsi' AS Opsi";
		$from = "PROFILE.PROFILE_INFO I, PROFILE.PROFILE P, PROFILE.FIELD F";
		$filter = "I.PROFILE_ID = P.PROFILE_ID AND I.FIELD_ID =F.FIELD_ID AND I.PROFILE_ID = '" .$GET_ID_PELANGGAN ."'";
		$grid_infolain = new MultiGridcls($dbtask,'_infolain');				
		$grid_infolain->setQuery($select, $from, "F.FIELD",$filter);
													
		$grid_infolain->setResultsPerPage($batas);		
		$grid_infolain->hideColumn('PROFILE_ID');													
		$grid_infolain->hideColumn('PROFILEINFO_ID');
		$grid_infolain->hideColumn('FIELD_ID');
		$grid_infolain->setColumnHeader('FIELD', 'Jenis Informasi');													
		$grid_infolain->setColumnHeader('NILAI', 'Detail');
		$grid_infolain->setColumnHeader('AKTIF', 'Aktif');
		$grid_infolain->setColumnType('AKTIF', MultiGridcls::TYPE_ARRAY, Array("Y" => "Ya", "T" => "Tidak"));		
		$grid_infolain->setColumnType('Opsi', MultiGridcls::TYPE_FUNCTION, 'returnopsi_infolain', '1=>%PROFILEINFO_ID% 2=>%FIELD_ID% 3=>%FIELD% 4=>%NILAI% 5=>%AKTIF%');
		
		//$exec = new crud_master('PROFILE', "PROFILE.PROFILE_INFO");
		//if ($exec->CheckValue('PROFILE_ID','PROFILE_ID = "' .$GET_ID_PELANGGAN.'"')  != 0){
		return $grid_infolain->printTable('table_infolain',$linkserch);
		//}

}									

function getTOP($GET_ID_PELANGGAN, $batas){			
	$dbtask = new EyeMySQLAdap(':/cloudsql/database-sm:database-sm', 'root', '', 'MARKETING');
	$select = "T.TOP_ID, T.TOP, K.KODETRANSAKSI_ID, K.TRANSAKSI, DATE_FORMAT(T.TGL_MULAI, '%Y-%m-%d') AS TGL_MULAI, DATE_FORMAT(T.TGL_SELESAI, '%Y-%m-%d') AS TGL_SELESAI, DATEDIFF( T.TGL_SELESAI, T.TGL_MULAI ) AS Sisa,
	'Detail' AS Detail, 'Opsi' AS Opsi";
	$from = "MARKETING.TOP T INNER JOIN MARKETING.KODETRANSAKSI K ON T.KODETRANSAKSI_ID = K.KODETRANSAKSI_ID";
	//filter = '(T.TGL_SELESAI IS NULL OR T.TGL_SELESAI LIKE "%0000%") AND T.PELANGGAN_ID = "' .$GET_ID_PELANGGAN. '"';
	$filter = 'T.PELANGGAN_ID = "' .$GET_ID_PELANGGAN. '"';
	$grid_top = new MultiGridcls($dbtask,'_top');				
	$grid_top->setQuery($select, $from, "T.TOP_ID",$filter);
													
	$grid_top->setResultsPerPage($batas);														
	$grid_top->hideColumn('TOP_ID');																										
	$grid_top->hideColumn('KODETRANSAKSI_ID');																										
	$grid_top->setColumnHeader('TRANSAKSI', 'Jenis Transaksi');
	$grid_top->hideColumn('TGL_MULAI');
	$grid_top->hideColumn('TGL_SELESAI');
	$grid_top->setColumnHeader('Sisa', 'Masa Berlaku');
	$grid_top->setColumnType('TOP', MultiGridcls::TYPE_FUNCTION, 'returnformat_top', 'Top=>%TOP%');
	$grid_top->setColumnType('TGL_MULAI', MultiGridcls::TYPE_DATE, 'd M Y', '%TGL_MULAI%');
	$grid_top->setColumnType('TGL_SELESAI', MultiGridcls::TYPE_DATE, 'd M Y', '%TGL_SELESAI%');
	$grid_top->setColumnType('Sisa', MultiGridcls::TYPE_FUNCTION,'sisa', 'Sisa=>%Sisa%');
	$grid_top->setColumnType('Detail', MultiGridcls::TYPE_FUNCTION, 'returnformat_top', 'Detail=>%TOP_ID%');
	$grid_top->setColumnType('Opsi', MultiGridcls::TYPE_FUNCTION, 'returnopsi_top', '1=>%TOP_ID% 2=>%TOP% 3=>%KODETRANSAKSI_ID% 4=>%TGL_MULAI% 5=>%TGL_SELESAI%');

	function sisa($format){
		list($field, $val) = explode("=>", $format);
		$hasilformat = ($val == null ? "unlimited" : $val ." hari lagi");
		return $hasilformat;
	}
	
	function returnformat_top($format){
		list($field, $val) = explode("=>", $format);
		if ($field == "Top"){
			$hasilformat = ($val == 0 ? "CASH" : $val ." hari");
		}
		else if ($field == "Detail"){
			$table_detailTOP = new query('MARKETING', 'MARKETING.DETAIL D LEFT JOIN GBJ.MERK M
										ON D.MERK_ID = M.MERK_ID LEFT JOIN MASTERGUDANG.MASTER_BARANG P ON P.MASTERBARANG_ID = 
										D.PRODUK_ID');
			$querydetailTOP = $table_detailTOP->selectBy('P.MASTERBARANG_ID, M.MERK_ID, P.NAMA_BARANG, M.MERK, D.KECUALI', 'D.MASTER_ID = "'.$val .'"');			
			$numTOP = $querydetailTOP->num_rows();		
			if($numTOP>0){ 
				$hasilformat = (($querydetailTOP->current()->KECUALI == 'Y') ? "Berlaku Untuk: " : "Tidak Berlaku Untuk:") ."<br>";
				foreach($querydetailTOP as $currentdetail){
					if ($currentdetail->MASTERBARANG_ID !== NULL){
						$isi = strpos($currentdetail->NAMA_BARANG, 'ISI');
						$nama = $isi > 0  ? substr($currentdetail->NAMA_BARANG, 0, $isi) : $currentdetail->NAMA_BARANG;
						$item_id = $currentdetail->MASTERBARANG_ID;
					}
					else if ($currentdetail->MERK_ID !== NULL){
						$nama = $currentdetail->MERK;
						$item_id = $currentdetail->MERK_ID;
					}
					$hasilformat .= '<li item_id = "' .$item_id .'" class = "nama">' .$nama ."</li>";
				}		
			}
			else { 
					$hasilformat = "Tidak ada detail keterangan"; 
			}			
		}
		return $hasilformat;
	}

	function returnopsi_top($lastname){			
		$TOP_ID = trim(substr($lastname,strpos($lastname, "1=>")+3,strpos($lastname, "2=>")-3));
		$TOP = trim(substr($lastname,strpos($lastname, "2=>")+3,strpos($lastname, "3=>")-strpos($lastname, "2=>")-3));
		$TRANSAKSI = trim(substr($lastname,strpos($lastname, "3=>")+3,strpos($lastname, "4=>")-strpos($lastname, "3=>")-3));
		$TGL_MULAI = trim(substr($lastname,strpos($lastname, "4=>")+3,strpos($lastname, "5=>")-strpos($lastname, "4=>")-3));
		$TGL_SELESAI= trim(substr($lastname,strpos($lastname, "5=>")+3));
		//$TGL_MULAI = ($TGL_MULAI == '' OR $TGL_MULAI != '0000-00-00') ? Date("d M Y", strtotime($TGL_MULAI)) : "";
		//$TGL_SELESAI = ($TGL_SELESAI == '' OR $TGL_SELESAI != '0000-00-00') ? Date("d M Y", strtotime($TGL_SELESAI)) : "";
		
		$TGL_MULAI = ($TGL_MULAI == '' OR $TGL_MULAI != '0000-00-00') ? yymmdd_to_ddmmyy($TGL_MULAI) : "";
		$TGL_SELESAI = ($TGL_SELESAI == '' OR $TGL_SELESAI != '0000-00-00') ? yymmdd_to_ddmmyy($TGL_SELESAI) : "";
		
		if($_REQUEST['status'] == 'NEW' || $_REQUEST['status'] == 'EDIT' || $_REQUEST['status'] == 'VALID2'){
			$opsi = '<div class="btn-group"><a href="#" class="menu-detail dropdown-toggle" data-toggle="dropdown" 
						title="Menu"><i class="glyphicon glyphicon-cog"></i></a>
						<ul class="dropdown-menu">';
			$opsi .= '<li><a class="edit_top" data-toggle="modal" href="#modalDialog" top_id="' .$TOP_ID .'" top="' .$TOP .'" transaksi="' 
				.$TRANSAKSI .'" tgl_mulai="' .$TGL_MULAI .'" tgl_selesai="' .$TGL_SELESAI .'">Edit TOP</a></li>';
		
			$opsi .= '<li><a class="tambah_detail" data-toggle="modal" href="#modalDialog" tipe="TOP" master_id="' .$TOP_ID .'">Edit Detail TOP</a></li>';
			$opsi .= '<li><a class="hapus_detail" tipe="TOP" master_id="' .$TOP_ID .'">Hapus Detail TOP</a></li>';
			$opsi .= '</ul></div>';				
		}
		return $opsi;
	}
	//$exec = new crud_master('MARKETING', "MARKETING.TOP");
	//if ($exec->CheckValue('PELANGGAN_ID','PELANGGAN_ID = "' .$GET_ID_PELANGGAN.'"')  != 0){
	return $grid_top->printTable('table_top',$linkserch);	
	//}
}

function getLimit($GET_ID_PELANGGAN, $batas){			
	$dbtask = new EyeMySQLAdap(':/cloudsql/database-sm:database-sm', 'root', '', 'MARKETING');
	$select = "L.LIMITKREDIT_ID, L.LIMITKREDIT, C.CURRENCY_ID, C.CURRENCY, K.KODETRANSAKSI_ID, K.TRANSAKSI, L.DASARPEMBERIAN,
					DATE_FORMAT(L.TGL_MULAI, '%Y-%m-%d') AS TGL_MULAI, DATE_FORMAT(L.TGL_SELESAI, '%Y-%m-%d') AS TGL_SELESAI, DATEDIFF( L.TGL_SELESAI, L.TGL_MULAI ) AS Sisa,
					'Detail' AS Detail, 'Opsi' AS Opsi";
	$from = "MARKETING.LIMITKREDIT L INNER JOIN MARKETING.KODETRANSAKSI K ON L.KODETRANSAKSI_ID = K.KODETRANSAKSI_ID
				INNER JOIN MARKETING.CURRENCY C ON C.CURRENCY_ID = L.CURRENCY_ID";
													//$filter = '(L.TGL_SELESAI IS NULL OR L.TGL_SELESAI LIKE "%0000%") AND L.PELANGGAN_ID = "' .$GET_ID_PELANGGAN. '"';
	$filter = 'L.PELANGGAN_ID = "' .$GET_ID_PELANGGAN. '"';
	$grid_limit = new MultiGridcls($dbtask,'_limit');				
	$grid_limit->setQuery($select, $from, "L.LIMITKREDIT_ID",$filter);
													
	$grid_limit->setResultsPerPage($batas);		
	$grid_limit->hideColumn('LIMITKREDIT_ID');
	$grid_limit->hideColumn('CURRENCY_ID');
	$grid_limit->hideColumn('KODETRANSAKSI_ID');
	$grid_limit->hideColumn('DASARPEMBERIAN');
	$grid_limit->setColumnHeader('LIMITKREDIT', 'Limit');
	$grid_limit->setColumnHeader('TRANSAKSI', 'Jenis Transaksi');
	$grid_limit->setColumnHeader('CURRENCY', 'Mata Uang');
	$grid_limit->hideColumn('TGL_MULAI');
	$grid_limit->hideColumn('TGL_SELESAI');
	$grid_limit->setColumnHeader('Sisa', 'Masa Berlaku');
	//$grid_limit->setColumnHeader('Sisa_Limit', 'Sisa Limit');
	$grid_limit->setColumnType('LIMITKREDIT', MultiGridcls::TYPE_FUNCTION, 'returnformat_limit', 'Limit=>%LIMITKREDIT%');
	$grid_limit->setColumnType('TGL_MULAI', MultiGridcls::TYPE_DATE, 'd M Y', '%TGL_MULAI%');
	$grid_limit->setColumnType('TGL_SELESAI', MultiGridcls::TYPE_DATE, 'd M Y', '%TGL_SELESAI%');
	$grid_limit->setColumnType('Sisa', MultiGridcls::TYPE_FUNCTION,'sisa', 'Sisa=>%Sisa%');
	//$grid_limit->setColumnType('Sisa_Limit', MultiGridcls::TYPE_FUNCTION,'sisa_limit', '1=>%LIMITKREDIT_ID% 2=>%DASARPEMBERIAN%');
	$grid_limit->setColumnType('Detail', MultiGridcls::TYPE_FUNCTION, 'returnformat_limit', 'Detail=>%LIMITKREDIT_ID%');
	$grid_limit->setColumnType('Opsi', MultiGridcls::TYPE_FUNCTION, 'returnopsi_limit', '1=>%LIMITKREDIT_ID% 2=>%LIMITKREDIT% 3=>%CURRENCY_ID% 4=>%KODETRANSAKSI_ID% 5=>%DASARPEMBERIAN% 6=>%TGL_MULAI% 7=>%TGL_SELESAI%');

	function sisa($format){
		list($field, $val) = explode("=>", $format);
		$hasilformat = ($val == null ? "unlimited" : $val ." hari lagi");
		return $hasilformat;
	}
	
	
	function returnformat_limit($format){
		list($field, $val) = explode("=>", $format);
		if ($field == "Limit"){
			$hasilformat = ($val == "CASH" || $val == "UNLIMITED" ? $val : number_format($val, 2, ',', '.'));
		}
		else if ($field == "Detail"){
			$table_detaillimit = new query('MARKETING', 'MARKETING.DETAIL D LEFT JOIN GBJ.MERK M
										ON D.MERK_ID = M.MERK_ID LEFT JOIN MASTERGUDANG.MASTER_BARANG P ON P.MASTERBARANG_ID = 
										D.PRODUK_ID');
			$querydetaillimit = $table_detaillimit->selectBy('P.MASTERBARANG_ID, M.MERK_ID, P.NAMA_BARANG, M.MERK, D.KECUALI', 'D.MASTER_ID = "'.$val .'"');
			$numLimit = $querydetaillimit->num_rows();		
			if($numLimit>0){ 
				$hasilformat = (($querydetaillimit->current()->KECUALI == 'Y') ? "Berlaku Untuk: " : "Tidak Berlaku Untuk:") ."<br>";
				foreach($querydetaillimit as $currentdetail){
					if ($currentdetail->MASTERBARANG_ID !== NULL){
						$isi = strpos($currentdetail->NAMA_BARANG, 'ISI');
						$nama = $isi > 0  ? substr($currentdetail->NAMA_BARANG, 0, $isi) : $currentdetail->NAMA_BARANG;
					}
					else if ($currentdetail->MERK_ID !== NULL){
						$nama = $currentdetail->MERK;
						$item_id = $currentdetail->MERK_ID;
					}
					$hasilformat .= '<li item_id = "' .$item_id .'" class = "nama">' .$nama ."</li>";
				}																
			}
			else { 
				$hasilformat = "Tidak ada detail keterangan"; 
			}															
		}
		return $hasilformat;
	}

	
	function returnopsi_limit($lastname){								
		$LIMIT_ID = trim(substr($lastname,strpos($lastname, "1=>")+3,strpos($lastname, "2=>")-3));
		$LIMIT = trim(substr($lastname,strpos($lastname, "2=>")+3,strpos($lastname, "3=>")-strpos($lastname, "2=>")-3));
		$CURRENCY_ID = trim(substr($lastname,strpos($lastname, "3=>")+3,strpos($lastname, "4=>")-strpos($lastname, "3=>")-3));
		$TRANSAKSI = trim(substr($lastname,strpos($lastname, "4=>")+3,strpos($lastname, "5=>")-strpos($lastname, "4=>")-3));
		$DASARPEMBERIAN = trim(substr($lastname,strpos($lastname, "5=>")+3,strpos($lastname, "6=>")-strpos($lastname, "5=>")-3));
		$TGL_MULAI = trim(substr($lastname,strpos($lastname, "6=>")+3,strpos($lastname, "7=>")-strpos($lastname, "6=>")-3));
		$TGL_SELESAI= trim(substr($lastname,strpos($lastname, "7=>")+3));

		$TGL_MULAI = ($TGL_MULAI == '' OR $TGL_MULAI != '0000-00-00') ? yymmdd_to_ddmmyy($TGL_MULAI) : "";
		$TGL_SELESAI = ($TGL_SELESAI == '' OR $TGL_SELESAI != '0000-00-00') ? yymmdd_to_ddmmyy($TGL_SELESAI) : "";

		if($_REQUEST['status'] == 'NEW' || $_REQUEST['status'] == 'EDIT' || $_REQUEST['status'] == 'VALID2'){
			$opsi = '<div class="btn-group"><a href="#" class="menu-detail dropdown-toggle" data-toggle="dropdown" 
						title="Menu"><i class="glyphicon glyphicon-cog"></i></a>
						<ul class="dropdown-menu">';
			$opsi .= '<li><a class="edit_limit" data-toggle="modal" href="#modalDialog" limit_id="' .$LIMIT_ID .'" currency="' .$CURRENCY_ID .'" transaksi="' 
						.$TRANSAKSI .'" limit="' .$LIMIT .'" dasarpemberian="' .$DASARPEMBERIAN .'" tgl_mulai="' .$TGL_MULAI .'" tgl_selesai="' .$TGL_SELESAI .'">'
						.'Edit Limit</a>';														
			$opsi .= '<li><a class="tambah_detail" data-toggle="modal" href="#modalDialog" tipe="LIMITKREDIT" master_id="' .$LIMIT_ID .'">Edit Detail Limit</a></li>';
			$opsi .= '<li><a class="hapus_detail" tipe="LIMITKREDIT" master_id="' .$LIMIT_ID .'">Hapus Detail Limit</a></li>';
			$opsi .= '</ul></div>';				
		}
		return $opsi;
	}
	
	//$exec = new crud_master('MARKETING', "MARKETING.LIMITKREDIT");
	//if ($exec->CheckValue('PELANGGAN_ID','PELANGGAN_ID = "' .$GET_ID_PELANGGAN.'"')  != 0){
	return $grid_limit->printTable('table_limit',$linkserch);	
	//}
	
	
}

function getAlamat($GET_ID){
	$table_alamat = new query('PROFILE', 'PROFILE.PROFILE P, PROFILE.PROFILE_ALAMAT A, PROFILE.KOTA K, PROFILE.PROPINSI PR, PROFILE.NEGARA N,  MARKETING.PELANGGAN_ALAMAT I');
	$filter = 'A.PROFILEALAMAT_ID = "' .$GET_ID .'"';
	$queryalamat = $table_alamat->selectBy(
		'A.PROFILEALAMAT_ID, A.ALAMAT, A.JALAN, A.KODEPOS, A.KETERANGAN, A.AKTIF, K.KOTA_ID, K.KOTA, PR.PROPINSI, N.NEGARA, I.ALAMATKIRIM, I.HRPENGIRIMAN', 
		'P.PROFILE_ID = A.PROFILE_ID AND ' .$filter .' AND K.KOTA_ID = A.KOTA_ID AND PR.PROPINSI_ID = K.PROPINSI_ID
		AND N.NEGARA_ID = PR.NEGARA_ID AND I.PROFILE_ID = P.PROFILE_ID AND I.PELANGGANALAMAT_ID = A.PROFILEALAMAT_ID AND (A.TGL_SELESAI IS NULL 
		OR A.TGL_SELESAI LIKE "%0000%") ORDER BY I.ALAMATKIRIM DESC');	
	$alamatcurrent = $queryalamat->current();
	$arr = Array("alamat_id" => $alamatcurrent->PROFILEALAMAT_ID, "alamat" => $alamatcurrent->ALAMAT, "jalan" => $alamatcurrent->JALAN, 
					"kota_id" => $alamatcurrent->KOTA_ID, "kodepos" => $alamatcurrent->KODEPOS, "keterangan" => $alamatcurrent->KETERANGAN, "kota" => $alamatcurrent->KOTA,
					"propinsi" => $alamatcurrent->PROPINSI, "negara" => $alamatcurrent->NEGARA, "alamatkirim" => $alamatcurrent->ALAMATKIRIM,
					"hrpengiriman" => $alamatcurrent->HRPENGIRIMAN, "kota_id" => $alamatcurrent->KOTA_ID, "aktif" => $alamatcurrent->AKTIF);
	return json_encode($arr);
}

function getAlamatAll($GET_ID){
	$table_alamat = new query('PROFILE', 'PROFILE.PROFILE P, PROFILE.PROFILE_ALAMAT A, PROFILE.KOTA K, PROFILE.PROPINSI PR, PROFILE.NEGARA N,  MARKETING.PELANGGAN_ALAMAT I');
	$filter = 'A.PROFILE_ID = "' .$GET_ID .'"';
	$queryalamat = $table_alamat->selectBy(
		'A.PROFILEALAMAT_ID, A.ALAMAT, A.JALAN, A.KODEPOS, A.KETERANGAN, A.AKTIF, K.KOTA_ID, K.KOTA, PR.PROPINSI, N.NEGARA, I.ALAMATKIRIM, I.HRPENGIRIMAN', 
		'P.PROFILE_ID = A.PROFILE_ID AND ' .$filter .' AND K.KOTA_ID = A.KOTA_ID AND PR.PROPINSI_ID = K.PROPINSI_ID
		AND N.NEGARA_ID = PR.NEGARA_ID AND I.PROFILE_ID = P.PROFILE_ID AND I.PELANGGANALAMAT_ID = A.PROFILEALAMAT_ID AND (A.TGL_SELESAI IS NULL 
		OR A.TGL_SELESAI LIKE "%0000%") ORDER BY I.ALAMATKIRIM DESC');
		
	foreach($queryalamat as $alamatcurrent){ 
		$arr[] = Array("alamat_id" => $alamatcurrent->PROFILEALAMAT_ID, "alamat" => $alamatcurrent->ALAMAT, "jalan" => $alamatcurrent->JALAN, 
					"kota_id" => $alamatcurrent->KOTA_ID, "kodepos" => $alamatcurrent->KODEPOS, "keterangan" => $alamatcurrent->KETERANGAN, "kota" => $alamatcurrent->KOTA,
					"propinsi" => $alamatcurrent->PROPINSI, "negara" => $alamatcurrent->NEGARA, "alamatkirim" => $alamatcurrent->ALAMATKIRIM,
					"hrpengiriman" => $alamatcurrent->HRPENGIRIMAN, "kota_id" => $alamatcurrent->KOTA_ID,  "aktif" => $alamatcurrent->AKTIF);
	}
	return json_encode($arr);
}


function getApproval($id){	
	$table = new query('SIMIS','SIMIS.REQUESTCODE R'); 
	$query_requestcode = $table->selectBy("REQUESTCODE_ID,USERNAME, SUBJECT, NOTICKET, SETUJU","NOTICKET = '".$id."' ORDER BY URUTAN"); 
	$print = '<table width="100%" border="0">
		  <tr>
			<td><strong>No. Tiket </strong></td>
			<td><strong>Validator</strong></td>
			<td><strong>Status</strong></td>
			</tr>';
	$setuju1 = 'Y';
	foreach($query_requestcode as $currentrequestcode){
		$setuju = $currentrequestcode->SETUJU;
		$NOTICKET = $currentrequestcode->NOTICKET;
		$SUBJECT = $currentrequestcode->SUBJECT;
		$USERNAME = $currentrequestcode->USERNAME;
		$REQUESTCODE_ID = $currentrequestcode->REQUESTCODE_ID;
		if($setuju == 'Y'){	$setuju = 'Disetujui';
		} else if($setuju == 'T'){ $setuju = 'Ditolak';
		//} else if($alamatemail == $USERNAME and $setuju1 == 'Y'){ $setuju = '<a href="_ADM/_crud/function.php" >Setuju</a> <a href="_ADM/_crud/function.php">Tidak</a>';
		} else{
			$setuju = 'Menunggu';
			$setuju1 = '';
		}
		$print .= '<tr>
			<td>'.$NOTICKET.'</td>
			<td>'.$USERNAME.'</td>
			<td>'.$setuju.'</td>
			</tr>';		
	}
	$print .= '</table>';
	$data['app'] = $print;
	//echo json_encode($data, true);
	echo $print;
}

function getReobar($batas){								
										if (!isset($dbtask)) {
											$dbtask = new EyeMySQLAdap(':/cloudsql/database-sm:database-sm', 'root', '', 'PROFILE');
										}	
										function returnopsi($lastname){
												$status = trim(substr($lastname,strpos($lastname, "1=>")+3,strpos($lastname, "2=>")-3));
												$PROFILE_ID = trim(substr($lastname,strpos($lastname, "2=>")+3,strpos($lastname, "3=>")-strpos($lastname, "2=>")-3));
												$NOTICKET = trim(substr($lastname,strpos($lastname, "3=>")+3));
												$opsi = "<div class=\"btn-group\"><a href=\"#\" class=\"dropdown-toggle\" data-toggle=\"dropdown\" title=\"Menu\"><i class=\"glyphicon glyphicon-cog\"></i></a><ul class=\"dropdown-menu\" style=\"top: 0px; right: 20px; left: auto;\">";
												if($status == 'VALID'){
													$opsi .= "<li><a class=\"edit_profil\" profile_id=\"" .$PROFILE_ID ."\"><i class=\"fa fa-edit\"></i> Edit</a></li>";
												}
												if($status == 'NEW' or $status == 'EDIT'){
														$opsi .= "<li><a class=\"validasi_profil\" profile_id=\"".$PROFILE_ID."\"><i class=\"fa fa-check\"></i> Validasi</a></li>";
												} 
												if($status == 'APPROVAL' || $status == "VALID"){
														$opsi .= "<li><a href=\"#show_app\" class=\"lihat_approval\"  data-toggle=\"modal\" ticket_id=\"".$NOTICKET."\"><i class=\"fa fa-envelope-o\"></i> Lihat Approval</i></a></li>";
												}
												//if($Status == 'VALID'){						
													//	$opsi .= "<li><a href=\"#\" class=\"blacklist\" data-toggle=\"modal\" id=\"".$PROFILE_ID."\"><i class=\"fa fa-check\"></i> Blacklist</a></li>";
												//}
												$opsi .="<li><a href=\"pesanan.php\"><span class=\"glyphicon glyphicon-shopping-cart\"></span> Buat Order</a>";
												$opsi .= "</ul></div>";
												$stat = new query("MARKETING", "MARKETING.PELANGGAN");
												if($status == "VALID2"){
													$ARRAY_FIELD = array("STATUSAGEN" => "VALID");
													$stat_ubh = $stat->updateBy('PELANGGAN_ID',$PROFILE_ID, $ARRAY_FIELD);
												}
												return $opsi;
										}
										if(!isset($_REQUEST['order1']) || $_REQUEST['order1'] === ""){$order = ' ORDER BY P.STATUSAGEN';}
										$filter = ' AKTIF = "Y" AND SYSTEM_ID = "2"';
										$grid_reobar = new MultiGridcls($dbtask,'_reobar');				
										$grid_reobar->setQuery("DISTINCT P.STATUSAGEN AS Status, PROF.PROFILE_ID,PROF.NAMA, NOTICKET,  'Opsi' AS Opsi"
										, "PROFILE PROF
											INNER JOIN SYSTEM_PROFILE SY ON SY.PROFILE_ID = PROF.PROFILE_ID
											INNER JOIN MARKETING.PELANGGAN P ON P.PELANGGAN_ID = PROF.PROFILE_ID
											INNER JOIN MARKETING.AGEN_AREA AA ON AA.KONTAK_ID = P.PELANGGAN_ID 
											INNER JOIN MARKETING.MKT_AREA MA ON MA.MKTAREA_ID = AA.MKTAREA_ID
											INNER JOIN MARKETING.MKT_PROAREA MPA ON MPA.MKTAREA_ID = MA.MKTAREA_ID
											INNER JOIN MARKETING.MKT_WILPRO MWA ON MPA.MKTPROPINSI_ID = MWA.MKTPROPINSI_ID
											INNER JOIN MARKETING.MKT_REGWIL MR ON MR.MKTWILAYAH_ID = MWA.MKTWILAYAH_ID											
											", "PROFILE_ID",$filter);
										$grid_reobar->setResultsPerPage($batas);
										//$grid_reobar->setGridHeight("500");
										$grid_reobar->setColumnType('Status', MultiGridcls::TYPE_ARRAY, array('VALID' => ' <span class="label label-success">Valid</span>', 'DROP' => '<span class="label label-warning">Drop</span>', 'EDIT' => '<span class="label label-info">Edit</span>', 'NEW' => '<span class="label label-default">New</span>', 'APPROVAL'=> '<span class="label label-danger">Approval</span>')); 
										$grid_reobar->setColumnType('NAMA', MultiGridcls::TYPE_HREF, "m_detailpelanggan.php?srch-term=&nama=%NAMA%&id=%PROFILE_ID%");
										$grid_reobar->hideColumn('PROFILE_ID');
										$grid_reobar->hideColumn('NOTICKET');
										$grid_reobar->setColumnType('Opsi', MultiGridcls::TYPE_FUNCTION, 'returnopsi', '1=>%Status% 2=>%PROFILE_ID% 3=>%NOTICKET%');
										return $grid_reobar->printTable('table_reobar',isset($_REQUEST['srch-term']) ? "&srch-term=" .$_REQUEST['srch-term'] : "");
}
										
function getReoteng($batas){								
										if (!isset($dbtask)) {
											$dbtask = new EyeMySQLAdap(':/cloudsql/database-sm:database-sm', 'root', '', 'PROFILE');
										}	
										function returnopsi($lastname){
												$status = trim(substr($lastname,strpos($lastname, "1=>")+3,strpos($lastname, "2=>")-3));
												$PROFILE_ID = trim(substr($lastname,strpos($lastname, "2=>")+3,strpos($lastname, "3=>")-strpos($lastname, "2=>")-3));
												$NOTICKET = trim(substr($lastname,strpos($lastname, "3=>")+3));
												$opsi = "<div class=\"btn-group\"><a href=\"#\" class=\"dropdown-toggle\" data-toggle=\"dropdown\" title=\"Menu\"><i class=\"glyphicon glyphicon-cog\"></i></a><ul class=\"dropdown-menu\" style=\"top: 0px; right: 20px; left: auto;\">";
												if($status == 'VALID'){
													$opsi .= "<li><a class=\"edit_profil\" profile_id=\"" .$PROFILE_ID ."\"><i class=\"fa fa-edit\"></i> Edit</a></li>";
												}
												if($status == 'NEW' or $status == 'EDIT'){
														$opsi .= "<li><a class=\"validasi_profil\" profile_id=\"".$PROFILE_ID."\"><i class=\"fa fa-check\"></i> Validasi</a></li>";
												} 
												if($status == 'APPROVAL' || $status == "VALID"){
														$opsi .= "<li><a href=\"#show_app\" class=\"lihat_approval\"  data-toggle=\"modal\" ticket_id=\"".$NOTICKET."\"><i class=\"fa fa-envelope-o\"></i> Lihat Approval</i></a></li>";
												}
												//if($Status == 'VALID'){						
													//	$opsi .= "<li><a href=\"#\" class=\"blacklist\" data-toggle=\"modal\" id=\"".$PROFILE_ID."\"><i class=\"fa fa-check\"></i> Blacklist</a></li>";
												//}
												$opsi .= "</ul></div>";
												return $opsi;
										}
	
										function label($lastname){
		
												$nama = trim(substr($lastname,strpos($lastname, "1=>")+3,strpos($lastname, "2=>")-3));
												$PROFILE_ID = trim(substr($lastname,strpos($lastname, "2=>")+3,strpos($lastname, "3=>")-strpos($lastname, "2=>")-3));
												$blokir = trim(substr($lastname,strpos($lastname, "3=>")+3));
												$link = "<a href=\"m_detailpelanggan.php?srch-term=&nama=$nama&id=$PROFILE_ID\">$nama</a>";
												if ($blokir != null){
												$link .= " <a id=\"blokir\" href=\"$blokir\" title=\"$blokir \n $blokir\" class=\"label label-danger\">blokir</a>";
												}

												return $link;
											}
	
	
										if(!isset($_REQUEST['order1']) || $_REQUEST['order1'] === ""){$order = ' ORDER BY P.STATUSAGEN';}
										$filter = ' AKTIF = "Y" AND SYSTEM_ID = "2" AND MR.MKTREGIONAL_ID = "01608BG000007"';
										$grid_reoteng = new MultiGridcls($dbtask,'_reoteng');				
										$grid_reoteng->setQuery("DISTINCT P.STATUSAGEN AS Status, PROF.PROFILE_ID,PROF.NAMA, NOTICKET, 'Opsi' AS Opsi"
										, "PROFILE PROF
											INNER JOIN SYSTEM_PROFILE SY ON SY.PROFILE_ID = PROF.PROFILE_ID
											INNER JOIN MARKETING.PELANGGAN P ON P.PELANGGAN_ID = PROF.PROFILE_ID
											INNER JOIN MARKETING.AGEN_AREA AA ON AA.KONTAK_ID = P.PELANGGAN_ID 
											INNER JOIN MARKETING.MKT_AREA MA ON MA.MKTAREA_ID = AA.MKTAREA_ID
											INNER JOIN MARKETING.MKT_PROAREA MPA ON MPA.MKTAREA_ID = MA.MKTAREA_ID
											INNER JOIN MARKETING.MKT_WILPRO MWA ON MPA.MKTPROPINSI_ID = MWA.MKTPROPINSI_ID
											INNER JOIN MARKETING.MKT_REGWIL MR ON MR.MKTWILAYAH_ID = MWA.MKTWILAYAH_ID
											LEFT JOIN MARKETING.PELANGGAN_BLOKIR B ON PROF.PROFILE_ID = B.PELANGGAN_ID
											", "PROFILE_ID",$filter);
										$grid_reoteng->setResultsPerPage($batas);
										//$grid_reoteng->setGridHeight("500");
										$grid_reoteng->setColumnType('Status', MultiGridcls::TYPE_ARRAY, array('VALID' => ' <span class="label label-success">Valid</span>', 'DROP' => '<span class="label label-warning">Drop</span>', 'EDIT' => '<span class="label label-info">Edit</span>', 'NEW' => '<span class="label label-default">New</span>', 'APPROVAL'=> '<span class="label label-danger">Approval</span>')); 
										$grid_reoteng->setColumnType('NAMA', MultiGridcls::TYPE_FUNCTION, 'label', '1=>%NAMA% 2=>%PROFILE_ID% 3=>%Blokir%');
										$grid_reoteng->hideColumn('PROFILE_ID');
										$grid_reoteng->hideColumn('NOTICKET');
										$grid_reoteng->setColumnType('Opsi', MultiGridcls::TYPE_FUNCTION, 'returnopsi', '1=>%Status% 2=>%PROFILE_ID% 3=>%NOTICKET%');
										return $grid_reoteng->printTable('table_reoteng',isset($_REQUEST['srch-term']) ? "&srch-term=" .$_REQUEST['srch-term'] : "");
}

function getProfileSearch($batas){								
										if (!isset($dbtask)) {
											$dbtask = new EyeMySQLAdap(':/cloudsql/database-sm:database-sm', 'root', '', 'PROFILE');
										}	
										function returnopsi($lastname){
												$status = trim(substr($lastname,strpos($lastname, "1=>")+3,strpos($lastname, "2=>")-3));
												$PROFILE_ID = trim(substr($lastname,strpos($lastname, "2=>")+3,strpos($lastname, "3=>")-strpos($lastname, "2=>")-3));
												$NOTICKET = trim(substr($lastname,strpos($lastname, "3=>")+3));
												$opsi = "<div class=\"btn-group\"><a href=\"#\" class=\"dropdown-toggle\" data-toggle=\"dropdown\" title=\"Menu\"><i class=\"glyphicon glyphicon-cog\"></i></a><ul class=\"dropdown-menu\" style=\"top: 0px; right: 20px; left: auto;\">";
												if($status == 'VALID'){
													$opsi .= "<li><a class=\"edit_profil\" profile_id=\"" .$PROFILE_ID ."\"><i class=\"fa fa-edit\"></i> Edit</a></li>";
												}
												if($status == 'NEW' or $status == 'EDIT'){
														$opsi .= "<li><a class=\"validasi_profil\" profile_id=\"".$PROFILE_ID."\"><i class=\"fa fa-check\"></i> Validasi</a></li>";
												} 
												if($status == 'APPROVAL' || $status == "VALID"){
														$opsi .= "<li><a href=\"#show_app\" class=\"lihat_approval\"  data-toggle=\"modal\" ticket_id=\"".$NOTICKET."\"><i class=\"fa fa-envelope-o\"></i> Lihat Approval</i></a></li>";
												}
												//if($Status == 'VALID'){						
													//	$opsi .= "<li><a href=\"#\" class=\"blacklist\" data-toggle=\"modal\" id=\"".$PROFILE_ID."\"><i class=\"fa fa-check\"></i> Blacklist</a></li>";
												//}
												$opsi .= "</ul></div>";
												return $opsi;
										}
											function label($lastname){
		
												$nama = trim(substr($lastname,strpos($lastname, "1=>")+3,strpos($lastname, "2=>")-3));
												$PROFILE_ID = trim(substr($lastname,strpos($lastname, "2=>")+3,strpos($lastname, "3=>")-strpos($lastname, "2=>")-3));
												$blokir = trim(substr($lastname,strpos($lastname, "3=>")+3));
												$link = "<a href=\"m_detailpelanggan.php?srch-term=&nama=$nama&id=$PROFILE_ID\">$nama</a>";
												if ($blokir != null){
												$link .= " <a id=\"blokir\" href=\"$blokir\" title=\"$blokir \n $blokir\" class=\"label label-danger\">blokir</a>";
												}

												return $link;
											}
	
	
										if(!isset($_REQUEST['order1']) || $_REQUEST['order1'] === ""){$order = ' ORDER BY P.STATUSAGEN';}
										$filter_regional = "";
										/*switch (substr($_SESSION['kode_dept'],0,3)) {
											case "CMW" :
												$filter_regional = ' AND MR.MKTREGIONAL_ID = "01608BG000006"';
												break;
											case "CME" :
												$filter_regional = ' AND MR.MKTREGIONAL_ID = "01608BD000001"';
												break;
											case "CMM" :
												$filter_regional = ' AND MR.MKTREGIONAL_ID = "01608BG000007"';
												break;
										}*/	

										$filter = ' AKTIF = "Y" AND SYSTEM_ID = "2" AND PROF.NAMA LIKE "%' .$_REQUEST['srch-term'] .'%"' . $_REQUEST['regional'];
										$grid_search = new MultiGridcls($dbtask,'_search');				
										$grid_search->setQuery("DISTINCT P.STATUSAGEN AS Status, PROF.PROFILE_ID,PROF.NAMA, B.BLOKIR AS Blokir, NOTICKET, 'Opsi' AS Opsi"
										, "PROFILE.PROFILE PROF
											INNER JOIN SYSTEM_PROFILE SY ON SY.PROFILE_ID = PROF.PROFILE_ID
											INNER JOIN MARKETING.PELANGGAN P ON P.PELANGGAN_ID = PROF.PROFILE_ID
											INNER JOIN MARKETING.AGEN_AREA AA ON AA.KONTAK_ID = P.PELANGGAN_ID 
											INNER JOIN MARKETING.MKT_AREA MA ON MA.MKTAREA_ID = AA.MKTAREA_ID
											INNER JOIN MARKETING.MKT_PROAREA MPA ON MPA.MKTAREA_ID = MA.MKTAREA_ID
											INNER JOIN MARKETING.MKT_WILPRO MWA ON MPA.MKTPROPINSI_ID = MWA.MKTPROPINSI_ID
											INNER JOIN MARKETING.MKT_REGWIL MR ON MR.MKTWILAYAH_ID = MWA.MKTWILAYAH_ID
											LEFT JOIN MARKETING.PELANGGAN_BLOKIR B ON PROF.PROFILE_ID = B.PELANGGAN_ID
											", "PROFILE_ID",$filter);
										$grid_search->setResultsPerPage($batas);
										$grid_search->setColumnType('Status', MultiGridcls::TYPE_ARRAY, array('VALID' => ' <span class="label label-success">Valid</span>', 'DROP' => '<span class="label label-warning">Drop</span>', 'EDIT' => '<span class="label label-info">Edit</span>', 'NEW' => '<span class="label label-default">New</span>', 'APPROVAL'=> '<span class="label label-danger">Approval</span>')); 
										$grid_search->setColumnType('NAMA', MultiGridcls::TYPE_FUNCTION, 'label', '1=>%NAMA% 2=>%PROFILE_ID% 3=>%Blokir%');
										$grid_search->hideColumn('PROFILE_ID');
										$grid_search->hideColumn('NOTICKET');
										$grid_search->hideColumn('Blokir');
										$grid_search->setColumnType('Opsi', MultiGridcls::TYPE_FUNCTION, 'returnopsi', '1=>%Status% 2=>%PROFILE_ID% 3=>%NOTICKET%');
										return $grid_search->printTable('table_reoteng', "");
}

function getAll($batas){
	if (!isset($dbtask)) {
		$dbtask = new EyeMySQLAdap(':/cloudsql/database-sm:database-sm', 'root', '', 'PROFILE');
	}
	function returnopsi($lastname){
		$status = trim(substr($lastname,strpos($lastname, "1=>")+3,strpos($lastname, "2=>")-3));
		$PROFILE_ID = trim(substr($lastname,strpos($lastname, "2=>")+3,strpos($lastname, "3=>")-strpos($lastname, "2=>")-3));
		$NOTICKET = trim(substr($lastname,strpos($lastname, "3=>")+3));
		$opsi = "<div class=\"btn-group\"><a href=\"#\" class=\"dropdown-toggle\" data-toggle=\"dropdown\" title=\"Menu\"><i class=\"glyphicon glyphicon-cog\"></i></a><ul class=\"dropdown-menu\" style=\"top: 0px; right: 20px; left: auto;\">";
				if($status == 'VALID'){
				$opsi .= "<li><a class=\"edit_profil\" profile_id=\"" .$PROFILE_ID ."\"><i class=\"fa fa-edit\"></i> Edit</a></li>";
				}
				if($status == 'NEW' or $status == 'EDIT'){
				$opsi .= "<li><a class=\"validasi_profil\" profile_id=\"".$PROFILE_ID."\"><i class=\"fa fa-check\"></i> Validasi</a></li>";
				} 
				if($status == 'APPROVAL' || $status == "VALID"){
				$opsi .= "<li><a href=\"#show_app\" class=\"lihat_approval\"  data-toggle=\"modal\" ticket_id=\"".$NOTICKET."\"><i class=\"fa fa-envelope-o\"></i> Lihat Approval</i></a></li>";
				}
				//if($Status == 'VALID'){						
				//	$opsi .= "<li><a href=\"#\" class=\"blacklist\" data-toggle=\"modal\" id=\"".$PROFILE_ID."\"><i class=\"fa fa-check\"></i> Blacklist</a></li>";
				//}
				$opsi .= "</ul></div>";
				$stat = new query("MARKETING", "MARKETING.PELANGGAN");
				if($status == "VALID2"){
				$ARRAY_FIELD = array("STATUSAGEN" => "VALID");
				$stat_ubh = $stat->updateBy('PELANGGAN_ID',$PROFILE_ID, $ARRAY_FIELD);
				}
				return $opsi;
	}
	
	
	function label($lastname){
		
		$nama = trim(substr($lastname,strpos($lastname, "1=>")+3,strpos($lastname, "2=>")-3));
		$PROFILE_ID = trim(substr($lastname,strpos($lastname, "2=>")+3,strpos($lastname, "3=>")-strpos($lastname, "2=>")-3));
		$blokir = trim(substr($lastname,strpos($lastname, "3=>")+3,strpos($lastname, "4=>")-strpos($lastname, "3=>")-3));
		$kode = trim(substr($lastname,strpos($lastname, "4=>")+3,strpos($lastname, "5=>")-strpos($lastname, "4=>")-3));
		//$order = trim(substr($lastname,strpos($lastname, "5=>")+3,strpos($lastname, "6=>")-strpos($lastname, "5=>")-3));
		$utang = trim(substr($lastname,strpos($lastname, "5=>")+3));
		$link = "<a href=\"m_detailpelanggan.php?srch-term=&nama=$nama&id=$PROFILE_ID\">$nama</a>";
		
		if ($blokir != null){
		$tbl_limit = new query("MARKETING", "MARKETING.LIMITKREDIT");
		$qry_limit = $tbl_limit->selectBy("LIMITKREDIT", 'PELANGGAN_ID ="'.$PROFILE_ID.'"');
		$jlh_limit = $qry_limit->current();
			if ($jlh_limit->LIMITKREDIT != null){
				$jlh = number_format((float)$jlh_limit->LIMITKREDIT, 2, ',', '.');
			}
			else {
				$jlh = "0";
			}
		$link .= " <a id=\"blokir\" href=\"#\" title=\"$blokir \ntotal nominal limit: $jlh\" class=\"text text-danger\"><i class=\"glyphicon glyphicon-minus-sign\"></i></a>";
		}
		if (!($utang == null or $utang == 0)){
			$jlh_utang = number_format($utang, 2, ',', '.');
		$link .= " <a id=\"utang\" href=\"#\" class=\"text text-muted\" title=\"total utang : Rp. $jlh_utang\"><i class=\"glyphicon glyphicon-usd\"></i></a>";
		}
		return $link;
			
	}
	
	function format($lastname){
		$omzet = trim(substr($lastname,strpos($lastname, "1=>")+3));
		if($omzet > 0 && $omzet != "" ){
		$link = number_format($omzet, 2, ',', '.'); 
		$link = 'Rp. '.$link;
		}
		else{
		$link = "";
		}
		return $link;
	}
	
	$filter_regional = "";
	switch (substr($_SESSION['kode_dept'],0,3)) {
		case "CMW" :
			$filter_regional = ' AND MR.MKTREGIONAL_ID = "01608BG000006"';
			break;
		case "CME" :
			$filter_regional = ' AND MR.MKTREGIONAL_ID = "01608BD000001"';
			break;
		case "CMM" :
			$filter_regional = ' AND MR.MKTREGIONAL_ID = "01608BG000007"';
			break;
	}	
	$filter = 'AKTIF = "Y" AND SYSTEM_ID = "2"'.$filter_regional;
	
	
	$grid_all = new MultiGridcls($dbtask,'_all');
	$grid_all->setQuery("DISTINCT P.STATUSAGEN AS Status, PROF.PROFILE_ID,PROF.NAMA, PROF.KODE, B.BLOKIR AS Blokir, NOTICKET, 'Opsi' AS Opsi"
										, "PROFILE.PROFILE PROF
											INNER JOIN SYSTEM_PROFILE SY ON SY.PROFILE_ID = PROF.PROFILE_ID
											INNER JOIN MARKETING.PELANGGAN P ON P.PELANGGAN_ID = PROF.PROFILE_ID
											INNER JOIN MARKETING.AGEN_AREA AA ON AA.KONTAK_ID = P.PELANGGAN_ID 
											INNER JOIN MARKETING.MKT_AREA MA ON MA.MKTAREA_ID = AA.MKTAREA_ID
											INNER JOIN MARKETING.MKT_PROAREA MPA ON MPA.MKTAREA_ID = MA.MKTAREA_ID
											INNER JOIN MARKETING.MKT_WILPRO MWA ON MPA.MKTPROPINSI_ID = MWA.MKTPROPINSI_ID
											INNER JOIN MARKETING.MKT_REGWIL MR ON MR.MKTWILAYAH_ID = MWA.MKTWILAYAH_ID
											LEFT JOIN MARKETING.PELANGGAN_BLOKIR B ON PROF.PROFILE_ID = B.PELANGGAN_ID
											
											", "PROFILE_ID",$filter);
	$grid_all->setResultsPerPage($batas);
	//$grid_all->setColumnType('Status', MultiGridcls::TYPE_FUNCTION, 'status', '1=>%Status%');
	$grid_all->setColumnType('Status', MultiGridcls::TYPE_ARRAY, array('VALID' => '<a class="label label-success" id="valid" title="PROFIL PELANGGAN SUDAH VALID DAN DISETUJUI">Valid</a>', 'DROP' => '<a id="drop" class="label label-warning">Drop</a>', 'EDIT' => '<a class="label label-info" id="edit" title="DATA PELANGGAN SEDANG DIEDIT">Edit</a>', 'NEW' => '<a class="label label-default" id="new" title="CALON PELANGGAN BARU">New</a>', 'APPROVAL'=> '<a class="label label-danger" id="approve" title="PELANGGAN SEDANG MENUNGGU UNTUK DISETUJUI">Approval</a>'));
	//$grid_all->setColumnType('NAMA', MultiGridcls::TYPE_HREF, "m_detailpelanggan.php?srch-term=&nama=%NAMA%&id=%PROFILE_ID%");
	$grid_all->setColumnType('NAMA', MultiGridcls::TYPE_FUNCTION, 'label', '1=>%NAMA% 2=>%PROFILE_ID% 3=>%Blokir% 4=>%KODE% 5=>%UTANG%');
	$grid_all->setColumnHeader('NAMA', 'Nama Pelanggan');	
	//$grid_all->setColumnHeader('OMZET', 'Jumlah Omzet');	
	$grid_all->hideColumn('PROFILE_ID');
	//$grid_all->hideColumn('OMZET');
	$grid_all->hideColumn('NOTICKET');
	$grid_all->hideColumn('WILAYAH_ID');
	$grid_all->hideColumn('WILAYAH');
	$grid_all->hideColumn('REGIONAL');
	$grid_all->hideColumn('AREA');
	$grid_all->hideColumn('AREA_ID');
	$grid_all->hideColumn('Blokir');
	$grid_all->hideColumn('KODE');
	//$grid_all->setColumnType('OMZET', MultiGridcls::TYPE_FUNCTION, 'format', '1=>%OMZET%');
	//$grid_all->hideColumn('NOTICKET');
	//$grid_all->hideColumn('UTANG');
	$grid_all->setColumnType('Opsi', MultiGridcls::TYPE_FUNCTION, 'returnopsi', '1=>%Status% 2=>%PROFILE_ID% 3=>%NOTICKET%');
	return $grid_all->printTable('table_all', "");

}
		
function getBlock($batas, $fill){
	if (!isset($dbtask)) {
		$dbtask = new EyeMySQLAdap(':/cloudsql/database-sm:database-sm', 'root', '', 'PROFILE');
	}
	function returnopsi($lastname){
		$status = trim(substr($lastname,strpos($lastname, "1=>")+3,strpos($lastname, "2=>")-3));
		$PROFILE_ID = trim(substr($lastname,strpos($lastname, "2=>")+3,strpos($lastname, "3=>")-strpos($lastname, "2=>")-3));
		$NOTICKET = trim(substr($lastname,strpos($lastname, "3=>")+3));
		$opsi = "<div class=\"btn-group\"><a href=\"#\" class=\"dropdown-toggle\" data-toggle=\"dropdown\" title=\"Menu\"><i class=\"glyphicon glyphicon-cog\"></i></a><ul class=\"dropdown-menu\" style=\"top: 0px; right: 20px; left: auto;\">";
				if($status == 'VALID'){
				$opsi .= "<li><a class=\"edit_profil\" profile_id=\"" .$PROFILE_ID ."\"><i class=\"fa fa-edit\"></i> Edit</a></li>";
				}
				if($status == 'NEW' or $status == 'EDIT'){
				$opsi .= "<li><a class=\"validasi_profil\" profile_id=\"".$PROFILE_ID."\"><i class=\"fa fa-check\"></i> Validasi</a></li>";
				} 
				if($status == 'APPROVAL' || $status == "VALID"){
				$opsi .= "<li><a href=\"#show_app\" class=\"lihat_approval\"  data-toggle=\"modal\" ticket_id=\"".$NOTICKET."\"><i class=\"fa fa-envelope-o\"></i> Lihat Approval</i></a></li>";
				}
				//if($Status == 'VALID'){						
				//	$opsi .= "<li><a href=\"#\" class=\"blacklist\" data-toggle=\"modal\" id=\"".$PROFILE_ID."\"><i class=\"fa fa-check\"></i> Blacklist</a></li>";
				//}
				$opsi .= "</ul></div>";
				return $opsi;
	}
	
	
	function label($lastname){
		
		$nama = trim(substr($lastname,strpos($lastname, "1=>")+3,strpos($lastname, "2=>")-3));
		$PROFILE_ID = trim(substr($lastname,strpos($lastname, "2=>")+3,strpos($lastname, "3=>")-strpos($lastname, "2=>")-3));
		$blokir = trim(substr($lastname,strpos($lastname, "3=>")+3,strpos($lastname, "4=>")-strpos($lastname, "3=>")-3));
		$kode = trim(substr($lastname,strpos($lastname, "4=>")+3,strpos($lastname, "5=>")-strpos($lastname, "4=>")-3));
		//$order = trim(substr($lastname,strpos($lastname, "5=>")+3,strpos($lastname, "6=>")-strpos($lastname, "5=>")-3));
		$utang = trim(substr($lastname,strpos($lastname, "5=>")+3));
		$link = "<a href=\"m_detailpelanggan.php?srch-term=&nama=$nama&id=$PROFILE_ID\">$nama</a>";
		
		if ($blokir != null){
		$tbl_limit = new query("MARKETING", "MARKETING.LIMITKREDIT");
		$qry_limit = $tbl_limit->selectBy("LIMITKREDIT", 'PELANGGAN_ID ="'.$PROFILE_ID.'"');
		$jlh_limit = $qry_limit->current();
			if ($jlh_limit->LIMITKREDIT != null){
				$jlh = number_format((float)$jlh_limit->LIMITKREDIT, 2, ',', '.');
			}
			else {
				$jlh = "0";
			}
		$link .= " <a id=\"blokir\" href=\"#\" title=\"$blokir \ntotal nominal limit: $jlh\" class=\"text text-danger\"><i class=\"glyphicon glyphicon-minus-sign\"></i></a>";
		}
		if (!($utang == null or $utang == 0)){
			$jlh_utang = number_format($utang, 2, ',', '.');
		$link .= " <a id=\"utang\" href=\"#\" class=\"text text-muted\" title=\"total utang : Rp. $jlh_utang\"><i class=\"glyphicon glyphicon-usd\"></i></a>";
		}
		return $link;
			
	}
	
	function format($lastname){
		$omzet = trim(substr($lastname,strpos($lastname, "1=>")+3));
		if($omzet > 0 && $omzet != "" ){
		$link = number_format($omzet, 2, ',', '.'); 
		$link = 'Rp. '.$link;
		}
		else{
		$link = "";
		}
		return $link;
	}
	
	
		
	//$fill = ' AND BLOKIR IS NOT NULL';
	$filter = 'AKTIF = "Y" AND SYSTEM_ID = "2"';
	$filter .= $fill;
	
	$grid_block = new MultiGridcls($dbtask,'_block');
	$grid_block->setQuery("DISTINCT P.STATUSAGEN AS Status, PROF.PROFILE_ID,PROF.NAMA, PROF.KODE, B.BLOKIR AS Blokir, NOTICKET, U.OMZET, U.UTANG, 'Opsi' AS Opsi"
										, "PROFILE.PROFILE PROF
											INNER JOIN SYSTEM_PROFILE SY ON SY.PROFILE_ID = PROF.PROFILE_ID
											INNER JOIN MARKETING.PELANGGAN P ON P.PELANGGAN_ID = PROF.PROFILE_ID
											INNER JOIN MARKETING.AGEN_AREA AA ON AA.KONTAK_ID = P.PELANGGAN_ID 
											INNER JOIN MARKETING.MKT_AREA MA ON MA.MKTAREA_ID = AA.MKTAREA_ID
											INNER JOIN MARKETING.MKT_PROAREA MPA ON MPA.MKTAREA_ID = MA.MKTAREA_ID
											INNER JOIN MARKETING.MKT_WILPRO MWA ON MPA.MKTPROPINSI_ID = MWA.MKTPROPINSI_ID
											INNER JOIN MARKETING.MKT_REGWIL MR ON MR.MKTWILAYAH_ID = MWA.MKTWILAYAH_ID
											LEFT JOIN MARKETING.PELANGGAN_BLOKIR B ON PROF.PROFILE_ID = B.PELANGGAN_ID
											LEFT JOIN (SELECT SUM(T.KURANG_BAYAR) AS UTANG, SUM(T.TOTAL + T.KURANG_BAYAR) AS OMZET, T.PELANGGAN_ID FROM  FINANCE.TAGIHAN T GROUP BY  T.PELANGGAN_ID) U ON PROF.PROFILE_ID = U.PELANGGAN_ID
											", "PROFILE_ID",$filter);
	$grid_block->setResultsPerPage($batas);
	//$grid_all->setColumnType('Status', MultiGridcls::TYPE_FUNCTION, 'status', '1=>%Status%');
	$grid_block->setColumnType('Status', MultiGridcls::TYPE_ARRAY, array('VALID' => '<a class="label label-success" id="valid" title="PROFIL PELANGGAN SUDAH VALID DAN DISETUJUI">Valid</a>', 'DROP' => '<a id="drop" class="label label-warning">Drop</a>', 'EDIT' => '<a class="label label-info" id="edit" title="DATA PELANGGAN SEDANG DIEDIT">Edit</a>', 'NEW' => '<a class="label label-default" id="new" title="CALON PELANGGAN BARU">New</a>', 'APPROVAL'=> '<a class="label label-danger" id="approve" title="PELANGGAN SEDANG MENUNGGU UNTUK DISETUJUI">Approval</a>'));
	//$grid_all->setColumnType('NAMA', MultiGridcls::TYPE_HREF, "m_detailpelanggan.php?srch-term=&nama=%NAMA%&id=%PROFILE_ID%");
	$grid_block->setColumnType('NAMA', MultiGridcls::TYPE_FUNCTION, 'label', '1=>%NAMA% 2=>%PROFILE_ID% 3=>%Blokir% 4=>%KODE% 5=>%UTANG%');
	$grid_block->setColumnHeader('OMZET', 'Jumlah Omzet');	
	$grid_block->setColumnHeader('TANGGAL_SJ', 'Order terakhir');	
	$grid_block->hideColumn('PROFILE_ID');
	$grid_block->hideColumn('NOTICKET');
	$grid_block->hideColumn('WILAYAH_ID');
	$grid_block->hideColumn('WILAYAH');
	$grid_block->hideColumn('REGIONAL');
	$grid_block->hideColumn('AREA');
	$grid_block->hideColumn('AREA_ID');
	$grid_block->hideColumn('Blokir');
	$grid_block->hideColumn('KODE');
	$grid_block->setColumnType('OMZET', MultiGridcls::TYPE_FUNCTION, 'format', '1=>%OMZET%');
	//$grid_all->hideColumn('NOTICKET');
	$grid_block->hideColumn('UTANG');
	$grid_block->setColumnType('Opsi', MultiGridcls::TYPE_FUNCTION, 'returnopsi', '1=>%Status% 2=>%PROFILE_ID% 3=>%NOTICKET%');
	return $grid_block->printTable('table_block', "");

}


function getCoba($batas){
	if (!isset($dbtask)) {
		$dbtask = new EyeMySQLAdap(':/cloudsql/database-sm:database-sm', 'root', '', 'PROFILE');
	}
	function returnopsi($lastname){
		$status = trim(substr($lastname,strpos($lastname, "1=>")+3,strpos($lastname, "2=>")-3));
		$PROFILE_ID = trim(substr($lastname,strpos($lastname, "2=>")+3,strpos($lastname, "3=>")-strpos($lastname, "2=>")-3));
		$NOTICKET = trim(substr($lastname,strpos($lastname, "3=>")+3));
		$opsi = "<div class=\"btn-group\"><a href=\"#\" class=\"dropdown-toggle\" data-toggle=\"dropdown\" title=\"Menu\"><i class=\"glyphicon glyphicon-cog\"></i></a><ul class=\"dropdown-menu\" style=\"top: 0px; right: 20px; left: auto;\">";
				if($status == 'VALID'){
				$opsi .= "<li><a class=\"edit_profil\" profile_id=\"" .$PROFILE_ID ."\"><i class=\"fa fa-edit\"></i> Edit</a></li>";
				}
				if($status == 'NEW' or $status == 'EDIT'){
				$opsi .= "<li><a class=\"validasi_profil\" profile_id=\"".$PROFILE_ID."\"><i class=\"fa fa-check\"></i> Validasi</a></li>";
				} 
				if($status == 'APPROVAL' || $status == "VALID"){
				$opsi .= "<li><a href=\"#show_app\" class=\"lihat_approval\"  data-toggle=\"modal\" ticket_id=\"".$NOTICKET."\"><i class=\"fa fa-envelope-o\"></i> Lihat Approval</i></a></li>";
				}
				//if($Status == 'VALID'){						
				//	$opsi .= "<li><a href=\"#\" class=\"blacklist\" data-toggle=\"modal\" id=\"".$PROFILE_ID."\"><i class=\"fa fa-check\"></i> Blacklist</a></li>";
				//}
				$opsi .= "</ul></div>";
				return $opsi;
	}
	
	
	function label($lastname){
		
		$nama = trim(substr($lastname,strpos($lastname, "1=>")+3,strpos($lastname, "2=>")-3));
		$PROFILE_ID = trim(substr($lastname,strpos($lastname, "2=>")+3,strpos($lastname, "3=>")-strpos($lastname, "2=>")-3));
		$blokir = trim(substr($lastname,strpos($lastname, "3=>")+3,strpos($lastname, "4=>")-strpos($lastname, "3=>")-3));
		$kode = trim(substr($lastname,strpos($lastname, "4=>")+3,strpos($lastname, "5=>")-strpos($lastname, "4=>")-3));
		$order = trim(substr($lastname,strpos($lastname, "5=>")+3,strpos($lastname, "6=>")-strpos($lastname, "5=>")-3));
		$utang = trim(substr($lastname,strpos($lastname, "6=>")+3));
		$link = "<a href=\"m_detailpelanggan.php?srch-term=&nama=$nama&id=$PROFILE_ID\">$nama</a>";
		
		if ($blokir != null){
		$tbl_limit = new query("MARKETING", "MARKETING.LIMITKREDIT");
		$qry_limit = $tbl_limit->selectBy("LIMITKREDIT", 'PELANGGAN_ID ="'.$PROFILE_ID.'"');
		$jlh_limit = $qry_limit->current();
			if ($jlh_limit->LIMITKREDIT != null){
				$jlh = number_format((float)$jlh_limit->LIMITKREDIT, 2, ',', '.');
			}
			else {
				$jlh = "0";
			}
		$link .= " <a id=\"blokir\" href=\"#\" title=\"$blokir \ntotal nominal limit: $jlh\" class=\"label label-default\">blokir</a>";
		}
		
		//$tbl_jual = new query("FINANCE", "FINANCE.JUALHARIAN");
		//$qry_jual = $tbl_jual->selectBy("MAX(TANGGAL_SJ) AS TGL", 'KDAGEN = "' .$kode.'"');
		//$tgl_jual = $qry_jual->current();
		if ($order != null){
		$link .= " <a id=\"jual\" href=\"#\" class=\"label label-primary\">$order</a>";
		}
		//$tbl_utang = new query("FINANCE", "FINANCE.TAGIHAN");
		//$qry_utang = $tbl_utang->selectBy("SUM(TAGIHAN.KURANG_BAYAR) AS utang", 'TAGIHAN.PELANGGAN_ID ="'.$PROFILE_ID.'"');
		//$utang = $qry_utang->current();
		if (!($utang == null or $utang == 0)){
			$jlh_utang = number_format($utang, 2, ',', '.');
		$link .= " <a id=\"utang\" href=\"#\" class=\"label label-danger\" title=\"total utang : Rp. $jlh_utang\">utang belum lunas!</a>";
		}
		return $link;
			
	}
	

	
	
		
	//$fill = ' AND BLOKIR IS NOT NULL';
	$filter = 'AKTIF = "Y" AND SYSTEM_ID = "2" AND UTANG > 0';
	
	
	$grid_coba = new MultiGridcls($dbtask,'_all');
	$grid_coba->setQuery("DISTINCT P.STATUSAGEN AS Status, PROF.PROFILE_ID,PROF.NAMA, PROF.KODE, B.BLOKIR AS Blokir, NOTICKET, JH.TANGGAL_SJ, U.UTANG, 'Opsi' AS Opsi"
										, "PROFILE.PROFILE PROF
											INNER JOIN SYSTEM_PROFILE SY ON SY.PROFILE_ID = PROF.PROFILE_ID
											INNER JOIN MARKETING.PELANGGAN P ON P.PELANGGAN_ID = PROF.PROFILE_ID
											INNER JOIN MARKETING.AGEN_AREA AA ON AA.KONTAK_ID = P.PELANGGAN_ID 
											INNER JOIN MARKETING.MKT_AREA MA ON MA.MKTAREA_ID = AA.MKTAREA_ID
											INNER JOIN MARKETING.MKT_PROAREA MPA ON MPA.MKTAREA_ID = MA.MKTAREA_ID
											INNER JOIN MARKETING.MKT_WILPRO MWA ON MPA.MKTPROPINSI_ID = MWA.MKTPROPINSI_ID
											INNER JOIN MARKETING.MKT_REGWIL MR ON MR.MKTWILAYAH_ID = MWA.MKTWILAYAH_ID
											LEFT JOIN MARKETING.PELANGGAN_BLOKIR B ON PROF.PROFILE_ID = B.PELANGGAN_ID
											LEFT JOIN (SELECT KDAGEN, MAX(TANGGAL_SJ) AS TANGGAL_SJ FROM FINANCE.JUALHARIAN GROUP BY KDAGEN) JH ON PROF.KODE = JH.KDAGEN
											LEFT JOIN (SELECT SUM(T.KURANG_BAYAR) AS UTANG, T.PELANGGAN_ID FROM  FINANCE.TAGIHAN T GROUP BY  T.PELANGGAN_ID) U ON PROF.PROFILE_ID = U.PELANGGAN_ID
											", "PROFILE_ID",$filter);
	$grid_coba->setResultsPerPage($batas);
	//$grid_all->setColumnType('Status', MultiGridcls::TYPE_FUNCTION, 'status', '1=>%Status%');
	$grid_coba->setColumnType('Status', MultiGridcls::TYPE_ARRAY, array('VALID' => '<a id="valid" href="#" class="label label-success">Valid</a>', 'DROP' => '<a id="drop" href="#" class="label label-warning">Drop</a>', 'EDIT' => '<a id="edit" href="#" class="label label-info">Edit</a>', 'NEW' => '<a id="new" href="#" class="label label-default">New</a>', 'APPROVAL'=> '<a href="#" class="label label-danger"">Approval</a>'));
	//$grid_all->setColumnType('NAMA', MultiGridcls::TYPE_HREF, "m_detailpelanggan.php?srch-term=&nama=%NAMA%&id=%PROFILE_ID%");
	$grid_coba->setColumnType('NAMA', MultiGridcls::TYPE_FUNCTION, 'label', '1=>%NAMA% 2=>%PROFILE_ID% 3=>%Blokir% 4=>%KODE% 5=>%TANGGAL_SJ% 6=>%UTANG%');
	$grid_coba->hideColumn('PROFILE_ID');
	$grid_coba->hideColumn('NOTICKET');
	$grid_coba->hideColumn('WILAYAH_ID');
	$grid_coba->hideColumn('WILAYAH');
	$grid_coba->hideColumn('REGIONAL');
	$grid_coba->hideColumn('AREA');
	$grid_coba->hideColumn('AREA_ID');
	$grid_coba->hideColumn('Blokir');
	$grid_coba->hideColumn('KODE');
	$grid_coba->hideColumn('TANGGAL_SJ');
	//$grid_all->hideColumn('NOTICKET');
	$grid_coba->hideColumn('UTANG');
	$grid_coba->setColumnType('Opsi', MultiGridcls::TYPE_FUNCTION, 'returnopsi', '1=>%Status% 2=>%PROFILE_ID% 3=>%NOTICKET%');
	return $grid_coba->printTable('table_reoteng', "");

}


function getNonApprove($batas, $fill){

	if (!isset($dbtask)) {
		$dbtask = new EyeMySQLAdap(':/cloudsql/database-sm:database-sm', 'root', '', 'PROFILE');
	}
	function returnopsi($lastname){
		$status = trim(substr($lastname,strpos($lastname, "1=>")+3,strpos($lastname, "2=>")-3));
		$PROFILE_ID = trim(substr($lastname,strpos($lastname, "2=>")+3,strpos($lastname, "3=>")-strpos($lastname, "2=>")-3));
		$NOTICKET = trim(substr($lastname,strpos($lastname, "3=>")+3));
		$opsi = "<div class=\"btn-group\"><a href=\"#\" class=\"dropdown-toggle\" data-toggle=\"dropdown\" title=\"Menu\"><i class=\"glyphicon glyphicon-cog\"></i></a><ul class=\"dropdown-menu\" style=\"top: 0px; right: 20px; left: auto;\">";
				if($status == 'VALID'){
				$opsi .= "<li><a class=\"edit_profil\" profile_id=\"" .$PROFILE_ID ."\"><i class=\"fa fa-edit\"></i> Edit</a></li>";
				}
				if($status == 'NEW' or $status == 'EDIT'){
				$opsi .= "<li><a class=\"validasi_profil\" profile_id=\"".$PROFILE_ID."\"><i class=\"fa fa-check\"></i> Validasi</a></li>";
				} 
				if($status == 'APPROVAL' || $status == "VALID"){
				$opsi .= "<li><a href=\"#show_app\" class=\"lihat_approval\"  data-toggle=\"modal\" ticket_id=\"".$NOTICKET."\"><i class=\"fa fa-envelope-o\"></i> Lihat Approval</i></a></li>";
				}
				//if($Status == 'VALID'){						
				//	$opsi .= "<li><a href=\"#\" class=\"blacklist\" data-toggle=\"modal\" id=\"".$PROFILE_ID."\"><i class=\"fa fa-check\"></i> Blacklist</a></li>";
				//}
				$opsi .= "</ul></div>";
				return $opsi;
	}
	
	
	function label($lastname){
		
		$nama = trim(substr($lastname,strpos($lastname, "1=>")+3,strpos($lastname, "2=>")-3));
		$PROFILE_ID = trim(substr($lastname,strpos($lastname, "2=>")+3,strpos($lastname, "3=>")-strpos($lastname, "2=>")-3));
		$blokir = trim(substr($lastname,strpos($lastname, "3=>")+3,strpos($lastname, "4=>")-strpos($lastname, "3=>")-3));
		$kode = trim(substr($lastname,strpos($lastname, "4=>")+3,strpos($lastname, "5=>")-strpos($lastname, "4=>")-3));
		//$order = trim(substr($lastname,strpos($lastname, "5=>")+3,strpos($lastname, "6=>")-strpos($lastname, "5=>")-3));
		$utang = trim(substr($lastname,strpos($lastname, "5=>")+3));
		$link = "<a href=\"m_detailpelanggan.php?srch-term=&nama=$nama&id=$PROFILE_ID\">$nama</a>";
		
		if ($blokir != null){
		$tbl_limit = new query("MARKETING", "MARKETING.LIMITKREDIT");
		$qry_limit = $tbl_limit->selectBy("LIMITKREDIT", 'PELANGGAN_ID ="'.$PROFILE_ID.'"');
		$jlh_limit = $qry_limit->current();
			if ($jlh_limit->LIMITKREDIT != null){
				$jlh = number_format((float)$jlh_limit->LIMITKREDIT, 2, ',', '.');
			}
			else {
				$jlh = "0";
			}
		$link .= " <a id=\"blokir\" href=\"#\" title=\"$blokir \ntotal nominal limit: $jlh\" class=\"text text-danger\"><i class=\"glyphicon glyphicon-minus-sign\"></i></a>";
		}
		if (!($utang == null or $utang == 0)){
			$jlh_utang = number_format($utang, 2, ',', '.');
		$link .= " <a id=\"utang\" href=\"#\" class=\"text text-muted\" title=\"total utang : Rp. $jlh_utang\"><i class=\"glyphicon glyphicon-usd\"></i></a>";
		}
		return $link;
			
	}
	

	function format($lastname){
		$omzet = trim(substr($lastname,strpos($lastname, "1=>")+3));
		if($omzet > 0 && $omzet != "" ){
		$link = number_format($omzet, 2, ',', '.'); 
		$link = 'Rp. '.$link;
		}
		else{
		$link = "";
		}
		return $link;
	}
	
		
	$filter = 'AKTIF = "Y" AND SYSTEM_ID = "2"';
	$filter .= $fill;
	
	
	$grid_approval = new MultiGridcls($dbtask,'_approval');
	$grid_approval->setQuery("DISTINCT P.STATUSAGEN AS Status, PROF.PROFILE_ID,PROF.NAMA, PROF.KODE, B.BLOKIR AS Blokir, NOTICKET, U.OMZET, U.UTANG, 'Opsi' AS Opsi"
										, "PROFILE.PROFILE PROF
											INNER JOIN SYSTEM_PROFILE SY ON SY.PROFILE_ID = PROF.PROFILE_ID
											INNER JOIN MARKETING.PELANGGAN P ON P.PELANGGAN_ID = PROF.PROFILE_ID
											INNER JOIN MARKETING.AGEN_AREA AA ON AA.KONTAK_ID = P.PELANGGAN_ID 
											INNER JOIN MARKETING.MKT_AREA MA ON MA.MKTAREA_ID = AA.MKTAREA_ID
											INNER JOIN MARKETING.MKT_PROAREA MPA ON MPA.MKTAREA_ID = MA.MKTAREA_ID
											INNER JOIN MARKETING.MKT_WILPRO MWA ON MPA.MKTPROPINSI_ID = MWA.MKTPROPINSI_ID
											INNER JOIN MARKETING.MKT_REGWIL MR ON MR.MKTWILAYAH_ID = MWA.MKTWILAYAH_ID
											LEFT JOIN MARKETING.PELANGGAN_BLOKIR B ON PROF.PROFILE_ID = B.PELANGGAN_ID
											LEFT JOIN (SELECT KDAGEN, MAX(TANGGAL_SJ) AS TANGGAL_SJ FROM FINANCE.JUALHARIAN GROUP BY KDAGEN) JH ON PROF.KODE = JH.KDAGEN
											LEFT JOIN (SELECT SUM(T.KURANG_BAYAR) AS UTANG, SUM(T.TOTAL + T.KURANG_BAYAR) AS OMZET, T.PELANGGAN_ID FROM  FINANCE.TAGIHAN T GROUP BY  T.PELANGGAN_ID) U ON PROF.PROFILE_ID = U.PELANGGAN_ID
											", "PROFILE_ID",$filter);
	$grid_approval->setResultsPerPage($batas);
	//$grid_all->setColumnType('Status', MultiGridcls::TYPE_FUNCTION, 'status', '1=>%Status%');
	$grid_approval->setColumnType('Status', MultiGridcls::TYPE_ARRAY, array('VALID' => '<a class="label label-success" id="valid" title="PROFIL PELANGGAN SUDAH VALID DAN DISETUJUI">Valid</a>', 'DROP' => '<a id="drop" class="label label-warning">Drop</a>', 'EDIT' => '<a class="label label-info" id="edit" title="DATA PELANGGAN SEDANG DIEDIT">Edit</a>', 'NEW' => '<a class="label label-default" id="new" title="CALON PELANGGAN BARU">New</a>', 'APPROVAL'=> '<a class="label label-danger" id="approve" title="PELANGGAN SEDANG MENUNGGU UNTUK DISETUJUI">Approval</a>'));
	//$grid_all->setColumnType('NAMA', MultiGridcls::TYPE_HREF, "m_detailpelanggan.php?srch-term=&nama=%NAMA%&id=%PROFILE_ID%");
	$grid_approval->setColumnType('NAMA', MultiGridcls::TYPE_FUNCTION, 'label', '1=>%NAMA% 2=>%PROFILE_ID% 3=>%Blokir% 4=>%KODE% 5=>%UTANG%');
	//$grid_approval->setColumnHeader('TANGGAL_SJ', 'Order terakhir');	
	$grid_approval->setColumnHeader('OMZET', 'Jumlah Omzet');
	$grid_approval->hideColumn('PROFILE_ID');
	$grid_approval->hideColumn('NOTICKET');
	$grid_approval->hideColumn('WILAYAH_ID');
	$grid_approval->hideColumn('WILAYAH');
	$grid_approval->hideColumn('REGIONAL');
	$grid_approval->hideColumn('AREA');
	$grid_approval->hideColumn('AREA_ID');
	$grid_approval->hideColumn('Blokir');
	$grid_approval->hideColumn('KODE');
	$grid_approval->setColumnType('OMZET',MultiGridcls::TYPE_FUNCTION, 'format', '1=>%OMZET%');
	//$grid_all->hideColumn('NOTICKET');
	$grid_approval->hideColumn('UTANG');
	$grid_approval->setColumnType('Opsi', MultiGridcls::TYPE_FUNCTION, 'returnopsi', '1=>%Status% 2=>%PROFILE_ID% 3=>%NOTICKET%');
	return $grid_approval->printTable('table_approval', isset($_REQUEST['srch-term']) ? "&srch-term=" .$_REQUEST['srch-term'] : "");
										

}

function getReotim($batas){								
										if (!isset($dbtask)) {
											$dbtask = new EyeMySQLAdap(':/cloudsql/database-sm:database-sm', 'root', '', 'PROFILE');
										}	
										function returnopsi($lastname){
												$status = trim(substr($lastname,strpos($lastname, "1=>")+3,strpos($lastname, "2=>")-3));
												$PROFILE_ID = trim(substr($lastname,strpos($lastname, "2=>")+3,strpos($lastname, "3=>")-strpos($lastname, "2=>")-3));
												$NOTICKET = trim(substr($lastname,strpos($lastname, "3=>")+3));
												$opsi = "<div class=\"btn-group\"><a href=\"#\" class=\"dropdown-toggle\" data-toggle=\"dropdown\" title=\"Menu\"><i class=\"glyphicon glyphicon-cog\"></i></a><ul class=\"dropdown-menu\" style=\"top: 0px; right: 20px; left: auto;\">";
												if($status == 'VALID'){
													$opsi .= "<li><a class=\"edit_profil\" profile_id=\"" .$PROFILE_ID ."\"><i class=\"fa fa-edit\"></i> Edit</a></li>";
												}
												if($status == 'NEW' or $status == 'EDIT'){
														$opsi .= "<li><a class=\"validasi_profil\" profile_id=\"".$PROFILE_ID."\"><i class=\"fa fa-check\"></i> Validasi</a></li>";
												} 
												if($status == 'APPROVAL' || $status == "VALID"){
														$opsi .= "<li><a href=\"#show_app\" class=\"lihat_approval\"  data-toggle=\"modal\" ticket_id=\"".$NOTICKET."\"><i class=\"fa fa-envelope-o\"></i> Lihat Approval</i></a></li>";
												}
												//if($Status == 'VALID'){						
													//	$opsi .= "<li><a href=\"#\" class=\"blacklist\" data-toggle=\"modal\" id=\"".$PROFILE_ID."\"><i class=\"fa fa-check\"></i> Blacklist</a></li>";
												//}
												$opsi .= "</ul></div>";
												return $opsi;
										}
										if(!isset($_REQUEST['order1']) || $_REQUEST['order1'] === ""){$order = ' ORDER BY P.STATUSAGEN';}
										$filter = ' AKTIF = "Y" AND SYSTEM_ID = "2" AND MR.MKTREGIONAL_ID = "01608BD000001"';
										$grid_reotim = new MultiGridcls($dbtask,'_reotim');				
										$grid_reotim->setQuery("DISTINCT P.STATUSAGEN AS Status, PROF.PROFILE_ID,PROF.NAMA, NOTICKET,  'Opsi' AS Opsi"
										, "PROFILE PROF
											INNER JOIN SYSTEM_PROFILE SY ON SY.PROFILE_ID = PROF.PROFILE_ID
											INNER JOIN MARKETING.PELANGGAN P ON P.PELANGGAN_ID = PROF.PROFILE_ID
											INNER JOIN MARKETING.AGEN_AREA AA ON AA.KONTAK_ID = P.PELANGGAN_ID 
											INNER JOIN MARKETING.MKT_AREA MA ON MA.MKTAREA_ID = AA.MKTAREA_ID
											INNER JOIN MARKETING.MKT_PROAREA MPA ON MPA.MKTAREA_ID = MA.MKTAREA_ID
											INNER JOIN MARKETING.MKT_WILPRO MWA ON MPA.MKTPROPINSI_ID = MWA.MKTPROPINSI_ID
											INNER JOIN MARKETING.MKT_REGWIL MR ON MR.MKTWILAYAH_ID = MWA.MKTWILAYAH_ID											
											", "PROFILE_ID",$filter);
										$grid_reotim->setResultsPerPage($batas);
										//$grid_reotim->setGridHeight("500");
										$grid_reotim->setColumnType('Status', MultiGridcls::TYPE_ARRAY, array('VALID' => ' <span class="label label-success">Valid</span>', 'DROP' => '<span class="label label-warning">Drop</span>', 'EDIT' => '<span class="label label-info">Edit</span>', 'NEW' => '<span class="label label-default">New</span>', 'APPROVAL'=> '<span class="label label-danger">Approval</span>')); 
										$grid_reotim->setColumnType('NAMA', MultiGridcls::TYPE_HREF, "m_detailpelanggan.php?srch-term=&nama=%NAMA%&id=%PROFILE_ID%");
										$grid_reotim->hideColumn('PROFILE_ID');
										$grid_reotim->hideColumn('NOTICKET');
										$grid_reotim->setColumnType('Opsi', MultiGridcls::TYPE_FUNCTION, 'returnopsi', '1=>%Status% 2=>%PROFILE_ID% 3=>%NOTICKET%');
										return $grid_reotim->printTable('table_reotim',isset($_REQUEST['srch-term']) ? "&srch-term=" .$_REQUEST['srch-term'] : "");
}

function getCostum($batas, $fill){
	if (!isset($dbtask)) {
		$dbtask = new EyeMySQLAdap(':/cloudsql/database-sm:database-sm', 'root', '', 'PROFILE');
	}
	function returnopsi($lastname){
		$status = trim(substr($lastname,strpos($lastname, "1=>")+3,strpos($lastname, "2=>")-3));
		$PROFILE_ID = trim(substr($lastname,strpos($lastname, "2=>")+3,strpos($lastname, "3=>")-strpos($lastname, "2=>")-3));
		$NOTICKET = trim(substr($lastname,strpos($lastname, "3=>")+3));
		$opsi = "<div class=\"btn-group\"><a href=\"#\" class=\"dropdown-toggle\" data-toggle=\"dropdown\" title=\"Menu\"><i class=\"glyphicon glyphicon-cog\"></i></a><ul class=\"dropdown-menu\" style=\"top: 0px; right: 20px; left: auto;\">";
				if($status == 'VALID'){
				$opsi .= "<li><a class=\"edit_profil\" profile_id=\"" .$PROFILE_ID ."\"><i class=\"fa fa-edit\"></i> Edit</a></li>";
				}
				if($status == 'NEW' or $status == 'EDIT'){
				$opsi .= "<li><a class=\"validasi_profil\" profile_id=\"".$PROFILE_ID."\"><i class=\"fa fa-check\"></i> Validasi</a></li>";
				} 
				if($status == 'APPROVAL' || $status == "VALID"){
				$opsi .= "<li><a href=\"#show_app\" class=\"lihat_approval\"  data-toggle=\"modal\" ticket_id=\"".$NOTICKET."\"><i class=\"fa fa-envelope-o\"></i> Lihat Approval</i></a></li>";
				}
				//if($Status == 'VALID'){						
				//	$opsi .= "<li><a href=\"#\" class=\"blacklist\" data-toggle=\"modal\" id=\"".$PROFILE_ID."\"><i class=\"fa fa-check\"></i> Blacklist</a></li>";
				//}
				$opsi .= "</ul></div>";
				$stat = new query("MARKETING", "MARKETING.PELANGGAN");
				if($status == "VALID2"){
				$ARRAY_FIELD = array("STATUSAGEN" => "VALID");
				$stat_ubh = $stat->updateBy('PELANGGAN_ID',$PROFILE_ID, $ARRAY_FIELD);
				}
				return $opsi;
	}
	
	function format($lastname){
		$omzet = trim(substr($lastname,strpos($lastname, "1=>")+3));
		if($omzet > 0 && $omzet != "" ){
		$link = number_format($omzet, 2, ',', '.'); 
		$link = 'Rp. '.$link;
		}
		else{
		$link = "0";
		}
		return $link;
	}
	
	function blokir($lastname){
		$omzet = trim(substr($lastname,strpos($lastname, "1=>")+3));
		if($omzet != "" ){
		$link = '<a id="" href="" class="label label-danger">'.$omzet.'</a>';
		}
		else{
		$link = '<a id="" href="" class="label label-success">Tidak Diblokir</a>';
		}
		return $link;
	}
	
	$filter = 'AKTIF = "Y" AND SYSTEM_ID = "2"';
	
	$grid_costum = new MultiGridcls($dbtask,'_costum');
	$grid_costum->setQuery("DISTINCT P.STATUSAGEN AS Status, PROF.PROFILE_ID,PROF.NAMA, PROF.KODE, B.BLOKIR, NOTICKET, U.OMZET, U.UTANG, JH.TANGGAL_SJ, L.LIMITKREDIT, 'Opsi' AS Opsi"
										, "PROFILE.PROFILE PROF
											INNER JOIN SYSTEM_PROFILE SY ON SY.PROFILE_ID = PROF.PROFILE_ID
											INNER JOIN MARKETING.PELANGGAN P ON P.PELANGGAN_ID = PROF.PROFILE_ID
											INNER JOIN MARKETING.AGEN_AREA AA ON AA.KONTAK_ID = P.PELANGGAN_ID 
											INNER JOIN MARKETING.MKT_AREA MA ON MA.MKTAREA_ID = AA.MKTAREA_ID
											INNER JOIN MARKETING.MKT_PROAREA MPA ON MPA.MKTAREA_ID = MA.MKTAREA_ID
											INNER JOIN MARKETING.MKT_WILPRO MWA ON MPA.MKTPROPINSI_ID = MWA.MKTPROPINSI_ID
											INNER JOIN MARKETING.MKT_REGWIL MR ON MR.MKTWILAYAH_ID = MWA.MKTWILAYAH_ID
											LEFT JOIN MARKETING.PELANGGAN_BLOKIR B ON PROF.PROFILE_ID = B.PELANGGAN_ID
											LEFT JOIN (SELECT KDAGEN, MAX(TANGGAL_SJ) AS TANGGAL_SJ FROM FINANCE.JUALHARIAN GROUP BY KDAGEN) JH ON PROF.KODE = JH.KDAGEN
											LEFT JOIN (SELECT SUM(T.KURANG_BAYAR) AS UTANG, SUM(T.TOTAL + T.KURANG_BAYAR) AS OMZET, T.PELANGGAN_ID FROM  FINANCE.TAGIHAN T GROUP BY  T.PELANGGAN_ID) U ON PROF.PROFILE_ID = U.PELANGGAN_ID
											LEFT JOIN MARKETING.LIMITKREDIT L ON PROF.PROFILE_ID = L.PELANGGAN_ID
											", "PROFILE_ID",$filter);
	$grid_costum->setResultsPerPage($batas);
	if ($fill != ""){
		$a = $s = $b = $u = $o = $or = $l = 0;
		$fill2 = explode(",", $fill);
		/*foreach ($fill2 as $item)
		{
			if($item == 'All'){
				break;
			}
			if($item == 'Status'){
				//$grid_costum->hideColumn('Status');
				break;
				
			}
			
			
			if($item != 'Blokir'){
				$grid_costum->hideColumn('BLOKIR');
				
			}
			
			if($item != 'Utang'){
				$grid_costum->hideColumn('UTANG');
				break;
			}
			
			if($item != 'Omzet'){
				$grid_costum->hideColumn('OMZET');
				
			}
			
			if($item != 'Order'){
				$grid_costum->hideColumn('TANGGAL_SJ');
				
			}
			
			if($item != 'Limit'){
				$grid_costum->hideColumn('LIMITKREDIT');
				
			}
			
			
		}*/
		
		foreach ($fill2 as $item){
			if($item == 'All'){
				$a = 1;
				break;
			}
			if($item == 'Status'){
				$s = 1;
			}
			if($item == 'Blokir'){
				$b = 1;
			}
			if($item == 'Utang'){
				$u = 1;
			}
			if($item == 'Omzet'){
				$o = 1;
			}
			if($item == 'Order'){
				$or = 1;
			}
			if($item == 'Limit'){
				$l = 1;
			}
			
		}
		
		if($a == 1){
			
		}
		else{
			if($s == 0){
				$grid_costum->hideColumn('Status');
			}
			if($b == 0){
				$grid_costum->hideColumn('BLOKIR');
			}
			if($u == 0){
				$grid_costum->hideColumn('UTANG');
			}
			if($o == 0){
				$grid_costum->hideColumn('OMZET');
			}
			if($or == 0){
				$grid_costum->hideColumn('TANGGAL_SJ');
			}
			if($l == 0){
				$grid_costum->hideColumn('LIMITKREDIT');
			}
		}
	}
	//$grid_all->setColumnType('Status', MultiGridcls::TYPE_FUNCTION, 'status', '1=>%Status%');
	$grid_costum->setColumnType('Status', MultiGridcls::TYPE_ARRAY, array('VALID' => '<a class="label label-success" id="valid" title="PROFIL PELANGGAN SUDAH VALID DAN DISETUJUI">Valid</a>', 'DROP' => '<a id="drop" class="label label-warning">Drop</a>', 'EDIT' => '<a class="label label-info" id="edit" title="DATA PELANGGAN SEDANG DIEDIT">Edit</a>', 'NEW' => '<a class="label label-default" id="new" title="CALON PELANGGAN BARU">New</a>', 'APPROVAL'=> '<a class="label label-danger" id="approve" title="PELANGGAN SEDANG MENUNGGU UNTUK DISETUJUI">Approval</a>'));
	$grid_costum->setColumnType('NAMA', MultiGridcls::TYPE_HREF, "m_detailpelanggan.php?srch-term=&nama=%NAMA%&id=%PROFILE_ID%");
	//$grid_costum->setColumnType('NAMA', MultiGridcls::TYPE_FUNCTION, 'label', '1=>%NAMA% 2=>%PROFILE_ID% 3=>%Blokir% 4=>%KODE% 5=>%UTANG%');
	$grid_costum->setColumnHeader('NAMA', 'Nama Pelanggan');	
	$grid_costum->setColumnHeader('OMZET', 'Jumlah Omzet');	
	$grid_costum->setColumnHeader('BLOKIR', 'Informasi Blokir');
	$grid_costum->setColumnHeader('TANGGAL_SJ', 'Order terakhir');
	$grid_costum->setColumnHeader('LIMITKREDIT', 'Jenis Limit');
	$grid_costum->setColumnHeader('UTANG', 'Jumlah Hutang');
	$grid_costum->hideColumn('PROFILE_ID');
	$grid_costum->hideColumn('NOTICKET');
	$grid_costum->hideColumn('WILAYAH_ID');
	$grid_costum->hideColumn('WILAYAH');
	$grid_costum->hideColumn('REGIONAL');
	$grid_costum->hideColumn('AREA');
	$grid_costum->hideColumn('AREA_ID');
	//$grid_costum->hideColumn('Blokir');
	$grid_costum->hideColumn('KODE');
	$grid_costum->setColumnType('BLOKIR', MultiGridcls::TYPE_FUNCTION, 'blokir', '1=>%BLOKIR%');
	$grid_costum->setColumnType('OMZET', MultiGridcls::TYPE_FUNCTION, 'format', '1=>%OMZET%');
	$grid_costum->setColumnType('TANGGAL_SJ', MultiGridcls::TYPE_DATE, 'd M Y', '%TGL_SELESAI%');
	$grid_costum->setColumnType('UTANG', MultiGridcls::TYPE_FUNCTION, 'format', '1=>%UTANG%');
	//$grid_all->hideColumn('NOTICKET');
	//$grid_costum->hideColumn('UTANG');
	$grid_costum->setColumnType('Opsi', MultiGridcls::TYPE_FUNCTION, 'returnopsi', '1=>%Status% 2=>%PROFILE_ID% 3=>%NOTICKET%');
	return $grid_costum->printTable('table_costum', "");

	
	
}

function getSisaLimit($GET_ID_PELANGGAN){
$table_sisaLimit = new query('FINANCE', "");
$sisaLimit = $table_sisaLimit->queryfull("SELECT S.NAMA AS NAMA_PELANGGAN, FORMAT(SUM(S.LIMITKREDIT)+SUM(S.SALDO),0) AS 'LIMIT', FORMAT(SUM(S.KURANG_BAYAR),0) AS TAGIHAN , FORMAT((SUM(S.LIMITKREDIT)+SUM(S.SALDO))-SUM(S.KURANG_BAYAR),0) AS 'SISALIMIT' FROM(select P.NAMA, '' AS LIMITKREDIT, CASE WHEN T.SALDO IS NULL THEN 0 ELSE T.SALDO END AS SALDO, CASE WHEN T.KURANG_BAYAR IS NULL THEN 0 ELSE T.KURANG_BAYAR END AS KURANG_BAYAR from 
PROFILE.PROFILE P INNER JOIN PROFILE.SYSTEM_PROFILE SP ON SP.PROFILE_ID = P.PROFILE_ID INNER JOIN PROFILE.SYSTEM S ON S.SYSTEM_ID = SP.SYSTEM_ID  LEFT JOIN FINANCE.TAGIHAN T ON T.PELANGGAN_ID =P.PROFILE_ID  WHERE P.AKTIF = 'Y' AND S.SYSTEM = 'MARKETING' UNION ALL select P.NAMA, L.LIMITKREDIT, '' AS SALDO, '' AS KURANG_BAYAR from PROFILE.PROFILE P INNER JOIN PROFILE.SYSTEM_PROFILE SP ON SP.PROFILE_ID = P.PROFILE_ID INNER JOIN PROFILE.SYSTEM S ON S.SYSTEM_ID = SP.SYSTEM_ID LEFT JOIN MARKETING.LIMITKREDIT L ON L.PELANGGAN_ID =P.PROFILE_ID WHERE P.PROFILE_ID = '".$GET_ID_PELANGGAN."' AND L.TGL_MULAI <= now() AND (L.TGL_SELESAI >= now() OR L.TGL_SELESAI IS NULL) AND LIMITKREDIT <> 'UNLIMITED' AND LIMITKREDIT <> 'CASH'  AND P.AKTIF = 'Y' AND S.SYSTEM = 'MARKETING') S GROUP BY S.NAMA HAVING SUM(S.LIMITKREDIT)+SUM(S.SALDO) <> 0
");
if ($sisaLimit->num_rows() == 1){
	$sisaLimit = $sisaLimit->current();
	if ($sisaLimit->SISALIMIT > 0){
	$sisa_limit_pelanggan = "<span class=\"label label-success\" style=\"font-size: 13px;\">Sisa Limit : Rp. ".str_replace(",", ".", $sisaLimit->SISALIMIT).",00</span>";
	}
	else{
	$sisa_limit_pelanggan = "<span class=\"label label-danger\" style=\"font-size: 13px;\"> Sisa Limit Minus (Rp. ".str_replace(",", ".", $sisaLimit->SISALIMIT).",00)</span>";
	}
	
	
}
else{
	//$sisa_limit_pelanggan = "<span class=\"label label-default\">Tidak Memiliki Limit Kredit</span>";
	$sisa_limit_pelanggan = "";
}
	return $sisa_limit_pelanggan;
}


	if ($act == 'INFOLAIN'){
		echo getInfoLainLain($id, 20);
	}
	else if ($act == "PIC"){
		echo getPIC($id, 20);
	}
	else if ($act == "TOP" || $act == "DETAIL_TOP"){
		echo getTOP($id, 20);
	}
	else if ($act == "LIMIT" || $act == "DETAIL_LIMITKREDIT"){
		echo getLimit($id, 20);
	}
	else if ($act == "PROFIL"){
		echo getProfil($id);
	}	
	else if ($act == "GET_TELEPON"){
		echo getPhone($id,20);
	}
	else if ($act == "TELEPON"){
		Multigridcls::useAjaxTable('/get_data_profil.php?id=' .$id ."&act=GET_TELEPON&status=" .$_REQUEST['status'],"_phone_" .$id);
	}	
	else if ($act == "ALAMAT_ALL"){
		echo getAlamatAll($id);
	}		
	else if ($act == "ALAMAT"){
		echo getAlamat($id);
	}	
	else if ($act == "DOKUMEN"){
		echo getDokumen($id,20);
	}	
	else if ($act == "GRID_REOBAR"){
		echo getReobar(20);
	}
	else if ($act == "GRID_REOTENG"){
		echo getReoteng(20);
	}
	else if ($act == "GRID_REOTIM"){
		echo getReotim(20);
	}	
	else if ($act == 'GRID_NONAPPROVE'){
		echo getNonApprove(20, $fill);		
	}
	else if ($act == 'APPROVAL'){
		echo getApproval($id);
	}
	else if ($act == 'GRID_SEARCH'){
		echo getProfileSearch(20);
	}
	else if ($act == 'KODE'){
		echo getKode($id, $kode);
	}
	else if ($act == 'ALL'){
			
		echo getAll(20);
		
	}
	else if ($act == 'BLOKIR'){
		echo getBlock(20, $fill);
		
	}


	else if ($act == 'Coba'){
		echo getCoba(20);
	}
	
	else if ($act == 'FILTER'){
		$fill ='';
		if($filter == 'BLOKIR'){
			$fill .= ' AND BLOKIR IS NOT NULL';
			
		}
		else if($filter == 'UTANG'){
			$fill .=' AND UTANG > 0';
			//return Multigridcls::useAjaxTable('/get_data_profil.php?act=Coba','_block');
		}
		return Multigridcls::useAjaxTable('/get_data_profil.php?act=BLOKIR&fill='.$fill,'_block');
	}

	else if ($act == 'getCost'){
		echo getCostum(20, $fill);
	}

	else if ($act == 'costum'){
		return Multigridcls::useAjaxTable('/get_data_profil.php?act=getCost&fill='.$fill,'_costum');
	}

	else if ($act == 'APPROVE'){
		$fill ='';
		if($filter == 'approval'){
			$fill .= ' AND STATUSAGEN = "APPROVAL"';
			
		}
		else if($filter == 'edit'){
			$fill .=' AND STATUSAGEN = "EDIT"';
			
		}
		else if($filter == 'new'){
			$fill .=' AND STATUSAGEN = "NEW"';
			
		}
		else if($filter == 'valid'){
			$fill .=' AND STATUSAGEN = "VALID"';
			
		}
		else{
		}
		return Multigridcls::useAjaxTable('/get_data_profil.php?act=GRID_NONAPPROVE&fill='.$fill,'_approval');
	}
	
	else if ($act == 'tim'){
		return Multigridcls::useAjaxTable('/get_data_profil.php?act=GRID_REOTIM','_reotim');
	}
	else if ($act == 'teng'){
		return Multigridcls::useAjaxTable('/get_data_profil.php?act=GRID_REOTENG','_reoteng');
	}
	else if ($act == 'bar'){
		return Multigridcls::useAjaxTable('/get_data_profil.php?act=GRID_REOBAR','_reobar');
	}
	else if ($act == 'ind'){
		Multigridcls::useAjaxTable('/get_data_profil.php?act=ALL', '_all');
	}
	else if ($act == 'sisalimit'){
		echo getSisaLimit($id);
	}
	else {
	header('location:/lock.php?logingagal');
	}


class crud_master{
	protected $_NAMA_DB;
	protected $_NAMA_TABEL;
	//protected $_NAMA_FIELD_FINDBY;
	//protected $_VALUE_FIELD_FINDBY;
	protected $_NUM_ROW;
	
function __construct($NAMA_DB,$NAMA_TABEL){
		$this->_NAMA_DB = $NAMA_DB;
		$this->_NAMA_TABEL = $NAMA_TABEL;

		$this->_DB_CONN = new query($this->_NAMA_DB,$this->_NAMA_TABEL);
		
	}

public function CheckValue($SELECT,$WHERE){

		$currentNama = $this->_DB_CONN->selectBy($SELECT,$WHERE);
		$this->_NUM_ROW = $currentNama->num_rows();
		return $this->_NUM_ROW;
	}	
	
}	
?>