<?php require_once 'src/db.php';
//connect to DB and pull data needed for tiles
$dbconn = post_connect();
//Query database to pull in language data
$query = 'SELECT more, strengths, skills, photo FROM me LIMIT 1;';
$result = pg_query($query) or die ('Query failed: '. pg_last_error());
$row = pg_fetch_row($result);
//Set the user data from the row retrived
$mMore = $row[0];
$mStrengths = $row[1];
$mSkills = $row[2];
$mPhoto = $row[3];
// Free resultset and close DB
pg_free_result($result);
pg_close($dbconn);
?>
<!DOCTYPE html>
<html>
    <head>
        <?php include_once 'src/style.php'?>
        <title>How to Reach Nicholas</title>
    </head>
    <body>
       <?php
            include 'src/contact_svgs.php';
            require_once 'src/header.php';
            GenerateHeader(PAGE::CONTACT);
        ?>
        <div id=main>
            <features>
                <blocked>
                    <svg id="strengths_hex">
                        <use xlink:href="#hex"/>
                        <g transform="translate(54.999991,55.401955)">
                            <use class="center_origin"id="strengths_icon_id" xlink:href="#strength_icon" style="-webkit-animation: SHAKEY 1s 500ms 1; animation: SHAKEY 1s 500ms 1;"/>
                        </g>
                    </svg>
                    <name>strengths</name>
                    <br>
                    <content>
                    <?php echo $mStrengths; ?>
                    </content>
                </blocked>
                <blocked id="skills_block">
                    <svg id="skills_hex">
                        <use xlink:href="#hex"/>
                      <g transform="translate(42,66)">
                        <use class="large_gear" id="large_gear" xlink:href="#skill_gear_large" style="-webkit-animation: ROll 2s 1.5s 1; animation: ROll 2s 1.5s 1;"/>
                      </g>
                      <g transform="translate(76,40)">
                        <use class="small_gear" id="small_gear" xlink:href="#skill_gear_small" style="-webkit-animation: ROllREVERSE 2s 1.5s 1; animation: ROllREVERSE 2s 1.5s 1;"/>
                      </g>
                    </svg>
                    <name>skills</name>
                    <br>
                    <content>
                    <?php echo $mSkills; ?>
                    </content>
                </blocked>
                <blocked id="hex_block">
                    <a title="Resume" href="resume.php"><svg id="resume_hex" class="hex_group">
                        <use xlink:href="#hex" transform="rotate(90,55,55)" />
                        <use xlink:href="#resume_icon"/>
                    </svg></a>
                    <a title="LinkedIn" href="https://www.linkedin.com/pub/nicholas-gillespie/4a/203/764"><svg id="linken_hex" class="hex_group">
                        <use xlink:href="#hex" transform="rotate(90,55,55)"/>
                        <image xlink:href="img/linkedin.png" height="70px" width="70px" x=20 y=20 />
                    </svg></a>
                   <a title="Mail" href="mailto:&#104;&#101;&#108;&#108;&#111;&#064;&#110;&#105;&#099;&#103;&#046;&#105;&#111;"> <svg id="mail_hex" class="hex_group">
                        <use xlink:href="#hex" transform="rotate(90,55,55)"/>
                        <use xlink:href="#mail_icon"/>
                    </svg></a>
                </blocked>
            </features>
            <info>
                <blocked>
                    <?php echo "<img id='photo' alt='profile photo' src='img/uploads/{$mPhoto}'/>"?>
                    <br>
                    <name>more about me</name>
                    <br>
                    <content>
                    <?php echo $mMore; ?>
                    </content>
                </blocked>
                <?php
                    function spamcheck($field) {
                      // Sanitize e-mail address
                      $field=filter_var($field, FILTER_SANITIZE_EMAIL);
                      // Validate e-mail address
                      if(filter_var($field, FILTER_VALIDATE_EMAIL)) {
                        return TRUE;
                      } else {
                        return FALSE;
                      }
                    }
                ?>
                <p id="form_title">contact</p>
                <?php
                // display form if user has not clicked submit
                if (!isset($_POST["email"])) {
                  ?>
                <form id="contact_block" method="post" action="<?php echo $_SERVER["PHP_SELF"];?>">
                    <name>name</name><input type="text" name="name" required placeholder="John Smith"><br>
                    <name>email</name><input type="email" name="email" required placeholder="jsmith@mymail.com"><br>
                    <name>message</name><textarea required name="message" placeholder="I would like to talk to you!"></textarea><br>
                    <input class="button" type="submit" value="send" />
                </form>
                <?php 
                    } else {  // the user has submitted the form
                      // Check if the "from" input field is filled out
                      if (isset($_POST["email"])) {
                        // Check if "from" email address is valid
                        $mailcheck = spamcheck($_POST["email"]);
                        if ($mailcheck==FALSE) {
                          echo "Invalid input";
                        } else {
                          $name = $_POST["name"];
                          $from = $_POST["email"]; // sender
                          $message = "Message From: ".$name."\n\n";
                          $message = $message . $_POST["message"];
                          // message lines should not exceed 70 characters (PHP rule), so wrap it
                          $message = wordwrap($message, 70);
                          // send mail
                          mail("contact@odua.co","Contact Form Message",$message,"From: $from\n");
                          echo "<br><br><br>Thank you, I'll get to your comment as soon as I can!";
                        }
                      }
                    }
                ?><br>
                
            </info>
            
        </div>
        <?php include 'src/footer.php'?>
        <script type="text/javascript" src="scripts/contact.js"></script>
    </body>
</html>