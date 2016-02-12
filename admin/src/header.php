<?php 
interface PAGE
{
    const LANDING = 0;
    const PROJECTS = 1;
    const THOUGHTS = 2;
    const PROFILE = 3;
    const ODUA = 4;
    const LOGOUT = 5;
}
function GenerateHeader($page){
    echo "<header>
        <a  href=\"http://nic.odua.co\"><logo> <sup>&#60;&#47;</sup>";
        if ($page==PAGE::LANDING) echo '<strong>';
        echo "nic.odua.co</strong>&#62; <!--</nic.odua.co>--></logo></a>
        <menu>
            <ul>";
        if ($page==PAGE::PROJECTS) echo '<strong>';
        echo "<li><a href=\"projects.php\">projects</a></li></strong>";
        if ($page==PAGE::THOUGHTS) echo '<strong>';
        echo "<li><a href=\"thoughts.php\">thoughts</a></li></strong>";
        if ($page==PAGE::PROFILE) echo '<strong>';
        echo "<li><a href=\"profile.php\">profile</a></li></strong>";
    if ($page==PAGE::ODUA) echo '<strong>';
        echo "<li><a href=\"odua.php\">odua</a></li></strong>";
        if ($page==PAGE::LOGOUT) echo '<strong>';
        echo "<li><a href=\"logout.php\">logout</a></li></strong>
            </ul>
        </menu>
    </header>
    <hr>";
}

?>