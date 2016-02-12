<!DOCTYPE html>
<html>
    <head>
        <?php include_once 'src/style.php'?>
        <title>Projects by Nicholas</title>
        <script type="text/javascript" src="scripts/animations.js"></script>
    </head>
    <body>
       <?php
            include 'src/contact_svgs.php';
            require_once 'src/header.php';
            GenerateHeader(PAGE::PROJECTS);
        ?>
        <div id=main class="flex">
            <projects>
                <?php require_once 'src/db.php';
                    //Connect to the DB and pull language data
                    $dbconn = post_connect();
                    $query = 'SELECT name, color FROM languages ORDER BY id ASC;';
                    $raw_lanugage = pg_query($query) or die ('Query failed: '. pg_last_error());
                    //Make language group for each langugae
                    while ($line = pg_fetch_array($raw_lanugage, null, PGSQL_ASSOC)) {
                        $l_name = $line["name"];
                        $l_color = $line["color"];
                        
                        echo "<div class='language' id='{$l_name}' class='project_collection'>
                            <lang_title>
                                <sup>&#60;&#47;</sup><span style='color:{$l_color};'>{$l_name}</span>&#62;
                            </lang_title>
                            <hr style='background:{$l_color};'>";
                        //Query all project in this language and add them
                        $projects_query = 'SELECT name, loc, contribution, technology, description, url, opensourced, sourceurl, photo FROM projects WHERE visible = true and language = \''.$l_name.'\';';
                        $raw_projects = pg_query($projects_query) or die ('Query failed: '. pg_last_error());
                        $is_projects = false;
                        while($line_project = pg_fetch_array($raw_projects, null, PGSQL_ASSOC)){
                            $is_projects = true;
                            $p_name = $line_project["name"];
                            $p_loc = $line_project["loc"] < 1 ? "Under Development" : $line_project["loc"];
                            $p_cont = $line_project["contribution"];
                            $p_tech = $line_project["technology"];
                            $p_desc = $line_project["description"];
                            $p_url = $line_project["url"];
                            $p_source_url = $line_project["sourceurl"];
                            $p_open = $line_project["opensourced"] == 'f' ? "<span class='s_closed'>closed</span>" : "<a href='{$p_source_url}' class='s_open'>opened</a>";
                            $p_photo = $line_project["photo"];
                            
                            echo "<project id='{$p_name}'>
                                <img src='img/uploads/{$p_photo}' class='project-image'>
                                <project-details>
                                    <name>{$p_name}</name>
                                    <p><t1>LOC: </t1>{$p_loc}</p>
                                    <p><t1>Contribution: </t1>{$p_cont}</p>
                                    <p><t1>Technology: </t1>{$p_tech}</p>
                                    <p><t1>Description: </t1>{$p_desc}</p>
                                    <p><t1>More info:</t1>Visit the dedicated site @ <a href='{$p_url}'>{$p_url}</a></p>
                                    <p><t1>Source: </t1>{$p_open}</p>
                                </project-details>
                            </project><br>";
                        }//end $line_project while
                        if(!$is_projects){
                            echo "<p> Projects still in the process of being uploaded</p><br>";
                        }
                        echo "</div>"; //close language div
                        //Free projects from current language
                        pg_free_result($raw_projects);
                    }//end $line while
                    // Free resultset and close DB
                    pg_free_result($raw_lanugage);
                    pg_close($dbconn);
                ?>
            </projects>
            <aside>
                <?php include 'src/languages-sidebar.php'?>
            </aside>
        </div>
        <?php include 'src/footer.php'?>
    </body>
</html>