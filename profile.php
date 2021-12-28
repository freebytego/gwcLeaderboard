<?php 
	include 'database.php';
	include 'colorandmodel.php';
	if (!isset($_GET['username'])) {
		header('Location: ../');
	} else {
		$profileUsername = $_GET['username'];
		$checkExists = "SELECT * FROM users WHERE nickname=:profileUsername";
		$sth = $dbConnection->prepare($checkExists);
		$sth->bindParam(':profileUsername', $profileUsername);
		$sth->execute();
		$sth->setFetchMode(PDO::FETCH_ASSOC);
		$result = $sth->fetch();	
		if ($result != false) {
			$color_id = $result['color'];
			$model_id = $result['model'];
		} else {
			header('Location: ../');
		}
	}
?>

<!DOCTYPE html>
<html style="background: url(backgrounds/TP<?php

	$bgLevel = rand(0,$levelCount);
	if ($bgLevel == 0) {
		echo "_def";
	} else {
		echo($bgLevel);
	}

 ?>.png) no-repeat center center fixed; font-family: 'Quicksand', sans-serif;
	background-size: cover;">
<head>
	<title>Game with Car Profile</title>
	<link rel="stylesheet" type="text/css" href="style.css">
	<link rel="icon" type="image/png" href="favicon.png"/>
</head>
<body>
	<div class="leaderboard">
		<a href="index.php"><img src="logo.png" class="logo"></a>
		<div class="profile">
			<b class="profile-nickname"><?php

			if ($profileUsername == "freebyte" or $profileUsername == "KVBA") {
				echo($profileUsername." "."(╯°□°）╯");
			} else {
				echo($profileUsername);
			}
			
			
			?></b>
			<b>Car details:</b>
			<div class="car-info">
				<div class="car-color-container"><div class="car-color" style="background-color: <?php echo returnColor($color_id); ?>"></div>
			</div>
				<div class="car-model-container"><img src="<?php echo returnModel($model_id); ?>" class="car-model"></div>
			</div>
			<div class="times">
				
				<?php 
					$sqlRequest = "SELECT * FROM leaderboard WHERE nickname=:profileUsername";
					$sth = $dbConnection->prepare($sqlRequest);
					$sth->bindParam(':profileUsername', $profileUsername);
					$sth->execute();
					$sth->setFetchMode(PDO::FETCH_ASSOC);
					$result = $sth->fetchAll();

					$pendingAmount = 0;
					if ($result != false) {
						array_multisort(array_column($result, 'level'), SORT_ASC, $result);
						foreach ($result as $row) {
							if ($row['pending'] == 'NO') {?>
							<div class="time">
								<b class="profile-track">Track <?php echo $row['level']; ?></b>
								<p class="profile-time"><?php echo $row['time']; ?></p>
								<p class="profile-sector">S1: <?php echo $row['sec1']; ?></p>
								<p class="profile-sector">S2: <?php echo $row['sec2']; ?></p>
								<p class="profile-sector">S3: <?php echo $row['sec3']; ?></p>
								<?php
								if (in_array($row['version'], $listOfVersions)) {
									if (array_search($row['version'], $listOfVersions) <= array_search("0.3.0", $listOfVersions)) { ?>
										<p class="profile-engine">Engine: <?php
										switch ($row['engine']) {
										case 1:
											echo "Speed";
											break;
										case 2:
											echo "Acceleration";
											break;
										case 3:
											echo "Turn";
											break;
										default:
											echo "Speed";
											break;
									}
									?></p>
										<?php }
									}
									?>
								<p class="profile-version">Version: <?php echo $row['version']; ?></p>
							</div>
				<?php   	} else {
								$pendingAmount += 1;
							}
						}
					}
				?>
			</div>
			<div class="times">
			<?php if ($pendingAmount > 0) { ?>
				<div class="time" style="width: 35%;">
					<b class="profile-version">Waiting for approval: <?php echo $pendingAmount; ?> level(s)</b>
				</div>
			<?php } ?>
			</div>
		</div>
	</div>
</body>
</html>