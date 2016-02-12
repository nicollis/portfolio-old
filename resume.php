<!DOCTYPE html>
<html>
    <head>
        <?php include_once 'src/style.php'?>
        <title>Resume</title>
        <script type="text/javascript" src="scripts/animations.js"></script>
    </head>
    <body>
        <?php 
            require_once 'src/header.php';
            GenerateHeader(PAGE::CONTACT);
        ?>
        <div >
            <?php echo file_get_contents("src/ProgrammingResume.html"); ?>
        </div>
        <?php include 'src/footer.php'?>
    </body>
</html>