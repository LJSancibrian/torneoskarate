<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Eventos extends CI_Controller
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

    public function calendario()
    {
        validUrl();
        $data = [];
        show($data);
    }
}

