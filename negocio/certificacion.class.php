<?php

class Certificacion {
    var $IdCertificacion;
    var $Fecha;
    var $IdModulado;
    var $IdProfesionales;
    var $nombreProfesional;
    var $IdEspecialidad;
    var $nombreEspecialidad;
    var $IdHospital;
    var $nombreHospital;
    var $FechaInicio;
    var $FechaFinal;
    var $Horas;
    var $PorMonto;

    public function setIdCertificacion($id){
        $this->IdCertificacion = $id;
    }

    public function setFecha($Fecha){
        $this->Fecha = $Fecha;
    }

    public function setIdModulado($IdModulado){
        $this->IdModulado = $IdModulado;
    }

    public function setIdProfesionales($IdProfesionales){
        $this->IdProfesionales = $IdProfesionales;
    }

    public function setIdEspecialidad($IdEspecialidad){
        $this->IdEspecialidad = $IdEspecialidad;
    }

    public function setIdHospital($IdHospital){
        $this->IdHospital = $IdHospital;
    }

    public function setFechaInicio($FechaInicio){
        $this->FechaInicio = $FechaInicio;
    }

    public function setFechaFinal($FechaFinal){
        $this->FechaFinal = $FechaFinal;
    }

    public function setHoras($Horas){
        $this->Horas = $Horas;
    }

    public function setPorMonto($PorMonto){
        $this->PorMonto = $PorMonto;
    }

    public function setnombreProfesional($nombreProfesional){
        $this->nombreProfesional = $nombreProfesional;
    }

    public function setnombreEspecialidad($nombreEspecialidad){
        $this->nombreEspecialidad = $nombreEspecialidad;
    }

    public function setnombreHospital($nombreHospital){
        $this->nombreHospital = $nombreHospital;
    }

    public function getIdCertificacion(){
        return $this->IdCertificacion;
    }

    public function getFecha(){
        return $this->Fecha;
    }

    public function getIdModulado(){
        return $this->IdModulado;
    }

    public function getIdProfesionales(){
        return $this->IdProfesionales;
    }

    public function getIdHospital(){
        return $this->IdHospital;
    }

    public function getFechaInicio(){
        return $this->FechaInicio;
    }

    public function getFechaFinal(){
        return $this->FechaFinal;
    }

    public function getHoras(){
        return $this->Horas;
    }

    public function getPorMonto(){
        return $this->PorMonto;
    }

    public function getnombreProfesional(){
        return $this->nombreProfesional;
    }

    public function getnombreHospital(){
        return $this->nombreHospital;
    }


}