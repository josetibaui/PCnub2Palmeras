<?php

// PCnub2Palmeras/WebService/WebService.php



/**
 * Descripcion de WebService
 *
 * @author José Tibau <uabitesoj at gmail.com>
 */

namespace PCnub2Palmeras\WebService;

use PCnub2Palmeras\Comun\AnalizarArgumentos;

class WebService {

    protected $accessUrl = array();
    protected $opciones;

    function __construct($argumentos = array()) {
        $this->setAccessUrl();
        $args = new AnalizarArgumentos($argumentos);
        $this->opciones = $args->getAgumentos();
        unset($args);
    }

    function readUrl() {
        foreach ($this->accessUrl as $origen => $url) {
            echo "$origen ---- $url" . PHP_EOL;
            $urlResponse = $this->get_remote_data($url, false, true);
            var_dump($urlResponse);
            if ($urlResponse['info']['http_code'] == 200 && key_exists('data', $urlResponse) && !empty($urlResponse['data'])) {
                $datos = json_decode($urlResponse['data'], false, 512, JSON_BIGINT_AS_STRING);
                foreach ($datos as $fila) {
//                    echo gettype($fila). PHP_EOL;
//                    var_dump(json_decode(json_encode($fila)));
                }
            }
        }
    }

    function setAccessUrl() {
        $this->accessUrl = [
            'facturas' => 'http://labs.pos-ecuador.com/webservice/ajax_facturas.php?action=listar&pass=1234&cliente=LasPalmeras',
            'ventas' => 'http://labs.pos-ecuador.com/webservice/ajax_ventas.php?action=listar&pass=1234&cliente=LasPalmeras',
            'productos' => 'http://labs.pos-ecuador.com/webservice/ajax_pmix.php?action=listar&pass=1234&cliente=LasPalmeras',
            'nomina' => 'http://labs.pos-ecuador.com/webservice/ajax_nomina.php?action=listar&pass=1234&cliente=LasPalmeras'
        ];
    }

    /*
     * Tomado de https://stackoverflow.com/questions/5971398/php-get-contents-of-a-url-or-page
     * 
     * Es más complicadp que usar file_get_contents($url) o stream_get_contents(fopen($url, "rb")), 
     *  pero es mejor.
     */

//needs "php_curl" to be enabled (+php_openssl)
    function get_remote_data($url, $post_paramtrs = false, $return_full_array = false) {
        $c = curl_init();
        curl_setopt($c, CURLOPT_URL, $url);
        curl_setopt($c, CURLOPT_RETURNTRANSFER, 1);
        //if parameters were passed to this function, then transform into POST method.. (if you need GET request, then simply change the passed URL)
        if ($post_paramtrs) {
            curl_setopt($c, CURLOPT_POST, TRUE);
            curl_setopt($c, CURLOPT_POSTFIELDS, "var1=bla&" . $post_paramtrs);
        }
        curl_setopt($c, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($c, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($c, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows NT 6.1; rv:33.0) Gecko/20100101 Firefox/33.0");
        curl_setopt($c, CURLOPT_COOKIE, 'CookieName1=Value;');
        //We'd better to use the above command, because the following command gave some weird STATUS results..
        //$header[0]= $user_agent="User-Agent: Mozilla/5.0 (Windows NT 6.1; rv:33.0) Gecko/20100101 Firefox/33.0";  $header[]="Cookie:CookieName1=Value;"; $header[]="Accept: text/xml,application/xml,application/xhtml+xml,text/html;q=0.9,text/plain;q=0.8,image/png,*/*;q=0.5";  $header[]="Cache-Control: max-age=0"; $header[]="Connection: keep-alive"; $header[]="Keep-Alive: 300"; $header[]="Accept-Charset: ISO-8859-1,utf-8;q=0.7,*;q=0.7"; $header[] = "Accept-Language: en-us,en;q=0.5"; $header[] = "Pragma: ";  curl_setopt($c, CURLOPT_HEADER, true);     curl_setopt($c, CURLOPT_HTTPHEADER, $header);

        curl_setopt($c, CURLOPT_MAXREDIRS, 10);
        //if SAFE_MODE or OPEN_BASEDIR is set,then FollowLocation cant be used.. so...
        $follow_allowed = ( ini_get('open_basedir') || ini_get('safe_mode')) ? false : true;
        if ($follow_allowed) {
            curl_setopt($c, CURLOPT_FOLLOWLOCATION, 1);
        }
        curl_setopt($c, CURLOPT_CONNECTTIMEOUT, 9);
        curl_setopt($c, CURLOPT_REFERER, $url);
        curl_setopt($c, CURLOPT_TIMEOUT, 60);
        curl_setopt($c, CURLOPT_AUTOREFERER, true);
        curl_setopt($c, CURLOPT_ENCODING, 'gzip,deflate');
        $data = curl_exec($c);
        $status = curl_getinfo($c);
        curl_close($c);

        preg_match('/(http(|s)):\/\/(.*?)\/(.*\/|)/si', $status['url'], $link);
        //correct assets URLs(i.e. retrieved url is: http://example.com/DIR/SUBDIR/page.html... then href="./image.JPG" becomes href="http://example.com/DIR/SUBDIR/image.JPG", but  href="/image.JPG" needs to become href="http://example.com/image.JPG")
        //inside all links(except starting with HTTP,javascript:,HTTPS,//,/ ) insert that current DIRECTORY url (href="./image.JPG" becomes href="http://example.com/DIR/SUBDIR/image.JPG")
        $data = preg_replace('/(src|href|action)=(\'|\")((?!(http|https|javascript:|\/\/|\/)).*?)(\'|\")/si', '$1=$2' . $link[0] . '$3$4$5', $data);
        //inside all links(except starting with HTTP,javascript:,HTTPS,//)    insert that DOMAIN url (href="/image.JPG" becomes href="http://example.com/image.JPG")
        $data = preg_replace('/(src|href|action)=(\'|\")((?!(http|https|javascript:|\/\/)).*?)(\'|\")/si', '$1=$2' . $link[1] . '://' . $link[3] . '$3$4$5', $data);
        // if redirected, then get that redirected page
        if ($status['http_code'] == 301 || $status['http_code'] == 302) {
            //if we FOLLOWLOCATION was not allowed, then re-get REDIRECTED URL
            //p.s. WE dont need "else", because if FOLLOWLOCATION was allowed, then we wouldnt have come to this place, because 301 could already auto-followed by curl  :)
            if (!$follow_allowed) {
                //if REDIRECT URL is found in HEADER
                if (empty($redirURL)) {
                    if (!empty($status['redirect_url'])) {
                        $redirURL = $status['redirect_url'];
                    }
                }
                //if REDIRECT URL is found in RESPONSE
                if (empty($redirURL)) {
                    preg_match('/(Location:|URI:)(.*?)(\r|\n)/si', $data, $m);
                    if (!empty($m[2])) {
                        $redirURL = $m[2];
                    }
                }
                //if REDIRECT URL is found in OUTPUT
                if (empty($redirURL)) {
                    preg_match('/moved\s\<a(.*?)href\=\"(.*?)\"(.*?)here\<\/a\>/si', $data, $m);
                    if (!empty($m[1])) {
                        $redirURL = $m[1];
                    }
                }
                //if URL found, then re-use this function again, for the found url
                if (!empty($redirURL)) {
                    $t = debug_backtrace();
                    return call_user_func($t[0]["function"], trim($redirURL), $post_paramtrs);
                }
            }
        }
        // if not redirected,and nor "status 200" page, then error..
        elseif ($status['http_code'] != 200) {
            $data = "ERRORCODE22 with $url<br/><br/>Last status codes:" . json_encode($status) . "<br/><br/>Last data got:$data";
        }
        return ( $return_full_array ? array('data' => $data, 'info' => $status) : $data);
    }

    /*
      Resultados obtenidos el 26-nov-2017

      Facturas:
      [{"Fecha":"2017-09-21","Checknumber":"10005","Autorizacion":"2109201704179258367500110030010000000010012237611","Serie":"003","Counter":"1","RUCCedula":"1791274156001","Nombre":"ASERLACO SA","TipoDoc":"4","Pago":"EFECTIVO","Descuentos":".0000","Propina":".0000","Aditionalcharge":"-1.8900","Ventaneta":"-18.8700","Ventaexcenta":".0000","Impuesto":"-2.2700","Ventatotal":"-21.1400"},{"Fecha":"2017-09-21","Checknumber":"10003","Autorizacion":"2109201701179258367500110030010000000180012237615","Serie":"003","Counter":"18","RUCCedula":"9999999999999","Nombre":"CONSUMIDOR FINAL","TipoDoc":"1","Pago":"EFECTIVO","Descuentos":".0000","Propina":".0000","Aditionalcharge":"1.7800","Ventaneta":"17.8200","Ventaexcenta":".0000","Impuesto":"2.1300","Ventatotal":"21.7300"},{"Fecha":"2017-09-21","Checknumber":"10002","Autorizacion":"2109201701179258367500110030010000000190012237610","Serie":"003","Counter":"19","RUCCedula":"1791274156001","Nombre":"ASERLACO SA","TipoDoc":"1","Pago":"EFECTIVO","Descuentos":".0000","Propina":".0000","Aditionalcharge":"1.8900","Ventaneta":"18.8700","Ventaexcenta":".0000","Impuesto":"2.2600","Ventatotal":"23.0200"},{"Fecha":"2017-10-16","Checknumber":"10003","Autorizacion":"1610201701179258367500110030010000000280031331111","Serie":"003","Counter":"28","RUCCedula":"9999999999999","Nombre":"CONSUMIDOR FINAL","TipoDoc":"1","Pago":"EFECTIVO","Descuentos":".0000","Propina":".0000","Aditionalcharge":".0000","Ventaneta":"20.4400","Ventaexcenta":".0000","Impuesto":"2.4500","Ventatotal":"22.8900"},{"Fecha":"2017-10-16","Checknumber":"10005","Autorizacion":"1610201701179258367500110030010000000300031331110","Serie":"003","Counter":"30","RUCCedula":"9999999999999","Nombre":"CONSUMIDOR FINAL","TipoDoc":"1","Pago":"EFECTIVO","Descuentos":".0000","Propina":".0000","Aditionalcharge":".0000","Ventaneta":"27.2500","Ventaexcenta":".0000","Impuesto":"3.2700","Ventatotal":"30.5200"},{"Fecha":"2017-10-17","Checknumber":"10002","Autorizacion":"1710201701179258367500110030010000000340031331117","Serie":"003","Counter":"34","RUCCedula":"9999999999999","Nombre":"CONSUMIDOR FINAL","TipoDoc":"1","Pago":"EFECTIVO","Descuentos":".0000","Propina":".0000","Aditionalcharge":".0000","Ventaneta":"15.2100","Ventaexcenta":".0000","Impuesto":"1.8300","Ventatotal":"17.0400"},{"Fecha":"2017-10-17","Checknumber":"10003","Autorizacion":"1710201701179258367500110030010000000350031331112","Serie":"003","Counter":"35","RUCCedula":"9999999999999","Nombre":"CONSUMIDOR FINAL","TipoDoc":"1","Pago":"EFECTIVO","Descuentos":".0000","Propina":".0000","Aditionalcharge":".0000","Ventaneta":"7.9900","Ventaexcenta":".0000","Impuesto":".9600","Ventatotal":"8.9500"},{"Fecha":"2017-11-14","Checknumber":"10005","Autorizacion":"1411201701179258367500110030010000000360031800110","Serie":"003","Counter":"36","RUCCedula":"9999999999999","Nombre":"CONSUMIDOR FINAL","TipoDoc":"1","Pago":"EFECTIVO","Descuentos":".0000","Propina":".0000","Aditionalcharge":".0000","Ventaneta":"23.9700","Ventaexcenta":".0000","Impuesto":"2.8700","Ventatotal":"26.8400"}]

      Productos Vendidos:
      [{"dob":"2017-10-17","longname":"Cebiche Mixto","itemaloha":"2","price":"7.946","total":"15.89","sucursal":"1"},{"dob":"2017-10-18","longname":"Cebiche Mixto","itemaloha":"2","price":"7.946","total":"15.89","sucursal":"1"},{"dob":"2017-10-18","longname":"Cebiche TriMixt","itemaloha":"1","price":"7.946","total":"7.95","sucursal":"1"},{"dob":"2017-10-18","longname":"Cebiche Camaron","itemaloha":"1","price":"7.946","total":"7.95","sucursal":"1"},{"dob":"2017-10-18","longname":"Viche Pescado","itemaloha":"2","price":"5.446","total":"10.89","sucursal":"1"},{"dob":"2017-10-18","longname":"Porcion Chifle","itemaloha":"1","price":"1.17","total":"1.17","sucursal":"1"},{"dob":"2017-10-18","longname":"Porcion Menestra","itemaloha":"1","price":"1.17","total":"1.17","sucursal":"1"},{"dob":"2017-10-18","longname":"Inca","itemaloha":"1","price":"1.17","total":"1.17","sucursal":"1"},{"dob":"2017-10-18","longname":"Fioira Manzana","itemaloha":"1","price":"1.17","total":"1.17","sucursal":"1"},{"dob":"2017-10-18","longname":"Contenedor","itemaloha":"1","price":"0.25","total":"0.25","sucursal":"1"},{"dob":"2017-10-26","longname":"Cebiche Camaron","itemaloha":"1","price":"7.946","total":"7.95","sucursal":"1"},{"dob":"2017-11-06","longname":"Bola Verde Marinera","itemaloha":"1","price":"8.652","total":"8.65","sucursal":"1"},{"dob":"2017-11-06","longname":"Comida Completa 2","itemaloha":"1","price":"7.634","total":"7.63","sucursal":"1"},{"dob":"2017-11-06","longname":"Pilsener Regular","itemaloha":"1","price":"2.036","total":"2.04","sucursal":"1"},{"dob":"2017-11-06","longname":"CocaCola","itemaloha":"1","price":"1.17","total":"1.17","sucursal":"1"},{"dob":"2017-11-06","longname":"Ceviche Mixto1","itemaloha":"1","price":"7.22","total":"7.22","sucursal":"1"},{"dob":"2017-11-13","longname":"Chicharron Pescado","itemaloha":"3","price":"7.991","total":"23.97","sucursal":"1"},{"dob":"2017-11-14","longname":"Chicharron Pescado","itemaloha":"3","price":"7.991","total":"23.97","sucursal":"1"}]

      Ventas:
      [{"subtotal":174.63,"iva":"6.19","servicio":null,"cortesias":null,"total":180.82,"cuentas":"10","clientes":"9","ticket":20.09}]

      Nonmina:
      []

     */
}
