<?php
Class CharacterCreation{

	protected $CID = null;
	protected $stature = null;
	protected $classID = null;
	protected $gender = null;

	public function __construct($CID, $stature, $classID, $gender)
	{
		$this->CID 		= $CID;
		$this->stature 	= $stature;
		$this->classID 	= $classID;
		$this->gender 	= $gender;
	}

	public function rollAttributes($_attribDice)
	{
		unset($_attribDice['attribs']['minHt'], $_attribDice['attribs']['maxHt']);
		$attrib1 			= $_attribDice['attrib1'];
		$attrib2 			= $_attribDice['attrib2'];
		$Dice = new Dice();
		foreach ($_attribDice['attribs'] as $attrib => $dice) {
			$dice 			.= '?GE';
			$_parsed 		= $Dice->parse($dice);
			$sum = 0;
			if ($attrib == $attrib1){
				while($sum < 10){
					$_roll 	= $Dice->roll($_parsed);
					$sum 	= "{$_roll['sum']}";
				}
			}
			else if ($attrib == $attrib2){
				while($sum < 8){

					$_roll	= $Dice->roll($_parsed);
					$sum 	= "{$_roll['sum']}";
				}
			}
			else {

				$_roll 	= $Dice->roll($_parsed);
				$sum	= "{$_roll['sum']}";
			}
			if ($sum <= 0){

				$_charAttribs[$attrib] = "ERROR ROLLING ATTRIBUTE $attrib : $dice<br>";
			}
			else {

				$_charAttribs[$attrib] = $sum;
			}
		}
		$_attribBonuses = $this->findAttributeBonuses($_charAttribs);
		$_charAttribs['BONUS'] =  (!empty($_attribBonuses) ? $_attribBonuses : null);
		return $_charAttribs;
	}


	public function findAttributeBonuses($_charAttribs)
	{
/*
INT : CRAFTS
WIS : GIFTS
PAT : FAITH 	<-/
WILL : SAVES
MEM : TALENTS
STR : DAM 		<-/
AGI : AtR
SPD: DOD 		<-/
END : LIFE 		<-/
BTY : GOLD 		<-/
CHA : GOLD 		<-/
VIR : SINS
*/

		$_increase = array();
		foreach ($_charAttribs as $attrib => $AV) {
			if ($AV >= 12){
				$_increase[$attrib] = 1;
				$remainderAV = $AV - 12;
				while($remainderAV >= 2){
					$_increase[$attrib] += 1;
					$remainderAV -= 2;
				}
			}
		}
		return $_increase;
	}


	public function findCharSaves($_saveOpts, $_charAttribBonuses)
	{
		$charSaves = (!empty($_charAttribBonuses['WILL']) ? 1 + $_charAttribBonuses['WILL'] : 1);
		shuffle($_saveOpts);
		while ($charSaves > 0){
			$charSaves--;
			$saveOpt = array_pop($_saveOpts);
			$_charSaves[] = $saveOpt;
		}
		return $_charSaves;
	}

	public function rollCharVariables($_charVarDice, $_charAttribs)
	{
		$Dice = new Dice();
		$STR = $_charAttribs['STR'];
		$END = $_charAttribs['END'];
		$_charVars['Life'] = ($STR + $END) + $Dice->parseAndRoll($_charVarDice['lifeREF']);
		$_faithAttribs = array(
			'PAT' => $_charAttribs['PAT'],
			'VIR' => $_charAttribs['VIR']
		);
		$_faithREF 			= explode('-', $_charVarDice['faithREF']);
		$faithDice 			= array_pop($_faithREF) . '?GE';
		$_charVars['Faith'] = $Dice->parseAndRoll($faithDice);
		if (count($_faithREF) > 0){
			foreach ($_faithREF as $attrib) {
				$_charVars['Faith'] += "{$_faithAttribs[$attrib]}";
			}
		}
		$_charVars['gold'] 		= $Dice->parseAndRoll("{$_charVarDice['gold']}");
		$_charVars['silver'] 	= $Dice->parseAndRoll("{$_charVarDice['silver']}");
		$lifeIncrease 			= (!empty($_charAttribs['BONUS']['END']) ? $_charAttribs['BONUS']['END'] : 0);
		$faithIncrease 			= (!empty($_charAttribs['BONUS']['PAT']) ? $_charAttribs['BONUS']['PAT'] : 0);
		$_charVars['Life'] 		+= $Dice->parseAndRoll("{$lifeIncrease}d4?GE");
		$_charVars['Faith'] 	+= $Dice->parseAndRoll("{$faithIncrease}d4?GE");
		$goldIncrease 			= (!empty($_charAttribs['BONUS']['CHA']) ? $_charAttribs['BONUS']['CHA'] : 0);
		$goldIncrease 			+= (!empty($_charAttribs['BONUS']['BTY']) ? $_charAttribs['BONUS']['BTY'] : 0);
		for($i = 0; $i < $goldIncrease; $i++){
			$_charVars['gold'] += $Dice->parseAndRoll('2d4x50');
		}
		return $_charVars;
	}


	public function calcAbilities($_charAttribs)
	{
		function average($attrib1, $attrib2){

			return intval(round(($attrib1 + $attrib2) / 2));
		}
		$_charAbils['Perception'] 	= average($_charAttribs['INT'], $_charAttribs['WIS']);
		$_charAbils['Search'] 		= average($_charAttribs['INT'], $_charAttribs['PAT']);
		$_charAbils['Climb'] 		= average($_charAttribs['WILL'], $_charAttribs['STR']);
		$_charAbils['Jump'] 		= average($_charAttribs['WILL'], $_charAttribs['AGI']);
		$_charAbils['Balance'] 		= average($_charAttribs['PAT'], $_charAttribs['AGI']);
		$_charAbils['Hide'] 		= average($_charAttribs['WIS'], $_charAttribs['SPD']);
		$_charAbils['Appeal'] 		= average($_charAttribs['CHA'], $_charAttribs['VIR']);
		return $_charAbils;
	}

	public function calcCombatAbilities($_charAttribs)
	{
		$_charComabs = array(
			'ADV' => '0',
			'DOD' => '0',
			'DEF' => '0',
			'DAM' => '0'
		);
		$DAMIncrease = (!empty($_charAttribs['BONUS']['STR']) ? $_charAttribs['BONUS']['STR'] : null);
		$_charComabs['DAM'] += $DAMIncrease;
		$SPDIncrease = (!empty($_charAttribs['BONUS']['SPD']) ? $_charAttribs['BONUS']['SPD'] : null);
		$_charComabs['DOD'] += $SPDIncrease;
		return $_charComabs;
	}

	public function randomCharName($_charNames){

		$gender = $this->gender;
		$key = rand(1,count($_charNames) - 1);
		$charName = "{$_charNames[$key]}";
		$_titles['M'] = array('Sir','Lord','Master','Brother','Father');
		$_titles['F'] = array('Lady','Mistress','Lady','Maid','Sister', 'Mother');
		$_randTitles = $_titles[$gender];
		shuffle($_randTitles);
		return array_pop($_randTitles) . " $charName";
	}

	public function findCharacterData($_heightRange, $_charAttribs, $_charNames)
	{
		$gender = $this->gender;
		$stature = $this->stature;
		$minHt = $_heightRange['minHt'];
		$maxHt = $_heightRange['maxHt'];
		$inches = rand($minHt,$maxHt); // Find height in inches
		$_feet = explode('.',($inches / 12));
		$feet = $_feet[0];
		$rand = null;
		if (($remInches = ($inches % 12)) > 0){

			$remInches = ' ' . $remInches . 'in.';
		}
		else {

			$remInches = '';
		}
		$STR = $_charAttribs['STR'];
		switch($stature){
			case 'comm': //  = = = = Stature
			$weight = 103; // Starting Weight
			$count = ($STR - 6); // Diff between min and max STR
			do {
				$rand += rand(0,20); // Random Incremental Weight based on STR
				$count--;
			} while ($count >= 0); // Runs at least once
			$weight += $rand;
			break;
			case 'dwar':
			$weight = 112;
			$count = ($STR - 8);
			do {
				$rand += rand(0,18);
				$count--;
			} while ($count >= 0);
			$weight += $rand;
			break;
			case 'wee':
			$weight = 19;
			$count = ($STR - 2);
			do {
				$rand += rand(0,10);
				$count--;
			} while ($count >= 0);
			$weight += $rand;
			break;
			case 'giant':
			$weight = 218;
			$count = ($STR - 10);
			do {
				$rand += rand(0,25);
				$count--;
			} while ($count >= 0);
			$weight += $rand;
			break;
		};
		if ($gender == 'M'){ // add 12% for males

			$weight += ($weight * 0.12);
		}
		$weight = round($weight,0);
		$height = "{$feet}ft.{$remInches}";
		$_character = array(
			'CID' => $this->CID,
			'name' => $this->randomCharName($_charNames),
			'classID' => $this->classID,
			'weight' => $weight,
			'height' => $height,
			'inches' => $inches,
			'gender' => $gender,
			'stature' => $stature,
			'LVL' => 1);
		return $_character;
	}

	public function findSinsPhobias($_charAttribs, $_classFacts)
	{

		function sinPhobNum($AV){

			switch($AV){
				case ($AV >= 12): return false;
				case ($AV >= 10): return 1;
				case ($AV >= 8): return 2;
				case ($AV >= 6): return 3;
				case ($AV >= 4): return 4;
				case ($AV >= 2): return 5;
			}
		}
		function sinsPhobs($AV,$_probsArray){

			$probsNum = sinPhobNum($AV);
			$_problems = array();
			if (is_array($_probsArray)){

				shuffle($_probsArray);
				while ($probsNum > 0){

					$problem = array_pop($_probsArray);
					$_problems[] = $problem;
					$probsNum--;
				}
				return $_problems;
			}
			else {

				return false;
			}
		}
		$_phobias = $_classFacts['phobias'];
		$_sins = $_classFacts['sins'];
		return array(
			'phobias' 	=> sinsPhobs($_charAttribs['WILL'],$_phobias),
			'sins' 		=> sinsPhobs($_charAttribs['VIR'], $_sins));
	}

	public function findBlessings($_classBlessings, $Faith)
	{
		$_blessings = array();
		while ($Faith >= 5){
			$Faith -= 5;
			$countBlessings = 0;
			while ($countBlessings < 2){
				$rand = rand(0, 99);
				foreach ($_classBlessings as $min => $_maxBlessing) {
					foreach ($_maxBlessing as $max => $blessing) {
						if ((($rand >= $min) && ($rand <= $max)) && (!in_array($blessing, $_blessings))){
							$_blessings[$blessing] = $blessing;
							$countBlessings++;
						}
					}
				}
			}
		}
		return $_blessings;
	}

	// = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = //
	// = = = = = = = = =  = = = = = FIND CLASS SKILLS =  = = = = = = = = = = = = = //
	// = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = //
	public function findClassGifts($_classSkills)
	{
		$classID = $this->classID;
		$_gifts = array();
		if (($classID != 'adv') && ($classID != 'fig')){
			for($i = 1; $i <= 7; $i++){
				$_gifts[] = $_classSkills[$i];
			}
		}
		return $_gifts;
	}

	public function countSelectedSkills($_classSkills, $skillCat, $_charSkills = array(), $_selectedSkills = array())
	{
		$_skillCats = array(
			'gifts' 	=> 7,
			'talents' 	=> 5,
			'crafts' 	=> 3
		);
		$correctAmount = $_skillCats[$skillCat];
		if (count($_selectedSkills < $correctAmount)){
			shuffle($_classSkills);
			while(count($_selectedSkills) < $correctAmount){
				$randomSkill = array_pop($_classSkills);
				if ((!in_array($randomSkill, $_selectedSkills)) && (empty($_charSkills[$randomSkill]))){
					$_selectedSkills[] = $randomSkill;
				}
			}
		}
		else if (count($_selectedSkills) > $correctAmount){
			shuffle($_selectedSkills);
			while (count($_selectedSkills > $correctAmount)){
				$randomSkill = array_pop($_selectedSkills);
			}
		}
		sort($_selectedSkills, SORT_FLAG_CASE | SORT_NATURAL);
		return $_selectedSkills;
	}


	public function findWSComAbs($_charSkills){

		$_skillCats = array('gifts','talents','crafts');
		foreach ($_skillCats as $skillCat) {
			unset($_charSkills[$skillCat]);
		}
		foreach ($_charSkills as $skill => $_PF) {
			$PF = (isset($_charSkills[$skill]['PF']) ? $_charSkills[$skill]['PF'] : $_charSkills["[ {$skill} ]"]['PF']);
			if (substr($skill,0,2) == 'WS'){
				$_WS[$skill]['AtR'] = null;
				if ($PF > 2){

					$_WS[$skill]['ATT'] = 2;
					$_WS[$skill]['C_S'] = true; // CAN Select +1 to CRI or SPC
				}
				else if ($PF > 1){

					$_WS[$skill]['ATT'] = 1;
					$_WS[$skill]['C_S'] = true; // CAN Select +1 to CRI or SPC
				}
				else if ($PF > 0){

					$_WS[$skill]['ATT'] = 1;
				}
				$_WS[$skill]['AtR']++;
			}
			else if ($skill == 'Combat Abilities'){

				$_WS[$skill] = $PF;
			}
		}
		return $_WS;
	}
}


?>
