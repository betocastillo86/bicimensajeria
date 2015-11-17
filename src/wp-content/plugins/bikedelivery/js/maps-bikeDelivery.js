/**
 * Created by Beto on 11/12/2015.
 */
(function($){


	$.fn.bdmap = function(options){
		initialize(this, options);
	};


    var props = {
        marks : [],
        directionDisplay : undefined,
        directionService : undefined,
        map :undefined,
        service :undefined,
        templateDistance : undefined,
        templateError : undefined,
        form : undefined
    };

	function initialize(obj, options)
	{
		var opts = $.extend({}, $.fn.bdmap.defaults, options);


		//Busca todos los campos de tipo direcciÃ³n y les empieza a agregar los enventos
		var addressFields = $(opts.elAddresses);
		addressFields.each(function(index, element){

            var $el = $(this);

            //Busca el campo dirección
            var txtAddress = $el.first('input[type="text"]');
            addEventsAddress(txtAddress, index);



		});

        props.directionService = new google.maps.DirectionsService();
        props.directionDisplay = new google.maps.DirectionsRenderer();
        props.templateDistance = Handlebars.compile($("#templateDistance").html());
        props.templateError = Handlebars.compile($("#templateError").html());
        props.form = $('#formService');


        $('#order_day').datepicker({
            minDate :0
        });

        $('#order_time').timepicker();


        $('#btnNewService').on('click', newService);
        loadValidators();


        loadCurrentPosition(opts);

	}

    /***
     * Agrega lso envetos necesarios para las direcciones
     * @param obj
     * @param index
     */
    function addEventsAddress(obj, index)
    {
        obj.on('change', function(target){ findAddressByText(target, index); } );
    }

    function findAddressByText(obj, index)
    {
        var addressText = $(obj.target).val();

        if(addressText == '')
            return alert('No hay seleccionado ningun texto');

        $.ajax({
            method:'GET',
            url : '/wp-admin/admin-ajax.php?action=getLocation&address'+addressText,
            data : { address : addressText }
        })
        .success(function(resp){
            markMap(resp.latitude, resp.longitude, obj, index);
            //Realiza nuevamente validaciones para tener en cuenta las direcciones
            props.form.validate().form();

            //$('#'+obj.target.id + '_lat').val(resp.latitude);
            //$('#'+obj.target.id + '_lon').val(resp.longitude);
        })
        .error(function(resp){
            showError(resp.responseJSON.errorMessage);
            //Realiza nuevamente validaciones para tener en cuenta las direcciones
            props.form.validate().form();
            //$('#'+obj.target.id + '_lat').val('');
            //$('#'+obj.target.id + '_lon').val('');
        });
    }

    /****
     * Marca la posición en el mapa enviada, adicionalmente valida que parte del trayecto es para pintar o no el recorrido
     * @param lat latitud que se desea ubicar
     * @param lon longitud de la dirección
     * @param obj caja de texto que dispara el evento
     * @param index numero de la caja de texto
     */
    function markMap(lat, lon, obj, index) {

        var googlePosition = new google.maps.LatLng(lat, lon);


        //Si la posicion en el mapa ya existe la elimina
        /*if(props.marks.length >= index+1)
        {
            props.marks[index].setMap(null);
        }*/

        //Si la marca no existe la agrega
        if (props.marks.length <= index) {
            var marker = new google.maps.Marker({
                position: googlePosition,
                map: props.map
            });
            props.marks.push(marker);
        }
        else {
            //Sino acutaliza el valor de la lista
            var marker = props.marks[index];
            //marker.setMap(null);
            marker.setPosition(googlePosition);
        }

        //Recorre todos los puntos y genera la trayectoria
        if(props.marks.length > 1)
        {
            var directionsRequest = {
                origin : props.marks[0].getPosition(),
                destination : props.marks[1].getPosition(),
                travelMode: google.maps.TravelMode.DRIVING
                //travelMode: google.maps.TravelMode.BICYCLING
            };
            props.directionService.route(directionsRequest, function(response, status){
                if (status == google.maps.DirectionsStatus.OK) {
                    props.directionDisplay.setMap(props.map);
                    props.directionDisplay.setDirections(response);
                }
            });


            //Si tiene mas de una marca realiza el llamado para calcular las rutas en el servidor
            calculateServerRoute();
        }


        props.map.setCenter(googlePosition);
    }

    /****
     * Carga el mapa y lo muestra centrado en una posición especifica
     * @param latitude
     * @param longitude
     */
    function loadMapOnPosition(latitude, longitude)
    {
        var latlng = new google.maps.LatLng(latitude, longitude);
        var myOptions = {
            zoom: 15,
            center: latlng,
            mapTypeId: google.maps.MapTypeId.ROADMAP
        };
        if(!props.map)
            props.map = new google.maps.Map($("#map_location")[0], myOptions);
        else
            props.map.setCenter(new google.maps.LatLng(latitude, longitude));

    }

    function calculateServerRoute()
    {
        var obj = {
            origin :{
                lat : props.marks.length > 0 ? props.marks[0].getPosition().lat() : 0,
                lon : props.marks.length > 0 ? props.marks[0].getPosition().lng() : 0
            },
            destination :{
                lat : props.marks.length > 1 ? props.marks[1].getPosition().lat() : 0,
                lon : props.marks.length > 1 ? props.marks[1].getPosition().lng() : 0
            }
        };

        $.ajax({
            method:'POST',
            url : '/wp-admin/admin-ajax.php?action=calcRoute',
            data : { data : JSON.stringify(obj) }
        })
        .success(function(resp){
           $("#divDistance").html( props.templateDistance(resp));
        })
        .error(function(resp){
            showError(resp.responseJSON.errorMessage);
        });
    }


    function setServiceObject()
    {
        var obj = {
            origin :{
                addressText : $("#address_from").val(),
                lat : props.marks.length > 0 ? props.marks[0].getPosition().lat() : 0,
                lon : props.marks.length > 0 ? props.marks[0].getPosition().lng() : 0
            },
            destination :{
                addressText : $("#address_to").val(),
                lat : props.marks.length > 1 ? props.marks[1].getPosition().lat() : 0,
                lon : props.marks.length > 1 ? props.marks[1].getPosition().lng() : 0
            },
            day : $("#order_day").val(),
            time : $("#order_time").val(),
            description : $("#order_description").val(),
            value : $('#order_value').val()
        }

        props.service = obj;
    }

    function calculatePrincing() {


    }

    function newService()
    {
        //$("#formService").submit();
        props.form.validate().form();
    }

    function showError(message)
    {
        var el = $("#divError");
        el.show();
        el.html(props.templateError(message));
        ///setTimeout(2000, function(){el.fadeOut(3000)});
        el.fadeOut(7000);
    }

    var locationAutoloaded = false;
    function loadCurrentPosition(options) {
        var lat = options.defaultLatitude;
        var lon = options.defaultLongitude;

        if (navigator.geolocation) {
            var that = this;
            navigator.geolocation.getCurrentPosition(function (position) {
                lat = position.coords.latitude;
                lon = position.coords.longitude;
                locationAutoloaded = true;
                loadMapOnPosition(lat, lon);
            });
            /**Si el usuario no selecciona la autoubicación se toma una por defecto**/
            setTimeout(function(){
                if(!locationAutoloaded)
                    loadMapOnPosition(lat, lon);
            }, 5000);
        }
        else {
            //Si no tiene geolocalización lo ubica en la posición por defecto
            loadMapOnPosition(lat, lon);
        }
    }

    function loadValidators()
    {
       //Realiza las validaciones para que las direcciones esten bien diligenciadas
        jQuery.validator.addMethod('address', function(value, element){
           setServiceObject();
           if(element.id == 'address_from')
               return props.service.origin.lat != 0 && props.service.origin.lon != 0;
           else
               return props.service.destination.lat != 0 && props.service.destination.lon != 0;
        });

        jQuery.validator.addMethod('time', function(value, element){

        });

        jQuery.validator.setDefaults({
            highlight: function (element, errorClass, validClass) {
                if (element.type === "radio") {
                    this.findByName(element.name).addClass(errorClass).removeClass(validClass);
                } else {
                    $(element).closest('.form-group').removeClass('has-success has-feedback').addClass('has-error has-feedback');
                    $(element).closest('.form-group').find('i.fa').remove();
                    $(element).closest('.form-group').append('<i class="fa fa-exclamation fa-lg form-control-feedback"></i>');
                }
            },
            unhighlight: function (element, errorClass, validClass) {
                if (element.type === "radio") {
                    this.findByName(element.name).removeClass(errorClass).addClass(validClass);
                } else {
                    $(element).closest('.form-group').removeClass('has-error has-feedback').addClass('has-success has-feedback');
                    $(element).closest('.form-group').find('i.fa').remove();
                    $(element).closest('.form-group').append('<i class="fa fa-check fa-lg form-control-feedback"></i>');
                }
            }
        });

         props.form.validate({
            rules :{
                'address_from' :{
                    required:true,
                    address : true,
                    minlength : 5,
                    maxlength : 50
                },
                'address_to' :{
                    required:true,
                    minlength : 5,
                    maxlength : 50
                },
                'order_day' :{
                    required:true
                },
                'order_time' :{
                    required:true,
                    time :true
                },
                'order_value' :{
                    required:true
                }
            },
            messages :{
                address_from : 'Ingresa una direccion de recogida valida',
                address_to:'Ingresa una direccion de envio valida',
                order_day : 'Ingresa la fecha de recogida',
                order_time : 'Ingresa la hora de recogida',
                order_value : 'Ingresa el valor del envío'
            }
        });
    }


	
    $.fn.bdmap.defaults = {
    	defaultLatitude : 4.57262365310281,
        defaultLongitude : -74.0970325469971,
    	elMap : '#map_location',
    	elAddresses : '.addressField',
        map : undefined
    };

})(jQuery);