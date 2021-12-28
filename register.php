<?php 
	include 'database.php';
	if (isset($_GET['key']) && isset($_GET['nickname']) && isset ($_GET['password']) && $_GET['key'] == hash('sha256', $secret_key)) {
		$username = $_GET['nickname'];
		$password = $_GET['password'];

		if ($username != strip_tags($username)) {
			die("tags");
		} else if ($username == "placeholder") {
			echo "error";
		} else {
			$username = htmlspecialchars($username);
			$availableRequest = "SELECT * FROM users WHERE nickname=:username";
			$sth = $dbConnection->prepare($availableRequest);
			$sth->bindParam(':username', $username);
			$sth->execute();
			$sth->setFetchMode(PDO::FETCH_ASSOC);
			$result = $sth->fetchColumn();
			if ($result != false) {
				die("taken");
			} else {
				$sqlRequest = "INSERT INTO users(nickname, password) VALUES (:username, :password)";
				$sth = $dbConnection->prepare($sqlRequest);
				$sth->bindParam(':username', $username);
				$sth->bindParam(':password', $password);
				$result = $sth->execute();
				if ($result != false) {
				  echo "success";
				} else {
				  echo "error";
				}
			}
		}
	} else {
		echo "go away.";
	}
?>