<?php
/**
 * Vista que carga toda la informaci�n del env�o
 * Created by PhpStorm.
 * User: Beto
 * Date: 11/8/2015
 * Time: 10:34 AM
 */
class NewDeliveryView
{
    function __construct()
    {
        add_shortcode('bd_new_delivery', array($this, 'show_view'));
    }


    function show_view($attr)
    {
        ?>
        <div id="divError" class="row">

        </div>
        <form id="formService">
        <div class="row">
            <div class="col-sm-6">
                <div class="panel panel-default">

                    <div class="panel-heading">
                        <h3 class="panel-title">Detalle del env�o</h3>
                    </div>

                    <div class="panel-body">
                        <?php $this->basic_order_form() ?>
                    </div>
                </div>
            </div>
            <div class="col-sm-6">
                <div class="panel panel-default">

                    <div class="panel-heading">
                        <h3 class="panel-title">Ubicaci&oacute;n</h3>
                    </div>

                    <div class="panel-body">
                        <?php $this->show_map() ?>
                        <?php $this->show_result() ?>
                    </div>
                </div>
            </div>

        </div>
        </form>

        <?php
            $this->load_scripts();
            $this->load_templates();
        ?>


        <?php
    }

    /***
     * Muestra la informaci�n b�sica del formulario para solicitar un servicio
     */
    function basic_order_form()
    {
        wp_enqueue_style("bd-timepicker",BD_PLUGIN_URL.'css/bootstrap-timepicker.min.css' );
        wp_enqueue_script("bd-functions",BD_PLUGIN_URL.'js/bikedelivery.js' );
        wp_enqueue_script("bd-maps-functions",BD_PLUGIN_URL.'js/maps-bikeDelivery.js' );
        wp_enqueue_script("bd-js-timepicker",BD_PLUGIN_URL.'js/bootstrap-timepicker.min.js' );
        wp_enqueue_script("google-maps",'http://maps.google.com/maps/api/js?sensor=false' );
        wp_enqueue_script("handlebars",'https://cdnjs.cloudflare.com/ajax/libs/handlebars.js/4.0.4/handlebars.min.js' );
        wp_enqueue_script("jqueryvalidate",'https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.13.1/jquery.validate.min.js' );




        ?>

        <div class="col-xs-12 addressField">
            <div class="form-group">
                <label for="address_from">Direcci&oacute;n origen</label>
                <input id="address_from" name="address_from" type="text" data-var="address_from" class="form-control required" />
                <input id="address_from_lat" type="hidden" />
                <input id="address_from_lon" type="hidden" />
            </div>
        </div>
        <div class="col-xs-12 addressField">
            <div class="form-group">
            <label for="address_to">Direcci&oacute;n destino</label>

            <input id="address_to" name="address_to" type="text" data-var="address_to" class="form-control required"  />
                <input id="address_to_lat" type="hidden" />
                <input id="address_to_lon" type="hidden" />
            </div>
        </div>
        <div class="col-xs-6">
            <div class="form-group">
            <label for="order_day">D&iacute;a</label>
            <input id="order_day" type="text" name="order_day" data-var="order_day" class="form-control required" value="<?php echo current_time('Y/m/d') ?>"   />
            </div>
        </div>
        <div class="col-xs-6">
            <div class="form-group">
            <label for="order_time">Hora</label>
            <input id="order_time" type="text" name="order_time" data-var="order_time" class="form-control required" data-minute-step="10" value="<?php echo current_time('timestamp') ?>"  />
            </div>
        </div>
        <div class="col-xs-12">
            <div class="form-group">
            <label for="order_description">Descripci&oacute;n</label>
            <textarea data-var="order_description" name="order_description" class="form-control required" placeholder="Describe como mejor te parezca tu envio"></textarea>
            </div>
        </div>
        <div class="col-xs-12">
            <div class="form-group">
                <label for="order_value">Valor del objeto a env&iacute;ar</label>
                <input id="order_value" type="text" name="order_value" data-var="order_time" class="form-control required"   />
            </div>
        </div>
        <div class="col-sm-offset-2 col-sm-12">
        <p><input type="button" id="btnNewService" class="btn btn-lg btn-success"  role="button" value="Solicitar Servicio"/></p>
        </div>
        <?php
    }

    function load_templates()
    {
        ?>
        <script id="templateDistance" type="text/x-handlebars-template">
            <h4>Distancia</h4>
            <p>{{distance}} Km</p>
            <h4>Duración</h4>
            <p>{{duration}} Minutos</p>
        </script>

        <script id="templateError" type="text/x-handlebars-template">
            <div class="alert alert-danger" role="alert">
                {{this}}
            </div>
        </script>

        <?php
    }


    /***
     * Javascript con funcionalidades
     */
    function load_scripts()
    {
        ?>
        <script>
            jQuery(document).on('ready', function(){ jQuery(document).bdmap();});
        </script>

        <?php
    }

    /***
     * Muestra el mapa con las funcionalidades
     */
    function show_map()
    {
        ?>


        <style>
            body { background-color:#CCC }
            #map_location {  height: 440px;
                padding: 20px;
                border: 2px solid #CCC;
                margin-bottom: 20px;
                background-color:#FFF }
            #map_location { height: 400px }
            @media all and (max-width: 991px) {
                #map_location  { height: 650px }
            }
        </style>

        <div id="map_location"></div>

        <?php
    }

    function show_result()
    {
        ?>
        <div id="divDistance" class="col-xs-12">

        </div>
        <?php

    }
}