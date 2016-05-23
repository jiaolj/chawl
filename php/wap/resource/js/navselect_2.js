//显示选择导航
var cookieSign = '';//显示的标记
function setSortClick( classBox, sign,e  ){
	if( !classBox ) return false;
	if( !sign ) return false;
	var documentHeight = $(document).height();
	var windowHeight = $(window).height();
	var bodyHeight = $("body").height();

	var contentHeight = $("."+classBox).height();
	var newHeight = documentHeight;
	if( documentHeight < windowHeight) newHeight = windowHeight;
	if( documentHeight >= windowHeight && documentHeight <  contentHeight+82 )  newHeight = contentHeight+82;
	
	
	var screen_mask = $("#screen_mask");
	var close_cross = $("#close_cross");
	var top_bar_mask = $("#top_bar_mask");
	var popup_list = $("#popup_list");
	if( screen_mask.is(":visible") && cookieSign == classBox  && sign == 'oneNav'){
		closeSortSelect();
	}else{
		if( sign == 'oneNav'){
			cookieSign = classBox;
		}else{
			$('#popup_list_left').css('width','50%');
		}
		$('.nav_list').hide();
		$('.'+classBox).show();
		
		screen_mask.height( newHeight-82 ).show();
		
		top_bar_mask.show();
		popup_list.show();
		close_cross.show();
		
		if( e ){
			$('.two_sort').css('background-color','#fff');
			$(e).css('background-color','#ededed');
		}
		
		//搜索和登录
		var account_box = $("#account_box");
		var search_box = $("#search_box");
		if( account_box.is(":visible") ) account_box.hide();
		if( search_box.is(":visible") ) search_box.hide();
	}
}
//关闭
function closeSortSelect(){
	$("#screen_mask").height( 0 ).hide();
	$("#top_bar_mask").hide();
	$("#popup_list").hide();
	$("#close_cross").hide();
	$('.nav_list,.nav_list_more').hide();
	$('#popup_list_left').css('width','100%');
}