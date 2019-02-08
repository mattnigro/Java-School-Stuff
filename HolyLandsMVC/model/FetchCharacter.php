<?php

Class FetchCharacter{

	protected $db 				= null;
	protected $CID 				= null;
	protected $_characterData 	= array();

	public function __construct($db, $CID){

		$this->db = $db;
		$this->CID = $CID;
		$this->_characterData = $this->fetchCharacterData();
	}

	public function fetchCharacterData()
	{
		$CID 		= $this->CID;
		$_character = array();
		$sql = "
			SELECT c.*, ca.*, cabs.*, cv.*, cca.*
			FROM characters c
			JOIN character_attributes ca ON c.CID = ca.CID
			JOIN character_abilities cabs ON c.CID = cabs.CID
			JOIN character_variables cv ON c.CID = cv.CID
			JOIN character_comabs cca ON c.CID = cca.CID
			AND c.CID='{$CID}'";
		if ($result = mysqli_query($this->db, $sql)){
			while ($row = mysqli_fetch_assoc($result)){
				$_character['character']['CID'] 		= $row['CID'];
				$_character['character']['MID'] 		= $row['MID'];
				$_character['character']['name'] 		= $row['name'];
				$_character['character']['LVL'] 		= $row['LVL'];
				$_character['character']['stature']		= $row['stature'];
				$_character['character']['class'] 		= $row['class'];
				$_character['character']['classID'] 	= $row['classID'];
				$_character['character']['gender'] 		= $row['gender'];
				$_character['character']['age'] 		= $row['age'];
				$_character['character']['height'] 		= $row['height'];
				$_character['character']['weight'] 		= $row['weight'];
				$_character['character']['inches'] 		= $row['inches'];
				$_character['charAttribs']['INT'] 		= $row['INTv'];
				$_character['charAttribs']['WIS'] 		= $row['WISv'];
				$_character['charAttribs']['PAT'] 		= $row['PATv'];
				$_character['charAttribs']['WILL'] 		= $row['WILLv'];
				$_character['charAttribs']['MEM'] 		= $row['MEMv'];
				$_character['charAttribs']['STR'] 		= $row['STRv'];
				$_character['charAttribs']['AGI'] 		= $row['AGIv'];
				$_character['charAttribs']['SPD'] 		= $row['SPDv'];
				$_character['charAttribs']['END'] 		= $row['ENDv'];
				$_character['charAttribs']['BTY'] 		= $row['BTYv'];
				$_character['charAttribs']['CHA'] 		= $row['CHAv'];
				$_character['charAttribs']['VIR'] 		= $row['VIRv'];
				$_character['charAbils']['Perception'] 	= $row['Perception'];
				$_character['charAbils']['Search'] 		= $row['Search'];
				$_character['charAbils']['Climb'] 		= $row['Climb'];
				$_character['charAbils']['Jump'] 		= $row['Jump'];
				$_character['charAbils']['Balance'] 	= $row['Balance'];
				$_character['charAbils']['Hide'] 		= $row['Hide'];
				$_character['charAbils']['Appeal'] 		= $row['Appeal'];
				$_character['charVars']['Life'] 		= $row['Life'];
				$_character['charVars']['mLife'] 		= $row['mLife'];
				$_character['charVars']['Faith'] 		= $row['Faith'];
				$_character['charVars']['mFaith'] 		= $row['mFaith'];
				$_character['charVars']['gold'] 		= $row['gold'];
				$_character['charVars']['silver'] 		= $row['silver'];
				$_character['charVars']['EXP'] 			= $row['EXP'];
				$_character['charVars']['mEXP'] 		= $row['mEXP'];
				$_character['charComAbs']['ADV'] 		= $row['ADV'];
				$_character['charComAbs']['DEF'] 		= $row['DEF'];
				$_character['charComAbs']['DOD'] 		= $row['DOD'];
				$_character['charComAbs']['DAM'] 		= $row['DAM'];
				$_character['charEAV']					= $this->fetchCharacterEAV();
				$_character['charSaves']				= $this->fetchCharacterSaves();
				$_character['charSkills']				= $this->fetchCharacterSkills();
				$_character['charWS']					= $this->fetchWeaponSkills();
				$_character['charPowers']				= $this->fetchCharacterPowers();
			}
		}
		else {
			print 'ERROR FETCHING CHARACTER DATA '. mysqli_error($this->db);
			die();
		}
		return $_character;
	}

	public function fetchCharacterEAV()
	{
		$CID = $this->CID;
		$_character = array();
		$sql = "
			SELECT *
			FROM character_eav
			WHERE CID='{$CID}'";
		if ($result = mysqli_query($this->db, $sql)){
			while ($row = mysqli_fetch_assoc($result)){
				$attrib = $row['attrib'];
				$_character[$attrib][] = $row['value'];
			}
		}
		else {
			print 'ERROR FETCHING CHARACTER EAV '. mysqli_error($this->db);
			die();
		}
		return $_character;
	}

	public function fetchCharacterPowers()
	{
		$CID = $this->CID;
		$_character = array();
		$sql = "
			SELECT *
			FROM character_powers
			WHERE CID='{$CID}'";
		if ($result = mysqli_query($this->db, $sql)){
			while ($row = mysqli_fetch_assoc($result)){
				$type = $row['type'];
				$power = $row['power'];
				$Fc = $row['Fc'];
				$_character[$type][$power] = $row['Fc'];
			}
		}
		else {
			print 'ERROR FETCHING CHARACTER POWERS '. mysqli_error($this->db);
			die();
		}
		return $_character;
	}

	public function fetchCharacterSaves()
	{
		$CID = $this->CID;
		$_character = array();
		$sql = "
			SELECT *
			FROM character_saves
			WHERE CID='{$CID}'";
		if ($result = mysqli_query($this->db, $sql)){
			while ($row = mysqli_fetch_assoc($result)){
				$saveVs = $row['SaveVs'];
				$_character[$saveVs] = $row['PF'];
			}
		}
		else {
			print 'ERROR FETCHING CHARACTER SAVES '. mysqli_error($this->db);
			die();
		}

		return $_character;
	}

	public function fetchCharSpecialist()
	{
		$_spec = array();
		$sql = "
			SELECT `value`
			FROM `character_eav`
			WHERE `CID` = '{$this->CID}'
			AND `attrib` = 'Specialist'";
		if ($result = mysqli_query($this->db, $sql)){
			while ($row = mysqli_fetch_assoc($result)){
				$_spec[] = $row['value'];
			}
		}
		else {
			print 'ERROR FETCHING SPECIALIST SKILLS '. mysqli_error($this->db);
			die();
		}
		return $_spec;
	}

	public function fetchCharacterSkills($showSpecialist = false){

		$db = $this->db;
		$CID = $this->CID;
		$_type = array();
		$_skills = array();
		$_charSpecialist = $this->fetchCharSpecialist();
		$sql = "
			SELECT *
			FROM character_skills
			WHERE CID='{$CID}'";
		if ($result = mysqli_query($db, $sql)){
			while ($row = mysqli_fetch_assoc($result)){
				foreach ($row as $key => $value){

					$key == 'type' ? $_type[] = $value : null;
					if($key == 'skill'){
						if((in_array($value, $_charSpecialist)) && ($showSpecialist == true)){
							$value = "[ {$value} ]";
						}
						$_skill[] = $value;
					}
					$key == 'PF' ? $_PF[] = $value : null;
				}
			}
			if (!empty($_type)){
				foreach ($_type as $key => $type) { // i.e. 3 => gifts

					$thisSkill = "{$_skill[$key]}";
					$thisPF = "{$_PF[$key]}";
					$_skills[$type][] = $thisSkill;
					$_skills[$thisSkill]['PF'] = $thisPF;
				}
			}
		}
				//dd($_skills);
		return $_skills;
	}


	public function fetchWeaponSkills()
	{
		$CID = $this->CID;
		$db= $this->db;
		$_WS = array();
		$_skipKeys = array('WS', 'CID', 'MODIFIED');
		$sql = "SELECT * FROM character_ws WHERE `CID`='$CID'";
		if ($result = mysqli_query($db, $sql)){
			while ($row = mysqli_fetch_assoc($result)){
				foreach ($row as $key => $value){

					$WS = $row['WS'];
					if (!in_array($key, $_skipKeys)){
						$_WS[$WS][$key] = $value;
					}
				}
			}
			return $_WS;
		}
	}

	public function fetchComAbs()
	{
		$CID = $this->CID;
		$db= $this->db;
		$_comAbs = array();
		$sql = "
			SELECT * FROM character_comabs WHERE `CID` = '$CID'";
		if ($result = mysqli_query($db, $sql)){
			while ($row = mysqli_fetch_assoc($result)){
				foreach ($row as $key => $value){

					$_comAbs[$key] = $value;
				}
			}
		}
		else {

			print "FAILED TO FETCH COMBAT ABILITIES " . mysqli_error($db);
		}
		return $_comAbs;
	}


	public function fetchCharacterArmor()
	{
		$_armor = array();
		$sql = "
			SELECT *
			FROM character_armor
			WHERE CID='{$this->CID}'";
		if ($result = mysqli_query($this->db, $sql)){
			while ($row = mysqli_fetch_assoc($result)){
				$armor 	= $row['armor'];
				$AP 	= $row['AP'];
				$aDEF 	= $row['aDEF'];
				$PEN 	= $row['PEN'];
				$CAP 	= $row['CAP'];
				$value 	= $row['value'];
				$img 	= $row['image'];
				$_armor[$AP]	= array(
					'armor'		=> $armor,
					'aDEF' 		=> $aDEF,
					'CAP' 		=> $CAP,
					'PEN' 		=> $PEN,
					'value' 	=> $value,
					'image' 	=> $img
				);
			}
		}
		else {
			print 'ERROR FETCHING DATA '. mysqli_error($this->db);
		}
		return $_armor;

	}
	public function fetchCharacterWeapons()
	{
		$CID = $this->CID;
		$_weapons = array();
		$sql = "
			SELECT *
			FROM character_weapons
			WHERE `CID`='{$CID}'";
		if ($result = mysqli_query($this->db, $sql)){
			while ($row = mysqli_fetch_assoc($result)){
				$weapId = $row['WeapID'];
				$_weapons[$weapId] = array(
					'weapon' 	=> $row['weapon'],
					'DAM' 		=> $row['DAM'],
					'image' 	=> $row['image']
				);
			}
			return $_weapons;
		}
		else {
			print "ERROR FETCHING CHARACTER WEAPONS " . mysqli_error($this->db);
		}
	}

}
?>
