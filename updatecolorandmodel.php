<?php 
	include 'database.php';
	if (!empty($_GET['key']) && !empty($_GET['nickname']) && !empty ($_GET['password']) && isset ($_GET['color']) && isset ($_GET['model'])) {
		$key = $_GET['key'];
		$username = $_GET['nickname'];
		$password = $_GET['password'];
		$color = $_GET['color'];
		$model = $_GET['model'];

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
				if ($key == hash('sha256', $secret_key)) {
					$sqlRequest = "UPDATE users SET color = :color, model = :model WHERE nickname=:username";
					$sth = $dbConnection->prepare($sqlRequest);
					$sth->bindParam(':username', $username);
					$sth->bindParam(':color', $color);
					$sth->bindParam(':model', $model);
					$result = $sth->execute();
					if ($result != false) {
						echo "poggers";
					} else {
						echo "not poggers";
					}
				} else {
					echo "error";
				}
			}
		}
	} else {
		echo "go away.";
	}
?>