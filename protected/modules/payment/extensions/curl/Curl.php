<?php

namespace application\modules\payment\extensions\curl;

/**
 * Created by PhpStorm.
 * User: dima
 * Date: 25.04.14
 * Time: 15:21
 * @property string $header
 * @property string $body
 * @property string $requestMethod
 * @property string $dataType
 * @property string $xmlParserType
 * @property \stdClass $result
 * @property string $error
 */
class Curl extends \CComponent
{
    private static $_requestMethods = [
        'get',
        'post',
        'content'
    ];

    private static $_dataTypes = [
        'json',
        'xml',
    ];

    private static $_xmlParsersTypes = [
        'object',
        'array'
    ];

    public $requestHead = false;
    public $requestBody = true;

    protected $url;
    protected $method;
    protected $host;
    protected $requestMethod = 'post';
    protected $port;
    protected $header;
    protected $body;
    protected $protocol = 'http';
    protected $error;
    protected $result;
    protected $dataType = 'json';
    protected $xmlParserType = 'object';

    public function init()
    {

    }

    public function open($url='', $method='curl', $port = 80, $dataType = null)
    {
        if($dataType === null)
            $dataType = $this->dataType;
        else
            $this->dataType = $dataType;

        $urlParts = explode('://', $url);
        if(!isset($urlParts[0]) || !isset($urlParts[1])){
            $this->error = 'Неправильный адрес запроса.';
        }

        if(!$this->error){
            $this->method = $method;
            $this->url = $url;
            $this->port = $port;
            $this->protocol = $urlParts[0];

            if(!$this->dataType)
                $this->dataType = $dataType;

            if($method == 'socket'){
                $hostAndMethod = explode('/', $urlParts[1]);
                $this->host = array_shift($hostAndMethod);
                foreach($hostAndMethod as $name){
                    if(strlen($name)>1) $this->requestMethod .= '/'.$name;
                }
                if($port == 80 && (strpos($url, 'https') !== false || strpos($url, 'ssl') !== false)){
                    $this->protocol = 'ssl';
                    $this->port = 443;
                }
            }
        }

        return $this;
    }

    public function setHeader($header = [])
    {
        $this->header = (array)$header;
    }

    public function setBody($body = '')
    {
        $this->body = (string)$body;
    }

    public function setRequestMethod($method)
    {
        $method = strtolower($method);
        if(in_array($method, self::$_requestMethods))
            $this->requestMethod = $method;
    }

    public function setDataType($type)
    {
        $type = strtolower($type);
        if(in_array($type, self::$_dataTypes))
            $this->dataType = $type;
    }

    public function setXmlParserType($type)
    {
        $type = strtolower($type);
        if(in_array($type, self::$_xmlParsersTypes))
            $this->xmlParserType = $type;
    }

    public function getError()
    {
        return $this->error;
    }

    public function getResult()
    {
        return $this->result;
    }

    public function sendRequest()
    {
        $methodName = 'send'.ucfirst($this->method).ucfirst($this->requestMethod).'Request';

        if(method_exists($this, $methodName))
            return $this->$methodName();
        else
            $this->error = 'Не существует такого метода: "'.$this->method.'"';

        return false;
    }

    private function sendSocketPostRequest()
    {
        try{
            if(!$this->header){
                $this->header="POST ".$this->requestMethod." HTTP/1.1\r\nHost: ".$this->host."\r\nContent-Type: application/x-www-form-urlencoded\r\nContent-Length: ".
                    (strlen($this->body));
            }
            $socket=fsockopen($this->url, $this->port);
            fwrite($socket,$this->header."\r\n\r\n".$this->body."\r\n\r\n");
            $result='';
            while(!feof($socket)){
                $result.=fgets($socket, 4098);
            };

            fclose($socket);

            return $this->readAnswer($result);
        }catch(\Exception $e){
            $this->error = 'Не удалось получить данные от сервера: '.$e->getMessage();
        }
        return false;
    }

    private function sendCurlPostRequest()
    {
        try{
            $curl = curl_init();

            if($this->protocol == 'ssl' || $this->protocol == 'https'){
                curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
                curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
            }

            curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
            curl_setopt($curl, CURLOPT_URL, $this->url);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
            curl_setopt($curl, CURLOPT_HEADER, $this->requestHead);
            if($this->header)
                curl_setopt($curl, CURLOPT_HTTPHEADER, $this->header);
            curl_setopt($curl, CURLOPT_POST, true);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $this->body);

            $out = curl_exec($curl);
            curl_close($curl);

            return $this->readAnswer($out);
        }catch(\Exception $e){
            $this->error = 'Не удалось получить данные от сервера: '.$e->getMessage();
        }
        return false;
    }

    private function sendCurlGetRequest()
    {
        try{
            $curl = curl_init();

            curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
            curl_setopt($curl, CURLOPT_URL, $this->url);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
            curl_setopt($curl, CURLOPT_HEADER, $this->requestHead);
            if($this->header)
                curl_setopt($curl, CURLOPT_HTTPHEADER, $this->header);

            $out = curl_exec($curl);
            curl_close($curl);

            return $this->readAnswer($out);
        }catch(\Exception $e){
            $this->error = 'Не удалось получить данные от сервера: '.$e->getMessage();
        }

        return false;
    }

    private function sendCurlContentRequest()
    {
        try{
            $content = file_get_contents($this->url);
            return $this->readAnswer($content);
        }catch(\Exception $e){
            $this->error = 'Не удалось получить данные от сервера: '.$e->getMessage();
        }

        return false;
    }

    private function readAnswer($answer)
    {
        if(!$answer){
            $this->error = 'Не удалось получить данные от сервера';
            return false;
        }

        if($this->dataType == 'json'){
            $this->result = $this->parseJson($answer);
        }elseif($this->dataType == 'xml'){
            $this->result = $this->parseXml($answer);
        }else{
            $this->result = $answer;
        }

        return (bool)$this->result;
    }

    private function parseJson($answer)
    {
        if($this->result = json_decode($answer))
            return true;
        if($this->result = json_decode(substr($answer, strpos($answer, '{'))))
            return true;

        $this->error = 'Не удалось прочитать ответ сервера';
        return false;
    }

    private function parseXml($answer)
    {
        try{
            $xml = simplexml_load_string($answer);
            if(!$xml){
                $this->error = 'Не удалось прочитать XML-документ';
            }

            $json = json_encode($xml);
            return json_decode($json, $this->xmlParserType == 'array');
        }catch(\Exception $e){
            $this->error = 'Не удалось получить данные от сервера: '.$e->getMessage();
        }
        return false;
    }
}