<?php 

    include 'database.php';
    $timesArray = [];
    $version = "0.2.0";
    if(!isset($_GET['version'])) die("noversion");
    $version = $_GET['version'];
    if(!isset($_GET['level'])) die("nolevel");
    if (is_numeric($_GET['level']) && $_GET['level'] > 0 && $_GET['level'] <= $levelCount) {
        $level = $_GET['level'];
    } else {
        die("wrong");
    }

    $top = "SELECT nickname, level, time, version FROM leaderboard WHERE level=:track AND version=:version AND pending='NO' ORDER BY time LIMIT 5";
	$sth = $dbConnection->prepare($top);
	$sth->bindParam(':track', $level);
	$sth->bindValue(':version', $version);
	$sth->execute();
	$sth->setFetchMode(PDO::FETCH_ASSOC);
	$data = $sth->fetchAll();
    if ($data != false) {
        array_push($timesArray, $data);
    }
	echo json_encode($timesArray);
?>