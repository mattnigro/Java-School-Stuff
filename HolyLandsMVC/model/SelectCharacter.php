<?php

Class SelectCharacter
{
	// = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = //
	// = = = = = = = = = = = SELECT FROM CHARACTER CLASSES = = = = = = = = = = = //
	// = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = //

	protected $db				= null;
	public $attributes	 		= array();
	public $characterClasses 	= array();
	public $classFacts 			= array();
	public $statureAttribs 		= array();
	public $classSkills 		= array();
	public $characterNames 		= array();
	public $saveOptions			= array();
	public $heightsWeights		= array();
	public $classBlessings		= array();
	public $weaponSkills		= array();

	public function __construct($db)
	{
		$this->db 					= $db;
		$this->characterClasses 	= $this->selectCharacterClasses();
		$this->attributes 			= $this->selectClassAttributes();
		$this->statureAttribs 		= $this->selectAttributeDice();
		$this->classFacts 			= $this->selectClassFacts();
		$this->classSkills 			= $this->selectClassSkills();
		$this->characterNames 		= $this->selectCharacterNames();
		$this->saveOptions 			= $this->selectSaveOptions();
		$this->heightRange			= $this->selectHeightRange();
		$this->classBlessings		= $this->selectBlessings();
		$this->weaponSkills			= $this->selectWeaponSkills();
	}

	public function blessingOptions($classID)
	{
		$blessingType = $this->characterClasses[$classID]['blessings'];
		return $this->classBlessings[$blessingType];
	}

	public function selectHeightRange()
	{
		foreach ($this->statureAttribs as $stature => $_attribs) {

			$_heightWeight[$stature]['minHt'] = $_attribs['minHt'];
			$_heightWeight[$stature]['maxHt'] = $_attribs['maxHt'];
		}
		return $_heightWeight;
	}

	public function selectStatureDice($stature, $classID)
	{
		return array(
			'attribs' => $this->statureAttribs[$stature],
			'attrib1' => $this->characterClasses[$classID]['attrib1'],
			'attrib2' => $this->characterClasses[$classID]['attrib2'],
		);
	}

	public function selectCharVariablesDice($classID)
	{
		return array(
			'lifeREF'	=> $this->characterClasses[$classID]['lifeREF'],
			'faithREF'	=> $this->characterClasses[$classID]['faithREF'],
			'gold' 		=> $this->characterClasses[$classID]['gold'],
			'silver' 	=> $this->characterClasses[$classID]['silver'],
		);
	}

	public function selectClassAttributes()
	{
		$db = $this->db;
		$_classAttribs = array();
		$sql = "
			SELECT *
			FROM dbchar_attribs";
		if ($result = mysqli_query($db, $sql)){
			while ($row = mysqli_fetch_assoc($result)){
				foreach ($row as $key => $value){

					$attrib		= "{$row['attrib']}";
					$attribute 	= "{$row['attribute']}";
					$affects 	= "{$row['affects']}";
					$_classAttribs[$attrib] = array (
						'Attribute'	=> $attribute,
						'affects' 	=> $affects);
				}
			}
		}
		else {

			print "ERROR FETCHING CLASS ATTRIBUTES: " . mysql_error($db);
			die();
		}
		return $_classAttribs;
	}


	public function selectCharacterClasses(){

		$db = $this->db;
		$_classAttribs = $this->selectClassAttributes();
		$sql = "
			SELECT *
			FROM dbchar_classes
			ORDER BY class";
		if ($result = mysqli_query($db, $sql)){
			while ($row = mysqli_fetch_assoc($result)){
				$classID 		= "{$row['classID']}";
				$attrib1 = (!empty($row['attrib1']) ? $row['attrib1'] : null);
				$attrib2 = (!empty($row['attrib2']) ? $row['attrib2'] : null);
				$_charClasses[$classID] = array(
					'class' 		=> "{$row['class']}",
					'statures' 		=> explode('-',"{$row['statures']}"),
					'attrib1' 		=> $attrib1,
					'attribute1' 	=> (!empty($_classAttribs[$attrib1]['Attribute']) ? $_classAttribs[$attrib1]['Attribute'] : null),
					'attrib2' 		=> $attrib2,
					'attribute2' 	=> (!empty($_classAttribs[$attrib2]['Attribute']) ? $_classAttribs[$attrib2]['Attribute'] : null),
					'image' 		=> "{$row['image']}",
					'lifeREF' 		=> "{$row['lifeREF']}",
					'lifeLVL' 		=> "{$row['lifeLVL']}",
					'faithREF' 		=> "{$row['faithREF']}",
					'faithLVL' 		=> "{$row['faithLVL']}",
					'blessings' 	=> "{$row['blessings']}",
					'gold' 			=> "{$row['gold']}",
					'silver' 		=> "{$row['silver']}"
				);
			}
		}
		else {

			print 'Error fetching Character Classes ' . mysqli_error($db);
			die();
		}
		return $_charClasses;
	}

	public function selectAttributeDice(){

		$db = $this->db;
		$sql = "
			SELECT *
			FROM dbchar_statures";
		if ($result = mysqli_query($db, $sql)){
			while ($row = mysqli_fetch_assoc($result)){

				$stature = "{$row['stature']}";
				$_attribs[$stature] = array(
					'INT' 	=> "{$row['INTd']}",
					'WIS' 	=> "{$row['WISd']}",
					'PAT' 	=> "{$row['PATd']}",
					'WILL' 	=> "{$row['WILLd']}",
					'MEM' 	=> "{$row['MEMd']}",
					'STR' 	=> "{$row['STRd']}",
					'AGI' 	=> "{$row['AGId']}",
					'SPD' 	=> "{$row['SPDd']}",
					'END' 	=> "{$row['ENDd']}",
					'BTY' 	=> "{$row['BTYd']}",
					'CHA' 	=> "{$row['CHAd']}",
					'VIR' 	=> "{$row['VIRd']}",
					'minHt' => "{$row['minHt']}",
					'maxHt' => "{$row['maxHt']}"
				);
			}
			return $_attribs;
		}
		else {

			print "ERROR FETCHING STATURE ATTRIBUTES " . mysqli_error($db);
			die();
		}
	}

	public function selectClassSkills(){

		$db 		= $this->db;
		$_skills 	= array();
		$sql = "
			SELECT *
			FROM `dbchar_skills`
			ORDER BY `SkID`";
		if ($result = mysqli_query($db, $sql)){
			while ($row = mysqli_fetch_assoc($result)){
				foreach ($row as $classID => $skill) {

					if ($classID == 'SkID'){
						$SkID = $row['SkID'];
					}
					else {
						$_skills[$classID][$SkID] = $skill;
					}
				}
			}
		}
		return $_skills;
	}


	public function selectClassFacts(){

		$db = $this->db;
		$sql = "
			SELECT *
			FROM dbchar_facts";
		if ($result = mysqli_query($db, $sql)){
			while ($row = mysqli_fetch_assoc($result)){
				foreach ($row as $key => $value){

					if (!empty($key)){ // Allows me to add more phobias or lands, etc.
						switch($key){
							case 'phobia':
								$_phobias[] = $value;
								continue;
							case 'sin':
								$_sins[] = $value;
								continue;
							case 'lands':
								$_lands[] = $value;
								continue;
							case 'languages':
								$_langs[] = $value;
								continue;
							case 'addLG':
								$_addLG[] = $value;
								continue;

						}
					}
				}
			}
			foreach ($_lands as $key => $land) {

				$langGroup = str_replace('-',', ',"{$_langs[$key]}");
				$languages = "{$_addLG[$key]}";
				$_landLangs[$land] = array(
					'languages' => $languages,
					'langGroup' => $langGroup);
			}
			return array(
				'sins' => $_sins,
				'phobias' => $_phobias,
				'landLangs' => $_landLangs);
		}
		else {

			print "ERROR SELECTING CLASS FACTS " . mysqli_error($db);
			die();
		}
	}


	public function selectCharacterNames(){

		$db = $this->db;
		$_names = array();
		$sql = "
			SELECT *
			FROM dbchar_names";
		if ($result = mysqli_query($db, $sql)){
			while ($row = mysqli_fetch_assoc($result)){
				$NID = "{$row['nameID']}";
				if (!empty($row['male'])){
					$_names['M'][$NID] = "{$row['male']}";
				}
				if (!empty($row['female'])){
					$_names['F'][$NID] = "{$row['female']}";
				}
			}
		}
		else {

			return "ERROR FETCHING CHARACTER NAMES " . mysqli_error($db);
			die();
		}
		return $_names;
	}


	public function selectSaveOptions()
	{
		$db = $this->db;
		$_saveOpts = array();
		$sql = "
			SELECT `save`
			FROM dbchar_saves
			WHERE `rank`<='1'
			ORDER BY RAND()";
		if ($result = mysqli_query($db, $sql)){
			while ($row = mysqli_fetch_assoc($result)){

				$save = "{$row['save']}";
				$_saveOpts[] = $save;
			}
		}
		else {
			print "ERROR SELECTING CHAR SAVES " . mysqli_error($db);
			die();
		}

		return $_saveOpts;
	}

	public function selectBlessings()
	{
		$sql = "
			SELECT *
			FROM dbchar_blessings";
		if ($result = mysqli_query($this->db, $sql)){
			while ($row = mysqli_fetch_assoc($result)){
				$cMin		= $row['CourageMin'];
				$cMax 		= $row['CourageMax'];
				$Courage 	= $row['Courage'];
				$_blessings['Courage'][$cMin][$cMax] = $Courage;
				$dMin 		= $row['DutyMin'];
				$dMax 		= $row['DutyMax'];
				$Duty 		= $row['Duty'];
				$_blessings['Duty'][$dMin][$dMax] = $Duty;
				$fMin 		= $row['FortuneMin'];
				$fMax 		= $row['FortuneMax'];
				$Fortune 	= $row['Fortune'];
				$_blessings['Fortune'][$fMin][$fMax] = $Fortune;
			}
		}
		else {
			print 'ERROR SELECTING BLESSINGS '. mysqli_error($this->db);
			die();
		}
		return $_blessings;
	}

	public function selectWeaponSkills()
	{
		return array(
			'WS Hand to Hand' 	=> array('ATTb' => 0, 'CRIb' => 0, 'SPCb' => 0, 'AtR' => 2),
			'WS Light Arms' 	=> array('ATTb' => 0, 'CRIb' => 0, 'SPCb' => 0, 'AtR' => 1),
			'WS Heavy Arms' 	=> array('ATTb' => 0, 'CRIb' => 0, 'SPCb' => 0, 'AtR' => 1),
			'WS Paired Weapons' => array('ATTb' => 0, 'CRIb' => 0, 'SPCb' => 0, 'AtR' => 2),
			'WS Missiles' 		=> array('ATTb' => 0, 'CRIb' => 0, 'SPCb' => 0, 'AtR' => 1),
			'WS Thrown' 		=> array('ATTb' => 0, 'CRIb' => 0, 'SPCb' => 0, 'AtR' => 1),
			'WS Kick Attack' 	=> array('ATTb' => 0, 'CRIb' => 0, 'SPCb' => 0, 'AtR' => 1)
		);
	}

	// = = = = = = = = = = = = = = = = = = = = = = = =  = = = = = = = = = = = = //
	// = = = = = = = = = = = INSERT INIT CHARACTER DATA = = = = = = = = = = = = //
	// = = = = = = = = = = = = = = = = = = = = = = = =  = = = = = = = = = = = = //


	/**
	* Requires $_EAV['landOfOrigin'] = array('Roman Empire')
	*
	* @param int $CID
	* @param array $_charEAVs
	*/
	public function insertCharEAV($CID, $_charEAVs){
		$db = $this->db;
		foreach ($_charEAVs as $attrib => $_values) {

			foreach ($_values as $value) {

				if (!empty($value)){
					$sql = "
						INSERT INTO `character_eav`
						(`CID`, `attrib`, `value`, `MODIFIED`)
						VALUES ('$CID', '$attrib', '$value', '" . MODIFIED . "');";
					if (!$result = mysqli_query($db,$sql)){

						print "ERROR ENTERING EAV DATA " . mysqli_error($db);
						die();
					}
				}
			}
		}
	}

	public function insertCharSaves($CID, $_insertSaves)
	{
		$db = $this->db;
		foreach ($_insertSaves as $saves) {

			$_save[$saves] = (isset($_save[$saves]) ? $_save[$saves] : null);
			$_save[$saves]++;
		}
		foreach ($_save as $save => $PF) {
			$sql = "
				INSERT INTO `character_saves`
				(`CID`, `SaveVs`, `PF`, `MODIFIED`)
				VALUES ('{$CID}', '{$save}', '{$PF}', '" . MODIFIED . "');";
			if (!$result = mysqli_query($db,$sql)){

				print "ERROR ENTERING Character Saves " . mysqli_error($db);
				die();
			}
		}
	}

	/**
	* Save Initial character values
	*
	* @param array $_charInit
	* @returns array Attribute Bonuses
	*/
	public function saveCharInit($_charInit)
	{

		$db = $this->db;
		// = = = = = = = INSERT INIT CHARACTER DATA = = = = = = = = //
		$CID 		= "{$_charInit['CID']}";
		$MID 		= "{$_charInit['MID']}";
		$_character = $_charInit['character'];
		$name 		= "{$_character['name']}";
		$stature 	= "{$_character['stature']}";
		$classID 	= "{$_character['classID']}";
		$class 		= $this->characterClasses[$classID]['class'];
		$height 	= "{$_character['height']}";
		$weight 	= "{$_character['weight']}";
		$inches 	= "{$_character['inches']}";
		$gender 	= "{$_character['gender']}";
		$sql = "
			INSERT INTO `characters`
			(`CID`, `MID`, `name`, `LVL`, `stature`, `class`, `classID`, `gender`, `height`, `inches`, `weight`, `MODIFIED`)
			VALUES ('{$CID}', '{$MID}', '{$name}', '1', '{$stature}', '{$class}', '{$classID}', '{$gender}', '{$height}', '{$inches}', '{$weight}', '" . MODIFIED . "');";
		if (!$result = mysqli_query($db,$sql)){

			print "ERROR ENTERING CHARACTER " . mysqli_error($db);
			die();
		}
		// = = = = = = = INSERT INIT CHARACTER ABILS = = = = = = = = //
		$_charAbils = $_charInit['charAbils'];
		$Perception = "{$_charAbils['Perception']}";
		$Search 	= "{$_charAbils['Search']}";
		$Climb 		= "{$_charAbils['Climb']}";
		$Jump 		= "{$_charAbils['Jump']}";
		$Balance 	= "{$_charAbils['Balance']}";
		$Hide 		= "{$_charAbils['Hide']}";
		$Appeal 	= "{$_charAbils['Appeal']}";
		$sql = "
			INSERT INTO `character_abilities`
			(`CID`, `Perception`, `Search`, `Climb`, `Jump`, `Balance`, `Hide`, `Appeal`, `MODIFIED`)
			VALUES ('{$CID}', '{$Perception}', '{$Search}', '{$Climb}', '{$Jump}', '{$Balance}', '{$Hide}', '{$Appeal}', '" . MODIFIED . "');";
		if (!$result = mysqli_query($db,$sql)){
			print "ERROR ENTERING CHARACTER ABILITIES " . mysqli_error($db);
			die();
		}
		// = = = = = = = INSERT INIT CHARACTER ATTRIBS = = = = = = = = //
		$_charAttribs = $_charInit['charAttribs'];
		$_EAV['BONUS'] = array();
		if (!empty($_charAttribs['BONUS'])){
			foreach ($_charAttribs['BONUS'] as $attrib => $AV) {

				$_EAV['BONUS'][] = $attrib;
			}
		}
		$this->insertCharEAV($CID, $_EAV); // Insert Char Attrib Bonuses
		unset($_charAttribs['BONUS'], $_EAV);
		$cols = "(`CID`";
		$values = "('{$CID}'";
		foreach ($_charAttribs as $col => $value) {
			$cols .= ",`{$col}v`";
			$values .= ",'{$value}'";
		}
		$cols .= ",`MODIFIED`)";
		$values .= ",'" . MODIFIED ."')";
		$db = $this->db;
		$sql = "
			INSERT INTO `character_attributes`
			{$cols}
			VALUES $values;";
		if (!$result = mysqli_query($db,$sql)){
			return "ERROR ENTERING CHARACTER ATTRIBUTES " . mysqli_error($db);
			die();
		}
		// = = = = = = = INSERT INIT CHARACTER VAR DATA = = = = = = = = //
		$_charVars 	= $_charInit['charVars'];
		$Faith 		= "{$_charVars['Faith']}";
		$Life 		= "{$_charVars['Life']}";
		$gold 		= "{$_charVars['gold']}";
		$silver 	= "{$_charVars['silver']}";
		$sql = "
			INSERT INTO `character_variables`
			(`CID`, `Life`, `mLife`, `Faith`, `mFaith`, `gold`, `silver`, `EXP`, `mEXP`, `MODIFIED`)
			VALUES ('$CID', '$Life', '$Life', '$Faith', '$Faith', '$gold', '$silver', 0, '1230', '" . MODIFIED . "');";
		if (!$result = mysqli_query($db,$sql)){
			print "ERROR ENTERING DATA " . mysqli_error($db);
			die();
		}
		// = = = = = = = INSERT INIT CHARACTER SINS/PHOBIAS = = = = = = = = //
		$_charProbs = $_charInit['charProbs'];
		$this->insertCharEAV($CID, $_charProbs);

		// = = = = = = = INSERT INIT CHARACTER LANDS/LANGS = = = = = = = = //
		$_landsLangs['landOfOrigin'] 	= ['Roman Empire'];
		$_landsLangs['langGroup'] 		= ['Roman'];
		$_landsLangs['languages'] 		= ['Italian','German','Greek'];
		$this->insertCharEAV($CID, $_landsLangs);

		// = = = = = = = INSERT INIT CHARACTER BLESSINGS = = = = = = = = //
		if(is_array($_blessings = $_charInit['charPowers'])){
			foreach ($_blessings as $blessing) {
				$sql = "
					INSERT INTO `character_powers`
					(`CID`, `type`, `power`, `FC`, `MODIFIED`)
					VALUES ('$CID', 'Blessings', '$blessing', '5','" . MODIFIED . "');";
				if (!$result = mysqli_query($db,$sql)){
					print "ERROR ENTERING CHARACTER BLESSINGS " . mysqli_error($db);
				}
			}
		}
		// = = = = = = = INSERT INIT CHARACTER COMABS = = = = = = = = //
		if (is_array($_charComAbs = $_charInit['charComAbs'])){
			$ADV = "{$_charComAbs['ADV']}";
			$DEF = "{$_charComAbs['DEF']}";
			$DOD = "{$_charComAbs['DOD']}";
			$DAM = "{$_charComAbs['DAM']}";

			$sql = "
			INSERT INTO `character_comabs`
			(`CID`, `ADV`, `DEF`, `DOD`, `DAM`, `MODIFIED`)
			VALUES ('{$CID}', '{$ADV}', '{$DEF}', '{$DOD}', '{$DAM}', '" . MODIFIED . "');";
			if (!$result = mysqli_query($db,$sql)){

				return "ERROR ENTERING CHARACTER COMABS " . mysqli_errno($db);
			}
		}

		// = = = = = = = INSERT INIT CHARACTER SAVES = = = = = = = = //

		if (is_array($_charSaves = $_charInit['charSaves'])){

			$this->insertCharSaves($CID, $_charSaves);
		}

		// = = = = = = = INSERT INIT CHARACTER WS AtRs = = = = = = = = //
		if (is_array($_WSAtR = $_charInit['WSAtR'])){
			foreach ($_WSAtR as $WS => $_WSaction) {

				$ATTb = (!empty($_WSaction['ATT']) ? $_WSaction['ATT'] : 0);
				$AtRs = (!empty($_WSaction['AtR']) ? $_WSaction['AtR'] : 0);
				$sql = "
				INSERT INTO `character_ws`
				(`CID`, `WS`, `ATTb`, `AtRs`, `MODIFIED`)
				VALUES ('$CID', '$WS', '$ATTb', '$AtRs', '" . MODIFIED . "');";
				if (!$result = mysqli_query($db,$sql)){

					print "ERROR ENTERING WEAPON SKILLS " . mysqli_error($db);
					die();

				}
			}
		}
	}

	// = = = = = = = = = = = = = = = = = = = = = = = =  = = = = = = = = = = = = //
	// = = = = = = = = = = = UPDATE INIT CHARACTER DATA = = = = = = = = = = = = //
	// = = = = = = = = = = = = = = = = = = = = = = = =  = = = = = = = = = = = = //

	public function deleteInitLandsLangs($CID)
	{
		$db = $this->db;
		if ($CID){
			$sql = "
				DELETE FROM `character_eav`
				WHERE `CID`='{$CID}'
				AND `attrib`='languages'";
			if (!$result = mysqli_query($db,$sql)){

				print "ERROR DELETING CHARACTER LANGS " . mysqli_error($db);
				die();
			}

			$sql = "
				DELETE FROM `character_eav`
				WHERE `CID`='{$CID}'
				AND `attrib`='landOfOrigin'";
			if (!$result = mysqli_query($db,$sql)){

				print "ERROR DELETING CHARACTER LAND " . mysqli_error($db);
				die();
			}

			$sql = "
				DELETE FROM `character_eav`
				WHERE `CID`='{$CID}'
				AND `attrib`='langGroup'";
			if (!$result = mysqli_query($db,$sql)){

				print "ERROR DELETING CHARACTER LANG GROUP " . mysqli_error($db);
				die();
			}
		}
	}


	public function deleteInitSaves($CID)
	{
		$db = $this->db;
		if ($CID){
			$sql = "
				DELETE FROM `character_saves`
				WHERE `CID`='{$CID}';";
			if (!$results = mysqli_query($db,$sql)){

				print "ERROR DELETING CHARACTER SAVES" . mysqli_error($db);
				die();
			}
		}
	}
	/**
	* Requires $_EAV['landOfOrigin'] = array('Roman Empire')
	*
	* @param int $CID
	* @param array $_charEAVs
	*/
	public function updateCharEAV($CID, $_charEAVs){
		$db = $this->db;
		foreach ($_charEAVs as $attrib => $_values) {

			foreach ($_values as $value) {

				if (!empty($value)){
					$sql = "
						UPDATE `character_eav`
						SET value=
						(`CID`, `attrib`, `value`, `MODIFIED`)
						VALUES ('$CID', '$attrib', '$value', '" . MODIFIED . "');";
					if (!$result = mysqli_query($db,$sql)){

						print "ERROR UPDATING EAV DATA " . mysqli_error($db);
						die();
					}
				}
			}
		}
	}


	public function selectLangsFromLangGroup($langGroup)
	{
		$db = $this->db;
		$sql = "
			SELECT `languages`
			FROM dbchar_facts
			WHERE `addLG`='{$langGroup}'";
		if ($result = mysqli_query($this->db, $sql)){
			while ($row = mysqli_fetch_assoc($result)){
				return explode('-', $row['languages']);
			}
		}
		else {
			print 'ERROR SELECTING LANGUAGES FROM GROUP ' . mysqli_error($db);
			die();
		}
	}

	public function updateCharInit($_updates, $_IDs){

		$db = $this->db;
		$classID = "{$_IDs['classID']}";
		$CID = "{$_IDs['CID']}";
		$MID = "{$_IDs['MID']}";
		$age = intval(substr("{$_updates['age']}",0,2));
		$_save = array();
		if (($age < 16) || ($age > 44)){
			$age = 23;
		}
		$DataModel = new DataModel($db);
		$charName =  $DataModel->sanatizeDBText($_updates['charName'], true);// charName => Master Ivas
		$charName = mysqli_real_escape_string($db,str_replace($DataModel->getBadChars(),'',$charName));
		$sql ="
			UPDATE `characters`
			SET `name`='{$charName}', `age`='{$age}', `MODIFIED`='" . MODIFIED . "'
			WHERE `CID`='{$CID}' AND `MID`='{$MID}'";
		if (!$result = mysqli_query($db,$sql)){
			print "ERROR UPDATING Character " . mysqli_error($db);
			die();
		}
		// DELETE INITIAL SAVES, LANDS, LANGS TO UPDATE WITH SELECTED ONES
		$this->deleteInitSaves($CID);
		$this->deleteInitLandsLangs($CID);
		$_EAV['landOfOrigin'] = array("{$_updates['land']}");
		$_EAV['langGroup'] = array("{$_updates['lang']}");
		$_EAV['languages'] = $this->selectLangsFromLangGroup($_EAV['langGroup'][0]);
		$this->insertCharEAV($CID,$_EAV);
		$this->insertCharSaves($CID, $_updates['saves']);
	}

	public function deletePreventDuplicates($CID, $dbTable, $columnItems = array(), $limit = null)
	{
		if ((!empty($CID)) && (!empty($dbTable)) && (!empty($columnItems))){
			$limit = (!empty($limit) ? 'LIMIT ' . $limit : null);
			$sql ="
				DELETE FROM `{$dbTable}`
				WHERE `CID`='{$CID}' AND `{$columnItems['column']}` = '{$columnItems['items']}'
				$limit";
			if (!$result = mysqli_query($this->db, $sql)){

				print "ERROR DELETING DUPLICATES " . mysqli_error($this->db);
			}
		}
	}


	public function insertCharSkills($CID, $_skills, $type, $PF)
	{
		$db = $this->db;
		if (is_array($_skills)){
			$this->deletePreventDuplicates($CID, 'character_skills', array('column' => 'type', 'items' => $type));
			foreach($_skills as $skill){
				$sql = "
					INSERT INTO `character_skills`
					(`CID`, `type`, `skill`, `PF`, `MODIFIED`)
					VALUES ('$CID', '$type', '$skill', '$PF', '" . MODIFIED . "');";
				if (!$result = mysqli_query($db,$sql)){

					print "ERROR ENTERING SKILLS " . mysqli_error($db);
				}
			}
		}
	}

	public function updateComAbs($CID, $_selectOpts){

		$db = $this->db;
		// = == = = = = = = DECRYPT COMABS AND MULTIPLIERS = = = = = = //
		$_cryptDivision = array(
			'afgGF' => 3,		// ADV / 3
			'aFggF' => 12,		// DOD / 12
			'AFGgf' => 30,		// DEF / 30
			'AfGgf' => 7		// DAM / 7
		);
		$_cryptComAb = array(
			'afgGF' => 'ADV', 	// ADV / 3
			'aFggF' => 'DOD',	// DOD / 12
			'AFGgf' => 'DEF',	// DEF / 30
			'AfGgf' => 'DAM'	// DAM / 7
		);
		foreach ($_cryptDivision as $crypt => $div) { // i.e. 'AfGgf' => 7
			$decryptDiv = (!empty($_selectOpts[$crypt]) ?  ($_selectOpts[$crypt] / $div) : 0); // i.e. 14 / 7 = +2
			$decryptComAb = "{$_cryptComAb[$crypt]}"; // i.e. 'DAM'
			$_charComAbs[$decryptComAb] = $decryptDiv; // i.e. [DAM] = 2
		}
		// = == = = = = = = INSERT ANY COMABS INTO DB = = = = = = //
		$_inc = (!empty($_selectOpts['incCA']) ? $_selectOpts['incCA'] : null);
		if (!empty($_inc)){
			foreach ($_inc as $comAb) {
				$_charComAbs[$comAb]++; // i.e. [DAM]++
			}
			foreach ($_charComAbs as $comAb => $bonus) {
				$sql="
					UPDATE `character_comabs`
					SET `$comAb`='$bonus' , `MODIFIED`='" . MODIFIED . "'
					WHERE `CID`='$CID'";
				if (!$result = mysqli_query($db,$sql)){
					print "ERROR UPDATING Combat Abilities " . mysqli_error($db) . "\n";
					die();
				}
			}
		}
	}


	public function findSpecialistPF($CID)
	{
		$sql = "
			SELECT `PF`
			FROM `character_skills`
			WHERE `skill`='Specialist' AND `CID`='{$CID}'";
		if ($result = mysqli_query($this->db, $sql)){
			while ($row = mysqli_fetch_assoc($result)){
				return $row['PF'];
			}
		}
		else {
			print 'ERROR FETCHING SPECIALIST PF '. mysqli_error($this->db);
		}
		return false;
	}


	public function updateSpecialistSkills($_specialists, $CID)
	{
		foreach ($_specialists as $skill) {
			$this->insertCharEAV($CID, array('Specialist' => [$skill]));
		}
	}


	public function updateWSs($CID, $_selectedOpts)
	{
		$db = $this->db;
		if (isset($_selectedOpts)){
			$Controls = new ControllerModel();
			$_wsActions = array('ATT', 'C_S', 'AtR');
			foreach ($_selectedOpts as $wsAction => $_encryptAction) {
				if (in_array($wsAction, $_wsActions)){
					foreach ($_encryptAction as $encryptedAction) {
						$_decryptAction = $Controls->explode2KeyValues([$encryptedAction]);
						$action = $_decryptAction[0];
						$bonus = $_decryptAction[1];
						$WS = $_decryptAction[2];
						$sql="
							UPDATE `character_ws`
							SET `{$action}`='{$bonus}' , `MODIFIED`='" . MODIFIED . "'
							WHERE `CID`='$CID' AND `WS`='$WS' LIMIT 1";
						if (!$result = mysqli_query($db,$sql)){

							print "ERROR UPDATING Weapon Skills " . mysqli_error($db);
							die();
						}
					}
				}
			}
				//die();
		}
	}

	public function deleteCharEAV($CID, $attrib, $limit)
	{

		if (($CID) && ($attrib)){

			$limit <= 0 ? $limit = ' LIMIT 1' : $limit = " LIMIT $limit";
			$db = $this->db;
			$sql = "DELETE FROM `character_eav`
				WHERE (`CID`='$CID' AND `attrib`='$attrib'){$limit}";
			if (!$result = mysqli_query($db,$sql)){

				print "ERROR DELETING EAVs " . mysqli_error($db);
				die();
			}
		}
	}
	public function addCharacterToAccount($CID, $MID)
	{
		$db = $this->db;
		$sql ="
			UPDATE `characters`
			SET `created`='" . MODIFIED . "'
			WHERE `CID`='{$CID}' AND `MID`='{$MID}'";
			//dd($sql);
		if (!$result = mysqli_query($db, $sql)){

			print "ERROR CONFIRMING CHARACTER " . mysqli_error($db);
			die();
		}
	}
}
?>
