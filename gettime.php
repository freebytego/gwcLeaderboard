<?php 
	include 'database.php';
	if (isset($_GET['key']) && isset($_GET['username']) && isset($_GET['password'])) {
		$key = $_GET['key'];
		$username = $_GET['username'];
		$password = $_GET['password'];

		$checkLogin = "SELECT * FROM users WHERE nickname=:username AND password=:password";
		$sth = $dbConnection->prepare($checkLogin);
		$sth->bindParam(':username', $username);
		$sth->bindParam(':password', $password);
		$sth->execute();
		$sth->setFetchMode(PDO::FETCH_ASSOC);
		$result = $sth->fetchColumn();
		if ($result != false) {
			if ($key == hash('sha256', $secret_key)) {
				$existRequest = "SELECT * FROM leaderboard WHERE nickname=:username";
				$sth = $dbConnection->prepare($existRequest);
				$sth->bindParam(':username', $username);
				$sth->execute();
				$sth->setFetchMode(PDO::FETCH_ASSOC);
				$data = $sth->fetchAll();
				if ($data != false) {
					echo(json_encode($data));
				} else {
					echo "nodata";
				}				
			}
		} else {
			die("Wrong login or password");
		}	
	} else {
		echo "go away.";
	}
?>