<?php defined('BASEPATH') or exit('No direct script access allowed');
class Utilidades
{
    protected $u;

    public function __construct()
    {
        $this->u = &get_instance();
        $this->u->load->database();
        $this->u->user = $this->user();
    }

    public function user($id = '')
    {
         if ($this->u->ion_auth->logged_in()) {
            if ($id != '') {
                $user = $this->u->ion_auth->user($id)->row();
            } else {
                $user = $this->u->ion_auth->user()->row();
            }
            $user->groups    = $this->u->ion_auth->get_users_groups($user->id)->result();
            foreach ($user->groups as $key => $groups) {
                $user->groups_ids[] = $groups->id;
            }
            $user->group = (isset($user->groups[0])) ? $user->groups[0] : (object)[];
            $user->rol = (isset($user->groups[0])) ? $user->groups[0]->description : 'Sin grupo';
            return $user;
        } else {
            return false;
        }
    }

    public function upload_file($files, $upload_path, $filename = '', $allowed_types = '', $inputname = '', $overwrite = '')
    {
        $this->u->load->library('upload');
        $ruta       = explode('/', $upload_path);
        $deep       = count($ruta);
        $ruta_check = '';
        for ($i = 0; $i < $deep; $i++) {
            if ($ruta_check != '') {
                $ruta_check = $ruta_check . '/' . $ruta[$i];
            } else {
                $ruta_check = $ruta[$i];
            }
            if (!file_exists($ruta_check)) {
                mkdir($ruta_check, 0777, true);
            }
        }

        if ($allowed_types == '') {
            if ($files['type'] == 'application/vnd.ms-excel') {
                $allowed_types = '*';
            } else {
                $allowed_types = 'jpeg|jpg|png|pdf|doc|docx|xls|xlsx|ppt|pptx|txt|rtf|csv';
            }
        }
        //$allowed_types           = '*'; //por si este es el problema de la carga de archivos.
        $config['upload_path']   = $upload_path;
        $config['allowed_types'] = $allowed_types; //'jpg|png|pdf|doc|docx|xls|xlsx|ppt|pptx|txt|rtf|csv';
        if ($filename != '') {
            $config['file_name'] = $filename;
        } else {
            $config['encrypt_name'] = true;
        }

        if ($overwrite != '') {
            $config['overwrite'] = $overwrite;
        } else {
            $config['overwrite'] = false;
        }
        $this->u->upload->initialize($config);
        if ($inputname == '') {
            $inputname = 'archivo';
        }
        if (!$this->u->upload->do_upload($inputname)) {
            $data = ['display_errors' => $this->u->upload->display_errors('', '')];
        } else {
            $data = $this->u->upload->data();
        }
        return $data;
    }

    public function sendmail($datos, $forzado = false)
    {
        /***************************
        Se reibe un array con los siguientes elementos
        (minimo):
        'to' => email de destino
        'subject' => asunto del envio
        (opcional):
        'config' => otra configuracion que no sea la de por defecto
        'attach' => archivos adjuntos, en array
        'bcc' => para mas de un destinatario
        'message' => para enviar un email especiífico
        /***************************/
        $this->u->load->config('emails');
        if ($_SERVER['SERVER_NAME'] == 'localhost' || $this->u->config->item('smtp_user') == '') {
            return true;
        } else {
            if (isset($datos['configemail'])) {
                $config = $datos['configemail'];
            } else {
                if($this->u->config->item('protocol') != ''){
                    $config['protocolo'] = $this->u->config->item('protocol');
                }
                if($this->u->config->item('smtp_host') != ''){
                    $config['smtp_host'] = $this->u->config->item('smtp_host');
                }
                if($this->u->config->item('smtp_port') != ''){
                    $config['smtp_port'] = $this->u->config->item('smtp_port');
                }
                if($this->u->config->item('smtp_user') != ''){
                    $config['smtp_user'] = $this->u->config->item('smtp_user');
                }
                if($this->u->config->item('smtp_pass') != ''){
                    $config['smtp_pass'] = $this->u->config->item('smtp_pass');
                }
                if($this->u->config->item('protocol') != ''){
                    $config['protocolo'] = $this->u->config->item('protocol');
                }
            }

            $config['mailtype'] = $this->u->config->item('mailtype');
            $config['charset'] = $this->u->config->item('charset_email');
            $config['bcc_batch_mode'] = $this->u->config->item('bcc_batch_mode');
            $config['validation'] = $this->u->config->item('validation');

            $this->u->load->library("email");
            $this->u->email->clear(TRUE);
            $this->u->email->initialize($config);
            if (isset($datos['from'])) {
                $this->u->email->from($datos['from'], 'Karatepielagos.com');
            }else{
                $this->u->email->from($this->u->config->item('smtp_user'), 'Karatepielagos.com');
            }
            
            $this->u->email->to($datos['to']);
            $this->u->email->subject($datos['subject']);
            if (isset($datos['attach'])) {
                foreach ($datos['attach'] as $k => $file) {
                    $this->u->email->attach($file);
                }
            }
            if (isset($datos['bcc'])) {
                $this->u->email->to('');
                $this->u->email->bcc($datos['bcc']);
            }
            if (isset($datos['replay_to'])) {
                $this->u->email->reply_to($datos['replay_to']['email'], $datos['replay_to']['name']);
            }

            if (isset($datos['message'])) {
                $message = $datos['message'];
            } else {
                $message = $this->u->load->view('emails/plantilla_base', $datos, true);
            }
            

            $this->u->email->message($message);
            if ($this->u->email->send()) {
                return true;
            } else {
                return $this->u->email->print_debugger();
            }
        }
    }

    public function rand_unique($tabla, $field, $length, $upper = false)
    {
        $code = rand_alphanumeric($length, $upper);
        $this->u->db->where($field, $code);
        $code_exist = $this->u->db->get($tabla)->num_rows();
        if ($code_exist > 0) {
            $this->rand_unique($tabla, $field, $length, $upper);
        } else {
            return $code;
        }
    }


    function randomDate($start_date, $end_date, $nonumbreday = [])
    {
        
        $min = strtotime($start_date);
        $max = strtotime($end_date);
        $val = rand($min, $max);
        $date = date('Y-m-d', $val);
        if(count($nonumbreday) > 0){
            if(in_array($date,$nonumbreday)){
                $date = $this->randomDate($start_date, $end_date, $nonumbreday);
            }
        }
        return $date;
    }

    function breadcrumb(){
        $num_segments = $this->u->uri->total_segments();
        $link = base_url();
        $string = '';
        for ($i=0; $i <= $num_segments; $i++) { 
        	$bread=$this->u->uri->segment($i);
        	if($i == 0){
        		$string .= '<ul class="breadcrumbs">';
        		$string .= '<li class="nav-home">';
        		$string .= '<a href="'.base_url().'gestion"><i class="flaticon-home"></i></a></li>';
        	}else{
        		$link .= $bread;
        		$string .= '<li class="separator"><i class="flaticon-right-arrow"></i></li>';
                $string .= '<li class="nav-item"><a href="'.$link.'">'.ucfirst($bread).'</a></li>';
                $link .= '/';
        	}
        	if($i == $num_segments){
        		$string .= '</ul>';
        	}  				        	
        }  
        return $string;
	}

}
