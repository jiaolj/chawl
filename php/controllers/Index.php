<?php 
require 'Main.php';
class Index extends Main{
	public function __construct(){
		parent::__construct();
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
		$this->myView('portal/index.php', $data );
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
		$this->myView('portal/search', $data);
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
		);
	
		$this->myView('portal/detail.php', $data );
	}
	//城市切换
	public function switch_city(){
		$city_list = $this->city_model->cityList(" parent_id=0 and isclose= 0 ","","order by ctime asc");
		$city_list_new = array();
		foreach ($city_list as $k=>$v){
			$two_city_list = $this->city_model->cityList(" parent_id= {$v['id']} and isclose = 0 and level = 2","","order by ctime asc");
			$v['two_city'] = array();
			if( !empty( $two_city_list ) ){
				$v['two_city'] = $two_city_list;
			}
			$city_list_new[] = $v;
		}
		//获取热门城市
		$hot_city_list = $this->city_model->cityList(" parent_id !=0 and isclose = 0 and ishot = 1 ",""," order  by ctime asc ");
		
		$data = array(
			'city_list_new'	=> $city_list_new,
			'hot_city_list' => $hot_city_list
		);
		
		$this->myView('portal/switch_city',$data);
		
	}
	//查快递
	function express(){
		$this->myView('portal/express');
	}
	//收藏
	function  keep(){
		$res = array('info' =>'收藏失败','status'=>'n');
		$id = isset($_REQUEST['id']) ? intval($_REQUEST['id']) : 0;
		$user_id = $this->session->userdata('user_id');
		if( $user_id<= 0 || $id <= 0){
			$res['info'] = '对不起，没有登录不能收藏名片！';
			echo json_encode( $res );
			exit();
		}
		$isExists = $this->db->query("select * from keep where uid = $user_id and page_id = $id")->num_rows();
		if( $isExists> 0 ){
			$res['info'] = '你已经收藏过此名片';
			echo json_encode( $res );
			exit();
		}
		$page = $this->page_model->page( "id=".$id );
		if($page['uid']== $user_id){
			$res['info'] = '不能收藏自己的信息';
			echo json_encode( $res );
			exit();
		}
		$data['uid'] = $user_id;
		$data['page_id'] = $id;
		$data['ctime'] = time();
		$result = $this->db->insert('keep',$data);
		
		if( $result ){
			$res['info']  = '收藏成功，谢谢！';
			$res['status'] = 'y';
			echo json_encode($res);
			exit();
		}else{
			echo json_encode( $res );
			exit(); 
		}
		
	}
	//立即推荐
	function hot(){
		$id = isset($_REQUEST['id']) ? intval( $_REQUEST['id']) : 0;
		if( $id<= 0){
			echo json_encode( array('info'=>'参数错误','status'=>'n') );
			exit(); 
		}
		$page = $this->page_model->page("id = $id");
		if( empty( $page ) ){
			echo json_encode( array('info'=>'推荐失败,没有该信息存在','status'=>'n') );
			exit(); 
		}
		//检查是否是可以刷新的 如果是刷新的才可以推荐  如果不是  则不能
		if( $page['is_refresh'] <=0  ){
			echo json_encode( array('info'=>'对不起，该会员未通过VIP认证，不能推荐！','status'=>'n') );
			exit();
		}
		
		
		//检查是否可以推荐此信息
		/*$isHot = $this->db->query("select * from user where id = {$page['uid']} and isMember = 1 ")->num_rows();
		$isAdmin = $this->session->userdata('isAdmin');
		if( $isHot<=0 && $isAdmin<=0 ){
			echo json_encode( array('info'=>'对不起，该会员未通过VIP认证，不能推荐！','status'=>'n') );
			exit(); 
		}
		*/
		$ip = get_client_ip();
		$user_id = $this->session->userdata('user_id');
		$where = ' 1=1 ';
		if( !empty($ip) ){
			$where .= " and ip = '{$ip}' ";
		}
		if( $user_id ){
			$where .= " and uid = {$user_id} ";
		}
		$where .= " and page_id = {$page['id']} ";
		
		
		$hot = $this->db->query("select * from hot where $where order by ctime desc limit 0,1 ")->row_array();
		$time = time();
		if( !empty($hot) ){
			$curr_time = date('Y-m-d',$time);
			$before_time = date('Y-m-d',$hot['ctime']);
			if( strtotime($before_time) == strtotime($curr_time) ){
				echo json_encode( array('info'=>'亲，今天你已经推荐过我了，明天再来哦！','status'=>'n') );
				exit(); 
			}
		}
		$data = array();
		$data['uid'] = $user_id ? $user_id : 0;
		$data['ctime'] = $time;
		$data['page_id'] = $page['id'];
		$data['ip'] = $ip;
		$result = $this->db->insert('hot',$data);
		if( $result )	{
			$result = $this->db->query("update page set hot = hot+1,hot_time =".$time."  where id = {$id}");
		}
		if( $result ){
			echo json_encode( array('info'=>'推荐成功，感谢你对我的支持！','status'=>'y') );
			exit(); 
		}else{
			echo json_encode( array('info'=>'推荐出错,请重试','status'=>'n') );
			exit(); 
		}
	
	}
	//递交名片
	function card(){
		$id = isset($_REQUEST['id']) ? intval( $_REQUEST['id']) : 0;
		if( $id<= 0){
			echo json_encode( array('info'=>'参数错误','status'=>'n') );
			exit(); 
		}
		$page = $this->page_model->page("id = $id");
		if( empty( $page ) ){
			echo json_encode( array('info'=>'递交失败,没有该信息存在','status'=>'n') );
			exit(); 
		}
		$user_id = $this->session->userdata('user_id');
		if( empty( $user_id ) ){
			echo json_encode( array('info'=>'对不起，没有登录不能递交名片！','status'=>'n') );
			exit(); 
		}
		if( $page['uid'] == $user_id ){
			echo json_encode( array('info'=>'对不起，不能递交给自己','status'=>'n') );
			exit(); 
		}
		
		//检查是否可以递交名片
		$isMember = $this->db->query("select * from user where id = {$user_id} and isMember = 1")->num_rows();
		if( $isMember<=0 ){
			echo json_encode( array('info'=>'递交失败，你的名片未通过VIP认证！','status'=>'n') );
			exit(); 
		}
		
		$isCard = $this->db->query("select * from card where uid ={$user_id} and touid = {$page['uid']}")->num_rows();
		if( $isCard>0 ){
			echo json_encode( array('info'=>'已经递交','status'=>'n') );
			exit(); 
		}
		//查询
		$link_str = "";
		$page_list = $this->db->query("select * from page where uid = {$user_id}")->result_array();
		if( count($page_list)>0 ){
			foreach ($page_list as $k=>$v){
				$isExists = $this->db->query("select * from card where uid = $user_id and touid = {$page['uid']} and page_id = {$v['id']}")->num_rows();
				if( $isExists>0 ) continue;
				$data = array();
				$data['uid'] = $user_id;
				$data['ctime'] = date("Y-m-d H:i:s");
				$data['touid'] = $page['uid'];
				$data['page_id'] = $v['id'];
				$result = $this->db->insert('card',$data);
				$link_str .= '<a href="'.two_site_url( $v['id'] ).'" >'.two_site_url( $v['title'] ).'</a><br/>';
			}
		}else{
			echo json_encode( array('info'=>'对不起，递交失败，你还没有发布名片','status'=>'n') );
			exit(); 
			
		}
		//发邮件
		$toUser= $this->db->query("select * from user where id = {$page['uid']}")->row_array();
		if( $toUser ){
			
			$this->load->library('mymail');
			$userinfo = $this->db->query("select * from user where id = {$user_id}")->row_array();
			if( empty($userinfo['nickname']) ){
				$phone = $this->session->userdata('phone');
				$phone = $phone ? $phone : $this->session->userdata('username');
			}else{
				$phone = $userinfo['nickname'];
			}
			$subject = "{$phone}-在查物流网给您递交了一张名片";
			$content = '
			您好！<br/>
			在查物流网中，您收到'.$phone.'给你递交了名片，点击查看- <a href="'.site_url('uc/card').'" >'.site_url('uc/card').'</a><br/>
			名片信息列表：'.$link_str.' 
			如果您还有任何的疑问, 请与网站管理员联络.<br/>
			87756.com网站管理员<br/>';
			//$subject = iconv("UTF-8", "GB2312", $subject);
			//$content = iconv("UTF-8", "GB2312", $content);
			
			$this->mymail->send( $toUser['mail'] ,$subject,$content);
			
			//echo json_encode( array('info'=>'邮件发送成功,请注意查收，修改新的密码','status'=>'y') );
			//exit();
		
		}
		
		if( true ){
			echo json_encode( array('info'=>'递交成功，我们会通知对方查收！','status'=>'y') );
			exit(); 
		}else{
			echo json_encode( array('info'=>'对不起，递交失败','status'=>'n') );
			exit(); 
		}
		
		
	}
	
	/**
	 * 快速发布名片
	 */
	public function fastpost(){
		$user_id = $this->session->userdata('user_id');
		if($user_id){
			$total = $this->db->query("select * from page where uid = $user_id")->num_rows();
			if($total > 0){
				$return = array('info'=>"对不起！您不能发布名片信息了,只能发布 {$total}条！",'status'=>'n');
				$this->myView("portal/promptpost", $return);
				return;
			}
		}
			$systemList = $this->system_model->getSystemConfig( "'fastsharetitle','fastsharedes'" );
		foreach ($systemList as $row){
				$meta[$row->name] = $row->value;
		}
		$data=array("bottomShow"=>2);
		$data["fastsharetitle"]=$meta["fastsharetitle"];
		$data["fastsharedes"]=$meta["fastsharedes"];
		$meta['title']=$data["fastsharetitle"];
		$this->myView('portal/fastpost',$data);
	}
	/**
	 * 执行快速发布名片
	 */
	public function dopostcard(){
		$this->load->helper('url');
		$data=array();
		$data["title"]="【".$_REQUEST["profile"]."】".$_REQUEST["startingcity"]."至".$_REQUEST["directroute"];
		$data["phone"]=$_REQUEST["phone"];
		$data["ctime"]=time();
	
		$return  = $this->page_model->fastadd( $data );
		$data["info"]=$return["info"];
		$data["status"]=$return["status"];
	    $data["id"]=isset($return["id"])?$return["id"]:0;
		$data["logo"]=isset($return["logo"])?$return["logo"]:"";
		if($data["logo"]!=""){
		//获得文件扩展名  输出水印图片
		$temp_arr = explode(".", $data['logo'] );
		$file_ext = array_pop($temp_arr);
		$file_ext = trim($file_ext);
		$file_path_pre = str_replace(".".$file_ext, "", $data['logo'] );
		$file_ext = strtolower($file_ext);
		//判断水印图片是否存在
		if(file_exists( UPLOAD."page/".$file_path_pre.md5("_water_img").".".$file_ext )){
			$data['logo'] = SITE_URL."uploads/page/".$file_path_pre.md5("_water_img").".".$file_ext; //timthumb(base_url()."uploads/page/".$page['logo']."_water.".$file_ext ,440,280, 2);
		}else{
			$data['logo'] = SITE_URL."uploads/page/".$data['logo'];
		}
		}
		if($data["id"]>0){
		 redirect("/detail/".$data["id"]."/1");
		}else{
		 $this->myView("portal/promptpost",$data);
		}
	}
	//上传图片方法
	function uploadImg(){
		//附件操作
		$this->load->helper('upload');
		$info = upload('page','page');//目录 ，附件类别
		//门头照片
		$logo = !$info['status'] ? "" : $info['info'][0]['savepath'].$info['info'][0]['savename'];
		echo "<script>window.parent.callback('$logo')</script>";
	
	
	}
	
	
	
}
?>