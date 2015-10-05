<?php

session_start();

ini_set('memory_limit', '2048M');
set_time_limit('3600');

include 'PHPExcelLibrary/PHPExcel.php';
include 'PHPExcelLibrary/PHPExcel/IOFactory.php';

function loadData($tmpName) {

    //If KTPHTest.db is not exist
    class MyDB extends SQLite3 {

        function __construct() {
            $this->open('KTPHTest.db');
        }

    }

    $db = new MyDB();

    if (!$db) {
        $response = $db->lastErrorMsg();
        echo $response;
        exit;
    }

    echo 'Database created successfully';

    $myFile = "C:/wamp/www/FYP1/PDashboard/KTPHTest.db";

    $newFileName = 'KTPHBackup.' . date("MdYGi") . '.db';
    $newfile = 'C:/wamp/www/FYP1/PDashboard/' . $newFileName;

    if (!copy($myFile, $newfile)) {
        echo "failed to copy the file";
    }

    $sqlDelete = <<<EOF
            
    DROP TABLE IF EXISTS ScreeningRecords;
    DROP TABLE IF EXISTS Demographics;  
    DROP TABLE IF EXISTS SGPostal;    
    DROP TABLE IF EXISTS GeoCode;
            
EOF;

    $result = $db->exec($sqlDelete);

    if (!$result) {
        echo $db->lastErrorMsg();
    }
    echo 'Table deleted successfully';


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
        L_HDL_f	FLOAT,
        L_LDL_f	FLOAT,
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
        Zone VARCHAR(10)
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
    }
    echo 'Table created successfully';

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

        //Validate: Number of sheets.
        if (sizeof($worksheetNames) != 3) {
            $_SESSION["response"] = 'File must contain 3 worksheets!';
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
        foreach ($worksheetNames as $keyWorksheet => $sheetName) {

            $objPHPExcel->setActiveSheetIndexByName($sheetName);
            $sheet = $objPHPExcel->getActiveSheet();
            $rowData = $sheet->toArray(null, true, true, true);

            /* Per Row */

            if ($sheetName == 'Demographics') {
                $count = 0;
                $db->exec('begin');

                foreach ($rowData as $keyRowNumber => $valueRowArray) {
                    $count++;
                    if ($keyRowNumber == 1) {
                        //Validate: Number of column
                        if (sizeof($valueRowArray) != 11) {
                            $_SESSION["response"] = 'Demographics worksheet must have 11 columns!';
                            header("Location: dataprocessing.php");
                            exit;
                        }
                    } else {
                        $rowObject = array_values($valueRowArray);

                        $rowObject[2] = preg_replace("/[^0-9a-z]+/i", "", $rowObject[2]);

                        //Transform:Address to Postal Code
                        $Postal = substr($rowObject[2], -6);

                        if (!ctype_digit($Postal)) {
                            $Postal = str_replace($Postal[0], "0", $Postal);
                        }

                        array_push($rowObject, $Postal);

                        //Load Data: 

                        $value = implode("','", $rowObject);
                        $sqlquery = <<<EOF
                        INSERT OR IGNORE INTO Demographics VALUES ( '$value');
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


                $sqlDemographicRow = <<<EOF
                SELECT Count(*) as count FROM Demographics;        
EOF;
                $rows = $db->query($sqlDemographicRow);
                $row = $rows->fetchArray();
                $loadRecord = $row['count'];


                $_SESSION["Demographic"] = $loadRecord;
            }


            if ($sheetName == 'Screening Records') {
                $count = 0;
                $db->exec('begin');

                foreach ($rowData as $keyRowNumber => $valueRowArray) {
                    $count++;
                    if ($keyRowNumber == 1) {
                        //Validate: Number of column
                        if (sizeof($valueRowArray) != 25) {
                            $_SESSION["response"] = 'Screening Records worksheet must have 25 columns!';
                            header("Location: dataprocessing.php");
                            exit;
                        }
                    } else {
                        $rowObject = array_values($valueRowArray);

                        //Load Data: 
                        $value = implode("','", $rowObject);
                        $sqlquery = <<<EOF
                        INSERT OR IGNORE INTO ScreeningRecords VALUES ( '$value');
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

                $sqlScreeningRecordsRow = <<<EOF
                SELECT Count(*) as count FROM ScreeningRecords;        
EOF;
                $rows = $db->query($sqlScreeningRecordsRow);
                $row = $rows->fetchArray();
                $loadRecord = $row['count'];

                $_SESSION["Screening"] = $loadRecord;
            }


            if ($sheetName == 'SGPostal') {

                $sqlSGPostalRow = <<<EOF
                SELECT Count(*) as count FROM SGPostal;        
EOF;
                $rows = $db->query($sqlSGPostalRow);
                $row = $rows->fetchArray();
                $loadRecord = $row['count'];


                if ($loadRecord == 0) {
                    $count = 0;
                    $db->exec('begin');

                    foreach ($rowData as $keyRowNumber => $valueRowArray) {
                        $count++;
                        if ($keyRowNumber == 1) {
                            //Validate: Number of column
                            if (sizeof($valueRowArray) != 8) {
                                $_SESSION["response"] = 'SGPostal worksheet must have 8 columns!';
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

                            if ($count % 5000 == 0) {
                                $db->exec('commit');
                                $db->exec('begin');
                            }
                        }
                    }

                    $db->exec('commit');

                    $sqlSGPostalRow = <<<EOF
                SELECT Count(*) as count FROM SGPostal;        
EOF;
                    $rows = $db->query($sqlSGPostalRow);
                    $row = $rows->fetchArray();
                    $loadRecord = $row['count'];


                    $_SESSION["Postal"] = $loadRecord;
                }
            }
        }

        //Geo Coding
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
        }

        $sqlGeoCodeRow = <<<EOF
        SELECT PostalCode FROM GeoCode;        
EOF;
        $returned_set = $db->query($sqlGeoCodeRow);

        while ($result = $returned_set->fetchArray()) {
            $PostalCodeGoogle = $result['PostalCode'];
            $url = "http://maps.googleapis.com/maps/api/geocode/json?sensor=false&address=" . urlencode($PostalCodeGoogle);
            $resp_json = file_get_contents($url);
            $resp = json_decode($resp_json, true);

            if ($resp['status'] == 'OK') {
                $lati = $resp['results'][0]['geometry']['location']['lat'];
                $longi = $resp['results'][0]['geometry']['location']['lng'];
                //$formatted_address = $resp['results'][0]['formatted_address'];

                
                $GeoCodeSql = <<<EOF
              INSERT INTO GeoCode(latitude,longitude) VALUES('$lati','$longi');
EOF;
                $GeoCodeResult = $db->exec($GeoCodeSql);

                if (!$GeoCodeResult) {
                    echo $db->lastErrorMsg();
                }
                 
                 
            }
        }
    } catch (Exception $e) {
        die('Error loading file "' . pathinfo($tmpName, PATHINFO_BASENAME) . '": ' . $e->getMessage());
    }
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

    try {
        switch ($error) {
            case UPLOAD_ERR_OK:
                $valid = true;
                //validate file extensions
                if (!in_array($ext, array('xls', 'xlsx'))) {
                    $valid = false;
                    $response = 'Invalid file extension!';
                    throw new RuntimeException($response);
                }
                //validate file size
                /*
                  if ($size / 1024 / 1024 > 5) {
                  $valid = false;
                  $response = 'File size is exceeding maximum allowed size.';
                  throw new RuntimeException($response);
                  }
                 */
                //upload file
                if ($valid) {
                    //Call loadData function
                    call_user_func('loadData', $tmpName);
                    $targetPath = dirname(__FILE__) . DIRECTORY_SEPARATOR . 'uploads' . DIRECTORY_SEPARATOR . $name;
                    move_uploaded_file($tmpName, $targetPath);

                    //Redirect 
                    $_SESSION["response"] = "Successfully loaded to database!";
                    header("Location: dataprocessing.php");
                    exit;
                }
                break;
            /**
              case UPLOAD_ERR_INI_SIZE:
              $response = 'The uploaded file exceeds the upload_max_filesize directive in php.ini.';
              throw new RuntimeException($response);
              break;
             * */
            case UPLOAD_ERR_FORM_SIZE:
                $response = 'The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form.';
                throw new RuntimeException($response);
                break;
            case UPLOAD_ERR_PARTIAL:
                $response = 'The uploaded file was only partially uploaded.';
                throw new RuntimeException($response);
                break;
            case UPLOAD_ERR_NO_FILE:
                $response = 'No file was uploaded.';
                throw new RuntimeException($response);
                break;
            case UPLOAD_ERR_NO_TMP_DIR:
                $response = 'Missing a temporary folder. Introduced in PHP 4.3.10 and PHP 5.0.3.';
                throw new RuntimeException($response);
                break;
            case UPLOAD_ERR_CANT_WRITE:
                $response = 'Failed to write file to disk. Introduced in PHP 5.1.0.';
                throw new RuntimeException($response);
                break;
            case UPLOAD_ERR_EXTENSION:
                $response = 'File upload stopped by extension. Introduced in PHP 5.2.0.';
                throw new RuntimeException($response);
                break;
            default:
                $response = 'Unknown error';
                throw new RuntimeException($response);
                break;
        }
    } catch (RuntimeException $e) {
        $response = $e->getMessage();
        $_SESSION["response"] = $response;
        header("Location: dataprocessing.php");
    }
}
?>