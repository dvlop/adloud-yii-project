<?php

use application\components\ControllerBase;

class AccountController extends ControllerBase
{
    public $layout = '//layouts/authorized';


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

    public function beforeAction($action)
    {
        /*if(\Yii::app()->user->isGuest){
            $email = \Yii::app()->request->getParam('email');
            $hash = \Yii::app()->request->getParam('hash');
            if(!$email || !$hash){
                $this->redirect(\Yii::app()->createUrl('index/index'));
            }else{
                $model = new UserInfoModel();
                $model->email = $email;
                if($model->checkHash($hash)){
                    if(\Yii::app()->user->noPasswordLogin($email))
                        $this->redirect(\Yii::app()->createUrl(\Yii::app()->user->enterInviteUrl));
                }

                $this->redirect(\Yii::app()->createUrl('index/index'));
            }
        }*/

        parent::beforeAction($action);

        $this->breadcrumbs[\Yii::app()->createUrl('account/index')] = Yii::t('account', 'Персональные данные');

        return true;
    }

    public function actionIndex()
    {
        if(\Yii::app()->user->isFirstLogin)
            $this->redirect(\Yii::app()->user->baseInfoUrl);

        if(!\Yii::app()->user->isUser)
            $this->redirect(\Yii::app()->user->enterInviteUrl);

        if(\Yii::app()->user->getIsBanned()){
            $this->redirect(\Yii::app()->createUrl('account/banned'));
        }

        $this->pageName = Yii::t('account', 'Персональные данные');

        $model = new UserInfoModel();

        if(!$model->initUser()){
            $this->setFlash($model->errors, Yii::t('account', 'Данные о пользователе не удалось найти в системе'), Yii::t('account', 'Попробуйте выполнить вход повторно'));
            $this->redirect(\Yii::app()->user->loginUrl);
        }

        if(\Yii::app()->request->isPostRequest && isset($_POST['UserInfoModel'])){
            $model->attributes = $_POST['UserInfoModel'];

            if($model->update()){
                Yii::app()->user->setFlash('success', Yii::t('account', 'Ваш профиль был успешно обновлён'));
                if(Yii::app()->request->getParam('language') != Yii::app()->user->getLanguage())
                    $this->redirect(Yii::app()->createUrl('account/index'), ['language' => Yii::app()->user->getLanguage()]);
            }
            else
                $this->setFlash($model->errors, Yii::t('account', 'Не удалось обновить профиль'), Yii::t('account', 'Попробуйте позже'));
        }

        $this->render('index', ['model' => $model]);
    }

    public function ActionThankyou()
    {
        $this->layout = '//layouts/thankyou';
        $this->render('thankyou');
    }

    public function actionBanned()
    {
        if(!\Yii::app()->user->getIsBanned()){
            $this->redirect(\Yii::app()->createUrl('account/index'));
        }

        $this->layout = '//layouts/thankyou';
        $this->render('banned');
    }

    public function actionBaseInfo()
    {
        if(\Yii::app()->user->isUser)
            $this->redirect(\Yii::app()->user->accountUrl);

        if(\Yii::app()->user->getIsBanned()){
            $this->redirect(\Yii::app()->createUrl('account/banned'));
        }

        $model = new UserBaseInfoModel();

        if(!$model->initById()){
            $this->setFlash($model->errors, 'Не удалось найти данные пользователя', 'Возможно, они не были внесены');
            $this->redirect(\Yii::app()->user->accountUrl);
        }

        if(\Yii::app()->request->isPostRequest && isset($_POST['UserBaseInfoModel'])){
            $model->attributes = $_POST['UserBaseInfoModel'];

            if($model->validate()){
                if($model->setBaseInfo()){
                    $this->redirect(\Yii::app()->user->enterInviteUrl);
                }
            }

            if($model->hasErrors()){
                $this->setFlash($model->errors);
            }

        }

        $this->render('baseInfo', ['model' => $model]);
    }

    public function actionUserInfo(){
        $user = \models\User::getInstance();
        $user->initById(\core\Session::getInstance()->getUserId());

        $model = new UserInfoModel('update');
        $model->user = $user;

        if (\Yii::app()->getRequest()->getIsPostRequest()) {
            $model->setAttributes($_POST['UserInfoModel']);
            if($model->validate()){
                if($model->update()){
                    \Yii::app()->user->setFlash('success', "Данные сохранены");
                } else {
                    \Yii::app()->user->setFlash('error', $model->errors['update'][0]);
                }
            }
        } else {

            $model->webmoneyId = $user->webmoneyId;
            $model->fullName = $user->fullName;
            $model->email = $user->email;
        }

        $this->render('userInfo', array('model' => $model));
    }

    public function actionEnterInvite()
    {
        $this->layout = '//layouts/darkBackground';
        $this->pageName = '';
        $this->breadcrumbs = [];

        $model = new UserInfoModel();

        if(!$model->initUser()){
            $this->setFlash($model->errors, 'Данные о пользователе не удалось найти в системе', 'Попробуйте выполнить вход повторно');
            $this->redirect(\Yii::app()->user->loginUrl);
        }

        if(\Yii::app()->request->isPostRequest && isset($_POST['UserInfoModel'])){
            $model->attributes = $_POST['UserInfoModel'];

            if($model->checkInvite()){
                $this->redirect(\Yii::app()->user->accountUrl);
            }
        }

        if($model->hasErrors())
            $this->setFlash($model->errors);

        $this->render('enterInvite', ['model' => $model]);
    }

    public function actionChangePassword($id = null)
    {
        if($id && \Yii::app()->request->isAjaxRequest && isset($_POST['oldPassword']) && isset($_POST['newPassword'])){
            $ajax = [
                'html' => '',
                'message' => '',
                'error' => '',
            ];

            if(\Yii::app()->user->changePassword($_POST['oldPassword'], $_POST['newPassword'])){
                $ajax['message'] = Yii::t('account', 'Пароль был успешно изменён!');
            }else{
                $ajax['error'] = Yii::t('account', 'Не удалось изменить пароль');
                $this->parseError(\Yii::app()->user->error, Yii::t('account', 'Проверьте, правильно ли введены данные'));
            }

            echo \CJSON::encode($ajax);
        }

        \Yii::app()->end();
    }

    public function actionLoginById($id)
    {
        Yii::app()->user->loginById($id);
        $this->redirect(Yii::app()->createUrl('account/index'));
    }

    public function actionBalance(){

        if(\Yii::app()->user->getIsBanned()){
            $this->redirect(\Yii::app()->createUrl('account/banned'));
        }

        $model = \models\User::getInstance();
        $model->initById(\core\Session::getInstance()->getUserId());

        $balance = round($model->getMoneyBalance(), 2);
        $logList = $model->getTransactionLog([]);

        $this->pageName = Yii::t('balance', 'Мой баланс');

        foreach($logList as $key => $transaction){
            switch ($transaction['comment']){
                case 'advertiser_expenses':
                    $logList[$key]['comment'] = \Yii::t('balance', 'Расход в системе');
                    $logList[$key]['sign'] = 'minus';
                    break;
                case 'webmaster_income':
                    $logList[$key]['comment'] = \Yii::t('balance', 'Доход в системе');
                    $logList[$key]['sign'] = 'plus';
                    break;
                case 'referal_payment':
                    $logList[$key]['comment'] = \Yii::t('balance', 'Реферальные начисления');
                    $logList[$key]['sign'] = 'plus';
                    break;
                case 'system_in':
                    $logList[$key]['comment'] = \Yii::t('balance', 'Ввод средств в систему');
                    $logList[$key]['sign'] = 'plus';
                    break;
                case 'system_out':
                    $logList[$key]['comment'] = \Yii::t('balance', 'Вывод средств из системы');
                    $logList[$key]['sign'] = 'minus';
                    break;
            }
        }

        $this->render('balance', [
            'logList' => $logList,
            'balance' => $balance
        ]);
    }

}