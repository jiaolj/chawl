getLocation.init(function(){
	(function(){
		var _tp = Base.tools.getQueryString('tp'),
			_isLoad = 0,
			_argArea = 0;
		if(_tp) _tp = parseInt(_tp);
		else _tp = 2;
		window.MVC = {
			querys : {
				args : function(){
					var args = json(Base.tools.getQueryString('args'));
					if(!args){
						args = {findType : _tp, citys:{'1':'全国','2':'全国'}};//$('#cityname').text().replace('市','')
					};
					return  args;
				}(),
				is : 0,
				page : 1,
				end : 0,
				ajaxUrl : '',
				cache : []
			},
			dom : {
				list : $('#list'),
				load : '<div class="load"><img src="img/car_on.png">努力加载中...</div>',
				tmp_car : '<dt i="#ii" car_id="#car_id"> \
					<div class="logo"><span j="1"><img class="headimg" j="1" src="#headimgurl"><br /><img class="vip" src="img/other/car.png"></span></div> \
					<div class="text"> \
						<p class="tit starting"><span j="1"><img src="img/other/in.png"> #starting</span></p> \
						<p class="tit destination"><span j="1"><img src="img/other/out.png"> #destination</span></p> \
						<p class="remark" title="#rmktitle"><span j="1">#remark</span></p> \
					</div> \
					<div class="contact"> \
						#time2 <br /> \
						<img class="cimg" is_tel="#is_tel" phone="#phone" user_id="#user_id" src="img/find/dialup.png"> \
					</div><br class="cb" />\
				</dt>',
				tmp_goods : '<dt i="#ii" goods_id="#goods_id"> \
					<div class="logo"><span><img class="headimg" src="#headimgurl"></span><img class="vips#vip" src="img/blog/vip.png"></div> \
					<div class="text"> \
						<p class="tit starting"><span><img src="img/other/in.png"> #starting</span></p> \
						<p class="tit destination"><span><img src="img/other/out.png"> #destination</span></p> \
						<p class="remark" title="#rmktitle"><span>#remark</span></p> \
					</div> \
					<div class="contact"> \
						#time2 <br /> \
						<img class="cimg" is_tel="#is_tel" phone="#phone" user_id="#user_id" src="img/find/dialup.png"> \
					</div><br class="cb" />\
				</dt>'
			},
			rdata : function(i,d,j){
				var numList = j.remark.match(/[0-9]+/ig),
					remark = j.remark,
					phone = '';
				if(numList){
					$.each(numList,function(k,j){
						if(j.length>5) {
							phone = j;
							remark = remark.replace(phone.substring(3,9),'***');
						}
					})
				};
				d=d.replace('#remark',remark).replace('#rmktitle',j.remark).replace('#car_no',j.car_no).replace('#car_id',j.car_id).replace('#goods_id',j.goods_id).replace('#ii',i).replace('#is_tel',j.is_tel).replace('#user_id',j.user_id).replace('#phone',j.phone).replace('#headimgurl',j.user_info[0].headimgurl).replace('#starting',j.starting).replace('#destination',j.destination).replace('#city',j.city).replace('#volume',_None(j.volume)).replace('#weight',_None(j.weight)).replace('#time1',Base.tools.int_to_str(j.ctime)).replace('#time2',Base.tools.past_time(j.ctime));
				if(j.length=='不限') d = d.replace('#length','未知');
				else d=d.replace('#length',parseFloat(j.length) && parseFloat(j.length).toFixed(1)+'米');
				if(j.model=='车长车型') d=d.replace('#model','未知');
				else d=d.replace('#model',j.model.replace('所需车辆','未知').replace('不限','未知'));
				if(j.user_info.pages.length>0) d=d.replace('#vip','');
				else d=d.replace('#vip',' hide');
				return d;
			},
			getHtml : function(data,ag){
				var obj = this,
					ag = ag || {},
					htm = '',
					tmp = function(){
						if(obj.querys.args.findType==1) return obj.dom.tmp_car;
						else  return obj.dom.tmp_goods;
					}()
				;
				$.each(data,function(k,j){
					if(j.user_info && j.user_info[0]) obj.dom.list.append(obj.rdata(k,tmp,j)).find('dt:last').click(function(e){
						var o = $(this),
							tar = e.target,
							ths = $(tar),
							prrt = ths.parent().parent()
						;
						if(tar.className=='cimg'){
							var cnt = $('.consult'),
								cnt1 = $('.consult>a').eq(0),
								cnt2 = $('.consult>a').eq(1)
							;
							var pi = prrt.attr('i'),
								ni = cnt.attr('i'),
								phone = ths.attr('phone')
							;
							if(phone=='-1'){
								cnt2.css('color','#ccc');
								cnt2.attr('href','#');
							}else{
								cnt2.css('color','#fff');
								cnt2.attr('href','tel:'+phone);
							}
							cnt1.attr('href','mdetail.html?id='+ths.attr('user_id')+'&ref='+new Date().getTime());
							if(pi!=ni){
								cnt.attr('i',pi);cnt.show();
							}else{
								if(cnt.css('display')=='none') cnt.show();
								else cnt.hide();
							}
						}else{
							var car_id = o.attr('car_id'),
								goods_id = o.attr('goods_id'),
								url = 'mypubs.html'
							;
							if(goods_id) url+='?goods_id='+goods_id;
							else if(car_id) url+='?car_id='+car_id;
							location.href = url+'&back_url='+getUrl()+'?args='+encodeURIComponent(str(obj.querys.args))+'&back_ScrollTop='+Base.turn.getScrollTop();
						}
					});
				})
				if(ag.stop) {
					if(ag.stop==Base.turn.getScrollTop()) _isLoad = 1;
					if(_isLoad==0) $('body,html').scrollTop(ag.stop);
				}
			},
			getList : function(ag){
				var obj = this,
					ag = ag || {},
					arg = {}
				;
				if(ag.clear==1) obj.dom.list.empty();
				obj.dom.list.append(obj.dom.load);
				obj.querys.is = 1;
				arg.page = obj.querys.page;
				if(_argArea!=4){
					if(_argArea!=3){
						if(obj.querys.args.citys['1']!='出发地') arg.starting = obj.querys.args.citys['1'].split(' ').pop();
					}
					if(_argArea!=2){
						if(obj.querys.args.citys['2']!='目的地') arg.destination = obj.querys.args.citys['2'].split(' ').pop();
					}
				}
				log(arg);
				$.ajax({
					url: obj.querys.ajaxUrl,
					data: arg,
					dataType: 'json',
					success : function(dd) {
						log(dd);
						var lst = dd.list;
						obj.querys.is = 0;
						if(lst.length>5){
							obj.querys.cache = lst;
							obj.getHtml(lst,ag);
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
								if(obj.querys.args.findType==1) {
									var txt = '车源';
								}else if(obj.querys.args.findType==2) {
									var txt = '货源';
								}
								obj.dom.list.append('<div class="moreText">以下为平台推荐的'+txt+'</div>');
								obj.querys.page = 1;
								_argArea = 2;
								obj.getList();
							}
						}
						obj.dom.list.find('.load').remove();
					},
					error : function(jqXHR,textStatus) {
						log(' request failed'+textStatus);
					}
				});
			},
			query : function(ag){
				var obj = this;
				obj.getList(ag);
			},
			init : function(){
				var obj = this,
					ag = {};
				if(obj.querys.args.findType==1) {
					obj.querys.ajaxUrl = '/viewcar';
					$('#findtype>div').eq(0).addClass('active');
				}
				else {
					obj.querys.ajaxUrl = '/viewgoods';
					$('#findtype>div').eq(1).addClass('active');
				}
				$('.from').html(obj.querys.args.citys['1'].split(' ').pop().replace('出发地','全国')+' <b></b>');
				$('.to').html(obj.querys.args.citys['2'].split(' ').pop().replace('目的地','全国')+' <b></b>');
				obj.querys.args.citys['1'] = obj.querys.args.citys['1'].replace('全国','出发地');
				obj.querys.args.citys['2'] = obj.querys.args.citys['2'].replace('全国','目的地');
				
				$('#ctype').text(obj.querys.args.ctype);
				$('#long').text(obj.querys.args.long);
				var stop = Base.tools.getQueryString('back_ScrollTop');
				if(stop) ag.stop = parseInt(stop);
				obj.query(ag);
				Base.turn.get(obj,ag);
				$('#findtype>div').click(function(){
					var ts = $(this);
					obj.querys.args.findType = parseInt(ts.attr('i'));
					location.href = '?args=' + encodeURIComponent(str(obj.querys.args));
				})
				$('.choice').click(function(){
					obj.querys.args.url = function(){var l = location.href.split('/').pop(),r = l.split('?')[0];return r}();
					var k = $(this).attr('k'),
						v = $(this).attr('v')
					;
					if(k=='from' || k=='to'){
						obj.querys.args.tp = v;
						location.href = 'city.html?args='+encodeURIComponent(str(obj.querys.args));
					}
					else if(k=='change'){
						var cge = obj.querys.args.citys[1];
						obj.querys.args.citys[1] = obj.querys.args.citys[2].replace('目的地','出发地');
						obj.querys.args.citys[2] = cge.replace('出发地','目的地');
						location.href = '?args='+encodeURIComponent(str(obj.querys.args));
					}
				});
				Base.turn.get(obj);
				/*$('#list').click(function(e){
					var tar = e.target,
						ths = $(tar),
						prrt = ths.parent().parent(),
						remark = prrt.find('.text p.remark').attr('title'),
						cnt = $('.consult'),
						cnt1 = $('.consult>a').eq(0),
						cnt2 = $('.consult>a').eq(1);
					if(tar.className=='cimg'){
						var pi = prrt.attr('i'),
							ni = cnt.attr('i'),
							phone = ths.attr('phone')
						;
						if(phone=='-1'){
							cnt2.css('color','#ccc');
							cnt2.attr('href','#');
						}else{
							cnt2.css('color','#fff');
							cnt2.attr('href','tel:'+phone);
						}
						cnt1.attr('href','mdetail.html?id='+ths.attr('user_id')+'&ref='+new Date().getTime());
						if(pi!=ni){
							cnt.attr('i',pi);cnt.show();
						}else{
							if(cnt.css('display')=='none') cnt.show();
							else cnt.hide();
						}
					}
					if(ths.attr('j')=='1'){
						var car_id = prrt.parent().attr('car_id'),
							goods_id = prrt.parent().attr('goods_id'),
							url = 'mypubs.html'
						;
						if(goods_id) url+='?goods_id='+goods_id;
						else if(car_id) url+='?car_id='+car_id;
						location.href = url+'&back_url='+getUrl()+'?args='+str(obj.querys.args)+'&back_ScrollTop='+Base.turn.getScrollTop();
					}
				})*/
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