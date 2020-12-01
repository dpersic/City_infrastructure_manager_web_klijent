<?php
include_once("dbc.php");
session_start();
$sActionID="";
if(isset($_POST['action_id']))
{
	$sActionID=$_POST['action_id'];
}
else
{
	//$sAction = $_GET['action'];
}

switch ($sActionID) 
{
	/*--------------------------------------------------------------------------WEB------------------------------------------------------------------------------------*/
   case 'dodaj_ispad_web':
   	
   	if(EMPTY($_POST['datum_vrijeme_zavrsetak_ispada']))
   	{
   	 	$sDatum_vrijeme_zavrsetka_ispada=null;
   	}
   	else
   	{
   		$sDatum_vrijeme_zavrsetka_ispada=$_POST['datum_vrijeme_zavrsetak_ispada'];
   	}

	 $sQuery = "INSERT INTO [PIS_TEST].[dbo].[03_Ispad]([03_Ispad].[ID_korisnik], [03_Ispad].[ID_vrsta_ispada], [03_Ispad].[ID_grad],[03_Ispad].[Datum_vrijeme_pocetka_ispada],[03_Ispad].[Datum_vrijeme_zavrsetka_ispada],[03_Ispad].[Dodatan_opis]) VALUES  (:ID_korisnik, :ID_vrsta_ispada, :ID_grad, :Datum_vrijeme_pocetka_ispada,:Datum_vrijeme_zavrsetak_ispada, :Dodatan_opis)";


		$oData = array(
		 'ID_korisnik' => $_POST['id_korisnik'],
		 'ID_vrsta_ispada' => $_POST['id_vrsta_ispada'],
		 'ID_grad' => $_POST['id_grad'],
		 'Datum_vrijeme_pocetka_ispada' => $_POST['datum_vrijeme_pocetka_ispada'],
		 'Datum_vrijeme_zavrsetak_ispada' => $sDatum_vrijeme_zavrsetka_ispada,
		 'Dodatan_opis' => $_POST['dodatan_opis']
		);
		try
		{
			$oStatement=$oDbConnector->prepare($sQuery);
			$oStatement->execute($oData);
			echo 1;
		}
		catch(PDOException $error)
		{
			echo $error;
			echo 0;
		}		
	break;

	case 'edit_ispad':
		$sQuery ="UPDATE [03_Ispad] SET
		 ID_korisnik =:ID_korisnik_edit,
		 ID_vrsta_ispada =:ID_vrsta_ispada_edit,
		 ID_grad =:ID_grad_edit,
		 Datum_vrijeme_zavrsetka_ispada =:Datum_vrijeme_zavrsetka_ispada_edit,
		 Dodatan_opis =:Dodatan_opis_edit WHERE ID_ispad =:ID_Ispada";

		$oData = array(
		
		 'ID_korisnik_edit' => $_POST['id_korisnik_edit'],
		 'ID_Ispada' => $_POST['id_ispad_edit'],
		 'Datum_vrijeme_zavrsetka_ispada_edit' => $_POST['datum_vrijeme_zavrsetak_ispada_edit'],
		 'ID_vrsta_ispada_edit' => $_POST['id_vrsta_ispada_edit'],
		 'ID_grad_edit' => $_POST['id_grad_edit'],
		 'Dodatan_opis_edit' => $_POST['dodatan_opis_edit']
		);
		try
		{
			$oStatement=$oDbConnector->prepare($sQuery);
			$oStatement->execute($oData);
			echo 1;
		}
		catch(PDOException $error)
		{
			echo $error;
			echo 0;
		}
	break;


	case 'delete_ispad':
   	$sQuery = "DELETE FROM [03_Ispad] WHERE ID_ispad=:id_ispada"; 
			$oData = array(
			 'id_ispada'=>$_POST['id_ispada']
			);	
			try
			{
				$oStatement=$oDbConnector->prepare($sQuery);
				$oStatement->execute($oData);
				echo 1;
			}
			catch(PDOException $error)
			{
				echo $error;
				echo 0;
			}
	break;




/*---------------------------------------------------------------------C#------------------------------------------------------------------------------------------*/
	case 'dodaj_ispad':
		$sQuery = "INSERT INTO [PIS_TEST].[dbo].[03_Ispad] (ID_korisnik, ID_vrsta_ispada, ID_grad, Datum_vrijeme_pocetka_ispada,Dodatan_opis) VALUES
		 (:ID_korisnik, :ID_vrsta_ispada, :ID_grad, :Datum_vrijeme_pocetka_ispada,:Dodatan_opis)";
		$oData = array(
		 'ID_korisnik' => $_POST['Id_Username'],
		 'ID_vrsta_ispada' => $_POST['Id_TypeOfFailure'],
		 'ID_grad' => $_POST['Id_City'],
		 'Datum_vrijeme_pocetka_ispada' => $_POST['BeginOfFailure'],
		 'Dodatan_opis' => $_POST['AdditionalDescription']
		);
		try
		{
			$oStatement=$oDbConnector->prepare($sQuery);
			$oStatement->execute($oData);
			echo 1;
			echo($sQuery);
		}
		catch(PDOException $error)
		{
			echo $error;
			echo 0;
		}		
	break;
}
?>