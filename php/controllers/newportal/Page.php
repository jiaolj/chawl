<?php
/**
* 名片操作 
*/
require 'Main.php';
class Page extends Main
{
	public function __construct()
	{
		parent::__construct();
	}

	public function add_support()
	{
		$res = $this->page_model->add_support();
		echo json_encode($res);
	}

	// 名片留言
	public function leave_message()
	{
		$res = $this->page_model->leave_message();
		echo json_encode($res);
	}

	/**
	 * 首页的信息列表
	 * @param  $city
	 */
	public function index($city_spell = ''){
		if( $city_spell != '' ){ //设置城市
			$this->setCity( $city_spell );
		}
		
		//获取城市
		$city = $this->getCity();	
		$navList =  $this->getBigSortList();
		$bigId = 0;
		if( isset($_REQUEST['sort']) && intval( $_REQUEST['sort'] ) ){
			$bigId = intval( $_REQUEST['sort'] );
		}else{
			$bigId = ($navList && isset($navList[0]['id']) ) ? $navList[0]['id'] : 0;
		}
		//选择类别
		$select_arr = array();
		if($bigId>0){
			
			$bigRow = $this->sort_model->getSortRow("id = {$bigId}"); 
			$select_arr['sort'] = $bigRow ? $bigRow['name'] : '没有类别';
		}
		//查询 二级分类和三级
		$sort_list = $this->sort_model->getSortArr( $bigId,"(city_id ='0' or city_id like '%,{$city['city_id']},%') " );
		//获取全部的类别
		//$sort_list_all = $this->sort_model->getSortArr( 0," city_id like '%,{$city['city_id']},%' " );
		$where = " sort_id like '%{$bigId}%' and isagree = 1 ";
		if($bigRow["city_id"]!="0"){
		// $where .=" and (city_id ='0' or city_id like '%{$city['city_id']}%') ";
		}
		//参数的获取 小分类
		if( !empty($sort_list) ){
			foreach ($sort_list as $k=>$v){
				$select_arr['sid'.$v['id']] = $v['name'];
				if( isset($_GET['sid'.$v['id']]) && $_GET['sid'.$v['id']]>0 ){
					$where .= " and sort_id like '%".$_GET['sid'.$v['id']]."%' ";
					foreach( $v['childs'] as $k1=>$v2){//选中导航
						if( $_GET['sid'.$v['id']] == $v2['id'] ){				
							$select_arr['sid'.$v['id']] = $v2['name'];
						}
					}
				}
			}
		}

		//根据出发地跟目的地做一个筛选
		$starting = isset($_GET['starting']) ? $_GET['starting'] : "";
		$destination = isset($_GET['destination']) ? $_GET['destination'] : "";
		$where .= " and `starting` like '%{$starting}%' and destination like '%{$destination}%' " ;

		//排序
		$order = isset($_GET['order']) ? $_GET['order'] : "";
		$order_str = " order by  is_vip desc, is_refresh desc, istop desc,top_time desc,hot_time desc,hot desc,view desc,mtime desc,ctime desc ";
		$select_arr['order'] = "默认排序";
		switch ($order){
			case 'hot' : $order_str = " order by  view desc,hot_time desc,hot desc,istop desc,top_time desc,is_vip desc,view desc,mtime desc,ctime desc ";$select_arr['order']='人气排序'; break;
			case 'new' : $order_str = " order by mtime desc,ctime desc,istop desc,top_time desc,is_vip desc,hot_time desc,hot desc,view desc ";$select_arr['order']='更新排序'; break;
		}
		$total = $this->db->query("select * from page where {$where}")->num_rows();
		//分页
		$page = 1;
		$count = 20;
		$countPage = ceil($total/$count);
		if( isset($_REQUEST['page']) && intval($_REQUEST['page'])>0 ){
			$page = intval( $_REQUEST['page'] ); 
		}
		$limit = ($page-1)*$count.",".$count;
		$page_arr = array('page'=>$page,'count'=>$count,'countPage'=>$countPage,'total'=>$total);	
		$page_list = $this->page_model->pageList( $where, $limit , $order_str );
		// echo $where;
		// echo json_encode($page_list);
		
		$wheretemp=" sort_id like '%{$bigId}%' and isagree = 1 ";
		if($bigRow["city_id"]!="0"){
		   $wheretemp.=" and city_id like '%{$city['city_id']}%' ";
		}
		$page_list_all = $this->page_model->pageList($wheretemp,"");		
		//循环大类下面的条数
		$sort_id_arr = array();
		foreach($page_list_all as $k=>$v ){	
			$sort_id_arr[] = $v['sort_id'];
		}	
		//检查是否登录
		$user_id = $this->session->userdata('user_id');
		$user_info  = array();
		if( $user_id ){
			$user_info = $this->user_model->getUser('id',$user_id);
		}
		//计算条数
		if( !empty( $sort_list)){
			foreach ($sort_list as $k=>&$v){
				if(isset($v['childs']) && !empty($v['childs']) ){
					foreach($v['childs'] as $tk=>&$tv){
						$tv['count'] = 0;
						foreach($sort_id_arr as $sk=>$sv){
							if(strpos($sv, ",{$tv['id']},") !== false){
								$tv['count']++;
							}
						}
					}
				}
			}
		}
		//组装区域
		$data_page_list = array();
		foreach ($page_list as $k=>&$v){		
			//$v['logo'] = timthumb(base_url()."uploads/page/".$v['logo'],288,173);	
			if($v['logo']){					
						//获得文件扩展名  输出水印图片
				$temp_arr = explode(".", $v['logo'] );
				$file_ext = array_pop($temp_arr);
				$file_ext = trim($file_ext);
				$file_path_pre = str_replace(".".$file_ext, "", $v['logo'] );
				$file_ext = strtolower($file_ext);
				if($bigRow["city_id"]!="0"){
				$v['logo'] = SITE_URL."uploads/page/".$file_path_pre.md5("_water_img").".".$file_ext;
				}else if(file_exists( UPLOAD."page/".$file_path_pre.md5("_thumb_img").".".$file_ext )){
				$v['logo'] = SITE_URL."uploads/page/".$file_path_pre.md5("_thumb_img").".".$file_ext;
			}else{
			$v['logo'] = SITE_URL."uploads/page/".$v['logo'];
			}
			}
				$user = $this->db->query("select * from user where id = {$v['uid']} and isMember = 1")->num_rows();
				$v['isMember'] = $user>0 ? 1 : 0;
				$data_page_list[] = $v;
		}		
		$data = array(
			'user_info'	=> $user_info,
			'city' => $city,
			'page_arr' => $page_arr,
			'navList' => $navList,
			'select_arr' => $select_arr,	
			'sort_list' => $sort_list,
			'page_list' => $data_page_list,
			'bigRow'=>$bigRow,
			//'sort_list_all' => $sort_list_all
		);
		// $this->myView('portal/index.php', $data );
		echo json_encode($data);
	}
}