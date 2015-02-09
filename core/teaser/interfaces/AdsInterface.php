<?php
/**
 * Created by PhpStorm.
 * User: t0m
 * Date: 06.04.14
 * Time: 13:48
 */

namespace interfaces;


interface AdsInterface {

    public function __construct(array $data  = []);

    public function getAdsType();

    public function getSerialized();

    public function getFields();
} 