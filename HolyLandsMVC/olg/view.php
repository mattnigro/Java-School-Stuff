<?php

include '../inc/_init.php';
include '{$root}view/header.php';
?>
<html>
<body>

<p id="convoBox" style="width: 300px; height: 150px;"><?php print $notification ?></p>


<script>
var count = 0;
var interval = setInterval(function() {

	count++;
	console.log(count)
	var xhr = new XMLHttpRequest();
	xhr.open('POST', '_send.php', true);
	xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
	xhr.send("foo=bar");
	xhr.onload = function(){

		if (xhr.readyState == 4 && xhr.status === 200){

			document.getElementById('convoBox').innerHTML = xhr.responseText;
			clearInterval(interval);
		}
	}
}, 7000);
</script>
</body>
</html>
