<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Login extends MY_Controller {
	
	public function index()
	{		 
		if ($this->_is_login) {
			redirect(base . '/dashboard');
		}else{
			if ($this->input->post()) {
				$param = $this->input->post();
				
				$param['table'] = 'user';
				$password = crypt(md5($param['userpassword']),'$6$1P455w0rDny4=5899$hrisP4$$W0rD$');
				$this->db->join('karyawan','karyawan.karyawan_id = user.karyawan_id');
				$this->db->where('karyawan_nik', $param['username']);
				$this->db->where('userpassword', $password);
				$_cek = $this->model_generic->_cek($param);
					if($_cek){
						$param['table'] = 'user';
						$this->db->where('karyawan_nik', $param['username']);
						$this->db->where('userpassword', $password);
						$this->db->join('karyawan','karyawan.karyawan_id = user.karyawan_id');
						$this->db->join('personal','personal.personal_id = karyawan.personal_id');
						$user_info = $this->model_generic->_get($param);
						// opn($user_info);exit();
						$_SESSION['user_info'] = $user_info;
						redirect(base . '/dashboard');
					}else{
						redirect(base . '/login');
					}
			}
				$this->parser->parse('login.html', $this->data, false);
		}
	}
	
}
