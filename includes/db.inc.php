<?php 
try
{
	$pdo = new PDO('mysql:hostname=localhost;dbname=ijdb','ijdb','mypassword');
	$pdo->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
	$pdo->exec('SET NAMES "utf8"');
}
catch (PDOException $e)
{
	$error = 'Unable to connect to server'. $e->getMessage();
	include 'error.html';
	exit();
}
?>