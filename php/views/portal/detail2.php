<!DOCTYPE>
<html>
<head>
<base href="<?php echo base_url();?>"/>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<meta http-equiv="Cache-control" content="no-cache">
<meta name="viewport" content="width=device-width,minimum-scale=1.0,maximum-scale=1.0,user-scalable=no">
<link rel="apple-touch-icon" href="<?php echo source_url() ?>images/wuliuicon.png"/>
<link charset="utf-8" type="text/css" rel="stylesheet" href="<?php echo source_url() ?>new/css/base.css?t=20131139"/>
<link charset="utf-8" type="text/css" rel="stylesheet" href="<?php echo source_url() ?>new/css/detail.css?t=20131139"/>
<script src="http://bdimg.share.baidu.com/static/api/js/share.js?v=89860593.js?cdnversion=403159"></script>
</head>
<body>
<script type="text/javascript" src="http://api.map.baidu.com/api?type=quick&amp;v=2.0&amp;ak=C9mkSR7qE9aiyguAAk1Q0ttY"></script>
<script type="text/javascript" src="http://api.map.baidu.com/getscript?type=quick&amp;file=api&amp;ak=C9mkSR7qE9aiyguAAk1Q0ttY&amp;t=20140109092002"></script>
<script type="text/javascript" src="http://api.map.baidu.com/getscript?type=quick&amp;file=feature&amp;ak=C9mkSR7qE9aiyguAAk1Q0ttY&amp;t=20140109092002"></script>
<header>
	<img src="<?php echo source_url() ?>new/img/detail/icon.png" /> 我的名片
</header>
<!-- 内容 -->
<content>
	<section class="one">
		<div class="tit"><b><?php echo $page['title']?></b></div>
		<div class="data">
			<div class="mobile"><img src="<?php echo source_url() ?>new/img/detail/mobile.png" /><?php echo $page['phone']?></div>
			<div class="location"><img src="<?php echo source_url() ?>new/img/detail/location.png" /><?php echo $page['address']; ?></div>
			<div class="explain"><b><?php echo $page['hot']; ?></b>推荐 <b><?php echo $page['view']; ?></b>浏览</div>
			<div class="pic"><img src="<?php echo $page['logo']; ?>" /></div>
		</div>
		<div class="card">
			<p><a onClick="recommend()">推荐名片 <span>◎</span></a></p>
			<p><a onclick="if(document.getElementById('bdsharebuttonbox').style.display == 'block'){ document.getElementById('bdsharebuttonbox').style.display = 'none'; }else{ document.getElementById('bdsharebuttonbox').style.display ='block'; }">分享名片 <span>◎</span></a></p>
			<p><a onclick="goToAddress('上海市松江区新桥镇茸华路1233号B区10-12库','【川域物流】上海至成都往返专线（四川全境）')">一键导航 <span>◎</span></a></p>
			<br class="cb"/>
		</div>
		<div class="bdsharebuttonbox" id='bdsharebuttonbox'>
				<a class="bds_more flt" data-cmd="more">分享：</a>
				<a class="bds_tqq frt" data-cmd="tqq" title="分享到腾讯微博">腾讯微博</a>
				<a class="bds_sqq frt" data-cmd="sqq" title="分享到QQ好友">QQ好友</a>
				<a class="bds_tsina frt" data-cmd="tsina" title="分享到新浪微博">微博</a>
				<a class="bds_qzone frt" data-cmd="qzone" title="分享到QQ空间">空间</a>
				<br class="cb"/>
		</div>
	</section>
	<section class="two">
		<div class="tit"><b>车货信息</b></div>
		<div class="data">
			<div class="from">
				<img src="<?php echo source_url()?>new/img/detail/from.png" />杭州 滨江
				<img src="<?php echo source_url()?>new/img/detail/to.png" />
				<img src="<?php echo source_url()?>new/img/detail/area.png" />北京 三里屯
			</div>
			<div class="company">杭州行风物流有限公司</div>
		</div>
		<dl class="card">
			<dt>货品信息  &nbsp;&nbsp;配件 36吨 25方</dt>
			<dt>所需车辆  &nbsp;&nbsp;需13米高栏车</dt>
			<dt>装货时间  &nbsp;&nbsp;今日下午<span>12:00</span>发货</dt>
			<dt>备注消息  &nbsp;&nbsp;货物需要直达，希望中途不要停车</dt>
		</dl>
	</section>
	<section class="three">
		<div class="tit"><b>名片详情</b></div>
		<div class="tit">
			名片详情名片详情名片详情名片详情名片详情名片详情
		</div>
		<div class="text">
			<div class="title">上海收货地址：</div>
			<b>上海市XXXX区X街</b> <br />
			电话：<b>11111111111</b> <br />
			电话：<b>11111111111</b> <br />
			手机：<b>11111111111</b> 任先生<br />
			手机：<b>11111111111</b> 任先生<br />
			邮箱：<b>1@11.com</b>
		</div>
	</section>
	<section class="four">
		<div class="tit"><b>图片信息</b></div>
		<dl class="pic">
			<dt><img src="<?php echo source_url()?>new/img/detail/test.png" /></dt>
			<dt><img src="<?php echo source_url()?>new/img/detail/test.png" /></dt>
			<dt><img src="<?php echo source_url()?>new/img/detail/test.png" /></dt>
			<dt><img src="<?php echo source_url()?>new/img/detail/test.png" /></dt>
		</dl>
	</section>
</content>
<script>
function goToAddress(address,title){
	var myGeo = new BMap.Geocoder();
	// 将地址解析结果显示在地图上,并调整地图视野
	myGeo.getPoint(address, function(point){
	  if (point) {
	   var lng=point.lng;
					var lat=point.lat;
					var url="http://api.map.baidu.com/marker?location="+lat+","+lng+"&title="+title+"&content="+address+"&output=html&appName=查物流";
					}else{
					var url="http://api.map.baidu.com/geocoder?address="+address+"&output=html";
					}
	window.location.href=url;
	}, "");
}
function recommend(){
	alert('推荐！');
}
</script>
</body>
</html>