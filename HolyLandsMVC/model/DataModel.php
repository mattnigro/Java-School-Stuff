<?php

Class DataModel{

	public $db = null;

	protected $badChars = array();

	public function __construct($db)
	{
		$this->db = $db;
		$this->badChars = $this->getBadChars();
	}

	public function fetchCharacterTables()
	{
		return array(
			's',
			'_armor',
			'_ammo',
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
	}

	public function getBadChars()
	{
		return $badChars = array('  ', '--','"','%',';','!=','=','&','*','\\','/','+','(',')','`','?','[',']','{','}','^','<','>','_','@','$','#',':');
	}

	/**
	* Removes unsafe entities, allows: (, . ; -)
	* Converts but does not remove single quote
	* @param string $inputText
	* @param bool $removeEnts // Remove bad chars, default false
	*/
	public function sanatizeDBText($inputText, $removeEnts = false)
	{

		$db = $this->db;
		$badChars = $this->getBadChars();
		if ($removeEnts === true){

			return trim(str_replace($badChars,'',htmlentities(mysqli_real_escape_string($db,$inputText),ENT_QUOTES)));
		}
		else {

			return trim(htmlentities(mysqli_real_escape_string($db,$inputText),ENT_QUOTES));
		}
	}

	public function sanitizeDBnums($int){

		return intval($this->sanatizeDBText($int,true));
	}

	public function nextID($dbTable){

		$_ids = array(
			'members' 		=> 'MID',
			'characters' 	=> 'CID',
			'adventures' 	=> 'AID',
			'campaigns' 	=> 'CampID'
		);
		if (!empty($id = "{$_ids[$dbTable]}")){

			$db = $this->db;
			$query = "SELECT MAX($id) FROM $dbTable";
			if ($result = mysqli_query($db, $query)){
				while ($_sql = mysqli_fetch_assoc($result)){
					foreach ($_sql as $key => $value){

						return "{$_sql["MAX($id)"]}" + 1;
					}
				}
			}
		}
	}


	public function getLockStep ($CID){
		$sql = "
			SELECT `created`
			FROM characters
			WHERE `CID`='{$CID}'";
		if ($result = mysqli_query($this->db, $sql)){
			while ($row = mysqli_fetch_assoc($result)){
				return $row['created'];
			}
		}
		else {
			print 'ERROR FETCHING LOCKSTEP '. mysqli_error($this->db);
			die();
		}
		return false;
	}

	public function getLockStepCharAttribs($CID)
	{
		$sql = "
			SELECT `stature`, `gender`, `classID`
			FROM characters
			WHERE `CID`='{$CID}'";
		if ($result = mysqli_query($this->db, $sql)){
			while ($row = mysqli_fetch_assoc($result)){
				return array(
					'stature' => $row['stature'],
					'gender' => $row['gender'],
					'classID' => $row['classID'],
				);
			}
		}
		else {
			print 'ERROR FETCHING LOCKSTEP '. mysqli_error($this->db);
			die();
		}
		return false;
	}


	public function incrementLockStep($CID)
	{
		$sql ="
			UPDATE `characters`
			SET `created`= `created` +1 , `MODIFIED`='" . MODIFIED . "'
			WHERE `CID`='$CID'";
		if (!$result = mysqli_query($this->db, $sql)){

			print "ERROR INCREMENTING LOCKSTEP " . mysqli_error($this->db);
		}
	}

	public function getFromMID($get, $fromTbl, $MID){

		$db = $this->db;
		$sql = "
			SELECT `$get`
			FROM $fromTbl
			WHERE `MID`='$MID'";
		if ($result = mysqli_query($db, $sql)){
			while ($row = mysqli_fetch_assoc($result)){
				foreach ($row as $key => $value){

					return $row[$get];
				}

			}
		}
	}
}
?>
