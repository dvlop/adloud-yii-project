<?php

use models\Block;
use models\BlockParams;
use application\models\Blocks;
use application\models\Sites;

/**
 * Created by PhpStorm.
 * User: psyhonut
 * Date: 16.02.14
 * Time: 23:33
 * @property array $blockSizes
 * @property array $backgrounds
 * @property array $colors
 * @property array $blockFormats
 * @property Block $block
 */
class BlockForm extends CFormModel
{
    private static $sizesCollection = [
        [160, 600],
        [200, 300],
        [200, 600],
        [240, 400],
        [240, 600],
        [300, 250],
        [300, 600],
        [336, 280],
        [468, 60],
        [600, 300],
        [640, 300],
        [728, 90],
    ];

    private static $backgroundsCollection = [
        ['gray', 'Серый'],
        ['border', 'Обводка'],
        ['white', 'Белый'],
    ];

    private static $colorsCollection = [
        ['black', 'Black', 'black_scheme'],
        ['cheerful', 'Cheerful', 'cheerful_scheme'],
        ['purple', 'Purple', 'purple_scheme'],
        ['sky', 'Sky', 'sky_scheme'],
        ['surf', 'Surf', 'surf_scheme'],
        ['juice', 'Juice', 'juice_scheme'],
        ['france', 'France', 'france_scheme'],
        ['greeny', 'Greeny', 'greeny_scheme'],
    ];

    public $id;
    public $siteId;
    public $description;
    public $size = '160x600';
    public $color = 'standart';
    public $bg = 'gray';
    public $name;
    public $status = 0;
    /**
     * @var BlockParams
     */
    public $blockParams;
    public $params = [];
    public $type;

    private $_newFormats;
    private $_block;
    private $_blockModel;

    public function attributeLabels()
    {
        return array(
            'description' => 'Ведите название блока:',
            'bg' => 'Выберите заливку:',
            'color' => 'Выберите цветовое решение:',
            'css' => 'Стиль',
        );
    }

    public function rules()
    {
        return [
            ['description, size, color, name, status, bg, params, type', 'default'],
            ['description, size, color, name, status, bg, type', 'filter', 'filter' => 'htmlspecialchars'],
        ];
    }

    public function init($id = null)
    {
        $this->block = Block::getInstance()->init($id);

        $this->id = $this->block->getId();
        $this->siteId = $this->block->siteId;
        $this->size = $this->block->size;
        $this->color = $this->block->color;
        $this->bg = $this->block->bg;
        $this->name = $this->block->description;
        $this->status = $this->block->status;
        $this->blockParams = $this->block->params;
        $this->params = (array)$this->block->params;
        $this->type = $this->block->type;
        if(!$this->description && $this->blockParams){
            $this->description = $this->blockParams->description;
        }
    }

    public function getBlockSizes()
    {
        $sizes = [];

        foreach(self::$sizesCollection as $size){
            $value = $size[0].'x'.$size[1];
            $sizes[] = (object)[
                'value' => $value,
                'width' => $size[0],
                'height' => $size[1],
                'name' => 'Тизерный блок '.$size[0].'&times;'.$size[1].'px',
                'selected' => $this->size == $value,
            ];
        }

        return $sizes;
    }

    public function getBackgrounds()
    {
        $backgrounds = [];

        foreach(self::$backgroundsCollection as $bg){
            $backgrounds[] = (object)[
                'value' => $bg[0],
                'name' => $bg[1],
                'selected' => $this->bg == $bg[0],
            ];
        }

        return $backgrounds;
    }

    public function getColors()
    {
        $colors = [];

        foreach(self::$colorsCollection as $color){
            $colors[] = (object)[
                'value' => $color[0],
                'name' => $color[1],
                'class' => $color[2],
                'selected' => $this->color == $color[0],
            ];
        }

        return $colors;
    }

    public function getBlockFormats()
    {
        if($this->_newFormats === null){
            $this->_newFormats = [];

            foreach(Block::getInstance()->getNewFormats() as $format){
                $formatParts = explode('x', $format);

                $this->_newFormats[] = (object)[
                    'value' => $format,
                    'isChecked' => $this->blockParams ? $format == $this->blockParams->format : false,
                    'width' => isset($formatParts[0]) ? $formatParts[0] : '160',
                    'height' => isset($formatParts[1]) ? $formatParts[1] : '300',
                ];
            }
        }

        return $this->_newFormats;
    }

    public function block($siteId, $id = null)
    {
        $block = Block::getInstance()->init($id);
        $this->id = $id;

        if(!$this->description && $this->blockParams)
            $this->description = $this->blockParams->description;

        $block->description = $this->description;
        $block->name = $this->name;
        $block->size = $this->size;
        $block->color = $this->color;
        $block->bg = $this->bg;
        $block->siteId = $siteId;
        $block->params = $this->blockParams;
        $block->type = $this->type;

        try{
            $site = \models\Site::getInstance();

            $block->allowAdult = $site->allowAdult;
            $block->allowShock = $site->allowShock;
            $block->allowSms = $site->allowSms;
            $block->allowAnimation = $site->allowAnimation;

            if(!$site->initById($siteId)){
                $this->addError(null, 'Невозможно найти сайт с ID '.$siteId);
                return false;
            }

            if(!$id){
                $blockId = $block->saveBlock();
                $result = $blockId;
            }else{
                $block->setId($id);
                $block->update();
                $result = $id;
            }

            if(!$result)
                return false;

            $blockModel = $this->getBlockModel();
            if($blockModel){
                if($id){
                    if($blockModel->status == Blocks::STATUS_PUBLISHED){
                        $blockModel->unPublish();
                        $blockModel->publish();
                    }
                }else{
                    $site = Sites::model()->findByPk($siteId);
                    if($site && $site->status == Sites::STATUS_PUBLISHED)
                        $blockModel->publish();
                }
            }

            return true;
        }catch(Exception $e){
            $error = YII_DEBUG ? $e->getMessage() : 'Возникла ошибка при создании рекламного блока. Попробуйте еще раз позже.';
            $this->addError(null, $error);
            return false;
        }
    }

    public function setBlock(Block $block)
    {
        $this->_block = $block;
    }

    public function getBlock()
    {
        if($this->_block === null){
            $this->_block = Block::getInstance()->init($this->id);
        }

        return $this->_block;
    }

    /**
     * @return Blocks
     */
    public function getBlockModel()
    {
        if($this->_blockModel === null){
            $this->_blockModel = $this->id ? Blocks::model()->findByPk($this->id) : new Blocks();
        }

        return $this->_blockModel;
    }

    public function validate($attributes=null, $clearErrors=true)
    {
        if($this->params && is_array($this->params)){
            $this->blockParams = new BlockParams($this->params);
        }else{
            $this->params = [];
        }

        return parent::validate($attributes, $clearErrors);
    }
}