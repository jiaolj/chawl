<header>		
	<section id="top_bar" style="text-align:center;height:47px;padding:5px 0px 0px 10px;">				
		<ul style="padding-top:5px;">
			<li style="width:20%;text-align:left;"><img onclick="doHREF('<?php echo site_url('uc')?>')" style="width:30px;height:30px;" src="<?php echo source_url()?>images/goback.jpg"/></li>
			<li style="width:56%;vertical-align:30%;"><span  class='header_title_text'>我收藏的名片</span></li>
			<li style="width:20%;"></li>
		</ul>
	</section>         
</header>

<content>
	<form id="data-form" method="post">
	
	<?php if( !empty($list) ){ ?>
	<section style="text-align:center;" class="goods_list_item">
		<ul>
			<li style="width:auto;vertical-align:top;text-align:left;">
				<div style='width:113px;height:1px;background:transparent;'></div>
				<span style='font-size:14px;margin:0;padding:5px 0;font-weight:bold;color:#ed6d00;'><label><input type='checkbox' class='selected-btu'/>全选</label></span>&nbsp;
				<span style='font-size:14px;margin:0;padding:5px 0;font-weight:bold;color:#ed6d00;' class='delete-btu'>确定取消</span>
			</li>
			<li style="text-align:left;width:62%;"></li>
		</ul>
	</section>
	<?php	foreach( $list as $k=>$v){?>
	
	<section style="text-align:center;" class="goods_list_item">
		<ul>
			<li style="width:auto;vertical-align:top;text-align:left;position:relative;">
				<img class="goods_list_image" src="<?php echo $v['logo']?>" onclick="doHREF('<?php echo site_url('detail').'/'.$v['page_id'];?>')" />
				<div>
					<span style='font-size:14px;margin:0;padding:5px 0;font-weight:bold;color:#ed6d00;'><label><input type='checkbox' name='kid[]' class='kid' value='<?php echo $v['id']?>'/>取消收藏</label></span>
				</div>
				<?php if($v['isMember']>0){ ?>
    			<img src="<?php echo source_url()?>images/vip.png" style='position:absolute;left:-3px;top:-3px;'/>
    			<?php }?>
			</li>
			<li style="text-align:left;width:62%;" >
				<ul>
   					<li style='height:32px;overflow:hidden;' onclick="doHREF('<?php echo site_url('detail').'/'.$v['page_id']?>');">
						<span class="black_14_b_label"><?php echo $v['title']?></span>
					</li>
					<li style='display:block;position:relative;'>
						<span style='position: absolute;top:0;right:0px;'><em style='color:#ed6d00;font-weight:bold;font-style: normal;'><?php echo $v['hot']?></em>人推荐</span>
						<a  style='display:block; height:18px;overflow:hidden;' href="tel:<?php echo $v['phone']?>"><span style='color:#ed6d00;font-size:16px;font-weight:bold;'><?php echo $v['phone']?></span></a>
						<a  style='display:block; height:18px;overflow:hidden;margin-top:6px;' href="javascript:;"><span style='color:#666;font-size:14px;'><?php echo $v['address']?></span></a>
					</li>
   				</ul>
			</li>
		</ul>
	</section>
	<?php } }else{ ?>
	<section style='background:#fff;height:200px;'>
    	<div style='padding:20px;'>你还没有收藏过名片！</div>
    </section>
	<?php }?>
	</form>
	 <?php if($page_arr['countPage'] > 1){ ?>
    <section id="navi_bottom" style="text-align:center;">
    	
    	<div class="navi_button" style="border-right:1px #ededed solid;<?php if( $page_arr['page'] <=1 ){ ?>background:transparent !important;<?php } ?>">
    		<?php if( $page_arr['page'] > 1 ){ ?><a href="<?php  echo $page_arr['pre'] ?>"><span class="dark_grey_14_label">上一页</span></a><?php } ?>
    	</div>
    	<div style="width:32%;text-align:center; display:inline-block;">
    		<span style="position:relative;top:8px;" class="dark_grey_14_label"><?php echo $page_arr['page'].'/'.$page_arr['countPage'] ?></span></div>
    	<div class="navi_button" style="border-left:1px #ededed solid;<?php if( $page_arr['page'] >= $page_arr['countPage'] ){ ?>background:transparent !important;<?php } ?>">
    		<?php if( $page_arr['page'] < $page_arr['countPage'] ){ ?><a href="<?php   echo $page_arr['next'] ?>"><span class="dark_grey_14_label">下一页</span></a><?php } ?>
    	</div>
    </section>
    <?php } ?>
</content>
<script type="text/javascript">
$(function(){
/*
 *全选和全不选
 */
$(".selected-btu").click(function(){
   $(".kid").attr("checked",this.checked);    
    
});

/*
*删除数据
*
*e.preventDefault();阻止默认行为，不让提交
* 获取url;
*/

$(".delete-btu").click(function(){
    var url = '<?php echo site_url('uc/deletekeep') ?>';
    submit_form(url);
    
});

function submit_form(url){
   var count = 0;
   var selected_id = $(".kid");
   $.each( selected_id, function(i,n){
         if($(n).attr("checked")){
               count++; 
          }
   });
   
   if(count <= 0 ){
        alert("请选择取消收藏的名片记录");
        return ;
   }
   
   var form =$("#data-form").get(0);
   form.action = url;
   form.submit();
}

});
</script>