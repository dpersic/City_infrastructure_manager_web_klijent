$(document).ready(function(){
   
     $('#myAddModal').on('hidden.bs.modal', function () 
     {
        $("#dropdown_vrsta_ispada").val('-1');
        $(this).find("input[type=text]").val('');
        $("#dropdown_zupanija").val('-1');
        $("#dropdown_grad").val('-1');
        $('#datetimepickerpocetak').data("DateTimePicker").date(moment()); 
        $('#datetimepickerForEdit').data("DateTimePicker").date('');                        
    });
        PrikaziPodatke();
        PrikaziPodatkePovijest();

        initMap_trenutanIspad();
        initMap_povijestIspad();        
        InitializeDateTimePickerPocetakIspada();
        InitializeDateTimePickerZavrsetakIspada(); 
        PrikaziPodatkeUser_view();    
        PrikaziPodatkePovijest_user_view();
       
      });

var dataTableInitialized = false; 
var dataTableInitialized_povijest=false;

var itemsList = [];
var ispadiList=[];

var dataTableInitialized_dasanji_user_view = false;
var dataTableInitialized_povijest_user_view = false;
var ispadiList_danasnji_user_view=[];
var ispadiList_povijest_user_view = [];

var adminID=localStorage.getItem('id_korisnik');
console.log(adminID); //Ispis dohvacenog ID_korisnika.

var oLang="https://cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Croatian.json";
console.log(oLang);


/*---------------------------------------------------------------Postavljanje Mape i markera DANAŠNJIH ISPISA----------------------------------------------------------------------*/
function initMap_trenutanIspad()
{
     $.ajax({
        type: "GET",
        url: 'json.php?action=prikazi_pregled_ne_zavrsenih_ispada',
        success: function (oData){

            //console.log(oData);
            markerList=[];
            oData.forEach(function(ispadi)
            {
                var object = {
                    vrsta_ispada:ispadi.vrsta_ispada,
                    opis: ispadi.opis,
                    grad: ispadi.grad,
                    pocetak_ispada: ispadi.pocetak_ispada,
                    kraj_ispada: ispadi.kraj_ispada,
                    lat: ispadi.lat,
                    lng: ispadi.lng,
                    status: ispadi.status,
                  
                };
                markerList.push(object);
            
            });         

    // Map options
     var options =  {
           zoom:7.52,
           center:{lat:44.5876608,lng:16.7656699}
     }

     for(var i=0;i<markerList.length;i++)
        {
          // console.log('lat'+markerList[i].lat+', lng: '+markerList[i].lng,+' grad '+markerList[i].grad, + ' opis'+markerList[i].opis, markerList[i].vrsta_ispada); //Potrebno pretvoriti u stirng opis i grad
        }

     // New map
     var mapa = new google.maps.Map(document.getElementById('map'), options);
     for(var i=0; i < markerList.length;i++)
     {
       //console.log('lat'+markerList[i].lat+', lon: '+markerList[i].lng); //ISPIS SVIH Lat i LNG
       var marker_icon="";
       if(markerList[i]['vrsta_ispada'] == "Nestanak vode")
       {
         marker_icon = 'water';
       }
       else if(markerList[i]['vrsta_ispada'] == "Nestanak električne energije")
       {
        marker_icon = 'earthquake';
       }
       else if(markerList[i]['vrsta_ispada'] == "Prekid prometa")
       {
        marker_icon = 'cabs';
       }
       else
       {
        marker_icon = 'hotsprings';
       }
       var position = new google.maps.LatLng(markerList[i].lat,markerList[i].lng);
       var marker = new google.maps.Marker({
            animation:google.maps.Animation.DROP,
            map:mapa,
            position:position,
            icon: 'http://maps.google.com/mapfiles/ms/micons/'+marker_icon+'.png',
            title:'Kliknite kako bi vidjeli detalje ispada'        
        });

       popUpMessage(marker,i);
     }  
        },      
    });
        

function popUpMessage(marker, i) {
    var data = markerList[i];

      secretMessage = `
        <b>Grad ispada: </b>${data.grad} 
        <br/><b>Vrsta ispada: </b>${data.vrsta_ispada} 
        <br/><b>Detaljan opis ispada: </b>${data.opis}
        <br/><b>Status: </b>${data.status}
        `;

      var infowindow = new google.maps.InfoWindow({
        content: secretMessage
      });

      marker.addListener('click', function() {
        infowindow.open(marker.get('map'), (marker));
      });
  }
}
/*------------------------------------------------------------Postavljanje Mape i markera DANAŠNJIH ISPISA----------------------------------------------------------------------*/

function mapsLegend()
{
  const iconBase = 'http://maps.google.com/mapfiles/ms/micons/';
  const icons = {
    voda: {
      name: 'Nestanak vode',
      icon: iconBase + 'water.png',
    },
    plin: {
      name: 'Nestanak plina',
      icon: iconBase + 'hotsprings.png',
    },
     promet: {
      name: 'Prekid prometa',
      icon: iconBase + 'cabs.png',
    },
     el_energija: {
      name: 'Nestanak električne energije',
      icon: iconBase + 'earthquake.png',
    },
  };
    const legend = document.getElementById("legend");
    for (const key in icons) {
      const type = icons[key];
      const name = type.name;
      const icon = type.icon;
      const div = document.createElement("div");
      div.innerHTML = '<img src="' + icon + '"> ' + name;
      legend.appendChild(div);

    }
    /* map.controls[google.maps.ControlPosition.RIGHT_BOTTOM].push(legend);*/
}
function mapsLegendPrikazSvihIspada()
{
  const iconBase = 'http://maps.google.com/mapfiles/ms/micons/';
  const icons = {
    voda: {
      name: 'Nestanak vode',
      icon: iconBase + 'water.png',
    },
    plin: {
      name: 'Nestanak plina',
      icon: iconBase + 'hotsprings.png',
    },
     promet: {
      name: 'Prekid prometa',
      icon: iconBase + 'cabs.png',
    },
     el_energija: {
      name: 'Nestanak električne energije',
      icon: iconBase + 'earthquake.png',
    },
  };
    const legend = document.getElementById("legend_svi_prikazi");
    for (const key in icons) {
      const type = icons[key];
      const name = type.name;
      const icon = type.icon;
      const div = document.createElement("div");
      div.innerHTML = '<img src="' + icon + '"> ' + name;
      legend_svi_prikazi.appendChild(div);

    }
     mapa_povijest.controls[google.maps.ControlPosition.RIGHT_BOTTOM].push(legend_svi_prikazi);
}

/*------------------------------------------------------------Postavljanje Mape i markera SVIH ISPISA---------------------------------------------------------------------------*/
function initMap_povijestIspad()
{
     $.ajax({
        type: "GET",
        url: 'json.php?action=prikazi_pregled_zavrsenih_ispada',
        success: function (oData){

            //console.log(oData);
            markerList_povijest=[];
            oData.forEach(function(povijest_ispadi)
            {
                var object = {
                    vrsta_ispada:povijest_ispadi.vrsta_ispada,
                    opis: povijest_ispadi.opis,
                    grad: povijest_ispadi.grad,
                    pocetak_ispada: povijest_ispadi.pocetak_ispada,
                    kraj_ispada: povijest_ispadi.kraj_ispada,
                    lat: povijest_ispadi.lat,
                    lng: povijest_ispadi.lng,
                    status: povijest_ispadi.status,
                  
                };
                markerList_povijest.push(object);            
            });        
    // Map options
     var options = {
        zoom:7.52,
        center:{lat:44.5876608,lng:16.7656699}
     }

     for(var i=0;i<markerList_povijest.length;i++)
     {
        //console.log('lat'+markerList_povijest[i].lat+', lng: '+markerList_povijest[i].lng,+' grad '+markerList_povijest[i].grad, + ' opis'+markerList_povijest[i].opis, markerList_povijest[i].vrsta_ispada); //Potrebno pretvoriti u stirng opis i grad
     }

     // New map
     var mapa_povijest = new google.maps.Map(document.getElementById('map_povijest'), options);
     for(var i=0; i < markerList_povijest.length;i++)
     {
        var marker_icon="";
       if(markerList_povijest[i]['vrsta_ispada'] == "Nestanak vode")
       {
         marker_icon = 'water';
       }
       else if(markerList_povijest[i]['vrsta_ispada'] == "Nestanak električne energije")
       {
        marker_icon = 'earthquake';
       }
       else if(markerList_povijest[i]['vrsta_ispada'] == "Prekid prometa")
       {
        marker_icon = 'cabs';
       }
       else
       {
        marker_icon = 'hotsprings';
       }
       //console.log('lat'+markerList[i].lat+', lon: '+markerList[i].lng); //ISPIS SVIH Lat i LNG
       var position = new google.maps.LatLng( markerList_povijest[i].lat, markerList_povijest[i].lng);
             
       var marker = new google.maps.Marker({
            animation:google.maps.Animation.DROP,
            map:mapa_povijest,
            position:position,
            icon: 'http://maps.google.com/mapfiles/ms/micons/'+marker_icon+'.png',
            title:'Kliknite kako bi vidjeli detalje ispada',

           
        });
       popUpMessage_povijest(marker,i);
     }  

        },      
    });
        
  function popUpMessage_povijest(marker, i) {
    var data_povijest = markerList_povijest[i];

      secretMessagePovijest = `
        <b>Grad ispada: </b>${data_povijest.grad} 
        <br/><b>Vrsta ispada: </b>${data_povijest.vrsta_ispada} 
        <br/><b>Detaljan opis ispada: </b>${data_povijest.opis}
        <br/><b>Status: </b>${data_povijest.status}

        `;

      var infowindow = new google.maps.InfoWindow({
        content: secretMessagePovijest
      });

      marker.addListener('click', function() {
        infowindow.open(marker.get('map_povijest'), marker);
      });
  }

}
/*------------------------------------------------------------------------------Postavljanje Mape i markera SVIH ISPISA-----------------------------------------------------------*/


/*------------------------------------------------------------------------------Prikazivanje Današnjih ispada---------------------------------------------------------------------*/
function PrikaziPodatke() {

    $.ajax({
        type: "GET",
        url: 'json.php?action=prikazi_pregled_ne_zavrsenih_ispada',
        success: function (oData){
            //console.log(oData);
            itemsList = [];                    
            oData.forEach(function(ispad)
            {                
                var object = {
                   // danasnji_datum: ispad.danasnji_datum,
                    id_ispad: ispad.id_ispad,
                    vrsta_ispada: ispad.vrsta_ispada,
                    grad: ispad.grad,
                    zupanija: ispad.zupanija,
                    pocetak_ispada: ispad.pocetak_ispada,
                    kraj_ispada: ispad.kraj_ispada,
                    opis: ispad.opis,
                    edit: '<button type="button" onclick="ModalEditIspad('+ ispad.id_ispad +')" class="btn btn-sm btn-info"><i class="fas fa-edit"></i></button>',
                    delete:'<button type="button" onclick="ModalDeleteIspad('+ispad.id_ispad+')" class="btn btn-sm btn-danger"><i class="fas fa-trash-alt"></i></button>'
                  
                };
                itemsList.push(object);
            });           
            if(dataTableInitialized)/*Osvjezavanje podataka, ako je inicijalizirano dodavati podatke*/
            {
                var table = $('#Tablica_ispada').DataTable();
                
                buildSelect(table);
                buildSelect1(table);

                table.on('draw',function()
                 {
                     buildSelect(table);
                     buildSelect1(table);
                 });

                table.clear().draw(); //Ova metoda jednostavno uklanja sve retke iz podatakaTables
                table.rows.add(itemsList); //mogućuje dodavanje više novih redaka odjednom,PUNJENJE PODATCIMA           
                table.columns.adjust().draw(); //pokušava rasporediti tablice u optimalnom formatu na temelju podataka u ćelijama
            }
            else
            {               
                oTable = $('#Tablica_ispada').DataTable(
                {
                    "language": {
                      "url": "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Croatian.json"
                    },
                    "ooData": itemsList,
                    "columnDefs":
                        [
                            {
                                /* KOLONA 1 */                            
                                "targets": 0,
                                "bVisible": true,
                                "data": 'id_ispad',
                                "bSortable": true,
                                "width": "20px"
                            },
                             {
                                /* KOLONA 2 */
                                "targets": 1,
                                "bVisible": true,
                                /*"data": "id_grad"*/
                                "data": "grad",
                                "bSortable": false,
                                "width": "20px"
                            },
                            {            
                                "searchable":true,                 
                                "targets": 2,
                                "bVisible": true,
                                /*"data": "id_grad"*/
                                "data": "zupanija",
                                "bSortable": false,
                                "width": "20px",
                            
                            },
                            {
                                /* KOLONA 3 */
                                "searchable":true,
                                "targets": 3,
                                "bVisible": true,
                                /*"data": "id_vrsta_ispada",*/
                                "data": "vrsta_ispada",
                                "bSortable": false,
                                "width": "20px"
                            },
                             {
                                /* KOLONA 4 */
                                "targets": 4,
                                "bVisible": true,
                                "data": "pocetak_ispada",
                                "bSortable": false,
                                "width": "20px"
                            },
                            {
                                /* KOLONA 5 */
                                "targets": 5,
                                "bVisible": true,
                                "data": "kraj_ispada",
                                "bSortable": false,
                                "width": "20px"
                            },
                             {
                                /* KOLONA 6 */
                                "targets": 6,
                                "bVisible": true,
                                "data": "opis",
                                "bSortable": false,
                                "width": "20px"
                            },
                             {
                                /* KOLONA 7 */
                                "targets": 7,
                                "bVisible": true,
                                "data": "edit",
                                "bSortable": false,
                                "width": "20px"
                            },
                            {
                                /* KOLONA 8 */
                                "targets": 8,
                                "bVisible": true,
                                "data": "delete",
                                "bSortable": false,
                                "width": "20px"
                            },
                        ],
                    
                    "dom": '<"clear">lCBrtip',
                    "order": [0, 'asc'],
                    "iDisplayLength": 10,
                    "buttons": [
                    {
                        extend: 'colvis',
                        text: 'Kolone',
                        className: 'btn btn-default dropdown-toggle',
                        columns: ':not(.not_visible)' //th klasa not_visible za kolone koje se ne prikazuju probaj
                    },
                    {
                        text: 'Dodajte ispad',
                        className: 'btn btn-default',
                        action: function (e, dt, node, config) 
                        {                
                           PopulateSelectVrstaIspada(true, null); /*Radi se */
                           PopulateSelectZupanija(true, null);                            
                           $('#myAddModal').modal('show');
                        }
                    },
                   
                ],
                });
                dataTableInitialized = true;
            }
        },
        error: function()
         {
            console.log('Nema postojećih podataka.');
         }
    });
}
/*--------------------------------------------------------------------------Prikazivanje Današnjih ispada---------------------------------------------------------------------*/
function buildSelect( table ) {
                       table.columns(2).every( function () {
                         var column = table.column( this, {search: 'applied'} );
                         var select = $('<select><option value="">Filtriraj po županiji</option></select>').appendTo( $('#dropdown_pokusaj').empty() )
                         .on( 'change', function () {
                           var val = $.fn.dataTable.util.escapeRegex(
                             $(this).val()
                           );

                           column
                           .search( val ? '^'+val+'$' : '', true, false )
                               .draw();
                             } );

                             column.data().unique().sort().each( function ( d, j ) {
                               select.append( '<option value="'+d+'">'+d+'</option>' );
                             } );
      
      // The rebuild will clear the exisiting select, so it needs to be repopulated
             var currSearch = column.search();
          //   console.log(currSearch)
             if ( currSearch ) {
               var unescaped = currSearch.replace(/\\/g, '');
      //  console.log(unescaped)
        select.val( unescaped.substring(1, unescaped.length-1) );
      }

    } );
}

 function buildSelect1( table ) 
 {
 table.columns(3).every( function () 
 {
   var column = table.column( this, {search: 'applied'} );
   var select = $('<select><option value="">Filtriraj po vrsti ispada</option></select>').appendTo( $('#dropdown_pokusaj1').empty() )
   .on( 'change', function () 
   {
     var val = $.fn.dataTable.util.escapeRegex(
       $(this).val()
     );

     column
     .search( val ? '^'+val+'$' : '', true, false )
         .draw();
       } );

       column.data().unique().sort().each( function ( d, j ) 
       {
         select.append( '<option value="'+d+'">'+d+'</option>' );
       } );
// The rebuild will clear the exisiting select, so it needs to be repopulated
var currSearch = column.search();
//console.log(currSearch)
if ( currSearch ) 
{
  var unescaped = currSearch.replace(/\\/g, '');
  // console.log(unescaped)
 select.val( unescaped.substring(1, unescaped.length-1) );
}
 });
}



function PopulateSelectZupanija(isAddModal, zupanijaToSelect, gradToSelect) {

    $.ajax({
        type: "GET",
        url: 'json.php?action=populate_select_zupanije',
        success: function (oData){

            //console.log(oData);
            $('#dropdown_zupanija').empty();
            $('#dropdown_zupanija_edit').empty();
            var option = document.createElement("option");
            option.value= '-1';
            option.innerHTML = 'Izaberite županiju';

            if(isAddModal == true) {
                $('#dropdown_zupanija').append(option);
            } 
            else 
            {
               $('#dropdown_zupanija_edit').append(option);
            }
            oData.forEach(function(zupanija)
            {
                var option = document.createElement("option");
                option.value= zupanija.id_zupanija;
                option.innerHTML = zupanija.naziv_zupanije;

                if(isAddModal == true) {
                    $('#dropdown_zupanija').append(option);
                } else {
                   $('#dropdown_zupanija_edit').append(option);
                }
            }); 
            if (zupanijaToSelect != null)
             {
              if(isAddModal == true) /*Ako ima vrijednost zupanija*/
              {
                    $('#dropdown_zupanija').val(zupanijaToSelect); /*Ako ima vrijednost, provjera zupaniju*/
                } else {
                    $('#dropdown_zupanija_edit').val(zupanijaToSelect);
                }
                ZupanijaChange(false, gradToSelect);
             }
            
        },
        error: function() {
            console.log('Cannot retrieve data.');
        }
    });
}

function ZupanijaChange(isAddModal, valueToSelect) /*isAddModel --true otvara se add modal
fasle-- edit modal*/
{
   
    var id = "";
    /*Kod za pokupiti podatke*/  /*Provjera modala, ako je true id_dropdowna modalu
                                  ako je false dropdown*/
    if(isAddModal == true) 
    {
        console.log("tu sam");
      id = 'dropdown_zupanija';
    } 
    else 
    {   console.log("nisam tu");
      id = 'dropdown_zupanija_edit';
    }
    var id_grada_odabrane_zupanije=document.getElementById(id).value;
    PopulateSelectGrad(id_grada_odabrane_zupanije, isAddModal, valueToSelect);
    console.log(valueToSelect);
}

 
function PopulateSelectGrad(id_grada_odabrane_zupanije, isAddModal, valueToSelect) {
  console.log(id_grada_odabrane_zupanije);
  console.log(valueToSelect);
    $.ajax({
        type: "GET",
        url: 'json.php?action=populate_select_gradovi&id_grada_odabrane_zupanije='+id_grada_odabrane_zupanije,
        success: function (oData){

            console.log(oData);

            $('#dropdown_grad').empty();
            $('#dropdown_gradEdit').empty();
            var option = document.createElement("option");
            option.value= '-1';
            option.innerHTML = 'Izaberite grad'

            if(isAddModal == true) 
            {           
                $('#dropdown_grad').append(option);
            } 
            else 
            {
              
                $('#dropdown_gradEdit').append(option);
            }
            oData.forEach(function(grad)
            {
                var option = document.createElement("option");
                option.value= grad.id_grad;
                option.innerHTML = grad.naziv_grada;

                if(isAddModal == true) 
                {
                    $('#dropdown_grad').append(option);
                } 
                else 
                {
                    $('#dropdown_gradEdit').append(option);
                }
               
            }); 
            if (valueToSelect != null) /*Odmah izabere grad, selectira*/
             {
              if(isAddModal == true){
                    $('#dropdown_grad').val(valueToSelect);
                } 
                else 
                {
                    $('#dropdown_gradEdit').val(valueToSelect);
                }
            }
        },
        error: function() 
        {
            console.log('Cannot retrieve data.');
        }
    });
}



function PopulateSelectVrstaIspada(isAddModal, valueToSelect) {

    $.ajax({
        type: "GET",
        url: 'json.php?action=populate_select_vrste_ispada',
        success: function (oData){

            console.log(oData);
            $('#dropdown_vrsta_ispada').empty();
            $('#dropdown_vrsta_ispadaEdit').empty();
            var option = document.createElement("option");
            option.value= '-1';
            option.innerHTML = 'Izaberite vrstu ispada';
            if(isAddModal == true){
                $('#dropdown_vrsta_ispada').append(option);
            } else {
                $('#dropdown_vrsta_ispadaEdit').append(option);
            }
            oData.forEach(function(ispad)
            {
                var option = document.createElement("option");
                option.value= ispad.id_vrsta_ispada;
                option.innerHTML = ispad.vrsta_ispada;
                if(isAddModal == true){
                    $('#dropdown_vrsta_ispada').append(option);
                } else {
                    $('#dropdown_vrsta_ispadaEdit').append(option);
                }
                
            }); 
            if (valueToSelect != null)
             {
              if(isAddModal == true){
                    $('#dropdown_vrsta_ispada').val(valueToSelect);
                } else {
                    $('#dropdown_vrsta_ispadaEdit').val(valueToSelect);
                }
            }
        },
        error: function() {
            console.log('Cannot retrieve data.');
        }
    });
}


/*-----------------------------------------------DATETIME PICKER za pocetak i kraj ispada---------------------------------------------------------------------------------------*/
function InitializeDateTimePickerPocetakIspada() 
{
  $('#datetimepickerpocetak').datetimepicker(
  {
         format: 'YYYY-MM-DD HH:mm:ss',
            locale: 'hr',
            useCurrent: true,
            defaultDate: moment()
  });

}

function InitializeDateTimePickerZavrsetakIspada() 
{
  $('#datetimepickerzavrsetak').datetimepicker(
  {
            format: 'YYYY-MM-DD HH:mm:ss',
            locale: 'hr',
            useCurrent: false,
  });

  $('#datetimepickerForEdit').datetimepicker(
  {
            format: 'YYYY-MM-DD HH:mm:ss',
            locale: 'hr',
            useCurrent: false
            
    });
}


/*-----------------------------------------------DATETIME PICKER za pocetak i kraj ispada---------------------------------------------------------------------------------------*/
function DodajIspad()
{ 
    var KorisnikID=adminID; //dohvacam id-eve 
    console.log(KorisnikID);
    var grad=$('#dropdown_grad').val();
    var grad_int=parseInt(grad,10);
    var ispad=$('#dropdown_vrsta_ispada').val();
    var ispad_int=parseInt(ispad,10);
    var opis=$('#inptOpis').val();

    var datum_pocetak=$('#datetimepickerpocetak').data("DateTimePicker").date().format('YYYY-MM-DD HH:mm:ss');
    var datum_zavrsetak=$('#datetimepickerzavrsetak').data("DateTimePicker").date();
    console.log(datum_zavrsetak);
    if(datum_zavrsetak == null)
    {
      datum_zavrsetak=null;
    }
    else
    {
      datum_zavrsetak=$('#datetimepickerzavrsetak').data("DateTimePicker").date().format('YYYY-MM-DD HH:mm:ss');
    }

    if(datum_pocetak == ""  || grad == "-1" || ispad == "-1" || opis == "" )
    {
      alert("Popunite potrebna polja!");
    }
    else
    {
      var failure = {
          
          ID_korisnik:KorisnikID,
          ID_vrsta_ispada:ispad_int,
          ID_grad:grad_int,
          Datum_vrijeme_pocetka_ispada:datum_pocetak,
          Datum_vrijeme_zavrsetak_ispada:datum_zavrsetak,
          Dodatan_opis:opis
        
         
      };
        CompleteDodajIspad(failure);
    }
}

function CompleteDodajIspad(failure) {
    $.ajax({
        type: "POST",
        url:'action.php',
        data:
        {
           action_id:'dodaj_ispad_web',        
           id_korisnik:failure.ID_korisnik,
           id_vrsta_ispada:failure.ID_vrsta_ispada,
           id_grad:failure.ID_grad,
           datum_vrijeme_pocetka_ispada:failure.Datum_vrijeme_pocetka_ispada,
           datum_vrijeme_zavrsetak_ispada:failure.Datum_vrijeme_zavrsetak_ispada,
           dodatan_opis:failure.Dodatan_opis

        },
        success: function (oData)
        {
            console.log(oData);
            
            $("#myAddModal").modal('hide');
            PrikaziPodatke();
            initMap_trenutanIspad();

        },
        error: function (XMLHttpRequest, textStatus, exception) {
            console.log("Ajax failure\n");
        },
        async: true
    });
}


function ModalEditIspad(id_ispada) {
console.log(id_ispada); //Dohvacanje ID_ispada prilikom klika
    $.ajax({
        type: "GET",
        url: 'json.php?action=dohvati_ispad&id=' + id_ispada,
        success: function (oData){
            console.log(oData);
            var odabran_ispad = oData[0];
            var id_vrstaispada=odabran_ispad.vrsta_ispada;
            PopulateSelectZupanija(false, odabran_ispad.zupanija, odabran_ispad.grad);
            PopulateSelectVrstaIspada(false, odabran_ispad.vrsta_ispada);
            
            $('#datetimepickerForEdit').data("DateTimePicker").date(moment(odabran_ispad.kraj_ispada));
            $('#inptOpisEdit').val(odabran_ispad.opis);
            $('#editButton').attr('onclick', 'SaveEditedItem("'+id_ispada+'")');
            $('#myEditModal').modal('show');
        },
        error: function() {
            console.log('Cannot retrieve data.');
        }
    });
}

function SaveEditedItem(id_ispada) {
    var ID_ispad=id_ispada;
    var KorisnikID=adminID;
    var novi_datum_edit = $('#datetimepickerForEdit').data("DateTimePicker").date().format('YYYY-MM-DD HH:mm:ss');
    var novi_id_vrste_ispada_edit = $('#dropdown_vrsta_ispadaEdit').val();
    var novi_grad_edit = $('#dropdown_gradEdit').val();
    var novi_opis_edit = $('#inptOpisEdit').val();
    

    if(novi_datum_edit=="" || novi_id_vrste_ispada_edit=="-1" || novi_grad_edit=="-1" || novi_opis_edit=="")
    {
        alert("Popunite potrebna polja!");
    }
    else
    {
        var novi_ispad = {
            ID_ispada_edit:ID_ispad,
            ID_korisnik_edit:KorisnikID,
            Datum_vrijeme_zavrsetka_ispada_edit:novi_datum_edit,
            ID_vrsta_ispada_edit:novi_id_vrste_ispada_edit,
            ID_grad_edit:novi_grad_edit,
            Dodatan_opis_edit:novi_opis_edit
            
        };       
        console.log(novi_ispad);
            CompleteEditItem(novi_ispad);                                   
    }
}

function CompleteEditItem(novi_ispad) {
  console.log(novi_ispad);
    $.ajax({
        type: "POST",
        url: 'action.php',
        data:
        {
            action_id:'edit_ispad',
            id_ispad_edit:novi_ispad.ID_ispada_edit,
            id_korisnik_edit:novi_ispad.ID_korisnik_edit,
            datum_vrijeme_zavrsetak_ispada_edit:novi_ispad.Datum_vrijeme_zavrsetka_ispada_edit,
            id_vrsta_ispada_edit:novi_ispad.ID_vrsta_ispada_edit,
            id_grad_edit:novi_ispad.ID_grad_edit,
            dodatan_opis_edit:novi_ispad.Dodatan_opis_edit   
        },
        success: function (oData)
        {
            console.log(oData);
            $("#myEditModal").modal('hide');
            PrikaziPodatke();
            initMap_trenutanIspad();
        },
        error: function (XMLHttpRequest, textStatus, exception) {
            console.log("Ajax failure\n");
        },
        async: true
    });
}



function ModalDeleteIspad(id_ispada) 
{

    $('#deleteButton').attr('onclick', 'DeleteItem("'+id_ispada+'")');
    $('#myDeleteModal').modal('show');
}

function ModalDeleteSviIspadi(id_ispada) 
{

    $('#deleteButton').attr('onclick', 'DeleteSviIspadi("'+id_ispada+'")');
    $('#myDeleteModalSviIspadi').modal('show');
}


function DeleteItem(id_ispada) {
  console.log(id_ispada);
   $.ajax({
        type: "POST",
        url: 'action.php',
        data:
        {
            action_id:'delete_ispad',      
            id_ispada: id_ispada
        },
        success: function (oData)
        {
            console.log(oData);
            $("#myDeleteModal").modal('hide');

            PrikaziPodatke();
            initMap_trenutanIspad();
        },
        error: function (XMLHttpRequest, textStatus, exception) {
            console.log("Ajax failure\n");
        },
        async: true
    });
}
function DeleteSviIspadi(id_ispada) {
  console.log(id_ispada);
   $.ajax({
        type: "POST",
        url: 'action.php',
        data:
        {
            action_id:'delete_ispad',      
            id_ispada: id_ispada
        },
        success: function (oData)
        {
            console.log(oData);
            $("#myDeleteModalSviIspadi").modal('hide');

            PrikaziPodatkePovijest();
            initMap_povijestIspad();
        },
        error: function (XMLHttpRequest, textStatus, exception) {
            console.log("Ajax failure\n");
        },
        async: true
    });
}
/*----------------------------------------------Prikazivanje SVIH ISPADA--------------------------------------------------------------------------------------------------------*/

function PrikaziPodatkePovijest() {

    $.ajax({
        type: "GET",
        url: 'json.php?action=prikazi_pregled_zavrsenih_ispada',
        success: function (oData){

            console.log(oData);
            ispadiList = [];
            
            oData.forEach(function(povijest_ispad)
            {
                
                var object = {
                    id_ispad: povijest_ispad.id_ispad,
                    vrsta_ispada: povijest_ispad.vrsta_ispada,
                    grad: povijest_ispad.grad,
                    zupanija: povijest_ispad.zupanija,
                    pocetak_ispada: povijest_ispad.pocetak_ispada,
                    kraj_ispada: povijest_ispad.kraj_ispada,
                    opis: povijest_ispad.opis,
                    delete:'<button type="button" onclick="ModalDeleteSviIspadi('+povijest_ispad.id_ispad+')" class="btn btn-sm btn-danger"><i class="fas fa-trash-alt"></i></button>'
                  
                };
                ispadiList.push(object);
            });
            if(dataTableInitialized_povijest)
            {
                var table = $('#Tablica_povijesti_ispada').DataTable();
                buildSelectPovijest(table);
                buildSelect1Povijest(table);
                table.on('draw', function()
                {
                    buildSelectPovijest(table);
                    buildSelect1Povijest(table);
                });
               

                table.clear().draw();
                table.rows.add(ispadiList);             
                table.columns.adjust().draw();

            }
            else
            {

                oTable = $('#Tablica_povijesti_ispada').DataTable(
                {
                      "language": {
                      "url": "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Croatian.json"
                    },
                    "ooData": ispadiList,
                    "columnDefs":
                        [
                            {
                                /* KOLONA 1 */
                                "targets": 0,
                                "bVisible": true,
                                "data": 'id_ispad',
                                "bSortable": true,
                                "width": "20px"
                            },
                             {
                                /* KOLONA 2 */
                                "targets": 1,
                                "bVisible": true,
                                /*"data": "id_grad"*/
                                "data": "grad",
                                "bSortable": false,
                                "width": "20px"
                            },
                            {                             
                                "targets": 2,
                                "bVisible": true,
                                /*"data": "id_grad"*/
                                "data": "zupanija",
                                "bSortable": false,
                                "width": "20px"
                            
                            },
                            {
                                /* KOLONA 3 */
                                "targets": 3,
                                "bVisible": true,
                                /*"data": "id_vrsta_ispada",*/
                                "data": "vrsta_ispada",
                                "bSortable": false,
                                "width": "20px"
                            },
                             {
                                /* KOLONA 4 */
                                "targets": 4,
                                "bVisible": true,
                                "data": "pocetak_ispada",
                                "bSortable": false,
                                "width": "20px"
                            },
                            {
                                /* KOLONA 5 */
                                "targets": 5,
                                "bVisible": true,
                                "data": "kraj_ispada",
                                "bSortable": false,
                                "width": "20px"
                            },
                             {
                                /* KOLONA 6 */
                                "targets": 6,
                                "bVisible": true,
                                "data": "opis",
                                "bSortable": false,
                                "width": "20px"
                            },
                            {
                                /* KOLONA 7 */
                                "targets": 7,
                                "bVisible": true,
                                "data": "delete",
                                "bSortable": false,
                                "width": "20px"
                            },
                         
                        ],
                    "dom": 'lBf<"refresh">rtip',
                    "order": [0, 'asc'],
                    "iDisplayLength": 10,
                    "buttons": 
                    [
                    {
                        extend: 'colvis',
                        text: 'Kolone',
                        className: 'btn btn-default dropdown-toggle',
                        columns: ':not(.not_visible)' //th klasa not_visible za kolone koje se ne prikazuju
                    },               
                ]

                });

                dataTableInitialized_povijest = true;
            }
        },
        error: function() {
            console.log('Nema postojećih podataka.');
        }
    });

}
/*----------------------------------------------Prikazivanje SVIH DANAŠNJIH ISPADA---------------------------------------------------------------------*/
 function buildSelectPovijest( table ) {
                       table.columns(2).every( function () {
                         var column = table.column( this, {search: 'applied'} );
                         var select = $('<select><option value="">Filtriraj po županiji</option></select>').appendTo( $('#dropdown_prikaz_svih').empty() )
                         .on( 'change', function () {
                           var val = $.fn.dataTable.util.escapeRegex(
                             $(this).val()
                           );

                           column
                           .search( val ? '^'+val+'$' : '', true, false )
                               .draw();
                             } );

                             column.data().unique().sort().each( function ( d, j ) {
                               select.append( '<option value="'+d+'">'+d+'</option>' );
                             } );
      
      // The rebuild will clear the exisiting select, so it needs to be repopulated
             var currSearch = column.search();
          //   console.log(currSearch)
             if ( currSearch ) {
               var unescaped = currSearch.replace(/\\/g, '');
      //  console.log(unescaped)
        select.val( unescaped.substring(1, unescaped.length-1) );
      }

    } );
}

function buildSelect1Povijest( table ) {
                       table.columns(3).every( function () {
                         var column = table.column( this, {search: 'applied'} );
                         var select = $('<select><option value="">Filtriraj po vrsti ispada</option></select>').appendTo( $('#dropdown_prikaz_svih1').empty() )
                         .on( 'change', function () {
                           var val = $.fn.dataTable.util.escapeRegex(
                             $(this).val()
                           );

                           column
                           .search( val ? '^'+val+'$' : '', true, false )
                               .draw();
                             } );

                             column.data().unique().sort().each( function ( d, j ) {
                               select.append( '<option value="'+d+'">'+d+'</option>' );
                             } );
      
      // The rebuild will clear the exisiting select, so it needs to be repopulated
             var currSearch = column.search();
          //   console.log(currSearch)
             if ( currSearch ) {
               var unescaped = currSearch.replace(/\\/g, '');
      //  console.log(unescaped)
        select.val( unescaped.substring(1, unescaped.length-1) );
      }

    } );
}


function Histogram()
{

    $.ajax({
        type: "GET",
        url: 'json.php?action=prikazi_pregled_zavrsenih_ispada',
        success: function (oData){

            console.log(oData);
            svi_ispadiList=[];

             oData.forEach(function(svi_ispadi)
            {
                
                var object = {
                    id_ispad: svi_ispadi.id_ispad,
                    vrsta_ispada: svi_ispadi.vrsta_ispada,
                    grad: svi_ispadi.grad,
                    zupanija: svi_ispadi.zupanija,
                    pocetak_ispada: svi_ispadi.pocetak_ispada,
                    kraj_ispada: svi_ispadi.kraj_ispada,
                    opis: svi_ispadi.opis,
                  
                };
                svi_ispadiList.push(object);

            });
             var length=svi_ispadiList.length;
             console.log(" Sve ukupno ispada " + length); //broj ispada, y-os
            var ispadi_zupanijeList=[];
            var ispadi_brojispadaList=[];
             var broj_ispada_zupanije_brodska = 0;
             var broj_ispada_zupanije_osjecka = 0;
             var broj_ispada_zupanije_dubrovacka = 0;
             var broj_ispada_zupanije_bjelovarska = 0;
             var broj_ispada_zupanije_zagreb = 0;
             var broj_ispada_zupanije_istarska = 0;
             var broj_ispada_zupanije_karlovacka = 0;
             var broj_ispada_zupanije_koprivnicka = 0;
             var broj_ispada_zupanije_krapinska = 0;
             var broj_ispada_zupanije_licka = 0;
             var broj_ispada_zupanije_medimurska = 0;
             var broj_ispada_zupanije_pozeska = 0;
             var broj_ispada_zupanije_primoska = 0;
             var broj_ispada_zupanije_sisacka = 0;
             var broj_ispada_zupanije_splitska = 0;
             var broj_ispada_zupanije_sibenska = 0;
             var broj_ispada_zupanije_varazdinska = 0;
             var broj_ispada_zupanije_viroviticka = 0;
             var broj_ispada_zupanije_vukovarska = 0;
             var broj_ispada_zupanije_zadarska = 0;
             var broj_ispada_zupanije_zagrebacka = 0;

             for(var i=0; i < svi_ispadiList.length; i++)
             {
                switch(svi_ispadiList[i]['zupanija'])
                {
                   case "Bjelovarsko-bilogorska":
                   var naziv_zupanije_BB="Bjelovarsko-bilogorska";
                   broj_ispada_zupanije_bjelovarska++;
                   var broj_ispada_zupanije_bjelovarska_string=broj_ispada_zupanije_bjelovarska.toString();
                   break;

                   case "Brodsko-posavska":
                    var naziv_zupanije_BP="Brodsko-posavska";
                     broj_ispada_zupanije_brodska++;
                    var broj_ispada_zupanije_brodska_string=broj_ispada_zupanije_brodska.toString();
                   break;

                    case "Dubrovačko-neretvanska":
                    var naziv_zupanije_DN=svi_ispadiList[i]['zupanija'];
                  broj_ispada_zupanije_dubrovacka++;
                  var broj_ispada_zupanije_dubrovacka_string=broj_ispada_zupanije_dubrovacka.toString();
                  break;

                    case "Grad Zagreb":
                    var naziv_zupanije_GZ="Grad Zagreb";
                    broj_ispada_zupanije_zagreb++;
                    var broj_ispada_zupanije_zagreb_string=broj_ispada_zupanije_zagreb.toString();
                    break;

                      case "Istarska":
                  var naziv_zupanije_I="Istarska";
                    broj_ispada_zupanije_istarska++;
                    var broj_ispada_zupanije_istarska_string=broj_ispada_zupanije_istarska.toString();
                  break;


                  case "Karlovačka":
                     var naziv_zupanije_K="Karlovačka";
                   broj_ispada_zupanije_karlovacka++;
                   var broj_ispada_zupanije_karlovacka_string=broj_ispada_zupanije_karlovacka.toString();
                  break;

                  case "Koprivničko-križevačka":
                  var naziv_zupanije_KK="Koprivničko-križevačka";
                  broj_ispada_zupanije_koprivnicka++;
                  var broj_ispada_zupanije_koprivnicka_string=broj_ispada_zupanije_koprivnicka.toString();
                  break;

                  case "Krapinsko-zagorska":
                    var naziv_zupanije_KZ="Krapinsko-zagorska";
                  broj_ispada_zupanije_krapinska++;
                  var broj_ispada_zupanije_krapinska_string=broj_ispada_zupanije_krapinska.toString();
                  break;

                  case "Ličko-senjska":
                   var naziv_zupanije_LS="Ličko-senjska";
                  broj_ispada_zupanije_licka++;
                  var broj_ispada_zupanije_licka_string=broj_ispada_zupanije_licka.toString();
                  break;

                  case "Međimurska":
                  var naziv_zupanije_M="Međimurska";
                  broj_ispada_zupanije_medimurska++;
                  var broj_ispada_zupanije_medimurska_string=broj_ispada_zupanije_medimurska.toString();
                  break;

                  case "Osječko-baranjska":
                   var naziv_zupanije_OB=svi_ispadiList[i]['zupanija'];
                    broj_ispada_zupanije_osjecka++;
                   var broj_ispada_zupanije_osjecka_string=broj_ispada_zupanije_osjecka.toString();
                  break;

                  case "Požeško-slavonska":
                  var naziv_zupanije_PS="Požeško-slavonska";
                  broj_ispada_zupanije_pozeska++;
                  var broj_ispada_zupanije_pozeska_string=broj_ispada_zupanije_pozeska.toString();
                  break;

                  case "Primorsko-goranska":
                  var naziv_zupanije_PG="Primorsko-goranska";
                  broj_ispada_zupanije_primoska++;
                  var broj_ispada_zupanije_primorska_string=broj_ispada_zupanije_primoska.toString();
                  break;

                  case "Sisačko-moslavačka":
                   var naziv_zupanije_SM="Sisačko-moslavačka";
                  broj_ispada_zupanije_sisacka++;
                  var broj_ispada_zupanije_sisacka_string=broj_ispada_zupanije_sisacka.toString();
                  break;

                  case "Splitsko-dalmatinska":
                   var naziv_zupanije_SD="Splitsko-dalmatinska";
                  broj_ispada_zupanije_splitska++;
                  var broj_ispada_zupanije_splitska_string=broj_ispada_zupanije_splitska.toString();
                  break;

                  case "Šibensko-kninska":
                  var naziv_zupanije_ŠK="Šibensko-kninska";
                  broj_ispada_zupanije_sibenska++;
                  var broj_ispada_zupanije_sibenska_string=broj_ispada_zupanije_sibenska.toString();
                  break;

                  case "Varaždinska":
                   var naziv_zupanije_V="Varaždinska";
                  broj_ispada_zupanije_varazdinska++;
                  var broj_ispada_zupanije_varazdinska_string=broj_ispada_zupanije_varazdinska.toString();
                  break;

                  case "Virovitičko-podravska":
                    var naziv_zupanije_VP="Virovitičko-podravska";
                  broj_ispada_zupanije_viroviticka++;
                  var broj_ispada_zupanije_viroviticka_string=broj_ispada_zupanije_viroviticka.toString();
                  break;
                 
                 case "Vukovarsko-srijemska":
                  var naziv_zupanije_VS="Vukovarsko-srijemska";
                  broj_ispada_zupanije_vukovarska++;
                  var broj_ispada_zupanije_vukovarska_string=broj_ispada_zupanije_vukovarska.toString();
                 break;

                 case "Zadarska":
                   var naziv_zupanije_Z="Zadarska";
                  broj_ispada_zupanije_zadarska++;
                  var broj_ispada_zupanije_zadarska_string=broj_ispada_zupanije_zadarska.toString();
                 break;

                 case "Zagrebačka":
                  var naziv_zupanije_ZAG="Zagrebačka";
                  broj_ispada_zupanije_zagrebacka++;
                  var broj_ispada_zupanije_zagrebacka_string=broj_ispada_zupanije_zagrebacka.toString();
                 break;
                   
                 default:
                 
                 break;
                }
             }

             ispadi_zupanijeList.push(naziv_zupanije_BB);
             ispadi_zupanijeList.push(naziv_zupanije_BP);
             ispadi_zupanijeList.push(naziv_zupanije_DN);
             ispadi_zupanijeList.push(naziv_zupanije_GZ);
             ispadi_zupanijeList.push(naziv_zupanije_I);
             ispadi_zupanijeList.push(naziv_zupanije_K);
             ispadi_zupanijeList.push(naziv_zupanije_KK);
             ispadi_zupanijeList.push(naziv_zupanije_KZ);
             ispadi_zupanijeList.push(naziv_zupanije_LS);
             ispadi_zupanijeList.push(naziv_zupanije_M);
             ispadi_zupanijeList.push(naziv_zupanije_OB);
             ispadi_zupanijeList.push(naziv_zupanije_PS);
             ispadi_zupanijeList.push(naziv_zupanije_PG);
             ispadi_zupanijeList.push(naziv_zupanije_SM);
             ispadi_zupanijeList.push(naziv_zupanije_SD);
             ispadi_zupanijeList.push(naziv_zupanije_ŠK);
             ispadi_zupanijeList.push(naziv_zupanije_V);
             ispadi_zupanijeList.push(naziv_zupanije_VP);
             ispadi_zupanijeList.push(naziv_zupanije_VS);
             ispadi_zupanijeList.push(naziv_zupanije_Z);
             ispadi_zupanijeList.push(naziv_zupanije_ZAG);
             
             console.log(ispadi_zupanijeList); //ISPIS SVIH ZUPANIJA

             ispadi_brojispadaList.push(broj_ispada_zupanije_bjelovarska_string);
             ispadi_brojispadaList.push(broj_ispada_zupanije_brodska_string);
             ispadi_brojispadaList.push(broj_ispada_zupanije_dubrovacka_string);
             ispadi_brojispadaList.push(broj_ispada_zupanije_zagreb_string);
             ispadi_brojispadaList.push(broj_ispada_zupanije_istarska_string);
             ispadi_brojispadaList.push(broj_ispada_zupanije_karlovacka_string);
             ispadi_brojispadaList.push(broj_ispada_zupanije_koprivnicka_string);
             ispadi_brojispadaList.push(broj_ispada_zupanije_krapinska_string);
             ispadi_brojispadaList.push(broj_ispada_zupanije_licka_string);
             ispadi_brojispadaList.push(broj_ispada_zupanije_medimurska_string);
             ispadi_brojispadaList.push(broj_ispada_zupanije_osjecka_string);
             ispadi_brojispadaList.push(broj_ispada_zupanije_pozeska_string);
             ispadi_brojispadaList.push(broj_ispada_zupanije_primorska_string);
             ispadi_brojispadaList.push(broj_ispada_zupanije_sisacka_string);
             ispadi_brojispadaList.push(broj_ispada_zupanije_splitska_string);
             ispadi_brojispadaList.push(broj_ispada_zupanije_sibenska_string);
             ispadi_brojispadaList.push(broj_ispada_zupanije_varazdinska_string);
             ispadi_brojispadaList.push(broj_ispada_zupanije_viroviticka_string);
             ispadi_brojispadaList.push(broj_ispada_zupanije_vukovarska_string);
             ispadi_brojispadaList.push(broj_ispada_zupanije_zadarska_string);
             ispadi_brojispadaList.push(broj_ispada_zupanije_zagrebacka_string);
             console.log(ispadi_brojispadaList); //ISPIS BROJA ISPADA 

                
        var data = [
          {
            /*histfunc: "avg",
            y: ispadi_brojispadaList,
            x: ispadi_zupanijeList,
            type: "histogram",
            name: "Prosječan broj ispada unutar županije"*/
          },
          {
            histfunc: "sum",
            y: ispadi_brojispadaList,
            x: ispadi_zupanijeList,
            type: "histogram",
            name: "Ukupan broj ispada unutar županije"
          }
        ]   

        var layout = 
        {
          title: 
            {
          text:'Prikaz broja ispada u pojedinoj županiji',
          font: 
            {
            family: 'Lucida Sans Typewriter,monospace',
            size: 24
            },
           xref: 'paper',
           x: 0.05,
         },
          xaxis:
          {
           title: 
           {
             text: '  Županije',
             font:
              {
               family: 'Lucida Sans Typewriter, monospace',
               size: 21,
               color: '#7f7f7f'
             }
           },
         },
           yaxis: 
           {
            title: 
            {
              text: 'Broj ispada',
              font: 
              {
                family: 'Courier New, monospace',
                size: 21,
                color: '#7f7f7f'
              }
            }
          }
        };

        Plotly.newPlot('myDiv', data, layout)
        },
        error: function() {
            console.log('Cannot retrieve data.');
        }
    });
}




/*Prikaz podatka USER_VIEW današnji ispadi*/
function PrikaziPodatkeUser_view() {

    $.ajax({
        type: "GET",
        url: 'json.php?action=prikazi_pregled_ne_zavrsenih_ispada',
        success: function (oData){

            console.log(oData);
            ispadiList_danasnji_user_view = [];
            
            oData.forEach(function(user_view_danasnji)
            {
                
                var object = {
                    id_ispad: user_view_danasnji.id_ispad,
                    vrsta_ispada: user_view_danasnji.vrsta_ispada,
                    grad: user_view_danasnji.grad,
                    zupanija: user_view_danasnji.zupanija,
                    pocetak_ispada: user_view_danasnji.pocetak_ispada,
                    kraj_ispada: user_view_danasnji.kraj_ispada,
                    opis: user_view_danasnji.opis,
                  
                };
                ispadiList_danasnji_user_view.push(object);
               
            });
             console.log(ispadiList_danasnji_user_view);
            if(dataTableInitialized_dasanji_user_view)
            {
                var table = $('#Tablica_ispada_user_view').DataTable();
                buildSelectDanasnji_user_view(table);
                buildSelect1Danasnji_user_view(table);
                table.on('draw', function(){
                    buildSelectDanasnji_user_view(table);
                    buildSelect1Danasnji_user_view(table);
                });
   

                table.clear().draw();
                table.rows.add(ispadiList_danasnji_user_view);             
                table.columns.adjust().draw();

            }
            else
            {

                oTable = $('#Tablica_ispada_user_view').DataTable(
                {
                      "language": {
                      "url": "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Croatian.json"
                    },
                    "ooData": ispadiList_danasnji_user_view,
                    "columnDefs":
                        [
                            {
                                /* KOLONA 1 */
                                "targets": 0,
                                "bVisible": true,
                                "data": 'id_ispad',
                                "bSortable": true,
                                "width": "20px"
                            },
                             {
                                /* KOLONA 2 */
                                "targets": 1,
                                "bVisible": true,
                                /*"data": "id_grad"*/
                                "data": "grad",
                                "bSortable": false,
                                "width": "20px"
                            },
                            {                             
                                "targets": 2,
                                "bVisible": true,
                                /*"data": "id_grad"*/
                                "data": "zupanija",
                                "bSortable": false,
                                "width": "20px"
                            
                            },
                            {
                                /* KOLONA 3 */
                                "targets": 3,
                                "bVisible": true,
                                /*"data": "id_vrsta_ispada",*/
                                "data": "vrsta_ispada",
                                "bSortable": false,
                                "width": "20px"
                            },
                             {
                                /* KOLONA 4 */
                                "targets": 4,
                                "bVisible": true,
                                "data": "pocetak_ispada",
                                "bSortable": false,
                                "width": "20px"
                            },
                            {
                                /* KOLONA 5 */
                                "targets": 5,
                                "bVisible": true,
                                "data": "kraj_ispada",
                                "bSortable": false,
                                "width": "20px"
                            },
                             {
                                /* KOLONA 6 */
                                "targets": 6,
                                "bVisible": true,
                                "data": "opis",
                                "bSortable": false,
                                "width": "20px"
                            },
                         
                        ],
                    "dom": 'lBf<"refresh">rtip',
                    "order": [0, 'asc'],
                    "iDisplayLength": 10,
                    "buttons": 
                    [
                    {
                        extend: 'colvis',
                        text: 'Kolone',
                        className: 'btn btn-default dropdown-toggle',
                        columns: ':not(.not_visible)' //th klasa not_visible za kolone koje se ne prikazuju
                    },               
                ]

                });

                dataTableInitialized_dasanji_user_view = true;
            }
        },
        error: function() {
            console.log('Nema postojećih podataka.');
        }
    });
}


function buildSelectDanasnji_user_view( table ) {
                       table.columns(2).every( function () {
                         var column = table.column( this, {search: 'applied'} );
                         var select = $('<select><option value="">Filtriraj po županiji</option></select>').appendTo( $('#dropdown_filter_zupanija_user_view').empty() )
                         .on( 'change', function () {
                           var val = $.fn.dataTable.util.escapeRegex(
                             $(this).val()
                           );

                           column
                           .search( val ? '^'+val+'$' : '', true, false )
                               .draw();
                             } );

                             column.data().unique().sort().each( function ( d, j ) {
                               select.append( '<option value="'+d+'">'+d+'</option>' );
                             } );
      
      // The rebuild will clear the exisiting select, so it needs to be repopulated
             var currSearch = column.search();
          //   console.log(currSearch)
             if ( currSearch ) {
               var unescaped = currSearch.replace(/\\/g, '');
      //  console.log(unescaped)
        select.val( unescaped.substring(1, unescaped.length-1) );
      }

    } );
}

function buildSelect1Danasnji_user_view( table ) {
                       table.columns(3).every( function () {
                         var column = table.column( this, {search: 'applied'} );
                         var select = $('<select><option value="">Filtriraj po vrsti ispada</option></select>').appendTo( $('#dropdown_filter_vrsta_ispada_user_view').empty() )
                         .on( 'change', function () {
                           var val = $.fn.dataTable.util.escapeRegex(
                             $(this).val()
                           );

                           column
                           .search( val ? '^'+val+'$' : '', true, false )
                               .draw();
                             } );

                             column.data().unique().sort().each( function ( d, j ) {
                               select.append( '<option value="'+d+'">'+d+'</option>' );
                             } );
      
      // The rebuild will clear the exisiting select, so it needs to be repopulated
             var currSearch = column.search();
          //   console.log(currSearch)
             if ( currSearch ) {
               var unescaped = currSearch.replace(/\\/g, '');
      //  console.log(unescaped)
        select.val( unescaped.substring(1, unescaped.length-1) );
      }

    } );
}







/*Prikaz svih ispada USER_VIEW*/
function PrikaziPodatkePovijest_user_view() {

    $.ajax({
        type: "GET",
        url: 'json.php?action=prikazi_pregled_zavrsenih_ispada',
        success: function (oData){

            console.log(oData);
            ispadiList_povijest_user_view = [];
            
            oData.forEach(function(povijest_ispad_user_view)
            {
                
                var object = {
                    id_ispad: povijest_ispad_user_view.id_ispad,
                    vrsta_ispada: povijest_ispad_user_view.vrsta_ispada,
                    grad: povijest_ispad_user_view.grad,
                    zupanija: povijest_ispad_user_view.zupanija,
                    pocetak_ispada: povijest_ispad_user_view.pocetak_ispada,
                    kraj_ispada: povijest_ispad_user_view.kraj_ispada,
                    opis: povijest_ispad_user_view.opis,
                  
                };
                ispadiList_povijest_user_view.push(object);
            });
            if(dataTableInitialized_povijest_user_view)
            {
                var table = $('#Tablica_povijesti_ispada_user_view').DataTable();
                buildSelectPovijest_ispada_user_view_zupanije(table);
                buildSelectPovijest_ispada_user_view_vrsta_ispada(table);
                table.on('draw', function(){
                    buildSelectPovijest_ispada_user_view_zupanije(table);
                    buildSelectPovijest_ispada_user_view_vrsta_ispada(table);
                });
         

                table.clear().draw();
                table.rows.add(ispadiList_povijest_user_view);             
                table.columns.adjust().draw();

            }
            else
            {

                oTable = $('#Tablica_povijesti_ispada_user_view').DataTable(
                {
                      "language": {
                      "url": "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Croatian.json"
                    },
                    "ooData": ispadiList_povijest_user_view,
                    "columnDefs":
                        [
                            {
                                /* KOLONA 1 */
                                "targets": 0,
                                "bVisible": true,
                                "data": 'id_ispad',
                                "bSortable": true,
                                "width": "20px"
                            },
                             {
                                /* KOLONA 2 */
                                "targets": 1,
                                "bVisible": true,
                                /*"data": "id_grad"*/
                                "data": "grad",
                                "bSortable": false,
                                "width": "20px"
                            },
                            {                             
                                "targets": 2,
                                "bVisible": true,
                                /*"data": "id_grad"*/
                                "data": "zupanija",
                                "bSortable": false,
                                "width": "20px"
                            
                            },
                            {
                                /* KOLONA 3 */
                                "targets": 3,
                                "bVisible": true,
                                /*"data": "id_vrsta_ispada",*/
                                "data": "vrsta_ispada",
                                "bSortable": false,
                                "width": "20px"
                            },
                             {
                                /* KOLONA 4 */
                                "targets": 4,
                                "bVisible": true,
                                "data": "pocetak_ispada",
                                "bSortable": false,
                                "width": "20px"
                            },
                            {
                                /* KOLONA 5 */
                                "targets": 5,
                                "bVisible": true,
                                "data": "kraj_ispada",
                                "bSortable": false,
                                "width": "20px"
                            },
                             {
                                /* KOLONA 6 */
                                "targets": 6,
                                "bVisible": true,
                                "data": "opis",
                                "bSortable": false,
                                "width": "20px"
                            },
                         
                        ],
                    "dom": 'lBf<"refresh">rtip',
                    "order": [0, 'asc'],
                    "iDisplayLength": 10,
                    "buttons": 
                    [
                    {
                        extend: 'colvis',
                        text: 'Kolone',
                        className: 'btn btn-default dropdown-toggle',
                        columns: ':not(.not_visible)' //th klasa not_visible za kolone koje se ne prikazuju
                    },               
                ]

                });

                dataTableInitialized_povijest_user_view = true;
            }
        },
        error: function() {
            console.log('Nema postojećih podataka.');
        }
    });
}



function buildSelectPovijest_ispada_user_view_zupanije( table ) {
                       table.columns(2).every( function () {
                         var column = table.column( this, {search: 'applied'} );
                         var select = $('<select><option value="">Filtriraj po županiji</option></select>').appendTo( $('#dropdown_filter_zupanija_user_view_svi_ispadi').empty() )
                         .on( 'change', function () {
                           var val = $.fn.dataTable.util.escapeRegex(
                             $(this).val()
                           );

                           column
                           .search( val ? '^'+val+'$' : '', true, false )
                               .draw();
                             } );

                             column.data().unique().sort().each( function ( d, j ) {
                               select.append( '<option value="'+d+'">'+d+'</option>' );
                             } );
      
      // The rebuild will clear the exisiting select, so it needs to be repopulated
             var currSearch = column.search();
          //   console.log(currSearch)
             if ( currSearch ) {
               var unescaped = currSearch.replace(/\\/g, '');
      //  console.log(unescaped)
        select.val( unescaped.substring(1, unescaped.length-1) );
      }

    } );
}

function buildSelectPovijest_ispada_user_view_vrsta_ispada( table ) {
                       table.columns(3).every( function () {
                         var column = table.column( this, {search: 'applied'} );
                         var select = $('<select><option value="">Filtriraj po vrsti ispada</option></select>').appendTo( $('#dropdown_filter_vrsta_ispada_user_view_svi_ispadi').empty() )
                         .on( 'change', function () {
                           var val = $.fn.dataTable.util.escapeRegex(
                             $(this).val()
                           );

                           column
                           .search( val ? '^'+val+'$' : '', true, false )
                               .draw();
                             } );

                             column.data().unique().sort().each( function ( d, j ) {
                               select.append( '<option value="'+d+'">'+d+'</option>' );
                             } );
      
      // The rebuild will clear the exisiting select, so it needs to be repopulated
             var currSearch = column.search();
          //   console.log(currSearch)
             if ( currSearch ) {
               var unescaped = currSearch.replace(/\\/g, '');
      //  console.log(unescaped)
        select.val( unescaped.substring(1, unescaped.length-1) );
      }

    } );
}


