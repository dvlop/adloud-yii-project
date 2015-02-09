<?php
/**
 * Created by PhpStorm.
 * User: rem
 * Date: 14.07.14
 * Time: 14:27
 */

namespace application\modules\admin\controllers;

use application\components\ControllerAdmin;
use application\models\SplitTests;
use models\Block;

class BlockController extends ControllerAdmin
{
    public function actionList()
    {
        $model = Block::getInstance();

        $blocks = [];
        try{
            $params = [
                'limit' => 100000,
                'offset' => 0,
            ];
            if($allBlocks = $model->getAllUsersBlock($params)){

                foreach($allBlocks as $block){
                    $categories = [];

                    if($cats = $model->getCategoriesNames($block['categories'])){
                        foreach($cats as $cat){
                            $categories[] = $cat['description'];
                        }
                    }

                    $blocks[] = (object)[
                        'id' => $block['id'],
                        'name' => $block['description'],
                        'siteId' => $block['siteId'],
                        'categories' => implode(', ', $categories),
                        'type' => $block['size'],
                        'date' => $block['date'],
                    ];
                }
            }
        }catch(\Exception $e){
            \Yii::app()->user->setFlash('error', $e->getMessage());
        }

        $this->render('list', [
            'blocks' => $blocks
        ]);
    }

    public function actionChangeStatus($id)
    {
        if(\Yii::app()->request->isAjaxRequest && isset($_POST['status']) && isset($_POST['format'])){
            $json = [
                'message' => '',
                'error' => '',
                'html' => ''
            ];

            $status = \Yii::app()->request->getPost('status') == 'true';
            $format = \Yii::app()->request->getPost('format');

            try{
                if(Block::changeFormatStatus($format, $status))
                    $json['message'] = 'Статус блока успешно изменён';
                else
                    $json['error'] = 'Не удалось изменить статус блока';
            }catch(\Exception $e){
                $json['error'] = $this->parseError($e->getMessage(), 'Не удалось изменить статус');
            }

            echo \CJSON::encode($json);
        }

        \Yii::app()->end();
    }

    public function actionChangeAdsStatus($blockId)
    {
        if(\Yii::app()->request->isAjaxRequest && isset($_POST['status']) && isset($_POST['adsId'])){
            $json = [
                'message' => '',
                'error' => '',
                'html' => ''
            ];

            $status = \Yii::app()->request->getPost('status') == 'true';
            $adsId = \Yii::app()->request->getPost('adsId');

            $model = Block::getInstance();

            try{
                if($model->changeAdsInBlockStatus($adsId, $blockId, $status))
                    $json['message'] = 'Объявление отключено в данном блоке';
                else
                    $json['error'] = 'Не удалось отключить объявление';
            }catch(\Exception $e){
                $json['error'] = $this->parseError($e->getMessage(), 'Не удалось отключить объявление');
            }

            echo \CJSON::encode($json);
        }

        \Yii::app()->end();
    }

    public function actionFormatTestList($format){

        if(\Yii::app()->request->isPostRequest){
            $test = new SplitTests();
            $test->name = \Yii::app()->request->getPost('name');
            $test->start_date = date('Y-m-d');
            $test->format = $format;
            try{
                $test->save();
            } catch (\Exception $e){
                \Yii::app()->user->setFlash('error', $e->getMessage());
            }
        }
        $tests = SplitTests::model()->findAllByAttributes(['format' => $format]);
        $this->render('formatTestList', ['tests' => $tests, 'format' => $format]);

    }

    public function actionFormatTest($id){
        $fomatTest = SplitTests::model()->findByPk($id);
        $format = $fomatTest->format;

        if($fomatTest->state){
            $typeStats = Block::getFormatSplitTestStats($format);
        } else {
            $typeStats = json_decode($fomatTest->results, 1);
            $typeStats = $typeStats ? $typeStats : Block::getFormatSplitTestStats($format);
        }

        $this->render('formatTest', ['types' => $typeStats, 'format' => $format, 'status' => $fomatTest->state]);
    }

    public function actionAddSplitTestType($format, $type, $state, $id){
        $fomatTest = SplitTests::model()->findByPk($id);
        if(!$fomatTest->state){
            echo json_encode(['error' => 'cannot start']);
            return;
        }
        if($state){
            $result = Block::addSplitTestTypeToFormat($format, $type);
        } else {
            $result = Block::removeTestTypeToFormat($format, $type);
        }
        echo json_encode(['message' => $result ? 'Операция успешна' : 'Ошибка']);
    }

    public function actionChangeFormatSplitTestStatus($id, $status){
        $fomatTest = SplitTests::model()->findByPk($id);
        if($status){
            $fomatTest->state = 1;
        } else {
            $stats = Block::getFormatSplitTestStats($fomatTest->format);
            $fomatTest->state = 0;
            foreach($stats as $key=>$val){
                $stats[$key]['status'] = false;
            }
            $fomatTest->results = json_encode($stats);
            Block::stopFormatSplitTest($fomatTest->format);
        }
        $fomatTest->save($fomatTest->state);
        echo json_encode(['message' => 'Операция успешна']);
    }
} 