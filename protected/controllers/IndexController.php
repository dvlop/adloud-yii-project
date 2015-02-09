<?php

use application\components\ControllerBase;

class IndexController extends ControllerBase
{
    public function actionIndex()
    {
        if(!\Yii::app()->user->isGuest)
            $this->redirect(\Yii::app()->createUrl('webmaster/site/list'));

        $this->layout = '//layouts/landing';

        $ref = \Yii::app()->request->getQuery('ref');

        if($ref) {
            setcookie('referer',$ref,time()+3600*24*30);
        }

        $this->render('index');
    }

    public function actionRegister() {
        if(!\Yii::app()->user->isGuest)
            $this->redirect(\Yii::app()->user->accountUrl);

        $this->layout = '//layouts/thankyou';

        $this->render('register', ['model' => $this->model]);
    }

    public function actionRecovery() {
        if(!\Yii::app()->user->isGuest)
            $this->redirect(\Yii::app()->user->accountUrl);

        $this->layout = '//layouts/thankyou';

        $this->render('recovery', ['model' => $this->model]);
    }

    public function actionAuth() {
        if(!\Yii::app()->user->isGuest)
            $this->redirect(\Yii::app()->user->accountUrl);

        $this->layout = '//layouts/thankyou';

        $this->render('auth', ['model' => $this->model]);
    }

    public function actionSendPass() {
        if(!\Yii::app()->user->isGuest)
            $this->redirect(\Yii::app()->user->accountUrl);

        $this->layout = '//layouts/thankyou';

        $this->render('sendpass', ['model' => $this->model]);
    }

    public function actionLogout()
    {
        \Yii::app()->user->logout();
        $this->redirect(\Yii::app()->createUrl('index/index'));
    }

    public function actionLogin()
    {
        if(\Yii::app()->request->isAjaxRequest && isset($_POST['UserLoginForm']) && \Yii::app()->user->isGuest){
            $json = [
                'message' => '',
                'error' => '',
                'html' => '',
                'url' => '#',
            ];

            $model = new UserLoginForm();

            $model->setAttributes($_POST['UserLoginForm']);
            $this->model = $model;

            if($model->validate() && $model->authenticate()){
                \Yii::app()->user->setFlash('success', 'Здравствуйте, '.\Yii::app()->user->helloName.'!');
                $json['url'] = \Yii::app()->createUrl('webmaster/site/list', ['language' => Yii::app()->user->getLanguage()]);
            }else{
                $json['error'] = $this->parseError($model->errors, 'К сожалению не удалось выполнить вход в аккаунт', 'Попробуйте позже');
            }

            echo \CJSON::encode($json);
            \Yii::app()->end();
        }

        $this->redirect(\Yii::app()->createUrl('index/index'));
    }

    public function actionRegistration()
    {
        if(\Yii::app()->request->isAjaxRequest && isset($_POST['UserRegistrationForm'])){
            $json = [
                'message' => '',
                'error' => '',
                'html' => '',
                'url' => '#',
            ];

            $model = new UserRegistrationForm();
            $model->setAttributes($_POST['UserRegistrationForm']);

            if(!$model->password2)
                $model->password2 = $model->password;

            if($model->validate()){
                if($id = $model->registration()){
                    $json['message'] = 'Ваш аккаунт успешно зарегистрирован. Приветсвуем Вас!';
                    if(Yii::app()->user->noPasswordLogin($model->email)){
                        $json['url'] = Yii::app()->createUrl('account/thankyou');
                    }else{
                        $json['error'] = $this->parseError(Yii::app()->user->error, 'Не удалось войти в систему, попробуйте войти самостоятельно, используя Ваш логин и пароль');
                        $json['url'] = Yii::app()->createUrl(Yii::app()->user->loginUrl);
                    }
                }else{
                    $json['error'] = $this->parseError($model->errors, 'Не удалось зарегистрировать аккаунт', 'Возможно, аккаунт с таким e-mail уже зарегистрирован в системе');
                }
            }else{
                $json['error'] = $this->parseError($model->errors, 'Не удалось зарегистрировать аккаунт', 'Возможно, аккаунт с таким e-mail уже зарегистрирован в системе');
            }

            echo \CJSON::encode($json);
            \Yii::app()->end();
        }

        $this->redirect(\Yii::app()->createUrl('index/index'));
    }

    public function actionRestorePassword()
    {
        $this->pageName = 'Установите новый пароль';
        $this->layout = '//layouts/blackBackground';

        $confirmCode    = \Yii::app()->request->getParam('confirm');
        $model          = new UserRestorePasswordForm();

        if($confirmCode === null){
            if(\Yii::app()->request->isAjaxRequest && isset($_POST['UserRestorePasswordForm'])){
                $json = [
                    'message' => '',
                    'error' => '',
                    'html' => '',
                    'url' => '/sendpass',
                ];

                $model->setAttributes($_POST['UserRestorePasswordForm']);

                if($model->validate() && $model->sendEmail()){
                    $json['message'] = 'Письмо с инструкциями по восстановлению пароля отправлено Вам на почу';
                }else{
                    $json['error'] = $this->parseError($model->errors, 'Не удалось восстановить пароль.', 'Попробуйте позже');
                    //$json['error'] = $model->errors;
                }

                echo \CJSON::encode($json);
                \Yii::app()->end();
            }
        }
        elseif($model->email = \Yii::app()->request->getParam('email')){
            if($confirmCode == hash(\Yii::app()->params['hashAlgo'], $model->email.\Yii::app()->params['salt'])){
                if(!$user = $model->initUserByEmail()){
                    \Yii::app()->user->setFlash('error', 'Неправильная ссылка');
                    $this->redirect(\Yii::app()->createAbsoluteUrl('/'));
                }

                $model = new UserSetNewPasswordForm();
                $model->email = $user->email;

                return $this->render('setNewPassword', ['model' => $model]);
            }else{
                \Yii::app()->user->setFlash('error', 'Неправильная ссылка');
                $this->redirect(\Yii::app()->createAbsoluteUrl('/'));
            }
        }
        else{
            \Yii::app()->user->setFlash('error', 'Неправильная ссылка');
            $this->redirect(\Yii::app()->createAbsoluteUrl('/'));
        }

        $this->redirect(\Yii::app()->createUrl('index/index'));
    }

    public function actionSetNewPassword()
    {
        $this->pageName = 'Восстановление пароля';
        $this->layout = '//layouts/blackBackground';

        $this->model = new UserSetNewPasswordForm();

        if(\Yii::app()->request->isPostRequest && isset($_POST['UserSetNewPasswordForm'])){
            $this->model->setAttributes($_POST['UserSetNewPasswordForm']);

            if($this->model->validate()){
                if($this->model->setNewPassword()){
                    $loginModel = new UserLoginForm();

                    $loginModel->email = $this->model->email;
                    $loginModel->password = $this->model->password;

                    $this->model = $loginModel;

                    if($loginModel->authenticate()){
                        \Yii::app()->user->setFlash('success', 'Пароль успешно изменён');
                        $this->redirect(\Yii::app()->createUrl(\Yii::app()->user->accountUrl));
                    }else{
                        \Yii::app()->user->setFlash('error', 'Пароль был изменён, но, к сожалению, не удалось войти в систему. Воспольуйтесь формой входа.');
                        $this->redirect(\Yii::app()->createUrl('index/index'));
                    }
                }
            }
        }

        return $this->render('setNewPassword', ['model' => $this->model]);
    }

    public function actionContacts()
    {
        $this->layout = '//layouts/contacts';
        $this->render('contacts');
    }

    public function ajaxTest($key)
    {
        if($key != Yii::app()->params['secretKey'])
            \Yii::app()->end('Fuck off!');
        if(!Yii::app()->user->isAdmin)
            \Yii::app()->end('Fuck off!');

        if(\Yii::app()->request->isAjaxRequest && isset($_POST['value'])){
            $json = [
                'message' => '',
                'error' => '',
                'html' => '',
            ];

            try{

                $json['message'] = '';
            }catch(Exception $e){
                $json['error'] = $this->parseError($e->getMessage(), '', '');
            }

            echo \CJSON::encode($json);
        }

        \Yii::app()->end();
    }

    public function actionSetRates($key)
    {
        if($key != Yii::app()->params['secretKey'])
            \Yii::app()->end('Fuck off!');
        if(!Yii::app()->user->isAdmin)
            \Yii::app()->end('Fuck off!');

        $categories = \models\Category::getInstance()->find(['id']);
        $ads = \models\Ads::getInstance()->find(['click_price', 'categories']);

        $catsRates = [];


        foreach($categories as $catId){
            $minPrice = 100000;
            $maxPrice = 0;
            foreach($ads as $ad){
                $cats = $ad->getAdsCategories();
                $cats = str_replace('{', '', $cats);
                $cats = str_replace('}', '', $cats);
                $cats = explode(',', $cats);

                if(in_array($catId->id, $cats)){
                    if($ad->click_price < $minPrice)
                        $minPrice = $ad->click_price;
                    if($ad->click_price > $maxPrice)
                        $maxPrice = $ad->click_price;
                }
            }
            if($minPrice !== 100000 && $maxPrice !== 0){
                $catsRates[] = (object)[
                    'id' => $catId->id,
                    'min' => $minPrice,
                    'max' => $maxPrice
                ];
            }
        }

        foreach($catsRates as $cat){
            \core\RedisIO::set("min-efficiency-rate:{$cat->id}", $cat->min);
            \core\RedisIO::set("max-efficiency-rate:{$cat->id}", $cat->max);
        }

        echo '<center><h3>Рейтинги для всех категорий обновлены!</h3></center>';
        Yii::app()->end();
    }

    public function actionSetAnimation($key)
    {
        if($key != Yii::app()->params['secretKey'])
            \Yii::app()->end('Fuck off!');

        if(!Yii::app()->user->isAdmin)
            \Yii::app()->end('Fuck off!');

        $animations = [];
        $noAnimations = [];

        $ads = \models\Ads::getInstance()->getAll();

        foreach($ads as $ad){
            $content = \CJSON::decode($ad->content);
            $img = $content['imageUrl']['image'];
            if(strpos($img, '.gif'))
                $animations[] = $ad->id;
            else
                $noAnimations[] = $ad->id;
        }

        try{
            \models\Ads::getInstance()->setAnimations($animations, true);
            \models\Ads::getInstance()->setAnimations($noAnimations, false);

            $blocks = \models\Block::getInstance()->getAllUsersBlock();

            foreach($blocks as $block){
                $categories = str_replace('{', '', str_replace('}', '', $block['categories']));
                $categories = explode(',', $categories);

                $siteIdString = "site:{$block['siteId']}";
                $blockIdString = "block:{$block['id']}";

                \core\RedisIO::set($siteIdString, 1);
                \core\RedisIO::set($blockIdString, serialize([
                    'categories' => $categories,
                    'id' => $block['id'],
                    'siteId' => $block['siteId'],
                    'userId' => $block['userId'],
                    'allowShock' => $block['allowShock'],
                    'allowAdult' => $block['allowAdult'],
                    'allowSms' => $block['allowSms'],
                    'allowAnimation' => $block['allowAnimation'],
                ]));
            }
        }catch(Exception $e){
            echo 'Не удалось установить метку для анимированныйх изображений.<br />'.$e->getMessage();
        }

        echo '<center><h3>Метки для анимированных изображений успешно установлены!</h3></center>';
        Yii::app()->end();
    }

    public function actionRefRashBlocks($key)
    {
        if($key != Yii::app()->params['secretKey'])
            \Yii::app()->end('Fuck off!');

        if(!Yii::app()->user->isAdmin)
            \Yii::app()->end('Fuck off!');

        $errors = [];

        foreach(\application\models\Blocks::model()->findAll() as $block){
            try{
                \models\Publisher::getInstance()->unPublishBlock($block->id);
                \models\Publisher::getInstance()->publishBlock($block->id, null, true);
            }catch(Exception $e){
                $errors[] = $e->getMessage().' id: '.$block->id.', siteId: '.$block->site_id;
            }
        }

        if($errors)
            Yii::app()->test->show($errors);
        else
            echo '<center><h3>Блоки успешно переопубликованы!</h3></center>';
        Yii::app()->end();
    }

    public function actionAddMoney($key, $money = null)
    {
        if(Yii::app()->user->isGuest)
            \Yii::app()->end('Fuck off!');
        if($key != Yii::app()->params['secretKey'])
            \Yii::app()->end('Fuck off!');

        $money = $money ? $money : 1523;
        $money = rand(1, 10)*$money + rand(1, 10)/5;
        Yii::app()->user->model->addMoneyBalance($money, 'Test money');

        echo "<center><h3>Счёт был пополнен на {$money} $</h3></center>";
        Yii::app()->end();
    }

    public function actionCheckBalance($key)
    {
        if(Yii::app()->user->isGuest)
            \Yii::app()->end('Fuck off!');
        if($key != Yii::app()->params['secretKey'])
            \Yii::app()->end('Fuck off!');

        $money = Yii::app()->user->getFullBalance();

        echo "<center><h3>Баланс юзера: {$money} денег</h3></center>";
    }

    public function actionNotificationList(){
        if(Yii::app()->request->isAjaxRequest && isset($_POST['getlist'])){
            $notifications = \application\models\Notification::model()->findAllByAttributes(['user_id' => Yii::app()->getUser()->getId()],['order' => 'date desc', 'limit' => 10]);

            $result = "";

            foreach($notifications as $msg){
                $date = new DateTime($msg->date);
                $time = $date->format('H:i:s');
                $icon = $this->getMessageTypeData($msg->type)['icon'];
                $text = $this->getMessageTypeData($msg->type)['text'] ? $this->getMessageTypeData($msg->type)['text'] : $msg->text;
                $isNew = $msg->is_new ? 'new-notice' : '';

                $result .= "<li class='notifications-item {$isNew}'>";
                $result .= "<time class='notifications-time'>{$time}</time>";
                $result .= "<div class='notifications-content'>";
                $result .= "<div class='notifications-icon'>";
                $result .= "<img src='{$icon}'/>";
                $result .= "</div>";
                $result .= "<div class='notifications-text'>{$text}</div>";
                $result .= "</div>";
                $result .= "</li>";

                $msg->is_new = false;
                $msg->is_shown = true;
                $msg->update(['is_new','is_shown']);
            }

            echo $result;
        }
    }

    public function actionGetNotification(){
        if(Yii::app()->request->isAjaxRequest && isset($_POST['listen'])){
            $notifications = \application\models\Notification::model()->findAllByAttributes([
                'user_id' => Yii::app()->getUser()->getId()
            ],[
                'order' => 'date desc'
            ]);

            $tickets = \application\models\Message::model()->findAllBySql('
            SELECT message.id
            FROM message
            INNER JOIN ticket ON (ticket.id = message.ticket_id)
            WHERE
            message.is_admin = TRUE AND
            message.status = 1 AND
            ticket.user_id = :user_id',
            [':user_id' => Yii::app()->getUser()->getId()]);

            $result = [];
            $notificationList = [];

            $this->setBalanceNotification('low-balance', $notifications);
            $this->setBalanceNotification('null-balance', $notifications);

            foreach($notifications as $msg){
                if($msg->is_new && !$msg->is_shown)
                    $notificationList[] = $msg;
            }

            if($notificationList){
                $result = array_merge($result,[
                    'count' => count($notificationList),
                    'message' => $notificationList[0]->text,
                    'icon' => $this->getMessageTypeData($notificationList[0]->type)['icon']
                ]);
            }
            if($tickets){
                $result = array_merge($result,[
                    'tickets' => count($tickets),
                ]);
            }

            echo json_encode($result);

            foreach($notifications as $msg){
                if(!$msg->is_shown){
                    $msg->is_shown = true;
                    $msg->update(['is_shown']);
                }
            }
        }
    }

    public function actionCheckclick($key)
    {
        if($key != Yii::app()->params['secretKey'])
            \Yii::app()->end('Fuck off!');

        echo '<pre>';

            print_r(getallheaders());
            echo '<br />';

            print_r($_GET);
            echo '<br />';

            print_r($_SERVER);

        exit('</pre>');
    }

    private function setBalanceNotification($type, $notifications){
        foreach($notifications as $msg){
            if($msg->type == $type){
                return false;
            }
        }

        if(\application\models\Campaign::model()->findByAttributes(['user_id' => Yii::app()->getUser()->getId()])){
            if(($type == 'low-balance' && Yii::app()->user->balance < 3 && Yii::app()->user->balance >= 0.05) || ($type == 'null-balance' && Yii::app()->user->balance < 0.05)){
                \application\models\Notification::create([
                   'user_id' => Yii::app()->getUser()->getId(),
                    'is_new' => true,
                    'text' => \application\models\Notification::getTypeData()[$type]['text'],
                    'type' => $type,
                    'is_shown' => false
                ]);
            }
        }

        return true;
    }

    private function getMessageTypeData($type){
        return [
            'icon' => Yii::app()->theme->baseUrl.'/assets/images/adloud/notify-icon/'.\application\models\Notification::getTypeData()[$type]['icon'],
            'text' => \application\models\Notification::getTypeData()[$type]['text']
        ];
    }
}