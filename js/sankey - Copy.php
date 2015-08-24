<?php
    $username = "root"; 
    $password = "";   
    $host = "localhost";
    $database="ktph";
    
    $conn = mysqli_connect($host, $username, $password, $database);
    //$connection = mysql_select_db($database, $conn);

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
    $query = mysqli_query($conn,$myquery);
    
    if ( ! $myquery ) {
        ////echo mysql_error();
        echo "mySQL Error";
        die("Connection failed: ");
    }
    
    $nodes = array();
    
    for ($x = 0; $x < mysqli_num_rows($query); $x++) {
        $nodes[] = mysqli_fetch_assoc($query);
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
    $query = mysqli_query($conn,$myquery);
    
    if ( ! $myquery ) {
        ////echo mysql_error();
        echo "mySQL Error";
        die("Connection failed: ");
    }
    
    $links = array();
    
    for ($x = 0; $x < mysqli_num_rows($query); $x++) {
        $links[] = mysqli_fetch_assoc($query);
    }

echo "{";
echo '"links": ', json_encode($links), "\n";
echo ',"nodes": ', json_encode($nodes), "\n";
echo "}";

    mysqli_close($conn);
?>