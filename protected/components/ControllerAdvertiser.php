<?php
/**
 * User: maksymenko.ml
 * Date: 25.03.14
 * Time: 17:46
 */

namespace application\components;

use application\models\TargetList;
use core\PostgreSQL;
use core\Session;

class ControllerAdvertiser extends ControllerBase
{
    public $layout = '//layouts/authorized';

    public $sideMenu = [
        [
            'name' => 'Рабочий стол',
            'url' => 'advertiser',
            'actions' => array('campaignslist', 'campaign'),
            'menu' => array(
                [
                    'name' => 'Мои кампании',
                    'url' => '/advertiser/campaign/list',
                    'icon' => 'icon-rocket',
                ],
                [
                    'name' => 'Статистика',
                    'url' => 'advertiser/stats',
                    'icon' => 'icon-plus-sign',
                ],
                [
                    'name' => 'Рефералы',
                    'url' => '/advertiser/referals',
                    'icon' => 'icon-referals',
                ],
                [
                    'name' => 'Списки сайтов',
                    'url' => 'advertiser/lists',
                    'icon' => 'icon-plus-sign',
                ],
                [
                    'name' => 'Ретаргетинг',
                    'url' => 'advertiser/retargeting',
                    'icon' => 'icon-plus-sign',
                ],
                [
                    'name' => 'Пополнить счет',
                    'url' => 'payment/payment/index',
                    'icon' => 'icon-money',
                ],
            ),
        ],
    ];

    public function filters()
    {
        return array(
            'accessControl',
        );
    }

    public function accessRules()
    {
        return array(
            array('deny',
                'actions'=>array(),
                'users'=>array('?'),
            ),
        );
    }

    public function setLastController(){
        $db = PostgreSQL::getInstance()->getConnection(Session::getInstance(), 'persistent_data');

        $controllerName = 'advertiser';
        $id = Session::getInstance()->getUserId();

        $sql = 'UPDATE users SET last_controller = :controller WHERE id = :id';

        $statement = $db->prepare($sql);
        $statement->bindParam(':controller', $controllerName, \PDO::PARAM_STR);
        $statement->bindParam(':id', $id, \PDO::PARAM_STR);
        $statement->execute();
    }

    public function beforeAction($action)
    {
        parent::beforeAction($action);

        if(\Yii::app()->user->getIsBanned()){
            $this->redirect(\Yii::app()->createUrl('account/banned'));
        }

        $this->breadcrumbs[\Yii::app()->createUrl('advertiser/campaign/list')] = \Yii::t('campaigns', 'Мои кампании');

        $this->setLastController();

        $this->sideMenu[0]['name'] = \Yii::t('campaigns', $this->sideMenu[0]['name']);

        foreach($this->sideMenu[0]['menu'] as $num => $elem){
            if(isset($elem['name'])){
                $this->sideMenu[0]['menu'][$num]['name'] = \Yii::t('campaigns', $elem['name']);
            }
        }

        foreach($this->alterPageName as $num => $pageName){
            if(isset($pageName['headers'])){
                foreach($pageName['headers'] as $headNum => $head){
                    if(isset($head['name']))
                        $this->alterPageName[$num]['headers'][$headNum]['name'] = \Yii::t('campaigns', $head['name']);
                }
            }
            if(isset($pageName['elements'])){
                foreach($pageName['elements'] as $elementNum => $element){
                    if(isset($element['name']))
                        $this->alterPageName[$num]['elements'][$elementNum]['name'] = \Yii::t('campaigns', $element['name']);
                }
            }
        }

        foreach($this->topButtons as $num => $elem){
            if(isset($elem['name']))
                $this->topButtons[$num]['name'] = \Yii::t('campaigns', $elem['name']);

            if(isset($elem['elements'])){
                foreach($elem['elements'] as $elementNum => $element){
                    if(isset($element['name']))
                        $this->topButtons[$num]['elements'][$elementNum]['name'] = \Yii::t('campaigns', $element['name']);
                }
            }
        }

        //\Yii::app()->test->show($this->alterPageName);

        return true;
    }

    protected function getModalCode($blockId)
    {
        $model = TargetList::model();
        $model->id = $blockId;

        $text = 'Можно встаить этот код на любые страницы и веб-сайты, для которых выполняются наши правила и условия';

        return [
            'title' => 'Вставьте рекламный код на Ваш сайт',
            'subtitle' => $text,
            'content' => htmlspecialchars($model->getInsertRetargetingCode()),
            'buttonOk' => 'Скопировать код в буфер',
            'buttonCancel' => 'Сохранить и выйти',
            'cancelUrl' => \Yii::app()->createUrl('webmaster/site/list'),
            'okSpanClass' => 'input-icon fui-exit pull-left',
            'cancelSpanClass' => 'input-icon fui-check pull-left',
            'afterOkText' => 'Код скопирован в буфер',
        ];
    }
}