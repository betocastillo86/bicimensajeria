<?php

require_once('GoogleMaps.php');

/**
 * Created by PhpStorm.
 * User: Beto
 * Date: 11/12/2015
 * Time: 9:46 PM
 */
class GoogleMapsDirections extends GoogleMaps
{
    /**
     * Constructor. The request is not executed until `geocode()` is called.
     *
     * @param  string $format optional response format (JSON default)
     * @param  bool|string $sensor deprecated as of v2.3.0
     */
    public function __construct($format = self::FORMAT_JSON, $sensor = false) {
        $this->setFormat($format);
        $this->setMode('driving');
        //$this->setMode('bicycling');
    }

    /**
     * Path portion of the Google Geocoding API URL.
     */
    const URL_PATH = "/maps/api/directions/";

    /**
     * HTTP URL of the Google Geocoding API.
     */
    const URL_HTTP = "http://maps.googleapis.com/maps/api/directions/";

    /**
     * HTTPS URL of the Google Geocoding API.
     */
    const URL_HTTPS = "https://maps.googleapis.com/maps/api/directions/";

    private $originLon;

    private $originLat;





    private $destinationLon;

    private $destinationLat;

    private $destination;

    private $mode;

    /**
     * @return mixed
     */
    public function getMode()
    {
        return $this->mode;
    }

    /**
     * @param mixed $mode
     */
    public function setMode($mode)
    {
        $this->mode = $mode;
    }

    /**
     * @return mixed
     */
    public function getOrigin()
    {
        return $this->origin;
    }

    /**
     * @param mixed $origin
     */
    public function setOrigin($origin)
    {
        $this->origin = $origin;
    }

    /**
     * @return mixed
     */
    public function getDestination()
    {
        return $this->destination;
    }

    /**
     * @param mixed $destination
     */
    public function setDestination($destination)
    {
        $this->destination = $destination;
    }


    /**
     * @return mixed
     */
    public function getOriginLon()
    {
        return $this->originLon;
    }

    /**
     * @param mixed $originLon
     */
    public function setOriginLon($originLon)
    {
        $this->originLon = $originLon;
    }

    /**
     * @return mixed
     */
    public function getOriginLat()
    {
        return $this->originLat;
    }

    /**
     * @param mixed $originLat
     */
    public function setOriginLat($originLat)
    {
        $this->originLat = $originLat;
    }

    /**
     * @return mixed
     */
    public function getDestinationLon()
    {
        return $this->destinationLon;
    }

    /**
     * @param mixed $destinationLon
     */
    public function setDestinationLon($destinationLon)
    {
        $this->destinationLon = $destinationLon;
    }

    /**
     * @return mixed
     */
    public function getDestinationLat()
    {
        return $this->destinationLat;
    }

    /**
     * @param mixed $destinationLat
     */
    public function setDestinationLat($destinationLat)
    {
        $this->destinationLat = $destinationLat;
    }


    /****
     * @param $latOrigin
     * @param $lonOrigin
     * @param $latDestination
     * @param $lonDestination
     * @param  bool $raw whether to return the raw (string) response
     * @param  resource $context stream context from `stream_context_create()`
     * @return array|mixed|object|SimpleXMLElement|string
     */
    function route($originLat, $originLon, $destinationLat, $destinationLon, $raw = false,  $context = null)
    {

        //Setea todas las propiedades
        $this->setOriginLat($originLat);
        $this->setOriginLon($originLon);
        $this->setDestinationLat($destinationLat);
        $this->setDestinationLon($destinationLon);

        $response = file_get_contents($this->getRequestUrl($this->directionsQueryString(),self::URL_PATH, false), false, $context);

        if ($raw) {
            return $response;
        }
        elseif ($this->isFormatJson()) {
            return json_decode($response, true);
        }
        elseif ($this->isFormatXml()) {
            return new SimpleXMLElement($response);
        }
        else {
            return $response;
        }
    }



    /**
     * Build the query string with all set parameters of the geocode request.
     *
     * @link   https://developers.google.com/maps/documentation/geocoding/intro#GeocodingRequests
     * @return string encoded query string of the geocode request
     */
    private function directionsQueryString() {
        $queryString = array();

        // One of the following is required.
        //$address = $this->getAddress();
        $queryString['origin'] = $this->getLatitudeLongitude($this->getOriginLat(), $this->getOriginLon());
        $queryString['destination'] = $this->getLatitudeLongitude( $this->getDestinationLat(), $this->getDestinationLon());
        $queryString['mode'] = $this->getMode();

        // Optional parameters.
        /*$queryString['region'] = $this->getRegion();
        $queryString['language'] = $this->getLanguage();
        $queryString['result_type'] = $this->getResultTypeFormatted();
        $queryString['location_type'] = $this->getLocationTypeFormatted();
        $queryString['bounds'] = $this->getBounds();*/

        // Required.
        //$queryString['sensor'] = $this->getSensor();

        // Remove any unset parameters.
        $queryString = array_filter($queryString);

        // The signature is added later using the path + query string.
        if ($this->isBusinessClient()) {
            $queryString['client'] = $this->getClientId();
        }
        elseif ($this->getApiKey()) {
            $queryString['key'] = $this->getApiKey();
        }

        // Convert array to proper query string.
        return http_build_query($queryString);
    }








}