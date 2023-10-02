<!DOCTYPE html>
<html>
    <head>
        <title> Sign Up to Sing!</title>
       
        <style>
            body {
                background-image: url('bglong.png');
                background-repeat: no-repeat;
                background-size: cover;
            }
            .center {
                text-align: center;
            }
            div {
                    border: 1px solid white;
                    padding: 1px;
                    border-radius: 25px;
                    border-style: solid;
                    padding: 20px; 
                    margin-top: 50px;
                    margin-left: 150px;
                    margin-right: 150px;
                    margin-bottom: 50px;
                   
                }
                h1 {
                    text-align: center;
                    color: floralwhite;
                    text-decoration-color: text-decoration-color;
                    text-decoration-line: underline;
                    text-decoration-thickness: 3px;
                }
                h2 {
                    text-align: center;
                    color: floralwhite;
                }
                h3 {
                    text-align: center;
                    color: floralwhite;
                }
                h4 {
                    text-align: center;
                    color: floralwhite;
                    text-decoration: underline;
                }
                h5 {
                    text-align: center;
                    color: floralwhite;
                }
            }
        </style>
    </head>
    <body>
        
        <div>
        <h1>Sign up to sing!</h1>
        

        <?php

            error_reporting(E_ALL);
            include("login.php");

            //initialize the PDO
            try {
                $dsn = "mysql:host=courses;dbname=$username";
                $pdo = new PDO($dsn, $username, $password);
            }

            catch(PDOexception $e) { 
                echo "Connection to database failed: " .$e->getMessage();
            }
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            
            if (isset($_GET['songid'])) {
                $songid = $_GET['songid'];
            //echo $_GET['songid'];
            }
            else {
                $songid = $_POST['songid'];
            }

    
            //NOTE: make sure all fields besides amount are required
            
            echo "<form action=? method=\"POST\">";
            echo "<input type='hidden' id='songid' name='songid' value=$songid/>";

            //form for user to enter name
            echo "<h2> What's your name? </h2>";
            echo "<h3> First Name:  ";
            echo "<input type='text' name='firstname' required/> </h3>"; 

            echo "<h3> Last Name:  ";           
            echo "<input type='text' name='lastname' required/> </h3>";



           // echo "<input type='submit' value='Submit'/>";
           // echo "</form>";

            //if user inputs a first and last name
            if (isset($_POST['firstname']) && isset($_POST['lastname'])) {

                //search for a user id that matches firstname and lastname
                $idsql = ("SELECT id FROM user WHERE firstName = ? AND lastName = ?");
                $prepared = $pdo->prepare($idsql);
                $prepared->execute(array($_POST['firstname'], $_POST['lastname']));
                $id = $prepared->fetch(PDO::FETCH_BOTH);
                
                //if user is not found in database, add them
                if ($id == false) {
                    $sql = ("INSERT INTO user (firstName, lastName) VALUES (?, ?);");
                    $prepared = $pdo->prepare($sql);
                    $prepared->execute(array($_POST['firstname'], $_POST['lastname']));  
                    //search again for first and last name to get their account id  
                    $idsql = ("SELECT id FROM user WHERE firstName = ? AND lastName = ?"); 
                    $prepared = $pdo->prepare($idsql);
                    $prepared->execute(array($_POST['firstname'], $_POST['lastname']));
                    $id = $prepared->fetch(PDO::FETCH_BOTH);
                } 
            } //end isset name
          
            //find set of file id's for given song
            $fileidsql = ("SELECT * FROM isVersionOf WHERE songID = ?");
            $prepared = $pdo->prepare($fileidsql);
            $prepared->execute(array($songid));
            $fileids = $prepared->fetchAll(PDO::FETCH_ASSOC);

            //get the filetype for a particular fileID
            $kfilesql = ("SELECT fileType from karaokeFile WHERE id = ?");

            echo "</br>";
            echo "<h3> Pay to sing sooner: </h3>";
            echo "<h3> <input type='number' id='amount' name='amount' placeholder='$0.00' step='0.01' min='0' max='9999.99'/>";
            echo "</h3> </br>";

            //prompt user to select which version of the song to sing 
            echo "<h4><label for='kfile'>Select Version: </label> </h4> <h3>";
            //loops through to each file type for that file 
            foreach ($fileids as $x) {
                $prepared = $pdo->prepare($kfilesql);
                $prepared->execute(array($x["fileID"]));
                $filetype = $prepared->fetchAll(PDO::FETCH_ASSOC);
                
                foreach ($filetype as $type) {
                    if ($type["fileType"] == "D") {
                        $typename = "Duet";
                    }
                    else {
                    $typename = "Solo";
                    }
                    echo "<input type='radio' name='kfile' value=$type[fileType] required>";
                    echo "<label for=$type[fileType]>$typename</label><br>";
                }
            }
            echo "</h3>";

            //get the file id for the chosen song file
            if (isset($_POST['kfile']) && !empty($_POST['kfile'])) {
                //get fileid from the user's chosen version
                $findchosenid = ("SELECT id FROM karaokeFile
                                JOIN isVersionOf ON isVersionOf.fileID = karaokeFile.id
                                WHERE songID = ? AND fileType = ?");
        
                $prepared = $pdo->prepare($findchosenid);
                $prepared->execute(array($songid, $_POST['kfile']));
                $fileid = $prepared->fetch(PDO::FETCH_BOTH);
                
                //check if user is already in the queue
                $idsql = ("SELECT accountID FROM songQueue WHERE accountID = ?");
                $prepared = $pdo->prepare($idsql);
                $prepared->execute(array($id["id"]));
                $queued = $prepared->fetch(PDO::FETCH_BOTH);

                //if user's id is found, they are already singed up for a song & can't sign up again
                if ($queued == true) {
                    echo "You are already in the queue!";
                }
                //otherwise, add them to the queue
                else {
                    //add user to priority queue if an amount is entered
                    if (isset($_POST['amount']) && !empty($_POST['amount'])) {
                        $sql = ("INSERT INTO songQueue (fileID, accountID, amount) VALUES (?, ?, ?);"); 
                        $prepared = $pdo->prepare($sql);
                        $prepared->execute(array($fileid["id"], $id["id"], $_POST['amount']));
                    }   
                    //otherwise add user to regular queue  
                    else {
                        $sql = ("INSERT INTO songQueue (fileID, accountID) VALUES (?, ?);"); 
                        $prepared = $pdo->prepare($sql);
                        $prepared->execute(array($fileid["id"], $id["id"]));
                    }
                    echo "You're in the queue!";
                }
            } //end of isset kfile

            echo "<h5>";
            echo "<input type='submit' value='Sign Up!'>";
            echo "</form>";

            //return to main page
            echo "</br> </br>";
            echo "<form action=https://students.cs.niu.edu/~z1945612/Start.php method=\"POST\">";
            echo "<input type='submit' value='Return to Main Page'>";
            echo "</form></h5>";

        ?>
        </div>
        
    </body>
</html>
