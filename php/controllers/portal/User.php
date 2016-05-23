<?php
/**
 * 用户的基本操作
 */
require 'Main.php';
class User extends Main{
	public function __construct(){
		parent::__construct();
		//检查是否登录,如果登录跳转到用户中心
		$user_id = $this->session->userdata('user_id');
		if( $user_id !==false && $user_id >0 ){
			redirect('','location');
		}
	}
	//注册
	public function register(){		
		$this->myView("portal/register");
	}
	//注册的数据提交
	public function doRegister(){			
		$res = array('info'=>'注册失败','status'=>'n' );
		$phone = isset($_REQUEST['phone']) ? trim( $_REQUEST['phone']) : '';
		$password = isset($_REQUEST['password']) ? trim( $_REQUEST['password']) : '';
		$mail = isset($_REQUEST['mail']) ? trim( $_REQUEST['mail']) : '';
		$vcaptcha = isset($_REQUEST['vcaptcha']) ? trim($_REQUEST['vcaptcha']) : '';
		$nickname = isset($_REQUEST['nickname']) ? trim($_REQUEST['nickname']) : '';
		$wexin = isset($_REQUEST['wexin']) ? trim( $_REQUEST['wexin']) : '';
		if( empty($phone) ){
			$res['info'] = '手机号码不能为空';
			echo json_encode( $res );
			exit();
		}
		$user = $this->user_model->getUser('phone',$phone);
		if( $user || !empty($user) ){
			$res['info'] = '手机号码已经注册,注册失败';
			echo json_encode( $res );
			exit();	
		}
		if( empty($nickname) ){
			$res['info'] = '昵称不能为空';
			echo json_encode( $res );
			exit();
		}
		
		if(  empty($password) ){
			$res['info'] = '密码不能为空';
			echo json_encode( $res );
			exit();
		}
		//if( empty($mail) ){
		//
		//	$res['info'] = '邮箱不能为空';
		//	echo json_encode( $res );
		//	exit();
		//}
		if( $mail ){
			$user = $this->user_model->getUser('mail',$mail);
			if( $user || !empty($user) ){
				$res['info'] = '邮箱已经使用,请使用其它的邮箱,注册失败';
				echo json_encode( $res );
				exit();	
			}	
		}	
		if( $vcaptcha != $this->session->userdata('captcha') ){
			$res['info'] = '验证码错误,请重新输入,注册失败';
			echo json_encode( $res );
			exit();	
		}
		$password = md5(md5($password));
		$data['phone'] = $phone;
		$data['mail'] = $mail;
		$data['pass'] = $password;
		$data['ctime'] = time();
		$data['nickname'] = $nickname;
		$data['wexin'] = $wexin;
		$result = $this->db->insert('user', $data );	
		if( $result ){
			$user_id = $this->db->insert_id();
			$session = array(
				'user_id'		=> $user_id,
				'username'		=> $mail,
				'phone'			=> $phone,
				'isAdmin'		=> 0,
				'isMember'		=> 0
			);
			$this->session->set_userdata( $session );
			$this->input->set_cookie('user_info_list',$user_id,1*365*24*3600 );
			$res['status'] = 'y';
			$res['info'] = '注册成功';
			echo json_encode( $res );
			exit();	
		
		}else{
			$res['info'] = '注册失败,请重试';
			echo json_encode( $res );
			exit();	
			
		}
		
	}
	//登录
	public function login(){	
		$this->myView('portal/login');
	}
	//登录
	public function doLogin(){
		$res = array('info'=>'登录失败用户名或密码错误','status'=>'n' );
		$phone = isset($_REQUEST['phone']) ? trim( $_REQUEST['phone']) : '';
		$password = isset($_REQUEST['password']) ? trim($_REQUEST['password']) : '';
		if( empty($phone) || empty($password) ){
			echo json_encode( $res );
			exit();
		}
		$user = $this->user_model->doLogin($phone,$password);
		if( $user!==false && !empty( $user ) ){
			$session = array(
				'user_id'		=> $user['id'],
				'username'		=> $user['mail'],
				'phone'			=> $user['phone'],
				'isAdmin'		=> $user['isAdmin'],
				'isMember'		=> $user['isMember']
				);
			$this->session->set_userdata( $session );
			$this->input->set_cookie('user_info_list',$user['id'],1*365*24*3600 );
			$res['info'] = '登录成功';
			$res['status'] = 'y';
			echo json_encode( $res );
			exit();
		}else{
			echo json_encode( $res );
			exit();
		}	
	}
	//忘记密码
	function forget(){
		$data['meta']['title'] = '忘记密码?';
		$this->myView('portal/forget',$data);	
	}
	//忘记密码
	function doForget(){
		$mail = isset($_REQUEST['mail']) ? trim( $_REQUEST['mail'] ) : '';
		if(empty($mail)){
			echo json_encode( array('info'=>'邮箱不能为空，发送邮箱失败','status'=>'n') );
			exit(); 
		}
		$findMsg = $this->find_model->addFind('mail',$mail);
		if( $findMsg ){
			
			$this->load->library('mymail');
			
			$subject = "密码找回邮箱验证";
			$content = '
			您好！<br/>
			您收到这封这封电子邮件是因为您 (也可能是某人冒充您的名义) 忘记密码. 假如这不是您本人所申请, 请不用理会这封电子邮件, 但是如果您持续收到这类的信件骚扰, 请您尽快联络管理员.<br/>
			要设置新的密码, 请使用以下链接去设置新的密码.<br/>
			<a href="'.site_url('resetpass').'/'.$findMsg['code'].'">'.site_url('resetpass').'/'.$findMsg['code'].'</a><br/>
			在打开之后之后, 输入邮箱验证码以及新的密码:<br/>
			邮箱验证码: '.$findMsg['randpass'].'<br/>
			 如果您还有任何的疑问, 请与网站管理员联络.<br/>
			87756.com网站管理员<br/>';
			//$subject = iconv("UTF-8", "GB2312", $subject);
			//$content = iconv("UTF-8", "GB2312", $content);
			
			$this->mymail->send($mail,$subject,$content);
			
			echo json_encode( array('info'=>'邮件发送成功,请注意查收，修改新的密码','status'=>'y') );
			exit();
		
		}else{
		
			echo json_encode( array('info'=>'验证邮箱失败,请重试','status'=>'n') );
			exit(); 

		}
		
		
	}
	//找回密码 设置新的密码
	function resetpass($code){
		$result = $this->find_model->verify( $code );
		if( !$result || ($result['is_del'] == 1) ){ //不存在或已经验证过
			notExists();
			exit();
		}else{
			$data['code'] = $code;
			$this->myView('portal/resetpass',$data);
			
		}
	
	}
	function doResetpass(){
		$res = array('info'=>'找回密码,设置新密码失败','status'=>'n' );
		$code = isset($_REQUEST['code']) ? trim( $_REQUEST['code']) : '';
		$password = isset($_REQUEST['password']) ? trim( $_REQUEST['password']) : '';
		$randpass = isset($_REQUEST['randpass']) ? trim( $_REQUEST['randpass']) : '';
		if( empty($code) ||  empty($password) || empty($randpass) ){
			echo json_encode( $res );
			exit();
		}
		
		$result = $this->db->query("select * from find where code = '{$code}' and randpass = '{$randpass}'")->row_array();
		if( $result ){
			$data = array();
			$data['is_del'] = 1;
			$this->db->query("update find set is_del = 1 where id = {$result['id']}");
			$data = array();
			$data['pass'] = md5( md5( $password ) );
			$result = $this->db->update( 'user', $data, array( 'id'=> $result['uid'] ) );
			if( $result ){
				$res['info'] = '设置新密码成功';
				$res['status'] = 'y';
				echo json_encode( $res );
				exit();
			}else{
				$res['info'] = '安全验证不通过,请重新再试';
				echo json_encode( $res );
				exit();
			}
		}else{
			$res['info'] = '安全验证不通过,请重新再试';
			echo json_encode( $res );
			exit();
			
		}
	
	}
	
}