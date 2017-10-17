<?php
Class CharacterCreation{


	public function rollAttributes($_attribDice){

		$attrib1 = $_attribDice['attrib1'];
		$attrib2 = $_attribDice['attrib2'];
		$Dice = new Dice();
		unset($_attribDice['attrib1'], $_attribDice['attrib2']);

		foreach ($_attribDice as $attrib => $dice) {

			$dice .= '?GE';
			$_parsed = $Dice->parse($dice);
			$sum = 0;
			if ($attrib == $attrib1){

				while($sum < 10){

					$_roll = $Dice->roll($_parsed);
					$sum = "{$_roll['sum']}";
				}
			}
			else if ($attrib == $attrib2){

				while($sum < 8){

					$_roll = $Dice->roll($_parsed);
					$sum = "{$_roll['sum']}";
					//print "Rolling $attrib: $sum<br>";
				}
			}
			else {

				$_roll = $Dice->roll($_parsed);
				$sum = "{$_roll['sum']}";
			}
			if ($sum <= 0){

				$_charAttribs[$attrib] = "ERROR ROLLING ATTRIBUTE $attrib : $dice<br>";
			}
			else {

				$_charAttribs[$attrib] = $sum;
			}
		}
		return $_charAttribs;
	}

	public function calcAbilities($_charAttribs){

		function average($attrib1,$attrib2){

			return round(($attrib1 + $attrib2) / 2);
		}
		$_charAbils['Perception'] = average($_charAttribs['INT'],$_charAttribs['WIS']);
		$_charAbils['Search'] = average($_charAttribs['INT'],$_charAttribs['PAT']);
		$_charAbils['Climb'] = average($_charAttribs['WILL'],$_charAttribs['STR']);
		$_charAbils['Jump'] = average($_charAttribs['WILL'],$_charAttribs['AGI']);
		$_charAbils['Balance'] = average($_charAttribs['PAT'],$_charAttribs['AGI']);
		$_charAbils['Hide'] = average($_charAttribs['WIS'],$_charAttribs['SPD']);
		$_charAbils['Appeal'] = average($_charAttribs['CHA'],$_charAttribs['VIR']);
		return $_charAbils;
	}

	public function addAttributeBonuses($_charAttribs, $_classAttribs){

		foreach ($_charAttribs as $attrib => $AV) {

			//print "$attrib => $AV<br>\n";
			if ($AV >= 12){

				$Dice = new Dice();
				$affects = $_classAttribs[$attrib]['affects'];
				$stack = 1;
				$rem = $AV - 12;

				$add = '';
				while($rem >= 2){

					$rem -= 2;
					$stack++;
				}
				if ($affects == 'LIFE'){

					for ($i = 0; $i < $stack; $i++){

						$add += $Dice->parseAndRoll('1d4?GE');
					}
					$_bonus['Life'] = $add;
				}
				else if ($affects == 'FAITH'){

					for ($i = 0; $i < $stack; $i++){

						$add += $Dice->parseAndRoll('1d4?GE');
					}
					$_bonus['Faith'] = $add;
				}
				else if ($affects == 'GOLD'){

					for ($i = 0; $i < $stack; $i++){

						$add += $Dice->parseAndRoll('2d6x10?GE');
					}
					$_bonus['Gold'] = $add;
					$_bonus['Silver'] = $add;
				}
				else {

					$_bonus[$affects] = $stack;
				}
				//print " = = = =  = > > >Attrib: $attrib $AV : stacks $stack ($affects) + $add<br>";
			}
		}

		return $_bonus;
	}

	public function rollLifeFaith($_diceREF,$_charAttribs){

		$_suppAttribs = explode('-',$_diceREF);
		$dice = array_pop($_suppAttribs) . '?GE';
		$Dice = new Dice();
		$roll = $Dice->parseAndRoll($dice);
		//print "Life/Faith roll: $roll<br>";
		if (count($_suppAttribs) > 0){

			foreach ($_suppAttribs as $attrib) {

				$addRoll = "{$_charAttribs[$attrib]}";
				$roll += "{$_charAttribs[$attrib]}";
				//print "Life/Faith: $roll + $attrib: $addRoll<br>";
			}
		}
		return $roll;
	}

	public function charHeightWeight($minHt, $maxHt, $stature, $STR, $gender)
	{
		$inches = rand($minHt,$maxHt); // Find height in inches
		$_feet = explode('.',($inches / 12));
		$feet = $_feet[0];
		if (($remInches = ($inches % 12)) > 0){

			$remInches = ' ' . $remInches . 'in.';
		}
		else {

			$remInches = '';
		}
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
			'weight' => $weight,
			'height' => $height,
			'inches' => $inches,
			'gender' => $gender,
			'stature' => $stature,
			'LVL' => 1);
		//print "Gender: $gender | Stature: $stature | Weight: $weight lbs. for STR: $STR {$feet}ft. {$remInches}in. min $minHt [rand $inches in.] max $maxHt<br>";
		return $_character;
	}

	public function findSinsPhobias($VIR, $WILL, $_classFacts){

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
			'phobias' => sinsPhobs($WILL,$_phobias),
			'sins' => sinsPhobs($VIR, $_sins));
	}
	public function getSubmitIDs($sIDs){
	$_sID = explode('-',$sIDs); // sID => clr-C6-M12

		$classID = "{$_sID[0]}";
		$CID = str_replace('C','',"{$_sID[1]}");
		$MID = str_replace('M','',"{$_sID[2]}");
		return array (
			'classID' => $classID,
			'CID' => $CID,
			'MID' => $MID);
	}
	public function setSubmitIDs($classID,$CID,$MID){

		return "{$classID}-C{$CID}-M{$MID}";
	}

	public function pickedTooManySkills($_classSkills, $_charSkills, $numAllowed, $skillType){

		$numPicked = 0;
		shuffle($_charSkills);
		while ($numPicked < $numAllowed){

			$pickedSkill = array_pop($_charSkills);
			$_pickedSkills[] = $pickedSkill;
			$numPicked = count($_pickedSkills);
		}
		foreach ($_classSkills as $key => $classSkill) {

			if (in_array($classSkill,$_pickedSkills)){
				$_heldSkills[$key] = $classSkill;
			}
			else {
				$_skillsNotHeld[$key] = $classSkill;
			}
		}
		return array(
			$skillType => $_heldSkills,
			'notHeld' => $_skillsNotHeld);
	}

	public function pickedTooFewSkills($_classSkills, $_charSkills, $numAllowed, $skillType){

		foreach ($_charSkills as $key => $value) {

			$_heldSkills[$key] = $value; // CREATE SEPARATION ARRAY

			unset($_classSkills[$key]);
		}
		$numPicked = count($_charSkills); // i.e. only picked 2
		foreach ($_classSkills as $key => $value) {

			if (in_array($value,$_heldSkills)){
				unset($_classSkills[$key]);
			}
			else {
				$_skillsNotHeld[$key] = $value; // TO BE UNSET
				$_randKey[] = $key;
			}
		}
		shuffle($_randKey);
		while($numPicked < $numAllowed){

			$newKey = array_pop($_randKey);
			$newSkill = "{$_classSkills[$newKey]}";
			unset($_skillsNotHeld[$newKey]);
			$_heldSkills[$newKey] = $newSkill;
			$numPicked = count($_heldSkills);
		}
		return array(
			$skillType => $_heldSkills,
			'notHeld' => $_skillsNotHeld);
	}
	/**
	* Makes only unchosen Skills available to choose
	*
	* @param array $_classSkills
	* @param array $_skillsHeld
	* @param string $skillType // 'gifts','talents','crafts'
	*/
	public function filterHeldSkills($_classSkills, $_skillsHeld, $skillType){

		$_heldSkills = $_skillsHeld[$skillType];// CHOSEN GIFTS OR TALENTS
		foreach ($_classSkills["notHeld"] as $SkID => $notHeld) { // GO THROUGH ALL THE SKILLS THEY HAVE TO CHOOSE FROM

			if (!in_array($notHeld,$_heldSkills)){ // IF THEY DIDN'T CHOSE IT...

				$_skillsNotHeld[] = $notHeld; // .. RECREATE ARRAY OF SKILLS STILL AVAILABLE
			}
		}
		return array(
			$skillType => $_heldSkills,
			'notHeld' => $_skillsNotHeld);
	}

	public function pickSkills($_classSkills, $_charSkills, $skillType){

		$_numsAllowed = array(
			'gifts' => 7,
			'talents' => 5,
			'crafts' => 3);
		$numPicked = count($_charSkills);
		$numAllowed = $_numsAllowed[$skillType];
		foreach ($_charSkills as $key => $checkSkill) {

			if (!in_array($checkSkill,$_classSkills['notHeld'])){
				unset($_charSkills[$key]);
			}
		}
		if ($numPicked != $numAllowed){

			if ($numPicked > $numAllowed){
				$_classSkills = $this->pickedTooManySkills($_classSkills['notHeld'], $_charSkills, $numAllowed, $skillType);
			}
			else if ($numPicked < $numAllowed){

				$_classSkills = $this->pickedTooFewSkills($_classSkills['notHeld'],$_charSkills, $numAllowed, $skillType);
			}
		}
		else {
			$_skillsChosen[$skillType] = $_charSkills;
			$_classSkills = $this->filterHeldSkills($_classSkills,$_skillsChosen,$skillType); // FILTER OUT SKILL TYPE
		}
		return $_classSkills;
	}

	public function findWSComAbs($_charSkills, $_skillCats,$addAtR){

		$_WS = array(
			'WS Hand to Hand' 	=> array('ATT' => false, 'C_S' => false, 'AtR' => 2),
			'WS Light Arms' 	=> array('ATT' => false, 'C_S' => false, 'AtR' => 1),
			'WS Heavy Arms' 	=> array('ATT' => false, 'C_S' => false, 'AtR' => 1),
			'WS Paired Weapons' 	=> array('ATT' => false, 'C_S' => false, 'AtR' => 2),
			'WS Missiles' 		=> array('ATT' => false, 'C_S' => false, 'AtR' => 1),
			'WS Thrown' 		=> array('ATT' => false, 'C_S' => false, 'AtR' => 1),
			'WS Kick Attack' 	=> array('ATT' => false, 'C_S' => false, 'AtR' => 1));
		foreach ($_skillCats as $skillCat) {

			$_skillType = $_charSkills[$skillCat];
			foreach ($_skillType as $skill) {

				$PF = "{$_charSkills[$skill]['PF']}";
				if (substr($skill,0,2) == 'WS'){

					if ($PF > 2){

						$_WS[$skill]['ATT'] = 2;
						$_WS[$skill]['C_S'] = true; // CAN Select +1 to CRI or SPC
						if ($addAtR > 0) {
							$_WS[$skill]['AtR']++; // TODO MAY HAVE TO REDO THIS  IN CASE THEY HAVE MORE addAtRs than Gifts and Talents
							$addAtR--;
						}
					}
					else if ($PF > 1){

						$_WS[$skill]['ATT'] = 1;
						$_WS[$skill]['C_S'] = true; // CAN Select +1 to CRI or SPC
						if ($addAtR > 0) {
							$_WS[$skill]['AtR']++;
							$addAtR--;
						}
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
		}
		return $_WS;
	}
}


?>
