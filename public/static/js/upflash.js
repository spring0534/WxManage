function challs_flash_onComplete(a){ //每次上传完成调用的函数，并传入一个Object类型变量，包括刚上传文件的大小，名称，上传所用时间,文件类型
	var name=a.fileName; //获取上传文件名
	var size=a.fileSize; //获取上传文件大小，单位字节
	var time=a.updateTime; //获取上传所用时间 单位毫秒
	var type=a.fileType; //获取文件类型，在 Windows 上，此属性是文件扩展名。 在 Macintosh 上，此属性是由四个字符组成的文件类型
	document.getElementById('show').innerHTML+=name+' --- '+size+'字节 ----文件类型：'+type+'--- 用时 '+(time/1000)+'秒<br><br>'
}

function challs_flash_onCompleteData(a){ //获取服务器反馈信息事件
	document.getElementById('show').innerHTML+='<font color="#ff0000">服务器端反馈信息：</font><br />'+a+'<br />';	
}
function challs_flash_onStart(a){ //开始一个新的文件上传时事件,并传入一个Object类型变量，包括刚上传文件的大小，名称，类型
	var name=a.fileName; //获取上传文件名
	var size=a.fileSize; //获取上传文件大小，单位字节
	var type=a.fileType; //获取文件类型，在 Windows 上，此属性是文件扩展名。 在 Macintosh 上，此属性是由四个字符组成的文件类型
	document.getElementById('show').innerHTML+=name+'开始上传！<br />';
	
	return true; //返回 false 时，组件将会停止上传
}

function challs_flash_onStatistics(a){ //当组件文件数量或状态改变时得到数量统计，参数 a 对象类型
	var uploadFile = a.uploadFile; //等待上传数量
	var overFile = a.overFile; //已经上传数量
	var errFile = a.errFile; //上传错误数量
}

function challs_flash_alert(a){ //当提示时，会将提示信息传入函数，参数 a 字符串类型
	document.getElementById('show').innerHTML+='<font color="#ff0000">组件提示：</font>'+a+'<br />';
}

/*function challs_flash_onCompleteAll(a){ //上传文件列表全部上传完毕事件,参数 a 数值类型，返回上传失败的数量
	document.getElementById('show').innerHTML+='<font color="#ff0000">所有文件上传完毕，</font>上传失败'+a+'个！<br />';
	//window.location.href='http://www.access2008.cn/update'; //传输完成后，跳转页面
}
*/
function challs_flash_onSelectFile(a){ //用户选择文件完毕触发事件，参数 a 数值类型，返回等待上传文件数量
	document.getElementById('show').innerHTML+='<font color="#ff0000">文件选择完成：</font>等待上传文件'+a+'个！<br />';
}

function challs_flash_deleteAllFiles(){ //清空按钮点击时，出发事件

	//返回 true 清空，false 不清空
	return confirm("你确定要清空列表吗?");
}

function challs_flash_onError(a){ //上传文件发生错误事件，并传入一个Object类型变量，包括错误文件的大小，名称，类型
	var err=a.textErr; //错误信息
	var name=a.fileName; //获取上传文件名
	var size=a.fileSize; //获取上传文件大小，单位字节
	var type=a.fileType; //获取文件类型，在 Windows 上，此属性是文件扩展名。 在 Macintosh 上，此属性是由四个字符组成的文件类型
	document.getElementById('show').innerHTML+='<font color="#ff0000">'+name+' - '+err+'</font><br />';
}

function challs_flash_FormData(a){ // 使用FormID参数时必要函数
	try{
		var value = '';
		var id=document.getElementById(a);
		if(id.type == 'radio'){
			var name = document.getElementsByName(id.name);
			for(var i = 0;i<name.length;i++){
				if(name[i].checked){
					value = name[i].value;
				}
			}
		}else if(id.type == 'checkbox'){
			var name = document.getElementsByName(id.name);
			for(var i = 0;i<name.length;i++){
				if(name[i].checked){
					if(i>0) value+=",";
					value += name[i].value;
				}
			}
		}else if(id.type == 'select-multiple'){
		    for(var i=0;i<id.length;i++){
		        if(id.options[i].selected){
					if(i>0) value+=",";
			         values += id.options[i].value; 
			    }
		    }
		}else{
			value = id.value;
		}
		return value;
	 }catch(e){
		return '';
	 }
}

function challs_flash_style(){ //组件颜色样式设置函数
	var a = {};
	
	/*  整体背景颜色样式 */
	a.backgroundColor=['#f3f3f3','#f3f3f3','#f3f3f3'];	//颜色设置，3个颜色之间过度
	a.backgroundLineColor='#f3f3f3';					//组件外边框线颜色
	a.backgroundFontColor='#f06';					//组件最下面的文字颜色
	a.backgroundInsideColor='#f3f3f3';					//组件内框背景颜色
	a.backgroundInsideLineColor=['#ccc','#fff'];	//组件内框线颜色，2个颜色之间过度
	a.upBackgroundColor='#ffffff';						//上翻按钮背景颜色设置
	a.upOutColor='#ccc';								//上翻按钮箭头鼠标离开时颜色设置
	a.upOverColor='#f06';							//上翻按钮箭头鼠标移动上去颜色设置
	a.downBackgroundColor='#fff';					//下翻按钮背景颜色设置
	a.downOutColor='#ccc';							//下翻按钮箭头鼠标离开时颜色设置
	a.downOverColor='#f06';							//下翻按钮箭头鼠标移动上去时颜色设置
	
	/*  头部颜色样式 */
	a.Top_backgroundColor=['#EAEAEA','#EAEAEA']; 		//颜色设置，数组类型，2个颜色之间过度
	a.Top_fontColor='#333';							//头部文字颜色
	
	
	/*  按钮颜色样式 */
	a.button_overColor=['#ccc','#f06'];			//鼠标移上去时的背景颜色，2个颜色之间过度
	a.button_overLineColor='#f06';					//鼠标移上去时的边框颜色
	a.button_overFontColor='#ffffff';					//鼠标移上去时的文字颜色
	a.button_outColor=['#ffffff','#dde8fe']; 			//鼠标离开时的背景颜色，2个颜色之间过度
	a.button_outLineColor='#91bdef';					//鼠标离开时的边框颜色
	a.button_outFontColor='#245891';					//鼠标离开时的文字颜色
	
	/* 文件列表样式 */
	a.List_scrollBarColor="#000000"						//列表滚动条颜色
	a.List_backgroundColor='#EAF0F8';					//列表背景色
	a.List_fontColor='#333333';							//列表文字颜色
	a.List_LineColor='#B3CDF1';							//列表分割线颜色
	a.List_cancelOverFontColor='#ff0000';				//列表取消文字移上去时颜色
	a.List_cancelOutFontColor='#D76500';				//列表取消文字离开时颜色
	a.List_progressBarLineColor='#B3CDF1';				//进度条边框线颜色
	a.List_progressBarBackgroundColor='#D8E6F7';		//进度条背景颜色	
	a.List_progressBarColor=['#FFCC00','#FFFF00'];		//进度条进度颜色，2个颜色之间过度
	
	/* 错误提示框样式 */
	a.Err_backgroundColor='#C0D3EB';					//提示框背景色
	a.Err_fontColor='#245891';							//提示框文字颜色
	a.Err_shadowColor='#000000';						//提示框阴影颜色
	
	
	return a;
}


var isMSIE = (navigator.appName == "Microsoft Internet Explorer");   
function thisMovie(movieName){   
  if(isMSIE){   
  	return window[movieName];   
  }else{
  	return document[movieName];   
  }   
}