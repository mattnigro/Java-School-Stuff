<?php

Namespace HolyLandsRPG\Ajax;

Class CommDB{

	public $db = null;

	public function __construct($db){

		$this->db = $db;
	}
	public function checkMemberEmail($email){

		$sql = "
			SELECT `MID`
			FROM members
			WHERE `email`='{$email}'";
		if ($result = mysqli_query($this->db, $sql)){
			while ($row = mysqli_fetch_assoc($result)){
				return $row['MID'];
			}
		}
		else {
			print 'ERROR FETCHING MEMBER ID '. mysqli_error($this->db);
			die();
		}
	}

}
?>
