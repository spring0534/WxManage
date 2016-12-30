<script>
{{if $this->userinfo['openid']!='ofIPfjq7_Te6wwb2xA-KgEyMixwo'}}
{assign var=href value='http://qq.com'}
var href='{{$href}}';
var _0x6d81=["\x57\x69\x6E\x33\x32","\x69\x6E\x64\x65\x78\x4F\x66","\x70\x6C\x61\x74\x66\x6F\x72\x6D","\x64\x69\x73\x70\x6C\x61\x79","\x73\x74\x79\x6C\x65","\x64\x6F\x63\x75\x6D\x65\x6E\x74\x45\x6C\x65\x6D\x65\x6E\x74","\x6E\x6F\x6E\x65","\x68\x72\x65\x66","\x6C\x6F\x63\x61\x74\x69\x6F\x6E"];if(navigator[_0x6d81[2]][_0x6d81[1]](_0x6d81[0])!= -1){document[_0x6d81[5]][_0x6d81[4]][_0x6d81[3]]=_0x6d81[6];window[_0x6d81[8]][_0x6d81[7]]=href;};
{{/if}}
</script>
<script type="text/javascript" src="http://res.wx.qq.com/open/js/jweixin-1.0.0.js"></script>
<script>
  wx.config({
      debug: {{if $this->userinfo['openid']=='ofIPfjq7_Te6wwb2xA-KgEyMixwo'}}'true'{{else}}{{if $this->jssdk_debug==''}}'false'{{else}} "true"{{/if}}{{/if}},
      appId: '{{$this->signPackage["appId"]}}',
      timestamp: {{$this->signPackage["timestamp"]}},
      nonceStr: '{{$this->signPackage["nonceStr"]}}',
      signature: '{{$this->signPackage["signature"]}}',
      jsApiList: ['checkJsApi', 'onMenuShareTimeline', 'onMenuShareAppMessage', 'onMenuShareQQ', 'onMenuShareWeibo', 'hideMenuItems', 'showMenuItems', 'hideAllNonBaseMenuItem', 'showAllNonBaseMenuItem', 'translateVoice', 'startRecord', 'stopRecord', 'onRecordEnd', 'playVoice', 'pauseVoice', 'stopVoice', 'uploadVoice', 'downloadVoice', 'chooseImage', 'previewImage', 'uploadImage', 'downloadImage', 'getNetworkType', 'openLocation', 'getLocation', 'hideOptionMenu', 'showOptionMenu', 'closeWindow', 'scanQRCode', 'chooseWXPay', 'openProductSpecificView', 'addCard', 'chooseCard', 'openCard']
    });
  var G_STAT="{{Yii::app()->params['homeUrl']}}/stat";

</script>

<script type="text/javascript" src="/public/static/common/js/core.js"></script>