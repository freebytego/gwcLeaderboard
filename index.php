<?php
include 'database.php';
?>

<?php
function getStatWithLimit($limit, $conn, $track, $version, $listOfVersions)
{
	$sqlRequest = "SELECT * FROM leaderboard WHERE level=:track AND nickname!='placeholder' AND version=:ver AND pending='NO' ORDER BY time LIMIT :limits";
	$sth = $conn->prepare($sqlRequest);
	$sth->bindParam(':track', $track);
	$sth->bindParam(':limits', $limit);
	$sth->bindValue(':ver', $version);
	$sth->execute();
	$sth->setFetchMode(PDO::FETCH_ASSOC);
	$result = $sth->fetchAll();
	if ($result != false) {
		$count = 1;
		foreach ($result as $entry) {
?>
			<tr>
				<th class="table-spot" <?php if ($count == 1) echo "style='color: #15b9ca;'"; ?>><?php echo $count ?></th>
				<th class="table-nickname"><a href="profile.php?username=<?php echo $entry['nickname'] ?>"><?php echo $entry['nickname'] ?></a></th>
				<th class="table-time"><?php echo $entry['time'] ?></th>
				<th class="table-secTime"><?php echo $entry['sec1'] ?></th>
				<th class="table-secTime"><?php echo $entry['sec2'] ?></th>
				<th class="table-secTime"><?php echo $entry['sec3'] ?></th>
				<?php
				if (in_array($version, $listOfVersions)) {
					if (array_search($version, $listOfVersions) <= array_search("0.3.0", $listOfVersions)) { ?>
				<th class="table-engine"><?php
				switch ($entry['engine']) {
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
				?></th>
					<?php }
				}
				?>
			</tr>
<?php $count += 1;
		}
	}
}
?>

<!DOCTYPE html>
<html style="background: url(backgrounds/TP<?php
							if (isset($_GET['track']) && $_GET['track'] <= $levelCount) {
								echo $_GET['track'];
							} else if (empty($_GET['track']) || $_GET['track'] < 1 && $_GET['track'] > $levelCount) {
								echo "_def";
							}
							?>.png) no-repeat center center fixed; font-family: 'Quicksand', sans-serif;
	background-size: cover;">

<head>
	<title>Game with Car Leaderboard</title>
	<link rel="stylesheet" type="text/css" href="style.css">
	<link rel="icon" type="image/png" href="favicon.png" />

	<script>
		function getVersionOption() {
			let versionList = document.getElementById("versions");
			const queryString = window.location.search;
			const getParams = new URLSearchParams(queryString);
			if (getParams.has("version")) {
				getParams.set("version", versionList.value)
			} else {
				getParams.append("version", versionList.value)
			}
			window.location.replace(`${location.pathname}?${getParams}`);
		}
	</script>
	<script>
		function change_setting(type, object = null) {
			let List = document.getElementById(type);
			if (object != null) { List = object; }

			const queryString = window.location.search;
			const getParams = new URLSearchParams(queryString);
			if (getParams.has(type)) {
				getParams.set(type, List.value)
			} else {
				getParams.append(type, List.value)
			}
			window.location.replace(`${location.pathname}?${getParams}`);
		}
		function setVersionValue() {
			let versionList = document.getElementById("version");
			versionList.value = <?php echo isset($_GET['version']) ? '"'.$_GET['version'].'"' : '"'.$latestVersion.'"'; ?>
		}
	</script>
</head>

<body>
	<div class="leaderboard">
		<a href="index.php"><img src="logo.png" class="logo"></a>
		<div class="track-selector">
			<?php
				for ($i = 1; $i <= $levelCount; $i++) {
				if ($i <= 5) {
					echo '<input class="track-button-green" type="button" value="'.$i.'" name="track" onclick="change_setting(`track`, this);">';
				} else if ($i <= 10) {
					echo '<input class="track-button-blue" type="button" value="'.$i.'" name="track" onclick="change_setting(`track`, this);">';
				} else if ($i <= 15) {
					echo '<input class="track-button-purple" type="button" value="'.$i.'" name="track" onclick="change_setting(`track`, this);">';
				} else {
					echo '<input class="track-button-blue" type="button" value="'.$i.'" name="track" onclick="change_setting(`track`, this);">';
				}
			} ?>
		</div>
		<div class="version-selector">
			<b>Version:</b>
			<select name="versions" id="version" class="versions" onchange="change_setting('version')">
				<?php 
				foreach ($listOfVersions as $ver) {
					echo "<option value=".$ver.">".$ver."</option>";
				}
				?>
			</select>
			<script>setVersionValue();</script>
		</div>
		<b>Track <?php if (isset($_GET['track'])) echo $_GET['track'];
					else echo $defaultTrackSelected; ?></b>
		<div class="entries">
			<table class="table">
				<tr class="table-names">
					<th style='color: black'>#</th>
					<th style='color: black'>Username</th>
					<th style='color: black'>Time</th>
					<th>Sector 1</th>
					<th>Sector 2</th>
					<th>Sector 3</th>
					<?php
					if (in_array(isset($_GET['version']) ? $_GET['version'] : $latestVersion, $listOfVersions)) {
						if (array_search(isset($_GET['version']) ? $_GET['version'] : $latestVersion, $listOfVersions) <= array_search("0.3.0", $listOfVersions)) { ?>
					<th>Engine</th>
						<?php }
					}
					?>
				</tr>
				<?php
				if (isset($_GET['track'])) getStatWithLimit($entriesCount, $dbConnection, $_GET['track'], isset($_GET['version']) ? $_GET['version'] : $latestVersion, $listOfVersions);
				else getStatWithLimit($entriesCount, $dbConnection, $defaultTrackSelected, isset($_GET['version']) ? $_GET['version'] : $latestVersion, $listOfVersions);
				if (isset($_GET['track']) && $_GET['track'] > $levelCount) echo "What are you doing here";
				?>
			</table>
		</div>
	</div>
</body>
</html>