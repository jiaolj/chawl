<header>		
	<section id="top_bar" style="text-align:center;height:47px;padding:5px 0px 0px 10px;">				
		<ul style="padding-top:5px;">
			<li style="width:20%;text-align:left;"><img onclick="doHREF('<?php echo site_url('uc')?>')" style="width:30px;height:30px;" src="<?php echo source_url()?>images/goback.jpg"/></li>
			<li style="width:56%;vertical-align:30%;"><span class='header_title_text'>管理我的信息</span></li>
			<li style="width:20%;"></li>
		</ul>
	</section>         
</header>

<content>
	<section class="notice_bar" style='display:none;text-align:center;'></section>
	<section style="text-align:center;padding:0px 10px 0px 10px;">
		<div id="lr_form_left" style="display:block;width:100%;">
		<form id="submit_form_left" action="" method="post">
			<ul style="list-style:none;padding:0px;margin:0px 0px 10px 0px;text-align:left;">
				<li>
					<div class='input_field' style='color:#000;font-size:16px;font-weight:bold; padding-top:8px;padding-left:5px;'><span style='color:red;'><?php if( $user['isMember']>0 ){ echo '【认证会员】'; }else{ echo '【普通会员】';}?></span></div>
				</li>
				<li>
					<input id="phone" class="input_field"  type="text" name="phone" value="<?php echo $user['phone']?>" placeholder="输入手机号码"/>
				</li>
				<li>
					<input id="wexin" class="input_field"  type="text" name="wexin" value="<?php echo $user['wexin']?>" placeholder="输入微信号"/>
				</li>
				<li>
					<input id="mail" class="input_field"  type="text" name="mail" value="<?php echo $user['mail']?>" placeholder="输入邮箱号码"/>
				</li>
				<li>
					<input id="nickname" class="input_field"  type="text" name="nickname" value="<?php echo $user['nickname']?>" placeholder="输入昵称"/>
				</li>
				<li>
					<input id="oldpassword" class="input_field"  type="password" name="oldpassword" value="" placeholder="输入旧密码"/>
				</li>
				<li>
					<input id="password2" class="input_field"  type="password" name="password2" value="" placeholder="输入新密码（不低于6位的字母和数字）"/>
				</li>
				<li style="margin-top:15px;">
					<div class="plain_button_orange" ><a onclick='accountClick( this )'>确&nbsp;&nbsp;&nbsp;定</a></div>
				</li>
			</ul>
		</form>	
		</div>
	</section>
</content>
<script type='text/javascript'>
	function accountClick( e ){
		var oldpasswordobj = $("#oldpassword");
		var password2obj = $("#password2");
		var notice_bar = $(".notice_bar");
		var nicknameobj = $("#nickname");
		var phoneobj = $("#phone");
		var mailobj = $("#mail");
		var oldpassword = $.trim( oldpasswordobj.val() );
		var password2 = $.trim( password2obj.val() );
		var nickname = $.trim( nicknameobj.val() );
		var phone = $.trim( phoneobj.val() );
		var mail = $.trim( mailobj.val() );
		var wexin = $.trim( $("#wexin").val() );
		if( (password2 || oldpassword) && (oldpassword.length <1 ) ){
			notice_bar.show().html('密码格式不正确');
			return false;
		}
		if( (password2 || oldpassword) && ( password2.length < 6 ) ){
			notice_bar.show().html('密码格式不正确');
			return false;
		}
			
		if( nickname.length<2 ){
			notice_bar.show().html('昵称格式不正确');
			return false;
		}
		if( phone.length<11 ){
			notice_bar.show().html('手机号码格式不正确');
			return false;
		}
		if( mail.length<8 ){
			//notice_bar.show().html('邮箱格式不正确');
			//return false;
		}
		notice_bar.show().html('处理中...');
		$.ajax({
			type: "POST",
			url: "<?php echo site_url('uc/updatepass')?>",
			dataType: "json",
			data:{oldpassword:oldpassword,password:password2,nickname:nickname,mail:mail,phone:phone,wexin:wexin},
			cache:false,
			success : function(res){
				if(res.status != 'n'){
					notice_bar.show().html('保存信息成功');
					oldpasswordobj.val('');
					password2obj.val('');
				}else{
					notice_bar.show().html( res.info );
				}
			}
		});

	}
	$(function(){
		$('input[type=password]').focus(function(){
			$(".notice_bar").hide();
		});
	});
</script>