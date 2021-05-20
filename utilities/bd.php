<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class CAdatabase extends PDO{
    //db connection variables
    private $user="root";
    private $pass="";
    private $dsn = "mysql:host=localhost;dbname=ca1";

    
    public function __construct(){
        try{
            parent::__construct($this->dsn, $this->user, $this->pass);
            $this->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $ex) {
            die($ex->getMessage());
        }
    }
    //numBoxes
    public function getBranchNumBoxes($branch){
        $query="SELECT branchNumBoxes FROM branches WHERE branchName = '".$branch."'";
        
        $stmt=$this->prepare($query);
        $stmt->execute();
        $row=$stmt->fetch(PDO::FETCH_OBJ);
        return $row->branchNumBoxes;
    }
    
    //functions for patients
    private function buildQuery($status,$program,$sex,$therapist,$attentionDay,$attentionHour){
        $query="SELECT patientID, patientName, patientSurname, patientRUT, patientAge, attentionBlock, attentionProgram, programStatus, therapist, patientSex FROM patients WHERE";
        if($status == "1"){
            $query.=" programStatus = 1";
        }else{
            if($status=="2"){
                $query.=" programStatus = 0";
            }
        }
        if($program!=="TODOS"){
            $query.=" AND attentionProgram = '".$program."'";
        }
        if($sex!=="TODOS"){
            $query.=" AND patientSex = '".$sex."'";
        }
        if($therapist!=="TODOS"){
            $query.=" AND therapist = '".$therapist."'";
        }
        if($attentionDay!=="%" or $attentionHour!=="%"){
            $query.=" AND".$this->buildQueryForAttentionBlock($attentionDay, $attentionHour);
        }
        return $query." ORDER BY patientSurname";}
    
    private function buildQueryForAttentionBlock($attentionDay,$attentionHour){
        $query="";
        if($attentionDay!=="%" or $attentionHour!=="%"){
            $stmt=$this->consultBlock($attentionDay, $attentionHour);
            $flag=false;
            while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
                if(!$flag){
                    $query.=" (attentionBlock = ".$row["blockID"];
                    $flag=true;
                }else{
                    $query.=" OR attentionBlock = ".$row["blockID"];
                }
            }
            $query.=")";
        }
        return $query;
    }
    
    private function determineIfArguments($status,$program,$sex,$therapist,$attentionDay,$attentionHour){
        return $status!=="0" or $program!=="TODOS" or $sex!=="TODOS" or $therapist!=="TODOS" or $attentionDay!=="%" or $attentionHour!=="%";
    }
    
    public function patientsConsult($status="1",$program="TODOS",$sex="TODOS",$therapist="TODOS",$attentionDay="%",$attentionHour="%"){
        $query="SELECT patientID, patientName, patientSurname, patientRUT, patientAge, attentionBlock, attentionProgram, programStatus, therapist, patientSex FROM patients";
        if($this->determineIfArguments($status, $program, $sex, $therapist, $attentionDay, $attentionHour)){
            $query=$this->buildQuery($status, $program, $sex, $therapist, $attentionDay, $attentionHour);
            
        }
        //echo "<p>".$query."</p><br>";
        //echo $status."<br>".$program."<br>".$sex."<br>".$therapist."<br>".$attentionDay.$attentionHour;
        $stmt=$this->prepare($query);
        $stmt->execute();
        return $stmt;
        //return $stmt->fetch(PDO::FECHT_OBJ);
    }
    
    public function patientConsultByRUT($rut){
        //depurar RUT (eliminar puntos y guión
        $query="SELECT patientID, patientName, patientSurname, attentionBlock, attentionProgram, programStatus, therapist, patientSex FROM patients WHERE patientRUT = ".$rut;
        $stmt = $this->prepare($query);
        $stmt->execute();
        return $stmt;
        //return $stmt->fetch(PDO::FECHT_OBJ);
    }
    
    public function patientConsultByName($name, $surname){
        $query="SELECT patientID, patientName, patientSurname, attentionBlock, attentionProgram, programStatus, therapist, patientSex FROM patients WHERE";
        if($name!=="" and $surname!==""){
            $query.=" patientName LIKE '%".$name."%' AND patientSurname LIKE '%".$surname."%'";
        }else{
            if($name!==""){
                $query.=" patientName LIKE '%".$name."%'";
            }else{
                $query.=" patientSurname LIKE '%".$surname."%'";
            }
        }
        $query.=" ORDER BY patientSurname";
        $stmt = $this->prepare($query);
        $stmt->execute();
        return $stmt;
        //return $stmt->fetch(PDO::FECHT_OBJ);
    }
    
    public function patientConsultByID($id){
        $query="SELECT * FROM patients WHERE patientID = ".$id;
        $stmt=$this->prepare($query);
        $stmt->execute();
        $queryResult=$stmt->fetch(PDO::FETCH_OBJ);
        $result=["patientName"=>$queryResult->patientName,
                "patientSurname"=>$queryResult->patientSurname,
                "patientRUT"=>$queryResult->patientRUT,
                "patientEmail"=>$queryResult->patientEmail,
                "patientPhone"=>$queryResult->patientPhone,
                "patientSex"=>$queryResult->patientSex,
                "patientRegion"=>$queryResult->patientRegion,
                "patientCity"=>$queryResult->patientCity,
                "patientComune"=>$queryResult->patientComune,
                "patientAddress"=>$queryResult->patientAddress,
                "therapist"=>$this->consultTherapistNameByID($queryResult->therapist),
                "attentionBlock"=>$this->consultBlockByID($queryResult->attentionBlock),
                "attentionProgram"=>$this->consultProgramByID($queryResult->attentionProgram),
                "programStatus"=>$queryResult->programStatus,
                "processStartDate"=>$queryResult->processStartDate,
                "processEndDate"=>$queryResult->processEndDate,
                "totalDates"=>$queryResult->totalDates,
                "absences"=>$queryResult->absences,
                "startProcessReport"=>$queryResult->startProcessReport,
                "endProcessReport"=>$queryResult->endProcessReport];
        return $result;
    }
    
    private function consultTherapistNameByID($id){
        $query="SELECT name, surname FROM users WHERE userID = ".$id;
        $stmt=$this->prepare($query);
        $stmt->execute();
        $result=$stmt->fetch(PDO::FETCH_OBJ);
        return $result->name." ".$result->surname;
    }
    
    private function consultBlockByID($id){
        $query="SELECT blockDay, blockHour FROM blocks WHERE blockID = ".$id;
        $stmt=$this->prepare($query);
        $stmt->execute();
        $result=$stmt->fetch(PDO::FETCH_OBJ);
        return $result->blockDay."-".$result->blockHour." hrs";
    }
    
    private function consultProgramByID($id){
        $query="SELECT atName FROM blocks WHERE atID = ".$id;
        $stmt=$this->prepare($query);
        $stmt->execute();
        $result=$stmt->fetch(PDO::FETCH_OBJ);
        return $result->atName;
    }
    
    
    //bloques de atenciòn
    public function consultBlock($attentionDay, $attentionHour){
        $stmt=$this->prepare("SELECT blockID FROM blocks WHERE blockName LIKE '".$attentionDay.$attentionHour."'");
        $stmt->execute();
        return $stmt;
    }
    
    //functions to fill Schedule
    public function consultDayScheduleByTherapist($day, $branch,$date){
        $query="SELECT d.therapist AS therapist, "
                . "d.blockS AS blockS, "
                . "d.blockE AS blockE, "
                . "d.box AS box, "
                . "u.name AS name, "
                . "u.surname AS surname "
                . "FROM ((".$day." d INNER JOIN branches b ON d.branch=b.branchID) "
                . "INNER JOIN users u ON d.therapist = u.userID) "
                . "WHERE b.branchName = '".$branch."' AND (startDate<= '".$date."' OR startDate IS NULL) AND (endDate IS NULL OR endDate>='".$date."') ORDER BY therapist ASC";
        $stmt=$this->prepare($query);
        $stmt->execute();
        return $stmt;
    }
    
    public function consultPatientSchedule($day, $branch, $date){
        $blockS=0;
        $blockE=0;
        switch ($day){
            case "monday":
                $blockS=1;
                $blockE=14;
                break;
            case "tuesday":
                $blockS=15;
                $blockE=28;
                break;
            case "wednesday":
                $blockS=29;
                $blockE=42;
                break;
            case "thursday":
                $blockS=43;
                $blockE=56;
                break;
            case "friday":
                $blockS=57;
                $blockE=70;
                break;
            case "saturday":
                $blockS=71;
                $blockE=84;
                break;
        }
        $stmt=$this->prepare("SELECT "
                . "p.patientID AS patientID, "
                . "p.patientName AS patientName, "
                . "p.patientSurname AS patientSurname,"
                . " p.patientRUT AS patientRUT,"
                . " p.patientEmail as patientEmail, "
                . "p.patientPhone AS patientPhone,"
                . " p.patientSex AS patientSex,"
                . " p.attentionProgram AS attentionProgram,"
                . " p.box AS box, "
                . "p.absences AS absences,"
                . " p.totalDates AS totalDates, "
                . "p.therapist AS therapist,"
                . " p.attentionBlock AS attentionBlock,"
                . " p.justifiedAbsences as justifiedAbsences,"
                . " p.programTotalAttentions as programTotalAttentions, "
                . "p.nextDateStatus as nextDateStatus"
                . " FROM patients p INNER JOIN branches b ON p.branch=b.branchID "
                . "WHERE p.programStatus = TRUE "
                . "AND (p.attentionBlock BETWEEN ".$blockS." AND ".$blockE.") "
                . "AND b.branchName='".$branch."' AND p.processEndDate>'".$date."'");
        $stmt->execute();
        return $stmt;
    }
    public function consultBoxOccupationByTherapist($day,$box,$branch){
        $blockS=0;
        $blockE=0;
        switch ($day){
            case "monday":
                $blockS=1;
                $blockE=14;
                break;
            case "tuesday":
                $blockS=15;
                $blockE=28;
                break;
            case "wednesday":
                $blockS=29;
                $blockE=42;
                break;
            case "thursday":
                $blockS=43;
                $blockE=56;
                break;
            case "friday":
                $blockS=57;
                $blockE=70;
                break;
            case "saturday":
                $blockS=71;
                $blockE=84;
                break;
        }
        $stmt=$this->prepare("SELECT therapist, blockS, blockE FROM ".$box." WHERE branch=".$branch." AND (blockS BETWEEN ".$blockS." AND ".$blockE.") AND (blockE BETWEEN ".$blockS." AND ".$blockE.") ORDER BY BlockS ASC");
        $stmt->execute();
        return $stmt;
    }
    
    public function consultPatientPreviousAgenda($branch, $date){
        $stmt=$this->prepare("SELECT "
                . "p.patientID AS patientID, "
                . "p.patientName AS patientName, "
                . "p.patientSurname AS patientSurname, "
                . "p.patientRUT AS patientRUT, "
                . "p.patientEmail as patientEmail, "
                . "p.patientPhone AS patientPhone, "
                . "p.patientSex AS patientSex, "
                . "p.attentionProgram AS attentionProgram, "
                . "p.box AS box, "
                . "p.absences AS absences, "
                . "p.totalDates AS totalDates, "
                . "p.therapist AS therapist, "
                . "p.attentionBlock AS attentionBlock, "
                . "p.justifiedAbsences as justifiedAbsences, "
                . "p.programTotalAttentions as programTotalAttentions, "
                . "d.attentionStatus as nextDateStatus "//in this case nextDateStatus = that Date Status
                . "FROM ((patients p INNER JOIN branches b ON p.branch=b.branchID) "
                . "INNER JOIN daily d ON p.patientID=d.patient) "
                . "WHERE d.attetionDate = '".$date."'"
                . "AND b.branchName='".$branch."'");
        $stmt->execute();
        return $stmt;
    }
    
    public function updateAttentionStatus($patientID,$attentionStatus,$date){
        try{
            $query = "UPDATE daily SET attentionStatus = ".$attentionStatus." WHERE patient = ".$patientID." AND attetionDate = ".$date;
            $stmt = $this->prepare($query);
            $stmt->execute();
        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }
    
    public function insertPatientsOnDaily($patients,$date,$branch){
        try{
            $query="INSERT INTO daily (patient,branch,box,block,therapist,attentionProgram,dateNum,attetionDate,attentionStatus) VALUES ";
            $last=count($patients)-1;
            $i=0;
            foreach($patients as $p){
                $query.="(".$p['id'].",".$branch.",".$p['box'].",".$p['block'].",".$p['therapist'].",".$p['attentionProgram'].",".$p['totalDates'].",".$date.",".$p['nextDateStatus'].")";
                if(++$i<$last){
                    $query.=",";
                }
            }
            $stmt = $this->prepare($query);
            $stmt->execute();
        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }
}

