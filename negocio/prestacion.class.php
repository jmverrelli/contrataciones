<?php

class Prestacion {
    var $IdPrestacion;
    var $IddetalleCertificacion;
    var $Prestacion;
    var $Valor;
    
    public function setIdPrestacion($id){
        $this->IdPrestacion = $id;
    }

    public function setIddetalleCertifcacion($IddetalleCertifcacion){
        $this->IddetalleCertifcacion = $IddetalleCertifcacion;
    }

    public function setPrestacion($Prestacion){
        $this->Prestacion = $Prestacion;
    }

    public function setValor($Valor){
        $this->Valor = $Valor;
    }

    public function getIdPrestacion(){
        return $this->IdPrestacion;
    }

    public function getIddetalleCertificacion(){
        return $this->IddetalleCertificacion;
    }

    public function getPrestacion(){
        return $this->Prestacion;
    }

    public function getValor(){
        return $this->Valor;
    }

}