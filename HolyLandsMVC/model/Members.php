<?php

Class Members
{

	public $db;

	public function __construct($db){

		$this->db = $db;
	}

	public function validateEmail($email){

		if (empty($email)){
			return false;
		}
		$db = $this->db;
		$_removeChars = array(' ', '"', '?', '/', '=');
	    $email = strtolower(str_replace($_removeChars,'',$email));
	    // = = = = = = = = = = = = = CHECK EMAIL IS VALID = = = = = = = = = = = = = //
	    $_email 	= explode('@',$email);
	    $username 	= "{$_email[0]}";
	    $domain 	= "{$_email[1]}";
	    $_domain 	= explode('.',$domain);
	    $host 		= "{$_domain[0]}";
	    $ext 		= "{$_domain[1]}";
	    if ((strlen($username) < 2) || (strlen($domain) < 5) || (strlen($host) < 2) || (strlen($ext) < 2)){

	        return false;
	    }
	    // = = = = = = = = = = = = = CHECK EMAIL IS NEW = = = = = = = = = = = = = //
	    $sql = "SELECT email FROM members WHERE `email`='{$email}'";
	    if ($result = mysqli_query($db,$sql)){
	        while($row = mysqli_fetch_assoc($result)){
	        	if ($row['email']){
					return false; // EMAIL ALREADY IN DATABASE
	        	}
	        }
	    }
	    return $email;
	}

	public function insertNewMember($_insert)
	{
		$db = $this->db;
		$sql = "
		INSERT INTO `members`
		(`MID`, `firstName`, `lastName`, `email`, `password`, `access`, `joined`, `MODIFIED`)
		VALUES ('{$_insert['MID']}', '{$_insert['firstName']}', '{$_insert['lastName']}', '{$_insert['email']}', '{$_insert['password']}', '1', '" . MODIFIED . "', '" . MODIFIED . "');";
		if (!$result = mysqli_query($db, $sql)){

			print "ERROR ENTERING NEW MEMBER " . mysqli_error($db);
			die();
		}
	}

	public function getMemberTitle($MID)
	{
		$db = $this->db;
		$sql = "
			SELECT firstName, lastName, email, joined
			FROM `members`
			WHERE `MID`='{$MID}'
			LIMIT 1";
		if ($result = mysqli_query($db, $sql)){
			while ($row = mysqli_fetch_assoc($result)){
				foreach ($row as $key => $value){

					return array(
						'firstName' => $row['firstName'],
						'lastName' => $row['lastName'],
						'email' => $row['email'],
						'joined' => $row['joined']
					);
				}
			}
		}
	}


	public function getBannerAccessForm($MID = null)
	{
		if (isset($MID)){

			$_member = $this->getMemberTitle($MID);
			$playerName = "{$_member['firstName']}";
			return '
				<legend>Account</legend>
				<div style="width: 300px;">
					<h3 style="margin-left: 7px">Welcome, <a href="../account/">' . $playerName . '</a></h3>
					<a style="margin-left: 12px" href="../login/?q=logout">Log Out</a>
				</div>';
		}

		return '
			<legend>Login</legend>
			<form name="loginForm" action="../login/" method="POST">
				<table id="tblLoginForm">
					<tr>
						<td>
							<input type="text" name="email" class="txtLogin" id="txtLoginEmail" placeholder="Email address" required="required" />
						</td>
						<td>
							<input type="password" name="password" class="txtLogin" placeholder="Password" required="required" />
						</td>
						<td>
							<input type="submit" name="btnSubmit" id="btnLogin" value="Log In!">
							<input type="hidden" name="action" value="login">
						</td>
					</tr>
					<tr>
						<td style="text-align: center">
						</td>
						<td style="text-align: center">
							<!--div id="aForgotPass">Forgot Password</div-->
						</td>
						<td>
						</td>
					</tr>
				</table>
			</form>';
	}

	public function getAccessPermissions($cookieDough)
	{
		$db = $this->db;
		$_cookieDough = $this->getCookieData($cookieDough);
		$MID = ($_cookieDough['MID']);
		$access = $_cookieDough['access'];
		$logged = $_cookieDough['logged'];
		if ($access > 1){
			return $access;
		}
		if ($logged == 'OUT'){
			return false;
		}
		$hasAccess = false;
		$sql = "
			SELECT `access`
			FROM members
			WHERE `MID`='{$MID}'
			LIMIT 1";
		if ($result = mysqli_query($db, $sql)){
			while ($row = mysqli_fetch_assoc($result)){
				foreach ($row as $key => $value){

					$hasAccess = intval($row['access']);
					//dd($hasAccess);
				}
			}
			if ($hasAccess < 1){
				return false;
			}
		}
		else {
			print "ACCESS DENIED " . mysqli_error($db);
			die();
		}
		return $hasAccess;
	}

	public function login($email, $password)
	{
		$db = $this->db;
		$DataModel = new DataModel($db);
		$email = strtolower($DataModel->sanatizeDBText($email));
		$hasAccess = false;
		$sql = "
			SELECT `MID`, `access`
			FROM `members`
			WHERE `email` = '{$email}'
			AND `password` = '{$password}'
			LIMIT 1";
		if ($result = mysqli_query($db, $sql)){
			while ($row = mysqli_fetch_assoc($result)){
				$MID 			= $row['MID'];
				$access 		= $row['access'];
				$cookieDough 	= $this->bakeCookie($MID, $access, 'IN');
				$this->setCookie($cookieDough);
				return true;
			}
		}
		else {
			print "CANNOT LOGIN " . mysqli_error($db);
			die();
		}
		return $hasAccess;
	}


	public function bakeCookie($MID, $access, $loggedIn = 'OUT')
	{
		return strrev(base64_encode(($MID * 777) . '^' . $access . '^' . $loggedIn));
	}

	public function setCookie($cookieDough)
	{
		setcookie('MID',$cookieDough,time() + 77700, '/');
	}

	public function getCookieData($cookieDough)
	{
		$_cookieDough = explode('^', base64_decode(strrev($cookieDough)));
		$MID = ($_cookieDough[0] / 777);
		$access = $_cookieDough[1];
		$loggedIn = $_cookieDough[2];
		return array(
			'MID' => $MID,
			'access' => $access,
			'logged' => $access
		);
	}

	public function resumeUnfinishedCharacter($MID)
	{
		$sql = "
			SELECT `CID`
			FROM characters
			WHERE MID='{$MID}'
			AND created < '40'
			LIMIT 1";
		if ($result = mysqli_query($this->db, $sql)){
			while ($row = mysqli_fetch_assoc($result)){
				return $row['CID'];
			}
		}
		else {
			print 'ERROR FETCHING LOCKSTEP DATA '. mysqli_error($this->db);
			die();
		}
		return false;
	}
}
?>
