<?php

include 'database.php';

function player_is_trusted($nickname, $conn) {
    $sql = "SELECT trusted FROM users WHERE nickname = :username";
    $sth = $conn->prepare($sql);
    $sth->bindParam(':username', $nickname);
    $sth->execute();
	$sth->setFetchMode(PDO::FETCH_ASSOC);
	$data = $sth->fetchColumn();
    if ($data == "YES") {
        return true;
    } else {
        return false;
    }
}

?>