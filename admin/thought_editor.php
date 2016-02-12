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
if(isset($_POST['title']))
    if(strlen($_POST['title'])>0){
        $title = filter_var($_POST["title"], FILTER_SANITIZE_STRING);
        $tags = to_pg_array(explode(" ", filter_var($_POST['tags'], FILTER_SANITIZE_STRING)));
        $visible = isset($_POST['visible']) ? 't' : 'f';
        $is_on_odua = isset($_POST['is_on_odua']) ? 't' : 'f';
        $content = $_POST['content'];
        $name = isset($_GET['p']) ? $_GET['p'] : $title;
        //Data grabed and sanatized, add to database
        $dbconn = post_connect();
        $query = "UPDATE blog SET title='$title',entry='$content',tags='$tags',visible='$visible',is_on_odua='$is_on_odua' WHERE title='$name';
        INSERT INTO blog (title, entry, tags, visible, is_on_odua) SELECT '$title', '$content', '$tags', '$visible', '$is_on_odua'
        WHERE NOT EXISTS (SELECT 1 FROM blog WHERE title='$title');";
        $results = pg_query($dbconn, $query) or die ('Query failed: '. pg_last_error());
        pg_close($dbconn);
        if($results){
            pg_free_result($results);
            header('Location: thoughts.php');
        }
        pg_free_result($results);
    }
?>
<!DOCTYPE html>
<html>
    <head>
        <link rel="icon" href="../FavIcon.ico"/>
        <title>Post Editor</title>
        <link href='https://fonts.googleapis.com/css?family=Roboto:400,400italic,700' rel='stylesheet' type='text/css'>
        <link rel="stylesheet" type="text/css" href="../css/style_reset.css">
        <link rel="stylesheet" type="text/css" href="css/design.css">
        <script type="text/javascript" src="ckeditor/ckeditor.js"></script>
    </head>
    <body style="text-align: center;">
       <?php
            require_once 'src/header.php';
            GenerateHeader(PAGE::THOUGHTS);
        ?>
        <div id=main class="post_editor">
            <?php
            //See if we are updating a blog post, if so load in data  
            $u_title = '';
            $u_tags = '';
            $u_content = '';
            $u_visible = 'checked';
            $u_is_on_odua = '';
            $u_post = $_SERVER['PHP_SELF'];

            //If updating load data from db and set to varaibles above
            if(isset($_GET['p'])){
                $dbconn = post_connect();
                $query = "SELECT title, entry, array_to_json(tags), visible, is_on_odua FROM blog WHERE title='{$_GET['p']}'";
                $raw_entery = pg_query($query) or die ('Query failed: '. pg_last_error());
                $line = pg_fetch_array($raw_entery, null, PGSQL_ASSOC);
                $u_title = $line['title'];
                $u_content = $line['entry'];
                $u_visible = $line['visible']=='t'? 'checked':'';
                $u_is_on_odua = $line['is_on_odua']=='t'? 'checked':'';
                $tag_array = json_decode($line['array_to_json'], true);
                foreach ($tag_array as $key=>$value){
                    if(strlen($u_tags)>2){$u_tags = $u_tags." ";}
                    $u_tags = $u_tags.$value;
                }
                pg_free_result($raw_entery);
                pg_close($dbconn);
                //set form post url!
                $u_post = $u_post."?p=".$u_title;
            }
            echo "<form method='post' action='{$u_post}'>
                Title<input type='text' name='title' placeholder='my take on being me' value='{$u_title}'/><br>
                Tags<input type='text' name='tags' placeholder='first c# awesome' value='{$u_tags}'/><br>
                Visible<input class='checkbox' type=checkbox name='visible' {$u_visible} /> On Odua<input class='checkbox' type=checkbox name='is_on_odua' {$u_is_on_odua} /><br>
                <textarea class='ckeditor' name='content'>{$u_content}</textarea>
                <input class='button' type='submit' name='submit' value='post'/>
            </form>";
            ?>
         </div>
        <?php include '../src/footer.php'?>
    </body>
</html>