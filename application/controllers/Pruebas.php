<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Pruebas extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->base = 'public/template';
    }
    public function index()
    {
        
        $data['view']           = 'pruebas/index';
        show($data);
    }

}