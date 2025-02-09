<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Home extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->base = 'public/template';
    }
    public function index()
    {
        $data['page_header']    = 'Torneos de Karate';
        $data['page_sub_header']    = 'Ayuntamiento de Piélagos';

        // se buscan los grupos, ordenados por fecha torneo
        $grupos = $this->database->getTorneosHome();
        $data['grupos'] = $grupos;
        $params = [
            'tabla' => 'torneos',
            'where' => [
                'estado' => 1,
                'deletedAt' => '0000-00-00 00:00:00',
                'fecha >=' => date('Y-m-d'),
            ],
            'order_by' => [
                'fecha' => 'asc'
            ]
        ];
        $proximos_torneos = $this->database->getWhere($params, 'nopage');
        $data['proximos_torneos'] = [];
        if (count($proximos_torneos) > 0) {
            $data['proximos_torneos'] = $proximos_torneos;
        }

        $params2 = [
            'tabla' => 'torneos',
            'where' => [
                'estado' => 1,
                'deletedAt' => '0000-00-00 00:00:00',
                'fecha <' => date('Y-m-d'),
            ],
            'order_by' => [
                'fecha' => 'desc'
            ]
        ];
        $torneos_pasados = $this->database->getWhere($params2, 'nopage');
        $data['torneos_pasados'] = [];
        if (count($torneos_pasados) > 0) {
            $data['torneos_pasados'] = $torneos_pasados;
        }
        $data['view']           = 'public/girdtorneos';
        show($data);
    }

    public function torneos($slug)
    {
        validUrl();
        $data = [];
        $torneo = $this->database->buscarDato('torneos', 'slug', $slug);
        if (!isset($torneo) || $torneo == false) {
            show_404();
        }
        $data['page_header']    =  $torneo->titulo;
        $data['torneo'] = $torneo;
        if ($torneo->tipo != 2) {
            $data['competicioneskata'] = $this->database->getCompeticionesTorneo($torneo->torneo_id, 'KATA');
            foreach ($data['competicioneskata'] as $key => $competicionkata) {
                if ($competicionkata->tipo == 'puntos') {
                    $data['competicioneskata'][$key]->clasificacionfinal = $this->database->clasificacionFinalKata($competicionkata->competicion_torneo_id);
                } else {
                    $data['competicioneskata'][$key]->clasificacionfinal = $this->database->clasificacionGlobalKumite($competicionkata->competicion_torneo_id, [1, 2, 3]);
                }
            }
        }

        if ($torneo->tipo != 1) {
            $data['competicioneskumite'] = $this->database->getCompeticionesTorneo($torneo->torneo_id, 'KUMITE');
            foreach ($data['competicioneskumite'] as $key => $competicionkumite) {
                $clasificacion = $this->database->clasificacionKumite($competicionkumite->competicion_torneo_id);
                if (!empty($clasificacion) && count($clasificacion) > 0) {
                    $data['competicioneskumite'][$key]->clasificacionfinal = $clasificacion;
                } else {
                    //$data['competicioneskumite'][$key]->clasificacionfinal = [];
                }
            }
        }
        // clubes participantes
        $data['clubs'] = $this->database->torneoClubs($torneo->torneo_id);
        $data['view']           = 'public/vistatorneo';
        show($data);
    }

    public function clasificacion_kumite($competicion_torneo_id)
    {
        $this->database->clasificacionKumite($competicion_torneo_id);
    }

    public function clasificacion_kata($competicion_torneo_id)
    {
        $finalkata = $this->database->clasificacionFinalKata($competicion_torneo_id, [1, 2, 3]);
        $kata = $this->database->clasificacionKata($competicion_torneo_id, [1, 2]);
    }

    public function vercompeticion($slug, $competicion_torneo_id)
    {
        $data = [];
        $competicion = $this->database->getCompeticion($competicion_torneo_id);
        if (!isset($competicion) || $competicion == false) {
            show_error('La competicion no existe');
        }
        $torneo = $this->database->buscarDato('torneos', 'slug', $slug);
        if (!isset($torneo) || $torneo == false || $torneo->deletedAt != '0000-00-00 00:00:00') {
            show_error('El torneo no existe o ha finalizado');
        }
        if ($competicion->torneo_id != $torneo->torneo_id) {
            show_error('La competición no existe en el torneo.');
        }
        if ($competicion->estado < 1) {
            $data['view'] = 'public/vistacompeticionnosorteo';
        }
        if ($competicion->estado > 0) {
            if ($competicion->tipo == 'puntos') {
                $data['rondaspuntos'] = $this->database->getrondaskata($competicion_torneo_id);
                $data['ordenparticipacion'] = $this->database->inscritosOrdenCompeticion($competicion_torneo_id);
                if($competicion->iniciacion == 0){
                        $data['finalistas'] = $this->database->finalKata($competicion_torneo_id);
                }else{
                    $data['finalistas'] = [];
                }
                $data['view'] = 'public/vistacompeticionkata';
                $data['js_files'] = [
                    base_url() . 'assets/public/js/vistacompeticionkata.js',
                ];
            }
            if ($competicion->tipo == 'liguilla' || $competicion->tipo == 'eliminatoria') {
                $data['view'] = 'public/vistacompeticionkumite';
                $data['matches'] = $this->database->getMatchesTree($competicion_torneo_id);
                if($competicion->tipo == 'liguilla' && $competicion->iniciacion == 1){
                    $data['eliminatorias'] = [];
                }else{
                    $data['eliminatorias'] = $this->database->getEliminatoriasTree($competicion_torneo_id);
                }

                $data['css_files']       = [
                    assets_url() . 'plugins/jquery.gracket/style.css',
                ];
                $data['js_files'] = [
                    assets_url() . 'plugins/jquery.gracket/jquery.gracket.js',
                    base_url() . 'assets/public/js/vistacompeticionkumite.js',
                ];
            }

            if ($competicion->tipo == 'rey') {
                $data['ordenparticipacion'] = $this->database->inscritosOrdenCompeticion($competicion_torneo_id);
                $grupos = [];
                foreach ($data['ordenparticipacion']['ordenados'] as $key => $part) {
                    $grupos[$part->grupo][] = $part;
                }
                $data['grupos'] = $grupos;
                $data['view'] = 'public/vistacompeticionrey';
                $eliminatorias = $this->database->getEliminatoriasTree($competicion_torneo_id);
                for ($i = 1; $i <= count($grupos); $i++) {
                    $data['players'][$i] = $this->database->clasificacionGrupoRey($competicion_torneo_id, $i);
                }

                $data['eliminatorias'] = $eliminatorias;
                $data['css_files']       = [
                    assets_url() . 'plugins/jquery.gracket/style.css',
                ];
                $data['js_files'] = [
                    assets_url() . 'plugins/jquery.gracket/jquery.gracket.js',
                    base_url() . 'assets/public/js/vistacompeticionrey.js',
                ];
            }
        }

        $data['page_header']    =  $competicion->modalidad . ' ' . $competicion->categoria . ' ' . $competicion->nivel;
        $data['torneo'] = $torneo;
        $data['competicion'] = $competicion;
        show($data);
    }

    public function LM2022()
    {
        validUrl();
        $data = [];
        $competiciones = $this->database->getCompeticionesLM(2022);
        $competicioneskata = [];
        $competicioneskumite = [];
        $sibling = [];
        foreach ($competiciones as $key => $value) {
            if ($value->modalidad == 'kata' && !in_array($value->sibling_id, $sibling)) {
                $competicioneskata[] = $value;
                $sibling[] = $value->competicion_torneo_id;
            } elseif ($value->modalidad == 'kumite' && !in_array($value->sibling_id, $sibling)) {
                $competicioneskumite[] = $value;
                $sibling[] = $value->competicion_torneo_id;
                if ($value->sibling_id > 0) {
                    $sibling[] = $value->sibling_id;
                };
            }
        }
        $data['page_header']    =  'LIGA MUNICIPAL 2022<br>AYTO. PIÉLAGOS';
        $data['kata'] = $competicioneskata;
        $data['kumite'] = $competicioneskumite;
        $data['view']           = 'public/vistalm2022';
        show($data);
    }

    public function compLM2022($competicion_torneo_id)
    {
        //validUrl();
        $data = [];
        $competicion = $this->database->getCompeticion($competicion_torneo_id);
        if (!isset($competicion) || $competicion == false) {
            show_error('La competicion no existe');
        }
        // buscar las categorias hijas
        $jornadas = $this->database->getSiblings($competicion_torneo_id, [$competicion_torneo_id]);
        $clasificacion = $this->database->get_clasificacionLM($competicion_torneo_id);
        $data['page_header']    =  $data['page_header']    =  $competicion->modalidad . ' ' . $competicion->categoria . ' ' . $competicion->nivel;
        $data['competicion'] = $competicion;
        $data['jornadas'] = $jornadas;
        $data['clasificacion'] = $clasificacion;
        $data['view']          = 'public/vistacompeticionlm2022';
        show($data);
    }

    public function clasificaciongrupo($grupo_id)
    {
        // validUrl();
        $data = [];
        $grupo = $this->database->buscarDato('torneos_grupos', 'grupo_id', $grupo_id);
        $competiciones = $this->database->getCompeticionesGrupo($grupo_id);
        $competicioneskata = [];
        $competicioneskumite = [];
        $sibling = [];
        foreach ($competiciones as $key => $value) {
            if ($value->modalidad == 'kata' && !in_array($value->sibling_id, $sibling)) {
                $competicioneskata[] = $value;
                $sibling[] = $value->competicion_torneo_id;
            } elseif ($value->modalidad == 'kumite' && !in_array($value->sibling_id, $sibling)) {
                $competicioneskumite[] = $value;
                $sibling[] = $value->competicion_torneo_id;
                if ($value->sibling_id > 0) {
                    $sibling[] = $value->sibling_id;
                };
            }
        }
        $data['page_header']    =  strtoupper($grupo->titulo) . '<br>AYTO. PIÉLAGOS';
        $data['kata'] = $competicioneskata;
        $data['kumite'] = $competicioneskumite;
        $data['view']           = 'public/vistalm2022';
        show($data);
    }

    public function patrocinadores(){
        
        $this->load->view('public/patrocinadores');
    }

    public function scoreboard(){
        $this->load->view('gestion/torneos/scoreboard');
    }


    /**************
     * 
     * ACCESO
     * 
     */
    public function login()
    {
        validUrl();
        if (!$this->ion_auth->logged_in()) {
            $this->form_validation->set_rules('identity', 'Email', 'required');
            $this->form_validation->set_rules('password', 'Contraseña', 'required');
            if ($this->form_validation->run() === true) {
                if ($this->ion_auth->login($this->input->post('identity'), $this->input->post('password'), false)) {
                    $this->session->set_flashdata('success', $this->ion_auth->messages());
                    if (!$this->ion_auth->in_group([1, 2, 3, 4, 5, 6, 7, 8, 9, 10])) {
                        if ($this->input->post('gotourl') != '') {
                            redirect($this->input->post('gotourl'), 'refresh');
                        } else {
                            redirect('actividades', 'refresh');
                        }
                    } else {
                        redirect('gestion', 'refresh');
                    }
                } else {
                    $this->session->set_flashdata('error', $this->ion_auth->errors());
                    if ($this->input->post('gotourl') != '') {
                        $this->session->set_flashdata('gotourl', $this->input->post('gotourl'));
                    }
                    redirect('login', 'refresh');
                }
            } else {
                $data['error']    = (validation_errors()) ? validation_errors() : $this->session->flashdata('error');
                $this->session->set_flashdata('error', $data['error']);
                if ($this->input->post('gotourl') != '') {
                    $this->session->set_flashdata('gotourl', $this->input->post('gotourl'));
                }
                $data['page_header']    = 'Área Privada';
                $data['page_sub_header']    = 'Torneos de Karate';
                $data['view']           = 'public/secciones/login';
                show($data);
            }
        } else if (!$this->ion_auth->in_group([1, 2, 3, 4])) {
            if ($this->session->flashdata('referer') != '') {
                redirect($this->session->flashdata('referer'), 'refresh');
            } else {
                redirect('gestion', 'refresh');
            }
        } else {
            redirect('gestion', 'refresh');
        }
    }

    public function loginUser()
    {
        if (!$this->input->is_ajax_request()) {
            show_404();
            exit();
        }
        $this->form_validation->set_rules('captcha', 'Captcha', 'trim|valid_captcha');
        $this->form_validation->set_rules('password', 'Password', 'required');
        $this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email');
        if ($this->form_validation->run() === true) {
            if ($this->ion_auth->login($this->input->post('email'), $this->input->post('password'), false)) {
                $user = $this->ion_auth->user()->row();
                if ($this->ion_auth->in_group([1, 2, 3])) {
                    $url = base_url() . 'gestion';
                } else {
                    $url = base_url() . 'area-privada';
                }
                $response = [
                    'msn'      => 'Hola, ' . $user->first_name,
                    'userID'   => $user->id,
                    'csrf'     => $this->security->get_csrf_hash(),
                    'redirect' => $url,
                ];
                echo json_encode($response);
                exit();
            } else {
                $response = [
                    'error'     => 1,
                    'error_msn' => 'No se puede recuperar los datos del usuario. Datos de acceso incorrecto',
                    'csrf'      => $this->security->get_csrf_hash(),
                ];
                echo json_encode($response);
                exit();
            }
        } else {
            $response = [
                'error'            => 1,
                'error_validation' => $this->form_validation->error_array(),
                'csrf'             => $this->security->get_csrf_hash(),
            ];
            echo json_encode($response);
            exit();
        }
    }

    public function logout()
    {
        validUrl();
        $this->ion_auth->logout();
        redirect('', 'refresh');
    }

    public function recordar_password()
    {
        validUrl();
        $this->form_validation->set_rules('identity', 'Correo electrónico', 'required|valid_email');
        if ($this->form_validation->run() === false) {
            $data['error']    = (validation_errors()) ? validation_errors() : $this->session->flashdata('error');
            $this->session->set_flashdata('error', $data['error']);
            if ($this->input->post('gotourl') != '') {
                $this->session->set_flashdata('gotourl', $this->input->post('gotourl'));
            }
            $data['page_header']    = 'Área privada';
            $data['page_sub_header']    = 'Torneos de Karate';
            $data['view']           = 'public/secciones/recuperar_contrasena';
            show($data);
        } else {
            $identity = $this->ion_auth->where('email', $this->input->post('identity'))->users()->row();
            if (empty($identity)) {
                $this->session->set_flashdata('error', 'El email indicado no se corresponde con ninguna cuenta de usuario.');
                $data['page_header']    = 'Área privada';
                $data['page_sub_header']    = 'Torneos de Karate';
                $data['view']           = 'public/secciones/recuperar_contrasena';
                show($data);
            } else {
                $forgotten = $this->ion_auth->forgotten_password($identity->{$this->config->item('identity', 'ion_auth')});
                if ($forgotten) {
                    /*$url =  base_url().'recuperar-password-confirm/'.$forgotten['forgotten_password_code'];
                    $this->session->set_flashdata('info', 'Sistema para recuperar contraseña automáticamente');
                    redirect($url, 'refresh');*/
                    if ($_SERVER['SERVER_NAME'] == 'localhost') {
                        $url = base_url() . 'recuperar-password/' . $forgotten['forgotten_password_code'];
                        $this->session->set_flashdata('error', 'Sistema para recuperar contraseña automáticamente');
                        redirect($url, 'refresh');
                    } else {
                        $user_id = $identity->id;
                        $user    = $this->ion_auth->user($user_id)->row();
                        $link    = base_url() . 'recuperar-password/' . $forgotten['forgotten_password_code'];
                        $nombre  = $user->first_name . ' ' . $user->last_name;
                        $emaildata = [
                            'nombre' => $nombre,
                            'link' => $link
                        ];
                        $mensaje = $this->load->view('emails/recuperar_password', $emaildata, true);
                        $datos = [
                            'subject' => 'Recuperar contraseña',
                            'message' => $mensaje,
                            'to'      => $user->email,
                        ];

                        if ($this->utilidades->sendmail($datos) == true) {
                            $this->session->set_flashdata('success', 'Hemos enviado un correo electrónico con indicaciones para reestablecer la contraseña. Revise la bandeja de entrada y el correo no deseado.');
                            redirect('login', 'refresh');
                        } else {
                            $this->session->set_flashdata('error', 'Ha ocurrido un error al enviar el código de recuperación. Por favor, inténtalo más tarde.');
                            $data['identity'] = [
                                'name'        => 'identity',
                                'id'          => 'identity',
                                'type'        => 'email',
                                'class'       => 'form-control border-blue',
                                'placeholder' => 'Correo electrónico de la cuenta',
                                'value'       => $this->form_validation->set_value('identity'),
                            ];
                            $data['setting'] = $this->Model_common->all_setting();
                            $data['page_about'] = $this->Model_common->all_page_about();
                            $data['comment'] = $this->Model_common->all_comment();
                            $data['social'] = $this->Model_common->all_social();
                            $data['portfolio_footer'] = $this->Model_portfolio->get_portfolio_data();
                            $this->load->view('view_header', $data);
                            $this->load->view('usuarios/recordar_password', $data);
                            $this->load->view('view_footer', $data);
                        }
                    }
                } else {
                    $this->session->set_flashdata('error', $this->ion_auth->errors());
                    $data['identity'] = [
                        'name'        => 'identity',
                        'id'          => 'identity',
                        'type'        => 'email',
                        'class'       => 'form-control border-blue',
                        'placeholder' => 'Correo electrónico de la cuenta',
                        'value'       => $this->form_validation->set_value('identity'),
                    ];
                    $data['setting'] = $this->Model_common->all_setting();
                    $data['page_about'] = $this->Model_common->all_page_about();
                    $data['comment'] = $this->Model_common->all_comment();
                    $data['social'] = $this->Model_common->all_social();
                    $data['portfolio_footer'] = $this->Model_portfolio->get_portfolio_data();
                    $this->load->view('view_header', $data);
                    $this->load->view('usuarios/recordar_password', $data);
                    $this->load->view('view_footer', $data);
                }
            }
        }
    }

    public function recuperar_password_code($code = null)
    {
        //validUrl();
        if (!$code) {
            show_404();
        }
        $user = $this->ion_auth->forgotten_password_check($code);
        if ($user) {
            $this->form_validation->set_rules('new', $this->lang->line('reset_password_validation_new_password_label'), 'required|min_length[8]|matches[new_confirm]');
            $this->form_validation->set_rules('new_confirm', $this->lang->line('reset_password_validation_new_password_confirm_label'), 'required');
            if ($this->form_validation->run() === false) {
                $data['error']               = (validation_errors()) ? validation_errors() : $this->session->flashdata('error');
                $this->session->set_flashdata('error', $data['error']);
                $data['user_id'] = $user->id;
                $data['page_header']    = 'Área privada';
                $data['page_sub_header']    = 'Torneos de Karate';
                $data['view']           = 'public/secciones/recuperar_password_code';
                show($data);
            } else {
                $identity = $user->{$this->config->item('identity', 'ion_auth')};
                if ($user->id != $this->input->post('user_id')) {
                    $this->ion_auth->clear_forgotten_password_code($identity);
                    show_error($this->lang->line('error_csrf'));
                } else {
                    $change = $this->ion_auth->reset_password($identity, $this->input->post('new'));
                    if ($change) {
                        // regenerar el token de la api
                        // $this->load->model('funcionesapi');
                        // $this->load->helper(['jwt', 'authorization']);
                        // $user = $this->ion_auth->user($user->id)->row();s
                        // $token = JWT::encode(['pass' => $user->password, 'email' =>  $user->email], $this->config->item('encryption_key'));
                        // $params = ['token' => $token];
                        // $this->funcionesapi->actualizar('users', $params, 'id', $user->id);
                        $this->session->set_flashdata('success', $this->ion_auth->messages());
                        redirect('login', 'refresh');
                    } else {
                        $this->session->set_flashdata('error', $this->ion_auth->errors());
                        redirect('recuperar-password/' . $code, 'refresh');
                    }
                }
            }
        } else {
            $this->session->set_flashdata('error', $this->ion_auth->errors());
            redirect("recordar-password", 'refresh');
        }
    }


    function phpinfo()
    {
        phpinfo();
    }


    public function create_finalesLM()
    {
        //adminPage();
        $competiciones = $this->database->getCompeticionesLM(2022);
        // printr($competiciones);
        $competicioneskata = [];
        $competicioneskumite = [];
        $sibling = [];
        foreach ($competiciones as $key => $value) {
            if ($value->modalidad == 'kata' && !in_array($value->sibling_id, $sibling)) {
                $competicioneskata[] = $value;
                $sibling[] = $value->competicion_torneo_id;
                if ($value->nivel == 'C' || $value->nivel == 'D') { //open mixto d
                    $data = [
                        'sibling_id' => ($value->sibling_id == 0) ? $value->competicion_torneo_id : $value->sibling_id,
                        'tipo' => 'eliminatoria',
                        'torneo_id' => 4,
                        'modalidad' =>  $value->modalidad,
                        'categoria' =>  'GRAN FINAL ' . $value->categoria,
                        'genero' =>  $value->genero,
                        'nivel' =>  $value->nivel,
                    ];
                    $competicion_torneo_id = $this->database->insert('torneos_competiciones', $data);
                }
            } elseif ($value->modalidad == 'kumite' && !in_array($value->sibling_id, $sibling)) {
                $competicioneskumite[] = $value;
                $sibling[] = $value->competicion_torneo_id;
                if ($value->sibling_id > 0) {
                    $sibling[] = $value->sibling_id;
                };
                if ($value->nivel != 'A' && $value->nivel != 'TEAM' && $value->nivel != '-') {
                    $data = [
                        'sibling_id' => ($value->sibling_id == 0) ? $value->competicion_torneo_id : $value->sibling_id,
                        'tipo' => 'eliminatoria',
                        'torneo_id' => 4,
                        'modalidad' =>  $value->modalidad,
                        'categoria' =>  'GRAN FINAL ' . $value->categoria,
                        'genero' =>  $value->genero,
                        'nivel' =>  $value->nivel,
                    ];
                    $competicion_torneo_id = $this->database->insert('torneos_competiciones', $data);
                }
            }
        }
    }
}
