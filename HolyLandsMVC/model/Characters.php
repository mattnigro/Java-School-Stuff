<?php

Class Characters{

	private $db;

	private $_weaponsClasses = array(); // Unique classIDs for selecting initial weapons


	public function __construct($db){

		$this->db = $db;
	}

	public function fetchAllCharacters($MID = null)
	{
		$db = $this->db;
		$_characters = array();
		$_members = array();
		$WHERE = (!is_null($MID) ? "WHERE MID='{$MID}'" : null);
		$sql = "
			SELECT c.*, m.*
			FROM `characters` c
			JOIN `members` m
			ON m.MID = c.MID
			ORDER BY m.firstName
			{$WHERE}";
		if ($result = mysqli_query($db, $sql)){
			while ($row = mysqli_fetch_assoc($result)){
				foreach ($row as $key => $value){
/**
*Array
(
    [CID] => 2
    [MID] => 13
    [name] => Master Badulf
    [LVL] => 1
    [stature] => dwar
    [class] => Cleric
    [classID] => clr
    [gender] => M
    [age] => 26
    [height] => 5ft. 2in.
    [inches] => 62
    [weight] => 195
    [created] => 1523079137
    [MODIFIED] => 1522810240
    [firstName] => Robert
    [lastName] => Nigro
    [email] => me@mattnigro.com
    [password] => d0b3d457b5b20cfee4c57b0adebee688
    [access] => 1
    [joined] => 1522810240
)
*/
					$MID 		= $row['MID'];
					$CID 		= $row['CID'];
					$firstName 	= $row['firstName'];
					$lastName 	= $row['lastName'];
					$name 		= $row['name'];
					$LVL 		= $row['LVL'];
					$stature 	= $row['stature'];
					$class 		= $row['class'];
					$gender 	= $row['gender'];
					$created 	= $row['created'];
					$_members[$MID]['firstName'] = $row['firstName'];
					$_members[$MID]['lastName'] = $row['lastName'];
					$_members[$MID]['memberChars'][$CID] = array(
						'name'		=> $name,
						'LVL'		=> $LVL,
						'stature' 	=> $stature,
						'class' 	=> $class,
						'gender' 	=> $gender,
						'created' 	=> $created,
						'view'		=> 'admin'
					);
				}
			}
		}
		else {
			print "ERROR FETCHING CHARACTERS " . mysqli_error($db);
			die();
		}
		return $_members;
	}


	public function fetchMemberCharacters($MID)
	{
		$_characters = array();
		$sql = "
			SELECT c.*, cv.EXP, cv.gold, cv.silver
			FROM characters c
			JOIN character_variables cv
			ON c.CID = cv.CID
			WHERE MID = '{$MID}'";
		if ($result = mysqli_query($this->db, $sql)){
			while ($row = mysqli_fetch_assoc($result)){
				foreach ($row as $key => $value){

					$CID 		= $row['CID'];
					$name 		= $row['name'];
					$LVL 		= $row['LVL'];
					$stature 	= $row['stature'];
					$class 		= $row['class'];
					$gender 	= $row['gender'];
					$created 	= $row['created'];
					$EXP 		= $row['EXP'];
					$gold 		= $row['gold'];
					$silver 	= $row['silver'];
					$_characters[$CID] = array(
						'name' 		=> $name,
						'LVL' 		=> $LVL,
						'stature' 	=> $stature,
						'class' 	=> $class,
						'gender' 	=> $gender,
						'created' 	=> $created,
						'gold' 		=> $gold,
						'silver' 	=> $silver,
						'view' 		=> null
					);
				}
			}
		}
		else {
			print "ERROR FETCHING CHARACTER " . mysqli_error($db);
			die();
		}
		return $_characters;
	}


	public function fetchWeaponClasses()
	{
		$sql = "
			SELECT DISTINCT class
			FROM `dbchar_weapons`";
		if ($result = mysqli_query($this->db, $sql)){
			while ($row = mysqli_fetch_assoc($result)){
				$_classes[] = $row['class'];
			}
		}
		else {
			print "ERROR FETCHING WEAPONS CLASSES " . mysqli_error($this->db);
			die();
		}
		return $_classes;
	}

	public function fetchClassWeapons($statCat, $classID)
	{
		$_classes = $this->fetchWeaponClasses();
		$classID = (in_array($classID, $_classes) ? $classID : 'com');
		$statureWeapon = ($statCat == 'dwar' || $statCat == 'comm' ? 'cw.weapon' : "CONCAT('{$statCat} ', cw.weapon)");
		$_weapons = array();
		$sql = "
			SELECT w.WS, w.weapon, w.DAM, w.value, w.AVG, w.image
			FROM `dbchar_weapons` cw
			JOIN `dbweapons` w
			WHERE cw.class = '{$classID}'
			AND w.weapon = {$statureWeapon}
			ORDER BY w.value, w.AVG";
		if ($result = mysqli_query($this->db, $sql)){
			while ($row = mysqli_fetch_assoc($result)){
				$WS = $row['WS'];
				$weapon = $row['weapon'];
				$_weapons[$WS][$weapon] = array(
					'value'		=> $row['value'],
					'AVG' 		=> $row['AVG'],
					'image'		=> $row['image'],
					'DAM'		=> $row['DAM'],
				);
			}
		}
		else {
			print "ERROR FETCHING CLASS WEAPONS " . mysqli_error($this->db);
		}
		return $_weapons;
	}

	public function fetchClassArmor($statCat, $classID)
	{
		$statureArmor = ($statCat == 'dwar' || $statCat == 'comm' ? 'ca.armor' : "CONCAT('{$statCat} ', ca.armor)");
		$_armor = array();
		$sql = "
			SELECT a.armor, a.AP, a.aDEF, a.value, a.image
			FROM `dbarmor` a
			JOIN `dbchar_armor` ca
			ON a.armor = {$statureArmor}
			WHERE ca.class = '{$classID}'
			ORDER BY a.value";
		if ($result = mysqli_query($this->db, $sql)){
			while ($row = mysqli_fetch_assoc($result)){
				$AP = $row['AP'];
				$armor = $row['armor'];
				$_armor[$AP][$armor] = array(
					'value'		=> $row['value'],
					'aDEF' 		=> $row['aDEF'],
					'image'		=> $row['image']
				);
			}
		}
		else {
			print "ERROR FETCHING CLASS ARMOR " . mysqli_error($this->db);
		}
		return $_armor;
	}


	public function deleteCharacter($CID)
	{
		$db = $this->db;
		$DataModel = new DataModel($db);
		$_charTables = $DataModel->fetchCharacterTables();
		if ((is_array($_charTables)) && (isset($CID))){
			foreach ($_charTables as $charTable) {
				//dd($_charTables);
				$dbTable = 'character' . $charTable;
				$sql = "DELETE FROM {$dbTable} WHERE `CID`='{$CID}'";
				if (!$result = mysqli_query($db,$sql)){

					print "<br>FAILED TO DELETE FROM {$charTable} " . mysql_error($this->db);
				}
			}
		}
	}
}
?>
