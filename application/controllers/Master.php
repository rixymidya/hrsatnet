<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Master extends MY_Controller {
	
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
		redirect(base);
	}
	
	public function department()
	{  
		$param['table']		 = 		'department';
		$all_department 	 = 		$this->model_generic->_get($param);
		$this->data['all_department'] 	= 	$all_department;
		// opn($all_department);exit();
		
		$this->data['function']		= __function__;
		$this->data['content'] = $this->parser->parse('master/department.html', $this->data, true);
		$this->parser->parse('layout.html', $this->data, false);
	}
	
	public function jabatan()
	{  
		$param['table']		 = 		'jabatan';
		$all_jabatan 	 = 		$this->model_generic->_get($param);
		$this->data['all_jabatan'] 	= 	$all_jabatan;
		// opn($all_jabatan);exit();
		
		$this->data['function']		= __function__;
		$this->data['content'] = $this->parser->parse('master/jabatan.html', $this->data, true);
		$this->parser->parse('layout.html', $this->data, false);
	}
	
	
}
