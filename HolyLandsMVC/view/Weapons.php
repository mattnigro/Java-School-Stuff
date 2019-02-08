<?php
Class WeaponView{

	public function SelectWeaponsTable($_weapons){

		$_parsedDAM = $_weapons['parsedDAM'];
		$weaponSelectTable = "
<table style='margin: 0 auto'>
	<tr>
		<th>Weapon</th>
		<th>DAM</th>
		<th></th>
	</tr>\n";
		foreach ($_weapons as $weapon => $_weapon) {

			$DAM = "{$_weapon['DAM']}";
			$AVG = "{$_weapon['AVG']}";
			$_DAM = $_parsedDAM[$DAM];
			$qty = "{$_DAM['qty']}";
			$sides = "{$_DAM['sides']}";
			$bonus = "{$_DAM['bonus']}";
			//print "$weapon ? ? ? $DAM = $qty [D] $sides / $bonus ? ? ? <br>";
			$DAMdice ='';
			if ($qty > 0){

				do{
					$DAMdice .= "<image src='../images/icons/d{$sides}sm_sap.png' alt='d{$sides}'>";
					$qty--;
				}
				while ($qty >0);
				$DAMdice .= $bonus;
			}
			$img = "{$_weapon['img']}";
			$weaponSelectTable .= "
		<tr>
			<td style='text-align: right'>$weapon</td>
			<td>$DAMdice</td>
			<td class='tdHighlight'>
				<img src='../images/weapons/HLRPG_{$img}' alt='$img' title='$weapon'></td>
		</tr>\n";
		}

		$weaponSelectTable .= "</table>\n";

		return $weaponSelectTable;
	}

	public function selectWeaponsByWS($_WSList){

		$selectOptions = "
	<fieldset>
		<legend>Filter Weapons</legend>
		<form name='selectWS' method='POST' action=''>
			<select name='WSID'>\n";
		foreach ($_WSList as $key => $value) {

			$selected = "";
			if ($key == 'CL'){

				$selected = " selected='selected'";
			}
			$selectOptions .= "<option value='$key'{$selected}>$value</option>\n";
		}
		$selectOptions .= "
		<input type='submit' value='Select Weapons by Skill'>
		</form>
		</fieldset>\n";
		return $selectOptions;
	}
}
?>
