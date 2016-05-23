getLocation.init(function(){
	(function(){
		window.MVC = {
			querys : {
				args : function(){
					var a = json(Base.tools.getQueryString('args'));
					if(!a.cartype) a.cartype = '类别';
					if(!a.goodstype) a.goodstype = '类别';
					if(!a.long) a.long = '车长';
					if(!a.ctype) a.ctype = '';
					if(!a.citys) a.citys = {'1':$('#cityname').text().replace('市',''),'2':'全国'};
					return a;
				}(),
				is : 0,
				page : 1,
				end : 0,
			},
			dom : {
				list : $('#list'),
				load : '<div class="load"><img src="img/bottom/find_on.png">努力加载中...</div>',
				tmp : '\
					<a href="#href"><dt>\
						<div class="flt logo1"><img class="logo" src="#logo" /><img class="vip#vip" src="img/list/vip.png"></div>\
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
				'
			},
			rdata : function(d,j){
				d = d.replace('#href','detail.html?id='+j.id+'&uid='+j.uid).replace('#stars',j.view/*getStar(j.support_num || 0)*/).replace('#title',j.title).replace('#phone',j.phone).replace('#view',j.view).replace('#address',j.address);
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
			getList : function(i,argArea){
				var obj = this,
					argStr = '',
					arg = {},
					args = JSON.parse(JSON.stringify(obj.querys.args))
				;
				obj.dom.list.append(obj.dom.load);
				obj.querys.is = 1;
				arg.page = obj.querys.page;
				arg.sort = args.sort;
				if(argArea!=3){
					if(args.citys['1']!='出发地') arg.starting = args.citys['1'];
					if(args.citys['2']!='目的地') arg.destination = args.citys['2'];
				}
				log(arg);
				$.ajax({
					url : '/page/index',
					data : arg,
					dataType : 'json',
					success : function(dd) {
						log(dd);
						var lst = dd.page_list;
						if(lst.length>0){
							obj.getHtml(lst,i);
							obj.querys.is = 0;
						}
						else {
							obj.querys.end = 1;
							obj.querys.page = 1;
							obj.dom.list.append('<div class="moreText">以下为平台推荐的名片</div>');
							obj.getList(null,3);
						}
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
					args = JSON.parse(JSON.stringify(obj.querys.args))
				;
				$('.from').text(obj.querys.args.citys['1']);
				$('.to').text(obj.querys.args.citys['2']);
				obj.querys.args.citys['1'] = obj.querys.args.citys['1'].replace('全国','出发地');
				obj.querys.args.citys['2'] = obj.querys.args.citys['2'].replace('全国','目的地');
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
				$('#seoTitle').text(args.title);
				obj.query();
				Base.turn.get(obj);
				$('.choice').click(function(){
					args.url = function(){var l = location.href.split('/').pop(),r = l.split('?')[0];return r}();
					var k = $(this).attr('k'),
						v = $(this).attr('v')
					;
					if(k=='from' || k=='to'){
						args.tp = v;
						args.page = 'card';
						location.href = 'city.html?args='+str(args);
					}else if(k=='change'){
						var cge = args.citys[1];
						args.citys[1] = args.citys[2].replace('目的地','出发地');
						args.citys[2] = cge.replace('出发地','目的地');
						//$('#from').text(obj.querys.args.citys['1']);
						//$('#to').text(obj.querys.args.citys['2']);
						location.href = '?args='+str(args);
					}else if(k=='cartype'){
						//location.href = 'cartype.html?args='+str(args);
					}else if(k=='goodstype'){
						location.href = 'goodstype.html?args='+str(args);
					}else if(k=='ctype'){
						//location.href = 'long.html?args='+str(args);
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
	})
})