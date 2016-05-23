<?php
/**
 * 用户中心
 */
require 'Main.php';
class Ucenter extends Main{
	private $user_id,$phone,$isAdmin,$isMember,$username;
	private static $left = '1';
	public function __construct(){//初始化一些用户的基本信息，都是在session中取出
		parent::__construct();
		$user_id = $this->session->userdata('user_id');
		$phone = $this->session->userdata('phone');
		$isAdmin = $this->session->userdata('isAdmin');
		$isMember = $this->session->userdata('isMember');
		$username = $this->session->userdata('username');
		if( $user_id !==false && $user_id >0 ){
			$this->user_id = $user_id;
			$this->phone = $phone;
			$this->isAdmin = $isAdmin;
			$this->isMember = $isMember;
			$this->username = $username;
		}else{
			redirect('login');
		}
	}
	//注销登录
	public function logout(){
		
		$user = array(
			'user_id'		=> '',
			'username'		=> '',
			'phone'			=> '',
			'isAdmin'		=> '',
			'isMember'		=> ''
		);		
		$this->session->unset_userdata( $user );
		$this->input->set_cookie('user_info_list','',1*365*24*3600 );
		redirect('','location');
		exit();
	}
	//管理中心
	public function uc(){
		$this->myView('portal/uc');
	}
	//获取我发布的信息列表
	public function index(){
		$list = $this->page_model->pageList(" uid={$this->user_id} ", "" );
		foreach ($list as $k=>&$v){
			$temp_arr = explode(".", $v['logo'] );
			$file_ext = array_pop($temp_arr);
			$file_ext = trim($file_ext);
			$file_path_pre = str_replace(".".$file_ext, "", $v['logo'] );
			$file_ext = strtolower($file_ext);
			$v['logo'] = SITE_URL."uploads/page/".$file_path_pre.md5("_water_img").".".$file_ext;	
			//$user = $this->db->query("select * from user where id = {$v['uid']} and isMember = 1")->num_rows();
			$v['isMember'] = $this->isMember;
		}
		$data = array(
			'list' => $list,
			//'meta' => $meta,
			'left'	=> Ucenter::$left
		);
		
		$this->myView("portal/list", $data );
		
	}
	//账户信息
	function account(){
		
		//Ucenter::$left = 2;
		$user = $this->db->query("select * from user where id = {$this->user_id}")->row_array();
		$data = array(
			//'meta' => array('title'=>'我的账户信息'),
			//'left'	=> Ucenter::$left,
			'wexin' => $user['wexin'],
			'mail' => $user['mail'],
			'phone'	=> $user['phone'],
			'isMember' => $this->isMember,
			'user' => $user
		);
		
		$this->myView('portal/account', $data );
	
	}
	//修改账户信息
	function updatepass(){
		$res = array('info'=>'保存新的信息失败','status'=>'n');
		$oldpassword = isset( $_REQUEST['oldpassword']) ? trim($_REQUEST['oldpassword']) : '';
		$password =  isset( $_REQUEST['password']) ? trim($_REQUEST['password']) : '';
		$nickname = isset( $_REQUEST['nickname']) ? trim($_REQUEST['nickname']) : '';
		$phone = isset( $_REQUEST['phone']) ? trim($_REQUEST['phone']) : '';
		$mail = isset( $_REQUEST['mail']) ? trim($_REQUEST['mail']) : '';
		$wexin = isset($_REQUEST['wexin']) ? trim( $_REQUEST['wexin']) : '';
		
		if( empty( $nickname ) ){
			$res['info'] = '昵称不能为空';
			echo json_encode( $res );
			exit();
		}
		if( empty( $phone ) ){
			$res['info'] = '手机号码不能为空';
			echo json_encode( $res );
			exit();
		}
		//检查手机号码是否存在
		$user = $this->db->query("select * from user where id <> {$this->user_id} and phone = '{$phone}' ")->row_array();
		if( $user ){
			$res['info'] = '手机号码已使用,使用其它的手机号码';
			echo json_encode( $res );
			exit();
		}
		
		//if( empty( $mail ) ){
		//	$res['info'] = '邮箱不能为空';
		//	echo json_encode( $res );
		//	exit();
		//}
		//检查邮箱是否存在
		if( $mail ){
			$user = $this->db->query("select * from user where id <> {$this->user_id} and mail = '{$mail}' ")->row_array();
			if( $user ){
				$res['info'] = '邮箱已经使用,使用其它的邮箱';
				echo json_encode( $res );
				exit();
			}
		}
		$isUpdate = true;
		if( !$oldpassword || !$password ){
			//echo json_encode( $res );
			//exit();
			$isUpdate = false;
		}
		if( $isUpdate && ($oldpassword == $password) ){
			$res['info'] = '新密码和旧密码一样修改失败';
			echo json_encode( $res );
			exit();
		}
		$password = md5( md5( $password ) );
		$oldpassword = md5( md5($oldpassword) );
		$user = $this->db->query("select * from user where id = {$this->user_id} ")->row_array();
		if( $user ){
			$isUpdate && ($data['pass'] = $password);
			$data['nickname'] = $nickname;
			$data['mail'] = $mail;
			$data['phone'] = $phone;
			$data['wexin'] = $wexin;
			$result = $this->db->update('user',$data, array('id'=>$this->user_id) );
			if( $result ){
				if( $user['mail'] != $mail && $mail  ){ //如果邮箱修改了，则发送邮件
					$this->load->library('mymail');
					$subject = "邮箱修改";
					$content = '
					您好！<br/>
					您收到这封这封电子邮件是因为您 (也可能是某人冒充您的名义) 修改了邮箱. 假如这不是您本人所修改,请您尽快联络管理员.<br/>
					 如果您还有任何的疑问, 请与网站管理员联络.<br/>
					87756.com网站管理员<br/>';
					//$subject = iconv("UTF-8", "GB2312", $subject);
					//$content = iconv("UTF-8", "GB2312", $content);
					
					$this->mymail->send($mail,$subject,$content);
				}
				$res['info'] = '保存新的信息成功';
				$res['status'] = 'y';
				echo json_encode( $res );
				exit();
			
			}else{
				echo json_encode( $res );
				exit();
			}		
		}else{		
			$res['info'] = '旧密码错误';
			echo json_encode( $res );
			exit();
		}
		
	}
	//发布消息
	function post($msg=''){
		//先检查是否可以发布信息
		$user = $this->db->query("select * from user where id = {$this->user_id}")->row_array();
		$post_total	 = 1;		
		if( !empty($user) ){
			isset( $user['postnums'] ) && ( $post_total = $user['postnums'] );
		}
		//检查我的已经发布的条数
		$total = $this->db->query("select * from page where uid = {$this->user_id}")->num_rows();
		$cityList = $this->city_model->cityList("parent_id = 0 and isclose = 0","",' order by ctime asc ');
		//$sortList = $this->sort_model->getSortArr();
		$city_curr = $this->getCity();
		$province = $this->province();
		$post_page_sid = time();
		$this->input->set_cookie('post_page_sid',$post_page_sid,3600 );
		$data = array(
			//'meta' => array('title'=>'发布信息'),
			'isPost'  => ($post_total<=$total ? 0 : 1 ),
			'post_total' => $post_total,
			'left'	=> Ucenter::$left,
			'cityList' => $cityList,
			'city_curr' => $city_curr,
			'province' => $province,
			'msg' => $msg,
			'post_page_sid' => $post_page_sid
			//'sortList' => $sortList
		);
		//print_r( $sortList );
		$this->myView( 'portal/post',$data );
	
	}
	//发布数据
	function doPost(){
		$post_page_sid = $this->input->cookie('post_page_sid');
		if( isset($_REQUEST['post_page_sid']) && ( $post_page_sid == $_REQUEST['post_page_sid']) ){
			//先检查是否可以发布信息
			$user = $this->db->query("select * from user where id = {$this->user_id}")->row_array();
			$post_total	 = 1;		
			if( !empty($user) ){
				isset( $user['postnums'] ) && ( $post_total = $user['postnums'] );
			}
			//检查我的已经发布的条数
			$total = $this->db->query("select * from page where uid = {$this->user_id}")->num_rows();
			if( $post_total > $total ){
				$data['uid'] = $this->user_id;
				$data['ctime'] = $data['mtime'] = time();
				if( $this->isAdmin>0  )$data['isagree'] = 1;  //如果是管理员
				$data['is_vip'] = $user['isMember'] >0 ? 1 : 0; //是vip用户
				$return  = $this->page_model->add( $data, SITE_URL );
				
			}else{
			
				$return = array('info'=>"对不起！您不能发布名片了,只能发布 {$post_total}条！",'status'=>'n');
			
			}
		}else{
			$return['info'] ='不能重复提交表单';
		}
		//$return['meta'] = array('title'=>'发布信息的提示消息');
		//$return['mode'] = 'add'; 
		//$return['left']	= Ucenter::$left;
		$this->post( $return['info'] );
	}
	//编辑信息
	function edit( $id, $msg='' ){
		$page = $this->page_model->page("id = $id");
		if(!$page ){
			notExists();
			exit(0);
		}
		$cityList = $this->city_model->cityList("parent_id = 0","",' order by ctime asc ');
		//$sortList = $this->sort_model->getSortArr();
		//$smallList = $this->city_model->cityList("parent_id in (".trim($page['city_id'],',').")","");
		$page['content'] = stripslashes($page['content']);
		$page['content'] = strip_tags($page['content'],"<br>");
		$data = array(
			'page' => $page,
			'left'	=> Ucenter::$left,
			'cityList' => $cityList,
			//'sortList' => $sortList,
			//'meta'	=> array('title'=>'信息的修改'),
			'isPost' => 1,
			'msg' => $msg
			//'smallList' => $smallList
		);
		$this->myView('portal/edit',$data);
	}
	//提交编辑
	function doEdit( $id ){
		$data['mtime'] = time();
		$return  = $this->page_model->edit( $data ,$id,SITE_URL );
			
		//$return['meta'] = array('title'=>'编辑信息的提示消息');
		//$return['mode'] = 'edit'; 
		//$return['left']	= Ucenter::$left;		
		//$this->myView("portal/postsucc", $return['info']);
		$this->edit( $id, $return['info']);
	}
	//获取小城市下的
	function getSmallCityList(){
		$province_id_index = $_REQUEST['province_id_index'];
		$city_id_index = $_REQUEST['city_id_index'];
		$cityList = $this->city_model->cityList("parent_id = {$province_id_index} and isclose = 0 and level = 2",""," order by ctime asc ");
		$html = "";
		if(!empty($cityList)){
			$html .= '<select name="city[]" class="getCitySortList" onchange="getCitySortList()" style="height:30px; margin-top:4px; margin-left:5px; order:1px #ededed solid;">';
			foreach ( $cityList as $k=>$v){
				$html .= "<option value='{$v['id']}' ".(( strpos($city_id_index,$v['id']) !==false ) ? "selected='selected'" : "").">{$v['name']}</option>";
			}
			$html .= '</select>';
		}
		echo $html;
	}
	//获取收藏的
	function keep(){

		$where = " uid = {$this->user_id} ";
		
		$total = $this->db->query("select * from keep where {$where}")->num_rows();
		//分页
		$page = 1;
		$count = 20;
		$countPage = ceil($total/$count);
		if( isset($_REQUEST['page']) && intval($_REQUEST['page'])>0 ){
			$page = intval( $_REQUEST['page'] ); 
		}
		$limit = ($page-1)*$count.",".$count;
		$page_arr = array('page'=>$page,'count'=>$count,'countPage'=>$countPage,'total'=>$total);	
		$page_arr['pre'] = site_url('uc/keep').'?page='.($page-1);
		$page_arr['next'] = site_url('uc/keep').'?page='.($page+1);
		
		$keep_list = $this->db->query("select * from keep where {$where}  order by ctime desc limit ".$limit)->result_array();
		
		foreach($keep_list as $k=>&$v){
			$page = $this->page_model->page( "id = {$v['page_id']}" );
			if( empty($page) ) { unset($keep_list[$k]); continue; }
			$v['title'] = $page['title'];
			$v['hot'] = $page['hot'];
			$v['view'] = $page['view'];
			$v['phone'] = $page['phone'];
			$v['address'] = $page['address'];
			$temp_arr = explode(".", $page['logo'] );
			$file_ext = array_pop($temp_arr);
			$file_ext = trim($file_ext);
			$file_path_pre = str_replace(".".$file_ext, "", $page['logo'] );
			$file_ext = strtolower($file_ext);
			$v['logo'] = SITE_URL."uploads/page/".$file_path_pre.md5("_water_img").".".$file_ext;	
			$user = $this->db->query("select * from user where id = {$page['uid']} and isMember = 1")->num_rows();
			$v['isMember'] = $user>0 ? 1 : 0;
		}
		
		//Ucenter::$left = 3;
		$data = array(
			//'meta' => array('title'=>'我关注的信息'),
			//'left' => Ucenter::$left,
			'list' => $keep_list,
			'page_arr' => $page_arr
		);
		
		$this->myView('portal/keep',$data);
	}
	function deleteKeep(){
		$id_array = $_REQUEST['kid'];
		$id_str = join(',',$id_array);
		$result = $this->db->query("delete from keep where id in ({$id_str})");
		redirect('uc/keep');
	}
	//根据城市获取相应的分类
	function getCitySortList(){
		$city_id_index = isset($_REQUEST['city_id_index']) ? $_REQUEST['city_id_index'] : 0;
		$sort_id_index = isset($_REQUEST['sort_id_index']) ? $_REQUEST['sort_id_index'] : '';
		$this->setCity( $city_id_index );
		$str = '<li id="sort_select_tr">
				<div class="input_field">
                   	 <select name="sort"  class="big_sort"  style="height:30px; margin-top:4px; margin-left:5px; order:1px #ededed solid;">
                        <option value="">选择名片所属的类别</option>
                     </select>
               	</div>
	           	</li>';
		if( $city_id_index<= 0 ){
			echo $str;
			exit();
		}
		$sortList = $this->sort_model->getSortArr(0," city_id like '%,$city_id_index,%' ");
		if( empty($sortList) || $sortList === false ){
			echo $str;
			exit();
		}
		
		$str = '<li id="sort_select_tr">
					<div class="input_field">
                    		<select name="sort"  onchange="sortChange( this )" class="big_sort" style="height:30px; margin-top:4px; margin-left:5px; order:1px #ededed solid;" >
                    			<option value="">选择名片所属的类别</option>';
                    			foreach ($sortList as $k=>$sort){
                    			 $str .= "<option value='{$sort['id']}' ".( (stripos( $sort_id_index ,','.$sort['id'].',') !==false ) ? "selected='selected'" : "" ).">{$sort['name']}</option>";
                    			}
                    		$str .= '</select>
                    </div>
                 </li>
                 <li style="'.( $sort_id_index ? "" : "display:none;").'" id="sort_tr_item">
                 	<div class="input_field input_field_height">';
		                    	foreach( $sortList as $k=>$v ){
		                    	$str .= '<div class="sort_item_list" id="sort_item_list'.$v['id'].'" style="'.( ( stripos( $sort_id_index ,",{$v['id']},") !== false) ?'display:block;' : 'display:none;' ).'" >';
		                    			if( isset( $v['childs'] ) && !empty($v['childs']) ) {
											foreach($v['childs'] as $tk=>$tv ){//二级
										$str .= "<div class='sort_item'>
													<div style='line-height:20px;font-size:14px;font-weight:bold;'>
														<span>{$tv['name']}：</span>
														<input type='hidden' value='{$tv['id']}' name='sort{$tv['parent_id']}[]' checked='checked' />
													</div>
													<div style='width:auto;'>";	
												if(isset($tv['childs']) && !empty($tv['childs']) ){
													foreach($tv['childs'] as $sk=>$sv ){//三级
														if( $this->isAdmin == 1 ||  $this->isMember == 1){
															$str .= "<div class='fl' style='width:80px;'><label><input type='checkbox' class='small_radio' value='{$sv['id']}' name='sort{$sv['parent_id']}[]'  ".( (stripos($sort_id_index,','.$sv['id'].',') !==false ) ? "checked='checked'" : "" )."/>{$sv['name']}</label></div>";
														}else{
															$str .= "<div class='fl' style='width:80px;'><label><input type='radio' class='small_radio' value='{$sv['id']}' name='sort{$sv['parent_id']}'  ".( (stripos($sort_id_index,','.$sv['id'].',') !==false ) ? "checked='checked'" : "" )."/>{$sv['name']}</label></div>";
															
														}
													}
												}else{
									
													$str .="<div>还没有三级分类</div>";
										
												}
										$str .= "</div><div class='c'></div>
											</div>";			
									
											}
		                    			 }else{ 
		                    		
		                    				$str .= "<div>还没有二级分类</div>";
		                    			}
		                    		$str .= "</div>";
		                    	} 
		                    $str .= '
		               </div>
		           </li>';
		echo $str;
		
	}
	//收到的名片列表
	public function card(){
		$where = " touid = {$this->user_id} ";
		
		$total = $this->db->query("select * from card where {$where}")->num_rows();
		//分页
		$page = 1;
		$count = 20;
		$countPage = ceil($total/$count);
		if( isset($_REQUEST['page']) && intval($_REQUEST['page'])>0 ){
			$page = intval( $_REQUEST['page'] ); 
		}
		$limit = ($page-1)*$count.",".$count;
		$page_arr = array('page'=>$page,'count'=>$count,'countPage'=>$countPage,'total'=>$total);	
		$page_arr['pre'] = site_url('uc/card').'?page='.($page-1);
		$page_arr['next'] = site_url('uc/card').'?page='.($page+1);
		
		$keep_list = $this->db->query("select * from card where {$where}  order by ctime desc limit ".$limit)->result_array();
		
		foreach($keep_list as $k=>&$v){
			$page = $this->page_model->page( "id = {$v['page_id']}" );
			if( empty($page) ) { unset($keep_list[$k]); continue; }
			$v['title'] = $page['title'];
			$v['hot'] = $page['hot'];
			$v['view'] = $page['view'];
			$v['phone'] = $page['phone'];
			$v['address'] = $page['address'];
			$temp_arr = explode(".", $page['logo'] );
			$file_ext = array_pop($temp_arr);
			$file_ext = trim($file_ext);
			$file_path_pre = str_replace(".".$file_ext, "", $page['logo'] );
			$file_ext = strtolower($file_ext);
			$v['logo'] = SITE_URL."uploads/page/".$file_path_pre.md5("_water_img").".".$file_ext;	
			$user = $this->db->query("select * from user where id = {$page['uid']} and isMember = 1")->num_rows();
			$v['isMember'] = $user>0 ? 1 : 0;
		}
		
		//Ucenter::$left = 3;
		$data = array(
			//'meta' => array('title'=>'我关注的信息'),
			//'left' => Ucenter::$left,
			'list' => $keep_list,
			'page_arr' => $page_arr
		);
		$this->myView('portal/card',$data);
	}
	//删除名片
	public function deleteCard(){
		$id_array = $_REQUEST['cid'];
		$id_str = join(',',$id_array);
		$result = $this->db->query("delete from card where id in ({$id_str})");
		redirect('uc/card');
	}
}