<!DOCTYPE html>
<html>
<head>
	<base href="<?php echo base_url();?>"/>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
	<meta content="text/html; charset=utf-8" http-equiv="Content-Type"/>
	<meta charset="utf-8"/>
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no"/>
	<meta name="apple-mobile-web-app-capable" content="yes"/>
	<meta name="apple-mobile-web-app-status-bar-style" content="black"/>
	<meta name="format-detection" content="telephone=no"/>
	<meta name="apple-mobile-web-app-capable" content="yes"/>
	<link rel="apple-touch-icon" href="<?php echo source_url() ?>images/wuliuicon.png"/>
	<link charset="utf-8" type="text/css" rel="stylesheet" href="<?php echo source_url() ?>/new/css/base.css?t=20131139"/>
  <link charset="utf-8" type="text/css" rel="stylesheet" href="<?php echo source_url() ?>/new/css/detail.css?t=20131139"/>
  <link charset="utf-8" type="text/css" rel="stylesheet" href="<?php echo source_url() ?>/new/css/index.css?t=20131139"/>
  <link charset="utf-8" type="text/css" rel="stylesheet" href="<?php echo source_url() ?>/new/css/list.css?t=20131139"/>
  <link charset="utf-8" type="text/css" rel="stylesheet" href="<?php echo source_url() ?>/new/css/pub.css?t=20131139"/>
  <link charset="utf-8" type="text/css" rel="stylesheet" href="<?php echo source_url() ?>/new/css/where.css?t=20131139"/>
	<script type='text/javascript' src='<?php echo source_url()?>jquery-1.8.2.min.js'></script>
	<script type="text/javascript"  src="<?php echo source_url() ?>js/Comm.js" ></script>
	<script type="text/javascript"  src="<?php echo source_url() ?>js/functions.js?t=20131127" ></script>
	<script src="http://res.wx.qq.com/open/js/jweixin-1.0.0.js"></script>
	<script type="text/javascript" charset="utf-8">
	addEventListener("load", function() { 
	 setTimeout(hideURLbar, 0);
	 }, false);
	  /*
   * 注意：
   * 1. 所有的JS接口只能在公众号绑定的域名下调用，公众号开发者需要先登录微信公众平台进入“公众号设置”的“功能设置”里填写“JS接口安全域名”。
   * 2. 如果发现在 Android 不能分享自定义内容，请到官网下载最新的包覆盖安装，Android 自定义分享接口需升级至 6.0.2.58 版本及以上。
   * 3. 常见问题及完整 JS-SDK 文档地址：http://mp.weixin.qq.com/wiki/7/aaa137b55fb2e0456bf8dd9148dd613f.html
   *
   * 开发中遇到问题详见文档“附录5-常见错误及解决办法”解决，如仍未能解决可通过以下渠道反馈：
   * 邮箱地址：weixin-open@qq.com
   * 邮件主题：【微信JS-SDK反馈】具体问题
   * 邮件内容说明：用简明的语言描述问题所在，并交代清楚遇到该问题的场景，可附上截屏图片，微信团队会尽快处理你的反馈。
   */
  wx.config({
    debug: false,
    appId: '<?php echo $signPackage["appId"];?>',
    timestamp: <?php echo $signPackage["timestamp"];?>,
    nonceStr: '<?php echo $signPackage["nonceStr"];?>',
    signature: '<?php echo $signPackage["signature"];?>',
    jsApiList: [
      // 所有要调用的 API 都要加到这个列表中
	  "onMenuShareTimeline","onMenuShareAppMessage","onMenuShareQQ","onMenuShareWeibo"
    ]
  });
  //微信分享
	var shareTitle="<?php echo $meta['title'];?>";
	var descContent="<?php echo $meta['description']?>";
	var imgUrl = "";
    var lineLink = "";
	</script>
	<meta name="Keywords" content="<?php echo $meta['words']?>"/>
	<meta name="Description" content="<?php echo $meta['description']?>"/>
	<meta name="author" content="<?php echo $meta['author']?>"/>
	<title><?php echo $meta['title']?></title>		
</head>
<body>

	<?php echo $main_body; ?>

	<footer>
		<!--<section style="padding:10px 0px 10px 0px;text-align:center; border-top: 1px #ededed solid;">
            <?php //if(!empty($serviceList)){ 
            	//$count = count($serviceList);
            	//echo "<ul style='margin-top:10px; '>";
            	//foreach( $serviceList as $k=>$v){
            		//echo '<li style="width:'.floor(1/$count*100).'%'.';"><a href="http://wpa.qq.com/msgrd?v=3&amp;uin='.$v['qq'].'&amp;site=qq&amp;menu=yes" target="_blank"><img style="vertical-align: top;" alt="" src="http://wpa.qq.com/pa?p=2:'.$v['qq'].':41"></a></li>';
            	//}
            	//echo "</ul>";
            //}?>
        </section>
        -->
		<!--
		<section style="padding:10px 0px 10px 0px;text-align:center;line-height:25px; height:25px; border-top: 1px #ededed solid;">
            <ul>
             	<li style="width:45%;"><a href="http://m.chawuliu.com"><span class="version_button">触屏版</span></a></li>
             	<li style="width:45%;"><a href="http://www.chawuliu.com"><span class="version_button">电脑版</span></a></li>
            </ul>  
        </section> -->
		<?php if(!isset($bottomShow)||$bottomShow!=2){?>
        <section>
           	<div class="plain_bar" style="padding:5px 0px 5px 0px;">	
            	<span>全国统一客服热线：</span>
            	<a style="display:inline-block;" href="tel:400-800-7756"><span class="blue_12_label">400-800-7756</span></a>
				<span><br/>© 2015 查物流版权所有<br/>
				<!--	<a style="color:#377ba6" href="<?php echo site_url('fastpost')?>" >
	  <img style="width:100%;margin-top:10px;" src="<?php echo source_url() ?>/images/bottom_fabu.jpg"/>
				</a>-->
				<a style="color:#377ba6" href="http://mp.weixin.qq.com/s?__biz=MjM5OTM3OTQ2MQ==&mid=204346358&idx=1&sn=b
				821194b67fc4e6a702844735cc39222#rd" target="_blank">
	  <img style="width:100%;margin-top:10px;" src="<?php echo source_url() ?>/images/bottom_guanzhu.jpg"/>
				</a>
			
				</span>			 
			</div>
       	</section>
		<?php } ?>
   </footer>
   <?php require_once 'cs.php';echo '<img src="'._cnzzTrackPageView(1000314444).'" width="0" height="0"/>';?>
   <script type='text/javascript'>
  /* $(function(){
		var ajax_get;		//ajax的get请求
		//刷新信息
		function pushPage(){
			if(ajax_get){ajax_get.abort();}
			ajax_get = $.getJSON('<?php echo site_url('pushPage') ?>',{},function(data){
				
			});
		}
		setInterval(pushPage,5000);
	});
	*/
	wx.ready(function () {
    // 在这里调用 API
	   weixin(wxData.title,wxData.link,wxData.imgUrl,wxData.desc);
    });
	　var wxData = {
        'imgUrl': imgUrl,
        'link' : lineLink,
        'desc' : descContent,
        'title' : shareTitle
    };
	var weixin = function (title,link,imgurl,desc){
        wx.ready(function () {
            wx.onMenuShareTimeline({
                title: title,
                link: link,
                imgUrl: imgurl
            });
            wx.onMenuShareAppMessage({
                title: title,
                desc: desc,
                link: link,
                imgUrl: imgurl
            });
            wx.onMenuShareQQ({
                title: title,
                desc: desc,
                link: link,
                imgUrl: imgurl
            });
            wx.onMenuShareWeibo({
                title: title,
                desc: desc,
                link: link,
                imgUrl: imgurl
            });
            obj.sound();
        });
    };
   </script>
</body>
</html>