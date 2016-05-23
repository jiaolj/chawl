<?php
/*
* 环信聊天
*/
class bard extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model('bard_model');
	}

	public function set_card()
	{
		$res = $this->bard_model->publish();
		echo json_encode($res);
	}

	public function get_cards()
	{
		$cards = $this->bard_model->get_cards();
		echo json_encode($cards);
	}
}