<?php
/**
 * Created by PhpStorm.
 * User: rem
 * Date: 21.08.14
 * Time: 14:51
 */

namespace application\console;

use application\models\Ads;
use application\models\Blocks;

class FixAllClicks extends Console
{
    public function run()
    {
        $model = new Ads();

        $columns = [
            'clicks' => 0,
        ];

        $where = 'clicks IS NULL';

        try{
            $command = $model->getDbConnection()->createCommand();
            $command->update(Ads::model()->tableName(), $columns, $where);
            $command->execute();
        }catch(\Exception $e){

        }

        $model->setActualConnection();
        try{
            $command = $model->getDbConnection()->createCommand();
            $command->update(Ads::model()->tableName(), $columns, $where);
            $command->execute();
        }catch(\Exception $e){

        }
        $model->setPersistentConnection();

        $model = new Blocks();

        try{
            $command = $model->getDbConnection()->createCommand();
            $command->update(Blocks::model()->tableName(), $columns, $where);
            $command->execute();
        }catch(\Exception $e){

        }

        $columns = [
            'shows' => 0,
        ];

        $where = 'shows IS NULL';

        try{
            $command = $model->getDbConnection()->createCommand();
            $command->update(Ads::model()->tableName(), $columns, $where);
            $command->execute();
        }catch(\Exception $e){

        }

        $model->setActualConnection();
        try{
            $command = $model->getDbConnection()->createCommand();
            $command->update(Ads::model()->tableName(), $columns, $where);
            $command->execute();
        }catch(\Exception $e){

        }
        $model->setPersistentConnection();

        $model = new Blocks();

        try{
            $command = $model->getDbConnection()->createCommand();
            $command->update(Blocks::model()->tableName(), $columns, $where);
            $command->execute();
        }catch(\Exception $e){

        }

        return true;
    }
} 