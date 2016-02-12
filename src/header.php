<?php 
interface PAGE
{
    const LANDING = 0;
    const PROJECTS = 1;
    const THOUGHTS = 2;
    const CONTACT = 3;
}
function GenerateHeader($page){
    if (!strpos( $_SERVER['HTTP_USER_AGENT'], 'Chrome'))
    {
        echo "<div class='browser'>For best experience please try using a browser with latest web standards like <a href='http://chrome.google.com/'>Google Chrome</a>, as this site uses a lot of advanced features, not yet implemented in other browsers</div>";
    }
    echo "<header>
        <a  href=\"/\"><logo> <sup>&#60;&#47;</sup>";
        if ($page==PAGE::LANDING) echo '<strong>';
        echo "nic.odua.co</strong>&#62; <!--</nic.odua.co>--></logo></a>
        <menu>
            <ul>";
        if ($page==PAGE::PROJECTS) echo '<strong>';
        echo "<li><a href=\"projects.php\">projects</a></li></strong>";
        if ($page==PAGE::THOUGHTS) echo '<strong>';
        echo "<li><a href=\"thoughts.php\">thoughts</a></li></strong>";
        if ($page==PAGE::CONTACT) echo '<strong>';
        echo "<li><a href=\"contact.php\">contact</a></li></strong>
            </ul>
        </menu>
    </header>
    <hr>";
}

?>