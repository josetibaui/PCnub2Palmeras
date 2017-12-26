<?php

// PCnub2Palmeras\Comun\AnalizarArgumentos.php

namespace PCnub2Palmeras\Comun;


/**
 * Descripcion de AnalizarAtgumentos
 *
 * @author José Tibau <uabitesoj at gmail.com>
 */
class AnalizarArgumentos {

    protected $argumentos;
    protected $argumentosNormalizados = array();

    public function __construct($argumentos) {
        $this->argumentos = $argumentos;
        $this->analizarArgumentos();
        return $this;
    }

    public function getAgumentos() {
        return $this->argumentosNormalizados;
    }

    protected function analizarArgumentos() {
        foreach ($this->argumentos as $argumento) {
            list($etiqueta, $valor) = explode('=', $argumento);
            $etiquetaLower = strtolower($etiqueta);
//            echo 'La etiqueta '. $etiqueta. ' tiene el valor '. $valor. PHP_EOL;
//            $argumentos[$etiqueta] = $valor;
            switch (TRUE) {
                case ($etiquetaLower == 'local' || $etiquetaLower == 'locales' || $etiquetaLower == 'l'):
                    $this->setLocales($valor);
                    break;
                case ($etiquetaLower == 'desde' || $etiquetaLower == 'inicio'):
                    $this->setfechaDesde($valor);
                    break;
                case ($etiquetaLower == 'hasta' || $etiquetaLower == 'fin'):
                    $this->setfechaHasta($valor);
                    break;
            }
        }
    }

//    protected function analizarLocales($argumentos) {
//        $argumentoLocales = array_intersect_key(array('local'=>'','locales'=>'', 'l'=>''), $argumentos);
//        var_dump($argumentoLocales);
//        $locales = 
//    }
//    protected function analizarDesde($argumentos) {
//        $fechaDesde = array_intersect_key(array('desde'=>'','inicio'=>''), $argumentos);
//        var_dump($fechaDesde);
//    }
//    protected function analizarHasta($argumentos) {
//        $fechaHasta = array_intersect_key(array('hasta'=>'','fin'=>''), $argumentos);
//        var_dump($fechaHasta);
//        
//    }
    protected function setLocales($locales) {
        if (!locales){
//            $listaLocales = getListaLocalesActivosVentas();
            $listaLocales = [1,2,3,4,5,6,7,8,9,10,11];
        } else {
            $listaLocales = explode(',', $locales);
        }
        $this->argumentosNormalizados['locales'] = $listaLocales;
    }

}
