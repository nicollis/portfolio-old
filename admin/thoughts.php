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
        $query = "UPDATE blog SET visible={$_POST['visible']} WHERE title='{$_POST['name']}';";
    else if($_POST['type']=="is_on_odua")
        $query = "UPDATE blog SET is_on_odua={$_POST['is_on_odua']} WHERE title='{$_POST['name']}';";
    else if($_POST['type']=="remove")
        $query = "DELETE FROM blog WHERE title='{$_POST['name']}';";
    pg_query($dbconn, $query) or die ('Query failed: '. pg_last_error());
    pg_close($dbconn);
}
?>
<!DOCTYPE html>
<html>
    <head>
        <link rel="icon" href="../FavIcon.ico"/>
        <title>Blog Editor</title>
        <link href='https://fonts.googleapis.com/css?family=Roboto:400,400italic,700' rel='stylesheet' type='text/css'>
        <link rel="stylesheet" type="text/css" href="../css/style_reset.css">
        <link rel="stylesheet" type="text/css" href="css/design.css">
        <script type="text/javascript" src="scripts/admin.js"></script>
    </head>
    <body style="text-align: center;">
       <?php
            require_once 'src/header.php';
            GenerateHeader(PAGE::THOUGHTS);
        ?>
        <div id=main class="thoughts">
            <new-post>
                <controls>
                    <a href="thought_editor.php"><input id="new_post" type="button" class="button" value="new post" /></a>
                    <input type=text placeholder="search entries" name="search" id="search" value='<?php if(isset($_GET["s"])){echo $_GET["s"];}?>' onkeyup="checkSubmit(event);">
                </controls>
            </new-post>
            <table id="blog-enteries">
                <tr>
                    <th>entery name</th>
                    <th>date posted</th>
                    <th>post tags</th>
                    <th>visible</th>
                    <th>on odua</th>
                    <th>delete</th>
                </tr>
                <?php require_once 'src/db.php';
                    //Connect to the DB and pull language data
                    $dbconn = post_connect();
                    if(!isset($_GET["s"]))
                        $query = 'SELECT title, date, array_to_json(tags), visible, is_on_odua FROM blog ORDER BY id ASC;';
                    else{
                        $search = substr(filter_var($_GET["s"], FILTER_SANITIZE_STRING),0,140);
                        $query = "SELECT title, date, entry, array_to_json(tags), visible, is_on_odua FROM blog WHERE '".$search."' % ANY(tags) OR lower(entry) LIKE '%".$search."%' OR lower(title) LIKE '%".$search."%';";
                    }
                    $raw_blog = pg_query($query) or die ('Query failed: '. pg_last_error());
                    //Make language group for each langugae
                    if(pg_num_rows($raw_blog)>=1){
                        while ($line = pg_fetch_array($raw_blog, null, PGSQL_ASSOC)) {
                                
                            echo "<tr>
                                    <td id=td_name><a title='edit entery' href='thought_editor.php?p={$line['title']}'>{$line['title']}</a></td>
                                    <td id=td_datestamp>{$line['date']}</td>
                                    <td>";
                            $tag_array = json_decode($line['array_to_json'], true);
                            foreach ($tag_array as $key=>$value){
                                if($key>0){ echo ", ";}
                                echo $value;
                            }
                            $checked = $line['visible']=="t" ? 'checked' : '';
                            $is_on_odua = $line['is_on_odua']=="t"? 'checked' : '';
                            echo "</td>
                                    <td><input type='checkbox' name='visible' onclick='updateVisiblity(\"thoughts.php\",\"{$line['title']}\", this);' {$checked}></td>
                                    <td><input type='checkbox' name='is_on_odua' onclick='updateOnOdua(\"thoughts.php\",\"{$line['title']}\", this);' {$is_on_odua}></td>
                                    <td><input type='checkbox' name='delete' onclick='deletePost(\"thoughts.php\",\"{$line['title']}\", this);'></td>
                                </tr>";
                        }//end $line while
                    }else{
                        echo "<tr>
                            <td id=td_name>No Entry's Found</td>
                             <td id=td_datestamp>00-00-0000</td>
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
                    window.location = "thoughts.php?s="+search;
                }
            }
        </script>
    </body>
</html>