<?php
require_once 'db.php';
//connect to DB and pull data needed for tiles
$dbconn = post_connect();
//Query database to pull in language data
$query = 'SELECT name, nickname, color FROM languages ORDER BY id ASC;';
$results = pg_query($query) or die ('Query failed: '. pg_last_error());
$counter = 1000;

// for each lanaguage pull data and format a tile
while ($line = pg_fetch_array($results, null, PGSQL_ASSOC)) {
    $l_name = $line["name"];
    $l_nickname = $line["nickname"];
    $l_color = $line["color"];
    
    echo "<a href='#{$l_name}'><svg id='{$l_nickname}_hex' onmouseover='this.style.webkitFilter=\"drop-shadow(0px 0px 4px {$l_color})\";
					this.style.filter=\"drop-shadow(0px 0px 4px {$l_color})\";'
                    onmouseout='this.style.webkitFilter=\"\";
					this.style.filter=\"\";'
					onload='resetRotateY(this, {$counter});'
                     >
				<g
				 transform='translate(0,-986.36218)'
				 id='layer1'>
					<path
					   d='m 268.61355,354.62999 c -3.73655,6.57992 -57.82062,38.77698 -65.34797,38.95013 -7.52736,0.17316 -61.68116,-31.6391 -65.52419,-38.12569 -3.84303,-6.4866 -3.60231,-68.70828 0.0118,-75.32195 3.61413,-6.61366 58.00059,-37.96277 65.49414,-37.99858 7.49355,-0.0358 61.46991,30.69793 65.37802,37.17415 3.9081,6.47621 3.72472,68.74202 -0.0118,75.32194 z'
					   transform='matrix(0.72081848,0,0,0.38573309,-91.483554,896.75408)'
					   id='path2985-6'
					   style='fill:#f3f3f3;fill-opacity:1;stroke:#efefef;stroke-width:3;stroke-linecap:round;stroke-linejoin:round;stroke-miterlimit:4;stroke-opacity:1;stroke-dasharray:none' />
					<text
					   x='54.976563'
					   y='1034.2421'
					   id='text2984'
					   xml:space='preserve'
					   style='text-align:center;line-height:125%;text-anchor:middle;fill:{$l_color};'><tspan
						 x='54.976563'
						 y='1030.2421'
						 id='tspan2986'
						 style='font-size:36px;font-family:monospace;'>{$l_nickname}</tspan></text>
				</g>  
			</svg></a>
			";
	$counter += 300;
}

// Free resultset and close DB
pg_free_result($results);
pg_close($dbconn);
?>