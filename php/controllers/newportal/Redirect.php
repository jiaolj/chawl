<?php
/*
* 跳转控制器
*/
class Redirect extends CI_Controller
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
	}
 
	public function Index()
	{
		$code = $this->input->get('code');
		$sign = $this->input->get('sign');

		//log_message('error', 'code: '.$code.', sign: '.$sign);

		$json = $this->wechat->getOauthAccessToken(); 
		// log_message('error', json_encode($json));
		$openid = $json['openid'];
		$access_token = $json['access_token'];
		$this->Register($access_token, $openid);
					log_message('error', '111111111');

		switch ($sign) {
			case 1:
				redirect('http://wap.chawuliu.com/resource/new/index.html');
				break;
			case 2:
				redirect('http://wap.chawuliu.com/resource/new/find.html');
				break;
			case 3:
				redirect('http://wap.chawuliu.com/resource/new/my.html');
				break;
			default:
				redirect('http://wap.chawuliu.com/resource/new/index.html');
				break;
		}
		exit(0);
	}


	/*通过openid获取用户信息并注册*/
	private function Register($access_token, $openid)
	{	
		// if ($this->wechat->getOauthAuth($access_token, $openid) == false) {
		// 	exit(0);
		// }

		$user_info = $this->wechat->getUserInfo($openid);

				// log_message('error', '111111111');

		// log_message('error', json_encode($user_info));
		if ($user_info == false) 
			return;
				// log_message('error', '222222222');

		$data['openid'] = $user_info['openid'];
		// $data['nickname'] = filterEmoji($user_info['nickname']);
		$data['nickname'] = $user_info['nickname'];
		log_message('error', $data['nickname']);
		$data['sex'] = $user_info['sex'] == '1' ? 'M' : 'F';
		$data['province'] = $user_info['province'];
		$data['country'] = $user_info['country'];
		$data['city'] = $user_info['city'];
		// $data['subscribe'] = $user_info['subscribe'];
		// $data['subscribe_time'] = $user_info['subscribe_time'];
		// $data['groupid'] = $user_info['groupid'];
		$data['headimgurl'] = $user_info['headimgurl'];
		$data['phone'] = '';
		$data['isAdmin'] = 0;
		$data['isMember'] = 0;
		$data['isRegister'] = 0;
		$data['mail'] = '';


		//查询之前是否注册过
		$row = $this->db->query("SELECT * FROM user WHERE openid = '{$openid}' ")->result();
		if (isset($row) && count($row)>0) {
			$user_id = $row[0]->id;
			$phone = $row[0]->phone;
			$isAdmin = $row[0]->isAdmin;
			$isMember = $row[0]->isMember;
			$isRegister = $row[0]->isRegister;
			$mail = $row[0]->mail;

			//$this->db->set('subscribe', 1);
			//$this->db->set('subscribe_time', $user_info['subscribe_time']);
			$this->db->set('sex', $user_info['sex'] == '1' ? 'M' : 'F');
			$this->db->set('nickname', $user_info['nickname']);
			$this->db->set('province', $user_info['province']);
			$this->db->set('country', $user_info['country']);
			$this->db->set('city', $user_info['city']);
			//$this->db->set('groupid', $user_info['groupid']);
			$this->db->set('headimgurl', $user_info['headimgurl']);
			$this->easemob->editNickname($user_id, $data['nickname']);

			$this->db->where('id', $user_id);
			$this->db->update('user');

			$data['id'] = $user_id;
			$data['phone'] = $phone;
			$data['isAdmin'] = $isAdmin;
			$data['isMember'] = $isMember;
			$data['isRegister'] = $isRegister;
			$data['mail'] = $mail;

			$this->doLogin($data);

			return;
		}

		$data['ctime'] = time();
		$pass = '123456';//默认密码为123456
		$data['pass'] = md5(md5($pass));
		$this->db->insert('user', $data);
		$user_id = $this->db->insert_id();
		$data['id'] = $user_id;

		//注册环信账号
		$this->easemob->createUser($user_id, $data['pass']);
		$this->easemob->editNickname($user_id, $data['nickname']);

		$this->doLogin($data);

	}

	//登录
	private function doLogin($user)
	{
		if( $user!==false && !empty( $user ) ){
			$session = array(
				'user_id'		=> $user['id'],
				'username'		=> $user['mail'],
				'nickname'		=> $user['nickname'],
				'phone'			=> $user['phone'],
				'isAdmin'		=> $user['isAdmin'],
				'isMember'		=> $user['isMember'],
				'isRegister'	=> $user['isRegister'],
				'headimgurl'	=> $user['headimgurl']
				);
			$this->session->set_userdata( $session );
			$this->input->set_cookie('user_info_list',$user['id'],1*365*24*3600 );
			log_message('error', '用户登录成功: '.$user['openid'].', nickname:'.$user['nickname']);
		}	
	}

	private function filterEmoji($str)
	{
		$str = preg_replace_callback('/./u', 
			function(array $match){
				return strlen($match[0]) >= 4 ? '' : $match[0];
			}, 
			$str);
		return $str;
	}


}
