<?php 
	include 'database.php';
	if (isset($_GET['key']) && isset($_GET['username']) && isset($_GET['password']) && isset($_GET['newpassword'])) {
		$key = $_GET['key'];
		$username = $_GET['username'];
		$password = $_GET['password'];
		$new_password = $_GET['newpassword'];

		$checkLogin = "SELECT * FROM users WHERE nickname=:username AND password=:password";
		$sth = $dbConnection->prepare($checkLogin);
		$sth->bindParam(':username', $username);
		$sth->bindParam(':password', $password);
		$sth->execute();
		$sth->setFetchMode(PDO::FETCH_ASSOC);
		$result = $sth->fetchColumn();
		if ($result != false) {
			if ($key == hash('sha256', $secret_key)) {
				$sqlRequest = "UPDATE users SET password = :new_password WHERE nickname=:username AND password = :password";
					$sth = $dbConnection->prepare($sqlRequest);
					$sth->bindParam(':new_password', $new_password);
					$sth->bindParam(':username', $username);
					$sth->bindParam(':password', $password);
					$result = $sth->execute();
					if ($result != false) {
					  echo "success";
					} else {
					  echo "Error";
					}
			}
		} else {
			die("wrong");
		}	
	} else {
		echo "go away.";
	}
?>