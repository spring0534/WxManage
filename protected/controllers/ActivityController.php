<?php

/**
* ActivityController.php
* ----------------------------------------------
* 版权所有 2014-2015
* ----------------------------------------------
* @date: 2014-12-5
*
*/
class ActivityController extends BaseController{
	function check_ghid(){
		if (! gh()->ghid) {
			$this->error('请先绑定公众号!');
		}
	}
	protected function beforeAction($action){
		if($this->getAction()->id!='adminManage'){
			$this->check_ghid();
		}
		return parent::beforeAction($action);
	}

	public function actionView($id){
		$this->render('view', array(
			'model'=>$this->loadModel($id)
		));
	}
	/**
	 * 获取已开通微应用列表
	 * @date: 2014-12-10
	 * @author : 佚名
	 * @return
	 *
	 */
	function getActList($selectAll=false){
		$criteria=new CDbCriteria();
		// $criteria->select='id,ptype';
		$criteria->with='plugin';
		if(!$selectAll){
			$criteria->addCondition("t.ghid='".gh()->ghid."'"); // 公众号开通的
			$criteria->addCondition('t.status=1'); // 可用
			$criteria->addCondition("t.starttime<'".date('Y-m-d H:i:s'."'")); //
			$criteria->addCondition("t.endtime>'".date('Y-m-d H:i:s'."'")); // 未过期
		}
		// dump(PluginGhUse::model()->findAll($criteria));exit;
		return PluginGhUse::model()->findAll($criteria); // $params is not needed
	}
	public function actionCreate(){
		$model=new Activity();
		if (isset($_POST['Activity'])){
			$model->attributes=$_POST['Activity'];
			$plugin_use=PluginGhUse::model()->findByAttributes(array(
				'ptype'=>$model->type,
				'ghid'=>gh()->ghid
			));

			if ($plugin_use){

				if (empty($plugin_use->status)){
					$this->error('非法操作！1');
				}
				if (strtotime($plugin_use->endtime)<time()){
					$this->error('您开通的微应用已到期，请续费或者重新开通！');
				}
				if ($plugin_use->maxnum<=0){
					$this->error('该类型微应用，您可以创建的活动已经使用完，请联系相关人员。');
				}
			}else{
				$this->error('非法操作！2');
			}
			$model->ghid=gh()->ghid;
			$model->tenant_id=gh()->tenant_id;
			$ptype=$model->type;
			if ($model->save()){
				$plugin_use->maxnum=$plugin_use->maxnum-1;
				if ($plugin_use->save()!==false){
				}
				; // 减去一次使用的次数
				$this->success('添加成功', $this->createUrl('/pluginProp/config', array(
					'aid'=>$aid=$model->attributes['aid'],
					'op'=>'next'
				)));
			}else{
				$this->error('操作失败');
			}
		}
		// $m=$this->getActList();
		// dump($m);exit;
		$this->render('create', array(
			'model'=>$model,
			'plugin'=>$this->getActList()
		));
	}
	public function actionUpdate($id){
		$model=Activity::model()->findByAttributes(array(
			'ghid'=>gh()->ghid,
			'aid'=>$id
		));
		// $this->performAjaxValidation($model);
		if (isset($_POST['Activity'])){
			$akey=$model->akey;
			$model->attributes=$_POST['Activity'];
			$model->akey=$akey;

			if ($model->save())
				$this->success('操作成功');
		}

		$this->render('update', array(
			'model'=>$model,
			'plugin'=>$this->getActList()
		));
	}
	public function actionDelete($id){
		$model=Activity::model()->findByAttributes(array(
			'ghid'=>gh()->ghid,
			'aid'=>$id
		));
		ActivitySettings::model()->deleteAllByAttributes(array('aid'=>$id));
		$this->loadModel($model->aid)->delete();
		// 如果是AJAX请求删除,请取消跳转
		if (!isset($_GET['ajax']))
			$this->success('操作成功');
	}
	public function actionIndex(){
		$dataProvider=new CActiveDataProvider('Activity');
		$this->render('index', array(
			'dataProvider'=>$dataProvider
		));
	}
	public function actionAdmin(){
		$model=new Activity('search');
		$model->unsetAttributes(); // clear any default values
		if (isset($_GET['Activity']))
			$model->attributes=$_GET['Activity'];

		$this->render('admin', array(
			'model'=>$model,

		));
	}

	public function actionAdminManage(){

		$model=new Activity('search_manage');
		$model->unsetAttributes(); // clear any default values
		if (isset($_GET['Activity']))
			$model->attributes=$_GET['Activity'];

		$this->render('admin_manage', array(
			'model'=>$model,

		));
	}
	function json_result($msg='',$code=-1,$result=''){
		echo json_encode(array('result'=>$result,'code'=>$code,'msg'=>$msg));exit;
	}
	public function actionCopy(){
		if (isset($_POST['keyword'])){
			echo json_encode(array(
				'html'=>$this->getGhidlist($_POST['keyword'])
			));
			exit();
		}
		if (isset($_POST['Activity'])){
			$model=new Activity();
			$oaid=intval($_POST['aid']);
			$Activity_model=Activity::model()->findByPk($oaid);
			if(empty($Activity_model))$this->json_result('参数错误！');
			$model->attributes=$Activity_model->attributes;
			$model->title=$_POST['Activity']['title'];
			$model->akey='';
			$ghid=$_POST['Activity']['ghid'];
			switch (user()->groupid){
				case 0:
					$model->ghid=$ghid;
					$gh_info=SysUserGh::model()->findByAttributes(array('ghid'=>$ghid));
					if(!$gh_info)$this->json_result('非法操作！');
					$model->tenant_id=$gh_info->tenant_id;
					break;
				case 1:
					$model->ghid=$ghid;
					$gh_info=SysUserGh::model()->findByAttributes(array('ghid'=>$ghid));
					if(!$gh_info)$this->json_result('非法操作！');
					$model->tenant_id=$gh_info->tenant_id;
					break;
				case 2:
					$model->ghid=$ghid;
					$gh_info=SysUserGh::model()->findByAttributes(array('ghid'=>$ghid));
					if(!$gh_info)$this->json_result('非法操作！');
					$model->tenant_id=$gh_info->tenant_id;
					break;
				case 3:
					$model->ghid=$ghid;
					$gh_info=SysUserGh::model()->findByAttributes(array('ghid'=>$ghid));
					if(!$gh_info)$this->json_result('非法操作！');
					$model->tenant_id=$gh_info->tenant_id;
					break;
				default:
					if($Activity_model->ghid!=gh()->ghid)$this->json_result('非法操作！');
					break;
			}

			$plugin_use=PluginGhUse::model()->findByAttributes(array(
				'ptype'=>$model->type,
				'ghid'=>$model->ghid
			));
			if ($plugin_use){
				if (empty($plugin_use->status)){
					$this->json_result('所选商户已停用该微应用！');
				}
				if (strtotime($plugin_use->endtime)<time()){
					$this->json_result('所选商户开通的微应用已到期，请续费或者重新开通！');
				}
				if ($plugin_use->maxnum<=0){
					$this->json_result('所选商户的该类型微应用可创建的活动次数已经使用完，请联系相关人员。');
				}
			}else{
				$this->json_result('所选商户尚未开通该微应用！');
			}
			if ($model->save()){
				$plugin_use->maxnum=$plugin_use->maxnum-1;
				$plugin_use->save();
				$newAid=$model->aid;
				$ActivityConfig_model=ActivitySettings::model()->findAllByAttributes(array('aid'=>$oaid));
				foreach ($ActivityConfig_model as $k=>$v){
					$as=new ActivitySettings();
					$as->aid=$newAid;
					$as->propname=$v->propname;
					$as->propvalue=$v->propvalue;
					$as->email=$v->email;
					$as->tm=$v->tm;
					$as->ltm=$v->ltm;

					$as->save();
				}
				$this->json_result('操作成功',$model->ghid==gh()->ghid?1:2);
			}else{
				$this->json_result('操作失败');
			}
		}
		$id=intval($_GET['id']);
		if (empty($id))
			$this->error('参数错误！');
		$model=Activity::model()->findByPk($id);
		$this->render('copy', array(
			'model'=>$model,
			'ghidlist'=>$this->getGhidlist()
		));
	}
	function getGhidlist($keyword=''){
		/*$criteria=new CDbCriteria();
		$criteria->select='id,ghid,name';
		$criteria->compare(ghid, $keyword, true, 'or');
		$criteria->compare(name, $keyword, true, 'or');
		$criteria->addCondition('status=1'); // 可用
		$criteria->limit=30;
		// dump($criteria);
		$data=SysUserGh::model()->findAll($criteria);
		*/
		$criteria=new CDbCriteria();
		$criteria->select='id,ghid,username,nickname,company';
		$criteria->compare('nickname', $keyword, true, 'or');
		$criteria->compare('company', $keyword, true, 'or');
		$criteria->addCondition('ghid is not null and ghid!=""'); // 可用
		$criteria->addCondition('status=1'); // 可用
		$criteria->addCondition('groupid in(3,4)'); // 可用
		$criteria->limit=30;

		switch (user()->groupid){
			case 3://代理商
				$criteria->addCondition('FIND_IN_SET('.user()->id.',pids) '); // 可用
				break;
			case 0 and 1 and 2:

				break;
			default:
				$criteria->addCondition('id=0');
				break;
		}
		// dump($criteria);
		$data=SysUser::model()->findAll($criteria);
		foreach ($data as $k=>$v){
			$list.='<li class="active-result" data-ghid="'.$v['ghid'].'"  data-option-array-index="'.$v['id'].'">'.$v['nickname']." (".$v['ghid'].")".'</li>';
		}
		return $list;
	}
	public function loadModel($id){
		$model=Activity::model()->findByPk($id);
		if ($model===null)
			throw new CHttpException(404, 'The requested page does not exist.');
		return $model;
	}

	/**
	 * AJAX验证
	 * @param Activity $model
	 * 模型验证
	 */
	protected function performAjaxValidation($model){
		if (isset($_POST['ajax'])&&$_POST['ajax']==='activity-form'){
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
	/**
	 * 更新活动配置
	 * @date: 2014-12-30
	 * @author : 佚名
	 */
	function actionUpdateCache($aid){
		Yii::app()->cache->set('ActivityConfig'.$aid, null);
		Yii::app()->cache->set('ActivityConfig_deal'.$aid, null);//getActivityConfig($deal_with=FALSE) $deal_with 为true时 的活动参数缓存
		$this->success('更新缓存成功！');
	}
	/**
	 * 资源包删除
	 * @date: 2014-12-31
	 * @author : 佚名
	 * @param unknown $aid
	 */
	function actionZipDel($aid){
		$activity=Activity::model()->findByPk($aid);
		if (!$activity||$activity->ghid!=gh()->ghid){
			$this->json_error('活动不存在！');
		}
		$pu=Plugin::model()->findByAttributes(array(
			'ptype'=>$activity->type
		));
		if (!$pu){
			$this->json_error('微应用不存在！');
		}
		@list ($appdir, $controller, $action)=explode('.', $pu->processor_class);
		$app_path=ROOT_PATH.'/microapp/'.$appdir.'/resources/';
		delDir($app_path.$activity->aid);
		$this->success('删除成功！');
	}
	/**
	 * 导出资源
	 * @date: 2014-12-31
	 * @author : 佚名
	 * @param unknown $aid
	 */
	function actionExportZip($aid){
		$activity=Activity::model()->findByPk($aid);
		if (!$activity||$activity->ghid!=gh()->ghid){
			$this->error('活动不存在！');
		}
		$res_name='res_'.$activity->type.'_'.$activity->aid.'.zip';
		$pu=Plugin::model()->findByAttributes(array(
			'ptype'=>$activity->type
		));
		if (!$pu){
			$this->error('微应用不存在！');
		}
		@list ($appdir, $controller, $action)=explode('.', $pu->processor_class);
		$app_path=ROOT_PATH.'/microapp/'.$appdir;
		$theme='default'; // 默认使用default
		if ($theme_name=Yii::app()->db->createCommand()
			->select('wx_theme')
			->from('plugin_theme')
			->where('id='.$activity->themeid)
			->queryScalar()){
			$theme=$theme_name; // 使用配置主题
		}
		$theme_path=$app_path.'/themes/'.$theme;
		foreach (scandir($theme_path) as $p){
			// 排除一些目录
			if ($p!='.'&&$p!='..'&&$p!='views'&&$p!=$res_name&&$p!='resource'&&$p!='static'){
				$theme_paths[]=$theme_path.'/'.$p;
			}
		}
		$path_list=implode(',', $theme_paths);
		$zip_file=$theme_path.'/'.$res_name;
		if (file_exists($zip_file)){
			@unlink($zip_file);
		}
		Yii::$enableIncludePath=false;
		Yii::import('ext.pclzip.pclzip', 1);
		$archive=new PclZip($zip_file);
		$v_list=$archive->create($path_list, PCLZIP_OPT_REMOVE_PATH, $theme_path);

		if ($v_list==0){
			die("Error : ".$archive->errorInfo(true));
		}
		Yii::$enableIncludePath=true;
		if(!file_exists($zip_file)){
			$this->error('生成压缩包失败，请稍后再试！');
		}
		$file = fopen($zip_file,"r"); // 打开文件
		// 输入文件标签
		Header("Content-type: application/octet-stream");
		Header("Accept-Ranges: bytes");
		Header("Accept-Length: ".filesize($zip_file));
		Header("Content-Disposition: attachment; filename=" . $res_name);
		// 输出文件内容
		echo fread($file,filesize($zip_file));
		fclose($file);
		@unlink($zip_file);
		exit();
		/*
		header("HTTP/1.1 303 See Other");
		header('Location: '.WEB_URL.'/microapp/'.$appdir.'/themes/'.$theme.'/'.$res_name);*/
	}
	function fileext($filename) {
		return strtolower ( trim ( substr ( strrchr ( $filename, '.' ), 1, 10 ) ) );
	}
	//目录列表
	function dir_list($path, $exts = '', $list = array()) {
		$path = dir_path ( $path );
		$files = glob ( $path . '*' );
		foreach ( $files as $v ) {
			$fileext = $this->fileext ( $v );
			if (! $exts || preg_match ( "/\.($exts)/i", $v )) {
				$list [] = $v;
			}
		}
		return $list;
	}
	function dir_path($path) {
		$path = str_replace ( '\\', '/', $path );
		if (substr ( $path, - 1 ) != '/')
			$path = $path . '/';
		return $path;
	}
	function actionDirlist() {

		$aid=intval($_GET['aid']);
		$activity=Activity::model()->findByPk($aid);
		if (!$activity||$activity->ghid!=gh()->ghid){
			$this->json_error('活动不存在！');
		}
		$pu=Plugin::model()->findByAttributes(array(
			'ptype'=>$activity->type
		));
		if (!$pu){
			$this->json_error('微应用不存在！');
		}
		@list ($appdir, $controller, $action)=explode('.', $pu->processor_class);
		$app_path=$this->dir_path(ROOT_PATH.'/microapp/'.$appdir.'/resources/');
		$dir = $_GET['dir'];
		if(strpos($dir, '.')!==false){
			$this->error('参数错误！');
		}
		$path=$app_path.$aid.'/'.$dir;
		if(!is_dir($path)){
			$this->error('请先上传资源包！');
		}
		$dir_name = array ();
		$tmp_array = $this->dir_list ( $path );//文件及目录列表
		//dump($tmp_array);
		foreach ( $tmp_array as $k => $v ) {
			$dir_name [$k] ['pname'] = str_replace ( $app_path.$aid.'/', '', $v );
			$dir_name [$k] ['filename'] = urlencode ( stripslashes ( str_replace ( '/', '', str_replace ( $path, '', $v ) ) ) );
			$dir_name [$k] ['isdir'] = is_dir ( $v ) ? 1 : 0;
		}
		//dump($dir_name);//exit;
		//判断是否显示上层目录
		$tmp_backstr = strrchr ( $dir, '/' );
		$return_folder='';
		//dump($tmp_backstr);
		if ($tmp_backstr !== false) {
			$return_folder= str_replace ( $tmp_backstr, '', $dir ) ;
		}

		//dump($return_folder);
		$this->render('dirlist',array(
			'app_path'=>'/microapp/'.$appdir.'/resources/',
			'return_folder'=>$return_folder,
			'dir'=> $dir,
			'cur_position'=> str_replace ( '/', '\\', str_replace ($app_path, '', $path ) ),
			'home'=>$aid.'\\',
			'filelist'=> $dir_name,
			'aid'=>$aid
		));
	}
}
