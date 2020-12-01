<?php

$sHost = "193.198.57.183";
$sUsername = "student";
$sPassword = "student";
$sDatabase = "PIS_TEST";

try 
	{
		$oDbConnector = new PDO("sqlsrv:Server=$sHost;Database=$sDatabase;ConnectionPooling=0", "$sUsername", "$sPassword");
		//echo "Spojen!";
	} 
catch (PDOException $e) 
	{
		echo "Pogreška";
	}

?>