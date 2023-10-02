<html>
<head> 
	<title>Welcome</title> 
</head> 
<body>

<?php

echo '<span>';
echo '<form action="https://students.cs.niu.edu/~z1968850/dj.php" method="post">';
	echo '<input type="submit" value="DJ Login">';

echo '</form>';

echo '<form action="https://students.cs.niu.edu/~z1979694/GroupProject/Search.php" method="post">';
	echo '<input type="submit" value="Sign In to Sing">';

echo '</form>';
echo '</span>';


?>
</body>


</htm> 

<!-- For the User to look at the songs, do we want them to be able to resubmit a Search on the same page?
	we could manage this by passing a hidden value in the form that tells if it is the start of the seaching process, ie they came from this page, and pass a different one when
	doing the search so that way we can look and see what value is pressent before we try and look for a value that might not exist. And we can use it to tell what search to do,
	like the title, band or contributer. -->   