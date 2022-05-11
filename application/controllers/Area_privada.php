<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Area_privada extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        if (!$this->ion_auth->logged_in()) {
            $this->session->set_flashdata('info', 'Inicie sesión');
            redirect('login', 'refresh');
        }
        $this->base = 'gestion/template';
    }

    public function index()
    {
        validUrl();
        logged();
        $data = [];
        $data['page_header']    = 'Area privada';
        show($data);
    }

    public function perfil()
    {
        validUrl();
        logged();
        $data['page_header']    = 'Mi perfil de usuario';
        $data['view']     = 'gestion/datos_usuario';
        $data['user']     = $this->user;
        $data['perfil']    = TRUE;
        $data['js_files'] = [base_url() . 'assets/admin/js/vistas/perfil.js'];
        show($data);
    }

    public function editar_perfil_usuario_form()
    {
        //validUrl();
        logged();
        isAjax();
        if ($this->input->post('userID') == '') {
            $response = [
                'error'     => 1,
                'error_msn' => 'Identificador de usuario no válido.',
                'csrf'      => $this->security->get_csrf_hash(),
            ];
            returnAjax($response);
        }
        $user = $this->utilidades->user($this->input->post('userID'));
        if (!$user) {
            $response = [
                'error'     => 1,
                'error_msn' => 'El usuario no existe.',
                'csrf'      => $this->security->get_csrf_hash(),
            ];
            returnAjax($response);
        }

        $this->form_validation->set_rules('first_name', 'Nombre', 'required');
        $this->form_validation->set_rules('last_name', 'Apellidos', 'required');
        if ($user->email != $this->input->post('email')) {
            $this->form_validation->set_rules('email', 'Email', 'required|is_unique[users.email]', ['is_unique' => 'El email ya está en uso']);
        } else {
            $this->form_validation->set_rules('email', 'Email', 'required');
        }
        if ($user->dni != $this->input->post('dni')) {
            $this->form_validation->set_rules('dni', 'DNI, NIF, NIE...', 'trim|is_unique[users.dni]', ['is_unique' => 'El dni ya está en uso']);
        } else {
            $this->form_validation->set_rules('dni', 'DNI, NIF, NIE...', 'trim');
        }
        $this->form_validation->set_rules('phone', 'Teléfono', 'trim');
        if ($this->input->post('newpassword') != '') {
            $this->form_validation->set_rules('newpassword', 'Nueva contraseña', 'min_length[8]|matches[confirmpassword]', ['matches' => 'Las contraseñas no coinciden']);
            $this->form_validation->set_rules('confirmpassword', 'Confirmar contraseña', 'required');
        }
        validForm();
        if ($user->first_name != $this->input->post('first_name')) {
            $datos['first_name'] = $this->input->post('first_name');
        }
        if ($user->last_name != $this->input->post('last_name')) {
            $datos['last_name'] = $this->input->post('last_name');
        }
        if ($user->dni != $this->input->post('dni')) {
            $datos['dni'] = $this->input->post('dni');
        }
        if ($user->email != $this->input->post('email')) {
            $datos['email'] = $this->input->post('email');
        }
        if ($user->phone != $this->input->post('phone')) {
            $datos['phone'] = $this->input->post('phone');
        }

        if ($this->input->post('newpassword') != '') {
            $datos['password'] = $this->input->post('newpassword');
        }

        $cambio_estado = 0;
        if ($this->input->post('active') == 'OK') {
            if ($user->active == 0) {
                $this->ion_auth->activate($user->id);
                $cambio_estado = 1;
            }
        } else {
            if ($user->active == 1) {
                $this->ion_auth->deactivate($user->id);
                $cambio_estado = 1;
            }
        }
        $cambio_datos = 0;
        if (!empty($datos)) {
            $this->ion_auth->update($this->input->post('userID'), $datos);
            $cambio_datos = 1;
        }
        if ($cambio_datos == 1 || $cambio_estado == 1) {
            $response = [
                'error' => 0,
                'msn'   => $this->ion_auth->messages(),
                'csrf'  => $this->security->get_csrf_hash(),
            ];
            echo json_encode($response);
            exit();
        } else {
            if ($this->ion_auth->errors()) {
                $error_msn =  $this->ion_auth->errors();
            } else {
                $error_msn = 'No se han realizado cambios en los datos del usuario';
            }
            $response = [
                'error'     => 1,
                'error_msn' => $error_msn,
                'csrf'      => $this->security->get_csrf_hash(),
            ];
            returnAjax($response);
        }
    }
}
