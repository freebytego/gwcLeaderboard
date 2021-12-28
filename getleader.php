<?php 

	include 'database.php';



	function get_leader_for_version($version, $level, $dbConnection) {

		$top = "SELECT nickname, level, time, version FROM leaderboard WHERE level=:track AND version=:version AND pending='NO' ORDER BY time LIMIT 1";

		$sth = $dbConnection->prepare($top);

		$sth->bindParam(':track', $level);

		$sth->bindValue(':version', $version);

		$sth->execute();

		$sth->setFetchMode(PDO::FETCH_ASSOC);

		$data = $sth->fetchAll();

		if ($data != false) {

			return $data;

		} else {

			$json_string = "{\"nickname\": \"placeholder\",\"level\": {$level},\"time\": \"99:99.99\",\"version\": \"{$version}\"}";

			return ([json_decode($json_string)]);

		}

	}

	$best_times = array();

	for($i = 1; $i <= $levelCount; $i++) {

		foreach ($listOfVersions as $version) {
			array_push($best_times, get_leader_for_version($version, $i, $dbConnection));
		}	

	}

	echo json_encode($best_times);

?>