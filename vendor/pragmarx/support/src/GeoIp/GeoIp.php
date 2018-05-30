<?php

namespace PragmaRX\Support;

use GeoIp2\Database\Reader as GeoIpReader;
use PragmaRX\Support\GeoIp\GeoIp2;
use PragmaRX\Support\GeoIp\GeoIp1;

class GeoIp
{
    private $geoIp;

    public function __construct() {
        $this->geoIp = $this->getGeoIpInstance();
    }

    public function searchAddr($addr) {
        return $this->geoIp->searchAddr($addr);
    }

    /**
     * @return boolean
     */
    public function isEnabled() {
        return $this->geoIp->isEnabled();
    }

    /**
     * @param boolean $enabled
     */
    public function setEnabled($enabled) {
        return $this->geoIp->setEnabled($enabled);
    }

    public function isGeoIpAvailable() {
        return $this->geoIp->isGeoIpAvailable();
    }

    private function getGeoIpInstance() {
        if (class_exists(GeoIpReader::class))
        {
            return new GeoIp2();
        }

        return new GeoIp1();
    }
}
