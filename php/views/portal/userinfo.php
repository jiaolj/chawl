<header>		
	<section id="top_bar" style="text-align:center;height:47px;padding:5px 0px 0px 10px;">				
		<ul style="padding-top:5px;">
			<li style="width:20%;text-align:left;"><img onclick="javascript:history.back();" style="width:30px;height:30px;" src="<?php echo source_url()?>images/goback.jpg"/></li>
			<li style="width:56%;vertical-align:30%;"><span  class='header_title_text'>收到的名片信息</span></li>
			<li style="width:20%;"></li>
		</ul>
	</section>         
</header>

<content>
	<?php if( !empty($list) ){ foreach( $list as $k=>$v){?>
	<section style="text-align:center;" class="goods_list_item">
		<ul>
			<li style="width:auto;vertical-align:top; text-align:left;position:relative;">
				<img class="goods_list_image" src="<?php echo $v['logo']?>" onclick="doHREF('<?php echo site_url('detail').'/'.$v['id'];?>')" />
				<?php if($v['isMember']>0){ ?>
    			<img src="<?php echo source_url()?>images/vip.png" style='position:absolute;left:-3px;top:-3px;'/>
    			<?php }?>
			</li>
			<li style="text-align:left;width:62%;" >
				<ul>
   					<li style='height:32px;overflow:hidden;' onclick="doHREF('<?php echo site_url('detail').'/'.$v['id']?>');">
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
    	<div style='padding:20px;'>他还没有发布过信息！</div>
    </section>
	<?php }?>
</content>