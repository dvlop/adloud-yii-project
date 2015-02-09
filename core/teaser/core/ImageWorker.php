<?php
/**
 * Created by t0m
 * Date: 14.01.14
 * Time: 22:13
 */

namespace core;


use config\Config;

class ImageWorker {

    public static $allowedFormats = [
        'image/jpg' => ['imagecreatefromjpeg', 'imagejpeg'],
        'image/jpeg' => ['imagecreatefromjpeg', 'imagejpeg'],
        'image/png' => ['imagecreatefrompng', 'imagepng'],
        'image/gif' => ['imagecreatefromgif', 'imagegif'],
    ];

    public static function buildAddress(array $imageInfo){
        $imageLocation = Config::getInstance()->getImageServersSettings()[$imageInfo['addressAlias']]['address'] . $imageInfo['image'];
        return trim(str_replace(DIRECTORY_SEPARATOR, '/', $imageLocation));
    }

    public static function buildPath(array $imageInfo){
        return trim(str_replace('/', DIRECTORY_SEPARATOR, $imageInfo['path']));
    }

    public static function uploadImage($localPath){
        list($alias, $address, $port) = ImageServers::getImageServerLocation();

        if(function_exists('curl_file_create')){
            $post['file'] = curl_file_create($localPath, mime_content_type($localPath));
        }else{
            $post = array('file'=> '@'.$localPath . ';type=' . getimagesize($localPath)['mime']);
        }

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $address.'/'.'upload.php');
        curl_setopt($ch, CURLOPT_PORT, $port);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        $result = curl_exec($ch);
        curl_close($ch);

        $data = @json_decode($result, true);
        if(!$data){
            throw new \exceptions\DataLayerException('error while uploading image');
        }
        if(isset($data['error'])){
            switch($data['error']){
                case 1:
                    $error = 'file is too big';
                    break;
                case 2:
                case 3:
                    $error = 'incorrect file type';
                    break;
                default:
                    $error = 'cannot upload file';
            }
            throw new \exceptions\DataLayerException($error);
        }

        return array(
            'image' => $data['image'],
            'addressAlias' => $alias
        );
    }

    public static function resize($image, $attributes = [])
    {
        if($attributes){
            $x_o = $attributes['x1'];
            $y_o = $attributes['y1'];
            $w_o = $attributes['width'];
            $h_o = $attributes['height'];
        }else{
            $x_o = 0;
            $y_o = 0;
            $w_o = 100;
            $h_o = 100;
        }

        if (($x_o < 0) || ($y_o < 0) || ($w_o < 0) || ($h_o < 0)) {
            return false;
        }
        list($w_i, $h_i, $type) = getimagesize($image); // Получаем размеры и тип изображения (число)
        $types = array("", "gif", "jpeg", "png"); // Массив с типами изображений
        $ext = $types[$type]; // Зная "числовой" тип изображения, узнаём название типа
        if ($ext) {
            $func = 'imagecreatefrom'.$ext; // Получаем название функции, соответствующую типу, для создания изображения
            $img_i = $func($image); // Создаём дескриптор для работы с исходным изображением
        } else {
            return false;
        }
        if ($x_o + $w_o > $w_i) $w_o = $w_i - $x_o; // Если ширина выходного изображения больше исходного (с учётом x_o), то уменьшаем её
        if ($y_o + $h_o > $h_i) $h_o = $h_i - $y_o; // Если высота выходного изображения больше исходного (с учётом y_o), то уменьшаем её
        $img_o = imagecreatetruecolor($w_o, $h_o); // Создаём дескриптор для выходного изображения
        imagecopy($img_o, $img_i, 0, 0, $x_o, $y_o, $w_o, $h_o); // Переносим часть изображения из исходного в выходное
        $func = 'image'.$ext; // Получаем функция для сохранения результата
        return $func($img_o, $image); // Сохраняем изображение в тот же файл, что и исходное, возвращая результат этой операции

        /*

        *** resize($image = '', $coordinates = []) ***

        if(!$image || !is_array($image)){
            throw new \exceptions\DataLayerException('invalid image info');
        }

        $imageSettings = Config::getInstance()->getImageServersSettings()[$image['addressAlias']];

        $path = $coordinates['path'];

        $file = $path.$image['image'];
        $file = str_replace('/', DIRECTORY_SEPARATOR, $file);
        $file = str_replace('\\', DIRECTORY_SEPARATOR, $file);

        if(!file_exists($file)){
            throw new \exceptions\DataLayerException('file not found');
        }

        $info = getimagesize($file);

        if(!$info[0] || !$info[1]){
            throw new \exceptions\DataLayerException('invalid file uploaded!');
        }

        if(!array_key_exists($info['mime'], self::$allowedFormats)){
            throw new \exceptions\DataLayerException('invalid file format!');
        }

        $w = isset($coordinates['w']) && $coordinates['w'] ? $coordinates['w'] : $info[0];
        $h = isset($coordinates['h']) && $coordinates['h'] ? $coordinates['h'] : $info[1];

        if(!$w || !$h){
            throw new \exceptions\DataLayerException('Invalid image params');
        }

        $x = isset($coordinates['x']) ? $coordinates['x'] : 0;
        $y = isset($coordinates['y']) ? $coordinates['y'] : 0;

        $newWidth = $imageSettings['width'];
        $newHeight = $imageSettings['height'];

        $oldImage = self::createImageResource($file, $info['mime']);
        $newImage = imagecreatetruecolor($newWidth, $newHeight);

        //imagecopyresampled($newImage, $oldImage, $newX, $newY, $oldX, $oldY, $newW, $newH, $oldW, $oldH);
        imagecopyresampled($newImage, $oldImage, 0, 0, $x, $y, $newWidth, $newHeight, $w, $h);

        if(!self::createImage($newImage, $file, $info['mime'])){
            throw new \exceptions\DataLayerException('Error creating image');
        }

        imagedestroy($oldImage);

        return self::buildAddress($image);*/
    }

    private static function createImageResource($image, $mime) {
        foreach(self::$allowedFormats as $format => $functions){
            if($mime == $format){
                $functionName = $functions[0];
                return $functionName($image);
            }
        }
        return null;
    }

    private static function createImage($image, $file, $mime) {
        foreach(self::$allowedFormats as $format => $functions){
            if($mime == $format){
                $functionName = $functions[1];
                return $functionName($image, $file);
            }
        }
        return null;
    }
}