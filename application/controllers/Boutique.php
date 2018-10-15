<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Boutique extends MY_Controller {
	public function __construct()
    {
        parent::__construct();
        $this->data['header']      = $this->parser->parse('layout/_header.html', $this->data, true);
        $this->data['footer']      = $this->parser->parse('layout/_footer.html', $this->data, true);
        $this->data['body']        = $this->parser->parse('layout/_content.html', $this->data, true);
	}
	
	public function index()
	{
		$this->data['content'] = $this->parser->parse('boutique.html', $this->data, true);
		$this->parser->parse('layout/_index.html', $this->data, false);
	}
	public function _index()
	{
		$this->data['nilai'] = '-';
		$this->data['word'] = '-';
		$this->data['hasil'] = '-';
		if ($this->input->post()) {
			$karakter = $this->input->post('karakter');
			$word = $this->input->post('word');
			$this->data['nilai'] = $karakter;
			$this->data['word'] = $word;
			if (isset($karakter)) {
				$char = substr_count($word, $karakter);
				$this->data['hasil'] = "Ditemukan karakter '".$karakter."' sebanyak : " .$char;
			}
		}
		
		$this->data['content'] = $this->parser->parse('soal2.html', $this->data, true);
		$this->parser->parse('layout/_index.html', $this->data, false);
	}

}
