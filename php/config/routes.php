<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
| -------------------------------------------------------------------------
| URI ROUTING
| -------------------------------------------------------------------------
| This file lets you re-map URI requests to specific controller functions.
|
| Typically there is a one-to-one relationship between a URL string
| and its corresponding controller class/method. The segments in a
| URL normally follow this pattern:
|
|	example.com/class/method/id/
|
| In some instances, however, you may want to remap this relationship
| so that a different class/function is called than the one
| corresponding to the URL.
|
| Please see the user guide for complete details:
|
|	http://codeigniter.com/user_guide/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There area two reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router what URI segments to use if those provided
| in the URL cannot be matched to a valid route.
|
*/


$route['404_override'] = "Error/error_404";
$route['scaffolding_trigger'] = "scaffolding";
$route['default_controller'] 	= 'portal/Index/index';  //首页
$route['error'] 	= 'portal/Error/index';  //错误页面

$route['switch_city'] 	= 'portal/Index/switch_city';  
$route['detail/(:num)'] 	= 'portal/Index/detail/$1'; 
$route['detail/(:num)/(:num)'] 	= 'portal/Index/detail/$1/$2';  
$route['express'] = 'portal/Index/express';
$route['keep'] = 'portal/Index/keep';
$route['hot'] = 'portal/Index/hot';
$route['card'] = 'portal/Index/card';
$route['search'] = 'portal/Index/search';
$route['search/(:num)'] = 'portal/Index/search/$1';
$route['search/(:num)/(:any)'] = 'portal/Index/search/$1/$2';

//用户
$route['register'] = "portal/User/register";
$route['doregister'] = "portal/User/doRegister";
$route['login']= "portal/User/login";
$route['dologin']= "portal/User/doLogin";
$route['verifyValue']= "portal/User/verifyValue";
$route['getValidation']= "portal/Common/getValidation"; //获取验证码
$route['forget']= "portal/User/forget";
$route['doforget']= "portal/User/doForget";
$route['resetpass/(:any)']= "portal/User/resetpass/$1";
$route['doresetpass']= "portal/User/doResetpass";

//用户中心
$route['uc'] = "portal/Ucenter/uc"; //管理界面
$route['uc/list'] = "portal/Ucenter/index"; //我的信息列表
$route['uc/logout'] = "portal/Ucenter/logout"; //注销登录
$route['uc/edit/(:num)'] = "portal/Ucenter/edit/$1"; //编辑信息
$route['uc/doedit/(:num)'] = "portal/Ucenter/doEdit/$1"; //编辑信息
$route['uc/top'] = "portal/Ucenter/top"; //置顶信息
$route['uc/post'] = "portal/Ucenter/post"; //发布信息
$route['uc/postsucc/(:num)'] = "portal/Ucenter/postsucc/$1"; //发布信息成功调用
$route['uc/dopost'] = "portal/Ucenter/doPost"; //发布信息
$route['uc/account'] = "portal/Ucenter/account"; //
$route['uc/updatepass'] = "portal/Ucenter/updatepass"; //修改密码
$route['uc/deletekeep'] = "portal/Ucenter/deleteKeep";//删除 信息
$route['uc/keep'] = "portal/Ucenter/keep"; //收藏
$route['uc/card'] = "portal/Ucenter/card"; //名片列表
$route['uc/deletecard'] = "portal/Ucenter/deleteCard";//删除 信息
$route['uc/getSmallCityList'] = "portal/Ucenter/getSmallCityList";
$route['uc/getCitySortList'] = "portal/Ucenter/getCitySortList";//加载城市下的类别

//快速发布名片
$route['fastpost'] = "portal/Index/fastpost";
$route['us/dopostcard'] = "portal/Index/dopostcard";
$route['us/uploadImg'] = "portal/Index/uploadImg";
//朋友
$route['userinfo/(:num)'] = "portal/Friend/userinfo/$1";//朋友的信息


/*新的首页网页路由*/

$route['detail2/(:num)'] = 'newportal/Index/detail/$1'; 
$route['index2/(:any)'] = 'newportal/Index/index/$1';
$route['search2'] = 'newportal/Index/search';

/*说说*/
$route['shuoshuo'] 					= 'newportal/Saysay/ListPost';//说说帖子列表
$route['shuoshuo/doPost'] 			= 'newportal/Saysay/PublishPost'; //发布说说
$route['shuoshuo/reply/(:num)'] 	= 'newportal/Saysay/ListReply/$1'; //查看回复
$route['shuoshuo/doReply/(:num)']	= 'newportal/Saysay/PublishReply/$1';//回复帖子
$route['shuoshuo/postinfo/(:num)']  = 'newportal/Saysay/PostDetail/$1'; //帖子详情
$route['shuoshuo/like/(:num)']		= 'newportal/Saysay/PostLike/$1'; //点赞

/*车源货源*/
$route['viewcar'] 					= 'newportal/CarGoods/ListCar';//查看车源
$route['viewgoods'] 				= 'newportal/CarGoods/ListGoods';//查看货源
$route['car/publish']				= 'newportal/CarGoods/PublishCar';//发布车源
$route['goods/publish']				= 'newportal/CarGoods/PublishGoods';//发布货源
$route['cg/num']					= 'newportal/CarGoods/Statistic'; //车货总数
$route['car/detail']				= 'newportal/CarGoods/detail_car';
$route['goods/detail']				= 'newportal/CarGoods/detail_goods';

/*用户注册*/
$route['user/telmsg']				= 'newportal/User/FetchTelMsg'; //获取手机短信
$route['user/checkmsg']				= 'newportal/User/CheckCaptcha'; //验证短信验证码
$route['user/doRegister']			= 'newportal/User/doRegister'; //注册

/* 定制路线 */
$route['user/route/car']			= 'newportal/User/get_car_route'; //获取路线
$route['user/route/goods']			= 'newportal/User/get_goods_route'; //获取路线
$route['user/doRoute/car']			= 'newportal/User/set_car_route'; //定制路线
$route['user/doRoute/goods']		= 'newportal/User/set_goods_route'; //定制路线
$route['user/updateRoute/car']		= 'newportal/User/update_car_route'; //更新路线
$route['user/updateRoute/goods']	= 'newportal/User/update_goods_route'; //更新路线
$route['user/delete/goods_route']	= 'newportal/User/delete_goods_route'; //删除路线
$route['user/delete/car_route']		= 'newportal/User/delete_car_route'; //删除路线
$route['user/delete/goods']			= 'newportal/User/delete_goods'; //删除路线
$route['user/delete/car']			= 'newportal/User/delete_car'; //删除路线

/* 环信聊天 */
$route['chat/test'] 				= 'newportal/Chat'; //环信测试页面
$route['chat/userinfo']				= 'newportal/Chat/get_user'; //根据username返回头像和昵称


/*我的名片*/
$route['user/mycard']				= 'newportal/User/my_card';//我的名片
$route['user/mycar']				= 'newportal/User/my_car'; //我的车源
$route['user/mygoods']				= 'newportal/User/my_goods'; //我的车源

/*发布名片*/
$route['user/bcard/publish']		= 'newportal/Bard/set_card';
$route['user/bcard/list']			= 'newportal/Bard/get_cards';

/*聊天记录*/
$route['user/chat/save']			= 'newportal/Chat/save_record';
$route['user/chat/query']			= 'newportal/Chat/query_record';
$route['user/chat/history']			= 'newportal/Chat/history';

/*微信注册*/
//$route['wechat/auth']				= 'newportal/WeChatCtl/getOauthRedirect'; //网页授权
//$route['wechat/authToken']			= 'newportal/WeChatCtl/getOauthAccessToken'; //网页授权之后获取用户信息并注册之
$route['wechat/sign'] 				= 'newportal/WeChatCtl/getJsSign';
$route['wechat/event']				= 'newportal/WeChatCtl/EventMessage';
$route['wechat/api']				= 'newportal/WeChatCtl/api';

/*页面访问2.0*/
$route['view/detail'] 				= 'newportal/View/ViewDetail';	
$route['view/list'] 				= 'newportal/View/ViewList';
$route['view/sspost'] 				= 'newportal/View/ViewShuoshuoPost'; 
$route['view/index']				= 'newportal/View/ViewIndex';

/*微信点击菜单后跳转路由*/
$route['redirect']					= 'newportal/Redirect/Index';

/* 支持名片 */
$route['page/support']				= 'newportal/Page/add_support';
$route['page/message']				= 'newportal/Page/leave_message';
$route['page/index']				= 'newportal/Page/index';
$route['page/run']					= 'newportal/Test/run';
$route['page/test']					= 'newportal/Test/test';

//首页传递参数的匹配
$route['(:any)'] 	= 'portal/Index/index/$1';  //首页,城市的参数


/*信息的刷新*/
$route['pushPage'] = 'portal/Common/pushPage';








