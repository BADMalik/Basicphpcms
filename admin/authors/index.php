	<?php
include_once $_SERVER['DOCUMENT_ROOT'].'/includes/db.inc.php';
include_once $_SERVER['DOCUMENT_ROOT'] .'/includes/magicquotes.inc.php';
try	
{
	$result = $pdo->query('SELECT id,name FROM author');

}
catch(PDOException $e)
{
	$error = 'Could not select authors '.$e->getMessage();
	include'error.html';
	exit();
}
foreach ($result as $value) 
{
	$authors[]=array(
		'id'=>$value['id'],
		'name'=>$value['name']
	);
}

if(isset($_GET['add']))
{
	$pageTitle='Add new author';
	$name='';
	$email='';
	$action='addform';
	$id='';
	$button = 'Add Author';
	include 'form.html';
	exit();
}
if(isset($_GET['addform']))
{
	include $_SERVER['DOCUMENT_ROOT'].'/includes/db.inc.php';
	try
	{
		$sql = 'INSERT INTO author SET name=:name,email=:email';
		$s=$pdo->prepare($sql);
		$s->bindValue(':name',$_POST['name']);
		$s->bindValue(':email',$_POST['email']);
		$s->execute();
	}
	catch (PDOException $e)
	{
		$error='cant insert new authors'.$e->getMessage();
		include 'error.html';
		exit();
	}
	header('Location:.');
	exit();
}

if(isset($_POST['action']) and $_POST['action'] == 'Edit')
{
	include $_SERVER['DOCUMENT_ROOT'].'/includes/db.inc.php';
	try
	{
		

		$sql='SELECT id,name,email FROM author WHERE id=:id';
		$s=$pdo->prepare($sql);
		$s->bindValue(':id',$_POST['id']);
		$s->execute();
	} 
	catch (PDOException $e) 
	{
		$error='cant edit hte author .'.$e->getMessage();
		include 'error.html';
		exit();
	}
	$result =$s->fetch();
	$pageTitle='Edit Author';
	$name = $result['name'];
	$email = $result['email'];
	$action='editform';
	$id=$result['id'];
	$button='Update Author';
	include 'form.html';
	exit();
}

if(isset($_GET['editform']))
{
	include $_SERVER['DOCUMENT_ROOT'].'/includes/db.inc.php';
	try 
	{
		$sql='UPDATE author SET name=:name,email=:email WHERE id=:id';
		$s=$pdo->prepare($sql);
		$s->bindValue(':name',$_POST['name']);
		$s->bindValue(':id',$_POST['id']);
		$s->bindValue(':email',$_POST['email']);
		$s->execute();
	} 
	catch (PDOException $e) 
	{
		$error ='Cant edit form'.$e->getMessage();
		include 'error.html';
		exit();	
	}
	header('Location:.');
	exit();
}

if(isset($_POST['action']) and isset($_POST['action'])=='Delete')
{
	include $_SERVER['DOCUMENT_ROOT'].'/includes/db.inc.php';
	//get jokes belonging to author
	try
	{
		$sql = 'SELECT id FROM joke WHERE authorid=:id';
		$s = $pdo->prepare($sql);
		$s->bindValue(':id',$_POST['id']);
		$s->execute();
	}
	catch(PDOException $e)
	{
		$error = 'cant get authorid from joke table'
		.$e->getMessage();
		include 'error.html';
		exit();
	}
	$result = $s->fetchAll();

	//Delete  jokecategory entry
	try
	{
		$sql = 'DELETE FROM jokecategory WHERE jokeid=:id ';
		$s=$pdo->prepare($sql);

		foreach ($result as $value) 
		{
			$jokeid=$value['id'];
			$s->bindValue(':id',$jokeid);
			$s->execute();
		}
	}
	catch(PDOException $e)
	{
		$error = 'Cant Delete jokecategory entry'.$e.getMessage();
		include 'error.html';
		exit();
	}
	//Delete jokes belonging to author 
	try 
	{
		$sql = 'DELETE FROM joke WHERE authorid=:id';
		$s=$pdo->prepare($sql);
		$s->bindValue(':id',$_POST['id']);
		$s->execute();
	}
	 catch (PDOException $e) 
	 {
		$error = 'Cant Delete joke entry'.$e.getMessage();
		include 'error.html';
		exit();
	}
	//delete the author from author table
	try 
	{	
		$sql = 'DELETE FROM author WHERE id=:id';
		$s=$pdo->prepare($sql);
		$s->bindValue(':id',$_POST['id']);
		$s->execute();
		
	}
	catch (PDOException $e)
	{
		$error = ' cant delete author'
		.$e->getMessage();
		include 'error.html';
		exit();	
	}

	$confirm = 'Data was successfully deleted';
	include 'confirm.html';
	exit();
	
}



include'authors.html';
 ?>