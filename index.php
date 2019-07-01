<?php
include_once $_SERVER['DOCUMENT_ROOT'] .'/includes/magicquotes.inc.php';
if (isset($_GET['addjoke']))
{
	include 'form.html';
	exit();
}	
	include $_SERVER['DOCUMENT_ROOT'].'/includes/db.inc.php';

if (isset($_POST['joketext']))
{
	try
	{
		$sql = 'INSERT INTO joke SET
		joketext = :joketext,
		jokedate = CURDATE()';
		$s = $pdo->prepare($sql);
		$s->bindValue(':joketext', $_POST['joketext']);
		$s->execute();
	}
catch (PDOException $e)
{
	$error = 'Error adding submitted joke: ' . $e->getMessage();
	include 'error.html';
	exit();
}
	header('Location: .');
	exit();
}
if (isset($_GET['deletejoke']))
{
	include $_SERVER['DOCUMENT_ROOT'].'/includes/db.inc.php';
	try
	{
		$sql = 'DELETE FROM joke WHERE id = :id';
		$s = $pdo->prepare($sql);
		$s->bindValue(':id', $_POST['id']);
		$s->execute();
	}
	catch (PDOException $e)
	{
		$error = 'Error deleting joke: ' . $e->getMessage();
		include 'error.html';
		exit();
	}
		header('Location: .');
		exit();
}
	include $_SERVER['DOCUMENT_ROOT'].'/includes/db.inc.php';

try
{
	$sql = 'SELECT joke.id,name,email,joketext FROM joke INNER JOIN author ON authorid=author.id';
	$result = $pdo->query($sql);
}
catch (PDOException $e)
{
	$error = 'Error fetching jokes: ' . $e->getMessage();
	include 'error.html';
	exit();
}
//while ($row = $result->fetch())
foreach ($result as $value) 
{

	$jokes[] = array(
		'id'=>$value['id'],'text'=>$value['joketext'],'email'=>$value['email'],'name'=>$value['name']);
}
	include 'jokes.html';
?>