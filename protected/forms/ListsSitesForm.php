<?php

use models\Lists;

/**
 * @property array $typesNames
 * @property array $campaignsList
 * @property string $typeName;
 * @property string $sitesText
 * @property array allCampaigns
 */
class ListsSitesForm extends CFormModel
{
    private static $_listTypes = [
        Lists::WHITE_LIST => 'Белый список',
        Lists::BLACK_LIST => 'Черный список',
    ];

    private $_campaigns;
    private $_allCampaigns;

    public $id;
    public $name;
    public $type = Lists::WHITE_LIST;
    public $sites;
    public $campaigns;

    public function attributeLabels()
    {
        return [
            'name' => \Yii::t('lists', 'Введите название сайта'),
            'type' => \Yii::t('lists', 'Тип списка'),
            'sites' => \Yii::t('lists', 'Введите через запятую ID сайтов, которые хотите добавить в список.'),
            'campaigns' => \Yii::t('lists', 'Выберите кампании, к которым следует применить список'),
        ];
    }

    public function rules()
    {
        return [
            ['name', 'required'],
            ['id, type, campaigns, sites', 'default'],
            ['name, type', 'filter', 'filter' => 'htmlspecialchars'],
        ];
    }

    public function getTypesNames()
    {
        $types = [];

        foreach(self::$_listTypes as $id => $name){
            $types[] = (object)[
                'name' => $name,
                'value' => $id,
                'checked' => $this->type == $id,
            ];
        }

        return $types;
    }

    public function getTypeName($typeId = null)
    {
        if($typeId === null)
            $typeId = $this->type;
        if(isset(self::$_listTypes[$typeId]))
            return self::$_listTypes[$typeId];
        else
            return self::$_listTypes[Lists::WHITE_LIST];
    }

    public function getCampaignsList()
    {
        if($this->_campaigns === null){
            $this->_campaigns = [];

            try{
                if($campaigns = \models\Campaign::getInstance()->findAll(['id', 'description', 'publish', 'where' => ['user_id' => Yii::app()->user->id,]])){
                    foreach($campaigns as $campaign){
                        if($campaign->publish == \models\Campaign::STATUS_ARCHIVED)
                            continue;

                        if($this->campaigns)
                            $campaign->checked = in_array($campaign->id, $this->campaigns);
                        else
                            $campaign->checked = false;

                        if($this->allCampaigns)
                            $campaign->disabled = !$campaign->checked && in_array($campaign->id, $this->allCampaigns);
                        else
                            $campaign->disabled = false;

                        $this->_campaigns[] = $campaign;
                    }
                }
            }catch(Exception $e){
                $this->addError('null', $e->getMessage());
            }
        }

        return $this->_campaigns;
    }

    public function getSitesText()
    {
        if($this->sites)
            return implode("\r\n", $this->sites);
        else
            return '';
    }

    public function getAllCampaigns()
    {
        if($this->_allCampaigns === null){
            try{
                $this->_allCampaigns = Lists::getInstance()->getAllCampaigns();
            }catch(Exception $e){
                $this->_allCampaigns = [];
            }
        }
        return $this->_allCampaigns;
    }

    public function validate($attributes=null, $clearErrors=true)
    {
        if(!$this->sites || !is_string($this->sites)){
            $this->addError('sites', 'неправыльно заполнено поле "сайты"');
            return false;
        }

        $this->sites = htmlspecialchars($this->sites);

        if(strpos($this->sites, ',') !== false)
            $sites = array_filter(explode(',', $this->sites));
        elseif(strpos($this->sites, '.') !== false)
            $sites = array_filter(explode('.', $this->sites));
        elseif(strpos($this->sites, ' ') !== false)
            $sites = array_filter(explode(' ', $this->sites));
        else
            $sites = array_filter(preg_split('/\n|\r\n?/',  $this->sites));

        if($sites){
            foreach($sites as $num => $siteId){
                $intVal = intval($siteId);
                if($intVal)
                    $sites[$num] = $intVal;
                else
                    unset($sites[$num]);
            }
        }

        if(!$sites || !is_array($sites)){
            $this->addError('sites', 'неправыльно заполнено поле "сайты"');
            return false;
        }

        $this->sites = $sites;

        if(is_array($this->campaigns))
            $this->campaigns = array_filter($this->campaigns);

        if(!is_array($this->campaigns) || empty($this->campaigns)){
            $this->addError('campaigns', 'не указаны кампании');
            return false;
        }

        return parent::validate($attributes, $clearErrors);
    }
} 