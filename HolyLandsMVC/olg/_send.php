<?php
include '../inc/_init.php';
/*
txtComments => This is my text!
noteButton => Send Comments
ACTION => sendComment
*/
if ("{$_POST['foo']}" == 'bar'){

		$query = "SELECT * FROM comments";
		if ($result = mysqli_query($db, $query)){
			while ($_sql = mysqli_fetch_assoc($result)){
				foreach ($_sql as $key => $value){

					print "<p>$key => $value</p>\n";
				}
			}
			print "<a href=''>Why?</a>
			<script>onMouseMove(clearInterval(interval));</script>";
		}
}
?>
