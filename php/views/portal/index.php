<!-- 头部  -->
	<header>
	<!-- 头部广告  -->
	<script type="text/javascript"  src="<?php echo source_url() ?>js/TouchSlide.1.1.js?t=20140715" ></script>
	 <section id="slider">
           <div id="focus" class="focus">
				<div class="hd">
					<ul></ul>
				</div>
				<div class="bd">
					<ul>
 				
				<?php   $advList = loadAdv( $city['city_id'] ); ?>
	<?php 
		foreach ( $advList as $k=>$v){
		$imageurl=str_replace("http://m.chawuliu.com","http://www.chawuliu.com",$v["img"]);
		$imageurl=str_replace("http://wap.chawuliu.com","http://www.chawuliu.com",$imageurl);
			echo '<li><a href="'.$v["url"].'"><img src="'.$imageurl.'" /></a></li>';
		}

	?>					
					
					
					</ul>
				</div>
			</div>
			<script type="text/javascript">         
				TouchSlide({ 
					slideCell:"#focus",
					titCell:"#focus .hd ul", //开启自动分页 autoPage:true ，此时设置 titCell 为导航元素包裹层
					mainCell:"#focus .bd ul", 
					effect:"leftLoop", 
					autoPlay:true,//自动播放
					autoPage:true //自动分页
				});
			</script>
</section>
	<!-- 头部切换的布局  -->
	<section id="top_bar" style="height:47px;padding:5px 0px 0px 10px;">				
        <ul>
	        <li onclick="doHREF('<?php echo site_url('')?>')" ><img style="width:100px;vertical-align:sub;" src="<?php echo source_url()?>images/logo.png"/></li>
	        <li onclick="doHREF('<?php echo site_url('switch_city')?>');">
	        	<span style="" class="white_16_label" id="city_name"><?php echo $city['city_name']?></span>
	        	<br/><span class="white_12_label">[切换分站]</span>
	        </li>
	        <li style="float:right;position:relative;top:5px;right:15px;">
	        	<img src="<?php echo source_url()?>images/account.jpg" class="header_button" onclick="doDropBox('account_box');"/>
	        	<span style="position:relative;top:20px;left:5px;" class="white_12_label" onclick="doDropBox('account_box');">账户</span>
	        </li>
	        <li style="float:right;position:relative;top:5px;right:20px;">
	        	<img src="<?php echo source_url()?>images/search.jpg" class="header_button" onclick="checkRecord('search_record');doDropBox('search_box');"/>
	        	<span style="position:relative;top:20px;left:5px;" class="white_12_label" onclick="checkRecord('search_record');doDropBox('search_box');">搜索</span>
	        </li>
        </ul>
	</section>
	<!-- 搜索的框  -->
	<section id="search_box" style="text-align:center;padding:5px;font-size:12px;background:#fe9417;border-top:1px #ffca8b dashed;display:none;">
        <div style="padding:10px 15px 0px 15px;">
           <form id="search_form" action="<?php echo site_url('search').'/1'?>" method="get" >
              <input id="search" class="input_field" type="text" name="kw" value="" placeholder="请输入城市、名称、地址等关键字"/> 
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
	<!-- 账户操作  -->
	<section id="account_box" style="padding:10px 0px 5px 0px;text-align:center;background-color:#fe9417;font-size:14px;border-top:1px #ffca8b dashed;display:none;">
		<?php if( $user_info ){ ?>			                
     	<div style="display:block;padding:0px 30px 10px 30px;text-align:left;color:#ffffff;font-size:12px;"><span>欢迎您回来！<?php echo $user_info['nickname'] ? $user_info['nickname'] : $user_info['mail']?></span></div>
     	<?php }?>
     	<ul style="list-style:none;padding:0px;margin:0px;">
     		<?php if( !$user_info ){ ?>	
     		<!-- 登录 -->
       		<li style="width:45%;display:inline-block;">
       			<img onclick="doHREF('<?php echo site_url('login')?>')" style="width:48px;height:48px;" src="<?php echo source_url()?>images/logon.jpg" />
       		</li>
       		<!-- 注册 -->
       		<li style="width:45%;display:inline-block;">
       			<img onclick="doHREF('<?php echo site_url('register') ?>');" style="width:48px;height:48px;" src="<?php echo source_url()?>images/register.jpg"/>
       		</li>
       		<?php }else{ ?>
       		<!-- 注销 -->
       		<li style="width:45%;display:inline-block;">
       			<img onclick="doHREF('<?php echo site_url('uc/logout')?>')" style="width:48px;height:48px;" src="<?php echo source_url()?>images/logoff.jpg" />
       		</li>
       		<!-- 管理 -->
       		<li style="width:45%;display:inline-block;">
       			<img onclick="doHREF('<?php echo site_url('uc')?>')" style="width:48px;height:48px;" src="<?php echo source_url()?>images/dimanagement.jpg" />
       		</li>
       		<?php }?>
     	</ul>
	</section>
	</header>
	<!-- 头部结束  -->
	<!-- 背景 -->
	<section id="screen_mask" style="display:none;position:absolute;top:112px;width:100%; height:0px; background-color:black; opacity:0.5;z-index:20000;"></section>
	<!-- top banner -->
	<section id="top_bar_mask" style="display:none;position:absolute;top:0px;width:100%; height:52px; background-color:black; opacity:0.5;z-index:20000;"></section>
	<!-- 二级菜单选则  -->
	<section id="popup_list" style="display:none;width:100%;position:absolute;top:112px;z-index:30000;">
		<!-- 细类的输出 -->
		<?php 
			if(!empty($sort_list)){ 	
				foreach ($sort_list as $k=>$v){
		?>
		<div id="popup_list_left" class='sid<?php echo $v['id']?>_nav nav_list' style="width:100%;display:none;">
			<ul style="border-top:1px #cccccc solid;" >
				<!--<li onclick="doHREF('<?php echo getUrlStr('sid'.$v['id'],'del');?>');" style="<?php if( !isset($_GET['sid'.$v['id']]) ){ echo "color:#1d1d1d;background-color:#ededed;border-left:3px #ed6d00 solid;"; } ?>">
					<div class="tag">全部<?php echo $v['name']?></div>
					<div class="quantity"></div>
				</li>-->
				<?php 	foreach ($v['childs'] as $tk=>$tv){ ?>
				<li onclick="doHREF('<?php echo getUrlStr('sid'.$tv['parent_id'],'add').$tv['id'] ?>');" style="<?php if(  isset($_GET['sid'.$tv['parent_id']]) && ( intval($_GET['sid'.$tv['parent_id']]) == $tv['id'] )  ){ echo "color:#1d1d1d;background-color:#ededed;border-left:3px #ed6d00 solid;"; }?>" >
					<div class="tag"><?php echo $tv['name']?>(<?php echo $tv['count']?>)</div>
					<div class="quantity"></div>
				</li>
				<?php } ?>
			</ul>
		</div>
		<?php }  } ?>
		
		<!-- 排序的输出 -->
		<div id="popup_list_left" class='order_nav nav_list' style="width:100%;">
			<ul style="border-top:1px #cccccc solid;" >
				<li onclick="doHREF('<?php echo getUrlStr('order').'default'; ?>');" style="<?php if( (isset($_GET['order']) && (trim($_GET['order'])=='default') ) || !isset($_GET['order']) )echo "color:#1d1d1d;background-color:#ededed;border-left:3px #ed6d00 solid;";?>">
					<div class="tag">默认排序</div>
					<div class="quantity"></div>
				</li>
				<li onclick="doHREF('<?php echo getUrlStr('order').'hot'; ?>');" style="<?php if( isset($_GET['order']) && (trim($_GET['order'])=='hot') )echo "color:#1d1d1d;background-color:#ededed;border-left:3px #ed6d00 solid;";?>">
					<div class="tag">人气排序</div>
					<div class="quantity"></div>
				</li>
				<li onclick="doHREF('<?php echo getUrlStr('order').'new'; ?>');" style="<?php if( isset($_GET['order']) && (trim($_GET['order'])=='new') )echo "color:#1d1d1d;background-color:#ededed;border-left:3px #ed6d00 solid;";?>">
					<div class="tag">更新排序</div>
					<div class="quantity"></div>
				</li>
			</ul>
		</div>
	</section>
	<!-- 导航栏  -->
	<section id="navi_top">
		<!-- 大类输出  -->
		<ul class='big-nav'>
			<?php 	
				$count = count( $navList ) + 1; $percent = floor(1/$count*100-1).'%';  foreach ($navList as $k=>$v){  
			?>
			
			<li style='width:<?php echo  $percent; ?>' onclick="doHREF('<?php echo trim(site_url(''),'/').'/'.$city['city_spell'].'?sort='.$v['id'] ?>');" >
				<div class="wrapper <?php if( (isset($_GET['sort']) && ($v['id'] == intval($_GET['sort']) ) ) || ( !isset( $_GET['sort']) && ($k ==0)  )  ){ echo 'wrappered'; } ?>" ><?php echo $v['name']?></div>
			</li>
			<?php } ?>
			<li onclick="doHREF('<?php echo site_url('express')?>');" style='width:<?php echo  $percent; ?>' >
				<div class="wrapper">快递查询</div>
			</li>
		</ul>
		<!-- 选中 -->
		<ul style="border-bottom:1px #cccccc solid;">
			<?php 	
				$count = count( $sort_list ) + 1; $percent = floor(1/$count*100-1).'%';  foreach ($sort_list as $k=>$v){  
			?>
			<li style='width:<?php echo  $percent; ?>' onclick="setSortClick('sid<?php echo $v['id'] ?>')" >
				<div class="wrapper two_wrapper" ><div class='tag'><?php echo $select_arr['sid'.$v['id']]; ?></div><div class="triangle_mark_down sid<?php echo $v['id'] ?>_mark"></div></div>
			</li>
			<?php } ?>
			<li style='width:<?php echo  $percent; ?>' onclick="setSortClick('order')" >
				<div class="wrapper two_wrapper" ><div class='tag'><?php echo $select_arr['order']; ?></div><div class="triangle_mark_down order_mark"></div></div>
			</li>
		</ul>
	</section>
	<!-- 名片的输出 -->
	<?php if( !empty($page_list) ){ 
		foreach ($page_list as $k=>$v){
	?>
    <section style="text-align:center;" class="goods_list_item" >
    	<ul>
    		<li style="width:auto;vertical-align:top;position:relative;" onclick="doHREF('<?php echo site_url('detail').'/'.$v['id']?>');">
        <?php if($v['logo']!=""){?>
		<img class="goods_list_image" src="<?php echo $v['logo']?>" />
		<?php }else{ ?>  
<img class="goods_list_image" src="<?php echo source_url()?>images/default.jpg" />  		
			<?php } ?>
    			<?php if($bigRow["city_id"]!="0"){ if($v['isMember']>0){ ?>
    			<img src="<?php echo source_url()?>images/vip.png" style='position:absolute;left:-3px;top:-3px;'/>
    				<?php }else if($v['is_refresh']>0){?>
		<img src="<?php echo source_url()?>images/renzheng.png" style='position:absolute;left:-3px;top:-3px;'/>
		<?php }} ?>
    		</li>
   			<li style="text-align:left;width:62%;">
   				<ul>
   					<li style='height:32px;overflow:hidden;' onclick="doHREF('<?php echo site_url('detail').'/'.$v['id']?>');">
						<span class="black_14_b_label"><?php echo $v['title']?></span>
					</li>
					<li style='display:block;position:relative;'>
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
    	<div style='padding:20px;'>抱歉！还没有名片,请浏览其它的分类</div>
    </section>
    <?php } ?>
    <?php if($page_arr['countPage'] > 1){ ?>
    <section id="navi_bottom" style="text-align:center;">
    	
    	<div class="navi_button" style="border-right:1px #ededed solid;<?php if( $page_arr['page'] <=1 ){ ?>background:transparent !important;<?php } ?>">
    		<?php if( $page_arr['page'] > 1 ){ ?><a href="<?php  echo getUrlStr('page','add').($page_arr['page']-1) ?>"><span class="dark_grey_14_label">上一页</span></a><?php } ?>
    	</div>
    	<div style="width:31%;text-align:center; display:inline-block;">
    		<span style="position:relative;top:8px;" class="dark_grey_14_label"><?php echo $page_arr['page'].'/'.$page_arr['countPage'] ?></span></div>
    	<div class="navi_button" style="border-left:1px #ededed solid;<?php if( $page_arr['page'] >= $page_arr['countPage'] ){ ?>background:transparent !important;<?php } ?>">
    		<?php if( $page_arr['page'] < $page_arr['countPage'] ){ ?><a href="<?php  echo getUrlStr('page','add').($page_arr['page']+1) ?>"><span class="dark_grey_14_label">下一页</span></a><?php } ?>
    	</div>
    </section>
    <?php } ?>
    <!-- 关闭的按钮 -->
    <img src="<?php echo source_url()?>images/close.png" style="display:none;z-index:25000;height:20px;width:20px;position:absolute; top:15px; right:10px;" onclick="closeSortSelect()" id="close_cross">
    <script type='text/javascript' src="<?php echo source_url()?>js/navselect.js"></script>
		<footer>
	<section style="padding:10px 0px 10px 0px;text-align:center;line-height:25px; height:25px; border-top: 1px #ededed solid;">
            <ul>
             	<li style="width:30%;"><a href="http://m.chawuliu.com"><span class="version_button">触屏版</span></a></li>
             <!--	<li style="width:30%;"><a href="<?php echo site_url('fastpost') ?>"><span class="version_button">快速发布</span></a></li>-->
             	<li style="width:30%;"><a href="http://www.chawuliu.com"><span class="version_button">电脑版</span></a></li>
            </ul>  
        </section>
			</footer>