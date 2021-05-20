<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
class Patient{
    private $id;
    private $name;
    private $surname;
    private $rut;
    private $sex;
    private $phone;
    private $attentionProgram;
    private $attentionBlock;
    private $box;
    private $therapist;
    private $totalDates;
    private $absences;
    
    public function __construct(
            $id,
            $name,
            $surname,
            $rut,
            $sex,
            $phone,
            $attentionProgram,
            $attentionBlock,
            $box,
            $therapist,
            $totalDates,
            $absences
            ){
        $this->id=$id;
        $this->name=$name;
        $this->surname=$surname;
        $this->rut=$rut;
        $this->sex=$sex;
        $this->phone=$phone;
        $this->attentionProgram=$attentionProgram;
        $this->attentionBlock=$attentionBlock;
        $this->box=$box;
        $this->therapist=$therapist;
        $this->totalDates=$totalDates;
        $this->absences=$absences;
    }
    
    
}
