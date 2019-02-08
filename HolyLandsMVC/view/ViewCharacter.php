<?php
Class ViewCharacter {

	private $CID = null;
	private $MID = null;

	public function __construct($CID, $MID){

		$this->CID = $CID;
		$this->MID = $MID;
	}
public function drawListTable($title, $_charList){

		$CID = $this->CID;
		$listTable = "
	<fieldset>
		<legend>$title</legend>
			<table class='tblCharOptions'>\n";
		foreach ($_charList as $action => $PF) { // i.e. STR => 12
			if ($title == 'Blessings'){
				$PF = "Fc: {$PF}";
			}
			else if ($title == 'Attributes'){
				$PF = (intval($PF) >= 12 ? "<b>$PF</b>" : $PF);
			}
			else {
				$PF = "+{$PF}";
			}
			$listTable .= "
			<tr>
				<td class='tdOption'>
					<b>$action</b>:
				</td>
				<td class='tdOption'>
					$PF
				</td>
			</tr>\n";
		}
		return $listTable .= "
		</table>
	</fieldset>\n";
	}


	public function drawDataTable($_charData){
		$_statureFolk = array(
			'comm' => 'CommonFolk',
			'dwar' => 'DwarFolk',
			'wee' => 'WeeFolk',
			'giant' => 'GiantFolk');
		//dd($_charData);
		return "
	<fieldset>
			<legend>Character Data</legend>
		<table id='tblCharVars'>
			<tr>
				<td class='paddedCell'>
					<h3 style='margin: 7px auto'>{$_charData['character']['name']}<br>
					<span style='font-size: smaller'>LVL {$_charData['character']['LVL']} {$_statureFolk[$_charData['character']['stature']]} {$_charData['character']['class']}</span>
				</td>
			</tr>
			<tr>
				<td class='paddedCell'>
					<table style='width: 100%'>
						<tr>
							<td class='paddedCell'>
								<b>Land of Origin</b>:<br>
								" . $_charData['charEAV']['landOfOrigin'][0] . "
							</td>
							<td class='paddedCell'>
								<b>Spoken Languages</b>:<br>
								" . implode(', ',$_charData['charEAV']['languages']) . "
							</td>
						</tr>
					</table>
				</td>
			</tr>
			<tr>
				<td class='paddedCell'>
					<table style='width: 100%'>
						<tr>
							<td class='quarterCell paddedCell'>
								<b>Age</b>: {$_charData['character']['age']}
							</td>
							<td class='paddedCell'>
								<b>Height</b>: {$_charData['character']['height']}
							</td>
							<td class='quarterCell paddedCell'>
								<b>Weight</b>: {$_charData['character']['weight']}lbs.
							</td>
							<td class='quarterCell paddedCell'>
								<b>Gender</b>: {$_charData['character']['gender']}
							</td>
						</tr>
					</table>
				</td>
			</tr>
			<tr>
				<td class='paddedCell'>
					<b>Sins</b>: " . (!empty($_charData['charEAV']['sins']) ? implode(', ', $_charData['charEAV']['sins']) : 'none') . "
				</td>
			</tr>
			<tr>
				<td class='paddedCell'>
					<b>Phobias</b>: " . (!empty($_charData['charEAV']['phobias']) ? implode(', ', $_charData['charEAV']['phobias']) : 'none') . "
				</td>
			</tr>
			<tr>
				<td class='paddedCell'>
					<table style='width: 100%'>
						<tr>
							<td class='quarterCell paddedCell'>
								<b>Life</b>: {$_charData['charVars']['Life']} / {$_charData['charVars']['mLife']}
							</td>
							<td class='paddedCell'>
								<b>Faith</b>: {$_charData['charVars']['Faith']} / {$_charData['charVars']['mFaith']}
							</td>
							<td class='quarterCell paddedCell'>
								<b>Gold</b>: {$_charData['charVars']['gold']}g
							</td>
							<td class='quarterCell paddedCell'>
								<b>Silver</b>: {$_charData['charVars']['silver']}s
							</td>
						</tr>
					</table>
				</td>
			</tr>
			<tr>
				<td class='paddedCell'>
					<b>EXP</b>: {$_charData['charVars']['EXP']} / {$_charData['charVars']['mEXP']}
				</td>
			</tr>
		</table>
	</fieldset>\n";
	}

	public function drawCharacterHeader($CID, $db)
	{

		$FetchCharacter = new FetchCharacter($db, $CID, $this->MID);
		$_charData = $FetchCharacter->fetchCharacterData();
		$charDataTable = $this->drawDataTable($_charData);

		$_charAttribs = $_charData['charAttribs'];
		$attribTable = $this->drawListTable('Attributes', $_charAttribs);

		$_charAbils =  $_charData['charAbils'];
		$abilsTable = $this->drawListTable('Abilities', $_charAbils);

		$_charSaves =  $_charData['charSaves'];
		$savesTable = $this->drawListTable('Saving Throws', $_charSaves);
		return 	"
		<table class='tblCharSheet'>
			<tr>
				<td class='tdCharSheetDelim'>
					<table style='width: 100%'>
						<tr>
							<td>
								{$charDataTable}
							</td>
							<td>
								{$attribTable}
							</td>
							<td>
								{$abilsTable}
								{$savesTable}
							</td>
						</tr>
					</table>
				</td>
			</tr>
		</table>\n";
	}

	public function drawSkillsTable($_charSkills)
	{
		$_gifts 	= $_charSkills['gifts'];
		$_talents 	= $_charSkills['talents'];
		$_crafts 	= $_charSkills['crafts'];
		unset($_charSkills['gifts'], $_charSkills['talents'], $_charSkills['crafts']);
		$_skills = array(
			'gifts' 	=> $_gifts,
			'talents' 	=> $_talents,
			'crafts' 	=> $_crafts
		);
		$giftTable = '
		<table>
			<tr>
				<th>Gifts</th>
			</tr>';
		$talentTable = '
		<table>
			<tr>
				<th>Talents</th>
			</tr>';
		$craftTable = '
		<table>
			<tr>
				<th>Crafts</th>
			</tr>';
		foreach ($_skills as $table => $_skillList) {

			sort($_skillList);
			foreach ($_skillList as $skill) {

				$PF = (!empty($_charSkills[$skill]) ? $_charSkills[$skill]['PF'] : null);
				$tableRow = '
					<tr class="">
						<td style="padding: 3px 7px;"><b>' . $skill . '</b></td>
						<td style="padding: 3px 7px;">+' . $PF . '</td></tr>';
				$giftTable .= ($table == 'gifts' ? $tableRow : null);
				$talentTable .= ($table == 'talents' ? $tableRow : null);
				$craftTable .= ($table == 'crafts' ? $tableRow : null);
			}
		}
		return "
		<table class='tblCharSheet'>
			<tr>
				<td class='tdCharSheetDelim'>
					<fieldset>
						<legend>Skills</legend>
					<table style='width: 100%; margin: 0 auto'>
						<tr>
							<td style='padding: 0 7px;'>{$giftTable}</table></td>
							<td style='padding: 0 7px;'>{$talentTable}</table></td>
							<td style='padding: 0 7px;'>{$craftTable}</table></td>
						</tr>
					</table>
				</td>
			</tr>
		</table>\n";
	}

	public function drawComAbsTable($_comAbs)
	{
		return "
			<fieldset>
			<legend>Combat Abilities</legend>
				<table>
					<tr>
						<td class='tdComAbs'><b>ADV</b></td>
						<td class='tdComAbs' style='padding-left: 30px'>+{$_comAbs['ADV']}</td>
					</tr>
					<tr>
						<td class='tdComAbs'><b>DOD</b></td>
						<td class='tdComAbs' style='padding-left: 30px'>+{$_comAbs['DOD']}</td>
					</tr>
					<tr>
						<td class='tdComAbs'><b>DEF</b></td>
						<td class='tdComAbs' style='padding-left: 30px'>+{$_comAbs['DEF']}</td>
					</tr>
					<tr>
						<td class='tdComAbs'><b>DAM</b></td>
						<td class='tdComAbs' style='padding-left: 30px'>+{$_comAbs['DAM']}</td>
					</tr>
				</table>
			</legend>
			</fieldset>\n";
	}

	public function drawWSTable($_WS){
/*
WS Light Arms: WS => WS Light Arms
WS Light Arms: ATTb => 1
WS Light Arms: CRIb => 1
WS Light Arms: SPCb => 0
WS Light Arms: AtRs => 2
*/
		$WSTable = "
		<fieldset>
			<legend>Weapon Skills</legend>
				<table id='tblWS'>
					<tr id='trDelimHead'>
						<td></td>
						<td class='paddedSides' style='text-align: center'><b>ATT</b></td>
						<td class='paddedSides' style='text-align: center'><b>CRI</b></td>
						<td class='paddedSides' style='text-align: center'><b>SPC</b></td>
						<td class='paddedSides' style='text-align: center'><b>AtR</b></td></tr>\n";
		foreach ($_WS as $WS => $_action) {

			$ATT = "+{$_action['ATTb']}";
			$CRI = "+{$_action['CRIb']}";
			$SPC = "+{$_action['SPCb']}";
			$AtR = "{$_action['AtRs']}";
			$WSTable .= "
			<tr class='trDelim' style='height: 22px'>
				<td class='paddedSides'><b>{$WS}</b></td>
				<td class='paddedSides' style='text-align: center'>{$ATT}</td>
				<td class='paddedSides' style='text-align: center'>{$CRI}</td>
				<td class='paddedSides' style='text-align: center'>{$SPC}</td>
				<td class='paddedSides' style='text-align: center'>{$AtR}</td>
			</tr>\n";
		}
		return $WSTable .= "</table></fieldset>\n";
	}

	public function drawPowersTable($_powers){

		$powersTable = "
		<fieldset>
			<legend>Miracles and Blessings</legend>\n";
		foreach ($_powers as $type => $_power) {
			$powersTable .= "
			<table style='width: 100%'>
				<tr>
					<th>{$type}</th>
					<th>Fc</th>
				</tr>\n";
			foreach ($_power as $name => $Fc) {

				$powersTable .= "
				<tr>
					<td>{$name}</td>
					<td>{$Fc}</td>
				<tr>\n";
			}
		}
		if (empty($_powers)){
			$powersTable .= "
		<table style='width: 100%'>
			<tr>
				<td><b>- none -</b></td>
				<td> - </td>
			</tr>\n";
		}
		return $powersTable .= '
		</table>
	</fieldset>';
	}
	public function drawEquipmentTable($_equipment){

		$equipmentTable = "
		<fieldset>
			<legend>Equipment and Items</legend>\n";
		foreach ($_powers as $type => $_power) {
			$equipmentTable .= "
			<table style='width: 100%'>
				<tr>
					<th>{$type}</th>
					<th>Fc</th>
				</tr>\n";
			foreach ($_power as $name => $Fc) {

				$equipmentTable .= "
				<tr>
					<td>{$name}</td>
					<td>{$Fc}</td>
				<tr>\n";
			}
		}
		if (empty($_powers)){
			$powersTable .= "
		<table style='width: 100%'>
			<tr>
				<td><b>- none -</b></td>
				<td> - </td>
			</tr>\n";
		}
		return $powersTable .= '
		</table>
	</fieldset>';
	}

	public function drawWeaponsTable($_weapons)
	{
		$titleRow = (!empty($_weapons) ? "
			<tr>
				<th style='text-align: center'>
					Weapon
				</th>
				<td>
					DAM
				</td>
			</tr>\n" : "
			<tr>
				<th style='text-align: center'>
					- none -
				</th>
				<td>
					-
				</td>
			</tr>\n");
		$weaponsTable = "
		<fieldset>
			<legend>Weapons</legend>
				<table style='margin: 0 auto'>
					{$titleRow}";
		foreach ($_weapons as $WeapID => $_weapon) {
			$_search = array('wee ', 'giant ');
			$imgName = str_replace($_search,'',$_weapon['image']);
			$weapon = $_weapon['weapon'];
			$DAM = $_weapon['DAM'];
			$weaponsTable .= "
				<tr>
					<td style='text-align: right; padding-right: 3px'>
						{$weapon}
					</td>
					<td style='text-align: left; padding-left: 3px'>
						{$DAM}
					</td>
				</tr>";
		}
		return $weaponsTable .= "
		</table>
		</fieldset>\n";
	}
	public function drawArmorTable($_selectedArmor)
	{
		$tDEF = 4;
		$armorTable = "
			<fieldset>
				<legend>Armor and Clothes</legend>
					<table style='margin: 0 auto'>
						<tr>
							<th>
								AP:
							</th>
							<td>
							</td>
							<th>
								aDEF
							</th>
						</tr>\n";
		$_armorTDs = array('head', 'back', 'legs', 'feet', 'chest', 'arms');
		sort($_armorTDs);
		foreach ($_armorTDs as $AP) {
			$_armorData = isset($_selectedArmor[$AP]) ? $_selectedArmor[$AP] : null;
			//dd($_armorData);
			$APtitle = '<b>' . ucwords($AP) . '</b>';
			$aDEF = (!empty($_armorData) ? '+' . $_armorData['aDEF'] : '+0');
			$armor = (!empty($_armorData['armor']) ? $_armorData['armor'] : ' - none - ');
			$tDEF += $aDEF;
			$armorTable .= "
				<tr>
					<td style='text-align: center'>
						{$APtitle}
					</td>
					<td style='text-align: right'>
						{$armor}
					</td>
					<td style='text-align: center'>
						{$aDEF}
					</td>
				</tr>\n";
		}
		return $armorTable .= "
			<tr style='border-top: 1px solid black'>
				<td style='text-align: right; padding-top: 3px' colspan='2'>
					(nDEF: 4 + aDEF) = <b>tDEF</b>:
				</td>
				<td style='text-align: center'>
					<b>$tDEF</b>
				</td>
			</tr>
		</table>
	</fieldset>\n";

	}
	public function drawWSItemsTable($_tables = array())
	{

	}
}
?>
