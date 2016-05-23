<?php
/**
 * 公用的一些函数
 */
class Common extends CI_Controller{
	
	public function __construct(){
		parent::__construct();
	}
	//获取验证码
	public function getValidation(){
		$this->load->helper('validation');
		$cap_array = getValidation();
		$cap_array['word'] = strtolower($cap_array['word']);
		if(isset($cap_array['image'])){
			$cap_array['image'] = str_replace(base_url(), SITE_URL, $cap_array['image']);
		}
		$this->session->set_userdata(array('captcha'=>strtolower($cap_array['word']) ));			
		echo json_encode($cap_array);
	}
	//刷新信息
	public function pushPage(){
		$city_id = $this->getCity();
		if( !file_exists( UPLOAD."/wap_push_time_{$city_id}.txt") ){
			//@mkdir( UPLOAD."/web_push_time.txt" );
			$fp = fopen( UPLOAD."/wap_push_time_{$city_id}.txt" , "a" );
			$time = time();
			flock($fp, LOCK_EX) ;
			fwrite($fp,$time );
			flock($fp, LOCK_UN);
			fclose($fp);
		}else{
			$time  = @file_get_contents(  UPLOAD."/wap_push_time_{$city_id}.txt" );
			$time = trim( $time );
			$time = $time ? $time : time();
		}
		$currTime = time();
		//如果当前的时间与上次刷新的时间差 有10分钟  则刷新
		if( $currTime - $time >=  600 ){
			$sortList = $this->db->query("select * from sort where level=1")->result_array();
			foreach ($sortList as $k=>$v){
				//查询当前大类下面的信息
				$smallPageCount = $this->db->query("select * from page where is_vip <> 1 and isagree = 1 and is_refresh = 1 and sort_id like '%,{$v['id']},%' and city_id like '%,{$city_id},%' ")->num_rows();

				//获取要刷新的一条
				$limit = ($smallPageCount>0 ? ($smallPageCount-1) : 0).',1';
				$pushPageRow = $this->db->query(" select id from page where is_vip <> 1 and isagree = 1 and is_refresh = 1 and sort_id like '%,{$v['id']},%' and city_id like '%,{$city_id},%'  order by  istop desc,  top_time desc,hot_time desc,  hot desc, view desc,mtime desc,ctime desc limit {$limit} ")->row_array();
				if( $pushPageRow ){
					$result = $this->db->query("update page set hot = hot+1,hot_time =".$currTime."  where id = {$pushPageRow['id']}");
				}
			}
			//插入本次记录的时间
			$fp = fopen( UPLOAD."/wap_push_time_{$city_id}.txt" , "w+" );
			flock($fp, LOCK_EX) ;
			fwrite($fp,$currTime );
			flock($fp, LOCK_UN);
			fclose($fp);
		}
		echo 'success';
	}
	//获取选取的城市
	function getCity(){
		//获取城市
		$city_id = $this->input->cookie('city_id');
		if( $city_id<= 0 ){
			$city = $this->city_model->cityList("parent_id != 0 and isclose = 0 and level = 2","0,1"," order by is_default desc,ctime desc ");
			if( empty($city) || $city===false ){
				$city_id = 0;
			}else{
				$city_id = $city[0]['id'];
			}
		}
		return $city_id;
	}
}