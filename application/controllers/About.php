<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class About extends MY_Controller {
	
	
	
	
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
		$this->data['content'] = $this->parser->parse('about.html', $this->data, true);
		$this->parser->parse('layout/_index.html', $this->data, false);
	}
}
