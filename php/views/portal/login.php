<header>
	<section id="top_bar" style="text-align:center;height:47px;padding:5px 0px 0px 10px;">				
		<ul style="padding-top:5px;">
			<li style="width:20%;text-align:left;"><img onclick="javascript:history.back();" style="width:30px;height:30px;" src="<?php echo source_url()?>images/goback.jpg"></li>
			<li style="width:56%;vertical-align:30%;"><span  class='header_title_text'>登&nbsp;&nbsp;&nbsp;&nbsp;录</span></li>
			<li style="width:20%;">
				<ul>
					<li style="float:right;position:relative;top:0px;right:15px;"><img src="<?php echo source_url()?>images/register-top.jpg" class="header_button" onclick="doHREF('<?php echo site_url('register')?>')"><span style="position:relative;top:20px;left:5px;" class="white_12_label" onclick="doHREF('<?php echo site_url('register')?>');">注册</span></li>
				</ul>
			</li>
		</ul>
	</section>
</header>
<content>
	<section class="notice_bar" style='display:none;text-align:center;'></section>
    <section style="padding-top:10px;solid;text-align:center;">
		<div id="lr_form_left" style="display:block;width:100%;">
		<form id="submit_form_left" action="" method="post">
		<ul style="list-style:none;padding:0px;margin:0px 0px 10px 0px;text-align:left;">
			<li>
				<input id="phone" class="input_field"  type="text" name="phone" value="" placeholder="请输入手机号或邮箱"/>
			</li>
			<li>
				<input id="password" class="input_field" type="password" name="password" value="" placeholder="请输入密码"/> 
			</li>
			<li style="margin-top:15px;">
					<div class="plain_button_orange"  ><a onclick="window.top.loginForm( this )" >确&nbsp;&nbsp;&nbsp;定</a></div>
				</li>
			</ul>
		</form>	
		</div>
    	<a style="color:#377BA6;font-size:12px;text-decoration:underline;cursor:pointer;display:inline-block; margin-bottom:10px;" onclick="doHREF('<?php echo site_url('forget')?>')">忘记密码?</a> &nbsp; <a style="color:#377BA6;font-size:12px;text-decoration:underline;cursor:pointer;display:inline-block; margin-bottom:10px;" onclick="doHREF('<?php echo site_url('register')?>')">还没有查物流帐号？请注册</a>                		
	</section>
</content>
<script type='text/javascript'>
//登录
function loginForm( e ){

	var phoneobj = $("#phone");
	var phone = $.trim( phoneobj.val() );
	var passwordobj = $("#password");
	var password = $.trim( passwordobj.val() );
	var notice_bar = $(".notice_bar");
	if( !phone ){
		notice_bar.show().html('手机或邮箱不能为空');
		return false;
	}
	if( !password){
		
		notice_bar.show().html('密码不能为空');
		return false;
	}
	notice_bar.hide();
	
	$.ajax({
		type: "POST",
		url: "<?php echo site_url('dologin')?>",
		dataType: "json",
		data:{phone:phone,password:password},
		cache:false,
		success : function(res){
			if(res.status != 'n'){
				window.location.href = "<?php echo base_url(); ?>";
			}else{
				notice_bar.show().html(res.info);
			}
		}
	});
	
}
</script>