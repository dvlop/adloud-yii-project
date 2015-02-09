<?php
/**
 * Controller is the customized base controller class.
 * All controller classes for this application should extend from this base class.
 * @property FormModel $model
 * @property string $contactsString
 */

namespace application\components;

use application\models\Notification;

class ControllerBase extends \CController
{
	/**
	 * @var string the default layout for the controller view. Defaults to '//layouts/column1',
	 * meaning using a single column layout. See 'protected/views/layouts/column1.php'.
	 */
	public $layout = '//layouts/main';

	/**
	 * @var array context menu items. This property will be assigned to {@link CMenu::items}.
	 */
	public $menu = [];
	/**
	 * @var array the breadcrumbs of the current page. The value of this property will
	 * be assigned to {@link CBreadcrumbs::links}. Please refer to {@link CBreadcrumbs::links}
	 * for more details on how to specify this property.
	 */

    public $defaultAction = 'index';

    public $breadcrumbs = [];
	public $sideMenu = [];
    public $topButtons = [];
    public $scripts = [];
    public $scriptFiles = [];
    public $cssFiles = [];
    public $alterPageName = [];
    public $navMenu = array(
        [
            'url' => 'index/index',
            'name' => 'Вебмастеру',
        ],
        [
            'url' => 'landing/forAdvertisers',
            'name' => 'Рекламодателю',
        ],
        [
            'url' => 'http://blog.adloud.net',
            'name' => 'Блог',
        ],
        [
            'url' => 'index/contacts',
            'name' => 'Контакты',
        ],
        /*[
            'url' => '/index/privacy',
            'name' => 'Политика конфиденциальности',
        ],
        [
            'url' => '/index/about',
            'name' => 'О компании',
        ],
         [
            'url' => '/index/blog',
            'name' => 'Блог',
        ],*/
    );
    public $contactsNavMenu = [
        [
            'url' => 'index/index',
            'name' => 'Вебмастеру',
            'class' => 'nav-link',
            'liClass' => 'nav-item'
        ],
        [
            'url' => 'landing/forAdvertisers',
            'name' => 'Рекламодателю',
            'class' => 'nav-link',
            'liClass' => 'nav-item'
        ],
        [
            'url' => 'http://blog.adloud.net',
            'name' => 'Блог',
            'class' => 'nav-link',
            'liClass' => 'nav-item'
        ],
        [
            'url' => 'index/contacts',
            'name' => 'Контакты',
            'class' => 'nav-link',
            'liClass' => 'nav-item'
        ],
    ];
    public $bottomMenu = [
        [
            'url' => 'index/index',
            'name' => 'Главная',
            'class' => 'nav-link',
            'liClass' => 'footer-nav-item',
            'aClass' => 'footer-nav-link',
        ],
        [
            'url' => 'landing/forAdvertisers',
            'name' => 'Рекламодателю',
            'class' => 'nav-link',
            'liClass' => 'footer-nav-item',
            'aClass' => 'footer-nav-link',
        ],
        [
            'url' => 'http://blog.adloud.net',
            'name' => 'Блог',
            'class' => 'nav-link',
            'liClass' => 'footer-nav-item',
            'aClass' => 'footer-nav-link',
        ],
        [
            'url' => 'index/contacts',
            'name' => 'Контакты',
            'class' => 'nav-link',
            'liClass' => 'footer-nav-item',
            'aClass' => 'footer-nav-link',
        ],
    ];

    public $model;

    public $title;
    public $pageName = '';
    public $themePathAlias = '';
    public $viewsPathAlias = '';
    public $layoutPathAlias = '';
    public $partialPathAlias = '';
    public $widgetPathAlias = '';
    public $bulkOperations = array();

    public $modalContent = array();


//    public function filters()
//    {
//        return array(
//            'accessControl',
//        );
//    }
//
//    public function accessRules()
//    {
//        return array(
//            array('deny',
//                'users'=>array('?'),
//            ),
//        );
//    }

    public function createMultilanguageReturnUrl($lang = null){
        if($lang === null)
            $lang = \Yii::app()->user->getLanguage();

        if(count($_GET) > 0){
            $arr = $_GET;
            $arr['language'] = $lang;
        }else{
            $arr = ['language' => $lang];
        }

        return $this->createUrl('', $arr);
    }

    public function init()
    {
        if(isset($_GET['language'])){
            \Yii::app()->language = htmlspecialchars($_GET['language']);
        }

        $this->breadcrumbs[\Yii::app()->createUrl('index/index')] = \Yii::t('main', 'Главная');
        $this->title = \Yii::app()->name;
        \Yii::app()->theme = 'adloud'; // bootstrap/adloud

        $this->themePathAlias = 'themes.'.\Yii::app()->theme->name.'.';
        $this->viewsPathAlias = $this->themePathAlias.'views.';
        $this->layoutPathAlias = $this->viewsPathAlias.'layouts.';
        $this->partialPathAlias = $this->viewsPathAlias.'partials.';
        $this->widgetPathAlias = $this->themePathAlias.'widgets.';

        \Yii::app()->viewPath = \Yii::app()->theme->basePath.DIRECTORY_SEPARATOR.'views';

        foreach($this->navMenu as $num => $element){
            if(isset($element['name']))
                $this->navMenu[$num]['name'] = \Yii::t('landing', $element['name']);
        }

        foreach($this->contactsNavMenu as $num => $element){
            if(isset($element['name']))
                $this->contactsNavMenu[$num]['name'] = \Yii::t('landing', $element['name']);
        }

        foreach($this->bottomMenu as $num => $element){
            if(isset($element['name']))
                $this->bottomMenu[$num]['name'] = \Yii::t('landing', $element['name']);
        }
    }

    public function beforeAction($action)
    {
        parent::beforeAction($action);

        if(!isset($_GET['language']) && (!$this->module || $this->module->id != 'payment')){
            $this->redirect($this->createMultilanguageReturnUrl());
        }

        $elements = \Yii::app()->pageElements->all;

        if(isset($elements[$action->id])){

            if(isset($elements[$action->id]['topButtons'])){
                foreach($elements[$action->id]['topButtons'] as $num => $elem){
                    if(isset($elem['elements'])){
                        foreach($elem['elements'] as $elemNum => $element){
                            if(isset($element['name']))
                                $elements[$action->id]['topButtons'][$num]['elements'][$elemNum]['name'] = \Yii::t('campaigns', $element['name']);
                        }
                    }
                }
            }

            if(isset($elements[$action->id]['bulkOperations'])){
                foreach($elements[$action->id]['topButtons'] as $num => $elem){
                    if(isset($element['name']))
                        $elements[$action->id]['topButtons'][$num]['name'] = \Yii::t('campaigns', $element['name']);
                }
            }

            foreach($elements[$action->id] as $name=>$element){
                switch($name){
                    case 'pageName':
                        $this->pageName = $element;
                        break;
                    case 'scriptFiles':
                        if(is_array($element))
                            $this->scriptFiles = array_merge($this->scriptFiles, $element);
                        else
                            $this->scriptFiles[] = $element;
                        break;
                    case 'cssFiles':
                        if(is_array($element))
                            $this->cssFiles = array_merge($this->cssFiles, $element);
                        else
                            $this->cssFiles[] = $element;
                        break;
                    case 'breadcrumbs':
                        $this->breadcrumbs[\Yii::app()->createUrl($this->id.'/'.$action->id)] = $element;
                        break;
                    default:
                        $this->$name = $element;
                }
            }
        }

        return true;
    }

    public function partial($view, $data=null, $return=false, $processOutput=false)
    {
        if(strpos($view, '.') === false)
            $view = $this->partialPathAlias.$view;

        return parent::renderPartial($view, $data, $return, $processOutput);
    }

    public function renderWidget($className, $properties=array(), $captureOutput=false)
    {
        if(strpos($className, '.') === false)
            $className = $this->widgetPathAlias.$className;

        return parent::widget($className, $properties, $captureOutput);
    }

    public function getContactsString()
    {
        return \Yii::app()->params['contacts'];
    }

    protected function setFlash($error, $errorStart = '', $errorEnd = '')
    {
        \Yii::app()->user->setFlash('error', $this->parseError($error, $errorStart, $errorEnd));
    }

    protected function parseError($error, $errorStart = '', $errorEnd = '', $show = false)
    {
        $modelError = '';

        if(is_array($error)){
            foreach($error as $text){
                if(is_array($text)){
                    $modelError .= implode('; ', $text);
                }else{
                    $modelError .= $text;
                }
            }
        }else{
            $modelError = (string)$error;
        }

        $errorText = $errorStart;
        if(!$show){
            if(YII_DEBUG){
                $errorText .= $errorText ? ': '.$modelError : $modelError;
            }else{
                $errorText .= $errorText ? '. '.$errorEnd : $errorEnd;
            }
        }else{
            $errorText .= $errorText ? ': '.$modelError : $modelError;
        }

        return $this->parseString($errorText);
    }

    private function parseString($string)
    {
        $string = trim(htmlspecialchars(str_replace('\'', '', $string)));
        $errorStrings = explode(': ', $string);
        $string = \Yii::t('errors', $errorStrings[0]);

        if(isset($errorStrings[1]))
            $string .= ': '.\Yii::t('errors', $errorStrings[1]);

        return $string;
    }

    public function sendNotification($userId, $type, $text = 'Системное сообщение'){
        if(Notification::getTypeData()[$type]['text'])
            $text = Notification::getTypeData()[$type]['text'];

        return Notification::create([
            'user_id' => $userId,
            'is_new' => true,
            'type' => $type,
            'text' => $text
        ]);
    }
}