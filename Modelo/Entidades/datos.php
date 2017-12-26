<?php

// PCnub2Palmeras/Modelo/Entidades/datos

namespace PCnub2Palmeras\Modelo\Entidades;

/**
 * Descripcion de datos
 *
 * @author José Tibau <uabitesoj at gmail.com>
 */
class datos extends Entidad {

    protected $idDatos;
    protected $idLote;
    protected $origen;
    protected $datos;

    function getIdDatos() {
        return $this->idDatos;
    }

    function getIdLote() {
        return $this->idLote;
    }

    function getOrigen() {
        return $this->origen;
    }

    function getDatos() {
        return $this->datos;
    }

    function setIdDatos($idDatos = 0) {
        $this->idDatos = $idDatos;
    }

    function setIdLote($idLote) {
        $this->idLote = $idLote;
    }

    function setOrigen($origen) {
        $this->origen = $origen;
    }

    function setDatos($datos) {
        $this->datos = $datos;
    }


}
