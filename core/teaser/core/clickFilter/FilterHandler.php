<?php
/**
 * Created by PhpStorm.
 * User: t0m
 * Date: 21.04.14
 * Time: 23:22
 */

namespace core\clickFilter;


class FilterHandler extends FilterAbstract {

    /**
     * @var Filter[]
     */
    private $filters;

    public function addFilter(Filter $cf){
        $this->filters[] = $cf;
    }

    public function filter(){
        foreach($this->filters as $filter){

            $filter->setAdsData($this->adsData);
            $filter->setBlockData($this->blockData);
            $filter->setViewerSession($this->viewerSession);
            $result = $filter->filter();

            if($result !== ClickErrors::OK){
                return $result;
            }
        }

        return ClickErrors::OK;
    }

} 