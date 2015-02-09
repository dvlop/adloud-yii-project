<?php
/**
 * Created by PhpStorm.
 * User: rem
 * Date: 08.07.14
 * Time: 14:37
 */

namespace application\modules\admin\controllers;

use application\components\ControllerAdmin;
use application\models\TicketCategory;
use models\User;

class UserController extends ControllerAdmin
{
    public function actionIndex($id = null)
    {
        $model = new \UsersForm();

        if(!$model->initUser($id)){
            $this->setFlash($model->errors, 'Не удалось найти пользователя с ID '.$id);
            $this->redirect(\Yii::app()->createUrl('admin/user/list'));
        }

        if(\Yii::app()->request->isPostRequest && isset($_POST['UsersForm'])){
            $model->attributes = $_POST['UsersForm'];

            if($model->validate()){
                if(!$model->save()){
                    $this->setFlash($model->errors, 'Не удалось изменить настороки пользователя');
                }

                \Yii::app()->user->setFlash('success', 'Данные пользователя '.$model->fullName.' успешно изменены');
                $this->redirect(\Yii::app()->createUrl('admin/user/list'));
            }else{
                $this->setFlash($model->errors, 'Не удалось изменить настороки пользователя');
            }
        }

        $baseInfoModel = new \UserBaseInfoModel();

        if(!$baseInfoModel->initById($id)){
            $this->setFlash($model->errors, 'Не удалось найти пользователя с ID '.$id);
            $this->redirect(\Yii::app()->createUrl('admin/user/list'));
        }

        $this->render('index', ['user' => $model, 'baseInfoModel' => $baseInfoModel]);
    }

    public function actionList($status = null, $activity = null)
    {
        $model = new \UsersForm();
        $model->setStatus($status);
        $model->setActivity($activity);

        $this->render('list', [
            'users' => $model->getUsers(),
            'model' => $model,
            'ticketCategories' => TicketCategory::model()->findAll(),
        ]);
    }

    public function actionStats($id){
        $model = new \AdminForm('applyMoneyTransaction');
        $model->userId = $id;

        $user = \models\User::getInstance();
        $user->initById($id);
        $request = $user->getMoneyPayoutRequestList()[0];
        $model->requestId = $request['id'];
        if(\Yii::app()->getRequest()->getIsPostRequest()){
            $model->setAttributes($_POST['AdminForm']);
            if(isset($_POST['cancel'])){
                $this->redirect(\Yii::app()->createUrl('admin/money/payoutRequestList'));
                return;
            }
            if($model->validate()){
                if($model->applyMoneyTransaction()){
                    \Yii::app()->user->setFlash('success', "Деньги выведены!");
                } else {
                    \Yii::app()->user->setFlash('error', "Произошла ошибка! Попробуйте еще раз!");
                }

                $this->redirect(array('admin/money/payoutRequestList'));
            }
        }

        $userStats = [];
        try{
            $payout = \models\MoneyPayouts::getInstance();
            $userStats = $payout->getUserStats($id);
        }catch(\Exception $e){
            \Yii::app()->user->setFlash('error', "Произошла ошибка! Попробуйте еще раз.");
        }
        \Yii::app()->user->setFlash('error', "Нет статистики по пользователю. Ждет пересчета.");
        $this->render('showStats', array(
            'stats' => $userStats,
            'currentBalance' => $user->getMoneyBalance(),
            'request' => $request['amount'],
            'model' => $model
        ));
    }

    public function actionActivate($id = null)
    {
        if($id && \Yii::app()->request->isAjaxRequest && isset($_POST['activateUser'])){
            $json = [
                'massage' => '',
                'error' => '',
                'html' => '',
            ];

            $model = new \UserInfoModel();
            $model->id = $id;

            if($model->setRole(\models\User::ACCESS_USER)){
                $message = 'Пользователь '.$model->fullName.' успешно ';
                $message .= $_POST['activateUser'] == 1 ? 'Активирован' : 'Деактивирован';
                $json['message'] = $message;
                \Yii::app()->user->setFlash('success', $message);
            }else{
                $json['error'] = 'Не удалось изменить статус пользователя '.$model->fullName;
            }

            echo \CJSON::encode($json);
        }

        \Yii::app()->end();
    }

    public function actionBan($id = null, $status = null, $activity = null)
    {
        if($id && \Yii::app()->request->isAjaxRequest && isset($_POST['banUser'])){
            $json = [
                'massage' => '',
                'error' => '',
                'html' => '',
            ];

            $model = User::getInstance();
            $model->initById($id);

            $role = \models\User::ACCESS_USER;

            if($_POST['banUser'] == 1) {
                $role = \models\User::ACCESS_BANNED;
            }

            if($model->setRole($role)){
                $userModel =  new \UsersForm();
                $userModel->setActivity(\UsersForm::SELECTOR_ALL);
                $userModel->setStatus(\UsersForm::SELECTOR_ALL);
                $users = $userModel->getUsers();
                $user = $users[$id];

                $message = 'Пользователь '.$model->fullName.' успешно ';
                $message .= $_POST['banUser'] == 1 ? 'забанен' : 'разбанен';
                $json['message'] = $message;

                if($status == $userModel->status)
                    $json['html'] = $this->renderPartial('themes.'.\Yii::app()->theme->name.'.views.admin.user._userTableRow', ['user' => $user, 'model' => $userModel], true);
                else
                    $json['html'] = 'remove-this';
            }else{
                $json['error'] = 'Не удалось изменить статус бана пользователя '.$model->fullName;
            }

            echo \CJSON::encode($json);
        }

        \Yii::app()->end();
    }

    public function actionAddInvite($userId = null)
    {
        $model = new \UserInfoModel();

        if(!$model->initUser($userId)){
            $this->setFlash($model->errors, 'Данные о пользователи не удалось найти в системе');
            $this->redirect(\Yii::app()->user->loginUrl);
        }

        $this->render('addInvite', ['model' => $model]);
    }
}