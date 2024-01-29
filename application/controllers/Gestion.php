<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Gestion extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        if (!$this->ion_auth->in_group([1, 2, 3, 4, 5, 6])) {
            $this->session->set_flashdata('info', 'Inicie sesión');
            redirect('login', 'refresh');
        }

        $this->base = 'gestion/template';
    }

    public function index()
    {
        validUrl();
        if(!$this->ion_auth->in_group([1,2,3])){
            redirect('torneos');
        }
        $data['page_header']    = 'Inicio';
        show($data);
    }
  
}

