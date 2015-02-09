<?php
/**
 * Created by PhpStorm.
 * User: Дима
 * Date: 12.06.14
 * Time: 11:04
 */

class ImageLoader
{
    private $defaultPath;
    private $defaultExt;
    private $_errors = [];
    private $_image;

    public function __construct($defaultExt = '.jpg')
    {
        $this->defaultExt = $defaultExt;
        $this->defaultPath = dirname(__FILE__).DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'images'.DIRECTORY_SEPARATOR.'tmp';
    }

    public function downloadFavicon($url, $fileName = '')
    {
        $url = (string)$url;
        if(!$url){
            $this->setError('invalid url');
            return false;
        }

        $last = $url[strlen($url)-1];

        if($last !== '/')
            $url .= '/';

        return $this->downloadImage($url.'favicon.ico', $fileName);
    }

    public function downloadImage($imageUrl, $imageName = '')
    {
        $imageUrl = (string)$imageUrl;

        if(!$imageUrl || strlen($imageUrl) < 3){
            $this->setError('invalid image url');
            return false;
        }

        if(strpos($imageUrl, 'http') !== 0)
            $imageUrl = 'http://'.$imageUrl;

        $urlParts = explode('/', $imageUrl);

        if(!isset($urlParts[2]) || !isset($urlParts[3])){
            $this->setError('invalid image url');
            return false;
        }

        try{
            $fileName = (string)$imageName;
            if(!$fileName)
                $fileName = $this->defaultPath.DIRECTORY_SEPARATOR.time().'-'.$urlParts[2].'-'.end($urlParts);
            elseif(strpos($fileName, '/') === false && strpos($fileName, '\\') === false)
                $fileName = $this->defaultPath.DIRECTORY_SEPARATOR.$fileName;


            if(!$fp = fopen($fileName, "w")){
                $this->setError('directory '.$this->defaultPath.' or file '.$fileName.' is not writable');
                return false;
            }

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 6.1) AppleWebKit/537.11 (KHTML, like Gecko) Chrome/23.0.1271.95 Safari/537.11');

            curl_setopt($ch, CURLOPT_URL, $imageUrl);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_FILE, $fp);

            if(!curl_exec($ch)){
                curl_close($ch);
                fclose($fp);
                unlink($fileName);

                $this->setError('can not open link: '.$imageUrl);
                return false;
            }

            fflush($fp);
            fclose($fp);
            curl_close($ch);

            $result = file_get_contents($fileName);
            $result = strpos($result, '<!DOCTYPE HTML>') === false;

            if(!$result){
                $this->setError('image was not found in server');
                unlink($fileName);
                return false;
            }

            $this->_image = $fileName;
            return true;
        }catch(Exception $e){
            $this->setError(var_export($e->getMessage()));
            return false;
        }

    }

    public function getImage()
    {
        return $this->_image;
    }

    public function getError()
    {
        if($this->_errors)
            return implode('; ', $this->_errors);
        else
            return '';
    }

    public function setPath($path)
    {
        $path = (string)$path;
        if(!$path){
            $this->setError('invalid path');
            return false;
        }
        if(!is_dir($path)){
            $this->setError('dir is not exist');
            return false;
        }

        $this->defaultPath = $path;
        return true;
    }

    private function setError($message)
    {
        if($message)
            $this->_errors[] = $message;
    }
}