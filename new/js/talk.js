var _id = Base.tools.getQueryString('id');
_getUserDetail(_id,function(detail){
	$('.seoTitle').text(detail.nickname);
	var conn = new Easemob.im.Connection(),
		_nickname = detail.nickname,
		_headimgurl = detail.headimgurl,
		_vip = function(){
			if(detail.pages.length>0) return '';
			return 'display:none';
		}(),
		_user_id = null,
		_dom = {
			time : '<div class="time">#time</div>',
			loads : '<div class="time loads">加载中...</div>',
			list : '<div class="list">#list</div>',
			other : '\
				<div class="list-item clearfix other">\
					<div class="user-image fl" style="background: url(#headimgurl) 50% 50% no-repeat;position:relative"><img style="position:absolute;right:-0.06rem;bottom:-0.06rem;width:0.16rem;'+_vip+'" src="img/blog/vip.png"/></div>\
					<div class="user-message fl">\
						<p>#msg</p>\
						<p class="sanjiao"></p>\
				</div>\
			',
			my : '\
				<div class="list-item clearfix self">\
					<div class="user-image fr" style="background: url(#headimgurl) 50% 50% no-repeat;position:relative"><img style="position:absolute;right:-0.06rem;bottom:-0.06rem;width:0.16rem;#vip" src="img/blog/vip.png"/></div>\
					<div class="user-message fr">\
						<p>#msg</p>\
						<p class="sanjiao"></p>\
					</div>\
				</div>\
			'
		},
		getNowTime = function(j){
			if(j) var now = new Date(j);
			else var now = new Date();
			var hh = now.getHours(),
				mm = now.getMinutes(),
				r = ''
			;
			if(hh<11 && hh>=1) r = '早上';
			else if(hh<13 && hh>=11) r = '中午';
			else if(hh<19 && hh>=13) r = '下午';
			else if(hh<1 || hh >= 19) r = '晚上';
			if(hh>12) hh-=12;
			r += hh;
			r += ':'+mm;
			return r;
		},
		getStrTimeAll=function(con){if(!con){con="-"}var now=new Date();var year=now.getFullYear();var month=now.getMonth()+1;var day=now.getDate();var hh=now.getHours();var mm=now.getMinutes();var ss=now.getSeconds();var clock=year+con;if(month<10){clock+="0"}clock+=month+con;if(day<10){clock+="0"}clock+=day+" ";if(hh<10){clock+="0"}clock+=hh+":";if(mm<10){clock+="0"}clock+=mm+":";if(ss<10){clock+="0"}clock+=ss;return(clock)}, //2020-01-01 00:00:00
		getUserDetail = function(uid,suc){
			$.ajax({
				url: '/chat/userinfo',
				data: {user_id:uid},
				dataType: 'json',
				success : function(dd) {
					suc && suc(dd);
				},
				error : function(xhr, type) {
					log(' request failed'+xhr);
				}
			})
		},
		data = Base.tools.getQueryString('data')
	;
	_id = _id.split('ref')[0];
	$('#data').append(_dom.loads);
	window.onbeforeunload = function(event) {conn.close()}
	conn.init({
		https : Easemob.im.config.https,
		url: Easemob.im.config.xmppURL,
		//当连接成功时的回调方法
		onOpened : function() {
			conn.setPresence();
			$('.send').show();
			$('#data .loads').remove();
			$('.send-box').show();
		},
		//收到文本消息时的回调方法
		onTextMessage : function(msg) {
			getUserDetail(msg.from,function(dd){
				var o = getNowTime(),
					n = $('#data').find('.time:last').text()
				;
				if(o!=n) $('#data').append(_dom.time.replace('#time',o));
				$('#data').append(_dom.list.replace('#list',_dom.other.replace('#headimgurl',dd.headimgurl).replace('#msg',msg.data)));
				window.scroll(0,window.document.body.scrollHeight);
			})
		},
		//异常时的回调方法
		onError : function(msg) {
			log(str(msg));
		}
	});

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

	getUser(function(){
		var myvip = function(){
			if(_userInfo.pages.length>0) return '';
			return 'display:none';
		}();
		//_user_id = _userInfo.user_id;
		conn.open({
			user : _userInfo.user_id,
			pwd : '14e1b600b1fd579f47433b88e8d85291',
			appKey : Easemob.im.config.appkey
		});
		getHistory(_id,_userInfo.user_id,function(msg){
			$.each(msg,function(k,j){
				var o = getNowTime(parseInt(j.ctime) * 1000),
					n = $('#data').find('.time:last').text();
				if(o!=n) $('#data').append(_dom.time.replace('#time',o));
				$('#data').append(_dom.list.replace('#list',_dom[j.t].replace('#headimgurl',j.headimgurl).replace('#msg',j.msg).replace('#vip',myvip)));
			})
			window.scroll(0,window.document.body.scrollHeight);
		});
		$('#send').click(function(){
			var msgs = $('#msgs').val();
			if(!msgs){
				return;
			}
			var sendJson  = {
				from_user : _userInfo.user_id,
				to_user : _id,
				msg : msgs
			};
			conn.sendTextMessage({
				to : _id,
				msg : msgs
			})
			$('#msgs').val('');
			var o = getNowTime(),
				n = $('#data').find('.time:last>span').text()
			;
			if(o!=n) $('#data').append(_dom.time.replace('#time',o));
			$('#data').append(_dom.list.replace('#list',_dom.my.replace('#headimgurl',_userInfo.headimgurl).replace('#msg',msgs).replace('#vip',myvip)));
			window.scroll(0,window.document.body.scrollHeight);
			
			$.ajax({
				url: '/user/chat/save',
				data : sendJson,
				dataType: 'json',
				success : function(back) {
					alert(back.info);
				},
				error : function(jqXHR,textStatus) {
					//log(' request failed'+textStatus);
					log('保存聊天记录成功');
				}
			});
		})
	});
})