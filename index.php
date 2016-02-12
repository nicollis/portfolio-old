<?php require_once 'src/db.php';
//connect to DB and pull data needed for tiles
$dbconn = post_connect();
//Query database to pull in language data
$query = 'SELECT name, about, photo FROM "me" LIMIT 1;';
$result = pg_query($query) or die ('Query failed: '. pg_last_error());
$row = pg_fetch_row($result);
//Set the user data from the row retrived
$mName = $row[0];
$mAbout = $row[1];
$mPhoto = $row[2];
// Free resultset and close DB
pg_free_result($result);
pg_close($dbconn);
?>
<!DOCTYPE html>
<html>
    <head>
        <?php include_once 'src/style.php'?>
        <title>Nicholas Gillespie</title>
        <script type="text/javascript" src="scripts/animations.js"></script>
    </head>
    <body>
        <?php 
            require_once 'src/header.php';
            GenerateHeader(PAGE::LANDING);
        ?>
        <div id=main>
            <article>
                <?php echo "<img id=\"photo\" alt=\"profile photo\" src=\"img/uploads/{$mPhoto}\"/>";?>
                <br>
                <name><?php echo $mName; ?></name>
                <br>
                <?php echo $mAbout; ?>
            </article>
            <badges>
                <?php include 'src/languages.php' ?>
            </badges>
        </div>
        <hr>
        <div id="featured">
            <?php require_once 'src/db.php';
                //Connect to the DB and pull language data
                $dbconn = post_connect();
                $query = 'SELECT name, language, featured_description, featured_photo FROM projects WHERE featured = true LIMIT  3;';
                $raw_featured = pg_query($query) or die ('Query failed: '. pg_last_error());
                //Make language group for each langugae
                while ($line = pg_fetch_array($raw_featured, null, PGSQL_ASSOC)) {
                    $f_name = $line["name"];
                    $f_language = $line["language"];
                    $f_desc = $line["featured_description"];
                    $f_photo = $line["featured_photo"];
                    //get language color
                    $color_query = 'SELECT color FROM languages WHERE name = \''.$f_language."' LIMIT 1;";
                    $raw_color = pg_query($color_query) or die ('Query failed: '. pg_last_error());
                    $color_row = pg_fetch_row($raw_color);
                    $f_language = $color_row[0];
                    echo "<a class='project' href='projects.php#{$f_name}'>
                            <div class='project_title'>{$f_name}</div>
                            <div class='project_type'>{$f_desc}</div>
                            <hr class='project_break' style='background-color: {$f_language};'>
                            <img width='250px' height='400px' class='project_image' src='img/uploads/{$f_photo}'/>
                        </a>";
                    pg_free_result($raw_color);
                }
                // Free resultset and close DB
                pg_free_result($raw_featured);
                pg_close($dbconn);
            ?>
        </div>
        <?php include 'src/footer.php'?>
    </body>
</html>