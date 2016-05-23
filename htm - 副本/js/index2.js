(function(){
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
			load : '<div class="load">拼命加载中...</div>',
			tmp : '\
				<a href="#href"><dt>\
					<div class="flt logo1"><img class="logo" src="#logo" /><img class="vip#vip" src="img/list/vip.png"></div>\
					<div class="frt text1">\
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
			tmp_car : '<dt i="#ii" d="#car_id" t="car.html"> \
				<div class="flt logo"><span j="1"><img class="br" j="1" src="#headimgurl"><br /><img src="img/other/car.png"></span></div> \
				<div class="flt text"> \
					<p class="tit starting"><span j="1"><img src="img/other/in.png"> #starting</span></p> \
					<p class="tit destination"><span j="1"><img src="img/other/out.png"> #destination</span></p> \
					<p><img src="img/find/time.png"><span j="1"> #time1 装</span></p> \
					<p><img src="img/find/truck.png"><span j="1"> #car_no #length #model</span></p> \
				</div> \
				<div class="contact"> \
					#time2 <br /> \
					<img class="cimg" is_tel="#is_tel" phone="#phone" user_id="#user_id" src="img/find/dialup.png"> \
				</div> \
				<br class="cb" /> \
			</dt>',
			tmp_goods : '<dt i="#ii" d="#goods_id" t="goods.html"> \
				<div class="flt logo"><span j="1"><img class="br" j="1" src="#headimgurl"><br /><img src="img/other/goods.png"></span></div> \
				<div class="flt text"> \
					<p class="tit starting"><span j="1"><img src="img/other/in.png"> #starting</span></p> \
					<p class="tit destination"><span j="1"><img src="img/other/out.png"> #destination</span></p> \
					<p><img src="img/find/time.png"><span j="1"> #time1 装</span></p> \
					<p><img src="img/find/goodstype.png"><span j="1"> 配件 #weight吨 #volume方 需 #length #model</span></p> \
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
				d = d.replace('#href','detail.html?id='+j.id+'&ref='+new Date().getTime()).replace('#stars',j.view/*getStar(j.support_num || 0)*/).replace('#title',j.title).replace('#phone',j.phone).replace('#view',j.view).replace('#address',j.address);
				if(j.is_vip=='1') d = d.replace('#vip','');
				else d = d.replace('#vip',' hide');
				if(j.logo) d = d.replace('#logo',j.logo);
				else d = d.replace('#logo','http://www.chawuliu.com/uploads/page/default9d665cfbf7bbd3834f7accbeaeea423b.jpg');
				return d;
			}
			else return d.replace('#car_no',j.car_no).replace('#goods_id',j.goods_id).replace('#ii',i).replace('#is_tel',j.is_tel).replace('#user_id',j.user_id).replace('#phone',j.user_info[0] && j.user_info[0].phone).replace('#headimgurl',j.user_info[0] && j.user_info[0].headimgurl).replace('#starting',j.starting).replace('#destination',j.destination).replace('#city',j.city).replace('#model',j.model.replace('不限','未知').replace('所需车辆','未知').replace('车长车型','未知')).replace('#length',parseFloat(j.length) && parseFloat(j.length).toFixed(1)+'米' || j.length.replace('不限','未知')).replace('#volume',_None(j.volume)).replace('#weight',_None(j.weight)).replace('#time1',Base.tools.int_to_str(j.ctime)).replace('#time2',Base.tools.int_to_str(j.ctime,1));
		},
		getHtml : function(data,i){
			var obj = this,
				htm = '',
				tmp = function(){
					if(obj.querys.args.findType==1) return obj.dom.tmp;
					else if(obj.querys.args.findType==2) return obj.dom.tmp_goods;
					else if(obj.querys.args.findType==3) return obj.dom.tmp_car;
				}()
			;
			if(!tmp) return;
			if(i) obj.dom.list.html(function(){for(var i=0;i<data.length;i++) htm += obj.rdata(i,tmp,data[i]);return htm});
			else for(var i=0;i<data.length;i++) obj.dom.list.append(obj.rdata(i,tmp,data[i]));
		},
		getList : function(i){
			var obj = this,arg = {};
			obj.dom.list.append(obj.dom.load);
			obj.querys.is = 1;
			arg.page = obj.querys.page;
			if(obj.querys.args.findType==1) {
				arg.sort = 130;
			}
			if(obj.querys.args.citys['1']!='出发地') arg.starting = obj.querys.args.citys['1'];
			if(obj.querys.args.citys['2']!='目的地') arg.destination = obj.querys.args.citys['2'];
			//log(arg);
			//log(obj.querys.ajaxUrl);
			$.ajax({
				url: obj.querys.ajaxUrl,
				data: arg,
				dataType: 'json',
				success : function(dd) {
					log(dd);
					var lst = dd.list || dd.page_list;
					if(lst.length>0){
						obj.querys.cache = lst;
						obj.getHtml(lst,i);
						obj.querys.is = 0;
					}
					else obj.querys.end = 1;
					obj.dom.list.find('.load').remove();
				},
				error : function(jqXHR,textStatus) {
					log(' request failed'+textStatus);
				}
			});
		},
		query : function(){
			var obj = this;
			obj.getList(1);
		},
		init : function(){
			var obj = this;
			if(!obj.querys.args) obj.querys.args = {findType : 1,ctype:'车型',long:'车长',tp:'',citys:{'1':$('#cityname').text().replace('市',''),'2':'全国'}};
			var args = JSON.parse(JSON.stringify(obj.querys.args));
			$('#from').text(obj.querys.args.citys['1']);
			$('#to').text(obj.querys.args.citys['2']);
			obj.querys.args.citys['1'] = obj.querys.args.citys['1'].replace('全国','出发地');
			obj.querys.args.citys['2'] = obj.querys.args.citys['2'].replace('全国','目的地');
			if(args.findType==1) {
				//var py = codefans_net_CC2PY(obj.querys.args.citys['1']).toLowerCase();
				//if(py=='chufadi' || py=='mudidi') py = $('#cityname').attr('py');
				//obj.querys.ajaxUrl = '/index2/'+py;
				obj.querys.ajaxUrl = '/page/index'
				$('#change td').eq(0).addClass('active');
			}
			else if(args.findType==2) {
				obj.querys.ajaxUrl = '/viewgoods';
				$('#change td').eq(1).addClass('active');
			}
			else if(args.findType==3) {
				obj.querys.ajaxUrl = '/viewcar';
				$('#change td').eq(2).addClass('active');
			}
			Base.turn.get(obj);
			obj.query();
			$('.click').click(function(){
				var arg = '?url='+getUrl()+'&ref='+new Date().getTime();
				if(user_info.phone && user_info.phone.length>5) location.href = 'pubtype.html'+arg;
				else location.href = 'reg.html'+arg;
			})
			$('.choice').click(function(){
				args.url = function(){var l = location.href.split('/').pop(),r = l.split('?')[0];return r}();
				var k = $(this).attr('k'),
					v = $(this).attr('v')
				;
				if(k=='from' || k=='to'){
					args.tp = v;
					if(args.findType==1) args.page = 'card';
					location.href = 'city.html?args='+str(args);
				}
				else if(k=='change'){
					var cge = args.citys[1];
					args.citys[1] = args.citys[2].replace('目的地','出发地');
					args.citys[2] = cge.replace('出发地','目的地');
					location.href = '?args='+str(args);
				}
			});
			$('#change td').click(function(){
				var ts = $(this),
					i = parseInt(ts.attr('d'));
				args.findType = i + 1;
				location.href = '?args=' + str(args);
			})
			$('#list').click(function(e){
				var tar = e.target,
					ths = $(tar),
					prrt = ths.parent().parent(),
					cnt = $('.consult'),
					cnt1 = $('.consult>a').eq(0),
					cnt2 = $('.consult>a').eq(1);
				if(tar.className=='cimg'){
					var pi = prrt.attr('i'),
						ni = cnt.attr('i')
					;
					if(ths.attr('is_tel')=='0') cnt2.css('color','#ccc').attr('href','tel:'+ths.attr('phone'));
					else cnt2.css('color','#fff').attr('is_tel','1');
					cnt1.attr('href','mdetail.html?id='+ths.attr('user_id')+'&ref='+new Date().getTime());
					if(pi!=ni){
						cnt.attr('i',pi);cnt.show();
					}else{
						if(cnt.css('display')=='none') cnt.show();
						else cnt.hide();
					}
				}
				if(ths.attr('j')=='1'){
					location.href = prrt.parent().attr('t')+'?args='+str(obj.querys.cache[parseInt(prrt.parent().attr('i'))]);
				}
			});
		}
	}
})();
getUser2(function(dd){
	getLocation.init(function(){
		MVC.init();
	});
	var conn = new Easemob.im.Connection(),
		_num = 0,
		_msgAll = {},
		_getNum = function(){
			if(_num>0) $('#num').show().text(_num);
			else $('#num').hide();
		}
	;
	$('#to-msg').click(function(){
		location.href = 'msg.html?args='+str(_msgAll);
	})
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
		user : dd.user_id,
		pwd : '14e1b600b1fd579f47433b88e8d85291',
		appKey : Easemob.im.config.appkey
	});
});