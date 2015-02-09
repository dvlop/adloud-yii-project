<?php
/**
 * Created by PhpStorm.
 * User: t0m
 * Date: 16.02.14
 * Time: 22:58
 */

namespace models;

use ads\AdsRenderer;
use ads\AdsStandard;
use ads\NewAdsRendered;
use core\RatingManager;
use MLF\layers\Logic;
use models\dataSource\BlockRendererDataSource;

/**
 * @property BlockRendererDataSource $nextLayer
 */
class BlockRenderer extends Logic {
    /**
     * @return \models\BlockRenderer
     */
    public static function getInstance()
    {
        return parent::getInstance();
    }

    public function renderNoUpdate($blockId, $settings, $blockData = null)
    {
        if($blockData === null)
            $blockData = $this->nextLayer->getBlockInfo($blockId);

        if(!$blockData)
            return false;

        $settings['noUpdate'] = true;

        $ads = $this->nextLayer->runAuction($settings, $blockData, new RatingManager());

        if(!$ads)
            return false;

        $renderer = new AdsRenderer(Block::FORMAT_ADS_STANDARD, $settings['type'], $settings['colorScheme'], $settings['backgroundScheme'], $blockId);

        foreach($ads as $ad){
            $ad['content']['id'] = $ad['id'];
            $renderer->addAds(new AdsStandard($ad['content']));
        }

        return [$renderer->getStyles(), $renderer->render()];
    }

    public function render($blockId, $settings){
        $blockData = $this->nextLayer->getBlockInfo($blockId);

        if(!$blockData)
            return false;

        $ads = $this->nextLayer->runAuction($settings, $blockData, new RatingManager());

        if(!$ads)
            return false;

        $this->updateBlockShows($blockId);
        $class = 'ads\\' . $settings['format'];

        $renderer = new AdsRenderer($settings['format'], $settings['type'], $settings['colorScheme'], $settings['backgroundScheme'], $blockId);

        foreach($ads as $ad){
            $ad['content']['id'] = $ad['id'];
            \core\RedisIO::hIncrBy("block-stats:{$blockId}",$ad['id']);
            $renderer->addAds(new $class($ad['content']));
        }

        return [$renderer->getStyles(), $renderer->render()];
    }

    public function newRender($blockId, $settings, $block = null)
    {
        $isPreview = $block === true || is_array($block);
        if(!is_array($block)){
            $blockData = $this->nextLayer->getBlockInfo($blockId);
        }else{
            $blockData = $block;
        }

        if(!$blockData)
            return false;

        $settings['adsNum'] = $settings['verticalCount']*$settings['horizontalCount'];

        $ads = $this->nextLayer->runAuction($settings, $blockData, new RatingManager(), $isPreview);

        if(!$ads){
            return false;
        }

        if(!$block){
            $this->updateBlockShows($blockId);
            //$this->updateAdsShows($ads);
        }

        if(isset($blockData['splitFormat']) && $blockData['splitFormat'])
            $settings['splitFormat'] = $blockData['splitFormat'];

        if(!isset($settings['splitFormat']) || !$settings['splitFormat'])
            $settings['splitFormat'] = 'AdsStandard';

        $class = 'ads\\'.ucfirst($settings['splitFormat']);

        if(!$block)
            $settings = array_merge($settings, $blockData);
        else
            $settings = array_merge($blockData, $settings);

        $settings['blockId'] = $blockId;

        $renderer = new NewAdsRendered($settings);

        foreach($ads as $ad){
            $renderer->addAds(new $class(array_merge($ad['content'], ['id' => $ad['id']])));
        }

        return [$renderer->getStyles(), $renderer->render()];
    }

    public function getTypes()
    {
        return $this->nextLayer->getTypes();
    }

    private function updateBlockShows($blockId){
        $this->nextLayer->updateBlockShows($blockId);
    }

    private function updateAdsShows(array $data){
        $this->nextLayer->updateAdsShows($data);
    }
} 