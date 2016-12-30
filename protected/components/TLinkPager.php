<?php

class TLinkPager extends CLinkPager{

	const CSS_FIRST_PAGE='prev';

	const CSS_LAST_PAGE='next';

	const CSS_PREVIOUS_PAGE='prev';

	const CSS_NEXT_PAGE='next';

	const CSS_INTERNAL_PAGE='';

	const CSS_HIDDEN_PAGE='disabled';

	const CSS_SELECTED_PAGE='active';
	public $params;
	public $pageVar='page';
	public $route='';
	public function createPageUrl($page){
		$params=$this->params===null ? $_GET : $this->params;
		if ($page>0){
			$params[$this->pageVar]=$page+1;
		}else{
			unset($params[$this->pageVar]);
		}
		unset($params['_akey']);
		$cc=$params['_m'];
		unset($params['_m']);
		unset($params['admin']);
		unset($params['scr']);
		unset($params['interface']);
		$action=$params['_action'] ? $params['_action'] : 'index';
		$this->route='/'.$_GET['_akey'].'/'.$cc.'/'.$params['_controller'].'/'.$action;
		unset($params['_controller']);
		unset($params['_action']);
		return Yii::app()->createUrl($this->route, $params, '&');
		// return AAU('/page/'.$page);
	}
	/**
	 *
	 * @var string the CSS class for the first page button. Defaults to 'first'.
	 * @since 1.1.11
	 */
	public $firstPageCssClass=self::CSS_FIRST_PAGE;
	
	/**
	 *
	 * @var string the CSS class for the last page button. Defaults to 'last'.
	 * @since 1.1.11
	 */
	public $lastPageCssClass=self::CSS_LAST_PAGE;
	
	/**
	 *
	 * @var string the CSS class for the previous page button. Defaults to 'previous'.
	 * @since 1.1.11
	 */
	public $previousPageCssClass=self::CSS_PREVIOUS_PAGE;
	
	/**
	 *
	 * @var string the CSS class for the next page button. Defaults to 'next'.
	 * @since 1.1.11
	 */
	public $nextPageCssClass=self::CSS_NEXT_PAGE;
	
	/**
	 *
	 * @var string the CSS class for the internal page buttons. Defaults to 'page'.
	 * @since 1.1.11
	 */
	public $internalPageCssClass=self::CSS_INTERNAL_PAGE;
	
	/**
	 *
	 * @var string the CSS class for the hidden page buttons. Defaults to 'hidden'.
	 * @since 1.1.11
	 */
	public $hiddenPageCssClass=self::CSS_HIDDEN_PAGE;
	
	/**
	 *
	 * @var string the CSS class for the selected page buttons. Defaults to 'selected'.
	 * @since 1.1.11
	 */
	public $selectedPageCssClass=self::CSS_SELECTED_PAGE;
	
	/**
	 *
	 * @var integer maximum number of page buttons that can be displayed. Defaults to 10.
	 */
	public $maxButtonCount=10;
	
	/**
	 *
	 * @var string the text label for the next page button. Defaults to 'Next &gt;'.
	 */
	public $nextPageLabel='&gt;';
	
	/**
	 *
	 * @var string the text label for the previous page button. Defaults to '&lt; Previous'.
	 */
	public $prevPageLabel='&lt;';
	
	/**
	 *
	 * @var string the text label for the first page button. Defaults to '&lt;&lt; First'.
	 */
	public $firstPageLabel='&lt;&lt;';
	
	/**
	 *
	 * @var string the text label for the last page button. Defaults to 'Last &gt;&gt;'.
	 */
	public $lastPageLabel='&gt;&gt;';
	
	/**
	 *
	 * @var string the text shown before page buttons. Defaults to 'Go to page: '.
	 */
	public $header='';
	
	/**
	 *
	 * @var string the text shown after page buttons.
	 */
	public $footer='';
	
	/**
	 *
	 * @var mixed the CSS file used for the widget. Defaults to null, meaning
	 * using the default CSS file included together with the widget.
	 * If false, no CSS file will be used. Otherwise, the specified CSS file
	 * will be included when using this widget.
	 */
	public $cssFile;
	
	/**
	 *
	 * @var array HTML attributes for the pager container tag.
	 */
	public $htmlOptions=array(
		'class'=>'pagination'
	);
}