<?php
/**
* FButtonColumn.php
* ----------------------------------------------
* 版权所有 2014-2015 
* ----------------------------------------------
* @date: 2015-3-9
* 
*/
class FButtonColumn extends CButtonColumn{
	public function init(){
		$this->initDefaultButtons();
		foreach ($this->buttons as $id=>$button){
			if (strpos($this->template, '{'.$id.'}')===false&&strpos($this->template, '$data->')===false)
				unset($this->buttons[$id]);
		}
	}
	protected function renderDataCellContent($row, $data){
		$tr=array();
		ob_start();
		foreach ($this->buttons as $id=>$button){
			$this->renderButton($id, $button, $row, $data);
			$tr['{'.$id.'}']=ob_get_contents();
			ob_clean();
		}
		ob_end_clean();
		if(strpos($this->template, '$data->')!==false){
			$template=$this->evaluateExpression($this->template, array(
				'row'=>$row, 
				'data'=>$data
			));
		}
		echo strtr($template, $tr);
	}
}