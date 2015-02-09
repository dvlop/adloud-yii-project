<?php
/**
 * Created by PhpStorm.
 * User: t0m
 * Date: 21.04.14
 * Time: 23:07
 */

namespace core\clickFilter;

use core\ViewerSession;

abstract class FilterAbstract implements Filter {

    protected $adsData;
    protected $blockData;
    /**
     * @var ViewerSession
     */
    protected $viewerSession;

    public function setAdsData(array $adsData)
    {
        $this->adsData = $adsData;
    }

    public function setBlockData(array $blockData)
    {
        $this->blockData = $blockData;
    }

    public function setViewerSession(ViewerSession $viewerSession)
    {
        $this->viewerSession = $viewerSession;
    }

    abstract public function filter();

} 