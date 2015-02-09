<?php
/**
 * Created by PhpStorm.
 * User: rem
 * Date: 13.08.14
 * Time: 11:16
 */

namespace application\models\entities;

use core\ImageWorker;

class AdsContentEntity extends AbstractEntity
{
    public $url;
    public $showUrl;
    public $caption;
    public $description;
    public $buttonText;
    public $imageUrl;
    public $imageFile;
    public $showButton = true;
    public $clickUrl;
    public $cropParams;
    public $admin_message;

    private $_animation = false;

    public function setCropParams($params)
    {
        if($params){
            if(is_string($params)){
                $this->cropParams = \CJSON::decode($params);

                if(!$this->cropParams){
                    $this->cropParams = [];

                    $params = str_replace('{', '', $params);
                    $params = str_replace('}', '', $params);
                    $params = explode(',', $params);

                    foreach($params as $value){
                        $tmp = explode(':', $value);
                        if(isset($tmp[1])){
                            $name = $tmp[0];
                            if(strpos($name, '&') !== false){
                                $name = str_replace('&', '', $name);
                                $name = str_replace('quot;', '', $name);
                            }

                            $this->cropParams[$name] = $tmp[1];
                        }
                    }
                }
            }elseif(is_array($params)){
                $this->cropParams = $params;
            }
        }
    }

    public function setImage($image)
    {
        $image = (string)$image;
        $result = true;

        if($image){
            if(!file_exists($image)){
                $this->_error = 'Не найден файл: '.$image;
                return false;
            }

            if(!$this->cropParams){
                $this->_error = 'Не переданы параметры обрезки изображения';
                return false;
            }

            try{
                $result = $this->cropImage($image, $this->cropParams['x1'], $this->cropParams['y1'], $this->cropParams['width'], $this->cropParams['height']);
                $this->imageUrl = ImageWorker::uploadImage($image);
            }catch(\Exception $e){
                $this->_error = $e->getMessage();
                return false;
            }
        }

        return $result;
    }

    public function getImageUrl()
    {
        if($this->imageUrl && isset($this->imageUrl['image']))
            return '/images'.str_replace('\\', '/', $this->imageUrl['image']);
        else
            return '/images/files/teaser_img.jpg';
    }

    /**
     * @return bool
     */
    public function getIsAnimation()
    {
        return $this->_animation;
    }

    public function setAdminMessage($message)
    {
        $message = (string)$message;
        $message = trim($message);
        $this->admin_message = $message ? htmlspecialchars($message) : null;
    }

    public function getAdminMessage()
    {
        return trim($this->admin_message);
    }

    private function cropImage($image, $x_o, $y_o, $w_o, $h_o)
    {
        try{
            $imageMagick = new \Imagick($image);
            $imageMagick = $imageMagick->coalesceImages();

            $i = 0;
            foreach($imageMagick as $frame){
                $frame->cropImage($w_o, $h_o, $x_o, $y_o);
                $frame->thumbnailImage($w_o, $h_o);
                $frame->setImagePage($w_o, $h_o, 0, 0);
                $i++;
            }

            if($i > 1)
                $this->_animation = true;

            $imageMagick = $imageMagick->deconstructImages();
            $result = $imageMagick->writeImages($image, true);
        }catch(\Exception $e){
            $this->_error =$e->getMessage();
            $result = false;
        }

        return $result;
    }
}