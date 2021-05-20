<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

include_once 'utilities/bd.php';
include_once 'utilities/utilities.php';
include_once 'utilities/patient.php';
/*
 * variables needed to perform the query:
 * - location
 * - date
 * - day
 * - box or therapist
 */
if(isset($_GET['branch'])){
    if(isset($_GET['orderBy'])){
        $conn = new CAdatabase();
        $todayDate = getDate();
        $todayWday=$todayDate['wday'];
        //$today=strtodate($todayDate['year'].'-'.$todayDate['mon'].'-'.$todayDate['mday']);
        $today=date_create($todayDate['year'].'-'.$todayDate['mon'].'-'.$todayDate['mday']);
        //var_dump($today);
        $day= Utilities::getWeekDay($today, date_create($_GET['dt']), $todayWday);
        $numBoxes=$conn->getBranchNumBoxes($_GET['branch']);
        $data=array();
        $data['numBoxes']=$numBoxes;
        $data['attentionBlocks']=Utilities::attentionBlocksRange($day);
        $data['therapists']=array();
        $data['patients']=array();
        $stmt=$conn->consultDayScheduleByTherapist($day, $_GET['branch'],$_GET['dt']);
        $previousTherapist=0;
        while($row=$stmt->fetch(PDO::FETCH_OBJ)){
            if($row->therapist!=$previousTherapist){
                $d=array(
                    'id'=>$row->therapist,
                    'initials'=>substr($row->name,0,1).substr($row->surname,0,1),
                    'name'=>$row->name." ".$row->surname,
                    'attentions'=>array(
                                    array(
                                        'box'=>$row->box,
                                        'blockS'=>$row->blockS,
                                        'blockE'=>$row->blockE
                                    )
                                )
                );
                $previousTherapist=$row->therapist;
                array_push($data['therapists'], $d);
            }else{
                array_push($data['therapists']['attentions'],array('box'=>$row->box,'blockS'=>$row->blockS,'blockE'=>$row->blockE));
            }
        }
        //alert($todaydate_create($_GET['dt']));
        $stmt2=$today<=date_create($_GET['dt'])?$conn->consultPatientSchedule($day, $_GET['branch'],$_GET['dt']):$conn->consultPatientPreviousAgenda($_GET['branch'],$_GET['dt']);
        
        while($row=$stmt2->fetch(PDO::FETCH_OBJ)){
            $appoinmentsLeft=$row->programTotalAttentions-$row->totalDates+$row->justifiedAbsences;
            if(Utilities::appointmentsNumber($today,date_create($_GET['dt']))<=$appoinmentsLeft){
                $d=array(
                    'id'=>$row->patientID, 
                    'initials'=>substr($row->patientName,0,1).substr($row->patientSurname,0,1),
                    'name'=>$row->patientName." ".$row->patientSurname, 
                    'rut'=>$row->patientRUT, 
                    'email'=>$row->patientEmail,
                    'phone'=>$row->patientPhone, 
                    'sex'=>$row->patientSex, 
                    'program'=>$row->attentionProgram, 
                    'box'=>$row->box, 
                    'absences'=>$row->absences, 
                    'totalDates'=>$row->totalDates,
                    'justifiedAbsences'=>$row->justifiedAbsences,
                    'programTotalAttentions'=>$row->programTotalAttentions,
                    'therapist'=>$row->therapist, 
                    'block'=>$row->attentionBlock,
                    'nextDateStatus'=>$row->nextDateStatus
                );
                array_push($data['patients'],$d);
            }
        }
        http_response_code(200);
        echo json_encode($data);
    }
}