<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class Utilities{
    public static function getWeekDay($date1,$date2,$todayWeekDay){
        $dd= date_diff($date1, $date2);
        $diff=$dd->d%7;
        $weekDay=$todayWeekDay+$diff;
        if($weekDay<0){
            $weekDay+=7;
        }
        if($weekDay>=7){
            $weekDay-=7;
        }
        switch($weekDay){
            case 0:
                return "sunday";
            case 1:
                return "monday";
            case 2:
                return "tuesday";
            case 3:
                return "wednesday";
            case 4:
                return "thursday";
            case 5:
                return "friday";
            case 6:
                return "saturday";
        }
    }
    
    public static function attentionBlocksRange($day){
        switch($day){
            case 'monday':
                return array('blockS'=>1,'blockE'=>14);
            case 'tuesday':
                return array('blockS'=>15,'blockE'=>28);
            case 'wednesday':
                return array('blockS'=>29,'blockE'=>42);
            case 'thursday':
                return array('blockS'=>43,'blockE'=>56);
            case 'friday':
                return array('blockS'=>57,'blockE'=>70);
            case 'saturday':
                return array('blockS'=>71,'blockE'=>84);
        }
    }
    
    public static function appointmentsNumber($date1,$date2){
        $dd= date_diff($date1, $date2);
        return floor($dd->d/7)+1;
    }
    
    public static function scheduleFrame($day){
        $frame=array();
        $blockS=0;
        $blockE=0;
        switch($day){
            case 'monday':
                $blockS=1;
                $blockE=14;
                break;
            case 'tuesday':
                $blockS=15;
                $blockE=28;
                break;
            case 'wednesday':
                $blockS=29;
                $blockE=42;
                break;
            case 'thursday':
                $blockS=43;
                $blockE=56;
                break;
            case 'friday':
                $blockS=57;
                $blockE=70;
                break;
            case 'saturday':
                $blockS=71;
                $blockE=84;
                break;
        }
        $hour=8;
        for($i=blockS;$i<=$blockE;$i++){
            $frame[$i]=array(
                'hour'=>$hour<10?'0'.$hour.':00':$hour.':00',
                'therapist'=>null,
                'patient'=>null
            );
            $hour++;
        }
        return $frame;
    }
    public static function scheduleFrame2($day){
        $frame=array();
        $blockS=0;
        $blockE=0;
        switch($day){
            case 'monday':
                $blockS=1;
                $blockE=14;
                break;
            case 'tuesday':
                $blockS=15;
                $blockE=28;
                break;
            case 'wednesday':
                $blockS=29;
                $blockE=42;
                break;
            case 'thursday':
                $blockS=43;
                $blockE=56;
                break;
            case 'friday':
                $blockS=57;
                $blockE=70;
                break;
            case 'saturday':
                $blockS=71;
                $blockE=84;
                break;
        }
        for($i=blockS;$i<=$blockE;$i++){
            $frame[$i]=array('patient'=>null, 'box'=>null);
        }
        return $frame;
    }
    
    public static function getMonthYearLabel(){
        $fecha = getDate();
        $label="";
        switch ($fecha['mon']){
            case 1:
                $label.="Enero ";
                break;
            case 2:
                $label.="Febrero ";
                break;
            case 3:
                $label.="Marzo ";
                break;
            case 4:
                $label.="Abril ";
                break;
            case 5:
                $label.="Mayo ";
                break;
            case 6:
                $label.="Junio ";
                break;
            case 7:
                $label.="Julio ";
                break;
            case 8:
                $label.="Agosto ";
                break;
            case 9:
                $label.="Septiembre ";
                break;
            case 10:
                $label.="Octubre ";
                break;
            case 11:
                $label.="Noviembre ";
                break;
            case 12:
                $label.="Diciembre ";
                break;
        }
        $label.=$fecha['year'];
        return $label;
    }
}