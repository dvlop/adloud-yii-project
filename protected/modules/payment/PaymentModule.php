<?php
namespace application\modules\payment;

/**
 * Created by PhpStorm.
 * User: dima
 * Date: 24.04.14
 * Time: 10:22
 * @property string $error
 * @property \application\modules\payment\PaymentModule $paymentSystem
 * @property array $paymentSystems
 * @property string $currencyCode
 * @property string $currency
 * @property array $paymentNames
 * @property array $attributes
 * @property array $paymentUrls
 * @property array $currencies
 * @property array $sessionParams
 * @property string $form
 * @property string $hiddenFields
 * @property array $formFields
 * @property string $balance
 * @property string $orderId
 * @property \application\modules\payment\components\ExternalPart $external
 * @property \application\modules\payment\extensions\curl\Curl $curl
 * @property \application\modules\payment\extensions\currency\Currency $currencyRates
 * @property string $body
 * @property string $method
 * @property \WebUser $user
 * @property array $requestParams
 * @property string secretHash
 */
class PaymentModule extends \CWebModule
{
    public $controllerNamespace = '\application\modules\payment\controllers';

    const SESSION_NAME = 'userPaymentSession_';

    protected static $_paymentSystemsNames = [
        'WMZ' => 'WMZ',
        'WMR' => 'WMR',
        'yandexMoney' => 'Яндекс деньги',
        'qiwi' => 'QIWI',
        'creditCard' => 'Кредитная карта',
    ];

    protected static $_systemCurrencies = [
        'WMZ' => 'WMZ',
        'WMR' => 'WMR',
        'yandexMoney' => 'RU',
        'qiwi' => 'RU',
        'creditCard' => 'RU',
    ];

    protected static $_paymentNames = [
        'WMZ' => 'Webmoney WMZ',
        'WMR' => 'Webmoney WMR',
        'YAR' => 'Яндекс.Деньги',
        'CARD' => 'Кредитные карты',
        'QIWI' => 'QIWI',
    ];

    protected static $_methodNames = [
        'method_form' => 'form',
        'method_iframe' => 'iframe',
    ];

    public $accountNumber;
    public $systemCurrency;
    public $primaryKey;
    public $money;
    public $path = 'protected/modules';
    public $payments;
    public $creditCards;
    public $allowedCurrencies;
    public $serviceUrl;
    public $requestMethod = 'POST';
    public $externalClass = 'ExternalPart';
    public $serviceId;
    public $resultUrl;
    public $successUrl;
    public $failUrl;
    public $curlClass = 'curl';
    public $currencyRatesClass = 'currencyRates';
    public $addMoneyUrl;
    public $token;
    public $testMode = false;
    public $projectName;
    public $defaultMethod = 'method_form';
    public $clientSecret;
    public $theme = 'default';
    public $salt = 'ID3FUDF0099OEO98UI';
    public $sessionLiveTime = 3600;
    public $hashAlg = 'md5';
    public $toUpper = true;
    public $baseUrl = '/payment';
    public $moneyLimit;

    protected $_errors = [];
    protected $_paymentSystem;
    protected $_paymentUrls = [];
    protected $_currency;
    protected $_paymentSystems = [];
    protected $_external;
    protected $_curl;
    protected $_currencyRates;
    protected $_requestParams;
    protected $_secretHash;

    private $_additionalParams;

    public function init()
    {
        // this method is called when the module is being created
        // you may place code here to customize the module or the application

        // import the module-level models and components
        $this->setImport(array(
            //'payment.models.*',
            //'payment.components.*',
        ));

        if($this->moneyLimit === null)
            $this->moneyLimit = \Yii::app()->params['moneyInLimit'];
    }

    public function beforeControllerAction($controller, $action)
    {
        if(parent::beforeControllerAction($controller, $action))
        {
            // this method is called before any module controller action is performed
            // you may place customized code here
            return true;
        }
        else
            return false;
    }

    public function initPaymentSystem($systemName='')
    {
        return $this->getPaymentSystem($systemName);
    }

    public function setError($errorText='')
    {
        if(is_string($errorText) && $errorText !== '')
            $this->_errors[] = $errorText;
    }

    public function setCurrency($currencyName = '')
    {
        $this->_currency = $currencyName;
    }

    public function setAttributes($attributes = null)
    {
        if($attributes !== null){
            if(is_object($attributes)){
                if(isset($attributes->currency))
                    $this->currency = $attributes->currency;
                if(isset($attributes->creditCard))
                    $this->initCard($attributes->creditCard);
                if(isset($attributes->money))
                    $this->money = $attributes->money;
            }elseif(is_array($attributes)){
                if(isset($attributes['currency']))
                    $this->currency = $attributes['currency'];
                if(isset($attributes['creditCard']))
                    $this->initCard($attributes['creditCard']);
                if(isset($attributes['money']))
                    $this->money = $attributes['money'];
            }
        }
    }

    public function setRequestParams($params = [])
    {
        $this->_requestParams = [];

        if(!$params){
            $params = $this->requestMethod == 'POST' ? $_POST : $_GET;
        }

        foreach($this->getRequestParamsNames() as $name => $value){
            $this->_requestParams[$name] = isset($params[$value]) ? $params[$value] : null;
        }

        $this->setHash();
    }

    public function setSessionParams($money = null)
    {
        if($money === null)
            $money = $this->money;
        \Yii::app()->session[self::SESSION_NAME.$this->getUser()->getId()] = [
            'money' => $money,
        ];
    }

    public function getSessionParams()
    {
        return \Yii::app()->session[self::SESSION_NAME.$this->getUser()->getId()];
    }

    public function unsetSessionParams()
    {
        unset(\Yii::app()->session[self::SESSION_NAME.$this->getUser()->getId()]);
    }

    public function getRequestParamsNames()
    {
        return [];
    }

    public function getRequestParams()
    {
        if($this->_requestParams === null){
            $this->setRequestParams();
        }

        return $this->_requestParams;
    }

    /**
     * @param string $systemName
     * @return \application\modules\payment\PaymentModule
     */
    public function getPaymentSystem($systemName='')
    {
        if($this->_paymentSystem !== null){
            if(is_string($systemName) && $systemName !== '' && $systemName !== '1'){
                if($this->_paymentSystem->id == $systemName)
                    return $this->_paymentSystem;
            }else
                return $this->_paymentSystem;
        }

        if(!is_string($systemName) || !$systemName){
            if(!$this->paymentSystems){
                $this->error = 'Необходимо указать имя платёжной системы';
                return null;
            }

            $names = array_keys($this->paymentSystems);
            $systemName = array_shift($names);
        }

        $params = [];

        if(isset($this->payments[$systemName])){
            $params = $this->payments[$systemName];
        }elseif(!in_array($systemName, $this->payments)){
            foreach($this->payments as $name=>$payment){
                if(is_array($payment) && isset($payment['systemCurrency'])){
                    $currencyName = $payment['systemCurrency'];
                    $params = $payment;

                    if(is_array($currencyName)){
                        foreach($currencyName as $curName){
                            if($systemName == $curName){
                                $systemName = $name;
                                break;
                            }
                        }
                    }else{
                        if($systemName == $currencyName){
                            $systemName = $name;
                            break;
                        }
                    }
                }else{
                    $names = array_keys($this->payments);
                    $systemName = array_shift($names);
                    $params = $payment;
                    break;
                }
            }
        }

        if(!isset($this->payments[$systemName])){
            $names = array_keys($this->payments);
            $systemName = array_shift($names);
            $params = $this->payments[$systemName];
        }

        $className = ucfirst($systemName);
        $systemFile = $this->basePath.DIRECTORY_SEPARATOR.'components'.DIRECTORY_SEPARATOR.$className.'.php';

        if(!file_exists($systemFile)){
            $this->error = 'Не удалось найти файл '.$systemFile;
            return null;
        }

        $className = '\\application\\modules\\payment\\components\\'.$className;

        $this->_paymentSystem = new $className($systemName, $this, $params);
        $this->_paymentSystem->_currency = isset(self::$_systemCurrencies[$systemName]) ? self::$_systemCurrencies[$systemName] : $systemName;
        $this->_paymentSystem->theme = $this->theme;

        return $this->_paymentSystem;
    }

    public function getPaymentSystems()
    {
        if(!empty($this->_paymentSystems))
            return $this->_paymentSystems;

        foreach($this->payments as $name=>$system){
            if(is_array($system)){
                $systemName = $name;
                $attributes = $system;
            }else{
                $systemName =  $system;
                $attributes = [];
            }

            if(isset($attributes['systemCurrency'])){
                $currency = $attributes['systemCurrency'];

                if(is_array($currency)){
                    foreach($currency as $currencyName){
                        if(isset(self::$_paymentNames[$currencyName]))
                            $this->_paymentSystems[$currencyName] = self::$_paymentNames[$currencyName];
                        else
                            $this->_paymentSystems[$currencyName] = $currencyName;
                    }
                }else{
                    if(isset(self::$_paymentNames[$currency]))
                        $this->_paymentSystems[$currency] = self::$_paymentNames[$currency];
                    else
                        $this->_paymentSystems[$currency] = $currency;
                }
            }else{
                if(isset(self::$_paymentNames[$systemName]))
                    $this->_paymentSystems[$systemName] = self::$_paymentNames[$systemName];
                else
                    $this->_paymentSystems[$systemName] = $systemName;
            }
        }

        return $this->_paymentSystems;
    }

    public function getPaymentNames()
    {
        return self::$_paymentSystemsNames;
    }

    /**
     * @return string
     */
    public function getCurrency()
    {
        if($this->_currency === null){
            $currencies = self::$_paymentNames;
            return $this->_currency = array_shift($currencies);
        }

        if(isset(self::$_paymentNames[$this->_currency]))
            return self::$_paymentNames[$this->_currency];
        else{
            $currencies = array_keys(self::$_paymentNames);
            $this->_currency = self::$_paymentNames[array_shift($currencies)];
        }

        return $this->_currency;
    }

    /**
     * @return string
     */
    public function getCurrencyCode()
    {
        if($this->_currency === null || !isset(self::$_paymentNames[$this->_currency])){
            $names = array_keys(self::$_paymentNames);
            $this->_currency = array_shift($names);
        }

        return $this->_currency;
    }

    /**
     * @return array
     */
    public function getAttributes()
    {
        if(get_class($this) == 'PaymentModule')
            $paymentSystemId = $this->paymentSystem->id;
        else
            $paymentSystemId = $this->id;

        return array(
            'currency' => $this->currency,
            'paymentSystem' => $this->paymentSystem,
            'card' => $paymentSystemId == $this->cardId ? $this->card : null,
            'money' => $this->money,
            'serviceUrl' => $this->serviceUrl,
            'requestMethod' => $this->requestMethod,
        );
    }

    public function getMethod($methodName = '')
    {
        if(!is_string($methodName) || $methodName === '')
            $methodName = $this->defaultMethod;

        if(isset(self::$_methodNames[$methodName]))
            return self::$_methodNames[$methodName];
        else
            return self::$_methodNames['method_form'];
    }

    public function getForm()
    {
        $method = $this->method;

        if(method_exists($this, $method)){
            return $this->$method();
        }else{
            $this->error = 'В классе "'.get_class($this).'" не существует метода "'.$method.'"';
            return false;
        }
    }

    public function getHiddenFields()
    {
        return array();
    }

    public function getFormFields()
    {
        return array(
            'paymentSystemId' => $this->id,
        );
    }

    public function getCurl()
    {
        if($this->_curl !== null)
            return $this->_curl;

        if(strpos($this->curlClass, '.') !== false || strpos($this->curlClass, '/') !== false){
            $curlFile = \Yii::getPathOfAlias($this->curlClass).'.php';

            $this->_curl = $this->external->loadClassFromFile($curlFile, $this->curlClass);
        }elseif(isset(\Yii::app()->curl) && \Yii::app()->curl !== null){
            $this->_curl = \Yii::app()->curl;
        }else{
            $curlFile = $this->basePath.DIRECTORY_SEPARATOR.'extensions'.DIRECTORY_SEPARATOR.$this->curlClass.DIRECTORY_SEPARATOR.ucfirst($this->curlClass).'.php';
            $this->_curl = $this->external->loadClassFromFile($curlFile, $this->curlClass);
        }

        return $this->_curl;
    }

    /**
     * @return extensions\currency\Currency|bool
     */
    public function getCurrencyRates()
    {
        if($this->_currencyRates !== null)
            return $this->_currencyRates;

        if(strpos($this->currencyRatesClass, '.') !== false || strpos($this->currencyRatesClass, '/') !== false){
            $currencyRatesFile = \Yii::getPathOfAlias($this->currencyRatesClass).'.php';

            $this->_currencyRates = $this->external->loadClassFromFile($currencyRatesFile, $this->currencyRatesClass);
        }elseif(isset(\Yii::app()->currency) && \Yii::app()->currency !== null){
            $this->_currencyRates = \Yii::app()->currency;
        }else{
            $currencyRatesFile = $this->basePath.DIRECTORY_SEPARATOR.'extensions'.DIRECTORY_SEPARATOR.$this->currencyRatesClass.DIRECTORY_SEPARATOR.ucfirst($this->currencyRatesClass).'.php';
            $this->_currencyRates = $this->external->loadClassFromFile($currencyRatesFile, $this->currencyRatesClass);
        }

        return $this->_currencyRates;
    }

    public function getError()
    {
        return implode('; ', $this->_errors);
    }

    public function getUser()
    {
        return $this->getExternal()->getUser();
    }

    public function getBalance()
    {
        if(!$this->external){
            $this->error = 'Не удалось проверить бланс пользователя';
            return false;
        }

        $balance = $this->external->balance;

        if($balance === false && $this->external->error){
            $this->error = $this->external->error;
            return false;
        }

        return $balance ? $balance : 0;
    }

    public function getOrderId()
    {
        if(!$this->external){
            $this->error = 'Не удалось получить номер заказа';
            return false;
        }

        if($this->money && !$this->external->money)
            $this->external->money = $this->money;

        if($orderId = $this->external->orderId){
            return $orderId;
        }else{
            $this->error = $this->external->error;
            return false;
        }
    }

    public function getHashString($email = null)
    {
        if($email === null)
            $email = $this->external->user->email;

        return crypt($email, $this->salt);
    }

    public function getSecretHash()
    {
        return $this->_secretHash;
    }

    public function createResultUrl()
    {
        if($this->resultUrl)
            $resultUrl = $this->resultUrl;
        elseif(isset($this->parentModule->resultUrl) && $this->parentModule->resultUrl)
            $resultUrl = $this->parentModule->resultUrl;
        else
            $resultUrl = $this->parentModule->id.'/'.strtolower($this->id).'/result';

        $host = \Yii::app()->request->hostInfo;
        if(strpos($host, 'https://') === false)
            $host = str_replace('http://', 'https://', $host);

        return $host.\Yii::app()->createUrl($resultUrl, $this->getUrlAdditionalParams());
    }

    public function createSuccessUrl()
    {
        if($this->successUrl)
            $successUrl = $this->successUrl;
        elseif(isset($this->parentModule->successUrl) && $this->parentModule->successUrl)
            $successUrl = $this->parentModule->successUrl;
        else
            $successUrl = $this->parentModule->id.'/'.lcfirst($this->id).'/success';

        return \Yii::app()->createAbsoluteUrl($successUrl, $this->getUrlAdditionalParams());
    }

    public function createFailUrl()
    {
        if($this->failUrl)
            $failUrl = $this->failUrl;
        elseif(isset($this->parentModule->failUrl) && $this->parentModule->failUrl)
            $failUrl = $this->parentModule->failUrl;
        else
            $failUrl = $this->parentModule->id.'/'.lcfirst($this->id).'/fail';

        return \Yii::app()->createAbsoluteUrl($failUrl);
    }

    public function setSecretHash($string)
    {
        $this->_secretHash = hash($this->hashAlg, $string);
        if($this->toUpper)
            $this->_secretHash = strtoupper($this->_secretHash);
    }

    public function addMoneyToBalance($money = 0, $params = [])
    {
        if(!$this->getExternal()){
            $this->error = 'Не удалось зачислить деньги на баланс';
            return false;
        }

        if($this->money && !$this->getExternal()->money)
            $this->getExternal()->money = $this->money;

        if(!$this->getExternal()->addMoneyToBalance($money, $params)){
            $this->error = $this->getExternal()->error;
            return false;
        }

        return true;
    }

    public function checkRequest($params = [])
    {
        if($params)
            $this->setRequestParams($params);

        return $this->_requestParams ? true : false;
    }

    public function checkSeqKey($key, $orderId)
    {
        $sessionId = $this->getUserSessionId($orderId);
        return $this->external->sessionGet($sessionId) == $key;
    }

    public function checkUserId($id)
    {
        return $this->getExternal()->checkUserId($id);
    }

    /**
     * @return \application\modules\payment\components\ExternalPart
     */
    protected function getExternal()
    {
        if($this->_external === null){
            $externalClassName = $this->externalClass;

            $externalFile = $this->basePath.DIRECTORY_SEPARATOR.$externalClassName.'.php';

            if(!file_exists($externalFile))
                $externalFile = $this->basePath.DIRECTORY_SEPARATOR.'components'.DIRECTORY_SEPARATOR.$externalClassName.'.php';

            if(file_exists($externalFile)){
                require_once($externalFile);

                if(class_exists('\\application\\modules\\payment\\components\\'.$externalClassName)){
                    $externalClassName = '\\application\\modules\\payment\\components\\'.$externalClassName;
                    $this->_external = new $externalClassName();
                }else{
                    if(class_exists($externalClassName)){
                        $this->_external = new $externalClassName();
                    }else{
                        $this->error = 'Не удалось загрузить класс "'.$externalClassName.'"';
                        return false;
                    }
                }
            }else{
                $this->error = 'Не удалось найти файл '.$externalFile;
                return false;
            }
        }

        return $this->_external;
    }

    protected function getUrlAdditionalParams()
    {
        if($this->_additionalParams === null){
            $this->_additionalParams = [
                'userId' => $this->getExternal()->getUser()->getId(),
            ];
        }

        return $this->_additionalParams;
    }


}