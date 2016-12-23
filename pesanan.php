<?php
session_start();
include_once "../class.eyemysqladap.inc.php";
include_once "../class.MultiGrid.inc.php";
include_once '_conn/query.php';
include_once '_conn/select.php';
include_once "../class.eyemysqladap.inc.php";



if(!empty($_SESSION['alamat_email'])){

if(isset($_GET['limit'])){	
$limit = $_GET['limit']; 	
}
	
function createLookup($db,$table,$fields,$filter){
	$tbl_list = new query($db, $table);
	$list = $tbl_list->select($fields, $filter);
	$list = iterator_to_array($list);
	return json_encode($list);	
}
	
$pelanggan = createLookup("PROFILE", "PROFILE.PROFILE P INNER JOIN PROFILE.SYSTEM_PROFILE S ON P.PROFILE_ID = S.PROFILE_ID", 
						 "DISTINCT P.NAMA, P.PROFILE_ID", "WHERE S.SYSTEM_ID = '2' AND P.AKTIF = 'Y' AND P.KODE !='' ORDER BY P.NAMA");
	
$select_area = createLookup("MARKETING", 'MARKETING.MKT_AREA MA INNER JOIN MARKETING.AGEN_AREA AA ON
							MA.MKTAREA_ID = AA.MKTAREA_ID',"MA.MKTAREA_ID, MA.AREA, AA.KONTAK_ID",
							'WHERE MA.TGL_SELESAI IS NULL OR MA.TGL_SELESAI LIKE "%0000%"');
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
	
ul.ui-autocomplete.ui-menu {
		z-index: 5000;
	}
</style>
		
<?php 
	include "modal.html"; 
	include "html_showapproval.html"; 
?>
<div class="navbar">
	<div class="row">
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
						<span style="float:left;"><a href="/index_ADM.php">Home</a>> Transaksi	> <b>Penjualan</b></span><br>
						
					</div>
			  		<div class="panel-body">
			  		<div id="wrapper">
					
			
										<?php 
											$cek_kode = substr($_SESSION['kode_dept'],0,3);	
											//print($cek_kode);
										?>
					<div id="sidebar-wrapper">
						
					</div>
					<div id="page-content-wrapper">	
					<div class="col-md-12 col-sm-12">
					
					<div class="row" style="padding: 5px 10px 5px 10px">
					<div id="topdiv" class="col-md-12"></div>
					<div class="panel panel-default">
					<div class="panel-heading"><i class="fa fa-user"></i><span class="judul">Informasi Pemesanan</span></div>
						
							<div class="panel-body">
								<form id="formPesanan" class="form-horizontal center-block" action="#" role="form" style="font-size:14px">          
		   						<div class="col-md-6">
								<div class="form-group">
									<label class="col-sm-4 control-label" id="pelanggan_l">Pelanggan <span style="color : red">*</span></label>
									<div class="col-sm-8">
									<select id="pelanggan" name="pelanggan"  class="form-control" required>
										<option value="Blokir">Pilih Blokir</option>
									</select>
									</div>
								</div>	
								
								<div class="form-group">
									<label class="col-sm-4 control-label" id="sub_l">Pilih Gudang</label>
									<div class="col-sm-8">
									<select id="gudang" name="gudang"  class="form-control">
										<option value="">SM</option>
									</select>
									</div>
								</div>
															
								<div class="form-group">
									<label class="col-sm-4 control-label" id="tanggalKirim_l">Tanggal Kirim <span style="color : red">*</span></label>
									<div class="col-sm-8">
										<input type="text" data-date-format="dd-mm-yyyy" placeholder="Masukan tanggal rencana pengiriman" name="tanggalKirim" id="tanggalKirim" class="form-control datetime" 
											   value="" required>
										
									</div>
								</div>
									
								<div class="form-group">
									<label class="col-sm-4 control-label" id="alamat_l">Alamat Kirim <span style="color : red">*</span></label>
									<div class="col-sm-8">
									<select id="alamat" name="alamat"  class="form-control" required>
										<option value="">Pilih Nama Alamat</option>
									</select>
									</div>
								</div>
									 
								<div class="form-group">
									<label class="col-sm-4 control-label" id="keterangan_l">Keterangan</label>
									<div class="col-sm-8">
										<textarea placeholder="Pilih jenis alamat terlebih dahulu" rows="4" id="keterangan" name="keterangan" class="form-control"></textarea>
									</div>
								</div>	
																
								</div>
								<div class="col-md-6">
									<div class="form-group">
										<label class="col-sm-4 control-label" id="area_l">Area</label>
										<div class="col-sm-8">
											<input type="text" placeholder="" id="area" name="area" class="form-control" value="Surabaya" disabled="yes">
										</div>
									</div>
									
									<div class="form-group">
										<label class="col-sm-4 control-label" id="gudang_l">TOP Pelanggan</label>
										<div class="col-sm-8">
											<input type="text" placeholder="Masukkan negara" id="top" name="top" class="form-control" value="CASH" disabled="yes">
										</div>
									</div>	
									
									<div class="form-group">
										<label class="col-sm-4 control-label" id="gudang_l">Sisa Limit</label>
										<div class="col-sm-8">
											<input type="text" placeholder="Masukkan negara" id="limit" name="limit" class="form-control" value="CASH" disabled="yes">
										</div>
									</div>
										
									<div class="form-group">
										<label class="col-sm-4 control-label" id="detailAlamat_l">Detail Alamat</label>
										<div class="col-sm-8">
											<textarea placeholder="Pilih jenis alamat terlebih dahulu" rows="4" id="detailAlamat" name="detailAlamat" class="form-control" disabled="yes"></textarea>
										</div>
									</div>
								
								</div>
								</form>
							</div>
						
					
					</div>
					
					<div class="panel panel-default">
						<div class="panel-heading"><i class="fa fa-user"></i><span class="judul">Informasi Ekspedisi</span></div>
						<div class="panel-body">
							<form id="formPengiriman" class="form-horizontal col-md-12 center-block" action="#" role="form" style="font-size:14px"> 
							<div class="col-md-6">
												
							<div class="form-group">
									<label class="col-sm-4 control-label" id="ekspedisi_l">Pilih Ekspedisi</label>
									<div class="col-sm-8">
									<select id="ekspedisi" name="ekspedisi"  class="form-control">
										<option value="">Ekspedisi 1</option>
									</select>
									</div>
							</div>
							
							<div class="form-group">
									<label class="col-sm-4 control-label" id="alamatEkspedisi_l">Alamat Ekspedisi</label>
									<div class="col-sm-8">
									<select id="alamatEkspedisi" name="alamatEkspedisi"  class="form-control">
										<option value="">Alamat 1</option>
									</select>
									</div>
							</div>	
							</div>
							<div class="col-md-6">
								
								<div class="form-group">
									<label class="col-sm-4 control-label" id="teleponEkspedisi_l">No. Telepon Ekspedisi</label>
									<div class="col-sm-8">
									<input type="text" placeholder="Masukkan negara" id="teleponEkspedisi" name="teleponEkspedisi" class="form-control" value="080998" disabled="yes">
									</div>
								</div>
																								
								<div class="form-group">
									<label class="col-sm-4 control-label" id="detailAlamatEkspedisi_l">Detail Alamat Ekspedisi</label>
									<div class="col-sm-8">
										<textarea placeholder="Pilih jenis alamat ekspedisi terlebih dahulu" rows="4" id="detailAlamatEkspedisi" name="detailAlamatEkspedisi" class="form-control" disabled="yes"></textarea>
									</div>
								</div>
								
							</div>
							</form>
						</div>
						
					</div>	
						
					<div class="panel panel-default" id="bonus_paketan">
						<div class="panel-heading"><i class="fa fa-user"></i><span class="judul">Informasi Bonus Paketan</span>
						</div>
						<div class="panel-body">
							<table class="table table-striped table-bordered table-hover col-md-12" id="myBonus_table">
								<thead>
									<tr>
										<th class="col-md-3" style="vertical-align: middle; text-align:center;">Item Bonus</th>
										<th class="col-md-3" style="vertical-align: middle; text-align:center;">Nama Paketan</th>
										<th class="col-md-1" style="vertical-align: middle; text-align:center;">Jumlah</th>
										<th class="col-md-2" style="vertical-align: middle; text-align:center;">Tanggal Expired</th>
										<th class="col-md-1" style="vertical-align: middle; text-align:center;">Opsi</th>
									</tr>
								</thead>
								<tbody>
									<tr>
										<td class="one">YY HB B ISI 20 LUSIN</td>
										<td class="two">LOPE</td>
										<td class="three">2</td>
										<td class="four">12 Jul 2016</td>
										<td class="five" style="vertical-align: middle; text-align:center;"><a id="tambah_bonus" class="glyphicon glyphicon-plus" style="font-size: 18px;"></a></td>
									</tr>
									<tr>
										<td class="one">YY GEGE B ISI 20 LUSIN</td>
										<td class="two">LOPE</td>
										<td class="three">2</td>
										<td class="four">12 Jul 2016</td>
										<td class="five" style="vertical-align: middle; text-align:center;"><a id="tambah_bonus" class="glyphicon glyphicon-plus" style="font-size: 18px;"></a></td>
									</tr>
								<tbody>
							</table>
						</div>
					</div>
					
					<div class="panel panel-default">
						<div class="panel-heading"><i class="fa fa-user"></i><span class="judul">Item yang Dipesan</span>
							<a id="bonus" href="#modalDialog" data-toggle="modal">   + Tambahkan item Bonus</a>
						</div>
						<div class="panel-body">
						<form id="formPesanan" class="form-horizontal center-block" action="#" role="form" style="font-size:14px"> 
								
							<table class="table table-striped table-bordered table-hover col-md-12" id="myTable">
								<thead>
									<tr>
										<th class="col-md-3" style="vertical-align: middle; text-align:center;">Produk</th>
										<th class="col-md-1" style="vertical-align: middle; text-align:center;">Jumlah</th>
										<th class="col-md-1" style="vertical-align: middle; text-align:center;">Sub Total</th>
										<th class="col-md-2" style="vertical-align: middle; text-align:center;">Bonus</th>
										<th class="col-md-1" style="vertical-align: middle; text-align:center;">Status</th>
										<th class="col-md-3" style="vertical-align: middle; text-align:center;">Keterangan</th>
										<th class="col-md-1" style="vertical-align: middle; text-align:center;">Opsi</th>
									</tr>
								</thead>
								<tbody>
									<tr>
										<td ><select class="form-control" id="sel_0" title="item_beli">
											 <option>item 1</option>
											 <option>item 2</option>
											 <option>item 3</option>
											 <option>item 4</option>
										   </select>
										</td >
										<td><input type="number" class="form-control" id="jumlah" placeholder="" value="0"></td>
										<td style="vertical-align: middle;">Rp 65.000.000</td>
										<td ><select class="form-control" id="bnp_0" title="Bonus">
											 <option selected>Bukan Bonus</option>
											 <option>Paketen</option>
											 <option>BNP</option>
										   </select>
										</td >
										<td style="vertical-align: middle;">CONFIRM</td>
										<td><input type="text" class="form-control" id="keterangan"></td>
										<td style="vertical-align: middle; text-align:center;"><label class="control-label"><a id="delete_row" class="glyphicon glyphicon-trash" style="font-size: 18px;"></a></label></td>
									</tr>
								</tbody>
							</table>
							<table class="table table-hover borderless table-responsive">
							<?php if($limit == 1){?>
									<tr>
										<td class="col-md-2"><strong>Total Karung</strong></td>
										<td id="profile_kode" class="col-md-4">150</td>
										<td class="col-md-2"><strong>Sisa Limit Karung</strong></td>
										<td class="col-md-4"><span style="color: red;">-8</span></td>
									</tr>
									<tr>
										<td class="col-md-2"><strong>Total Tagihan</strong></td>
										<td id="profile_kode" class="col-md-4" >Rp. 300.000.000</td>
										<td class="col-md-2"><strong>Sisa Limit Kredit</strong></td>
										<td class="col-md-4"><span style="color: red;">Rp. -200.000.000</span></td>
									</tr>
							<?php }
								else if($limit == 2){ ?>
									<tr>
										<td class="col-md-2"><strong>Total Karung</strong></td>
										<td id="profile_kode" class="col-md-4">150</td>
										<td class="col-md-2"><strong>Sisa Limit Karung</strong></td>
										<td class="col-md-4"><span style="color: blue;">8</span></td>
									</tr>
									<tr>
										<td class="col-md-2"><strong>Total Tagihan</strong></td>
										<td id="profile_kode" class="col-md-4" >Rp. 300.000.000</td>
										<td class="col-md-2"><strong>Sisa Limit Kredit</strong></td>
										<td class="col-md-4"><span style="color: blue;">Rp. 200.000.000</span></td>
									</tr>
							<?php }else{?>
									<tr>
										<td class="col-md-2"><strong>Total Karung</strong></td>
										<td id="profile_kode" class="col-md-4">150</td>
										<td class="col-md-2"><strong>Sisa Limit Karung</strong></td>
										<td class="col-md-4">CASH</td>
									</tr>
									<tr>
										<td class="col-md-2"><strong>Total Tagihan</strong></td>
										<td id="profile_kode" class="col-md-4" >Rp. 300.000.000</td>
										<td class="col-md-2"><strong>Sisa Limit Kredit</strong></td>
										<td class="col-md-4">CASH</td>
									</tr>
							<?php }?>
							</table>
							<div align="center">
							<a id="btn_repeat" class="btn btn-primary" aria-hidden="true" href="pesanan_repeat.php">Buat Repetitive Order</a>
							</div>
						</form>
						</div>
					</div>
						
					<div align="right">
						<button id="btn_save" class="btn btn-primary" aria-hidden="true">Save</button>
						<button id="reset" class="btn btn-link">Reset</button>
					</div>
						
					</div>
					</div>
					</div>
					</div>			
			  		</div>
						
			
			
							
					
					</div>
			</div>
   		</div>
	</div>
</div>
		
<?php include "../_template/navbar_footer.php"; ?>
		
<script src="/js/jquery-ui/jquery-ui.js"></script>
<script type="text/javascript" src="/js/modal.js"></script>
<script type="text/javascript" src="/js/jquery-mockjax/jquery.mockjax.js"></script>
<script type="text/javascript" src="/js/bootstrap-datepicker/bootstrap-datepicker.js"></script>
		
		
</body>
</html>

<script>
var sel = 0;
	
//function load(){
	//alert("Page Loaded");
//	var options = <?php echo $pelanggan;?>;
//	var list_keys = Object.keys(options[0]);
//	list_keys = list_keys.splice(2, list_keys.length-2);
	//console.log(options[0]['PROFILE_ID']);
//	for(var item in options){
//		html +='<option value="'+options[item]['PROFILE_ID']+'">'+options[item]['NAMA']+'</option>';
//	}
	
//}
	
$(function() {
	//alert("Page Loaded");
	
	var options = <?php echo $pelanggan;?>;
	var list_keys = Object.keys(options[0]);
	var html = "";
	list_keys = list_keys.splice(2, list_keys.length-2);
	//console.log(options[0]['PROFILE_ID']);
	for(var item in options){
		html +='<option value="'+options[item]['PROFILE_ID']+'">'+options[item]['NAMA']+'</option>';
	}
	$('#pelanggan').append(html);
	//console.log(html);
	
	$('body').on('focus',".datetime", function(){
		$(this).datepicker();				
	});	
	
	//$('#area_l').hide();
	//$('#area').hide();
	$('#bonus_paketan').hide();
	
	
	
	$('body').on('change',"#pelanggan", function(){
		var pelanggan = $('#pelanggan option:selected').val();
		var area_list = <?php echo $select_area;?>;
		var area_pelanggan = "";
		console.log(area_list[0]['KONTAK_ID']);
		for (var item in area_list){
			if(pelanggan == area_list[item]['KONTAK_ID']){
				area_pelanggan = area_list[item]['AREA'];
				break;
			}
			else{
				area_pelanggan = "TIDAK ADA DATA AREA PELANGGAN";
			}
		}
		$('#area').val(area_pelanggan);
		$('#area_l').show();
		$('#area').show();
		$('#bonus_paketan').show();
	});
	
	$('body').on('change', "[title='item_beli']:last", function(){
		sel = sel + 1;
		//alert(sel);
		var x = $('#myTable tr').length;
		var row = `
									<tr>
										<td ><select class="form-control" id="sel_`+ sel +`" title="item_beli">
											 <option>item 1</option>
											 <option>item 2</option>
											 <option>item 3</option>
											 <option>item 4</option>
										   </select>
										</td >
										<td><input type="number" class="form-control" id="usr" placeholder="" value="0"></td>
										<td>Rp 65.000.000</td>
										<td ><select class="form-control" id="bnp_0" title="Bonus">
											 <option selected>Bukan Bonus</option>
											 <option>Paketen</option>
											 <option>BNP</option>
										   </select>
										</td >
										<td style="vertical-align: middle;">CONFIRM</td>
										<td><input type="text" class="form-control" id="keterangan"></td>
										<td style="vertical-align: middle; text-align:center;"><label class="control-label"><a id="delete_row" class="glyphicon glyphicon-trash" style="font-size: 18px;"></a></label></td>
									</tr>
        `;
		$('#myTable > tbody:last-child').append(row);
		console.log(row);
	});
	
	$('body').on('click', '#delete_row', function(){
		//if(sel > 0){
		//	$(this).closest('tr').remove();
		//}
		var row =`
									
										<td ><select class="form-control" id="sel_`+ sel +`" title="item_beli">
											 <option>item 1</option>
											 <option>item 2</option>
											 <option>item 3</option>
											 <option>item 4</option>
										   </select>
										</td >
										<td><input type="number" class="form-control" id="usr" placeholder="" value="0"></td>
										<td>Rp 65.000.000</td>
										<td ><select class="form-control" id="bnp_0" title="BNP">
											<option selected>Bukan Bonus</option>
											 <option>TIDAK</option>
											 <option>YA</option>
										   </select>
										</td >
										<td style="vertical-align: middle;">CONFIRM</td>
										<td><input type="text" class="form-control" id="keterangan"></td>
										<td style="vertical-align: middle; text-align:center;"><label class="control-label"><a id="delete_row" class="glyphicon glyphicon-trash" style="font-size: 18px;"></a></label></td>
									
        `;
		
		$(this).closest('tr').html(row);
	});
	
	$('body').on('click', '#tambah_bonus', function(){
		var value = $(this).closest('tr').children('td.one').text();
		var paketan = $(this).closest('tr').children('td.two').text();
		var jumlah = $(this).closest('tr').children('td.three').text();
		var row = ` <tr>
										<td ><select class="form-control" id="sel_`+ sel +`" title="item_beli" disabled="yes">
											 <option>item 1</option>
											 <option>item 2</option>
											 <option>item 3</option>
											 <option>item 4</option>
											 <option selected="selected">`+ value +`</option>
										   </select>
										</td >
										<td><input type="number" class="form-control" id="usr" placeholder="" value="`+ jumlah +`" disabled="yes"></td>
										<td>0</td>
										<td ><select class="form-control" id="bnp_0" title="BNP" disabled="yes">
											<option>Bukan Bonus</option>
											 <option selected="selected">Paketan</option>
											 <option>BNP/option>
										   </select>
										</td >
										<td style="vertical-align: middle;">OK</td>
										<td><input type="text" class="form-control" id="keterangan" disabled="yes" value="Bonus dari Paketan `+ paketan +`"></td>
										<td style="vertical-align: middle; text-align:center;"><label class="control-label"><a id="delete_row" class="glyphicon glyphicon-trash" style="font-size: 18px;"></a></label></td>
									</tr>


`;
		//alert(jumlah);
		$('#myTable > tbody:last-child').prepend(row);
		$(this).closest('tr').remove();
	});
	
	
	var fields_Profil = [
		{
			f_id:"contactperson", f_name: "contactperson", f_type: "general", f_placeholder: "", 
			f_required: true, errorMessage: "Kolom ini wajib diisi",
			f_value: '', f_label: "Item Bonus"
		},
		{
			f_id:"contactperson", f_name: "contactperson", f_type: "general", f_placeholder: "Jumlah item bonus yang ingin dikirimkan", 
			f_required: true, errorMessage: "Kolom ini wajib diisi",
			f_value: '', f_label: "Jumlah"
		}
	
	];
	
	$('body').on('click', '#btn_save', function(){
		if($('#pelanggan option:selected').val() == "Blokir"){
			uiconfirm("Pelanggan ini berada dalam status blokir, apakah anda yakin untuk mengirimkan email approval?",
				function(){
					window.location = "pesanan_overview.php";
					
		});
	   }
	   else if($('#tanggalKirim').val() == "22-06-2016"){
	   		uiconfirm("Anda menginputkan data penjualan kurang dari H+2 dari tanggal kirim, hal ini akan menyebabkan data penjualan harus divalidasi terlebih dahulu. Apakah anda yakin untuk melanjutkan ?",
				function(){
					window.location = "pesanan_overview.php";
					
		});
	   }
	   else{
	   		uiconfirm("Apakah anda yakin data penjualan yang anda masukkan sudah benar ? (catatan : data yang sudah diinputkan ke system tidak bisa dirubah lagi.)",
				function(){
					window.location = "pesanan_overview.php";
				  
			});	
	   }
	});
	
	$('#bonus').on('click', function(){		
				$('#formModal').html(buildForm('PROFIL','Item Bonus','ADD', fields_Profil));
				
	});
});
</script>
	
	
<?php

}
else{
header('location:/lock.php?logingagal');
}
?>