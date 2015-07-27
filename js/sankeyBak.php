<?php
    $servername = "localhost";
    $username = "root"; 
    $password = "";   
    //$host = "localhost";
    $database="ktph";
    
    //$server = mysql_connect($host, $username, $password);
    //$server = mysql_connect($servername, $username, $password);
    $connection = new mysqli($servername,$username,$password,$database);

    $myquery = "
SELECT DISTINCT(`scnZone`) AS name FROM `ktphalldata`
UNION
SELECT DISTINCT(CONCAT(Healthy,'-',Habits)) AS name FROM `ktphalldata`
UNION
SELECT DISTINCT(CONCAT(Healthy,'-',Habits,'-F')) AS name FROM `ktphalldata`
UNION
SELECT DISTINCT(`action`) AS name FROM `ktphalldata`
GROUP BY name
";
    //$query = mysql_query($myquery);
    
    if ( $connection->query($myquery) === FALSE ) {
        echo "Error: SQL";
        die;
    }else{
       echo "Success"; 
    }
    
    $nodes = array();
    
    for ($x = 0; $x < mysql_num_rows($query); $x++) {
        $nodes[] = mysql_fetch_assoc($query);
    }

    $myquery = "
SELECT `scnZone` AS source, CONCAT(Healthy,'-',Habits) AS target,
COUNT(*) as value
FROM `ktphalldata`
WHERE `revisit` = 'TRUE' AND `visitFY` = 'FY2013'
GROUP BY source, target
UNION
SELECT CONCAT(Healthy,'-',Habits) AS source, `action` AS target,
COUNT(*) as value
FROM `ktphalldata`
WHERE `revisit` = 'TRUE' AND `visitFY` = 'FY2013'
GROUP BY source, target
UNION
SELECT `action` AS source, CONCAT(Healthy,'-',Habits,'-F') AS target,
COUNT(*) as value
FROM `ktphalldata`
WHERE `revisit` = 'TRUE' AND `visitFY` = 'FY2014'
GROUP BY source, target 
";
    $query = mysql_query($myquery);
    
    if ( ! $myquery ) {
        echo mysql_error();
       die;
    }
    
    $links = array();
    
    for ($x = 0; $x < mysql_num_rows($query); $x++) {
        $links[] = mysql_fetch_assoc($query);
    }

echo "{";
echo '"links": ', json_encode($links), "\n";
echo ',"nodes": ', json_encode($nodes), "\n";
echo "}";

    mysql_close($server);
?>