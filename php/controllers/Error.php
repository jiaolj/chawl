<?php
//require('portal/Main.php');
class Error extends CI_Controller{
	
	public function __construct(){		
		parent::__construct();
	}
	public function error_404(){		
		redirect();
		die(0);
	}
}
