<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Archivos extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        if (!$this->ion_auth->in_group([1, 2, 3])) {
            $this->session->set_flashdata('info', 'Inicie sesión');
            redirect('login', 'refresh');
        }

        $this->base = 'gestion/template';
    }

    public function index()
    {
        validUrl();
        $data = [];
        show($data);
    }

    public function nuevo()
    {
        validUrl();
        $data = [];
        show($data);
    }

    public function categorias()
    {
        validUrl();
        $data = [];
        show($data);
    }

    public function categoria_nueva()
    {
        validUrl();
        $data = [];
        show($data);
    }


    public function cartel_torneo()
    {
        adminPage();
        $this->form_validation->set_rules('torneo_id', 'Torneo', 'trim|required');
        if (empty($_FILES['archivo']['name'])) {
            $this->form_validation->set_rules('archivo', 'Archivo', 'required', ['required' => 'No se ha recibido ningun archivo']);
        }
        if ($this->form_validation->run() == false) {
            $this->session->set_flashdata('error', validation_errors());
            redirect($_SERVER['HTTP_REFERER'], 'refresh');
        }
        $upload_path = 'uploads/carteles/';
        $archivo_servidor = $this->utilidades->upload_file($_FILES['archivo'], $upload_path.'original/', '', 'jpeg|jpg|png|');
        
        if (!isset($archivo_servidor['display_errors'])) {
            //printr($archivo_servidor);
            $this->load->library('image_lib');
            $config_img['image_library'] = 'gd2';
            $config_img['source_image'] = FCPATH. $upload_path.'original/'.$archivo_servidor['file_name']; //$archivo_servidor['full_path'];
            $config_img['new_image'] = FCPATH.$upload_path.$archivo_servidor['file_name'];
            //$config_img['create_thumb'] = TRUE;
            $config_img['maintain_ratio'] = TRUE;
            $config_img['width']         = 500;
            $this->image_lib->clear();
            $this->image_lib->initialize($config_img);
            $this->image_lib->resize();
            if ( ! $this->image_lib->resize()){
                echo $this->image_lib->display_errors();
            }

            $datos['cartel'] = base_url().$upload_path . $archivo_servidor['file_name'];
            $this->database->actualizar('torneos', $datos, 'torneo_id', input('torneo_id'));
            $this->session->set_flashdata('success', 'Cartel actualizado');
            redirect($_SERVER['HTTP_REFERER'], 'refresh');
        } else {
            $this->session->set_flashdata('error', $archivo_servidor['display_errors']);
            redirect($_SERVER['HTTP_REFERER'], 'refresh');
        }
    }

    public function getArchivosTorneo($columna = null, $valor = null)
    {
        logged();
        $this->load->library('Datatable');
        $tabla  = 'documentos';
        $campos = [
            'documentos.titulo',
            'documentos.tipo',
            'documentos.acceso',
            'documentos.url',
            'documentos.item_rel',
            'documentos.item_id',
            $tabla . '.*'
        ];
        $join = [];
        $add_rule = [
            "group_by" => "documentos.documento_id",
        ];
        $where = [];
        if ($this->input->get('torneo_id') != '') {
            $where['documentos.item_rel'] = 'torneos';
            $where['documentos.item_id'] = $this->input->get('torneo_id');
        }
        if ($this->input->get('estado') != '') {
            $where['documentos.estado'] = $this->input->get('estado');
        }
        $where['documentos.deletedAt'] = '0000-00-00 00:00:00';
        if ($columna != "" && $valor != "") {
            $where  = [$tabla . '.' . $columna => $valor];
            $result = json_decode($this->datatable->get_datatable($this->input->get(), $tabla, $join, $campos, $where, $add_rule));
        } else {
            $result = json_decode($this->datatable->get_datatable($this->input->get(), $tabla, $join, $campos, $where, $add_rule));
        }
        $res = json_encode($result);
        echo $res;
    }

    public function manageFiles()
    {
        adminPage();
        $this->form_validation->set_rules('titulo', 'Título del archivo', 'trim|required');
        $this->form_validation->set_rules('tipo', 'Clasificación / categoría', 'trim|required');
        $this->form_validation->set_rules('acceso', 'Acceso permitido a usuario nivel', 'trim|required');
        $this->form_validation->set_rules('item_rel', 'Tipo de elemento relacionado', 'trim|required');
        $this->form_validation->set_rules('item_id', 'ID del elemento relacionado', 'trim|required');
        $this->form_validation->set_rules('estado', 'Estado', 'trim|required');
        $this->form_validation->set_rules('archivo_id', 'ID del archivo', 'trim|required');
        if($this->input->post('archivo_id') == 'nuevo' && empty($_FILES['documento']['name'])) {
            $this->form_validation->set_rules('documento', 'Archivo', 'required', ['required' => 'No se ha recibido ningun archivo']);
        }
        if ($this->form_validation->run() == false) {
            $this->session->set_flashdata('error', validation_errors());
            redirect($_SERVER['HTTP_REFERER'], 'refresh');
        }
        if($this->input->post('archivo_id') != 'nuevo'){
            $archivo = $this->database->buscarDato('documentos', 'documento_id', input('archivo_id'));
            if(!archivo){
                $this->session->set_flashdata('error', 'El archivo solicitado no existe.');
                redirect($_SERVER['HTTP_REFERER'], 'refresh');
            }
        }
        $params = [
            'titulo' => input('titulo'),
            'tipo' => input('tipo'),
            'acceso' => input('acceso'),
            'item_rel' => input('item_rel'),
            'item_id' => input('item_id'),
            'estado' => input('estado'),
        ];
        if(!empty($_FILES['documento']['name'])) {
            $upload_path = 'uploads/documentos/';
            $archivo_servidor = $this->utilidades->upload_file($_FILES['documento'], $upload_path, '', '', 'documento');
            if (!isset($archivo_servidor['display_errors'])) {
                $params['url'] = base_url().$upload_path . '/' . $archivo_servidor['file_name'];
            } else {
                $this->session->set_flashdata('error', $archivo_servidor['display_errors']);
                redirect($_SERVER['HTTP_REFERER'], 'refresh');
            }
        }
        if($this->input->post('archivo_id') != 'nuevo'){
            $archivo_actualizado = $this->database->actualizar('documentos', $params, 'documento_id', input('archivo_id'));
            if($archivo_actualizado == input('archivo_id')){
                if(isset($params['url'])){
                    $ruta = str_replace(base_url(), FCPATH, $archivo->url);
                    if (file_exists($ruta)) {
                        unlink($ruta);
                    }
                }
                $this->session->set_flashdata('success', 'Archivo / documento actualizado');
                redirect($_SERVER['HTTP_REFERER'], 'refresh');
            }else{
                $this->session->set_flashdata('error', 'No se han realizado cambios en el archivo / documento');
                redirect($_SERVER['HTTP_REFERER'], 'refresh');
            }
        }else{
            $archivo_nuevo = $this->database->insert('documentos', $params);
            if($archivo_nuevo){
                $this->session->set_flashdata('success', 'Archivo / documento creado correctamente');
                redirect($_SERVER['HTTP_REFERER'], 'refresh');
            }else{
                $this->session->set_flashdata('error', 'No se han creado el archivo / documento');
                redirect($_SERVER['HTTP_REFERER'], 'refresh');
            }
        }
    }
}

