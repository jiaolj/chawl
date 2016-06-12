//getLocation.init(function(){
(function(){
	var lbox = $('.leavebox'),
		lboxArea = $('.leavebox textarea'),
		cover = $('.cover-page'),
		_isLoad = 0,
		_kwd = '',
		_args = {},
		lboxShow = function(){
			lbox.show();
			cover.show();
			$('.send2').hide();
			lboxArea[0].focus();
		},
		lboxHide = function(){
			lbox.hide();
			cover.hide();
			$('.send2').show();
		},
		_key='blog';
	;
	$('.leavebox a.cancel').click(function(){
		lboxHide();
	})
	window.MVC = {
		querys : {
			is : 0,
			page : 1,
			end : 0
		},
		dom : {
			list : $('#list'),
			load : '<div class="load"><img src="img/car_on.png">努力加载中...</div>',
			tmp : '\
				<dt did="#id"> \
					<div class="titles"> \
						<div class="tit flt"> \
							<img class="logo" src="#headimgurl" />#nickname <img class="vip#vip" src="img/blog/v.png" /> \
						</div> \
						<a class="card frt">查看名片</a> \
						<br class="cb"/> \
					</div> \
					<div class="text"><a url="bdetail.html?id=#id"> \
						<div class="title">#content</div> \
						<div class="img" i="#ii">#pictures</div> \
					</a></div> \
					<div class="see"> \
						#viewers \
					</div> \
					<div class="action"> \
						<span class="hide"><a class="click" tp="like"><img class="left" src="img/blog/like.png" /><span>#like_num</span></a> <img class="left" src="img/blog/talk.png" /><span>#comment_num</span> <img class="left" src="img/blog/share.png" /></span> \
						<span class="from">来自：#city_id</span>\
						<span class="frt talk"><a class="click" tp="talk" htm="mdetail.html?id=#user_id">聊一聊</a></span> \
						<br class="cb"/> \
					</div> \
				</dt>\
			'
		},
		getImages : function(pic,id){
			var obj = this,htm = '',plist = [];
			if(pic.length>0){
				plist = pic.split(',');
				$.each(plist,function(k,i){
					var src = 'http://www.chawuliu.com/uploads/'+i;
					var img = new Image();
					img.src = src;
					img.onload = function(){
						var o1 = $('.img[i="'+id+'"] img:eq('+k+')');
						o1.attr('src',src)
						if(img.width>img.height) o1.css('height','100%');
						else o1.css('width','100%');
					};
					//if(k<3) 
					htm += '<span><img class="jui-auto-img" src="" /></span>';
				})
				htm += '<br class="cb" />';
			}
			return htm;
		},
		getViewers : function(pic){
			var htm = '';
			for(var p in pic) htm += '<img src="'+pic[p].headimgurl+'" />';
			return htm;
		},
		rdata : function(d,j){
			var obj = this;
			d = d.replace('#user_id',j.user_id).replace('#ii',j.id).replace('#city_id',j.city_id || '').replace('#id',j.id).replace('#id',j.id).replace('#headimgurl',j.user_info[0] && j.user_info[0].headimgurl).replace('#nickname',j.user_info[0] && j.user_info[0].nickname.substring(0,10)).replace('#content',Base.tools.sub(j.content,200)).replace('#pictures',obj.getImages(j.pictures,j.id)).replace('#view_num',j.view_num).replace('#viewers',obj.getViewers(j.viewers)).replace('#like_num',j.like_num).replace('#comment_num',j.comment_num);
			if(j.pages.length>0) d = d.replace('#vip','');
			else d = d.replace('#vip',' hide');
			return d;
		},
		getHtml : function(data,ag){
			var obj = this,
				ag = ag || {},
				htm = '',
				tmp = obj.dom.tmp
			;
			$.each(data,function(k,j){
				obj.dom.list.append(obj.rdata(tmp,j));
				var os = obj.dom.list.find('dt:last');
				os.find('.text a').click(function(){
					var url = $(this).attr('url');
					location.href = url+'&back_url='+getUrl()+'?args='+encodeURIComponent(str(obj.querys.args))+'&back_ScrollTop='+Base.turn.getScrollTop();
				})
				os.find('a.click').click(function(){
					if(_userInfo){
						var o = $(this),
							tp = o.attr('tp'),
							dt = o.parent().parent()
						;
						if(tp=='like'){
							var numo = o.find('span'),
								num = parseInt(numo.text())
							;
							$.ajax({
								url: '/shuoshuo/like/' + dt.attr('did'),
								data:{},
								dataType: 'json',
								success : function(dd) {
									num ++;
									if(dd.info=='点赞成功') numo.text(num);
									alert(dd.info);
								}
							})
						}
						else if(tp='talk'){
							location.href = $(this).attr('htm');
						}
					}
					else _followFunc();
				})
			})
			if(ag.stop) {
				log(ag.stop,Base.turn.getScrollTop());
				if(ag.stop==Base.turn.getScrollTop()) _isLoad = 1;
				if(_isLoad==0) $('body,html').scrollTop(ag.stop);
			}
		},
		getList : function(arg){
			var obj = this,
				args = {page:obj.querys.page},
				arg = arg || {};
			if(arg.clear==1) obj.dom.list.empty();
			obj.dom.list.append(obj.dom.load);
			obj.querys.is = 1;
			if(_kwd.length>0) args.kwd = _kwd;
			log(args);
			$.ajax({
				url: '/shuoshuo',
				data: args,
				dataType: 'json',
				success : function(dd) {
					log(dd);
					if(dd.length>0){
						obj.getHtml(dd,arg);
						obj.querys.is = 0;
					}
					else obj.querys.end = 1;
					obj.dom.list.find('.load').remove();
				},
				error : function(xhr, type) {
					log(' request failed'+xhr);
				}
			});
		},
		query : function(arg){
			var obj = this;
			obj.getList(arg);
		},
		getHis : function(){
			var obj = this;
			$('dl.his').html(function(){
				var htm = '',
					data = $.cookie(_key).split(',');
				data.reverse();
				$.each(data,function(k,j){
					if(j.length>0 && k<10) htm += '<dt><img src="img/time.png"/> '+unescape(j)+'</dt>';
				})
				return htm;
			}).find('dt').click(function(){
				var name = $(this).text().trim();
				obj.getHisFuc(name);
			})
		},
		getHisFuc : function(name){
			var obj = this,
				kwdHis = $.cookie(_key).split(',');
			if(kwdHis.indexOf(escape(name))==-1) {
				kwdHis.push(escape(name));
				$.cookie(_key,kwdHis.join(','));
			}
			obj.getHis();
			$('#searchBox').toggleClass('hide');
			if(name) _kwd = name;
			else {
				_kwd = '';
				name = '搜索';
			}
			$('.sbtn span').text(name);
			obj.querys.end = 0;
			obj.query({clear:1});
		},
		init : function(){
			var obj = this,
				ag = {},
				stop = Base.tools.getQueryString('back_ScrollTop');
			if(stop) ag.stop = parseInt(stop);
			obj.query(ag);
			Base.turn.get(obj,ag);
			$('dl.menus dt').click(function(){
				var o = $(this);
				_args.area = o.attr('d');
				o.parent().find('.active').removeClass('active');
				o.addClass('active');
				obj.query({clear:1});
			})
			//历史搜索
			if(!$.cookie(_key)) {
				$.cookie(_key,'');
			}
			obj.getHis();
			$('.sbtn').click(function(){
				$('#searchBox').toggleClass('hide');
			})
			$('a.back').click(function(){
				$('#searchBox').toggleClass('hide');
			})
			$('#searchBox .words>a').click(function(){
				var name = $(this).text().trim();
				obj.getHisFuc(name);
			})
			$('#searchBox a.ok').click(function(){
				var name = $('#searchBox .search input').val().trim();
				obj.getHisFuc(name);
			})
		}
	}
})();
MVC.init();
getUser(function(){
	$('.click').click(function(){
		if(_userInfo) location.href = $(this).attr('htm');
		else _followFunc();
	})
	if(_userInfo) {
		_getUserDetail(_userInfo.user_id,function(back){
			$('#headimgurl').html('<img src="'+back.headimgurl+'"/>');
		})
	}
})
//})