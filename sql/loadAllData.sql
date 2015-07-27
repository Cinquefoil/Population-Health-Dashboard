LOAD DATA INFILE 'C:/wamp/www/proj2 - Copy/allDataWGeo.csv  ' 
INTO TABLE ktphalldata 
FIELDS TERMINATED BY ',' 
ENCLOSED BY '"'
LINES TERMINATED BY '\n'
IGNORE 1 ROWS
(`Addr_Postal.Code`,`Zone`,@`Measurement.Att.Date`,`Gender.Full.Text`,`Race.Full.Text`,`Nationality`,`StaffEducation`,`M_Weight`,`M_Height`,`M_Waist`,
`M_Systolic_1st`,`M_Diastolic_1st`,`L_Chol_f`,`L_Trig_f`,`L_HDL_f`,`L_LDL_f`,`L_Glucose_f`,`f_BMI`,`X8Q_MH_HeartAttack`,`X8Q_MH_Stroke`,`X8Q_MH_Diabetes`,
`X8Q_MH_HBP`,`X8Q_MH_HBldChol`,`X8Q_MH_DiabetesTrt`,`X8Q_MH_HBldCholTrt`,`X8Q_FH_DadBroCHD`,`X8Q_FH_MomSisCHD`,`X8Q_LS_Smoking`,`X8Q_LS_Exercise`,`X8Q_LS_Stress`,
`X8Q_GN_Health`,`X8Q_LS_FruitsVeg`,`X8Q_MH_DiabetesFoot`,`X8Q_Living`,`Q_TAXI_Ache`,`Q_TAXI_LengthAche`,`Q_TAXI_AveSleep`,`PreferredLanguage`,`OccupationType`,
`Healthy`,`Habits`,`Diabetes`,`BloodPressure`,`Cholesterol`,`Combine`,`Overweight`,`New`,`UnhealthyCat`,`Control`,`ControlGrp`,`existNewGrp`,`controlTree`,`AgeGrp`,
`BMIGrp`,`Fasting`,`SugarHigh`,`BPHigh`,`HealthState`,`medicalHistCholSugBp`,`screening`,`action`,`scnZone`,`NurseAction`,`DrOutcome`,`month`,`revisit`,`DHLDescNHGP`,
`FollowUpPolyClinic`,`DiagnosisAtPoly`,`DateOfPolyVisit`,`FollowupType`,`VisitsNHGP`,`X10YrRisk`,`fScoreType`,`fScore`,`fScoreCat`,`VisitFY`,`URA_DGP`,`healthRisk`,
`NRIC`,`GoogleLat`,`GoogleLng`)
SET `Measurement.Att.Date` = STR_TO_DATE(@'Measurement.Att.Date', '%d/%m/%Y');

