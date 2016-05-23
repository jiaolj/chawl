<?php
require 'jssdk.php';
class Main extends CI_Controller{
	
	public static $isPassCityValue = '';
	public Static $city_id = 0;
	public Static $city_name = '';
	public Static $city_spell = ''; 
	function __construct(){
		parent::__construct();
	}
	
	function myView($viewer, array $data= array()){
		$viewer = $this->load->view($viewer,$data,true);
		//关键字、描述、标题	
		$systemList = $this->system_model->getSystemConfig( "'title','description','words','author','losetime'" );					
		$meta = array();
		if( empty($systemList) ){
			$meta['title'] = '物流网|货运公司|专线托运部|信息部大全|查快递';
			$meta['description'] = '物流网|货运公司|专线托运部|信息部大全|查快递';
			$meta['words'] = '物流网|货运公司|专线托运部|信息部大全|查快递';
			$meta['author'] = 'www.87756.com,www.gezitech.com';
		}else{
			foreach ($systemList as $row){
				$meta[$row->name] = $row->value;
			}
		}
		//获取默认的城市
		$city = $this->getCity();
		$city_id = $city['city_id'];
		$city_name = $city['city_name'];
		$city_spell = $city['city_spell'];
		
		
		//配置分站的title
		//$meta['title'] = "【{$city_name}物流网】{$city_name}物流、{$city_name}物流公司、{$city_name}物流专线、{$city_name}货运公司-查物流{$city_name}站";							
		//$meta['title'] = "{$city_name}物流网|{$city_name}货运公司 |{$city_name}专线托运部|信息部大全|-查物流{$city_name}站";
		$meta['title'] = "【查物流】{$city_name}物流网,{$city_name}物流,{$city_name}物流公司,{$city_name}物流专线！";			
		//网站title配置  详情
		if(isset($data['meta']) && !empty($data['meta']) && isset( $data['meta']['sign'] ) && $data['meta']['sign']=='detail' ){			
		//	$meta['title'] = $data['meta']['title']."-查物流{$city_name}站";
			$meta['title'] = $data['meta']['title']."-查物流推荐";
			$meta['words'] =$city_name."托运,专线物流查询,".$city_name."物流,微信查询".$city_name."物流,".$city_name."货运,".$city_name."物流网,".$city_name."物流公司,".$city_name."托运部,".$city_name."发货,永康物流,东阳物流,浦江物流,货运专线,物流信息平台";
		
		}	
		
		
		//友情链接
		//$friendList = $this->friend_model->friendList("is_verify=1");
		
		
		//获取热门城市
		//$hot_city_list_main = $this->city_model->cityList(" parent_id !=0 and isclose = 0 and ishot = 1 ",""," order  by initial asc ");
		$data_view = array(
			'main_body' => $viewer,
			'meta'	=> $meta,
			'city_id' => $city_id,
			//	'friendList' => $friendList,
			//'hot_city_list_main' => $hot_city_list_main,
			//'serviceList' => $this->getServiceList()
		);
		$jssdk = new JSSDK("wxbbfe68842d68246f", "6faa70a3f6c4cfdc95061f1d0898e908");
        $signPackage = $jssdk->GetSignPackage();
		$data_view["signPackage"]=$signPackage;
		$this->load->view("portal/layout", $data_view );
	
	}//获取大类
	function getBigSortList(){
		$city = $this->getCity();
		$navList = $this->sort_model->getSort(1,0,"(city_id ='0' or city_id like '%,{$city['city_id']},%' )");
		return $navList;
	}
	//获取选取的城市
	function getCity(){
		//获取城市
		$city_id = (Main::$city_id > 0) ?  Main::$city_id : $this->input->cookie('city_id');
		$city_name = (Main::$city_name != "") ?  Main::$city_name : $this->input->cookie('city_name');
		$city_spell = (Main::$city_spell != "") ?  Main::$city_spell : $this->input->cookie('city_spell');
		if( empty($city_id) || $city_id===false ){
			$city = $this->setCity();
			$city_id = $city['id'];
			$city_name = $city['name'];
			$city_spell = $city['spell'];
		}
	
		return array('city_id'=>$city_id,'city_name'=>$city_name,'city_spell'=>$city_spell );
	}
	//设置城市
	function setCity( $id = 0 ){
		if( is_numeric( $id ) ){//如果是数字
			if( $id>0 ) $id = " and id = $id ";
			else $id = '';
		}else{//城市拼音
			if( !empty($id) ) $id = " and spell = '{$id}' ";
			else $id = '';
		}
		
		$city = $this->city_model->cityList("parent_id != 0 and isclose = 0 and level = 2  $id ","0,1"," order by is_default desc,ctime desc ");
		if( empty($city) || $city===false ){
			$city['id'] = 0;
			$city['name'] = '没有城市';
			$city['spell'] = '';
		}else{
			$city = $city[0];
			$this->input->set_cookie('city_id',$city['id'],1*365*24*3600 );
			$this->input->set_cookie('city_name',$city['name'],1*365*24*3600 );
			$this->input->set_cookie('city_spell',$city['spell'],1*365*24*3600 );
			$view_city = $this->input->cookie('view_city');//获取历史记录
			$view_city = explode(',', $view_city);
			foreach ($view_city as $k=>$v){
				if($v==$city['id'] || empty($v) ) unset($view_city[$k]);
			}
			array_unshift($view_city,$city['id']);
			$view_city = join(',', $view_city);
			$this->input->set_cookie('view_city',$view_city,1*365*24*3600 );
		}
		Main::$city_id = $city['id'];
		Main::$city_name = $city['name'];
		Main::$city_spell = $city['spell'];
		return $city;
	}
	//获取省
	function province(){
		$city = $this->getCity();
		//查询城市
		$city_row = $this->db->query("select * from city where id = {$city['city_id']}")->row_array();
		
		//查询省
		if( $city_row ){
			$province = $this->city_model->cityList("id = {$city_row['parent_id']}","0,1");	
		}else{ 
			$province = array();
		}
		if($province===false || empty($province) ){
			$province['id'] = 0;
			$province['parent_id'] = 0;
			$province['name'] = '没有城市';
		}else{
			$province = $province[0];
		}
		return $province;
	}
	//设置访问记录的id
	function setViewLog( $id ){
		if( intval($id)<=0 ) return false;
		$viewLog = $this->input->cookie('viewLog');//获取历史记录
		$viewLog = explode(',', $viewLog);
		foreach ($viewLog as $k=>$v){
			if($v==$id || empty($v) ) unset($viewLog[$k]);
		}
		array_unshift( $viewLog,$id );
		$viewLog = join(',', $viewLog);
		$this->input->set_cookie('viewLog',$viewLog,1*365*24*3600 );
	}
	//获取访问记录的信息的记录信息
	function getViewLogList(){
		$viewLog = $this->input->cookie('viewLog');//获取历史记录
		$viewLog = trim($viewLog,',');
		if( empty($viewLog) ){
			return array();
		}
		$viewLog = explode(',', $viewLog);
		$viewLog = array_filter( $viewLog );
		$viewLog = array_slice($viewLog,0,5);
		$where = " id in (".join(',',$viewLog).") and isagree = 1 ";
		$viewLogList_temp = $this->page_model->pageList( $where );
		$viewLogList = array();
		foreach ($viewLog as $v2){ //排序
			foreach ($viewLogList_temp as $v ){
				if( $v['id'] == $v2 ){
					$viewLogList[] = $v;
				}
				
			}
		}
		foreach( $viewLogList as &$v3 ){//图片网络地址
			$v3['logo'] = timthumb(base_url()."uploads/page/".$v3['logo'],70,42);			
		}
		return $viewLogList;
	}
	//清空浏览记录
	function deleteViewLogList(){
				
		$this->input->set_cookie('viewLog',"");
		return true;
	}
	//获取咨询客服的信息
	function getServiceList(){
		$serviceList = $this->db->query("select * from qqinfo order by ctime")->result_array();
		return $serviceList;
	}
}