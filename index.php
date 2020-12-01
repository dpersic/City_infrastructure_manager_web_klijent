<?php

include_once("dbc.php");
 session_start();
 

?>

<!DOCTYPE html>
<html lang="hr">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="content-type" content="text/html; charset=UTF-8" />
    <title>City Infrastructure Manager</title>
    <link rel="stylesheet" type="text/css" href="css/style.css">
    <link href="https://fonts.googleapis.com/css?family=Roboto:100,100italic,300,300italic,400,400italic,500,500italic,700,700italic,900,900italic" rel="stylesheet" type="text/css" />
    <link href="assets/plugins/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet" />
    <link href="assets/plugins/font-awesome/5.0/css/fontawesome-all.min.css" rel="stylesheet" />
    <link href="assets/plugins/DataTables/media/css/dataTables.bootstrap.min.css" rel="stylesheet" />
    <link href="assets/plugins/DataTables/extensions/Buttons/css/buttons.bootstrap.min.css" rel="stylesheet" />
    <link href="assets/plugins/DataTables/extensions/Responsive/css/responsive.bootstrap.min.css" rel="stylesheet" />
    <link href="assets/plugins/bootstrap-eonasdan-datetimepicker/build/css/bootstrap-datetimepicker.min.css" rel="stylesheet" />
    <link href="https://cdn.datatables.net/1.10.20/css/jquery.dataTables.min.css">
    <link href="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js">
    

    <script src="assets/plugins/jquery/jquery-3.2.1.min.js"></script>
    <script src="assets/plugins/jquery-ui/jquery-ui.min.js"></script>
    <script src="assets/plugins/bootstrap/3.3.7/js/bootstrap.min.js"></script>

    <script src="assets/plugins/bootstrap-daterangepicker/moment.js"></script>
    <script src="assets/plugins/moment/moment-with-locales.min.js"></script>
    <script src="assets/plugins/bootstrap-eonasdan-datetimepicker/build/js/bootstrap-datetimepicker.min.js"></script>
  
    <script src="assets/plugins/DataTables/media/js/jquery.dataTables.min.js"></script>
    <script src="assets/plugins/DataTables/media/js/dataTables.bootstrap.min.js"></script>
    <script src="assets/plugins/DataTables/extensions/Buttons/js/dataTables.buttons.min.js"></script> 
    <script src="assets/plugins/DataTables/extensions/Buttons/js/buttons.colVis.min.js"></script>
    <script src="assets/plugins/DataTables/extensions/Buttons/js/buttons.bootstrap.min.js"></script>
    <script src="assets/plugins/DataTables/extensions/Buttons/js/buttons.print.min.js"></script>
    <script src="assets/plugins/DataTables/extensions/Responsive/js/dataTables.responsive.min.js"></script>
    <script src="assets/plugins/DataTables/extensions/Buttons/js/jszip.min.js"></script>
    <script src="assets/plugins/DataTables/extensions/Buttons/js/buttons.html5.min.js"></script>
    <script src="assets/plugins/moment/moment.min.js"></script>

    <link rel="stylesheet" type="text/css" href="css/login_style.css">
    <script src="js/login.js"></script>
  

<div calss="content">
<div class="container-fluid">
    <div class="loginbox">
    <img src="css/avatar2.png" class="avatar">
        <h1>Prijava</h1>
        <form>
            <p>Korisničko ime</p>
            <input type="text" id="korisnicko_ime" placeholder="Unesite korisničko ime">
            <p>Lozinka</p>
            <input type="password" id="lozinka" placeholder="Unesite lozinku">
            <button type="button" class="btn btn-block" id="login" value="Prijavi se" onclick="Login()">Prijavi se</button>
            <button type="button" class="btn btn-block" id="login"value="Nastavi kao gost" onclick="Guest()">Nastavi kao gost</button>
        </form>

        
    </div>
    </div>
</div>
 <div class="loader-wrapper">
      <span class="loader"><span class="loader-inner"></span></span>
    </div> 
</body>

</head>
</html>

