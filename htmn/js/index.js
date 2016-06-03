getLocation.init(function(){
	(function(){
		var _argArea = 1,
			_isLoad = 0;
		window.MVC = {
			querys : {
				args : function(){
					return json(Base.tools.getQueryString('args'));
				}(),
				is : 0,
				page : 1,
				end : 0,
				ajaxUrl : '',
				cache : []
			},
			dom : {
				list : $('#list'),
				load : '<div class="load"><img src="img/bottom/find_on.png">努力加载中...</div>',
				tmp : '\
					<a url="#href"><dt>\
						<div class="flt logo1"><img class="logo" src="#logo" /><img class="vip#renzheng" src="img/list/renzheng.png"><img class="vip#vip" src="img/list/vip.png"></div>\
						<div class="flt text1">\
							<div class="tit">#title</div>\
							<div class="phone">\
								<span>#phone</span>\
								<span class="star">\
									<span class="viewNum">#stars</span><span class="view">人浏览</span>\
								</span>\
							</div>\
							<div class="area">#address</div>\
						</div>\
						<br class="cb" />\
					</dt></a>\
				',
				tmp_car : '<dt i="#ii" car_id="#car_id"> \
					<div class="flt logo"><span j="1"><img class="headimg" j="1" src="#headimgurl"><br /><img src="img/other/car.png"></span></div> \
					<div class="flt text"> \
						<p class="tit starting"><span j="1"><img src="img/other/in.png"> #starting</span></p> \
						<p class="tit destination"><span j="1"><img src="img/other/out.png"> #destination</span></p> \
						<p class="remark" title="#rmktitle"><span j="1">#remark</span></p> \
					</div> \
					<div class="contact"> \
						#time2 <br /> \
						<img class="cimg" is_tel="#is_tel" phone="#phone" user_id="#user_id" src="img/find/dialup.png"> \
					</div> \
					<br class="cb" /> \
				</dt>',
				tmp_goods : '<dt i="#ii" goods_id="#goods_id"> \
					<div class="flt logo"><span j="1"><img class="headimg" j="1" src="#headimgurl"><br /><img src="img/other/goods.png"></span></div> \
					<div class="flt text"> \
						<p class="tit starting"><span j="1"><img src="img/other/in.png"> #starting</span></p> \
						<p class="tit destination"><span j="1"><img src="img/other/out.png"> #destination</span></p> \
						<p class="remark" title="#rmktitle"><span j="1">#remark</span></p> \
					</div> \
					<div class="contact"> \
						#time2 <br /> \
						<img class="cimg" is_tel="#is_tel" phone="#phone" user_id="#user_id" src="img/find/dialup.png"> \
					</div> \
					<br class="cb" /> \
				</dt>'
			},
			rdata : function(i,d,j){
				var obj = this;
				if(obj.querys.args.findType==1) {
					d = d.replace('#href','detail.html?id='+j.id+'&uid='+j.uid).replace('#stars',j.view/*getStar(j.support_num || 0)*/).replace('#title',j.title).replace('#phone',j.phone).replace('#view',j.view).replace('#address',j.address);
					if(j.is_vip=='1') d = d.replace('#vip','');
					else d = d.replace('#vip',' hide');
					//if(j.isagree=='1') d = d.replace('#renzheng','');
					//else d = d.replace('#renzheng',' hide');
					d = d.replace('#renzheng',' hide');
					if(j.logo) d = d.replace('#logo',j.logo);
					else d = d.replace('#logo','http://www.chawuliu.com/uploads/page/default9d665cfbf7bbd3834f7accbeaeea423b.jpg');
					return d;
				}
				else {
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
					return d.replace('#remark',remark).replace('#rmktitle',j.remark).replace('#car_no',j.car_no).replace('#goods_id',j.goods_id).replace('#car_id',j.car_id).replace('#ii',i).replace('#is_tel',j.is_tel).replace('#user_id',j.user_id).replace('#phone',j.phone).replace('#headimgurl',j.user_info[0] && j.user_info[0].headimgurl).replace('#starting',j.starting).replace('#destination',j.destination).replace('#city',j.city).replace('#model',j.model.replace('不限','未知').replace('所需车辆','未知').replace('车长车型','未知')).replace('#length',parseFloat(j.length) && parseFloat(j.length).toFixed(1)+'米' || j.length.replace('不限','未知')).replace('#volume',_None(j.volume)).replace('#weight',_None(j.weight)).replace('#time1',Base.tools.int_to_str(j.ctime)).replace('#time2',Base.tools.int_to_str(j.ctime,1));
				}
			},
			getHtml : function(data,ag){
				var obj = this,
					ag = ag || {},
					htm = '',
					tmp = function(){
						if(obj.querys.args.findType==1) return obj.dom.tmp;
						else if(obj.querys.args.findType==2) return obj.dom.tmp_goods;
						else if(obj.querys.args.findType==3) return obj.dom.tmp_car;
					}()
				;
				if(!tmp) return;
				$.each(data,function(k,j){
					obj.dom.list.append(obj.rdata(k,tmp,j)).find('a:last').click(function(){
						var url = $(this).attr('url');
						location.href = url+'&back_url='+getUrl()+'?args='+encodeURIComponent(str(obj.querys.args))+'&back_ScrollTop='+Base.turn.getScrollTop();
					})
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
					arg = {};
				if(ag.clear==1) obj.dom.list.empty();
				obj.dom.list.append(obj.dom.load);
				obj.querys.is = 1;
				arg.page = obj.querys.page;
				if(obj.querys.args.findType==1) {
					arg.sort = 130;
				}
				if(_argArea!=3){
					if(obj.querys.args.citys['1']!='出发地') arg.starting = obj.querys.args.citys['1'];
					if(obj.querys.args.citys['2']!='目的地') arg.destination = obj.querys.args.citys['2'];
				}
				$.ajax({
					url: obj.querys.ajaxUrl,
					data: arg,
					dataType: 'json',
					success : function(dd) {
						log(dd);
						var lst = dd.list || dd.page_list;
						if(lst.length>5){
							obj.querys.cache = lst;
							obj.getHtml(lst,ag);
							obj.querys.is = 0;
						}
						else {
							if(lst.length>0 && lst.length<=5){
								obj.getHtml(lst,ag);
							}
							if(_argArea==3){
								obj.querys.end = 1;
							}else{
								var txt = '';
								if(obj.querys.args.findType==1) txt = '名片';
								else if(obj.querys.args.findType==2) txt = '货源';
								else if(obj.querys.args.findType==3) txt = '车源';
								obj.dom.list.append('<div class="moreText">以下为平台推荐的'+txt+'</div>');
								obj.querys.page = 1;
								_argArea = 3;
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
				if(!obj.querys.args) obj.querys.args = {findType : 1,ctype:'车型',long:'车长',tp:'',citys:{'1':$('#cityname').text(),'2':'全国','3':$('#cityname').text()}};
				if(obj.querys.args.tp=='3') obj.querys.args.citys['1'] = obj.querys.args.citys['3'];
				var args = JSON.parse(JSON.stringify(obj.querys.args));
				$('#from').html(obj.querys.args.citys['1'].replace('出发地','全国')+' <b></b>');
				$('#to').html(obj.querys.args.citys['2'].replace('目的地','全国')+' <b></b>');
				obj.querys.args.citys['1'] = obj.querys.args.citys['1'].replace('全国','出发地');
				obj.querys.args.citys['2'] = obj.querys.args.citys['2'].replace('全国','目的地');
				$('.cityname').text(obj.querys.args.citys['3']);
				if(args.findType==1) {
					obj.querys.ajaxUrl = '/page/index'
				}
				else if(args.findType==2) {
					obj.querys.ajaxUrl = '/viewgoods';
				}
				else if(args.findType==3) {
					obj.querys.ajaxUrl = '/viewcar';
				}
				$('#change td[d="'+(args.findType-1)+'"]').addClass('active');
				
				var stop = Base.tools.getQueryString('back_ScrollTop');
				if(stop) ag.stop = parseInt(stop);
				obj.query(ag);
				Base.turn.get(obj,ag);
				
				$('.choice').click(function(){
					args.url = getUrl();
					var k = $(this).attr('k'),
						v = $(this).attr('v')
					;
					if(k=='from' || k=='to'){
						args.tp = v;
						args.page = 'card';
						location.href = 'city.html?args='+encodeURIComponent(str(args));
					}
					else if(k=='change'){
						var cge = args.citys[1];
						args.citys[1] = args.citys[2].replace('目的地','出发地');
						args.citys[2] = cge.replace('出发地','目的地');
						location.href = '?args='+encodeURIComponent(str(args));
					}
				});
				$('.cityname').click(function(){
					args.url = getUrl();
					args.tp = '3';
					args.page = 'card';
					location.href = 'city.html?args='+encodeURIComponent(str(args));
				});
				$('#change td').click(function(){
					var ts = $(this),
						i = parseInt(ts.attr('d'));
					args.findType = i + 1;
					location.href = '?args=' + encodeURIComponent(str(args));
				})
				$('#list').click(function(e){
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
						location.href = url+'&back_url='+getUrl()+'?args='+encodeURIComponent(str(obj.querys.args))+'&back_ScrollTop='+Base.turn.getScrollTop();
					}
				});
			}
		}
	})();

	MVC.init();
	getUser(function(){
		$('.click').click(function(){
			if(_userInfo) location.href = $(this).attr('htm');
			else _followFunc();
		})
		$('#to-msg').click(function(){
			if(_userInfo) location.href = 'msg.html?args='+encodeURIComponent(str(_msgAll));
			else _followFunc();
		})
		if(_userInfo){
			var conn = new Easemob.im.Connection(),
				_num = 0,
				_msgAll = {},
				_getNum = function(){
					if(_num>0) $('#num').show().text(_num);
					else $('#num').hide();
				}
			;
			window.onbeforeunload = function(e) {conn.close()};
			conn.init({
				https : Easemob.im.config.https,
				url: Easemob.im.config.xmppURL,
				//当连接成功时的回调方法
				onOpened : function() {
					conn.setPresence();
				},
				//收到文本消息时的回调方法
				onTextMessage : function(msg) {
					_num ++;
					_getNum();
					if(!_msgAll[msg.from]) _msgAll[msg.from] = {nickname:'',headimgurl:'',data:[]};
					_msgAll[msg.from].data.push(msg.data);
					$.ajax({
						url: '/chat/userinfo',
						data: {user_id:msg.from},
						dataType: 'json',
						success : function(dd) {
							_msgAll[msg.from].headimgurl = dd.headimgurl;
							_msgAll[msg.from].nickname = dd.nickname;
						},
						error : function(xhr, type) {
							log(' request failed'+xhr);
						}
					})
				},
				//异常时的回调方法
				onError : function(msg) {
					log(str(msg));
				}
			});
			conn.open({
				user : _userInfo.user_id,
				pwd : '14e1b600b1fd579f47433b88e8d85291',
				appKey : Easemob.im.config.appkey
			})
		}
	})
})