<!-- 头部  -->
	<header>
	<!-- 头部切换的布局  -->
	<section id="top_bar" style="height:47px;padding:5px 0px 0px 10px;">				
        <ul>
	        <li style="width:20%;text-align:left;">
			<img onclick="doHREF('<?php echo base_url()?>')" style="width:100px;vertical-align:sub;" src="<?php echo source_url()?>images/logo.png"></li>
	        <li style="float:right;position:relative;top:5px;right:20px;">
	        	<img src="<?php echo source_url()?>images/search.jpg" class="header_button" onclick="checkRecord('search_record');doDropBox('search_box');"/>
	        	<span style="position:relative;top:20px;left:5px;" class="white_12_label" onclick="checkRecord('search_record');doDropBox('search_box');">搜索</span>
	        </li>
        </ul>
	</section>
	<!-- 搜索的框  -->
	<section id="search_box" style="text-align:center;padding:5px;font-size:12px;background:#fe9417;border-top:1px #ffca8b dashed;display:none;">
        <div style="padding:10px 15px 0px 15px;">
           <form id="search_form" action="<?php echo site_url('search').( $is_fliter ? '/1' : '' ); ?>" method="get" >
              <input id="search" class="input_field" type="text" name="kw" value="" placeholder="请输入标题、电话、地址等关键字"/> 
	          <input id="search_submit"  style="display:inline-block;" type="button" value="搜索" onclick="submitSearch( this )"/>
           </form>
        </div>
        <script type='text/javascript'>
        function submitSearch( e ){
            var kw = $("#search").val();
            kw = $.trim( kw );
			if( !kw ){
				return false;
			}else{
				doRecord('search_record', 30);
				$("#search_form").submit();
			}
        }
        </script>
        <!-- 搜索的记录 -->		
        <div id="search_record" style="padding:0px 0px 15px 0px;text-align:center;"></div>
        <!-- 清除历史按钮  -->
		<div style="height:20px;width:100%;padding:0px 0px 5px 0px;">
			<span onclick="cleanRecord('search_record');" style="float:right;color:#ffffff;margin-right:20px;">[清除历史]</span>
		</div>
	</section>
	</header>
	<section class="notice_bar"><label>搜索 "<?php echo $searchName;?>" 共<?php echo $page_arr['total']?>条结果：</label></section>
	<!-- 名片的输出 -->
	<?php if( !empty($page_list) ){ 
		foreach ($page_list as $k=>$v){
	?>
    <section style="text-align:center;" class="goods_list_item">
    	<ul>
    		<li style="width:auto;vertical-align:top;position:relative;" onclick="doHREF('<?php echo site_url('detail').'/'.$v['id']?>');">
    		  <?php if($v['is_refresh']>0){?>
		<img class="goods_list_image" src="<?php echo $v['logo']?>" />
		<?php }else{ ?>  
<img class="goods_list_image" src="<?php echo source_url()?>images/default.jpg" />  		
			<?php } ?>
    			<?php if($v['isMember']>0){ ?>
    			<img src="<?php echo source_url()?>images/vip.png" style='position:absolute;left:-3px;top:-3px;'/>
    			<?php }else if($v['is_refresh']>0){?>
		<img src="<?php echo source_url()?>images/renzheng.png" style='position:absolute;left:-3px;top:-3px;'/>
		<?php } ?>
    		</li>
   			<li style="text-align:left;width:62%;">
   				<ul>
   					<li style='height:32px;overflow:hidden;' onclick="doHREF('<?php echo site_url('detail').'/'.$v['id']?>');">
						<span class="black_14_b_label"><?php echo $v['title']?></span>
					</li>
					<li style='display:block; position:relative;'>
						<span style='position: absolute;top:0;right:0px;'><em style='color:#ed6d00;font-weight:bold;font-style: normal;'><?php echo $v['view']?></em>人浏览</span>
						<a  style='display:block; height:18px;overflow:hidden;' href="tel:<?php echo $v['phone']?>"><span style='color:#ed6d00;font-size:16px;font-weight:bold;'><?php echo $v['phone']?></span></a>
						<a  style='display:block; height:18px;overflow:hidden;margin-top:6px;' href="javascript:;"><span style='color:#666;font-size:14px;'><?php echo $v['address']?></span></a>
					</li>
   				</ul>
   			</li>
    	</ul>
    </section>
    <?php } }else{ ?>
    <section style='background:#fff;height:200px;'>
    	<div style='padding:20px;text-align:center;'>没有符合条件的名片</div>
    </section>
    <?php } ?>
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