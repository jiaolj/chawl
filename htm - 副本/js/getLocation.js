var getLocation = {
//浏览器原生获取经纬度方法
	latAndLon: function (callback, error) {
		var that = this;
		if (navigator.geolocation) {
			navigator.geolocation.getCurrentPosition(function (position) {
					var latitude = position.coords.latitude;
					var longitude = position.coords.longitude;
					localStorage.setItem("latitude", latitude);
					localStorage.setItem("longitude", longitude);
					var data = {
						latitude: latitude,
						longitude: longitude
					};
					if (typeof callback == "function") {
						callback(data);
					}
				},
				function () {
					if (typeof error == "function") {
						error();
					}
				});
		} else {
			if (typeof error == "function") {
				error();
			}
		}
	},
//微信JS-SDK获取经纬度方法
	weichatLatAndLon: function (callback, error) {
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
						'checkJsApi',
						'getLocation'
					]
				});
				wx && wx.ready(function(){
					wx.getLocation({
						success: function (res) {
							var latitude = res.latitude; // 纬度，浮点数，范围为90 ~ -90
							var longitude = res.longitude; // 经度，浮点数，范围为180 ~ -180。
							var speed = res.speed; // 速度，以米/每秒计
							var accuracy = res.accuracy; // 位置精度
							localStorage.setItem("latitude", latitude);
							localStorage.setItem("longitude", longitude);
							var data = {
								latitude: latitude,
								longitude: longitude
							};
							if (typeof callback == "function") {
								callback(data);
							}
						},
						cancel: function () {
							//这个地方是用户拒绝获取地理位置
							if (typeof error == "function") {
								error();
							}
						}
					});
				});
				wx && wx.error(function(res){
					alert(str(res));
				});
			}
		})
	},
	//将经纬度转换成城市名和街道地址，参见百度地图接口文档：http://developer.baidu.com/map/index.php?title=webapi/guide/webservice-geocoding
	cityname: function (latitude, longitude, callback) {
		$.ajax({
			url: 'http://api.map.baidu.com/geocoder/v2/?ak=btsVVWf0TM1zUBEbzFz6QqWF&callback=renderReverse&location=' + latitude + ',' + longitude + '&output=json&pois=1',
			type: "get",
			dataType: "jsonp",
			jsonp: "callback",
			success: function (data) {
				console.log(data);
				var province = data.result.addressComponent.province;
				var cityname = (data.result.addressComponent.city);
				var district = data.result.addressComponent.district;
				var street = data.result.addressComponent.street;
				var street_number = data.result.addressComponent.street_number;
				var formatted_address = data.result.formatted_address;
				localStorage.setItem("province", province);
				localStorage.setItem("cityname", cityname);
				localStorage.setItem("district", district);
				localStorage.setItem("street", street);
				localStorage.setItem("street_number", street_number);
				localStorage.setItem("formatted_address", formatted_address);
				//domTempe(cityname,latitude,longitude);
				var data = {
					latitude: latitude,
					longitude: longitude,
					cityname: cityname
				};
				if (typeof callback == "function") {
					callback(data);
				}

			}
		});
	},
	//设置默认城市
	setDefaultCity: function (callback) {
		log("获取地理位置失败！");
		//默认经纬度
		var latitude = "";
		var longitude = "";
		var cityname = "上海市";
		localStorage.setItem("latitude", latitude);
		localStorage.setItem("longitude", longitude);
		localStorage.setItem("cityname", cityname);
		localStorage.setItem("province", "江苏省");
		localStorage.setItem("district", "虎丘区");
		localStorage.setItem("street", "珠江路");
		localStorage.setItem("street_number", "88号");
		localStorage.setItem("formatted_address", "江苏省苏州市虎丘区珠江路88号");
		var data = {
			latitude: latitude,
			longitude: longitude,
			cityname: cityname
		};
		if (typeof callback == "function") {
			callback(data);
		}
	},
	//更新地理位置
	refresh: function (callback) {
		var that = this;
		//重新获取经纬度和城市街道并设置到localStorage
		that.latAndLon(
			function (data) {
				that.cityname(data.latitude, data.longitude, function (datas) {
					if (typeof callback == "function") {
						callback();
					}
				});
			},
			function(){
				that.setDefaultCity(function(){
					if (typeof callback == "function") {
						callback();
					}
				});
			});
	},
	setValue : function(v,suc){
		$('#cityname').text(v);
		//$('#cityname').attr('py',codefans_net_CC2PY(v.replace('市','')).toLowerCase());
		suc && suc();
	},
	init : function(suc){
		var _key = 'cityname1';
		if($.cookie(_key)){
			getLocation.setValue($.cookie(_key),suc);
		}
		else{
			getLocation.latAndLon(
				function (data) {
					//data包含经纬度信息
					getLocation.cityname(data.latitude, data.longitude, function (datas) {
						//datas包含经纬度信息和城市
						getLocation.setValue(datas.cityname,suc);
						$.cookie(_key,datas.cityname,3600*2);
					});
				},
				function () {
					getLocation.setDefaultCity(
						function (defaultData) {
							//设置默认城市
							getLocation.setValue(defaultData.cityname,suc);
							$.cookie(_key,defaultData.cityname,3600*2);
						}
					);
				}
			);
			/*getLocation.weichatLatAndLon(
				function (data) {
					//data包含经纬度信息
					getLocation.cityname(data.latitude, data.longitude, function (datas) {
						//datas包含经纬度信息和城市
						getLocation.setValue(datas.cityname,suc);
						$.cookie(_key,datas.cityname,3600*2);
					})
				},
				function () {
					getLocation.setDefaultCity(
						function (defaultData) {
							//设置默认城市
							getLocation.setValue(defaultData.cityname,suc);
							$.cookie(_key,defaultData.cityname,3600*2);
						}
					)
				}
			)*/
		}
	}
};