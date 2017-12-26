<?php

// PCnub2Palmeras/Modelo/Entidades/lote

/**
 * Descripcion de lote
 *
 * @author José Tibau <uabitesoj at gmail.com>
 */

namespace PCnub2Palmeras\Modelo\Entidades;

class lote extends Entidad {

    protected $idLote;
    protected $desde;
    protected $hasta;
    protected $inicio;
    protected $fin;
    protected $estado;

    function getIdLote() {
        return $this->idLote;
    }

    function getDesde() {
        return $this->desde;
    }

    function getHasta() {
        return $this->hasta;
    }

    function getInicio() {
        return $this->inicio;
    }

    function getFin() {
        return $this->final;
    }

    function getEstado() {
        return $this->estado;
    }

    function setIdLote($idLote = 0) {
        $this->idLote = $idLote;
    }

    function setDesde($desde) {
        $this->desde = $desde;
    }

    function setHasta($hasta) {
        $this->hasta = $hasta;
    }

    function setInicio($inicio) {
        $this->inicio = $inicio;
    }

    function setFin($fin = null) {
        $this->final = $fin;
    }

    function setEstado($estado = 0) {
        $this->estado = $estado;
    }

}
