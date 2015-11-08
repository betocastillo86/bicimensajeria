<?php
/**
 * Plugin Name: Bike Delivery
 * Author: Gabriel Castillo
 * Version: 0.0.1
 * Description: Plugin para mensajeria Urbana
 */


if (defined('URE_PLUGIN_URL')) {
    wp_die('It seems that other version of User Role Editor is active. Please deactivate it before use this version');
}


require_once('inc/BikeDelivery.php');
require_once('inc/ConstantBD.php');
require_once('inc/User.php');

/**Views**/

require_once('inc/views/UpdateUserData.php');




$GLOBALS['bike_delivery'] = new BikeDelivery();
