<?php

require_once "LayersConfig.php";
require_once "CodeGenerator.php";

$help = "usage: \n php mlf.php build path/to/dir \n php mlf.php newModel path/to/dir ModelName \n";

if(!isset($argv[1])){
    die($help);
}

$codeGenerator = new \MLF\CodeGenerator($argv[2]);

switch($argv[1]){
    case 'build':
        if(empty($argv[2]) || !file_exists($argv[2]) || !is_dir($argv[2])){
            die('incorrect destination path');
        }
        $codeGenerator->buildSchema();
        break;
    case 'newModel':
        if(empty($argv[3])){
            die('incorrect model name');
        }
        $codeGenerator->createModel($argv[3]);
        break;
    default:
        die($help);
}
