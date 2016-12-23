<?php
session_start();
include_once "../class.eyemysqladap.inc.php";
include_once "../class.MultiGrid.inc.php";

if(!empty($_SESSION['alamat_email'])  //&& !empty($_SESSION['action']) && ( ($_SESSION['action']) == 'SR' || ($_SESSION['action']) == 'SUPERADMIN' ) 
){
$batas = 20;
$agen_id = '';
$namaagen = '';
$notagihan = '';
$namapelanggan = '';
$queryagen = "";
$halkategori = "";
$date1 = date("Y-m-d");
$date2 = date("Y-m-d");
$Where1 = '';
$judul='';

if(isset($_REQUEST['srch-term'])){
	IF($_REQUEST['srch-term'] != ''){
		$judul .= ' Pencarian berdasarkan: '.$_REQUEST['srch-term'];
		$Where1 = ' AND PROF.NAMA LIKE "%'.strtoupper($_REQUEST['srch-term']).'%" ';
	}
}
?>
<?php 
include_once '_conn/query.php';
	$k = 0;

function createLookup($db,$table,$fields,$filter){
	$tbl_list = new query($db, $table);
	$list = $tbl_list->select($fields, $filter);
	$list = iterator_to_array($list);
	return json_encode($list);	
}
switch (substr($_SESSION['kode_dept'],0,3)) {
	case "CMW" :
		$filter_regional = ' AND MKTREGIONAL_ID = "01608BG000006"';
		break;
	case "CME" :
		$filter_regional = ' AND MKTREGIONAL_ID = "01608BD000001"';
		break;
	case "CMM" :
		$filter_regional = ' AND MKTREGIONAL_ID = "01608BG000007"';
		break;
}	

$select_regional = createLookup("MARKETING", 'MARKETING.MKT_REGIONAL',
								"MKTREGIONAL_ID, REGIONAL",'WHERE (TGL_SELESAI IS NULL OR TGL_SELESAI LIKE "%0000%")' .$filter_regional);					
$select_wilayah = createLookup("MARKETING", 'MARKETING.MKT_WILAYAH MW INNER JOIN MARKETING.MKT_REGWIL MR ON
								MW.MKTWILAYAH_ID = MR.MKTWILAYAH_ID',"MW.MKTWILAYAH_ID, MW.WILAYAH, MR.MKTREGIONAL_ID",
								'WHERE MW.TGL_SELESAI IS NULL OR MW.TGL_SELESAI LIKE "%0000%"');
$select_propinsi = createLookup("MARKETING", 'MARKETING.MKT_PROPINSI MP INNER JOIN MARKETING.MKT_WILPRO MW ON
								MP.MKTPROPINSI_ID = MW.MKTPROPINSI_ID',"MP.MKTPROPINSI_ID, MP.PROPINSI, MW.MKTWILAYAH_ID",
								'WHERE MP.TGL_SELESAI IS NULL OR MP.TGL_SELESAI LIKE "%0000%"');
$select_area = createLookup("MARKETING", 'MARKETING.MKT_AREA MA INNER JOIN MARKETING.MKT_PROAREA MP ON
							MA.MKTAREA_ID = MP.MKTAREA_ID',"MA.MKTAREA_ID, MA.AREA, MP.MKTPROPINSI_ID",
							'WHERE MA.TGL_SELESAI IS NULL OR MA.TGL_SELESAI LIKE "%0000%"');
$select_tipe = createLookup("MARKETING", 'MARKETING.AGEN_TIPE',"AGENTIPE_ID, TIPE", "");
$select_gps = createLookup("MARKETING", "MARKETING.GPS","NAMA, GPS","ORDER BY NAMA");
							

?>
<!DOCTYPE html>
<html lang="en">
	<?php include '../_template/head.php'; //TEMPLATE HEADER DAN BERISI CSS GENERAL ?>
	<body id="page-top">
<?php include '../_template/navbar_head.php'; //TEMPLATE MENU YANG ADA PADA HEADER ?>
<?php //include '../_template/navbar_sub.php'; //TEMPLATE MENU YANG ADA PADA NAVIGASI BAR?>

<!-- CSS YANG DIBUTUHKAN DI PAGE INI SAJA -->
<link rel="stylesheet" href="/js/DataTables/media/css/DT_bootstrap.css" />
<link rel="stylesheet" href="/js/x-editable/css/bootstrap-editable.css">
<link rel="stylesheet" href="/css/jquery-ui/jquery-ui.css"> <?php //Datepicker ?>
<script src="/js/jquery/jquery.min.js"></script>
<script src="/js/jquery-ui/jquery-ui.js"></script>
<script src="/js/dialog.js"></script>
<style>
<?php //AGAR TABEL BISA DI SCROLL HORIZONTAL  ?>
.dataTables_wrapper {
	   overflow-x: auto;
	   display:block;
}
.ui-dialog { z-index: 3000 !important ;}	   
</style>

<!------------------------------------------------------------------------->

<!--main-->
<?php
	include "modal.html"; 
	include "html_showapproval.html"; 
?>
<div class="navbar">
	<div class="row"> <br><br>  
	<!-- <?php //BREADCRUMB UNTUK MEMPERLIHATKAN SEDANG BERADA DI MENU APA ?>-->
			<!--ol class="breadcrumb">
			<li><a href="#">Home</a></li>
			<li><a href="#">Master</a></li>
			<li class="active">Pelanggan</li>
			</ol-->
	<!------------------------------------------------------------------------->		
		<div class="col-md-12 col-sm-12"> <!-- <?php //lg = large, md = medium, sm = small untuk melihat width nya, ada pada bootstrap.css ?>-->
		  <div class="panelblue"> <?php //style card yang bisa di custom di styles.css ?> 
          			<div class="panel-heading" style="margin-bottom: -10px;padding-bottom:0px"> 
						<span style="float:left;"><a href="/index_ADM.php">Home</a>> Master	> <b>Pelanggan</b></span><br>
						<div style="padding-top:10px">
						<font size="4"><b>Master Pelanggan</b></font>&nbsp;&nbsp;<a id="tambahdata" href="#modalDialog" data-toggle="modal" >+ Tambah Data Pelanggan</a></div>
					</div>
					<div class="panel-body">
			<?php //----------------------Form search--------------------------//?>
										<?php 
											$cek_kode = substr($_SESSION['kode_dept'],0,3);	
											//print($cek_kode);
										?>
								<form method="GET" action="m_pelanggan.php" id="srch-form">
								<div class="input-group" style="padding-bottom:5px;border-bottom:1px solid #dadada;">
									<input type="text" class="form-control hint" style="z-index:1;" placeholder="pencarian berdasarkan Nama Pelanggan" name="srch-term" id="srch-term" data-container="body" data-placement="bottom" data-content="Untuk pencarian data Pelanggan berdasarkan Nama">
									<input type="hidden" name="nama" id="nama" value="">
									<input type="hidden" name="id" id="id" value="">
									<!--
									<div class="input-group-btn">
										
										<select id="regional" name="regional" class="form-control dropdown-menu">
										<?php if ($cek_kode == "CM0" || $cek_kode == 'OS0' || $cek_kode == "D00"){?>
										<option value=''>Semua Regional</option>
										<?php
											}
											if ($cek_kode == "CME" || $cek_kode == "CM0" || $cek_kode == 'OS0' || $cek_kode == "D00"){
										?>
										<option value=' AND MR.MKTREGIONAL_ID = "01608BD000001"'>Indonesia Timur</option>
										<?php
											}
											if ($cek_kode == "CMM" || $cek_kode == "CM0" || $cek_kode == 'OS0' || $cek_kode == "D00"){
										?>	
										<option value=' AND MR.MKTREGIONAL_ID = "01608BG000007"'>Indonesia Tengah</option>
										<?php
											}
											if ($cek_kode == "CMW" || $cek_kode == "CM0" || $cek_kode == 'OS0' || $cek_kode == "D00"){
										?>
										<option value=' AND MR.MKTREGIONAL_ID = "01608BG000006"'>Indonesia Barat</option>
										<?php
											}		
										?>
										</select>

									</div> -->
									<div class="input-group-btn">
										<button class="btn btn-default" type="submit"><i class="glyphicon glyphicon-search"></i></button>
										<?php if($queryagen != ''){ ?>
										<a class="btn btn-default" href="m_agen.php" title="Kembali"><i class="fa fa-undo"></i></a>
										<?php } ?>
									</div>
								</div>
								</form>

						
			<?php //-----------------------------------------------------------//?>
			
							
						<div class="row">
								<?
								if (isset($_REQUEST['srch-term']) && $_REQUEST['srch-term']){
								?>
								<div class="col-md-12 col-sm-12">								
									<div class="panel panel-default">
										<div class="panel-heading"><i class="fa fa-user"></i><? echo $judul; ?></div>
											<div class="panel-body" >
										<?php
											Multigridcls::useAjaxTable('/get_data_profil.php?act=GRID_SEARCH&srch-term=' .$_REQUEST['srch-term'].'&regional='.$_REQUEST['regional'],'_search');
										?>
											</div>
									</div>
								</div>
								<?	
									}
								else if ($k == 7) {
								?>
								<!-- START GRID PELANGGAN BELUM APPROVE-->
								<div class="col-md-6">								
									<div class="panel panel-default">
										<div class="panel-heading"><i class="fa fa-user"></i> Profil Pelanggan Belum Approve</div>
											<div class="panel-body" >
										<?php
											Multigridcls::useAjaxTable('/get_data_profil.php?act=GRID_NONAPPROVE', '_nonapprove');
										?>
											</div>
									</div>
								</div>
							<!-- END  GRID PELANGGAN BELUM APPROVE-->		
							<?							
							if ($cek_kode == "CMW" || $cek_kode == "CM0" || $cek_kode == 'OS0' || $cek_kode == "D00"){
							?>
							<!-- START GRID PELANGGAN REOBAR-->
							<div class="col-md-6">								
								<div class="panel panel-default">
									<div class="panel-heading"><i class="fa fa-user"></i> Profil Pelanggan Regional Barat</div>
										<div class="panel-body" >
										<?php
											Multigridcls::useAjaxTable('/get_data_profil.php?act=GRID_REOBAR','_reobar');
										?>
										<br>
									</div>
								</div>
							</div>
							<!-- END  GRID PELANGGAN REOBAR-->			
							<?
							}
							?>

								<!-- START GRID PELANGGAN BELUM APPROVE-->		
							<?
							if ($cek_kode == "CMM" || $cek_kode == "CM0" || $cek_kode == 'OS0' || $cek_kode == "D00"){
							?>								
								<div class="col-md-6">								
									<div class="panel panel-default">
										<div class="panel-heading"><i class="fa fa-user"></i> Profil Pelanggan Regional Tengah</div>
											<div class="panel-body" >
										<?php
											Multigridcls::useAjaxTable('/get_data_profil.php?act=GRID_REOTENG','_reoteng');
										?>
											</div>
									</div>
								</div>
							<?
							}							
							?>
							<!-- END  GRID PELANGGAN BELUM APPROVE-->		
							<?							
							if ($cek_kode == "CME" || $cek_kode == "CM0" || $cek_kode == 'OS0' || $cek_kode == "D00"){
							?>							
							<!-- START GRID PELANGGAN REOBAR-->
							<div class="col-md-6">								
								<div class="panel panel-default">
									<div class="panel-heading"><i class="fa fa-user"></i> Profil Pelanggan Regional Timur</div>
										<div class="panel-body" >
										<?php
											Multigridcls::useAjaxTable('/get_data_profil.php?act=GRID_REOTIM','_reotim');
										?>
									</div>
								</div>
							</div>
							<!-- END  GRID PELANGGAN REOBAR-->							
							<?  } 
							}
							
							else{ ?>
							<div class="col-md-12 col-sm-12">
							<div class="panel panel-default">
							<div class="panel-heading"><i class="fa fa-user"></i><span class="judul">DAFTAR PELANGGAN</span>&nbsp&nbsp<a class="label label-primary semua" id="semua"></a>
							</div>
							<div class="panel-body">
								
								<span id="costum_label">Informasi yang ingin ditampilkan : &nbsp</span>
								<label class="checkbox-inline"><input type="checkbox" value="All" id="custom">All</label>
								<label class="checkbox-inline"><input type="checkbox" value="Status" id="custom">Status Keaktifan</label>
								<label class="checkbox-inline"><input type="checkbox" value="Blokir" id="custom">Blokir</label>
								<label class="checkbox-inline"><input type="checkbox" value="Utang" id="custom">Utang</label>
								<label class="checkbox-inline"><input type="checkbox" value="Omzet" id="custom">Omzet</label>
								<label class="checkbox-inline"><input type="checkbox" value="Order" id="custom">Order terakhir</label>
								<label class="checkbox-inline"><input type="checkbox" value="Limit" id="custom">Nilai Limit</label>
								
								<div id="display"> 
								<?php
									//Multigridcls::useAjaxTable('/get_data_profil.php?act=ALL', '_all');
									Multigridcls::useAjaxTable('/get_data_profil.php?act=GRID_REOBAR','_reobar');
									
								?>
								</div>
							</div>
								<div>
								<div class="col-md-5" id="keterangan"><br>Keterangan Simbol: <br>
									<a class="text text-danger" id="blokir"><i class="glyphicon glyphicon-minus-sign"></i></a> = PELANGGAN YANG DIBLOKIR<br>
									<a class="text text-muted" id="utang"><i class="glyphicon glyphicon-usd"></i></a> = PELANGGAN DENGAN HUTANG BELUM LUNAS
								</div>
								<div class="col-md-5" id="legend_status"> <br>Keterangan Status: <br>
									<div class="col-md-2"><a class="label label-success" id="valid">	VALID</a></div><div class="col-md-10">= PROFIL PELANGGAN SUDAH VALID DAN DISETUJUI</div><br><br>
									<div class="col-md-2"><a class="label label-danger" id="approve">	APPROVAL</a></div><div class="col-md-10">= PELANGGAN SEDANG MENUNGGU UNTUK DISETUJUI</div><br><br>
									<div class="col-md-2"><a class="label label-info" id="edit">		EDIT</a></div><div class="col-md-10">= DATA PELANGGAN SEDANG DIEDIT</div><br><br>
									<div class="col-md-2"><a class="label label-default" id="new">	NEW</a></div><div class="col-md-10">= CALON PELANGGAN BARU</div>
								</div>
								</div>
							</div>
							</div>
							<?php 
								}
	
							?>
						</div>						
						</div>
					</div>
			</div>
   		</div>
	
	<?php //Tambah data maupun edit data, di hide terlebih dahulu untuk nanti ditampilkan dengan menggunakan javascript dibawah?>
		</div>

<?php include "../_template/navbar_footer.php"; ?>



	<?php //javascript yang dibutuhkan untuk halaman ini saja ?>
		
		
		
		<script src="/js/jquery-ui/jquery-ui.js"></script>
		<script type="text/javascript" src="/js/modal.js"></script>
		<script type="text/javascript" src="/js/bootstrap-datepicker/bootstrap-datepicker.js"></script>
		<script>
			
	$(function() {
		$('body').on('focus',".datetime", function(){
				$(this).datepicker();				
		});	
		
				
		$('body').on('change',"#regional", function(){
			//$(this).attr('new').append('1');
			if ($('#regional option:selected').val() == "tim"){
				$(".judul").html("<b>DAFTAR PELANGGAN DARI REGIONAL TIMUR</b>");
			}
			else if($('#regional option:selected').val() == "teng" ){
				$(".judul").html("<b>DAFTAR PELANGGAN DARI REGIONAL TENGAH</b>");
			}
			else if($('#regional option:selected').val() == "bar"){
				$(".judul").html("<b>DAFTAR PELANGGAN DARI REGIONAL BARAT</b>");
			}
			else{
				$(".judul").html("<b>DAFTAR PELANGGAN DARI SELURUH REGIONAL</b>");
				$("#semua").html("");
			}
			
			$.ajax({
				url: "/get_data_profil.php?act=" + $('#regional option:selected').val(),
				type: "GET",
				success: function(msg){
					console.log($(this).attr('new'));
					$("#display").html(msg);
				},
				error: function(){
					alert("koneksi bermasalah, silahkan reload halaman");				
				}
			});
			
		});
		
		$('body').on('keypress', '#srch-term', function(e){
			if(e.keyCode == 13){
				e.preventDefault();
				$('#srch-form').submit();
			}
		});
		
		$('body').on('click', 'input[type=checkbox]', function(){
			var countchecked = $("input:checked").length;
			var checkedvalues = $("input:checked").map(function(){return this.value}).get();
			if(countchecked == 0){
				
			}
			else{
				var i;
				var opt="";
			for (i=0; i < checkedvalues.length; i++){
				if(i == 0){
					opt += checkedvalues[i];
				}else{
				opt += "," + checkedvalues[i];
				}
				$("#semua").html("<b>tampilkan halaman default<b>");
				$.ajax({
				url: "/get_data_profil.php?act=costum&fill=" + opt,
				type: "GET",
				success: function(msg){
					
					$("#display").html(msg);
				},
				error: function(){
					alert("koneksi bermasalah, silahkan reload halaman");				
				}
				});
				
			}
			console.log(countchecked);
			console.log(opt);
			}
			
		});
		
		$('body').on('click',"#semua", function(){
			$(".judul").html("<b>DAFTAR PELANGGAN</b>");
			$("#semua").html("");
			$(".checkbox-inline, #custom, #costum_label").show();
			$.ajax({
				url: "/get_data_profil.php?act=ind",
				type: "GET",
				success: function(msg){
					//console.log($(this).attr('new'));
					$("#display").html(msg);
				},
				error: function(){
					alert("koneksi bermasalah, silahkan reload halaman");				
				}
			});
		});
		
		$('body').on('click',"#blokir", function(){
			$(".judul").html("<b>DAFTAR PELANGGAN YANG DI BLOKIR</b>");
			$("#semua").html("<b>tampilkan semua daftar pelanggan<b>");
			$(".checkbox-inline, #custom, #costum_label").hide();
			$.ajax({
				url: "/get_data_profil.php?act=FILTER&filter=BLOKIR",
				type: "GET",
				success: function(msg){
					console.log($(this).attr('new'));
					$("#display").html(msg);
				},
				error: function(){
					alert("koneksi bermasalah, silahkan reload halaman");				
				}
			});
		});
		
		$('body').on('click',"#utang", function(){
			$(".judul").html("<b>DAFTAR PELANGGAN YANG PUNYA UTANG</b>");
			$("#semua").html("<b>tampilkan semua daftar pelanggan<b>");
			$(".checkbox-inline, #custom, #costum_label").hide();
			$.ajax({
				url: "/get_data_profil.php?act=FILTER&filter=UTANG",
				type: "GET",
				success: function(msg){
					console.log($(this).attr('new'));
					$("#display").html(msg);
				},
				error: function(){
					alert("koneksi bermasalah, silahkan reload halaman");				
				}
			});
		});
		
		$('body').on('click',"#approve", function(){
			$(".judul").html("<b>DAFTAR PELANGGAN YANG BELUM DIAPPROVE</b>");
			$("#semua").html("<b>tampilkan semua daftar pelanggan<b>");
			$(".checkbox-inline, #custom, #costum_label").hide();
			$.ajax({
				url: "/get_data_profil.php?act=APPROVE&filter=approval",
				type: "GET",
				success: function(msg){
					console.log($(this).attr('new'));
					$("#display").html(msg);
				},
				error: function(){
					alert("koneksi bermasalah, silahkan reload halaman");				
				}
			});
		});
		
		$('body').on('click',"#edit", function(){
			$(".judul").html("<b>DAFTAR PELANGGAN YANG SEDANG DI EDIT</b>");
			$("#semua").html("<b>tampilkan semua daftar pelanggan<b>");
			$(".checkbox-inline, #custom, #costum_label").hide();
			$.ajax({
				url: "/get_data_profil.php?act=APPROVE&filter=edit",
				type: "GET",
				success: function(msg){
					console.log($(this).attr('new'));
					$("#display").html(msg);
				},
				error: function(){
					alert("koneksi bermasalah, silahkan reload halaman");				
				}
			});
		});
		
		$('body').on('click',"#new", function(){
			$(".judul").html("<b>DAFTAR CALON PELANGGAN</b>");
			$("#semua").html("<b>tampilkan semua daftar pelanggan<b>");
			$(".checkbox-inline, #custom, #costum_label").hide();
			$.ajax({
				url: "/get_data_profil.php?act=APPROVE&filter=new",
				type: "GET",
				success: function(msg){
					console.log($(this).attr('new'));
					$("#display").html(msg);
				},
				error: function(){
					alert("koneksi bermasalah, silahkan reload halaman");				
				}
			});
		});
		
		$('body').on('click',"#valid", function(){
			$(".judul").html("<b>DAFTAR PELANGGAN VALID</b>");
			$("#semua").html("<b>tampilkan semua daftar pelanggan<b>");
			$(".checkbox-inline, #custom, #costum_label").hide();
			$.ajax({
				url: "/get_data_profil.php?act=APPROVE&filter=valid",
				type: "GET",
				success: function(msg){
					console.log($(this).attr('new'));
					$("#display").html(msg);
				},
				error: function(){
					alert("koneksi bermasalah, silahkan reload halaman");				
				}
			});
		});
		
		
		
		$('body').on('click','.lihat_approval', function(){
			$.ajax({
				url: "/get_data_profil.php?id=" + $(this).attr('ticket_id') + "&act=APPROVAL",
				type: "GET",
				success: function(msg){	
					$("#showAPP").html(msg);
				},
				error: function(){
					alert("koneksi bermasalah, silahkan reload halaman");				
				}
			});
		});
		
		$('body').on('click','.dis_approval', function(){
			$.ajax({
				url: "/get_data_profil.php?id=" + $(this).attr('ticket_id') + "&act=APPROVAL",
				type: "GET",
				success: function(msg){	
					$("#showAPP").html(msg);
				},
				error: function(){
					alert("koneksi bermasalah, silahkan reload halaman");				
				}
			});
		});
		
		$('body').on('click', '.edit_profil', function(){
			var id = $(this).attr('profile_id');
			uiconfirm("Mengedit profil ini berarti merubah status pelanggan menjadi edit, Apakah anda yakin untuk mengedit Profil Pelanggan ini?",
				function(){
					$.ajax({
						type: "POST",
						url: "/_ADM/crud_profile.php?act=PROFIL&action=EDIT&id=" +id,
						success: function(msg){						
								if (msg.indexOf("Error") != -1){
									uialert(msg);
									return false;
								}
								else {
									window.location = "m_detailpelanggan.php?id=" + id;									
								}
						},
						error: function(jqXHR, textStatus, errorThrown) {								
							uialert('Error status code: '+jqXHR.status + '\n' +errorThrown + '\n' + 'Response: ' + jqXHR.responseText);					
						}
					});
				});		
		});
		$('body').on('click', '.validasi_profil', function(){
			var id = $(this).attr('profile_id');
			uiconfirm('Apakah anda yakin akan memvalidasi profil ini? ', function() {
			$.ajax({
				type: "POST",
				url: "/_ADM/crud_profile.php?action=VALIDASI&id=" +id,
				success: function(msg){						
					if (msg != 0){
						uialert(msg);
						
					}
					else{
					window.location = "m_detailpelanggan.php?id=" + id;
					}
				},
				error: function(jqXHR, textStatus, errorThrown) {								
					uialert('Error status code: '+jqXHR.status + '\n' +errorThrown + '\n' + 'Response: ' + jqXHR.responseText);					
				}
			});
			});
		});
		
		
		
		//$('body').on('keydown','input,select',  function(e) {
		//	var key = e.charCode ? e.charCode : e.keyCode ? e.keyCode : 0;
		//	if(key == 13) {
		//		e.preventDefault();
		//		var inputs = $(this).closest('form').find(':input:visible');
		//		inputs.eq( inputs.index(this)+ 1 ).focus();
		//	}
		//});
	    function filterOptions(elems, key, val){
			$(elems).find('option:disabled').show();
			 $(elems).find('option').each(function(i,elem){			 
				$(this).prop( "disabled", ($(this).attr(key) != val));
			 });						 
			$(elems).find('option:disabled').hide();
			$(elems).val($(elems).find('option:enabled:first').val());
			return true;
		}
		
				
		var onChange_Regional = function(){		   
			var regional_id = $('#regional option:selected').val();
            if (filterOptions($('#wilayah'), 'MKTREGIONAL_ID', regional_id)){
				$("#wilayah").val($("#wilayah option:enabled:first").val());
				onChange_Wilayah();
			}
		};
		var onChange_Wilayah = function(){		   
			var wilayah_id = $('#wilayah option:selected').val();
            if (filterOptions($('#propinsi'), 'MKTWILAYAH_ID', wilayah_id)){
				$("#propinsi").val($("#propinsi option:enabled:first").val());
				onChange_Propinsi();
			}
		};
		var onChange_Propinsi = function(){		   
			var propinsi_id = $('#propinsi option:selected').val();
            if (filterOptions($('#area'), 'MKTPROPINSI_ID', propinsi_id)){
				$("#area").val($("#area option:enabled:first").val());
			}
		};
		
		var cekEmail = function(){
			var returnvalue = 0;
			var email = $('#email').val();
			var email_split = email.split(",");
			var i;
			for (i = 0; i < email_split.length; i++){
				var atpos = email_split[i].indexOf("@");
				var dotpos = email_split[i].indexOf(".");
				if(atpos < 1 || dotpos < atpos+2 || dotpos+2 >= email_split[i].length)
				{
					returnvalue = "Email yang dimasukan tidak valid";
				}
			}
			return returnvalue;
		};
		
		var findLocation = function(){
			if (navigator && navigator.geolocation){	
				navigator.geolocation.getCurrentPosition(function(position){
					$('#gps').val(position.coords.latitude + ',' + position.coords.longitude);
				},
				function(msg){
					uialert(typeof msg == 'string' ? msg : "Error")
				});
			}
			else {
				uialert("Error: Not Supported");
			}
		}
		
		
		
		
		var fields_Profil = [
			{ 	f_id:"profile_id", f_name: "profile_id", f_type: "hidden",  f_value: ""},
			{ 	f_id:"kode",  f_name: "kode", f_type: "general", f_placeholder: "Masukkan kode pelanggan", 
				f_required: true, errorMessage: "Kolom ini wajib diisi",f_value: '', 
				f_label: "Kode <span style=\"color : red\">*</span>"
			},			
			{ 	f_id:"nama", f_name: "nama", f_type: "general", f_placeholder: "Masukkan nama pelanggan", 
				f_required: true, errorMessage: "Kolom ini wajib diisi",
				f_value: '', f_label: "Nama <span style=\"color : red\">*</span>"
			},
			{ 	f_id:"contactperson", f_name: "contactperson", f_type: "general", f_placeholder: "Masukkan nama pemilik badan usaha", 
				f_required: true, errorMessage: "Kolom ini wajib diisi",
				f_value: '', f_label: "Nama Pemilik <span style=\"color : red\">*</span>"
			},
			{ 	f_id:"nocp", f_name: "nocp", f_type: "general", f_placeholder: "Masukkan nomor telepon pemilik yang bisa dihubungi", 
				errorMessage: "Kolom ini wajib diisi",
				f_value: '', f_label: "No Telp. Pemilik", f_type: "number"
			},
			{ 	f_id:"regional", f_name: "regional", f_type: "list", f_value: "<? echo $regionalid; ?>", f_label: "Regional",
			    f_listType: "table", f_listKey: "MKTREGIONAL_ID", f_list: "REGIONAL",
				f_options: <? echo $select_regional; ?>, f_onChange: onChange_Regional 
			},
			{ 	f_id:"wilayah", f_name: "wilayah", f_type: "list", f_value: "<? echo $wilayahid; ?>", f_label: "Wilayah",
			    f_listType: "table", f_listKey: "MKTWILAYAH_ID", f_list: "WILAYAH",
				f_options: <? echo $select_wilayah; ?>, f_onChange: onChange_Wilayah 
			},
			{ 	f_id:"propinsi", f_name: "propinsi", f_type: "list", f_value: "<? echo $propinsiid; ?>", f_label: "Propinsi",
			    f_listType: "table", f_listKey: "MKTPROPINSI_ID", f_list: "PROPINSI",
				f_options: <? echo $select_propinsi; ?>, f_onChange: onChange_Propinsi 
				},
			{ 	f_id:"area", f_name: "area", f_type: "list", f_value: "<? echo $areaid; ?>", f_label: "Area",
			    f_listType: "table", f_listKey: "MKTAREA_ID", f_list: "AREA",	f_options: <? echo $select_area; ?>},
			{ 	f_id:"gps",  f_name: "gps", f_type: "list", f_value: '', f_label: "GPS", f_listType: "table", f_listKey: "GPS", f_list: "NAMA", f_options: <? echo $select_gps; ?>
			},
			{ 	f_id:"email",  f_name: "email", f_type: "general", f_placeholder: "Masukkan email", 
				f_value: '', f_label: "Email", f_funcvalidate: cekEmail
			},
			{ 	f_id:"npwp",  f_name: "npwp", f_type: "general", f_placeholder: "Masukkan NPWP", 
				f_value: '', f_label: "NPWP"
			},
			{ 	f_id:"tipe", f_name: "tipe", f_type: "list", f_value: "", f_label: "Tipe",
			    f_listType: "table", f_listKey: "AGENTIPE_ID", f_list: "TIPE",
				f_options: <? echo $select_tipe; ?>
			},
			
			
			
		];
		$('#tambahdata').on('click', function(){			
				$('#formModal').html(buildForm('PROFIL','Tambah Pelanggan','ADD', fields_Profil));
				//$('#regional').val($("#regional option:enabled:first").val());
				filterOptions($('#wilayah'),'mktregional_id', $('#regional').val());
				filterOptions($('#propinsi'),'mktwilayah_id', $('#wilayah').val());
				filterOptions($('#area'),'mktpropinsi_id', $('#propinsi').val());		
				//$('#area').val($("#area option:enabled:first").val());
		});
		
		
		
		$('#modalDialog').on('click', '#btn_saveModal', function(){	
			var act = $('#modalDialog').attr('act');
			var inputFields = fields_Profil;
		    var status  = $('#modalDialog').attr('status');
			if (!validateForm(inputFields)){return;}			
			else {
				uiconfirm('Apakah anda yakin untuk menambah pelanggan baru ?', function() {
					$.ajax({
						type: "POST",
						url: "/_ADM/crud_profile.php?act=" + act +"&action=" + status,
						data: $('#formModal').serialize(),
						success: function(msg){						
								if (msg.indexOf("Error") != -1){
									alert(msg);
									return false;
								}
								$('#modalDialog').modal('hide');
								window.location = "m_detailpelanggan.php?id=" + msg;
								
						},
						error: function(jqXHR, textStatus, errorThrown) {								
							alert('Error status code: '+jqXHR.status + '\n' +errorThrown + '\n' + 'Response: ' + jqXHR.responseText);					
						}
					});
				});
			}
		});
	});
			

		
		
		</script>
		
	</body>
</html>
<?php
}
else{
header('location:/lock.php?logingagal');
}
?>