<?php
require 'Main.php';
class Friend extends Main{
	public function __construct(){
		parent::__construct();
	}
	//查看用户的信息
	public function userinfo( $uid ){
		$user = $this->db->query("select * from user where id = $uid")->num_rows();
		if( $user<=0 ){
			notExists();
			exit(0);
		}
		$list = $this->page_model->pageList(" uid={$uid} ", "" );
		foreach ($list as $k=>&$v){
			$temp_arr = explode(".", $v['logo'] );
			$file_ext = array_pop($temp_arr);
			$file_ext = trim($file_ext);
			$file_path_pre = str_replace(".".$file_ext, "", $v['logo'] );
			$file_ext = strtolower($file_ext);
			$v['logo'] = SITE_URL."uploads/page/".$file_path_pre.md5("_water_img").".".$file_ext;	
			$user = $this->db->query("select * from user where id = {$v['uid']} and isMember = 1")->num_rows();
			$v['isMember'] = $user > 0 ? 1 : 0;
		}
		$data = array(
			'list' => $list
		);
		
		$this->myView("portal/userinfo", $data );
		
	}
}