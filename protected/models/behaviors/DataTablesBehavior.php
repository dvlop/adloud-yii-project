<?php
/**
 * Created by PhpStorm.
 * User: Дима
 * Date: 24.09.14
 * Time: 11:09
 */

namespace application\models\behaviors;


class DataTablesBehavior extends \CActiveRecordBehavior
{
    const PARAM_START = 'iDisplayStart';
    const PARAM_LIMIT = 'iDisplayLength';
    const PARAM_SORT_COL = 'iSortCol_';
    const PARAM_SORT_COUNT = 'iSortingCols';
    const PARAM_SORTABLE = 'bSortable_';
    const PARAM_SORT_DIR = 'sSortDir_';
    const PARAM_SEARCH = 'sSearch';
    const PARAM_SEARCHABLE = 'bSearchable_';
    const PARAM_SEARCH_NUM = 'sSearch_';

    public $columnsArray = [];

    /**
     * @var \CDbCriteria
     */
    private $criteria;

    /**
     * @var \CDbCriteria
     */
    private $criteriaBeforeSearch;

    /**
     * @var \CDbCriteria
     */
    private $criteriaAfterSearch;

    private $params = [];
    private $additionalConditions = [];

    private $_columns;

    /**
     * @return \CActiveRecord
     */
    public function getOwner()
    {
        return parent::getOwner();
    }

    public function getAjaxData($params, $additionalAttributes = [])
    {
        $this->setParams($params, $additionalAttributes);

        $data = [];
        foreach($this->getData() as $model){
            $tmpData = [];
            foreach($this->getColumns() as $column){
                $attr = $column->name;
                $tmpData[] = $model->$attr;
            }

            $data[] = $tmpData;
        }

        $json = array(
            'sEcho' => $this->getIsEcho(),
            'iTotalRecords' => $this->getCount(),
            'iTotalDisplayRecords' => $this->getFilteredCount(),
            'aaData' => $data
        );

        return $json;
    }

    public function setParams($params, $additionalAttributes)
    {
        $this->params = (array)$params;
        $this->additionalConditions = $additionalAttributes;

        $this->criteriaBeforeSearch = new \CDbCriteria();
        $this->criteriaBeforeSearch->condition = $this->setConditions($this->additionalConditions);

        $this->criteria = new \CDbCriteria();
        $this->criteriaAfterSearch = new \CDbCriteria();

        $this->getLimit();
        $this->getSort();
        $this->getFilter();
    }

    public function getCount()
    {
        return $this->getOwner()->count($this->criteriaBeforeSearch);
    }

    public function getFilteredCount()
    {
        return $this->getOwner()->count($this->criteriaAfterSearch);
    }

    /**
     * @return \stdClass[]
     */
    public function getColumns($columnsArray = [])
    {
        if($this->_columns === null){
            $this->_columns = [];

            if($columnsArray)
                $this->columnsArray = $columnsArray;

            if(!$this->columnsArray)
                $this->columnsArray = $this->getOwner()->getColumnsArray();

            foreach($this->columnsArray as $attr){
                $column = (object)[
                    'name' => $attr['name'],
                    'text' => $attr['text'],
                    'sort' => '',
                    'search' => '',
                    'type' => '',
                ];

                $column->sort = isset($attr['sort']) ? $attr['sort'] : $attr['name'];
                $column->search = isset($attr['search']) ? $attr['search'] : $column->sort;
                $column->type = isset($attr['type']) ? $attr['type'] : 'integer';

                $this->_columns[] = $column;
            }
        }

        return $this->_columns;
    }

    public function getData()
    {
        return $this->getOwner()->findAll($this->criteria);
    }

    public function getFilter()
    {
        $params = $this->params;
        $searchName = self::PARAM_SEARCH;
        $columns = $this->getColumns();
        $condition = '';
        $attributes = [];

        // Search
        if(isset($params[$searchName]) && $params[$searchName])
        {
            $value = $params[$searchName];

            for($i=0 ; $i < count($columns) ; $i++){
                $name = $columns[$i]->search;
                list($cond, $attr) = $this->addAttribute($name, $value, $columns[$i]->type);

                if($cond){
                    $condition .= $cond.' OR ';
                    $attributes = array_merge($attributes, $attr);
                }
            }

            if($condition)
                $condition = '( '.substr($condition, 0, strlen($condition)-4).' )';
        }
        // END Search

        // Individual filtering
        $searchable = self::PARAM_SEARCHABLE;
        $search = self::PARAM_SEARCH_NUM;
        $filters = '';

        for($i=0; $i<count($columns); $i++)
        {
            if(isset($params[$searchable.$i]) && $params[$searchable.$i] == 'true' && isset($params[$search.$i]) && $params[$search.$i])
            {
                $name = $columns[$i]->search;
                $value = $params[$search.$i];

                list($cond, $attr) = $this->addAttribute($name, $value, $columns[$i]->type, ':filter_'.$i);

                if($cond){
                    $filters .= $cond.' AND ';
                    $attributes = array_merge($attributes, $attr);
                }
            }
        }

        if($filters){
            $filters = substr($filters, 0, strlen($filters)-4);
            if($condition)
                $condition = "$condition AND ( $filters )";
            else
                $condition = "( $filters )";
        }
        // END Individual filtering

        if($this->additionalConditions){
            $additional = $this->setConditions($this->additionalConditions);

            if($condition)
                $condition = "$condition AND ( $additional )";
            else
                $condition = "( $additional )";
        }

        if($condition){
            $this->criteria->condition = $condition;
            $this->criteria->params = $attributes;

            $this->criteriaAfterSearch->condition = $condition;
            $this->criteriaAfterSearch->params = $attributes;
        }
    }

    public function getSort()
    {
        $by = 'id desc';
        $params = $this->params;
        $colName = self::PARAM_SORT_COL;
        $columns = $this->getColumns();

        if(isset($params[$colName.'0']))
        {
            for($i=0 ; $i < intval($params[self::PARAM_SORT_COUNT]) ; $i++){
                $sortNum = self::PARAM_SORTABLE.intval($params[$colName.$i]);

                if(isset($params[$sortNum]) && $params[$sortNum] == 'true'){
                    $num = intval($params[$colName.$i]);
                    if(isset($columns[$num])){
                        $by = $columns[$num]->sort.' '.$params[self::PARAM_SORT_DIR.$i];
                        break;
                    }
                }
            }
        }

        $this->criteria->order = $by;
        return ['order' => $by];
    }

    public function getLimit()
    {
        $start = 0;
        $limit = \Yii::app()->params['defaultPageSize'];

        if(isset($this->params[self::PARAM_START]))
            $start = intval($this->params[self::PARAM_START]);
        if(isset($this->params[self::PARAM_LIMIT]) && $this->params[self::PARAM_LIMIT] != '-1')
            $limit = intval($this->params[self::PARAM_LIMIT]);

        $this->criteria->limit = $limit;
        $this->criteria->offset = $start;

        return ['limit' => $limit, 'offset' => $start];
    }

    private function setConditions($conditions)
    {
        if(is_string($conditions)){
            return $conditions;
        }else{
            $index = 0;
            $condition = '';

            foreach($conditions as $name => $value){
                $condition .= 't.'.$name.' = \''.$value.'\' AND ';
                $index++;
            }

            if($index)
                $condition = substr($condition, 0, strlen($condition)-5);

            return $condition;
        }
    }

    private function addAttribute($name, $value, $type = 'integer', $filterName = '')
    {
        $condition = '';
        $attributes = [];
        if(!$filterName)
            $filterName = ':filtered_value';

        switch($type){
            case 'integer':
                $condition .= 't.'.$name.' = '.intval($value);
                break;
            case 'float':
                $condition .= 't.'.$name.' = '.floatval($value);
                break;
            case 'string':
                $condition .= 't.'.$name.' LIKE '.$filterName;
                $attributes[$filterName] = trim($value).'%';
                break;
            case 'date':
                $condition .= 'date(t.'.$name.') = '.$filterName;
                $attributes[$filterName] = trim($value);
                break;
        }

        return [$condition, $attributes];
    }

    private function getIsEcho()
    {
        return isset($this->params['sEcho']) ? intval($this->params['sEcho']) : 1;
    }
}