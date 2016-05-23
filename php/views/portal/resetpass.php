<header>
		<section id="top_bar" style="text-align:center;height:47px;padding:5px 0px 0px 10px;">				
		<ul style="padding-top:5px;">
			<li style="width:20%;text-align:left;"><img onclick="javascript:history.back();" style="width:30px;height:30px;" src="<?php echo source_url()?>images/goback.jpg"></li>
			<li style="width:56%;vertical-align:30%;"><span  class='header_title_text'>设置新密码</span></li>
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
		                <input id="randpass" class="input_field"  type="text" name="randpass" value="" placeholder="邮箱验证码"/>
	                </li>
	                <li>
                		<input id="password" class="input_field" type="password" name="password" value="" placeholder="输入新密码（不低于6位的字母和数字）"/>
	                </li>
		            <li style="margin-top:15px;">
		            	<input type="hidden" value="<?php echo $code; ?>" name="code" id='code'>
                		<div class="plain_button_orange" onclick='resetPassForm( this )'><a>确&nbsp;&nbsp;&nbsp;定</a></div>
		            </li>
                </ul>
             </form>
	</section>
</content>
<script type='text/javascript'>
//重设密码
function resetPassForm( e ){
	var randpassobj = $("#randpass");
	var randpass = $.trim( randpassobj.val() );
	var passwordobj = $("#password");
	var password = $.trim( passwordobj.val() );
	var notice_bar = $(".notice_bar");
	var code = $("#code").val();
	if( !randpass ){
		notice_bar.show().html('验证码错误');
		return false;
	}
	if( password.length <6 ){
		notice_bar.show().html('密码格式不正确');
		return false;
	}
	notice_bar.show().html('处理中...');
	$.ajax({
		type: "POST",
		url: "<?php echo site_url('doresetpass')?>",
		dataType: "json",
		data:{randpass:randpass,password:password,code:code},
		cache:false,
		success : function(res){
			if(res.status != 'n'){
				window.location.href = "<?php echo site_url('login'); ?>";
			}else{
				notice_bar.show().html(res.info);
			}
		}
	});
	
}
</script>