<?php

Class Campaigns{

	private $db;

	public function __construct($db){

		$this->db = $db;
	}

	public function getCampaigns($RAC){
/*
CampID => 1
RAC => Racmaster
MID => 12347
title => Genesis of Champions
MODIFIED => 1505440459
*/
		$db = $this->db;
		$query = "SELECT * FROM campaigns WHERE `MID`=$RAC";
		if ($result = mysqli_query($db, $query)){
			while ($_sql = mysqli_fetch_assoc($result)){
				foreach ($_sql as $key => $value){

					$CampID = "{$_sql['CampID']}";
					$Rac = "{$_sql['RAC']}";
					$RacID = "{$_sql['MID']}";
					$title = "{$_sql['title']}";
					$created = "{$_sql['MODIFIED']}";
					$_camp[$CampID] = array(
						'Rac' => $Rac,
						'RacID' => $RacID,
						'title' => $title,
						'created' => $created,
						'adventures' => $this->getCampAdventures($CampID));

				}
			}
		}
		else {

			return 'Error Fetching Campaings: ' . mysqli_errno($db);
		}
		return $_camp;
	}

	public function getCampAdventures($CampID){
/*
AdvID => 1
CampID => 1
title => The King and the Elves
MODIFIED => 1505440459
*/
		$db = $this->db;
		$query = "SELECT * FROM adventures WHERE `CampID`=$CampID";
		if ($result = mysqli_query($db, $query)){

			while ($_sql = mysqli_fetch_assoc($result)){

				foreach ($_sql as $key => $value){

					$AdvID = "{$_sql['AdvID']}";
					$title = "{$_sql['title']}";
					$created = "{$_sql['created']}";
					$modified = "{$_sql['MODIFIED']}";
					$_adv[$AdvID] = array(
						'title' => $title,
						'created' => $created,
						'modified' => $modified,
						'CampID' => $CampID);
					if (is_array($_characters = $this->getPartyCharacters($AdvID))){

						$_adv[$AdvID]['characters'] = $_characters;
					}
				}

			}
		}
		else {

			return 'Error Fetching Campaign Adventures: ' . mysqli_errno($db);
		}
		return $_adv;
	}
	public function getPartyCharacters($AdvID){
/*
CID => 40
MID => 12348
name => Vilfred Skovgard
LVL => 2
stature => Common
class => Cleric
created => 1504762423
MODIFIED => 1504762423
*/
		$db = $this->db;
		$query = "SELECT * FROM characters WHERE `AdvID`=$AdvID ORDER BY `name`";
		if ($result = mysqli_query($db, $query)){
			while ($_sql = mysqli_fetch_assoc($result)){
				foreach ($_sql as $key => $value){

					$CID = "{$_sql['CID']}";
					$MID = "{$_sql['MID']}";
					$charName = "{$_sql['name']}";
					$charLVL = "{$_sql['LVL']}";
					$stature = "{$_sql['stature']}";
					$charClass = "{$_sql['class']}";
					$gender = "{$_sql['gender']}";
					$_chars[$CID] = array(
						'MID' => $MID,
						'charName' => $charName,
						'charLVL' => $charLVL,
						'stature' => $stature,
						'charClass' => $charClass,
						'gender' => $gender,
						'vitals' => $this->getCharacterVitals($CID));
				}

			}
			return $_chars;
		}
		else {

			print "ERROR FETCHING Adventure Party: " . mysqli_errno($db);
		}

	}
	public function getCharacterVitals($CID){
/*
CID => 43
Life => 19
mLife => 27
Faith => 3
mFaith => 3
EXP => 850
mEXP => 1230
*/
		$db = $this->db;
		$query = "SELECT * FROM character_vitals WHERE `CID`=$CID";
		if ($result = mysqli_query($db, $query)){
			while ($_sql = mysqli_fetch_assoc($result)){

				$life = "{$_sql['Life']}";
				$mLife = "{$_sql['mLife']}";
				$faith = "{$_sql['Faith']}";
				$mFaith = "{$_sql['mFaith']}";
				$EXP = "{$_sql['EXP']}";
				$mEXP = "{$_sql['mEXP']}";
				$_charVits = array(
					'Life' => $life,
					'mLife' => $mLife,
					'Faith' => $faith,
					'mFaith' => $mFaith,
					'EXP' => $EXP,
					'mEXP' => $mEXP);
			}
		}
		else {

			print "ERROR FETCHING Character Vitals: " . mysqli_errno($db);
		}
		return $_charVits;
	}

}
?>
