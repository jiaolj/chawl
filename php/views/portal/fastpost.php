
<header>
		<section id="top_bar" style="text-align:center;height:47px;padding:5px 0px 0px 10px;">				
		<ul style="padding-top:5px;">
			<li style="width:20%;text-align:left;"><a href="<?php echo site_url()?>"><img  style="width:30px;height:30px;" src="<?php echo source_url()?>images/back_new.jpg"></a></li>
			<li style="width:56%;vertical-align:30%;"><span  class='header_title_text'>快速发布名片</span></li>
			<li style="width:20%;">
			<ul>
			<li style="float:right;position:relative;top:0px;right:15px;">

			</li>
			</ul>
			</li>
		</ul>
	</section>
</header>
<content>
	<section class="notice_bar" style='display:none;text-align:center;'></section>
	<section style="text-align:center;padding:0px 10px 0px 10px;">
		<div id="lr_form_left" style="display:block;width:100%;">
		<form id="submit_form_left" action="<?php echo site_url('us/dopostcard') ?>" method="post"  enctype="multipart/form-data">
			<ul style="list-style:none;padding:0px;margin:0px 0px 10px 0px;text-align:left;">
				<li>
					<input id="profile" class="input_field" val="公司简称"  type="text" name="profile" required="required" placeholder="输入公司简称，格式：德邦物流"/>
				</li>
				<li>
					<input id="startingcity" class="input_field" val="出发城市" type="text" required="required" name="startingcity"  placeholder="输入出发城市，格式：上海"/>
				</li>
				<li>
					<input id="directroute" class="input_field" val="直达路线"  type="text" required="required" name="directroute" placeholder="输入直达线路，格式：广州、深圳专线"/>
				</li>
				<li>
					<input id="phone" class="input_field" val="联系电话"  type="text" required="required" name="phone" placeholder="输入联系电话，格式：021-56565656"/>
				</li>
				<li>
					<input id="address" class="input_field"val="收货地址"   type="text" required="required" name="address" placeholder="输入收货地址，格式：XX区XX路XX号"/>
				</li>
 <style>
	#logofile{ position:absolute; z-index:12; filter:alpha(opacity:0); opacity:0;}
	#filepositive{ position:absolute; z-index:12; filter:alpha(opacity:0); opacity:0;}
	#fileopposite{ position:absolute; z-index:12; filter:alpha(opacity:0); opacity:0;}
</style>
				
				<li>
					<input id="chief_name" class="input_field" val="负责人姓名" required="required" type="text" name="chief_name" placeholder="输入负责人姓名（必填，需认证，不公开）"/>
				</li>
				<li>
					<input id="chief_phone" class="input_field" val="负责人手机" required="required" type="text" name="chief_phone" placeholder="输入负责人手机（必填，需认证，不公开）"/>
				</li>
				<li>
					<input id="chief_wechat"  class="input_field" val="负责人微信"   type="text" name="chief_wechat" placeholder="输入负责人微信（必填，需认证，不公开）"/>
				</li>
				<li style="margin-top:5px;overflow: hidden;">
				<div style="float:left;position:relative">
					<div id="preview" class="newfilepreview" >
					<img src="<?php echo source_url()?>images/xpl_add_normal.png" >
					</div>
					<div class="newupbtn">
					
					上传门头图片
					</div>
					<input type="file" class="newfilehiden"   onchange="preview(this,'preview')"  id="logofile" name="logo" placeholder="上传门头照片"/>
					</div>
					
					<div style="float:left;margin-left:10px;position:relative">
					<div id="card_filepositive" class="newfilepreview">
					<img src="<?php echo source_url()?>images/xpl_add_normal.png" >
					
					</div>
					<div class="newupbtn ">
					上传名片正面
					</div>
					<input type="file" class="newfilehiden"   onchange="zpicture(this)" id="filepositive" name="card_positive" placeholder="上传名片正面"/>
					
					</div>
					<div style="float:left;margin-left:10px;position:relative">
					<div id="card_fileopposite" class="newfilepreview">
					<img src="<?php echo source_url()?>images/xpl_add_normal.png" >
					
					</div>
					<div class="newupbtn">
					
					上传名片反面
					</div>
					<input class="newfilehiden" type="file"  onchange="fopposite(this)"  id="fileopposite"  name="card_opposite" placeholder="上传名片反面"/>
					
                 </div>
				</li>
				<li style="margin-top:5px;overflow: hidden;color:#cccccc;font-size:14px;">
					（图片格式：手机横拍大小，高500，宽900）
				</li>
				<li style="margin-top:5px;overflow: hidden;" id="otherfilelist">
				    <div style="float:left;position:relative">
					<div id="previewother1" class="newfilepreview" >
					<img src="<?php echo source_url()?>images/xpl_add_normal.png" >
					</div>
					<div class="newupbtn">
					上传其他照片
					</div>
					<input type="file" class="newfilehiden"   onchange="preview(this,'previewother1')"  id="logofile" name="otherphoto1" placeholder="上传其他照片"/>
					</div>
					
					
				</li>
				<li style="margin-top:5px;overflow: hidden;color:#cccccc;font-size:14px;">
					（图片格式：手机横拍大小，高500，宽900）
				</li>
				<li style="margin-top:15px;">
					<div class="plain_button_orange" style="background: #fe9417 none repeat scroll 0 0;border-radius: 5px;color: #ffffff;display: block;font-size: 16px;font-weight: 900;height: 100%;width: 100%;">
						 <input id="subtype" style="background: none; border: none; color: #fff;font-size: 20px;width: 100%;height: 100%;display: none;" type="submit" value="确认无误，点击发布！"/>
						  <input id="buttype" style="background: none; border: none; color: #fff;font-size: 20px;width: 100%;height: 100%;" type="button"  value="确认无误，点击发布！"/>
					</div>
				</li>
			</ul>
		</form>	
		</div>
	</section>
</content>
<iframe style="display:none" name='hidden_frame' id="hidden_frame"></iframe>
<script type='text/javascript'>
     shareTitle="<?php echo $fastsharetitle;?>";
	 descContent="<?php echo $fastsharedes;?>";
	 imgUrl = "http://m.chawuliu.com/<?php echo source_url()?>images/fastshare_icon.jpg";
     lineLink = "";
	 document.title=shareTitle;
//门头照片
	function preview(file,previewid) {
		 var prevDiv = document.getElementById(previewid);
		 if (file.files && file.files[0]) {
			 var reader = new FileReader();
			 reader.onload = function(evt){
				prevDiv.innerHTML = '<img style="width: 100%;height: 100%;" src="' + evt.target.result + '" />';
				$("#"+previewid).css("display","");
			 }  
			 reader.readAsDataURL(file.files[0]);
		 }
		 if(previewid!="preview"){
		 addMoreFiles();
		 }
	}	
//名片正面
	function zpicture(file){
		 var prevDiv = document.getElementById('card_filepositive');
	
		 if (file.files && file.files[0]) {
			 var reader = new FileReader();
			 reader.onload = function(evt){
				prevDiv.innerHTML = '<img style="width: 100%;height: 100%;" src="' + evt.target.result + '" />';
				$("#card_filepositive").css("display","");
			 }  
			 reader.readAsDataURL(file.files[0]);
		 }
	}
//名片反面
	function fopposite(file){
		 var prevDiv = document.getElementById('card_fileopposite');
			
		 if (file.files && file.files[0]) {
			 var reader = new FileReader();
			 reader.onload = function(evt){
				prevDiv.innerHTML = '<img style="width: 100%;height: 100%;" src="' + evt.target.result + '" />';
				$("#card_fileopposite").css("display","");
			 }  
			 reader.readAsDataURL(file.files[0]);
		 }
	}


	
	$(function(){
		var notice_bar = $(".notice_bar");
		$(".input_field").blur(function(){
			var profile = $.trim( $(this).val() );//简介
			var conval=$(this).attr("val");
				if( profile.length<1){
					notice_bar.show().html(conval+'格式不正确');
					$("#subtype").css("display","none");
					$("#buttype").css("display","");
				}else{
					$(".notice_bar").css("display","none");
					$("#buttype").css("display","none");
					$("#subtype").css("display","");
				}
			});
		
	});
	var filecount=1;
	function addMoreFiles(){
	filecount++;
	if(filecount==7){return;}
	var marginleft="10px;";
	if(filecount==4)marginleft="0px;";
	var addString=" <div style=\"float:left;position:relative;margin-left:"+marginleft+"\">";
addString+="<div id=\"previewother"+filecount+"\" class=\"newfilepreview\" >";
addString+="<img src=\"<?php echo source_url()?>images/xpl_add_normal.png\" >";
addString+="</div>";
addString+="<div class=\"newupbtn\">";
addString+="上传其他照片";
addString+="</div>";
addString+="<input type=\"file\" class=\"newfilehiden\"   onchange=\"preview(this,\'previewother"+filecount+"\')\"  id=\"logofile\" name=\"otherphoto"+filecount+"\" placeholder=\"上传其他照片\"/>";
addString+="</div>";
if(filecount==4){
addString+="<br>";
}
$("#otherfilelist").append(addString);
	}
</script>