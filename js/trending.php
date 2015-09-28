<?php
    $username = "root"; 
    $password = "";   
    $host = "localhost";
    $database="ktph";
    
    $conn = mysqli_connect($host, $username, $password, $database);
    //$connection = mysql_select_db($database, $conn);
 
    $myquery = "
    SELECT * 
    FROM (
    select *
    from `ktphalldata`
    WHERE `revisit` = 'TRUE' 
    ORDER BY `NRIC`,`Measurement.Att.Date` ASC 
    ) as test
    GROUP BY `NRIC`
UNION
    SELECT * 
    FROM (
    select *
    from `ktphalldata`
    WHERE `revisit` = 'TRUE' 
    ORDER BY `NRIC`,`Measurement.Att.Date` DESC 
    ) as test
    GROUP BY `NRIC`
    order by `NRIC`
    ";    
  /* 
  
  SELECT * 
    FROM `ktphalldata`
    WHERE `revisit` = 'TRUE'
    ORDER BY `NRIC`, `Measurement.Att.Date` DESC
    
    
  SELECT * FROM `ktphalldata`   
WHERE `Measurement.Att.Date`> '2014-01-01' AND 
`Measurement.Att.Date`< '2014-12-31'
ORDER BY `Measurement.Att.Date` ASC
  
  
    SELECT * 
    FROM (
    select *
    from `ktphalldata`
    WHERE `revisit` = 'TRUE'
    ORDER BY `NRIC`,`Measurement.Att.Date` ASC 
    ) as test
    GROUP BY `NRIC`
UNION
    SELECT * 
    FROM (
    select *
    from `ktphalldata`
    WHERE `revisit` = 'TRUE'
    ORDER BY `NRIC`,`Measurement.Att.Date` DESC 
    ) as test
    GROUP BY `NRIC`
    order by `NRIC`
    
    
    $myquery = "
SELECT k1.`Healthy`, k1.`Habits`, k1.`NRIC`, k1.`VisitFY`, k2.`VisitFY`, k1.`M_Systolic_1st`, k1.`M_Diastolic_1st`, k1.`L_Chol_f`, 
k1.`L_Trig_f`, k1.`L_HDL_f`, k1.`L_LDL_f`, k1.`L_Glucose_f`, k1.`f_BMI`, 
k1.`revisit`, k1.`NurseAction`, k1.`Measurement.Att.Date`,
IF(k1.`Measurement.Att.Date` < k2.`Measurement.Att.Date`,'Past','Current') AS PastOrCurrent
FROM `ktphalldata` k1 INNER JOIN `ktphalldata` k2 ON (k1.`NRIC` = k2.`NRIC` )
WHERE k1.`revisit` = 'TRUE' and (k1.`NurseAction` = 'Y' OR k1.`NurseAction` = 'Teleconsult') AND
(k1.`Measurement.Att.Date` < k2.`Measurement.Att.Date` OR k1.`Measurement.Att.Date` > k2.`Measurement.Att.Date` )
GROUP BY k1.`NRIC`, k1.`Measurement.Att.Date`
ORDER BY k1.`NRIC`, k1.`VisitFY`
";
*/

/*
    $myquery1 = "
        SELECT * 
        FROM `ktphalldata` k1 INNER JOIN `ktphalldata` k2 ON (k1.`NRIC` = k2.`NRIC` )
        WHERE k1.`revisit` = 'TRUE' and (k1.`NurseAction` = 'Y' OR k1.`NurseAction` = 'Teleconsult') AND
        (k1.`Measurement.Att.Date` < k2.`Measurement.Att.Date` OR k1.`Measurement.Att.Date` > k2.`Measurement.Att.Date` )
        GROUP BY k1.`NRIC`, k1.`Measurement.Att.Date`
        ORDER BY k1.`NRIC`, k1.`VisitFY`
    ";
    
    $myquery = "
        SELECT  DISTINCT(`scnZone`)
        FROM `ktphalldata` 
       
    ";*/
    
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
/*
    $links = array();
    
    for ($x = 0; $x < mysqli_num_rows($query); $x++) {
        $links[] = mysqli_fetch_assoc($query);
    }
   
echo "{";
echo '"links": ', json_encode($links), "\n";
echo ',"nodes": ', json_encode($nodes), "\n";
echo "}";
*/
echo json_encode($nodes);

    mysqli_close($conn);
?>