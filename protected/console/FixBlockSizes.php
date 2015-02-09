<?php
/**
 * Created by PhpStorm.
 * User: rem
 * Date: 08.09.14
 * Time: 14:33
 */

namespace application\console;

use application\models\Blocks;

class FixBlockSizes extends Console
{
    public function run()
    {
        $model = new Blocks();

        $blocks = $model->findAll();

        $formats = [
            'light' => [
                '160x600',
                '200x300',
                '200x600',
                '240x400',
                '240x600',
                '240x4400',
                '300x250',
                '300x600',
                '336x280',
                '468x60',
                '600x300',
                '640x300',
                '728x90',
            ],
            'pro' => [
                '160x600',
                '200x300',
                '200x600',
                '240x400',
                '240x600',
                '240x4400',
                '300x250',
                '300x600',
                '336x280',
                '468x60',
                '600x300',
                '640x300',
                '728x90',
            ],
            'standard' => [
                '160x600',
                '200x300',
                '200x600',
                '240x400',
                '240x600',
                '240x4400',
                '300x250',
                '300x600',
                '336x280',
                '468x60',
                '600x300',
                '640x300',
                '728x90',
            ],
            'simple' => [
                '160x280',
                '200x90',
                '200x350',
                '240x100',
                '240x120',
                '300x150',
                '320x160',
            ],
            'newStandard' => [
                '160x300',
                '200x300',
                '240x134',
                '240x300',
                '300x125',
                '300x150',
                '336x140',
            ],
            'new' => [
                '160x300',
                '200x300',
                '240x134',
                '240x300',
                '300x125',
                '300x150',
                '336x140',
            ],
            'minimal' => [
                '160x300',
                '200x300',
                '240x134',
                '240x300',
                '300x125',
                '300x150',
                '336x140',
            ],
            'min' => [
                '160x300',
                '200x300',
                '240x134',
                '240x300',
                '300x125',
                '300x150',
                '336x140',
            ],
            'market' => [
                '160x300',
                '200x300',
                '240x134',
                '240x300',
                '300x125',
                '300x150',
                '336x140',
            ],
        ];

        foreach($blocks as $block){
            $content = (object)\CJSON::decode($block->content);
            $format = $block->format ? $block->format : ($content ? $content->splitFormat : false);
            $size = $block->ads_type ? $block->ads_type : ($content ? $content->format : false);

            if(!$format || !$size)
                continue;

            if($format == 'AdsMinimal' || $format == 'AdsNewStandard' || $format == 'AdsStandard')
                continue;

            if(!in_array($size, $formats[$format]))
                $size = $formats[$format][0];

            $content->splitFormat = $format;
            $content->format = $size;

            $block->format = $format;
            $block->ads_type = $size;
            $block->content = \CJSON::encode($content);

            $block->update(['format', 'ads_type', 'content']);
        }

        return true;
    }
} 