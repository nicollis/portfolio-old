<?php
require_once 'src/db.php';
session_start();
//make sure user is allowed
if($_SESSION != null){
    if(!auth_check($_SESSION['email'], $_SESSION['password']))
        { session_destroy(); header('Location: index.php');}
}else{ session_destroy(); header('Location: index.php');}
//User is allowed!
//Check to see if user posted data
if(isset($_POST['about']))
    if(strlen($_POST['about'])>0){
        $about = filter_var($_POST["about"], FILTER_SANITIZE_STRING);
        $more = filter_var($_POST['more'], FILTER_SANITIZE_STRING);
        $strengths = filter_var($_POST['strengths'], FILTER_SANITIZE_STRING);
        $skills = filter_var($_POST['skills'], FILTER_SANITIZE_STRING);
        
        //Data grabed and sanatized, add to database
        $dbconn = post_connect();
        $query = "UPDATE me SET about='$about', more='$more', strengths='$strengths', skills='$skills' WHERE name='Nicholas Gillespie';";
        $results = pg_query($dbconn, $query) or die ('Query failed: '. pg_last_error());
        
        //process and save the images to the proper directory
        $uploaddir = '../img/uploads/';
        if(strlen($_FILES['image']['name'])>3){
            $upload_project_photo = $uploaddir . basename($_FILES['image']['name']);
            move_uploaded_file($_FILES['image']['tmp_name'], $upload_project_photo);
            $upload_project_photo = basename($_FILES['image']['name']);
            $photo_query= "UPDATE me SET photo='$upload_project_photo' WHERE name='Nicholas Gillespie';";
            pg_query($dbconn, $photo_query) or die ('Query failed: '. pg_last_error());
        }
    
        pg_close($dbconn);
        pg_free_result($results);
    }
?>
<!DOCTYPE html>
<html>
    <head>
        <link rel="icon" href="../FavIcon.ico"/>
        <title>Profile Editor</title>
        <link href='https://fonts.googleapis.com/css?family=Roboto:400,400italic,700' rel='stylesheet' type='text/css'>
        <link rel="stylesheet" type="text/css" href="../css/style_reset.css">
        <link rel="stylesheet" type="text/css" href="css/design.css">
    </head>
    <body style="text-align: center;">
       <?php
            require_once 'src/header.php';
            GenerateHeader(PAGE::PROFILE);
        ?>
        <div id=main class="profile">
            <?php require_once 'src/db.php';
            $dbconn = post_connect();
            $query = "SELECT * FROM me;";
            $results = pg_query($dbconn, $query) or die ('Query failed: '. pg_last_error());
            $raw_me = pg_query($query) or die ('Query failed: '. pg_last_error());
            $line = pg_fetch_array($raw_me, null, PGSQL_ASSOC);
            echo "<img id='photo' height='80px' src='../img/uploads/{$line['photo']}'/>
                    <form enctype='multipart/form-data' method='post' action='profile.php'>
                        Image: <input type='file' name='image'/>80x80<br>
                        <name>Nicholas Gillespie</name> <br>
                        <textarea id='project_desc' name='about'>{$line['about']}</textarea><br>
                        <name>more about me</name> <br>
                        <textarea id='project_desc' name='more'>{$line['more']}</textarea><br>
                        <name>strengths</name> <br>
                        <textarea id='project_desc' name='strengths'>{$line['strengths']}</textarea><br>
                        <name>skills</name> <br>
                        <textarea id='project_desc' name='skills'>{$line['skills']}</textarea><br><br>
                        <input type='submit' class='button' name='Update'/>
                    </form>";
            pg_free_result($raw_me);
            pg_close($dbconn);
            ?>
         </div>
        <?php include '../src/footer.php'?>
    </body>
</html>