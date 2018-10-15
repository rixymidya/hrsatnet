<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Karyawan extends MY_Controller {
	
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
		$param['table'] 	= 	'karyawan';
		
		$this->db->join('personal','personal.personal_id = karyawan.personal_id');
		$this->db->join('kantor_penempatan','kantor_penempatan.kantor_penempatan_id = karyawan.penempatan_id');
		$this->db->join('project','project.project_id = karyawan.project_id');
		
// $this->db->join('table1', 'table1.id = table2.id');
		$all_karyawan 		= 	$this->model_generic->_get($param);
			foreach($all_karyawan as $key => $value){
				
			// jenis kelamin
				switch ($value->jenis_kelamin){
					case 1:
						$value->jenis_kelamin ='L';
					break;
					case 2:
						$value->jenis_kelamin ='P';
					break;
					default :
						$value->jenis_kelamin ='-';
					break;
				}
			}
		// opn ($all_karyawan);exit();
        $this->data['all_karyawan']	= $all_karyawan;
		
		$this->data['content'] = $this->parser->parse('karyawan/karyawan.html', $this->data, true);
		$this->parser->parse('layout.html', $this->data, false);
	}
	
	public function add()
	{	
		if($this->input->post()){
			$param 		= 	$this->input->post(null, true);	
			opn($param);exit();
			$param['table'] 	= 	'karyawan';
			$this->model_generic->_insert($param);
			redirect(base.'/'.$class);
		}
		$this->data['content'] = $this->parser->parse('karyawan/tambah_karyawan.html', $this->data, true);
		$this->parser->parse('layout.html', $this->data, false);
	}
	public function delete()
	{	
		$arg = func_get_args();
			if (isset($arg[0])) {
				$karyawan = $arg[0];
				$param['table'] = 'karyawan';
				$this->db->where('karyawan_id', $karyawan);
				$this->model_generic->_del($param);
				redirect(base.'/karyawan');
			}
	}
	
	public function detail()
	{
		$arg = func_get_args();
			if (isset($arg[0])) {
				$karyawan = $arg[0];
				$param['table'] = 'karyawan';
				$this->db->where('karyawan_id', $karyawan);
				$cek_karyawan = $this->model_generic->_cek($param);
					if($cek_karyawan){
						$param['table'] = 'karyawan';
						$this->db->join('personal','personal.personal_id = karyawan.personal_id');
						$this->db->join('kantor_penempatan','kantor_penempatan.kantor_penempatan_id = karyawan.penempatan_id');
						$this->db->join('project','project.project_id = karyawan.project_id');
						$this->db->join('jabatan','jabatan.jabatan_id = karyawan.jabatan_id');
						$this->db->where('karyawan_id', $karyawan);
						$all_karyawan = $this->model_generic->_get($param);
						// opn($all_karyawan);exit();
							foreach($all_karyawan as $key => $value){
								
							// keluarga personal 
								$param['table'] 	= 	'keluarga_personal';
								$this->db->where('personal_id', $value->personal_id);
								$value->keluarga_personal = $this->model_generic->_get($param);
								$value->jumlah_anak = $this->model_generic->_count($param);
									$keluarga_personal = $value->keluarga_personal;
									$this->data['keluarga_personal'] = $keluarga_personal;
										foreach($keluarga_personal as $k => $v){
											switch ($v->keluarga_personal_status){
												case 1:
													$v->keluarga_personal_status ='Ayah';
												break;
												case 2:
													$v->keluarga_personal_status ='Ibu';
												break;
												case 3:
													$v->keluarga_personal_status ='Saudara';
												break;
												case 4:
													$v->keluarga_personal_status ='Istri';
												break;
												case 5:
													$v->keluarga_personal_status ='Anak';
												break;
												default :
													$v->keluarga_personal_status ='-';
												break;


											}
										
										// $this->db->select('keluarga_personal_status');
										$this->db->where('keluarga_personal_status', 4);
										$nama_pasangan = $this->model_generic->_get($param);
										$value->nama_pasangan = $nama_pasangan[0]->keluarga_personal_nama;
											
									// opn ($v);exit();
										}

							// sertifikat 
								$param['table'] 	= 	'sertifikat';
								$this->db->where('karyawan_id', $value->karyawan_id);
								$value->sertifikat_karyawan = $this->model_generic->_get($param);
									$sertifikat_karyawan = $value->sertifikat_karyawan;
									$this->data['sertifikat_karyawan'] = $sertifikat_karyawan;
										foreach($sertifikat_karyawan as $k => $v){
											
											$v->sertifikat_terbit = date('d F Y', strtotime($v->sertifikat_terbit));
											$v->sertifikat_expired = date('d F Y', strtotime($v->sertifikat_expired));
											switch ($v->sertifikat_kategori){
												case 1:
													$v->sertifikat_kategori ='Wajib';
												break;
												case 2:
													$v->sertifikat_kategori ='Opsional';
												break;
												default:
													$v->sertifikat_kategori ='-';
												break;
											}
										}
										
										// opn ($sertifikat_karyawan);exit();

							// kontrak karyawan` 
								$param['table'] 	= 	'kontrak_karyawan';
								$this->db->where('karyawan_id', $value->karyawan_id);
								$value->kontrak_karyawan = $this->model_generic->_get($param);
									$kontrak_karyawan = $value->kontrak_karyawan;
									$this->data['kontrak_karyawan'] = $kontrak_karyawan;
										foreach($kontrak_karyawan as $k => $v){
											$v->kontrak_mulai = date('d F Y', strtotime($v->kontrak_mulai));
											$v->kontrak_selesai = date('d F Y', strtotime($v->kontrak_selesai));
											if($v->nama_kontrak == 2){
												$v->kontrak_selesai = 'Selamanya';
											}
											switch ($v->nama_kontrak){
												case 1:
													$v->nama_kontrak ='Kontrak';
												break;
												case 2:
													$v->nama_kontrak ='Tetap';
												break;
												default:
													$v->nama_kontrak ='-';
												break;
											}
										}
										
										// opn ($kontrak_karyawan);exit();

							// pendidikan 
								$param['table'] 	= 	'pendidikan';
								$this->db->where('personal_id', $value->personal_id);
								$value->pendidikan_personal = $this->model_generic->_get($param);
									$pendidikan_personal = $value->pendidikan_personal;
									$this->data['pendidikan_personal'] = $pendidikan_personal;
									
								$this->db->order_by("pendidikan_lulus","desc");
								$pendidikan_terakhir = $this->model_generic->_get($param, 1);
								$value->pendidikan_terakhir = $pendidikan_terakhir[0]->pendidikan_level;
								$value->tempat_lulus = $pendidikan_terakhir[0]->pendidikan_nama;
								$value->jurusan_pendidikan = $pendidikan_terakhir[0]->pendidikan_jurusan;
								$value->tahun_lulus = $pendidikan_terakhir[0]->pendidikan_lulus;
									
									// pendidikan_terakhir
										switch ($value->pendidikan_terakhir){
											case 1:
												$value->pendidikan_terakhir ='Tidak Sekolah';
											break;
											case 2:
												$value->pendidikan_terakhir ='SD Sederajat';
											break; 
											case 3:
												$value->pendidikan_terakhir ='SLTP Sederajat';
											break; 
											case 4:
												$value->pendidikan_terakhir ='SLTA Sederajat';
											break;  
											case 5:
												$value->pendidikan_terakhir ='D 1';
											break;  
											case 6:
												$value->pendidikan_terakhir ='D 2';
											break;  
											case 7:
												$value->pendidikan_terakhir ='D 3';
											break;  
											case 8:
												$value->pendidikan_terakhir ='Sarjana';
											break;  
											case 9:
												$value->pendidikan_terakhir ='S2 / Magister';
											break; 
											case 10:
												$value->pendidikan_terakhir ='S3 / Doktor';
											break;  
											default :
												$value->pendidikan_terakhir ='-';
											break;
										} 
										
								// opn ($pendidikan_terakhir);exit();

								
								if($value->foto == null){
									// $value->foto = $karyawan[0]->foto;
									
									switch ($value->jenis_kelamin){
										case 1:
											$value->foto ='blank1.jpg';
										break;
										case 2:
											$value->foto = 'blank2.jpg';
										break; 
										default:
											$value->foto = 'blank.png';
										break; 
									}
								}
							
							// Tanggal Lahir 
								$value->tanggal_lahir = date('d F Y', strtotime($value->tanggal_lahir));
								$value->tanggal_diterima = date('d F Y', strtotime($value->tanggal_diterima));
								
							// jenis kelamin
								switch ($value->jenis_kelamin){
									case 1:
										$value->jenis_kelamin ='Pria';
									break;
									case 2:
										$value->jenis_kelamin ='Wanita';
									break;
									default :
										$value->jenis_kelamin ='-';
									break;
								}
								
							// agama
								switch ($value->agama){
									case 1:
										$value->agama ='Islam';
									break;
									case 2:
										$value->agama ='Kristen';
									break;
									case 3:
										$value->agama ='Katholik';
									break;
									case 4:
										$value->agama ='Hindu';
									break;
									case 5:
										$value->agama ='Budha';
									break;
									default :
										$value->agama ='-';
									break;
								}
								
							// golongan darah
								switch ($value->darah){
									case 1:
										$value->darah ='A';
									break;
									case 2:
										$value->darah ='B';
									break;
									case 3:
										$value->darah ='AB';
									break;
									case 4:
										$value->darah ='O';
									break; 
									default :
										$value->darah ='-';
									break;
								}
								
							// status karyawan
								switch ($value->status_karyawan){
									case 1:
										$value->status_karyawan ='Permanent';
									break;
									case 2:
										$value->status_karyawan ='Kontrak';
									break; 
									default :
										$value->status_karyawan ='-';
									break;
								}
								
							// perusahaan
								switch ($value->perusahaan){
									case 1:
										$value->perusahaan ='PT. SatNetCom';
									break;
									case 2:
										$value->perusahaan ='PT. MegaSatCom';
									break;  
								}
								
							// status status_pajak
								switch ($value->status_pajak){
									case 1:
										$value->status_pajak ='TK';
									break;
									case 2:
										$value->status_pajak ='K0';
									break;  
									case 3:
										$value->status_pajak ='K1';
									break; 
									case 4:
										$value->status_pajak ='K2';
									break;  
									case 5:
										$value->status_pajak ='K3';
									break;  
									case 6:
										$value->status_pajak ='T1';
									break; 
									case 7:
										$value->status_pajak ='T2';
									break;  
									case 8:
										$value->status_pajak ='T3';
									break;  
									default:
										$value->status_pajak ='-';
									break;  
								}
								
							// status menikah
								switch ($value->status_kawin){
									case 1:
										$value->status_kawin ='Belum Menikah';
									break;
									case 2:
										$value->status_kawin ='Menikah';
									break; 
									case 2:
										$value->status_kawin ='Pernah Menikah';
									break; 
									default :
										$value->status_kawin ='-';
									break;
								}
								
								
								if ($value->keterangan == null || ''){
									$value->keterangan = '-';
								}
								

							}
						// opn ($all_karyawan);exit();
						$this->data['detail_karyawan'] = $all_karyawan;
					}else{
						redirect(base);
					}
				
		$this->data['content'] = $this->parser->parse('karyawan/detail.html', $this->data, true);
		$this->parser->parse('layout.html', $this->data, false);
			}else{
				redirect(base);
			}
		// opn($all_karyawan);
	}
}
	
?>