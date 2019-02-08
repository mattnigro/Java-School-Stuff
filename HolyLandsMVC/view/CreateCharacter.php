<?php
Class CreateCharacter{

	public function statureFolk($stature = null){

		$_statureFolk = array(
			'comm' => 'CommonFolk',
			'dwar' => 'DwarFolk',
			'wee' => 'WeeFolk',
			'giant' => 'GiantFolk');
		if (empty($stature)){

			return $_statureFolk; // Return entire array of options
		}
		else {

			$stature = strtolower($stature);
			return "{$_statureFolk[$stature]}"; // Just need the name
		}
	}
	public function SelectClasses($_charClasses, $MID){

		$_statureFolk = $this->statureFolk();
		$characterTable = "
<table style='margin:0 auto' id='charsTable'>
	<tr>
		<td colspan='2' style='text-align: center'>
			<img src='../images/characters/LU_statures.png' title='Character Statures'></td>\n";
		$count = 2;
		foreach ($_charClasses as $classID => $_charClass) {

			$img = "{$_charClass['image']}";
			$class = "{$_charClass['class']}";
			$attribute1 = "{$_charClass['attribute1']}";
			$attribute2 = "{$_charClass['attribute2']}";
			$attrib1 = "{$_charClass['attrib1']}";
			$attrib2 = "{$_charClass['attrib2']}";
			$lifeRef = "{$_charClass['lifeREF']}";
			$faithRef = "{$_charClass['faithREF']}";
			$lifeLVL = "{$_charClass['lifeLVL']}";
			$faithLVL = "{$_charClass['faithLVL']}";
			$blessings = "{$_charClass['blessings']}";
			$gold = "{$_charClass['gold']}";
			$silver = "{$_charClass['silver']}";
			$_modalPop[$classID] = array(
				'Class: The ' => $class,
				'Primary Attribute: '=> $attrib1,
				'Secondary Attribute: '=> $attrib2,
				'Life: '=> "STR+END +$lifeRef: +$lifeLVL/LVL",
				'Faith: '=> str_replace('-','+',$faithRef) . ": +$faithLVL/LVL",
				'Blessings Type: '=> $blessings,
				'Starting Coinage: '=> str_replace('?GE','',$gold) . 'g, ' . str_replace('?GE','',$silver) . 's');
			$modalPop = $this->modal_pop($_modalPop);
			$count++;
			if ($count === 1){

				$characterTable .= "

<!-- = = = = = = = = BEGIN CHARACTER CLASS ROW = = = = = = = = = -->
			<tr>";
			}
			$characterTable .= "
<!-- = = = = = = = = BEGIN CHARACTER CLASS CELL = = = = = = = = = -->
<td style='vertical-align: top'>
	<form name='create_{$classID}' action='../olg/' method='POST'>
	<fieldset>
		<legend>{$class}</legend>
	<table>
		<tr class='trHighlightGo'>
			<td colspan='2' class='tdCharClassLU'>\n";
			if (is_array($_statures = $_charClass['statures'])){

				$statRowCount = 1;
				$statureOpts = "
<tr class='trHighlightGo'>
	<td>
		<input type='radio' id='comm{$classID}' name='{$classID}' value='comm' checked='true'>
			<label for='comm{$classID}'>CommonFolk</label></td>
	<td style='text-align: right'>
		 <label for='{$classID}m'> Male </label>
		 <input type='radio' id='{$classID}m' name='gender' value='M' checked='checked'> </td>
				</tr>\n";
				foreach ($_statures as $stat) {

					$femaleOpt = '';
					if ($statRowCount === 1){
						$femaleOpt = "
<label for='{$classID}f'> Female </label>
<input type='radio' id='{$classID}f' name='gender' value='F'> \n";
					}
					if ($stat != 'comm'){
						$statRowCount++;
						$stature = "{$_statureFolk[$stat]}";
						$statureOpts .= "
<tr class='trHighlightGo'>
	<td>
		<input type='radio' id='$stat{$classID}' name='$classID' value='$stat'> <label for='$stat{$classID}'>$stature</label></td>
	<td style='text-align: right'>
		$femaleOpt</td></tr>\n";
					}
				}
				while ($statRowCount < 4) {

					$femaleOpt = '';
					if ($statRowCount === 1){
						$femaleOpt = "
<label for='{$classID}f'> Female </label>
<input type='radio' id='{$classID}f' name='gender' value='F'> \n";
					}
					$statRowCount++;
					$statureOpts .= "
<tr class='trHighlightNo'>
	<td>
		<img src='../images/icons/badRadio.png' class='badRadio'> - - - - - - - </td>
	<td style='text-align: right'>
		$femaleOpt</td></tr>\n";
				}
			}
			$characterTable .= "
	<img src='../images/characters/LU_{$img}' class='smCharImg' title='The $class' onclick='charTip(\"#m{$classID}\")'></td></tr>
<tr>
	<td colspan='2'>$modalPop
		<input type='hidden' name='sID' value='$classID-M{$MID}'>
		<input type='hidden' name='action' value='initChar'>
		<input type='submit' name='create_{$classID}' class='btnSubmit' value='Create $class'></td></tr>
$statureOpts
</table></fieldset>
</form>

</td>\n";
			if ($count === 5){
				$characterTable .= "</tr>\n";
				$count = 0;
			}
			$_charClass = '';
		}
		$characterTable .= "
		</tr></table>\n";
		return $characterTable;
	}


	private function modal_pop($_modalPop){
/*
kni : Class: The => Knight
kni : Primary Attribute: => Endurance
kni : Secondary Attribute: => Strength
kni : Life: => 1d8 + 1d6/LVL
kni : Faith: => PAT+1d4 + 1d4/LVL
kni : Blessings Type: => Courage
kni : Starting Coinage: => 5d4x10g, 1d4x3s
*/
			//asort($_modalPop);
			foreach ($_modalPop as $classID => $_class) {

				$modalPop = "
				<div class='modalPop' id='m{$classID}'>\n";
					foreach ($_class as $key => $value) {

						$modalPop .= "<b>$key</b> $value<br>\n";
					}
					$modalPop .= "</div>\n";
			}
			return $modalPop;
	}


	// = = = = = = = = = = = BEGIN CHARACTER DATA = = = =  = = = = = = = = //
	public function viewAttribsTable($_charAttribs)
	{
		$tblCharAttribs = "
		<table id='tblCharAttribs'>\n";
		foreach ($_charAttribs as $attrib => $AV) {

			$dispAV = ($AV >= 12 ? "<b>{$AV}</b>" : $AV);
			$tblCharAttribs .= "
			<tr>
				<td class='tdAttribs'>
					<b>{$attrib}</b>:
				</td>
				<td class='tdAttribs'>
					{$dispAV}
				</td>
			</tr>\n";
		}
		return $tblCharAttribs .= "
		</table>\n";
	}

	public function viewAbilsTable($_charAbils){

		$tblCharAbils = "
		<table id='tblCharAbils'>\n";
		foreach ($_charAbils as $abil => $PF) {

			$tblCharAbils .= "
			<tr>
				<td class='paddedCell'>
					<b>{$abil}</b>:
				</td>
				<td class='paddedCell'>
					+{$PF}
				</td>
			</tr>\n";
		}
		return $tblCharAbils .= "
		</table>\n";
	}

	public function initCharDetails($_charData, $_classFacts, $_saveOpts)
	{
		$_character 	= $_charData['character'];
		$_charAttribs 	= $_charData['charAttribs'];
		$_charAbils 	= $_charData['charAbils'];
		$_charVars 		= $_charData['charVars'];
		$_charEAV		= $_charData['charEAV'];
		$_charSaves		= $_charData['charSaves'];
		$CID 			= "{$_character['CID']}";
		$classID 		= "{$_character['classID']}";
		$MID 			= "{$_character['MID']}";
		$attribsTable 	= $this->viewAttribsTable($_charAttribs);
		$abilsTable 	= $this->viewAbilsTable($_charAbils);
		$charVarTable 	= $this->viewCharVariables($_character, $_charVars, $_charEAV, $_classFacts);
		$charSaveOpts	= $this->viewSaveOptions($_saveOpts, $_charSaves);
		$Controls = new ControllerModel();
		$sID = $Controls->encryptSubmitIDs($classID, $CID, $MID);
		return "
	<table class='charsTable'>
		<tr>
			<td id='tdCharImg' style='padding: 3px'>
			</td>

			<td class='tdCharData' style='padding: 3px'>
				<form action='/olg/?q=uc' name='formCharDetails' method='POST'>
					<fieldset>
						<legend>Create Character Details</legend>
						<table id='tblCharData'>
							<tr>
								<td id='tdCharVars' style='vertical-align: top; text-align: left'>
									{$charVarTable}
									<table style='width: 100%;'>
										<tr>
											<td style='text-align: right; padding-top: 7px;'>
												<input type='submit' class='btnSubmit' value='< < Reselect Character' name='btnDelete' id='btnDelete'>
												<input type='submit' class='btnSubmit' value='Choose Skills > >' name='btnSkills' id='btnBegin'>
												<input type='hidden' name='staging' value='skills'>
												<input type='hidden' name='action' value='charInit'>
												<input type='hidden' name='sID' value='{$sID}'>
											</td>
										</tr>
									</table>

								</td>
								<td id='tdCharAttribs' style='vertical-align: top; text-align: left'>
									Attributes
									 {$attribsTable}
								 </td>
								<td id='tdCharAbils' style='vertical-align: top; text-align: left'>

									Abilities
									{$abilsTable}
									<hr>
									Saving Throws
									{$charSaveOpts}
								</td>
							</tr>
						</table>
					</fieldset>
				</form>
			</td>
		</tr>
	</table>\n"; // END CHAR
	}

	private function landLanguageOptions($_classFacts){

		$_landLangs = $_classFacts['landLangs'];
		$max = count($_landLangs);
		$rand = rand(1,$max);
		$landOpts =
		"<select name='land' class='selectOpts' style='width: 140px'>\n";
		$langOpts =
		"<select name='lang' class='selectOpts' style='width: 270px'>\n";
		$_selects = array(2,3,4,5,6,7,9,10,11); // INDICES of COMMON LANDS (i.e. Englands, Ireland, etc.)
		shuffle($_selects);
		$select = array_pop($_selects);
		$count = 0;
		foreach ($_landLangs as $land => $_langs) {

			$count++;
			$count == $select ? $selected = "selected='true'" : $selected = '';
			$language = $_langs['languages'];
			$langGroup = $_langs['langGroup'];
			$landOpts .= "<option value='$land'{$selected}>$land</option>\n";
			$langOpts .= "<option value='$language'{$selected}>$language ($langGroup)</option>\n";
		}
		$landOpts .= "</select>\n";
		$langOpts .= "</select>\n";
		return array(
			'landOpts' => $landOpts,
			'langOpts' => $langOpts);
	}

	public function viewCharVariables($_character, $_charVars, $_charEAV, $_classFacts){

		$stature 		= "{$_character['stature']}";
		$statureFolk	= $this->statureFolk($stature);
		$classID 		= "{$_character['classID']}";
		$class 			= "{$_character['class']}";
		$_phobias 		= (!empty($_charEAV['phobias']) ? $_charEAV['phobias'] : null);
		$_sins	 		= (!empty($_charEAV['sins']) ? $_charEAV['sins'] : null);
		$charName 		= "{$_character['name']}";
		$sins 			= null;
		$phobias 		= null;
		if (is_array($_landlangOpts = $this->landLanguageOptions($_classFacts))){

			$landOpts = "{$_landlangOpts['landOpts']}";
			$langOpts = "{$_landlangOpts['langOpts']}";
			$trLandLangs = "
			<tr>
				<td colspan='4' class='paddedCell'>
				<table>
					<tr>
						<td><b>Land of Origin</b></td>
						<td><b>Spoken Language Group</b></td></tr>
					<tr class='trHighlightGo'>
						<td>
							{$landOpts}</td>
						<td>
							{$langOpts}</td></tr></table>
					</td></tr>\n";
		}
		if ($_phobias){

			$phobias = "
			<tr>
				<td colspan='4' class='paddedCell'>
					<b>Phobias</b>: " . implode(', ',$_phobias) . "</td></tr>\n";
		}
		if ($_sins){

			$sins = "
			<tr>
				<td colspan='4' class='paddedCell'>
					<b>Sins</b>: " . implode(', ',$_sins) . "</td></tr>\n";
		}
		$ageDropDown  = "
			<select name='age'>\n";
			$rand = rand(20,30);
			for ($age = 16; $age <= 43; $age++){
				$age == $rand ? $selected = "selected='selected'" : $selected = '';
				$ageDropDown .= "<option value='$age'{$selected}>$age</option>\n";
			}
			$age .= "</select>\n";
		return $tblCharVars = "
		<table id='tblCharVars'>
			<tr>
				<td colspan='4'class='paddedCell' style='padding-top: 0'>
					<h2 style='margin: 0; padding: 0'>
						<span id='spanCName'>{$charName}</span>
					</h2>
						<h4 style='margin: 0; padding: 0'>LVL 1 {$statureFolk} {$class}</h4>
					</h3>
				</td>
			</tr>
			<tr class='trHighlightGo'>
				<td colspan='4'class='paddedCell'>
					<input type='text' id='txtCharName'
						onfocus='this.value.select'
						onblur='if(this.value==\"\"){this.value=\"{$charName}\"};'
						required='required' id='txtCharName'
						name='charName' value='{$charName}'
						placeholder='Enter Character Name Here'
						style='' maxlength='40'>
				</td>
			</tr>
			$trLandLangs
			<tr>
				<td class='paddedCell quarterCell'>
					<b>Age:</b>$ageDropDown
				</td>
				<td class='paddedCell'>
					<b>Height:</b> {$_character['height']}
				</td>
				<td class='paddedCell quarterCell'>
					<b>Weight:</b> {$_character['weight']}lbs.
				</td>
				<td class='paddedCell quarterCell'>
					<b>Gender:</b> {$_character['gender']}
				</td>
			</tr>
			$sins
			$phobias
			<tr>
				<td class='paddedCell quarterCell'>
					<b>Life:</b> {$_charVars['Life']}
				</td>
				<td class='paddedCell'>
					<b>Faith:</b> {$_charVars['Faith']}
				</td>
				<td class='paddedCell quarterCell'>
					<b>Gold:</b> {$_charVars['gold']}g
				</td>
				<td class='paddedCell quarterCell'>
					<b>Silver:</b> {$_charVars['silver']}s
				</td>
			</tr>
		</table>\n";
	}


	public function viewSaveOptions($_saveOpts, $_charSaves)
	{
		$saveOpts = null;
		$selectOpts = "
	<table>\n";
		foreach ($_charSaves as $charSave => $PF) {

			$saveOpts .= "
		<tr class='trHighlightGo'>
			<td>
				<select name='saves[]' style='width: 130px'>\n";
			sort($_saveOpts);
			foreach ($_saveOpts as $saveOpt) {

				$selected = ($saveOpt == $charSave ? ' selected="selected"' : null);
				$saveOpts .= "
					<option value='$saveOpt'{$selected}>+1 vs. $saveOpt</option>\n";
			}
		}
		$saveOpts .= "
				</select>
			</td>
		</tr>\n";
		$selectOpts .= $saveOpts;
			return $selectOpts . "
	</table>\n";;
	}


	private function drawSkillRow($SkID, $skill, $bonus){

		$count = 0;
		$_selectSkills = array('WS Light Arms', 'Combat Abilities', 'Resilience', 'Specialist', 'Sneak', 'Medical');
		if (in_array($skill, $_selectSkills)){

			$checked = " checked='checked'";
			$border = " style='border: 1px solid #0000FF;'";
		}
		else {

			$checked = '';
			$border = " style='border: 0px solid #FFFFFF;';";
		}
		$count++;
		return "
			<tr id='tr{$SkID}' class='trHighlightGo'{$border}>
				<td id='td{$SkID}' style='vertical-align: middle'>
					<label for='SkID{$SkID}'>$skill</label>
				</td>
				<td style='vertical-align: middle'>
					<label for='SkID{$SkID}'>$bonus<label>
				</td>
				<td style='vertical-align: middle'>
					<input type='checkbox'
						id='SkID{$SkID}'
						name='SkID[$SkID]'
						value='$skill'{$checked}
						onclick='trCheck(tr{$SkID},this)'>
				</td>
			</tr>\n";
	}

	public function selectGiftsTable($_classSkills){

		if (!empty($_classSkills)){ // SELECT GIFTS FOR ADVENTURER AND FIGHTER

			$oneThird = round((count($_classSkills) / 3),0);
			$count = 0;
			$bonus = '+2';
			$thisTable = '';
			foreach ($_classSkills as $SkID => $skill) {

				$count++;
				$thisTable .= $this->drawSkillRow($SkID,$skill,$bonus);
				if ($count == ($oneThird * 1)){
					$giftTable = "
					<table class='tblCharOptions'>
						$thisTable
					</table>\n";
					$thisTable = '';
				}
				if ($count == ($oneThird * 2)){
					$talentTable = "
					<table class='tblCharOptions'>
						$thisTable
					</table>\n";
					$thisTable = '';
				}
			}
			$craftTable  = "
				<table class='tblCharOptions'>
				$thisTable</table>\n";

			$message = "<p>The Adventurer and Fighter Character Classes have the option to choose any Skills
			they want as Gifts, Talents, and Crafts; but they all start a the same proficiency.</p>
			<p><b>Gifts</b>: Select any seven (7) Skills from the left to add as your character's Gifts.<br>
			These are Skills in which your character will have the highest proficiency as he or she levels up.
			<br>If you select more or less than seven Skills, the system will randomly select some for you.<br>
			Click the button below after you have chosen your character's Gifts.<br></p>
			<p><b>Note</b>: Skills that are often critical for gameplay are automatically selected for you, but they are optional.</p>";

			return array(
				'giftTable' => $giftTable,
				'talentTable' => $talentTable,
				'craftTable' => $craftTable,
				'message' => $message);
		}
	}

	public function selectTalentsTable($_charGifts, $_classSkills, $classID){

		$thisTable = null;
		(($classID == 'adv') || ($classID == 'fig')) ? $giftPF = '+2' : $giftPF = '+3';
		$giftTable = "
		<b>Gifts</b>
		<table class='tblCharOptions'>\n";
			foreach ($_charGifts as $gift) {

				$giftTable .= "
					<tr class='trHighlightNo'>
						<td>$gift</td>
						<td>{$giftPF}</td>
						<td><img src='../images/icons/checkedCheck.png' class='badRadio'></tr>\n";
			}
		$giftTable .= "</table>\n";
		$half = round(((count($_classSkills) - count($_charGifts)) / 2),0);
		$count = 0;
		foreach ($_classSkills as $SkID => $skill) {

			if (!in_array($skill, $_charGifts)){
				$count++;
				$thisTable .= $this->drawSkillRow($SkID, $skill, '+2');
				if ($count == ($half * 1)){
					$talentTable = "
					<table class='tblCharOptions'>
						$thisTable</table>\n";
					$thisTable = '';
				}
			}
		}
		$craftTable  = "
			<table class='tblCharOptions'>
			$thisTable</table>\n";

		$message = "<p><b>Gifts</b>: Your character's Gifts have been selected based on the Character Class you've chosen.</p>
		<p><b>Talents</b>: Select any five (5) Skills from the left as your character's Talents.<br>
		These are Skills that your character has an above-average prociency. You will be able to select (3) Crafts next.<br>
		<br>If you select more or less than five Talents, the system will randomly select some for you.<br>
		Click the button below after you have selected your character's Talents.<br></p>
		<p><b>Note</b>: Skills that are often critical for gameplay are automatically selected for you, but they are optional.</p>";

		return array(
			'giftTable' => $giftTable,
			'talentTable' => $talentTable,
			'craftTable' => $craftTable,
			'message' => $message);

	}

	public function selectCraftsTable($_charGifts, $_charTalents, $_classSkills, $classID)
	{
		(($classID == 'adv') || ($classID == 'fig')) ? $giftPF = '+2' : $giftPF = '+3';
		$giftTable = "
		<b>Gifts</b>
		<table class='tblCharOptions'>\n";
		foreach ($_charGifts as $gift) {
			$giftTable .= "
				<tr class='trHighlightNo'>
					<td>$gift</td>
					<td>{$giftPF}</td>
					<td><img src='..//images/icons/checkedCheck.png' class='badRadio'></tr>\n";
		}
		$giftTable .= "</table>\n";
		$talentTable = "
		<b>Talents</b>
		<table class='tblCharOptions'>\n";
		foreach ($_charTalents as $talent) {

			$talentTable .= "
				<tr class='trHighlightNo'>
					<td>$talent</td>
					<td>+2</td>
					<td><img src='..//images/icons/checkedCheck.png' class='badRadio'></tr>\n";
		}
		$talentTable .= "</table>\n";
		(($classID == 'adv') || ($classID == 'fig')) ? $bonus = '+2' : $bonus = '+1';
		$thisTable = "
				<table class='tblCharOptions'>\n";
		foreach ($_classSkills as $SkID => $skill) {
			if ((!in_array($skill, $_charGifts)) && (!in_array($skill, $_charTalents))){
				$thisTable .= $this->drawSkillRow($SkID, $skill, $bonus);
			}
		}
		$craftTable = "
			<table class='tblCharOptions'>
				$thisTable
			</table>\n";

		$message = "<p><b>Gifts</b>: Your character's Gifts have been selected.</p>
		<p><b>Talents</b>: Your character's Talents have also been selected.</p>
		<p><b>Crafts</b>: Select any (3) of your character's hobbies or interests. Crafts are not something your character can excel at in the same way he or she can with Gifts and Talents.
		<br>If you select more or less than three Skills, the system will randomly select some for you.<br>
		Click the button below after you have chosen your character's Crafts.<br></p>";

		return array(
			'giftTable' => $giftTable,
			'talentTable' => $talentTable,
			'craftTable' => $craftTable,
			'message' => $message);
	}


	public function confirmSkillsTable($_charSkills, $_charCrafts, $classID, $specialistPF = 0){

		if (!empty($_charGifts = $_charSkills['gifts'])){ // GIFTS ARE ALREADY CHOSEN
			(($classID == 'adv') || ($classID == 'fig')) ? $giftPF = '+2' : $giftPF = '+3';

			$giftTable = "
			<b>Gifts</b>
			<table class='tblCharOptions'>\n";
				foreach ($_charGifts as $gift) {

					$giftTable .= "
						<tr class='trHighlightNo'>
							<td>$gift</td>
							<td>{$giftPF}</td>
							<td><img src='../images/icons/checkedCheck.png' class='badRadio'></tr>\n";
				}
			$giftTable .= "</table>\n";

			$_charTalents = $_charSkills['talents'];
			$talentTable = "
			<b>Talents</b>
			<table class='tblCharOptions'>\n";
				foreach ($_charTalents as $talent) {

					$talentTable .= "
						<tr class='trHighlightNo'>
							<td>$talent</td>
							<td>+2</td>
							<td><img src='../images/icons/checkedCheck.png' class='badRadio'></tr>\n";
				}
			$talentTable .= "</table>\n";

			$craftTable = "
			<b>Crafts</b>
			<table class='tblCharOptions'>\n";
			(($classID == 'adv') || ($classID == 'fig')) ? $craftPF = '+2' : $craftPF = '+1';
				foreach ($_charCrafts as $craft) {

					$craftTable .= "
						<tr class='trHighlightNo'>
							<td>$craft</td>
							<td>$craftPF</td>
							<td><img src='../images/icons/checkedCheck.png' class='badRadio'></tr>\n";
				}
			$craftTable .= "</table>\n";


			$message = "
			<table>
				<tr>
					<td>
						<p>All of your character's <b>Skills</b> have now been selected.</p>
						<p>If you chose the <b>Specialist</b> Skill, you can select which Skill(s) your character will specialize in:</p>
					</td>
				</tr>\n";
			$_skillDropDowns = (
				$specialistPF > 0
				? $this->createSpecialistOptions($_charSkills, $specialistPF)
				: array('<b>You did not select the Specialist Skill</b>')
			);
			foreach ($_skillDropDowns as $skillDropDown) {
				$message .= "
				<tr>
					<td>
						Specialist: {$skillDropDown}
					</td>
				</tr>\n";
			}
			$message .= "
			</table>\n";
			return array(
				'giftTable' 	=> $giftTable,
				'talentTable' 	=> $talentTable,
				'craftTable' 	=> $craftTable,
				'message' 		=> $message);
		}

	}

	public function createSpecialistOptions($_charSkills, $specialistPF)
	{
		$_skillDropDowns = array();
		//dd($_charSkills);
		unset($_charSkills['gifts'], $_charSkills['talents'], $_charSkills['crafts']);
		for($i = 0; $i < $specialistPF; $i++){
			$_skillDropDowns[$i] = "
			<select name='spec[]'>\n";
			foreach ($_charSkills as $skill => $_PF) {

				$selected = null;
				if (($i == 0) && ($skill == 'Combat Abilities')){
					$selected = ' selected="selected"';
				}
				if (($i == 1) && ($skill == 'WS Light Arms')){
					$selected = ' selected="selected"';
				}
				if (($skill != 'Specialist') && ($skill != 'Resilience')){
					$_skillDropDowns[$i] .="
				<option value='{$skill}'{$selected}>+{$_PF['PF']} {$skill}</option>\n";
				}
			}
			$_skillDropDowns[$i] .= "
			</select>\n";
		}
		return $_skillDropDowns;
	}

	public function displaySkillsTable($_skillsTables, $_IDs){

		$classID = "{$_IDs['classID']}";
		$CID = "{$_IDs['CID']}";
		$MID = "{$_IDs['MID']}";
		$Controls = new ControllerModel();
		$encryptIDs = $Controls->encryptSubmitIDs($classID, $CID, $MID);
		$frmAction = "{$_skillsTables['frmAction']}";
		$giftTable = "{$_skillsTables['giftTable']}";
		$talentTable = "{$_skillsTables['talentTable']}";
		$craftTable = "{$_skillsTables['craftTable']}";
		$message = "{$_skillsTables['message']}";
		return "
		<table id='tblSkills'>
			<tr>
				<td>
					<form action='' name='frmSkills' method='POST'>
					<fieldset>
						<legend>Select Your Character's Gifts, Talents, and Crafts</legend>
					<table style='width: 1oo%; margin: 0 auto'>
						<tr>
							<td style='padding: 0 7px;'>$giftTable</td>
							<td style='padding: 0 7px;'>$talentTable</td>
							<td style='padding: 0 7px;'>$craftTable</td>
							<td style='padding: 0 7px; border-left: 1px solid #D9D6E8'>$message<br>
								<input type='submit' class='btnSubmit' value='Next Step >>'>
							</td></tr></table>
							<input type='hidden' name='action' value='{$frmAction}'>
							<input type='hidden' name='staging' value='skills'>
							<input type='hidden' name='sID' value='$encryptIDs'>
							</fieldset></form>
				</td></tr></table>\n";
	}

	public function selectComAbsTable($comAbsPF, $_charComabs)
	{
		$comAbsPF = (isset($comAbsPF) ? $comAbsPF : 0);
		$ADVb = "{$_charComabs['ADV']}";
		$DODb = "{$_charComabs['DOD']}";
		$DEFb = "{$_charComabs['DEF']}";
		$DAMb = "{$_charComabs['DAM']}";
		$thRow = null;
		$ADVrow = null;
		$DODrow = null;
		$DEFrow = null;
		$DAMrow = null;
		for ($i=0; $i < $comAbsPF; $i++){

			$j = $i + 1;
			$thRow .= "<th>+1</th>";
			if (($i===2) && ($ADVb > 0)){
				$ADVrow .= "
				<td class='tdComAbOpt'>
					<img src='../images/icons/badRadio.png' class='badRadio'></td>";
			}
			else if ($i===0){
				$ADVrow .= "
				<td class='tdComAbOpt'>
					<input type='radio' id='ADV{$i}' name='incCA[{$i}]' value='ADV'
						checked='true'
						onclick='if(this.checked=true){
							document.getElementById(\"ADV{$j}\").disabled=true;
							document.getElementById(\"ADV{$j}\").checked=false;
							document.getElementById(\"DOD{$j}\").disabled=false;
							document.getElementById(\"DEF{$j}\").disabled=false;
							document.getElementById(\"DAM{$j}\").disabled=false;}'></td>\n";
			}
			else if (($i===2) && ($ADVb <= 0)){
				$ADVrow .= "
				<td class='tdComAbOpt'>
					<input type='radio' id='ADV{$i}' name='incCA[{$i}]' value='ADV'
						checked='true'
						onclick='if(this.checked=true){
							document.getElementById(\"ADV{$j}\").disabled=true;
							document.getElementById(\"ADV{$j}\").checked=false;
							document.getElementById(\"DOD{$j}\").disabled=false;
							document.getElementById(\"DEF{$j}\").disabled=false;
							document.getElementById(\"DAM{$j}\").disabled=false;}'></td>\n";
			}
			else if ($i === 1){
				$ADVrow .= "
				<td class='tdComAbOpt'>
					<input type='radio' id='ADV{$i}' name='incCA[{$i}]' value='ADV'
						disabled='true'
						onclick='if(this.checked=true){
							document.getElementById(\"ADV{$j}\").disabled=true;
							document.getElementById(\"ADV{$j}\").checked=false;
							document.getElementById(\"DOD{$j}\").disabled=false;
							document.getElementById(\"DEF{$j}\").disabled=false;
							document.getElementById(\"DAM{$j}\").disabled=false;}'></td>\n";
			}
			else {
				$ADVrow .= "
				<td class='tdComAbOpt'>
					<input type='radio' id='ADV{$i}' name='incCA[{$i}]' value='ADV'
						onclick='if(this.checked=true){
							document.getElementById(\"ADV{$j}\").disabled=true;
							document.getElementById(\"ADV{$j}\").checked=false;
							document.getElementById(\"DOD{$j}\").disabled=false;
							document.getElementById(\"DEF{$j}\").disabled=false;
							document.getElementById(\"DAM{$j}\").disabled=false;}'></td>\n";
			}
			if (($i === 2) && ($DODb > 0)){
				$DODrow .= "
				<td class='tdComAbOpt'>
					<img src='../images/icons/badRadio.png' class='badRadio'></td>";
			}
			else if ($i === 1) {
				$DODrow .= "
				<td class='tdComAbOpt'>
					<input type='radio' id='DOD{$i}' name='incCA[{$i}]' value='DOD'
						checked='true'
						onclick='if(this.checked=true){
							document.getElementById(\"ADV{$j}\").disabled=false;
							document.getElementById(\"DOD{$j}\").disabled=true;
							document.getElementById(\"DOD{$j}\").checked=false;
							document.getElementById(\"DEF{$j}\").disabled=false;
							document.getElementById(\"DAM{$j}\").disabled=false;}'></td>\n";
			}
			else {
				$DODrow .= "
				<td class='tdComAbOpt'>
					<input type='radio' id='DOD{$i}' name='incCA[{$i}]' value='DOD'
						onclick='if(this.checked=true){
							document.getElementById(\"ADV{$j}\").disabled=false;
							document.getElementById(\"DOD{$j}\").disabled=true;
							document.getElementById(\"DOD{$j}\").checked=false;
							document.getElementById(\"DEF{$j}\").disabled=false;
							document.getElementById(\"DAM{$j}\").disabled=false;}'></td>\n";
			}
			if (($i===2) && ($DEFb > 0)){
				$DEFrow .= "
				<td class='tdComAbOpt'>
					<img src='../images/icons/badRadio.png' class='badRadio'></td>";
			}
			else if (($i===2)&&($DEFb <= 0)) {
				$DEFrow .= "
				<td class='tdComAbOpt'>
					<input type='radio' id='DEF{$i}' name='incCA[{$i}]' value='DEF'
						checked='true'
						onclick='if(this.checked=true){
							document.getElementById(\"ADV{$j}\").disabled=false;
							document.getElementById(\"DOD{$j}\").disabled=false;
							document.getElementById(\"DEF{$j}\").disabled=true;
							document.getElementById(\"DEF{$j}\").checked=false;
							document.getElementById(\"DAM{$j}\").disabled=false;}'></td>\n";
			}
			else {
				$DEFrow .= "
				<td class='tdComAbOpt'>
					<input type='radio' id='DEF{$i}' name='incCA[{$i}]' value='DEF'
						onclick='if(this.checked=true){
							document.getElementById(\"ADV{$j}\").disabled=false;
							document.getElementById(\"DOD{$j}\").disabled=false;
							document.getElementById(\"DEF{$j}\").disabled=true;
							document.getElementById(\"DEF{$j}\").checked=false;
							document.getElementById(\"DAM{$j}\").disabled=false;}'></td>\n";
			}
			if (($i===2) && ($DAMb > 0)){
				$DAMrow .= "
				<td class='tdComAbOpt'>
					<img src='../images/icons/badRadio.png' class='badRadio'></td>";
			}
			else {
				$DAMrow .= "
				<td class='tdComAbOpt'>
					<input type='radio' id='DAM{$i}' name='incCA[{$i}]' value='DAM'
						onclick='if(this.checked=true){
							document.getElementById(\"ADV{$j}\").disabled=false;
							document.getElementById(\"DOD{$j}\").disabled=false;
							document.getElementById(\"DEF{$j}\").disabled=false;
							document.getElementById(\"DAM{$j}\").disabled=true;
							document.getElementById(\"DAM{$j}\").checked=false;}'></td>\n";
			}
		}
		return "
		<fieldset>
			<legend>Combat Abilities</legend>
				<table>
					<tr>
						<th></th>
						<th></th>{$thRow}</tr>
					<tr class='trHighlightGo'>
						<td class='tdComAbs'>
							<b>ADV</b>
							<input type='hidden' name='afgGF' value='" . ($ADVb * 3) . "'>
						</td>
						<td class='tdComAbs'>+{$ADVb}</td>
						{$ADVrow}
					</tr>
					<tr class='trHighlightGo'>
						<td class='tdComAbs'>
							<b>DOD</b>
							<input type='hidden' name='aFggF' value='" . ($DODb * 12) . "'>
						</td>
						<td class='tdComAbs'>+{$DODb}</td>
						{$DODrow}
					</tr>
					<tr class='trHighlightGo'>
						<td class='tdComAbs'>
							<b>DEF</b>
							<input type='hidden' name='AFGgf' value='" . ($DEFb * 30) . "'>
						</td>
						<td class='tdComAbs'>+{$DEFb}</td>
						{$DEFrow}
					</tr>
					<tr class='trHighlightGo'>
						<td class='tdComAbs'>
							<b>DAM</b>
							<input type='hidden' name='AfGgf' value='" . ($DAMb * 7) . "'>
						</td>
						<td class='tdComAbs'>+{$DAMb}</td>
						{$DAMrow}
					</tr>
				</table>
			</fieldset>\n";
	}

	public function displayWSTable($comAbsTable, $_weaponSkills, $_IDs, $_charWS = array()){

		$classID = "{$_IDs['classID']}";
		$CID = "{$_IDs['CID']}";
		$MID = "{$_IDs['MID']}";
		$Controls = new ControllerModel();
		$encryptIDs = $Controls->encryptSubmitIDs($classID, $CID, $MID);
		unset($_charWS['Combat Abilities']);
		$WStable = "
		<fieldset>
			<legend>Weapon Skills</legend>
				<table style='margin: 0 auto'>
					<tr id='trDelimHead'>
						<th>Weapon Skill</th>
						<th style='text-align: center; width: 57px;'>ATT</th>
						<th class='tdWS'>CRI</th>
						<th class='tdWS'>SPC</th>
						<th style='text-align: center; width: 57px;'>AtR</th>
					</tr>\n";
		$count = 0;
		foreach ($_weaponSkills as $weaponSkill => $_initBonuses) {

			$ATTb = 0;
			$CRIb = 0;
			$SPCb = 0;
			$trStyle = 'trHighlightNo';
			$AtRs = $_initBonuses['AtR'];
			$optAtRs = null;
			$optATT = null;
			$cryptATT = null;
			$cryptAtRs = null;
			if ($_actions = (!empty($_charWS[$weaponSkill]) ? $_charWS[$weaponSkill] : null)){

				foreach ($_actions as $action => $bonus) {

					$count++;
					if ($action == 'ATT'){

						$trStyle = (($ATTb = $bonus) ? 'trHighlightGo' : 'trHighlightNo');
						$cryptATT = $Controls->implode2KeyValues('ATTb', $ATTb, $weaponSkill);
						$optATT = "<input type='hidden' name='ATT[{$count}]' value='$cryptATT' />\n";
					}
					else if ($action == 'C_S'){

						if($CrSpOpts = $bonus){

							$cryptCRI = $Controls->implode2KeyValues('CRIb', 1, $weaponSkill);
							$cryptSPC = $Controls->implode2KeyValues('SPCb', 1, $weaponSkill);
							$CRIb = "
							<table style='margin: 0 auto 0 0'>
								<tr>
									<td class='tdWSoptLeft'>
										<label id='lC{$count}' for='C{$count}'>+1</label></td>
									<td class='tdWSoptRight'>
										<input type='radio' id='C{$count}' name='C_S[{$count}]' value='{$cryptCRI}' checked='true'
											onclick='if(this.checked==true){
												document.getElementById(\"lS{$count}\").innerText=\"+0\";
												document.getElementById(\"lC{$count}\").innerText=\"+1\";}'>
									</td>
								</tr>
							</table>\n";
							$SPCb = "
							<table style='margin: 0 auto 0 0'>
								<tr>
									<td class='tdWSoptLeft'>
										<label id='lS{$count}' for='S{$count}'>+0</label></td>
									<td class='tdWSoptRight'>
										<input style='margin-left: 5px' type='radio' id='S{$count}' name='C_S[{$count}]' value='{$cryptSPC}'
											onclick='if(this.checked==true){
												document.getElementById(\"lC{$count}\").innerText=\"+0\";
												document.getElementById(\"lS{$count}\").innerText=\"+1\";}'>
									</td>
								</tr>
							</table>\n";
						}
						else {

							$CRIb = '+0';
							$SPCb = '+0';
						}

					}
					else if ($action == 'AtR'){

						$AtRs += $bonus;
						$cryptAtRs = $Controls->implode2KeyValues('AtRs', $AtRs, $weaponSkill);
						$optAtRs = "<input type='hidden' name='AtR[{$count}]' value='$cryptAtRs' />\n";
					}
				}
			}
			$WStable .= "
			<tr class='{$trStyle} trDelim'>
				<td class='paddedSides'>
					{$weaponSkill}
				</td>
				<td class='paddedSides' style='width: 57px; text-align: center'>
					+{$ATTb}
					{$optATT}
				</td>
				<td class='tdWS' style='width: 57px'>
					{$CRIb}
				</td>
				<td class='tdWS' style='width: 57px'>
					{$SPCb}
				</td>
				<td class='paddedSides' style='width: 57px; text-align: center'>
					{$AtRs}
					{$optAtRs}
				</td>
			</tr>";
		}
		$WStable .= "
		</table>
		</fieldset>\n";
		return "
		<form name='frmComWS' action='' method='POST'>
		<table id='tblComWS'>
			<tr>
				<td>$comAbsTable</td>
				<td>$WStable</td>
				<td>
					<input type='hidden' name='staging' value='skills'>
					<input type='hidden' name='action' value='RoMRa2SNOpaEW40'>
					<input type='hidden' name='sID' value='$encryptIDs'>
					<input type='submit' name='btnSubmit' value='Next Step >>' style='float:right'>
				</td>
			</tr>
		</table>
		</form>\n";
	}
}
