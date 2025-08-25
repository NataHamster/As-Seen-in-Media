<?php
  $host = 'localhost';
	$db = 'db';
	$user = 'user';
	$pass = 'pass';


	$mysqli = new mysqli($host, $db, $user, $pass);
	mysqli_set_charset($mysqli, "utf8");
?>
