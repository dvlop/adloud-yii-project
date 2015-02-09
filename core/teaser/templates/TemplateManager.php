<?php
/**
 * Created by PhpStorm.
 * User: t0m
 * Date: 17.02.14
 * Time: 0:26
 */

namespace templates;
use application\models\TargetList;
use config\Config;
use models\Block;

class TemplateManager {

    public static function renderInsertCode(Block $block){
        $tpl = file_get_contents(__DIR__ . DIRECTORY_SEPARATOR . 'views' . DIRECTORY_SEPARATOR . 'insertCode.html');

        $tpl = str_replace('{url}', Config::getInstance()->getBaseUrl() . "block.js", $tpl);
        $tpl = str_replace('{colorScheme}', $block->color, $tpl);
        $tpl = str_replace('{backgroundScheme}', $block->bg, $tpl);
        $tpl = str_replace('{allowAdult}', $block->allowAdult ? 'true' : 'false', $tpl);
        $tpl = str_replace('{allowShock}', $block->allowShock ? 'true' : 'false', $tpl);
        $tpl = str_replace('{allowSms}', $block->allowSms ? 'true' : 'false', $tpl);
        $tpl = str_replace('{type}', $block->size, $tpl);
        $tpl = str_replace('{id}', $block->getId(), $tpl);

        return $tpl;
    }

    public static function renderNewInsertCode(Block $block)
    {
        $tpl = file_get_contents(__DIR__ . DIRECTORY_SEPARATOR . 'views' . DIRECTORY_SEPARATOR . 'newInsertCode.html');

        $tpl = str_replace('{url}', Config::getInstance()->getBaseUrl() . "block.js", $tpl);
        $tpl = str_replace('{format}', $block->params->format, $tpl);
        $tpl = str_replace('{verticalCount}', $block->params->verticalCount, $tpl);
        $tpl = str_replace('{horizontalCount}', $block->params->horizontalCount, $tpl);
        $tpl = str_replace('{captionColor}', $block->params->captionColor, $tpl);
        $tpl = str_replace('{textColor}', $block->params->textColor, $tpl);
        $tpl = str_replace('{buttonColor}', $block->params->buttonColor, $tpl);
        $tpl = str_replace('{backgroundColor}', $block->params->backgroundColor, $tpl);
        $tpl = str_replace('{borderColor}', $block->params->borderColor, $tpl);
        $tpl = str_replace('{border}', $block->params->border, $tpl);
        $tpl = str_replace('{id}', $block->getId(), $tpl);
        $tpl = str_replace('{allowAdult}', $block->allowAdult ? 'true' : 'false', $tpl);
        $tpl = str_replace('{allowShock}', $block->allowShock ? 'true' : 'false', $tpl);
        $tpl = str_replace('{allowSms}', $block->allowSms ? 'true' : 'false', $tpl);

        return $tpl;
    }

    public static function renderInsertRetargetingCode(TargetList $target){
        $tpl = file_get_contents(__DIR__ . DIRECTORY_SEPARATOR . 'views' . DIRECTORY_SEPARATOR . 'insertRetargetingCode.html');

        $tpl = str_replace('{url}', Config::getInstance()->getBaseUrl() . "target.js", $tpl);
        $tpl = str_replace('{key}', $target->id, $tpl);

        return $tpl;
    }

}