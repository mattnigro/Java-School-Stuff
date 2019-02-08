<?php

Class ControllerModel{

	public function __construct(){

	}

	public function decryptSubmitIDs($sIDs)
	{
	$_sID = explode('^',base64_decode($sIDs)); // sID => clr-C6-M12

		$classID = "{$_sID[0]}";
		$CID = str_replace('C','',"{$_sID[1]}");
		$MID = str_replace('M','',"{$_sID[2]}");
		return array (
			'classID' 	=> $classID,
			'CID' 		=> $CID,
			'MID' 		=> $MID);
	}

	public function encryptSubmitIDs($classID, $CID, $MID){

		return base64_encode("{$classID}^C{$CID}^M{$MID}");
	}

	public function createNumberSelects($name, $max, $selected = null, $min = 0)
	{
		$min = ($min < 0 ? 0 : $min);
		$numberSelect = "
		<select name='{$name}'>\n";
			for($i = $min; $i <= $max; $i++){
				$select = ($i == $selected ? ' selected="selected"' : null);
				$numberSelect .= "<option value='{$i}'{$select}>$i</option>\n";
			}
		return $numberSelect .= "</select>\n";
	}

	public function createBonusSelects($max, $name, $selected = null, $min = 0)
	{
		$numberSelect = "
		<select name='{$name}'>\n";
			for($i = $min; $i <= $max; $i++){
				$displayNum = ($i < 0 ? $i : '+' . $i);
				$select = ($displayNum == $selected ? ' selected="selected"' : null);
				$numberSelect .= "<option value='{$displayNum}'{$select}>{$displayNum}</option>\n";
			}
		return $numberSelect .= "</select>\n";
	}

	public function encryptID_GET($ID)
	{
		return ($ID * 777) . 'O' . rand(1023,12023);
	}

	public function decryptID_GET($ID)
	{
		$_decrypt = explode('O', $ID);
		return intval($_decrypt[0] / 777);
	}

	/**
	* Creates eNcRYpTeDsTriNg
	*
	* @param string $key
	* @param string $value
	*/
	public function implodeKeyValue($key, $value)
	{
		return strrev(base64_encode("{$key}^{$value}"));
	}

	/**
	* Requires array([0] => 'EnCrYpTEdkEyVAlUe')
	*
	* @param array $_keyValues
	*/
	public function explodeKeyValues($_keyValues = array())
	{
		$_return = null;
		if (!empty($_keyValues)){

			foreach ($_keyValues as $encryptedKeyValue) {

				if (!empty($encryptedKeyValue)){
					$keyValue = base64_decode(strrev($encryptedKeyValue));
					$_keyValue = explode('^', $keyValue);
					$key = $_keyValue[0];
					$value = $_keyValue[1];
					$_return [$key] = $value;
				}
			}
		}
		return $_return;
	}


	/**
	* IMPLODES $key-$value1-$value2
	*
	* @param string $key
	* @param string $value1
	* @param string $value2
	*/
	public function implode2KeyValues($key, $value1, $value2)
	{
		return strrev(base64_encode("{$key}^{$value1}^{$value2}"));
	}

	/**
	* Requires array [0 => EnrYpTedkEYVaLuEs]
	*
	* @param string $_key2Values
	* @returns array($key, $value1, $value2)
	*/
	public function explode2KeyValues($_key2Values = array())
	{
		$_return = null;
		if (!empty($_key2Values)){

			foreach ($_key2Values as $encryptedKeyValues) {

				if (!empty($encryptedKeyValues)){
					$keyValues = base64_decode(strrev($encryptedKeyValues));

					$_keyValue = explode('^', $keyValues);
					$key = $_keyValue[0];
					$value1 = $_keyValue[1];
					$value2 = $_keyValue[2];
					$_return = array($key, $value1, $value2);
				}
			}
		}
		return $_return;
	}

}
?>
