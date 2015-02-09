<?php

namespace application\components;

/**
 * Created by PhpStorm.
 * User: rem
 * Date: 31.07.14
 * Time: 9:57
 * @property string[] $listColumns
 * @property string[] $listColumnsNames
 * @property array $listColumnsValues
 * @property array $searchColumns
 * @property array $sortColumns
 * @property array $additionalSearchAttributes
 * @property array $params
 * @property string $condition
 * @property int $userPageSize
 * @property int $adminPageSize
 * @property int $defaultPageSize
 */
class BaseModel extends \CActiveRecord
{
    /**
     * @var \CDbConnection
     */
    protected static $_connection;
    protected static $_connectionName = 'db';

    private $_listColumnsVals;
    private $_searchColumnsCriteria;
    private $_isCopy = false;

    public $pageSize;
    public $id;

    public function setAttributes($values, $safeOnly=true)
    {
        if($values && is_array($values)){
            foreach($values as $name=>$value){
                $methodName = 'set'.ucfirst(str_replace('_', '', $name));

                if(method_exists($this, $methodName)){
                    $this->$methodName($value);
                    unset($values[$name]);
                }elseif(is_string($value) && trim($value) === ''){
                    $values[$name] = null;
                }
            }
        }

        if((property_exists($this, 'userId') || method_exists($this, 'setUserId')) && $this->userId === null)
            $this->userId = \Yii::app()->user->id;

        parent::setAttributes($values, $safeOnly);
    }

    public function __get($name)
    {
        $value = null;
        $methodName = 'get'.ucfirst($name);

        if(method_exists($this, $methodName)){
            return $this->parseDbArray($this->$methodName());
        }

        return parent::__get($name);
    }

    public function getModelName()
    {
        return str_replace('\\', '_', get_class($this));
    }

    public function getListColumns()
    {
        /**
         * example: [
         *      'post' => [
         *          'name' => 'post',
         *          'value' => 'post', //optional (default = attr "name")
         *      ],
         * ]
         */
        return [];
    }

    public function getSearchColumns()
    {
        /**
         * example: [
         *      'post' => [
         *          'name' => 'post',
         *          'value' => $this->getPost(), //optional (default = attr "name")
         *          'partialMatch' => false, //optional (default: true)
         *          'operation' => 'OR'  //optional (default: 'AND')
         *      ]
         * ]
         */
        return [];
    }

    public function getSortColumns()
    {
        /**
         * example:
         * [
         *      'id',
         *      'name'
         * ]
         */
        return [];
    }

    public function getAdditionalSearchColumns()
    {
        return [];
    }

    public function getConditions()
    {
        /**
         * @var string query condition. This refers to the WHERE clause in an SQL statement.
         * For example, <code>age>31 AND team=1</code>.
         */
        return '';
    }

    public function getParams()
    {
        /**
         * @var array list of query parameter values indexed by parameter placeholders.
         * For example, <code>array(':name'=>'Dan', ':age'=>31)</code>.
         */
        return [];
    }

    public function getListColumnsNames()
    {
        return array_keys($this->listColumns);
    }

    public function getUserPageSize()
    {
        return \Yii::app()->params['pageSize'];
    }

    public function getAdminPageSize()
    {
        return \Yii::app()->params['adminPageSize'];
    }

    public function getDefaultPageSize()
    {
        return $this->pageSize ? $this->pageSize : $this->getAdminPageSize();
    }

    /**
     * @return array
     */
    public function getListColumnsValues()
    {
        if($this->_listColumnsVals === null){
            $this->_listColumnsVals = [];

            foreach($this->getListColumns() as $name => $value){
                $type = 'raw';

                if(is_string($value)){
                    $valName = is_string($name) ? $name : $value;
                    $val = $value;
                }elseif(is_array($value)){
                    if(isset($value['class'])){
                        $this->_listColumnsVals[] = $value;
                        continue;
                    }else{
                        $valName = is_string($name) ? $name : ( isset($value['name']) ? $value['name'] : '' );
                        $val = isset($value['value']) ? $value['value'] : $valName;
                        if(isset($value['type']))
                            $type = $value['type'];
                    }
                }else{
                    continue;
                }

                $res = [
                    'name' => $valName,
                    'type' => $type,
                    'value' => $this->parseValueString($val)
                ];

                if(is_array($value))
                    $res = array_merge($value, $res);

                $this->_listColumnsVals[] = $res;
            }
        }

        return $this->_listColumnsVals;
    }

    /**
     * Retrieves a list of models based on the current search/filter conditions.
     *
     * Typical usecase:
     * - Initialize the model fields with values from filter form.
     * - Execute this method to get CActiveDataProvider instance which will filter
     * models according to data in model fields.
     * - Pass data provider to CGridView, CListView or any similar widget.
     *
     * @return \CActiveDataProvider the data provider that can return the models
     * based on the search/filter conditions.
     */
    public function search()
    {
        if(!$this->beforeSearch())
            return null;

        $modelName = str_replace('\\', '_', get_class($this));

        if(isset($_GET[$modelName])){
            $this->setAttributes($_GET[$modelName]);
            if(isset($_GET[$modelName]['id']))
                $this->id = $_GET[$modelName]['id'];
        }

        return new \CActiveDataProvider($this, [
            'criteria' => $this->getSearch($this->getAdditionalSearchColumns()),
            'sort' => [
                'defaultOrder' => 'id DESC',
                'attributes' => $this->getSortColumns(),
            ],
            'pagination' => [
                'pageSize' => $this->getDefaultPageSize()
            ],
        ]);
    }

    /**
     * @param array $attrs
     * @return \CDbCriteria
     */
    public function getSearch($attrs = [])
    {
        if($this->_searchColumnsCriteria === null){
            $this->_searchColumnsCriteria = new \CDbCriteria;
            $this->_searchColumnsCriteria->condition = $this->getConditions();

            $tmpArr = [];

            foreach($this->getSearchColumns() as $name => $value){
                $partial = true;
                $operation = 'AND';

                if(is_string($value)){
                    $valName = is_string($name) ? $name : $value;
                    $val = $value;
                }elseif(is_array($value)){
                    $valName = is_string($name) ? $name : ( isset($value['name']) ? $value['name'] : '' );
                    $val = isset($value['value']) ? $value['value'] : $valName;
                    if(isset($value['partialMatch']))
                        $partial = $value['partialMatch'];
                    if(isset($value['operation']))
                        $operation = $value['operation'];
                }else{
                    continue;
                }

                $tmpArr[] = [
                    'name' => 't.'.$valName,
                    'value' => $this->getSearchValue($val),
                    'partialMatch' => $partial,
                    'operation' => $operation
                ];
            }

            $tmpArr = array_merge($tmpArr, $attrs);

            if($tmpArr){
                foreach($tmpArr as $arr){
                    if(!isset($arr['query']))
                        $this->_searchColumnsCriteria->compare($arr['name'], $arr['value'], $arr['partialMatch'], $arr['operation']);
                    else{
                        $query = $arr['query'];

                        if(isset($query['select']))
                            $this->_searchColumnsCriteria->select = $query['select'];
                        if(isset($query['with']))
                            $this->_searchColumnsCriteria->with = $query['with'];
                        if(isset($query['join']))
                            $this->_searchColumnsCriteria->join = $query['join'];
                        if(isset($query['group']))
                            $this->_searchColumnsCriteria->group = $query['group'];
                        if(isset($query['condition']))
                            $this->_searchColumnsCriteria->condition = $query['condition'];
                        if(isset($query['params']))
                            $this->_searchColumnsCriteria->params = $query['params'];
                    }
                }
            }
        }

        return $this->_searchColumnsCriteria;
    }

    public function beforeSearch()
    {
        $modelName = str_replace('\\', '_', get_class($this));

        if(isset($_GET[$modelName])){
            if(isset($_GET[$modelName]['id'])){
                if($id = intval($_GET[$modelName]['id']))
                    $this->id = $id ? $id : null;

                $_GET[$modelName]['id'] = null;
            }
        }

        return true;
    }

    /**
     * @param $value
     * @return array
     */
    public function parseDbArray($value)
    {
        if($value && is_string($value) && strpos($value, '{') !== false && strpos($value, '}') !== false){
            $value = str_replace('{', '', $value);
            $value = str_replace('}', '', $value);
            $value = array_filter(explode(',', $value));
        }

        return $value;
    }

    /**
     * @param $value
     * @return string
     */
    public function parseDbString($value)
    {
        if(is_array($value)){
            $value = '{'.implode(',', $value).'}';
        }elseif(is_int($value) || is_float($value) || (is_string($value) && strpos($value, '{') === false && strpos($value, '}') === false)){
            $value = '{'.$value.'}';
        }

        return $value;
    }

    public function setActualConnection()
    {
        self::$_connectionName = 'dbActual';
        $this->getDbConnection(true);
    }

    public function setPersistentConnection()
    {
        self::$_connectionName = 'db';
        $this->getDbConnection(true);
    }

    public function copy($runValidation = true, $attributes = null)
    {
        if(!$this->beforeCopy())
            return false;

        if($this->hasErrors())
            return false;

        $className = get_class($this);
        $newModel = new $className();

        $attr = $this->getAttributes();

        unset($attr['id']);

        if(method_exists($newModel, 'setEntity'))
            $newModel->setEntity($this->getEntity());

        $newModel->setAttributes($attr);

        if(!$newModel->beforeCopy()){
            $this->addErrors($newModel->getErrors());
            return false;
        }

        $result = $newModel->save($runValidation, $attributes);

        if(!$result)
            $this->addErrors($newModel->getErrors());

        return $result;
    }

    public function beforeCopy()
    {
        $this->_isCopy = true;

        return true;
    }

    public function getIsCopy()
    {
        return $this->_isCopy;
    }

    public function getEntity()
    {
        return null;
    }

    /**
     * @return \CDbConnection
     */
    public function getDbConnection($update = false)
    {
        if($update || self::$_connection === null){
            $dbName = self::$_connectionName;
            self::$_connection = \Yii::app()->$dbName;

            if(self::$_connection instanceof \CDbConnection){
                self::$_connection->setActive(true);
            }
            else
                throw new \CDbException(\Yii::t('yii',"Active Record requires a '$dbName' CDbConnection application component."));
        }

        return self::$_connection;
    }

    private function parseNameString($name)
    {
        if(!$name)
            return '';

        if(strpos($name, '_') !== false){
            $tmp = explode('_', $name);
            $name = $tmp[0];
            unset($tmp[0]);

            if($tmp){
                foreach($tmp as $namePart){
                    $name .= ucfirst($namePart);
                }
            }
        }

        return (string)$name;
    }

    private function parseValueString($val)
    {
        $val = $this->parseNameString($val);

        if(!$val)
            return '';

        $tmp = 'get'.ucfirst($val);

        if(method_exists($this, $tmp))
            $val = '$data->'.$tmp.'()';
        else
            $val = '$data->'.$val;

        return $val;
    }

    private function getSearchValue($valName)
    {
        $valName = $this->parseNameString($valName);

        if(!$valName)
            return null;

        $methodName = 'get'.ucfirst($valName);

        if(method_exists($this, $methodName))
            $val = $this->$methodName();
        else
            $val = $this->$valName;

        return $val;
    }
}