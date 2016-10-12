<?php

class DetalleCertificacion {
    var $IddetalleCertificacion;
    var $IdCertificacion;
    var $valor;
    var $cantidad;
    var $IdPrestacion;
    var $nombrePrestacion;

    public function setIddetalleCertificacion($id){
        $this->IddetalleCertificacion = $id;
    }

    public function setIdCertificacion($IdCertificacion){
        $this->IdCertificacion = $IdCertificacion;
    }

    public function setvalor($valor){
        $this->valor = $valor;
    }

    public function setcantidad($cantidad){
        $this->cantidad = $cantidad;
    }

    public function setIdPrestacion($IdPrestacion){
        $this->IdPrestacion = $IdPrestacion;
    }

    public function setnombrePrestacion($nombrePrestacion){
        $this->nombrePrestacion = $nombrePrestacion;
    }

    public function getIdCertificacion(){
        return $this->IdCertificacion;
    }

    public function getIddetalleCertificacion(){
        return $this->IddetalleCertificacion;
    }

    public function getvalor(){
        return $this->valor;
    }

    public function getcantidad(){
        return $this->cantidad;
    }

    public function getIdPrestacion(){
        return $this->IdPrestacion;
    }

    public function getnombrePrestacion(){
        return $this->nombrePrestacion;
    }

}