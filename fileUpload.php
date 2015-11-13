<?php

session_start();

include 'PHPExcelLibrary/PHPExcel.php';
include 'PHPExcelLibrary/PHPExcel/IOFactory.php';

ini_set('memory_limit', '2048M');
set_time_limit('3600');

function loadData($tmpName) {
    //*Back Up Database
    $myFile = 'C:/wamp/www/PDashboard/KTPHTest.db';

    if (file_exists($myFile)) {
        $newFileName = 'KTPHBackup.' . date("MdYGi") . '.db';
        $newfile = 'C:/wamp/www/PDashboard/' . $newFileName;

        if (!copy($myFile, $newfile)) {
            echo "failed to copy the file";
            exit;
        }
    }

    //If KTPHTest.db is not exist
    class MyDB extends SQLite3 {

        function __construct() {
            $this->open('KTPHTest.db');
        }

    }

    $db = new MyDB();

    if (!$db) {
        echo $db->lastErrorMsg();
        exit;
    }

    //echo 'Database created successfully';
    $sqlDelete = <<<EOF
    DROP TABLE IF EXISTS GeoCode;
EOF;

    $result = $db->exec($sqlDelete);

    if (!$result) {
        echo $db->lastErrorMsg();
        exit;
    }


    $sqlTable = <<<EOF
        CREATE TABLE IF NOT EXISTS Demographics
        (NRIC VARCHAR(10) PRIMARY KEY NOT NULL ,
        Name VARCHAR(10),
        Address VARCHAR(50),
        DOB VARCHAR(10),
        GenderFullText VARCHAR(2),
        Occupation VARCHAR(10),
        MobilePhone VARCHAR(10),
        HomePhone VARCHAR(10),
        OfficePhone VARCHAR(10),
        FileLocation VARCHAR(10),
        LastUpdated VARCHAR(10),
        PostalCode VARCHAR(10)
        );

        CREATE TABLE IF NOT EXISTS ScreeningRecords
        ( RecodID VARCHAR(20) PRIMARY KEY NOT NULL,
        NRIC VARCHAR(20),
        L_Glucose_f FLOAT,
        L_Trig_f FLOAT,
        L_Chol_f FLOAT,
        L_HDL_f FLOAT,
        L_LDL_f FLOAT,
        M_Systolic_1st FLOAT,
        M_Diastolic_1st FLOAT,
        M_Weight FLOAT,
        M_Height FLOAT,
        f_BMI FLOAT,
        M_Waist FLOAT,
        X8Q_LS_Smoking VARCHAR(10),
        X8Q_LS_Exercise FLOAT,
        X8Q_MH_HeartAttack VARCHAR(10),
        X8Q_MH_Stroke VARCHAR(10),
        X8Q_MH_Diabetes VARCHAR(10),
        X8Q_MH_HBP VARCHAR(10),
        X8Q_MH_HBldChol VARCHAR(10),
        UnhealthyCat VARCHAR(10),
        PreferredLanguage VARCHAR(10),
        MeasurementAttDate VARCHAR(10),
        action VARCHAR(10),
        Zone VARCHAR(10),
        FOREIGN KEY(NRIC) REFERENCES Demographics(NRIC)    
        );

        CREATE TABLE IF NOT EXISTS SGPostal
        (cartodb_id VARCHAR(20) PRIMARY KEY NOT NULL,
        region1 VARCHAR(10),
        region2 VARCHAR(10),
        locality VARCHAR(10),
        postcode VARCHAR(10),
        latitude FLOAT,
        longitude FLOAT,
        elevation VARCHAR(10)
        );
EOF;

    $ret = $db->exec($sqlTable);

    if (!$ret) {
        echo $db->lastErrorMsg();
        exit;
    }
    //echo 'Table created successfully';
    //Read file
    try {
        //Cache Setting
        $cacheMethod = PHPExcel_CachedObjectStorageFactory::cache_in_memory;
        $cacheSettings = array('memoryCacheSize' => '2048M');
        PHPExcel_Settings::setCacheStorageMethod($cacheMethod, $cacheSettings);

        //Get file type
        $inputFileType = PHPExcel_IOFactory::identify($tmpName);

        //Automatically detect the correct reader to load for this file
        $objReader = PHPExcel_IOFactory::createReader($inputFileType);

        //If we dont need any formatting on the data
        $objReader->setReadDataOnly();

        //load only certain sheets from the file
        //--$loadSheets = array('Demographics', 'Screening Records','Call Log Data','Assessment Data','Report Collection Data');
        //--$objReader->setLoadSheetsOnly($loadSheets);
        //Default behavior is to load all sheets
        //$objReader->setLoadAllSheets();
        //Load excel data into PHP
        $objPHPExcel = $objReader->load($tmpName);

        $worksheetNames = $objPHPExcel->getSheetNames($tmpName);

        //Validate: Number of worksheets.
        if (sizeof($worksheetNames) != 3) {
            $_SESSION["FileValidation"] = 'File must contain 3 worksheets!';
            header("Location: dataprocessing.php");
            exit;
        } else {
            for ($i = 0; $i <= 2; $i++) {
                if ($worksheetNames[0] != 'Demographics' || $worksheetNames[1] != 'Screening Records' || $worksheetNames[2] != 'SGPostal') {
                    $_SESSION["response"] = 'File must contain 3 worksheet:Demographics, Screening Records, SGPostal!';
                    header("Location: dataprocessing.php");
                    exit;
                }
            }
        }

        $_SESSION["worksheets"] = array_values($worksheetNames);

        /* Per Worksheet */
        foreach ($worksheetNames as $sheetName) {

            $objPHPExcel->setActiveSheetIndexByName($sheetName);
            $sheet = $objPHPExcel->getActiveSheet();
            $rowData = $sheet->toArray(null, true, true, true);

            /* Per Row */
            if ($sheetName == 'Demographics') {
                $count = 0;
                $db->exec('begin');

                $loadRecord=0;
                foreach ($rowData as $keyRowNumber => $valueRowArray) {
                    $count++;
                    if ($keyRowNumber == 1) {
                        //Validate: Number of column
                        if (sizeof($valueRowArray) != 11) {
                            $_SESSION["FileValidation"] = 'Demographics worksheet must have 11 columns!';
                            header("Location: dataprocessing.php");
                            exit;
                        }
                    } else {
                        $rowObject = array_values($valueRowArray);

                        $rowObject[2] = preg_replace("/[^0-9a-z]+/i", " ", $rowObject[2]);

                        //Transform:Address to Postal Code
                        $Postal = substr($rowObject[2], -6);

                        if (!ctype_digit($Postal)) {
                            $Postal = str_replace($Postal[0], "0", $Postal);

                            if (!ctype_digit($Postal)) {
                                $Postal = 'Address';
                            }
                        }

                        array_push($rowObject, $Postal);


                        //Load Data: http://stackoverflow.com/questions/418898/sqlite-upsert-not-insert-or-replace
                        $value = implode("','", $rowObject);
                        $sqlquery = <<<EOF
                        INSERT OR REPLACE INTO Demographics VALUES ( '$value');
EOF;
                        $ret = $db->exec($sqlquery);

                        if (!$ret) {
                            echo $db->lastErrorMsg();
                        }
                        
                        $loadRecord ++;

                        if ($count % 5000 == 0) {
                            $db->exec('commit');
                            $db->exec('begin');
                        }
                    }
                }

                $db->exec('commit');

                /*
                $sqlDemographicRow = <<<EOF
                SELECT Count(*) as count FROM Demographics;        
EOF;
                $rows = $db->query($sqlDemographicRow);
                $row = $rows->fetchArray();
                $loadRecord = $row['count'];
                */
                
                $_SESSION["DemographicCount"] = $count-1;
                $_SESSION["Demographic"] = $loadRecord;
            }


            if ($sheetName === 'Screening Records') {
                $count = 0;
                $db->exec('begin');

                $loadRecord = 0;
                
                foreach ($rowData as $keyRowNumber => $valueRowArray) {
                    $count++;
                    if ($keyRowNumber == 1) {
                        //Validate: Number of column
                        if (sizeof($valueRowArray) != 25) {
                            $_SESSION["FileValidation"] = 'Screening Records worksheet must have 25 columns!';
                            header("Location: dataprocessing.php");
                            exit;
                        }
                    } else {
                        $rowObject = array_values($valueRowArray);

                        //Load Data: 
                        $value = implode("','", $rowObject);
                        $sqlquery = <<<EOF
                        INSERT OR REPLACE INTO ScreeningRecords VALUES ( '$value');
EOF;
                        $ret = $db->exec($sqlquery);

                        if (!$ret) {
                            echo $db->lastErrorMsg();
                        }
                        
                        $loadRecord++;
                                
                        if ($count % 5000 == 0) {
                            $db->exec('commit');
                            $db->exec('begin');
                        }
                    }
                }
                $db->exec('commit');

                /*
                $sqlScreeningRecordsRow = <<<EOF
                SELECT Count(*) as count FROM ScreeningRecords;        
EOF;
                $rows = $db->query($sqlScreeningRecordsRow);
                $row = $rows->fetchArray();
                $loadRecord = $row['count'];
                */
                $_SESSION["ScreeningCount"] = $count-1;
                $_SESSION["Screening"] = $loadRecord;
            }


            if ($sheetName == 'SGPostal') {

                /*
                  $sqlSGPostalRow = <<<EOF
                  SELECT Count(*) as count FROM SGPostal;
                  EOF;
                  $rows = $db->query($sqlSGPostalRow);
                  $row = $rows->fetchArray();
                  $loadRecord = $row['count'];
                 */


                //if ($loadRecord == 0) {
                $count = 0;
                $db->exec('begin');

                $loadRecord = 0;
                foreach ($rowData as $keyRowNumber => $valueRowArray) {
                    $count++;
                    if ($keyRowNumber == 1) {
                        //Validate: Number of column
                        if (sizeof($valueRowArray) != 8) {
                            $_SESSION["FileValidation"] = 'SGPostal worksheet must have 8 columns!';
                            header("Location: dataprocessing.php");
                            exit;
                        }
                    } else {
                        $rowObject = array_values($valueRowArray);

                        //Load Data: 
                        $value = implode("','", $rowObject);
                        $sqlquery = <<<EOF
                        INSERT OR IGNORE INTO SGPostal VALUES ( '$value');
EOF;
                        $ret = $db->exec($sqlquery);

                        if (!$ret) {
                            echo $db->lastErrorMsg();
                        }
                        $loadRecord++;

                        if ($count % 5000 == 0) {
                            $db->exec('commit');
                            $db->exec('begin');
                        }
                    }
                }

                /*
                $db->exec('commit');

                $sqlSGPostalRow = <<<EOF
                SELECT Count(*) as count FROM SGPostal;        
EOF;
                $rows = $db->query($sqlSGPostalRow);
                $row = $rows->fetchArray();
                $loadRecord = $row['count'];
                */

                $_SESSION['PostalCount'] = $count-1;
                $_SESSION['Postal'] = $loadRecord;
            }
        }

        //Geo Coding
        $filename = 'C:/wamp/www/PDashboard/GeoCodeError.txt';

        if (file_exists($filename)) {
            unlink($filename);
            $GeoCodeError = fopen("C:/wamp/www/PDashboard/GeoCodeError.txt", "w");
        } else {
            $GeoCodeError = fopen("C:/wamp/www/PDashboard/GeoCodeError.txt", "w");
        }

        fwrite($GeoCodeError, "NRIC");
        fwrite($GeoCodeError, " ");
        fwrite($GeoCodeError, "Address");
        fwrite($GeoCodeError, " ");
        fwrite($GeoCodeError, "ErrorType");
        fwrite($GeoCodeError, "\r\n");

        $GeoErrorReport = array();

        $sqlquery = <<<EOF
        CREATE TABLE GeoCode as
        SELECT 
        NRIC, Name, Address, DOB, GenderFullText, Occupation, MobilePhone, HomePhone, OfficePhone, 
        FileLocation, LastUpdated, PostalCode, SGPostal.cartodb_id, SGPostal.region1, SGPostal.region2, 
        SGPostal.locality, SGPostal.latitude, SGPostal.longitude, SGPostal.elevation,SGPostal.postcode 
        FROM Demographics
        LEFT JOIN SGPostal
        ON PostalCode=SGPostal.postcode;        
EOF;
        $geoRet = $db->exec($sqlquery);

        if (!$geoRet) {
            echo $db->lastErrorMsg();
            exit;
        }

        $sqlGeoCodeRow = <<<EOF
        SELECT NRIC,Address,PostalCode FROM GeoCode WHERE latitude IS NULL;        
EOF;
        $returned_set = $db->query($sqlGeoCodeRow);

        while ($result = $returned_set->fetchArray()) {
            $PostalCodeGoogle = $result['PostalCode'];
            $Address = $result['Address'];
            $NRIC = $result['NRIC'];

            if ($PostalCodeGoogle === 'Address') {
                $PostalCodeGoogle = $Address;
            }

            $url = "http://maps.googleapis.com/maps/api/geocode/json?sensor=false&key=AIzaSyCnmEGhcJgjntBiImyqIufnMf_CqL1mWPs&address=Singapore" . urlencode($PostalCodeGoogle);
            $resp_json = file_get_contents($url);
            $resp = json_decode($resp_json, true);

            if ($resp['status'] === 'OK') {
                $lati = $resp['results'][0]['geometry']['location']['lat'];
                $longi = $resp['results'][0]['geometry']['location']['lng'];
                //$formatted_address = $resp['results'][0]['formatted_address'];

                $GeoCodeSql = <<<EOF
              INSERT OR REPLACE INTO GeoCode(latitude,longitude) VALUES('$lati','$longi');
EOF;
                $GeoCodeResult = $db->exec($GeoCodeSql);

                if (!$GeoCodeResult) {
                    echo $db->lastErrorMsg();
                }
            } else {
                $url = "https://maps.googleapis.com/maps/api/geocode/json?sensor=false&key=AIzaSyCGcZmwlKDt4XipECzUQJP31C1Mp9906h0&address=Singapore" . urlencode($PostalCodeGoogle);
                $resp_json = file_get_contents($url);
                $resp = json_decode($resp_json, true);

                if ($resp['status'] === 'OK') {
                    $lati = $resp['results'][0]['geometry']['location']['lat'];
                    $longi = $resp['results'][0]['geometry']['location']['lng'];
                    //$formatted_address = $resp['results'][0]['formatted_address'];

                    $GeoCodeSql = <<<EOF
                    INSERT OR REPLACE INTO GeoCode(latitude,longitude) VALUES('$lati','$longi');
EOF;
                    $GeoCodeResult = $db->exec($GeoCodeSql);

                    if (!$GeoCodeResult) {
                        echo $db->lastErrorMsg();
                    }
                } else {
                    fwrite($GeoCodeError, $NRIC);
                    fwrite($GeoCodeError, " ");
                    fwrite($GeoCodeError, $Address);
                    fwrite($GeoCodeError, " ");
                    fwrite($GeoCodeError, "Invalid Address!");
                    fwrite($GeoCodeError, "\r\n");

                    array_push($GeoErrorReport, $Address);
                }
            }
        }

        if (!empty($GeoErrorReport)) {
            $_SESSION["GeoErrorReport"] = $GeoErrorReport;
        }


        $db->close();
    } catch (Exception $e) {
        die('Error loading file "' . pathinfo($tmpName, PATHINFO_BASENAME) . '": ' . $e->getMessage());
    }
}

function loadCSVData($tmpName) {
    //Backup current database
    $myFile = 'C:/wamp/www/PDashboard/KTPHTest.db';

    if (file_exists($myFile)) {
        $newFileName = 'KTPHBackup.' . date("MdYGi") . '.db';
        $newfile = 'C:/wamp/www/PDashboard/' . $newFileName;

        if (!copy($myFile, $newfile)) {
            echo "failed to copy the file";
            exit;
        }
    }

    //Create KTPHTest.db is not exist
    class MyDBForCSV extends SQLite3 {

        function __construct() {
            $this->open('KTPHTest.db');
        }

    }

    $db = new MyDBForCSV();

    if (!$db) {
        echo $db->lastErrorMsg();
        exit;
    }

    //Create ktphalldata table
    $sqlTable = <<<EOF
        CREATE TABLE IF NOT EXISTS ktphalldata(
            `NRIC` varchar(20) DEFAULT NULL PRIMARY KEY NOT NULL,
            `Addr_Postal.Code` varchar(6) DEFAULT NULL,
            `Zone` varchar(20) DEFAULT NULL,
            `Measurement.Att.Date` varchar(20) DEFAULT NULL,
            `Gender.Full.Text` varchar(10) DEFAULT NULL,
            `Race.Full.Text` varchar(10) DEFAULT NULL,
            `Nationality` varchar(10) DEFAULT NULL,
            `StaffEducation` varchar(20) DEFAULT NULL,
            `M_Weight` float DEFAULT NULL,
            `M_Height` float DEFAULT NULL,
            `M_Waist` float DEFAULT NULL,
            `M_Systolic_1st` float DEFAULT NULL,
            `M_Diastolic_1st` float DEFAULT NULL,
            `L_Chol_f` float DEFAULT NULL,
            `L_Trig_f` float DEFAULT NULL,
            `L_HDL_f` float DEFAULT NULL,
            `L_LDL_f` float DEFAULT NULL,
            `L_Glucose_f` float DEFAULT NULL,
            `f_BMI` float DEFAULT NULL,
            `X8Q_MH_HeartAttack` varchar(5) DEFAULT NULL,
            `X8Q_MH_Stroke` varchar(5) DEFAULT NULL,
            `X8Q_MH_Diabetes` varchar(5) DEFAULT NULL,
            `X8Q_MH_HBP` varchar(5) DEFAULT NULL,
            `X8Q_MH_HBldChol` varchar(5) DEFAULT NULL,
            `X8Q_MH_DiabetesTrt` varchar(5) DEFAULT NULL,
            `X8Q_MH_HBldCholTrt` varchar(5) DEFAULT NULL,
            `X8Q_FH_DadBroCHD` varchar(5) DEFAULT NULL,
            `X8Q_FH_MomSisCHD` varchar(5) DEFAULT NULL,
            `X8Q_LS_Smoking` varchar(5) DEFAULT NULL,
            `X8Q_LS_Exercise` varchar(5) DEFAULT NULL,
            `X8Q_LS_Stress` varchar(10) DEFAULT NULL,
            `X8Q_GN_Health` varchar(10) DEFAULT NULL,
            `X8Q_LS_FruitsVeg` varchar(10) DEFAULT NULL,
            `X8Q_MH_DiabetesFoot` varchar(10) DEFAULT NULL,
            `X8Q_Living` varchar(10) DEFAULT NULL,
            `Q_TAXI_Ache` varchar(10) DEFAULT NULL,
            `Q_TAXI_LengthAche` varchar(10) DEFAULT NULL,
            `Q_TAXI_AveSleep` varchar(10) DEFAULT NULL,
            `PreferredLanguage` varchar(20) DEFAULT NULL,
            `OccupationType` varchar(30) DEFAULT NULL,
            `Healthy` varchar(30) DEFAULT NULL,
            `Habits` varchar(30) DEFAULT NULL,
            `Diabetes` varchar(30) DEFAULT NULL,
            `BloodPressure` varchar(30) DEFAULT NULL,
            `Cholesterol` varchar(30) DEFAULT NULL,
            `Combine` varchar(50) DEFAULT NULL,
            `Overweight` varchar(50) DEFAULT NULL,
            `New` varchar(50) DEFAULT NULL,
            `UnhealthyCat` varchar(80) DEFAULT NULL,
            `Control` varchar(50) DEFAULT NULL,
            `ControlGrp` varchar(50) DEFAULT NULL,
            `existNewGrp` varchar(50) DEFAULT NULL,
            `controlTree` varchar(50) DEFAULT NULL,
            `AgeGrp` varchar(20) DEFAULT NULL,
            `BMIGrp` varchar(20) DEFAULT NULL,
            `Fasting` varchar(20) DEFAULT NULL,
            `SugarHigh` varchar(30) DEFAULT NULL,
            `BPHigh` varchar(30) DEFAULT NULL,
            `HealthState` varchar(20) DEFAULT NULL,
            `medicalHistCholSugBp` varchar(20) DEFAULT NULL,
            `screening` varchar(50) DEFAULT NULL,
            `action` varchar(20) DEFAULT NULL,
            `scnZone` varchar(20) DEFAULT NULL,
            `NurseAction` varchar(30) DEFAULT NULL,
            `DrOutcome` varchar(30) DEFAULT NULL,
            `month` varchar(15) DEFAULT NULL,
            `revisit` varchar(15) DEFAULT NULL,
            `DHLDescNHGP` text,
            `FollowUpPolyClinic` varchar(30) DEFAULT NULL,
            `DiagnosisAtPoly` text,
            `DateOfPolyVisit` varchar(20) DEFAULT NULL,
            `FollowupType` varchar(20) DEFAULT NULL,
            `VisitsNHGP` varchar(20) DEFAULT NULL,
            `X10YrRisk` int(11) DEFAULT NULL,
            `fScoreType` int(11) DEFAULT NULL,
            `fScore` int(11) DEFAULT NULL,
            `fScoreCat` varchar(20) DEFAULT NULL,
            `VisitFY` varchar(20) DEFAULT NULL,
            `URA_DGP` varchar(30) DEFAULT NULL,
            `healthRisk` varchar(30) DEFAULT NULL
        );
EOF;

    $ret = $db->exec($sqlTable);
    if (!$ret) {
        echo $db->lastErrorMsg();
        exit;
    }

    //Read CSV 
    $file_handle = fopen($tmpName, "r") or die('Could not read file!');

    $counter = 1;

    $db->exec('begin');
    while (!feof($file_handle)) {
        if ($counter != 1) {
            $line_of_text = fgetcsv($file_handle);
            
            $csvArray = array();
            for ($x = 0; $x <= 79; $x++) {
                if(strcmp($line_of_text[$x],'') == 0){
                    $line_of_text[$x]='NA';
                }
                array_push($csvArray, $line_of_text[$x]);
            }

            list($NRIC, $AddrPostalCode, $Zone, $MeasurementAttDate, $GenderFullText, $RaceFullText, $Nationality, $StaffEducation, $MWeight, $MHeight, $MWaist, $MSystolic1st, $MDiastolic1st, $LCholf, $LTrigf, $LHDLf, $LLDLf, $LGlucosef, $fBMI, $X8QMHHeartAttack, $X8QMHStroke, $X8QMHDiabetes, $X8QMHHBP, $X8QMHHBldChol, $X8QMHDiabetesTrt, $X8QMHHBldCholTrt, $X8QFHDadBroCHD, $X8QFHMomSisCHD, $X8QLSSmoking, $X8QLSExercise, $X8QLSStress, $X8QGNHealth, $X8QLSFruitsVeg, $X8QMHDiabetesFoot, $X8QLiving, $QTAXIAche, $QTAXILengthAche, $QTAXIAveSleep, $PreferredLanguage, $OccupationType, $Healthy, $Habits, $Diabetes, $BloodPressure, $Cholesterol, $Combine, $Overweight, $New, $UnhealthyCat, $Control, $ControlGrp, $existNewGrp, $controlTree, $AgeGrp, $BMIGrp, $Fasting, $SugarHigh, $BPHigh, $HealthState, $medicalHistCholSugBp, $screening, $action, $scnZone, $NurseAction, $DrOutcome, $month, $revisit, $DHLDescNHGP, $FollowUpPolyClinic, $DiagnosisAtPoly, $DateOfPolyVisit, $FollowupType, $VisitsNHGP, $X10YrRisk, $fScoreType, $fScore, $fScoreCat, $VisitFY, $URADGP, $healthRisk) = $csvArray;

            
            $sqlqueryCSV = <<<EOF
            INSERT OR REPLACE INTO ktphalldata ( `NRIC`, `Addr_Postal.Code`, `Zone`, `Measurement.Att.Date`, `Gender.Full.Text`, `Race.Full.Text`, `Nationality`, `StaffEducation`, `M_Weight`, `M_Height`, `M_Waist`, `M_Systolic_1st`, `M_Diastolic_1st`, `L_Chol_f`, `L_Trig_f`, `L_HDL_f`, `L_LDL_f`, `L_Glucose_f`, `f_BMI`, `X8Q_MH_HeartAttack`, `X8Q_MH_Stroke`, `X8Q_MH_Diabetes`, `X8Q_MH_HBP`, `X8Q_MH_HBldChol`, `X8Q_MH_DiabetesTrt`, `X8Q_MH_HBldCholTrt`, `X8Q_FH_DadBroCHD`, `X8Q_FH_MomSisCHD`, `X8Q_LS_Smoking`, `X8Q_LS_Exercise`, `X8Q_LS_Stress`, `X8Q_GN_Health`, `X8Q_LS_FruitsVeg`, `X8Q_MH_DiabetesFoot`, `X8Q_Living`, `Q_TAXI_Ache`, `Q_TAXI_LengthAche`, `Q_TAXI_AveSleep`, `PreferredLanguage`, `OccupationType`, `Healthy`, `Habits`, `Diabetes`, `BloodPressure`, `Cholesterol`, `Combine`, `Overweight`, `New`, `UnhealthyCat`, `Control`, `ControlGrp`, `existNewGrp`, `controlTree`, `AgeGrp`, `BMIGrp`, `Fasting`, `SugarHigh`, `BPHigh`, `HealthState`, `medicalHistCholSugBp`, `screening`, `action`, `scnZone`, `NurseAction`, `DrOutcome`, `month`, `revisit`, `DHLDescNHGP`, `FollowUpPolyClinic`, `DiagnosisAtPoly`, `DateOfPolyVisit`, `FollowupType`, `VisitsNHGP`, `X10YrRisk`, `fScoreType`, `fScore`, `fScoreCat`, `VisitFY`, `URA_DGP`, `healthRisk`) VALUES ('$NRIC','$AddrPostalCode', '$Zone', '$MeasurementAttDate', '$GenderFullText', '$RaceFullText', '$Nationality', '$StaffEducation', '$MWeight', '$MHeight', '$MWaist', '$MSystolic1st', '$MDiastolic1st', '$LCholf', '$LTrigf', '$LHDLf', '$LLDLf', '$LGlucosef', '$fBMI', '$X8QMHHeartAttack', '$X8QMHStroke', '$X8QMHDiabetes', '$X8QMHHBP', '$X8QMHHBldChol', '$X8QMHDiabetesTrt', '$X8QMHHBldCholTrt', '$X8QFHDadBroCHD', '$X8QFHMomSisCHD', '$X8QLSSmoking', '$X8QLSExercise', '$X8QLSStress', '$X8QGNHealth', '$X8QLSFruitsVeg', '$X8QMHDiabetesFoot', '$X8QLiving', '$QTAXIAche', '$QTAXILengthAche', '$QTAXIAveSleep', '$PreferredLanguage', '$OccupationType', '$Healthy', '$Habits', '$Diabetes', '$BloodPressure', '$Cholesterol', '$Combine', '$Overweight', '$New', '$UnhealthyCat', '$Control', '$ControlGrp', '$existNewGrp', '$controlTree', '$AgeGrp', '$BMIGrp', '$Fasting', '$SugarHigh', '$BPHigh', '$HealthState', '$medicalHistCholSugBp', '$screening', '$action', '$scnZone', '$NurseAction', '$DrOutcome', '$month', '$revisit', '$DHLDescNHGP', '$FollowUpPolyClinic', '$DiagnosisAtPoly', '$DateOfPolyVisit', '$FollowupType', '$VisitsNHGP', '$X10YrRisk', '$fScoreType', '$fScore', '$fScoreCat', '$VisitFY', '$URADGP', '$healthRisk');
EOF;

            $ret = $db->exec($sqlqueryCSV);

            if (!$ret) {
                echo $db->lastErrorMsg();
            }
        }
        
        $counter++;
        
        if ($counter % 2000 == 0) {
            $db->exec('commit');
            $db->exec('begin');
        }
    }
    
    $db->exec('commit');

    fclose($file_handle);
    $db->close();
    
    $_SESSION["CSV"] = "CSV";
}


//turn on php error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $name = $_FILES['file']['name'];
    $tmpName = $_FILES['file']['tmp_name'];
    $error = $_FILES['file']['error'];
    $size = $_FILES['file']['size'];
    $ext = strtolower(pathinfo($name, PATHINFO_EXTENSION));

    //#1
    $StartFileUpload = $_POST["StartFileUpload"];
    if ($StartFileUpload != null) {
        $FileUpload = 'File Upload Successful!';
        $_SESSION['FileUpload'] = $FileUpload;
    }


    //#2
    try {
        switch ($error) {
            case UPLOAD_ERR_OK:
                $valid = true;
                //validate file extensions
                if (!in_array($ext, array('xls', 'xlsx', 'csv'))) {
                    $valid = false;
                    $FileValidation = 'Invalid file extension!';
                    throw new RuntimeException($FileValidation);
                }
                //validate file size
                /*
                  if ($size / 1024 / 1024 > 5) {
                  $valid = false;
                  $FileValidation = 'File size is exceeding maximum allowed size.';
                  throw new RuntimeException($FileValidation);
                  }
                 */
                //upload file
                if ($valid) {
                    //Call loadData function
                    if (strcmp($ext, 'csv') == 0) {
                        call_user_func('loadCSVData', $tmpName);
                    } else {
                        call_user_func('loadData', $tmpName);
                    }

                    //$targetPath = dirname(__FILE__) . DIRECTORY_SEPARATOR . 'uploads' . DIRECTORY_SEPARATOR . $name;
                    //move_uploaded_file($tmpName, $targetPath);
                    //Redirect 
                    $_SESSION["FileValidation"] = "File Validation Successful!";
                    header("Location: dataprocessing.php");
                    exit;
                }
                break;
            /**
              case UPLOAD_ERR_INI_SIZE:
              $FileValidation = 'The uploaded file exceeds the upload_max_filesize directive in php.ini.';
              throw new RuntimeException($FileValidation);
              break;
             * */
            case UPLOAD_ERR_FORM_SIZE:
                $FileValidation = 'The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form.';
                throw new RuntimeException($FileValidation);
                break;
            case UPLOAD_ERR_PARTIAL:
                $FileValidation = 'The uploaded file was only partially uploaded.';
                throw new RuntimeException($FileValidation);
                break;
            case UPLOAD_ERR_NO_FILE:
                $FileValidation = 'No file was uploaded.';
                throw new RuntimeException($FileValidation);
                break;
            case UPLOAD_ERR_NO_TMP_DIR:
                $FileValidation = 'Missing a temporary folder. Introduced in PHP 4.3.10 and PHP 5.0.3.';
                throw new RuntimeException($FileValidation);
                break;
            case UPLOAD_ERR_CANT_WRITE:
                $FileValidation = 'Failed to write file to disk. Introduced in PHP 5.1.0.';
                throw new RuntimeException($FileValidation);
                break;
            case UPLOAD_ERR_EXTENSION:
                $FileValidation = 'File upload stopped by extension. Introduced in PHP 5.2.0.';
                throw new RuntimeException($FileValidation);
                break;
            default:
                $FileValidation = 'Unknown error';
                throw new RuntimeException($FileValidation);
                break;
        }
    } catch (RuntimeException $e) {
        $FileValidation = $e->getMessage();
        $_SESSION["FileValidation"] = $FileValidation;
        header("Location: dataprocessing.php");
    }
}
?>