<header>		
	<section id="top_bar" style="text-align:center;height:47px;padding:5px 0px 0px 10px;">				
		<ul style="padding-top:10px;">
			<li style="width:65%;vertical-align:20%;float: left;"	><span class='header_title_text' style="font-size: 16px;margin-left: -50px;">快速发布名片成功提示</span></li>
			<li style="width:33%;float: left;cursor: pointer;border: 1px solid #ffb38c;background: #fff1e8 none repeat scroll 0 0;color: #ee5500;" id="kspost"  >
				<img src="<?php echo source_url()?>images/post.png" style='position:relative;top:0px; margin-right:5px;margin-left:5px;width: 11px;'/>
				快速发布</li>
		</ul>
	</section>
</header>
<script type="text/javascript">
	$(function(){
		$("#kspost").click(function(){
			window.location.href="<?php echo site_url('fastpost') ?>"; 
		})
	});
	
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
	//微信分享
	shareTitle="<?php echo $title;?>";
	descContent="";
	imgUrl = "<?php echo $logo;?>";
    lineLink = "<?php echo site_url('detail').'/'.$id; ?>";

	
 
</script>
<content>
	<section style="text-align:center;padding:0px 10px 0px 10px;">
		<div id="lr_form_left" style="display:block;width:100%;">
			<div style="font-size: 16px;font-family:cursive;margin-top: 40%;margin-bottom: 40%;">
				<?php if($status =='y'){ ?>
    			<img src='<?php echo source_url()?>images/<?php if($status =='y'){ echo 'right.png'; }else{ echo 'wrong.png'; } ?>'width="40px" height="40px" style='position:relative;top:10px; margin-right:10px;margin-left:10px;'/>
				<?php } ?>
				<?php if($status =='y'){ ?>
				<span><?php echo $info; ?><br>您可以<a href='javascript:share();' style='color:#ee5500'>分享到朋友圈</a>或<a href='<?php echo site_url('detail').'/'.$id; ?>' style='color:#ee5500'>预览效果</a></span>
				<?php }else{ ?>
				<span><?php echo $info; ?>,<a href='javascript:history.back(-1);' style='color:#ee5500;' >返回重新再试！</a></span>
				<?php }?>
			</div>
		</div>
	</section>
</content>
