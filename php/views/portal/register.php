<header>
		<section id="top_bar" style="text-align:center;height:47px;padding:5px 0px 0px 10px;">				
		<ul style="padding-top:5px;">
			<li style="width:20%;text-align:left;"><img onclick="javascript:history.back();" style="width:30px;height:30px;" src="<?php echo source_url()?>images/goback.jpg"></li>
			<li style="width:56%;vertical-align:30%;"><span  class='header_title_text'>注&nbsp;&nbsp;&nbsp;&nbsp;册</span></li>
			<li style="width:20%;"><ul><li style="float:right;position:relative;top:0px;right:15px;"><img src="<?php echo source_url()?>images/logon-top.jpg" class="header_button" onclick="doHREF('<?php echo site_url('login')?>')"><span style="position:relative;top:20px;left:5px;" class="white_12_label" onclick="doHREF('<?php echo site_url('login')?>');">登录</span></li></ul></li>
		</ul>
	</section>
</header>

<content>
	<section class="notice_bar" style='display:none;text-align:center;'></section>
	<section style="text-align:center;padding:0px 10px 0px 10px;">
             <form id="submit_form" action="" method="post">
              	<ul style="list-style:none;padding:0px;margin:0px 0px 10px 0px;text-align:left;">
               		<li>
		                <input id="phone" class="input_field"  type="text" name="phone" value="" placeholder="请输入手机号码"/>
	                </li>
	                <li>
		                <input id="nickname" class="input_field"  type="text" name="nickname" value="" placeholder="请输入昵称"/>
	                </li>
					  <li>
                		<input id="wexin" class="input_field" type="text" name="wexin" value="" placeholder="请输入微信号"/>
	                </li>
	                <li>
                		<input id="mail" class="input_field" type="email" name="mail" value="" placeholder="请输入邮箱（用于取回密码）"/>
	                </li>
	                <li>
		                <input id="password" class="input_field" type="password" name="password" value="" placeholder="请输入密码（不低于6位的字母和数字）"/>
	                </li>
	                <li>
		                <input id="verifycode" class="input_field" style="width:50%;" type="text" name="validate" placeholder="请输入验证码" value="">
                		<span class='captcha_box' style='display:inline-block;height:32px;position:relative; top:12px;'></span>
               		</li>
		            <li style="margin-top:15px;">
                		<div class="plain_button_orange" onclick='registerForm( this )'><a>确&nbsp;&nbsp;&nbsp;定</a></div>
		            </li>
                </ul>
             </form>	
             <a style="margin-bottom:10px;color:#377BA6;font-size:12px;text-decoration:underline;cursor:pointer;display:inline-block;" onclick="doHREF('<?php echo site_url('login')?>')">已经有查物流帐号？请登录</a>
	</section>
</content>
<script type='text/javascript'>
//验证码
function initCaptcha(){
	$.ajax({
		type: "POST",
		url: "<?php echo site_url('getValidation') ?>?"+Math.round(Math.random()*1000),
		dataType: "json",
		cache:false,
		success : function(res){
			if(res){
				$('.captcha_box').html(res.image);
				$('#refresh_captcha').attr('title','看不清？点击刷新').css('cursor','pointer').click(function(){
					initCaptcha();
				});
			}else{
				alert('验证码生成失败');
			}
		}
	});
	
}
initCaptcha();
//注册
function registerForm( e ){
	var phoneobj = $("#phone");
	var phone = $.trim( phoneobj.val() );
	var nicknameobj = $("#nickname");
	var nickname = $.trim( nicknameobj.val() );
	var mailobj = $("#mail");
	var mail = $.trim( mailobj.val() );
	var passwordobj = $("#password");
	var password = $.trim( passwordobj.val() );
	var verifycodeobj = $("#verifycode");
	var verifycode = $.trim( verifycodeobj.val() );
	var notice_bar = $(".notice_bar");
	var reg1 = /^[0-9]{11}$/;
	if( !reg1.test(phone) ){
		notice_bar.show().html('请填写正确的手机号码');
		return false;
	}
	if( nickname.length <2 ){
		notice_bar.show().html('昵称格式错误');
		return false;
	}
	
	var reg2 =  /^(.)+@(.)+(.)+(.)+/;
	if( mail && !reg2.test(mail) ){
		
		notice_bar.show().html('请填写正确的邮箱');
		return false;
	}
	if( password.length <6 ){
		notice_bar.show().html('密码格式不正确');
		return false;
	}
	if( verifycode.length !=4 ){
		notice_bar.show().html('验证码输入错误');
		return false;
	}
	
	notice_bar.hide();
	
	$.ajax({
		type: "POST",
		url: "<?php echo site_url('doregister')?>",
		dataType: "json",
		data:{phone:phone,mail:mail,password:password,vcaptcha:verifycode,nickname:nickname},
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