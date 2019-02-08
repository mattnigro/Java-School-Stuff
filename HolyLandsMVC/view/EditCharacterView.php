<?php

Class EditCharacterView{

	protected $CID = null;

	public function __construct($CID){
		$this->CID = $CID;
	}
	public function drawCharVariablesForm($_charData)
	{
		$_character = $_charData['character'];
		$_charVars = $_charData['charVars'];
		$ControllerModel = new ControllerModel();
		$modLifeFaith = (isset($levelUp) ? 7 : 0);
		$lifeSelect = $ControllerModel->createNumberSelects('mLife', $_charVars['mLife'] + $modLifeFaith, $_charVars['mLife'], $_charVars['mLife'] - 7);
		$faithSelect = $ControllerModel->createNumberSelects('mFaith', $_charVars['mFaith'] + $modLifeFaith, $_charVars['Faith'], $_charVars['mFaith'] - 7);
		$pad = '7px';
		$Controls = new ControllerModel();
		$encryptCID = $Controls->encryptID_GET($this->CID);
		$descript = "LVL {$_character['LVL']} {$_character['stature']} {$_character['class']}";
		$_staticStats = array( // CHECK AGAINST THESE TO SEE WHAT'S BEEN UPDATED
			'charName' 	=> $_character['name'],
			'LVL' 		=> $_character['LVL'],
			'mLife' 	=> $_charVars['mLife'],
			'mFaith' 	=> $_charVars['mFaith'],
			'gold' 		=> $_charVars['gold'],
			'silver' 	=> $_charVars['silver'],
			'EXP' 		=> $_charVars['EXP']
		);
		$staticStats = base64_encode(json_encode($_staticStats));
		return "
		<form name='frmModCharacter' action='../admin/char_edit.php?e=dw&CID={$encryptCID}' method='POST'>
			<table>
				<tr>
					<td>
						Character Name:
						<input type='text'
							onfocus='this.value.select'
							onblur='if(this.value==\"\"){this.value=\"{$_character['name']}\"};'
							required='required' id='txtCharName'
							name='charName' value='{$_character['name']}'
							placeholder='Enter Character Name Here'
							style='width: 200px' maxlength='40'>
						<span style='font-size: smaller'>
							({$descript})
						</span>
					</td>
				</tr>
				<tr>
					<td style='text-align: center; padding: {$pad} 0'>
						<table style='width: 100%; margin: {$pad} 0';>
							<tr>
								<td class='tdCharVar'>
									Life: {$lifeSelect}
								</td>
								<td class='tdCharVar'>
									Faith: {$faithSelect}
								</td>
								<td class='tdCharVar'>
									Gold: <input type='text' name='gold' value={$_charVars['gold']} style='width: 33px' />/{$_charVars['gold']}g
								</td>
								<td class='tdCharVar'>
									Silver: <input type='text' name='silver' value={$_charVars['silver']} style='width: 23px' />/{$_charVars['silver']}s
								</td>
								<td class='tdCharVar'>
									EXP: <input type='text' name='EXP' value={$_charVars['EXP']} style='width: 33px' />/{$_charVars['mEXP']}
								</td>
							</tr>
						</table>
						<input type='submit' value='Save Stats >>' style='width: 470px' />
						<input type='hidden' name='action' value='editChar' />
						<input type='hidden' name='stats' value='{$staticStats}' />
						<input type='hidden' name='CID' value='{$this->CID}' />
					<td>
				<tr>
			</table>
		</form>\n";
	}

	public function drawAddWeaponsForm($_allWeapons)
	{
		$_thrown = array('CT', 'GT', 'WT');
		$thrownOpts = "
			<select name='weapon[thrown]'>
				<option value=''>Select Thrown Weapon</option>\n";
		$_light = array('CL', 'GL', 'WL');
		$lightOpts = "
			<select name='weapon[light]'>
				<option value=''>Select Light Weapon</option>\n";
		$_heavy = array('CH', 'GH', 'WH');
		$heavyOpts = "
			<select name='weapon[heavy]'>
				<option value=''>Select Heavy Weapon</option>\n";
		$_missiles = array('CM', 'GM', 'WM');
		$missileOpts = "
			<select name='weapon[missile]'>
				<option value=''>Select Missile Weapon</option>\n";
		foreach ($_allWeapons as $weapon => $_weapon) {

			$DAM = $_weapon['DAM'];
			$WS = $_weapon['WS'];
			$Controls = new ControllerModel();

			$weaponDAM = $Controls->implodeKeyValue($weapon, $DAM);
			$option = "<option value='{$weaponDAM}'>{$weapon} {$DAM}</option>\n";
			switch ($WS){
				case in_array($WS, $_thrown):
					$thrownOpts 	.= $option;
					break;
				case in_array($WS, $_light):
					$lightOpts 		.= $option;
					break;
				case in_array($WS, $_heavy):
					$heavyOpts 		.= $option;
					break;
				case in_array($WS, $_missiles):
					$missileOpts 	.= $option;
					break;
			}
		}
		$thrownOpts 	.= '</select>';
		$lightOpts 		.= '</select>';
		$heavyOpts 		.= '</select>';
		$missileOpts 	.= '</select>';

		$Controls = new ControllerModel();
		$encryptCID = $Controls->encryptID_GET($this->CID);
		return "
		<form name='frmAddWeap' action='../admin/char_edit.php?e=dw&CID={$encryptCID}' method='POST'>
				<table style='width: 700px'>
					<tr>
						<th colspan='4' style='text-align: left'>
							Add Weapons
						</th>
					<tr>
						<td>
							$lightOpts
						</td>
						<td>
							$heavyOpts
						</td>
						<td>
							$missileOpts
						</td>
						<td>
							$thrownOpts
						</td>
					</tr>
					<tr>
						<td style='text-align: center' colspan='4'>
							<input type='submit' value='Add Selected Weapons >>' style='width: 470px' />
							<input type='hidden' name='action' value='addWeap' />
							<input type='hidden' name='CID' value='{$this->CID}' />
				</table>
			</form>";
	}

	public function drawDeleteWeaponsForm($_characterWeapons)
	{
		$Controls = new ControllerModel();
		$encryptCID = $Controls->encryptID_GET($this->CID);
		$deleteWeaponsTable = "
	<form name='frmDeleteWeap' action='../admin/char_edit.php?e=dw&CID={$encryptCID}' method='POST'>
		<table>
			<tr>
				<td></td>
				<th>Delete Weapons</th>
				<td></td>
			</tr>\n";
		foreach ($_characterWeapons as $weapID => $_weapon) {

			$deleteWeaponsTable .= "
			<tr class='trHighlightGo' style='height: 23px'>
				<td style='text-align: right'>
					<input type='checkbox' id='delWeap{$weapID}' name='weapID[{$weapID}]' value='delete' />
				</td>
				<td>
					<label for='delWeap{$weapID}'>{$_weapon['weapon']}</label>
				</td>
				<td style='text-align: right'>
					{$_weapon['DAM']}</label>
				</td>
			</tr>\n";
		}
			return $deleteWeaponsTable .= "
			<tr>
				<td colspan='3' style='text-align: center'>
					<input type='submit' class='btnSubmit' name='btnSubmit' value='Delete Selected Weapons >>' style='width: 270px' />
					<input type='hidden' name='action' value='delWeap' />
					<input type='hidden' name='CID' value='{$this->CID}' />
				</td>
			</tr>
		</table>
	</form>\n";
	}

	public function drawImproveWeaponsForm($_characterWeapons)
	{
		$Controls = new ControllerModel();
		$encryptCID = $Controls->encryptID_GET($this->CID);
		$improveWeaponsTable = "
	<form name='frmImproveWeap' action='../admin/char_edit.php?e=iw&CID={$encryptCID}' method='POST'>
		<table>
			<tr>
				<th colspan='2'>Modify Weapons</th>

			</tr>\n";
		foreach ($_characterWeapons as $weapID => $_weapon) {

			$selectDAM = 0;
			$_DAM = explode('+',$_weapon['DAM']);
			if (!empty($_DAM[1])){
				$selectDAM = '+' . $_DAM[1];
			}
			else {
				$_DAM = explode('-',$_weapon['DAM']);
				if (!empty($_DAM[1])){

					$selectDAM = '-' . $_DAM[1];
				}
			}
			$selectATT = 0;
			$_ATT = explode(' ATT',$_weapon['weapon']);
			if (!empty($_ATT[1])){
				$selectATT = str_replace('(','', substr($_ATT[0], -3));
			}
			$DAMBonus = $Controls->createBonusSelects(5,"DAM[{$weapID}]", $selectDAM ,-3);
			$ATTBonus = $Controls->createBonusSelects(5,"ATT[{$weapID}]", $selectATT, -7);
			$improveWeaponsTable .= "
			<tr class='trHighlightGo'>
				<td>
					{$DAMBonus} DAM
				</td>
				<td>
					{$ATTBonus} ATT
				</td>
			</tr>\n";
		}
		return $improveWeaponsTable .= "
			<tr>
				<td colspan='2' style='text-align: center'>
					<input type='submit' class='btnSubmit' name='btnSubmit' value='Modify Selected Weapons >>' style='width: 270px' />
					<input type='hidden' name='action' value='impWeap' />
					<input type='hidden' name='CID' value='{$this->CID}' />
				</td>
			</tr>
		</table>
	</form>\n";
	}

}
?>
