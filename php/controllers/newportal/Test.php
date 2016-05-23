<?php
/*
* 环信聊天
*/
class test extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
	}

	public function run()
	{
		$pages = $this->db->query("select * from page where `starting` = '' and destination = ''")->result();
		foreach ($pages as &$page) {
			$id = $page->id;
			$title = $page->title;
			$arr = explode('至', $title);
			if(count($arr) >= 2)
			{

				$this->db->where('id', $id);
				$this->db->set('starting', $arr[0]);
				$this->db->set('destination', $arr[1]);
				$this->db->update('page');
			}

			echo $arr[0];
			echo $arr[1];

		}

	}

	public function test()
	{
		echo "print('hello world')";
	}

}