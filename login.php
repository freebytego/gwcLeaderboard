<?php 
	include 'database.php';
	if (!empty($_GET['nickname']) && !empty ($_GET['password'])) {
		$username = $_GET['nickname'];
		$password = $_GET['password'];

		if ($username != strip_tags($username)) {
			die("tags");
		} else {
			$username = htmlspecialchars($username);
			$sqlRequest = "SELECT * FROM users WHERE nickname=:username AND password=:password";
			$sth = $dbConnection->prepare($sqlRequest);
			$sth->bindParam(':username', $username);
			$sth->bindParam(':password', $password);
			$sth->execute();
			$sth->setFetchMode(PDO::FETCH_ASSOC);
			$result = $sth->fetchColumn();
			if ($result != false) {
				echo "success";
			} else {
				echo "error";
			}
		}
	} else {
		echo "go away.";
	}
?>