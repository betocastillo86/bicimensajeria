<?php
/**
 * Realiza consultas a servicios externos por direcciones
 * Created by PhpStorm.
 * User: Beto
 * Date: 11/12/2015
 * Time: 12:11 PM
 */
require_once(BD_PLUGIN_DIR.'inc/models/LocationAddressModel.php');
require_once(BD_PLUGIN_DIR.'inc/models/DirectionMapModel.php');
require_once (BD_PLUGIN_DIR.'inc/lib/GoogleMapsGeocoder.php');
require_once (BD_PLUGIN_DIR.'inc/lib/GoogleMapsDirections.php');
require_once (BD_PLUGIN_DIR.'inc/entities/ErrorCodes.php');
class ExternalAddressService
{

    private $API_KEY = 'AIzaSyDZ23fBsKzTsXNfoXaBdHawqbsTmoyDuTY';

    function __construct()
    {

    }

    /****
     * Consulta en un proveedor externo (Google) una direcci�n por texto y devuelve las coordenadas o el mensaje de error
     * @param $addressText direccion en text
     * @return LocationAddressModel Coordenadas de la direcci�n
     */
    function getLocationByAddressText($addressText)
    {
        //Instancia el objeto que hace los lalamados a Google
        $geocoder = new GoogleMapsGeocoder($addressText);
        $geocoder->setApiKey($this->API_KEY);

        //Realiza el llamado para validar la direcci�n
        $google_results = $geocoder->geocode() ;

        $response = new LocationAddressModel();

        //Si tiene alguna respuesta
        if(end($google_results['results']) != null)
        {

            //carga los datos en el modelo
            $data_address = end( $google_results['results'])['geometry'];

            if($data_address['location_type'] != 'APPROXIMATE')
            {
                $response->success = true;
                $response->latitude = $data_address['location']['lat'];
                $response->longitude = $data_address['location']['lng'];
            }
            else
            {
                $response->setError(false,  'No fue encontrada la direccion, intenta con un texto diferente', ErrorCodes::$ERROR_NO_ADDRESS_FOUND);
            }
        }
        else
        {
            $response->setError(false,  'No fue encontrada la direccion, intenta con un texto diferente', ErrorCodes::$ERROR_NO_ADDRESS_FOUND);
        }

        return $response;
    }


    function calculateRoute($originLat, $originLon, $destinationLat, $destinationLon)
    {
        //Instancia el objeto que hace los lalamados a Google
        $directions = new GoogleMapsDirections();
        $directions->setApiKey($this->API_KEY);

        //Realiza el llamado para validar la direcci�n
        $google_results = $directions->route($originLat, $originLon, $destinationLat, $destinationLon) ;


        $model = new  DirectionMapModel();


        if(count($google_results['routes']) > 0 && count($google_results['routes'][0]['legs']) > 0)
        {
            $model->success = true;
            $model->distance = round($google_results['routes'][0]['legs'][0]['distance']['value'] / 1000, 2) ;
            $model->duration = round($google_results['routes'][0]['legs'][0]['duration']['value'] / 60, 0);
        }
        else
        {
            $model->setError(false,  'No fue encontrada una ruta para ese recorrido', ErrorCodes::$ERROR_NO_ROUTE_FOUND);
        }

        return $model;
    }




}