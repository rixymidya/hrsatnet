<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Main extends MY_Controller {
	
	public function __construct()
    {
        parent::__construct(); 
	}
	
	public function index()
	{
		if ($this->_is_login) {
			redirect (base . '/dashboard'); 
		}else{
			redirect (base . '/login');
		}
	} 
}
?>
