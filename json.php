<?php
//POSTAVLJANJE VREMENA 
session_start();
date_default_timezone_set('Europe/Zagreb');
include_once("dbc.php");
header('Content-type: text/json');             
header('Content-type: application/json; charset=utf-8');

$sAction = $_GET['action'];

$oJson = array();


switch($sAction){
    //------>WEB<-----
    case "prikazi_pregled_zavrsenih_ispada":
         $sQuery = "SELECT [03_Ispad].[ID_ispad],[03_Korisnik].[ime],[03_Korisnik].[prezime],[03_Sifrarnik_vrsta_ispada].[vrsta_ispada],[03_Grad].[naziv_grad],[03_Grad].[lat], [03_Grad].lng,[03_Zupanija].[naziv_zupanija],[03_Ispad].[Datum_vrijeme_pocetka_ispada],[03_Ispad].[Datum_vrijeme_zavrsetka_ispada],[03_Ispad].[Dodatan_opis] FROM [PIS_TEST].[dbo].[03_Ispad] LEFT JOIN [03_Korisnik] ON [03_Ispad].ID_korisnik=[03_Korisnik].ID_korisnik LEFT JOIN [03_Grad] ON [03_Ispad].ID_grad=[03_Grad].ID_grad LEFT JOIN [03_Sifrarnik_vrsta_ispada] ON [03_Ispad].ID_vrsta_ispada=[03_Sifrarnik_vrsta_ispada].ID_vrsta_ispada LEFT JOIN [03_Zupanija] ON [03_Grad].ID_zupanija=[03_Zupanija].ID_zupanija";
      
        $oRecord = $oDbConnector->prepare($sQuery);
        $oRecord->execute();
        $oQueryData = $oRecord->fetchAll(PDO::FETCH_ASSOC);

        foreach($oQueryData as $oRow)    
        {   
            $oZapis['id_ispad'] = $oRow['ID_ispad'];
            $oZapis['vrsta_ispada'] = $oRow['vrsta_ispada'];
            $oZapis['grad'] = $oRow['naziv_grad'];
            $oZapis['zupanija'] = $oRow['naziv_zupanija'];
            $oZapis['pocetak_ispada'] = $oRow['Datum_vrijeme_pocetka_ispada'];
            $oZapis['lat'] = $oRow['lat'];
            $oZapis['lng'] = $oRow['lng'];          
            if(empty($oRow['Datum_vrijeme_zavrsetka_ispada']))
                {
                    $oZapis['kraj_ispada'] = "Trenutno nije predviđeno vrijeme sanacije problema";
                }
            else
                {
                      $oZapis['kraj_ispada'] = $oRow['Datum_vrijeme_zavrsetka_ispada'];
                }
            if(empty($oRow['Dodatan_opis']))
                {
                      $oZapis['opis'] = "Trenutno nisu predviđeni detalji problema";
                }
            else 
                {
                       $oZapis['opis'] = $oRow['Dodatan_opis'];
                }
             if($oRow['Datum_vrijeme_zavrsetka_ispada'] < $oRow['Datum_vrijeme_pocetka_ispada'])
                     {
                        $oZapis['status']="AKTIVAN";
                     }
                 else if($oRow['Datum_vrijeme_zavrsetka_ispada'] > $oRow['Datum_vrijeme_pocetka_ispada'])
                     {
                          $oZapis['status']="AKTIVAN";
                     }
                 else if($oRow['Datum_vrijeme_zavrsetka_ispada'] = $oRow['Datum_vrijeme_pocetka_ispada'])
                    {
                         $oZapis['status']="ZAVRŠEN";
                    }
                 if(empty($oRow['Datum_vrijeme_zavrsetka_ispada']))
                     {
                        $oZapis['status']="NIJE EVIDENTIRAN DATUM I VRIJEME";
                     }
            array_push($oJson, $oZapis);
            }
        break;

        case "populate_select_gradovi":
        $id_grada_odabrane_zupanije = $_GET["id_grada_odabrane_zupanije"];
        $sQuery = "SELECT [ID_grad],[ID_zupanija],[naziv_grad],[lat] ,[lng] FROM [PIS_TEST].[dbo].[03_Grad] WHERE ID_zupanija =".$id_grada_odabrane_zupanije;
        $oRecord = $oDbConnector ->query($sQuery);
        $oQueryData = $oRecord->fetchAll(PDO::FETCH_ASSOC);

        foreach ($oQueryData as $oRow)
        {
            $oZapis['id_grad'] = $oRow['ID_grad'];
            $oZapis['id_zupanija'] = $oRow['ID_zupanija'];
            $oZapis['naziv_grada'] = $oRow['naziv_grad'];
         

            array_push($oJson, $oZapis);
        }
        break;

        case "populate_select_zupanije":
        $sQuery = "SELECT [ID_zupanija],[naziv_zupanija] FROM [PIS_TEST].[dbo].[03_Zupanija]";

        $oRecord = $oDbConnector->prepare($sQuery);
        $oRecord->execute();
        $oQueryData = $oRecord->fetchAll(PDO::FETCH_ASSOC);

        foreach($oQueryData as $oRow)    
        {
            $oZapis['id_zupanija'] = $oRow['ID_zupanija'];
            $oZapis['naziv_zupanije'] = $oRow['naziv_zupanija'];
            array_push($oJson, $oZapis);
        }
        break;

        case "populate_select_vrste_ispada":
        $sQuery = "SELECT [ID_vrsta_ispada],[vrsta_ispada] FROM [PIS_TEST].[dbo].[03_Sifrarnik_vrsta_ispada]";
        $oRecord = $oDbConnector ->query($sQuery);
        $oQueryData = $oRecord->fetchAll(PDO::FETCH_ASSOC);

        foreach ($oQueryData as $oRow)
        {
            $oZapis['id_vrsta_ispada'] = $oRow['ID_vrsta_ispada']; 
            $oZapis['vrsta_ispada'] = $oRow['vrsta_ispada'];       
            array_push($oJson, $oZapis); 
        }      
        break;

        case "prikazi_korisnike":
        $sQuery = "SELECT [ID_korisnik],[korisnicko_ime],[lozinka],[ime],[prezime] FROM [PIS_TEST].[dbo].[03_Korisnik]";
        $oRecord = $oDbConnector ->query($sQuery);
        $oQueryData = $oRecord->fetchAll(PDO::FETCH_ASSOC);

        foreach ($oQueryData as $oRow)
        {
            $oZapis['id_korisnik'] = $oRow['ID_korisnik'];
            $oZapis['korisnicko_ime'] = $oRow['korisnicko_ime'];
            $oZapis['lozinka'] = $oRow['lozinka'];
            $oZapis['ime'] = $oRow['ime'];
            $oZapis['prezime'] = $oRow['prezime'];
         

            array_push($oJson, $oZapis);
        }      
    break;


        case "prikazi_pregled_ne_zavrsenih_ispada":
        $Danasnji_datum=date("Y-m-d"); /*Dohvati datum današnji npr 2020-10-05*/
        // var_dump($Danasnji_datum);

       $sQuery = "SELECT [03_Ispad].[ID_ispad],[03_Korisnik].[ime],[03_Korisnik].[prezime],[03_Sifrarnik_vrsta_ispada].[vrsta_ispada],[03_Grad].[naziv_grad],[03_Grad].[lat], [03_Grad].lng,[03_Zupanija].[naziv_zupanija],[03_Ispad].[Datum_vrijeme_pocetka_ispada],[03_Ispad].[Datum_vrijeme_zavrsetka_ispada],[03_Ispad].[Dodatan_opis] FROM [PIS_TEST].[dbo].[03_Ispad] LEFT JOIN [03_Korisnik] ON [03_Ispad].ID_korisnik=[03_Korisnik].ID_korisnik LEFT JOIN [03_Grad] ON [03_Ispad].ID_grad=[03_Grad].ID_grad LEFT JOIN [03_Sifrarnik_vrsta_ispada] ON [03_Ispad].ID_vrsta_ispada=[03_Sifrarnik_vrsta_ispada].ID_vrsta_ispada LEFT JOIN [03_Zupanija] ON [03_Grad].ID_zupanija=[03_Zupanija].ID_zupanija";

        
        $Danasnji_datum=date("Y-m-d");
        $oRecord = $oDbConnector->prepare($sQuery);
        $oRecord->execute();
        $oQueryData = $oRecord->fetchAll(PDO::FETCH_ASSOC);

        foreach($oQueryData as $oRow)    
        {
          //$Rbr++;
          //$oZapis['id_ispad'] = $oRow['ID_ispad'];                  
            if($Danasnji_datum != substr($oRow['Datum_vrijeme_pocetka_ispada'],0, 10))    
                continue;
            {    
                 $oZapis['id_ispad'] = $oRow['ID_ispad'];
                 $oZapis['lat'] = $oRow['lat'];
                 $oZapis['lng'] = $oRow['lng'];
                 $oZapis['danasnji_datum'] = $Danasnji_datum;
                 $oZapis['vrsta_ispada'] = $oRow['vrsta_ispada'];
                 $oZapis['grad'] = $oRow['naziv_grad'];
                 $oZapis['zupanija'] = $oRow['naziv_zupanija'];
                 $oZapis['pocetak_ispada'] =($oRow['Datum_vrijeme_pocetka_ispada']);
                 $oZapis['kraj_ispada'] = ($oRow['Datum_vrijeme_zavrsetka_ispada']);
                 if($oRow['Datum_vrijeme_zavrsetka_ispada'] < $oRow['Datum_vrijeme_pocetka_ispada'])
                     {
                        $oZapis['status']="AKTIVAN";
                     }
                 else if($oRow['Datum_vrijeme_zavrsetka_ispada'] > $oRow['Datum_vrijeme_pocetka_ispada'])
                     {
                          $oZapis['status']="AKTIVAN";
                     }
                 else if($oRow['Datum_vrijeme_zavrsetka_ispada'] = $oRow['Datum_vrijeme_pocetka_ispada'])
                    {
                         $oZapis['status']="ZAVRŠEN";
                    }
                 if(empty($oRow['Datum_vrijeme_zavrsetka_ispada']))
                     {
                        $oZapis['status']="NIJE EVIDENTIRAN DATUM I VRIJEME";
                     }
                if($oRow['Datum_vrijeme_zavrsetka_ispada'] == null) //tu ces provjrit jel je null kad ispravimo '1900-01-01 00:00:00.000'pokreni
                {
                    $oZapis['kraj_ispada']="Trenutno nije određen";
                }
                else
                {
                    $oZapis['kraj_ispada'];
                }
                if(empty($oRow['Dodatan_opis']))
                    {
                          $oZapis['opis'] = "Trenutno nije određen";
                    }
                else 
                    {
                          $oZapis['opis'] = $oRow['Dodatan_opis'];
                    }

           }        
            array_push($oJson, $oZapis);
        }
    break;

    case "dohvati_ispad":
        $Danasnji_datum=date("Y-m-d");
        $sID_ispada = $_GET["id"];

        $sQuery = /*"SELECT [03_Ispad].[ID_ispad],[03_Korisnik].[ime],[03_Korisnik].[prezime],[03_Sifrarnik_vrsta_ispada].[vrsta_ispada],[03_Grad].[naziv_grad],[03_Grad].[lat], [03_Grad].lng,[03_Zupanija].[naziv_zupanija],[03_Ispad].[Datum_vrijeme_pocetka_ispada],[03_Ispad].[Datum_vrijeme_zavrsetka_ispada],[03_Ispad].[Dodatan_opis] FROM [PIS_TEST].[dbo].[03_Ispad] LEFT JOIN [03_Korisnik] ON [03_Ispad].ID_korisnik=[03_Korisnik].ID_korisnik LEFT JOIN [03_Grad] ON [03_Ispad].ID_grad=[03_Grad].ID_grad LEFT JOIN [03_Sifrarnik_vrsta_ispada] ON [03_Ispad].ID_vrsta_ispada=[03_Sifrarnik_vrsta_ispada].ID_vrsta_ispada LEFT JOIN [03_Zupanija] ON [03_Grad].ID_zupanija=[03_Zupanija].ID_zupanija WHERE [03_Ispad].[ID_ispad]=".$sID_ispada;*/


        "SELECT 
            [03_Ispad].[ID_ispad],
            [03_Ispad].[ID_korisnik],
            [03_Ispad].[ID_vrsta_ispada],
            [03_Ispad].[ID_grad],
            [03_Ispad].[Datum_vrijeme_pocetka_ispada],
            [03_Ispad].[Datum_vrijeme_zavrsetka_ispada], 
            [03_Ispad].[Dodatan_opis],
            [03_Grad].[lat],
            [03_Grad].[lng],
            [03_Zupanija].[ID_zupanija]
             FROM [PIS_TEST].[dbo].[03_Ispad]
            LEFT JOIN [03_Grad] ON [03_Ispad].ID_grad=[03_Grad].ID_grad 
            LEFT JOIN [03_Zupanija] ON [03_Grad].ID_zupanija=[03_Zupanija].ID_zupanija WHERE [03_Ispad].[ID_ispad]=".$sID_ispada;
  
        $oRecord = $oDbConnector ->query($sQuery);
        $oQueryData = $oRecord->fetchAll(PDO::FETCH_ASSOC);

        foreach ($oQueryData as $oRow)
        {
             if($Danasnji_datum != substr($oRow['Datum_vrijeme_pocetka_ispada'],0, 10))    
                continue;

            {    
                 $oZapis['id_ispad'] = $oRow['ID_ispad']; //ID_Ispada = 246 
                 $oZapis['lat'] = $oRow['lat'];
                 $oZapis['lng'] = $oRow['lng'];
                 $oZapis['danasnji_datum'] = $Danasnji_datum;
                 $oZapis['vrsta_ispada'] = $oRow['ID_vrsta_ispada']; //drugi query
                // $oZapis['vrsta_ispada'] = $oRow['vrsta_ispada']; // 
                 $oZapis['grad'] = $oRow['ID_grad']; //drugi query
                // $oZapis['grad'] = $oRow['naziv_grad'];
                 $oZapis['zupanija'] = $oRow['ID_zupanija']; 
               //  $oZapis['zupanija'] = $oRow['naziv_zupanija'];
                 $oZapis['pocetak_ispada'] =($oRow['Datum_vrijeme_pocetka_ispada']);
                 $oZapis['kraj_ispada'] = ($oRow['Datum_vrijeme_zavrsetka_ispada']);
                 if($oRow['Datum_vrijeme_zavrsetka_ispada'] <= $oRow['Datum_vrijeme_pocetka_ispada'])
                     {
                        $oZapis['status']="NIJE RIJEŠENO";
                     }
                     elseif(empty($oRow['Datum_vrijeme_zavrsetka_ispada']))
                     {
                        $oZapis['status']="NIJE RIJEŠENO";
                     }
                     else
                     {
                         $oZapis['status']="RIJEŠENO";
                     }
                if($oRow['Datum_vrijeme_zavrsetka_ispada'] == null) //tu ces provjrit jel je null kad ispravim '1900-01-01 00:00:00.000'
                {
                    $oZapis['kraj_ispada']="Trenutno nije određen";
                }
                else
                {
                    $oZapis['kraj_ispada'];
                }
                if(empty($oRow['Dodatan_opis']))
                    {
                          $oZapis['opis'] = "Trenutno nije određen";
                    }
                else 
                    {
                          $oZapis['opis'] = $oRow['Dodatan_opis'];
                    }

           }       
            array_push($oJson, $oZapis); 
        }   
    break;


    
    




    

      
        //---->Andorid<-----
    case "prikazi_ispade_ispis":
        $sQuery = "SELECT [03_Ispad].[ID_ispad],[03_Korisnik].[ime],[03_Korisnik].[prezime],[03_Sifrarnik_vrsta_ispada].[vrsta_ispada],[03_Grad].[naziv_grad],[03_Grad].[lat], [03_Grad].lng,[03_Zupanija].[naziv_zupanija],[03_Ispad].[Datum_vrijeme_pocetka_ispada],[03_Ispad].[Datum_vrijeme_zavrsetka_ispada],[03_Ispad].[Dodatan_opis] FROM [PIS_TEST].[dbo].[03_Ispad] LEFT JOIN [03_Korisnik] ON [03_Ispad].ID_korisnik=[03_Korisnik].ID_korisnik LEFT JOIN [03_Grad] ON [03_Ispad].ID_grad=[03_Grad].ID_grad LEFT JOIN [03_Sifrarnik_vrsta_ispada] ON [03_Ispad].ID_vrsta_ispada=[03_Sifrarnik_vrsta_ispada].ID_vrsta_ispada LEFT JOIN [03_Zupanija] ON [03_Grad].ID_zupanija=[03_Zupanija].ID_zupanija ORDER BY [03_Ispad].Datum_vrijeme_zavrsetka_ispada ASC";


        $oRecord = $oDbConnector->prepare($sQuery);
        $oRecord->execute();
        $oQueryData = $oRecord->fetchAll(PDO::FETCH_ASSOC);

        foreach($oQueryData as $oRow)    
        {
          
            $oZapis['id_ispad'] = $oRow['ID_ispad'];
            $oZapis['ime'] = $oRow['ime'];
            $oZapis['prezime'] = $oRow['prezime'];
            $oZapis['vrsta_ispada'] = $oRow['vrsta_ispada'];
            $oZapis['grad'] = $oRow['naziv_grad'];
            $oZapis['lat'] = $oRow['lat'];
            $oZapis['lng'] = $oRow['lng'];
            $oZapis['zupanija'] = $oRow['naziv_zupanija'];
            $oZapis['pocetak_ispada'] = $oRow['Datum_vrijeme_pocetka_ispada'];
            $oZapis['kraj_ispada'] = $oRow['Datum_vrijeme_zavrsetka_ispada'];
            
            if($oRow['Datum_vrijeme_zavrsetka_ispada'] <= $oRow['Datum_vrijeme_pocetka_ispada'])
             {
                $oZapis['status']="NIJE RIJEŠENO";
             }
             elseif(empty($oRow['Datum_vrijeme_zavrsetka_ispada']))
             {
                $oZapis['status']="NIJE RIJEŠENO";
             }
             else
             {
                 $oZapis['status']="RIJEŠENO";
             }
            
            if(empty($oRow['Dodatan_opis']))
            {
                  $oZapis['opis'] = "Trenutno nije određen";
            }
            else 
            {
                  $oZapis['opis'] = $oRow['Dodatan_opis'];
            }
              
            array_push($oJson, $oZapis);
        }
        break;


        case "prikazi_rijesene_ispade":
        $sQuery = "SELECT [03_Ispad].[ID_ispad],[03_Korisnik].[ime],[03_Korisnik].[prezime],[03_Sifrarnik_vrsta_ispada].[vrsta_ispada],[03_Grad].[naziv_grad],[03_Grad].[lat], [03_Grad].lng,[03_Zupanija].[naziv_zupanija],[03_Ispad].[Datum_vrijeme_pocetka_ispada],[03_Ispad].[Datum_vrijeme_zavrsetka_ispada],[03_Ispad].[Dodatan_opis] FROM [PIS_TEST].[dbo].[03_Ispad] LEFT JOIN [03_Korisnik] ON [03_Ispad].ID_korisnik=[03_Korisnik].ID_korisnik LEFT JOIN [03_Grad] ON [03_Ispad].ID_grad=[03_Grad].ID_grad LEFT JOIN [03_Sifrarnik_vrsta_ispada] ON [03_Ispad].ID_vrsta_ispada=[03_Sifrarnik_vrsta_ispada].ID_vrsta_ispada LEFT JOIN [03_Zupanija] ON [03_Grad].ID_zupanija=[03_Zupanija].ID_zupanija WHERE [03_Ispad].Datum_vrijeme_zavrsetka_ispada IS NOT NULL ORDER BY [03_Ispad].Datum_vrijeme_pocetka_ispada DESC";


        $oRecord = $oDbConnector->prepare($sQuery);
        $oRecord->execute();
        $oQueryData = $oRecord->fetchAll(PDO::FETCH_ASSOC);

        foreach($oQueryData as $oRow)    
        {
          
            $oZapis['id_ispad'] = $oRow['ID_ispad'];
            $oZapis['ime'] = $oRow['ime'];
            $oZapis['prezime'] = $oRow['prezime'];
            $oZapis['vrsta_ispada'] = $oRow['vrsta_ispada'];
            $oZapis['grad'] = $oRow['naziv_grad'];
            $oZapis['lat'] = $oRow['lat'];
            $oZapis['lng'] = $oRow['lng'];
            $oZapis['zupanija'] = $oRow['naziv_zupanija'];
            $oZapis['pocetak_ispada'] = $oRow['Datum_vrijeme_pocetka_ispada'];
            $oZapis['kraj_ispada'] = $oRow['Datum_vrijeme_zavrsetka_ispada'];
            
            if($oRow['Datum_vrijeme_zavrsetka_ispada'] <= $oRow['Datum_vrijeme_pocetka_ispada'])
             {
                $oZapis['status']="NIJE RIJEŠENO";
             }
             elseif(empty($oRow['Datum_vrijeme_zavrsetka_ispada']))
             {
                $oZapis['status']="NIJE RIJEŠENO";
             }
             else
             {
                 $oZapis['status']="RIJEŠENO";
             }
            
            if(empty($oRow['Dodatan_opis']))
            {
                  $oZapis['opis'] = "Trenutno nije određen";
            }
            else 
            {
                  $oZapis['opis'] = $oRow['Dodatan_opis'];
            }
              
            array_push($oJson, $oZapis);
        }
        break;


    case "prikazi_trenutne_ispade":
        $sQuery = "SELECT [03_Ispad].[ID_ispad],[03_Korisnik].[ime],[03_Korisnik].[prezime],[03_Sifrarnik_vrsta_ispada].[vrsta_ispada],[03_Grad].[naziv_grad],[03_Grad].[lat], [03_Grad].lng,[03_Zupanija].[naziv_zupanija],[03_Ispad].[Datum_vrijeme_pocetka_ispada],[03_Ispad].[Datum_vrijeme_zavrsetka_ispada],[03_Ispad].[Dodatan_opis] FROM [PIS_TEST].[dbo].[03_Ispad] LEFT JOIN [03_Korisnik] ON [03_Ispad].ID_korisnik=[03_Korisnik].ID_korisnik LEFT JOIN [03_Grad] ON [03_Ispad].ID_grad=[03_Grad].ID_grad LEFT JOIN [03_Sifrarnik_vrsta_ispada] ON [03_Ispad].ID_vrsta_ispada=[03_Sifrarnik_vrsta_ispada].ID_vrsta_ispada LEFT JOIN [03_Zupanija] ON [03_Grad].ID_zupanija=[03_Zupanija].ID_zupanija WHERE [03_Ispad].Datum_vrijeme_zavrsetka_ispada IS NULL ORDER BY [03_Ispad].Datum_vrijeme_pocetka_ispada DESC";


        $oRecord = $oDbConnector->prepare($sQuery);
        $oRecord->execute();
        $oQueryData = $oRecord->fetchAll(PDO::FETCH_ASSOC);

        foreach($oQueryData as $oRow)    
        {
          
            $oZapis['id_ispad'] = $oRow['ID_ispad'];
            $oZapis['ime'] = $oRow['ime'];
            $oZapis['prezime'] = $oRow['prezime'];
            $oZapis['vrsta_ispada'] = $oRow['vrsta_ispada'];
            $oZapis['grad'] = $oRow['naziv_grad'];
            $oZapis['lat'] = $oRow['lat'];
            $oZapis['lng'] = $oRow['lng'];
            $oZapis['zupanija'] = $oRow['naziv_zupanija'];
            $oZapis['pocetak_ispada'] = $oRow['Datum_vrijeme_pocetka_ispada'];
            $oZapis['kraj_ispada'] = $oRow['Datum_vrijeme_zavrsetka_ispada'];
            
            if($oRow['Datum_vrijeme_zavrsetka_ispada'] <= $oRow['Datum_vrijeme_pocetka_ispada'])
             {
                $oZapis['status']="NIJE RIJEŠENO";
             }
             elseif(empty($oRow['Datum_vrijeme_zavrsetka_ispada']))
             {
                $oZapis['status']="NIJE RIJEŠENO";
             }
             else
             {
                 $oZapis['status']="RIJEŠENO";
             }
            
            if(empty($oRow['Dodatan_opis']))
            {
                  $oZapis['opis'] = "Trenutno nije određen";
            }
            else 
            {
                  $oZapis['opis'] = $oRow['Dodatan_opis'];
            }
              
            array_push($oJson, $oZapis);
        }
        break;

    case "prikazi_zupanije_android":
        $sQuery = "SELECT [ID_zupanija],[naziv_zupanija] FROM [PIS_TEST].[dbo].[03_Zupanija]";

        $oRecord = $oDbConnector->prepare($sQuery);
        $oRecord->execute();
        $oQueryData = $oRecord->fetchAll(PDO::FETCH_ASSOC);

        foreach($oQueryData as $oRow)    
        {
            $oZapis['id_zupanija'] = $oRow['ID_zupanija'];
            $oZapis['naziv_zupanije'] = $oRow['naziv_zupanija'];
            array_push($oJson, $oZapis);
        }
    break;


    case "prikazi_vrste_ispada_android":
        $sQuery = "SELECT [ID_vrsta_ispada],[vrsta_ispada] FROM [PIS_TEST].[dbo].[03_Sifrarnik_vrsta_ispada]";
        $oRecord = $oDbConnector ->query($sQuery);
        $oQueryData = $oRecord->fetchAll(PDO::FETCH_ASSOC);

        foreach ($oQueryData as $oRow)
        {
            $oZapis['id_vrsta_ispada'] = $oRow['ID_vrsta_ispada'];
            $oZapis['vrsta_ispada'] = $oRow['vrsta_ispada'];
         

            array_push($oJson, $oZapis);
        }      
    break;



       
    //---->C#<-----
    case "prikazi_ispade":
        $sQuery = "SELECT [ID_ispad],[ID_korisnik],[ID_vrsta_ispada],[ID_grad],[Datum_vrijeme_pocetka_ispada],[Datum_vrijeme_zavrsetka_ispada],[Dodatan_opis] FROM [PIS_TEST].[dbo].[03_Ispad] WHERE Datum_vrijeme_zavrsetka_ispada IS NULL";

        $oRecord = $oDbConnector->prepare($sQuery);
        $oRecord->execute();
        $oQueryData = $oRecord->fetchAll(PDO::FETCH_ASSOC);

        foreach($oQueryData as $oRow)    
        {
            $oZapis['id_ispad'] = $oRow['ID_ispad'];
            $oZapis['id_korisnik'] = $oRow['ID_korisnik'];
            $oZapis['id_vrsta_ispada'] = $oRow['ID_vrsta_ispada'];
            $oZapis['id_grad'] = $oRow['ID_grad'];
            $oZapis['pocetak_ispada'] = $oRow['Datum_vrijeme_pocetka_ispada'];
            $oZapis['kraj_ispada'] = $oRow['Datum_vrijeme_zavrsetka_ispada'];
            $oZapis['opis'] = $oRow['Dodatan_opis'];
            array_push($oJson, $oZapis);
        }
    break;

     case "prikazi_povijest_ispada":
        $sQuery = "SELECT [ID_ispad],[ID_korisnik],[ID_vrsta_ispada],[ID_grad],[Datum_vrijeme_pocetka_ispada],[Datum_vrijeme_zavrsetka_ispada],[Dodatan_opis] FROM [PIS_TEST].[dbo].[03_Ispad] WHERE Datum_vrijeme_zavrsetka_ispada IS NOT NULL";

        $oRecord = $oDbConnector->prepare($sQuery);
        $oRecord->execute();
        $oQueryData = $oRecord->fetchAll(PDO::FETCH_ASSOC);

        foreach($oQueryData as $oRow)    
        {
            $oZapis['id_ispad'] = $oRow['ID_ispad'];
            $oZapis['id_korisnik'] = $oRow['ID_korisnik'];
            $oZapis['id_vrsta_ispada'] = $oRow['ID_vrsta_ispada'];
            $oZapis['id_grad'] = $oRow['ID_grad'];
            $oZapis['pocetak_ispada'] = $oRow['Datum_vrijeme_pocetka_ispada'];
            $oZapis['kraj_ispada'] = $oRow['Datum_vrijeme_zavrsetka_ispada'];
            $oZapis['opis'] = $oRow['Dodatan_opis'];
            array_push($oJson, $oZapis);
        }
    break;

    case "prikazi_zupanije":
        $sQuery = "SELECT [ID_zupanija],[naziv_zupanija] FROM [PIS_TEST].[dbo].[03_Zupanija]";

        $oRecord = $oDbConnector->prepare($sQuery);
        $oRecord->execute();
        $oQueryData = $oRecord->fetchAll(PDO::FETCH_ASSOC);

        foreach($oQueryData as $oRow)    
        {
            $oZapis['id_zupanija'] = $oRow['ID_zupanija'];
            $oZapis['naziv_zupanije'] = $oRow['naziv_zupanija'];
            array_push($oJson, $oZapis);
        }
    break;


    case "prikazi_vrste_ispada":
        $sQuery = "SELECT [ID_vrsta_ispada],[vrsta_ispada] FROM [PIS_TEST].[dbo].[03_Sifrarnik_vrsta_ispada]";
        $oRecord = $oDbConnector ->query($sQuery);
        $oQueryData = $oRecord->fetchAll(PDO::FETCH_ASSOC);

        foreach ($oQueryData as $oRow)
        {
            $oZapis['id_vrsta_ispada'] = $oRow['ID_vrsta_ispada'];
            $oZapis['vrsta_ispada'] = $oRow['vrsta_ispada'];
         

            array_push($oJson, $oZapis);
        }      
    break;

    case "prikazi_korisnike":
        $sQuery = "SELECT [ID_korisnik],[korisnicko_ime],[lozinka],[ime],[prezime] FROM [PIS_TEST].[dbo].[03_Korisnik]";
        $oRecord = $oDbConnector ->query($sQuery);
        $oQueryData = $oRecord->fetchAll(PDO::FETCH_ASSOC);

        foreach ($oQueryData as $oRow)
        {
            $oZapis['id_korisnik'] = $oRow['ID_korisnik'];
            $oZapis['korisnicko_ime'] = $oRow['korisnicko_ime'];
            $oZapis['lozinka'] = $oRow['lozinka'];
            $oZapis['ime'] = $oRow['ime'];
            $oZapis['prezime'] = $oRow['prezime'];
         

            array_push($oJson, $oZapis);
        }      
    break;

      case "prikazi_gradove":
        $sQuery = "SELECT [ID_grad],[ID_zupanija],[naziv_grad],[lat],[lng] FROM [PIS_TEST].[dbo].[03_Grad]";
        $oRecord = $oDbConnector ->query($sQuery);
        $oQueryData = $oRecord->fetchAll(PDO::FETCH_ASSOC);

        foreach ($oQueryData as $oRow)
        {
            $oZapis['id_grad'] = $oRow['ID_grad'];
            $oZapis['id_zupanija'] = $oRow['ID_zupanija'];
            $oZapis['naziv'] = $oRow['naziv_grad'];
            $oZapis['lat'] = $oRow['lat'];
            $oZapis['lng'] = $oRow['lng'];
         

            array_push($oJson, $oZapis);
        }      
    break;


 }


echo json_encode($oJson);
?>

