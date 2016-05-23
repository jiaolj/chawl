(function(){
	window.MVC = {
		config : {
			id : Base.tools.getQueryString('id'),
			ldom : '\
					<dt>\
						<div class="flt head"><img src="#headimgurl" /></div>\
						<div class="flt txt">\
							<div class="up"><b>#nickname</b><span class="frt">#ctime</span></div>\
							<div class="down">#content<a href="mdetail.html?id=#user_id"><img src="img/blog/intalk.png" class="frt" /></a></div>\
						</div>\
						<br class="cb"/>\
					</dt>\
				'
		},
		getImages : function(pic){
			var obj = this,htm = '',plist = [];
			if(pic.length>0){
				plist = pic.split(',')
				for(var p in plist) htm += '<img src="http://www.chawuliu.com/uploads/'+plist[p]+'" />';
			}
			return htm;
		},
		getViewers : function(pic){
			var htm = '';
			for(var p in pic) htm += '<img src="'+pic[p].headimgurl+'" />';
			return htm;
		},
		getTime : function(){
			var date = new Date();
			var time = date.getHours() + ":" + date.getMinutes();
			return time;
		},
		dom : function(j){
			var obj = this,
				post = j.post
				replys = j.replys,
				ui = j.user_info[0];
			$('#headimgurl').attr('src',ui.headimgurl);
			$('#nickname').text(ui.nickname);
			$('#viewers').html(obj.getViewers(post.viewers));
			
			$('#pics').html(obj.getImages(post.pictures));
			$('#cnt').text(post.content);
			$('#view_num').text(post.view_num);
			$('#btime').text(Base.tools.past_time(post.ctime));
			$('#talk').attr('d',post.user_id);
			
			$('#leave').text(replys.length);
			$('#message').html(function(){
				var dom = obj.config.ldom,htm = '';
				for(var h in replys) htm += dom.replace('#headimgurl',replys[h].user_info[0] && replys[h].user_info[0].headimgurl).replace('#nickname',replys[h].user_info[0] && replys[h].user_info[0].nickname).replace('#ctime',Base.tools.past_time(replys[h].ctime)).replace('#user_id',replys[h].user_id).replace('#content',replys[h].content);
				return htm;
			})
		},
		init : function(){
			var obj = this;
			_ajax('/shuoshuo/postinfo/'+obj.config.id,function(back){
				obj.dom(back);
			})
			$('.send2>p').click(function(){
				if(_userInfo){
					var txt = $(this).text(),
						lbox = $('.leavebox');
					switch(txt){
						case '喜欢':
							$.ajax({
								url: '/shuoshuo/like/'+obj.config.id,
								dataType: 'json',
								success : function(dd) {
									alert(dd.info);
								}
							})
							break;
						case '留言':
							if(lbox.css('display')=='none') lbox.show();
							else lbox.hide();
							break;
						case '聊一聊':
							location.href = 'mdetail.html?id='+$('#talk').attr('d');
							break;
					}
				}
				else _followFunc();
			})
			$('#leavebtn').click(function(){
				var val = $('#leaveval').val();
				if(val){
					$.ajax({
						url: '/shuoshuo/doReply/'+obj.config.id,
						data: {content : val},
						dataType: 'json',
						success : function(dd) {
							alert(dd.info);
							if(dd.info=='发表回复成功'){
								var dom = obj.config.ldom;
								$('#leaveval').val('');
								$('.leavebox').hide();
								$('#message').prepend(dom.replace('#headimgurl',_userInfo.headimgurl).replace('#nickname',_userInfo.nickname).replace('#ctime',obj.getTime()).replace('#content',val));
							}
						}
					})
				}
			})
		}
	}
})();
getUser(function(){
	$('.click').click(function(){
		if(_userInfo) location.href = $(this).attr('htm');
		else _followFunc();
	})
	MVC.init();
});