<?php if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

function site_title()
{
    $ci = &get_instance();
    echo ($ci->config->item('site_name') != '') ? $ci->config->item('site_name') : '';
}

function euros($monto)
{
    return number_format($monto, 2, ",", ".") . '€';
}
function version()
{
    $CI = &get_instance();
    return  $CI->config->item('version');
}
function assets_url($uri = '', $group = false)
{
    $CI = &get_instance();

    if (!$dir = $CI->config->item('assets_path')) {
        $dir = 'assets/';
    }

    if ($group) {
        return $CI->config->base_url($dir . $group . '/' . $uri);
    } else {
        return $CI->config->base_url($dir . $uri);
    }
}

function ControlArchivos($patch = '')
{
    $CI = &get_instance();
    return $CI->config->base_url('ControlArchivos/' . $patch);
}

function printr($data, $exit = '')
{
    echo '<pre>';
    print_r($data);
    if ($exit == '') {
        exit();
    }
}

function rand_alphanumeric($length, $upper = '')
{
    if ($length > 0) {
        $rand_id = "";
        for ($i = 1; $i <= $length; $i++) {
            mt_srand((double) microtime() * 1000000);
            $num = mt_rand(1, 36);
            $rand_id .= assign_rand_value($num);
        }
    }
    if ($upper != '') {
        $rand_id = strtoupper($rand_id);
    }
    return $rand_id;
}

function assign_rand_value($num)
{
    // accepts 1 - 36
    switch ($num) {
        case "1":$rand_value = "a";
            break;
        case "2":$rand_value = "b";
            break;
        case "3":$rand_value = "c";
            break;
        case "4":$rand_value = "d";
            break;
        case "5":$rand_value = "e";
            break;
        case "6":$rand_value = "f";
            break;
        case "7":$rand_value = "g";
            break;
        case "8":$rand_value = "h";
            break;
        case "9":$rand_value = "i";
            break;
        case "10":$rand_value = "j";
            break;
        case "11":$rand_value = "k";
            break;
        case "12":$rand_value = "l";
            break;
        case "13":$rand_value = "m";
            break;
        case "14":$rand_value = "n";
            break;
        case "15":$rand_value = "o";
            break;
        case "16":$rand_value = "p";
            break;
        case "17":$rand_value = "q";
            break;
        case "18":$rand_value = "r";
            break;
        case "19":$rand_value = "s";
            break;
        case "20":$rand_value = "t";
            break;
        case "21":$rand_value = "u";
            break;
        case "22":$rand_value = "v";
            break;
        case "23":$rand_value = "w";
            break;
        case "24":$rand_value = "x";
            break;
        case "25":$rand_value = "y";
            break;
        case "26":$rand_value = "z";
            break;
        case "27":$rand_value = "0";
            break;
        case "28":$rand_value = "1";
            break;
        case "29":$rand_value = "2";
            break;
        case "30":$rand_value = "3";
            break;
        case "31":$rand_value = "4";
            break;
        case "32":$rand_value = "5";
            break;
        case "33":$rand_value = "6";
            break;
        case "34":$rand_value = "7";
            break;
        case "35":$rand_value = "8";
            break;
        case "36":$rand_value = "9";
            break;
    }
    return $rand_value;
}

function fechaES($fecha, $condia = false)
{
    $fecha     = substr($fecha, 0, 10);
    $numeroDia = date('d', strtotime($fecha));
    $dia       = date('l', strtotime($fecha));
    $mes       = date('F', strtotime($fecha));
    $anio      = date('Y', strtotime($fecha));
    $dias_ES   = array("Lunes", "Martes", "Miércoles", "Jueves", "Viernes", "Sábado", "Domingo");
    $dias_EN   = array("Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday", "Sunday");
    $nombredia = str_replace($dias_EN, $dias_ES, $dia);
    $meses_ES  = array("Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre");
    $meses_EN  = array("January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December");
    $nombreMes = str_replace($meses_EN, $meses_ES, $mes);
    if($condia != false){
        return $nombredia . " " . $numeroDia . " de " . $nombreMes . " de " . $anio;
    }else{
        return $numeroDia . " de " . $nombreMes . " de " . $anio;
    }
    
}


function quitar_tildes($cadena) {
$no_permitidas= array ("á","é","í","ó","ú","Á","É","Í","Ó","Ú","ñ","À","Ã","Ì","Ò","Ù","Ã™","Ã ","Ã¨","Ã¬","Ã²","Ã¹","ç","Ç","Ã¢","ê","Ã®","Ã´","Ã»","Ã‚","ÃŠ","ÃŽ","Ã”","Ã›","ü","Ã¶","Ã–","Ã¯","Ã¤","«","Ò","Ã","Ã„","Ã‹","ö");
$permitidas= array ("a","e","i","o","u","A","E","I","O","U","n","N","A","E","I","O","U","a","e","i","o","u","c","C","a","e","i","o","u","A","E","I","O","U","u","o","O","i","a","e","U","I","A","E","o");
$texto = str_replace($no_permitidas, $permitidas ,$cadena);
return $texto;
}

function limpiar_string($string)
{
    $string = trim($string);
    if (!mb_detect_encoding($string, 'UTF-8', true)) {
        $string = mb_convert_encoding($string, 'UTF-8', 'ISO-8859-1');
    }
    $string = str_replace(
        array('á', 'à', 'ä', 'â', 'ª', 'Á', 'À', 'Â', 'Ä'),
        array('a', 'a', 'a', 'a', 'a', 'A', 'A', 'A', 'A'),
        $string
    );

    $string = str_replace(
        array('é', 'è', 'ë', 'ê', 'É', 'È', 'Ê', 'Ë'),
        array('e', 'e', 'e', 'e', 'E', 'E', 'E', 'E'),
        $string
    );

    $string = str_replace(
        array('í', 'ì', 'ï', 'î', 'Í', 'Ì', 'Ï', 'Î'),
        array('i', 'i', 'i', 'i', 'I', 'I', 'I', 'I'),
        $string
    );

    $string = str_replace(
        array('ó', 'ò', 'ö', 'ô', 'Ó', 'Ò', 'Ö', 'Ô'),
        array('o', 'o', 'o', 'o', 'O', 'O', 'O', 'O'),
        $string
    );

    $string = str_replace(
        array('ú', 'ù', 'ü', 'û', 'Ú', 'Ù', 'Û', 'Ü'),
        array('u', 'u', 'u', 'u', 'U', 'U', 'U', 'U'),
        $string
    );

    $string = str_replace(
        array('ñ', 'Ñ', 'ç', 'Ç'),
        array('n', 'N', 'c', 'C'),
        $string
    );

    /*$string = str_replace(
        array("\\", "¨", "º", "-", "~",
            "#", "@", "|", "!", "\"",
            "·", "$", "%", "&", "/",
            "(", ")", "?", "'", "¡",
            "¿", "[", "^", "`", "]",
            "+", "}", "{", "¨", "´",
            ">", "< ", ";", ",", ":",
            "."),
        '',
        $string
    );*/

    $string = str_replace(
        array("¨", "º", "~",
            "#", "|", "!", 
            "·", "$", "%", "&",
            "(", ")", "?", "'", "¡",
            "¿", "[", "^", "`", "]",
            "+", "}", "{", "¨", "´",
            ">", "< ", ";", ",", ":"),
        '',
        $string
    );
    return $string;
}

function validUrl($str = '')
{
    $CI = &get_instance();
    if ($str == '') {
        $str = uri_string();
    }
    $stre = explode('/', $str);
    
    if(count($stre) > 1){
        if(count($stre) > 2){
            $str = $stre[0].'/'.$stre[1];
        }else{
            array_pop($stre);
            $str = implode('/', $stre);
        }
    }
    if (!array_key_exists($str, $CI->router->routes)) {
        show_404();
        exit();
    }
}

function show($data = [])
{
    $CI = &get_instance();
    $data['error']    = (validation_errors()) ? validation_errors() : $CI->session->flashdata('error');
    $data['info']     = $CI->session->flashdata('info');
    $data['success']  = $CI->session->flashdata('success');
    $CI->load->view($CI->base, $data);
}

function registrar_accion($tipo, $string)
{
    $file   = fopen(APPPATH . '/registros/'.$tipo.'/' . date('Y-m-d') . '.csv', 'a+');
    $string = date('Y-m-d H:i:s').' => '.$string;
    fwrite($file, $string . "\n");
    fclose($file);
}

function is_ajax(){
    $CI = &get_instance();
    $stream_clean = $CI->security->xss_clean($CI->input->raw_input_stream);
    $request = json_decode($stream_clean);
    if(isset($request) && is_array($request) && count($request) <1){
        show_404();
        die();
    }else{
        return $request;
    }
}

function adminPage()
{
    $CI = &get_instance();
    if (!$CI->ion_auth->in_group([1, 2, 3])){
        show_404();
        exit();
    }
}

function coachPage()
{
    $CI = &get_instance();
    if (!$CI->ion_auth->in_group([1, 2, 3, 5])){
        show_404();
        exit();
    }
}

function asistentePage()
{
    $CI = &get_instance();
    if (!$CI->ion_auth->in_group([1, 2, 3, 4])){
        show_404();
        exit();
    }
}

function asistenteCoachPage()
{
    $CI = &get_instance();
    if (!$CI->ion_auth->in_group([1, 2, 3, 4, 5])){
        show_404();
        exit();
    }
}

function logged()
{
    $CI = &get_instance();
    if (!$CI->ion_auth->logged_in()) {
        show_404();
    }
}

function isAjax()
{
    $CI = &get_instance();
    if (!$CI->input->is_ajax_request()) {
        show_404();
        exit();
    }
}

function validForm()
{
    $CI = &get_instance();
    if ($CI->form_validation->run() === false) {
        $response = [
            'error'            => 1,
            'error_validation' => $CI->form_validation->error_array(),
            'csrf'             => $CI->security->get_csrf_hash(),
        ];
        echo json_encode($response);
        exit();
    }
}

function returnAjax($response)
{
    echo json_encode($response);
    exit();
}

function input($field)
{
    $CI = &get_instance();
    return $CI->input->post($field);
}

function intToLetter($int)
{
    return chr(64 + $int);
}

function fechaCastellano($fecha)
{
    $fecha = substr($fecha, 0, 10);
    $numeroDia = date('d', strtotime($fecha));
    $dia = date('l', strtotime($fecha));
    $mes = date('F', strtotime($fecha));
    $anio = date('Y', strtotime($fecha));
    $dias_ES = array("Lunes", "Martes", "Miércoles", "Jueves", "Viernes", "Sábado", "Domingo");
    $dias_EN = array("Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday", "Sunday");
    $nombredia = str_replace($dias_EN, $dias_ES, $dia);
    $meses_ES = array("Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre");
    $meses_EN = array("January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December");
    $nombreMes = str_replace($meses_EN, $meses_ES, $mes);
    return $nombredia . ", " . $numeroDia . " de " . $nombreMes . " de " . $anio;
}
