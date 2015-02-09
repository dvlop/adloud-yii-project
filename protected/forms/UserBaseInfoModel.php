<?php
/**
 * Created by PhpStorm.
 * User: rem
 * Date: 27.05.14
 * Time: 16:26
 * @property int $userId
 * @property int $id
 */

class UserBaseInfoModel extends CFormModel
{
    public $siteUrl;
    public $desiredProfit;
    public $statLink;
    public $statLogin;
    public $statPassword;
    public $description;

    private $_userId;
    private $_infoId;

    public function attributeLabels()
    {
        return array(
            'id' => 'ID',
            'userId' => 'ID пользователя',
            'siteUrl' => 'Адрес сайта',
            'desiredProfit' => 'Желаемая прибыль',
            'statLink' => 'Ссылку на статистику',
            'statLogin' => 'Логин доступа к статистике',
            'statPassword' => 'Пароль доступа к статистике',
            'description' => 'Комментарий',
        );
    }

    public function rules()
    {
        return [
            ['siteUrl, statLink, statLogin, statPassword', 'required', 'message' => 'Поле {attribute} должно быть заполнено'],
            ['siteUrl, statLink', 'length', 'min'=>1, 'max'=>126],
            ['statLogin, statPassword', 'length', 'min'=>1],
            ['description', 'length', 'max'=>512],
            ['desiredProfit, description', 'default'],
            ['siteUrl, desiredProfit, statLink, statLogin, statPassword, description', 'filter', 'filter' => 'htmlspecialchars'],
        ];
    }

    public function getUserId()
    {
        return $this->_userId;
    }

    public function getId()
    {
        return $this->_infoId;
    }

    public function initById($id = null)
    {
        $info = \Yii::app()->user->getBaseInfo($id);

        if($info){
            $this->_infoId = $info->getId();
            $this->_userId = $info->getUserId();
            $this->siteUrl = $info->siteUrl;
            $this->desiredProfit = $info->desiredProfit;
            $this->statLink = $info->statLink;
            $this->statLogin = $info->statLogin;
            $this->statPassword = $info->statPassword;
            $this->description = $info->description;
        }

        return true;
    }

    public function setBaseInfo($id = null)
    {
        if(!$id)
            $id = \Yii::app()->user->id;

        try{
            $user = \models\User::getInstance();

            if(!$user->initById($id)){
                $this->addError('update', 'Не удалось найти пользователя с ID'.$id);
                return false;
            }

            if(!$user->setRole(\models\User::ACCESS_USER)){
                $this->addError('update', 'Не удалось изменить данные пользователя '.$user->fullName);
                return false;
            }

            if(!$info = \Yii::app()->user->getBaseInfo($id)){
                $info = \models\UserBaseInfo::getInstance();
                $method = 'save';
            }else{
                $method = 'update';
            }

            if(!$info){
                $this->addError('update', 'Не удалось сохранить данные '.\Yii::app()->user->error);
                return false;
            }

            $info->siteUrl = $this->siteUrl;
            $info->desiredProfit = $this->desiredProfit;
            $info->statLink = $this->statLink;
            $info->statLogin = $this->statLogin;
            $info->statPassword = $this->statPassword;
            $info->description = $this->description;

            $info->$method($id);

        }catch(Exception $e){
            $this->addError('update', $e->getMessage());
            return false;
        }

        return true;
    }
}