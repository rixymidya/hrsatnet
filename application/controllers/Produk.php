<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Produk extends MY_Controller {
	
	public function __construct()
    {
        parent::__construct();
        // parent::__construct();
        $this->data['header']      = $this->parser->parse('layout/_header.html', $this->data, true);
        $this->data['footer']      = $this->parser->parse('layout/_footer.html', $this->data, true);
        $this->data['body']        = $this->parser->parse('layout/_content.html', $this->data, true);
	}
	
	public function index()
	{
			redirect(base);
			// header('location:google.com');
	}
	
	public function detail()
	{
		$arg = func_get_args();
			if (isset($arg[0])) {
				$produk = $arg[0];
				$param['table'] = 'produk';
				$this->db->where('produk_id', $produk);
				$cek_produk = $this->model_generic->_cek($param);
					if($cek_produk){
						$param['table'] = 'produk';
						$this->db->where('produk_id', $produk);
						$all_produk = $this->model_generic->_get($param);
							foreach($all_produk as $key => $value){
								switch ($value->produk_kategori){
									case 1:
										$value->kategori ='Outdoor';
									break;
									case 2:
										$value->kategori ='Indoor';
									break;
									case 3:
										$value->kategori ='Aksesoris';
									break;
								}
								
								switch ($value->produk_jenis){
									case 1:
										$value->jenis ='Analog';
									break;
									case 2:
										$value->jenis ='HD';
									break;
									case 3:
										$value->jenis ='Analog HD';
									break;
									case 4:
										$value->jenis ='IP Camera';
									break;
								}
							}
						// opn ($value->produk_deskripsi);exit();
						$this->data['detail_produk'] = $all_produk;
						
						if($value->produk_keyword == null){
						    $this->data['keyword'] = 'CCTV, Balikpapan, CCTV Balikpapan, Kamera Pengintai, Kamera CCTV Balikpapan, ' .$value->produk_nama;     
						}else{
						    $this->data['keyword'] = $value->produk_keyword;
						}
						
						if($value->produk_deskripsi == null){
						    $this->data['deskripsi'] = 'CCTV Balikpapan - '.$value->produk_nama;   
						}else{
						    $this->data['deskripsi'] = $value->produk_deskripsi;
						}
						
						$param['table'] = 'produk';
						$this->db->order_by('produk_id', 'RANDOM');
						$this->db->limit(4);
						$produk_lain = $this->model_generic->_get($param);
						$this->data['produk_lain'] = $produk_lain;
					// opn($produk_lain);exit();
						
					}else{
						redirect(base);
					}
				
		$this->data['content'] = $this->parser->parse('produk/detail.html', $this->data, true);
		$this->parser->parse('layout/_index.html', $this->data, false);
			}else{
				redirect(base);
			}
	}
}
