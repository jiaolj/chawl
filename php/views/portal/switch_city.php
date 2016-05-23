<header>
	<section id="top_bar" style="text-align:center;height:47px;padding:5px 0px 0px 10px;">				
		<ul style="padding-top:5px;">
			<li style="width:20%;text-align:left;"><img onclick="javascript:history.back();" style="width:30px;height:30px;" src="<?php echo source_url()?>images/back_new.jpg"></li>
			<li style="width:56%;vertical-align:30%;"><span  class='header_title_text'>城市选择</span></li>
			<li style="width:20%;"></li>
		</ul>
	</section>
</header>
	
	<content>
		<section id="hot_city">
             <ul>
              	<li style ="height:30px;line-height:30px;padding:0px;border:0px;background:transparent;">热门城市</li>
              	<li style ="padding:0px;height:auto;line-height:auto;border:0px;background:transparent;">
	               	<!--<ul>
	               		<?php if( !empty($hot_city_list) ){
	               			$hotcount = count( $hot_city_list );
	               			foreach( $hot_city_list as $k=>$v){
	               		 ?>
	               		<li style="<?php if($hotcount==1){ echo "border:1px #ededed solid;border-radius:5px;"; }else{ if( $k==0 ){ echo "border-radius:5px 5px 0px 0px;"; }else if( ($k+1) == $hotcount ){ echo "border:1px #ededed solid;border-radius:0px 0px 5px 5px;"; }  } ?>" onclick="doHREF('<?php echo trim(base_url(),'/').'/'.$v['spell']?>');"><?php echo $v['name']?></li>
	               		<?php }}else{?>
	               		<li style="border-radius:5px; border:1px #ededed solid;">没有热门城市</li>
	               		<?php }?>
	               	</ul>          	
              	-->
              		<?php if( !empty($hot_city_list) ){ ?>
              		<div class='city_item'>
              			<?php 
              				$hotcount = count( $hot_city_list );
              				//缺席的a标签
              				$lackCount = $hotcount%3 >0 ? ( 3 - $hotcount%3) : 0;
              				//有多少行
              				$lineCount = ceil( $hotcount/3 );
              				$line = 0;
	               			foreach( $hot_city_list as $k=>$v){ ?>
	               			<?php if( ($k+1)%3 == 1 ){ $line++; ?>
              			<div class='city_item_content'>
              			<?php } ?>
              				<a href="<?php echo trim(base_url(),'/').'/'.$v['spell']?>"><?php echo $v['name']?></a>
              			<?php if( ($line==$lineCount) && $lackCount>0 && $k+1 == $hotcount ){
              				for( $i = 0; $i<$lackCount; $i++ ){
              			?>
              				<a href="javascript:;" class='no_content'></a>
              			<?php 	
              				}
              			} ?>
              			<?php if( ($k+1)%3 == 0 ){ ?>
              			</div>
              			<?php } ?>
              			<?php } ?>
              		</div>
              		<?php }else{ ?>
              		<div class='city_item_no'>没有热门城市</div>
              		<?php } ?>
              	</li>
             </ul>
		</section>
		<!-- 城市列表 -->
		<?php if( !empty( $city_list_new ) ){
			foreach ($city_list_new as $k=>$v){
		?>
		<section class="city_list">
			<div id="<?php echo $v['spell']?>_city" class="collapse_bar" onclick="doCollapse('<?php echo $v['spell']?>_city')">
               <span><?php echo $v['name']?></span>
               <span style="font-size:10px;float:right;">&nbsp;▼</span>
            </div>
            <div id="<?php echo $v['spell']?>_city_content" class="collapse_content" style="display:none;padding:10px;">
               <!--<ul>
               		<?php if(!empty($v['two_city'])){ $twocount = count( $v['two_city'] ); foreach ($v['two_city'] as $tk=>$tv){?>
               		<li style="<?php if($twocount==1){ echo "border:1px #ededed solid;border-radius:5px;"; }else{ if( $tk==0 ){ echo "border-radius:5px 5px 0px 0px;"; }else if( ($tk+1) == $twocount ){ echo "border:1px #ededed solid;border-radius:0px 0px 5px 5px;"; }  } ?>" onclick="doHREF('<?php echo trim(base_url(),'/').'/'.$tv['spell']?>');"><?php echo $tv['name']?></li>
               		<?php }}?>		
               </ul>
             -->
             <?php if( !empty($v['two_city']) ){ ?>
              		<div class='city_item'>
              			<?php 
              				$hotcount = count( $v['two_city'] );
              				
              				//缺席的a标签
              				$lackCount = $hotcount%3 >0 ? ( 3 - $hotcount%3) : 0;
              				//有多少行
              				$lineCount = ceil( $hotcount/3 );
              				$line = 0;
	               			foreach( $v['two_city'] as $tk=>$tv){ ?>
	               			<?php if( ($tk+1)%3 == 1 ){ $line++; ?>
              			<div class='city_item_content'>
              			<?php } ?>
              				<a href="<?php echo trim(base_url(),'/').'/'.$tv['spell']?>"><?php echo $tv['name']?></a>
              			<?php if( ($line==$lineCount) && $lackCount>0 && $tk+1 == $hotcount ){
              				for( $i = 0; $i<$lackCount; $i++ ){
              			?>
              				<a href="javascript:;" class='no_content'>&nbsp;</a>
              			<?php 	
              				}
              			} ?>
              			<?php if( ($tk+1)%3 == 0 ){ ?>
              			</div>
              			<?php } ?>
              			<?php } ?>
              		</div>
              		<?php  } ?>
             </div>
         </section>
         <?php } } ?>
	</content>