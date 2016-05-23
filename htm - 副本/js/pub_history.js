(function(){
	window.MVC = {
		config : {
			ajaxUrl : function(){
				var t = Base.tools.getQueryString('t');
				return '/view'+t;
			}(),
			page : 1,
			end : 0
		},
		querys : {
			is : 0
		},
		dom : {
			list : $('#list'),
			load : '<div class="load">拼命加载中...</div>',
			tmp : '	<dt> \
					<div class="tit"> \
						<span class="flt date"><img class="to" src="img/pub/histime.png" /> #time1</span> \
						<span class="frt time">#time2</span> \
						<br class="cb" /> \
					</div> \
					<div class="data"> \
<span class="flt">#starting</span><img class="to" src="img/other/fromto.png" /><span class="frt">#destination</span>\
					</div> \
				</dt>'
		},
		testHmtl : function(i){
			var obj = this,htm = '',tmp = obj.dom.tmp;
			if(i) obj.dom.list.html(function(){for(var i=0;i<10;i++) htm += tmp;return htm});
			else for(var i=0;i<10;i++) obj.dom.list.append(tmp);
		},
		rdata : function(i,d,j){
			return d.replace('#starting',j.starting).replace('#destination',j.destination).replace('#time1',Base.tools.int_to_str(j.ctime)).replace('#time2',Base.tools.int_to_str(j.ctime,1));
		},
		getHtml : function(data,i){
			var obj = this,htm = '',tmp = obj.dom.tmp;
			if(i) obj.dom.list.html(function(){for(var i=0;i<data.length;i++) htm += obj.rdata(i,tmp,data[i]);return htm});
			else for(var i=0;i<data.length;i++) obj.dom.list.append(obj.rdata(i,tmp,data[i]));
		},
		getList : function(i){
			var obj = this;
			obj.dom.list.append(obj.dom.load);
			obj.querys.is = 1;
			$.ajax({
				url: obj.config.ajaxUrl,
				data: {page:obj.config.page},
				dataType: 'json',
				success : function(dd) {
					log(dd);
					var lst = dd.list;
					if(lst.length>0){
						obj.getHtml(lst,i);
						obj.querys.is = 0;
					}else obj.querys.end = 1;
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
			obj.query();
			$('#where .sort').click(function(){
				$('.sort-data').show();
			});
			Base.turn.get(obj);
		}
	}
})();
MVC.init();