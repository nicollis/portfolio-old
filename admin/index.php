<?php
require_once 'src/db.php';
//Need to check if session is already going, if so send to thoughts.php
session_start();
//Next need to check if user is submitted login info, if so check
$input_error = false;
if(isset($_POST['email'])){
    //serialize data and test login
    $email = substr(filter_var($_POST["email"], FILTER_VALIDATE_EMAIL),0,30);
    $password = substr(filter_var(
        $_POST["passcode"], FILTER_SANITIZE_STRING),0,50);
    if($email && $password){
        if(auth_check($email, $password)){
            //password was successful, start sesson and redirect
            $_SESSION['email'] = $email;
            $_SESSION['password'] = $password;
            header('Location: thoughts.php');
        }else{
            //password failed log attempt alert user
            session_destroy();
            $input_error = true;
        }
    }else{
        $input_error = true;
        session_destroy();
        //TODO log failed attempt alert user
    }
}
if($_SESSION != null){
    if(auth_check($_SESSION['email'], $_SESSION['password']))
        header('Location: thoughts.php');
}
?>
<!DOCTYPE html>
<html>
    <head>
        <link rel="icon" href="../FavIcon.ico"/>
        <title>Login to the Backend</title>
        <link href='https://fonts.googleapis.com/css?family=Roboto:400,400italic,700' rel='stylesheet' type='text/css'>
        <link rel="stylesheet" type="text/css" href="../css/style_reset.css">
        <link rel="stylesheet" type="text/css" href="css/design.css">
    </head>
    <body style="text-align: center;">
       <?php
            require_once 'src/header.php';
            GenerateHeader(PAGE::LANDING);
        ?>
        <div id=main class="login">
            <?php if($input_error){ echo "<name style='color:darkred'>Login Failed</name><br><br>";}?>
            <form id="login" method="post" action="<?php echo $_SERVER["PHP_SELF"];?>">
                <name>username:</name><input name="email" type="email" placeholder="johnsmith@mymail.com"/><br>
                <name>passcode: </name><input name="passcode" type="password" /><br>
                <input class="button" type="submit" name="submit" value="login"/>
            </form>
         </div>
        <?php include '../src/footer.php'?>
    </body>
</html>