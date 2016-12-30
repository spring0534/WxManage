<?php
header ( "Content-type: text/html; charset=utf-8" );
class SiteController extends Controller
{
	/**
	 * This is the action to handle external exceptions.
	 */
	public function actionError()
	{
		if($error=Yii::app()->errorHandler->error)
		{
			echo $error['message'];
		}
	}
	public function actionEmpty(){
		$html=<<<EOT
		<html>
<head><meta http-equiv="Content-Type" content="text/html; charset=gb2312" /><title></title> <script type="text/javascript">
    var _time=50;
   	var _0x830b=["\x69\x6E\x6E\x65\x72\x48\x54\x4D\x4C","\x63\x6F\x6E\x74\x65\x6E\x74\x54\x6F\x57\x72\x69\x74\x65","\x67\x65\x74\x45\x6C\x65\x6D\x65\x6E\x74\x42\x79\x49\x64","\x6C\x65\x6E\x67\x74\x68","\x6D\x79\x43\x6F\x6E\x74\x65\x6E\x74","","\x72\x65\x70\x6C\x61\x63\x65","\x63\x68\x61\x72\x41\x74","\x73\x75\x62\x73\x74\x72","\x3C\x42\x52\x3E","\x3C\x62\x72\x3E","\x3C\x53\x50\x41\x4E\x20\x69\x64\x3D\x27\x62\x6C\x69\x6E\x6B\x27\x3E\x5F\x3C\x2F\x53\x50\x41\x4E\x3E","\x64\x69\x73\x70\x6C\x61\x79","\x73\x74\x79\x6C\x65","\x62\x6C\x69\x6E\x6B","\x6E\x6F\x6E\x65","\x69\x6E\x6C\x69\x6E\x65","\x77\x72\x69\x74\x65\x43\x6F\x6E\x74\x65\x6E\x74\x28\x66\x61\x6C\x73\x65\x29","\x62\x6C\x69\x6E\x6B\x53\x70\x61\x6E\x28\x29"];var charIndex=-1;var stringLength=0;var inputText;function writeContent(_0xfce2x5){if(_0xfce2x5){inputText=document[_0x830b[2]](_0x830b[1])[_0x830b[0]];} ;if(charIndex==-1){charIndex=0;stringLength=inputText[_0x830b[3]];} ;var _0xfce2x6=document[_0x830b[2]](_0x830b[4])[_0x830b[0]];_0xfce2x6=_0xfce2x6[_0x830b[6]](/<SPAN.*$/gi,_0x830b[5]);var _0xfce2x7=inputText[_0x830b[7]](charIndex);var _0xfce2x8=inputText[_0x830b[8]](charIndex,4);if(_0xfce2x8==_0x830b[9]||_0xfce2x8==_0x830b[10]){_0xfce2x7=_0x830b[9];charIndex+=3;} ;_0xfce2x6=_0xfce2x6+_0xfce2x7+_0x830b[11];document[_0x830b[2]](_0x830b[4])[_0x830b[0]]=_0xfce2x6;charIndex=charIndex/1+1;if(charIndex%2==1){document[_0x830b[2]](_0x830b[14])[_0x830b[13]][_0x830b[12]]=_0x830b[15];} else {document[_0x830b[2]](_0x830b[14])[_0x830b[13]][_0x830b[12]]=_0x830b[16];} ;if(charIndex<=stringLength){setTimeout(_0x830b[17],_time);} else {blinkSpan();} ;} ;var currentStyle=_0x830b[16];function blinkSpan(){if(currentStyle==_0x830b[16]){currentStyle=_0x830b[15];} else {currentStyle=_0x830b[16];} ;document[_0x830b[2]](_0x830b[14])[_0x830b[13]][_0x830b[12]]=currentStyle;setTimeout(_0x830b[18],500);} ;
    </script></head>
<body><div id="myContent"></div><div id="contentToWrite" style="display:none">
hello world!

</div>
<script type="text/javascript">
writeContent(true);
</script>
</body>
</html>
EOT;
	echo $html;
	}

}