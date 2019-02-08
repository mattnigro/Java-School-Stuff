<?php
header('Cache-Control: no-store, no-cache, must-revalidate');
header('Cache-Control: post-check=0, pre-check=0', FALSE);
header('Pragma: no-cache');

include '../inc/_init.php';

if ($hasAccess = $Members->getAccessPermissions($_COOKIE['MID'])){
	$_cookieDough = $Members->getCookieData($_COOKIE['MID']);
	$MID = $_cookieDough['MID'];

}
else {
	header('Location: ../login/?q=logout');
	die();
}

include '../controller/Dice.php';
include '../controller/CharacterCreation.php';
include '../view/CreateCharacter.php';
include '../view/ViewCharacter.php';
$_POST['action'] 	= (isset($_POST['action']) ? $_POST['action'] : null);
$_POST['staging'] 	= (isset($_POST['staging']) ? $_POST['staging'] : null);
$_GET['q'] 			= (isset($_GET['q']) ? $_GET['q'] : null);

if (($_POST['action']) == 'DELETE_ALL'){

	function deleteALL_characters($db){

		$deleting = null;
		$_charTables = array(
			's',
			'_abilities',
			'_attributes',
			'_comabs',
			'_eav',
			'_powers',
			'_saves',
			'_skills',
			'_variables',
			'_weapons',
			'_ws'
		);
			foreach ($_charTables as $charTable) {
				$dbTable = 'character' . $charTable;
				$sql = "DELETE FROM $dbTable WHERE 1";
				$deleting .= "$sql<br>";
				if (!$sql = mysqli_query($db,$sql)){

					print "<br>FAILED TO DELETE FROM $charTable " . mysqli_errno($db);
				}
			}
	}
	deleteALL_characters($db);
}
$tblCharInit 			= null;
$_selectSkillsTables 	= array();
$ComAbsWSTable 			= null;
$skillTable 			= null;
$deleting 				= null;
$_charEAV				= array();

//  = = = = = RESUME INCOMPLETE CHARACTER = = = = = //
$CID		 	= $Members->resumeUnfinishedCharacter($MID);
if ($lockStep	= $DataModel->getLockStep($CID)){
	$_lockStep 	= $DataModel->getLockStepCharAttribs($CID);
	$stature 	= $_lockStep['stature'];
	$gender 	= $_lockStep['gender'];
	$classID 	= $_lockStep['classID'];
}
$postAction 	= isset($_POST['action']) ? $_POST['action'] : null;
$postStaging 	= isset($_POST['staging']) ? $_POST['staging'] : null;

$Dice = new Dice(); // INITIALIZE DICE
$SelectCharacter 	= new SelectCharacter($db); // Initialize Class Options
$CreateCharacter 	= new CreateCharacter();

// LockStep 0: SELECT FROM CHAR CLASSES

$characterTable = null;
if ("{$_GET['q']}" == 'sc'){
	$jMessage - null;
	if (empty($lockStep)){
		$_charClasses 	= $SelectCharacter->characterClasses;
		$characterTable = $CreateCharacter->SelectClasses($_charClasses, $MID);
		if (isset($_GET['msg'])){
			$jMessage = (($_GET['msg'] == 'charDel') ? 'Previous Character Deleted' : $jMessage);
		}
	}
	else {
		$Characters = new Characters($db);
		$Characters->deleteCharacter($CID);
		header ('Location: ../olg/?q=sc&msg=charDel');
		die();
	}
}
// LockStep 1: DISPLAY INITAL CLASS OPTIONS
if ($postAction == 'initChar'){

	//dd($lockStep);
	if ($lockStep <= 1){
		if (!empty($_POST['sID'])){
			$_sID 		= explode('-',"{$_POST['sID']}");
			$classID 	= "{$_sID[0]}";
			$stature 	= "{$_POST[$classID]}";
			$gender 	= "{$_POST['gender']}";
			$CID 		= $DataModel->nextID('characters');
		}

		// = = = = = = GET INIT CLASS OPTIONS = = = = = = = = //
		$_attribDice 		= $SelectCharacter->selectStatureDice($stature, $classID);
		$_charVarDice 		= $SelectCharacter->selectCharVariablesDice($classID);
		$_heightRange		= $SelectCharacter->heightRange[$stature];
		$_charNames 		= $SelectCharacter->characterNames[$gender];
		$_classBlessings	= $SelectCharacter->classBlessings;
		$_saveOpts			= $SelectCharacter->saveOptions;
		$_WSAtR				= $SelectCharacter->weaponSkills;
		$_classFacts		= $SelectCharacter->classFacts;

		// = = = = = = ROLL INIT CLASS OPTIONS = = = = = = = = //
		$CharacterCreation	= new CharacterCreation($CID, $stature, $classID, $gender);
		$_charAttribs 	= $CharacterCreation->rollAttributes($_attribDice);
		$_charAbils 	= $CharacterCreation->calcAbilities($_charAttribs);
		$_charVars 		= $CharacterCreation->rollCharVariables($_charVarDice, $_charAttribs);
		$_charSaves		= $CharacterCreation->findCharSaves($_saveOpts, $_charAttribs['BONUS']);
		$_charComabs 	= $CharacterCreation->calcCombatAbilities($_charAttribs);
		$_character 	= $CharacterCreation->findCharacterData($_heightRange, $_charAttribs, $_charNames);
		$_charProbs 	= $CharacterCreation->findSinsPhobias($_charAttribs, $_classFacts);
		$_charPowers	= $CharacterCreation->findBlessings($SelectCharacter->blessingOptions($classID), $_charVars['Faith']);

		// = = = = = = SAVE INIT CLASS OPTIONS = = = = = = = = //
		$_charInit = array(
			'CID' 			=> $CID,
			'MID' 			=> $MID,
			'character' 	=> $_character,
			'charAttribs' 	=> $_charAttribs,
			'charAbils' 	=> $_charAbils,
			'charVars' 		=> $_charVars,
			'charSaves'		=> $_charSaves,
			'charComAbs' 	=> $_charComabs,
			'charProbs' 	=> $_charProbs,
			'charPowers' 	=> $_charPowers,
			'WSAtR'			=> $_WSAtR
		);
		$_charAttribBonuses = $SelectCharacter->saveCharInit($_charInit);
		$DataModel->incrementLockStep($CID); // LockStep = 1
	}
	else if ($lockStep == 1){
		$_classFacts = $SelectCharacter->classFacts;
		$_saveOpts = $SelectCharacter->saveOptions;
	}
	else {
		$postStaging = 'skills';
	}
	// = = = = = = DISPLAY INIT CLASS OPTIONS = = = = = = = = //
	$FetchCharacter = new FetchCharacter($db, $CID);
	$tblCharInit = $CreateCharacter->initCharDetails($FetchCharacter->fetchCharacterData(), $_classFacts, $_saveOpts);
}
// = = =  =+== += = += =+ =+=+ = =+ = = +==  = += = = =+ = = =+= = =+= = + = = += == =+ =+ = += +=+ = ///
if($postStaging == 'skills'){

	if (isset($_POST['sID'])){
		$_IDs 			= $Controls->decryptSubmitIDs("{$_POST['sID']}");
		$classID 		= "{$_IDs['classID']}";
		$CID 			= "{$_IDs['CID']}";
		$MID 			= "{$_IDs['MID']}";
	}
	if (isset($_POST['btnDelete'])){
		$Characters = new Characters($db);
		$Characters->deleteCharacter($CID);
		header ('Location: ../olg/?q=sc');
		die();
	}
	$ViewCharacter 		= new ViewCharacter($CID, $MID);
	$FetchCharacter		= new FetchCharacter($db, $CID);
	$_charData			= $FetchCharacter->fetchCharacterData();
	$_charSkills		= $_charData['charSkills'];
	$_classSkills 		= $SelectCharacter->classSkills[$classID];
	$CharacterCreation	= new CharacterCreation($CID, $_charData['character']['stature'], $classID, $_charData['character']['gender']);
	$_selectedSkills = (!empty($_POST['SkID']) ? $_POST['SkID'] : array());
}
// 2. UPDATE ANY CHANGES TO CHAR DETAILS; DISPLAY SKILLS TABLE
if ($postAction == 'charInit'){
	$_updates = array(
		'charName' 	=> $_POST['charName'],
		'land' 		=> $_POST['land'],
		'lang' 		=> $_POST['lang'],
		'age' 		=> $_POST['age'],
		'saves' 	=> $_POST['saves']
	);
	$SelectCharacter->updateCharInit($_updates, $_IDs);

	if (!$_charGifts = $CharacterCreation->findClassGifts($_classSkills, $_charSkills)){
		$_selectSkillsTables = $CreateCharacter->selectGiftsTable($_classSkills); // CREATE GIFTS TABLE FOR ADVENTURER AND FIGHTER
		$_selectSkillsTables['frmAction'] = 'sTfIg4';
	}
	else {
		$postAction = 'sTfIg4';
	}
}
if ($postAction == 'sTfIg4'){ // = = = GIFTS TO BE ENTERED; ADVENTURER OR FIGHTER HAVE SELECTED GIFTS

	if (($classID == 'adv') || ($classID == 'fig')){
		$_charGifts = $CharacterCreation->countSelectedSkills($_classSkills, 'gifts', array(), $_selectedSkills);
		$SelectCharacter->insertCharSkills($CID, $_charGifts,'gifts', 2);
	}
	else {
		$_charGifts = $CharacterCreation->findClassGifts($_classSkills);
		$SelectCharacter->insertCharSkills($CID, $_charGifts,'gifts', 3);
	}

	$_selectSkillsTables = $CreateCharacter->selectTalentsTable($_charGifts, $_classSkills, $classID);
	$_selectSkillsTables['frmAction'] = 'stnElat2';
}
 if ($postAction == 'stnElat2'){ // TALENTS SELECTED = = ALL CLASSES

 	$_charTalents = $CharacterCreation->countSelectedSkills($_classSkills, 'talents', $_charSkills, $_selectedSkills);
	$SelectCharacter->insertCharSkills($CID, $_charTalents,'talents', 2);

	$_selectSkillsTables = $CreateCharacter->selectCraftsTable($_charSkills['gifts'], $_charTalents, $_classSkills, $classID);
	$_selectSkillsTables['frmAction'] = 'StFarC12';
}
else if ($postAction == 'StFarC12'){ // CRAFTS SELECTED = = ALL CLASSES

	$_charCrafts = $CharacterCreation->countSelectedSkills($_classSkills, 'crafts', $_charSkills, $_selectedSkills);
	if (($classID == 'adv') || ($classID == 'fig')){
		$_charCrafts = $CharacterCreation->countSelectedSkills($_classSkills, 'crafts', $_charSkills, $_selectedSkills);
		$SelectCharacter->insertCharSkills($CID, $_charCrafts,'crafts', 2);
	}
	else {
		$_charCrafts = $CharacterCreation->countSelectedSkills($_classSkills, 'crafts', $_charSkills, $_selectedSkills);
		$SelectCharacter->insertCharSkills($CID, $_charCrafts,'crafts', 1);
	}
	//dd($_charCrafts);
	$_selectSkillsTables = $CreateCharacter->confirmSkillsTable(
		$_charSkills,
		$_charCrafts,
		$classID,
		$SelectCharacter->findSpecialistPF($CID)
	);
	$_selectSkillsTables['frmAction'] = 'sBAmoC7';
}
if ($postAction == 'sBAmoC7'){

	if ($_specialists = (isset($_POST['spec']) ? $_POST['spec'] : null)){
		$SelectCharacter->updateSpecialistSkills($_specialists, $CID);
	}
	$_charWS = array();
	$_charSkills = $FetchCharacter->fetchCharacterSkills();
	$skillTable = $ViewCharacter->drawSkillsTable($_charSkills);
	if (!empty($_charWS = $CharacterCreation->findWSComAbs($_charSkills))){
		$_weaponSkills = $SelectCharacter->weaponSkills; // Selects CLASS Weapon Skills
		//dd($_POST);
		$comAbsTable = $CreateCharacter->selectComAbsTable($_charWS['Combat Abilities'], $_charData['charComAbs']);
		$ComAbsWSTable = $CreateCharacter->displayWSTable($comAbsTable, $_weaponSkills, $_IDs, $_charWS);
	}
}
if ($postAction == 'RoMRa2SNOpaEW40'){ // TODO ADD AtR BONUSES to WSs

	$_selectedOpts = $_POST;
	$SelectCharacter->updateComAbs($CID, $_selectedOpts); // UPDATE and RETURN UPDATED COM ABS
	$SelectCharacter->updateWSs($CID, $_selectedOpts);
	$SelectCharacter->addCharacterToAccount($CID, $MID);
	$skillTable 	= $ViewCharacter->drawSkillsTable($_charData['charSkills']);
	$_charData['charComAbs'] = $FetchCharacter->fetchComAbs();
	$comAbsTable 	= $ViewCharacter->drawComAbsTable($_charData['charComAbs']);
	$_charData['charWS'] = $FetchCharacter->fetchWeaponSkills();
	$WSTable 		= $ViewCharacter->drawWSTable($_charData['charWS']);
	$powersTable 	= $ViewCharacter->drawPowersTable($_charData['charPowers']);

	$cryptCID = $Controls->encryptID_GET($CID);
	$ComAbsWSTable 	= "
		<table id='tblComWS'>
			<tr>
				<td>{$comAbsTable}</td>
				<td>{$WSTable}</td>
				<td>{$powersTable}</td>
			</tr>
			<tr>
				<td colspan='3'>
					<table style='margin: 0 auto'>
						<tr>
							<td style='text-align: center'>Select Weapons<br>
								<a href='../shop/?q=cs&Il={$cryptCID}'target='_blank'>
								<img src='../images/icons/btnWeapons.png' /></a>
							</td>
						</tr>
					</table>
				</td>
			</tr>
		</table>\n";
}

?>
<!DOCTYPE html>
<html>

<head>
<title>Holy Lands RPG :: Online Character Generator</title>
<link rel="stylesheet" type="text/css" href="../styles_OLG.css">
<script type="text/javascript" src="../js/jq.js"></script>
<script type="text/javascript" src="../js/ui.js"></script>
</head>

<body>

<div id="header">
<table style="width: 100%">
	<tr>
		<td>
			<img class="padImg" src="../images/logoHLRPG.png" alt="Holy Lands RPG logo" title="Welcome to Holy Lands RPG">
		</td>
		<td>
			<div id="divPageMessage"></div>
		</td>
		<td>
		</td>
		<td>
			<div id="divLogin">
			<fieldset>
				<?php print $Members->getBannerAccessForm($MID) ?>
			</fieldset>
			</div>
		</td>
	</tr>
</table>
</div>
<div id="bodyDiv">

<?php
foreach ($_POST as $key => $value) {

	//print "POST: key = $key; value = $value<br>\n";
}

if ($_POST['staging'] == 'skills'){

	$charDataTable = $ViewCharacter->drawCharacterHeader($CID, $db);

	print $charDataTable;
}

print $tblCharInit;

if ($_selectSkillsTables){

	$skillTable = $CreateCharacter->displaySkillsTable($_selectSkillsTables,$_IDs);
}
print $skillTable;
print $ComAbsWSTable;
print $characterTable;
?>
	</div>
<div style="padding-top: 100px;">


<?php
//print MODIFIED ."<br>";
//print date('mdy',MODIFIED);
$deleteButton = "
<table style='margin: 0 auto; width: 330px'>
	<tr>
		<td>
			<b>LockStep: $lockStep | $postAction</b>
			$deleting
			<form action='/olg/?q=sc' name='frmDELETE' method='POST'>
				<input type='submit' name='btnDELETE' value='DELETE ALL CHARACTERS'>
				<input type='hidden' name='action' value='DELETE_ALL' >
			</form>
		</td>
	</tr>
</table>\n";
if ($MID == 12){
	print $deleteButton;
}
?>
</div>

<?php include '../view/footer.php' ?>
