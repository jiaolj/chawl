getLocation.init(function(){
	(function(){
		var setGet = function(dom){
			var jq = $(dom);
			return {
				set :function(v){
					jq.val(v);
				},
				get : function(){
					return jq.val().trim();
				}
			}
		};
		var _argArea = 1,
			_lastPage = {},
			_isLoad = 0,
			_kwd = '',
			_order = {1:'',2:'new',3:'hot'},
			_seoTitle = {130:'精品专线',564:'落地配',929:'本地货车',926:'综合物流'},
			_cityName = _simpCity($('#cityname').text()),
			_starting,
			_destination,
			_key;
		window.MVC = {
			querys : {
				args : function(){
					var a = json(Base.tools.getQueryString('args')) || {};
					if(!a.sort) a.sort = 130;
					$('.seoTitle').text(_seoTitle[a.sort]);
					_starting = setGet('.where .starting span[sort="'+a.sort+'"] input');
					_destination = setGet('.where .destination span[sort="'+a.sort+'"] input');
					if(!a.findType) a.findType = 1;
					if(a.starting) _starting.set(a.starting);
					else {
						_starting.set(_cityName);
					}
					if(a.destination) {
						_destination.set(a.destination);
					}
					_key = 'kwd'+a.sort;
					$('span[sort="'+a.sort+'"]').show();
					$('a[sort="'+a.sort+'"]').addClass('active');
					return a;
				}(),
				is : 0,
				page : 1,
				end : 0,
			},
			dom : {
				list : $('#list'),
				load : '<div class="load"><img src="img/car_on.png">努力加载中...</div>',
				tmp : '\
					<dt url="#href">\
						<div class="flt logo1"><img class="logo" src="#logo" /><img class="vip#vip" src="img/list/vip.png"><img class="vip#member" src="img/list/renzheng.png"></div>\
						<div class="flt text1">\
							<div class="tit">#title</div>\
							<div class="phone">\
								<a href="tel:#phone"><span class="number" style="color:#df4b24">#phone</span></a>\
								<span class="star">\
									<span class="viewNum" style="color:#df4b24">#stars</span><span class="view">#people</span>\
								</span>\
							</div>\
							<div class="area">#address</div>\
						</div>\
						<br class="cb" />\
					</dt>\
				'
			},
			rdata : function(d,j){
				var obj = this;
				d = d.replace('#href','detail.html?id='+j.id+'&uid='+j.uid).replace('#title',j.title).replace('#phone',j.phone).replace('#phone',j.phone).replace('#view',j.view).replace('#address',j.address);
				
				if(j.isagree=='1' && j.is_refresh=='1'){
					if(j.is_vip=='1') d = d.replace('#vip','').replace('#member',' hide');
					else d = d.replace('#vip',' hide').replace('#member','');
				}
				else d = d.replace('#vip',' hide').replace('#member',' hide');
				/*if(j.is_vip=='1') {
					d = d.replace('#vip','').replace('#member',' hide');
				}
				else {
					d = d.replace('#vip',' hide');
					if(j.isagree=='1') d = d.replace('#member','');
					else d = d.replace('#member',' hide');
				}*/
				if(j.logo) d = d.replace('#logo',j.logo);
				else d = d.replace('#logo','http://www.chawuliu.com/uploads/page/default9d665cfbf7bbd3834f7accbeaeea423b.jpg');
				if(obj.querys.args.findType==3) d = d.replace('#people','人推荐').replace('#stars',j.support_num);
				else d = d.replace('#people','人浏览').replace('#stars',j.view);
				return d;
			},
			getHtml : function(data,ag){
				var obj = this,
					ag = ag || {},
					htm = '',
					tmp = obj.dom.tmp;
				$.each(data,function(k,j){
					obj.dom.list.append(obj.rdata(tmp,j)).find('dt:last').click(function(){
						var url = $(this).attr('url');
						location.href = url+'&back_url='+getUrl()+'?args='+encodeURIComponent(str(obj.querys.args))+'&back_ScrollTop='+Base.turn.getScrollTop();
					});
				})
				if(ag.stop) {
					log(ag.stop,Base.turn.getScrollTop());
					if(ag.stop==Base.turn.getScrollTop()) _isLoad = 1;
					if(_isLoad==0) $('body,html').scrollTop(ag.stop);
				}
			},
			getList : function(ag){
				var obj = this,
					ag = ag || {},
					argStr = '',
					arg = {},
					args = JSON.parse(JSON.stringify(obj.querys.args))
				;
				if(ag.clear==1) obj.dom.list.empty();
				obj.dom.list.append(obj.dom.load);
				obj.querys.is = 1;
				arg.page = obj.querys.page;
				arg.sort = args.sort;
				if(_argArea!=4){
					if(_argArea!=3){
						if(_starting.get()) arg.starting = _starting.get();
					}
					if(_argArea!=2){
						if(_destination.get()) arg.destination = _destination.get();
					}
				}
				if(_kwd.length>0) arg.kwd = _kwd;
				if(_order[args.findType].length>0) arg.order = _order[args.findType];
				log(arg);
				//setTimeout(function(){
					$.ajax({
						url : '/page/index',
						data : arg,
						dataType : 'json',
						success : function(dd) {
							log(dd);
							var lst = dd.page_list;
							if(lst.length>0){
								obj.getHtml(lst,ag);
								obj.querys.is = 0;
							}
							else {
								if(lst.length>0 && lst.length<=5){
									obj.getHtml(lst,ag);
								}
								if(_argArea==4){
									obj.querys.end = 1;
								}
								else if(_argArea==3){
									obj.querys.page = 1;
									_argArea = 4;
									obj.getList();
								}
								else if(_argArea==2){
									obj.querys.page = 1;
									_argArea = 3;
									obj.getList();
								}
								else{
									obj.querys.page = 1;
									obj.dom.list.append('<div class="moreText">以下为平台推荐的名片</div>');
									_argArea = 2;
									obj.getList();
								}
							}
							obj.dom.list.find('.load').remove();
						},
						error:function(jqXHR,textStatus) {
							log(' request failed'+textStatus);
						}
					});
				//},2000);
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
				if(kwdHis.indexOf(name)==-1) {
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
				$('.search-choice span').text(name);
				obj.querys.end = 0;
				obj.query({clear:1});
			},
			init : function(){
				var obj = this,
					ag = {init:1},
					args = json(str(obj.querys.args))
				;
				if(args.title=='精品专线'){
					$('#where130').show();
				}else if(args.title=='落地配'){
					$('#where100').show();
					$('#cartype').text(args.cartype);
				}else if(args.title=='本地货车'){
					$('#where101').show();
					$('#ctype').text(args.long + ' ' + args.ctype);
				}else if(args.title=='综合物流'){
					$('#where564').show();
					$('#goodstype').text(args.goodstype);
				}
				var stop = Base.tools.getQueryString('back_ScrollTop');
				if(stop) ag.stop = parseInt(stop);
				obj.query(ag);
				Base.turn.get(obj,ag);
				$('.query').click(function(){
					args.starting = _starting.get();
					args.destination = _destination.get();
					location.href = '?args=' + encodeURIComponent(str(args));
				})
				$('#change td[d="'+(args.findType-1)+'"]').addClass('active');
				$('#change td').click(function(){
					var ts = $(this),
						i = parseInt(ts.attr('d'));
					args.findType = i + 1;
					args.starting = _starting.get();
					args.destination = _destination.get();
					location.href = '?args=' + encodeURIComponent(str(args));
				})
				//历史搜索
				if(!$.cookie(_key)) {
					$.cookie(_key,'');
				}
				obj.getHis();
				$('.search-choice').click(function(){
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
})
setInterval(function(){
	if(!$('#cityname').text()) location.href = '';
},2000)