<?php

class DetalleLocacion {
    var $IdDetallesLocacion;
    var $IdLocacion;
    var $Hospital;
    var $Monto;

    public function setIdDetallesLocacion($id){
        $this->IdDetallesLocacion = $id;
    }

    public function setIdLocacion($id){
        $this->IdLocacion = $id;
    }

    public function setHospital($Hospital){
        $this->Hospital = $Hospital;
    }

    public function setMonto($Monto){
        $this->Monto = $Monto;
    }

    public function getIdDetallesLocacion(){
        return $this->IdDetallesLocacion;
    }

    public function getIdLocacion(){
        return $this->IdLocacion;
    }

    public function getHospital(){
        return $this->Hospital;
    }

    public function getMonto(){
        return $this->Monto;
    }

}