<?php

Class CharacterItems
{
	protected $CID = null;

	public function __construct($CID = null)
	{
		$this->CID = $CID;
	}

	public function displayInitialWeapons($_character, $_weaponOptions)
	{
		$Controls = new ControllerModel();
		$encryptCID = $Controls->encryptID_GET($this->CID);
        $tableWeapons = "
            <table style='width:700px; margin: 0 auto'>
            	<tr>
            		<td>For each Weapon Skill listed below you may choose one weapon. Your character may \"upgrade\" from the classes
            			default weapon by paying the additional cost in gold.<br>
            			<b>{$_character['name']} currently has {$_character['gold']}g</b>
            		</td>
            	</tr>
            	<tr>
            		<td style='text-align: left'>
			            <form name='selectWeapons' action='../shop/?q=cw&Il={$encryptCID}' method='POST'>
			            <table id='displayWeapons'>\n";
            foreach ($_weaponOptions as $WS => $_weapons) {
            	if (($_character['classID'] == 'clr') && ($WS == 'WS Thrown')){
					continue;
            	}
            	$tableWeapons .= "
            				<tr>
            					<th style='text-align: left' id='thTableHead' colspan='3'>
            						<br>{$WS}</th>
            				</tr>\n";
            	$weaponDefault = "{$_weapons['default']}";
            	unset($_weapons['default']);
            	foreach ($_weapons as $weapon => $_weapon) {

            		$img = $_weapon['image'];
            		$DAM = "{$_weapon['DAM']}";
            		//dd($_weapons);
            		$increase = $Controls->encryptID_GET("{$_weapon['value']}");
            		$encryptOpt = $Controls->implode2KeyValues($weapon, $increase, $DAM);
            		$selected = ($weapon == $weaponDefault ? ' checked="checked"' : null);
            		if ($_weapon['value'] > $_character['gold']){
						$trHighlight = 'trHighlightNo';
						$checkOption = "<img src='../images/icons/badRadio.png' class='badRadio' />\n";
						$weaponImg = "<img src='../images/weapons/HLRPG_{$img}' />\n";
						$textColor = ' color: #808080';
            		}
            		else {
            			$checkOption = "<input type='radio' id='opt{$encryptOpt}' name='weapon[{$WS}]' value='{$encryptOpt}'{$selected} />\n";
            			$weaponImg = "
            				<label for='opt{$encryptOpt}'>
            					<img src='../images/weapons/HLRPG_{$img}' />
            				</label>\n";
            			$textColor = null;
            			$trHighlight = 'trHighlightGo';
            		}
            		$tableWeapons .= "
            				<tr class='{$trHighlight}'>
            					<td style='vertical-align: middle'>
									{$checkOption}
            					</td>
            					<td style='vertical-align: middle'>
            						+{$_weapon['value']}g
            					</td>
            					<td style='vertical-align: middle;{$textColor}'>
            						{$weaponImg}</br>
            						{$weapon} {$DAM}
            					</td>
            				</tr>\n";
            	}
			}
			return $tableWeapons .= "
							<tr>
								<td colspan='3' style='text-align: center'>
									<input type='submit' name='btnSubmit' value='Select Weapons >>' style='width: 470px' />
									<input type='hidden' name='action' value='selectWeapons' />
								</td>
							</tr>
						</table>
						</form>
					</td>
				</tr>
			</table>\n";
	}

	public function displayInitialArmor($_character, $_classArmor)
	{
		$Controls = new ControllerModel();
		$CID = $this->CID;
		$encryptCID = $Controls->encryptID_GET($this->CID);
		$armorTable = "
<form method='POST' action='../shop/?q=sa&Il={$encryptCID}' name='frmArmor'>
	<table style='width:700px; margin: 0 auto'>
		<tr>
			<td>For each Weapon Skill listed below you may choose one weapon. Your character may \"upgrade\" from the classes
            	default weapon by paying the additional cost in gold.<br>
            	<b>{$_character['name']} currently has {$_character['gold']}g</b>
            </td>
        </tr>
    </table>
    <table style='width:auto; margin: 0 auto'>
        <tr>
			<td>\n";
		ksort($_classArmor);
		foreach ($_classArmor as $AP => $_armor) {
			$colSpan = count($_armor);
			$apLegend = 'AP: ' . strtoupper($AP);
			$armorTable .= "
			<fieldset>
				<legend>{$apLegend}</legend>
			<table style='margin: 0 auto'>
				<tr>\n";
			$count = 0;
			foreach ($_armor as $armor => $_armorData) {
				$img = "{$_armorData['image']}";
				$aDEF = "{$_armorData['aDEF']}";
				$value = "{$_armorData['value']}";
				$count++;
				if ($count == 1){
					$selected = ' checked="checked"';
					$baseValue = $value;
					$increase = 0;
				}
				else {
					$selected = null;
					$increase = round(($value - $baseValue) * 0.77);
				}
				$encryptArmorImg = $Controls->implodeKeyValue($armor, $img);
				$encryptaDEFValueIncrease = $Controls->implode2KeyValues($aDEF, $value, $increase);
				$encryptArmor = $Controls->implodeKeyValue($encryptArmorImg, $encryptaDEFValueIncrease);
				$armorTable .= "
				<td class='trHighlightGo' style='vertical-align: bottom; text-align: center'>
					<img src='../images/armor/HLRPG_{$img}' />
					<table>
						<tr>
							<td>
								<input type='radio' name='armor[{$AP}]' value='{$encryptArmor}'{$selected} />
							</td>
							<td>
								{$armor}<br>
								aDEF: +{$aDEF} [+{$increase}g]
							</td>
						</tr>
					</table>
				</td>\n";
			}
			$armorTable .= "
			</tr>
		</table>
		</fieldset>\n";
		}
		return $armorTable .= "
		</td>
	</tr>
	<tr>
		<td style='text-align: center'>
			<input style='width: 470px' type='submit' name='btnSubmit' value='Add Selected Armor >>' />
			<input type='hidden' name='action' value='selectArmor' />
		</td>
	</tr>
</table>
</form>\n";
	}

}
?>
