<?php

/**
 * Created by PhpStorm.
 * User: Beto
 * Date: 11/12/2015
 * Time: 9:50 PM
 */
class GoogleMaps
{
    /**
     * Domain portion of the Google Geocoding API URL.
     */
    const URL_DOMAIN = "maps.googleapis.com";

    /**
     * JSON response format.
     */
    const FORMAT_JSON = "json";

    /**
     * XML response format.
     */
    const FORMAT_XML = "xml";

    /**
     * No errors occurred, the address was successfully parsed and at least one
     * geocode was returned.
     */
    const STATUS_SUCCESS = "OK";

    /**
     * Geocode was successful, but returned no results.
     */
    const STATUS_NO_RESULTS = "ZERO_RESULTS";

    /**
     * Over limit of 2,500 (100,000 if premier) requests per day.
     */
    const STATUS_OVER_LIMIT = "OVER_QUERY_LIMIT";

    /**
     * Request denied, usually because of missing sensor parameter.
     */
    const STATUS_REQUEST_DENIED = "REQUEST_DENIED";

    /**
     * Invalid request, usually because of missing parameter that's required.
     */
    const STATUS_INVALID_REQUEST = "INVALID_REQUEST";

    /**
     * Unnown server error. May succeed if tried again.
     */
    const STATUS_UNKNOWN_ERROR = "UNKNOWN_ERROR";



    /**
     * A precise street address.
     */
    const TYPE_STREET_ADDRESS = "street_address";

    /**
     * A named route (such as "US 101").
     */
    const TYPE_ROUTE = "route";

    /**
     * A major intersection, usually of two major roads.
     */
    const TYPE_INTERSECTION = "intersection";

    /**
     * A political entity, usually of some civil administration.
     */
    const TYPE_POLITICAL = "political";

    /**
     * A national political entity (country). The highest order type returned.
     */
    const TYPE_COUNTRY = "country";

    /**
     * A first-order civil entity below country (states within the US).
     */
    const TYPE_ADMIN_AREA_1 = "administrative_area_level_1";

    /**
     * A second-order civil entity below country (counties within the US).
     */
    const TYPE_ADMIN_AREA_2 = "administrative_area_level_2";

    /**
     * A third-order civil entity below country.
     */
    const TYPE_ADMIN_AREA_3 = "administrative_area_level_3";

    /**
     * A fourth-order civil entity below country.
     */
    const TYPE_ADMIN_AREA_4 = "administrative_area_level_4";

    /**
     * A fifth-order civil entity below country.
     */
    const TYPE_ADMIN_AREA_5 = "administrative_area_level_5";

    /**
     * A commonly-used alternative name for the entity.
     */
    const TYPE_COLLOQUIAL_AREA = "colloquial_area";

    /**
     * An incorporated city or town.
     */
    const TYPE_LOCALITY = "locality";

    /**
     * A specific type of Japanese locality.
     */
    const TYPE_WARD = "ward";

    /**
     * A first-order civil entity below a locality.
     */
    const TYPE_SUB_LOCALITY = "sublocality";

    /**
     * A named neighborhood.
     */
    const TYPE_NEIGHBORHOOD = "neighborhood";

    /**
     * A named location, usually a building or collection of buildings.
     */
    const TYPE_PREMISE = "premise";

    /**
     * A first-order entity below a named location, usually a single building
     * within a collection of building with a common name.
     */
    const TYPE_SUB_PREMISE = "subpremise";

    /**
     * A postal code as used to address mail within the country.
     */
    const TYPE_POSTAL_CODE = "postal_code";

    /**
     * A prominent natural feature.
     */
    const TYPE_NATURAL_FEATURE = "natural_feature";

    /**
     * An airport.
     */
    const TYPE_AIRPORT = "airport";

    /**
     * A named park.
     */
    const TYPE_PARK = "park";

    /**
     * A named point of interest that doesn't fit within another category.
     */
    const TYPE_POINT_OF_INTEREST = "point_of_interest";

    /**
     * A floor of a building address.
     */
    const TYPE_FLOOR = "floor";

    /**
     * A place that has not yet been categorized.
     */
    const TYPE_ESTABLISHMENT = "establishment";

    /**
     * A parking lot or parking structure.
     */
    const TYPE_PARKING = "parking";

    /**
     * A specific postal box.
     */
    const TYPE_POST_BOX = "post_box";

    /**
     * A grouping of geographic areas used for mailing addresses in some
     * countries.
     */
    const TYPE_POSTAL_TOWN = "postal_town";

    /**
     * A room of a building address.
     */
    const TYPE_ROOM = "room";

    /**
     * A precise street number.
     */
    const TYPE_STREET_NUMBER = "street_number";

    /**
     * A bus stop.
     */
    const TYPE_BUS_STATION = "bus_station";

    /**
     * A train stop.
     */
    const TYPE_TRAIN_STATION = "train_station";

    /**
     * A public transit stop.
     */
    const TYPE_TRANSIT_STATION = "transit_station";

    /**
     * Helps calculate a more realistic bounding box by taking into account the
     * curvature of the earth's surface.
     */
    const EQUATOR_LAT_DEGREE_IN_MILES = 69.172;

    /**
     * API key to authenticate with.
     *
     * @var string
     */
    private $apiKey;

    /**
     * Response format.
     *
     * @var string
     */
    private $format;

    /**
     * Cryptographic signing key for Business clients.
     *
     * @var string
     */
    private $signingKey;

    /**
     * Client ID for Business clients.
     *
     * @var string
     */
    private $clientId;

    /**
     * Set the API key to authenticate with.
     *
     * @link   https://developers.google.com/console/help/new/#UsingKeys
     * @param  string $apiKey API key
     * @return GoogleMapsGeocoder
     */
    public function setApiKey($apiKey) {
        $this->apiKey = $apiKey;

        return $this;
    }

    /**
     * Get the API key to authenticate with.
     *
     * @link   https://developers.google.com/console/help/new/#UsingKeys
     * @return string API key
     */
    public function getApiKey() {
        return $this->apiKey;
    }


    /**
     * Set the response format.
     *
     * @link   https://developers.google.com/maps/documentation/geocoding/intro#GeocodingResponses
     * @param  string $format response format
     * @return GoogleMapsGeocoder
     */
    public function setFormat($format) {
        $this->format = $format;

        return $this;
    }

    /**
     * Get the response format.
     *
     * @link   https://developers.google.com/maps/documentation/geocoding/intro#GeocodingResponses
     * @return string response format
     */
    public function getFormat() {
        return $this->format;
    }

    /**
     * Set the cryptographic signing key for Business clients.
     *
     * @link   https://developers.google.com/maps/documentation/business/webservices/#cryptographic_signing_key
     * @param  string $signingKey cryptographic signing key
     * @return GoogleMapsGeocoder
     */
    public function setSigningKey($signingKey) {
        $this->signingKey = $signingKey;

        return $this;
    }

    /**
     * Get the cryptographic signing key for Business clients.
     *
     * @link   https://developers.google.com/maps/documentation/business/webservices/#cryptographic_signing_key
     * @return string cryptographic signing key
     */
    public function getSigningKey() {
        return $this->signingKey;
    }

    /**
     * Set the client ID for Business clients.
     *
     * @link   https://developers.google.com/maps/documentation/business/webservices/#client_id
     * @param  string $clientId client ID
     * @return GoogleMapsGeocoder
     */
    public function setClientId($clientId) {
        $this->clientId = $clientId;

        return $this;
    }

    /**
     * Get the client ID for Business clients.
     *
     * @link   https://developers.google.com/maps/documentation/business/webservices/#client_id
     * @return string client ID
     */
    public function getClientId() {
        return $this->clientId;
    }


    /**
     * Build the URL (with query string) of the geocode request.
     *
     * @link   https://developers.google.com/maps/documentation/geocoding/intro#GeocodingRequests
     * @param  bool $https whether to make the request over HTTPS
     * @queryString data to request
     * @return string URL of the geocode request
     */
    protected function getRequestUrl($queryString, $urlPath, $https = false) {
        // HTTPS is required if an API key is set.
        if ($https || $this->getApiKey()) {
            $scheme = "https";
        }
        else {
            $scheme = "http";
        }

        $pathQueryString = $urlPath . $this->getFormat() . "?" . $queryString;

        if ($this->isBusinessClient()) {
            $pathQueryString .= "&signature=" . $this->generateSignature($pathQueryString);
        }

        return $scheme . "://" . self::URL_DOMAIN . $pathQueryString;
    }

    /**
     * Get the latitude/longitude to reverse geocode to the closest address
     * in comma-separated format.
     *
     * @link   https://developers.google.com/maps/documentation/geocoding/intro#ReverseGeocoding
     * @return string|false comma-separated coordinates, or false if not set
     */
    public function getLatitudeLongitude($lat, $lon) {
        $latitude = $lat;
        $longitude = $lon;

        if ($latitude && $longitude) {
            return $latitude . "," . $longitude;
        }
        else {
            return false;
        }
    }


    /**
     * Generate the signature for a Business client geocode request.
     *
     * @link   https://developers.google.com/maps/documentation/business/webservices/auth#digital_signatures
     * @param  string $pathQueryString path and query string of the request
     * @return string Base64 encoded signature that's URL safe
     */
    protected function generateSignature($pathQueryString) {
        $decodedSigningKey = self::base64DecodeUrlSafe($this->getSigningKey());

        $signature = hash_hmac('sha1', $pathQueryString, $decodedSigningKey, true);
        $signature = self::base64EncodeUrlSafe($signature);

        return $signature;
    }

    /**
     * Encode a string with Base64 using only URL safe characters.
     *
     * @param  string $value value to encode
     * @return string encoded value
     */
    private static function base64EncodeUrlSafe($value) {
        return strtr(base64_encode($value), '+/', '-_');
    }

    /**
     * Decode a Base64 string that uses only URL safe characters.
     *
     * @param  string $value value to decode
     * @return string decoded value
     */
    private static function base64DecodeUrlSafe($value) {
        return base64_decode(strtr($value, '-_', '+/'));
    }

    /**
     * Whether the request is for a Business client.
     *
     * @return bool whether the request is for a Business client
     */
    public function isBusinessClient() {
        return $this->getClientId() && $this->getSigningKey();
    }

    /**
     * Whether the response format is JSON.
     *
     * @link   https://developers.google.com/maps/documentation/geocoding/intro#JSON
     * @return bool whether JSON
     */
    public function isFormatJson() {
        return $this->getFormat() == self::FORMAT_JSON;
    }

    /**
     * Whether the response format is XML.
     *
     * @link   https://developers.google.com/maps/documentation/geocoding/intro#XML
     * @return bool whether XML
     */
    public function isFormatXml() {
        return $this->getFormat() == self::FORMAT_XML;
    }
}