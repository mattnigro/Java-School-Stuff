<?php

Class CharacterController{

	public $CID = null;

	public $statCat = null;

	public $wsAbbrevs = array();

	public function __construct($CID, $stature)
	{

		$this->CID = $CID;
		$this->statCat = $this->findStatureCategory($stature);
		$this->wsAbbrevs = $this->fetchWSAbbrevs($stature);
	}

	public function findWeaponSkillPFs($_WS)
	{
		foreach ($_WS as $weaponSkill => $_bonus) {

			$ATTb = $_bonus['ATTb'];
			$CRIb = $_bonus['CRIb'];
			$SPCb = $_bonus['SPCb'];
			$PF = ($ATTb + $CRIb + $SPCb);
			$PF > 0 ? $_WS_PFs[$weaponSkill] = $PF : null;
		}
		return $_WS_PFs;
	}

	private function findStatureCategory($stature)
	{
		return ($stature == 'dwar' ? 'comm' : $stature);
	}

	private function fetchWSAbbrevs()
	{
		$_statureWeapons['giant'] = array(
			'WS Light Arms' 	=> 'GL',
			'WS Paired Weapons'	=> 'GL',
			'WS Heavy Arms' 	=> 'GH',
			'WS Thrown' 		=> 'GT',
			'WS Missiles' 		=> 'GM'
		);
		$_statureWeapons['wee'] = array(
			'WS Light Arms' 	=> 'WL',
			'WS Paired Weapons'	=> 'WL',
			'WS Heavy Arms' 	=> 'WH',
			'WS Thrown' 		=> 'WT',
			'WS Missiles' 		=> 'WM'
		);
		$_statureWeapons['comm'] = array(
			'WS Light Arms' 	=> 'CL',
			'WS Paired Weapons'	=> 'CL',
			'WS Heavy Arms' 	=> 'CH',
			'WS Thrown' 		=> 'CT',
			'WS Missiles' 		=> 'CM'
		);
		return $_statureWSs = $_statureWeapons[$this->statCat];
	}

	private function findWeaponScoreValues($_scores)
	{
		$statCat = $this->statCat;
		$_statIncrease['giant'] 	= 21;
		$_statIncrease['wee'] 		= 7;
		$_statIncrease['comm'] 		= 12;
/**
*Array
(
    [giant Shortsword] => 1105
    [giant Mace] => 1105
    [giant Archers Sword] => 945
    [giant Morningstar] => 997.5
    [giant Scimitar] => 1050
    [giant Battle Axe] => 1102.5
    [giant Flail] => 1155
    [min] => 945
)
*/
		$minScore = $_scores['min'];
		unset($_scores['min']);
		//sort($_scores);
		foreach ($_scores as $weapon => $score) {
			$multiplier = isset($_statIncrease[$statCat]) ? $_statIncrease[$statCat] : 12;
			$baseIncrease = ($score - $minScore) / 10;
			$modifier = ($multiplier * ($minScore / $score));
			$increase = round(($modifier * $baseIncrease) / 10);
			//print "<br> > $weapon : $score >> $multiplier ? $increase\n";
			$_weaponIncreases[$weapon] = $increase;
		}
		return $_weaponIncreases;
	}


	public function findWSWeaponOptions($_classWeapons)
	{
		$_statureWSs = $this->wsAbbrevs;
		foreach ($_statureWSs as $WeaponSkill => $WS) {

			if (isset($_classWeapons[$WS])){
				$_weaponsArray = $_classWeapons[$WS];
				$_scores = array();
				foreach ($_weaponsArray as $weapon => $_properties) {

					$value 	= $_properties['value'];
					$AVG 	= $_properties['AVG'];
					$score 	= ($value * $AVG);
					$_weaponProperties[$WS][$weapon]['image'] = $_properties['image'];
					$_weaponProperties[$WS][$weapon]['DAM'] = $_properties['DAM'];
					$_scores[$weapon] = $score;

				}
			}
			$minScore = min($_scores);
			$_scores['min'] = $minScore;
			$_goldIncrease[$WS] = $this->findWeaponScoreValues($_scores);
			$baseWeapon = array_search($minScore,$_scores); // Default weapon is the lowest "valued" one
			$_weaponProperties[$WS]['default'] = $baseWeapon;
		}
		foreach ($_goldIncrease as $WS => $_weapons) {

			foreach ($_weapons as $weapon => $increase) {

				$_weaponProperties[$WS][$weapon]['value'] = $increase;
			}
		}
		return $_weaponProperties;
	}

	public function findCharWeaponOptions($_wsPFs, $_classWeapons)
	{
		$_weaponOptions = null;
		$_WSWeaponOpts = $this->findWSWeaponOptions($_classWeapons);
		foreach ($_wsPFs as $weaponSkill => $_PFs) {

			if ($wsAbbrev = (isset($this->wsAbbrevs[$weaponSkill]) ? $this->wsAbbrevs[$weaponSkill] : null)){
				if (isset($_WSWeaponOpts[$wsAbbrev])) {
					$_weaponOptions[$weaponSkill] = $_WSWeaponOpts[$wsAbbrev];
				}
			}
		}
		return $_weaponOptions;
	}

	public function decryptArmorSelection ($_encryptedArmor)
	{
		$_armor = array();
		$_armor['goldCost'] = 0;
		$Controls = new ControllerModel();
		foreach ($_encryptedArmor as $AP => $decrypt) {

			$_decrypt = $Controls->explodeKeyValues([$decrypt]);
			foreach ($_decrypt as $decrypt1 => $decrypt2) {

				$_decrypt1 = $Controls->explodeKeyValues([$decrypt1]);
				$_decrypt2 = $Controls->explode2KeyValues([$decrypt2]);
				foreach ($_decrypt1 as $armor => $img) {
					$_armor[$AP]['armor'] = $armor;
					$_armor[$AP]['image'] = $img;
				}
				$_armor[$AP]['aDEF'] = $_decrypt2[0];
				$_armor[$AP]['value'] = $_decrypt2[1];
				$increase = $_decrypt2[2];
				$_armor['goldCost'] += $increase;
			}
		}
		return $_armor;
	}

}
?>
