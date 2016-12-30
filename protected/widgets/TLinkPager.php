<?php
class TLinkPager extends CLinkPager{
    const CSS_PREVIOUS_PAGE='prev';
    const CSS_NEXT_PAGE='next';
    const CSS_INTERNAL_PAGE='';
    const CSS_HIDDEN_PAGE='hidden';
    const CSS_SELECTED_PAGE='pageed'; 
	public $params;
	public $pageVar='page';
	public $route='';
	public function createPageUrl($page)
	{
		$params=$this->params===null ? $_GET : $this->params;
		if($page>0) {
			$params[$this->pageVar]=$page+1;
		}else{
			unset($params[$this->pageVar]);
		}
		unset($params['_akey']);
		unset($params['admin']);
		$action=$params['_action']?$params['_action']:'index';
		$this->route='/'.$_GET['_akey'].'/admin/'.$params['_controller'].'/'.$action;
		unset($params['_controller']);
		unset($params['_action']);
		return Yii::app()->createUrl ( $this->route, $params, '&' );

			
		//return AAU('/page/'.$page);
	}
  /*  public function init()
    {		
            if($this->nextPageLabel===null)
                    $this->nextPageLabel=Yii::t('yii','Next >');
            if($this->prevPageLabel===null)
                    $this->prevPageLabel=Yii::t('yii','< Previous');

            if(!isset($this->htmlOptions['id']))
                    $this->htmlOptions['id']=$this->getId();
            if(!isset($this->htmlOptions['class']))
                    $this->htmlOptions['class']='yiiPager';
    }

    public function run()
    {
            $this->registerClientScript();
            $buttons=$this->createPageButtons();
            $buttons[] = CHtml::tag('span', array('style'=>'height:25px;width:100px;text-align:center;line-height:25px;margin-left:148px;'),'共'.$this->getPageCount().'页');
            $buttons[] = CHtml::tag('span', array('style'=>'height:25px;line-height:25px;margin-left:30px;'), '前往第  '.CHtml::textField('pageNumber', '', array(
                'style'=>'border:1px solid #717071;width:42px;height:21px;text-align:center',
            )).CHtml::tag('span',array('id'=>'gotoBtn'),'确定').'  页');
            if(empty($buttons))
                    return;
            echo $this->header;
            echo CHtml::tag('div',$this->htmlOptions,implode("\n",$buttons));
            echo $this->footer;
    }

    protected function createPageButton($label,$page,$class,$hidden,$selected)
    {
            if($hidden || $selected)
                    $class.=' '.($hidden ? $this->hiddenPageCssClass : $this->selectedPageCssClass);
            return CHtml::link($label,$this->createPageUrl($page),array('class'=>$class));
    }

    protected function createPageButtons()
    {
            if(($pageCount=$this->getPageCount())<=1)
                     return array();
            list($beginPage,$endPage)=$this->getPageRange();
            $currentPage=$this->getCurrentPage(false); // currentPage is calculated in getPageRange()
            $buttons=array();

            // first page
            //$buttons[]=$this->createPageButton($this->firstPageLabel,0,$this->firstPageCssClass,$currentPage<=0,false);

            // prev page
            if(($page=$currentPage-1)<0)
                     $page=0;
             $buttons[]=$this->createPageButton($this->prevPageLabel,$page,$this->previousPageCssClass,$currentPage<=0,false);

            // internal pages
            for($i=$beginPage;$i<=$endPage;++$i)
                     $buttons[]=$this->createPageButton($i+1,$i,$this->internalPageCssClass,false,$i==$currentPage);

            // next page
            if(($page=$currentPage+1)>=$pageCount-1)
                    $page=$pageCount-1;
            $buttons[]=$this->createPageButton($this->nextPageLabel,$page,$this->nextPageCssClass,$currentPage>=$pageCount-1,false);

            // last page
            //$buttons[]=$this->createPageButton($this->lastPageLabel,$pageCount-1,$this->lastPageCssClass,$currentPage>=$pageCount-1,false);

            return $buttons;
    }*/

}