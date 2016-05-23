<?php
/**
 * 用户的基本操作
 */
require 'Main.php';
class User extends Main
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model('route_model');
		$this->load->model('car_model');
		$this->load->model('goods_model');

		// $user_info = array(
		// 	'nickname' 		=> 	$this->session->userdata('nickname'), 
		// 	'phone'			=>	$this->session->userdata('phone'),
		// 	'isAdmin'		=> 	$this->session->userdata('isAdmin'),
		// 	'isMember'		=> 	$this->session->userdata('isMember'),
		// 	'isRegister'	=> 	$this->session->userdata('isRegister'),
		// 	'headimgurl'	=> 	$this->session->userdata('headimgurl')
		// );

		// log_message('error', json_encode($user_info));

		// $user_id = $this->session->userdata('user_id');
		// $session_id =$this->session->userdata('session_id');

		//log_message('error', '用户用户, user_id: '.$user_id.', session_id: '.$session_id);

	}

	//第一步，获取手机验证码
	public function FetchTelMsg()
	{
		
		$res = $this->user_model->FetchTelMsg();
		echo json_encode($res);
	}

	/*2. 验证验证码*/
	public function CheckCaptcha()
	{
		$user_id = $this->session->userdata('user_id');
		log_message('error', '-check');
		$res = $this->user_model->CheckCaptcha();
		echo json_encode($res);
	}

	//3，输入密码
	public function doRegister()
	{
		$res = $this->user_model->doRegister();
		echo json_encode($res);
	}

	/*  获取车源路线 */
	public function get_car_route()
	{
		$res = $this->route_model->get_car_route();
		echo json_encode($res);
	}

	/*  获取货源路线 */
	public function get_goods_route()
	{
		$res = $this->route_model->get_goods_route();
		echo json_encode($res);
	}

	/* 定制货源路线 */
	public function set_car_route()
	{
		$res = $this->route_model->set_car_route();
		echo json_encode($res);
	}

	/* 定制车源路线 */
	public function set_goods_route()
	{
		$res = $this->route_model->set_goods_route();
		echo json_encode($res);
	}

	/*更新车源路线*/
	public function update_car_route()
	{
		$res = $this->route_model->update_car_route();
		echo json_encode($res);
	}

	/*更新货源路线*/
	public function update_goods_route()
	{
		$res = $this->route_model->update_goods_route();
		echo json_encode($res);
	}


	/*我的名片*/
	public function my_card()
	{
		$user_id = $this->session->userdata('user_id');
		//log_message('error', 'user_id: '.$user_id);
		if (empty($user_id)) {
			echo json_encode(array('info' => '未登录'));
			exit(0);
		}

		$user_info = array(
			'user_id'		=>  $this->session->userdata('user_id'),
			'nickname' 		=> 	$this->session->userdata('nickname'), 
			'phone'			=>	$this->session->userdata('phone'),
			'isAdmin'		=> 	$this->session->userdata('isAdmin'),
			'isMember'		=> 	$this->session->userdata('isMember'),
			'isRegister'	=> 	$this->session->userdata('isRegister'),
			'headimgurl'	=> 	$this->session->userdata('headimgurl')
		);
		$user_info['pages'] = $this->db->query("SELECT id FROM page WHERE uid = {$user_id} ORDER BY ctime DESC")->result_array();

		
		echo json_encode($user_info);
	}

	/*我的车源*/
	public function my_car()
	{
		$res = $this->car_model->my_car();
		echo json_encode($res);
	}

	/*我的货源*/
	public function my_goods()
	{
		$res = $this->goods_model->my_goods();
		echo json_encode($res);
	}

	public function delete_goods_route()
	{
		$res = $this->route_model->delete_goods_route();
		echo json_encode($res);
	}

	public function delete_car_route()
	{
		$res = $this->route_model->delete_car_route();
		echo json_encode($res);
	}

	public function delete_goods()
	{
		$res = $this->goods_model->delete_goods();
		echo json_encode($res);
	}

	public function delete_car()
	{
		$res = $this->car_model->delete_car();
		echo json_encode($res);
	}


}	







