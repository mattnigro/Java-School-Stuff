<?php
Class Campaign{


	public function __construct(){

	}
	public function parseCampaignElements($_storyElements){

		foreach ($_storyElements as $CampID => $_campaign) {

			if (is_array($_campaign)){

				$RAC = "{$_campaign['Rac']}";
				$RacID = "{$_campaign['RacID']}";
				$cTitle = "{$_campaign['title']}";
				$created = "{$_campaign['created']}";
				$_campaigns[$CampID] = array(
					'Rac' => $RAC,
					'RacID' => $RacID,
					'title' => $cTitle,
					'created' => $created);
				if (is_array($_adventure = $_campaign['adventures'])){

					foreach ($_adventure as $AdvID => $_advElements) {

						$aTitle = "{$_advElements['title']}";
						$aCreated = "{$_advElements['created']}";
						$modified = "{$_advElements['modified']}";
						$_adventures[$AdvID] = array(
							'title' => $aTitle,
							'created' => $aCreated,
							'modified' => $modified);
						if (is_array($_characters = $_advElements['characters'])){

							foreach ($_characters as $CID => $_charElements) {

								$MID = "{$_charElements['MID']}";
								$charName = "{$_charElements['charName']}";
								$charLVL = "{$_charElements['charLVL']}";
								$stature = "{$_charElements['stature']}";
								$charClass = "{$_charElements['charClass']}";
								$gender = "{$_charElements['gender']}";
								$_vitals = $_charElements['vitals'];
								$_party[$CID] = array(
									'MID' => $MID,
									'charName' => $charName,
									'charClass' => $charClass,
									'stature' => $stature,
									'gender' => $gender,
									'charLVL' => $charLVL,
									'vitals' => $_vitals);
							}
						}
					}
				}
				return array(
					'campaigns' => $_campaigns,
					'adventures' => $_adventures,
					'party' => $_party);
			}
		}
/*
array(1) {
  ["campaign"]=>array(6) {
    ["CampID"]=>string(1) "1"
    ["Rac"]=>string(9) "Racmaster"
    ["RacID"]=>string(5) "12347"
    ["title"]=>string(20) "Genesis of Champions"
    ["created"]=>string(10) "1505440459"
    ["adventures"]=>array(1) {
      [1]=>
      array(5) {
        ["title"]=>string(22) "The King and the Elves"
        ["created"]=>string(10) "1505440459"
        ["modified"]=>string(10) "1505440459"
        ["CampID"]=>string(1) "1"
        ["characters"]=>array(5) {
          [43]=>
          array(7) {
            ["MID"]=>string(5) "12351"
            ["charName"]=>string(15) "Amerigo Watson "
            ["charLVL"]=>string(1) "1"
            ["stature"]=>string(4) "Dwar"
            ["charClass"]=>string(10) "Adventurer"
            ["gender"]=>string(1) "M"
            ["vitals"]=>array(6) {
              ["Life"]=>
              string(2) "19"
              ["mLife"]=>
              string(2) "27"
              ["Faith"]=>
              string(1) "3"
              ["mFaith"]=>
              string(1) "3"
              ["EXP"]=>
              string(3) "850"
              ["mEXP"]=>
              string(4) "1230"
            }
          }
          [41]=>
          array(7) {
            ["MID"]=>
            string(5) "12349"
            ["charName"]=>
            string(15) "Demelza MacRoth"
            ["charLVL"]=>
            string(1) "1"
            ["stature"]=>
            string(4) "Dwar"
            ["charClass"]=>
            string(12) "Devil Hunter"
            ["gender"]=>
            string(1) "M"
            ["vitals"]=>
            array(6) {
              ["Life"]=>
              string(1) "2"
              ["mLife"]=>
              string(2) "22"
              ["Faith"]=>
              string(1) "6"
              ["mFaith"]=>
              string(1) "6"
              ["EXP"]=>
              string(3) "750"
              ["mEXP"]=>
              string(4) "1230"
            }
          }
          [44]=>
          array(7) {
            ["MID"]=>
            string(5) "12352"
            ["charName"]=>
            string(7) "Nereida"
            ["charLVL"]=>
            string(1) "1"
            ["stature"]=>
            string(3) "Wee"
            ["charClass"]=>
            string(5) "Saint"
            ["gender"]=>
            string(1) "F"
            ["vitals"]=>
            array(6) {
              ["Life"]=>
              string(2) "19"
              ["mLife"]=>
              string(2) "19"
              ["Faith"]=>
              string(2) "19"
              ["mFaith"]=>
              string(2) "21"
              ["EXP"]=>
              string(3) "570"
              ["mEXP"]=>
              string(4) "1230"
            }
          }
          [42]=>
          array(7) {
            ["MID"]=>
            string(5) "12350"
            ["charName"]=>
            string(23) "Thorston Shadow-Stomper"
            ["charLVL"]=>
            string(1) "1"
            ["stature"]=>
            string(5) "Giant"
            ["charClass"]=>
            string(7) "Warrior"
            ["gender"]=>
            string(1) "M"
            ["vitals"]=>
            array(6) {
              ["Life"]=>
              string(1) "8"
              ["mLife"]=>
              string(2) "20"
              ["Faith"]=>
              string(1) "4"
              ["mFaith"]=>
              string(1) "4"
              ["EXP"]=>
              string(3) "750"
              ["mEXP"]=>
              string(4) "1230"
            }
          }
          [40]=>
          array(7) {
            ["MID"]=>
            string(5) "12348"
            ["charName"]=>
            string(16) "Vilfred Skovgard"
            ["charLVL"]=>
            string(1) "2"
            ["stature"]=>
            string(6) "Common"
            ["charClass"]=>
            string(6) "Cleric"
            ["gender"]=>
            string(1) "M"
            ["vitals"]=>
            array(6) {
              ["Life"]=>
              string(2) "17"
              ["mLife"]=>
              string(2) "21"
              ["Faith"]=>
              string(2) "15"
              ["mFaith"]=>
              string(2) "17"
              ["EXP"]=>
              string(4) "1500"
              ["mEXP"]=>
              string(4) "2555"
            }
          }
        }
      }
    }
  }
}
*/

	}
}
?>
