<script type="text/javascript" src="http://api.map.baidu.com/api?type=quick&v=2.0&ak=C9mkSR7qE9aiyguAAk1Q0ttY"></script>
<header>
	<section id="top_bar" style="text-align:center;height:52px;line-height:52px;padding:0px 0px 0px 10px;">				
		<ul>
	    	<li style="padding-top:10px;width:20%;text-align:left;vertical-align:middle;">
			<img onclick="doHREF('<?php echo site_url()?>');" style="width:30px;height:30px;margin-left: 15px" src="<?php echo source_url()?>images/back_new.jpg"/></li>
            <li style="width:56%;"><span  class='header_title_text'>我的电子名片</span></li>
            <li style="width:20%;"></li>
        </ul>
	</section>
</header>
<!-- 内容 -->
<content>
    <section id="goods_detail" style='margin-top:10px;'>
         <ul style="box-shadow:0px 1px 2px 1px #ededed; border-radius:5px;border:1px #ededed solid;">                    
         	<li style="border-bottom:1px #ededed solid;padding:10px 0px 10px 0px;padding:10px;display:block;font-weight:bold;font-size:20px;" id="remainTime" class="dark_grey_14_label"><?php echo $page['title']?></li>		
			<li class="orange_16_b_label" style="display:block; padding:5px 0 5px 10px; ">
				<a  style='display:block;position:relative;' href="tel:<?php echo $page['phone']?>">
					<img src='<?php echo source_url()?>images/phone.png' style='position:relative;top:2px;'/><span style='color:#ed6d00;font-size:16px;font-weight:bold;margin-left:5px;'><?php echo $page['phone']?></span>
				</a>
			</li>
			<li style="display:block; padding:0 0 5px 10px; ">
				<a  style='display:block;margin-top:0px;' href="javascript:;">
					<img src='<?php echo source_url()?>images/address.png' style='position:relative;top:2px;'/><span style='color:#666;font-size:14px;margin-left:5px;'><?php echo $page['address']; ?></span>
				</a>
			</li>
			<li style="display:block;padding:0 0 5px 12px;">
				<strong style= 'color:#ed6d00; font-size:15px;' class='hotCount'><?php echo $page['hot']?></strong> 推荐&nbsp;&nbsp;<strong  style='color:#ed6d00; font-size:15px;'><?php echo $page['view']?></strong> 浏览
			</li>
			<li style="padding:5px 10px 10px 10px; display:block;position:relative;">
            	  <?php if($page['logo']!=""){?>
			<img style="width:100%;max-width:440px;" onerror="<?php echo source_url()?>images/default.jpg" src="<?php echo $page['logo']?>" />
		<?php }else{ ?>  
			<img style="width:100%;max-width:440px;" src="<?php echo source_url()?>images/default.jpg" />		
			<?php } ?>
            
            	
				<?php if($bigrow["city_id"]!="0"){ if($page['isMember']>0){ ?>
    			<img src="<?php echo source_url()?>images/vip.png" style='position:absolute;left:8px;top:2px;'/>
    			<?php }else if($page['is_refresh']>0){?>
		<img src="<?php echo source_url()?>images/renzheng.png" style='position:absolute;left:8px;top:2px;'/>
		<?php }} ?>
            </li>
			<!--<li style="display:block;padding:0 0 10px 10px;">
				<?php //if($isHot>0){?>
				<div class='clickButton' onclick='hotClick(this,<?php //echo $page['id']?>)'>立即推荐</div>
				<?php //}else{?>
				<div class='noclickButton'>立即推荐</div>
				<?php //}?>
				&nbsp;&nbsp;
				<?php //if($isCard>0){ ?>
				<div class='clickButton' onclick="cardClick(this,<?php //echo $page['id']?>)">递交名片</div>
				<?php //}else{?>
				<div class='noclickButton'><?php //echo $isCardMsg;?></div>
				<?php //} ?>
			</li>
			-->
			<li style="border-top:1px #ededed solid;padding:10px 0 ;display:block; ">
						<!--		<a href="tel:<?php echo $page['phone']?>" style='margin-left:10px;display:inline;'>
								<div class='clickButton' style="width:25%;max-width:85px;" >
							   一键拨号&nbsp;◎
								</div></a>-->
								
				<div class='clickButton' style="margin-left:10px;width:25%;max-width:85px;" onclick='hotClick(this,<?php echo $page['id']?>)'>
				推荐名片&nbsp;◎<span class='hot_count' style='display:none;'>(<?php echo $page['hot']?>)</span>
				</div>
				<!--<div class='clickButton' onclick="pageKeep(<?php echo $page['id']?>)"><span class='keep-des'>
				<?php if($isKeep>0){ echo "已收藏";}else{ echo "收藏名片"; }?></span>
				<span class='keep_count' style='display:none;' >(<?php echo $page['keep']?>)</span>
				</div> -->
				<!--<div class='clickButton' onclick="cardClick(this,<?php echo $page['id']?>)"><?php if($isCard>0 || $isCardMsg == '不能递交'){ echo '递交名片'; }else{ echo "已递交"; }?></div>-->
				<div class='clickButton' style="width:25%;max-width:85px;" onclick="if(document.getElementById('bdsharebuttonbox').style.display == 'block'){ document.getElementById('bdsharebuttonbox').style.display = 'none'; }else{ document.getElementById('bdsharebuttonbox').style.display ='block'; }">分享名片&nbsp;◎</div>			

				<div class='clickButton' style="width:25%;max-width:85px;" onclick="goToAddress('<?php echo $page['address']; ?>','<?php echo $page['title']; ?>')">一键导航&nbsp;◎</div>			
				
				<script>
					window._bd_share_config={"common":{"bdSnsKey":{},"bdText":"<?php echo $page['title']?>-<?php echo $shareSummary?>","bdMini":"1","bdMiniList":["mshare","weixin","kaixin001","tqf","tieba","douban","tsohu","taobao","baidu","mail"],"bdPic":"<?php echo $page['logo']?>","bdStyle":"1","bdSize":"16"},"share":{"bdSize":16},"image":{"viewList":["qzone","tsina","sqq","tqq"],"viewText":"分享：","viewSize":"16"},"selectShare":{"bdContainerClass":null,"bdSelectMiniList":["qzone","tsina","sqq","tqq"]}};with(document)0[(getElementsByTagName('head')[0]||body).appendChild(createElement('script')).src='http://bdimg.share.baidu.com/static/api/js/share.js?v=89860593.js?cdnversion='+~(-new Date()/36e5)];
				</script>	
			</li>
			<li style="width:100%;">		
			<div class="bdsharebuttonbox" id='bdsharebuttonbox' style='padding:5px 0px 0px 10px; margin-top:10px; border-top:1px solid #ededed; display:none;'>
					<a href="#" class="bds_more" data-cmd="more" style='margin:6px 0px 6px 0;'>分享：</a>
					<a href="#" class="bds_qzone" data-cmd="qzone" title="分享到QQ空间" style='color:#333'>空间</a>
					<a href="#" class="bds_tsina" data-cmd="tsina" title="分享到新浪微博"  style='color:#333'>微博</a>
					<a href="#" class="bds_sqq" data-cmd="sqq" title="分享到QQ好友"  style='color:#333'>QQ好友</a>
					<a href="#" class="bds_tqq" data-cmd="tqq" title="分享到腾讯微博"  style='color:#333'>腾讯微博</a>
				</div></li>
		</ul>
	</section>
    <section style="margin:10px 10px 10px 10px;background:#fff;box-shadow:0px 1px 2px 1px #ededed; border-radius:5px;border:1px #ededed solid;">
		<div id="tips_collapse" class="collapse_bar" onclick="doCollapse('tips_collapse');">
		<span>名片详情</span>
		<span style="font-size:12px;float:right;">&nbsp;▲</span>
		</div>
		<div id="tips_collapse_content" class="collapse_content" style="display:block;padding:10px 10px 10px 10px;list-style:square;color:#1d1d1d;font-size:12px;">
			<div class='content_item' style='font-size:16px;font-family:"microsoft yahei",sans serif;'>
			<?php 
			$content1 = "<p style='color:#FF8500;'>".$tip1."</p>";
			$content2 = "<p style='color:#FF8500;'>".$tip2."</p>";
			$content3 = "<p style='color:#FF8500;'>".$tip3."</p>";
			$content4 = "<p style='color:#FF8500;'>".$tip4."</p>";
			if($page['isagree']==1&&strpos($page['content'],'该专线未经查物流官方实地认证')==false){ ?>
			<?php 
			echo $page['content'];
			if($page['isMember']>0){
			echo $content4;
			}else if($page['is_refresh']>0){
			echo $content3;
			}else{
			echo $content2;
			}
			}else if($page['isagree']==0){
			  if(strpos($page['content'],'该专线未经查物流官方实地认证')==false){
			echo $page['content']."<br>";
			echo $content1;
			}else{
			   echo $page['content']."<br>";
			   }
			}
			?>
			</div>
			
		</div>	
	</section>
	<!--
	<section class="my_pos">
        <ul>
        	<li style="padding-left:10px;" onclick="doHREF('<?php echo site_url()?>');"><?php echo $cityName?>物流大全</li>
			<!--<li style="width:10px;">&gt;</li>
			<li style="width:60px;" onclick="doHREF('<?php echo site_url('detail').'/'.$page['id']; ?>');">名片详情</li>
	        -->
	      <!--  <li style="width:80px;float:right;margin-right:10px;" onclick="hideURLbar();">回顶部&nbsp;▲&nbsp;</li>
		</ul>
    </section>-->
</content>
<script type="text/javascript">
	imgUrl = "<?php echo $page['logo']?>";
    lineLink = "<?php echo site_url('detail').'/'.$page['id']; ?>";
	 function hotClick(e,id ){
		if(!e || !id) return false;
		$.post("<?php echo site_url('hot') ; ?>",{id:id},function(res){
			res= $.parseJSON(res);
			if( res.status == 'y'){
				var count = $(".hot_count").html();
				count = parseInt(count);
				$(".hot_count").html( count+1 );
				alert( res.info );
			}else{
				if( res.info  != '对不起，该会员未通过VIP认证，不能推荐！' ){
					alert( res.info );

				}
			}
		});	
	 }
	 function pageKeep(id){
		if(!id) return false;
		$.post("<?php echo site_url('keep') ; ?>",{id:id},function(res){
			res= $.parseJSON(res);
			if( res.status == 'y'){
				var count = $(".keep_count").html();
				$(".keep-des").html("已收藏");
				count = parseInt(count);
				$(".keep_count").html( count+1 );
			}else{
				alert( res.info );
			}
		});
	 }
	 function cardClick(e,id ){
		 if(!e || !id) return false;
			$.post("<?php echo site_url('card') ; ?>",{id:id},function(res){
				res= $.parseJSON(res);
				if( res.status == 'y'){
					$(e).html('已递交').unbind('click');
				}else{
					if( res.info  != '对不起，没有登录不能递交名片！'  && res.info  != '递交失败，你的名片未通过VIP认证！' ){
						alert( res.info );

					}
				}
		 });
	}
function goToAddress(address,title){
var myGeo = new BMap.Geocoder();
// 将地址解析结果显示在地图上,并调整地图视野
myGeo.getPoint(address, function(point){
  if (point) {
   var lng=point.lng;
				var lat=point.lat;
				var url="http://api.map.baidu.com/marker?location="+lat+","+lng+"&title="+title+"&content="+address+"&output=html&appName=查物流";
				}else{
				var url="http://api.map.baidu.com/geocoder?address="+address+"&output=html";
				}
window.location.href=url;
}, "");
}
function share(){
		var height = jQuery(document).height();
		var share_boxObj =jQuery(".share_box");
		share_boxObj.height( height );
		
		if( share_boxObj.is(":hidden") ){
			share_boxObj.show();
		}else{
			share_boxObj.hide();
		}
	}
document.writeln("<div class='share_box'><img class='share_img' src='<?php echo source_url()?>images/share_dialog.png'/></div>");
jQuery(".share_box").bind("click",function(){
		hidePopDiv();
	});
	function hidePopDiv(){
	    var share_boxObj = jQuery(".share_box");
		share_boxObj.hide();
		 try {
        if (typeof(eval("shareHideCallBack")) == "function") {
         shareHideCallBack(); 
        }
    } catch(e) {}
	}
<?php if(isset($showshare)&&$showshare==1){echo "share();";};?>
 </script>