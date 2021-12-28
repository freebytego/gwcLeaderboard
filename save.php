<?php
include 'database.php';

function get_leaderboard_data($db, $level) {
	$existRequest = "SELECT * FROM leaderboard WHERE level = :level AND version=:version ORDER BY time ASC";
	$sth = $db->prepare($existRequest);
	$sth->bindParam(':level', $level);
	$sth->bindValue(':version', !empty($_GET['version']) ? ''.$_GET['version'] : "0.1.3");
	$sth->execute();
	$sth->setFetchMode(PDO::FETCH_ASSOC);
	$doesExist = $sth->fetchAll();
	return $doesExist;
}

if (isset($_GET['key']) && isset($_GET['username']) && isset($_GET['password']) && isset($_GET['json_data'])) {
	$jsonString = $_GET['json_data'];
	$parsed = json_decode($jsonString, true);

	$nickname = $_GET['username'];
	$password = $_GET['password'];
	$key = $_GET['key'];

	$checkLogin = "SELECT * FROM users WHERE nickname=:username AND password=:password";
	$sth = $dbConnection->prepare($checkLogin);
	$sth->bindParam(':username', $nickname);
	$sth->bindParam(':password', $password);
	$sth->execute();
	$sth->setFetchMode(PDO::FETCH_ASSOC);

	$player_column = $sth->fetchColumn();

	if (!($player_column != false)) die("wrong");
	if (!($key == hash('sha256', $secret_key))) die("wrong");

	foreach ($parsed as $key => $data) {
		$doesExist = get_leaderboard_data($dbConnection, $key);
		$player_exists = false;
		$player_trusted = false;
		$needs_second_request = false;

		foreach ($doesExist as $column) {
			if ($column['nickname'] == $nickname) {
				$player_exists = true;
				break;
			}
		}

		if ($player_exists != false) {
			if ($player_trusted) {
				$sql = "UPDATE leaderboard SET time = :time, sec1 = :sec1, sec2 = :sec2, sec3 = :sec3, engine = :engine, pending = 'NO'
				WHERE nickname=:username AND level = :level AND version=:version";
			} else {
				$sql = "UPDATE leaderboard SET time = :time, sec1 = :sec1, sec2 = :sec2, sec3 = :sec3, engine = :engine, pending = 'YES'
				WHERE nickname=:username AND level = :level AND version=:version";
				$needs_second_request = true;
			}
		} else {
			if ($player_trusted) {
				$sql = 'INSERT INTO leaderboard (nickname, level, time, sec1, sec2, sec3, engine, version, pending) 
				VALUES (:username, :level, :time, :sec1, :sec2, :sec3, :engine, :version, "NO")';
			} else {
				$sql = 'INSERT INTO leaderboard (nickname, level, time, sec1, sec2, sec3, engine, version, pending) 
				VALUES (:username, :level, :time, :sec1, :sec2, :sec3, :engine, :version, "YES")';
				$needs_second_request = true;
			}
		}

		$sth = $dbConnection->prepare($sql);

		$_engineType = (!empty($data['engine']) ? "".$data['engine'] : "0");

		$sth->bindParam(':username', $nickname);
		$sth->bindParam(':time', $data['time']);
		$sth->bindParam(':sec1', $data['sec1']);
		$sth->bindParam(':sec2', $data['sec2']);
		$sth->bindParam(':sec3', $data['sec3']);
		$sth->bindParam(':engine', $_engineType);
		$sth->bindParam(':level', $key);
		$sth->bindValue(':version', !empty($_GET['version']) ? ''.$_GET['version'] : "0.1.3");

		$result = $sth->execute();
		if (!($result != false)) {
			die("Error");
		}

		if ($needs_second_request) {
			$place_request = get_leaderboard_data($dbConnection, $key);
			$counter = 0;
			foreach ($place_request as $column) {
				$counter += 1;
				if ($column['nickname'] == $nickname) {
					break;
				}
			}
			if ($counter > 5) {
				$sql = "UPDATE leaderboard SET pending = 'NO'
				WHERE nickname=:username AND level = :level AND version=:version";
				$sth = $dbConnection->prepare($sql);
				$sth->bindParam(':username', $nickname);
				$sth->bindParam(':level', $key);
				$sth->bindValue(':version', !empty($_GET['version']) ? ''.$_GET['version'] : "0.1.3");
				$result = $sth->execute();
				if (!($result != false)) {
					die("Error");
				}
			}
		}
	}
	echo "success";

} else {
	die("go away");
}

