var isIE6 = !-[1, ] && !window.XMLHttpRequest;
function read_cart(cf) {
    $.get(weburl + "/Ajax-show_cart.html?sf=" + Math.floor(Math.random() * 10), function (res) {
        //if(res.data!="0"){
        $("#mycart dl.c").html(res.msg);
        //}
        $("#mycart dl.f li.l span").html(res.data);
		if(typeof(cf)=='function'){
				cf();
		}
    }, "json");
}
$(document).ready(function () {
    read_cart();
    all_myrecord();
});
//全部记录
function all_myrecord() {
    $.get(weburl + "/Ajax-all_myrecord.html?sf=" + Math.floor(Math.random() * 10), function (res) {
        if (res.code == "ajaxok") {
            $("#myview dl.c").html(res.msg);
            $("#myview dl.f li.l span").html(res.data);
        }
    }, "json");
}
$(document).ready(function () {
    read_cart();
});
//清空浏览记录
function clear_myrecord() {
    $.get(weburl + "/Ajax-clear_myrecord.html", function (res) {
        if (res.code == "ajaxok") {
            $("#myview dl.c").html('');
            $("#myview dl.f li.l span").html("0");
        } else {
            alert(res.msg);
        }
    }, "json");
 
}
//删除一条浏览记录
function remove_record(obj) {
    $.get(weburl + "/Ajax-remove_record-id-" + $(obj).attr("hre") + ".html", function (res) {
        if (res.code == "ajaxok") {
            all_myrecord();
        } else {
            alert(res.msg);
        }
    }, "json");
 
}
//增加到购物车
//产品ID，数目，选择ID组合，选择文本组合
function add_cart(pid, n, skuid, val) {
    $.post(weburl + "/Ajax-add_cart.html", {
        id: pid,
        buynum: n,
        skuid: skuid,
        val: val
    }, function (res) {
        if (res.code == "ajaxok") {
            read_cart(function(){sele($('.src').first(), $('.src .list li img').first(), $('#ap'+$('#tap_id').val()).first(),1);});
            show_cart();
        } else {
            alert(res.msg);
        }
    }, "json");
 
}
//清空购物车
function clear_cart() {
    $.get(weburl + "/Ajax-clear_cart.html", function (res) {
        if (res.code == "ajaxok") {
            $("#mycart dl.c").html('');
            $("#mycart dl.f li.l span").html("0.00");
        } else {
            alert(res.msg);
        }
    }, "json");
 
}
 
//点击下面两个蛋
function show_cart() {
    $(".floatgrt ul.l").removeClass("on");
    $("#myview").hide();
    $("#mycart").show();
    if ($(".floatgrt ul.r").hasClass("on") == false) {
        $(".floatgrt ul.r").addClass("on");
    }
}
function show_view() {
    $(".floatgrt ul.r").removeClass("on");
    $("#myview").show();
    $("#mycart").hide();
    if ($(".floatgrt ul.l").hasClass("on") == false) {
        $(".floatgrt ul.l").addClass("on");
    }
}
$(".floatgrt ul").click(function () {
    if ($(this).html() == '购物车') {
        show_cart();
    } else {
        show_view();
 
    }
});
function remove_cart(obj) {
    $.get(weburl + "/Ajax-del_cart-rowid-" + $(obj).attr("hre") + ".html", function (res) {
        if (res.code == "ajaxok") {
            read_cart();
            show_cart();
        } else {
            alert(res.msg);
        }
    }, "json");
 
}
function hide_viewcart() {
    $(".flaotmycc").hide();
    $(".floatgrt ul").removeClass("on");
}
//单一购买
function buy_goods(pid, num, valid, val) {
    $.post(weburl + "/Ajax-buy_goods.html", {
        pid: pid,
        num: num,
        valid: valid,
        val: val
    }, function (res) {
        switch (res.code) {
        case "ajaxok":
            location.href = weburl + '/checkout.html?buyid=' + res.msg;
            break;
        case "nologin":
            showLogin();
            break;
        case "ajaxerror":
            alert(res.msg);
            break;
        }
 
    }, "json");
}
function sele(o, t, a,type) {
	var tosrc,tow,toh;
    var areaTop = a.offset().top;
    var areaLeft = a.offset().left;
    var areaWidth = a.outerWidth();
    var n = o.offset();
    n.opacity = 0.5;
    n.position = "absolute";
    n["z-index"] = 9999;
    var i = t.clone();
	if(type){
			tosrc=a.attr('src');
			tow=a.width();
			toh=a.height();
		}else{
			tosrc="http://r1.suc.itc.cn/itoolbar/channel/pp16x16.png";
			tow=toh=16;
	}
    i.css(n).appendTo("body").animate({
        opacity: .5,
        width: tow,
        height: toh,
        top: areaTop - 10,
        left: areaLeft
    }, 800, function () {
		
        i.attr("src", tosrc),
        i.animate({
            top: areaTop,
            left: type?areaLeft:(areaLeft + areaWidth / 2 - 8)
        }, 500, function () {
            i.remove();
 
        })
    })
}
$(window).scroll(function () {
    if ($(window).scrollTop() > 300) {
        $('#toTop').fadeIn(200)
    } else {
        $('#toTop').fadeOut(1000)
    };
    if (isIE6) {
        $(".floatgoods").css("position", "absolute");
        $(".floatgoods").css("top", ($(window).height() + $(window).scrollTop() - 40) + "px");
    }
});
