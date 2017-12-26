<?php

// Modelo\Entidades\igLocal.php

/**
 * Description of igLocal
 *
 * @author jtibau
 */

namespace PCnub2Palmeras\Modelo\Entidades;

class igLocal extends \PCnub2Palmeras\Modelo\Entidad {

    //database plm_rev
    protected $idLocal;
    protected $codLocal;
    protected $local;
    protected $alias;
    protected $tipo;
    protected $ciudad;
    protected $direccion;
    protected $referencia;
    protected $telefono;
    protected $codigoArea;
    protected $codigoPostal;
    protected $estado;

    public function setIdLocal($idLocal) {
        $this->idLocal = $idLocal;
    }

    function setCodLocal($codLocal) {
        $this->codLocal = $codLocal;
    }

    public function setNumero($numero) {
        $this->setCodLocal($numero);
    }

    function setLocal($local) {
        $this->local = $local;
    }

    public function setNombre($nombre) {
        $this->setLocal($nombre);
    }

    function setAlias($alias) {
        $this->alias = $alias;
    }

    public function setNombreSlug($nombreSlug) {
        $this->setAlias($nombreSlug);
    }

    public function setTipo($tipo) {
        $this->tipo = $tipo;
    }

    public function setCiudad($ciudad) {
        $this->ciudad = $ciudad;
    }

    public function setDireccion($direccion) {
        $this->direccion = $direccion;
    }

    public function setReferencia($referencia) {
        $this->referencia = $referencia;
    }

    public function setTelefono($telefono) {
        $this->telefono = $telefono;
    }

    public function setCodigoArea($codigoArea) {
        $this->codigoArea = $codigoArea;
    }

    public function setCodigoPostal($codigoPostal) {
        $this->codigoPostal = $codigoPostal;
    }

    function setEstado($estado) {
        $this->estado = $estado;
    }

    public function setStatus($status) {
        $this->setEstado($status);
    }

    public function getIdLocal() {
        return $this->idLocal;
    }

    function getCodLocal() {
        return $this->codLocal;
    }

    public function getNumero() {
        return $this->getCodLocal();
    }

    function getLocal() {
        return $this->local;
    }

    public function getNombre() {
        return $this->getLocal();
    }

    function getAlias() {
        return $this->alias;
    }

    public function getNombreSlug() {
        return $this->getAlias();
    }

    public function getTipo() {
        return $this->tipo;
    }

    public function getCiudad() {
        return $this->ciudad;
    }

    public function getDireccion() {
        return $this->direccion;
    }

    public function getReferencia() {
        return $this->referencia;
    }

    public function getTelefono() {
        return $this->telefono;
    }

    function getCodigoArea() {
        return $this->codigoArea;
    }

    function getCodigoPostal() {
        return $this->codigoPostal;
    }

    function getEstado() {
        return $this->estado;
    }

    public function getStatus() {
        return $this->getEstado();
    }

}
