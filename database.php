<?php 
	$ip = 'localhost';
	
	
	
	
	
	$levelCount = 15;
	$entriesCount = 20;
	$defaultTrackSelected = 1;
	$listOfVersions = array("0.3.0", "0.2.0", "0.1.4", "0.1.3");
	$latestVersion = $listOfVersions[0];


	
	$username = '{HERE IS THE USERNAME FOR DB}';
	$password = '{AND PASSWORD}';
	$database = '{AND THE DATABASE ITSELF';

	$secret_key = "SECRET KEY FROM THE GAME";

	$dbConnection = new PDO('mysql:dbname='.$database.';host='.$ip.';charset=utf8', $username, $password);
	$dbConnection->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
	$dbConnection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

?>