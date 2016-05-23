<header>		
	<section id="top_bar" style="text-align:center;height:47px;padding:5px 0px 0px 10px;">				
		<ul style="padding-top:5px;">
			<li style="width:20%;text-align:left;"><img onclick="doHREF('<?php echo site_url('uc')?>')" style="width:30px;height:30px;" src="<?php echo source_url()?>images/goback.jpg"/></li>
			<li style="width:56%;vertical-align:30%;"><span  class='header_title_text'>我发布的名片</span></li>
			<li style="width:20%;">
				<!--<ul>
					<li style="float:right;position:relative;top:0px;right:15px;">
						<img src="<?php echo source_url()?>/images/logon-top.jpg"  class="header_button" onclick="doHREF('<?php echo site_url('uc/post')?>')">
						<span style="position:relative;top:20px;left:5px;" class="white_12_label" onclick="doHREF('<?php echo site_url('uc/post')?>');">发布</span>
					</li>
				</ul>
			--></li>
		</ul>
	</section>         
</header>

<content>
	<section style='text-align:left;margin:15px 0px; padding:10px; background:#fff; border:1px solid #ccc;'>
    	注:发布或编辑名片信息请到www.87756.com电脑版,联系电话:400-800-7756
    </section>
	<?php if( !empty($list) ){ foreach( $list as $k=>$v){?>
	<section style="text-align:center;" class="goods_list_item">
		<ul>
			<li style="width:auto;vertical-align:top; text-align:left;position:relative;">
				<img class="goods_list_image" src="<?php echo $v['logo']?>" onclick="doHREF('<?php echo site_url('detail').'/'.$v['id'];?>')" />
				<div>
					<!--  <span style='font-size:14px;margin:0;padding:5px 0;font-weight:bold;color:#ed6d00;' onclick="doHREF('<?php echo site_url('uc/edit').'/'.$v['id']; ?>')">[编辑]</span>-->
					<span style='font-size:14px;margin:0;padding:5px 0;font-weight:bold;<?php if($v['isagree']>0){ ?>color:green;<?php }else{ ?>color:red;<?php }?> '>[<?php if($v['isagree']>0){ echo '已审核'; }else{ echo '未审核'; } ?>]</span>
				</div>
				<?php if($v['isMember']>0){ ?>
    			<img src="<?php echo source_url()?>images/vip.png" style='position:absolute;left:-3px;top:-3px;'/>
    			<?php }?>
			</li>
			<li style="text-align:left;width:62%;">
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
    	<div style='padding:20px;'>你还没有发布过名片！</div>
    </section>
	<?php }?>
</content>