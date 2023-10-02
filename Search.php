<?php
session_start();
?>

<html>
 <head>
 <!-- url('background2.jpg'); -->
 <!-- code from https://www.w3schools.com/howto/tryit.asp?filename=tryhow_css_table_center -->
  <meta name="viewport" content="width=device-width, initial-scale=1">
<style>
body {
 background-image: linear-gradient( 30deg, #C84674, #4A2350);

 table, th, td {
  border: 1px solid black;
  border-collapse: collapse;
}

table.center {
  margin-left: auto; 
  margin-right: auto;
}
}
</style>
</head>
<!-- Seaching for song-->

<!--  Using Get and Post in the same page!! -->

<?php


include("login.php"); //login.php creates the conection to the database so that we can test indviualy without sharing logins and to reduce amount of reused code.
/*
try {
	$dsn = "mysql:host=courses;dbname=z1979694";
	$pdo = new PDO($dsn, $username, $password);
	}
catch (PDOexception $e) {
	echo "Connection to database failed: " . $e->getMessage();
	}
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);*/

//Form to Get data from user
echo '<form action=? method="post">';
echo '<input type="text" id="name" name="name"> ';
//drop down menu to choose how to search
echo '<select name="type", id="type">';
echo '<option value="title">Title</option>';
echo '<option value="artist">Artist</option>';
echo '<option value="contributor">Contributor</option>';
echo '</select>';

echo '<input type="submit" value="Submit">';

echo "</form>";


//form to for a reset button

if(isset($_POST['type']) || isset($_SESSION['type']))
{
	if(isset($_POST['name']))
	{
		$_SESSION['name'] = $_POST['name'];
		$_SESSION['type'] = $_POST['type'];
		}

	//if the sort and direction is not set, set it to defalt values
	if(!isset($_GET['dir']))
	{
		$_GET['dir']= "ASC";
		$_GET['sort']="band.name";
	}
	//Seaching by contributor
	if($_SESSION['type'] == 'contributor')
	{
		if($_GET['sort']== "song.title")
			{
				if($_GET['dir']=="ASC")	
				{
					$sql = "SELECT DISTINCT band.name, song.title, song.id, contributor.stageName, contributor.id, song.genre FROM band, song, created, contributedTo, contributor 
					WHERE band.id = created.bandID AND song.id = created.songID AND song.id = contributedTo.songID 
					AND contributedTo.contributorID = contributor.id AND stageName like CONCAT('%', ?, '%') ORDER BY song.title ASC";
				}
				else 
				{
					$sql="SELECT DISTINCT band.name, song.title, song.id, contributor.stageName, contributor.id, song.genre FROM band, song, created, contributedTo, contributor 
					WHERE band.id = created.bandID AND song.id = created.songID AND song.id = contributedTo.songID 
					AND contributedTo.contributorID = contributor.id AND stageName like CONCAT('%', ?, '%') ORDER BY  song.title DESC";
				}
				}
		else if($_GET['sort']=="band.name")
			{
				if($_GET['dir']=="ASC")	
				{
					$sql = "SELECT DISTINCT band.name, song.title, song.id, contributor.stageName, contributor.id, song.genre FROM band, song, created, contributedTo, contributor 
					WHERE band.id = created.bandID AND song.id = created.songID AND song.id = contributedTo.songID 
					AND contributedTo.contributorID = contributor.id AND stageName like CONCAT('%', ?, '%') ORDER BY  band.name ASC";
				}
				else 
				{
					$sql="SELECT DISTINCT band.name, song.title, song.id, contributor.stageName, contributor.id, song.genre FROM band, song, created, contributedTo, contributor 
					WHERE band.id = created.bandID AND song.id = created.songID AND song.id = contributedTo.songID 
					AND contributedTo.contributorID = contributor.id AND stageName like CONCAT('%', ?, '%') ORDER BY band.name DESC";
				}
				}
				else if($_GET['sort']=="stagename")
			{
				if($_GET['dir']=="ASC")	
				{
					$sql = "SELECT DISTINCT band.name, song.title, song.id, contributor.stageName, contributor.id, song.genre FROM band, song, created, contributedTo, contributor 
					WHERE band.id = created.bandID AND song.id = created.songID AND song.id = contributedTo.songID 
					AND contributedTo.contributorID = contributor.id AND stageName like CONCAT('%', ?, '%') ORDER BY  contributor.stageName ASC";
				}
				else 
				{
					$sql="SELECT DISTINCT band.name, song.title, song.id, contributor.stageName, contributor.id, song.genre FROM band, song, created, contributedTo, contributor 
					WHERE band.id = created.bandID AND song.id = created.songID AND song.id = contributedTo.songID 
					AND contributedTo.contributorID = contributor.id AND stageName like CONCAT('%', ?, '%') ORDER BY contributor.stageName DESC";
				}
				}
		else {
			if($_GET['dir']=="ASC")	
			{
				$sql = "SELECT DISTINCT band.name, song.title, song.id, contributor.stageName, contributor.id, song.genre FROM band, song, created, contributedTo, contributor 
				WHERE band.id = created.bandID AND song.id = created.songID AND song.id = contributedTo.songID 
				AND contributedTo.contributorID = contributor.id AND stageName like CONCAT('%', ?, '%') ORDER BY  genre ASC";
			}
			else 
			{
				$sql="SELECT DISTINCT band.name, song.title, song.id, contributor.stageName, contributor.id, song.genre FROM band, song, created, contributedTo, contributor 
				WHERE band.id = created.bandID AND song.id = created.songID AND song.id = contributedTo.songID 
				AND contributedTo.contributorID = contributor.id AND stageName like CONCAT('%', ?, '%') ORDER BY genre DESC";
			}
			}
		$results2= $pdo->prepare($sql);
		$results2->execute(array($_SESSION['name']));
		$row2=$results2->fetch(PDO::FETCH_BOTH);

		if($row2== false)
		{
			echo 'No songs found';
			}
		else {
			$num=0;
			//echo '<table border="2"; bgcolor="white"; margin-left:auto; margin-right:auto>';
			echo '<table style="margin-left:auto;margin-right:auto; background-color:rgba(255, 255, 255, .7);" border ="2"';
			if($_GET['dir']=="ASC"){
				$dir = "DESC";
				}
			else {
				$dir = "ASC";
				}
			echo '<tr>';
			if($_GET['sort']== "stagename") {
				echo '<th>';
				echo '<a href="?dir=';
				echo $dir;
				echo '&sort=stagename">Contributor Name</a>';
				echo '</th>';
				}
			else {
				echo '<th><a href="?dir=ASC&sort=stagename">Contributor Name</a></th>';
				}
			if($_GET['sort']== "band.name")
				{
					echo '<th>';
					echo '<a href="?dir=';
					echo $dir;
					echo '&sort=band.name">Artist</a>';
					echo '</th>';
				}
			else {
				echo '<th>';
				echo '<a href="?dir=ASC&sort=band.name">Artist</a>';
				echo '</th>';
				}
			if($_GET['sort']=='song.title'){
				echo '<th>';
				echo '<a href="?dir=';
				echo $dir;
				echo '&sort=song.title">Song Title</a>';
				echo '</th>';
			}
			else{
				echo '<th><a href="?dir=ASC&sort=song.title">Song Title</a></th>';
				}
			if($_GET['sort']=='genre'){
				echo '<th>';
				echo '<a href="?dir=';
				echo $dir;
				echo '&sort=genre">Genre</a>';
				echo '</th>';
				}
			else{
				echo '<th><a href="?dir=ASC&sort=genre">Genre</a></th>';
				}
			echo '<th>Roles</th>';
			echo '</tr>';

			
			while($row2 !=0)
			{
				$num +=1;
				//stageName
				echo '<tr>';
				echo '<td>';
				echo $row2[3];
				echo '</td>';
			
				//BandName
				echo '<td>';
				echo $row2[0];
				echo '</td>';
				//Song Title
				echo '<td>';
					echo '<a href=https://students.cs.niu.edu/~z1933361/CSCI466-Group-Project/signup.php?songid=';
					echo $row2[2];
					echo '>';
					echo $row2[1];
					echo '</a></td>';
				/*echo '<td>';
				echo $row2[1];
				echo '</td>';
				*/
				//genre
				echo '<td>';
				echo $row2[5];
				echo '</td>';

				echo '<td>';
				$results3= $pdo->prepare('SELECT contributorRole FROM contributedTo WHERE contributorID =? AND songID = ?');
				$results3->execute(array($row2[4],$row2[2]));
				$row3=$results3->fetch(PDO::FETCH_BOTH);
				//Roles for that contributor
				echo '<ul>';
				while($row3 != false)
				{
					echo '<li>';
					echo $row3[0];
					echo '</li>';
				$row3=$results3->fetch(PDO::FETCH_BOTH);
				}
				echo '</ul>';
				echo '</td>';
				echo '</tr>';
				$row2=$results2->fetch(PDO::FETCH_BOTH);
				}
			echo '</table>';
			echo "<p style='color:black;'>Number of Songs Found: ";
			echo $num;
			echo "</p>";
			}
	}
	//For Searching by Artist or Song Title
	else {

		if($_SESSION['type']== 'artist'){
			if($_GET['sort']== "song.title")
			{
				if($_GET['dir']=="ASC")	
				{
					$sql = "SELECT band.name, song.title, song.id, song.genre FROM band, song, created WHERE band.id = created.bandid AND song.id = created.songid AND band.name like  CONCAT('%', ?, '%') ORDER BY song.title ASC";
				}
				else 
				{
					$sql="SELECT band.name, song.title, song.id, song.genre FROM band, song, created WHERE band.id = created.bandid AND song.id = created.songid AND band.name like  CONCAT('%', ?, '%') ORDER BY song.title DESC";
				}
				}
			else if($_GET['sort']=="band.name")
			{
				if($_GET['dir']=="ASC")	
				{
					$sql = "SELECT band.name, song.title, song.id, song.genre FROM band, song, created WHERE band.id = created.bandid AND song.id = created.songid AND band.name like  CONCAT('%', ?, '%') ORDER BY band.name ASC";
				}
				else 
				{
					$sql="SELECT band.name, song.title, song.id, song.genre FROM band, song, created WHERE band.id = created.bandid AND song.id = created.songid AND band.name like  CONCAT('%', ?, '%') 
					ORDER BY band.name DESC";
				}
				}
			else 
			{
				if($_GET['dir']=="ASC")	
				{
					$sql = "SELECT band.name, song.title, song.id, song.genre FROM band, song, created WHERE band.id = created.bandid AND song.id = created.songid AND band.name like  CONCAT('%', ?, '%') ORDER BY genre ASC";
				}
				else 
				{
					$sql="SELECT band.name, song.title, song.id, song.genre FROM band, song, created WHERE band.id = created.bandid AND song.id = created.songid AND band.name like  CONCAT('%', ?, '%') 
					ORDER BY genre DESC";
				}
			}
			}
		else {
			if($_GET['sort']== "song.title")
			{
				if($_GET['dir']=="ASC")	
				{
					$sql = "SELECT band.name, song.title, song.id, song.genre FROM band, song, created WHERE band.id = created.bandid AND song.id = created.songid AND song.title like  CONCAT('%', ?, '%') ORDER BY song.title ASC";
				}
				else 
				{
					$sql="SELECT band.name, song.title, song.id, song.genre FROM band, song, created WHERE band.id = created.bandid AND song.id = created.songid AND song.title like  CONCAT('%', ?, '%') ORDER BY song.title DESC";
				}
				}
			else if($_GET['sort']=="band.name")
			{
				if($_GET['dir']=="ASC")	
				{
					$sql = "SELECT band.name, song.title, song.id, song.genre FROM band, song, created WHERE band.id = created.bandid AND song.id = created.songid AND song.title like  CONCAT('%', ?, '%') ORDER BY band.name ASC";
				}
				else 
				{
					$sql="SELECT band.name, song.title, song.id, song.genre FROM band, song, created WHERE band.id = created.bandid AND song.id = created.songid AND song.title like  CONCAT('%', ?, '%') 
					ORDER BY band.name DESC";
				}
				}
		else {
			if($_GET['dir']=="ASC")	
			{
				$sql = "SELECT band.name, song.title, song.id, song.genre FROM band, song, created WHERE band.id = created.bandid AND song.id = created.songid AND song.title like  CONCAT('%', ?, '%') ORDER BY genre ASC";
			}
			else 
			{
				$sql="SELECT band.name, song.title, song.id, song.genre FROM band, song, created WHERE band.id = created.bandid AND song.id = created.songid AND song.title like  CONCAT('%', ?, '%') 
				ORDER BY genre DESC";
			}
			}
			}

		$results= $pdo->prepare($sql);
		$results->execute(array($_SESSION['name']));
		$row=$results->fetch(PDO::FETCH_BOTH);
		$num=0;

		if($row == false)
		{
			echo "No songs found";
			}
		else {
			echo '<table style="margin-left:auto;margin-right:auto; background-color:rgba(255, 255, 255, .7);" border ="2"';
			echo '<tr>';

			//Printing hetters
			if($_GET['sort']== "band.name")
			{
				if($_GET['dir']=="ASC")
				{
					$dir = "DESC";
				}
				else 
				{
					$dir = "ASC";
				}
				//Band Name
				echo '<th>';
				echo '<a href="?dir=';
				echo $dir;
				echo '&sort=band.name">Artist</a>';
				echo '</th>'; 
				//Song Title
				echo ' <th>';
				echo '<a href="?dir=ASC&sort=song.title">Song Title</a>';
				echo '</th>';
				//genre
				echo '<th><a href="?dir=ASC&sort=genre">Genre</a></th>'; 
				}
			else if($_GET['sort']=="song.title")
			{
				if($_GET['dir']=="ASC")
				{
					$dir = "DESC";
				}
				else 
				{
					$dir = "ASC";
				}
				//Band Name
				echo '<th>';
				echo '<a href="?dir=ASC&sort=band.name">Artist</a>';
				echo '</th>';
				//Song Title
				echo ' <th>';
				echo '<a href="?dir=';
				echo $dir;
				echo '&sort=song.title">Song Title</a>';
				echo '</th>';
				//genre
				echo '<th><a href="?dir=ASC&sort=genre">Genre</a></th>'; 
				}
			else if($_GET['sort']=="genre")
			{
				if($_GET['dir']=="ASC")
				{
					$dir = "DESC";
				}
				else 
				{
					$dir = "ASC";
				}
				//Band Name
				echo '<th>';
				echo '<a href="?dir=ASC&sort=band.name">Artist</a>';
				echo '</th>';
				//Song Title
				echo ' <th>';
				echo '<a href="?dir=ASC&sort=song.title">Song Title</a>';
				echo '</th>';
				//genre
				echo '<th>';
				echo '<a href="?dir=';
				echo $dir;
				echo '&sort=genre">Genre</a>';
				echo '</th>';
				}
			//contributors with Roles			
			echo ' <th>Contributors with Roles</th> </tr> ';

			//Printing out table
			while($row != false)
			{
				echo '<tr>';
				//Band Name
				echo '<td>';
				echo $row[0];
				echo '</td>';
				//Song Title
				echo '<td>';
				echo '<a href=https://students.cs.niu.edu/~z1933361/CSCI466-Group-Project/signup.php?songid=';
				echo $row[2];
				echo '>';
				echo $row[1];
				echo '</a></td>';
				//genre
				echo '<td>';
				echo $row[3];
				echo '</td>';

				//Finding all of the contributors for the song
				$results2= $pdo->prepare('SELECT DISTINCT contributor.stagename, contributor.id FROM contributor, contributedTo WHERE contributor.id = contributedTo.contributorID AND contributedTo.songID = ?');
				$results2->execute(array($row[2]));
				$row2=$results2->fetch(PDO::FETCH_BOTH);
					echo '<td>';
					echo '<ol>';
					while($row2 != false)
					{
						echo '<li>';
						echo $row2[0];
						echo '<ul>';

						//Finding the Roles for each of contributor for the song
						$results3= $pdo->prepare('SELECT contributorRole FROM contributedTo WHERE contributorID =? AND songID = ?');
						$results3->execute(array($row2[1],$row[2]));
						$row3=$results3->fetch(PDO::FETCH_BOTH);
						while($row3 != false)
						{
							echo '<li>';
							echo $row3[0];
							echo '</li>';
							$row3=$results3->fetch(PDO::FETCH_BOTH);
							}
						echo '</ul>';
						echo '</li>';
						$row2=$results2->fetch(PDO::FETCH_BOTH);
						}
					echo '</ol>';
					echo '</td>';
		
				echo '</tr>';	
				$num += 1;
				$row=$results->fetch(PDO::FETCH_BOTH);
				}
			echo '</table>';
			echo "<p style='color:black;'>Number of Songs Found: ";
			echo $num;
			echo "</p>";
			}
		}
	}

?>
</html>