<?php
/**
 * Created by t0m
 * Date: 18.12.13
 * Time: 14:25
 */

namespace MLF;


class CodeGenerator {

    private $destinationPath;
    private $settingsFile;
    private $templatesPath;

    public function __construct($destinationPath, $templatesPath = 'classTemplates', $settingsFile = 'layerSettings.json'){
        $this->destinationPath = $destinationPath;
        $this->settingsFile = $settingsFile;
        $this->templatesPath = $templatesPath;
    }

    public function buildSchema(){

        $layers = LayersConfig::getInstance($this->settingsFile)->getLayersName();

        $generalLayerTemplate = file_get_contents($this->templatesPath . DIRECTORY_SEPARATOR. 'GeneralLayer.tpl');

        foreach($layers as $layer){

            if($layer['type'] !== 'logic'){
                $dir = $this->destinationPath . DIRECTORY_SEPARATOR . lcfirst($layer['name']);
                if(!file_exists($dir)){
                    mkdir($dir);
                }

                $layerFileName = $dir . DIRECTORY_SEPARATOR . $layer['name'] . 'Layer.php';

                if(!file_exists($layerFileName)){
                    $currentLayer = $generalLayerTemplate;
                    $currentLayer = str_replace('{LayerName}', $layer['name'], $currentLayer);
                    file_put_contents($layerFileName, $currentLayer);
                }
            }

        }

    }

    public function createModel($modelName){

        $layerTemplate = file_get_contents($this->templatesPath . DIRECTORY_SEPARATOR. 'Layer.tpl');
        $logicTemplate = file_get_contents($this->templatesPath . DIRECTORY_SEPARATOR. 'Logic.tpl');
        $nextLayerComment = file_get_contents($this->templatesPath . DIRECTORY_SEPARATOR. 'nextLayerComment.tpl');

        $layers = array_reverse(LayersConfig::getInstance($this->settingsFile)->getLayersName());

        $nextLayer = null;
        foreach($layers as $layer){

            if($layer['type'] !== 'logic' && $layer['forEachModel']){
                $dir = $this->destinationPath . DIRECTORY_SEPARATOR . lcfirst($layer['name']);
                if(!file_exists($dir)){
                    return false;
                }

                $layerFileName = $dir . DIRECTORY_SEPARATOR . $modelName . $layer['name'] . '.php';

                if(!file_exists($layerFileName)){
                    $currentLayer = $layerTemplate;

                    if($nextLayer !== null){
                        $nextLayerCurrentComment = $nextLayerComment;
                        $nextLayerCurrentComment = str_replace('{NextLayer}', $nextLayer, $nextLayerCurrentComment);
                        $currentLayer = str_replace('{NextLayer}', $nextLayerCurrentComment, $currentLayer);
                    } else {
                        $currentLayer = str_replace('{NextLayer}', '', $currentLayer);
                    }
                    $currentLayer = str_replace('{LayerName}', $modelName . $layer['name'], $currentLayer);
                    file_put_contents($layerFileName, $currentLayer);
                }

                $nextLayer = $modelName . $layer['name'];
            } else {

                $dir = $this->destinationPath . DIRECTORY_SEPARATOR ;
                if(!file_exists($dir)){
                    return false;
                }

                $layerFileName = $dir . DIRECTORY_SEPARATOR . $modelName . '.php';

                if(!file_exists($layerFileName)){
                    $currentLayer = $logicTemplate;

                    if($nextLayer !== null){
                        $nextLayerCurrentComment = $nextLayerComment;
                        $nextLayerCurrentComment = str_replace('{NextLayer}', $nextLayer, $nextLayerCurrentComment);
                        $currentLayer = str_replace('{NextLayer}', $nextLayerCurrentComment, $currentLayer);
                    } else {
                        $currentLayer = str_replace('{NextLayer}', '', $currentLayer);
                    }
                    $currentLayer = str_replace('{LayerName}', $modelName , $currentLayer);
                    file_put_contents($layerFileName, $currentLayer);
                }
                $nextLayer = $modelName;
            }

        }

    }



}