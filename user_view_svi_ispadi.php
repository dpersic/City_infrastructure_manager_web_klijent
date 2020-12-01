<?php

include_once("dbc.php");
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



    <link rel="stylesheet" type="text/css" href="css/script.css">
    <script src="js/script.js"></script>

</head>

<body>


<div class="container-fluid">
  <header>
    <nav class="navbar navbar-default navbar-fixed-top">
     <div class="container-fluid">
      <div class="topnav" id="myTopnav">
        <a type="button" href="index.php" id="float_right_logout"><span class="glyphicon glyphicon-new-window"></span>Povratak na početnu stranicu</a>
        <a href="user_view.php">Pregled svih današnjih ispada</a>  
        <a href="user_view_svi_ispadi.php" class="active">Pregled svih ispada</a>
       </div>
       </div>
       </nav>
      </div>
</header>


<!----------------------------------------------------------------------TABLE----------------------------------------------------------------------------------------------------->

<div class="container-fluid">
  <div class="row">
    <div class="col-sm-12">
    <div class="container-fluid" style="border: 1px solid black;">
       <div class="col-md-12" style="margin-top:30px;">
        <div class="container-fluid">
        <button  id="dropdown_filter_zupanija_user_view_svi_ispadi" class="btn btn-default dropdown-toggle" style="width: auto; text-align: center; margin-left: 250px"></button>
         <button id="dropdown_filter_vrsta_ispada_user_view_svi_ispadi"  class="btn btn-default dropdown-toggle" style="width: auto; text-align: center;"></button>
        </div>    
      </div>        
            <table class="table" id="Tablica_povijesti_ispada_user_view">
                <thead>
                   <tr>
                      <th>Broj ispada</th>
                      <th>Naziv grada</th>
                      <th>Naziv županije</th>
                      <th>Vrsta ispada</th> 
                      <th>Datum i vrijeme početka ispada</th>
                      <th>Datum i vrijeme završetka ispada</th>                                             
                      <th>Detaljan opis</th>
                      </tr>
                </thead>
            </table>
         </div>
    </div>

<!--------------------------------------------------------------------GOOGLE MAPS------------------------------------------------------------------------------------------------->

     <div class="col-sm-12" style="margin-top: 35px">
            <div class="container-fluid">
                <div id="legend"><h3>Legenda: </h3></div>
            <div id="map_povijest" style="height: 750px;width: 100%;background-color: grey;"><p>Google Map-a</p></div>
        </div>
    </div>


    
    
   
    <script>
      $(document).ready(function(){
        PrikaziPodatkePovijest_user_view();
          mapsLegend();
      });
     
    </script>

<script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAleo4r61WEjsRmizS5SWLMa2k_rnYBDW4&callback=initMap_povijestIspad"></script>

</body>
</html>


