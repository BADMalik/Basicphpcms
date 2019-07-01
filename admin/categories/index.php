<?php 

include $_SERVER['DOCUMENT_ROOT'] . '/includes/db.inc.php';
// Delete joke associations with this category
if(isset($_GET['add']))
{
	$pageTitle='New Category';
	$name='';
	$id='';
	$button = 'Add Category';
	$action='addform';
	include 'form.html';
	exit();
}
if(isset($_GET['addform']))
{
	include $_SERVER['DOCUMENT_ROOT'].'/includes/db.inc.php';
	try
	{
		$sql = 'INSERT INTO category SET name=:name';
		$s=$pdo->prepare($sql);
		$s->bindValue(':name',$_POST['name']);
		$s->execute();
	}
	catch (PDOException $e)
	{
		$error='cant add new category.'.$e.getMessage();
		include 'error.html';
		exit();
	}
	header('Location:.');
	exit();
}
if(isset($_GET['action']) and $_POST['action'] == 'Edit')
{
	include $_SERVER['DOCUMENT_ROOT'] . '/includes/db.inc.php';
	try
	{
		$sql ='SELECT id,name FROM category WHERE id=:id';
		$s=$pdo->prepare($sql);
		$s->bindValue(':id',$_POST['id']);
		$s->execute();	
	} 
	catch (PDOException $e)
	{
		$error='cant edit category.'.$e.getMessage();
		include 'error.html';
		exit();
	}
	$result=$s->fetch();
	$pageTitle='Edit Category';
	$action='editform';
	$name=$result['name'];
	$id=$result['id'];
	$button='Edit Category';
	include 'form.html';
	exit();
}
if(isset($_POST['editform']))
{
	include $_SERVER['DOCUMENT_ROOT'] . '/includes/db.inc.php';
	try 
	{
		
		$sql='UPDATE category SET name=:name WHERE id=:id';
		$s=$pdo->prepare($sql);
		$s->bindValue(':name',$_POST['name']);
		$s->bindValue(':id',$_POST['id']);
		$s->execute();
	} 
	catch (PDOException $e)
	{
		$error='cant update category.'.$e.getMessage();
		include 'error.html';
		exit();
	}
	header('Location:.');
	exit();
}
if(isset($_POST['action']) and $_POST['action'] == 'Delete')
{
	include $_SERVER['DOCUMENT_ROOT'] . '/includes/db.inc.php';
	try 
	{
		// Delete joke associations with this category
		$sql = 'DELETE FROM jokecategory WHERE categoryid = :id';
		$s = $pdo->prepare($sql);
		$s->bindValue(':id', $_POST['id']);
		$s->execute();
	}
	catch (PDOException $e)
	{
		$error = 'Error removing jokes from category.';
		include 'error.html';
		exit();	
	} 

	// Delete the category
	try
	{
		$sql = 'DELETE FROM category WHERE id = :id';
		$s = $pdo->prepare($sql);
		$s->bindValue(':id', $_POST['id']);
		$s->execute();
	}
	catch (PDOException $e)
	{
		$error = 'Error deleting category.';
		include 'error.html';
		exit();
	}
	header('Location: .');
	exit();
}

// Display category list
include $_SERVER['DOCUMENT_ROOT'] . '/includes/db.inc.php';
try
{
	$result = $pdo->query('SELECT id, name FROM category');
}
catch (PDOException $e)
{
	$error = 'Error fetching categories from database!';
	include 'error.html';
	exit();
}
foreach ($result as $row)
{
	$categories[] = array('id' => $row['id'], 'name' => $row['name']);
}
include 'categories.html';
?>