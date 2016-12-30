<?php

class SysUsergroupController extends BaseController
{
	

	/**
	 * @return array action filters
	 */
	/*public function filters()
	{
		return array(
			'accessControl', // perform access control for CRUD operations
			'postOnly + delete', // we only allow deletion via POST request
		);
	}
	*/
	

	
	public function actionView($id)
	{
		$this->render('view',array(
			'model'=>$this->loadModel($id),
		));
	}

	
	public function actionCreate()
	{
		$model=new SysUsergroup;

		// $this->performAjaxValidation($model);

		if(isset($_POST['SysUsergroup']))
		{
			$model->attributes=$_POST['SysUsergroup'];
			if($model->save())
				$this->success('添加成功');
		}

		$this->render('create',array(
			'model'=>$model,
		));
	}


	public function actionUpdate($id)
	{
		$model=$this->loadModel($id);
		$flg=$model->flagarr;
		// $this->performAjaxValidation($model);
		if(isset($_POST['SysUsergroup']))
		{
			$model->attributes=$_POST['SysUsergroup'];
			$model->flagarr=$flg;
			if($model->save())
				$this->success('操作成功');
		}

		$this->render('update',array(
			'model'=>$model,
		));
	}


	/*public function actionDelete($id)
	{
		$this->loadModel($id)->delete();
		// 如果是AJAX请求删除,请取消跳转
		if(!isset($_GET['ajax']))
			$this->success('操作成功');
	}
*/
	
	/*public function actionIndex()
	{
		$dataProvider=new CActiveDataProvider('SysUsergroup');
		$this->render('index',array(
			'dataProvider'=>$dataProvider,
		));
	}
*/
	
	public function actionAdmin()
	{
		$model=new SysUsergroup('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['SysUsergroup']))
			$model->attributes=$_GET['SysUsergroup'];

		$this->render('admin',array(
			'model'=>$model,
		));
	}

	
	public function loadModel($id)
	{
		$model=SysUsergroup::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * AJAX验证
	 * @param SysUsergroup $model 模型验证
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='sys-usergroup-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
	public function actionEditflag($gid) {
		$flagarr = SysUsergroup::model()->findByPk($gid);
		$flagarr = unserialize($flagarr->flagarr);
	
		$result =Yii::app()->db->createCommand()
		->select('*')
		->from('sys_user_menu')
		->order('listorder')
		->queryAll();
		$menu = new Tree();
		$menu->icon = array(
			'│ ',
			'├─ ',
			'└─ '
		);
		$menu->nbsp = '&nbsp;&nbsp;&nbsp;';
		foreach ($result as $n => $t) {
			$result[$n]['checked'] = in_array($t['id'], (array)$flagarr) ? "checked" : "";
			$result[$n]['level'] = get_level($t['id'], $result);
			$result[$n]['parentid_node'] = ($t['pid']) ? ' class="child-of-node-' . $t['pid'] . '"' : '';
		}
		$str = "<tr  id='node-\$id' \$parentid_node>
							<td style='padding-left:30px;'>\$spacer<label><input class='cbox' style='width:20px' type='checkbox' name='menuid[]' value='\$id' level='\$level' \$checked onclick='javascript:checknode(this);'> \$title</label></td>
						</tr>";
		$menu->init($result);
		$categorys = $menu->get_tree(0, $str);
		$this->render('flag', array(
			'categorys' => $categorys,
			"gid" => $gid
		));
	}
	public function actionSaveflag() {
		$gid = $_POST["gid"];
		$flag = isset($_POST["menuid"]) ? $_POST["menuid"] : "";
		if (! is_array($flag)) {
			$this->error("请选择权限！");
		}
		$model=SysUsergroup::model()->findByPk($gid);
		$model->flagarr=serialize($flag);
		if ($model->save() !== false) {
			$this->success("权限保存成功！");
		} else {
			$this->error("权限保存失败！");
		}
	}
}
