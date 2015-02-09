<?php
/**
 * Created by PhpStorm.
 * User: t0m
 * Date: 21.04.14
 * Time: 23:26
 */

namespace core\clickFilter;


use core\ViewerSession;

interface Filter {
    public function setAdsData(array $adsData);

    public function setBlockData(array $blockData);

    public function setViewerSession(ViewerSession $viewerSession);

    public function filter();
} 