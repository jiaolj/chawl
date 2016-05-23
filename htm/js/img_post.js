$(function(){
	var mediaIds = [],
		canChooseCount = 3,
		isPost = 0,
		$add = $('.j-add'),
		$imgList = $('.pic-list');

	var initWX = function(){
		$.ajax({
			url: '/wechat/sign',
			data: {
				url: encodeURIComponent(location.href.split('#')[0])
			},
			success: function(data){
				var ticket = JSON.parse(data);

				wx && wx.config({
					debug: false,
					appId: ticket.appId,
					timestamp: ticket.timestamp,
					nonceStr: ticket.nonceStr,
					signature: ticket.signature,
					jsApiList: [
					    'chooseImage',
					    'uploadImage',
					    'downloadImage',
					    'previewImage'
					]
				});
			}
		})
	};

	initWX();

	$('.content')
		.on('touchend', '.j-add', function(){
			wx.chooseImage({
				count: canChooseCount,
				success: function(res){
					var imgList = res.localIds;

					if(canChooseCount < imgList.length){
						alert('最多能选择' + canChooseCount + '张');
						return;
					}

					html = $.map(imgList, function(img){
						return '<li class="item"><div class="wrapper"><a class="pic" style="background-image:url(' + img + ')"></a></div></li>';
					}).join('');

					canChooseCount -= imgList.length;
					$add.before(html);

					if(canChooseCount <= 0){
						$('.j-add').remove();
					}

					setTimeout(function(){
						uploadImgList(imgList);
					}, 150);
				}
			})
		})
		.on('touchend', '.j-publish', function(){
			var content = $.trim($('.desc').val()),
				mediaList = [];

			if(!content.length){
				alert('请输入说说内容！');
				return;
			}
			if(!mediaIds.length){
				alert('请选择至少一张图片！');
				return;
			}

			$.each(mediaIds, function(index, mediaId){
				mediaList.push({
					media_id: mediaId
				});
			});

			if(isPost==0) {
				isPost = 1;
				$.ajax({
					type: 'POST',
					url: '/shuoshuo/doPost',
					data: {
						content: content,
						media_array: JSON.stringify({
							list: mediaList
						})
					},
					success: function(data){
						alert('发送成功');
						isPost = 0;
						location.href = '/resource/new/blog.html?ref='+new Date().getTime();
					},
					error: function(error){
						alert('发送失败，请稍后再试');
						isPost = 0;
					}
				});
			}
			else{
				alert('发送中...');
			}
		})
		.on('keypress', '.desc', function(e){
			if(e.keyCode == 13){
				$('.j-publish').trigger('touchend');
			}
		});

	var uploadImgList = function(imgList){
		if(imgList && imgList.length){
			var img = imgList.shift();
			uploadImage(img, function(){
				uploadImgList(imgList);
			});
		}
	};

	var uploadImage = function(img, succCallback){
		wx.uploadImage({
			localId: img,
			isShowProgressTips: 0,
			success: function(res){
				mediaIds.push(res.serverId);

				succCallback();
			}
		})
	};

});