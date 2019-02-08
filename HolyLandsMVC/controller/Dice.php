<?php

Class Dice{

	private $qty; // i.e. [2]d8+3x4?ge

	private $sides; // i.e. 2d[8]+3x4?ge

	private $bonus; // i.e. 2d8[+3]x4?ge

	private $mult; // i.e. 2d8+3[x4]?ge

	private $mod; // i.e. 2d8+3x4?[ge] Grace Effect, Half Roll
	/*
	*
	This must be parsed first
	*
	*/
	public function roll($_parsed){

		$qty = intval($_parsed['qty']);
		$sides = intval($_parsed['sides']);
		if (($sides <= 0) || ($qty <= 0)){ // NOT DICE

			return false;
		}
		$bonus = "{$_parsed['bonus']}";
		$mult = "{$_parsed['mult']}";
		$mod = "{$_parsed['mod']}";
		$sum = 0;
		$sprintBonus = '';
		$count = $qty;
		if ($bonus != 0) {

			$sprintBonus = sprintf('%+d',$bonus); // Print '+3' or '-3'
		}
		if ($mult != ''){

			$sprintBonus .= "[{$mult}]";
		}
		$_roll['dice'] = "{$qty}d{$sides}{$sprintBonus}{$mod}";
		while($count > 0){

			$roll = rand(1,$sides);
			//print "[roll{$count}] = > $roll<br>";
			if (($mod == 'ge') && ($roll === 1)){ // Reroll Grace Effect

				$count++;
				$roll = 0;
			}
			else if (($qty === 1) && ($sides === 20)){ // For D20 ACTION

				if ($roll === 1){ // Natural 1

					return array(
						'dice' => 'D20',
						'sum' => '1',
						'CRIT' => 'Nat1');

				}else if ($roll === 20){ // Natural 20

					return array(
						'dice' => 'D20',
						'sum' => '20',
						'CRIT' => 'Nat20');
				}
			}
			$sum += $roll;
			$count--;
			//print "$sum on #$count<br>";
		}

		if ($mod == 'hr'){

			$sum = round($roll / 2);
		}
		$sum += $bonus;
		if ($sum < 0){

			$sum = 0;
		}
		if (strstr($mult,'x')){

			$mult = str_replace('x','',$mult);
			if ($mult > 0){

				$sum *= $mult;
			}
		}
		$_roll['sum'] = $sum;
		return $_roll;
	}


	public function parse($dice){

		// FORMAT MUST BE = = = = [ 2d4+3x10?GE]
		$bonus = '';
		$mult = '';
		$mod = '';
		$dice = strtolower(trim($dice));

		//print " ? ? ? $dice ? ? ? <br>";
		if (strstr($dice,'?')){ // i.e. 3d4x10?GE

			$_dice = explode('?',$dice);
			$dice = "{$_dice[0]}";
			$mod = "{$_dice[1]}";
		}
		if (strstr($dice,'x')){ // i.e. 3d4x10

			$_dice = explode('x',$dice);
			$dice = "{$_dice[0]}";
			$mult = "x{$_dice[1]}"; // i.e. x10
		}
		if (strstr($dice,'+')){ // i.e. 2d6+2

			$_dice = explode('+',$dice);
			$dice = "{$_dice[0]}";
			$bonus = "+{$_dice[1]}";
		}
		else if (strstr($dice,'-')){// i.e. 2d6-2

			$_dice = explode('-',$dice);
			$dice = "{$_dice[0]}";
			$bonus = "-{$_dice[1]}";
		}
		$_die = explode('d',$dice);
		$qty = "{$_die[0]}";
		$sides = "{$_die[1]}";
		if (($qty <= 0) || ($sides <= 0)){

			return false;
		}
		//print " = > $qty D $sides ([$bonus] [$mult] [$mod]<br>";
		return $_return = array(
			'qty' => $qty,
			'sides' => $sides,
			'bonus' => $bonus,
			'mult' => $mult,
			'mod' => $mod);
	}
	/**
	* FORMAT MUST BE "2d4+3x10?GE"
	*
	* @param string $dice
	* @returns sum of dice roll
	*/
	public function parseAndRoll($dice){

		$_parsed = $this->parse($dice);
		return $this->roll($_parsed)['sum'];
	}

}
?>
