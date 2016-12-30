<?php

/**微应用属性
* PluginPropController.php
* ----------------------------------------------
* 版权所有 2014-2015
* ----------------------------------------------
* @date: 2014-12-4
*
*/
class PluginPropController extends BaseController{
	public function actionView($id){
		$this->render('view', array(
			'model'=>$this->loadModel($id)
		));
	}
	protected $regxarr=array(
		"email"=> "电子邮件.",
		"url"=> "url",
		"urlstrict"=> "严格url",
		"number"=> "数字",
		"digits"=> "digits",
		"dateIso"=> "日期,形如1988-04-08",
		"alphanum"=> "阿拉伯字母",
		"phone"=> "电话号码.",
		"intege"=> "整数",
		"intege1"=> "正整数",
		"intege2"=> "负整数",
		"num1"=> "正整数 + 0",
		"num2"=> "负整数 + 0",
		"decmal"=> "浮点数",
		"decmal1"=> "正浮点数",
		"decmal2"=> "负浮点数",
		"decmal3"=> "浮点数",
		"decmal4"=> "正浮点数 + 0",
		"decmal5"=> "负浮点数 + 0",
		"price"=> "价格型式 可以为正浮点数或正整数",
		"color"=> "颜色",
		"chinese"=> "中文",
		"ascii"=> "ACSII字符",
		"zipcode"=> "邮编",
		"username"=> "英文字母",
		"uname"=> "数字、26个英文字母或者下划线",
		"exuname"=> "中文字符，数字，字母",
		"letter"=>"字母组成的字符串",
		"letter_u"=> "大写字母组成的字符串",
		"letter_l"=> "小写字母组成的字符串",
		"idcard"=> "身份证"

	);

	/**
	 * 重组数组
	 * ["intege"] => array(2) {
	 * ["name"] => string(6) "整数"
	 * ["value"] => string(6) "intege"
	 * }
	 * @方法名：chagearray
	 *
	 * @author 佚名
	 * @2014-4-21上午12:04:34
	 */
	public function chagearray(){
		$t=array();
		foreach ($this->regxarr as $key=>$val){
			$t[$key]["name"]=$val;
			$t[$key]["value"]=$key;
		}
		return $t;
	}

	// 检查字段是否存在
	/*
	 * public function checkajax_field(){
	 * $field=isset($_GET["field"]) ? $_GET["field"] : "";
	 * $modid=isset($_GET["modid"]) ? $_GET["modid"] : "";
	 * if ($field==""){
	 * $this->error('error');
	 * }
	 * $conn=M("con_field");
	 * $c=$conn->where("field='$field' and modelid=$modid")->count("id");
	 * if ($c>0){
	 * echo ("0");
	 * }else{
	 * echo ("1");
	 * }
	 * }
	 */

	/**
	 * 控件子属性，添加字段时用到，也就是控件表单
	 * @方法名：showsubarr
	 *
	 * @author 佚名
	 * @2013-11-21下午03:16:23
	 */
	public function actionShowsubarr(){
		$type=isset($_GET["type"]) ? $_GET["type"] : "";
		$this->printsub($type);
	}
	/**
	 * 软件控件属性
	 * @方法名：showsubstr
	 *
	 * @param 控件类型 $type
	 * @param 控件配置 $setting
	 * @author 佚名
	 * @2013-11-21下午03:07:11
	 */
	public function showsubstr($type, $setting){
		$bodystr=$this->renderFile(Yii::getPathOfAlias('application').'/field/'.$type."/".$type.".php", array(
			"setting"=>unserialize($setting)
		), true);
		return $bodystr;
	}
	// 输出出子框架
	/**
	 * 控件表单，从控件模型中读取生成
	 * @方法名：printsub
	 *
	 * @param unknown_type $type
	 * @author 佚名
	 * @2014-4-25上午09:58:16
	 */
	public function printsub($type){
		switch ($type){
			case "text":
				$subarr=array(
					"field_basic_table"=>1,
					"field_minlength"=>1,
					"field_maxlength"=>""
				);
				$setting=array(
					"size"=>50,
					"ispassword"=>"0"
				);

				break;
			case "textarea":
				$subarr=array(
					"field_basic_table"=>0,
					"field_minlength"=>0,
					"field_maxlength"=>""
				);
				$setting=array(
					"width"=>"100%",
					"height"=>"46",
					"enablehtml"=>"0"
				);

				break;
			case "editor":
				$subarr=array(
					"field_basic_table"=>0,
					"field_minlength"=>0,
					"field_maxlength"=>""
				);
				$setting=array(
					"height"=>"200"
				);

				break;
			case "number":
				$subarr=array(
					"field_basic_table"=>1,
					"field_minlength"=>0,
					"field_maxlength"=>""
				);
				$setting=array(
					"minnumber"=>"0",
					"decimaldigits"=>"0"
				);

				break;
			// 图片模式
			case "image":
				$subarr=array(
					"field_basic_table"=>0,
					"field_minlength"=>0,
					"field_maxlength"=>""
				);
				$setting=array(
					"upload_allowext"=>"gif|jpg|jpeg|png|bmp",
					"size_limit"=>"10"
				);

				break;
			case "datetime":
				$subarr=array(
					"field_basic_table"=>0,
					"field_minlength"=>0,
					"field_maxlength"=>""
				);
				$setting=array(); // "$timeformat"=>"date"

				break;
			case "onoff":
				$subarr=array(
					"field_basic_table"=>1,
					"field_minlength"=>1,
					"field_maxlength"=>""
				);
				$setting=array(
					"size"=>50
				);

				break;
			case "radio" or 'select' or 'multiple_select' or 'checkbox':
				$subarr=array(
					"field_basic_table"=>0,
					"field_minlength"=>0,
					"field_maxlength"=>""
				);
				$setting=array(
					"options"=>"选项名称1|选项值1",
					"boxtype"=>"radio",
					"width"=>"100",
					'height'=>'80',
					"outputtype"=>"1"
				);

				break;

			case "muimage":
				$subarr=array(
					"field_basic_table"=>0,
					"field_minlength"=>0,
					"field_maxlength"=>""
				);
				$setting=array(
					"upload_allowext"=>"gif|jpg|jpeg|png|bmp",
					"upload_limit"=>"10",
					"size_limit"=>"10"
				);

				break;
		}
		$bodystr=$this->renderFile(Yii::getPathOfAlias('application').'/field/'.$type."/".$type.".php", array(
			"setting"=>$setting
		), true);

		$subarr["setting"]=$bodystr;
		echo (json_encode($subarr));
		exit();
	}
	protected $diyfield=array(
		array(
			"name"=>'单行文本',
			"value"=>'text'
		),
		array(
			"name"=>'多行文本',
			"value"=>'textarea'
		),
		array(
			"name"=>'编辑器',
			"value"=>'editor'
		),
		array(
			"name"=>'开关按钮',
			"value"=>'onoff'
		),
		array(
			"name"=>'单选按钮',
			"value"=>'radio'
		),
		array(
			"name"=>'下拉框',
			"value"=>'select'
		),
		array(
			"name"=>'多选下拉框',
			"value"=>'multiple_select'
		),
		array(
			"name"=>'多选按钮组',
			"value"=>'checkbox'
		),
		array(
			"name"=>'图片',
			"value"=>'image'
		),
		array(
			"name"=>'多图片',
			"value"=>'muimage'
		),
		array(
			"name"=>'颜色',
			"value"=>'color'
		),
		array(
			"name"=>'数字',
			"value"=>'number'
		),
		array(
			"name"=>'日期和时间',
			"value"=>'datetime'
		),
		array(
			"name"=>'音乐',
			"value"=>'music'
		),
		/*array(
			"name"=>'活动列表',
			"value"=>'select_act'
		)*/
	);
	/**
	 * 删除微应用产生的所有活动的缓存
	 * @date: 2015-8-20
	 *
	 */
	function actionUpdateActCache(){
		set_time_limit(60);
		if(empty($_GET['ptype']))exit('parmas error');
		$model=Activity::model()->findAllByAttributes(array('type'=>$_GET['ptype']));
		if($model){
			foreach ($model as $k=>$v ){
				Yii::app()->cache->set('ActivityConfig'.$v['aid'], null);
				Yii::app()->cache->set('ActivityConfig_deal'.$v['aid'], null);//getActivityConfig($deal_with=FALSE) $deal_with 为true时 的活动参数缓存

			}
		}
		$this->success('操作成功');
	}
	public function actionCreate(){
		if (isset($_POST['PluginProp'])){
			$model=new PluginProp();
			$model->attributes=$_POST['PluginProp'];
			if (empty($model->propname))
				$this->error('参数错误'.$model->propname);
			if (PluginProp::model()->findByAttributes(array(
				'propname'=>$model->propname,
				'ptype'=>$model->ptype
			))){
				$this->error('已经存在属性名'.$model->propname.',属性名必须是唯一 的！');
			}
			$model->setting=serialize($_POST['setting']);
			$model->ctm=date('Y-m-d H:i:s');
			$model->pgroup=intval($model->pgroup);
			if ($model->save()){

				$this->success('添加成功',U('&admin',array('ptype'=>$model->ptype)));
			}else{

				$this->error('添加失败');
			}
		}
		if (empty($_GET['ptype']))
			$this->error('参数错误！');
		$this->render('create', array(
			"regxarr"=>$this->chagearray(),
			'diyfield'=>$this->diyfield,
			'ptype'=>$_GET['ptype']
		));
	}
	public function actionUpdate($id){
		$model=$this->loadModel($id);
		if (isset($_POST['PluginProp'])){
			$model->attributes=$_POST['PluginProp'];
			if($model->proptype=='muimage'){
				$_POST['setting']['defaultvalue']=serialize($_POST['uploadImages']['defaultvalue']);
			}
			$model->setting=serialize($_POST['setting']);
			$model->utm=date('Y-m-d H:i:s');
			$model->pgroup=intval($model->pgroup);
			if ($model->save())
				$this->success('操作成功',U('&admin',array('ptype'=>$model->ptype)));
		}
		$this->render('update', array(
			'model'=>$model,
			'diyfield'=>$this->diyfield,
			"regxarr"=>$this->chagearray(),
			"setting"=>$this->showsubstr($model->proptype, $model->setting),
			'plugin'=>Plugin::model()->findByAttributes(array(
				'ptype'=>$model->ptype
			))
		));
	}
	public function actionDelete($id){
		$this->loadModel($id)->delete();
		// 如果是AJAX请求删除,请取消跳转
		if (!isset($_GET['ajax']))
			$this->success('操作成功');
	}
	public function actionIndex(){
		$dataProvider=new CActiveDataProvider('PluginProp');
		$this->render('index', array(
			'dataProvider'=>$dataProvider
		));
	}
	public function actionAdmin(){
		if(empty($_GET['ptype']))exit('parmas error');
		$model=new PluginProp('search');
		$model->unsetAttributes(); // clear any default values
		if (isset($_GET['PluginProp']))
			$model->attributes=$_GET['PluginProp'];

		$this->render('admin', array(
			'model'=>$model
		));
	}
	function actionListorder(){
		$ids=$_POST['data'];
		$ptype=$_GET['ptype'];
		dump($ids);
		foreach ($ids as $key=>$r){
			$model=PluginProp::model()->findByPk($r[0]);
			if ($model){
				$model->seq=intval($r[1]);
				$model->save();
				// dump($model->getScenario());
			}
			unset($model);
		}
	}
	public function loadModel($id){
		$model=PluginProp::model()->findByPk($id);
		if ($model===null)
			throw new CHttpException(404, 'The requested page does not exist.');
		return $model;
	}

	/**
	 * AJAX验证
	 *
	 * @param PluginProp $model
	 * 模型验证
	 */
	protected function performAjaxValidation($model){
		if (isset($_POST['ajax'])&&$_POST['ajax']==='plugin-prop-form'){
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
	function actionConfigSave(){
		$aid=intval($_POST['aid']);
		if (empty($aid)||empty($_POST['ptype']))
			$this->error('参数问题');
		if ($a=Activity::model()->findByPk($aid)){
			$data=$_POST['info'];
			$themeid=intval($_POST['themeid']);
			$a->themeid=$themeid;
			$a->save();//主题模板修改
			$images=$_POST['uploadImages'];
			foreach ((array)$images as $k=>$v ){
				$data[$k]=serialize($v);
			}
			foreach ($data as $key=>$value){
				$model_set=ActivitySettings::model()->findByAttributes(array(
					'propname'=>$key,
					'aid'=>$aid
				));
				if ($model_set){
					$model_set->propname=$key;
					$model_set->propvalue=$value;
					//$model->screenshots=serialize($_POST['uploadImages']['screenshots']);
					$model_set->save();
				}else{
					$model_setting=new ActivitySettings();
					$model_setting->aid=$aid;
					$model_setting->propname=$key;
					$model_setting->propvalue=$value;
					$model_setting->tm=date('Y-m-d H:i:s');
					$model_setting->save();
				}
			}
			Yii::app ()->cache->set('ActivityConfig'.$aid,null);
			Yii::app()->cache->set('ActivityConfig_deal'.$aid, null);//getActivityConfig($deal_with=FALSE) $deal_with 为true时 的活动参数缓存
			$this->success('操作成功',$this->createUrl('/activity/admin'));
		}
	}
	function dir_path($path) {
		$path = str_replace ( '\\', '/', $path );
		if (substr ( $path, - 1 ) != '/')
			$path = $path . '/';
		return $path;
	}
	function actionConfig($aid){
		$act=Activity::model()->findByPk($aid);
		if (empty($act))
			$this->error('error');
		$act_config=ActivitySettings::model()->findAllByAttributes(array(
			'aid'=>$aid
		));

		$pu=Plugin::model()->findByAttributes(array(
			'ptype'=>$act->type
		));
		if (!$pu){
			$this->json_error('微应用不存在！');
		}
		@list ($appdir, $controller, $action)=explode('.', $pu->processor_class);
		$app_path=$this->dir_path(ROOT_PATH.'/microapp/'.$appdir.'/resources/');
		$path=$app_path.$aid;
		$isres=0;
		if(is_dir($path)){
			$isres=1;
		}
		$model=PluginProp::model()->findAll(array(
			'condition'=>'ptype=:ptype',
			'params'=>array(
				':ptype'=>$act->type
			),
			'order'=>'seq'
		));
		foreach ($act_config as $val){
			$config[$val->propname]=$val->propvalue;
		}
		foreach ($model as $v){
			if(in_array($v->propname, array('customWxCss','statJs'))||$v->isshow!=1){
				if( in_array(user()->groupid, array(4,5))){
					//客户不可见
				}else{
					$data[$v->pgroup][$v->propname]=$v->attributes;
					if (isset($config[$v->propname])){
						$data[$v->pgroup][$v->propname]['defaultvalue']=$config[$v->propname];
						$tmp=unserialize($data[$v->pgroup][$v->propname]['setting']);
						$tmp['defaultvalue']=$config[$v->propname];
						$data[$v->pgroup][$v->propname]['setting']=serialize($tmp);
						unset($tmp);
					}
					$data[$v->pgroup][$v->propname]['proptitle']=$data[$v->pgroup][$v->propname]['proptitle'].'<font color=red>(客户不可见)</font>';
				}
			}else{
				$data[$v->pgroup][$v->propname]=$v->attributes;
				if (isset($config[$v->propname])){
					$data[$v->pgroup][$v->propname]['defaultvalue']=$config[$v->propname];
					$tmp=unserialize($data[$v->pgroup][$v->propname]['setting']);
					$tmp['defaultvalue']=$config[$v->propname];
					$data[$v->pgroup][$v->propname]['setting']=serialize($tmp);
					unset($tmp);
				}
			}
		}
		foreach ($data as $kkk=>$vvv){
			$g[$kkk]=$this->form_print($vvv);
		}
		$tpl='config';
		if(!empty($_GET['op']))$tpl='configNext';
		$this->render($tpl, array(
			'models'=>$model,
			"list"=>$g,
			'aid'=>$aid,
			'act'=>$act,
			'pgroup'=>PluginPropGroup::model()->findAllByAttributes(array('ptype'=>$act->type)),
			'isres'=>$isres
		));
	}
	/**
	 * 控件生成
	 * @date: 2014-12-5
	 *
	 * @author : 佚名
	 * @param
	 * $list
	 * @return multitype:multitype: string
	 */
	public function form_print($list){
		$listarr=array();
		$regxstr="";
		Yii::import("ext.Pluginform");
		$forms=new Pluginform();

		// dump($list);
		foreach ((array)$list as $keys=>$vals){
			$listarr[$keys]["name"]=$vals["proptitle"];
			$fun=$vals["proptype"];
			$listarr[$keys]["input"]=$forms->$fun($vals['defaultvalue'], $vals);
			$listarr[$keys]["memo"]=$vals['memo'];
		}
		return array(
			$forms->formValidator,
			$listarr
		);
	}
}
