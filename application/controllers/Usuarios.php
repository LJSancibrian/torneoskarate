<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Usuarios extends CI_Controller
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

    public function administradores()
    {
        validUrl();
        $data['page_header']    = 'Usuarios administradores';
        if($this->user->group->id == 1){
            $data['default_filter'] = '1|2|3';
        }else{
            $data['default_filter'] = '2|3';
        }
        $data['view']           = 'gestion/common/tabla_datatable';
        $data['js_files']       = [assets_url() . 'admin/js/vistas/usuarios.js'];
        show($data);
    }

    public function auxiliares()
    {
        validUrl();
        $data['page_header']    = 'Usuarios auxiliares';
        $data['default_filter'] = '4';
        $data['view']           = 'gestion/common/tabla_datatable';
        $data['js_files']       = [assets_url() . 'admin/js/vistas/usuarios.js'];
        show($data);
    }

    public function nuevo()
    {
        validUrl();
        $data['page_header']    = 'Nuevo usuario';
        $data['view']     = 'gestion/datos_usuario';
        $data['js_files'] = [assets_url() . 'admin/js/vistas/usuario_nuevo.js'];
        show($data);
    }

    public function getUsuarios($columna = null, $valor = null)
    {
        $this->load->library('Datatable');
        $tabla  = 'users';
        $campos = [
            $tabla . '.id',
            $tabla . '.usercode',
            $tabla . '.first_name',
            $tabla . '.last_name',
            $tabla . '.phone',
            $tabla . '.email',
            $tabla . '.active',
            $tabla . '.*',
        ];
        $join     = ['users_groups' => $tabla .'.id = users_groups.user_id'];
        $add_rule = [
            "group_by" => $tabla . ".id",
        ];
        $where = [];
        if($this->input->get('rol') != ''){         
            $groups = explode('|', $this->input->get('rol'));
            if(count($groups) > 0){
                $add_rule['group_start'] = '';
                foreach ($groups as $key => $value) {
                    //echo $key.'-'.$value;
                    if($key === 0){
                        $add_rule['where'][] = ['users_groups.group_id',$value];
                    }else{
                        $add_rule['or_where'][] = ['users_groups.group_id',$value];
                    }
                }
                $add_rule['group_end'] = '';
            }else{
                $where['users_groups.group_id'] = $this->input->get('rol');
            }
        }
        if ($columna != "" && $valor != "") {
            $where  = [$tabla . '.' . $columna => $valor];
            $result = json_decode($this->datatable->get_datatable($this->input->get(), $tabla, $join, $campos, $where, $add_rule));
        } else {
            $result = json_decode($this->datatable->get_datatable($this->input->get(), $tabla, $join, $campos, $where, $add_rule));
        }
        
        $res = json_encode($result);
        echo $res;
    }

    public function nuevo_usuario_form()
    {
        if (!$this->input->is_ajax_request()) {
            show_404();
            exit();
        }
        $tables                  = $this->config->item('tables', 'ion_auth');
        $identity_column         = $this->config->item('identity', 'ion_auth');
        $data['identity_column'] = $identity_column;
        $this->form_validation->set_rules('first_name', 'Nombre', 'trim|required');
        $this->form_validation->set_rules('last_name', 'Apellidos', 'trim|required');
        $this->form_validation->set_rules('dni', 'DNI, NIF, NIE...', 'trim|required|validDni|is_unique[' . $tables['users'] . '.dni]', ['is_unique' => 'El dni ya está en uso']);
        $this->form_validation->set_rules('phone', 'Teléfono', 'trim|required');
        $this->form_validation->set_rules('rol', 'Rol', 'trim|required');
        $this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email|is_unique[' . $tables['users'] . '.email]', ['is_unique' => 'El email ya está en uso']);
        $this->form_validation->set_rules('newpassword', 'Contraseña', 'required|min_length[' . $this->config->item('min_password_length', 'ion_auth') . ']|matches[confirmpassword]');
        $this->form_validation->set_rules('confirmpassword', 'Confirmar contraseña', 'required');

        if ($this->form_validation->run() === false) {
            $response = [
                'error'            => 1,
                'error_validation' => $this->form_validation->error_array(),
                'csrf'             => $this->security->get_csrf_hash(),
            ];
            echo json_encode($response);
            exit();
        } else {
            $email           = strtolower($this->input->post('email'));
            $identity        = ($identity_column === 'email') ? $email : $this->input->post('identity');
            $password        = $this->input->post('newpassword');
            $additional_data = [
                'first_name' => $this->input->post('first_name'),
                'last_name'  => $this->input->post('last_name'),
                'dni'        => $this->input->post('dni'),
                'phone'      => $this->input->post('phone'),
                'usercode'   => $this->utilidades->rand_unique('users', 'usercode', 10, TRUE),
            ];
            $registro = $this->ion_auth->register($identity, $password, $email, $additional_data, [$this->input->post('rol')]);
            if ($registro) {
                if($this->input->post('active') == 'OK'){
                    $this->ion_auth->activate($registro['id']);
                }
                //$this->ion_auth->remove_from_group(NULL, $registro['id']);
                //$this->ion_auth->add_to_group($this->input->post('rol'), $registro['id']);
                if($this->input->post('rol') == 4){
                    $redirect = base_url() . 'usuarios/auxiliares';
                }else{
                    $redirect = base_url() . 'usuarios/administradores';
                }
                $response = [
                    'error'    => 0,
                    'msn'      => 'Usuario creado',
                    'redirect' => $redirect,
                    'csrf'     => $this->security->get_csrf_hash(),
                ];
                echo json_encode($response);
                exit();
            } else {
                $response = [
                    'error'     => 1,
                    'error_msn' => $this->ion_auth->errors(),
                    'csrf'      => $this->security->get_csrf_hash(),
                ];
                echo json_encode($response);
                exit();
            }
        }
    }

    public function ver($usuarioID)
    {
        
        $user = $this->database->buscarDato('users', 'usercode', $usuarioID);
        if(!$user){
            $user = $this->utilidades->user($usuarioID);
            if(!$user){
                show_404();
            }
        }
        $data['page_header']    = 'Datos de '.$user->first_name.' '.$user->last_name;
        $data['view']     = 'gestion/datos_usuario';
        $data['user']     = $this->utilidades->user($user->id);
        $data['js_files'] = [assets_url() . 'admin/js/vistas/usuario.js'];
        show($data);
    }

    public function editar_usuario_form()
    {
        if (!$this->input->is_ajax_request()) {
            show_404();
            exit();
        }
        if ($this->input->post('userID') == '') {
            $response = [
                'error'     => 1,
                'error_msn' => 'Identificador de usuario no válido.',
                'csrf'      => $this->security->get_csrf_hash(),
            ];
            echo json_encode($response);
            exit();
        }
        $user = $this->utilidades->user($this->input->post('userID'));
        if (!$user) {
            $response = [
                'error'     => 1,
                'error_msn' => 'El usuario no existe.',
                'csrf'      => $this->security->get_csrf_hash(),
            ];
            echo json_encode($response);
            exit();
        }

        $this->form_validation->set_rules('first_name', 'Nombre', 'required');
        $this->form_validation->set_rules('last_name', 'Apellidos', 'required');
        if ($user->email != $this->input->post('email')) {
            $this->form_validation->set_rules('email', 'Email', 'required|is_unique[users.email]', ['is_unique' => 'El email ya está en uso']);
        } else {
            $this->form_validation->set_rules('email', 'Email', 'required');
        }
        if ($user->dni != $this->input->post('dni')) {
            $this->form_validation->set_rules('dni', 'DNI, NIF, NIE...', 'trim|required|validDni|is_unique[users.dni]', ['is_unique' => 'El dni ya está en uso']);
        } else {
            $this->form_validation->set_rules('dni', 'DNI, NIF, NIE...', 'trim|required');
        }
        $this->form_validation->set_rules('phone', 'Teléfono', 'required');
        $this->form_validation->set_rules('rol', 'Rol', 'trim|required');
        if ($this->input->post('newpassword') != '') {
            $this->form_validation->set_rules('newpassword', 'Nueva contraseña', 'matches[confirmpassword]', ['matches' => 'Las contraseñas no coinciden']);
            $this->form_validation->set_rules('confirmpassword', 'Confirmar contraseña', 'required');
        }
        if ($this->form_validation->run() === false) {
            $response = [
                'error'            => 1,
                'error_validation' => $this->form_validation->error_array(),
                'csrf'             => $this->security->get_csrf_hash(),
            ];
            echo json_encode($response);
            exit();
        }
        if($user->first_name != $this->input->post('first_name')){ $datos['first_name'] = $this->input->post('first_name');}
        if($user->last_name != $this->input->post('last_name')){ $datos['last_name'] = $this->input->post('last_name');}
        if($user->dni != $this->input->post('dni')){ $datos['dni'] = $this->input->post('dni');}
        if($user->email != $this->input->post('email')){ $datos['email'] = $this->input->post('email');}
        if($user->phone != $this->input->post('phone')){ $datos['phone'] = $this->input->post('phone');}
       
        if ($this->input->post('newpassword') != '') {
            $datos['password'] = $this->input->post('newpassword');
        }
        $cambio_rol = 0;
        if ($user->group->id != $this->input->post('rol')) {
            $this->ion_auth->remove_from_group($user->group->id, $user->id);
            $this->ion_auth->add_to_group($this->input->post('rol'), $user->id);
            $cambio_rol = 1;
        }
        $cambio_estado = 0;
        if($this->input->post('active') == 'OK'){
            if($user->active == 0){
                $this->ion_auth->activate($user->id);
                $cambio_estado = 1;
            }
        }else{
            if($user->active == 1){
                $this->ion_auth->deactivate($user->id);
                $cambio_estado = 1;
            }
        }
        $cambio_datos = 0;
        if(!empty($datos)){
            $this->ion_auth->update($this->input->post('userID'), $datos);
            $cambio_datos = 1;
        }
        
        if ( $cambio_datos == 1 || $cambio_rol == 1 || $cambio_estado == 1) {
            $response = [
                'error' => 0,
                'msn'   => $this->ion_auth->messages(),
                'csrf'  => $this->security->get_csrf_hash(),
            ];
            echo json_encode($response);
            exit();
        } else {
            if($this->ion_auth->errors()){
                $error_msn =  $this->ion_auth->errors();
            }else{
                $error_msn = 'No se han realizado cambios en los datos del usuario';
            }
            $response = [
                'error'     => 1,
                'error_msn' => $error_msn,
                'csrf'      => $this->security->get_csrf_hash(),
            ];
            echo json_encode($response);
            exit();
        }
    }

    public function ver_usuario_fetch()
    {
        if (!$this->input->is_ajax_request()) {
            show_404();
            exit();
        }
        $this->form_validation->set_rules('user_id', 'ID usuario', 'trim|required');
        if ($this->form_validation->run() === false) {
            $response = [
                'error'            => 1,
                'error_validation' => $this->form_validation->error_array(),
                'csrf'             => $this->security->get_csrf_hash(),
            ];
            echo json_encode($response);
            exit();
        } else {
            $user_id = $this->input->post('user_id');
            $user = $this->database->buscarDato('users', 'id', $user_id);
            if (!$user) {
                $response = [
                    'error'     => 1,
                    'error_msn' => 'Usuarios no encontrado.',
                    'csrf'      => $this->security->get_csrf_hash(),
                ];
                echo json_encode($response);
                exit();
            }           
            $response = [
                'error'    => 0,
                'data'      => ['responsable' => $this->utilidades->user($user->id)],
                'csrf'     => $this->security->get_csrf_hash(),
            ];
            echo json_encode($response);
            exit();
        }
    }

    function generar_auxiliares()
    {
        for ($i=1; $i < 9; $i++) { 
            $email           = 'tatami'.$i.'@kime.es';
            $password        = 'mesa'.$i;
            $additional_data = [
                'first_name' => 'Mesa',
                'last_name'  => 'Tatami '.$i,
                'dni'        => rand_alphanumeric(8, 'UPPER'),
                'phone'      => 600000006,
                'usercode'   => $this->utilidades->rand_unique('users', 'usercode', 10, TRUE),
            ];
            $registro = $this->ion_auth->register($email, $password, $email, $additional_data, [4]);
            if ($registro) {
                $this->ion_auth->activate($registro['id']);
            }
        }
    }
}

