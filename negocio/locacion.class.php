<?php

class Locacion {
    var $IdLocacion;
    var $Locacion;
    var $IdProfesional;
    var $IdEspecialidad;
    var $Especialidad;
    var $nombreProfesional;
    var $FechaInicio;
    var $FechaFinal;

    public function setIdLocacion($id){
        $this->IdLocacion = $id;
    }

    public function setLocacion($Locacion){
        $this->Locacion = $Locacion;
    }

    public function setIdProfesional($IdProfesional){
        $this->IdProfesional = $IdProfesional;
    }

    public function setIdEspecialidad($IdEspecialidad){
        $this->IdEspecialidad = $IdEspecialidad;
    }

    public function setEspecialidad($Especialidad){
        $this->Especialidad = $Especialidad;
    }

    public function setFechaInicio($FechaInicio){
        $this->FechaInicio = $FechaInicio;
    }

    public function setFechaFinal($FechaFinal){
        $this->FechaFinal = $FechaFinal;
    }

    public function setnombreProfesional($nombreProfesional){
        $this->nombreProfesional = $nombreProfesional;
    }



    public function getIdLocacion(){
        return $this->IdLocacion;
    }

    public function getLocacion(){
        return $this->Locacion;
    }

    public function getIdProfesional(){
        return $this->IdProfesional;
    }

    public function getIdEspecialidad(){
        return $this->IdEspecialidad;
    }

    public function getEspecialidad(){
        return $this->Especialidad;
    }


    public function getFechaInicio(){
        return $this->FechaInicio;
    }

    public function getFechaFinal(){
        return $this->FechaFinal;
    }


    public function getnombreProfesional(){
        return $this->nombreProfesional;
    }



}