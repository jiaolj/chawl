(function(){
	window.MVC = {
		querys : {
			args : function(){
				var a = json(Base.tools.getQueryString('args'));
				if(!a.cartype) a.cartype = '类别';
				if(!a.goodstype) a.goodstype = '类别';
				if(!a.long) a.long = '车长';
				if(!a.ctype) a.ctype = '';
				if(!a.citys) a.citys = {'1':'出发地','2':'目的地'}
				var py = codefans_net_CC2PY(a.citys['1']).toLowerCase();
				if(py=='chufadi' || py=='mudidi') py = $('#cityname').attr('py');
				a.city = py;
				return a;
			}(),
			is : 0,
			page : 1,
			end : 0,
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
			'
		},
		rdata : function(d,j){
			d = d.replace('#href','detail.html?id='+j.id+'&ref='+new Date().getTime()).replace('#stars',j.view/*getStar(j.support_num || 0)*/).replace('#title',j.title).replace('#phone',j.phone).replace('#view',j.view).replace('#address',j.address);
			if(j.is_vip=='1') d = d.replace('#vip','');
			else d = d.replace('#vip',' hide');
			if(j.logo) d = d.replace('#logo',j.logo);
			else d = d.replace('#logo','http://www.chawuliu.com/uploads/page/default9d665cfbf7bbd3834f7accbeaeea423b.jpg');
			return d;
		},
		getHtml : function(data,i){
			var obj = this,htm = '',tmp = obj.dom.tmp;
			if(i) obj.dom.list.html(function(){for(var i=0;i<data.length;i++) htm += obj.rdata(tmp,data[i]);return htm});
			else for(var i=0;i<data.length;i++) obj.dom.list.append(obj.rdata(tmp,data[i]));
		},
		rdata100 : function(d,j){
			return d.replace('#href','detail.html?id='+j.car_id).replace('#stars',getStar(j.support_num || 0)).replace('#logo',j.user_info[0] && j.user_info[0].headimgurl).replace('#title',j.starting+'-'+j.destination).replace('#phone',j.ctime).replace('#view',j.view).replace('#address',j.city);
		},
		getHtml100 : function(data,i){
			var obj = this,htm = '',tmp = obj.dom.tmp;
			if(i) obj.dom.list.html(function(){for(var i=0;i<data.length;i++) htm += obj.rdata100(tmp,data[i]);return htm});
			else for(var i=0;i<data.length;i++) obj.dom.list.append(obj.rdata100(tmp,data[i]));
		},
		getArgStr : function(args){
			var obj = this,
				argStr = '',
				argNb = 0
			;
			for(var k in args) if(k=='city') argStr += args[k];
			for(var k in args){
				if(k!='city'){
					argNb ++;
					if(argNb==1) argStr += '?' + k + '=' + args[k];
					else argStr += '&'+ k + '=' + args[k];
				}
			}
			return argStr;
		},
		getArgURLStr : function(argjson){
			var obj = this,
				argStr = '',
				argNb = 0
			;
			for(var k in argjson){
				argNb ++;
				if(argNb==1) argStr += '?' + k + '=' + argjson[k];
				else argStr += '&'+ k + '=' + argjson[k];
			}
			return argStr;
		},
		getList : function(i){
			var obj = this,
				argStr = '',
				args = JSON.parse(JSON.stringify(obj.querys.args))
			;
			if(i) obj.dom.list.html(obj.dom.load);
			else obj.dom.list.append(obj.dom.load);
			obj.querys.is = 1;
			args.page = obj.querys.page;
			if(args.sort==101){
				Base.config.ajaxUrl = '/viewcar/';
				argStr = obj.getArgURLStr(args);
			}else{
				if(args.sort==100){
					args.sort = 130;
				}
				args.city = codefans_net_CC2PY(args.citys['1']).toLowerCase();
				args.kw = args.citys['2'];
				argStr = obj.getArgStr(args);
			}
			//log(Base.config.ajaxUrl + argStr);
			$.ajax({
				url : Base.config.ajaxUrl + argStr,
				data : {},
				dataType : 'json',
				success : function(dd) {
					log(dd);
					var lst = dd.page_list || dd.list;
					if(lst.length>0){
						if(args.sort==101) obj.getHtml100(lst,i);
						else obj.getHtml(lst,i);
						obj.querys.is = 0;
					}
					else obj.querys.end = 1;
					obj.dom.list.find('.load').remove();
				},
				error:function(jqXHR,textStatus) {
					log(' request failed'+textStatus);
				}
			});
		},
		query : function(){
			var obj = this;
			obj.getList(1);
		},
		init : function(){
			var obj = this,
				args = JSON.parse(JSON.stringify(obj.querys.args)),
				citys = args.citys,
				sort = args.sort,
				from = citys['1'],
				to = citys['2']
			;
			$('.from').text(from);
			$('.to').text(to);
			if(sort==130){
				$('#where130').show();
			}else if(sort==100){
				$('#where100').show();
				$('#cartype').text(args.cartype);
			}else if(sort==101){
				$('#where101').show();
				$('#ctype').text(args.long + ' ' + args.ctype);
			}else if(sort==564){
				$('#where564').show();
				$('#goodstype').text(args.goodstype);
			}
			$('#seoTitle').text(args.title);
			obj.query();
			getUser();
			$('.click').click(function(){
				var args = '?url='+getUrl()+'&ref='+new Date().getTime();
				if(user_info.phone && user_info.phone.length>5) location.href = 'pubtype.html'+args;
				else location.href = 'reg.html'+args;
			})
			Base.turn.get(obj);
			$('.choice').click(function(){
				obj.querys.args.url = function(){var l = location.href.split('/').pop(),r = l.split('?')[0];return r}();
				var k = $(this).attr('k'),
					v = $(this).attr('v')
				;
				if(k=='from' || k=='to'){
					obj.querys.args.tp = v;
					obj.querys.args.page = 'card';
					location.href = 'city.html?args='+str(obj.querys.args);
				}else if(k=='change'){
					obj.querys.args.citys[1] = $('#to').text();
					obj.querys.args.citys[2] = $('#from').text();
					$('#from').text(obj.querys.args.citys['1']);
					$('#to').text(obj.querys.args.citys['2']);
				}else if(k=='cartype'){
					location.href = 'cartype.html?args='+str(obj.querys.args);
				}else if(k=='goodstype'){
					location.href = 'goodstype.html?args='+str(obj.querys.args);
				}else if(k=='ctype'){
					location.href = 'long.html?args='+str(obj.querys.args);
				}
			});
		}
	}
})();
getLocation.init(function(){
	MVC.init();
})