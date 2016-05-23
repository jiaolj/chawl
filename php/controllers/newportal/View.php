<?php 
class View extends CI_Controller{

	public function __construct()
	{
		parent::__construct();
	}

	public function ViewShuoshuoPost()
	{
		$this->load->view('portal2/shuoshuo_post');
	}

	public function ViewIndex()
	{
		$this->load->view('portal2/index');
	}
	
}