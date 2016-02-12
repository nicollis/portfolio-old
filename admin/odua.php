<?php
require_once 'src/db.php';
session_start();
//make sure user is allowed
if($_SESSION != null){
    if(!auth_check($_SESSION['email'], $_SESSION['password']))
        { session_destroy(); header('Location: index.php');}
}else{ session_destroy(); header('Location: index.php');}
//User is allowed!
$imagedir = "../img/uploads/odua.co/";
$dbconn = post_connect();
$query = "SELECT about, hire_short, hire_tag, hire_details, more_about, open_source, array_to_json(slideshow), hire_skills FROM odua ORDER BY id DESC LIMIT 1;";
$raw_entery = pg_query($query) or die ('Query failed: '. pg_last_error());
$data = pg_fetch_array($raw_entery, null, PGSQL_ASSOC);
?>
<!DOCTYPE html>
<html>
    <head>
        <link rel="icon" href="../FavIcon.ico"/>
        <title>Odua Editor</title>
        <link href='https://fonts.googleapis.com/css?family=Roboto:400,400italic,700' rel='stylesheet' type='text/css'>
        <link rel="stylesheet" type="text/css" href="../css/style_reset.css">
        <link rel="stylesheet" type="text/css" href="css/design.css">
        <script type="text/javascript" src="ckeditor/ckeditor.js"></script>
        <script type="text/javascript" src="scripts/admin.js"></script>
    </head>
    <body style="text-align: center;" id=odua_form_body>
       <?php
            require_once 'src/header.php';
            GenerateHeader(PAGE::ODUA);
        ?>
        <div id=main class="profile">
            <form class='odua_form' enctype= "multipart/form-data" method='post' action='odua_post.php'>
                <h1>General Info</h1>
                <hr>
                <div class=textarea_block>
                    <div class=title>We're:</div>
                    <textarea rows=10 cols=30 name='were'><?php echo $data['about'];?></textarea>
                </div>
                <div class=textarea_block>
                    <div class=title>Hire:</div>
                    <textarea rows=10 cols=30 name='hire'><?php echo $data['hire_short'];?></textarea>
                </div>
                <br>
                <div class=textarea_block>
                    <div class=title>More About:</div>
                    <textarea rows=10 cols=30 name='more_about'><?php echo $data['more_about'];?></textarea>
                </div>
                <div class=textarea_block>
                    <div class=title>Open Source:</div>
                    <textarea rows=10 cols=30 name='open_source'><?php echo $data['open_source'];?></textarea>
                </div>
                <br><br>
                <h1>Slideshow</h1>
                <hr>
                <?php
                    $images = json_decode($data['array_to_json'], true);
                    foreach($images as $key=>$image){
                        $full_image = $imagedir . $image;
                        echo "<div class='slideshow_row' id='$image'>
                                <img src='$full_image' height=50 />
                                <button type='button' onclick='deleteImage(this, true)'>Delete</button>
                            </div>";
                    }
                ?>
                <div class='slideshow_row'>
                    <input type='file' name='slideshow_1'/>
                    <button type='button' onclick='deleteImage(this)'>Delete</button>
                </div>
                <div id="slideshow_row_placeholder"></div>
                <button id='add_photo' type="button" onclick="addImageRow(this)">Add Photo</button>
                <br><br>
                <h1>Hire</h1>
                <hr>
                <input name='hire_tag' placeholder="Hire Tag Line" style="width:300px;"
                       value="<?php echo $data['hire_tag'];?>" />
                <ul class=three-skills>
                    <?php
                        $skills = json_decode($data['hire_skills'],true);
                        foreach($skills as $key=>$value){
                            $skills[$key] = json_decode($skills[$key], true);
                        }
                    ?>
                    <li class=skill>
                        <img class="skill_img" src="<?php echo $imagedir.$skills['skill_1']['photo'];?>"/>
                        <input name='skill_1' type="file" />
                        <input name='skill_title_1' type=text value="<?php echo $skills['skill_1']['title'];?>" />
                        <textarea name='skill_details_1'><?php echo $skills['skill_1']['detail'];?></textarea>
                    </li>
                    <li class=skill>
                        <img class="skill_img" src="<?php echo $imagedir.$skills['skill_2']['photo'];?>"/>
                        <input name='skill_2' type="file" />
                        <input name='skill_title_2' type=text value="<?php echo $skills['skill_2']['title'];?>" />
                        <textarea name='skill_details_2'><?php echo $skills['skill_2']['detail'];?></textarea>
                    </li>
                    <li class=skill>
                        <img class="skill_img" src="<?php echo $imagedir.$skills['skill_3']['photo'];?>"/>
                        <input name='skill_3' type="file" />
                        <input name='skill_title_3' type=text value="<?php echo $skills['skill_3']['title'];?>" />
                        <textarea name='skill_details_3'><?php echo $skills['skill_3']['detail'];?></textarea>
                    </li>
                </ul>
                <textarea class='ckeditor' name='hire_details'>
                    <?php echo $data['hire_details'];?>
                </textarea>
                <hr>
                <input type='submit' class='button' name='Post'/>
            </form>
         </div>
        <?php include '../src/footer.php'?>
    </body>
</html>
<?php 
    pg_free_result($raw_entery);
    pg_close($dbconn);
?>