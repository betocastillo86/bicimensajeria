<?php
/**
 * Created by PhpStorm.
 * User: Beto
 * Date: 11/11/2015
 * Time: 3:13 PM
 */


class BikeDeliveryApi
{



    private $ERROR_NO_ADDRESS_FOUND = '1';

    function __construct()
    {
        add_action('wp_ajax_getLocation', array($this, 'getLocationByAddress'));
    }

    function getLocationByAddress()
    {
        require_once('services/ExternalAddressService.php');

        header( "Content-Type: application/json" );

        //Debe venir la dirección a consultar
        if(!isset($_GET['address']) || $_GET['address'] == '')
        {
            $response = json_encode( array( 'success' => false, 'errorMessage' => 'No contiene dirección' ) );
            status_header(400);
            echo $response;
            wp_die();
        }

        //Agrega el prefijo bogota TODO:Remover y que sea dinámico por ciudad
        $address = $_GET['address'].', Bogota';

        //Realiza el llamado al servicio
        $externalAddressService = new ExternalAddressService();
        $response =$externalAddressService->getLocationByAddressText($address);

        //Cambia el status_code dependiendo la respuesta
        status_header($response->success ? 200 : 400);

        echo json_encode($response);

        wp_die();
    }

}