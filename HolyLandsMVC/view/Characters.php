<?php

Class ViewCharacters{

	public function drawCharacterList($_characters, $db){

		if (empty($_characters)){
			return "
			<table style='width: 370px; margin: 0 auto'>
				<tr>
					<td style='text-align: center'>
						<a href='../olg/?q=sc'>Create a Character</a>
					</td>
				</tr>
			</table>\n";
		}

		$Controls = new ControllerModel();
		$characterTable = "
			<table class='tblCharacters'>
				<tr>
					<th></th>
					<th></th>
					<th></th>
					<th style='text-align: left'>Character</th>
					<th style='text-align: left'>LVL</th>
					<th></th>
				</tr>";
		foreach ($_characters as $CID => $_character) {

			$lvlImages 	= null;
			$iconDelete = null;
			$cryptCID 	= $Controls->encryptID_GET($CID);
			$FetchCharacter = new FetchCharacter($db, $CID);
			$iconWeapons = (!$FetchCharacter->fetchCharacterWeapons() ?
				"<a href='/shop/?q=cs&Il={$cryptCID}'>
					<img src='../images/icons/btnWeapons.png' />
				</a>" : null);
			$valid 		= ($_character['created'] > 40 ? true : false);
			$iconColor 	= ($valid === true ? 'sap' : 'gry');
			$rowColor 	= ($valid === false ? ' style="background-color: #FFC0C0"' : null);
			if (($_character['view'] == 'admin') || ($valid == false)){
				$iconDelete = "
				<a href='/admin/?d=dc&CID={$cryptCID}'>
					<img src='../images/icons/delete.png' />
				</a>";
				$iconWeapons = null;
			}
			$iconEdit = ($_character['view'] == 'admin' ? "
				<a href='/admin/char_edit.php?e=ec&CID={$cryptCID}'>
					<img src='../images/icons/edit.png' />
				</a>" : null);
			;

			if (($_character['view'] == 'admin') || ($valid === true)){

				$printIcon = "
				<a href='/view/?v=vc&CID={$cryptCID}' target='_blank'>
					<img src='../images/icons/story_{$iconColor}.png' />
				</a>";
			}
			else {
				 "<img src='../images/icons/story_{$iconColor}.png' />";

			}

			$tdWidth = 17;
			for($i = 0; $i < intval($_character['LVL']); $i++){

				$tdWidth += 17;
				$lvlImages .= "<img src='../images/icons/d4sm_sap.png' />\n";
			}
			if (isset($_character['lvlUp'])){
				$lvlImages .= "<img src='../images/icons/d4sm_crim.png' />\n";
				$tdWidth += 17;
			}
			$characterTable .= "
				<tr{$rowColor}>
					<td class='tdCharIcons'>
						{$printIcon}
					</td>
					<td>{$iconWeapons}</td>
					<td style='width: auto'>{$iconEdit}</td>
					<td class='tdCharacters'>{$_character['name']}, {$_character['class']}</td>
					<td class='tdCharIcons' style='width: {$tdWidth}px'>{$lvlImages}</td>
					<td>{$iconDelete}</td>
				<tr>\n";
		}
		if ((count($_characters) < 3) && ($_character['view'] != 'admin')){
			$characterTable .= "
			</table>
			<table style='margin-top: 30px; width: 470px; border: 1px solid #517BA5'>
				<tr>
					<td style='padding: 12px; text-align: center'>
						<a href='../olg/?q=sc'>Create a Character</a>
					</td>
				</tr>\n";
		}
		return $characterTable .= '
		</table>';
	}
}

