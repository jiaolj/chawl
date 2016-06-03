getUser(function(){
	$('.click').click(function(){
		if(_userInfo) location.href = $(this).attr('htm');
		else _followFunc();
	})
	var _hotClick = function(id){
		if(!id) return false;
		$.ajax({
			url : "http://wap.chawuliu.com/hot",
			data : {id:id},
			success : function(res){
				res = $.parseJSON(res);
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
			}
		})
	}
	//微信初始化
	var initWX = function(dd){
		$.ajax({
			url: '/wechat/sign',
			data: {
				url: encodeURIComponent(location.href.split('#')[0])
			},
			success: function(data){
				var ticket = JSON.parse(data),
					wxData = {
						'imgUrl': dd.page.logo,
						'link' : '',
						'desc' : '查物流-让天下货主与物流实现更高效的链接！',
						'title' : dd.page.title+' -查物流推荐'
					},
					weixin = function (title,link,imgurl,desc){
						wx.ready(function () {
							wx.onMenuShareTimeline({
								title: title,
								link: link,
								imgUrl: imgurl
							});
							wx.onMenuShareAppMessage({
								title: title,
								desc: desc,
								link: link,
								imgUrl: imgurl
							});
							wx.onMenuShareQQ({
								title: title,
								desc: desc,
								link: link,
								imgUrl: imgurl
							});
							wx.onMenuShareWeibo({
								title: title,
								desc: desc,
								link: link,
								imgUrl: imgurl
							});
							obj.sound();
						});
					}
				;
				wx && wx.config({
					debug: false,
					appId: ticket.appId,
					timestamp: ticket.timestamp,
					nonceStr: ticket.nonceStr,
					signature: ticket.signature,
					jsApiList: ["onMenuShareTimeline","onMenuShareAppMessage","onMenuShareQQ","onMenuShareWeibo"]
				});
				wx.ready(function () {
					weixin(wxData.title,wxData.link,wxData.imgUrl,wxData.desc);
				});
			}
		})
	};

	var did = Base.tools.getQueryString('id'),
		uid = Base.tools.getQueryString('uid'),
		lboxArea = $('.leavebox textarea'),
		cover = $('.cover-page'),
		viewsPage = 0,
		lboxShow = function(){
			$('.leavebox').removeClass('hide');
			cover.show();
			$('.send2').hide();
			lboxArea[0].focus();
		},
		lboxHide = function(){
			$('.leavebox').addClass('hide');
			cover.hide();
			$('.send2').show();
			$('.leavebox textarea').val('')
		},
		dtailDom = '\
			<dt>\
				<div class="flt heads">\
					<img id="logo1" src="#headimgurl" />\
				</div>\
				<div class="frt txt">\
					<div class="up">#nickname <span>#ctime</span></div>\
					<div class="down">#message</div>\
				</div>\
				<br class="cb"/>\
			</dt>\
		',
		getData = function(get){
			$.ajax({
				url: '/detail2/'+did,
				dataType: 'json',
				success : function(dd) {
					log(dd);
					if(!get){
						$('#title').text(dd.page.title);
						if(dd.page.is_vip=='1') $('#is_vip').show();
						else $('#is_vip').hide();
						$('#phones').html('<b>'+dd.page.phone+'</b>');
						setTimeout(function(){
							$('#phones').html('<a href="tel:'+dd.page.phone+'"><b style="color:#df4b24">'+dd.page.phone+'</b></a>');
						},3050);
						$('#hot').text(dd.page.hot);
						$('#view').text(dd.page.view);
						$('#address').text(dd.page.address);
						$('#logo').attr('src',dd.page.logo);
						$('#logo_share').attr('src',dd.page.logo);
						$('#onlinec').attr('htm','mdetail.html?id='+uid);
						$('#phonec').attr('href','tel:'+dd.page.phone);
						$('#ctime').text(Base.tools.int_to_str(dd.page.ctime));
						$('.seoTitle').text(dd.page.title);
						initWX(dd);
						(function(){
							var qrcode = new QRCode(document.getElementById("qrcode"), {
								width : 300,//设置宽高
								height : 300
							});
							qrcode.makeCode(location.href); //location.protocol + "//" + location.host + "/lottery/pos.html?id=" + did
						})();
						var cnt = dd.page.content,
							tmp = '<p style="color:#FF8500;line-height:22px">#insert</p>';
						if(dd.page.isMember>0){
							$('#viplogo').show();
							cnt += tmp.replace('#insert',dd.tip4);
						}
						$('#contents').html(cnt);
					}
					//留言
					var mc = dd.message_count,
						msgs = dd.messages;
					msgs.reverse();
					$('#viewNum').text(mc);
					$('#dtail').html(function(){
						var htm = '',
							tmp = dtailDom;
						for(var k in msgs) {
							var m = msgs[k];
							htm += tmp.replace('#message',m.message).replace('#ctime',Base.tools.int_to_str(m.ctime)).replace('#headimgurl',m.user_info[0] && m.user_info[0].headimgurl).replace('#nickname',m.user_info[0] && m.user_info[0].nickname.substring(0,13));
						}
						return htm;
					});
					$('#dtail>dt:gt(0)').hide();
					$('#stars').html(getStar(mc));
					$('.jsLoad').removeClass('hide');
				},
				error:function(jqXHR,textStatus) {
					log(' request failed'+textStatus);
				}
			});
		}
	;
	getData();
	
	$('#views').click(function(){
		viewsPage+=5;
		$('#dtail>dt:lt('+viewsPage+')').show();
		if(viewsPage>=$('#dtail>dt').length) $(this).hide();
		$('#viewsClose').show();
	})
	$('#viewsClose').click(function(){
		$('#dtail>dt:gt(0)').hide();
		viewsPage = 0;
		$('#views').show();
		$(this).hide();
	})
	$('#vote').click(function(){
		if(_userInfo) {
			if($('.leavebox').hasClass('hide')) lboxShow();
			else lboxHide();
		}
		else _followFunc();
	})
	$('#leave').click(function(){
		if(_userInfo) {
			if($('.leavebox').hasClass('hide')) lboxShow();
			else lboxHide();
		}
		else _followFunc();
	})
	$('.leavebox a.cancel').click(function(){
		lboxHide();
	})
	$('.leavebox a.submit').click(function(){
		var val  = $('.leavebox textarea').val();
		if(val){
			lboxHide();
			$.ajax({
				url: '/page/support',
				data: {page_id:did},
				dataType: 'json',
				success : function(dd) {
					if(dd.info=='不能重复支持'){
						alert('亲，您已经推荐过了！');
						getData(1);
					}
					else{
						$.ajax({
							url: '/page/message',
							data: {page_id: did, message: val},
							dataType: 'json',
							success : function() {
								getData(1);
								_hotClick(did);
							}
						})
					}
				}
			})
		}
	})
	
	$('a.back').click(function(){
		var url = Base.tools.getQueryString('back_url');
		if(url.indexOf('?')==-1) url+='?';
		else url+='&';
		url+='back_ScrollTop='+Base.tools.getQueryString('back_ScrollTop');
		location.href = url;
	})
	
	$('#address').click(function(){
		var myGeo = new BMap.Geocoder(),
			title = $('#title').text(),
			address = $('#address').text()
			;
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
	});
})