<?php
session_start();
include "dbc.php";

$sUsername=$_POST['sUsername'];
$sPassword=$_POST['sPassword'];

$sAction=$_POST['action_id'];

switch($sAction)
{
	case 'login':

		$response=[];

	$sQuery="SELECT [ID_korisnik],[korisnicko_ime],[lozinka],[ime],[prezime] FROM [PIS_TEST].[dbo].[03_Korisnik] WHERE [korisnicko_ime] ='$sUsername' AND [lozinka] = '$sPassword' ";
		  $oRecord = $oDbConnector->prepare($sQuery);
        $oRecord->execute();
        $oQueryData = $oRecord->fetchAll(PDO::FETCH_ASSOC);

        foreach($oQueryData as $oRow)  
		{
			 $oZapis['id_korisnik'] = $oRow['ID_korisnik'];
            $oZapis['korisnicko_ime'] = $oRow['korisnicko_ime'];
            $oZapis['lozinka'] = $oRow['lozinka'];
            $oZapis['ime'] = $oRow['ime'];
            $oZapis['prezime'] = $oRow['prezime'];
          

            $_SESSION['id'] = $oZapis['id_korisnik'];


			array_push($response, $oZapis);
		}

		echo json_encode($response);
	break;

	case 'logout':
		session_destroy();
	break;
}

?>