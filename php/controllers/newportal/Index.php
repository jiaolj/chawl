<?php 
require 'Main.php';
class Index extends Main{

	public function __construct(){
		parent::__construct();
	}

	public function index($city_spell = '')
	{
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
		$where .=" and (city_id ='0' or city_id like '%{$city['city_id']}%') ";
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
			$v['content'] = '';//暂时去掉content
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
		//$this->myView('portal/index.php', $data );
		echo json_encode( $data );
	} 
  	

  	//搜索信息
	function search($is_fliter = '',$key=''){
		$searchName = isset($_REQUEST['kw']) ? trim($_REQUEST['kw']) :'';
		$where = ' 1 = 1';
		if( $is_fliter == 1 ){
			$citys = $this->getCity();
			$city = $citys['city_id']>0 ? "(city_id ='0' or city_id like '%{$citys['city_id']}%') " : '';
			$where = ($city? $city." and " : '' )." ( title like '%{$searchName}%' or phone like '%{$searchName}%' or address like '%{$searchName}%') and isagree = 1  ";
		}else if( $is_fliter == 2 ){
			$searchName = $key =  urldecode(base64_decode( $key ));	
				
			$where = " new_function('{$key}',p.words,' ')=1 and p.isagree = 1 ";
			
		}
		$total = $this->db->query("select p.* from page as p where {$where}")->num_rows();
		//分页
		$page = 1;
		$count =20;
		$countPage = ceil($total/$count);
		if( isset($_REQUEST['page']) && intval($_REQUEST['page'])>0 ){
			$page = intval( $_REQUEST['page'] ); 
		}
		$limit = ($page-1)*$count.",".$count;
		$page_arr = array('page'=>$page,'count'=>$count,'countPage'=>$countPage,'total'=>$total);	
		
		if( $is_fliter == 1){
			$page_arr['pre'] = trim(site_url('search')).($is_fliter?'/1':'')."?kw={$searchName}&page=".($page-1);
			$page_arr['next'] =  trim(site_url('search')).($is_fliter?'/1':'')."?kw={$searchName}&page=".($page+1);
		}else{
			
			$page_arr['pre'] = trim(site_url('search')).($is_fliter?'/2':'')."/".base64_encode(urlencode( $searchName ))."?page=".($page-1);
			$page_arr['next'] =  trim(site_url('search')).($is_fliter?'/2':'')."/".base64_encode(urlencode( $searchName ))."?page=".($page+1);
	
		}
		$pageList = $this->page_model->wxPageList( $where ,$limit, " order by p.istop desc,p.top_time desc,p.is_vip desc,p.hot_time desc,p.hot desc,p.view desc,p.mtime desc,p.ctime desc ");
		 
		foreach ($pageList as $k=>&$v){
			//获得文件扩展名  输出水印图片
				if($v['logo']){
			$temp_arr = explode(".", $v['logo'] );
			$file_ext = array_pop($temp_arr);
			$file_ext = trim($file_ext);
			$file_path_pre = str_replace(".".$file_ext, "", $v['logo'] );
			$file_ext = strtolower($file_ext);
			$v['logo'] = SITE_URL."uploads/page/".$file_path_pre.md5("_water_img").".".$file_ext;
			}
			$user = $this->db->query("select * from user where id = {$v['uid']} and isMember = 1")->num_rows();
			$v['isMember'] = $user>0 ? 1 : 0;
		}
		$data['page_list'] = $pageList;
		$data['page_arr'] = $page_arr;
		$data['searchName'] = $searchName;
		$data['is_fliter'] = $is_fliter;
		echo json_encode($data);
	}



	//信息的详情
	public function detail($id,$showshare=0){
		$page = $this->page_model->page("id = $id");
		if(!$page ){
			notExists();
			exit(0);	
		}
		
		
		$page['content'] = stripslashes($page['content']); //去掉转义符号
		$citys = $this->getCity();
		//浏览次数加1
		$this->db->query("update page set view = view+1 where id = {$id}");
		$page['keep'] = $this->db->query("select * from keep where page_id = $id")->num_rows();
		//检查是否可以推荐此信息
		$isHot = $this->db->query("select * from user where id = {$page['uid']} and isMember = 1")->num_rows();
		
		//是否收藏
		$isKeep = 0;
		//是否递交
		$isCard = 0;
		$isCardMsg = "不能递交";
		$user_id = $this->session->userdata('user_id');
		if( $user_id>0 ){
			$isKeep = $this->db->query("select * from keep where page_id = {$id} and uid = {$user_id}")->num_rows();
			$isCard = $this->db->query("select * from card where touid = {$page['uid']} and uid = {$user_id}")->num_rows();
			if( $isCard>0 ){ $isCardMsg="已递交";}
			$isMember = $this->session->userdata('isMember');//是会员			
			$isCard = ($page['uid'] != $user_id ) && ($isMember>0) && ($isCard<=0) ? 1 : 0;
		}
		//网站分享的摘要
		$shareSummary = "查物流{$citys['city_name']}站";
		//消息作者是否是会员
		$user = $this->db->query("select * from user where id = {$page['uid']} and isMember = 1")->num_rows();
		$page['isMember'] = $user>0 ? 1 : 0;
		//为详细信息添加图片的完整的url
		$page['content'] = addImageHost( $page['content'] );
		
		$page['content']  = addPhoneALink( $page['content']) ; 
		
		$sort_str = explode(',', trim($page['sort_id'],',') );
		$sort_str_where = $mod =  '';
		$big_sort_name = "专线";
		foreach ($sort_str as $k=>$sort){
			
			$sort_str_where .= $mod." sort_id like '%{$sort}%' ";
			$mod = " or ";
			//父类的类别id
			if( $k == 0 ){ 
				$big_sort_row = $this->db->query("select * from sort where id = {$sort}")->row_array();
				if( !empty($big_sort_row) ) $big_sort_name = $big_sort_row['name'];
				break;
			}
		}
		if($page['logo']){
		//获得文件扩展名  输出水印图片
		$temp_arr = explode(".", $page['logo'] );
		$file_ext = array_pop($temp_arr);
		$file_ext = trim($file_ext);
		$file_path_pre = str_replace(".".$file_ext, "", $page['logo'] );
		$file_ext = strtolower($file_ext);
		if($big_sort_row["city_id"]!="0"){
		//判断水印图片是否存在
		if( file_exists( UPLOAD."page/".$file_path_pre.md5("_water_img").".".$file_ext ) ){
			$page['logo'] = SITE_URL."uploads/page/".$file_path_pre.md5("_water_img").".".$file_ext; //timthumb(base_url()."uploads/page/".$page['logo']."_water.".$file_ext ,440,280, 2);
		}else if(file_exists( UPLOAD."page/".$file_path_pre.md5("_thumb_img").".".$file_ext )){
			$page['logo'] = SITE_URL."uploads/page/".$file_path_pre.md5("_thumb_img").".".$file_ext;
		}else{
		$page['logo'] = SITE_URL."uploads/page/".$page['logo'];
		}
		}else{
		if(file_exists( UPLOAD."page/".$file_path_pre.md5("_thumb_img").".".$file_ext )){
			$page['logo'] = SITE_URL."uploads/page/".$file_path_pre.md5("_thumb_img").".".$file_ext;
		}else{
		$page['logo'] = SITE_URL."uploads/page/".$page['logo'];
		}
		}
		}
	
		$systemList = $this->system_model->getSystemConfig( "'tip1','tip2','tip3','tip4'" );
		foreach ($systemList as $row){
				$meta[$row->name] = $row->value;
		}

		// 查询名片评论信息
		$message_count = $this->db->query("SELECT * FROM page_message WHERE page_id = {$id}")->num_rows();
		$messages 	   = $this->db->query("SELECT * FROM page_message WHERE page_id = {$id}")->result_array(); 

		foreach ($messages as &$msg) {
			$user_id = $msg['user_id'];
			$user_info = $this->db->query("SELECT nickname, headimgurl FROM user WHERE id = {$user_id}")->result_array();
			//echo $this->db->last_query();
			if (isset($user_info)) {
				//echo json_encode($user_info);
				$msg['user_info'] = $user_info;
			}
		}

		// support
		$user_id = $this->session->userdata('user_id');

		if (empty($user_id)) {
			$user_id = 0;
		}

		$isSupport = 0;
		$su = $this->db->query("SELECT * FROM page_support WHERE page_id = {$id} AND user_id = {$user_id}")->row_array();
		if(!empty($su))
			$isSupport = 1;

		$data = array(
			'page' => $page,
			'meta'	=> array('title'=>$page['title'],'sign'=>'detail'),
			'isHot' => $isHot,
			'isKeep' => $isKeep,
			'isCard' => $isCard,
			'shareSummary' => $shareSummary,
			'isCardMsg' => $isCardMsg,
			'cityName' => $citys['city_name'],
			'showshare' =>$showshare,
			'tip1' =>$meta["tip1"],
			'tip2' =>$meta["tip2"],
			'tip3' =>$meta["tip3"],
			'tip4' =>$meta["tip4"],
			'bigrow'=>$big_sort_row,
			'message_count' => $message_count,
			'messages'		=> $messages,
			'isSupport'		=> $isSupport,
		);
		echo json_encode($data);
	}
}