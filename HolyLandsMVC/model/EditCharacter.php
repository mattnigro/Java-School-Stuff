<?php

Namespace HolyLandsRPG\Character;

Class EditCharacter{

	private $db = null;

	private $CID = null;

	public function __construct($CID, $db){

		$this->db = $db;
		$this->CID = $CID;
	}

	public function updateCharacterLog($CID, $cat, $toValue, $fromValue = null)
	{
		$cats = array(
			'LVL' => "Increased levels to LVL {$toValue}",
			'attrib' => "Increased {$fromValue} Attribute to {$toValue}",
			'Life' => "Increased Life from {$fromValue} to {$toValue}",
			'Faith' => "Increased Faith from {$fromValue} to {$toValue}",
			'LVL' => "Increased levels to LVL {$toValue}",
		);
		$sql = "
			INSERT INTO `log_characters`
			SET `CID`='{$CID}', `cat`='{$cat}', `logAction`='{$logAction}', `MODIFIED`='" . MODIFIED . "'";
		if (!$result = mysqli_query($this->db, $sql)){

			print "ERROR UPDATING CHARACTER LOG " . mysqli_error($this->db);
			die();
		}
	}


	public function updateCharacter($charName, $LVL = null)
	{
		$lvlUp = (!empty($LVL) ? ', `LVL`="' . $LVL . '"' : null);
		$sql = "
			UPDATE `characters`
			SET `name`='{$charName}'{$lvlUp}, `MODIFIED`='" . MODIFIED . "'
			WHERE `CID`='{$this->CID}'";
		if (!$result = mysqli_query($this->db, $sql)){

			print "ERROR UPDATING CHARACTER NAME and LVL " . mysqli_error($$this->db);
			die();
		}
	}

	public function updateCharVariables($_charVars, $_statics)
	{
		$mLife 		= "{$_charVars['mLife']}";
		$mFaith 	= "{$_charVars['mFaith']}";
		$gold 		= "{$_charVars['gold']}";
		$silver		= "{$_charVars['silver']}";
		$EXP 		= "{$_charVars['EXP']}";
		$sql = "
			UPDATE `character_variables`
			SET `Life`='{$mLife}', `mLife`='{$mLife}', Faith='{$mFaith}',  mFaith='{$mFaith}',  gold='{$gold}', silver='{$silver}', EXP='{$EXP}', `MODIFIED`='" . MODIFIED . "'
			WHERE `CID`='{$this->CID}'";
		if (!$result = mysqli_query($this->db, $sql)){

			print "ERROR UPDATING CHARACTER VARIABLES " . mysqli_error($this->db);
			die();
		}
	}

	public function removeGold($amount)
	{
		$sql ="
			UPDATE `character_variables`
			SET `gold`=(gold-{$amount}),
			`MODIFIED`='" . MODIFIED . "'
			WHERE `CID`='{$this->CID}'";
		if (!$result = mysqli_query($this->db,$sql)){

			print "ERROR REMOVING GOLD " . mysqli_error($this->db);
			die();
		}
	}

	public function fetchAllWeapons()
	{
		$sql = "
			SELECT *
			FROM dbweapons
			ORDER BY weapon, DAM";
		if ($result = mysqli_query($this->db, $sql)){
			while ($row = mysqli_fetch_assoc($result)){
				$WS = $row['WS'];
				$weapon = $row['weapon'];
				$DAM = $row['DAM'];
				$_weapons[$weapon] = array(
					'DAM' 	=> $DAM,
					'WS' 	=> $WS,
					'image' => $row['image'],
					'value' => $row['value']
				);
			}
		}
		else {

			print 'ERROR FETCHING ALL WEAPONS ' . mysqli_error($this->db);
		}
		return $_weapons;
	}

	public function fetchWeaponData($weapon = null)
	{
		$_weapon = array();
		$sql = "
			SELECT `DAM`, `value`, `image`
			FROM dbweapons
			WHERE `weapon`='{$weapon}'";
		if ($result = mysqli_query($this->db, $sql)){
			while ($row = mysqli_fetch_assoc($result)){
				$_weapon = array(
					'DAM' 	=> "{$row['DAM']}",
					'image' => "{$row['image']}",
					'value' => "{$row['value']}"
				);
			}
		}
		else {
			print 'ERROR FETCHING WEAPON DATA ' . mysql_error($this->db);
		}
		return $_weapon;
	}



	public function insertCharacterWeapons($_weapons = null)
	{
		$CID = $this->CID;
		foreach ($_weapons as $weapon => $DAM) {
			if ($_weaponData = $this->fetchWeaponData($weapon)){
				$DAM 	= $_weaponData['DAM'];
				$value 	= intval("{$_weaponData['value']}");
				$image 	= "{$_weaponData['image']}";
				$sql = "
				INSERT INTO `character_weapons`
				(`CID`, `weapon`, `DAM`, `value`, `image`, `MODIFIED`)
				VALUES ('{$CID}', '{$weapon}', '{$DAM}', '{$value}', '{$image}', '" . MODIFIED . "');";
				$sqls[] = $sql;
				if (!$result = mysqli_query($this->db, $sql)){

					print "ERROR ENTERING WEAPON DATA " . mysqli_error($this->db);
					die();
				}
			}
		}
	}
	public function fetchArmorData($armor)
	{
		$_armor = array();
		$sql = "
			SELECT *
			FROM dbarmor
			WHERE armor='{$armor}'";
		if ($result = mysqli_query($this->db, $sql)){
			while ($row = mysqli_fetch_assoc($result)){
				$AP = $row['AP'];
				$aDEF = $row['aDEF'];
				$PEN = $row['PEN'];
				$CAP = $row['CAP'];
				$value = $row['value'];
				$img = $row['image'];
				return array(
					'AP' => $AP,
					'armor' => $armor,
					'aDEF' => $aDEF,
					'CAP' => $CAP,
					'PEN' => $PEN,
					'value' => $value,
					'image' => $img
				);
			}
		}
		else {
			print 'ERROR FETCHING DATA '. mysqli_error($this->db);
		}
		return $_armor;

	}

	public function insertCharacterArmor($_selectedArmor = null)
	{
		$CID = $this->CID;
		foreach ($_selectedArmor as $AP => $_armorData) {
			$armor = $_armorData['armor'];
			$_armor[$AP] = $this->fetchArmorData($armor);
			$aDEF = $_armor[$AP]['aDEF'];
			$CAP = $_armor[$AP]['CAP'];
			$PEN = $_armor[$AP]['PEN'];
			$value = $_armor[$AP]['value'];
			$img = $_armor[$AP]['image'];
			$sql = "
				INSERT INTO `character_armor`
				(`CID`, `armor`, `AP`, `aDEF`, `CAP`, `PEN`, `value`, `image`, `MODIFIED`)
				VALUES ('{$CID}', '{$armor}', '{$AP}', '{$aDEF}', '{$CAP}', '{$PEN}', '{$value}', '{$img}', '" . MODIFIED . "');";
			if (!$result = mysqli_query($this->db, $sql)){

				print "ERROR ENTERING CHARACTER ARMOR " . mysqli_error($this->db);
			}
		}
	}

	public function updateCharacterWeapons($_charWeapons, $_newWeapDAM = array(), $_newWeapATT = array())
	{
		$CID = $this->CID;
		for ($i = -7; $i <= 7; $i++){
			$addOpt = ($i < 0 ? $i : '+' . $i);
			$_removeDAM[] = $addOpt;
		}
		$_removeATT = array_merge($_removeDAM, array(' (', 'ATT)'));
		$_modified = array();
		foreach ($_newWeapDAM as $weapID => $DAMb) {
			$_thisWeapon = $_charWeapons[$weapID];
			$_thisWeapon['DAM'] = str_replace($_removeDAM,'',$_thisWeapon['DAM']); // REMOVE ANY EXISTING BONUS
			if ($DAMb != '+0'){
				$_thisWeapon['DAM'] .= $DAMb; // Add Selected Bonus
			}
			if ($_thisWeapon['DAM'] != $_charWeapons[$weapID]['DAM']){
				$_charWeapons[$weapID]['DAM'] = $_thisWeapon['DAM'];
				$_modified[$weapID] = 'mod';
			}
		}
		foreach ($_newWeapATT as $weapID => $ATTb) {
			$_thisWeapon = $_charWeapons[$weapID];
			$_thisWeapon['weapon'] = str_replace($_removeATT,'',$_thisWeapon['weapon']); // REMOVE ANY EXISTING BONUS
			if ($ATTb != '+0'){
				$_thisWeapon['weapon'] .= " ({$ATTb} ATT)"; // Add Selected Bonus
			}
			if ($_thisWeapon['weapon'] != $_charWeapons[$weapID]['weapon']){
				$_charWeapons[$weapID]['weapon'] = $_thisWeapon['weapon'];
				$_modified[$weapID] = 'mod';
			}
		}
		foreach ($_charWeapons as $weapID => $_weapon) {

			if ($_modified[$weapID] == 'mod'){
				$weapon 	= "{$_weapon['weapon']}";
				$DAM 		= "{$_weapon['DAM']}";
				$sql ="
					UPDATE `character_weapons`
					SET `weapon`='{$weapon}', `DAM`='{$DAM}', `MODIFIED`='" . MODIFIED . "'
					WHERE `WeapID`='{$weapID}' AND `CID`='{$CID}'";
				if (!$result = mysqli_query($this->db, $sql)){

					print "ERROR UPDATING WEAPONS " . mysqli_error($this->db);
					die();
				}
			}
		}
	}


	public function deleteCharacterWeapons($_weapIDs)
	{
		foreach ($_weapIDs as $weapID => $action) {

			$sql ="
				DELETE FROM `character_weapons`
				WHERE `WeapID`='{$weapID}'
				AND `CID`='{$this->CID}'
				LIMIT 1";
			if (!$result = mysqli_query($this->db, $sql)){

				print "ERROR DELETING DATA " . mysqli_error($this->db);
			}
		}
	}


	public function fetchEXP_LVL($EXP)
	{
		$EXP = intval($EXP);
		$sql = "
			SELECT MAX(LVL)
			FROM dblvl_exp
			WHERE `EXP` <= '{$EXP}'";
		if ($result = mysqli_query($this->db, $sql)){
			while ($row = mysqli_fetch_assoc($result)){
				return ($row['MAX(LVL)']);
			}
		}
		else {
			print 'ERROR FETCHING EXP LVL '. mysqli_error($this->db);
		}
	}
}
?>
