var log = function(){for(var arg in arguments) console && console.log(arguments[arg])},
	str = function(d){return JSON && JSON.stringify(d) || d},
	json = function(d){return JSON && JSON.parse(d) || d},
	Base = (function(){
		var _obj = {},
			_getSize = function(x){
				document.getElementById('html').style.fontSize = document.body.clientWidth*x+'px';
			},
			_getRequest = function() {
			   var url = window.location.search; //获取url中"?"符后的字串   
			   var theRequest = new Object();   
			   if (url.indexOf("?") != -1) {   
				  var str = url.substr(1);   
				  strs = str.split("&");   
				  for(var i = 0; i < strs.length; i ++) {
					 theRequest[strs[i].split("=")[0]]=decodeURI(strs[i].split("=")[1]);
				  }   
			   }
			   return theRequest;   
			},
			_req = function(){
				var req = _getRequest();
				if(!req.url) req.url = 'index';
				return req;
			}();
			_temp = {
				load : '<div class="load">努力加载中...</div>',
				card : '\
					<dt>\
						<div class="img">\
							<img class="logo" src="#logo"/><img class="vip#vip" src="img/tuijian.png"/>\
						</div>\
						<div class="text">\
							<div class="tit">#title</div>\
							<div class="phone">\
								<span>#phone</span>\
								<span class="star">\
								<span class="viewNum">#stars</span>\
								<span class="view">人浏览</span></span>\
							</div>\
							<div class="area">#address</div>\
						</div>\
						<br class="cb"/>\
					</dt>\
				'
			},
			_config = {
				active : _req.url,
				o : $('#main'),
				index : {
					url : '/page/index',
					tempUrl : 'home.html',
					data : {sort:130},
					temp : _temp.card,
					o : 'dl.data',
				},
				my : {
					tempUrl : 'my.html',
				},
			},
			_load = function(url,o,suc){
				$.ajax({
					url : url,
					success : function(temp){
						o.html(temp);
						suc && suc();
					}
				})
			}
		;
		return {
			turn : {
				page : 1,
				is : 0,
				end : 0,
				getScrollTop : function() {
					var scrollTop = 0; 
					if (document.documentElement && document.documentElement.scrollTop) { 
					scrollTop = document.documentElement.scrollTop; 
					} 
					else if (document.body) { 
					scrollTop = document.body.scrollTop; 
					} 
					return scrollTop; 
				},
				getClientHeight : function() { //获取当前可是范围的高度 
					var clientHeight = 0; 
					if (document.body.clientHeight && document.documentElement.clientHeight) { 
					clientHeight = Math.min(document.body.clientHeight, document.documentElement.clientHeight); 
					} 
					else { 
					clientHeight = Math.max(document.body.clientHeight, document.documentElement.clientHeight); 
					} 
					return clientHeight; 
				},
				getScrollHeight : function() { //获取文档完整的高度 
					return Math.max(document.body.scrollHeight, document.documentElement.scrollHeight); 
				},
				get : function(suc){
					window.onscroll = function () { 
						if (_obj.turn.getScrollTop() + _obj.turn.getClientHeight() == _obj.turn.getScrollHeight()) {
							_obj.turn.page ++;
							suc && suc();
						} 
					}
				}
			},
			replaceData : function(d,j){
				d = d.replace('#href','detail.html?id='+j.id+'&uid='+j.uid).replace('#stars',j.view).replace('#title',j.title).replace('#phone',j.phone).replace('#view',j.view).replace('#address',j.address);
				if(j.is_vip=='1') d = d.replace('#vip','');
				else d = d.replace('#vip',' hide');
				//if(j.isagree=='1') d = d.replace('#renzheng','');
				//else d = d.replace('#renzheng',' hide');
				d = d.replace('#renzheng',' hide');
				if(j.logo) d = d.replace('#logo',j.logo);
				else d = d.replace('#logo','http://www.chawuliu.com/uploads/page/default9d665cfbf7bbd3834f7accbeaeea423b.jpg');
				return d;
			},
			query : function(){
				if(_obj.turn.is==0 && _obj.turn.end==0){
					_obj.turn.is = 1;
					var atv = _config[_config.active];
					$(atv.o).append(_temp.load);
					atv.data.page = _obj.turn.page;
					$.ajax({
						url : atv.url,
						data : atv.data,
						dataType : 'json',
						success : function(back){
							var lst = back.list || back.page_list;
							if(lst.length>0){
								for(var i=0;i<lst.length;i++) $(atv.o).append(_obj.replaceData(atv.temp,lst[i]));
							}
							else {
								_obj.turn.end = 1;
							}
							$(atv.o).find('.load').remove();
							_obj.turn.is = 0;
						}
					})
				}
			},
			load : function(){
				_load(_config[_config.active].tempUrl,_config.o,function(){
					if(_config.active=='index') {
						_obj.query();
						_obj.turn.get(function(){
							_obj.query();
						});
					}
				})
			},
			init : function(){
				_obj = this;
				_getSize(100/320);
				_obj.load();
				$('footer dl dt').click(function(){
					var o =$(this);
					_config.active = o.attr('url')
					if(_config.active){
						_obj.load();
					};
				})
				$('body').append('<a href="javascript:window.location.reload()" style="position:fixed;top:0.4rem;right:0;z-index:99;display:block;width:50px;height:50px"></a>');
			}
		}
	})();
;
Base.init();