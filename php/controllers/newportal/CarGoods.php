<?php
/**
* 
*/
class CarGoods extends CI_Controller
{
	
	public function __construct()
	{
		parent::__construct();
        $this->load->model('car_model');
        $this->load->model('goods_model');
        $this->load->helper(array('form', 'url','url_helper', 'file'));
	}

	public function index()
	{
		$this->load->view('cargoods', $data);
	}

	/*车源列表*/
	public function ListCar()
	{
		$res = $this->car_model->get_car();
		echo json_encode($res);
	}

	/*货源列表*/
	public function ListGoods()
	{
		$res = $this->goods_model->get_goods();
		echo json_encode($res);
	}

	/*发布车源*/
	public function PublishCar()
	{
		//TODO...暂时不检查是否注册
		$res = $this->car_model->set_car();
		echo json_encode($res);
	}

	/*发布货源*/
	public function PublishGoods()
	{
		//TODO...暂时不检查是否注册
		$res = $this->goods_model->set_goods();
		echo json_encode($res);
	}

	public function Statistic()
	{
		$car_num 	= $this->db->count_all('car');
		$goods_num	= $this->db->count_all('goods');
		$res = array(
			'car_num' 	=> $car_num,
			'goods_num' => $goods_num,
		);
		echo json_encode($res);
	}

	public function detail_car()
	{
		$res = $this->car_model->detail_car();
		echo json_encode($res);
	}

	public function detail_goods()
	{
		$res = $this->goods_model->detail_goods();
		echo json_encode($res);
	}
}




