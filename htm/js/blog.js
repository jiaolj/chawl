getLocation.init(function(){
	(function(){
		var lbox = $('.leavebox'),
			lboxArea = $('.leavebox textarea'),
			cover = $('.cover-page'),
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
				args : function(){
					return json(Base.tools.getQueryString('args')) || {findType : 1,ctype:'车型',long:'车长',tp:'',citys:{'1':'出发地','2':'目的地'}};
				}(),
				is : 0,
				page : 1,
				end : 0,
				ajaxUrl : '/shuoshuo'
			},
			dom : {
				list : $('#list'),
				load : '<div class="load"><img src="img/bottom/find_on.png">努力加载中...</div>',
				tmp : '\
					<dt did="#id"> \
						<div class="photo flt"><img class="logo" src="#headimgurl" /><img class="vip" src="img/blog/vip.png" /></div> \
						<div class="tit flt"> \
							<p>#nickname</p> \
							<p class="time">#ctime</p> \
						</div> \
						<br class="cb"/> \
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
			},//<span class="frt"><img src="img/blog/see.png" /><span>#view_num人看过</span></span> <br class="cb"/>
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
				return d.replace('#ctime',Base.tools.past_time(j.ctime)).replace('#user_id',j.user_id).replace('#ii',j.id).replace('#id',j.id).replace('#id',j.id).replace('#headimgurl',j.user_info[0] && j.user_info[0].headimgurl).replace('#nickname',j.user_info[0] && j.user_info[0].nickname).replace('#content',Base.tools.sub(j.content,200)).replace('#pictures',obj.getImages(j.pictures,j.id)).replace('#view_num',j.view_num).replace('#viewers',obj.getViewers(j.viewers)).replace('#like_num',j.like_num).replace('#comment_num',j.comment_num);
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
			getList : function(){
				var obj = this;
				obj.dom.list.append(obj.dom.load);
				obj.querys.is = 1;
				$.ajax({
					url: obj.querys.ajaxUrl,
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
			query : function(){
				var obj = this;
				obj.getList();
			},
			init : function(){
				var obj = this;
				$('#from').text(obj.querys.args.citys['1']);
				$('#to').text(obj.querys.args.citys['2']);
				$('#ctype').text(obj.querys.args.ctype);
				$('#long').text(obj.querys.args.long);
				obj.query();
				$('#findtype>div').click(function(){
					var ts = $(this),
						i = ts.index('#findtype>div');
					obj.querys.args.findType = i + 1;
					location.href = '?args=' + str(obj.querys.args);
				})
				$('.choice').click(function(){
					obj.querys.args.url = function(){var l = location.href.split('/').pop(),r = l.split('?')[0];return r}();
					var k = $(this).attr('k'),
						v = $(this).attr('v')
					;
					if(k=='from' || k=='to'){
						obj.querys.args.tp = v;
						location.href = 'city.html?args='+str(obj.querys.args);
					}else if(k=='ctype'){
						location.href = 'ctype.html?args='+str(obj.querys.args);
					}else if(k=='long'){
						obj.querys.args.back=1;
						location.href = 'long.html?args='+str(obj.querys.args);
					}
				});
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
	var getHistory = function(from_user,to_user,suc){
		_getUserDetail(from_user,function(back){
			var listAll = [];
			$.ajax({
				url: '/user/chat/query',
				data: {from_user:from_user,to_user:to_user},
				dataType: 'json',
				success : function(msg) {
					$.each(msg,function(k,j){
						j.t = 'other';
						j.headimgurl = back.headimgurl;
						listAll.push(j);
					})
					_getUserDetail(to_user,function(back){
						$.ajax({
							url: '/user/chat/query',
							data: {from_user:to_user,to_user:from_user},
							dataType: 'json',
							success : function(msg) {
								$.each(msg,function(k,j){
									j.t = 'my';
									j.headimgurl = back.headimgurl;
									listAll.push(j);
								})
								listAll.sort(function(a,b){return a.ctime-b.ctime});
								suc && suc(listAll);
							},
							error : function(xhr, type) {
								log(' request failed'+xhr);
							}
						})
					})
				},
				error : function(xhr, type) {
					log(' request failed'+xhr);
				}
			})
		})
	}
	getHistory(10238,10239,function(msg){
	});
})