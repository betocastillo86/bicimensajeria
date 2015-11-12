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
                    </div>
                </div>
            </div>

        </div>

        <?php $this->load_scripts() ?>

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


        ?>

        <div class="col-xs-12 addressField">
            <label for="addres_from">Direcci&oacute;n origen</label>
            <input id="addres_from" type="text" data-var="addres_from" class="form-control">
        </div>
        <div class="col-xs-12 addressField">
            <label for="addres_to">Direcci&oacute;n destino</label>
            <input id="addres_to" type="text" data-var="addres_to" class="form-control">
        </div>
        <div class="col-xs-6">
            <label for="order_day">D&iacute;a</label>
            <input id="order_day" type="text" data-var="order_day" class="form-control" value="<?php echo current_time('Y/m/d') ?>">
        </div>
        <div class="col-xs-6">
            <label for="order_time">Hora</label>
            <input id="order_time" type="text" data-var="order_time" class="form-control" value="<?php echo current_time('timestamp') ?>">
        </div>
        <div class="col-xs-12">
            <label for="order_description">Descripci&oacute;n</label>
            <textarea data-var="order_description" class="form-control" placeholder="Describe como mejor te parezca tu envio"></textarea>
        </div>
        <div class="col-xs-12">
            <label for="order_value">Valor del objeto a env&iacute;ar</label>
            <input id="order_value" type="text" data-var="order_time" class="form-control" >
        </div>


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
}