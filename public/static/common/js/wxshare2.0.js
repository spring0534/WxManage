
/**!
 * 微信内置浏览器的Javascript API及联众互动js统一封装，功能包括：
 * 1、分享到微信朋友圈
 * 2、分享给微信好友
 * 3、分享到腾讯微博
 * 4、隐藏/显示右上角的菜单入口
 * 5、隐藏/显示底部浏览器工具栏
 * 6、自动调用统计函数
 * @author wintrue(http://wintrue.cn)
 */
(function(W) {
	var WX_STAT = W.WX_STAT = {};
	var statUrl='xt.i-lz.cn/stat',statLogUrl='xt.i-lz.cn';
	function l(t,aid,wxid,srcType,fromWxid,attent,pageUrl,shareUrl,pageTitle,logType,shareType) {
		this.t = t;
		this.aid=aid;
		this.wxid=wxid;
		this.srcType=srcType;
		this.fromWxid=fromWxid;
		this.attent=attent;
		this.shareUrl=shareUrl;
		this.logType=logType;
		this.shareType=shareType;
        this.a = {};
        this.la()
    }
	var h = document, f = window, d = encodeURIComponent,    k = decodeURIComponent,    p = unescape,    r = escape,  m = "https:" === f.location.protocol ? "https:": "http:",    s = m + "//"+statUrl;
	//日志统计函数
    function g(a, b) {
        try {
            /*var c = [];
            c.push("id=1");
            c.push("r=" + d(h.referrer));
            c.push("page=" + d(f.location.href));
            c.push("agent=" + d(f.navigator.userAgent));
            c.push("ex=" + d(b));
            c.push("rnd=" + Math.floor(2147483648 * Math.random())); 
			(new Image).src = statLogUrl+"?" + c.join("&")*/
        } catch(e) {}
    }
    function makeUUID() {
	    var S4 = function () {
	        return (((1 + Math.random()) * 0x10000) | 0).toString(16).substring(1);
	    };
	    return ( S4() + "-" + S4() + "-" + S4() + "-" + S4() + "-" + S4() );
	}
   //l属性
	l.prototype = {
        la: function() {
            try {
                this.U();
				this.ca();
                this.j();
                this.ea();
                this.ha();
                this.ka();
				this.br();
				this.nt();
				this.os();
				this.mb();
            } catch(a) {
                g(a, "i failed")
            }
        },
		//当前路径
        ca: function() {
            try {
                return this.a.f = f.location.href||'';
            } catch(a) {
                g(a, "gCP failed")
            }
        },
		//来访问路径
        j: function() {
            try {
                return this.a.ra = h.referrer || ""
            } catch(a) {
                g(a, "gR failed")
            }
        },
		//当前语言
        ea: function() {
            try {
                return this.a.n = f.navigator.systemLanguage || f.navigator.language,
                this.a.n = this.a.n.toLowerCase(),
                this.a.n
            } catch(a) {
                g(a, "gL failed")
            }
        },
		//分辨率
        ha: function() {
            try {
                return this.a.Q = f.screen.width && f.screen.height ? f.screen.width + "x" + f.screen.height: "0x0",
                this.a.Q
            } catch(a) {
                g(a, "gS failed")
            }
        },
		//网络类型
		nt: function(){
			var browserDetail = f.navigator.userAgent.toLowerCase();
			var ver = browserDetail.match(/nettype[ |\/]((.*))/)
			if(ver){
			this.a.nt= ver[1];
			}
	   },
	   //微信浏览器版本
		br: function(){
			var browserDetail = f.navigator.userAgent.toLowerCase();
			var ver = browserDetail.match(/micromessenger[ |\/]((.*?\s+))/)
			if(ver){
			this.a.brv= ver[1];
			}
	   },
	   //手机系统、版本
	   os:function(){
			    var sUserAgent = navigator.userAgent;
				var browserDetail = f.navigator.userAgent.toLowerCase();
				var os='Other';
				var Android = sUserAgent.indexOf("Android") > -1;
				if (Android) {
					os= "Android";
					ver = browserDetail.match(/android[ |\/]((.*?\s+))/)
				}
				var iPhone = sUserAgent.indexOf("iPhone") > -1 ;
				if (iPhone) {
					os= "iPhone";
					ver = browserDetail.match(/os\s+(.*?\s+)like\s+mac/)
				}
				var iPad = sUserAgent.indexOf("iPad") > -1;
				if (iPad) {
					os= "iPad";
					ver = browserDetail.match(/os\s+(.*?\s+)like\s+mac/)
				}
				var iPod = sUserAgent.indexOf("iPod") > -1;
				if (iPod) {
					os= "iPod";
					ver = browserDetail.match(/os\s+(.*?\s+)like\s+mac/)
				}
				var Windows = sUserAgent.indexOf("Windows") > -1;
				if (Windows) {
					os= "Windows phone";  
					ver = browserDetail.match(/Windows\s+Phone(\s+.*?);/)
				}					
			   this.a.os=os;
			   if(ver)   this.a.osver=ver[1];
		},
		//安卓系统版本
	    mb: function(){
			var browserDetail = f.navigator.userAgent.toLowerCase();
			var ver = browserDetail.match(/android(.*[\w-]+)\s+build/)
			if(ver){
			if(this.a.osver){
				ver[1]=ver[1].replace(this.a.osver,'');
			}
			this.a.mb= ver[1];
			}
			 
	   },
		//是否支持cookie
        U: function() {
            try {
                return ! 1 === f.navigator.cookieEnabled ? this.a.X = !1 : this.a.X = !0
            } catch(a) {
                g(a, "cCE failed")
            }
        },
		//标题
        ka: function() {
			try {
                return  this.a.pa=h.title || "";
            } catch(a) {
                g(a, "get title failed")
            }
        },
		//整数
        ma: function(a) {
            return 0 > r(a).indexOf("%u") ? !1 : !0
        },
        A: function() {
            try {
               var a = [];
                a.push("referrer=" + d(this.a.ra));//来访路径
				a.push("url=" + d(this.a.f));//当前路径
                a.push("lg=" + d(this.a.n));//语言
                a.push("tid=" + d(this.t));//网站标识
                a.push("screen=" + d(this.a.Q));//分辨率
                a.push("title=" + d(this.a.pa));//标题
                a.push("brv=" + d(this.a.brv));//浏览器版本
				a.push("netType=" + d(this.a.nt));//系统
                a.push("os=" + d(this.a.os));//系统
				a.push("osv=" + d(this.a.osver));//安卓系统版本
				a.push("mobile=" + d(this.a.mb));//手机型号
				a.push("aid=" + d(this.aid));//活动id
				a.push("wxid=" + d(this.wxid));//微信id
				a.push("srcType=" + d(this.srcType));//来源类型 主要是从朋友圈、好友之类的
				a.push("fromWxid=" + d(this.fromWxid));//来源微信id
				a.push("attent=" + d(this.attent));//是否关注
				a.push("logType=" + d(this.logType));//统计类型 0，page  1，share
				a.push("shareType=" + d(this.shareType));//分享类型
				a.push("shareUrl=" + d(this.shareUrl));//分享类型
                a.push("rnd=" + Math.floor(2147483648 * Math.random())); 
    			(new Image).src = s+'?' + a.join("&");
 
            } catch(c) {
                g(c, "sD failed")
            }
        }
    };
	WX_STAT.debug=false;
	var dataForWeixin;
	var setting = {
		aid : 0,
		wxid : '',
		fromType : 0,
		fromWxid : '',
		attent : 0
	};
	WX_STAT.dataForWeixin = {
		hideToolbar : true,
		hideOptionMenu : false,
		networkType : "",
		title : "",
		desc : "",
		img : "",
		link : "",
		appId : ""

	};
	var _extend = function () {
        var result = {}, obj, k;
        for (var i = 0, len = arguments.length; i < len; i++) {
            obj = arguments[i];
            if (typeof obj === 'object') {
                for (k in obj) {
                    obj[k] && (result[k] = obj[k]);
                }
            }
        }
        return result;
    };
	var shareCallback = {};
	WX_STAT.init = function(config, shareCallbackfun, seting_config) {
		var body = document.body;
		if (!body) {
			alert('"documents.body" not ready')
		}
		var ua = window.navigator.userAgent.toLowerCase(); 
		if(ua.match(/MicroMessenger/i) != 'micromessenger'){ 
			return ;//不统计电脑端 
		} 
		var regroup = function(shareCallbackfun) {
			config = config || {};
			dataForWeixin = WX_STAT.dataForWeixin;
			for ( var i in dataForWeixin) {
				if (config[i] !== undefined) {
					dataForWeixin[i] = config[i];
				}
				if(config[i+'_vars']){
					for ( var ii in config[i+'_vars']) {
						var _var=config[i+'_vars'][ii];
						dataForWeixin[i]=dataForWeixin[i].replace(ii, eval(_var));
					}
				}
			}
			return dataForWeixin;
		}
		seting_config = seting_config || {};
		for ( var i in setting) {
			if (seting_config[i] !== undefined) {
				setting[i] = seting_config[i];
			}
		}
		shareCallback = shareCallbackfun;
		regroup();
		wx.ready(function () {
			// 隐藏或显示右上角按钮
			if (dataForWeixin.hideOptionMenu) {
				 wx.hideOptionMenu();
			} else {
				 wx.showOptionMenu();
			}
			pageStat(0);//页面载入统计
			// 2.1 监听“分享给朋友”，按钮点击、自定义分享内容及分享结果接口
			wx.onMenuShareAppMessage({
			  title: dataForWeixin.title,
			  desc: dataForWeixin.desc,
			  link: dataForWeixin.link,
			  imgUrl: dataForWeixin.img,
			  trigger: function (res) {
							
			  },
			  success: function (res) {
				pageStat(1, dataForWeixin.link, 1);// 分享好友统计
				shareCallback.ok && shareCallback.ok(res);
			  },
			  cancel: function (res) {
				shareCallback.cancel && shareCallback.cancel(res);
			  },
			  fail: function (res) {
				shareCallback.fail && shareCallback.fail(res);
			  }
			});
			// 2.2 监听“分享到朋友圈”按钮点击、自定义分享内容及分享结果接口
			wx.onMenuShareTimeline({
			  title: dataForWeixin.title,
			  link: dataForWeixin.link,
			  imgUrl: dataForWeixin.img,
			  success: function (res) {
					pageStat(1, dataForWeixin.link, 2);// 分享朋友圈统计
					shareCallback.ok && shareCallback.ok(res);
			  },
			  cancel: function (res) {
				shareCallback.cancel && shareCallback.cancel(res);
			  },
			  fail: function (res) {
				shareCallback.fail && shareCallback.fail(res);
			  }
			});
			// 2.3 监听“分享到QQ”按钮点击、自定义分享内容及分享结果接口
			wx.onMenuShareQQ({
			  title: dataForWeixin.title,
			  desc: dataForWeixin.desc,
			  link: dataForWeixin.link,
			  imgUrl: dataForWeixin.img,
			  success: function (res) {
				  pageStat(1, dataForWeixin.link, 3);
				shareCallback.ok && shareCallback.ok(res);
			  },
			  cancel: function (res) {
				shareCallback.cancel && shareCallback.cancel(res);
			  },
			  fail: function (res) {
				shareCallback.fail && shareCallback.fail(res);
			  }
			});
			 // 2.4 监听“分享到微博”按钮点击、自定义分享内容及分享结果接口
			wx.onMenuShareWeibo({
			  title: dataForWeixin.title,
			  desc: dataForWeixin.desc,
			  link: dataForWeixin.link,
			  imgUrl: dataForWeixin.img,
			  success: function (res) {
				  pageStat(1, dataForWeixin.link, 4);
				shareCallback.ok && shareCallback.ok(res);
			  },
			  cancel: function (res) {
				shareCallback.cancel && shareCallback.cancel(res);
			  },
			  fail: function (res) {
				shareCallback.fail && shareCallback.fail(res);
			  }
			});
		})
		
	};
	//统计
	function pageStat(logType, shareUrl, shareType) {
		var pageUrl = window.location.href;
		var pageTitle = document.title;
		if (!shareUrl) {
			shareUrl='';
		}
		if (!shareType) {
			shareType=0;
		}
		try {
			var n = new l(0,setting.aid,setting.wxid,setting.fromType,setting.fromWxid,setting.attent,pageUrl,shareUrl,pageTitle,logType,shareType);
			n.A() ;
		} catch(t) {
			g(t, "main failed")
		}
	}
})(window);
