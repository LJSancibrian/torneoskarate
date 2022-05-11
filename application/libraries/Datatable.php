<?php if (!defined('BASEPATH')) exit('No direct script access allowed');


class Datatable {
    
    protected $datatable;
    private $database;
    
    public function __construct($db = NULL)
    {
        $this->datatable =& get_instance();
    }

    public function get_datatable($param, $tabla, $join = null, $aColumns = null, $where = null, $add_rule=null){
        $iDraw = $param['draw'];
        $iColumns = $param['columns'];
        $iOrder = (isset($param['order'])) ? $param['order']: false;
        $iStart = $param['start'];
        $iLength = $param['length'];
        $iSearch = $param['search'];
        $select = implode(',', $aColumns);
        //PRIMERA CONSULTA PARA EL RECORDTOTAL
        // $add_rule
        if(isset($add_rule) && ($add_rule != '')){
            foreach ($add_rule as $key => $value) {
                if($value == ''){
                    $this->datatable->db->$key();
                }elseif(is_array($value)){
                    if($key == 'or_where'){
                        foreach ($value as $k => $v) {
                            $this->datatable->db->$key($v[0], $v[1]);
                        }
                    }else{
                        if(is_array($value)){
                            foreach ($value as $k => $v) {
                                $this->datatable->db->$key($v[0], $v[1]);
                            }
                        }else{
                            $this->datatable->db->$key($value[0], $value[1]);
                        }
                    }
                }else{
                    $this->datatable->db->$key($value);
                }
            }
        }
        // EL QUE
        $this->datatable->db->select($select);
        // DE DONDE
        $this->datatable->db->from($tabla);
        if((isset($join)) && ($join != '')){
            foreach ($join as $t_join => $condicion) {
                $this->datatable->db->join($t_join, $condicion, 'left');
            }
        }
        //CONDICION PRINCIPAL
        if(isset($where) && $where != '') {
            foreach ($where as $campo => $valor) {
                $this->datatable->db->where($campo, $valor);
            }
        }
        $recordsTotal = $this->datatable->db->get()->num_rows();
        $recordsFiltered = $recordsTotal;
        ////////////////////////////////////////////////////////////////
        //SEGUNDA CONSULTA PARA EL RECORDSFITERED
        // $add_rule
        if(isset($add_rule) && ($add_rule != '')){
            foreach ($add_rule as $key => $value) {
                if($value == ''){
                    $this->datatable->db->$key();
                }elseif(is_array($value)){
                    if($key == 'or_where'){
                        foreach ($value as $k => $v) {
                            $this->datatable->db->$key($v[0], $v[1]);
                        }
                    }else{
                        if(is_array($value)){
                            foreach ($value as $k => $v) {
                                $this->datatable->db->$key($v[0], $v[1]);
                            }
                        }else{
                            $this->datatable->db->$key($value[0], $value[1]);
                        }
                    }
                }else{
                    $this->datatable->db->$key($value);
                }
            }
        }
        // EL QUE
        $this->datatable->db->select($select);
        // DE DONDE
        $this->datatable->db->from($tabla);
        if((isset($join)) && ($join != '')){
            foreach ($join as $t_join => $condicion) {
                $this->datatable->db->join($t_join, $condicion, 'left');
            }
        }
        //CONDICION PRINCIPAL
        if(isset($where) && $where != '') {    
            foreach ($where as $campo => $valor) {
                $campo = explode(' AS ', $campo);
                $campo = end($campo);
                $this->datatable->db->where($campo, $valor);
            }
        } 
        //CONDICIONES EXTRA
        if(isset($iSearch) && $iSearch['value'] != '') {  
            $this->datatable->db->group_start(); 
            for($i=0; $i < count($aColumns); $i++) { 
                if(strpos($aColumns[$i], ".*") === false){
                    switch ($aColumns[$i]) {            
                        default:
                            $columna_de_ordenación = explode(' AS ', $aColumns[$i]); // $aColumns[$i];
                            break;
                    }
                    $columna_de_ordenación = $columna_de_ordenación[0];
                    if($i == 0){
                        $this->datatable->db->like($columna_de_ordenación, $iSearch['value']);
                    }else{
                        $this->datatable->db->or_like($columna_de_ordenación, $iSearch['value']);
                    }
                }else{
                    if($i == 0){
                        $i--;
                    }
                }
            }
            $this->datatable->db->group_end();
            $Data = $this->datatable->db->get();
        }else{
            $Data = $this->datatable->db->get();
        }
        $recordsFiltered = $Data->num_rows();
        //TERCERA CONSULTA PARA LOS RESULTADOS Y PAGINACIÓN
        // Ordering
        if(isset($iOrder) && $iOrder != false) {
            for($i=0; $i < count($iOrder); $i++) {
                switch ($aColumns[$iOrder[0]['column']]) {      
                    default:
                        $columna_de_ordenación = explode(' AS ', $aColumns[$iOrder[0]['column']]);
                        break;
                }
                $columna_de_ordenación = end($columna_de_ordenación);
                $this->datatable->db->order_by($columna_de_ordenación, strtoupper($iOrder[0]['dir']));
            }
        } else {
            $columna_de_ordenación = explode(' AS ', $aColumns[0]);
            $columna_de_ordenación = end($columna_de_ordenación);
            $this->datatable->db->order_by($columna_de_ordenación, 'ASC');
        }
        // Paging
        if(isset($iStart) && $iLength != '-1') {
            $this->datatable->db->limit($iLength, $iStart);
        } elseif(isset($iStart) && $iLength != '-1'){
            $this->datatable->db->limit($iLength, 1);
        }
        // $add_rule
        if(isset($add_rule) && ($add_rule != '')){
            foreach ($add_rule as $key => $value) {
                if($value == ''){
                    $this->datatable->db->$key();
                }elseif(is_array($value)){
                    if($key == 'or_where'){
                        foreach ($value as $k => $v) {
                            $this->datatable->db->$key($v[0], $v[1]);
                        }
                    }else{
                        if(is_array($value)){
                            foreach ($value as $k => $v) {
                                $this->datatable->db->$key($v[0], $v[1]);
                            }
                        }else{
                            $this->datatable->db->$key($value[0], $value[1]);
                        }
                    }
                }else{
                    $this->datatable->db->$key($value);
                }
            }
        }
        // EL QUE
        $this->datatable->db->select($select);
        // DE DONDE
        $this->datatable->db->from($tabla);
        if((isset($join)) && ($join != '')){
            foreach ($join as $t_join => $condicion) {
                $this->datatable->db->join($t_join, $condicion, 'left');
            }
        }
        //CONDICION PRINCIPAL
        if(isset($where) && $where != '') {    
            foreach ($where as $campo => $valor) {
                $campo = explode(' AS ', $campo);
                $campo = end($campo);
                $this->datatable->db->where($campo, $valor);
            }
        } 
        //CONDICIONES EXTRA
        if(isset($iSearch) && $iSearch['value'] != '') {  
            $this->datatable->db->group_start(); 
            for($i=0; $i < count($aColumns); $i++) {
                if(strpos($aColumns[$i], ".*") === false){
                    switch ($aColumns[$i]) {
                        default:
                            $columna_de_ordenación = explode(' AS ',$aColumns[$i]);// $aColumns[$i];
                            break;
                    }
                    $columna_de_ordenación = $columna_de_ordenación[0];
                    if($i == 0){
                        $this->datatable->db->like($columna_de_ordenación, $iSearch['value']);
                    }else{
                        $this->datatable->db->or_like($columna_de_ordenación, $iSearch['value']);
                    }
                }else{
                    if($i == 0){
                        $i--;
                    }
                }
            }
            $this->datatable->db->group_end();
            $Data = $this->datatable->db->get();
            //$recordsFiltered = $Data->num_rows();
        }else{
            $Data = $this->datatable->db->get();
        }
        // QUERY
        $this->datatable->db->last_query();
        // JSON enconding
        $json = json_encode([
            "draw" => isset($iDraw) ? $iDraw : 1,
            "recordsTotal" => $recordsTotal,
            "recordsFiltered" => $recordsFiltered,
            "data" => $Data->result()
        ]);
        
        return $json;
    }
}