<?php
if ("{$_GET['fkey']}" == '33'){

include $this->includes;

	$modText = new ModifyText($db); // INSTANTIATE TEXT ENCRYPTOR
	$email = $modText->CleanEmail("{$_POST['email']}"); // VALIDATE EMAIL ADDRESS
	if ($email !== 'ERROR'){

		$fKey = "{$_POST['fkey']}";
		$cryptKey = new CryptKey($fKey); // INSTANTIATE ENCRYPTION/DECRYPTION CLASS
		$dataID = new DataModel($db); //INSTANTIATE DATABASE MAPPING
		$contact = new MakeContact($db); // INSTANTIATE CONTACT MODEL

		$_CID = $dataID->SenderCID($email); // FIND NEW OR EXISTING CONTACT ID
		$CID = "{$_CID['CID']}"; // GOT NEW OR EXISTING CONTACT ID
		$senderName = $modText->ForSQL(ucwords(strtolower("{$_POST['name']}")));
		$_sender['CID'] = $CID;
		$_sender['name'] = $senderName;
		$_sender['email'] = $email;
		if (!$_CID['exists']){ // IT'S A NEW CONTACT
			$contact->setName($_sender); // ADD SENDER TO DATABASE
		}
		$MID = $dataID->NextID('messages'); // MAP THE MESSAGE ID
		$_message['MID'] = $MID; // ADD MESSAGE ID TO THE MESSAGE MAP
		$_message['CID'] = $CID; // ADD CONTACT ID TO THE MESSAGE MAP
		$subject = "{$_POST['subject']}"; // KEEP RAW SUBJECT FOR ME TO SEE
		$message = "{$_POST['message']}"; // KEEP RAW MESSAGE FOR ME TO SEE
		$_message['subject'] = $modText->ForSQL($subject); // MAKE SUBJECT SAFE
		$_message['message'] = $modText->ForSQL($message); // MAKE MESSAGE SAFE
		$_message['ukey'] = $cryptKey->Decrypt(); // DECRYPT ENCRYPTED USER IP
		$contact->addMessage($_message);

		//print " = = = = RETURN URL = $returnURL<br>";

		$sendDate = date('l, m/d/Y',time());
		$HTMLmessage = "
	<html>
		<table>
			<tr>
				<td colspan='2'>
					<p></p></td></tr>
			<tr>
				<td>
					<b>From:</b></td>
				<td>{$_sender['name']}</b> [{$_sender['email']}]</td></tr>
			<tr>
				<td>
					<b>Sent:</b> </td>
					<td>$sendDate</td></tr>
			<tr>
				<td>
					<b>Subject:</b></td>
				<td>{$_message['subject']}</td></tr>
			<tr>
				<td>
					<b>Message:</b></td>
				<td>{$_message['message']}</td></tr><table><html>\n";

		$toAddress = "MattNigro <xxx@mattnigro.com>";
		$headers  = 'MIME-Version: 1.0' . "\r\n";
		$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
		$headers .= "From: $senderName <$email>" . "\r\n";
		$headers .= "Reply-To: $senderName <$email>" . "\r\n";
		if(mail($toAddress,$subject,$HTMLmessage,$headers)){

			//print "HTML Mail sent.<br>";
		}
		else {

			print "Send Mail failed.<br>";
		}

		$toAddress = "$senderName <$email>";
		$headers  = 'MIME-Version: 1.0' . "\r\n";
		$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
		$headers .= "From: MattNigro <no-reply@mattnigro.com>" . "\r\n";
		$headers .= "Reply-To: MattNigro <no-reply@mattnigro.com>" . "\r\n";
		if(mail($toAddress,$subject,$HTMLmessage,$headers)){

			$appendURL = $cryptKey->encryptURL($CID,$MID);
			$returnURL = '../contact.php?mcu=' . $appendURL;
		}
		else {

			print "Send Mail failed.<br>";
		}
	}
}
else {

	$returnURL = '../contact.php?E=6'; // BAD EMAIL ADDRESS
}
header("Location: $returnURL");
?>
