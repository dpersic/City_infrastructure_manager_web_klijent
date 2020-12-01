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
    <script src="js/script.js"></script>
    <script src="js/login.js"></script>

</head>
<body>
<div class="container-fluid">
  <header>
    <nav class="navbar navbar-default navbar-fixed-top">
     <div class="container-fluid">
      <div class="topnav" id="myTopnav">      
         <a href="admin_view.php" class="active" id="float_left">Pregled svih današnjih ispada</a>  
         <a href="admin_view_svi_ispadi.php" id="float_left">Pregled svih ispada</a>
         <a href="admin_view_histogram.php" id="float_left">Histogram</a>
         <a type="button" id="float_right_logout" onclick="Logout()" ><span class="glyphicon glyphicon-log-out" ></span>Odjava</a>
       </div>
       </div>
       </nav>
      </div>
</header>
<!----------------------------------------------------------------------TABLE------------------------------------------------------------------------------------>

<div class="container-fluid">
  <div class="row">
    <div class="col-md-12">
    <div class="container-fluid" style="border: 1px solid black;">
      <div class="col-md-12" style="margin-top:30px;">
        <div class="container-fluid">
          <button  id="dropdown_pokusaj" class="btn btn-default dropdown-toggle" style="width: auto; text-align: center; margin-left:250px;"></button>
           <button id="dropdown_pokusaj1"  class="btn btn-default dropdown-toggle" style="width: auto; text-align: center;"></button>
        </div>    
      </div>     
            <table class="table" id="Tablica_ispada">
                <thead>
                   <tr>
                      <th>Broj ispada</th>
                      <th>Naziv grada</th>
                      <th>Naziv županije</th>
                      <th>Vrsta ispada</th> 
                      <th>Datum i vrijeme početka ispada</th>
                      <th>Datum i vrijeme završetka ispada</th>                               
                      <th>Detaljan opis</th>
                      <th>Uredi</th>
                      <th>Obriši</th>
                      </tr>
                </thead>
                <tfoot>
                <tr>
                     
                </tr>
              </tfoot>
            </table>
         </div>
    </div>
  </div>
</div>
<!----------------------------------------------------------------------TABLE------------------------------------------------------------------------------------>
<!--------------------------------------------------------------------GOOGLE MAPS-------------------------------------------------------------------------------->
<div class="col-sm-12" style="margin-top: 35px">
   <div class="container-fluid">
       <div id="legend"><h3>Legenda: </h3></div>
    </div>
    <div class="container-fluid">
        <div id="map" style="height: 750px;width: 100%;background-color: grey;"><p>Google Map-a</p></div>
    </div>
  </div>
<!--------------------------------------------------------------------GOOGLE MAPS-------------------------------------------------------------------------------->
<!--------------------------------------------------------------------MODALS------------------------------------------------------------------------------------->
<div class="modal fade" id="myAddModal" tabindex="-1" role="dialog" aria-labelledby="myAddModalLabel">
        <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title" id="myAddModalLabel">Dodaj novi ispad</h4>
            </div>
            <div class="modal-body">
                <div class="form-group">
                   <div class="form-group">
                      <label for="inptVrstaIspada">Vrsta ispada</label>
                   <select  type="text" id="dropdown_vrsta_ispada" class="form-control" style="width: 100%;">
            <option value="-1">Izaberite vrstu ispada</option>
          </select>
                </div>
          <div class="form-group">
          <label for="inptZupanija">Županija</label>
          <select onchange="ZupanijaChange(true, null);" type="text" id="dropdown_zupanija" class="form-control" style="width: 100%;">
            <option value="-1">Izaberite županiju</option>
          </select>
        </div>
        <div class="form-group">
           <label for="inptGrad">Grad</label>
          <select  type="text" id="dropdown_grad" class="form-control" style="width: 100%;">
            <option value="-1">Izaberite grad</option>
          </select >
                </div>
                    <label for="datetimepickerpocetak1">Datum i vrijeme početka ispada</label>
                    <div class='input-group date' id='datetimepickerpocetak'>
                        <input type='text' class="form-control" />
                        <span class="input-group-addon">
                            <span class="glyphicon glyphicon-calendar"></span>
                        </span>
                    </div>
                </div>
                  <div class="form-group">
                    <label for="datetimepickerzavrsetak">Datum i vrijeme završetka ispada</label>
                    <div class='input-group date' id='datetimepickerzavrsetak'>
                        <input type='text' class="form-control" />
                        <span class="input-group-addon">
                            <span class="glyphicon glyphicon-calendar"></span>
                        </span>
                    </div>
                </div>            
                <div class="form-group">
                    <label for="inptDetaljanOpis">Detaljan opis</label>
                    <input type="text" class="form-control" id="inptOpis">
                </div>
            </div>
            <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Zatvori</button>
            <button type="button" data-dismiss="modal" class="btn btn-primary" onclick="DodajIspad()" >Dodaj</button>
            </div>
        </div>
        </div>
    </div>


 <div class="modal fade" id="myEditModal" tabindex="-1" role="dialog" aria-labelledby="myEditModalLabel">
        <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title" id="myEditModalLabel">Ažuriraj ispad</h4>
            </div>
            <div class="modal-body">               
                <div class="form-group">
                    <label for="dropdown_EditIspad">Vrsta ispada</label>
                    <select id="dropdown_vrsta_ispadaEdit" class="form-control" >
                        <option value="-1">Izaberite vrstu ispada</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="dropdown_EditZupanija">Županija</label>
                    <select onchange="ZupanijaChange(false, null);" class="form-control" id="dropdown_zupanija_edit" style="width: 100%;">
                      <option value="-1">Izaberite županiju</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="dropdown_EditGrad">Grad</label>
                    <select class="form-control" id="dropdown_gradEdit" style="width: 100%;">
                       <option value="-1">Izaberite grad</option>
                     </select>
                </div>              
                 <div class="form-group">
                    <label for="datetimepickerForEdit">Datum i vrijeme završetka ispada</label>
                    <div class='input-group date' id='datetimepickerForEdit' style="width: 100%;">
                        <input type='text' class="form-control" />
                        <span class="input-group-addon">
                            <span class="glyphicon glyphicon-calendar"></span>
                        </span>
                    </div>
                </div>
                 <div class="form-group">
                    <label for="inptEditDetaljanOpis" style="width: 100%;">Detaljan opis</label>
                    <input type="text" class="form-control" id="inptOpisEdit">
                </div>
            </div>
            <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Zatvori</button>
            <button type="button" class="btn btn-primary" id="editButton">Ažuriraj</button>
            </div>
        </div>
        </div>
    </div>

<!--true mi add, false mi je edit-->



    <div class="modal fade" id="myDeleteModal" tabindex="-1" role="dialog" aria-labelledby="myDeleteModalLabel">
        <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title" id="myDeleteModalLabel">Obriši</h4>
            </div>
            <div class="modal-body">
                <div class="form-group">
                   <p>Jeste li sigurni da želite obrisati redak?</p>
                </div>
            </div>
            <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Zatvori</button>
            <button type="button" class="btn btn-primary" id="deleteButton">Obriši</button>
            </div>
        </div>
        </div>
    </div>
  </div>
  
<script>
  $(document).ready(function()
  {
    PrikaziPodatke();
    InitializeDateTimePickerPocetakIspada();
    InitializeDateTimePickerZavrsetakIspada();
    mapsLegend();
});    
</script>
<script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAleo4r61WEjsRmizS5SWLMa2k_rnYBDW4&callback=initMap_trenutanIspad"></script>
</body>
</html>


