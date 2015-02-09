<?php

namespace application\modules\payment\controllers;

use application\components\ControllerBase;
use application\modules\payment\models\MoneyForm;
use application\modules\payment\PaymentModule;

/**
 * Created by PhpStorm.
 * User: dima
 * Date: 24.04.14
 * Time: 10:25
 * @property \application\modules\payment\PaymentModule $module
 */
class PaymentController extends ControllerBase
{
    protected $_theme;
    protected $_secretKey = 'IDSFSF98UI';

    public $moduleName;
    public $path;
    public $partials;
    public $imagePath;
    public $sideMenu = [];
    public $systemCode = 'WMZ';

    public function beforeAction($action)
    {
        parent::beforeAction($action);

        if($this->action->id !== 'result' && (!$this->module->user || $this->module->user->isGuest))
            $this->redirect(\Yii::app()->createUrl(\Yii::app()->homeUrl));

        $this->breadcrumbs[\Yii::app()->createUrl('payment/payment/index')] = \Yii::t('payment', 'Пополнение счёта');
        $this->moduleName = $this->module->id;
        $this->path = $this->module->path;

        \Yii::app()->theme = $this->module->theme;

        $themePath = \Yii::app()->modulePath.DIRECTORY_SEPARATOR.$this->module->id.DIRECTORY_SEPARATOR.'themes';
        $viewsDir = $themePath.DIRECTORY_SEPARATOR.$this->module->theme.DIRECTORY_SEPARATOR.'views';
        $this->imagePath = \Yii::app()->theme->baseUrl.'/assets/images';

        if($this->module->theme == 'adloud')
            $this->sideMenu = $this->module->external->sideMenu;

        if(is_dir($viewsDir)){
            $this->module->viewPath = $viewsDir;
            $this->partials = 'application.modules.'.$this->module->id.'.themes.'.$this->module->theme.'.views._partials.';
        }else{
            $this->module->viewPath = $themePath.DIRECTORY_SEPARATOR.'default'.DIRECTORY_SEPARATOR.'views';
            $this->partials = 'application.modules.'.$this->module->id.'.themes.default.views._partials.';
        }

        return true;
    }

    public function actionIndex()
    {
        $this->pageName = \Yii::t('payment', 'Мой баланс');

        $model = new MoneyForm();
        $model->module = $this->module;

        $this->render('index', [
            'model' => $model,
        ]);
    }

    public function actionSelectPayment()
    {
        if(\Yii::app()->request->isAjaxRequest && isset($_POST['payment'])){
            $json = [
                'message' => '',
                'html' => '',
                'error' => '',
            ];

            if($this->module->initPaymentSystem($_POST['payment'])){
                if($form = $this->module->paymentSystem->form){
                    $json['html'] = $form;
                }else{
                    $json['error'] = $this->setFlash($this->module->paymentSystem->error, false);
                }
            }else{
                $json['error'] = $this->setFlash($this->module->error, false);
            }

            echo \CJSON::encode($json);
        }

        \Yii::app()->end();
    }

    public function actionEnterMoney()
    {
        if(\Yii::app()->request->isAjaxRequest && isset($_POST['paymentSystemId']) && isset($_POST['moneyAmount'])){
            $json = [
                'message' => '',
                'html' => '',
                'error' => '',
            ];

            $this->module->initPaymentSystem($_POST['paymentSystemId']);

            if($this->module->initPaymentSystem($_POST['paymentSystemId']) && $_POST['moneyAmount']){
                $money = floatval(\Yii::app()->request->getPost('moneyAmount'));

                if(!$money){
                    $json['error'] = \Yii::t('payment', 'Количество денег не указано');
                    echo \CJSON::encode($json);
                    \Yii::app()->end();
                }

                $rate = $this->module->paymentSystem->getCurrencyRates()->getRate($money, $this->module->paymentSystem->currencyCode);

                if($rate < $this->module->paymentSystem->moneyLimit){
                    $json['error'] =  \Yii::t('payment', 'Сумма пополнения счёта не может быть ниже, чем').' '.$this->module->paymentSystem->moneyLimit.' долларов';
                    echo \CJSON::encode($json);
                    \Yii::app()->end();
                }

                $this->module->paymentSystem->money = $money;

                if($fields = $this->module->paymentSystem->hiddenFields){
                    $json['html'] = $fields;
                    $this->module->paymentSystem->setSessionParams();
                }else{
                    $json['error'] = $this->setFlash($this->module->paymentSystem->error, false);
                }
            }else{
                $json['error'] = $this->setFlash($this->module->error, false);
            }

            echo \CJSON::encode($json);
        }

        \Yii::app()->end();
    }

    public function setFlash($error, $errorStart = '', $errorEnd = '')
    {
        if(is_array($error) && !empty($error)){
            $text = '';

            foreach($error as $errorText){
                if(is_array($errorText))
                    $text .= implode(', ', $errorText);
                else
                    $text .= $errorText.'; ';
            }

            $error = substr($text, 0, strlen($text)-2);
        }

        if(!is_string($error) || $error === '')
            return null;

        $flash = \Yii::t('payment', 'Произошла ошибка во время попытки пополнить счёт');

        if(YII_DEBUG)
            $flash .= ': '.$error;
        else
            $flash .= '. '.\Yii::t('payment', 'Пожалуйста, попробуйте позже');

        \Yii::app()->user->setFlash('error', $flash);

        return $flash;
    }

    public function getTheme()
    {
        if($this->_theme === null){
            if(\Yii::app()->theme === null){
                $this->_theme = new \stdClass();
                $this->_theme->basePath = \Yii::app()->modulePath;
            }
        }

        return $this->_theme;
    }

    public function checkPreRequest($userId = null)
    {
        if($userId === null){
            \Yii::app()->end(\Yii::t('payment', 'Неправильный запрос (отсутствует параметр "user ID")'));
        }

        $paymentSystem = $this->module->getPaymentSystem($this->systemCode);

        if(!$paymentSystem->checkUserId($userId)){
            \Yii::app()->end(\Yii::t('payment', 'Неправильный запрос (неверное значение параметра "user ID")'));
        }

        $params = $paymentSystem->getRequestParams();

        if(!isset($params['money'])){
            \Yii::app()->end(\Yii::t('payment', 'Неправильный запрос (отсутствует параметр "money")'));
        }
    }

    protected function pay($userId = null, $params = [])
    {
        if($userId === null)
            \Yii::app()->end();

        $paymentSystem = $this->module->getPaymentSystem($this->systemCode);

        if($paymentSystem->checkRequest($params)){
            $params = $paymentSystem->getRequestParams();

            $money = floatval($params['money']);

            $currencyCode = isset($params['currencyCode']) ? $params['currencyCode'] : $paymentSystem->currencyCode;
            $rate = $paymentSystem->getCurrencyRates()->getRate($money, $currencyCode);

            $params['userId'] = $userId;

            $paymentSystem->addMoneyToBalance($rate, $params);
        }

        \Yii::app()->end();
    }

    protected function checkResult($params = [])
    {
        $paymentSystem = $this->module->getPaymentSystem($this->systemCode);

        $errorTex1 = \Yii::t('payment', 'К сожалению не удалось пополнить счёт');
        $errorTex2 = \Yii::t('payment', 'Пожалуйста, обратитесь в нашу службу поддержки пользователей');

        $paymentSystem->setRequestParams($params);

        if($params = $paymentSystem->getRequestParams()){
            $sessionParams = $paymentSystem->getSessionParams();
            $money = isset($params['money']) ? $params['money'] : (isset($sessionParams['money']) ? $sessionParams['money'] : 0);
            $currencyCode = isset($params['currencyCode']) ? $params['currencyCode'] : $paymentSystem->currencyCode;
            $orderId = isset($params['orderId']) ? $params['orderId'] : null;

            if($money){
                $message = \Yii::t('payment', 'Вас счёт был успешно пополнен на').' '.$paymentSystem->currencyRates->getFormatted($money, $currencyCode);
                if($orderId !== null)
                    $message .= '. '.\Yii::t('payment', 'Номер заказа').': '.$orderId;
                \Yii::app()->user->setFlash('success', $message);
            }else{
                $this->setFlash($paymentSystem->error, $errorTex1, $errorTex2);
            }
        }else
           $this->setFlash($paymentSystem->error, $errorTex1, $errorTex2);

        $paymentSystem->unsetSessionParams();
        $this->goHome();
    }

    protected function goHome()
    {
        $paymentSystem = $this->module->getPaymentSystem($this->systemCode);
        $url = $paymentSystem->baseUrl;
        $host = \Yii::app()->request->hostInfo;


        if(strpos($host, 'https://') !== false)
            $host = str_replace('https://', 'http://', $host);

        $this->redirect($host.\Yii::app()->createUrl($url));
    }
}