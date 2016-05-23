(function(){
	window.MVC = {
		querys : {
			args : function(){
				return json(Base.tools.getQueryString('args')) || {findType : 2,ctype:'车型',long:'车长',tp:'',citys:{'1':'出发地','2':'目的地'}};
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
			tmp_car : '<dt i="#ii" d="#car_id" t="car.html"> \
				<div class="flt logo"><span j="1"><img class="headimg" j="1" src="#headimgurl"><br /><img class="vip" src="img/other/car.png"></span></div> \
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
				<div class="flt logo"><span j="1"><img class="headimg" j="1" src="#headimgurl"><br /><img class="vip" src="img/other/goods.png"></span></div> \
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
		testHmtl : function(i){
			var obj = this,
				htm = '',
				tmp = function(){
					if(obj.querys.args.findType==1) return obj.dom.tmp_car;
					else  return obj.dom.tmp_goods;
				}()
			;
			if(i) obj.dom.list.html(function(){for(var i=0;i<10;i++) htm += tmp;return htm});
			else for(var i=0;i<10;i++) obj.dom.list.append(tmp);
		},
		rdata : function(i,d,j){
			d=d.replace('#car_no',j.car_no).replace('#goods_id',j.goods_id).replace('#ii',i).replace('#is_tel',j.is_tel).replace('#user_id',j.user_id).replace('#phone',j.user_info[0] && j.user_info[0].phone).replace('#headimgurl',j.user_info[0] && j.user_info[0].headimgurl).replace('#starting',j.starting).replace('#destination',j.destination).replace('#city',j.city).replace('#volume',_None(j.volume)).replace('#weight',_None(j.weight)).replace('#time1',Base.tools.int_to_str(j.ctime)).replace('#time2',Base.tools.int_to_str(j.ctime,1));
			if(j.length=='不限') d = d.replace('#length','未知');
			else d=d.replace('#length',parseFloat(j.length) && parseFloat(j.length).toFixed(1)+'米');
			if(j.model=='车长车型') d=d.replace('#model','未知');
			else d=d.replace('#model',j.model.replace('所需车辆','未知').replace('不限','未知'));
			return d;
		},
		getHtml : function(data,i){
			var obj = this,
				htm = '',
				tmp = function(){
					if(obj.querys.args.findType==1) return obj.dom.tmp_car;
					else  return obj.dom.tmp_goods;
				}()
			;
			if(i) obj.dom.list.html(function(){for(var i=0;i<data.length;i++) htm += obj.rdata(i,tmp,data[i]);return htm});
			else for(var i=0;i<data.length;i++) obj.dom.list.append(obj.rdata(i,tmp,data[i]));
		},
		getList : function(i){
			var obj = this,arg = {};
			obj.dom.list.append(obj.dom.load);
			obj.querys.is = 1;
			arg.page = obj.querys.page;
			if(obj.querys.args.citys['1']!='出发地') arg.starting = obj.querys.args.citys['1'];
			if(obj.querys.args.citys['2']!='目的地') arg.destination = obj.querys.args.citys['2'];
			if(obj.querys.args.ctype!='车型') arg.model = obj.querys.args.ctype;
			if(obj.querys.args.long!='车长') arg['length'] = obj.querys.args.long;
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
					alert(' request failed'+textStatus);
				}
			});
		},
		query : function(){
			var obj = this;
			obj.getList(1);
		},
		init : function(){
			var obj = this;
			log(obj.querys.args.findType);
			if(obj.querys.args.findType==1) {
				obj.querys.ajaxUrl = '/viewcar';
				$('#findtype>div').eq(0).addClass('active');
			}
			else {
				obj.querys.ajaxUrl = '/viewgoods';
				$('#findtype>div').eq(1).addClass('active');
			}
			obj.querys.args.citys['1'] = obj.querys.args.citys['1'].replace('全国','出发地');
			obj.querys.args.citys['2'] = obj.querys.args.citys['2'].replace('全国','目的地');
			obj.querys.args.ctype = obj.querys.args.ctype.replace('0','车型');
			if(obj.querys.args.long=='0') obj.querys.args.long = '车长';
			
			$('#from').text(obj.querys.args.citys['1']);
			$('#to').text(obj.querys.args.citys['2']);
			$('#ctype').text(obj.querys.args.ctype);
			$('#long').text(obj.querys.args.long);
			obj.query();
			$('#findtype>div').click(function(){
				var ts = $(this);
				obj.querys.args.findType = parseInt(ts.attr('i'));
				location.href = '?args=' + str(obj.querys.args);
			})
			$('.choice').click(function(){
				obj.querys.args.url = function(){var l = location.href.split('/').pop(),r = l.split('?')[0];return r}();
				var k = $(this).attr('k'),
					v = $(this).attr('v')
				;
				if(k=='from' || k=='to'){
					obj.querys.args.tp = v;
					obj.querys.args.page = 'card';
					location.href = 'city.html?args='+str(obj.querys.args);
				}else if(k=='ctype'){
					location.href = 'ctype.html?args='+str(obj.querys.args);
				}else if(k=='long'){
					obj.querys.args.back=1;
					location.href = 'long.html?args='+str(obj.querys.args);
				}
			});
			Base.turn.get(obj);
			getUser();
			$('.click').click(function(){
				var args = '?url='+getUrl()+'&ref='+new Date().getTime()
				if(user_info.phone && user_info.phone.length>5) location.href = 'pubtype.html'+args;
				else location.href = 'reg.html'+args;
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
			})
		}
	}
})();
MVC.init();