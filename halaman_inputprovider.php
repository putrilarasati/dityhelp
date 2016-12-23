<!DOCTYPE html>
<html lang="en">
<head>
  <title>Halaman Login</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.2/jquery.min.js"></script>
  <script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
</head>
<body>

<div class="container">
  <div class="jumbotron">
    
<ul class="nav nav-tabs">
    <li><a href="#">Home</a></li>
    <li class="active" class="dropdown">
      <a class="dropdown-toggle" data-toggle="dropdown" href="#">Data Provider <span class="caret"></span></a>
      <ul class="dropdown-menu">
        <li><a href="#">Input Data Provider</a></li>
        <li><a href="#">Lihat Data Provider</a></li>                      
      </ul>
    </li>
<li class="dropdown">
      <a class="dropdown-toggle" data-toggle="dropdown" href="#">Data Tagihan <span class="caret"></span></a>
      <ul class="dropdown-menu">
        <li><a href="#">Input Data Tagihan</a></li>
        <li><a href="#">Lihat Data Tagihan</a></li>                      
      </ul>
    </li>
<li class="dropdown">
      <a class="dropdown-toggle" data-toggle="dropdown" href="#">Data Penilaian Performa <span class="caret"></span></a>
      <ul class="dropdown-menu">
        <li><a href="#">Input Data Penilaian Performa</a></li>
        <li><a href="#">Lihat Data Penilaian Performa</a></li>                      
      </ul>
    </li>
  </ul>

</div>
<h4>Silahkan masukkan data provider pada formulir dibawah</h4>
<form class="form-inline" role="form">
    <div class="form-group">
      <label for="focusedInput">ID Toko</label>
      <input class="form-control" id="focusedInput" type="text">
    </div>
<br>
<br>
<div class="form-group">
      <label for="focusedInput">Nama Toko</label>
      <input class="form-control" id="focusedInput" type="text">
    </div>
<br>
<br>
<div class="form-group">
      <label for="focusedInput">Alamat Toko</label>
      <input class="form-control" id="focusedInput" type="text">
    </div>
<br>
<br>
<form role="form">
    <div class="form-group">
      <label for="sel1">Cabang:</label>
      <select class="form-control" id="sel1">
        <option>1</option>
        <option>2</option>
        <option>3</option>
        <option>4</option>
      </select>
</div>
<br>
<br>
</form>
<form role="form">
    <div class="form-group">
      <label for="sel1">Provider:</label>
      <select class="form-control" id="sel1">
        <option>1</option>
        <option>2</option>
        <option>3</option>
        <option>4</option>
      </select>
</div>
</form>

<form>
<div>
    <button type="submit" class="btn btn-default">Submit</button>
  </form>
</div>
</body>
</html>