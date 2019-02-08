<?php
include '../inc/_init.php';
$toID = "{$_GET['toID']}";
$query = "SELECT COUNT(NID) FROM `notifications` WHERE `toID`='{$toID}' AND `MODIFIED`=''";
if ($result = mysqli_query($db, $query)){
	while ($_sql = mysqli_fetch_assoc($result)){
		foreach ($_sql as $key => $value){

			$notes = "{$_sql['COUNT(NID)']}";
		}
	}
}
if ($notes > 0){

	print $notes;
}
?>