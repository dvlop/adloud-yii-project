<?php

namespace application\components;

class ActualModel extends BaseModel
{
    protected static $_connectionName = 'dbActual';
    protected static $_connection;

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
} 