<?php
/**
 * 错误页面
 */
require 'Main.php';
class Error extends Main{
	public function __construct(){
		parent::__construct();
	}
	public function index(){		
	
		$this->myView("portal/error");
	}
	
}