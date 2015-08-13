--
-- Table structure for table `Security`
--

CREATE TABLE IF NOT EXISTS `ktphAllData` (
    `Addr_Postal.Code` varchar(6),
    `Zone` varchar(20),
    `Measurement.Att.Date` varchar(20),
    `Gender.Full.Text` varchar(10),
    `Race.Full.Text` varchar(10),
    `Nationality` varchar(10),
    `StaffEducation` varchar(20),
    `M_Weight` FLOAT,
    `M_Height` FLOAT,
    `M_Waist` FLOAT,
    `M_Systolic_1st` FLOAT,
    `M_Diastolic_1st` FLOAT,
    `L_Chol_f` FLOAT,
    `L_Trig_f` FLOAT,
    `L_HDL_f` FLOAT,
    `L_LDL_f` FLOAT,
    `L_Glucose_f` FLOAT,
    `f_BMI` FLOAT,
    `X8Q_MH_HeartAttack` varchar(5),
    `X8Q_MH_Stroke` varchar(5),
    `X8Q_MH_Diabetes` varchar(5),
    `X8Q_MH_HBP` varchar(5),
    `X8Q_MH_HBldChol` varchar(5),
    `X8Q_MH_DiabetesTrt` varchar(5),
    `X8Q_MH_HBldCholTrt` varchar(5),
    `X8Q_FH_DadBroCHD` varchar(5),
    `X8Q_FH_MomSisCHD` varchar(5),
    `X8Q_LS_Smoking` varchar(5),
    `X8Q_LS_Exercise` varchar(5),
    `X8Q_LS_Stress` varchar(10),
    `X8Q_GN_Health` varchar(10),
    `X8Q_LS_FruitsVeg` varchar(10),
    `X8Q_MH_DiabetesFoot` varchar(10),
    `X8Q_Living` varchar(10),
    `Q_TAXI_Ache` varchar(10),
    `Q_TAXI_LengthAche` varchar(10),
    `Q_TAXI_AveSleep` varchar(10),
    `PreferredLanguage` varchar(20),
    `OccupationType` varchar(30),
    `Healthy` varchar(30),
    `Habits` varchar(30),
    `Diabetes` varchar(30),
    `BloodPressure` varchar(30),
    `Cholesterol` varchar(30),
    `Combine` varchar(50),
    `Overweight` varchar(50),
    `New` varchar(50),
    `UnhealthyCat` varchar(80),
    `Control` varchar(50),
    `ControlGrp` varchar(50),
    `existNewGrp` varchar(50),
    `controlTree` varchar(50),
    `AgeGrp` varchar(20),
    `BMIGrp` varchar(20),
    `Fasting` varchar(20),
    `SugarHigh` varchar(30),
    `BPHigh` varchar(30),
    `HealthState` varchar(20),
    `medicalHistCholSugBp` varchar(20),
    `screening` varchar(50),
    `action` varchar(20),
    `scnZone` varchar(20),
    `NurseAction` varchar(30),
    `DrOutcome` varchar(30),
    `month` varchar(15),
    `revisit` varchar(15),
    `DHLDescNHGP` TEXT,
    `FollowUpPolyClinic` varchar(30),
    `DiagnosisAtPoly` TEXT,
    `DateOfPolyVisit` varchar(20),
    `FollowupType` varchar(20),
    `VisitsNHGP` varchar(20),
    `X10YrRisk` INT,
    `fScoreType` INT,
    `fScore` INT,
    `fScoreCat` varchar(20),
    `VisitFY` varchar(20),
    `URA_DGP` varchar(30),
    `healthRisk` varchar(30),
    `NRIC` varchar(20),
    `GoogleLat` FLOAT,
    `GoogleLng` FLOAT
    ) 


