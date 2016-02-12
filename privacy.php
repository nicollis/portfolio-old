
<!DOCTYPE html>
<html>
    <head>
        <?php include_once 'src/style.php'?>
        <title>Privacy Policy</title>
        <script type="text/javascript" src="scripts/animations.js"></script>
    </head>
    <body>
        <?php 
            require_once 'src/header.php';
            GenerateHeader(PAGE::CONTACT);
        ?>
        <div id=privacy>
            <iframe seamless src="https://www.iubenda.com/privacy-policy/280103"></iframe>
        </div>
        <?php include 'src/footer.php'?>
    </body>
</html>