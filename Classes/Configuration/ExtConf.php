<?php
namespace JWeiland\Maps2\Configuration;

/**
 * This file is part of the TYPO3 CMS project.
 *
 * It is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License, either version 2
 * of the License, or any later version.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 * The TYPO3 project - inspiring people to share!
 */
use TYPO3\CMS\Core\SingletonInterface;

/**
 * Class ExtConf
 *
 * @category Configuration
 * @package  Maps2
 * @author   Stefan Froemken <projects@jweiland.net>
 * @license  http://www.gnu.org/licenses/gpl.html GNU General Public License
 * @link     https://github.com/jweiland-net/maps2
 */
class ExtConf implements SingletonInterface
{

    /**
     * Use https
     *
     * @var boolean
     */
    protected $useHttps = false;

    /**
     * google maps library
     *
     * @var string
     */
    protected $googleMapsLibrary = '';

    /**
     * default latitude
     *
     * @var float
     */
    protected $defaultLatitude = 0;

    /**
     * default longitude
     *
     * @var float
     */
    protected $defaultLongitude = 0;

    /**
     * default radius
     *
     * @var integer
     */
    protected $defaultRadius = 0;

    /**
     * stroke color
     *
     * @var string
     */
    protected $strokeColor = '';

    /**
     * stroke opacity
     *
     * @var float
     */
    protected $strokeOpacity = 0;

    /**
     * stroke weight
     *
     * @var integer
     */
    protected $strokeWeight = 0;

    /**
     * fill color
     *
     * @var string
     */
    protected $fillColor = '';

    /**
     * fill opacity
     *
     * @var float
     */
    protected $fillOpacity = 0;

    /**
     * constructor of this class
     * This method reads the global configuration and calls the setter methods
     */
    public function __construct()
    {
        // get global configuration
        $extConf = unserialize($GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf']['maps2']);
        if (is_array($extConf) && count($extConf)) {
            // call setter method foreach configuration entry
            foreach ($extConf as $key => $value) {
                $methodName = 'set' . ucfirst($key);
                if (method_exists($this, $methodName)) {
                    $this->$methodName($value);
                }
            }
        }
    }

    /**
     * getter for useHttps
     *
     * @return boolean
     */
    public function getUseHttps()
    {
        if (empty($this->useHttps)) {
            return false;
        } else {
            return $this->useHttps;
        }
    }

    /**
     * setter for useHttps
     *
     * @param boolean $useHttps
     * @return void
     */
    public function setUseHttps($useHttps)
    {
        $this->useHttps = (bool) $useHttps;
    }

    /**
     * getter for googleMapsLibrary
     *
     * @return string
     */
    public function getGoogleMapsLibrary()
    {
        if (empty($this->googleMapsLibrary)) {
            $library = 'http://maps.google.com/maps/api/js?v=3.10&sensor=false';
        } else {
            $library = $this->googleMapsLibrary;
        }
        // $parts: 0 = full string; 1 = s or empty; 2 = needed url
        if (preg_match('|^http(s)?://(.*)$|i', $library, $parts)) {
            if ($this->getUseHttps()) {
                return 'https://' . $parts[2];
            } else {
                return 'http://' . $parts[2];
            }
        } else {
            throw new \InvalidArgumentException(
                'Google Maps library path does not start with http or https.
                Please reconfigure maps2 in ExtensionManager',
                1443676365
            );
        }
    }

    /**
     * setter for google maps library
     *
     * @param string $googleMapsLibrary
     * @return void
     */
    public function setGoogleMapsLibrary($googleMapsLibrary)
    {
        $this->googleMapsLibrary = $googleMapsLibrary;
    }

    /**
     * getter for defaultLatitude
     *
     * @return float
     */
    public function getDefaultLatitude()
    {
        if (empty($this->defaultLatitude)) {
            return 0.00;
        } else {
            return $this->defaultLatitude;
        }
    }

    /**
     * setter for defaultLatitude
     *
     * @param float $defaultLatitude
     * @return void
     */
    public function setDefaultLatitude($defaultLatitude)
    {
        $this->defaultLatitude = (float) $defaultLatitude;
    }

    /**
     * getter for defaultLongitude
     *
     * @return float
     */
    public function getDefaultLongitude()
    {
        if (empty($this->defaultLongitude)) {
            return 0.00;
        } else {
            return $this->defaultLongitude;
        }
    }

    /**
     * setter for defaultLongitude
     *
     * @param float $defaultLongitude
     * @return void
     */
    public function setDefaultLongitude($defaultLongitude)
    {
        $this->defaultLongitude = (float) $defaultLongitude;
    }

    /**
     * getter for defaultRadius
     *
     * @return integer
     */
    public function getDefaultRadius()
    {
        if (empty($this->defaultRadius)) {
            return 250;
        } else {
            return $this->defaultRadius;
        }
    }

    /**
     * setter for defaultRadius
     *
     * @param integer $defaultRadius
     * @return void
     */
    public function setDefaultRadius($defaultRadius)
    {
        $this->defaultRadius = (int) $defaultRadius;
    }

    /**
     * getter for strokeColor
     *
     * @return string
     */
    public function getStrokeColor()
    {
        if (empty($this->strokeColor)) {
            return '#FF0000';
        } else {
            return $this->strokeColor;
        }
    }

    /**
     * setter for strokeColor
     *
     * @param string $strokeColor
     * @return void
     */
    public function setStrokeColor($strokeColor)
    {
        $this->strokeColor = (string) $strokeColor;
    }

    /**
     * getter for strokeOpacity
     *
     * @return float
     */
    public function getStrokeOpacity()
    {
        if (empty($this->strokeOpacity)) {
            return 0.8;
        } else {
            return $this->strokeOpacity;
        }
    }

    /**
     * setter for strokeOpacity
     *
     * @param float $strokeOpacity
     * @return void
     */
    public function setStrokeOpacity($strokeOpacity)
    {
        $this->strokeOpacity = (float) $strokeOpacity;
    }

    /**
     * getter for strokeWeight
     *
     * @return integer
     */
    public function getStrokeWeight()
    {
        if (empty($this->strokeWeight)) {
            return 2;
        } else {
            return $this->strokeWeight;
        }
    }

    /**
     * setter for strokeWeight
     *
     * @param integer $strokeWeight
     * @return void
     */
    public function setStrokeWeight($strokeWeight)
    {
        $this->strokeWeight = (int) $strokeWeight;
    }

    /**
     * getter for fillColor
     *
     * @return string
     */
    public function getFillColor()
    {
        if (empty($this->fillColor)) {
            return '#FF0000';
        } else {
            return $this->fillColor;
        }
    }

    /**
     * setter for fillColor
     *
     * @param string $fillColor
     * @return void
     */
    public function setFillColor($fillColor)
    {
        $this->fillColor = (string) $fillColor;
    }

    /**
     * getter for fillOpacity
     *
     * @return float
     */
    public function getFillOpacity()
    {
        if (empty($this->fillOpacity)) {
            return 0.35;
        } else {
            return $this->fillOpacity;
        }
    }

    /**
     * setter for fillOpacity
     *
     * @param float $fillOpacity
     * @return void
     */
    public function setFillOpacity($fillOpacity)
    {
        $this->fillOpacity = (float) $fillOpacity;
    }
}
