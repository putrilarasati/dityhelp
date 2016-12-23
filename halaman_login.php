<?php include("koneksi.php");

if(isset($_POST['id_pegawai']) && isset($_POST['pwd']) ) {

if(!empty($_POST['id_pegawai']) && !empty($_POST['pwd']) ) 
 
{

$id_pegawai=$_POST['id_pegawai']; 
$password=$_POST['pwd']; 

$myquery="select * from usertbl where id_pegawai='$id_pegawai' and password='$password' limit 1"; 
$result=mysql_query($myquery) or die (mysql_error()); 
if (mysql_num_rows($result) == 1) { 
//jika account dan password cocok 


 $_SESSION['login']=true; 
 $_SESSION['uname']=$id_pegawai;
 $_SESSION['pswd']=$password;
 while ($row = mysql_fetch_row($result)) {
 
 $nama=$row[1];
 $level=$row[3];
 }
 $_SESSION['nama']=$nama;
 $_SESSION['user_level']=$level;
if  ($_SESSION['user_level']== "cabang") {

 header("location:halaman_utama_cabang.php");}
 else if($_SESSION['user_level']== "ho") {
 header("location:halaman_utama_ho.php");}
 else {
 //header("location:halaman_error.php");
  print $level;
 }
 }
 
 
 
 else { //jika username dan password tidak cocok 
 echo "<h1 align=\"center\">Login Gagal!Coba lagi yaa</h1>"; }
 
 

 }
 else { //jika form kosong munculkan pesan 
 echo "<h1 align=\"center\">Whoops, masih kosong tuh!</h1>"; 
 }
 }
 else {
 ?> 


<!DOCTYPE html>
<html lang="en">
<head>
  <title>Halaman Login</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="bootstrap-3.3.6-dist/css/bootstrap.min.css">
  <script src="bootstrap-3.3.6-dist/js/jquery.min.js"></script>
  <script src="bootstrap-3.3.6-dist/js/bootstrap.min.js"></script>
</head>
<body>
<div class="container">
  <div class="jumbotron">
  <div class="pull-right"><a href="#"><img src="bootstrap-3.3.6-dist/images/images_burned.png" alt="" /></a></div>
<h2>Sistem Pelaporan dan Pengecekan Tagihan Koneksi Internet</h2>

  </div>
     <hr size=2>
    <p>Silahkan melakukan Login terlebih dahulu!</p>
	

  <form  id="FLogin" name="FLogin" method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>">
    <div class="form-group">
      <label for="id_pegawai_l">ID Pegawai:</label>
      <input type="text" class="form-control" id="id_pegawai" name="id_pegawai" placeholder="Masukkan nomor ID pegawai anda">
    </div>
    <div class="form-group">
      <label for="pwd_l">Password:</label>
      <input type="password" class="form-control" id="pwd" name="pwd" placeholder="Masukkan paswword anda">
    </div>
    <div class="checkbox">
      <label><input type="checkbox"> Remember me</label>
    </div>
	 <input type="submit" name="button" id="button" class="btn btn-default" value="Login" />
  </form>
</div>
</body>
</html>
<?php } ?>