<?php

/**
 * Class LinkPager
 */
class LinkPager extends CLinkPager
{
    const PERV_PAGE_NAME = 'perv';
    const NEXT_PAGE_NAME = 'next';

    public $pervPageLinkClass = 'fui-arrow-left';
    public $nextPageLinkClass = 'fui-arrow-right';

    public $cssFile               = false;
    public $nextPageLabel         = '';
    public $prevPageLabel         = '';
    public $firstPageLabel        = '';
    public $lastPageLabel         = '';
    public $previousPageCssClass  = 'previous';
    public $nextPageCssClass      = 'next';
    public $firstPageCssClass     = 'hidden';
    public $lastPageCssClass      = 'hidden';
    public $selectedPageCssClass  = 'active';
    public $hiddenPageCssClass    = '';
    public $header                = '';
    public $mainContainerClass    = 'adloud_pagination pagination pull-right pages_nav';

    public function init()
    {
        parent::init();
    }

    public function run()
    {
        $this->registerClientScript();
        $buttons=$this->createPageButtons();
        if(empty($buttons))
            return;
        echo $this->header;
        echo '<div class="'.$this->mainContainerClass.'">'.CHtml::tag('ul',$this->htmlOptions,implode("\n",$buttons)).'</div>';
        echo $this->footer;
    }

    protected function createPageButtons()
    {
        if(($pageCount=$this->getPageCount()) <= 1)
            return array();

        list($beginPage,$endPage)=$this->getPageRange();
        $currentPage=$this->getCurrentPage(false); // currentPage is calculated in getPageRange()
        $buttons=array();

        // first page
        $buttons[]=$this->createPageButton($this->firstPageLabel,0,$this->firstPageCssClass,$currentPage<=0,false);

        // prev page
        if(($page=$currentPage-1)<0)
            $page=0;
        $buttons[]=$this->createPageButton($this->prevPageLabel,$page,$this->previousPageCssClass,$currentPage<=0,false,self::PERV_PAGE_NAME);

        // internal pages
        for($i=$beginPage;$i<=$endPage;++$i)
            $buttons[]=$this->createPageButton($i+1,$i,$this->internalPageCssClass,false,$i==$currentPage);

        // next page
        if(($page=$currentPage+1)>=$pageCount-1)
            $page=$pageCount-1;
        $buttons[]=$this->createPageButton($this->nextPageLabel,$page,$this->nextPageCssClass,$currentPage>=$pageCount-1,false,self::NEXT_PAGE_NAME);

        // last page
        $buttons[]=$this->createPageButton($this->lastPageLabel,$pageCount-1,$this->lastPageCssClass,$currentPage>=$pageCount-1,false);

        return $buttons;
    }

    protected function createPageButton($label,$page,$class,$hidden,$selected,$isPervNext=false)
    {
        if($hidden || $selected)
            $class .= ' '.($hidden ? $this->hiddenPageCssClass : $this->selectedPageCssClass);

        $htmlOptions = [];

        if($isPervNext){
            if($isPervNext == self::PERV_PAGE_NAME)
                $linkClass = $this->pervPageLinkClass;
            if($isPervNext == self::NEXT_PAGE_NAME)
                $linkClass = $this->nextPageLinkClass;

            $htmlOptions = [
                'class' => $linkClass,
            ];
        }

        return '<li class="'.$class.'">'.CHtml::link($label,$this->createPageUrl($page),$htmlOptions).'</li>';
    }
}