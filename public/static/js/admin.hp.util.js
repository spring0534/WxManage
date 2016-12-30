var RUITOP = RUITOP || {};
RUITOP.util = {
	rtrim: /^(\s|\u00A0)+|(\s|\u00A0)+$/g,
	trim: function(text) {
		return (text || "").replace(this.rtrim, "");
	},
	getHashParams: function(str) {
		var hash = str ? str: decodeURIComponent(location.hash),
		hashArr = [],
		obj = {};
		hash.replace(/[\.\?\/'"><:;,\[\]\{\}]/ig, '');
		hashArr = hash.split("\/");
		if (hashArr.length > 0) {
			obj["pageId"] = hashArr[0].substring(1);
			obj["urlParams"] = (hashArr.length > 1) ? TOUCH.util.strToObj(hashArr[1], true) : {};
		}
		return obj;
	},
	cookies: {
		getCookie: function(name) {
			var arr,
			reg = new RegExp("(^| )" + name + "=([^;]*)(;|$)");
			if (arr = document.cookie.match(reg)) {
				return unescape(arr[2]);
			} else {
				return '';
			}
		},
		setCookie: function(name, value, expiresHours) {
			var cookieString = name + "=" + escape(value);
			if (expiresHours > 0) {
				var date = new Date();
				date.setTime(date.getTime + expiresHours * 3600 * 1000);
				cookieString = cookieString + ";expires=" + date.toGMTString() + ";path=/;";
				document.cookie = cookieString;
			} else {
				document.cookie = cookieString + ";path=/;";
			}
		},
		delCookie: function(name) {
			var exp = new Date();
			exp.setTime(exp.getTime() - 1);
			var cval = getCookie(name);
			if (cval != null) document.cookie = name + "=" + cval + ";expires=" + exp.toGMTString() + ";path=/;";
		}
	}
}










