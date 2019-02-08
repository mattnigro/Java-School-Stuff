<?php
header('Cache-Control: no-store, no-cache, must-revalidate');
header('Cache-Control: post-check=0, pre-check=0', FALSE);
header('Pragma: no-cache');

include '../inc/_init.php';
include '../view/ViewCharacter.php';


$Characters = new Characters($db);
if ($hasAccess = $Members->getAccessPermissions($_COOKIE['MID'])){

	$_cookieDough = $Members->getCookieData($_COOKIE['MID']);
	$MID = "{$_cookieDough['MID']}";

	if ($_GET['v'] == 'vc'){
		$_cryptCID = explode('O', $_GET['CID']);
		$CID = intval($_cryptCID[0] / 777);

		$ViewCharacter = new ViewCharacter($CID, $MID);
		$FetchCharacter = new FetchCharacter($db, $CID);
		$charDataTable 	= $ViewCharacter->drawCharacterHeader($CID, $db);
		$skillTable 	= $ViewCharacter->drawSkillsTable($FetchCharacter->fetchCharacterSkills(true));
		$comAbsTable 	= $ViewCharacter->drawComAbsTable($FetchCharacter->fetchComAbs());
		$WSTable 		= $ViewCharacter->drawWSTable($FetchCharacter->fetchWeaponSkills());
		$weaponsTable	= $ViewCharacter->drawWeaponsTable($FetchCharacter->fetchCharacterWeapons());
		$armorTable		= $ViewCharacter->drawArmorTable($FetchCharacter->fetchCharacterArmor());
		$powersTable 	= $ViewCharacter->drawPowersTable($FetchCharacter->fetchCharacterPowers());
		$ComAbsWSTable 	= "
			<table class='tblCharSheet'>
				<tr>
					<td class='tdCharSheetDelim'>

						<table style='width: 100%'>
							<tr>
								<td>
									<table style='width: 100%'>
										<tr>
											<td>
												{$comAbsTable}
											</td>
											<td>
												{$WSTable}
											</td>
										</tr>
									</table>
									<table style='width: 100%'>
										<tr>
											<td>
												{$weaponsTable}
											</td>
											<td>
												{$armorTable}
											<td>
											<td style='width: 150px'>
											</td>
										</tr>
									</table>
								</td>
								<td>
									{$powersTable}
								</td>
							</tr>
						</table>
					</td>
				</tr>
			</table>

					\n";
	}
	else {
		header('Location: ../login/?q=logout');
	}
}
else {
	header('Location: ../login/?q=logout');
}
?>
<!DOCTYPE html>
<html>

<head>
<title>Holy Lands RPG :: My Account</title>
<link rel="stylesheet" type="text/css" href="../styles_OLG.css">
<!--link rel="stylesheet" type="text/css" href="../styles.css"-->
<script type="text/javascript" src="../js/ui.js"></script>
</head>

<body>
<table style="margin: 30px auto">
	<tr>
		<td style="text-align: center;">
			<img src="../images/logoCharSheet.jpg">
		</td>
	</tr>
</table>
<?php
//print md5('password');
print $charDataTable;
print $skillTable;
print $ComAbsWSTable;
?>
</div>
</body>
</html>