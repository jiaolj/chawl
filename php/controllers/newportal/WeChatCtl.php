<?php
class WeChatCtl extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();

		// $options = array(
		//     'token'=>'rhzx3519', //填写你设定的key
		//     'encodingaeskey'=>'sO40LlGm9RJjqX9OfiCAskipXYWEwXpayCwIdNsXLaN', //填写加密用的EncodingAESKey
		//     'appid'=>'wx1e900cb4be8e281c', //填写高级调用功能的app id, 请在微信开发模式后台查询
		//     'appsecret'=>'d4624c36b6795d1d99dcf0547af5443d' //填写高级调用功能的密钥
	 //    );
				$options = array(
		    'token'=>'PewCzEQ6vFVBQbQnz2nv26QF1VaJ8qNf', //填写你设定的key
		    'encodingaeskey'=>'tqi22NJB1J76Qv7vFWEBJFch1QWI2VIQEQBf9n1FH9q', //填写加密用的EncodingAESKey
		    'appid'=>'wxbbfe68842d68246f', //填写高级调用功能的app id, 请在微信开发模式后台查询
		    'appsecret'=>'6faa70a3f6c4cfdc95061f1d0898e908' //填写高级调用功能的密钥
	    );
		$this->load->library('wechat', $options);
		unset($options);

		$options['client_id']='YXA6Q9aE4LTpEeWJIW3kNpRT5g';
		$options['client_secret']='YXA6d_W3L4RljvDJ0xFdyjxNuO1mWUM';
		$options['org_name']='yibangwuliu';
		$options['app_name']='chawuliu';

		$this->load->library('easemob', $options);
	}


	/*返回jssdk签名*/
	public function getJsSign()
	{
		$res = array('info' => '获取微信签名失败', );
		$url = isset($_REQUEST['url']) ? trim($_REQUEST['url']) : '';
		if (empty($url)) {
			$res['info'] = 'url不能为空';
			echo json_encode($res);
			exit(0);
		}
		$url = urldecode($url);
		//var_dump($url);
		$signPackage = $this->wechat->getJsSign($url);

		if (is_bool($signPackage) && !$signPackage) {
			echo json_encode($res);
			exit(0);
		}

		echo json_encode($signPackage);
	}

	public function api()
	{
		$this->wechat->valid();

		$type = $this->wechat->getRev()->getRevType();
		
		switch($type) {
			case Wechat::MSGTYPE_TEXT:
					break;
			case Wechat::MSGTYPE_EVENT:
					$array = $this->wechat->getRev()->getRevEvent();
					$data = $this->wechat->getRevData();
					$openid = $data['FromUserName'];

					switch ($array['event']) {
						case 'subscribe':
							//$this->Register($openid);
							$this->wechat->text("欢迎关注查物流公众号")->reply();
							break;
						case 'unsubscribe':
							$this->UnRegister($openid);
							break;
						case 'VIEW':
							//log_message('error', 'VIEW: '.$openid);
							//$this->doLogin($openid);
						default:
							# code...
							break;
					}
					break;
			case Wechat::MSGTYPE_IMAGE:
					break;
			default:
				$this->wechat->text("help info")->reply();
		}

	}


	/*通过openid获取用户信息并注册*/
	private function Register($openid)
	{	

		$user_info = $this->wechat->getUserInfo($openid);
		if (is_bool($user_info) || $user_info['subscribe'] == 0) 
			return;

		$data['openid'] = $user_info['openid'];
		$data['nickname'] = $user_info['nickname'];
		$data['sex'] = $user_info['sex'] == '1' ? 'M' : 'F';
		$data['province'] = $user_info['province'];
		$data['country'] = $user_info['country'];
		$data['city'] = $user_info['city'];
		$data['subscribe'] = $user_info['subscribe'];
		$data['subscribe_time'] = $user_info['subscribe_time'];
		$data['groupid'] = $user_info['groupid'];
		$data['headimgurl'] = $user_info['headimgurl'];

		//查询之前是否注册过
		$row = $this->db->query("SELECT id FROM user WHERE openid = '{$openid}' ")->result();
		if (isset($row) && count($row)>0) {
			$user_id = $row[0]->id;
			$this->db->set('subscribe', 1);
			$this->db->set('subscribe_time', $user_info['subscribe_time']);
			$this->db->set('sex', $user_info['sex'] == '1' ? 'M' : 'F');
			$this->db->set('nickname', $user_info['nickname']);
			$this->db->set('province', $user_info['province']);
			$this->db->set('country', $user_info['country']);
			$this->db->set('city', $user_info['city']);
			$this->db->set('groupid', $user_info['groupid']);
			$this->db->set('headimgurl', $user_info['headimgurl']);
			$this->easemob->editNickname($user_id, $data['nickname']);

			$this->db->where('id', $user_id);
			$this->db->update('user');
			exit(0);
		}
		
		$pass = '123456';//默认密码为123456
		$data['pass'] = md5(md5($pass));
		$this->db->insert('user', $data);
		$user_id = $this->db->insert_id();

		//注册环信账号
		$this->easemob->createUser($user_id, $data['pass']);
		$this->easemob->editNickname($user_id, $data['nickname']);

	}

	/*用户取消关注时，设置subscribe字段为0*/
	private function UnRegister($openid = 'abc')
	{
		$row = $this->db->query("SELECT id FROM user WHERE openid = '{$openid}' ")->result();
		if (isset($row) && count($row)>0) {
			$user_id = $row[0]->id;
			$this->db->set('subscribe', 0);
			$this->db->set('subscribe_time', 0);
			$this->db->where('id', $user_id);
			$this->db->update('user');
		}
	}

	//登录
	private function doLogin($openid){
		if( empty($openid) ){
			exit();
		}

		$user = $this->user_model->doClickMenuLogin($openid);
		if( $user!==false && !empty( $user ) ){
			$session = array(
				'user_id'		=> $user['id'],
				'username'		=> $user['mail'],
				'nickname'		=> $user['nickname'],
				'phone'			=> $user['phone'],
				'isAdmin'		=> $user['isAdmin'],
				'isMember'		=> $user['isMember'],
				'isRegister'	=> $user['isRegister']
				);
			$this->session->set_userdata( $session );
			$this->input->set_cookie('user_info_list',$user['id'],1*365*24*3600 );
			$user_id = $this->session->userdata('user_id');
			$session_id =$this->session->userdata('session_id');
			log_message('error', '用户登录成功: '.$openid.', nickname:'.$user['nickname'].', user_id: '.$user_id.', session_id: '.$session_id);
		}	
	}



}





