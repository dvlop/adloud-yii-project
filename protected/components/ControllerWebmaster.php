<?php
/**
 * User: maksymenko.ml
 * Date: 25.03.14
 * Time: 17:46
 */
namespace application\components;

use core\PostgreSQL;
use core\Session;

class ControllerWebmaster extends ControllerBase
{
    public $layout = '//layouts/authorized';

    public $sideMenu = [
        [
            'name' => 'Рабочий стол',
            'url' => 'webmaster',
            'actions' => ['list', 'site'],
            'menu' => [
                [
                    'name' => 'Мои площадки',
                    'url' => '/webmaster/site/list',
                    'icon' => 'icon-globe',
                ],
                [
                    'name' => 'Статистика',
                    'url' => '/webmaster/stats',
                    'icon' => 'icon-plus-sign',
                ],
                [
                    'name' => 'Рефералы',
                    'url' => '/webmaster/referals',
                    'icon' => 'icon-referals',
                ],
                [
                    'name' => 'Вывод средств',
                    'url' => '/webmaster/money',
                    'icon' => 'icon-money',
                ],
                /*[
                    'name' => 'Личная информация',
                    'url' => '/account/userInfo',
                    'icon' => 'icon-plus-sign',
                ],*/
            ],
        ],
        /*[
            'name' => 'Финансы',
            'url' => 'webmaster',
            'actions' => ['moneyshow'],
            'menu' => [
                [
                    'name' => 'Вывести средства',
                    'url' => 'webmaster/money',
                    'icon' => 'icon-money',
                ],
            ],
        ],*/
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

        $controllerName = 'webmaster';
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

        $this->breadcrumbs[\Yii::app()->createUrl('webmaster/site/list')] = \Yii::t('sites', 'Мои площадки');

        $this->setLastController();

        $this->sideMenu[0]['name'] = \Yii::t('sites', $this->sideMenu[0]['name']);

        foreach($this->sideMenu[0]['menu'] as $num => $elem){
            if(isset($elem['name'])){
                $this->sideMenu[0]['menu'][$num]['name'] = \Yii::t('sites', $elem['name']);
            }
        }

        foreach($this->alterPageName as $num => $pageName){
            if(isset($pageName['headers'])){
                foreach($pageName['headers'] as $headNum => $head){
                    if(isset($head['name']))
                        $this->alterPageName[$num]['headers'][$headNum]['name'] = \Yii::t('sites', $head['name']);
                }
            }
        }

        foreach($this->topButtons as $num => $elem){
            if(isset($elem['name']))
                $this->topButtons[$num]['name'] = \Yii::t('campaigns', $elem['name']);

            if(isset($elem['elements'])){
                foreach($elem['elements'] as $elementNum => $element){
                    if(isset($element['name']))
                        $this->topButtons[$num]['elements'][$elementNum]['name'] = \Yii::t('sites', $element['name']);
                }
            }
        }

        return true;
    }

    protected function getModalCode($blockId)
    {
        $model = \models\Block::getInstance()->init($blockId);

        $text = $model->getSiteModerated() ?
            'Можно встаить этот код на любые страницы и веб-сайты, для которых выполняются наши правила и условия'
                                            :
            'Обратите внимание, что рекламный блок начнёт отображаться после того, как Ваш сайт пройдёт модерацию';

        return [
            'title' => 'Вставьте рекламный код на Ваш сайт',
            'subtitle' => $text,
            'content' => htmlspecialchars($model->getNewInsertCode()),
            'buttonOk' => 'Скопировать код в буфер',
            'buttonCancel' => 'Сохранить и выйти',
            'cancelUrl' => \Yii::app()->createUrl('webmaster/site/list'),
            'okSpanClass' => 'input-icon fui-exit pull-left',
            'cancelSpanClass' => 'input-icon fui-check pull-left',
            'afterOkText' => 'Код скопирован в буфер',
        ];
    }

    protected function getModalAsk($siteId)
    {
        return [
            'title' => 'Создание нового блока',
            'subtitle' => 'Укажите тип создаваемого блока',
            'contentContainer' => $this->renderPartial('themes.'.\Yii::app()->theme->name.'.views.webmaster.block._modalAskBlock', ['formats' => \models\Block::getBlockFormats()], true),
            'buttonOk' => 'Создать блок',
            'buttonCancel' => 'Отказаться',
            'cancelUrl' => \Yii::app()->createUrl('webmaster/block/list', ['siteId' => $siteId]),
            'okSpanClass' => 'input-icon fui-exit pull-left',
            'cancelSpanClass' => 'input-icon fui-check pull-left',
            'afterOkText' => 'Код скопирован в буфер',
        ];
    }
}