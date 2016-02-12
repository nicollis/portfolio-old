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
if(isset($_POST['project']))
    if(strlen($_POST['project'])>0){
        $project = filter_var($_POST["project"], FILTER_SANITIZE_STRING);
        $language = filter_var($_POST['language'], FILTER_SANITIZE_STRING);
        $loc = filter_var($_POST['loc'], FILTER_SANITIZE_NUMBER_INT);
        $contribution = filter_var($_POST['contribution'], FILTER_SANITIZE_STRING);
        $technology = filter_var($_POST['technology'], FILTER_SANITIZE_STRING);
        $url = filter_var($_POST['url'], FILTER_VALIDATE_URL);
        $sourceurl = filter_var($_POST['sourceurl'], FILTER_VALIDATE_URL);
        $description = filter_var($_POST['description'], FILTER_SANITIZE_STRING);
        $featured_details = filter_var($_POST['featured_details'], FILTER_SANITIZE_STRING);
        $is_open = isset($_POST['opened']) ? 't' : 'f';
        $is_featured = isset($_POST['featured']) ? 't' : 'f';
        $is_visible = isset($_POST['visible']) ? 't' : 'f';
        $is_on_odua = isset($_POST['is_on_odua']) ? 't' : 'f';
        $is_on_android = isset($_POST['is_on_android']) ? 't' : 'f';
        $is_on_web = isset($_POST['is_on_web']) ? 't' : 'f';
        $is_on_ios = isset($_POST['is_on_ios']) ? 't' : 'f';
        $name = isset($_GET['p']) ? $_GET['p'] : $project;
        
        //Data grabed and sanatized, add to database
        $dbconn = post_connect();
        $query = "UPDATE projects SET name='$project', language='$language', loc='$loc', contribution='$contribution', technology='$technology', url='$url', sourceurl='$sourceurl', description='$description', opensourced='$is_open', featured='$is_featured', visible='$is_visible', featured_description='$featured_details', is_on_odua='$is_on_odua', is_on_android='$is_on_android', is_on_web='$is_on_web', is_on_ios='$is_on_ios' WHERE name='$name';
        INSERT INTO projects (name, language, loc, contribution, technology, url, sourceurl, description, opensourced, featured, visible, featured_description, is_on_odua, is_on_android, is_on_ios, is_on_web) SELECT '$project', '$language', '$loc', '$contribution', '$technology', '$url', '$sourceurl', '$description', '$is_open', '$is_featured', '$is_visible', '$featured_details', '$is_on_odua', '$is_on_android', '$is_on_ios', '$is_on_web'
        WHERE NOT EXISTS (SELECT 1 FROM projects WHERE name='$project');";
        $results = pg_query($dbconn, $query) or die ('Query failed: '. pg_last_error());
        
        //process and save the images to the proper directory
        $uploaddir = '../img/uploads/';
        if(strlen($_FILES['image']['name'])>3){
            $upload_project_photo = $uploaddir . basename($_FILES['image']['name']);
            move_uploaded_file($_FILES['image']['tmp_name'], $upload_project_photo);
            $upload_project_photo = basename($_FILES['image']['name']);
            $photo_query= "UPDATE projects SET photo='$upload_project_photo' WHERE name='$project';";
            pg_query($dbconn, $photo_query) or die ('Query failed: '. pg_last_error());
        }
        if(strlen($_FILES['featured_image']['name'])>3){
            $upload_featured_photo = $uploaddir . basename($_FILES['featured_image']['name']);
            move_uploaded_file($_FILES['featured_image']['tmp_name'], $upload_featured_photo);
            $upload_featured_photo = basename($_FILES['featured_image']['name']);
            $featured_query= "UPDATE projects SET featured_photo='$upload_featured_photo' WHERE name='$project';";
            pg_query($dbconn, $featured_query) or die ('Query failed: '. pg_last_error());
        }
    
        pg_close($dbconn);
        if($results){
            pg_free_result($results);
            header('Location: projects.php');
        }
        pg_free_result($results);
    }
?>
<!DOCTYPE html>
<html>
    <head>
        <link rel="icon" href="../FavIcon.ico"/>
        <title>Project Editor</title>
        <link href='https://fonts.googleapis.com/css?family=Roboto:400,400italic,700' rel='stylesheet' type='text/css'>
        <link rel="stylesheet" type="text/css" href="../css/style_reset.css">
        <link rel="stylesheet" type="text/css" href="css/design.css">
    </head>
    <body style="text-align: center;">
       <?php
            require_once 'src/header.php';
            GenerateHeader(PAGE::PROJECTS);
        ?>
        <div id=main class="post_editor">
            <?php
            $a_lang=array();
            $dbconn = post_connect();
            //Get the list of lanauges
            $query = "SELECT name, nickname FROM languages;";
            $raw_languages = pg_query($query) or die ('Query failed: ' . pg_last_error());
            while ($line = pg_fetch_array($raw_languages, null, PGSQL_ASSOC)) {
                $a_lang[$line['name']]=$line['nickname'];
            }
            pg_free_result($raw_languages);
            //See if we are updating a blog post, if so load in data  
            $u_project = '';
            $u_loc = '';
            $u_contribution = '';
            $u_technology = '';
            $u_url = '';
            $u_is_opened = '';
            $u_source_url = '';
            $u_description = '';
            $u_photo = '';
            $u_is_featured = '';
            $u_featured_photo = '';
            $u_is_visible = 'checked';
            $u_is_on_odua = '';
            $u_is_on_android = '';
            $u_is_on_web = '';
            $u_is_on_ios = '';
            $u_post = $_SERVER['PHP_SELF'];

            //If updating load data from db and set to varaibles above
            if(isset($_GET['p'])){
                $query = "SELECT name, loc, contribution, technology, description, url, opensourced, sourceurl, photo, featured_photo, featured, language, visible, featured_description, is_on_odua, is_on_android, is_on_web, is_on_ios
  FROM projects WHERE name='{$_GET['p']}'";
                $raw_entery = pg_query($query) or die ('Query failed: '. pg_last_error());
                $line = pg_fetch_array($raw_entery, null, PGSQL_ASSOC);
                $u_project = $line['name'];
                $u_loc = $line['loc'];
                $u_contribution = $line['contribution'];
                $u_technology = $line['technology'];
                $u_url = $line['url'];
                $u_source_url = $line['sourceurl'];
                $u_description = $line['description'];
                $u_photo = $line['photo'];
                $u_selected_language = $line['language'];
                $u_featured_description = $line['featured_description'];
                $u_featured_photo = $line['featured_photo'];
                $u_is_opened = $line['opensourced']=='t'? 'checked':'';
                $u_is_featured = $line['featured']=='t'? 'checked':'';
                $u_is_visible = $line['visible']=='t'? 'checked':'';
                $u_is_on_odua = $line['is_on_odua']=='t'? 'checked':'';
                $u_is_on_android = $line['is_on_android']=='t'? 'checked':'';
                $u_is_on_web = $line['is_on_web']=='t'? 'checked':'';
                $u_is_on_ios = $line['is_on_ios']=='t'? 'checked':'';
                pg_free_result($raw_entery);
                
                //set form post url!
                $u_post = $u_post."?p=".$u_project;
            }
            echo "<form enctype='multipart/form-data' method='post' action='{$u_post}'>
                Project: <input type='text' name='project' placeholder='Awesomeness in C' value='{$u_project}'/>
                Language: <select name='language'>";
            foreach($a_lang as $key=>$value){
                if($u_selected_language == $key)
                    echo "<option value='{$key}' selected>{$value}</option>";
                else
                    echo "<option value='{$key}'>{$value}</option>";
            }
            echo "
                            </select><br>
                LOC: <input type='number' name='loc' placeholder='-1' value='{$u_loc}'/>
                Contribution: <input type='text' name='contribution' placeholder='Sole Developer' value='{$u_contribution}'/><br>
                Technology: <input type='text' name='technology' placeholder='android, ios, jni' value='{$u_technology}'/>
                 URL: <input type='url' name='url' placeholder='myapp.com' value='{$u_url}'/><br>
                Open Sourced: <input class='checkbox' type='checkbox' name='opened' {$u_is_opened}/>
                Source URL: <input type='url' name='sourceurl' placeholder='code.myapp.com' value='{$u_source_url}'/><br>
                Android: <input class='checkbox' type='checkbox' name='is_on_android' {$u_is_on_android}/>
                Web: <input class='checkbox' type='checkbox' name='is_on_web' {$u_is_on_web}/>
                iOS: <input class='checkbox' type='checkbox' name='is_on_ios' {$u_is_on_ios}/><br>
                Description: <br>
                <textarea id='project_desc' name='description'>{$u_description}</textarea><br>
                Image: <input type='file' name='image'/>250x200<br><img width='250px' height='200px' src='../img/uploads/{$u_photo}' /><br>
                Featured: <input class='checkbox' type='checkbox' name='featured' {$u_is_featured}/>Details: <select name='featured_details'>";
            if ($u_featured_description == 'design & development')
                echo "<option value='design & development' selected>design & development</option>";
            else
                echo "<option value='design & development'>design & development</option>";
            if ($u_featured_description == 'design')
                echo "<option value='design' selected>design</option>";
            else
                echo "<option value='design'>design</option>";
            if ($u_featured_description == 'development')
                echo "<option value='development' selected>development</option>";
            else
                echo "<option value='development'>development</option>";
            echo "
                            </select>
                Featured Image: <input type='file' name='featured_image'/>250x400<br>
                <img width='250px' height='400px' src='../img/uploads/{$u_featured_photo}' /><br>
                Visible: <input type='checkbox' name='visible' class='checkbox' {$u_is_visible}/>
                On Odua: <input type='checkbox' name='is_on_odua' class='checkbox' {$u_is_on_odua}/><br>
                <input type='submit' class='button' name='Post'/>
            </form>";
                pg_close($dbconn);
            ?>
         </div>
        <?php include '../src/footer.php'?>
    </body>
</html>