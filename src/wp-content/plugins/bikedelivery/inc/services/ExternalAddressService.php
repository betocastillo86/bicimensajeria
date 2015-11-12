<?php
/**
 * Realiza consultas a servicios externos por direcciones
 * Created by PhpStorm.
 * User: Beto
 * Date: 11/12/2015
 * Time: 12:11 PM
 */
require_once(BD_PLUGIN_DIR.'inc/models/LocationAddressModel.php');
require_once (BD_PLUGIN_DIR.'inc/lib/GoogleMapsGeocoder.php');
require_once (BD_PLUGIN_DIR.'inc/entities/ErrorCodes.php');
class ExternalAddressService
{

    private $API_KEY = 'AIzaSyDZ23fBsKzTsXNfoXaBdHawqbsTmoyDuTY';

    function __construct()
    {

    }

    /****
     * Consulta en un proveedor externo (Google) una dirección por texto y devuelve las coordenadas o el mensaje de error
     * @param $addressText direccion en text
     * @return LocationAddressModel Coordenadas de la dirección
     */
    function getLocationByAddressText($addressText)
    {
        //Instancia el objeto que hace los lalamados a Google
        $geocoder = new GoogleMapsGeocoder($addressText);
        $geocoder->setApiKey($this->API_KEY);

        //Realiza el llamado para validar la dirección
        $google_results = $geocoder->geocode() ;

        $response = new LocationAddressModel();

        //Si tiene alguna respuesta
        if(end($google_results['results']) != null)
        {
            //carga los datos en el modelo
            $data_address = end( $google_results['results'])['geometry']['location'];
            $response->success = true;
            $response->latitude = $data_address['lat'];
            $response->longitude = $data_address['lng'];
        }
        else
        {
            $response->setError(false,  $this->ERROR_NO_ADDRESS_FOUND, 'No fue encontrada la dirección, intenta con un texto diferente');
        }

        return $response;
    }

}