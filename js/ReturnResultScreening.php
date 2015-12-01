<?php

ini_set('memory_limit','2048M');

//Create KTPHTest.db is not exist
class MyDBForCSV extends SQLite3 {

    function __construct() {
        $this->open('../KTPHTest.db');
    }

}

$db = new MyDBForCSV();

if (!$db) {
    echo $db->lastErrorMsg();
    exit;
}
//SELECT * FROM ktphalldata
$sql = <<<EOF
        Select Healthy, Habits, [Measurement.Att.Date], OccupationType, scnZone, [Gender.Full.Text],
        AgeGrp, [Race.Full.Text], StaffEducation, BMIGrp,X8Q_LS_Exercise, SugarHigh,
        BPHigh,X8Q_MH_DiabetesFoot, X8Q_Living, X8Q_LS_Smoking, Q_TAXI_Ache, Q_TAXI_LengthAche,
        Q_TAXI_AveSleep, NRIC 
        from GeoCode group by NRIC
EOF;

$returned_set = $db->query($sql);

$datapie = array();

while ($row = $returned_set->fetchArray(SQLITE3_ASSOC)) {
    $datapie[] = $row;
    
//$row['NRIC'] . $row['Name'] . $row['Address'] . $row['DOB'] . $row['Gender.Full.Text'] . $row['Occupation'] . $row['MobilePhone'] . $row['HomePhone'] . $row['OfficePhone'] . $row['L_Glucose_f'] . $row['L_Trig_f'] . $row['L_Chol_f'] . $row['L_HDL_f'] . $row['L_LDL_f'] . $row['M_Systolic_1st'] . $row['M_Diastolic_1st'] . $row['M_Weight'] . $row['M_Height'] . $row['f_BMI'] . $row['M_Waist'] . $row['X8Q_LS_Smoking'] . $row['X8Q_LS_Exercise'] . $row['X8Q_MH_HeartAttack'] . $row['X8Q_MH_Stroke'] . $row['X8Q_MH_Diabetes'] . $row['X8Q_MH_HBP'] . $row['X8Q_MH_HBldChol'] . $row['UnhealthyCat'] . $row['PreferredLanguage'] . $row['Measurement.Att.Date'] . $row['action'] . $row['Zone'] . $row['Addr_Postal.Code'] . $row['Age'] . $row['AgeGrp'] . $row['BloodPressure'] . $row['BMIGrp'] . $row['BPHigh'] . $row['Cholesterol'] . $row['Combine'] . $row['Control'] . $row['ControlGrp'] . $row['controlTree'] . $row['DateOfPolyVisit'] . $row['DHLDescNHGP'] . $row['Diabetes'] . $row['DiagnosisAtPoly'] . $row['DrOutcome'] . $row['existNewGrp'] . $row['Fasting'] . $row['FollowUpPolyClinic'] . $row['FollowupType'] . $row['fScore'] . $row['fScoreCat'] . $row['fScoreType'] . $row['Habits'] . $row['healthRisk'] . $row['HealthState'] . $row['Healthy'] . $row['MeasurementMonth'] . $row['medicalHistCholSugBp'] . $row['MOH_RHS2015'] . $row['Nationality'] . $row['New'] . $row['NurseAction'] . $row['OccupationType'] . $row['Q_PH_PCP'] . $row['Q_TAXI_Ache'] . $row['Q_TAXI_AveSleep'] . $row['Q_TAXI_LengthAche'] . $row['Race.Full.Text'] . $row['Remarks'] . $row['repeatVisit'] . $row['revisit'] . $row['scnZone'] . $row['screening'] . $row['StaffEducation'] . $row['SugarHigh'] . $row['URA_DGP'] . $row['VisitFY'] . $row['VisitsNHGP'] . $row['WeightCategory'] . $row['X10YrRisk'] . $row['X8Q_FH_DadBroCHD'] . $row['X8Q_FH_MomSisCHD'] . $row['X8Q_GN_Health'] . $row['X8Q_GN_Helpful'] . $row['X8Q_Living'] . $row['X8Q_LS_FruitsVeg'] . $row['X8Q_LS_Stress'] . $row['X8Q_MH_DiabetesFoot'] . $row['X8Q_MH_DiabetesTrt'] . $row['X8Q_MH_HBldCholTrt'] . $row['latitudeKTPH'] . $row['longitudeKTPH'];
}
//echo sizeof($datapie);
echo json_encode($datapie);

$db->close();

?>
