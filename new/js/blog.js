//getLocation.init(function(){
(function(){
	var lbox = $('.leavebox'),
		lboxArea = $('.leavebox textarea'),
		cover = $('.cover-page'),
		_args = {}
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
		}
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
			load : '<div class="load"><img src="img/bottom/find_on.png">努力加载中...</div>',
			tmp : '\
				<dt did="#id"> \
					<div class="titles"> \
						<div class="tit flt"> \
							<img class="logo" src="#headimgurl" />#nickname <img class="vip" src="img/blog/v.png" /> \
						</div> \
						<a class="card frt">查看名片</a> \
						<br class="cb"/> \
					</div> \
					<div class="text"><a href="bdetail.html?id=#id"> \
						<div class="title">#content</div> \
						<div class="img" i="#ii">#pictures</div> \
					</a></div> \
					<div class="see"> \
						#viewers \
					</div> \
					<div class="action"> \
						<a class="click" tp="like"><img class="left" src="img/blog/like.png" /><span>#like_num</span></a> <img class="left" src="img/blog/talk.png" /><span>#comment_num</span> <img class="left" src="img/blog/share.png" /> \
						<span class="frt"><a class="click" tp="talk" htm="mdetail.html?id=#user_id"><img src="img/blog/chat.png" /></a></span> \
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
			return d.replace('#user_id',j.user_id).replace('#ii',j.id).replace('#id',j.id).replace('#id',j.id).replace('#headimgurl',j.user_info[0] && j.user_info[0].headimgurl).replace('#nickname',j.user_info[0] && j.user_info[0].nickname).replace('#content',Base.tools.sub(j.content,200)).replace('#pictures',obj.getImages(j.pictures,j.id)).replace('#view_num',j.view_num).replace('#viewers',obj.getViewers(j.viewers)).replace('#like_num',j.like_num).replace('#comment_num',j.comment_num);
		},
		getHtml : function(data){
			var obj = this,
				htm = '',
				tmp = obj.dom.tmp
			;
			for(var i=0;i<data.length;i++) obj.dom.list.append(obj.rdata(tmp,data[i])).find('dt:last a.click').click(function(){
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
		},
		getList : function(arg){
			var obj = this,
				arg = arg || {};
			if(arg.clear==1) obj.dom.list.empty();
			obj.dom.list.append(obj.dom.load);
			obj.querys.is = 1;
			$.ajax({
				url: '/shuoshuo',
				data: {page:obj.querys.page},
				dataType: 'json',
				success : function(dd) {
					log(dd);
					if(dd.length>0){
						obj.getHtml(dd);
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
		init : function(){
			var obj = this;
			obj.query();
			$('dl.menus dt').click(function(){
				var o = $(this);
				_args.area = o.attr('d');
				o.parent().find('.active').removeClass('active');
				o.addClass('active');
				obj.query({clear:1});
			})
			Base.turn.get(obj);
		}
	}
})();
getUser(function(){
	$('.click').click(function(){
		if(_userInfo) location.href = $(this).attr('htm');
		else _followFunc();
	})
	MVC.init();
})
//})