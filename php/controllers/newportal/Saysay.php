<?php 
require 'Main.php';
class Saysay extends Main{

	public function __construct(){
		parent::__construct();
		$this->load->model('post_model');
		$this->load->model('reply_model');

		$user_id = $this->session->userdata('user_id');
		$session_id =$this->session->userdata('session_id');

	}

	/* 帖子列表 */
	public function ListPost()
	{
		$res = $this->post_model->ListPost();
		echo json_encode($res);
	}

	/* 发表说说 */
	public function PublishPost()
	{
		$res = $this->post_model->Publish();
		echo json_encode($res);
	}

	/*回复列表*/
	public function ListReply($post_id)
	{
		$res = $this->reply_model->ListReply($post_id);
		echo json_encode($res);
	}

	/* 发表回复 */
	public function PublishReply($post_id)
	{
		$res = $this->reply_model->AddReply($post_id);
		echo json_encode($res);
	}

	/*帖子详情*/
	public function PostDetail($post_id)
	{
		$res = $this->post_model->Detail($post_id);
		echo json_encode($res);
	}

	/*点赞*/
	public function PostLike($post_id)
	{
		$res = $this->post_model->Like($post_id);
		echo json_encode($res);
	}
}
