<link rel="stylesheet" href="resource/kindeditor-4.1.10/themes/default/default.css" />
<!--<link rel="stylesheet" href="resource/kindeditor-4.1.10/plugins/code/prettify.css" />
--><script charset="utf-8" src="resource/kindeditor-4.1.10/kindeditor.js"></script>
<script charset="utf-8" src="resource/kindeditor-4.1.10/lang/zh_CN.js"></script><!--
<script charset="utf-8" src="resource/kindeditor-4.1.10/plugins/code/prettify.js"></script>
--><!-- 内容  -->
<header>		
	<section id="top_bar" style="text-align:center;height:47px;padding:5px 0px 0px 10px;">				
		<ul style="padding-top:5px;">
			<li style="width:20%;text-align:left;"><img onclick="doHREF('<?php echo site_url('uc/list')?>')" style="width:30px;height:30px;" src="<?php echo source_url()?>images/goback.jpg"/></li>
			<li style="width:56%;vertical-align:30%;"><span  class='header_title_text'>正在编辑信息</span></li>
			<li style="width:20%;"></li>
		</ul>
	</section>         
</header>

<content>
	<?php if( !empty( $msg ) ){ ?>
	<section class="notice_bar" style='text-align:center;'><?php echo  $msg; ?></section>
	<?php } ?>
	<section style="text-align:center;padding:0px 10px 0px 10px;">	
		<div id="lr_form_left" style="display:block;width:100%;">
		<form id="submit_form_left" action="<?php echo site_url("uc/doedit").'/'.$page['id']; ?>" method="post" enctype="multipart/form-data">
			<ul style="list-style:none;padding:0px;margin:0px 0px 10px 0px;text-align:left;">
				<li>
					<input id="title" class="input_field"  type="text" name="title" value="<?php echo $page['title']?>" placeholder="信息标题"/>
				</li>
				<li class='city_tr_box' >
					<div class='input_field city_list'>
						<select name='city[]'  onchange="smallCity()" id='smallCitySelect'  style='height:30px; margin-top:4px; margin-left:5px; order:1px #ededed solid;'>
                   			<option value=''>请选择所在地区</option>
                    		<?php foreach ($cityList as $k=>$city){ ?>
                    		<option value='<?php echo $city['id']?>' <?php if( strpos($page['city_id'], ','.$city['id'].',') !== false ) echo "selected='selected'"; ?>><?php echo $city['name']?></option>
                    		<?php } ?>
                   		</select>
                   	</div>
				</li>
				<!-- 分类的填充 -->
				<li>
					<input id="phone" class="input_field"  type="text" name="phone" value="<?php echo $page['phone']?>" placeholder="联系电话"/>
				</li>
				<li>
					<input id="address" class="input_field"  type="text" name="address" value="<?php echo $page['address']?>" placeholder="联系地址"/>
				</li>
				<li>
					<input id="logo" class="input_field"  type="file" name="logo" value="" placeholder="名片正面" style="padding-left:5px;padding-top:8px;"/>
				</li>
				<li>
					<div style='margin-top:10px;'>
					<textarea class="textarea" name="content" id="content" placeholder="详细详情" style=''><?php echo $page['content']?></textarea>	
					</div>
				</li>
				<li style="margin-top:15px;">
					<button type="submit" class='plain_button_orange'><span>确&nbsp;&nbsp;&nbsp;定</span></button>
				</li>
			</ul>
		</form>	
		</div>
	</section>
</content>
<script type='text/javascript'>
		var city_id_index = '<?php echo $page['city_id']?>';
		var sort_id_index = '<?php echo $page['sort_id']; ?>';
		var province_id_index = 0;
		//显示和隐藏
		function sortChange( e ){
			var sort_tr_item = $("#sort_tr_item"); //tr显示与隐藏
			var sort_id = $(e).val();
			if( sort_id!="" &&　sort_id >0 ){
				sort_tr_item.show();
				$(".sort_item_list").hide();
				$('#sort_item_list'+sort_id).show();
			}else{
				sort_tr_item.hide();
			}			
		}
		function smallCity(){
			province_id_index =  $("#smallCitySelect").val();
			if(  province_id_index != "" && province_id_index>0 ){
				$.post("<?php echo site_url('uc/getSmallCityList') ; ?>",{province_id_index:province_id_index,city_id_index:','+city_id_index+','},function(res){
					$('.getCitySortList').remove();
					$('.city_list').append(res);
					getCitySortList();
				});	
			}else{
				$('.getCitySortList').remove();
			}
		}
		smallCity( );
		function getCitySortList(  ){
			city_id_index  = $('.getCitySortList').val();
			$.post("<?php echo site_url('uc/getCitySortList') ; ?>",{ city_id_index:city_id_index,sort_id_index:sort_id_index },function(res){
				$("#sort_select_tr,#sort_tr_item").remove();
				$(".city_tr_box").after(res);
			});	
		}
		$(function(){
			$('input[type=text]').focus(function(){
				$(".notice_bar").hide();
			});
		});
		KindEditor.ready(function(K) {
			var editor1 = K.create('#content', {
				uploadJson : "<?php echo base_url(); ?>resource/kindeditor-4.1.10/php/upload_json.php",
				fileManagerJson : "<?php echo base_url(); ?>resource/kindeditor-4.1.10/php/file_manager_json.php",
				allowFileManager : true,	
				width : '100%',
				height:500,
				filterMode : false, //不会过滤HTML代码
		        resizeMode : 1, //编辑器只能调整高度
				afterCreate : function() {
					var self = this;
					K.ctrl(document, 13, function() {
						self.sync();
						K("#signup-submit").submit();
					});
					K.ctrl(self.edit.doc, 13, function() {
					     self.sync();
					     K("#signup-submit").submit();
					});
				},
				afterChange: function (e) { 
					this.sync();
				},
				items : [
							'forecolor', 'hilitecolor', 'bold', 'italic', 'underline',
							'removeformat', '|', 'justifyleft', 'justifycenter', 'justifyright']
			});
		});
	</script>