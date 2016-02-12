<?php
require_once 'src/db.php';
session_start();
//make sure user is allowed
if($_SESSION != null){
    if(!auth_check($_SESSION['email'], $_SESSION['password']))
        { session_destroy(); header('Location: index.php');}
}else{ session_destroy(); header('Location: index.php');}
//User is allowed!
//Check to see if there is any post data!
if(isset($_POST['type'])){
    $dbconn = post_connect();
    $query = "";
    if($_POST['type']=="visible")
        $query = "UPDATE projects SET visible={$_POST['visible']} WHERE name='{$_POST['name']}';";
    else if($_POST['type']=="remove")
        $query = "DELETE FROM projects WHERE name='{$_POST['name']}';";
    else if($_POST['type']=="featured")
        $query = "UPDATE projects SET featured={$_POST['featured']} WHERE name='{$_POST['name']}';";
    else if($_POST['type']=="is_on_odua")
        $query = "UPDATE projects SET is_on_odua={$_POST['is_on_odua']} WHERE name='{$_POST['name']}';";
    pg_query($dbconn, $query) or die ('Query failed: '. pg_last_error());
    pg_close($dbconn);
}
?>
<!DOCTYPE html>
<html>
    <head>
        <link rel="icon" href="../FavIcon.ico"/>
        <title>Projects</title>
        <link href='https://fonts.googleapis.com/css?family=Roboto:400,400italic,700' rel='stylesheet' type='text/css'>
        <link rel="stylesheet" type="text/css" href="../css/style_reset.css">
        <link rel="stylesheet" type="text/css" href="css/design.css">
        <script type="text/javascript" src="scripts/admin.js"></script>
    </head>
    <body style="text-align: center;">
       <?php
            require_once 'src/header.php';
            GenerateHeader(PAGE::PROJECTS);
        ?>
        <div id=main class="thoughts">
            <new-post>
                <controls>
                    <a href="project_editor.php"><input id="new_post" type="button" class="button" value="new project" /></a>
                    <input type=text placeholder="search projects" name="search" id="search" value='<?php if(isset($_GET["s"])){echo $_GET["s"];}?>' onkeyup="checkSubmit(event);">
                </controls>
            </new-post>
            <table id="blog-enteries">
                <tr>
                    <th>project</th>
                    <th>language</th>
                    <th>source</th>
                    <th>featured</th>
                    <th>visible</th>
                    <th>on odua</th>
                    <th>delete</th>
                </tr>
                <?php require_once 'src/db.php';
                    //Connect to the DB and pull language data
                    $dbconn = post_connect();
                    if(!isset($_GET["s"]))
                        $query = 'SELECT name, language, opensourced, visible, is_on_odua, featured FROM projects ORDER BY featured DESC, language ASC;';
                    else{
                        $search = substr(filter_var($_GET["s"], FILTER_SANITIZE_STRING),0,140);
                        $query = "SELECT name, language, opensourced, visible, is_on_odua, featured FROM projects WHERE lower(name) LIKE '%".$search."%' OR lower(language) LIKE '%".$search."%' ORDER BY featured DESC, language ASC;";
                    }
                    $raw_blog = pg_query($query) or die ('Query failed: '. pg_last_error());
                    //Make language group for each langugae
                    if(pg_num_rows($raw_blog)>=1){
                        while ($line = pg_fetch_array($raw_blog, null, PGSQL_ASSOC)) {
                                
                            echo "<tr>
                                    <td id=td_name><a title='edit entery' href='project_editor.php?p={$line['name']}'>{$line['name']}</a></td>
                                    <td id=td_datestamp>{$line['language']}</td>
                                    <td>";
                            echo $line['opensourced']=="t" ? 'opened' : 'closed';
                            $featured = $line['featured']=="t" ? 'checked' : '';
                            $checked = $line['visible']=="t" ? 'checked' : '';
                            $is_on_odua = $line['is_on_odua']=="t" ? 'checked' : '';
                            echo "</td>
                                    <td><input type='checkbox' name='featured' onclick='updatedFeatured(\"projects.php\", \"{$line['name']}\", this);' {$featured}></td>
                                    <td><input type='checkbox' name='visible' onclick='updateVisiblity(\"projects.php\", \"{$line['name']}\", this);' {$checked}></td>
                                    <td><input type='checkbox' name='is_on_odua' onclick='updateOnOdua(\"projects.php\",\"{$line['name']}\", this);' {$is_on_odua}></td>
                                    <td><input type='checkbox' name='delete' onclick='deletePost(\"projects.php\", \"{$line['name']}\", this);'></td>
                                </tr>";
                        }//end $line while
                    }else{
                        echo "<tr>
                            <td id=td_name>No Entry's Found</td>
                             <td id=td_datestamp></td>
                             <td></td>
                             <td></td>
                             <td></td>
                             <td></td>
                            </tr>";              
                    }
                    // Free resultset and close DB
                    pg_free_result($raw_blog);
                    pg_close($dbconn);
                ?>
            </table>
         </div>
        <?php include '../src/footer.php'?>
        <script type="text/javascript">
            function checkSubmit(evt){
                if (evt.which == 13){
                    var search = document.getElementById('search').value;
                    window.location = "projects.php?s="+search;
                }
            }
        </script>
    </body>
</html>