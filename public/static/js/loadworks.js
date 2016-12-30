function inilist(){
var max_H=0;
var wrap = document.getElementById("conlist");
var margin = 10;
var li=$(".wordlist");
var	li_W = li[0].offsetWidth+margin;
var h=[];
    li.css("position","absolute");
	var n = wrap.offsetWidth/li_W|0;
	for(var i = 0;i < li.length;i++) {
		li_H = li[i].offsetHeight;
		if(i < n) {
			h[i]=li_H;
			li.eq(i).css("top",0);
			li.eq(i).css("left",i * li_W);
			}
		else{
			min_H =Math.min.apply(null,h) ;
			
			minKey = getarraykey(h, min_H);
			h[minKey] += li_H+margin ;
			li.eq(i).css("top",min_H+margin);
			li.eq(i).css("left",minKey * li_W);	
		}
	}
	max_H =Math.max.apply(null,h) ;
	$("#conlist").css("height",max_H+margin+"px");
}
function getarraykey(s, v) {for(k in s) {if(s[k] == v) {return k;}}}

function getMore(url){
	var page=0,allpage=0;
	$(window).unbind("scroll");
	$(".loading").show();	
		$.getJSON(url, function(data){
		page=parseInt(data.page);	
		allpage=parseInt(data.allpage);	
		$("#conlist").append(data.htmls);
		$(".loading").hide();
		inilist();
		if(page<allpage-1){
		 $(window).bind("scroll",function(){
	if($(document).scrollTop() + $(window).height() > $(document).height() - 10 ) {
		getMore(url+"?page="+(page+1));
		}
	 });
		}
		});
	}