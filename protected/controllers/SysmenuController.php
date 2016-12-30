<?php

/**
* 菜单管理
* SysmenuController.php
* ----------------------------------------------
* 版权所有 2014-2015 
* ----------------------------------------------
* @date: 2014-11-14
* 
*/
class SysmenuController extends BaseController{
	public function actionIndex(){
		$array=Yii::app()->db->createCommand()
			->select('*')
			->from('sys_user_menu')
			->order('listorder')
			->queryAll();
		$tree=new Tree();
		$tree->icon=array(
			'&nbsp;&nbsp;&nbsp;│ ', 
			'&nbsp;&nbsp;&nbsp;├─ ', 
			'&nbsp;&nbsp;&nbsp;└─ '
		);
		$tree->nbsp='&nbsp;&nbsp;&nbsp;';
		$tr="
	<tr \$fonts>
		<td><input name='listorders[\$id]' type='text' size='3' value='\$listorder' style='width:30px'></td>
		<td >\$id</td>
		<td align='left'>\$spacer\$title</td>
		<td >\$modelname</td>
		<td >\$alert</td>
		<td >\$show</td>
		<td>
			<div class='dropdown'>
				<a href='javascript:;' title='' class='btn medium bg-blue' data-toggle='dropdown'>
					<span class='button-content'>
					<i class='glyph-icon font-size-11 icon-cog'></i>
					<i class='glyph-icon font-size-11 icon-chevron-down'></i>
					</span>
				</a>
				<ul class='dropdown-menu float-right'>
					\$addsub
					\$edit
					\$move		
					<li class='divider'></li>
					\$addrules
					<li class='divider'></li>
					<li>
						<a href='".$this->createUrl('sysmenu/del/id')."/\$id' onClick='javascript:return confirm('是否要删除');' class='font-red' title=''>
						<i class='glyph-icon icon-remove mrg5R'></i>
						删除
						</a>
					</li>
				</ul>
			</div>
		</td>
	</tr>";
		foreach ($array as $key=>$val){
			$array[$key]["level"]=get_level($val["id"], $array);
			$array[$key]["edit"]="<li><a href='".$this->createUrl('sysmenu/menuedit/id/'.$val["id"])."' title=''><i class='glyph-icon icon-edit mrg5R'></i>修改</a>	</li>";
			if ($array[$key]["level"]!=3){
				if ($array[$key]["level"]!=0){
					$array[$key]["move"]="<li><a href='".$this->createUrl('sysmenu/menumove/id/'.$val["id"])."' title=''><i class='glyph-icon icon-download mrg5R'></i>移动</a>	</li>";
				}
				if ($array[$key]["level"]!=3){
					$array[$key]["addsub"]="<li><a href='".$this->createUrl('sysmenu/menuadd/id/'.$val["id"])."' title=''><i class='glyph-icon icon-plus mrg5R'></i>添加子菜单</a>	</li>";
				}
			}
			if ($array[$key]["level"]==2){
				$array[$key]["addrules"]="<li><a href='".$this->createUrl('sysmenu/rulesadd/id/'.$val["id"])."' title=''><i class='glyph-icon icon-cog mrg5R'></i>添加权限操作</a>	</li>";
			}
			if ($array[$key]["level"]==0){
				$array[$key]["fonts"]="style='color:#ff0000;'";
			}
			if ($array[$key]["level"]==1){
				$array[$key]["fonts"]="style='color:#0000ff'";
			}
			if ($array[$key]["level"]!=3){
				if ($val["status"]==1){
					$array[$key]["alert"]="<font color='#0000FF'>启用</font>";
				}else{
					$array[$key]["alert"]="<font color='#FF0000'>禁用</font>";
				}
			}
			if ($val["isshow"]==1){
				$array[$key]["show"]="<font color='#0000FF'>显示</font>";
			}else{
				$array[$key]["show"]="<font color='#FF0000'>隐藏</font>";
			}
		}
		
		$tree->init($array);
		$menulist=$tree->get_tree(0, $tr);
		$this->render('index', array(
			"menulist"=>$menulist
		));
	}
	public function actionMenuadd(){
		$menuid=isset($_GET["id"]) ? trim($_GET["id"]) : "";
		if ($menuid==""){
			$array=Yii::app()->db->createCommand()
				->select('id,pid,title,modelname,listorder')
				->from('sys_user_menu')
				->order('listorder')
				->queryAll();
			$tree=new Tree();
			$tree->init($array);
			$str="<option value='\$id'>\$spacer \$title</option>";
			$select_categorys=$tree->get_tree(0, $str);
			$this->render('menuadd', array(
				"select_categorys"=>$select_categorys
			));
		}else{
			$mt=Yii::app()->db->createCommand()
				->select('title')
				->from('sys_user_menu')
				->where('id='.$menuid)
				->queryScalar();
			$this->render('menuadd', array(
				"menuid"=>$menuid, 
				"menuname"=>$mt
			));
		}
	}
	public function actionInsert(){
		$info=isset($_POST["info"]) ? $_POST["info"] : "";
		if (!is_array($info)){
			$this->error("参数错误！", $_SERVER['HTTP_REFERER']);
		}
		$model=new SysUserMenu();
		$model->attributes=$info;
		if ($model->save()){
			
			$this->success("增加成功！", U('&index'));
		}else{
			$this->error("增加失败！", $_SERVER['HTTP_REFERER']);
		}
	}
	public function actionUpdate(){
		$model=SysUserMenu::model()->findByPk($_POST['info']['id']);
		$info=isset($_POST["info"]) ? $_POST["info"] : "";
		if (!is_array($info)){
			$this->error("没有可以保存的数据！", $_SERVER['HTTP_REFERER']);
		}
		$model->attributes=$info;
		$re=$model->save();
		if ($re!==FALSE){ // 如果没有任何更新，则不提示为失败
			$this->success("编辑成功！",U('&index'));
		}else{
			$this->error("编辑失败！", $_SERVER['HTTP_REFERER']);
		}
	}
	public function actionListorder(){
		$ids=$_POST['listorders'];
		if (!is_array($ids)){
			$this->error("列表为空，不能操作！", $_SERVER['HTTP_REFERER']);
		}
		foreach ($ids as $key=>$r){
			$model=SysUserMenu::model()->findByPk($key);
			$model->listorder=$r;
			$model->save();
		}
		$this->success("排序成功！");
	}
	public function actionMenuedit(){
		$menuid=isset($_GET["id"]) ? trim($_GET["id"]) : "";
		$mlist=Yii::app()->db->createCommand()
			->select('*')
			->from('sys_user_menu')
			->where('id='.$menuid)
			->queryRow();
		$this->render('menuedit', array(
			'mlist'=>$mlist
		));
	}
	public function menumove(){
		$menuid=isset($_GET["id"]) ? trim($_GET["id"]) : "";
		$menu=M(strtolower(MODULE_NAME));
		$mlist=$menu->getById($menuid);
		$conn=M(strtolower(MODULE_NAME));
		$array=$conn->order("listorder")->select();
		foreach ($array as $key=>$val){
			$array[$key]["level"]=get_level($val["id"], $array);
		}
		
		$tree=new tree();
		$tree->init($array);
		$toparr=$tree->get_parent_list($menuid);
		$this->assign("mlist", $mlist);
		$this->assign("toparr", $toparr);
		$this->display();
	}
	public function move(){
		$menuid=isset($_POST["id"]) ? $_POST["id"] : "";
		$topid=isset($_POST["topid"]) ? $_POST["topid"] : "";
		if (!is_numeric($menuid)||!is_numeric($topid)){
			$this->error();
		}
		$conn=M(strtolower(MODULE_NAME));
		$info=array(
			"id"=>$menuid, 
			"pid"=>$topid
		);
		if ($conn->data($info)->save()){
			$this->success();
		}else{
			$this->error("菜单移动成功！");
		}
	}
	public function actionDel(){
		$menuid=isset($_GET["id"]) ? $_GET["id"] : "";
		if (!is_numeric($menuid)){
			$this->error("参数错误...");
		}
		$array=SysUserMenu::model()->findAll();
		$tree=new Tree();
		$tree->init($array);
		$pidarr=array();
		$pidarr=$tree->get_childid_list($menuid);
		$pidarr[]=intval($menuid);
		$ids=implode(",", $pidarr);
		if (SysUserMenu::model()->deleteAll("id in($ids)")){
			$this->success("删除成功！");
		}else{
			$this->error("删除失败！");
		}
	}
	public function get_child($myid, $arr){
		$a=$newarr=array();
		if (is_array($arr)){
			foreach ($arr as $id=>$a){
				if ($a['pid']==$myid)
					$newarr[]=$a["modelname"];
			}
		}
		return $newarr ? $newarr : false;
	}
	function actionRulesadd(){
		$this->error('~~');
	}
}