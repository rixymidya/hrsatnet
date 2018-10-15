<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Absensi extends MY_Controller {
	
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
	$asd='as';
	// opn($asd);exit();
	$this->data['content'] = $this->parser->parse('absensi/absensi.html', $this->data, true);
	$this->parser->parse('layout.html', $this->data, false);
	}
	
	public function karyawan()
	{	
		if($this->input->post()){
			$param 		= 	$this->input->post(null, true);
			$absensi_awal= 	substr($this->input->post('periode'), 0, 10);
			$absensi_akhir	=	substr($this->input->post('periode'), -10);
			$param['table'] 	= 	'karyawan';
			$this->db->join('personal','personal.personal_id = karyawan.personal_id');
			$this->db->join('absensi','absensi.no_absen = karyawan.no_absen');
			$this->db->where('absensi_tanggal BETWEEN "'. date('Y-m-d', strtotime($absensi_awal)). '" and "'. date('Y-m-d', strtotime($absensi_akhir)).'"');
			$this->db->where('karyawan_id',$param['karyawan_id']);
			$this->db->where('department_id',$param['department_id']);
			$periode_absensi = $this->model_generic->_get($param);
			// $ini = $this->db->last_query($periode_absensi);
			// opn($ini);exit();
				foreach($periode_absensi as $key => $value){
					$telat = new DateTime('08:01:00');
					$jam1 = new DateTime($value->jam_masuk);
					$jam2 = new DateTime($value->jam_keluar);
					$lama_kerja = $jam1->diff($jam2);
					$value->lama_kerja = $lama_kerja->h.':'.$lama_kerja->i.':'.$lama_kerja->s;
					$value->mulai_lembur = '-';
					$value->selesai_lembur = '-';
					$cek_hari = date('D', strtotime($value->absensi_tanggal));
					$value->absensi_tanggal = date('d F Y', strtotime($value->absensi_tanggal));
						if(($cek_hari == 'Sat')||($cek_hari == 'Sun')){
							$value->keterangan = 'Hari Libur';
							$value->absensi = 'Libur';
							$value->bg_color = 'darksalmon';
							$value->color = 'cornsilk';
						}else{
							$value->keterangan = '-';
							if($lama_kerja->h == 0){
								$value->keterangan = 'Tanpa Keterangan';
								$value->absensi = 'A';
								$value->bg_color = 'red';
								$value->color = 'cornsilk';
								
							}
							elseif($jam1 >= $telat){
								$value->absensi = 'HT';
							}else{
								$value->absensi = 'H';
							}	
						}
					
						
				}
				$param['table'] = 'absensi';
				$this->db->where('jam_masuk >= ', '08:01:00');
				$itung_telat = $this->model_generic->_count($param);
				$this->data['telat'] = $itung_telat .' X';
				
				$this->db->where('jam_keluar >= ', '12:00:00');
				$this->db->where('jam_keluar <= ', '17:00:00');
				$kecepetan = $this->model_generic->_count($param);
				$this->data['kecepetan'] = $kecepetan .' X';
				// $itung = $this->model_generic->_count($param);
				$this->data['periode_absensi'] = $periode_absensi;
				// opn($itung_telat);exit();
					
				$this->data['absen_perkaryawan'] = 'block';
			}else{
				$this->data['kecepetan'] = '-';
				$this->data['periode_absensi'] = array();
				$this->data['telat'] = '-';
				$this->data['absen_perkaryawan'] = 'none';
				
			}
	$param['table'] 	= 	'department';  
	$all_department = $this->model_generic->_get($param);
	$this->data['all_department'] = $all_department;
	
	$param['table'] 	= 	'karyawan';
	$this->db->join('personal','personal.personal_id = karyawan.personal_id');
	$all_karyawan = $this->model_generic->_get($param);
	$this->data['all_karyawan'] = $all_karyawan;
	// opn($all_karyawan);exit();
	
	$this->data['function'] = __function__;
	$this->data['content'] = $this->parser->parse('absensi/perkaryawan.html', $this->data, true);
	$this->parser->parse('layout.html', $this->data, false);
	}
	
	public function department()
	{	
		if($this->input->post()){
			$param 		= 	$this->input->post(null, true);
			$absensi_awal= 	substr($this->input->post('periode'), 0, 10);
			$absensi_akhir	=	substr($this->input->post('periode'), -10);
			
			$param['table'] 	= 	'karyawan';
			$this->db->join('personal','personal.personal_id = karyawan.personal_id');
			$this->db->where('department_id',$param['department_id']);
			$perdepartment = $this->model_generic->_get($param);
			$this->data['perdepartment'] = $perdepartment;
				foreach($perdepartment as $key => $value){
					$param['table'] = 'absensi';
					$this->db->where('no_absen', $value->no_absen);
					$this->db->where('absensi_tanggal BETWEEN "'. date('Y-m-d', strtotime($absensi_awal)). '" and "'. date('Y-m-d', strtotime($absensi_akhir)).'"');
					
					$value->all_absensi = $this->model_generic->_get($param);
					$all_absensi = 	$value->all_absensi;
						foreach($all_absensi as $k => $val){
							$telat = new DateTime('08:01:00');
							$jam1 = new DateTime($val->jam_masuk);
							$jam2 = new DateTime($val->jam_keluar);
							$lama_kerja = $jam1->diff($jam2);
							$value->lama_kerja = $lama_kerja->h.':'.$lama_kerja->i.':'.$lama_kerja->s;
							$val->absensi_tanggal = date('d F Y', strtotime($val->absensi_tanggal));
							$cek_hari = date('D', strtotime($val->absensi_tanggal));
							
							if(($cek_hari == 'Sat')||($cek_hari == 'Sun')){
								$val->absensi = '-';
								$val->bg_color = 'darksalmon';
								$val->color = '#eee';
							}else{
								// $val->keterangan = '-';
								if($lama_kerja->h == 0 || null){
									$val->keterangan = 'Tanpa Keterangan';
									$val->absensi = 'A';
									$val->bg_color = 'red';
									$val->color = 'cornsilk';
									
								}
								elseif($jam1 >= $telat){
									$val->absensi = 'HT';
								}else{
									$val->absensi = 'H';
								}	
							}
							
							$val->tanggalan = 	substr($val->absensi_tanggal, 0, 2);
						// opn($val);exit();
						}
				}
				$this->data['absen_department'] = 'block';
			}else{						
				$all_absensi = 	array();
				$this->data['perdepartment'] = array();
				$this->data['absen_department'] = 'none';
			}
		
	$this->data['all_absensi'] = $all_absensi;
			// opn($value);exit();
	$param['table'] 	= 	'department';  
	$all_department = $this->model_generic->_get($param);
	$this->data['all_department'] = $all_department;
	
	$param['table'] 	= 	'karyawan';
	$this->db->join('personal','personal.personal_id = karyawan.personal_id');
	$all_karyawan = $this->model_generic->_get($param);
	$this->data['all_karyawan'] = $all_karyawan;
	// opn($all_karyawan);exit();
	
	$this->data['function'] = __function__;
	$this->data['content'] = $this->parser->parse('absensi/perdepartment.html', $this->data, true);
	$this->parser->parse('layout.html', $this->data, false);
	}
	
	
	public function upload()
	{	
		if($this->input->post()){
				$path = 'aset/';
            require_once APPPATH . "/third_party/PHPExcel.php";
            $config['upload_path'] = $path;
            $config['allowed_types'] = 'xlsx|xls|csv';
            $config['remove_spaces'] = TRUE;
            $this->load->library('upload', $config);
            $this->upload->initialize($config);            
            if (!$this->upload->do_upload('absensi')) {
                $error = array('error' => $this->upload->display_errors());
				opn($error);exit();
            } else {
                $data = array('upload_data' => $this->upload->data());
				// opn($data);exit();
            }
            if(empty($error)){
              if (!empty($data['upload_data']['file_name'])) {
                $import_xls_file = $data['upload_data']['file_name'];
            } else {
                $import_xls_file = 0;
            }
            $inputFileName = $path . $import_xls_file;
            
            try {
                $inputFileType = PHPExcel_IOFactory::identify($inputFileName);
                $objReader = PHPExcel_IOFactory::createReader($inputFileType);
                $objPHPExcel = $objReader->load($inputFileName);
                $allDataInSheet = $objPHPExcel->getActiveSheet()->toArray(null, true, true, true);
                $flag = true;
                $i=0;
                foreach ($allDataInSheet as $value) {
                  if($flag){
                    $flag =false;
                    continue;
                  }
				  $param['table'] = 'absensi';
				  $this->db->order_by('absensi_id', 'desc');
				  $this->db->limit(1); 
				  $id_akhir = $this->model_generic->_get($param);
				  
				// $inserdata['table'] = 'absensi';
                  // $inserdata[$i]['absensi_id'] = $id_akhir[0]->absensi_id + 1;
                  $inserdata[$i]['no_absen'] = $value['A'];
                  $inserdata[$i]['absensi_tanggal'] = $value['B'];
                  $inserdata[$i]['jam_masuk'] = $value['C'];
                  $inserdata[$i]['jam_keluar'] = $value['D'];
                  $i++;
                }   
                $result = $this->model_generic->importData($inserdata); 
			// opn($result);exit();
			redirect(base.'/absensi/upload');
				
                if($result){
                  echo "Imported successfully";
                }else{
                  echo "ERROR !";
                }             
 
          } catch (Exception $e) {
               die('Error loading file "' . pathinfo($inputFileName, PATHINFO_BASENAME)
                        . '": ' .$e->getMessage());
            }
          }else{
              echo $error['error'];
            }
			
			
		}
		
		
	$this->data['function'] = __function__;
	$this->data['content'] = $this->parser->parse('absensi/upload_absen.html', $this->data, true);
	$this->parser->parse('layout.html', $this->data, false);
	
	}
}
