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
<link charset="utf-8" type="text/css" rel="stylesheet" href="<?php echo source_url() ?>new/css/base.css?t=20131139"/>
<link charset="utf-8" type="text/css" rel="stylesheet" href="<?php echo source_url() ?>new/css/detail.css?t=20131139"/>

</head>
<body>
<header>
	<img src="<?php echo source_url()?>new/img/detail/icon.png" /> 我的名片
</header>
<!-- 内容 -->
<content>
	<section class="one">
		<div class="tit"><b><?php echo $page['title']?></b></div>
		<div class="data">
			<div class="mobile"><img src="<?php echo source_url()?>new/img/detail/mobile.png" /><?php echo $page['phone']?></div>
			<div class="location"><img src="<?php echo source_url()?>new/img/detail/location.png" /><?php echo $page['address']; ?></div>
			<div class="explain"><b><?php echo $page['hot']?></b>推荐 <b><?php echo $page['view']?></b>浏览</div>
			<div class="pic"><img src="<?php echo $page['logo']?>" /></div>
		</div>
		<div class="card">
			<p><a>推荐名片 ◎</a></p>
			<p><a>分享名片 ◎</a></p>
			<p><a>一键导航 ◎</a></p>
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
		<?php echo $page['content']?>++
			<?php 
			$content1 = "<p style='color:#FF8500;'>".$tip1."</p>";
			$content2 = "<p style='color:#FF8500;'>".$tip2."</p>";
			$content3 = "<p style='color:#FF8500;'>".$tip3."</p>";
			$content4 = "<p style='color:#FF8500;'>".$tip4."</p>";
			if($page['isagree']==1&&strpos($page['content'],'该专线未经查物流官方实地认证')==false){ ?>
			<?php 
			echo $page['content'];
			if($page['isMember']>0){
			echo $content4;
			}else if($page['is_refresh']>0){
			echo $content3;
			}else{
			echo $content2;
			}
			}else if($page['isagree']==0){
			  if(strpos($page['content'],'该专线未经查物流官方实地认证')==false){
			echo $page['content']."<br>";
			echo $content1;
			}else{
			   echo $page['content']."<br>";
			   }
			}
			?>
		<!--
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
		-->
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

<script src=""></script>
</body>
</html>