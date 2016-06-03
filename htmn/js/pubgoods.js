var Main = (function(){
	var _obj = {},
		_tp = Base.tools.getQueryString('tp') || 'goods',
		_os = $('.oneStep'),
		_isPub = 0,
		_ts = $('.twoStep'),
		_arg = {starting:'从哪里出发',destination:'到哪里去',times:'可装货时间',leavewords:'',phone:''},
		_get = function(){
			$('#starting').text(_arg.starting);
			$('#destination').text(_arg.destination);
			$('#times').text(_arg.times);
		}
	;
	//车源或货源
	if(_tp=='goods'){
		$('.block-goods').show();
		$('.block-car').hide();
		$('#seoTilte').text('发布货源');
	}else if(_tp=='car'){
		$('.block-goods').hide();
		$('.block-car').show();
		$('#seoTilte').text('发布车源');
	}
	return {
		getCity : function(){
			$('.choiceCity .city-menu a.active').removeClass('active');
			$('.choiceCity .city-menu a[i="0"]').addClass('active');
			$('.choiceCity .city-data.active').removeClass('active');
			$('.choiceCity .city-data[i="0"]').addClass('active');
			$('.choiceCity .city-data[i="0"]').html(function(){
				var dom = '<dt><p class="number"><span>#number</span></p><p class="citys">#citys</p></dt>',
					htm = '',
					citys = '';
				for(var c in ChineseDistricts[86]){
					citys = '';
					htm += dom.replace('#number',c);
					for(var k in ChineseDistricts[86][c]){
						var t = ChineseDistricts[86][c][k];
						citys += '<a title="'+t.address+'" code="'+t.code+'">'+_simpCity(t.address)+'</a>';
					}
					htm = htm.replace('#citys',citys);
				}
				return htm;
			})
			return _obj;
		},
		getTimes : function(){
			$('#timesbox').html(function(){
				var date = Date.parse(new Date())/1000,
					htm = '',
					lst  = [],
					_getWeek = function(n,u){
						var u = u || '星期',jn = ['日','一','二','三','四','五','六'];
						return u+jn[n];
					},
					_int_to_str = function(i,d){
						var dt = new Date(parseInt(d) * 1000),
							back = dt.getFullYear()+'-'+Base.tools.toDouble(dt.getMonth()+1)+'-'+Base.tools.toDouble(dt.getDate());
							back2 = back
						;
						if(i==0) back2 += ' 今天';
						else if(i==1) back2 += ' 明天';
						else if(i==2) back2 += ' 后天';
						else back2 += ' ' + _getWeek(dt.getDay());
						return [back,back2];
					}
				;
				//htm += '<a><dt d="不限">不限</dt></a>';
				for(var i=0;i<60;i++){
					lst = _int_to_str(i,date);
					htm += '<a><dt d="' + lst[0] + '">' + lst[1] + '</dt></a>';
					date += 3600*24;
				}
				return htm;
			}).find('dt').click(function(){
				_arg.times = $(this).attr('d');
				_get();
				$('.choiceTimes').animate({top:'100%'},function(){
					$(this).hide().css({top:'1%'});
				})
			})
			return _obj;
		},
		init : function(){
			_obj = this;
			if(_userInfo) _arg.phone = _userInfo.phone;
			_get();
			//历史
			$('#history').attr('href','pub_history.html?t='+_tp);
			$('#oneStep').click(function(){
				if(_ts.hasClass('active')){
					var lv = $('#leavewords-'+_tp).val().trim(),
						numList = lv.match(/[0-9]+/ig),
						arg = {kwd:lv}
					;
					if(lv.indexOf('到')!=-1){
						arg.spt = '到';
					}else if(lv.indexOf('-')!=-1){
						arg.spt = '-';
					}else if(lv.indexOf('—')!=-1){
						arg.spt = '—';
					}else if(lv.indexOf('至')!=-1){
						arg.spt = '至';
					}else if(lv.indexOf('去')!=-1){
						arg.spt = '去';
					}else if(lv.indexOf('回')!=-1){
						arg.spt = '回';
					}else if(lv.indexOf('往')!=-1){
						arg.spt = '往';
					}else if(lv.indexOf('求')!=-1){
						arg.spt = '求';
					};
					log(arg);
					$.ajax({
						url : 'http://server.jiaolj.com/getLocation',
						data : arg,
						dataType : 'json',
						success : function(back){
							log(back);
							if(back.from && back.from.province_name){
								var fstr = '';
								if(back.from.province_name==back.from.city_name){
									fstr += back.from.province_name+' ';
								}else{
									if(back.from.province_name) fstr += back.from.province_name+' ';
									if(back.from.city_name) fstr += back.from.city_name+' ';
								}
								if(back.from.area_name) fstr += back.from.area_name;
								_arg.starting = fstr.trim();
							};
							if(back.to && back.to.province_name){
								log(back.to);
								var tstr = '';
								if(back.to.province_name==back.to.city_name){
									tstr += back.to.province_name+' ';
								}else{
									if(back.to.province_name) tstr += back.to.province_name+' ';
									if(back.to.city_name) tstr += back.to.city_name+' ';
								}
								if(back.to.area_name) tstr += back.to.area_name;
								_arg.destination = tstr.trim();
							};
							_get();
							_ts.removeClass('active');
							_os.addClass('active');
							if(numList){
								$.each(numList,function(k,j){
									if(j.length>5) {
										_arg.phone = j;
										$('#phone').val(_arg.phone);
									}
								})
							}else{
								$('#phone').val(_arg.phone);
							};
							$('#leavewords-'+_tp+'2').val(lv);
						}
					})
				}
			})
			$('a.back').click(function(){
				history.back(-1);
			})
			$('#pubOk').click(function(){
				if(_isPub==0){
					if(_arg.starting=='从哪里出发'){
						alert('请选择出发地');
						return;
					}
					if(_arg.destination=='到哪里去'){
						alert('请选择目的地');
						return;
					}
					if(_tp=='goods'){
						var arg = {
								starting : _arg.starting,
								destination : _arg.destination,
								type: '未知',
								model: '未知',
								length: '未知',
								weight:0.01,
								volume:0.01,
								phone : $('#phone').val() || -1,
								remark: $('#leavewords-'+_tp+'2').val(),
							}
						;
					}else if(_tp=='car'){
						var arg = {
								starting : _arg.starting,
								destination : _arg.destination,
								city: '未知',
								model: '未知',
								length: '未知',
								car_no: '未知',
								phone : $('#phone').val() || -1,
								remark: $('#leavewords-'+_tp+'2').val(),
							}
						;
					};
					if(_arg.times=='可装货时间'){
						alert('请选择可装货时间');
						return;
						//arg.dtime_chn = '1970-01-01';
					}else{
						arg.dtime_chn = _arg.times;
					};
					arg.dtime = Base.tools.str_to_int(arg.dtime_chn);
					_isPub = 1;
					$.ajax({
						url: '/'+_tp+'/publish',
						data : arg,
						timeout : 1000,
						//type : 'POST',
						dataType: 'json',
						success : function(back) {
							if(_tp=='goods' && back.info=='发布货源成功') {
								alert('发布货源成功');
								location.href = 'find.html?tp=2';
							}
							else if(_tp=='car' && back.info=='发布车源成功') {
								alert('发布车源成功');
								location.href = 'find.html?tp=1';
							}
							else alert(str(back));
						},
						error : function(XMLHttpRequest, textStatus, errorThrown){
							_isPub = 1;
							if(_tp=='goods') {
								alert('发布货源成功');
								location.href = 'find.html';
							}
							else if(_tp=='car') {
								alert('发布车源成功');
								location.href = 'find.html?tp=1';
							}
							//alert(str(XMLHttpRequest));
							//alert(str(textStatus));
							//alert(str(errorThrown));
						}
					});
				}else{
					//alert('正在发布，等待中..');
				}
			})
			$('.choice').click(function(){
				var k = $(this).attr('k')
				;
				if(k=='starting' || k=='destination'){
					_arg.tp = k;
					_arg.fromCity = _arg[k].split(' ');
					_obj.getCity();
					if(_arg.fromCity.length==1) _arg.fromCity = ['','',''];
					$('.city-title a').each(function(k,i){
						var txt = _arg.fromCity[k],
							ni = k+1,
							o = $('.choiceCity .city-data[i="'+k+'"] a[title="'+txt+'"]');
						$(i).text(txt);
						o.addClass('active');
						if(ni<=2){
							var sons = ChineseDistricts[o.attr('code')];
							$('.choiceCity .city-data[i="'+ni+'"]').html(function(){
								var htm = '<dt><p class="citys">';
								for(var t in sons) htm += '<a title="'+sons[t]+'" code="'+t+'">'+sons[t]+'</a>';
								htm += '</p></dt>';
								return htm;
							})
						}
					})
					$('.choiceCity').show().css({top:'100%'}).animate({top:'0.25rem'});
				}else if(k=='times'){
					$('.choiceTimes').show().css({top:'100%'}).animate({top:'1%'});
				}
			})
			//城市选择
			$('.choiceCity .city-menu a').click(function(){
				var o = $(this),
					i = o.attr('i');
				o.parent().find('.active').removeClass('active');
				o.addClass('active');
				$('.choiceCity .city-data.active').removeClass('active');
				$('.choiceCity .city-data[i="'+i+'"]').addClass('active');
			})
			$('.choiceCity .city-ok a').click(function(){
				var o = $(this),
					txt = o.text();
				if(txt=='确定'){
					_arg[_arg.tp] = _arg.fromCity.join(' ').trim();
					_get();
				}else if(txt=='取消') {
					
				}
				$('.choiceCity').animate({top:'100%'},function(){
					$(this).hide().css({top:'0.25rem'});
				})
			})
			$('.choiceCity .city-data').click(function(e){
				var tar = e.target,
					o = $(tar),
					i = parseInt($('.choiceCity .city-menu a.active').attr('i')),
					ni = i+1,
					isr = 0
				;
				//log(argjson.fromCity);
				if(tar.nodeName.toLowerCase()==='a'){
					var txt = o.text();
					if(txt==_arg.fromCity[i]) {
						_arg.fromCity[i] = '';
						o.removeClass('active');
						isr = 1;
					}else{
						_arg.fromCity[i] = txt
						o.parent().find('.active').removeClass('active');
						o.addClass('active');
					}
					if(ni<=2) {
						_arg.fromCity[ni] = '';
						$('.choiceCity .city-data[i="'+ni+'"]').empty();
					}
					if(ni==1) {
						_arg.fromCity[ni+1] = '';
						$('.choiceCity .city-data[i="'+(ni+1)+'"]').empty();
					}
					$('.city-title a').each(function(k,i){
						$(i).text(_arg.fromCity[k]);
					})
					if(isr==1) return;
					if(ni<=2){
						var sons = ChineseDistricts[o.attr('code')];
						$('.choiceCity .city-menu a.active').removeClass('active');
						$('.choiceCity .city-menu a[i="'+ni+'"]').addClass('active');
						$('.choiceCity .city-data.active').removeClass('active');
						$('.choiceCity .city-data[i="'+ni+'"]').addClass('active');
						$('.choiceCity .city-data[i="'+ni+'"]').html(function(){
							var htm = '<dt><p class="citys">';
							for(var t in sons) htm += '<a title="'+sons[t]+'" code="'+t+'">'+_simpCity(sons[t])+'</a>';
							htm += '</p></dt>';
							return htm;
						})
					}
				}
			})
			return _obj;
		}
	}
})();
window.onload = function(){
	getUser(function(){
		Main.init().getCity().getTimes();
	})
};