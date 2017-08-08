<?php
include '_inc/CryptKey.php';
$uIP = new CryptKey("{$_SERVER['REMOTE_ADDR']}");
$uKey = $uIP->Encrypt();
?>
<!DOCTYPE html>
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
		<meta name="author" content="Matthew Nigro">
		<title>Home :: MattNigro.com</title>
		<link rel="stylesheet" type="text/css" href="style.css">
		<script  type="text/javascript" src='/js/main.js'></script>
	</head>
	<body>

	<div id="container">
	  <div id="header">
	  <div id="bannerBack">
	  	<h2 id="bannerText">Welcome to Matt Nigro .com!</h2></div>
	  	<img src="/images/bannerMattNigro.jpg" style="border-radius: 7px" width="950" height="300" title="Matthew Nigro in Viking Iceland!" alt="Matthew Nigro in Iceland!">
	  </div>

<!-- = = = = = = = = = BEGIN NAVIGATION = = = = = = = = = = = = = -->
	  <div id="menu">
	  	<a href="http://mattnigro.com" title="Back to MattNigro.com Homepage">
	  		<div class="button">Home</div></a><br>
	  	<div class="button" id="btnContactModal">Contact</div><br>
	  	<a href="http://facebook.com/matthew.nigro" title="Find me on FaceBook!" target="_blank">
	  			<img class="btnImage" src="/images/fbLogo.png" width="50" height="50" alt="Find me on FaceBook!"></a>
	  	<a href="https://www.linkedin.com/in/matthew-nigro-122b05a/" title="Connect with me on LinkedIn!" target="_blank">
	  			<img class="btnImage" src="/images/linkedinLogo.png" width="50" height="50" alt="Connect with me on Google Plus!"></a>
				<a href="https://plus.google.com/u/0/+MatthewNigro" title="Connect with me on Google Plus!" target="_blank">
	  			<img class="btnImage" src="/images/gPlusLogo.png" width="50" height="50" alt="Connect with me on LinkedIn!"></a>
	  </div>

<!-- = = = = = = = = = BEGIN MAIN CONTENT = = = = = = = = = = = = = -->
	  <div id="content"><h2>What a Wonderful Summer!</h2>
	  <p>Thanks for visiting.</p>
	  <p>Lots of summer adventure pictures to come!</p>
	  <p style='text-align: center'>
	  <img src="/images/theSwim_MattNigro.jpg" width="492" height="193" alt="It's Summer Time!"></p>
	  <p>&nbsp;</p>
<!-- = = = = = = = = = BEGIN MODAL WINDOW = = = = = = = = = = = = = -->
	  <div id="modalForm">
<form name="contactForm" id="contactForm" action="contact/?fkey=33" method="post" target="_self" class="forms">
	<fieldset>
		<legend>Contact Me!</legend>
		<p>Note: all fields are required</p>
			<table class="frmTable">
				<tr>
					<td>
						<input type="text" name="name" title="Enter your name here" required="required" class="frmTextbox" maxlength="40" placeholder="Your name"></td></tr>
				<tr>
					<td>
						<input type="text" name="email" id="txtEmail" title="Enter your email address here" required="required" class="frmTextbox" maxlength="40" placeholder="Your email address"></td></tr>
				<tr>
					<td>
						<input type="text" name="subject" title="Enter the message subject here" required="required" class="frmTextbox" maxlength="70" placeholder="Message Subject"></td></tr>
				<tr>
					<td style="text-align: center;">
						<textarea name="message" id="contact_message" maxlength="1000" title="Enter your message here" required="required" class="frmTextarea" placeholder="Enter Message Here" onkeyup="CountChars(this)" style="margin-bottom: 0;"></textarea><br>
						<p id="characters_remaining" style="margin: 0; text-align: right; font-size: 10px; font-weight: bold; color: red;"></p></td></tr>
				<tr>
					<td style="text-align: center;">
						<input type="submit" class="button" id="btnFormSubmit" value="Send Email">
						<div class="btnSmall" id="btnCancel" onclick="HideModal()">cancel</div>
						<input type="hidden" name="fkey" value="<?php print $uKey ?>"></td></tr></table>

	</fieldset>
</form>
</div>
<!-- = = = = = = = = = END MODAL WINDOW = = = = = = = = = = = = = -->

</div>
	  <div id="footer">Copyright &copy; 2017, Matthew Nigro, All Rights Reserved</div>
	</div>
	<script  type="text/javascript" src='/js/hidden.js'></script>
	</body>
</html>
