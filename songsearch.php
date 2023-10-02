<!DOCTYPE html>
<html>
    <head>
        <title> Find a Song </title>
    </head>
    <body>
    Search for a song
    <?php

    error_reporting(E_ALL);

    //initialize PDO
    try {

        $dsn = "mysql:host=courses;dbname=z1979694";
        $pdo = new PDO($dsn, 'z1979694', '2001Jan26');
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        echo '<form action=? method="post">';

        echo '<input type="text" id="name" name="name"> ';
     //   echo '<input type="hidden" id="type" name="type" value="bname">';

        //drop down menu to choose how to search
        echo '<select name="searchby", id="searchby">';
        echo '<option value="title">Title</option>';
        echo '<option value="artist">Artist</option>';
        echo '<option value="contributor">Contributor</option>';
        echo '</select>';

        echo '<input type="submit" value="Submit">';

        if (isset($_POST['searchby']) && isset($_POST['name'])) {
            //search by title
            if ($_POST['searchby'] == "title") {
                echo "meow";
            }

            //search by artist
            else if ($_POST['searchby'] == "artist") {
                echo "bark";
            }

            //search by contributor
            else if ($_POST['searchby'] == "contributor") {
                echo "ribbit";
            }    
        }
        
    




        echo '</form>';

    }
    catch (PDOexception $e) {
        echo "Connection to database failed: " . $e->getMessage();
    }


    ?>
    </body>
</html>