<header>		
	<section id="top_bar" style="text-align:center;height:47px;padding:5px 0px 0px 10px;">				
		<ul style="padding-top:5px;">
			<li style="width:20%;text-align:left;vertical-align:middle;"><img onclick="doHREF('<?php echo site_url()?>')" style="width:30px;height:30px;" src="<?php echo source_url()?>images/goback.jpg"/></li>
			<li style="width:56%;"><span  class='header_title_text'>用户管理中心</span></li>
			<li style="width:20%;"></li>
		</ul>
	</section>         
</header>

<content>
	<section style="text-align:center;padding:0px 10px 0px 10px;">
		<ul style="list-style:none;padding:0px;text-align:left;">
			<div id="delivery_info">
				<li style="border-bottom:0px;" onclick="doHREF('<?php echo site_url('uc/list')?>');" >
					<span style="font-weight:900;">我发布的名片</span>
					<img style='float:right' src='<?php echo source_url()?>images/jiantou.png'/>
				</li>
			</div>
			<div id="delivery_info">
				<li style="border-bottom:0px;" onclick="doHREF('<?php echo site_url('uc/card')?>');">
					<span style="font-weight:900;">我收到的名片</span>
					<img style='float:right' src='<?php echo source_url()?>images/jiantou.png'/>
				</li>
			</div>
			<div id="delivery_info">
				<li style="border-bottom:0px;" onclick="doHREF('<?php echo site_url('uc/keep')?>');">
					<span style="font-weight:900;">我收藏的名片</span>
					<img style='float:right' src='<?php echo source_url()?>images/jiantou.png'/>
				</li>
			</div>
			<div id="delivery_info">
				<li style="border-bottom:0px;" onclick="doHREF('<?php echo site_url('uc/account')?>');">
					<span style="font-weight:900;">我的账户信息</span>
					<img style='float:right' src='<?php echo source_url()?>images/jiantou.png'/>
				</li>
			</div>
		</ul>
	</section>
	</content>