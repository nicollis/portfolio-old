<?php
if(intval($_SERVER['CONTENT_LENGTH'])>0 && count($_POST)===0){
    throw new Exception('PHP discarded POST data because of request exceeding post_max_size.');
}
require_once 'src/db.php';
session_start();
//make sure user is allowed
if($_SESSION != null){
    if(!auth_check($_SESSION['email'], $_SESSION['password']))
        { session_destroy(); header('Location: index.php');}
}else{ session_destroy(); header('Location: index.php');}
//User is allowed!
//---------
//Check to see if user posted data
if(isset($_POST['were'])){
    if(strlen($_POST['were'])>=0){
        $were = $_POST["were"];
        $hire = $_POST["hire"];
        $more_about = $_POST["more_about"];
        $open_source = $_POST["open_source"];
        //Query Slideshow images and upload/add any new ones
        $slideshow = to_pg_array(setup_slideshow());
        //Hire fields and imgae upload logic
        $hire_tag = filter_var($_POST["hire_tag"], FILTER_SANITIZE_STRING);
        //Check and process skill images/tags
        $skills = pull_skills();
        $skills['skill_1'] = json_encode(generate_skill_array(
            'skill_title_1', 'skill_details_1', 'skill_1', $skills['skill_1']));
        $skills['skill_2'] = json_encode(generate_skill_array(
            'skill_title_2', 'skill_details_2', 'skill_2', $skills['skill_2']));
        $skills['skill_3'] = json_encode(generate_skill_array(
            'skill_title_3', 'skill_details_3', 'skill_3', $skills['skill_3']));
        $skills = json_encode($skills);
    
        $hire_details = $_POST["hire_details"];
        
        //Open connection to the DB and upload the new entry
        $dbconn = post_connect();
        $query = "INSERT INTO odua (about, hire_short, more_about, open_source, slideshow, hire_tag, hire_skills, hire_details) VALUES ('$were', '$hire', '$more_about', '$open_source', '$slideshow', '$hire_tag', '$skills', '$hire_details');";
        $results = pg_query($dbconn, $query) or die ('Query failed: '. pg_last_error());
        pg_close($dbconn);
        if($results){
            pg_free_result($results);
            header('Location: odua.php');
        }
        pg_free_result($results);
    }// END if(strlen($_POST['were'])>=0)
}// END if(isset($_POST['were']))
//POST could be to delete a slideshow image
else if(isset($_POST['type'])){
    if($_POST['type']=="delete_image"){
        $dbconn = post_connect();
        $query = "SELECT id, array_to_json(slideshow) FROM odua ORDER BY id DESC LIMIT 1;";
        $raw_entery = pg_query($query) or die ('Query failed: '. pg_last_error());
        $line = pg_fetch_array($raw_entery, null, PGSQL_ASSOC);
        $photo_array = json_decode($line['array_to_json'], true);
        $slideshow_images = array();
        $count = 0;
        foreach ($photo_array as $key=>$value){
            if(strcmp($value, $_POST['image']) == 0) {continue;}
            $slideshow_images[$count] = $value;
            $count = $count+1;
        }
        $id = $line['id'];
        $slideshow = to_pg_array($slideshow_images);
        $update_query = "UPDATE odua SET slideshow='$slideshow' WHERE id=$id;";
        $results = pg_query($dbconn, $update_query) or die ('Query failed: '. pg_last_error());
        if($results){
            pg_free_result($results);
            echo $_POST['image'] . " removed.";
        }
        pg_close($dbconn);
        pg_free_result($raw_entery);
    }//END if($_POST['type']=="delete_image")
}//END if(isset($_POST['type']))


/*
    UTILIY FUNCTIONS FOR THIS FORM
*/

function setup_slideshow(){
    $slideshow_images = array();
    $count = 0;
    //Loop though $_FILES and find slideshow images
    foreach($_FILES as $key=>$value){
        if((strpos($key,'slideshow') !== FALSE) && strlen($value['name'])>3){
            upload_photo($value);
            $slideshow_images[$count] = basename($value['name']);
            $count = $count+1;
        }
    }
    //Query DB for old images currently in the slideshow
    $dbconn = post_connect();
    $query = "SELECT array_to_json(slideshow) FROM odua ORDER BY id DESC LIMIT 1;";
    $raw_entery = pg_query($query) or die ('Query failed: '. pg_last_error());
    $line = pg_fetch_array($raw_entery, null, PGSQL_ASSOC);
    $photo_array = json_decode($line['array_to_json'], true);
    foreach ($photo_array as $key=>$value){
        $slideshow_images[$count] = $value;
        $count = $count+1;
    }
    pg_close($dbconn);
    pg_free_result($raw_entery);
    return $slideshow_images;
}

function upload_photo($file){
    $uploaddir = '../img/uploads/odua.co/';
    $full_file_name = $uploaddir . basename($file['name']);
    move_uploaded_file($file['tmp_name'], $full_file_name);
    return $full_file_name;
}

function generate_skill_array($title_post, $detail_post, $file_post, $old_skill){
    $skill = array();
    $skill['title'] = filter_var($_POST[$title_post], FILTER_SANITIZE_STRING);
    $skill['detail'] = filter_var($_POST[$detail_post], FILTER_SANITIZE_STRING);
    //if basename is nil we need to use the old photo
    $skill['photo'] = basename($_FILES[$file_post]['name']);
    if(strlen($skill['photo'])<3){//if no new photo use the old one
        $old_skill = json_decode($old_skill, true);
        $skill['photo'] = $old_skill['photo'];
    }else{//Save the new photo to img dir
        upload_photo($_FILES[$file_post]);
    }
    return $skill;
}
function pull_skills(){
    $dbconn = post_connect();
    $query = "SELECT hire_skills FROM odua ORDER BY id DESC LIMIT 1;";
    $raw_entery = pg_query($query) or die ('Query failed: '. pg_last_error());
    $line = pg_fetch_array($raw_entery, null, PGSQL_ASSOC);
    $skills_array = json_decode($line['hire_skills'], true);
    pg_close($dbconn);
    pg_free_result($raw_entery);
    return $skills_array;
}
?>