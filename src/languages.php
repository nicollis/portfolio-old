<?php
require_once 'db.php';
//connect to DB and pull data needed for tiles
$dbconn = post_connect();
//Query database to pull in language data
$query = 'SELECT name, nickname, title, about, color, accent FROM languages ORDER BY id ASC;';
$results = pg_query($query) or die ('Query failed: '. pg_last_error());

// for each lanaguage pull data and format a tile
while ($line = pg_fetch_array($results, null, PGSQL_ASSOC)) {
    $l_name = $line["name"];
    $l_nickname = $line["nickname"];
    $l_title = LineSplitter($line["title"]);
    $l_about = LineSplitter($line["about"]);
    $l_color = $line["color"];
    $l_accent = $line["accent"];
    
    echo "<a href='projects.php#{$l_name}'><div class=\"language_div\" onmouseover=\"flippy('$l_nickname','_$l_nickname')\"
                     onmouseout=\"flippy('_$l_nickname','$l_nickname')\">
            <svg id=$l_nickname class=language >
            <g transform=\"translate(0,-942.36218)\">
                <path d=\"m 101.86914,1023.5507 c -2.678009,4.686 -41.440422,27.616 -46.835324,27.7393 -5.394909,0.1233 -44.207297,-22.5326 -46.9616221,-27.1522 -2.7543251,-4.6196 -2.5817995,-48.93224 0.00846,-53.64233 2.5902711,-4.71008 41.5694081,-27.03612 46.9400851,-27.06162 5.370678,-0.0255 44.055894,21.86228 46.856861,26.47448 2.80096,4.6122 2.66953,48.95627 -0.008,53.64237 z\"/>
                <text id=langauge_text x=\"54.880371\" y=\"1009.7621\" style=\"fill:$l_color;\">$l_nickname</text>
            </g>
        </svg>
        <svg id=_$l_nickname class=\"language_back\">
            <g transform=\"translate(0,-942.36218)\">
            <g transform=\"matrix(0.58881981,0,0,0.61394787,-135.10468,773.13655)\">
               <path id=lb_mainfill d=\"m 268.61355,354.62999 c -3.73655,6.57992 -57.82062,38.77698 -65.34797,38.95013 -7.52736,0.17316 -61.68116,-31.6391 -65.52419,-38.12569 -3.84303,-6.4866 -3.60231,-68.70828 0.0118,-75.32195 3.61413,-6.61366 58.00059,-37.96277 65.49414,-37.99858 7.49355,-0.0358 61.46991,30.69793 65.37802,37.17415 3.9081,6.47621 3.72472,68.74202 -0.0118,75.32194 z\" transform=\"matrix(1.2189051,0,0,1.1649304,75.15324,-5.0623626)\" style=\"fill:$l_color; stroke:$l_accent;\"/>
                <path id=lb_subfill d=\"m 401.1391,408.05689 c -4.5545,7.66515 -70.47785,45.17249 -79.65298,45.37419 0,-58.14565 -0.0222,-120.76756 -0.0222,-176.4243 9.13392,-0.0417 74.92598,35.76095 79.6896,43.3053 4.7636,7.54433 4.54008,80.07966 -0.0144,87.74481 z\" style=\"fill:$l_accent;\"/>
            </g>";
    //Check to see if 3 or 4 lines are needed then the apporiate one
    if(count($l_title) > 1){
        echo    "<text class=\"lb_text\" x=\"55.071991\" y=\"976.49506\">
                    <tspan x=\"55\" y=\"976\">$l_title[0]</tspan>
                    <tspan x=\"55\" y=\"993\">$l_title[1]</tspan>
                    <tspan x=\"55\" y=\"1011\">$l_about[0] </tspan>
                    <tspan x=\"55\" y=\"1028\">$l_about[1]</tspan>
                </text>";
    }else{
        echo    "<text class=\"lb_text\" x=\"55.071991\" y=\"976.49506\">
                    <tspan x=\"55\" y=\"984.5\">$l_title[0]</tspan>
                    <tspan x=\"55\" y=\"1002\">$l_about[0] </tspan>
                    <tspan x=\"55\" y=\"1019.5\">$l_about[1]</tspan>
                </text>";
    }
    echo    "</g>
        </svg>
    </div></a>";
    
}

// Free resultset and close DB
pg_free_result($results);
pg_close($dbconn);

//need to break the title and about into two lines
function LineSplitter($statement){
    $regex = '/(.{0,11})(\b|\z)/m';
    $results = preg_split($regex, $statement, NULL, PREG_SPLIT_DELIM_CAPTURE | PREG_SPLIT_NO_EMPTY);
    //if results has 3 lines combine lines 2 and 3
    if(count($results)>=3)
        $results[1] = $results[1] ." ". $results[2];
    return $results;
}
?>