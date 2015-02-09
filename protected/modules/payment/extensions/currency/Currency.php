<?php

namespace application\modules\payment\extensions\currency;

/**
 * Created by PhpStorm.
 * User: rem
 * Date: 17.07.14
 * Time: 14:43
 * @property string $error;
 * @property array $nbuRates;
 */
class Currency extends \CComponent
{
    const DEFAULT_CURRENCY = 'WMZ';
    const DEFAULT_FROM_CURRENCY = 'WMR';

    private static $_currencies = [
        'WMZ' => 'WMZ',
        'WMR' => 'WMR',
        'YAR' => 'Яндекс-рубль',
        'CARD' => '$',
        'QIWI' => 'Доллар QIWI',
        'USD' => '$',
        'RUR' => 'Руб.',
    ];

    private static $_allowedFromCurrencies = [
        'RUR' => [
            'url' => 'http://www.cbr.ru/scripts/XML_daily.asp?date_req=',
            'requestMethod' => 'content',
            'dataType' => 'xml',
        ],
        'WMR' => [
            'url' => 'https://wm.exchanger.ru/asp/XMLbestRatesMinus.asp',
            'requestMethod' => 'content',
            'dataType' => 'xml',
        ],
    ];

    private $_error;
    private $_nbuRates;
    private $_from;
    private $_to;

    public $systemCurrency;
    public $round = 8;
    public $formatterRound = 2;

    public function init()
    {
        if(!$this->checkCode($this->systemCurrency))
            $this->systemCurrency = self::DEFAULT_CURRENCY;

        self::$_allowedFromCurrencies['RUR']['url'] .= date('d/m/Y');
        $this->_from = self::DEFAULT_FROM_CURRENCY;
        $this->_to = self::DEFAULT_CURRENCY;
        $this->round = intval($this->round);

        if($this->systemCurrency === null)
            $this->systemCurrency = self::DEFAULT_CURRENCY;
    }

    public function getRate($money, $from = null, $to = null)
    {
        if($from === null)
            $from = self::DEFAULT_FROM_CURRENCY;

        if($to === null)
            $to = $this->systemCurrency;

        if(!$this->checkCode($from) || !$this->checkCode($to))
            return null;

        $money = floatval($money);

        if(!$money || $from == $to)
            return $money;

        $this->_from = $from;
        $this->_to = $to;

        $methodName = 'from'.strtoupper($from);

        return $this->$methodName($money);
    }

    public function getFormattedRate($money, $from, $to = null)
    {
        $money = $this->getRate($money, $from, $to);
        return $this->getFormatted($money, $to);
    }

    public function getFormatted($money, $code = null)
    {
        if($code === null)
            $code = $this->systemCurrency;
        elseif(!$this->checkCode($code))
            return null;

        return round(floatval($money), $this->formatterRound).' '.self::$_currencies[$code];
    }

    public function getNbuRates()
    {
        if($this->_nbuRates === null){
            if(!$this->checkAllowedCode())
                return null;

            $this->_nbuRates = [];
            $curl = $this->getCurl();

            $currencyParams = self::$_allowedFromCurrencies[$this->_from];

            $curl->open($currencyParams['url']);
            $curl->requestMethod = $currencyParams['requestMethod'];
            $curl->dataType = $currencyParams['dataType'];

            if($curl->sendRequest())
                $this->_nbuRates = $curl->result;
            else
                $this->_error = $curl->error;
        }

        return $this->_nbuRates;
    }

    public function getError()
    {
        return $this->_error;
    }

    private function fromRUR($money)
    {
        if($this->nbuRates && isset($this->nbuRates->Valute)){
            foreach($this->nbuRates->Valute as $valute){
                if($valute->CharCode == $this->_to){
                    $valute = (array)$valute;

                    if(isset($valute['Value'])){
                        $money = round($money/$valute['Value'], $this->round);
                        break;
                    }
                }
            }
        }

        return $money;
    }

    private function fromWMR($money)
    {
        if($this->nbuRates && isset($this->nbuRates->row)){
            foreach($this->nbuRates->row as $row){
                $row = (array)$row;
                if($row['@attributes']->Direct == $this->_from.' - '.$this->_to){
                    $rate = $row['@attributes']->BaseRate;
                    $rate = str_replace('+', '', $rate);
                    $rate = str_replace('-', '', $rate);
                    $rate = floatval($rate);

                    if($rate !== 0)
                        $money = round($money/$rate, $this->round);
                    break;
                }
            }
        }

        return $money;
    }

    private function checkCode($code)
    {
        if(!isset(self::$_currencies[$code])){
            $this->_error = 'В системе нет валюты "'.$code.'"';
            return false;
        }else{
            return true;
        }
    }

    private function checkAllowedCode($code = null)
    {
        if($code === null)
            $code = $this->_from;

        if(!isset(self::$_allowedFromCurrencies[$code])){
            $this->error = 'Система не умеет переводить из валюты "'.self::$_currencies[$code].'"';
            return false;
        }else{
            return true;
        }
    }

    private function getCurl()
    {
        return \Yii::app()->curl;
    }
}