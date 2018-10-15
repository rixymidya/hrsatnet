<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class MY_Controller extends CI_Controller {
  
  public function __construct(){
    parent::__construct();
    date_default_timezone_set("Asia/Jakarta");
    
    //opn(timezone_version_get());

    $this->_is_login = false;
    $this->_is_logout = true;
    $this->_is_admin = false;
    $this->_is_user = false;
	
	$this->data['is_admin'] = '';
	$this->data['is_user'] = '';
	
	// opn($_SESSION);exit();
    
    if(!isset($_SESSION['user_info'])){ 
		// $user_info = array();
		//$this->data['user_name'] = $user_info; 
		// opn($user_info);exit();
		$this->data['user_info'] = array();
	}
    else{ 
		$user_info = $_SESSION['user_info'];
		// opn($user_info);exit();
		$this->data['user_info'] = $user_info;
		
		// $this->_user_role = $_SESSION['user_info'][0]->user_role[0]->role_id;
		$this->_is_login   = true; 
		$this->_is_logout   = false; 
		
		
			foreach($user_info as $key => $value){
				$param['table'] = 'karyawan';
				$this->db->join('personal', 'personal.personal_id = karyawan.personal_id');
				$this->db->where('karyawan_nik', $user_info[0]->karyawan_nik);
				$karyawan = $this->model_generic->_get($param); 
				// opn($karyawan);exit();
					if($karyawan[0]->foto != null){
						$user_info[0]->foto =$karyawan[0]->foto;
					}else{
						switch ($karyawan[0]->jenis_kelamin){
							case 1:
								$user_info[0]->foto ='blank1.jpg';
							break;
							case 2:
								$user_info[0]->foto = 'blank2.jpg';
							break; 
							default:
								$user_info[0]->foto = 'blank.png';
							break; 
						}
					}
					
			}
			
			// $param['table'] = 'menu_akses';
			// $this->db->where('level', $this->_user_role);
			// $menu = $this->model_generic->_get($param);
			// opn($menu);exit();
			// $this->data['menu_akses'] = $menu;

	}	/// ini salah tempat
	$this->data['is_admin'] = $this->_is_admin?:'hidden destroy';
	$this->data['is_user'] = $this->_is_user?:'hidden destroy';
	$this->data['is_login'] = $this->_is_login?:'hidden destroy';
	$this->data['is_logout'] = $this->_is_logout?:'hidden destroy';
    
	//Pengaturan Web
	$param['table'] = 'setting';
	$this->db->where('setting_id',1);
	$setting = $this->model_generic->_get($param);
	$this->data['nama_app'] = $setting[0]->nama_app;
	$this->data['title_app'] = $setting[0]->title_app;
	//opn($setting[0]->nama_app);exit();
	
	$this->data['keyword'] = 'CCTV, Balikpapan, CCTV Balikpapan, Kamera Pengintai, Kamera CCTV Balikpapan'; 
	$this->data['tahun_ini'] = date("Y");
	
    $base = str_replace($_SERVER['SERVER_ADDR'], $_SERVER['HTTP_HOST'], base_url());
	$base = str_replace('[','',$base);
	$base = str_replace(']','',$base);
	define('base', rtrim($base,'/'));
    $this->data['base'] = base;  
	define('BASE', dirname(dirname(__DIR__)));
	define('DS', DIRECTORY_SEPARATOR);
	
  }
  
	 /*****************************************************************************/
    // public function _menu()
    // {
        // if ($this->_is_login) {
			// $param['table'] = 'menu_akses';
			// $menu = $this->model_generic->_get($param);
			// $this->data['menu_akses'] = $menu;
        // }
		// return 0;
    // }
}