<?php
/**
 * Created by PhpStorm.
 * User: Gabriel Castillo
 * Date: 11/7/2015
 * Time: 2:51 PM
 */
class BikeDelivery
{

    protected $users = null;

    protected $new_delivery_view = null;


    function __construct()
    {
        $this->users = new User();
        $this->new_delivery_view = new NewDeliveryView();
    }

}