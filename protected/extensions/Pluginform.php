<?php

/**
* Pluginform.php
* ----------------------------------------------
* 版权所有 2014-2015 
* ----------------------------------------------
* @date: 2014-12-5
* 
*/
class Pluginform{
	// 验证表单JS代码组装
	public $formValidator="";
	public function text($value, $propnameinfo){
		extract($propnameinfo);
		$setting=unserialize($setting);
		if ($setting)
			extract($setting);
		$size=$size;
		if ($value=="")
			$value=$defaultvalue;
		$type=$ispassword ? 'password' : 'text';
		if (!empty($maxlength)){
			$maxstr="data-max=$maxlength  ";
		}
		
		/*if (!empty($minlength)){
			$minstr="data-min=$minlength  ";
		}*/
		if ($required==1){
			$allow_empty="data-required='true' ";
		}
		if ($pattern!=""){
			$patternstr="data-type='$pattern' ";
		}
		$this->formValidator=$maxstr.$minstr.$allow_empty.$patternstr."data-trigger='change'";
		
		return '<input '.$this->formValidator.' type="'.$type.'" name="info['.$propname.']" id="'.$propname.'" size="'.$size.'" value="'.$value.'" class="input-text" '.$formattribute.' '.$css.'>';
	}
	function color($value, $propnameinfo){
		extract($propnameinfo);
		$setting=unserialize($setting);
		if ($setting)
			extract($setting);
		$size=$size;
		if ($value=="")
			$value=$defaultvalue;
		return '<input '.$this->formValidator.'  name="info['.$propname.']" id="'.$propname.'"class="colorpicker-position-bottom-left input" data-default-value="#ffcc00" type="text" value="#ffb987" />';
	}
	function music($value, $propnameinfo){
		extract($propnameinfo);
		$setting=unserialize($setting);
		if ($setting)
			extract($setting);
		if ($value=="")
			$value=$defaultvalue;
		$upload= musicUpdoad("info[$propname]", $value ? $value : $defaultvalue, $propname, array(
			'id'=>$propname
		));
		return $upload;
	}
	function textarea($value, $propnameinfo){
		extract($propnameinfo);
		$setting=unserialize($setting);
		if ($setting)
			extract($setting);
		if ($value=="")
			$value=$defaultvalue;
		if (!empty($maxlength)){
			$maxstr="data-max=$maxlength  ";
		}
		
		/*if (!empty($minlength)){
			$minstr="data-min=$minlength  ";
		}*/
		if ($required==1){
			$allow_empty="data-required='true' ";
		}
		if ($pattern!=""){
			$patternstr="data-type='$pattern' ";
		}
		$this->formValidator=$maxstr.$minstr.$allow_empty.$patternstr."data-trigger='change'";
		return "<textarea name='info[$propname]' id='$propname' style='width:{$width}px;height:{$height}px;' $formattribute $css>{$value}</textarea>";
	}
	function datetime($value, $propnameinfo){
		extract($propnameinfo);
		$setting=unserialize($setting);
		if ($setting)
			extract($setting);
		if (!empty($maxlength)){
			$maxstr="data-max=$maxlength  ";
		}
		
		/*if (!empty($minlength)){
			$minstr="data-min=$minlength  ";
		}*/
		if ($required==1){
			$allow_empty="data-required='true' ";
		}
		if ($pattern!=""){
			$patternstr="data-type='$pattern' ";
		}
		if (empty($value)){
			/*
			 * if ($timeformat == '%Y-%m-%d') {
			 * $value = toDate ( time (), 'Y-m-d' );
			 * } else {
			 * $value = toDate ( time (), 'Y-m-d H:i:s' );
			 * }
			 */
		}
		$this->formValidator=$maxstr.$minstr.$allow_empty.$patternstr."data-trigger='change'";
		return calendar("info[$propname]", $value, $timeformat);
	}
	function number($value, $propnameinfo){
		extract($propnameinfo);
		$setting=unserialize($setting);
		if ($setting)
			extract($setting);
		if ($value=="")
			$value=$defaultvalue;
		if (!empty($maxlength)){
			$maxstr="data-max=$maxlength  ";
		}
		
		/*if (!empty($minlength)){
			$minstr="data-min=$minlength  ";
		}*/
		if ($required==1){
			$allow_empty="data-required='true' ";
		}
		if ($pattern!=""){
			$patternstr="data-type='$pattern' ";
		}
		$this->formValidator=$maxstr.$minstr.$allow_empty.$patternstr."data-trigger='change'";
		return "<input ".$this->formValidator."  type='text' name='info[$propname]' id='$propname' value='$value' size='$size' class='input-text' {$formattribute} {$css}>";
	}
	
	/**
	 * 图片字段表单组合处理
	 *
	 * @param type $propname
	 * 字段名
	 * @param type $value
	 * 字段内容
	 * @param type $propnameinfo
	 * 字段配置
	 * @return type
	 */
	function image($value, $propnameinfo){
		extract($propnameinfo); // 字段变量
		$setting=unserialize($setting); // 配置
		if ($setting)
			extract($setting);
			/*
		 * if ($setting['show_type']) {
		 * $preview_img = $value ? $value : WEB_URL . '/Public/images/icon/upload-pic.png';
		 * return "<div style=\"text-align: left;\"><input type='hidden' name='info[$propname]' id='$propname' value='$value'>
		 * <a href='javascript:void(0);' onclick=\"flashupload('{$propname}_images', '附件上传','{$propname}',thumb_images,'1,{$setting['upload_allowext']},{$setting['isselectimage']},{$setting['images_width']},{$setting['images_height']},{$setting['watermark']},{$setting['size_limit']}','{$module}','$authkey');return false;\">
		 * <img src='$preview_img' id='{$propname}_preview' width='135' height='113' style='cursor:hand' /></a>
		 * <br/> " . $html . " </div>";
		 * } else {
		 *
		 * return "<input type='text' name='info[$propname]' id='$propname' value='$value' style='width:{$width}px;' class='input' /> <input type='button' class='button' onclick=\"flashupload('{$propname}_images', '附件上传','{$propname}',submit_images,'1,{$setting['upload_allowext']},{$setting['isselectimage']},{$setting['images_width']},{$setting['images_height']},{$setting['watermark']},{$setting['size_limit']}','{$module}','$authkey')\"/ value='上传图片'>" . $html;
		 * }
		 */
		if (isset($setting['show_type'])&&$setting['show_type']==0){
			$t_type='input';
		}else{
			$t_type='image';
		}
		return imageUpdoad("info[$propname]", $value ? $value : $defaultvalue, $propname, array(
			'style'=>'width:'.$width.'px;', 
			'id'=>$propname
		), $t_type);
	}
	
	/**
	 * 多图片字段类型表单组合处理
	 *
	 * @param type $propname
	 * 字段名
	 * @param type $value
	 * 字段内容
	 * @param type $propnameinfo
	 * 字段值
	 * @return string
	 */
	function muimage($value, $propnameinfo){
		extract($propnameinfo); // 字段变量
		$setting=unserialize($setting); // 配置
		if ($setting)
			extract($setting);
		
		return muimageUpload("$propname", $value ? $value : $defaultvalue, $propname, 'info');
	}
	function editor($value, $propnameinfo){
		extract($propnameinfo);
		$setting=unserialize($setting);
		if ($setting)
			extract($setting);
		if ($value=="")
			$value=$defaultvalue;
		if (!empty($maxlength)){
			$maxstr="data-max=$maxlength  ";
		}
		
		/*if (!empty($minlength)){
			$minstr="data-min=$minlength  ";
		}*/
		if ($required==1){
			$allow_empty="data-required='true' ";
		}
		if ($pattern!=""){
			$patternstr="data-type='$pattern' ";
		}
		$this->formValidator=$maxstr.$minstr.$allow_empty.$patternstr."data-trigger='change'";
		return ueditor("info[$propname]", $value, $propname, "'auto'", $height);
	}
	function onoff($value, $propnameinfo){
		$restr="";
		extract($propnameinfo);
		$setting=unserialize($setting);
		if ($setting)
			extract($setting);
		if ($value=="")
			$value=$defaultvalue;
		if (!empty($maxlength)){
			$maxstr="data-max=$maxlength  ";
		}
		
		/*if (!empty($minlength)){
			$minstr="data-min=$minlength  ";
		}*/
		if ($required==1){
			$allow_empty="data-required='true' ";
		}
		if ($pattern!=""){
			$patternstr="data-type='$pattern' ";
		}
		$this->formValidator=$maxstr.$minstr.$allow_empty.$patternstr."data-trigger='change'";
		if (!empty($style)){
			foreach (explode('|', $style) as $k=>$v){
				list ($k, $v)=explode('=', $v);
				$data[$k]=$v;
			}
		}
		if (empty($data))
			$data=array(
				'1'=>'Yes', 
				0=>'No'
			);
		return CHtml::switchButton("info[$propname]", $value, $data);
	}
	function radio($value, $propnameinfo){
		$restr="";
		extract($propnameinfo);
		$setting=unserialize($setting);
		if ($setting)
			extract($setting);
		if ($value=="")
			$value=$defaultvalue;
		if (!empty($maxlength)){
			$maxstr="data-max=$maxlength  ";
		}
		
		/*if (!empty($minlength)){
			$minstr="data-min=$minlength  ";
		}*/
		if ($required==1){
			$allow_empty="data-required='true' ";
		}
		if ($pattern!=""){
			$patternstr="data-type='$pattern' ";
		}
		$this->formValidator=$maxstr.$minstr.$allow_empty.$patternstr."data-trigger='change'";
		$oparr=$this->op2array($options);
		$old=json_decode($select_option, true);
		
		if ($old){
			$oparr=array_merge($oparr, $old);
		}
		return CHtml::groupButton("info[$propname]", $value, CHtml::listData($oparr, 'value', 'title'));
	}
	function checkbox($value, $propnameinfo){
		$restr="";
		extract($propnameinfo);
		$setting=unserialize($setting);
		if ($setting)
			extract($setting);
		if ($value=="")
			$value=$defaultvalue;
		if (!empty($maxlength)){
			$maxstr="data-max=$maxlength  ";
		}
		
		/*if (!empty($minlength)){
			$minstr="data-min=$minlength  ";
		}*/
		if ($required==1){
			$allow_empty="data-required='true' ";
		}
		if ($pattern!=""){
			$patternstr="data-type='$pattern' ";
		}
		$this->formValidator=$maxstr.$minstr.$allow_empty.$patternstr."data-trigger='change'";
		$oparr=$this->op2array($options);
		$old=json_decode($select_option, true);
		if ($old){
			$oparr=array_merge($oparr, $old);
		}
		// 选 值用逗号相隔存取
		return CHtml::multipleListButton("info[$propname]", explode(',', $value), CHtml::listData($oparr, 'value', 'title'));
	}
	function select($value, $propnameinfo){
		$restr="";
		extract($propnameinfo);
		$setting=unserialize($setting);
		if ($setting)
			extract($setting);
		
		if ($value=="")
			$value=$defaultvalue;
		if (!empty($maxlength)){
			$maxstr="data-max=$maxlength  ";
		}
		
		/*if (!empty($minlength)){
			$minstr="data-min=$minlength  ";
		}*/
		if ($required==1){
			$allow_empty="data-required='true' ";
		}
		if ($pattern!=""){
			$patternstr="data-type='$pattern' ";
		}
		$this->formValidator=$maxstr.$minstr.$allow_empty.$patternstr."data-trigger='change'";
		$oparr=$this->op2array($options);
		
		$old=json_decode($select_option, true);
		if ($old){
			$oparr=array_merge($oparr, $old);
		}
		
		$select=explode(',', $value);
		return CHtml::dropDownList("info[$propname]", $select, CHtml::listData($oparr, 'value', 'title'));
	}
	function multiple_select($value, $propnameinfo){
		$restr="";
		extract($propnameinfo);
		$setting=unserialize($setting);
		if ($setting)
			extract($setting);
		if ($value=="")
			$value=$defaultvalue;
		if (!empty($maxlength)){
			$maxstr="data-max=$maxlength  ";
		}
		
		/*if (!empty($minlength)){
			$minstr="data-min=$minlength  ";
		}*/
		if ($required==1){
			$allow_empty="data-required='true' ";
		}
		if ($pattern!=""){
			$patternstr="data-type='$pattern' ";
		}
		$this->formValidator=$maxstr.$minstr.$allow_empty.$patternstr."data-trigger='change'";
		$oparr=$this->op2array($options);
		$old=json_decode($select_option, true);
		if ($old){
			$oparr=array_merge($oparr, $old);
		}
		return CHtml::dropDownList("info[$propname]", explode(',', $value), CHtml::listData($oparr, 'value', 'title'), array(
			'multiple'=>'multiple'
		));
	}
	function select_act(){
	}
	function op2array($opstr){
		$rearr=array();
		$oparr=explode(chr(13), $opstr);
		foreach ($oparr as $key=>$val){
			$subarr=explode('|', $val);
			$rearr[$key]["title"]=str_ireplace(chr(10), "", $subarr[0]);
			$rearr[$key]["value"]=str_ireplace(chr(10), "", $subarr[1]);
		}
		return $rearr;
	}
}
?>