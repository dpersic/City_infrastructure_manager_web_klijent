<?php

include_once("dbc.php");
  session_start();
    if(!isset($_SESSION['id']))
    {
      header("location:index.php");
    }
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <title>City infrastructure manager</title>

   
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

     <script src="assets/plugins/bootbox/bootbox.min.js"></script>
    <script src="assets/plugins/bootbox/bootbox.locales.min.js"></script>


    <link rel="stylesheet" type="text/css" href="css/script.css">
    <link rel="stylesheet" type="text/css" href="css/histogram_style.css">
    <script src='https://cdn.plot.ly/plotly-latest.min.js'></script>
    <script src="js/script.js"></script>
     <script src="js/login.js"></script>

</head>
<body>
  <div class="container-fluid">
    <header>
      <nav class="navbar navbar-default navbar-fixed-top">
       <div class="container-fluid">
         <div class="topnav" id="myTopnav">
              <a href="admin_view.php">Pregled svih današnjih ispada</a>  
              <a href="admin_view_svi_ispadi.php">Pregled svih ispada</a>
              <a href="admin_view_histogram.php" class="active">Histogram</a>
               <a type="button" id="float_right_logout" onclick="Logout()" ><span class="glyphicon glyphicon-log-out" ></span>Odjava</a>
             
         </div>
       </div>
      </nav>
    </div>
</header>
<!----------------------------------------------------------------------TABLE----------------------------------------------------------------------------------->
<div class="container-fluid">
 <div id='myDiv'>
</div>


<script>
 $(document).ready(function(){

    Histogram(); 
    
    
  });  
</script>
<script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAleo4r61WEjsRmizS5SWLMa2k_rnYBDW4&callback=initMap_povijestIspad"></script>
</body>
</html>


