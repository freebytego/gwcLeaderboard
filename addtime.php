<?php 
	include 'database.php';
	if (isset($_GET['key']) && isset($_GET['username']) && isset($_GET['password']) && isset($_GET['time']) && isset($_GET['sec1']) && isset($_GET['sec2']) && isset($_GET['sec3']) && isset($_GET['level'])) {
		$key = $_GET['key'];
		$username = $_GET['username'];
		$password = $_GET['password'];
		$level = $_GET['level'];
		$time = $_GET['time'];
		$sec1 = $_GET['sec1'];
		$sec2 = $_GET['sec2'];
		$sec3 = $_GET['sec3'];

		$checkLogin = "SELECT * FROM users WHERE nickname=:username AND password=:password";
		$sth = $dbConnection->prepare($checkLogin);
		$sth->bindParam(':username', $username);
		$sth->bindParam(':password', $password);
		$sth->execute();
		$sth->setFetchMode(PDO::FETCH_ASSOC);
		$result = $sth->fetchColumn();
		if ($result != false) {
			if ($key == hash('sha256', $secret_key)) {
				$existRequest = "SELECT * FROM leaderboard WHERE nickname=:username AND level = :level";
				$sth = $dbConnection->prepare($existRequest);
				$sth->bindParam(':username', $username);
				$sth->bindParam(':level', $level);
				$sth->execute();
				$sth->setFetchMode(PDO::FETCH_ASSOC);
				$doesExist = $sth->fetchColumn();
				if ($doesExist != false) {
					if (in_array($username, $trusted_people)) {
						$sqlRequest = "UPDATE leaderboard SET time = :time, sec1 = :sec1, sec2 = :sec2, sec3 = :sec3, pending='NO'
						WHERE nickname=:username AND level = :level";
					} else {
						$sqlRequest = "UPDATE leaderboard SET time = :time, sec1 = :sec1, sec2 = :sec2, sec3 = :sec3, pending='YES'
						WHERE nickname=:username AND level = :level";
					}
					$sth = $dbConnection->prepare($sqlRequest);
					$sth->bindParam(':time', $time);
					$sth->bindParam(':sec1', $sec1);
					$sth->bindParam(':sec2', $sec2);
					$sth->bindParam(':sec3', $sec3);
					$sth->bindParam(':username', $username);
					$sth->bindParam(':level', $level);
					$result = $sth->execute();
					if ($result != false) {
					  echo "success";
					} else {
					  echo "Error";
					}
				} else {
					if (in_array($username, $trusted_people)) {
						$sqlRequest = "INSERT INTO leaderboard(nickname, level, time, sec1, sec2, sec3, pending) VALUES (:username, :level, :time, :sec1, :sec2, :sec3, 'NO')";
					} else {
						$sqlRequest = "INSERT INTO leaderboard(nickname, level, time, sec1, sec2, sec3, pending) VALUES (:username, :level, :time, :sec1, :sec2, :sec3, 'YES')";
					}
					$sth = $dbConnection->prepare($sqlRequest);
					$sth->bindParam(':time', $time);
					$sth->bindParam(':sec1', $sec1);
					$sth->bindParam(':sec2', $sec2);
					$sth->bindParam(':sec3', $sec3);
					$sth->bindParam(':username', $username);
					$sth->bindParam(':level', $level);
					$result = $sth->execute();
					if ($result != false) {
					  echo "success";
					} else {
					  echo "Error";
					}
				}
				
			}
		} else {
			die("Wrong login or password");
		}	
	} else {
		echo "go away.";
	}
?>