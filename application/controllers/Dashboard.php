<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends MY_Controller {
	
	public function __construct()
    {
        parent::__construct();
        $this->data['header']		= $this->parser->parse('dashboard/header.html', $this->data, true);
        $this->data['side_left']	= $this->parser->parse('dashboard/side_left.html', $this->data, true);
        $this->data['footer']		= $this->parser->parse('dashboard/footer.html', $this->data, true);
        $this->data['body']			= $this->parser->parse('dashboard/content.html', $this->data, true);
        $this->data['breadcrum']	= $this->parser->parse('dashboard/breadcrum.html', $this->data, true);
		$this->data['class']		= __CLASS__;
		
		if ($this->_is_login != true) { redirect(base);}  
	}
	
	public function index()
	{  
		// $param['table'] 	= 	'karyawan';
		// $karyawan		 	= 	$this->model_generic->_get($param);
			// foreach($karyawan as $key => $value){
				// $param['table'] 	= 	'kota_penempatan';
				// $total_karyawan 	= 	$this->model_generic->_count($param);
				// $this->data['total_karyawan'] = $total_karyawan;
			// }
		// opn($value);exit();
		
		$param['table'] 	= 	'karyawan';
		$total_karyawan 	= 	$this->model_generic->_count($param);
		$this->data['total_karyawan'] = $total_karyawan;
		
		$param['table'] 	= 	'karyawan';
		$this->db->where('penempatan_id', '1');
		$total_karyawan_bpp = 	$this->model_generic->_count($param);
		$this->data['total_karyawan_bpp'] = $total_karyawan_bpp;
		$param['table'] 	= 	'karyawan';
		
		
		$this->db->where('penempatan_id', '2');
		$total_karyawan_jkt = 	$this->model_generic->_count($param);
		$this->data['total_karyawan_jkt'] = $total_karyawan_jkt;
		
		$this->db->where('penempatan_id', '3');
		$total_karyawan_bdg = 	$this->model_generic->_count($param);
		$this->data['total_karyawan_bdg'] = $total_karyawan_bdg;
		// opn($total_karyawan_bpp);exit();
		
		
		$this->data['content'] = $this->parser->parse('dashboard/dashboard.html', $this->data, true);
		$this->parser->parse('layout.html', $this->data, false);
	}
	public function _index()
	{
		// opn($this->_is_login);exit();//buat ngeliat 
		if ($this->_is_login) {
			if ($this->input->post()) {
				 
				$param                  = $this->input->post(null, true);
				
				 $config['upload_path']          = './aset/images/produk/';
					$config['allowed_types']        = 'gif|jpg|png';
					$config['max_size']             = 100000;
					$config['max_width']            = 102400;
					$config['max_height']           = 76800;

					$this->load->library('upload', $config);
			// opn($config);exit();

					if ( ! $this->upload->do_upload('foto'))
					{
							$error = array('error' => $this->upload->display_errors());

							opn($error);exit();
					}
					else
					{
							$data = array('foto' => $this->upload->data());

							// opn();exit();
					}
				
				$param['table'] = 'produk';
				$param['produk_foto'] = $data['foto']['file_name'];
				$this->model_generic->_insert($param);
				redirect(base.'/dashboard');
			}
			
		$param_produk['table'] = 'produk';
		$all_produk = $this->model_generic->_get($param_produk);
			foreach ($all_produk as $key => $value){
				switch($value->produk_kategori){
					case 1: 
						$value->kategori = 'Violet';
					break;
					case 2: 
						$value->kategori = 'Orange';
					break;
					case 3: 
						$value->kategori = 'Blue';
					break;
				}
				switch($value->produk_jenis){
					case 1: 
						$value->jenis = 'Set';
					break;
					case 2: 
						$value->jenis = 'Khimar';
					break;
					case 3: 
						$value->jenis = 'Dress';
					break;
				}
			}
			// opn($value);exit();
		$this->data['all_produk'] = $all_produk;
		// opn($all_produk);exit();
		
		$this->data['content'] = $this->parser->parse('admin/dashboard.html', $this->data, true);
		$this->parser->parse('layout/_index_admin.html', $this->data, false);
		
		}else{
			// redirect(base . '/login');
			echo 'lu ngapain tong ?';
		}
	}
	
}
