<?php

session_start();

include 'PHPExcelLibrary/PHPExcel.php';
include 'PHPExcelLibrary/PHPExcel/IOFactory.php';

ini_set('memory_limit', '2048M');
set_time_limit('3600');

function loadData($tmpName) {
    //Back Up Database
    $files = glob('C:/wamp/www/PDashboard/DatabaseBackup/*'); // get all file names
    foreach ($files as $file) { // iterate files
        if (is_file($file)) {
            unlink($file);
        }
    }

    $myFile = 'C:/wamp/www/PDashboard/KTPHTest.db';

    if (file_exists($myFile)) {
        $newFileName = 'KTPHBackup.' . date("MdYGi") . '.db';
        $newfile = 'C:/wamp/www/PDashboard/DatabaseBackup/' . $newFileName;

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

    //Delete temp joined table;
    $sqlDelete = <<<EOF
    DROP TABLE IF EXISTS TempGeoCode;
EOF;

    $result = $db->exec($sqlDelete);

    if (!$result) {
        echo $db->lastErrorMsg();
    }

    sleep(5);

    /*
      CREATE TABLE IF NOT EXISTS ScreeningRecords(
      'NRIC' VARCHAR(20) PRIMARY KEY NOT NULL,
      'L_Glucose_f' FLOAT,
      'L_Trig_f' FLOAT,
      'L_Chol_f' FLOAT,
      'L_HDL_f' FLOAT,
      'L_LDL_f' FLOAT,
      'M_Systolic_1st' FLOAT,
      'M_Diastolic_1st' FLOAT,
      'M_Weight' FLOAT,
      'M_Height' FLOAT,
      'f_BMI' FLOAT,
      'M_Waist' FLOAT,
      'X8Q_LS_Smoking' VARCHAR(10),
      'X8Q_LS_Exercise' FLOAT,
      'X8Q_MH_HeartAttack' VARCHAR(10),
      'X8Q_MH_Stroke' VARCHAR(10),
      'X8Q_MH_Diabetes' VARCHAR(10),
      'X8Q_MH_HBP' VARCHAR(10),
      'X8Q_MH_HBldChol' VARCHAR(10),
      'UnhealthyCat' VARCHAR(10),
      'PreferredLanguage' VARCHAR(10),
      `Measurement.Att.Date` VARCHAR(10) PRIMARY KEY NOT NULL,
      'action' VARCHAR(10),
      'Zone' VARCHAR(10),
      'RecodID' VARCHAR(20),
      FOREIGN KEY ('NRIC') REFERENCES GeoCode('NRIC')
      );
     */
    $sqlTable = <<<EOF
        PRAGMA foreign_keys = ON;
        
        CREATE TABLE IF NOT EXISTS SGPostal(
        'region1' VARCHAR(20) NOT NULL,
        'region2' VARCHAR(20) NOT NULL,
        'locality' VARCHAR(10) NOT NULL,
        'postcode' VARCHAR(10) PRIMARY KEY NOT NULL,
        'latitude' FLOAT,
        'longitude' FLOAT,
        'elevation' VARCHAR(10) NOT NULL,
        'cartodb_id' VARCHAR(10) NOT NULL,
        'ResidentialNum'INT(5) NOT NULL
        );
            
        CREATE TABLE IF NOT EXISTS ktphalldata(
        'NRIC' VARCHAR(15) DEFAULT NULL,
        'Name' VARCHAR(20) DEFAULT NULL,
        'Address' VARCHAR(50) DEFAULT NULL,
        'DOB' VARCHAR(15) DEFAULT NULL,
        'Gender.Full.Text' VARCHAR(10) DEFAULT NULL,
        'Occupation' VARCHAR(15) DEFAULT NULL,
        'MobilePhone' VARCHAR(15) DEFAULT NULL,
        'HomePhone' VARCHAR(15) DEFAULT NULL,
        'OfficePhone' VARCHAR(15) DEFAULT NULL,
        'L_Glucose_f' FLOAT DEFAULT NULL,
        'L_Trig_f' FLOAT DEFAULT NULL,
        'L_Chol_f' FLOAT DEFAULT NULL,
        'L_HDL_f' FLOAT DEFAULT NULL,
        'L_LDL_f' FLOAT DEFAULT NULL,
        'M_Systolic_1st' FLOAT DEFAULT NULL,
        'M_Diastolic_1st' FLOAT DEFAULT NULL,
        'M_Weight' FLOAT DEFAULT NULL,
        'M_Height' FLOAT DEFAULT NULL,
        'f_BMI' FLOAT DEFAULT NULL,
        'M_Waist' FLOAT DEFAULT NULL,
        'X8Q_LS_Smoking' VARCHAR(5) DEFAULT NULL,
        'X8Q_LS_Exercise' VARCHAR(5) DEFAULT NULL,
        'X8Q_MH_HeartAttack' VARCHAR(5) DEFAULT NULL,
        'X8Q_MH_Stroke' VARCHAR(5) DEFAULT NULL,
        'X8Q_MH_Diabetes' VARCHAR(5) DEFAULT NULL,
        'X8Q_MH_HBP' VARCHAR(5) DEFAULT NULL,
        'X8Q_MH_HBldChol' VARCHAR(5) DEFAULT NULL,
        'UnhealthyCat' VARCHAR(15) DEFAULT NULL,
        'PreferredLanguage' VARCHAR(15) DEFAULT NULL,
        'Measurement.Att.Date' VARCHAR(20) DEFAULT NULL,
        'action' VARCHAR(15) DEFAULT NULL,
        'Zone' VARCHAR(20) DEFAULT NULL,
        'Addr_Postal.Code' VARCHAR(10) DEFAULT NULL,
        'Age' INT(11) DEFAULT NULL,
        'AgeGrp' VARCHAR(15) DEFAULT NULL,
        'BloodPressure' VARCHAR(15) DEFAULT NULL,
        'BMIGrp' VARCHAR(15) DEFAULT NULL,
        'BPHigh' VARCHAR(20) DEFAULT NULL,
        'Cholesterol' VARCHAR(20) DEFAULT NULL,
        'Combine' VARCHAR(20) DEFAULT NULL,
        'Control' VARCHAR(50) DEFAULT NULL,
        'ControlGrp' VARCHAR(50) DEFAULT NULL,
        'controlTree' VARCHAR(50) DEFAULT NULL,
        'DateOfPolyVisit' VARCHAR(15) DEFAULT NULL,
        'DHLDescNHGP' TEXT DEFAULT NULL,
        'Diabetes' VARCHAR(15) DEFAULT NULL,
        'DiagnosisAtPoly' VARCHAR(15) DEFAULT NULL,
        'DrOutcome' VARCHAR(15) DEFAULT NULL,
        'existNewGrp' VARCHAR(50) DEFAULT NULL,
        'Fasting' VARCHAR(15) DEFAULT NULL,
        'FollowUpPolyClinic' VARCHAR(15) DEFAULT NULL,
        'FollowupType' VARCHAR(15) DEFAULT NULL,
        'fScore' INT(11) DEFAULT NULL,
        'fScoreCat' VARCHAR(15) DEFAULT NULL,
        'fScoreType' VARCHAR(15) DEFAULT NULL,
        'Habits' VARCHAR(20) DEFAULT NULL,
        'healthRisk' VARCHAR(20) DEFAULT NULL,
        'HealthState' VARCHAR(15) DEFAULT NULL,
        'Healthy' VARCHAR(15) DEFAULT NULL,
        'MeasurementMonth' VARCHAR(15) DEFAULT NULL,
        'medicalHistCholSugBp' VARCHAR(10) DEFAULT NULL,
        'MOH_RHS2015' VARCHAR(15) DEFAULT NULL,
        'Nationality' VARCHAR(15) DEFAULT NULL,
        'New' VARCHAR(15) DEFAULT NULL,
        'NurseAction' VARCHAR(15) DEFAULT NULL,
        'OccupationType' VARCHAR(20) DEFAULT NULL,
        'Q_PH_PCP' VARCHAR(15) DEFAULT NULL,
        'Q_TAXI_Ache' VARCHAR(15) DEFAULT NULL,
        'Q_TAXI_AveSleep' VARCHAR(15) DEFAULT NULL,
        'Q_TAXI_LengthAche' VARCHAR(15) DEFAULT NULL,
        'Race.Full.Text' VARCHAR(15) DEFAULT NULL,
        'Remarks' VARCHAR(50) DEFAULT NULL,
        'repeatVisit' VARCHAR(15) DEFAULT NULL,
        'revisit' VARCHAR(15) DEFAULT NULL,
        'scnZone' VARCHAR(20) DEFAULT NULL,
        'screening' VARCHAR(50) DEFAULT NULL,
        'StaffEducation' VARCHAR(20) DEFAULT NULL,
        'SugarHigh' VARCHAR(20) DEFAULT NULL,
        'URA_DGP' VARCHAR(20) DEFAULT NULL,
        'VisitFY' VARCHAR(20) DEFAULT NULL,
        'VisitsNHGP' VARCHAR(5) DEFAULT NULL,
        'WeightCategory' VARCHAR(25) DEFAULT NULL,
        'X10YrRisk' INT(11) DEFAULT NULL,
        'X8Q_FH_DadBroCHD' VARCHAR(5) DEFAULT NULL,
        'X8Q_FH_MomSisCHD' VARCHAR(5) DEFAULT NULL,
        'X8Q_GN_Health' VARCHAR(10) DEFAULT NULL,
        'X8Q_GN_Helpful' VARCHAR(5) DEFAULT NULL,
        'X8Q_Living' VARCHAR(20) DEFAULT NULL,
        'X8Q_LS_FruitsVeg' VARCHAR(5) DEFAULT NULL,
        'X8Q_LS_Stress' VARCHAR(20) DEFAULT NULL,
        'X8Q_MH_DiabetesFoot' VARCHAR(5) DEFAULT NULL,
        'X8Q_MH_DiabetesTrt' VARCHAR(5) DEFAULT NULL,
        'X8Q_MH_HBldCholTrt' VARCHAR(5) DEFAULT NULL,
         PRIMARY KEY ('NRIC', 'Measurement.Att.Date')
        );   
            
        CREATE TABLE IF NOT EXISTS GeoCode(
        'NRIC' VARCHAR(15) DEFAULT NULL,
        'Name' VARCHAR(20) DEFAULT NULL,
        'Address' VARCHAR(50) DEFAULT NULL,
        'DOB' VARCHAR(15) DEFAULT NULL,
        'Gender.Full.Text' VARCHAR(10) DEFAULT NULL,
        'Occupation' VARCHAR(15) DEFAULT NULL,
        'MobilePhone' VARCHAR(15) DEFAULT NULL,
        'HomePhone' VARCHAR(15) DEFAULT NULL,
        'OfficePhone' VARCHAR(15) DEFAULT NULL,
        'L_Glucose_f' FLOAT DEFAULT NULL,
        'L_Trig_f' FLOAT DEFAULT NULL,
        'L_Chol_f' FLOAT DEFAULT NULL,
        'L_HDL_f' FLOAT DEFAULT NULL,
        'L_LDL_f' FLOAT DEFAULT NULL,
        'M_Systolic_1st' FLOAT DEFAULT NULL,
        'M_Diastolic_1st' FLOAT DEFAULT NULL,
        'M_Weight' FLOAT DEFAULT NULL,
        'M_Height' FLOAT DEFAULT NULL,
        'f_BMI' FLOAT DEFAULT NULL,
        'M_Waist' FLOAT DEFAULT NULL,
        'X8Q_LS_Smoking' VARCHAR(5) DEFAULT NULL,
        'X8Q_LS_Exercise' VARCHAR(5) DEFAULT NULL,
        'X8Q_MH_HeartAttack' VARCHAR(5) DEFAULT NULL,
        'X8Q_MH_Stroke' VARCHAR(5) DEFAULT NULL,
        'X8Q_MH_Diabetes' VARCHAR(5) DEFAULT NULL,
        'X8Q_MH_HBP' VARCHAR(5) DEFAULT NULL,
        'X8Q_MH_HBldChol' VARCHAR(5) DEFAULT NULL,
        'UnhealthyCat' VARCHAR(15) DEFAULT NULL,
        'PreferredLanguage' VARCHAR(15) DEFAULT NULL,
        'Measurement.Att.Date' VARCHAR(20) DEFAULT NULL,
        'action' VARCHAR(15) DEFAULT NULL,
        'Zone' VARCHAR(20) DEFAULT NULL,
        'Addr_Postal.Code' VARCHAR(10) DEFAULT NULL,
        'Age' INT(11) DEFAULT NULL,
        'AgeGrp' VARCHAR(15) DEFAULT NULL,
        'BloodPressure' VARCHAR(15) DEFAULT NULL,
        'BMIGrp' VARCHAR(15) DEFAULT NULL,
        'BPHigh' VARCHAR(20) DEFAULT NULL,
        'Cholesterol' VARCHAR(20) DEFAULT NULL,
        'Combine' VARCHAR(20) DEFAULT NULL,
        'Control' VARCHAR(50) DEFAULT NULL,
        'ControlGrp' VARCHAR(50) DEFAULT NULL,
        'controlTree' VARCHAR(50) DEFAULT NULL,
        'DateOfPolyVisit' VARCHAR(15) DEFAULT NULL,
        'DHLDescNHGP' TEXT DEFAULT NULL,
        'Diabetes' VARCHAR(15) DEFAULT NULL,
        'DiagnosisAtPoly' VARCHAR(15) DEFAULT NULL,
        'DrOutcome' VARCHAR(15) DEFAULT NULL,
        'existNewGrp' VARCHAR(50) DEFAULT NULL,
        'Fasting' VARCHAR(15) DEFAULT NULL,
        'FollowUpPolyClinic' VARCHAR(15) DEFAULT NULL,
        'FollowupType' VARCHAR(15) DEFAULT NULL,
        'fScore' INT(11) DEFAULT NULL,
        'fScoreCat' VARCHAR(15) DEFAULT NULL,
        'fScoreType' VARCHAR(15) DEFAULT NULL,
        'Habits' VARCHAR(20) DEFAULT NULL,
        'healthRisk' VARCHAR(20) DEFAULT NULL,
        'HealthState' VARCHAR(15) DEFAULT NULL,
        'Healthy' VARCHAR(15) DEFAULT NULL,
        'MeasurementMonth' VARCHAR(15) DEFAULT NULL,
        'medicalHistCholSugBp' VARCHAR(10) DEFAULT NULL,
        'MOH_RHS2015' VARCHAR(15) DEFAULT NULL,
        'Nationality' VARCHAR(15) DEFAULT NULL,
        'New' VARCHAR(15) DEFAULT NULL,
        'NurseAction' VARCHAR(15) DEFAULT NULL,
        'OccupationType' VARCHAR(20) DEFAULT NULL,
        'Q_PH_PCP' VARCHAR(15) DEFAULT NULL,
        'Q_TAXI_Ache' VARCHAR(15) DEFAULT NULL,
        'Q_TAXI_AveSleep' VARCHAR(15) DEFAULT NULL,
        'Q_TAXI_LengthAche' VARCHAR(15) DEFAULT NULL,
        'Race.Full.Text' VARCHAR(15) DEFAULT NULL,
        'Remarks' VARCHAR(50) DEFAULT NULL,
        'repeatVisit' VARCHAR(15) DEFAULT NULL,
        'revisit' VARCHAR(15) DEFAULT NULL,
        'scnZone' VARCHAR(20) DEFAULT NULL,
        'screening' VARCHAR(50) DEFAULT NULL,
        'StaffEducation' VARCHAR(20) DEFAULT NULL,
        'SugarHigh' VARCHAR(20) DEFAULT NULL,
        'URA_DGP' VARCHAR(20) DEFAULT NULL,
        'VisitFY' VARCHAR(20) DEFAULT NULL,
        'VisitsNHGP' VARCHAR(5) DEFAULT NULL,
        'WeightCategory' VARCHAR(25) DEFAULT NULL,
        'X10YrRisk' INT(11) DEFAULT NULL,
        'X8Q_FH_DadBroCHD' VARCHAR(5) DEFAULT NULL,
        'X8Q_FH_MomSisCHD' VARCHAR(5) DEFAULT NULL,
        'X8Q_GN_Health' VARCHAR(10) DEFAULT NULL,
        'X8Q_GN_Helpful' VARCHAR(5) DEFAULT NULL,
        'X8Q_Living' VARCHAR(20) DEFAULT NULL,
        'X8Q_LS_FruitsVeg' VARCHAR(5) DEFAULT NULL,
        'X8Q_LS_Stress' VARCHAR(20) DEFAULT NULL,
        'X8Q_MH_DiabetesFoot' VARCHAR(5) DEFAULT NULL,
        'X8Q_MH_DiabetesTrt' VARCHAR(5) DEFAULT NULL,
        'X8Q_MH_HBldCholTrt' VARCHAR(5) DEFAULT NULL,
        'latitude' FLOAT,
        'longitude' FLOAT,
        'ResidentialNum'INT(5),
        PRIMARY KEY ('NRIC', 'Measurement.Att.Date')
        );
            
       CREATE TABLE IF NOT EXISTS NRICReference(
        'NRIC' VARCHAR(15) PRIMARY KEY NOT NULL
       );    

       CREATE TABLE IF NOT EXISTS CallLogData(
        'NRIC' VARCHAR(15) NOT NULL,
        'Date' VARCHAR(15) NOT NULL,
        'Time' VARCHAR(15) NOT NULL,
        'Outcome' VARCHAR(15) NOT NULL,
        'Purpose of call' VARCHAR(15) NOT NULL,
        'Details' VARCHAR(100) NOT NULL,
        'Follow-up date' VARCHAR(15) NOT NULL,
        'Follow-up time' VARCHAR(15) NOT NULL,
        'Follow-up type' VARCHAR(50) NOT NULL,
        'Last Updated' VARCHAR(15) NOT NULL,
        'Created Date' VARCHAR(15) NOT NULL,
        'ID' VARCHAR(15) NOT NULL,
        PRIMARY KEY ('NRIC', 'Date', 'Time')
        );
            
        CREATE TABLE IF NOT EXISTS AssessmentData(
        'Basic Info_NRIC' VARCHAR(15) NOT NULL,
        'Basic Info_Date of Visit' VARCHAR(15) NOT NULL,
        'Basic Info_Time of Visit' VARCHAR(15) NOT NULL,
        'Health Status_Primary Health Care Provider (Chronic)' VARCHAR(15) NOT NULL,
        'Health Status_Follow up in PCP' VARCHAR(15) NOT NULL,
        'Health Status_Current Medication' VARCHAR(15) NOT NULL,
        'Health Status_Medication Compliance' VARCHAR(15) NOT NULL,
        'Health Status_Diabetes' VARCHAR(15) NOT NULL,
        'Health Status_On Medication1' VARCHAR(15) NOT NULL,
        'Health Status_HBldPress' VARCHAR(15) NOT NULL,
        'Health Status_On Medication2' VARCHAR(15) NOT NULL,
        'Health Status_HBldChol' VARCHAR(15) NOT NULL,
        'Health Status_On Medication3' VARCHAR(15) NOT NULL,
        'Health Status_Others' VARCHAR(15) NOT NULL,
        'Measurement_Systolic' VARCHAR(15) NOT NULL,
        'Measurement_Diastolic' VARCHAR(15) NOT NULL,
        'Measurement_Glucose' VARCHAR(15) NOT NULL,
        'Measurement_Own Monitoring Device?' VARCHAR(15) NOT NULL,
        'After Screening Action_Consulted Doctor After Screening' VARCHAR(15) NOT NULL,
        'After Screening Action_Doctor Diagnosis' VARCHAR(15) NOT NULL,
        'After Screening Action_Diabetes' VARCHAR(15) NOT NULL,
        'After Screening Action_Medicine1' VARCHAR(15) NOT NULL,
        'After Screening Action_HBldPress' VARCHAR(15) NOT NULL,
        'After Screening Action_Medicine2' VARCHAR(15) NOT NULL,
        'After Screening Action_HBldChol' VARCHAR(15) NOT NULL,
        'After Screening Action_Medicine3' VARCHAR(15) NOT NULL,
        'Assessment_Knowledge on Disease' VARCHAR(15) NOT NULL,
        'Assessment_Knowledge on Treatment' VARCHAR(15) NOT NULL,
        'Intervention Programme_Intro to Live Healthy Stay Active' VARCHAR(15) NOT NULL,
        'Intervention Programme_Live!' VARCHAR(15) NOT NULL,
        'Intervention Programme_Smoking Cessation' VARCHAR(15) NOT NULL,
        'Intervention Programme_IP Remarks' VARCHAR(15) NOT NULL,
        'Remarks_Nurse' VARCHAR(15) NOT NULL,
        'Future Follow Up_Further Action Required' VARCHAR(15) NOT NULL,
        'Future Follow Up_Second Follow Up Date' VARCHAR(15) NOT NULL,
        'Future Follow Up_Time' VARCHAR(15) NOT NULL,
        'Future Follow Up_Remarks' VARCHAR(15) NOT NULL,
        'Future Follow Up_Last updated Date' VARCHAR(15) NOT NULL,
        'Future Follow Up_Created Date' VARCHAR(15) NOT NULL,
        'Future Follow Up_ID' VARCHAR(15) NOT NULL,
        PRIMARY KEY ('Basic Info_NRIC', 'Basic Info_Date of Visit', 'Basic Info_Time of Visit')
        );
            
        CREATE TABLE IF NOT EXISTS ReportCollectionData(
        'NRIC' VARCHAR(15) NOT NULL,
        'Screening Date' VARCHAR(15) NOT NULL,
        'Type' VARCHAR(15) NOT NULL,
        'Collection Date' VARCHAR(15) NOT NULL,
        'Update Time' VARCHAR(15) NOT NULL,
        PRIMARY KEY ('NRIC', 'Screening Date')
        );
EOF;

    $ret = $db->exec($sqlTable);

    if (!$ret) {
        echo $db->lastErrorMsg();
        exit;
    }

    //Read file
    try {
        //Error Report
        $filename = 'C:/wamp/www/PDashboard/ExcelError.csv';

        if (file_exists($filename)) {
            unlink($filename);
            $ExcelError = fopen("C:/wamp/www/PDashboard/ExcelError.csv", "w");
        } else {
            $ExcelError = fopen("C:/wamp/www/PDashboard/ExcelError.csv", "w");
        }

        $title = "NRIC, ErrorType, Location";
        fputcsv($ExcelError, explode(',', $title));

        $ExcelErrorTest = true;

        //Cache Setting
        $cacheMethod = PHPExcel_CachedObjectStorageFactory::cache_in_memory;
        $cacheSettings = array('memoryCacheSize' => '2048M');
        PHPExcel_Settings::setCacheStorageMethod($cacheMethod, $cacheSettings);

        $inputFileType = PHPExcel_IOFactory::identify($tmpName);
        $objReader = PHPExcel_IOFactory::createReader($inputFileType);
        $objReader->setReadDataOnly();
        $objPHPExcel = $objReader->load($tmpName);

        $worksheetNames = $objPHPExcel->getSheetNames($tmpName);

        //Upload SGPostal OR Screen&FollowUp data
        if (sizeof($worksheetNames) == 1 && $worksheetNames[0] == 'sg_postalcode') {
            foreach ($worksheetNames as $sheetName) {
                $objPHPExcel->setActiveSheetIndexByName($sheetName);
                $sheet = $objPHPExcel->getActiveSheet();
                $rowData = $sheet->toArray(null, true, true, true);

                $count = 0;
                $db->exec('begin');
                foreach ($rowData as $keyRowNumber => $valueRowArray) {
                    $count++;
                    if ($keyRowNumber == 1) {
                        //Validate: Number of column
                        if (sizeof($valueRowArray) != 9) {
                            $_SESSION["FileValidation"] = 'SGPostal worksheet must have 9 columns!';
                            header("Location: dataprocessing.php");
                            exit;
                        }
                    } else {
                        $rowObject = array_values($valueRowArray);

                        //Load Data: 
                        $value = implode("','", $rowObject);
                        $sqlquery = <<<EOF
                        INSERT OR REPLACE INTO SGPostal VALUES ( '$value');
EOF;

                        $ret = $db->exec($sqlquery);

                        if (!$ret) {
                            echo $db->lastErrorMsg();
                        }

                        if ($count % 5000 == 0) {
                            $db->exec('commit');
                            $db->exec('begin');
                        }
                    }
                }
                $db->exec('commit');
            }

            $_SESSION['Upload_SGPostal'] = 'Upload_SGPostal';
            $_SESSION['PostalCount'] = $count - 1;
        } else {
            if (sizeof($worksheetNames) != 5) {
                $_SESSION["FileValidation"] = 'File must contain 5 worksheets!';
                header("Location: dataprocessing.php");
                exit;
            } else {
                for ($i = 0; $i <= 4; $i++) {
                    if ($worksheetNames[0] != 'Demographics' || $worksheetNames[1] != 'Screening Records' || $worksheetNames[2] != 'Call Log Data' || $worksheetNames[3] != 'Assessment Data' || $worksheetNames[4] != 'Report Collection Data') {
                        $_SESSION["response"] = 'File must contain 5 worksheet:Demographics, Screening Records, Call Log Data,Assessment Data,Report Collection Data!';
                        header("Location: dataprocessing.php");
                        exit;
                    }
                }
            }

            $_SESSION["worksheets"] = array_values($worksheetNames);


            //Get NRIC List
            $NRICList = array();
            $sqlNRIC = <<<EOF
            SELECT NRIC FROM NRICReference;        
EOF;

            $returned_NRIC = $db->query($sqlNRIC);

            while ($resultNRIC = $returned_NRIC->fetchArray()) {
                $NRIC = $resultNRIC['NRIC'];
                array_push($NRICList, $NRIC);
            }

            //Load data from Screening&FollowUp file
            foreach ($worksheetNames as $sheetName) {
                $objPHPExcel->setActiveSheetIndexByName($sheetName);
                $sheet = $objPHPExcel->getActiveSheet();
                $rowData = $sheet->toArray(NULL, true, true, true);

                /* Per Row */
                if ($sheetName == 'Demographics') {
                    //Read each line of the spreadsheet
                    $count = 0;
                    $db->exec('begin');
                    foreach ($rowData as $keyRowNumber => $valueRowArray) {
                        if ($keyRowNumber == 1) {
                            //Validate: Number of column
                            if (sizeof($valueRowArray) != 11) {
                                $_SESSION["FileValidation"] = 'Demographics worksheet must have 11 columns!';
                                header("Location: dataprocessing.php");
                                exit;
                            }
                        } else {
                            $rowObject = array_values($valueRowArray);

                            $UpdateNRIC = $rowObject[0];

                            if ($UpdateNRIC !== '') {

                                for ($x = 0; $x <= 10; $x++) {
                                    if (strpos($rowObject[$x], "'")) {
                                        $rowObject[$x] = str_replace("'", "", $rowObject[$x]);
                                    }

                                    if (strcmp($rowObject[$x], '') == 0) {
                                        $rowObject[$x] = 'NA';
                                    }
                                }

                                //Extract Postal Code
                                $AddressData = $rowObject[2];

                                if (substr($AddressData, -6) == "SINGAPORE") {
                                    $Postal = "NA";
                                } else {
                                    $Postal = substr($AddressData, strrpos($AddressData, " ") + 1);
                                }
                                array_push($rowObject, $Postal);

                                if (in_array($UpdateNRIC, $NRICList)) {
                                    $count++;

                                    $sqlquery = <<<EOF
                                    UPDATE GeoCode 
                                    SET Name = '$rowObject[1]', Address = '$rowObject[2]',  
                                    Occupation = '$rowObject[5]', MobilePhone = '$rowObject[6]', HomePhone = '$rowObject[7]', OfficePhone = '$rowObject[8]', 
                                    `Addr_Postal.Code`= '$rowObject[11]', latitude = NULL, longitude = NULL, ResidentialNum = NULL
                                    WHERE NRIC= '$UpdateNRIC';
EOF;
                                    $ret = $db->exec($sqlquery);

                                    if (!$ret) {
                                        echo $db->lastErrorMsg();
                                    }

                                    if ($count % 2000 == 0) {
                                        $db->exec('commit');
                                        $db->exec('begin');
                                    }
                                } else {
                                    $ExcelErrorTest = false;
                                    fputcsv($ExcelError, array($UpdateNRIC, 'NRIC is not existed in Database!', 'Demographic'));
                                }
                            }
                        }
                    }
                    $db->exec('commit');

                    $_SESSION["DemographicCount"] = $count;
                }

                /*
                  //Screening Records
                  if ($sheetName === 'Screening Records') {
                  $count = 0;
                  $db->exec('begin');
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

                  for ($x = 0; $x <= 24; $x++) {
                  if (strpos($rowObject[$x], "'")) {
                  $rowObject[$x] = str_replace("'", "", $rowObject[$x]);
                  }

                  if (strcmp($rowObject[$x], '') == 0) {
                  $rowObject[$x] = 'NA';
                  }
                  }

                  //Load Data:
                  $value = implode("','", $rowObject);
                  $sqlquery = <<<EOF
                  INSERT OR REPLACE INTO ScreeningRecords VALUES ( '$value');
                  EOF;
                  $ret = $db->exec($sqlquery);

                  if (!$ret) {
                  echo 'hell';
                  echo $db->lastErrorMsg();
                  }

                  if ($count % 2000 == 0) {
                  $db->exec('commit');
                  $db->exec('begin');
                  }
                  }
                  }
                  $db->exec('commit');

                  $_SESSION["ScreeningCount"] = $count;
                  }
                 */

                //Call Log Data
                if ($sheetName === 'Call Log Data') {
                    $count = 0;
                    $db->exec('begin');
                    foreach ($rowData as $keyRowNumber => $valueRowArray) {

                        if ($keyRowNumber == 1) {
                            //Validate: Number of column
                            if (sizeof($valueRowArray) != 12) {
                                $_SESSION["FileValidation"] = 'Call Log Data worksheet must have 12 columns!';
                                header("Location: dataprocessing.php");
                                exit;
                            }
                        } else {
                            $rowObject = array_values($valueRowArray);

                            $CallLogNRIC = $rowObject[0];

                            if ($CallLogNRIC !== '') {

                                for ($x = 0; $x <= 11; $x++) {
                                    if (strpos($rowObject[$x], "'")) {
                                        $rowObject[$x] = str_replace("'", "", $rowObject[$x]);
                                    }

                                    if (strcmp($rowObject[$x], '') == 0) {
                                        $rowObject[$x] = 'NA';
                                    }
                                }

                                if (in_array($CallLogNRIC, $NRICList)) {
                                    $count++;

                                    $value = implode("','", $rowObject);
                                    $sqlquery = <<<EOF
                        INSERT OR REPLACE INTO CallLogData VALUES ( '$value');
EOF;
                                    $ret = $db->exec($sqlquery);

                                    if (!$ret) {
                                        echo $db->lastErrorMsg();
                                    }

                                    if ($count % 2000 == 0) {
                                        $db->exec('commit');
                                        $db->exec('begin');
                                    }
                                } else {
                                    $ExcelErrorTest = false;
                                    fputcsv($ExcelError, array($CallLogNRIC, 'NRIC is not existed in Database!', 'Call Log Data'));
                                }
                            }
                        }
                    }
                    $db->exec('commit');
                    $_SESSION["CallLogCount"] = $count;
                }

                //Assessment Data
                if ($sheetName === 'Assessment Data') {
                    $count = 0;
                    $db->exec('begin');
                    foreach ($rowData as $keyRowNumber => $valueRowArray) {

                        if ($keyRowNumber == 1) {
                            //Validate: Number of column
                            if (sizeof($valueRowArray) != 40) {
                                $_SESSION["FileValidation"] = 'Assessment Data worksheet must have 40 columns!';
                                header("Location: dataprocessing.php");
                                exit;
                            }
                        } else {
                            $rowObject = array_values($valueRowArray);

                            $AssessmentNRIC = $rowObject[0];
                            if ($AssessmentNRIC !== '') {
                                for ($x = 0; $x <= 39; $x++) {
                                    if (strpos($rowObject[$x], "'")) {
                                        $rowObject[$x] = str_replace("'", "", $rowObject[$x]);
                                    }

                                    if (strcmp($rowObject[$x], '') == 0) {
                                        $rowObject[$x] = 'NA';
                                    }
                                }

                                if (in_array($AssessmentNRIC, $NRICList)) {
                                    $count++;
                                    $value = implode("','", $rowObject);
                                    $sqlquery = <<<EOF
                                INSERT OR REPLACE INTO AssessmentData VALUES ( '$value');
EOF;
                                    $ret = $db->exec($sqlquery);

                                    if (!$ret) {
                                        echo $db->lastErrorMsg();
                                    }

                                    if ($count % 2000 == 0) {
                                        $db->exec('commit');
                                        $db->exec('begin');
                                    }
                                } else {
                                    $ExcelErrorTest = false;
                                    fputcsv($ExcelError, array($AssessmentNRIC, 'NRIC is not existed in Database!', 'Assessment Data'));
                                }
                            }
                        }
                    }
                    $db->exec('commit');
                    $_SESSION["AssessmentDataCount"] = $count;
                }

                //Report Collection Data
                if ($sheetName === 'Report Collection Data') {
                    $count = 0;
                    $db->exec('begin');
                    foreach ($rowData as $keyRowNumber => $valueRowArray) {
                        if ($keyRowNumber == 1) {
                            //Validate: Number of column
                            if (sizeof($valueRowArray) != 5) {
                                $_SESSION["FileValidation"] = 'Report Collection Data worksheet must have 5 columns!';
                                header("Location: dataprocessing.php");
                                exit;
                            }
                        } else {

                            $rowObject = array_values($valueRowArray);
                            $collectionNRIC = $rowObject[0];

                            if ($collectionNRIC !== '') {

                                for ($x = 0; $x <= 4; $x++) {
                                    if (strpos($rowObject[$x], "'")) {
                                        $rowObject[$x] = str_replace("'", "", $rowObject[$x]);
                                    }

                                    if (strcmp($rowObject[$x], '') == 0) {
                                        $rowObject[$x] = 'NA';
                                    }
                                }

                                if (in_array($collectionNRIC, $NRICList)) {
                                    $count++;

                                    $value = implode("','", $rowObject);
                                    $sqlquery = <<<EOF
                                    INSERT OR REPLACE INTO ReportCollectionData VALUES ( '$value');
EOF;
                                    $ret = $db->exec($sqlquery);

                                    if (!$ret) {
                                        echo $db->lastErrorMsg();
                                    }

                                    if ($count % 2000 == 0) {
                                        $db->exec('commit');
                                        $db->exec('begin');
                                    }
                                } else {
                                    $ExcelErrorTest = false;
                                    fputcsv($ExcelError, array($collectionNRIC, 'NRIC is not existed in Database!', 'Report Collection Data'));
                                }
                            }
                        }
                    }
                    $db->exec('commit');
                    $_SESSION["ReportCollectionDataCount"] = $count;
                }
            }

            //Update GeoCode
            $filename = 'C:/wamp/www/PDashboard/GeoCodeError.csv';

            if (file_exists($filename)) {
                unlink($filename);
                $GeoCodeError = fopen("C:/wamp/www/PDashboard/GeoCodeError.csv", "w");
            } else {
                $GeoCodeError = fopen("C:/wamp/www/PDashboard/GeoCodeError.csv", "w");
            }

            $title = "NRIC, Address, Postal Code, ErrorType";
            fputcsv($GeoCodeError, explode(',', $title));

            $csvRecord = array();
            $GeoErrorReport = true;

            $sqlquery = <<<EOF
                CREATE TABLE TempGeoCode AS
                SELECT UpdateGeoCode.NRIC AS NRIC, UpdateGeoCode.Address AS Address, 
                       UpdateGeoCode.`Addr_Postal.Code` AS `Addr_Postal.Code`, SGPostal.latitude AS latitude, 
                       SGPostal.longitude AS longitude, SGPostal.ResidentialNum AS ResidentialNum 
                FROM 
                (SELECT NRIC, Address, `Addr_Postal.Code` FROM GeoCode WHERE longitude IS NULL) AS UpdateGeoCode
                LEFT JOIN SGPostal
                ON `Addr_Postal.Code` = SGPostal.postcode;
EOF;

            $geoRet = $db->exec($sqlquery);

            if (!$geoRet) {
                echo $db->lastErrorMsg();
            }


            $sqlGeoCodeRow = <<<EOF
                SELECT NRIC, Address, `Addr_Postal.Code` FROM TempGeoCode WHERE longitude IS NULL;        
EOF;

            $returned_set = $db->query($sqlGeoCodeRow);

            while ($result = $returned_set->fetchArray()) {
                $NRIC = $result['NRIC'];
                $Address = $result['Address'];
                $PostalCodeGoogle = $result['Addr_Postal.Code'];

                if (strlen($PostalCodeGoogle) == 6 || strlen($PostalCodeGoogle) == 5) {
                    //GeoCode
                    $url = "https://maps.googleapis.com/maps/api/geocode/json?sensor=false&key=AIzaSyCnmEGhcJgjntBiImyqIufnMf_CqL1mWPs&address=Singapore" . urlencode($PostalCodeGoogle);
                    $resp_json = file_get_contents($url);
                    $resp = json_decode($resp_json, true);

                    if ($resp['status'] === 'OK') {
                        $lati = $resp['results'][0]['geometry']['location']['lat'];
                        $longi = $resp['results'][0]['geometry']['location']['lng'];

                        $GeoCodeSql = <<<EOF
                            UPDATE TempGeoCode
                            SET latitude = '$lati', longitude = '$longi'
                            WHERE `Addr_Postal.Code` = '$PostalCodeGoogle';
EOF;

                        $GeoCodeResult = $db->exec($GeoCodeSql);

                        if (!$GeoCodeResult) {
                            echo $db->lastErrorMsg();
                        }
                    } else {
                        $GeoCodeSql = <<<EOF
                            UPDATE TempGeoCode
                            SET latitude = '-999.0', longitude = '-999.0'
                            WHERE `Addr_Postal.Code` = '$PostalCodeGoogle';
EOF;
                        $GeoCodeResult = $db->exec($GeoCodeSql);

                        if (!$GeoCodeResult) {
                            echo $db->lastErrorMsg();
                        }

                        $GeoErrorStr = $NRIC . "," . $Address . "," . $PostalCodeGoogle . "," . "Invalid Address!";
                        array_push($csvRecord, $GeoErrorStr);

                        $GeoErrorReport = false;
                    }
                } else {
                    $GeoCodeSql = <<<EOF
                        UPDATE TempGeoCode
                        SET latitude = '-999.0', longitude = '-999.0'
                        WHERE `Addr_Postal.Code` = '$PostalCodeGoogle';
EOF;
                    $GeoCodeResult = $db->exec($GeoCodeSql);

                    if (!$GeoCodeResult) {
                        echo $db->lastErrorMsg();
                    }

                    $GeoErrorStr = $NRIC . "," . $Address . "," . $PostalCodeGoogle . "," . "Invalid Address!";
                    array_push($csvRecord, $GeoErrorStr);

                    $GeoErrorReport = false;
                }
            }

            if ($GeoErrorReport == false) {
                $_SESSION["GeoErrorReport"] = "GeoErrorReport";

                foreach ($csvRecord as $line) {
                    echo var_dump($line);

                    if (sizeof($csvRecord) == 1) {
                        fputcsv($GeoCodeError, explode(',', $line));
                    } else {
                        fputcsv($GeoCodeError, explode(',', $line), ',');
                    }
                }
            }

            //Duplicate reords to GeoCode
            $updateKTPH = <<<EOF
            UPDATE GeoCode
            SET latitude = (SELECT TempGeoCode.latitude FROM TempGeoCode WHERE TempGeoCode.NRIC = GeoCode.NRIC ),
                longitude = (SELECT TempGeoCode.longitude FROM TempGeoCode WHERE TempGeoCode.NRIC = GeoCode.NRIC),
                ResidentialNum = (SELECT TempGeoCode.ResidentialNum FROM TempGeoCode WHERE TempGeoCode.NRIC = GeoCode.NRIC)
            WHERE EXISTS
            (SELECT TempGeoCode.latitude, TempGeoCode.longitude, TempGeoCode.ResidentialNum FROM TempGeoCode WHERE TempGeoCode.NRIC = GeoCode.NRIC );
EOF;
            $updateKTPHResult = $db->exec($updateKTPH);

            if (!$updateKTPHResult) {
                echo $db->lastErrorMsg();
            }
            //End of GeoCode Report 

            if ($ExcelErrorTest == false) {
                $_SESSION["ExcelErrorReport"] = "ExcelErrorReport";
            }

            fclose($GeoCodeError);
        }

        fclose($ExcelError);
        $db->close();
    } catch (Exception $e) {
        die('Error loading file "' . pathinfo($tmpName, PATHINFO_BASENAME) . '": ' . $e->getMessage());
    }
}

function loadCSVData($tmpName) {
    $cacheMethod = PHPExcel_CachedObjectStorageFactory::cache_in_memory;
    $cacheSettings = array('memoryCacheSize' => '2048M');
    PHPExcel_Settings::setCacheStorageMethod($cacheMethod, $cacheSettings);


    //Backup current database
    $files = glob('C:/wamp/www/PDashboard/DatabaseBackup/*'); // get all file names
    foreach ($files as $file) { // iterate files
        if (is_file($file)) {
            unlink($file);
        }
    }

    $myFile = 'C:/wamp/www/PDashboard/KTPHTest.db';

    if (file_exists($myFile)) {
        $newFileName = 'KTPHBackup.' . date("MdYGi") . '.db';
        $newfile = 'C:/wamp/www/PDashboard/DatabaseBackup/' . $newFileName;

        if (!copy($myFile, $newfile)) {
            echo "failed to copy the file";
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

    //Delete temp joined table;
    $sqlDelete = <<<EOF
    DELETE FROM ktphalldata;
    DROP TABLE IF EXISTS TempGeoCode;
EOF;

    $result = $db->exec($sqlDelete);

    if (!$result) {
        echo $db->lastErrorMsg();
    }

    sleep(5);

    //Read CSV 
    $file_handle = fopen($tmpName, "r") or die('Could not read file!');

    $counter = 0;

    $db->exec('begin');
    while (!feof($file_handle)) {
        //Read Title
        $line_of_text = fgetcsv($file_handle);

        if ($line_of_text != '') {

            if ($counter == 0) {
                if (count($line_of_text) != 93) {
                    $_SESSION["FileValidation"] = 'All KTPH screening data must have 93 columns!';
                    header("Location: dataprocessing.php");
                    exit;
                }
            }

            //Read Data
            if ($counter != 0) {
                $csvArray = array();
                for ($x = 0; $x <= 92; $x++) {
                    if (strpos($line_of_text[$x], "'")) {
                        $line_of_text[$x] = str_replace("'", "", $line_of_text[$x]);
                    }

                    if (strcmp($line_of_text[$x], '') == 0) {
                        $line_of_text[$x] = 'NA';
                    }
                    //Validation:!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!

                    array_push($csvArray, $line_of_text[$x]);
                }

                //list($NRIC, $Name, $Address, $DOB, $GenderFullText, $Occupation, $MobilePhone, $HomePhone, $OfficePhone, $L_Glucose_f, $L_Trig_f, $L_Chol_f, $L_HDL_f, $L_LDL_f, $M_Systolic_1st, $M_Diastolic_1st, $M_Weight, $M_Height, $f_BMI, $M_Waist, $X8Q_LS_Smoking, $X8Q_LS_Exercise, $X8Q_MH_HeartAttack, $X8Q_MH_Stroke, $X8Q_MH_Diabetes, $X8Q_MH_HBP, $X8Q_MH_HBldChol, $UnhealthyCat, $PreferredLanguage, $MeasurementAttDate, $action, $Zone, $Addr_PostalCode, $Age, $AgeGrp, $BloodPressure, $BMIGrp, $BPHigh, $Cholesterol, $Combine, $Control, $ControlGrp, $controlTree, $DateOfPolyVisit, $DHLDescNHGP, $Diabetes, $DiagnosisAtPoly, $DrOutcome, $existNewGrp, $Fasting, $FollowUpPolyClinic, $FollowupType, $fScore, $fScoreCat, $fScoreType, $Habits, $healthRisk, $HealthState, $Healthy, $MeasurementMonth, $medicalHistCholSugBp, $MOH_RHS2015, $Nationality, $New, $NurseAction, $OccupationType, $Q_PH_PCP, $Q_TAXI_Ache, $Q_TAXI_AveSleep, $Q_TAXI_LengthAche, $RaceFullText, $Remarks, $repeatVisit, $revisit, $scnZone, $screening, $StaffEducation, $SugarHigh, $URA_DGP, $VisitFY, $VisitsNHGP, $WeightCategory, $X10YrRisk, $X8Q_FH_DadBroCHD, $X8Q_FH_MomSisCHD, $X8Q_GN_Health, $X8Q_GN_Helpful, $X8Q_Living, $X8Q_LS_FruitsVeg, $X8Q_LS_Stress, $X8Q_MH_DiabetesFoot, $X8Q_MH_DiabetesTrt, $X8Q_MH_HBldCholTrt) = $csvArray;
                $NRIC = $csvArray[0];
                //Transfer Measurement.Att.Date
                $time = strtotime($csvArray[29]);
                $csvArray[29] = date('Y-m-d', $time);


                $valueCSV = implode("','", $csvArray);
                $sqlqueryCSV = <<<EOF
            INSERT OR REPLACE INTO ktphalldata VALUES ('$valueCSV');
            INSERT OR REPLACE INTO NRICReference VALUES ('$NRIC');
EOF;

                $ret = $db->exec($sqlqueryCSV);

                if (!$ret) {
                    echo $db->lastErrorMsg();
                }
            }

            $counter++;

            if ($counter % 3000 == 0) {
                $db->exec('commit');
                $db->exec('begin');
            }
        }
    }
    $db->exec('commit');

    //GeoCoding
    //Error Log
    $filename = 'C:/wamp/www/PDashboard/GeoCodeError.csv';

    if (file_exists($filename)) {
        unlink($filename);
        $GeoCodeError = fopen("C:/wamp/www/PDashboard/GeoCodeError.csv", "w");
    } else {
        $GeoCodeError = fopen("C:/wamp/www/PDashboard/GeoCodeError.csv", "w");
    }

    $title = "NRIC, Measurement Date, Address, Postal Code, ErrorType";
    fputcsv($GeoCodeError, explode(',', $title));

    $csvRecord = array();

    $GeoErrorReport = true;


    //(WHERE longitude IS NULL)
    $sqlquery = <<<EOF
        CREATE TABLE TempGeoCode AS
        SELECT 
            TempKTPH.NRIC as NRIC, TempKTPH.Name as Name, TempKTPH.Address as Address, TempKTPH.DOB as DOB, 
            TempKTPH.`Gender.Full.Text` as `Gender.Full.Text`, TempKTPH.Occupation as Occupation, TempKTPH.MobilePhone as MobilePhone, 
            TempKTPH.HomePhone as HomePhone, TempKTPH.OfficePhone as OfficePhone, TempKTPH.L_Glucose_f as L_Glucose_f, 
            TempKTPH.L_Trig_f as L_Trig_f, TempKTPH.L_Chol_f as L_Chol_f, TempKTPH.L_HDL_f as L_HDL_f, 
            TempKTPH.L_LDL_f as L_LDL_f, TempKTPH.M_Systolic_1st as M_Systolic_1st, TempKTPH.M_Diastolic_1st as M_Diastolic_1st, 
            TempKTPH.M_Weight as M_Weight, TempKTPH.M_Height as M_Height, TempKTPH.f_BMI as f_BMI, TempKTPH.M_Waist as M_Waist, 
            TempKTPH.X8Q_LS_Smoking as X8Q_LS_Smoking, TempKTPH.X8Q_LS_Exercise as X8Q_LS_Exercise, TempKTPH.X8Q_MH_HeartAttack as X8Q_MH_HeartAttack, 
            TempKTPH.X8Q_MH_Stroke as X8Q_MH_Stroke, TempKTPH.X8Q_MH_Diabetes as X8Q_MH_Diabetes, TempKTPH.X8Q_MH_HBP as X8Q_MH_HBP, 
            TempKTPH.X8Q_MH_HBldChol as X8Q_MH_HBldChol, TempKTPH.UnhealthyCat as UnhealthyCat, TempKTPH.PreferredLanguage as PreferredLanguage, 
            TempKTPH.`Measurement.Att.Date` as `Measurement.Att.Date`, TempKTPH.action as action, TempKTPH.Zone as Zone, 
            TempKTPH.`Addr_Postal.Code` as `Addr_Postal.Code`, TempKTPH.Age as Age, TempKTPH.AgeGrp as AgeGrp, TempKTPH.BloodPressure as BloodPressure, 
            TempKTPH.BMIGrp as BMIGrp, TempKTPH.BPHigh as BPHigh, TempKTPH.Cholesterol as Cholesterol, TempKTPH.Combine as Combine, 
            TempKTPH.Control as Control, TempKTPH.ControlGrp as ControlGrp, TempKTPH.controlTree as controlTree, 
            TempKTPH.DateOfPolyVisit as DateOfPolyVisit, TempKTPH.DHLDescNHGP as DHLDescNHGP, TempKTPH.Diabetes as Diabetes, 
            TempKTPH.DiagnosisAtPoly as DiagnosisAtPoly, TempKTPH.DrOutcome as DrOutcome, TempKTPH.existNewGrp as existNewGrp, 
            TempKTPH.Fasting as Fasting, TempKTPH.FollowUpPolyClinic as FollowUpPolyClinic, TempKTPH.FollowupType as FollowupType,
            TempKTPH.fScore as fScore, TempKTPH.fScoreCat as fScoreCat, TempKTPH.fScoreType as fScoreType, TempKTPH.Habits as Habits, 
            TempKTPH.healthRisk as healthRisk, TempKTPH.HealthState as HealthState, TempKTPH.Healthy as Healthy, 
            TempKTPH.MeasurementMonth as MeasurementMonth, TempKTPH.medicalHistCholSugBp as medicalHistCholSugBp, 
            TempKTPH.MOH_RHS2015 as MOH_RHS2015, TempKTPH.Nationality as Nationality, TempKTPH.New as New, TempKTPH.NurseAction as NurseAction, 
            TempKTPH.OccupationType as OccupationType, TempKTPH.Q_PH_PCP as Q_PH_PCP, TempKTPH.Q_TAXI_Ache as Q_TAXI_Ache, TempKTPH.Q_TAXI_AveSleep as Q_TAXI_AveSleep, 
            TempKTPH.Q_TAXI_LengthAche as Q_TAXI_LengthAche, TempKTPH.`Race.Full.Text` as `Race.Full.Text`, TempKTPH.Remarks as Remarks, 
            TempKTPH.repeatVisit as repeatVisit, TempKTPH.revisit as revisit, TempKTPH.scnZone as scnZone, TempKTPH.screening as screening, 
            TempKTPH.StaffEducation as StaffEducation, TempKTPH.SugarHigh as SugarHigh, TempKTPH.URA_DGP as URA_DGP, TempKTPH.VisitFY as VisitFY, 
            TempKTPH.VisitsNHGP as VisitsNHGP, TempKTPH.WeightCategory as WeightCategory, TempKTPH.X10YrRisk as X10YrRisk, 
            TempKTPH.X8Q_FH_DadBroCHD as X8Q_FH_DadBroCHD, TempKTPH.X8Q_FH_MomSisCHD as X8Q_FH_MomSisCHD, TempKTPH.X8Q_GN_Health as X8Q_GN_Health,
            TempKTPH.X8Q_GN_Helpful as X8Q_GN_Helpful, TempKTPH.X8Q_Living as X8Q_Living, TempKTPH.X8Q_LS_FruitsVeg as X8Q_LS_FruitsVeg, 
            TempKTPH.X8Q_LS_Stress as X8Q_LS_Stress, TempKTPH.X8Q_MH_DiabetesFoot as X8Q_MH_DiabetesFoot, TempKTPH.X8Q_MH_DiabetesTrt as X8Q_MH_DiabetesTrt,
            TempKTPH.X8Q_MH_HBldCholTrt as X8Q_MH_HBldCholTrt, SGPostal.latitude as latitude, SGPostal.longitude as longitude, SGPostal.ResidentialNum as ResidentialNum
        FROM 
        (SELECT * FROM ktphalldata) AS TempKTPH
        LEFT JOIN SGPostal
        ON `Addr_Postal.Code` = SGPostal.postcode;
EOF;

    $geoRet = $db->exec($sqlquery);

    if (!$geoRet) {
        echo $db->lastErrorMsg();
    }

    $sqlGeoCodeRow = <<<EOF
        SELECT NRIC,Address, `Addr_Postal.Code`, `Measurement.Att.Date` FROM TempGeoCode WHERE longitude IS NULL;        
EOF;

    $returned_set = $db->query($sqlGeoCodeRow);

    while ($result = $returned_set->fetchArray()) {
        $NRIC = $result['NRIC'];
        $MeasurementAttDate = $result['Measurement.Att.Date'];
        $Address = $result['Address'];
        $PostalCodeGoogle = $result['Addr_Postal.Code'];

        if (strlen($PostalCodeGoogle) == 6 || strlen($PostalCodeGoogle) == 5) {
            //GeoCode
            $url = "https://maps.googleapis.com/maps/api/geocode/json?sensor=false&key=AIzaSyCnmEGhcJgjntBiImyqIufnMf_CqL1mWPs&address=Singapore" . urlencode($PostalCodeGoogle);
            $resp_json = file_get_contents($url);
            $resp = json_decode($resp_json, true);

            if ($resp['status'] === 'OK') {
                $lati = $resp['results'][0]['geometry']['location']['lat'];
                $longi = $resp['results'][0]['geometry']['location']['lng'];
                //$formatted_address = $resp['results'][0]['formatted_address'];

                $GeoCodeSql = <<<EOF
                UPDATE TempGeoCode
                SET latitude = '$lati', longitude = '$longi'
                WHERE `Addr_Postal.Code` = '$PostalCodeGoogle';
EOF;
                $GeoCodeResult = $db->exec($GeoCodeSql);

                if (!$GeoCodeResult) {
                    echo $db->lastErrorMsg();
                }
            } else {
                $GeoCodeSql = <<<EOF
                UPDATE TempGeoCode
                SET latitude = '-999.0', longitude = '-999.0'
                WHERE `Addr_Postal.Code` = '$PostalCodeGoogle';
EOF;
                $GeoCodeResult = $db->exec($GeoCodeSql);

                if (!$GeoCodeResult) {
                    echo $db->lastErrorMsg();
                }

                $GeoErrorStr = $NRIC . "," . $MeasurementAttDate . "," . $Address . "," . $PostalCodeGoogle . "Invalid Address!";
                array_push($csvRecord, $GeoErrorStr);

                $GeoErrorReport = false;
            }
        } else {
            $GeoCodeSql = <<<EOF
            UPDATE TempGeoCode
            SET latitude = '-999.0', longitude = '-999.0'
            WHERE `Addr_Postal.Code` = '$PostalCodeGoogle';                         
EOF;
            $GeoCodeResult = $db->exec($GeoCodeSql);

            if (!$GeoCodeResult) {
                echo $db->lastErrorMsg();
            }

            $GeoErrorStr = $NRIC . "," . $MeasurementAttDate . "," . $Address . "," . $PostalCodeGoogle . "," . "Invalid Address!";
            array_push($csvRecord, $GeoErrorStr);

            $GeoErrorReport = false;
        }
    }

    if ($GeoErrorReport == false) {
        $_SESSION["GeoErrorReport"] = "GeoErrorReport";

        foreach ($csvRecord as $line) {
            if (sizeof($csvRecord) == 1) {
                fputcsv($GeoCodeError, explode(',', $line));
            } else {
                fputcsv($GeoCodeError, explode(',', $line), ',');
            }
        }
    }


    //Duplicate reords to GeoCode
    $updateKTPH = <<<EOF
    INSERT OR REPLACE INTO GeoCode
    SELECT * FROM TempGeoCode 
    WHERE NRIC = TempGeoCode.NRIC AND `Measurement.Att.Date` = TempGeoCode.`Measurement.Att.Date`;
EOF;

    $updateKTPHResult = $db->exec($updateKTPH);

    if (!$updateKTPHResult) {
        echo $db->lastErrorMsg();
    }

    fclose($GeoCodeError);
    fclose($file_handle);
    $db->close();

    $_SESSION['CSVCount'] = $counter - 1;
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